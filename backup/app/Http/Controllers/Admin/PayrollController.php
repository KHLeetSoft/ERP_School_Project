<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payroll;
use App\Models\Staff;
use App\Models\School;
use App\Models\User;
use App\Exports\PayrollsExport;
use App\Imports\PayrollsImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

class PayrollController extends Controller
{
    protected $adminUser;
    protected $schoolId;

    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware(function ($request, $next) {
            $this->adminUser = auth()->guard('admin')->user();
            $this->schoolId = $this->adminUser ? $this->adminUser->school_id : null;
            return $next($request);
        });
        $this->middleware('checkrole:admin');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorizeSchool();

        $query = Payroll::with(['staff', 'createdBy', 'updatedBy'])
            ->where('school_id', auth()->user()->school_id ?? 1);

        // Apply filters
        if ($request->filled('search.value')) {
            $search = $request->input('search.value');
            $query->where(function($q) use ($search) {
                $q->whereHas('staff', function($staffQuery) use ($search) {
                    $staffQuery->where('first_name', 'like', "%{$search}%")
                              ->orWhere('last_name', 'like', "%{$search}%")
                              ->orWhere('employee_id', 'like', "%{$search}%");
                });
            });
        }

        if ($request->filled('month')) {
            $query->where('payroll_month', $request->month);
        }

        if ($request->filled('year')) {
            $query->where('payroll_year', $request->year);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('staff_id')) {
            $query->where('staff_id', $request->staff_id);
        }

        // If AJAX request, return DataTables JSON
        if ($request->ajax()) {
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('staff_name', function($row) {
                    return $row->staff ? $row->staff->first_name . ' ' . $row->staff->last_name : 'N/A';
                })
                ->addColumn('employee_id', function($row) {
                    return $row->staff ? $row->staff->employee_id : 'N/A';
                })
                ->addColumn('payroll_period', function($row) {
                    return $row->payroll_period;
                })
                ->addColumn('gross_salary_formatted', function($row) {
                    return '₹' . number_format($row->gross_salary, 2);
                })
                ->addColumn('net_salary_formatted', function($row) {
                    return '₹' . number_format($row->net_salary, 2);
                })
                ->addColumn('status_badge', function($row) {
                    return $row->status_badge;
                })
                ->addColumn('actions', function($row) {
                    return view('admin.hr.payroll.partials.actions', compact('row'))->render();
                })
                ->rawColumns(['status_badge', 'actions'])
                ->make(true);
        }

        // For non-AJAX requests, return view with paginated data
        $payrolls = $query->orderBy('created_at', 'desc')->paginate(25);

        // Get filter options
        $staff = Staff::where('school_id', auth()->user()->school_id ?? 1)
            ->orderBy('first_name')->get();
        
