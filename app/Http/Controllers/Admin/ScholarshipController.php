<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Scholarship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ScholarshipsExport;
use App\Imports\ScholarshipsImport;

class ScholarshipController extends Controller
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
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Scholarship::where('school_id', auth()->user()->school_id ?? 1)
                ->orderByDesc('created_at');

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('actions', function ($r) { return view('admin.finance.scholarships.partials.actions', compact('r'))->render(); })
                ->editColumn('amount', fn($r)=> number_format($r->amount,2))
                ->editColumn('status', function ($r) {
                    $color = match($r->status){
                        'approved' => 'success', 'paid' => 'primary', 'rejected' => 'danger', default => 'secondary'
                    };
                    return "<span class='badge bg-{$color}'>".ucfirst($r->status)."</span>";
                })
                ->rawColumns(['actions','status'])
                ->make(true);
        }
        return view('admin.finance.scholarships.index');
    }

    public function create() { return view('admin.finance.scholarships.create'); }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'code' => 'required|string|max:50|unique:scholarships,code',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|in:pending,approved,rejected,paid',
            'awarded_date' => 'nullable|date',
        ]);

        Scholarship::create([
            'school_id' => auth()->user()->school_id ?? 1,
            'student_id' => $request->student_id,
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'amount' => $request->amount,
            'status' => $request->status,
            'awarded_date' => $request->awarded_date,
            'notes' => $request->notes,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('admin.finance.scholarships.index')->with('success','Scholarship created.');
    }

    public function show(Scholarship $scholarship)
    {
        $this->authorizeSchool($scholarship);
        return view('admin.finance.scholarships.show', compact('scholarship'));
    }

    public function edit(Scholarship $scholarship)
    {
        $this->authorizeSchool($scholarship);
        return view('admin.finance.scholarships.edit', compact('scholarship'));
    }

    public function update(Request $request, Scholarship $scholarship)
    {
        $this->authorizeSchool($scholarship);
        $request->validate([
            'name' => 'required|string|max:150',
            'code' => 'required|string|max:50|unique:scholarships,code,'.$scholarship->id,
            'amount' => 'required|numeric|min:0',
            'status' => 'required|in:pending,approved,rejected,paid',
            'awarded_date' => 'nullable|date',
        ]);
        $scholarship->update([
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'amount' => $request->amount,
            'status' => $request->status,
            'awarded_date' => $request->awarded_date,
            'notes' => $request->notes,
            'updated_by' => Auth::id(),
        ]);
        return redirect()->route('admin.finance.scholarships.index')->with('success','Scholarship updated.');
    }

    public function destroy(Scholarship $scholarship)
    {
        $this->authorizeSchool($scholarship);
        $scholarship->delete();
        return redirect()->route('admin.finance.scholarships.index')->with('success','Scholarship deleted.');
    }

    public function dashboard()
    {
        $schoolId = auth()->user()->school_id ?? 1;
        
        // Monthly data for last 12 months
        $labels = collect(range(11, 0))->map(fn($i) => Carbon::today()->subMonths($i)->format('M Y'))->reverse()->values();
        
        // Monthly amounts by status
        $monthly = collect(range(11, 0))->map(fn($i) => [
            'approved' => Scholarship::where('school_id', $schoolId)
                ->where('status', 'approved')
                ->whereMonth('awarded_date', Carbon::today()->subMonths($i)->month)
                ->whereYear('awarded_date', Carbon::today()->subMonths($i)->year)
                ->sum('amount'),
            'paid' => Scholarship::where('school_id', $schoolId)
                ->where('status', 'paid')
                ->whereMonth('awarded_date', Carbon::today()->subMonths($i)->month)
                ->whereYear('awarded_date', Carbon::today()->subMonths($i)->year)
                ->sum('amount'),
            'pending' => Scholarship::where('school_id', $schoolId)
                ->where('status', 'pending')
                ->whereMonth('created_at', Carbon::today()->subMonths($i)->month)
                ->whereYear('created_at', Carbon::today()->subMonths($i)->year)
                ->sum('amount'),
            'rejected' => Scholarship::where('school_id', $schoolId)
                ->where('status', 'rejected')
                ->whereMonth('created_at', Carbon::today()->subMonths($i)->month)
                ->whereYear('created_at', Carbon::today()->subMonths($i)->year)
                ->sum('amount'),
        ])->reverse()->values();
        
        $approved = $monthly->pluck('approved')->toArray();
        $paid = $monthly->pluck('paid')->toArray();
        $pending = $monthly->pluck('pending')->toArray();
        $rejected = $monthly->pluck('rejected')->toArray();
        
        // Status counts
        $statusCounts = [
            'pending' => Scholarship::where('school_id', $schoolId)->where('status', 'pending')->count(),
            'approved' => Scholarship::where('school_id', $schoolId)->where('status', 'approved')->count(),
            'rejected' => Scholarship::where('school_id', $schoolId)->where('status', 'rejected')->count(),
            'paid' => Scholarship::where('school_id', $schoolId)->where('status', 'paid')->count(),
        ];
        
        // Amount ranges for distribution
        $amountRanges = [
            '0-25k' => Scholarship::where('school_id', $schoolId)->whereBetween('amount', [0, 25000])->count(),
            '25k-50k' => Scholarship::where('school_id', $schoolId)->whereBetween('amount', [25000, 50000])->count(),
            '50k-75k' => Scholarship::where('school_id', $schoolId)->whereBetween('amount', [50000, 75000])->count(),
            '75k-100k' => Scholarship::where('school_id', $schoolId)->whereBetween('amount', [75000, 100000])->count(),
            '100k+' => Scholarship::where('school_id', $schoolId)->where('amount', '>', 100000)->count(),
        ];
        
        // Top scholarship categories by amount
        $topCategories = Scholarship::where('school_id', $schoolId)
            ->selectRaw('SUBSTRING_INDEX(name, " ", 1) as category, SUM(amount) as total_amount, COUNT(*) as count')
            ->groupBy('category')
            ->orderByDesc('total_amount')
            ->limit(8)
            ->get();
        
        // Monthly trend data for line charts
        $monthlyTrends = collect(range(11, 0))->map(fn($i) => [
            'total' => Scholarship::where('school_id', $schoolId)
                ->whereMonth('created_at', Carbon::today()->subMonths($i)->month)
                ->whereYear('created_at', Carbon::today()->subMonths($i)->year)
                ->sum('amount'),
            'count' => Scholarship::where('school_id', $schoolId)
                ->whereMonth('created_at', Carbon::today()->subMonths($i)->month)
                ->whereYear('created_at', Carbon::today()->subMonths($i)->year)
                ->count(),
        ])->reverse()->values();
        
        $monthlyTotal = $monthlyTrends->pluck('total')->toArray();
        $monthlyCount = $monthlyTrends->pluck('count')->toArray();
        
        // Cumulative data
        $cumulativeApproved = [];
        $cumulativePaid = [];
        $runningApproved = 0;
        $runningPaid = 0;
        
        foreach ($approved as $amount) {
            $runningApproved += $amount;
            $cumulativeApproved[] = $runningApproved;
        }
        
        foreach ($paid as $amount) {
            $runningPaid += $amount;
            $cumulativePaid[] = $runningPaid;
        }
        
        // Performance metrics
        $totalScholarships = array_sum($statusCounts);
        $totalAmount = Scholarship::where('school_id', $schoolId)->sum('amount');
        $avgAmount = $totalScholarships > 0 ? $totalAmount / $totalScholarships : 0;
        $approvalRate = $totalScholarships > 0 ? ($statusCounts['approved'] + $statusCounts['paid']) / $totalScholarships * 100 : 0;
        $paymentRate = $totalScholarships > 0 ? $statusCounts['paid'] / $totalScholarships * 100 : 0;
        
        // If no data exists, provide sample data for demonstration
        if ($totalScholarships == 0) {
            $labels = ['Jan 2024', 'Feb 2024', 'Mar 2024', 'Apr 2024', 'May 2024', 'Jun 2024'];
            $approved = [50000, 30000, 75000, 25000, 60000, 40000];
            $paid = [45000, 28000, 70000, 22000, 55000, 35000];
            $pending = [10000, 15000, 8000, 12000, 18000, 20000];
            $rejected = [5000, 8000, 3000, 6000, 10000, 12000];
            $monthlyTotal = [60000, 53000, 86000, 43000, 88000, 67000];
            $monthlyCount = [3, 4, 5, 3, 6, 4];
            $cumulativeApproved = [50000, 80000, 155000, 180000, 240000, 280000];
            $cumulativePaid = [45000, 73000, 143000, 165000, 220000, 255000];
            $statusCounts = ['pending' => 8, 'approved' => 6, 'rejected' => 5, 'paid' => 6];
            $amountRanges = ['0-25k' => 3, '25k-50k' => 4, '50k-75k' => 3, '75k-100k' => 2, '100k+' => 1];
            $topCategories = collect([
                ['category' => 'Merit', 'total_amount' => 120000, 'count' => 3],
                ['category' => 'Sports', 'total_amount' => 80000, 'count' => 2],
                ['category' => 'Science', 'total_amount' => 95000, 'count' => 3],
                ['category' => 'Arts', 'total_amount' => 45000, 'count' => 2],
                ['category' => 'Technology', 'total_amount' => 110000, 'count' => 2]
            ]);
            $totalScholarships = 22;
            $totalAmount = 450000;
            $avgAmount = 20454.55;
            $approvalRate = 54.55;
            $paymentRate = 27.27;
        }
        
        return view('admin.finance.scholarships.dashboard', compact(
            'labels', 'approved', 'paid', 'pending', 'rejected',
            'statusCounts', 'amountRanges', 'topCategories',
            'monthlyTotal', 'monthlyCount', 'cumulativeApproved', 'cumulativePaid',
            'totalScholarships', 'totalAmount', 'avgAmount', 'approvalRate', 'paymentRate'
        ));
    }

    public function export()
    {
        return Excel::download(new ScholarshipsExport(auth()->user()->school_id ?? 1), 'scholarships.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:xlsx,csv']);
        Excel::import(new ScholarshipsImport(auth()->user()->school_id ?? 1), $request->file('file'));
        return back()->with('success','Scholarships imported.');
    }

    private function authorizeSchool(Scholarship $scholarship): void
    {
        if (($scholarship->school_id ?? null) !== (auth()->user()->school_id ?? 1)) {
            abort(403);
        }
    }
}


