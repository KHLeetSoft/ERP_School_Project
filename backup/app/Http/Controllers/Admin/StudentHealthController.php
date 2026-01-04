<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentHealth;
use App\Models\StudentDetail;
use App\Models\ClassSection;
use App\Models\SchoolClass;
use App\Models\Subject;
use Yajra\DataTables\Facades\DataTables;

class StudentHealthController extends Controller
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
        $data = StudentHealth::with([
            'student',
            'class'
        ])
        ->when($adminSchoolId, function($q) use ($adminSchoolId) {
            $q->where('school_id', $adminSchoolId);
        });

        if ($request->filled('class_id')) {
            $data->where('class_id', $request->class_id);
        }

        if ($request->filled('student_id')) {
            $data->where('student_id', $request->student_id);
        }

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('student_name', function ($row) {
                return trim(optional($row->student)->first_name . ' ' . optional($row->student)->last_name) ?: '-';
            })
            ->addColumn('class_name', function ($row) {
                return optional($row->class)->name ?? '-';
            })
            ->editColumn('blood_group', function ($row) {
                return $row->blood_group ?? '-';
            })
            ->editColumn('allergies', function ($row) {
                return $row->allergies ?? '-';
            })
            ->editColumn('medical_conditions', function ($row) {
                return $row->medical_conditions ?? '-';
            })
            ->editColumn('immunizations', function ($row) {
                return $row->immunizations ?? '-';
            })
            ->editColumn('last_checkup_date', function ($row) {
                return $row->last_checkup_date
                    ? \Carbon\Carbon::parse($row->last_checkup_date)->format('d-m-Y')
                    : '-';
            })
            ->addColumn('actions', function ($row) {
                $buttons = '<div class="d-flex justify-content-start">';
                $buttons .= '<a href="' . route('admin.students.health.show', $row->id) . '" class="text-info me-2" title="View"><i class="bx bx-show"></i></a>';
                $buttons .= '<a href="' . route('admin.students.health.edit', $row->id) . '" class="text-primary me-2" title="Edit"><i class="bx bxs-edit"></i></a>';
                $buttons .= '<a href="javascript:void(0);" data-id="' . $row->id . '" class="text-danger delete-health" title="Delete"><i class="bx bx-trash"></i></a>';
                $buttons .= '</div>';
                return $buttons;
            })

            ->rawColumns(['actions'])
            ->make(true);
    }

    $classes = SchoolClass::where('school_id', $adminSchoolId)->get();
    $students = StudentDetail::where('school_id', $adminSchoolId)->get();

    return view('admin.students.health.index', compact('classes', 'students'));
}

    

    public function create()
    {
        $students = StudentDetail::all();
        $classSections = ClassSection::all();
        return view('admin.students.health.create', compact('students', 'classSections'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required',
            'class_section_id' => 'required',
            'health_condition' => 'required|string',
            'last_checkup_date' => 'nullable|date',
            'status' => 'required|string',
        ]);

        $data = $request->all();
        $data['school_id'] = auth()->user()->school_id;

        StudentHealth::create($data);
        return redirect()->route('admin.students.health.index')->with('success', 'Student health record added successfully.');
    }

    public function show($id)
    {
        $health = StudentHealth::with(['student', 'classSection'])->findOrFail($id);
        return view('admin.students.health.show', compact('health'));
    }

    public function edit($id)
    {
        $health = StudentHealth::findOrFail($id);
        $students = StudentDetail::all();
        $classSections = ClassSection::all();
        return view('admin.students.health.edit', compact('health', 'students', 'classSections'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'student_id' => 'required',
            'class_section_id' => 'required',
            'health_condition' => 'required|string',
            'last_checkup_date' => 'nullable|date',
            'status' => 'required|string',
        ]);

        $record = StudentHealth::findOrFail($id);
        $record->update($request->all());

        return redirect()->route('admin.students.health.index')->with('success', 'Student health record updated successfully.');
    }

    public function destroy($id)
    {
        StudentHealth::findOrFail($id)->delete();
        return response()->json(['success' => 'Student health record deleted successfully.']);
    }
}
