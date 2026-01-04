<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentResult;
use App\Models\StudentDetail;
use App\Models\ClassSection;
use App\Models\SchoolClass;
use App\Models\Subject;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StudentResultsExport;
use App\Imports\StudentResultsImport;

class StudentResultsController extends Controller
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
        $admin = auth()->user();
        $adminSchoolId = $admin->school_id;
    
        if ($request->ajax()) {
            $data = StudentResult::with([
                'student',
                'classSection.schoolClass',
                'classSection.section',
                'subject'
            ])
            ->when($adminSchoolId, function($q) use ($adminSchoolId) {
                $q->where('school_id', $adminSchoolId);
            });
    
            if ($request->filled('class_id')) {
                $classId = $request->class_id;
                $classSectionIds = ClassSection::where('school_id', $adminSchoolId)
                                    ->where('class_id', $classId)
                                    ->pluck('id')
                                    ->toArray();
                $data->whereIn('class_section_id', $classSectionIds);
            }
    
            if ($request->filled('subject_id')) {
                $data->where('subject_id', $request->subject_id);
            }
    
            if ($request->filled('exam_type')) {
                $data->where('exam_type', $request->exam_type);
            }
    
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('student_name', function ($row) {
                    return optional($row->student)->first_name . ' ' . optional($row->student)->last_name;
                })
                ->addColumn('class_name', function ($row) {
                    return $row->schoolClass->name ?? '';
                })
                ->addColumn('subject_name', function ($row) {
                    return $row->subject->subject_name ?? '';
                })
                ->addColumn('marks_obtained', function ($row) {
                    return ($row->marks_obtained ?? '-') . '/' . ($row->total_marks ?? '-');
                })
                ->addColumn('actions', function ($row) {
                    $buttons = '<div class="d-flex justify-content-start">';
                    $buttons .= '<a href="' . route('admin.students.results.show', $row->id) . '" class="text-info me-2" title="View"><i class="bx bx-show"></i></a>';
                    $buttons .= '<a href="' . route('admin.students.results.edit', $row->id) . '" class="text-primary me-2" title="Edit"><i class="bx bxs-edit"></i></a>';
                    $buttons .= '<a href="javascript:void(0);" data-id="' . $row->id . '" class="text-danger delete-result" title="Delete"><i class="bx bx-trash"></i></a>';
                    $buttons .= '</div>';
                    return $buttons;
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
    
        // AJAX नहीं होने पर view के लिए डेटा तैयार करें
        $classes = SchoolClass::where('school_id', $adminSchoolId)->get();
        $classSectionIds = ClassSection::where('school_id', $adminSchoolId)->pluck('id')->toArray();
        $subjects = Subject::whereIn('class_section_id', $classSectionIds)->get();
    
        return view('admin.students.results.index', compact('classes', 'subjects'));
    }
    
    
    public function create()
    {
        $students = StudentDetail::all();
        $classSections = ClassSection::all();
        return view('admin.students.results.create', compact('students', 'classSections'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required',
            'class_section_id' => 'required',
            'subject' => 'required|string',
            'marks_obtained' => 'required|numeric',
            'total_marks' => 'required|numeric',
            'exam_date' => 'required|date',
        ]);

        StudentResult::create($request->all());
        return redirect()->route('admin.students.results.index')->with('success', 'Result added successfully.');
    }

    public function edit($id)
    {
        $result = StudentResult::findOrFail($id);
        $students = StudentDetail::all();
        $classSections = ClassSection::all();
        return view('admin.students.results.edit', compact('result', 'students', 'classSections'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'student_id' => 'required',
            'class_section_id' => 'required',
            'subject' => 'required|string',
            'marks_obtained' => 'required|numeric',
            'total_marks' => 'required|numeric',
            'exam_date' => 'required|date',
        ]);

        $result = StudentResult::findOrFail($id);
        $result->update($request->all());

        return redirect()->route('admin.students.results.index')->with('success', 'Result updated successfully.');
    }

    public function destroy($id)
    {
        StudentResult::findOrFail($id)->delete();
        return response()->json(['success' => 'Result deleted successfully.']);
    }

    public function export()
    {
        return Excel::download(new StudentResultsExport, 'student_results.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv,xls'
        ]);

        Excel::import(new StudentResultsImport, $request->file('file'));

        return back()->with('success', 'Student results imported successfully.');
    }
}
