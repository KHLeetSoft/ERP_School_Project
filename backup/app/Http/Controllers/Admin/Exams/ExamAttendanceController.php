<?php

namespace App\Http\Controllers\Admin\Exams;

use App\Http\Controllers\Controller;
use App\Models\ExamAttendance;
use App\Models\Exam;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExamAttendanceExport;
use App\Imports\ExamAttendanceImport;

class ExamAttendanceController extends Controller
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
		$totals = [
			'all' => ExamAttendance::where('school_id', $schoolId)->count(),
			'present' => ExamAttendance::where('school_id', $schoolId)->where('attendance_status','present')->count(),
			'absent' => ExamAttendance::where('school_id', $schoolId)->where('attendance_status','absent')->count(),
			'late' => ExamAttendance::where('school_id', $schoolId)->where('attendance_status','late')->count(),
		];
		$recent = ExamAttendance::with('exam')->where('school_id', $schoolId)->orderByDesc('created_at')->limit(10)->get();
		return view('admin.exams.attendance.dashboard', compact('totals','recent'));
	}

	public function index(Request $request)
{
    if ($request->ajax()) {
        $schoolId = auth()->user()->school_id ?? null;

        $query = OnlineExam::with([
                'schoolClass:id,name',
                'section:id,name',
                'subject:id,subject_name as name'
            ])
            ->withCount([
                'attempts as completed_attempts_count' => function ($q) {
                    $q->whereIn('status', ['submitted', 'auto_submitted']);
                }
            ])
            ->where('school_id', $schoolId)
            ->when($request->class_id, fn($q) => $q->where('class_id', $request->class_id))
            ->when($request->section_id, fn($q) => $q->where('section_id', $request->section_id))
            ->when($request->subject_id, fn($q) => $q->where('subject_id', $request->subject_id))
            ->when($request->status, fn($q) => $q->where('status', $request->status));

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('class_name', fn($r) => optional($r->schoolClass)->name)
            ->addColumn('section_name', fn($r) => optional($r->section)->name)
            ->addColumn('subject_name', fn($r) => optional($r->subject)->name)
            ->addColumn('completed_attempts', fn($r) => $r->completed_attempts_count)
            ->addColumn('actions', function ($r) {
                $show = route('admin.exams.online-exam.results.show', $r->id);
                $edit = route('admin.exams.online-exam.results.edit', $r->id);
                $destroy = route('admin.exams.online-exam.results.destroy', $r->id);

                return '<div class="btn-group" role="group">'
                    . '<a href="' . $show . '" class="btn btn-sm" title="View"><i class="bx bx-show"></i></a>'
                    . '<a href="' . $edit . '" class="btn btn-sm" title="Edit"><i class="bx bxs-edit"></i></a>'
                    . '<button type="button" class="btn btn-sm delete-exam-btn" data-action="' . e($destroy) . '" title="Delete"><i class="bx bx-trash"></i></button>'
                    . '</div>';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    // Normal view (non-Ajax)
    $classes = SchoolClass::orderBy('name')->get(['id', 'name']);
    $sections = Section::orderBy('name')->get(['id', 'name']);
    $subjects = Subject::orderBy('subject_name')->get(['id', 'subject_name as name']);

    return view('admin.exams.online-exam.results.index', compact('classes', 'sections', 'subjects'));
}


	public function create()
	{
		$exams = Exam::orderByDesc('start_date')->get(['id','title']);
		return view('admin.exams.attendance.create', compact('exams'));
	}

	public function store(Request $request)
	{
		$data = $request->validate([
			'exam_id' => 'required|exists:exams,id',
			'class_name' => 'nullable|string|max:100',
			'section_name' => 'nullable|string|max:50',
			'student_id' => 'nullable|integer',
			'student_name' => 'required|string|max:255',
			'admission_no' => 'nullable|string|max:100',
			'roll_no' => 'nullable|string|max:50',
			'exam_date' => 'nullable|date',
			'subject_name' => 'nullable|string|max:150',
			'attendance_status' => 'required|in:present,absent,late',
			'remarks' => 'nullable|string',
			'status' => 'required|in:published,draft',
		]);
		$data['school_id'] = auth()->user()->school_id ?? null;
		ExamAttendance::create($data);
		return redirect()->route('admin.exams.attendance.index')->with('success', 'Attendance saved.');
	}

	public function show(ExamAttendance $attendance)
	{
		return view('admin.exams.attendance.show', compact('attendance'));
	}

	public function edit(ExamAttendance $attendance)
	{
		$exams = Exam::orderByDesc('start_date')->get(['id','title']);
		return view('admin.exams.attendance.edit', compact('attendance','exams'));
	}

	public function update(Request $request, ExamAttendance $attendance)
	{
		$data = $request->validate([
			'exam_id' => 'required|exists:exams,id',
			'class_name' => 'nullable|string|max:100',
			'section_name' => 'nullable|string|max:50',
			'student_id' => 'nullable|integer',
			'student_name' => 'required|string|max:255',
			'admission_no' => 'nullable|string|max:100',
			'roll_no' => 'nullable|string|max:50',
			'exam_date' => 'nullable|date',
			'subject_name' => 'nullable|string|max:150',
			'attendance_status' => 'required|in:present,absent,late',
			'remarks' => 'nullable|string',
			'status' => 'required|in:published,draft',
		]);
		$attendance->update($data);
		return redirect()->route('admin.exams.attendance.index')->with('success', 'Attendance updated.');
	}

	public function destroy(ExamAttendance $attendance)
	{
		$attendance->delete();
		return back()->with('success', 'Attendance deleted.');
	}

	public function export()
	{
		$schoolId = auth()->user()->school_id ?? null;
		return Excel::download(new ExamAttendanceExport($schoolId), 'exam_attendance.xlsx');
	}

	public function import(Request $request)
	{
		$request->validate(['file' => ['required','file','mimes:xlsx,csv,txt']]);
		$schoolId = auth()->user()->school_id ?? null;
		Excel::import(new ExamAttendanceImport($schoolId), $request->file('file'));
		return back()->with('success', 'Import completed.');
	}
}



