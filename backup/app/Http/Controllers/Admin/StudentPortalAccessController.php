<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentPortalAccess;
use App\Models\StudentDetail;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash;

class StudentPortalAccessController extends Controller
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
            $query = StudentPortalAccess::with(['student.user'])->latest();

            if ($request->filled('student_id')) {
                $query->where('student_id', $request->student_id);
            }
            if ($request->filled('is_enabled')) {
                $query->where('is_enabled', (bool) $request->is_enabled);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('student_name', function ($row) {
                    $s = $row->student;
                    $name = trim(($s->first_name ?? '') . ' ' . ($s->last_name ?? ''));
                    return e($name ?: ($s->user->name ?? '-'));
                })
                ->addColumn('enabled', fn($row) => $row->is_enabled ? 'Yes' : 'No')
                ->addColumn('last_login_at', fn($row) => e(optional($row->last_login_at)->format('Y-m-d H:i'))) 
                ->addColumn('actions', function ($row) {
                    $buttons = '<div class="d-flex">';
                    $buttons .= '<a href="' . route('admin.students.portal-access.show', $row->id) . '" class="text-info me-2" title="View"><i class="bx bx-show"></i></a>';
                    $buttons .= '<a href="' . route('admin.students.portal-access.edit', $row->id) . '" class="text-primary me-2" title="Edit"><i class="bx bxs-edit"></i></a>';
                    $buttons .= '<a href="javascript:void(0);" data-id="' . $row->id . '" class="text-danger delete-access" title="Delete"><i class="bx bx-trash"></i></a>';
                    $buttons .= '</div>';
                    return $buttons;
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        $students = StudentDetail::all();
        return view('admin.students.portal_access.index', compact('students'));
    }

    public function create()
    {
        $students = StudentDetail::all();
        return view('admin.students.portal_access.create', compact('students'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:student_details,id',
            'username' => 'required|string|max:255|unique:student_portal_accesses,username',
            'email' => 'nullable|email|max:255|unique:student_portal_accesses,email',
            'password' => 'required|string|min:6',
            'is_enabled' => 'nullable|boolean',
            'force_password_reset' => 'nullable|boolean',
        ]);

        $data = $request->only(['student_id','username','email','is_enabled','force_password_reset','notes']);
        $data['school_id'] = auth()->user()->school_id ?? null;
        $data['password_hash'] = Hash::make($request->password);
        $data['is_enabled'] = $request->boolean('is_enabled');
        $data['force_password_reset'] = $request->boolean('force_password_reset');
        StudentPortalAccess::create($data);
        return redirect()->route('admin.students.portal-access.index')->with('success', 'Portal access created.');
    }

    public function show($id)
    {
        $record = StudentPortalAccess::with(['student.user'])->findOrFail($id);
        return view('admin.students.portal_access.show', compact('record'));
    }

    public function edit($id)
    {
        $record = StudentPortalAccess::findOrFail($id);
        $students = StudentDetail::all();
        return view('admin.students.portal_access.edit', compact('record','students'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'student_id' => 'required|exists:student_details,id',
            'username' => 'required|string|max:255|unique:student_portal_accesses,username,'.$id,
            'email' => 'nullable|email|max:255|unique:student_portal_accesses,email,'.$id,
            'password' => 'nullable|string|min:6',
            'is_enabled' => 'nullable|boolean',
            'force_password_reset' => 'nullable|boolean',
        ]);

        $record = StudentPortalAccess::findOrFail($id);
        $data = $request->only(['student_id','username','email','is_enabled','force_password_reset','notes']);
        if ($request->filled('password')) {
            $data['password_hash'] = Hash::make($request->password);
        }
        $data['is_enabled'] = $request->boolean('is_enabled');
        $data['force_password_reset'] = $request->boolean('force_password_reset');
        $record->update($data);
        return redirect()->route('admin.students.portal-access.index')->with('success', 'Portal access updated.');
    }

    public function destroy($id)
    {
        StudentPortalAccess::findOrFail($id)->delete();
        return response()->json(['message' => 'Portal access deleted.']);
    }
}


