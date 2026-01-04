<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Excel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class TeacherController extends Controller
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
        
            $query = User::where('role_id', 3);

            if ($adminId) {
                $query->where('admin_id', $adminId);
            }

            $rows = $query->orderBy('id', 'desc')->get();
                      
             return DataTables::of($rows)
                ->addIndexColumn()
                ->addColumn('name', function ($data) {
                    return '<a href="' . route('admin.users.teachers.show', $data->id) . '" class="link me-2" title="View Teacher Details">' . e($data->name) . '</a>';
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
                ->addColumn('action', function ($data) {
                    $buttons = '<div class="d-flex justify-content">';
                    $buttons .= '<a href="' . route('admin.users.teachers.show', $data->id) . '" class="text-info me-2" title="View">
                                    <i class="bx bx-show" ></i>
                                </a>';
                    $buttons .= '<a href="' . route('admin.users.teachers.edit', $data->id) . '" class="text-primary me-2" title="Edit"> 
                                    <i class="bx bxs-edit"></i>
                                </a>';
                    $buttons .= '<a href="javascript:void(0);" data-id="' . $data->id . '" class="text-danger delete-teacher-btn" title="Delete">
                                    <i class="bx bx-trash" ></i>
                                </a>';
                    $buttons .= '<a href="javascript:void(0);" data-id="' . $data->id . '" class="text-warning reset-password-btn" title="Reset Password">
                                <i class="bx bx-key"></i>
                            </a>';
                    $buttons .= '</div>';
                    return $buttons;
                })
                ->rawColumns(['name', 'email', 'status', 'action'])
                ->make(true);
        }
        return view('admin.users.teachers.index');
    }

    public function create()
    {
        return view('admin.users.teachers.create');
    }

    public function store(Request $request)
    {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => 3,
           'admin_id' => auth()->guard('admin')->id(),
            'password' => bcrypt($request->password),
            'status' => 1,
        ]);

        return redirect()->route('admin.users.teachers.index')->with('success', 'Teacher added');
    }

    public function edit($id)
    {
        $teacher = User::findOrFail($id);
        return view('admin.users.teachers.edit', compact('teacher'));
    }

    public function update(Request $request, $id)
    {
        $teacher = User::findOrFail($id);
        $teacher->update([
            'name' => $request->name,
            'email' => $request->email,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.users.teachers.index')->with('success', 'Updated');
    }
    public function show($id)
    {
        $teacher = User::where('role_id', 3)->findOrFail($id); // 3 is teacher role ID
        return view('admin.users.teachers.show', compact('teacher'));
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return redirect()->route('admin.users.teachers.index')->with('success', 'Deleted');
    }

    /**
     * Display teacher dashboard with activity summary and key metrics
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        // Get the authenticated teacher user
        $teacher = auth()->guard('admin')->user();
        
        // Get current month and year
        $currentMonth = now()->month;
        $currentYear = now()->year;
        
        // Placeholder for actual activity data
        // In a real application, you would fetch this from your activities or lessons table
        $monthlyActivities = [
            'lessons' => 0,
            'assignments' => 0,
            'pending_reviews' => 0
        ];
        
        // Placeholder for recent activities
        // In a real application, you would fetch this from your activities or lessons table
        $recentActivities = collect();
        
        // Placeholder for monthly comparison data
        // In a real application, you would calculate this from your activities or lessons table
        $monthlyComparison = [
            'current' => 0,
            'previous' => 0,
            'percentage' => 0
        ];
        
        // Calculate percentage change
        if ($monthlyComparison['previous'] > 0) {
            $monthlyComparison['percentage'] = (($monthlyComparison['current'] - $monthlyComparison['previous']) / $monthlyComparison['previous']) * 100;
        }
        
        return view('admin.users.teachers.dashboard', compact(
            'teacher',
            'monthlyActivities',
            'recentActivities',
            'monthlyComparison',
            'currentMonth',
            'currentYear'
        ));
    }

    /**
     * Reset password for a teacher
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'teacher_id' => 'required|exists:users,id',
            'password' => ['required', 'string', 'min:8'],
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        $teacher = User::where('role_id', 3)->findOrFail($request->teacher_id);
        $teacher->update([
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'Password reset successfully']);
    }
    
}

