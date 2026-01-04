<?php

namespace App\Http\Controllers\Admin\Exams;

use App\Http\Controllers\Controller;
use App\Models\ExamMark;
use App\Models\Exam;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExamMarkExport;
use App\Imports\ExamMarkImport;

class ExamMarkController extends Controller
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
        $statusCounts = ExamMark::where('school_id', $schoolId)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')->pluck('total','status');
        $resultCounts = ExamMark::where('school_id', $schoolId)
            ->selectRaw('result_status, COUNT(*) as total')
            ->groupBy('result_status')->pluck('total','result_status');
        $subjectAvg = ExamMark::where('school_id', $schoolId)
            ->selectRaw('subject_name, AVG(obtained_marks) as avg_marks')
            ->groupBy('subject_name')->orderByDesc('avg_marks')->limit(10)->pluck('avg_marks','subject_name');
        $topStudents = ExamMark::where('school_id', $schoolId)
            ->selectRaw('student_id, student_name, AVG(percentage) as avg_pct')
            ->groupBy('student_id','student_name')->orderByDesc('avg_pct')->limit(10)->get();
        $recent = ExamMark::with('exam')->where('school_id', $schoolId)->orderByDesc('created_at')->limit(10)->get();
        return view('admin.exams.marks.dashboard', compact('statusCounts','resultCounts','subjectAvg','topStudents','recent'));
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $schoolId = auth()->user()->school_id ?? null;
            $query = ExamMark::with('exam')->where('school_id', $schoolId);
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('exam_title', fn($r)=> optional($r->exam)->title)
                ->addColumn('actions', function ($r) {
                    $show = route('admin.exams.marks.show', $r->id);
                    $edit = route('admin.exams.marks.edit', $r->id);
                    $destroy = route('admin.exams.marks.destroy', $r->id);
                    $print = route('admin.exams.marks.print', $r->id);
                    $download = route('admin.exams.marks.download', $r->id);
                    return '<div class="btn-group" role="group">'
                        . '<a href="' . $show . '" class="btn btn-sm" title="View"><i class="bx bx-show"></i></a>'
                        . '<a href="' . $edit . '" class="btn btn-sm" title="Edit"><i class="bx bxs-edit"></i></a>'
                        . '<a href="' . $download . '" class="btn btn-sm" title="Download CSV"><i class="bx bx-download"></i></a>'
                        . '<a href="' . $print . '" class="btn btn-sm" title="Print" target="_blank"><i class="bx bx-printer"></i></a>'
                        . '<button type="button" class="btn btn-sm delete-mark-btn" data-action="' . e($destroy) . '" title="Delete"><i class="bx bx-trash"></i></button>'
                        . '</div>';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        $exams = Exam::orderByDesc('start_date')->get(['id','title']);
        return view('admin.exams.marks.index', compact('exams'));
    }

    public function create()
    {
        $exams = Exam::orderByDesc('start_date')->get(['id','title']);
        return view('admin.exams.marks.create', compact('exams'));
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
            'subject_name' => 'required|string|max:150',
            'max_marks' => 'nullable|numeric',
            'obtained_marks' => 'nullable|numeric',
            'percentage' => 'nullable|numeric',
            'grade' => 'nullable|string|max:10',
            'result_status' => 'nullable|in:pass,fail',
            'remarks' => 'nullable|string',
            'status' => 'required|in:published,draft',
        ]);
        $data['school_id'] = auth()->user()->school_id ?? null;
        ExamMark::create($data);
        return redirect()->route('admin.exams.marks.index')->with('success', 'Mark saved.');
    }

    public function show(ExamMark $mark)
    {
        return view('admin.exams.marks.show', compact('mark'));
    }

    public function edit(ExamMark $mark)
    {
        $exams = Exam::orderByDesc('start_date')->get(['id','title']);
        return view('admin.exams.marks.edit', compact('mark','exams'));
    }

    public function update(Request $request, ExamMark $mark)
    {
        $data = $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'class_name' => 'nullable|string|max:100',
            'section_name' => 'nullable|string|max:50',
            'student_id' => 'nullable|integer',
            'student_name' => 'required|string|max:255',
            'admission_no' => 'nullable|string|max:100',
            'roll_no' => 'nullable|string|max:50',
            'subject_name' => 'required|string|max:150',
            'max_marks' => 'nullable|numeric',
            'obtained_marks' => 'nullable|numeric',
            'percentage' => 'nullable|numeric',
            'grade' => 'nullable|string|max:10',
            'result_status' => 'nullable|in:pass,fail',
            'remarks' => 'nullable|string',
            'status' => 'required|in:published,draft',
        ]);
        $mark->update($data);
        return redirect()->route('admin.exams.marks.index')->with('success', 'Mark updated.');
    }

    public function destroy(ExamMark $mark)
    {
        $mark->delete();
        return back()->with('success', 'Mark deleted.');
    }

    public function download(ExamMark $mark)
    {
        $fileName = 'mark_'.$mark->id.'.csv';
        $headers = ['Content-Type'=>'text/csv','Content-Disposition'=>'attachment; filename="'.$fileName.'"'];
        $callback = function () use ($mark) {
            $h = fopen('php://output','w');
            fputcsv($h, ['exam_id','class_name','section_name','student_id','student_name','admission_no','roll_no','subject_name','max_marks','obtained_marks','percentage','grade','result_status','remarks','status']);
            fputcsv($h, [$mark->exam_id,$mark->class_name,$mark->section_name,$mark->student_id,$mark->student_name,$mark->admission_no,$mark->roll_no,$mark->subject_name,$mark->max_marks,$mark->obtained_marks,$mark->percentage,$mark->grade,$mark->result_status,$mark->remarks,$mark->status]);
            fclose($h);
        };
        return response()->streamDownload($callback, $fileName, $headers);
    }

    public function print(ExamMark $mark)
    {
        return view('admin.exams.marks.print', compact('mark'));
    }

    public function export()
    {
        $schoolId = auth()->user()->school_id ?? null;
        return Excel::download(new ExamMarkExport($schoolId), 'exam_marks.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate(['file' => ['required','file','mimes:xlsx,csv,txt']]);
        $schoolId = auth()->user()->school_id ?? null;
        Excel::import(new ExamMarkImport($schoolId), $request->file('file'));
        return back()->with('success', 'Import completed.');
    }
}