        $months = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
        ];

        $years = range(date('Y') - 5, date('Y') + 1);
        $statuses = ['pending', 'approved', 'paid', 'rejected'];

        return view('admin.hr.payroll.index', compact('payrolls', 'staff', 'months', 'years', 'statuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorizeSchool();

        $staff = Staff::where('school_id', auth()->user()->school_id ?? 1)
            ->where('status', 'active')
            ->orderBy('first_name')->get();

        $months = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
        ];

        $years = range(date('Y') - 2, date('Y') + 1);
        $paymentMethods = ['bank_transfer', 'cash', 'cheque', 'online'];

        return view('admin.hr.payroll.create', compact('staff', 'months', 'years', 'paymentMethods'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorizeSchool();

        $request->validate([
            'staff_id' => 'required|exists:staff,id',
            'payroll_month' => 'required|integer|between:1,12',
            'payroll_year' => 'required|integer|min:2020|max:2030',
            'basic_salary' => 'required|numeric|min:0',
            'house_rent_allowance' => 'nullable|numeric|min:0',
            'dearness_allowance' => 'nullable|numeric|min:0',
            'conveyance_allowance' => 'nullable|numeric|min:0',
            'medical_allowance' => 'nullable|numeric|min:0',
            'special_allowance' => 'nullable|numeric|min:0',
            'overtime_pay' => 'nullable|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
            'incentives' => 'nullable|numeric|min:0',
            'arrears' => 'nullable|numeric|min:0',
            'provident_fund' => 'nullable|numeric|min:0',
            'tax_deduction' => 'nullable|numeric|min:0',
            'insurance_deduction' => 'nullable|numeric|min:0',
            'loan_deduction' => 'nullable|numeric|min:0',
            'other_deductions' => 'nullable|numeric|min:0',
            'payment_method' => 'required|in:bank_transfer,cash,cheque,online',
            'bank_name' => 'nullable|string|max:100',
            'account_number' => 'nullable|string|max:50',
            'ifsc_code' => 'nullable|string|max:20',
            'remarks' => 'nullable|string|max:500',
        ]);

        // Check if payroll already exists for this staff and period
        $existingPayroll = Payroll::where('staff_id', $request->staff_id)
            ->where('payroll_month', $request->payroll_month)
            ->where('payroll_year', $request->payroll_year)
            ->where('school_id', auth()->user()->school_id ?? 1)
            ->first();

        if ($existingPayroll) {
            return back()->withErrors(['payroll_period' => 'Payroll already exists for this staff and period.'])->withInput();
        }

        // Calculate gross and net salary
        $grossSalary = $request->basic_salary + 
                      ($request->house_rent_allowance ?? 0) + 
                      ($request->dearness_allowance ?? 0) + 
                      ($request->conveyance_allowance ?? 0) + 
                      ($request->medical_allowance ?? 0) + 
                      ($request->special_allowance ?? 0) + 
                      ($request->overtime_pay ?? 0) + 
                      ($request->bonus ?? 0) + 
                      ($request->incentives ?? 0) + 
                      ($request->arrears ?? 0);

        $totalDeductions = ($request->provident_fund ?? 0) + 
                          ($request->tax_deduction ?? 0) + 
                          ($request->insurance_deduction ?? 0) + 
                          ($request->loan_deduction ?? 0) + 
                          ($request->other_deductions ?? 0);

        $netSalary = $grossSalary - $totalDeductions;

        $payroll = Payroll::create([
            'school_id' => auth()->user()->school_id ?? 1,
            'staff_id' => $request->staff_id,
            'payroll_month' => $request->payroll_month,
            'payroll_year' => $request->payroll_year,
            'basic_salary' => $request->basic_salary,
            'house_rent_allowance' => $request->house_rent_allowance ?? 0,
            'dearness_allowance' => $request->dearness_allowance ?? 0,
            'conveyance_allowance' => $request->conveyance_allowance ?? 0,
            'medical_allowance' => $request->medical_allowance ?? 0,
            'special_allowance' => $request->special_allowance ?? 0,
            'overtime_pay' => $request->overtime_pay ?? 0,
            'bonus' => $request->bonus ?? 0,
            'incentives' => $request->incentives ?? 0,
            'arrears' => $request->arrears ?? 0,
            'gross_salary' => $grossSalary,
            'provident_fund' => $request->provident_fund ?? 0,
            'tax_deduction' => $request->tax_deduction ?? 0,
            'insurance_deduction' => $request->insurance_deduction ?? 0,
            'loan_deduction' => $request->loan_deduction ?? 0,
            'other_deductions' => $request->other_deductions ?? 0,
            'net_salary' => $netSalary,
            'payment_method' => $request->payment_method,
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
            'ifsc_code' => $request->ifsc_code,
            'status' => 'pending',
            'remarks' => $request->remarks,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('admin.hr.payroll.index')
            ->with('success', 'Payroll created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Payroll $payroll)
    {
        $this->authorizeSchool();
        $this->authorize('view', $payroll);

        $payroll->load(['staff', 'createdBy', 'updatedBy']);

        return view('admin.hr.payroll.show', compact('payroll'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payroll $payroll)
    {
        $this->authorizeSchool();
        $this->authorize('update', $payroll);

        $staff = Staff::where('school_id', auth()->user()->school_id ?? 1)
            ->where('status', 'active')
            ->orderBy('first_name')->get();

        $months = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
        ];

        $years = range(date('Y') - 2, date('Y') + 1);
        $paymentMethods = ['bank_transfer', 'cash', 'cheque', 'online'];

        return view('admin.hr.payroll.edit', compact('payroll', 'staff', 'months', 'years', 'paymentMethods'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payroll $payroll)
    {
        $this->authorizeSchool();
        $this->authorize('update', $payroll);

        $request->validate([
            'staff_id' => 'required|exists:staff,id',
            'payroll_month' => 'required|integer|between:1,12',
            'payroll_year' => 'required|integer|min:2020|max:2030',
            'basic_salary' => 'required|numeric|min:0',
            'house_rent_allowance' => 'nullable|numeric|min:0',
            'dearness_allowance' => 'nullable|numeric|min:0',
            'conveyance_allowance' => 'nullable|numeric|min:0',
            'medical_allowance' => 'nullable|numeric|min:0',
            'special_allowance' => 'nullable|numeric|min:0',
            'overtime_pay' => 'nullable|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
            'incentives' => 'nullable|numeric|min:0',
            'arrears' => 'nullable|numeric|min:0',
            'provident_fund' => 'nullable|numeric|min:0',
            'tax_deduction' => 'nullable|numeric|min:0',
            'insurance_deduction' => 'nullable|numeric|min:0',
            'loan_deduction' => 'nullable|numeric|min:0',
            'other_deductions' => 'nullable|numeric|min:0',
            'payment_method' => 'required|in:bank_transfer,cash,cheque,online',
            'bank_name' => 'nullable|string|max:100',
            'account_number' => 'nullable|string|max:50',
            'ifsc_code' => 'nullable|string|max:20',
            'remarks' => 'nullable|string|max:500',
        ]);

        // Check if payroll already exists for this staff and period (excluding current)
        $existingPayroll = Payroll::where('staff_id', $request->staff_id)
            ->where('payroll_month', $request->payroll_month)
            ->where('payroll_year', $request->payroll_year)
            ->where('school_id', auth()->user()->school_id ?? 1)
            ->where('id', '!=', $payroll->id)
            ->first();

        if ($existingPayroll) {
            return back()->withErrors(['payroll_period' => 'Payroll already exists for this staff and period.'])->withInput();
        }

        // Calculate gross and net salary
        $grossSalary = $request->basic_salary + 
                      ($request->house_rent_allowance ?? 0) + 
                      ($request->dearness_allowance ?? 0) + 
                      ($request->conveyance_allowance ?? 0) + 
                      ($request->medical_allowance ?? 0) + 
                      ($request->special_allowance ?? 0) + 
                      ($request->overtime_pay ?? 0) + 
                      ($request->bonus ?? 0) + 
                      ($request->incentives ?? 0) + 
                      ($request->arrears ?? 0);

        $totalDeductions = ($request->provident_fund ?? 0) + 
                          ($request->tax_deduction ?? 0) + 
                          ($request->insurance_deduction ?? 0) + 
                          ($request->loan_deduction ?? 0) + 
                          ($request->other_deductions ?? 0);

        $netSalary = $grossSalary - $totalDeductions;

        $payroll->update([
            'staff_id' => $request->staff_id,
            'payroll_month' => $request->payroll_month,
            'payroll_year' => $request->payroll_year,
            'basic_salary' => $request->basic_salary,
            'house_rent_allowance' => $request->house_rent_allowance ?? 0,
            'dearness_allowance' => $request->dearness_allowance ?? 0,
            'conveyance_allowance' => $request->conveyance_allowance ?? 0,
            'medical_allowance' => $request->medical_allowance ?? 0,
            'special_allowance' => $request->special_allowance ?? 0,
            'overtime_pay' => $request->overtime_pay ?? 0,
            'bonus' => $request->bonus ?? 0,
            'incentives' => $request->incentives ?? 0,
            'arrears' => $request->arrears ?? 0,
            'gross_salary' => $grossSalary,
            'provident_fund' => $request->provident_fund ?? 0,
            'tax_deduction' => $request->tax_deduction ?? 0,
            'insurance_deduction' => $request->insurance_deduction ?? 0,
            'loan_deduction' => $request->loan_deduction ?? 0,
            'other_deductions' => $request->other_deductions ?? 0,
            'net_salary' => $netSalary,
            'payment_method' => $request->payment_method,
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
            'ifsc_code' => $request->ifsc_code,
            'remarks' => $request->remarks,
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('admin.hr.payroll.index')
            ->with('success', 'Payroll updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payroll $payroll)
    {
        $this->authorizeSchool();
        $this->authorize('delete', $payroll);

        if ($payroll->status === 'paid') {
            return back()->withErrors(['status' => 'Cannot delete a paid payroll.']);
        }

        $payroll->delete();

        return redirect()->route('admin.hr.payroll.index')
            ->with('success', 'Payroll deleted successfully!');
    }

    /**
     * Show the payroll dashboard.
     */
    public function dashboard()
    {
        $this->authorizeSchool();

        $schoolId = auth()->user()->school_id ?? 1;
        $currentMonth = now()->month;
        $currentYear = now()->year;

        // Basic statistics
        $totalPayrolls = Payroll::where('school_id', $schoolId)->count();
        $pendingPayrolls = Payroll::where('school_id', $schoolId)->where('status', 'pending')->count();
        $approvedPayrolls = Payroll::where('school_id', $schoolId)->where('status', 'approved')->count();
        $paidPayrolls = Payroll::where('school_id', $schoolId)->where('status', 'paid')->count();
        $rejectedPayrolls = Payroll::where('school_id', $schoolId)->where('status', 'rejected')->count();

        // Financial statistics
        $totalGrossSalary = Payroll::where('school_id', $schoolId)->sum('gross_salary');
        $totalNetSalary = Payroll::where('school_id', $schoolId)->sum('net_salary');
        $totalAllowances = Payroll::where('school_id', $schoolId)->sum(DB::raw('house_rent_allowance + dearness_allowance + conveyance_allowance + medical_allowance + special_allowance + overtime_pay + bonus + incentives + arrears'));
        $totalDeductions = Payroll::where('school_id', $schoolId)->sum(DB::raw('provident_fund + tax_deduction + insurance_deduction + loan_deduction + other_deductions'));

        // Monthly trends for current year
        $monthlyData = Payroll::where('school_id', $schoolId)
            ->where('payroll_year', $currentYear)
            ->selectRaw('payroll_month, COUNT(*) as count, SUM(gross_salary) as total_gross, SUM(net_salary) as total_net')
            ->groupBy('payroll_month')
            ->orderBy('payroll_month')
            ->get();

        $monthlyLabels = [];
        $monthlyCounts = [];
        $monthlyGross = [];
        $monthlyNet = [];

        for ($i = 1; $i <= 12; $i++) {
            $monthlyLabels[] = date('M', mktime(0, 0, 0, $i, 1));
            $monthData = $monthlyData->where('payroll_month', $i)->first();
            $monthlyCounts[] = $monthData ? $monthData->count : 0;
            $monthlyGross[] = $monthData ? round($monthData->total_gross) : 0;
            $monthlyNet[] = $monthData ? round($monthData->total_net) : 0;
        }

        // Department-wise statistics
        $departmentStats = Payroll::join('staff', 'payrolls.staff_id', '=', 'staff.id')
            ->where('payrolls.school_id', $schoolId)
            ->where('payrolls.payroll_year', $currentYear)
            ->selectRaw('staff.department, COUNT(*) as count, AVG(payrolls.net_salary) as avg_salary, SUM(payrolls.net_salary) as total_salary')
            ->groupBy('staff.department')
            ->orderBy('total_salary', 'desc')
            ->get();

        // Status distribution
        $statusDistribution = Payroll::where('school_id', $schoolId)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        // Recent payrolls
        $recentPayrolls = Payroll::with('staff')
            ->where('school_id', $schoolId)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Payment method distribution
        $paymentMethodStats = Payroll::where('school_id', $schoolId)
            ->selectRaw('payment_method, COUNT(*) as count')
            ->groupBy('payment_method')
            ->get();

        return view('admin.hr.payroll.dashboard', compact(
            'totalPayrolls', 'pendingPayrolls', 'approvedPayrolls', 'paidPayrolls', 'rejectedPayrolls',
            'totalGrossSalary', 'totalNetSalary', 'totalAllowances', 'totalDeductions',
            'monthlyLabels', 'monthlyCounts', 'monthlyGross', 'monthlyNet',
            'departmentStats', 'statusDistribution', 'recentPayrolls', 'paymentMethodStats'
        ));
    }

    /**
     * Export payrolls to Excel/CSV.
     */
    public function export(Request $request)
    {
        $this->authorizeSchool();

        $format = $request->get('format', 'xlsx');
        $filename = 'payrolls_' . date('Y-m-d_H-i-s') . '.' . $format;

        return Excel::download(new PayrollsExport(
            auth()->user()->school_id ?? 1,
            $request->all()
        ), $filename);
    }

    /**
     * Import payrolls from Excel/CSV.
     */
    public function import(Request $request)
    {
        $this->authorizeSchool();

        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:2048'
        ]);

        try {
            Excel::import(new PayrollsImport(auth()->user()->school_id ?? 1), $request->file('file'));
            return back()->with('success', 'Payrolls imported successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['file' => 'Error importing file: ' . $e->getMessage()]);
        }
    }

    /**
     * Toggle payroll status.
     */
    public function toggleStatus(Request $request, Payroll $payroll)
    {
        $this->authorizeSchool();
        $this->authorize('update', $payroll);

        $request->validate([
            'status' => 'required|in:pending,approved,paid,rejected'
        ]);

        $newStatus = $request->status;

        // Validate status transition
        if ($payroll->status === 'paid' && $newStatus !== 'paid') {
            return response()->json(['error' => 'Cannot change status of a paid payroll.'], 400);
        }

        if ($newStatus === 'paid' && $payroll->status !== 'approved') {
            return response()->json(['error' => 'Only approved payrolls can be marked as paid.'], 400);
        }

        $payroll->update([
            'status' => $newStatus,
            'payment_date' => $newStatus === 'paid' ? now() : null,
            'updated_by' => auth()->id()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payroll status updated successfully!',
            'new_status' => $newStatus
        ]);
    }

    /**
     * Get payroll data for specific staff.
     */
    public function getByStaff(Request $request, Staff $staff)
    {
        $this->authorizeSchool();

        $payrolls = Payroll::where('school_id', auth()->user()->school_id ?? 1)
            ->where('staff_id', $staff->id)
            ->orderBy('payroll_year', 'desc')
            ->orderBy('payroll_month', 'desc')
            ->paginate(12);

        return view('admin.hr.payroll.staff-payrolls', compact('payrolls', 'staff'));
    }

    /**
     * Get payroll data for specific month/year.
     */
    public function getByPeriod(Request $request)
    {
        $this->authorizeSchool();

        $request->validate([
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2020|max:2030'
        ]);

        $payrolls = Payroll::with('staff')
            ->where('school_id', auth()->user()->school_id ?? 1)
            ->where('payroll_month', $request->month)
            ->where('payroll_year', $request->year)
            ->orderBy('created_at', 'desc')
            ->paginate(25);

        $monthName = date('F', mktime(0, 0, 0, $request->month, 1));
        $period = $monthName . ' ' . $request->year;

        return view('admin.hr.payroll.period-payrolls', compact('payrolls', 'period', 'monthName'));
    }

    /**
     * Authorize school access.
     */
    private function authorizeSchool()
    {
        // Add school authorization logic here if needed
        return true;
    }
}
