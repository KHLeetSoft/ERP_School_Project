<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentDetail;
use App\Models\User;
use App\Models\School;
use App\Models\SchoolClass;
use App\Models\Section;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Carbon;

class StudentDetailsController extends Controller
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

            $adminUser = auth()->user();

            if (!$adminUser || !$adminUser->id || $adminUser->role_id != 2) {
                return DataTables::of(collect([]))->make(true);
            }

            // Ab sirf wahi students dikhaye jinke user_id = admin ka ID
            $query = StudentDetail::with(['user', 'class', 'section'])
                        ->where('school_id', $adminUser->school_id)
                        ->latest();
            if ($request->filled('class_id')) {
                $query->where('class_id', $request->class_id);
            }
            if ($request->filled('section_id')) {
                $query->where('section_id', $request->section_id);
            }
            if ($request->filled('admission_no')) {
                $query->where('admission_no', 'like', '%' . $request->admission_no . '%');
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('student_name', function($row) {
                      return e(trim($row->first_name . ' ' . $row->last_name));
                })
                ->addColumn('email', fn($row) => e($row->user->email ?? '-'))
                ->addColumn('admission_no', fn($row) => e($row->admission_no))
                ->addColumn('roll_no', fn($row) => e($row->roll_no))
                ->addColumn('class', fn($row) => e($row->class->name ?? '-'))
                ->addColumn('section', fn($row) => e($row->section->name ?? '-'))
                ->addColumn('status', function ($row) {
                    return $row->user
                        ? ($row->user->is_active == 1
                            ? '<span class="badge bg-success">Active</span>'
                            : '<span class="badge bg-danger">Inactive</span>')
                        : '<span class="badge bg-secondary">No User</span>';
                })
                ->addColumn('actions', function ($row) {
                     $buttons = '<div class="d-flex justify-content">';
                    $buttons .= '<a href="' . route('admin.students.details.show', $row->id) . '" class="text-info me-2"><i class="bx bx-show"></i></a>';
                    $buttons .= '<a href="' . route('admin.students.details.edit', $row->id) . '" class="text-primary me-2"><i class="bx bxs-edit"></i></a>';
                    $buttons .= '<a href="javascript:void(0);" data-id="' . $row->id . '" class="text-danger delete-studentdetail" title="Delete"><i class="bx bx-trash"></i></a>';
                    $buttons .= '</div>';
                    return $buttons;
                  
                })
                ->rawColumns(['status', 'actions'])
                ->make(true);
        }
        return view('admin.students.details.index');
    }

    public function create()
    {
        $users = User::where('role_id', 6)->get();
        $schools = School::all();
        $classes = SchoolClass::all();
        $sections = Section::all();
        return view('admin.students.details.create', compact('users', 'schools', 'classes', 'sections'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id'           => 'required|exists:users,id',
            'school_id'         => 'required|exists:schools,id',
            'class_id'          => 'required|exists:school_classes,id',
            'section_id'        => 'required|exists:sections,id',
            'roll_no'           => 'required|integer',
            'admission_no'      => 'required|string|unique:student_details',
            'dob'               => 'required|date',
            'gender'            => 'required|in:male,female,other',
            'blood_group'       => 'nullable|string|max:3',
            'religion'          => 'nullable|string|max:50',
            'nationality'       => 'nullable|string|max:50',
            'category'          => 'nullable|string|max:50',
            'guardian_name'     => 'nullable|string|max:255',
            'guardian_contact'  => 'nullable|string|max:20',
            'address'           => 'nullable|string',
            'profile_image'     => 'nullable|string|max:255',
        ]);

        StudentDetail::create([
            'user_id'           => $request->user_id,
            'school_id'         => $request->school_id,
            'class_id'          => $request->class_id,
            'section_id'        => $request->section_id,
            'roll_no'           => $request->roll_no,
            'admission_no'      => $request->admission_no,
            'dob'               => $request->dob,
            'gender'            => $request->gender,
            'blood_group'       => $request->blood_group,
            'religion'          => $request->religion,
            'nationality'       => $request->nationality,
            'category'          => $request->category,
            'guardian_name'     => $request->guardian_name,
            'guardian_contact'  => $request->guardian_contact,
            'address'           => $request->address,
            'profile_image'     => $request->profile_image ?? 'uploads/students/default.png',
        ]);

        return redirect()->route('admin.students.details.index')->with('success', 'Student detail added successfully.');
    }

    public function show($id)
    {
        $details = StudentDetail::with(['user', 'school', 'class', 'section'])->findOrFail($id);
        return view('admin.students.details.show', compact('details'));
    }

    public function edit($id)
    {
        $details = StudentDetail::findOrFail($id);
        $users = User::where('role_id', 6)->get();
        $schools = School::all();
        $classes = SchoolClass::all();
        $sections = Section::all();
        return view('admin.students.details.edit', compact('details', 'users', 'schools', 'classes', 'sections'));
    }

    public function update(Request $request, $id)
    {
        $studentDetail = StudentDetail::findOrFail($id);

        $request->validate([
            'user_id'           => 'required|exists:users,id',
            'school_id'         => 'required|exists:schools,id',
            'class_id'          => 'required|exists:school_classes,id',
            'section_id'        => 'required|exists:sections,id',
            'roll_no'           => 'required|integer',
            'admission_no'      => 'required|string|max:255|unique:student_details,admission_no,' . $id,
            'dob'               => 'required|date',
            'gender'            => 'required|in:male,female,other',
            'blood_group'       => 'nullable|string|max:3',
            'religion'          => 'nullable|string|max:50',
            'nationality'       => 'nullable|string|max:50',
            'category'          => 'nullable|string|max:50',
            'guardian_name'     => 'nullable|string|max:255',
            'guardian_contact'  => 'nullable|string|max:20',
            'address'           => 'nullable|string',
            'profile_image'     => 'nullable|string|max:255',
        ]);

        $studentDetail->update($request->all());

        return redirect()->route('admin.students.details.index')->with('success', 'Student detail updated successfully.');
    }

    public function destroy($id)
    {
        $studentDetail = StudentDetail::findOrFail($id);
        $studentDetail->delete();

        return response()->json(['message' => 'Student detail deleted successfully.']);
    }
    public function export(Request $request)
    {
        $file = 'student_details_' . now()->format('Ymd_His') . '.xlsx';
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\StudentDetailExport(), $file);
    }

    public function import(Request $request)
    {
        $request->validate(['import_file' => 'required|file|mimes:xlsx,csv']);
        \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\StudentDetailImport(), $request->file('import_file'));
        return back()->with('success','Import completed');
    }

}
