<?php

namespace App\Http\Controllers\Admin\Exams;

use App\Http\Controllers\Controller;
use App\Models\ExamSchedule;
use App\Models\Exam;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExamScheduleExport;
use App\Imports\ExamScheduleImport;
use Carbon\Carbon;

class ExamScheduleController extends Controller
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

        $statusCounts = ExamSchedule::where('school_id', $schoolId)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total','status');

        $classCounts = ExamSchedule::where('school_id', $schoolId)
            ->selectRaw('class_name, COUNT(*) as total')
            ->groupBy('class_name')
            ->orderByDesc('total')
            ->limit(10)
            ->pluck('total','class_name');

        $subjectCounts = ExamSchedule::where('school_id', $schoolId)
            ->selectRaw('subject_name, COUNT(*) as total')
            ->groupBy('subject_name')
            ->orderByDesc('total')
            ->limit(10)
            ->pluck('total','subject_name');

        $monthlyCounts = ExamSchedule::where('school_id', $schoolId)
            ->selectRaw("DATE_FORMAT(exam_date, '%Y-%m') as ym, COUNT(*) as total")
            ->groupBy('ym')
            ->orderBy('ym')
            ->pluck('total','ym');

        $invigilatorCounts = ExamSchedule::where('school_id', $schoolId)
            ->whereNotNull('invigilator_name')
            ->selectRaw('invigilator_name, COUNT(*) as total')
            ->groupBy('invigilator_name')
            ->orderByDesc('total')
            ->limit(5)
            ->pluck('total','invigilator_name');

        $today = Carbon::today();
        $thisWeekStart = $today->copy()->startOfWeek();
        $thisWeekEnd = $today->copy()->endOfWeek();

        $upcoming = ExamSchedule::with('exam')
            ->where('school_id', $schoolId)
            ->whereDate('exam_date','>=',$today)
            ->orderBy('exam_date')
            ->take(10)
            ->get();

        $thisWeek = ExamSchedule::with('exam')
            ->where('school_id', $schoolId)
            ->whereBetween('exam_date', [$thisWeekStart, $thisWeekEnd])
            ->orderBy('exam_date')
            ->get();

        $totalSchedules = ExamSchedule::where('school_id', $schoolId)->count();
        $todayCount = ExamSchedule::where('school_id', $schoolId)->whereDate('exam_date',$today)->count();
        $postponedCount = ExamSchedule::where('school_id', $schoolId)->where('status','postponed')->count();

        return view('admin.exams.schedule.dashboard', compact(
            'statusCounts','classCounts','subjectCounts','monthlyCounts','invigilatorCounts',
            'upcoming','thisWeek','totalSchedules','todayCount','postponedCount'
        ));
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $schoolId = auth()->user()->school_id ?? null;
            $query = ExamSchedule::with('exam')->where('school_id', $schoolId);
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('exam_title', fn($r)=> optional($r->exam)->title)
                ->editColumn('exam_date', fn($r)=>optional($r->exam_date)->format('Y-m-d'))
                ->addColumn('actions', function ($r) {
                    $show = route('admin.exams.schedule.show', $r->id);
                    $edit = route('admin.exams.schedule.edit', $r->id);
                    $destroy = route('admin.exams.schedule.destroy', $r->id);
                    return '<div class="btn-group" role="group">'
                        . '<a href="' . $show . '" class="btn btn-sm" title="View"><i class="bx bx-show"></i></a>'
                        . '<a href="' . $edit . '" class="btn btn-sm" title="Edit"><i class="bx bxs-edit"></i></a>'
                        . '<button type="button" class="btn btn-sm delete-es-btn" data-action="' . e($destroy) . '" title="Delete"><i class="bx bx-trash"></i></button>'
                        . '</div>';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        $exams = Exam::orderByDesc('start_date')->get(['id','title']);
        return view('admin.exams.schedule.index', compact('exams'));
    }

    public function create()
    {
        $exams = Exam::orderByDesc('start_date')->get(['id','title']);
        return view('admin.exams.schedule.create', compact('exams'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'class_name' => 'required|string|max:100',
            'section_name' => 'nullable|string|max:50',
            'subject_name' => 'required|string|max:150',
            'exam_date' => 'required|date',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'room_no' => 'nullable|string|max:50',
            'max_marks' => 'nullable|numeric',
            'pass_marks' => 'nullable|numeric',
            'invigilator_name' => 'nullable|string|max:150',
            'status' => 'required|string',
            'notes' => 'nullable|string',
        ]);
        $data['school_id'] = auth()->user()->school_id ?? null;
        ExamSchedule::create($data);
        return redirect()->route('admin.exams.schedule.index')->with('success', 'Exam schedule created.');
    }

    public function show(ExamSchedule $schedule)
    {
        return view('admin.exams.schedule.show', compact('schedule'));
    }

    public function edit(ExamSchedule $schedule)
    {
        $exams = Exam::orderByDesc('start_date')->get(['id','title']);
        return view('admin.exams.schedule.edit', compact('schedule','exams'));
    }

    public function update(Request $request, ExamSchedule $schedule)
    {
        $data = $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'class_name' => 'required|string|max:100',
            'section_name' => 'nullable|string|max:50',
            'subject_name' => 'required|string|max:150',
            'exam_date' => 'required|date',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'room_no' => 'nullable|string|max:50',
            'max_marks' => 'nullable|numeric',
            'pass_marks' => 'nullable|numeric',
            'invigilator_name' => 'nullable|string|max:150',
            'status' => 'required|string',
            'notes' => 'nullable|string',
        ]);
        $schedule->update($data);
        return redirect()->route('admin.exams.schedule.index')->with('success', 'Exam schedule updated.');
    }

    public function destroy(ExamSchedule $schedule)
    {
        $schedule->delete();
        return back()->with('success', 'Exam schedule deleted.');
    }

    public function export()
    {
        $schoolId = auth()->user()->school_id ?? null;
        return Excel::download(new ExamScheduleExport($schoolId), 'exam_schedules.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate(['file' => ['required','file','mimes:xlsx,csv,txt']]);
        $schoolId = auth()->user()->school_id ?? null;
        Excel::import(new ExamScheduleImport($schoolId), $request->file('file'));
        return back()->with('success', 'Import completed.');
    }
}


