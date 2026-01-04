<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExpensesExport;
use App\Imports\ExpensesImport;

class ExpenseController extends Controller
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
            $query = Expense::where('school_id', auth()->user()->school_id ?? 1)->orderByDesc('expense_date');
            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('expense_date', fn($r)=> Carbon::parse($r->expense_date)->format('d M Y'))
                ->editColumn('amount', fn($r)=> number_format($r->amount, 2))
                ->addColumn('actions', function ($r) { return view('admin.finance.expenses.partials.actions', compact('r'))->render(); })
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('admin.finance.expenses.index');
    }

    public function create()
    {
        return view('admin.finance.expenses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'expense_date' => 'required|date',
            'category' => 'nullable|string|max:100',
            'vendor' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'method' => 'required|in:cash,card,bank,online,cheque',
            'reference' => 'nullable|string|max:255',
            'status' => 'required|in:pending,approved,paid,void',
            'notes' => 'nullable|string',
        ]);
        Expense::create([
            'school_id' => auth()->user()->school_id ?? 1,
            'expense_date' => $request->expense_date,
            'category' => $request->category,
            'vendor' => $request->vendor,
            'description' => $request->description,
            'amount' => $request->amount,
            'method' => $request->method,
            'reference' => $request->reference,
            'status' => $request->status,
            'notes' => $request->notes,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);
        return redirect()->route('admin.finance.expenses.index')->with('success', 'Expense added.');
    }

    public function show(Expense $expense)
    {
        $this->authorizeSchool($expense);
        return view('admin.finance.expenses.show', compact('expense'));
    }

    public function edit(Expense $expense)
    {
        $this->authorizeSchool($expense);
        return view('admin.finance.expenses.edit', compact('expense'));
    }

    public function update(Request $request, Expense $expense)
    {
        $this->authorizeSchool($expense);
        $request->validate([
            'expense_date' => 'required|date',
            'category' => 'nullable|string|max:100',
            'vendor' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'method' => 'required|in:cash,card,bank,online,cheque',
            'reference' => 'nullable|string|max:255',
            'status' => 'required|in:pending,approved,paid,void',
            'notes' => 'nullable|string',
        ]);
        $expense->update([
            'expense_date' => $request->expense_date,
            'category' => $request->category,
            'vendor' => $request->vendor,
            'description' => $request->description,
            'amount' => $request->amount,
            'method' => $request->method,
            'reference' => $request->reference,
            'status' => $request->status,
            'notes' => $request->notes,
            'updated_by' => Auth::id(),
        ]);
        return redirect()->route('admin.finance.expenses.index')->with('success', 'Expense updated.');
    }

    public function destroy(Expense $expense)
    {
        $this->authorizeSchool($expense);
        $expense->delete();
        if (request()->wantsJson()) return response()->json(['success' => true]);
        return redirect()->route('admin.finance.expenses.index')->with('success', 'Expense deleted.');
    }

    private function authorizeSchool(Expense $expense): void
    {
        if (($expense->school_id ?? null) !== (auth()->user()->school_id ?? 1)) abort(403);
    }

    public function export()
    {
        return Excel::download(new ExpensesExport(auth()->user()->school_id ?? 1), 'expenses.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:xlsx,csv']);
        Excel::import(new ExpensesImport(auth()->user()->school_id ?? 1), $request->file('file'));
        return back()->with('success', 'Expenses imported.');
    }

    public function dashboard()
    {
        $schoolId = auth()->user()->school_id ?? 1;
        $labels = collect(range(5,0))->map(fn($i)=> Carbon::today()->subMonths($i)->format('M Y'));
        $ymKeys = collect(range(5,0))->map(fn($i)=> Carbon::today()->subMonths($i)->format('Y-%m'));

        // Monthly totals
        $totals = [];
        foreach ($ymKeys as $ym) {
            $totals[] = (float) Expense::where('school_id', $schoolId)
                ->whereRaw('DATE_FORMAT(expense_date, "%Y-%m") = ?', [$ym])
                ->sum('amount');
        }

        // Category totals overall and monthly
        $categoryTotals = Expense::where('school_id', $schoolId)
            ->selectRaw('category, SUM(amount) as amount')
            ->groupBy('category')->pluck('amount','category')->map(fn($v)=> (float)$v);

        $categories = array_values(array_filter(array_keys($categoryTotals->toArray()), fn($c)=> !is_null($c) && $c !== ''));
        $categoryMonthlySeries = [];
        foreach ($categories as $cat) {
            $series = [];
            foreach ($ymKeys as $ym) {
                $series[] = (float) Expense::where('school_id', $schoolId)
                    ->where('category', $cat)
                    ->whereRaw('DATE_FORMAT(expense_date, "%Y-%m") = ?', [$ym])
                    ->sum('amount');
            }
            $categoryMonthlySeries[$cat] = $series;
        }

        // Vendor totals top 5
        $topVendors = Expense::where('school_id', $schoolId)
            ->selectRaw('vendor, SUM(amount) as amount')
            ->groupBy('vendor')
            ->orderByDesc('amount')
            ->limit(5)->get();
        $vendorLabels = $topVendors->pluck('vendor');
        $vendorValues = $topVendors->pluck('amount')->map(fn($v)=> (float)$v);

        // Method distribution and status counts
        $methodCounts = Expense::where('school_id', $schoolId)
            ->selectRaw('method, COUNT(*) as cnt')->groupBy('method')->pluck('cnt','method');
        $statusCounts = Expense::where('school_id', $schoolId)
            ->selectRaw('status, COUNT(*) as cnt')->groupBy('status')->pluck('cnt','status');

        // KPIs
        $monthStart = Carbon::now()->startOfMonth()->toDateString();
        $monthEnd = Carbon::now()->endOfMonth()->toDateString();
        $lastStart = Carbon::now()->subMonth()->startOfMonth()->toDateString();
        $lastEnd = Carbon::now()->subMonth()->endOfMonth()->toDateString();
        $totalThisMonth = (float) Expense::where('school_id', $schoolId)->whereBetween('expense_date', [$monthStart, $monthEnd])->sum('amount');
        $totalLastMonth = (float) Expense::where('school_id', $schoolId)->whereBetween('expense_date', [$lastStart, $lastEnd])->sum('amount');
        $growthPct = $totalLastMonth > 0 ? round((($totalThisMonth - $totalLastMonth)/$totalLastMonth)*100, 1) : null;

        return view('admin.finance.expenses.dashboard', compact(
            'labels','totals','categoryTotals','categoryMonthlySeries','vendorLabels','vendorValues','methodCounts','statusCounts','totalThisMonth','totalLastMonth','growthPct'
        ));
    }
}


