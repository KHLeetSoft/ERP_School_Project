<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\StudentPayment;

class FinanceReportsController extends Controller
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
        $schoolId = auth()->user()->school_id ?? 1;
        $start = $request->get('start_date') ? Carbon::parse($request->get('start_date'))->startOfDay() : Carbon::today()->subDays(29)->startOfDay();
        $end = $request->get('end_date') ? Carbon::parse($request->get('end_date'))->endOfDay() : Carbon::today()->endOfDay();

        return view('admin.finance.reports.index', compact('start', 'end'));
    }

    public function dashboard(Request $request)
    {
        $schoolId = auth()->user()->school_id ?? 1;
        $monthsBack = (int)($request->get('months') ?? 6);
        $monthsBack = max(1, min(24, $monthsBack));

        // Build month labels from oldest to newest
        $labels = collect(range($monthsBack - 1, 0))->map(fn($i)=> Carbon::today()->subMonths($i)->format('M Y'));

        // Initialize totals
        $incomeSeries = array_fill(0, $monthsBack, 0);
        $expenseSeries = array_fill(0, $monthsBack, 0);

        // Fetch income totals per month (prefer StudentPayments if present; otherwise use Invoices)
        $incomeSums = StudentPayment::query()
            ->where('school_id', $schoolId)
            ->whereDate('payment_date', '>=', Carbon::today()->subMonths($monthsBack - 1)->startOfMonth())
            ->whereDate('payment_date', '<=', Carbon::today()->endOfMonth())
            ->selectRaw('YEAR(payment_date) as y, MONTH(payment_date) as m, SUM(amount) as sum')
            ->groupBy('y','m')
            ->get()
            ->mapWithKeys(fn($r)=> [sprintf('%04d-%02d', $r->y, $r->m) => (float)$r->sum]);

        if ($incomeSums->isEmpty()) {
            $incomeSums = Invoice::query()
                ->where('school_id', $schoolId)
                ->whereDate('issue_date', '>=', Carbon::today()->subMonths($monthsBack - 1)->startOfMonth())
                ->whereDate('issue_date', '<=', Carbon::today()->endOfMonth())
                ->selectRaw('YEAR(issue_date) as y, MONTH(issue_date) as m, SUM(total) as sum')
                ->groupBy('y','m')
                ->get()
                ->mapWithKeys(fn($r)=> [sprintf('%04d-%02d', $r->y, $r->m) => (float)$r->sum]);
        }

        // Fetch expenses totals per month
        $expenseSums = Expense::query()
            ->where('school_id', $schoolId)
            ->whereDate('expense_date', '>=', Carbon::today()->subMonths($monthsBack - 1)->startOfMonth())
            ->whereDate('expense_date', '<=', Carbon::today()->endOfMonth())
            ->selectRaw('YEAR(expense_date) as y, MONTH(expense_date) as m, SUM(amount) as sum')
            ->groupBy('y','m')
            ->get()
            ->mapWithKeys(fn($r)=> [sprintf('%04d-%02d', $r->y, $r->m) => (float)$r->sum]);

        // Fill series arrays according to labels
        foreach ($labels as $idx => $label) {
            $date = Carbon::createFromFormat('M Y', $label);
            $key = $date->format('Y-m');
            $incomeSeries[$idx] = (float)($incomeSums[$key] ?? 0);
            $expenseSeries[$idx] = (float)($expenseSums[$key] ?? 0);
        }

        return view('admin.finance.reports.dashboard', compact('labels','incomeSeries','expenseSeries'));
    }
}


