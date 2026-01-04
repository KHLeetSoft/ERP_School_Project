<?php

namespace App\Http\Controllers\Admin\Exams;

use App\Http\Controllers\Controller;
use App\Models\ExamGrade;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExamGradeExport;
use App\Imports\ExamGradeImport;

class ExamGradeController extends Controller
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
            $schoolId = auth()->user()->school_id ?? null;
            $query = ExamGrade::query()->where('school_id', $schoolId);
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('actions', function ($r) {
                    $edit = route('admin.exams.grades.edit', $r->id);
                    $destroy = route('admin.exams.grades.destroy', $r->id);
                    $show = route('admin.exams.grades.show', $r->id);
                    return '<div class="btn-group" role="group">'
                        . '<a href="' . $show . '" class="btn btn-sm" title="View"><i class="bx bx-show"></i></a>'
                        . '<a href="' . $edit . '" class="btn btn-sm" title="Edit"><i class="bx bxs-edit"></i></a>'
                        . '<button type="button" class="btn btn-sm delete-grade-btn" data-action="' . e($destroy) . '" title="Delete"><i class="bx bx-trash"></i></button>'
                        . '</div>';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('admin.exams.grades.index');
    }

    public function create()
    {
        return view('admin.exams.grades.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'grade' => 'required|string|max:5',
            'grade_point' => 'nullable|numeric',
            'min_percentage' => 'nullable|numeric',
            'max_percentage' => 'nullable|numeric',
            'remark' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);
        $data['school_id'] = auth()->user()->school_id ?? null;
        ExamGrade::create($data);
        return redirect()->route('admin.exams.grades.index')->with('success', 'Grade created.');
    }

    public function edit(ExamGrade $grade)
    {
        return view('admin.exams.grades.edit', compact('grade'));
    }
    public function show($id)
    {
        $grade = ExamGrade::findOrFail($id);
        return view('admin.exams.grades.show', compact('grade'));
    }
    public function update(Request $request, ExamGrade $grade)
    {
        $data = $request->validate([
            'grade' => 'required|string|max:5',
            'grade_point' => 'nullable|numeric',
            'min_percentage' => 'nullable|numeric',
            'max_percentage' => 'nullable|numeric',
            'remark' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);
        $grade->update($data);
        return redirect()->route('admin.exams.grades.index')->with('success', 'Grade updated.');
    }

    public function destroy(ExamGrade $grade)
    {
        $grade->delete();
        return back()->with('success', 'Grade deleted.');
    }

    public function export()
    {
        $schoolId = auth()->user()->school_id ?? null;
        return Excel::download(new ExamGradeExport($schoolId), 'exam_grades.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate(['file' => ['required','file','mimes:xlsx,csv,txt']]);
        $schoolId = auth()->user()->school_id ?? null;
        Excel::import(new ExamGradeImport($schoolId), $request->file('file'));
        return back()->with('success', 'Import completed.');
    }
    public function dashboard()
    {
        $grades = ExamGrade::all();
        return view('admin.exams.grades.dashboard', compact('grades'));
    }

    
}


