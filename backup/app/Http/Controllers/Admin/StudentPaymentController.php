<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudentPayment;
use App\Models\StudentDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;

class StudentPaymentController extends Controller
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
            $query = StudentPayment::with(['student.user'])
                ->where('school_id', auth()->user()->school_id ?? 1)
                ->orderByDesc('payment_date');
            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('payment_date', fn($r)=> Carbon::parse($r->payment_date)->format('d M Y'))
                ->editColumn('student', fn($r)=> optional(optional($r->student)->user)->name)
                ->editColumn('amount', fn($r)=> number_format($r->amount, 2))
                ->addColumn('actions', function ($r) { return view('admin.finance.student-payments.partials.actions', compact('r'))->render(); })
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('admin.finance.student-payments.index');
    }

    public function create()
    {
        $students = StudentDetail::with('user')->where('school_id', auth()->user()->school_id ?? 1)->orderBy('admission_no')->get();
        return view('admin.finance.student-payments.create', compact('students'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'method' => 'required|in:cash,card,bank,online',
            'reference' => 'nullable|string|max:255',
            'status' => 'required|in:pending,completed,failed,refunded',
            'notes' => 'nullable|string',
        ]);

        StudentPayment::create([
            'school_id' => auth()->user()->school_id ?? 1,
            'student_id' => $request->student_id,
            'payment_date' => $request->payment_date,
            'amount' => $request->amount,
            'method' => $request->method,
            'reference' => $request->reference,
            'status' => $request->status,
            'notes' => $request->notes,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('admin.finance.student-payments.index')->with('success', 'Payment recorded.');
    }

    public function show(StudentPayment $student_payment)
    {
        $this->authorizeSchool($student_payment);
        return view('admin.finance.student-payments.show', ['payment' => $student_payment->load('student')]);
    }

    public function edit(StudentPayment $student_payment)
    {
        $this->authorizeSchool($student_payment);
        $students = StudentDetail::with('user')->where('school_id', auth()->user()->school_id ?? 1)->orderBy('admission_no')->get();
        return view('admin.finance.student-payments.edit', ['payment' => $student_payment, 'students' => $students]);
    }

    public function update(Request $request, StudentPayment $student_payment)
    {
        $this->authorizeSchool($student_payment);
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'method' => 'required|in:cash,card,bank,online',
            'reference' => 'nullable|string|max:255',
            'status' => 'required|in:pending,completed,failed,refunded',
            'notes' => 'nullable|string',
        ]);

        $student_payment->update([
            'student_id' => $request->student_id,
            'payment_date' => $request->payment_date,
            'amount' => $request->amount,
            'method' => $request->method,
            'reference' => $request->reference,
            'status' => $request->status,
            'notes' => $request->notes,
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('admin.finance.student-payments.index')->with('success', 'Payment updated.');
    }

    public function destroy(StudentPayment $student_payment)
    {
        $this->authorizeSchool($student_payment);
        $student_payment->delete();
        if (request()->wantsJson()) return response()->json(['success' => true]);
        return redirect()->route('admin.finance.student-payments.index')->with('success', 'Payment deleted.');
    }

    private function authorizeSchool(StudentPayment $payment): void
    {
        if (($payment->school_id ?? null) !== (auth()->user()->school_id ?? 1)) abort(403);
    }

    public function export()
    {
        $schoolId = auth()->user()->school_id ?? 1;
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\StudentPaymentsExport($schoolId), 'student_payments.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:xlsx,csv']);
        $schoolId = auth()->user()->school_id ?? 1;
        \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\StudentPaymentsImport($schoolId), $request->file('file'));
        return back()->with('success', 'Student payments imported.');
    }

    public function dashboard()
    {
        $schoolId = auth()->user()->school_id ?? 1;
        $labels = collect(range(5,0))->map(fn($i)=> Carbon::today()->subMonths($i)->format('M Y'));
        $ymKeys = collect(range(5,0))->map(fn($i)=> Carbon::today()->subMonths($i)->format('Y-m'));
        // 12-month trend
        $labels12 = collect(range(11,0))->map(fn($i)=> Carbon::today()->subMonths($i)->format('M Y'));
        $ymKeys12 = collect(range(11,0))->map(fn($i)=> Carbon::today()->subMonths($i)->format('Y-m'));

        // Monthly totals
        $totals = [];
        foreach ($ymKeys as $ym) {
            $totals[] = (float) StudentPayment::where('school_id', $schoolId)
                ->whereRaw('DATE_FORMAT(payment_date, "%Y-%m") = ?', [$ym])
                ->sum('amount');
        }
        $totals12 = [];
        foreach ($ymKeys12 as $ym) {
            $totals12[] = (float) StudentPayment::where('school_id', $schoolId)
                ->whereRaw('DATE_FORMAT(payment_date, "%Y-%m") = ?', [$ym])
                ->sum('amount');
        }

        // Status counts per month
        $allStatuses = ['pending','completed','failed','refunded'];
        $statusSeries = [];
        foreach ($allStatuses as $status) {
            $series = [];
            foreach ($ymKeys as $ym) {
                $series[] = (int) StudentPayment::where('school_id', $schoolId)
                    ->where('status', $status)
                    ->whereRaw('DATE_FORMAT(payment_date, "%Y-%m") = ?', [$ym])
                    ->count();
            }
            $statusSeries[$status] = $series;
        }

        // Method distribution overall
        $methodCounts = StudentPayment::where('school_id', $schoolId)
            ->selectRaw('method, COUNT(*) as cnt')
            ->groupBy('method')->pluck('cnt','method');

        // Average payment value per month
        $avgSeries = [];
        foreach ($ymKeys as $ym) {
            $sum = (float) StudentPayment::where('school_id', $schoolId)
                ->whereRaw('DATE_FORMAT(payment_date, "%Y-%m") = ?', [$ym])
                ->sum('amount');
            $count = (int) StudentPayment::where('school_id', $schoolId)
                ->whereRaw('DATE_FORMAT(payment_date, "%Y-%m") = ?', [$ym])
                ->count();
            $avgSeries[] = $count > 0 ? round($sum / max(1, $count), 2) : 0;
        }

        // Top students by amount
        $top = StudentPayment::where('school_id', $schoolId)
            ->selectRaw('student_id, SUM(amount) as amount')
            ->groupBy('student_id')
            ->orderByDesc('amount')
            ->limit(5)
            ->get();
        $studentIds = $top->pluck('student_id')->filter()->all();
        $studentNames = \App\Models\StudentDetail::with('user')
            ->whereIn('id', $studentIds)->get()
            ->keyBy('id')
            ->map(fn($s)=> optional($s->user)->name);
        $topLabels = $top->map(fn($r)=> (string) ($studentNames[$r->student_id] ?? 'Unknown'));
        $topValues = $top->pluck('amount')->map(fn($v)=> (float)$v);

        // Paid vs others monthly
        $completedSeries = [];
        $othersSeries = [];
        foreach ($ymKeys as $ym) {
            $completed = (float) StudentPayment::where('school_id', $schoolId)
                ->where('status', 'completed')
                ->whereRaw('DATE_FORMAT(payment_date, "%Y-%m") = ?', [$ym])
                ->sum('amount');
            $total = (float) StudentPayment::where('school_id', $schoolId)
                ->whereRaw('DATE_FORMAT(payment_date, "%Y-%m") = ?', [$ym])
                ->sum('amount');
            $completedSeries[] = $completed;
            $othersSeries[] = max(0, $total - $completed);
        }

        // Cumulative received
        $cumulativeSeries = [];
        $running = 0.0;
        foreach ($totals as $t) {
            $running += (float)$t;
            $cumulativeSeries[] = round($running, 2);
        }

        // Overall status distribution
        $statusCounts = StudentPayment::where('school_id', $schoolId)
            ->selectRaw('status, COUNT(*) as cnt')
            ->groupBy('status')->pluck('cnt','status');

        // Daily trend for last 30 days
        $last30Labels = collect(range(29,0))->map(fn($i)=> Carbon::today()->subDays($i)->format('d M'));
        $last30Keys = collect(range(29,0))->map(fn($i)=> Carbon::today()->subDays($i)->toDateString());
        $last30Series = [];
        foreach ($last30Keys as $day) {
            $last30Series[] = (float) StudentPayment::where('school_id', $schoolId)
                ->whereDate('payment_date', $day)
                ->sum('amount');
        }
        // 7-day moving average over last 30 series
        $ma7Series = [];
        $n = count($last30Series);
        for ($i = 0; $i < $n; $i++) {
            $start = max(0, $i - 6);
            $len = $i - $start + 1;
            $sum = 0;
            for ($j = $start; $j <= $i; $j++) { $sum += (float)$last30Series[$j]; }
            $ma7Series[] = round($sum / max(1, $len), 2);
        }

        // Method monthly amounts (stacked)
        $allMethods = ['cash','card','bank','online'];
        $methodMonthlySeries = [];
        foreach ($allMethods as $method) {
            $series = [];
            foreach ($ymKeys as $ym) {
                $series[] = (float) StudentPayment::where('school_id', $schoolId)
                    ->where('method', $method)
                    ->whereRaw('DATE_FORMAT(payment_date, "%Y-%m") = ?', [$ym])
                    ->sum('amount');
            }
            $methodMonthlySeries[$method] = $series;
        }

        // Unique payers per month
        $uniquePayers = [];
        foreach ($ymKeys as $ym) {
            $uniquePayers[] = (int) StudentPayment::where('school_id', $schoolId)
                ->whereRaw('DATE_FORMAT(payment_date, "%Y-%m") = ?', [$ym])
                ->distinct('student_id')->count('student_id');
        }

        // Success rate per month (completed / total %) and status amount series
        $successRateSeries = [];
        $completedCountSeries = [];
        $totalCountSeries = [];
        $pendingAmountSeries = [];
        $failedAmountSeries = [];
        $refundedAmountSeries = [];
        foreach ($ymKeys as $ym) {
            $completedCount = (int) StudentPayment::where('school_id', $schoolId)
                ->where('status','completed')
                ->whereRaw('DATE_FORMAT(payment_date, "%Y-%m") = ?', [$ym])->count();
            $totalCount = (int) StudentPayment::where('school_id', $schoolId)
                ->whereRaw('DATE_FORMAT(payment_date, "%Y-%m") = ?', [$ym])->count();
            $completedCountSeries[] = $completedCount;
            $totalCountSeries[] = $totalCount;
            $successRateSeries[] = $totalCount > 0 ? round(($completedCount / $totalCount) * 100, 1) : 0;

            $pendingAmountSeries[] = (float) StudentPayment::where('school_id', $schoolId)->where('status','pending')->whereRaw('DATE_FORMAT(payment_date, "%Y-%m") = ?', [$ym])->sum('amount');
            $failedAmountSeries[] = (float) StudentPayment::where('school_id', $schoolId)->where('status','failed')->whereRaw('DATE_FORMAT(payment_date, "%Y-%m") = ?', [$ym])->sum('amount');
            $refundedAmountSeries[] = (float) StudentPayment::where('school_id', $schoolId)->where('status','refunded')->whereRaw('DATE_FORMAT(payment_date, "%Y-%m") = ?', [$ym])->sum('amount');
        }

        // New vs Returning payers per month
        $newPayersSeries = [];
        $returningPayersSeries = [];
        $seen = collect();
        foreach ($ymKeys as $ym) { /* iterate from oldest to newest as built */ }
        // Ensure chronological from oldest to newest
        $ymKeysChrono = $ymKeys->values();
        foreach ($ymKeysChrono as $ym) {
            $monthStudents = StudentPayment::where('school_id', $schoolId)
                ->whereRaw('DATE_FORMAT(payment_date, "%Y-%m") = ?', [$ym])
                ->distinct('student_id')->pluck('student_id');
            $newCount = 0; $retCount = 0;
            foreach ($monthStudents as $sid) {
                if (!$seen->contains($sid)) { $newCount++; $seen->push($sid); } else { $retCount++; }
            }
            $newPayersSeries[] = $newCount;
            $returningPayersSeries[] = $retCount;
        }

        // Weekday distribution (Sun..Sat)
        $weekdayLabels = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
        $weekdayValues = [];
        foreach (range(1,7) as $dow) {
            $weekdayValues[] = (float) StudentPayment::where('school_id', $schoolId)
                ->whereRaw('DAYOFWEEK(payment_date) = ?', [$dow])
                ->sum('amount');
        }

        // Top classes by amount
        $topClassesRows = StudentPayment::join('student_details','student_payments.student_id','=','student_details.id')
            ->where('student_payments.school_id', $schoolId)
            ->selectRaw('student_details.class_id as class_id, SUM(student_payments.amount) as amount')
            ->groupBy('student_details.class_id')
            ->orderByDesc('amount')
            ->limit(5)
            ->get();
        $classIds = $topClassesRows->pluck('class_id')->filter()->all();
        $classNames = \App\Models\SchoolClass::whereIn('id', $classIds)->pluck('name','id');
        $topClassLabels = $topClassesRows->map(fn($r)=> (string) ($classNames[$r->class_id] ?? 'Class '.$r->class_id));
        $topClassValues = $topClassesRows->pluck('amount')->map(fn($v)=> (float)$v);

        // KPI cards
        $startThisMonth = Carbon::now()->startOfMonth()->toDateString();
        $endThisMonth = Carbon::now()->endOfMonth()->toDateString();
        $startLastMonth = Carbon::now()->subMonth()->startOfMonth()->toDateString();
        $endLastMonth = Carbon::now()->subMonth()->endOfMonth()->toDateString();
        $totalThisMonth = (float) StudentPayment::where('school_id', $schoolId)->whereBetween('payment_date', [$startThisMonth, $endThisMonth])->sum('amount');
        $totalLastMonth = (float) StudentPayment::where('school_id', $schoolId)->whereBetween('payment_date', [$startLastMonth, $endLastMonth])->sum('amount');
        $growthPct = $totalLastMonth > 0 ? round((($totalThisMonth - $totalLastMonth) / $totalLastMonth) * 100, 1) : null;
        $countThisMonth = (int) StudentPayment::where('school_id', $schoolId)->whereBetween('payment_date', [$startThisMonth, $endThisMonth])->count();
        $avgThisMonth = $countThisMonth > 0 ? round($totalThisMonth / $countThisMonth, 2) : 0;

        // Method totals (amount) overall
        $methodAmountTotals = StudentPayment::where('school_id', $schoolId)
            ->selectRaw('method, SUM(amount) as sum')
            ->groupBy('method')->pluck('sum','method')->map(fn($v)=> (float)$v);

        // Method share percentage per month (100% stacked)
        $methodShareSeries = [];
        foreach ($allMethods as $method) { $methodShareSeries[$method] = []; }
        foreach ($ymKeys as $idx => $ym) {
            $totalMonth = (float) StudentPayment::where('school_id', $schoolId)
                ->whereRaw('DATE_FORMAT(payment_date, "%Y-%m") = ?', [$ym])
                ->sum('amount');
            foreach ($allMethods as $method) {
                $amt = (float) StudentPayment::where('school_id', $schoolId)
                    ->where('method', $method)
                    ->whereRaw('DATE_FORMAT(payment_date, "%Y-%m") = ?', [$ym])
                    ->sum('amount');
                $methodShareSeries[$method][$idx] = $totalMonth > 0 ? round(($amt / $totalMonth) * 100, 1) : 0;
            }
        }

        // Amount histogram (counts)
        $histogramDefs = [
            ['label' => '<500', 'min' => 0, 'max' => 499.99],
            ['label' => '500-999', 'min' => 500, 'max' => 999.99],
            ['label' => '1000-1999', 'min' => 1000, 'max' => 1999.99],
            ['label' => '2000-4999', 'min' => 2000, 'max' => 4999.99],
            ['label' => '5000+', 'min' => 5000, 'max' => null],
        ];
        $histLabels = [];
        $histValues = [];
        foreach ($histogramDefs as $def) {
            $histLabels[] = $def['label'];
            $q = StudentPayment::where('school_id', $schoolId);
            if (is_null($def['max'])) {
                $q->where('amount', '>=', $def['min']);
            } else {
                $q->whereBetween('amount', [$def['min'], $def['max']]);
            }
            $histValues[] = (int)$q->count();
        }

        // Additional KPIs
        $refundedAmountThisMonth = (float) StudentPayment::where('school_id', $schoolId)
            ->where('status','refunded')->whereBetween('payment_date', [$startThisMonth, $endThisMonth])->sum('amount');
        $pendingCountThisMonth = (int) StudentPayment::where('school_id', $schoolId)
            ->where('status','pending')->whereBetween('payment_date', [$startThisMonth, $endThisMonth])->count();

        return view('admin.finance.student-payments.dashboard', compact(
            'labels','totals','statusSeries','methodCounts','avgSeries','topLabels','topValues','completedSeries','othersSeries','cumulativeSeries',
            'statusCounts','last30Labels','last30Series','methodMonthlySeries','uniquePayers','weekdayLabels','weekdayValues','topClassLabels','topClassValues',
            'totalThisMonth','totalLastMonth','growthPct','countThisMonth','avgThisMonth',
            'methodAmountTotals','methodShareSeries','histLabels','histValues','ma7Series','refundedAmountThisMonth','pendingCountThisMonth',
            'labels12','totals12','successRateSeries','pendingAmountSeries','failedAmountSeries','refundedAmountSeries','newPayersSeries','returningPayersSeries'
        ));
    }
}


