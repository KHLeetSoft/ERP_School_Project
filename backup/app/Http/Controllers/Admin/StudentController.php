<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\FileManagerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Role;
use App\Models\StudentDetail;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class StudentController extends Controller
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
            
            $adminId = $request->input('admin_id');
        
            $query = User::where('role_id', 6);

            if ($adminId) {
                $query->where('admin_id', $adminId);
            }

            $rows = $query->orderBy('id', 'desc')->get();

            return DataTables::of($rows)
                ->addIndexColumn()
                ->addColumn('name', function ($data) {
                    return '<a href="' . route('admin.users.students.show', $data->id) . '" class="link me-2" title="View Student Details">' . e($data->name) . '</a>';
                })
                ->addColumn('email', function ($data) {
                    return $data->email ?? '-';
                })
                ->addColumn('status', function ($data) {
                    if ($data->status) {
                        return '<span class="badge badge-pill badge-light-success">Active</span>';
                    } else {
                        return '<span class="badge badge-pill badge-light-danger">Inactive</span>';
                    }
                })
                
                ->addColumn('actions', function ($data) {
                    $buttons = '<div class="d-flex justify-content">';

                    $buttons .= '<a href="' . route('admin.users.students.show', $data->id) . '" class="text-info me-2" title="View">
                                    <i class="bx bx-show"></i>
                                </a>';

                    $buttons .= '<a href="' . route('admin.users.students.edit', $data->id) . '" class="text-primary me-2" title="Edit">
                                    <i class="bx bxs-edit""></i>
                                </a>';

                    $buttons .= '<a href="javascript:void(0);" data-id="' . $data->id . '" class="text-danger delete-student-btn" title="Delete">
                                    <i class="bx bx-trash"></i>
                                </a>';
                    $buttons .= '<a href="javascript:void(0);" data-id="' . $data->id . '" class="text-warning reset-password-btn" title="Reset Password">
                                <i class="bx bx-key"></i>
                            </a>';
                    $buttons .= '</div>';
                    return $buttons;
                })
                ->rawColumns(['name', 'email', 'status', 'actions'])
                ->make(true);
        }
        return view('admin.users.students.index');
    }

    public function create()
    {
        return view('admin.users.students.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'admission_no' => 'nullable|string|unique:student_details,admission_no',
        ]);

        // Create student user
        $student = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => 6, // Student role ID
            'admin_id' => auth()->guard('admin')->id(),
            'school_id' => $this->schoolId,
            'password' => bcrypt($request->password),
            'status' => 1,
        ]);

        // Create student details if admission number is provided
        if ($request->admission_no) {
            StudentDetail::create([
                'user_id' => $student->id,
                'school_id' => $this->schoolId,
                'admission_no' => $request->admission_no,
            ]);

            // Create student folder structure
            $fileManager = new FileManagerService();
            $fileManager->createStudentFolderStructure(
                $this->schoolId, 
                $student->id, 
                $student->name, 
                $request->admission_no
            );
        }

        return redirect()->route('admin.users.students.index')->with('success', 'Student added successfully');
    }

    public function show($id)
    {
        $student = User::where('role_id', 6)->findOrFail($id); // 6 is student role ID
        return view('admin.users.students.show', compact('student'));
    }

    public function edit($id)
    {
        $student = User::findOrFail($id);
        return view('admin.users.students.edit', compact('student'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'status' => 'required|boolean',
        ]);

        $student = User::findOrFail($id);
        $student->update([
            'name' => $request->name,
            'email' => $request->email,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.users.students.index')->with('success', 'Student updated successfully');
    }

    public function destroy($id)
    {
        $student = User::findOrFail($id);
        
        // Delete student folder if it exists
        $fileManager = new FileManagerService();
        $fileManager->deleteStudentFolder($this->schoolId, $id);
        
        $student->delete();
        return redirect()->route('admin.users.students.index')->with('success', 'Student deleted successfully');
    }

    /**
     * Display student dashboard with academic summary and key metrics
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        // Get the authenticated student user
        $student = auth()->guard('admin')->user();
        
        // Get current semester and academic year
        $currentSemester = 'Fall 2023'; // This would come from your settings or calculations
        $currentYear = '2023-2024';
        
        // Placeholder for actual academic data
        // In a real application, you would fetch this from your grades/courses tables
        $academicSummary = [
            'gpa' => 3.5,
            'attendance' => 92,
            'assignments_pending' => 3,
            'courses_enrolled' => 5
        ];
        
        // Placeholder for recent activities
        // In a real application, you would fetch this from your activities table
        $recentActivities = collect();
        
         // Placeholder for upcoming events
        // In a real application, you would fetch this from your events table
        $upcomingEvents = collect();
        
        return view('admin.users.students.dashboard', compact(
            'student',
            'academicSummary',
            'recentActivities',
            'upcomingEvents',
            'currentSemester',
            'currentYear'
        ));
    }
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:users,id',
            'password' => ['required', 'string', Password::min(8)],
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        $accountant = User::where('role_id', 6)->findOrFail($request->student_id);
        $accountant->update([
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'Password reset successfully']);
    }
}