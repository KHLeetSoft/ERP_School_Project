<?php

namespace App\Http\Controllers\Admin\Exams;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExamExport;
use App\Imports\ExamImport;
use Carbon\Carbon;

class ExamController extends Controller
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
    public function dashboard()
    {
        $schoolId = auth()->user()->school_id ?? null;
        $statusCounts = Exam::where('school_id', $schoolId)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $typeCounts = Exam::where('school_id', $schoolId)
            ->selectRaw('exam_type, COUNT(*) as total')
            ->groupBy('exam_type')
            ->pluck('total', 'exam_type');

        $monthlyCounts = Exam::where('school_id', $schoolId)
            ->selectRaw("DATE_FORMAT(start_date, '%Y-%m') as ym, COUNT(*) as total")
            ->groupBy('ym')
            ->orderBy('ym')
            ->pluck('total', 'ym');

        $monthlyStatus = Exam::where('school_id', $schoolId)
            ->selectRaw("DATE_FORMAT(start_date, '%Y-%m') as ym, status, COUNT(*) as total")
            ->groupBy('ym','status')
            ->orderBy('ym')
            ->get()
            ->groupBy('ym')
            ->map(function($rows){
                $row = ['scheduled'=>0,'completed'=>0,'cancelled'=>0,'draft'=>0];
                foreach ($rows as $r) { $row[$r->status] = (int) $r->total; }
                return $row;
            });

        $today = Carbon::today();
        $totalExams = Exam::where('school_id', $schoolId)->count();
        $upcomingExams = Exam::where('school_id', $schoolId)->whereDate('start_date','>', $today)->count();
        $ongoingExams = Exam::where('school_id', $schoolId)
            ->whereDate('start_date','<=',$today)
            ->whereDate('end_date','>=',$today)
            ->count();
        $avgDurationDays = (float) (Exam::where('school_id', $schoolId)
            ->whereNotNull('start_date')
            ->whereNotNull('end_date')
            ->selectRaw('AVG(DATEDIFF(end_date, start_date) + 1) as avg_days')
            ->value('avg_days') ?? 0);
        $completedCount = (int) ($statusCounts['completed'] ?? 0);
        $scheduleBase = (int) (($statusCounts['scheduled'] ?? 0) + $completedCount);
        $completionRate = $scheduleBase > 0 ? round(($completedCount / $scheduleBase) * 100, 1) : 0;

        $nextExams = Exam::where('school_id', $schoolId)
            ->whereDate('start_date','>=',$today)
            ->orderBy('start_date')
            ->take(5)
            ->get();

        $monthStart = $today->copy()->startOfMonth();
        $monthEnd = $today->copy()->endOfMonth();
        $thisMonth = Exam::where('school_id', $schoolId)
            ->whereBetween('start_date', [$monthStart, $monthEnd])
            ->orderBy('start_date')
            ->get();

        $recent = Exam::where('school_id', $schoolId)
            ->orderByDesc('start_date')
            ->take(10)
            ->get();

        // Insights
        $bmKey = $monthlyCounts->count() ? $monthlyCounts->sortDesc()->keys()->first() : null;
        $busiestMonthLabel = $bmKey ? Carbon::createFromFormat('Y-m', $bmKey)->format('M Y') : null;
        $busiestMonthCount = $bmKey ? (int) $monthlyCounts[$bmKey] : 0;

        $topExamType = $typeCounts->count() ? $typeCounts->sortDesc()->keys()->first() : null;
        $topExamTypeCount = $topExamType ? (int) $typeCounts[$topExamType] : 0;

        $durations = Exam::where('school_id', $schoolId)
            ->whereNotNull('start_date')
            ->whereNotNull('end_date')
            ->selectRaw('DATEDIFF(end_date, start_date) + 1 as d')
            ->pluck('d');
        $durationBuckets = [
            '1 day' => $durations->filter(fn($d) => $d == 1)->count(),
            '2-3 days' => $durations->filter(fn($d) => $d >= 2 && $d <= 3)->count(),
            '4-5 days' => $durations->filter(fn($d) => $d >= 4 && $d <= 5)->count(),
            '6-7 days' => $durations->filter(fn($d) => $d >= 6 && $d <= 7)->count(),
            '8+ days' => $durations->filter(fn($d) => $d >= 8)->count(),
        ];

        $next6 = collect(range(0,5))->map(fn($i) => $today->copy()->addMonths($i)->format('Y-m'));
        $nextSixMonthsCounts = $next6->mapWithKeys(fn($k) => [$k => (int) ($monthlyCounts[$k] ?? 0)]);

        $overdueExams = Exam::where('school_id', $schoolId)
            ->whereDate('end_date','<',$today)
            ->whereNotIn('status',['completed','cancelled'])
            ->count();

        return view('admin.exams.exam.dashboard', compact(
            'statusCounts','typeCounts','monthlyCounts','monthlyStatus','recent',
            'totalExams','upcomingExams','ongoingExams','avgDurationDays','completionRate','nextExams','thisMonth',
            'busiestMonthLabel','busiestMonthCount','topExamType','topExamTypeCount','durationBuckets','nextSixMonthsCounts','overdueExams'
        ));
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $schoolId = auth()->user()->school_id ?? null;
            $query = Exam::query()->where('school_id', $schoolId);
            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('start_date', fn($r)=>optional($r->start_date)->format('Y-m-d'))
                ->editColumn('end_date', fn($r)=>optional($r->end_date)->format('Y-m-d'))
                ->addColumn('actions', function ($r) {
                    $show = route('admin.exams.exam.show', $r->id);
                    $edit = route('admin.exams.exam.edit', $r->id);
                    $destroy = route('admin.exams.exam.destroy', $r->id);

                    return '<div class="btn-group" role="group">'
                        . '<a href="' . $show . '" class="btn btn-sm" title="View"><i class="bx bx-show"></i></a>'
                        . '<a href="' . $edit . '" class="btn btn-sm" title="Edit"><i class="bx bxs-edit"></i></a>'
                        . '<button type="button" class="btn btn-sm delete-exam-btn" data-action="' . e($destroy) . '" title="Delete"><i class="bx bx-trash"></i></button>'
                        . '</div>';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view('admin.exams.exam.index');
    }

    public function create()
    {
        return view('admin.exams.exam.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'exam_type' => 'nullable|string|max:100',
            'academic_year' => 'nullable|string|max:30',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:scheduled,completed,cancelled,draft',
        ]);
        $data['school_id'] = auth()->user()->school_id ?? null;
        Exam::create($data);
        return redirect()->route('admin.exams.exam.index')->with('success', 'Exam created.');
    }

    public function show(Exam $exam)
    {
        return view('admin.exams.exam.show', compact('exam'));
    }

    public function edit(Exam $exam)
    {
        return view('admin.exams.exam.edit', compact('exam'));
    }

    public function update(Request $request, Exam $exam)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'exam_type' => 'nullable|string|max:100',
            'academic_year' => 'nullable|string|max:30',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:scheduled,completed,cancelled,draft',
        ]);
        $exam->update($data);
        return redirect()->route('admin.exams.exam.index')->with('success', 'Exam updated.');
    }

    public function destroy(Exam $exam)
    {
        $exam->delete();
        return back()->with('success', 'Exam deleted.');
    }

    public function export(Request $request)
    {
        $schoolId = auth()->user()->school_id ?? null;
        return Excel::download(new ExamExport($schoolId), 'exams.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate(['file' => ['required','file','mimes:xlsx,csv,txt']]);
        $schoolId = auth()->user()->school_id ?? null;
        Excel::import(new ExamImport($schoolId), $request->file('file'));
        return back()->with('success', 'Import completed.');
    }
}


