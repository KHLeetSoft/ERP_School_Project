<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ParentPortalAccess;
use App\Models\ParentDetail;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash;

class ParentPortalAccessController extends Controller
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
            
            $query = ParentPortalAccess::with(['parentDetail'])->latest();

            if ($request->filled('parent_id')) {
                $query->where('parent_detail_id', $request->parent_id);
            }
            if ($request->filled('is_enabled')) {
                $query->where('is_enabled', (bool) $request->is_enabled);
            }
            if ($request->filled('access_level')) {
                $query->where('access_level', $request->access_level);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('parent_name', function ($row) {
                    $p = $row->parentDetail;
                    $name = trim(($p->primary_contact_name ?? '') . ' ' . ($p->secondary_contact_name ?? ''));
                    return e($name ?: 'N/A');
                })
                ->addColumn('enabled', fn($row) => $row->is_enabled ? 'Yes' : 'No')
                ->addColumn('access_level', fn($row) => ucfirst($row->access_level))
                ->addColumn('last_login_at', fn($row) => e(optional($row->last_login_at)->format('Y-m-d H:i'))) 
                ->addColumn('actions', function ($row) {
                    $buttons = '<div class="d-flex">';
                    $buttons .= '<a href="' . route('admin.parents.portal-access.show', $row->id) . '" class="text-info me-2" title="View"><i class="bx bx-show"></i></a>';
                    $buttons .= '<a href="' . route('admin.parents.portal-access.edit', $row->id) . '" class="text-primary me-2" title="Edit"><i class="bx bxs-edit"></i></a>';
                    $buttons .= '<a href="javascript:void(0);" data-id="' . $row->id . '" class="text-danger delete-access" title="Delete"><i class="bx bx-trash"></i></a>';
                    $buttons .= '</div>';
                    return $buttons;
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        $parents = ParentDetail::all();
        return view('admin.parents.portal_access.index', compact('parents'));
    }

    public function create()
    {
        $parents = ParentDetail::all();
        return view('admin.parents.portal_access.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'parent_detail_id' => 'required|exists:parent_details,id',
            'username' => 'required|string|max:255|unique:parent_portal_accesses,username',
            'email' => 'nullable|email|max:255|unique:parent_portal_accesses,email',
            'password' => 'required|string|min:6',
            'is_enabled' => 'nullable|boolean',
            'access_level' => 'required|in:basic,standard,premium',
            'force_password_reset' => 'nullable|boolean',
            'permissions' => 'nullable|array',
        ]);

        $data = $request->only(['parent_detail_id', 'username', 'email', 'is_enabled', 'access_level', 'force_password_reset', 'notes']);
        $data['school_id'] = auth()->user()->school_id ?? null;
        $data['password_hash'] = Hash::make($request->password);
        $data['is_enabled'] = $request->boolean('is_enabled');
        $data['force_password_reset'] = $request->boolean('force_password_reset');
        
        // Set default permissions based on access level
        if (!$request->filled('permissions')) {
            $data['permissions'] = $this->getDefaultPermissions($request->access_level);
        } else {
            $data['permissions'] = $request->permissions;
        }

        ParentPortalAccess::create($data);
        return redirect()->route('admin.parents.portal-access.index')->with('success', 'Parent portal access created successfully.');
    }

    public function show($id)
    {
        $record = ParentPortalAccess::with(['parentDetail'])->findOrFail($id);
        return view('admin.parents.portal_access.show', compact('record'));
    }

    public function edit($id)
    {
        $record = ParentPortalAccess::findOrFail($id);
        $parents = ParentDetail::all();
        return view('admin.parents.portal_access.edit', compact('record', 'parents'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'parent_detail_id' => 'required|exists:parent_details,id',
            'username' => 'required|string|max:255|unique:parent_portal_accesses,username,'.$id,
            'email' => 'nullable|email|max:255|unique:parent_portal_accesses,email,'.$id,
            'password' => 'nullable|string|min:6',
            'is_enabled' => 'nullable|boolean',
            'access_level' => 'required|in:basic,standard,premium',
            'force_password_reset' => 'nullable|boolean',
            'permissions' => 'nullable|array',
        ]);

        $record = ParentPortalAccess::findOrFail($id);
        $data = $request->only(['parent_detail_id', 'username', 'email', 'is_enabled', 'access_level', 'force_password_reset', 'notes']);
        
        if ($request->filled('password')) {
            $data['password_hash'] = Hash::make($request->password);
        }
        
        $data['is_enabled'] = $request->boolean('is_enabled');
        $data['force_password_reset'] = $request->boolean('force_password_reset');
        
        // Set default permissions based on access level if not provided
        if (!$request->filled('permissions')) {
            $data['permissions'] = $this->getDefaultPermissions($request->access_level);
        } else {
            $data['permissions'] = $request->permissions;
        }

        $record->update($data);
        return redirect()->route('admin.parents.portal-access.index')->with('success', 'Parent portal access updated successfully.');
    }

    public function destroy($id)
    {
        ParentPortalAccess::findOrFail($id)->delete();
        return response()->json(['message' => 'Parent portal access deleted successfully.']);
    }

    private function getDefaultPermissions($accessLevel)
    {
        $permissions = [
            'basic' => ['view_student_info', 'view_attendance', 'view_results'],
            'standard' => ['view_student_info', 'view_attendance', 'view_results', 'view_fees', 'view_schedule'],
            'premium' => ['view_student_info', 'view_attendance', 'view_results', 'view_fees', 'view_schedule', 'view_assignments', 'view_communications', 'download_reports']
        ];

        return $permissions[$accessLevel] ?? $permissions['basic'];
    }
}
