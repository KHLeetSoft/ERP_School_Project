<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExpenseCategoriesExport;
use App\Imports\ExpenseCategoriesImport;

class ExpenseCategoryController extends Controller
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
            $query = ExpenseCategory::where('school_id', auth()->user()->school_id ?? 1)
                ->with(['expenses', 'creator'])
                ->orderBy('name');
                
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('total_expenses', function ($r) {
                    return number_format($r->total_expenses, 2);
                })
                ->addColumn('monthly_expenses', function ($r) {
                    return number_format($r->monthly_expenses, 2);
                })
                ->addColumn('budget_utilization', function ($r) {
                    if (!$r->budget_limit) return '-';
                    $utilization = $r->budget_utilization;
                    $color = $r->status_color;
                    return "<span class='badge bg-{$color}'>{$utilization}%</span>";
                })
                ->addColumn('status', function ($r) {
                    $status = $r->is_active ? 'Active' : 'Inactive';
                    $color = $r->is_active ? 'success' : 'danger';
                    return "<span class='badge bg-{$color}'>{$status}</span>";
                })
                ->addColumn('actions', function ($r) { 
                    return view('admin.finance.expense-categories.partials.actions', compact('r'))->render(); 
                })
                ->rawColumns(['budget_utilization', 'status', 'actions'])
                ->make(true);
        }
        return view('admin.finance.expense-categories.index');
    }

    public function create()
    {
        return view('admin.finance.expense-categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:20|unique:expense_categories,code',
            'description' => 'nullable|string',
            'color' => 'required|string|max:7',
            'icon' => 'required|string|max:50',
            'budget_limit' => 'nullable|numeric|min:0',
            'budget_period' => 'required|in:monthly,quarterly,yearly',
            'is_active' => 'boolean',
        ]);

        ExpenseCategory::create([
            'school_id' => auth()->user()->school_id ?? 1,
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'description' => $request->description,
            'color' => $request->color,
            'icon' => $request->icon,
            'budget_limit' => $request->budget_limit,
            'budget_period' => $request->budget_period,
            'is_active' => $request->has('is_active'),
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('admin.finance.expense-categories.index')
            ->with('success', 'Expense category created successfully.');
    }

    public function show(ExpenseCategory $expenseCategory)
    {
        $this->authorizeSchool($expenseCategory);
        $expenseCategory->load(['expenses' => function($query) {
            $query->orderBy('expense_date', 'desc')->limit(10);
        }]);
        return view('admin.finance.expense-categories.show', compact('expenseCategory'));
    }

    public function edit(ExpenseCategory $expenseCategory)
    {
        $this->authorizeSchool($expenseCategory);
        return view('admin.finance.expense-categories.edit', compact('expenseCategory'));
    }

    public function update(Request $request, ExpenseCategory $expenseCategory)
    {
        $this->authorizeSchool($expenseCategory);
        
        $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:20|unique:expense_categories,code,' . $expenseCategory->id,
            'description' => 'nullable|string',
            'color' => 'required|string|max:7',
            'icon' => 'required|string|max:50',
            'budget_limit' => 'nullable|numeric|min:0',
            'budget_period' => 'required|in:monthly,quarterly,yearly',
            'is_active' => 'boolean',
        ]);

        $expenseCategory->update([
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'description' => $request->description,
            'color' => $request->color,
            'icon' => $request->icon,
            'budget_limit' => $request->budget_limit,
            'budget_period' => $request->budget_period,
            'is_active' => $request->has('is_active'),
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('admin.finance.expense-categories.index')
            ->with('success', 'Expense category updated successfully.');
    }

    public function destroy(ExpenseCategory $expenseCategory)
    {
        $this->authorizeSchool($expenseCategory);
        
        // Check if category has expenses
        if ($expenseCategory->expenses()->count() > 0) {
            if (request()->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Cannot delete category with existing expenses.']);
            }
            return redirect()->route('admin.finance.expense-categories.index')
                ->with('error', 'Cannot delete category with existing expenses.');
        }

        $expenseCategory->delete();
        
        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('admin.finance.expense-categories.index')
            ->with('success', 'Expense category deleted successfully.');
    }

    public function dashboard()
    {
        $schoolId = auth()->user()->school_id ?? 1;
        
        // Get all categories with their data
        $categories = ExpenseCategory::where('school_id', $schoolId)
            ->where('is_active', true)
            ->with(['expenses'])
            ->get();

        // If no categories exist, return empty data
        if ($categories->isEmpty()) {
            return view('admin.finance.expense-categories.dashboard', [
                'categoryPerformance' => collect(),
                'budgetData' => collect(),
                'monthlyTrend' => [],
                'monthLabels' => [],
                'categoryDistribution' => collect(),
                'budgetComparison' => collect(),
                'totalCategories' => 0,
                'totalBudget' => 0,
                'totalExpenses' => 0,
                'overBudgetCategories' => 0
            ]);
        }

        // Category performance data
        $categoryPerformance = $categories->map(function($category) {
            return [
                'name' => $category->name,
                'total_expenses' => $category->total_expenses,
                'monthly_expenses' => $category->monthly_expenses,
                'budget_limit' => $category->budget_limit,
                'utilization' => $category->budget_utilization ?? 0,
                'color' => $category->color,
                'expense_count' => $category->expenses->count()
            ];
        })->sortByDesc('total_expenses');

        // Budget utilization chart data
        $budgetData = $categories->filter(fn($c) => $c->budget_limit > 0)->map(function($category) {
            return [
                'name' => $category->name,
                'utilization' => $category->budget_utilization ?? 0,
                'color' => $category->color
            ];
        });

        // Monthly trend for top categories
        $topCategories = $categories->take(5);
        $monthlyTrend = [];
        $monthLabels = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthLabels[] = $date->format('M Y');
            
            foreach ($topCategories as $category) {
                $monthlyAmount = $category->expenses()
                    ->whereYear('expense_date', $date->year)
                    ->whereMonth('expense_date', $date->month)
                    ->sum('amount');
                    
                $monthlyTrend[$category->name][] = $monthlyAmount;
            }
        }

        // Category distribution pie chart
        $categoryDistribution = $categories->map(function($category) {
            return [
                'name' => $category->name,
                'amount' => $category->total_expenses,
                'color' => $category->color
            ];
        });

        // Budget vs Actual comparison
        $budgetComparison = $categories->filter(fn($c) => $c->budget_limit > 0)->map(function($category) {
            return [
                'name' => $category->name,
                'budget' => $category->budget_limit,
                'actual' => $category->monthly_expenses,
                'variance' => $category->budget_limit - $category->monthly_expenses,
                'color' => $category->color
            ];
        });

        // Summary statistics
        $totalCategories = $categories->count();
        $totalBudget = $categories->sum('budget_limit');
        $totalExpenses = $categories->sum('total_expenses');
        $overBudgetCategories = $categories->filter(fn($c) => ($c->budget_utilization ?? 0) > 100)->count();

        return view('admin.finance.expense-categories.dashboard', compact(
            'categoryPerformance',
            'budgetData',
            'monthlyTrend',
            'monthLabels',
            'categoryDistribution',
            'budgetComparison',
            'totalCategories',
            'totalBudget',
            'totalExpenses',
            'overBudgetCategories'
        ));
    }

    public function export()
    {
        return Excel::download(
            new ExpenseCategoriesExport(auth()->user()->school_id ?? 1), 
            'expense-categories.xlsx'
        );
    }

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:xlsx,csv']);
        
        try {
            Excel::import(
                new ExpenseCategoriesImport(auth()->user()->school_id ?? 1), 
                $request->file('file')
            );
            return back()->with('success', 'Expense categories imported successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    private function authorizeSchool(ExpenseCategory $expenseCategory): void
    {
        if (($expenseCategory->school_id ?? null) !== (auth()->user()->school_id ?? 1)) {
            abort(403);
        }
    }
}
