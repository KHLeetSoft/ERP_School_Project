<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\UserRole;
use App\Models\Permission;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class UserRoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of user roles
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = User::with(['roles', 'school'])->latest();

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('user_info', function ($user) {
                    $statusBadge = $user->status ? 'success' : 'danger';
                    
                    return '
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-2">
                                <span class="text-white fw-bold">' . substr($user->name, 0, 1) . '</span>
                            </div>
                            <div>
                                <h6 class="mb-0">' . $user->name . '</h6>
                                <small class="text-muted">' . $user->email . '</small>
                            </div>
                            <div class="ms-auto">
                                <span class="badge bg-' . $statusBadge . '">' . ($user->status ? 'Active' : 'Inactive') . '</span>
                            </div>
                        </div>
                    ';
                })
                ->addColumn('school_name', function ($user) {
                    return $user->school ? $user->school->name : '-';
                })
                ->addColumn('roles', function ($user) {
                    $roleBadges = $user->roles->map(function ($role) {
                        $badgeClass = $role->is_active ? 'primary' : 'secondary';
                        return '<span class="badge bg-' . $badgeClass . ' me-1">' . $role->name . '</span>';
                    })->join(' ');
                    
                    return $roleBadges ?: '<span class="text-muted">No roles assigned</span>';
                })
                ->addColumn('permissions_count', function ($user) {
                    // Get permissions count through roles
                    $roleIds = $user->roles()->pluck('roles.id');
                    $count = Permission::whereHas('roles', function($query) use ($roleIds) {
                        $query->whereIn('roles.id', $roleIds);
                    })->count();
                    return '<span class="badge bg-info">' . $count . '</span>';
                })
                ->addColumn('action', function ($user) {
                    $assignBtn = '<a href="' . route('superadmin.user-roles.assign', $user->id) . '" class="btn btn-sm btn-outline-primary" title="Assign Roles">
                        <i class="fas fa-user-plus"></i>
                    </a>';
                    
                    $viewBtn = '<a href="' . route('superadmin.user-roles.show', $user->id) . '" class="btn btn-sm btn-outline-info" title="View Details">
                        <i class="fas fa-eye"></i>
                    </a>';

                    return '<div class="btn-group" role="group">' . $viewBtn . $assignBtn . '</div>';
                })
                ->rawColumns(['user_info', 'school_name', 'roles', 'permissions_count', 'action'])
                ->make(true);
        }

        return view('superadmin.user-roles.index');
    }

    /**
     * Show the form for assigning roles to a user
     */
    public function assign(User $user)
    {
        $roles = Role::active()->get();
        $userRoles = $user->roles()->pluck('roles.id')->toArray();
        
        return view('superadmin.user-roles.assign', compact('user', 'roles', 'userRoles'));
    }

    /**
     * Store assigned roles for a user
     */
    public function store(Request $request, User $user)
    {
        $request->validate([
            'roles' => 'array',
            'roles.*' => 'exists:roles,id'
        ]);

        $roleIds = $request->roles ?? [];
        $user->roles()->sync($roleIds);

        return redirect()->route('superadmin.user-roles.index')
            ->with('success', 'User roles updated successfully.');
    }

    /**
     * Display the specified user's roles and permissions
     */
    public function show(User $user)
    {
        $user->load(['roles.permissions', 'school']);
        
        // Get permissions through roles
        $roleIds = $user->roles()->pluck('roles.id');
        $permissions = Permission::whereHas('roles', function($query) use ($roleIds) {
            $query->whereIn('roles.id', $roleIds);
        })->get()->groupBy(function($permission) {
            $parts = explode('.', $permission->name);
            return $parts[0] ?? 'unknown';
        });
        
        return view('superadmin.user-roles.show', compact('user', 'permissions'));
    }

    /**
     * Remove a role from a user
     */
    public function removeRole(User $user, Role $role)
    {
        $user->roles()->detach($role->id);

        return response()->json([
            'success' => true,
            'message' => 'Role removed successfully.'
        ]);
    }

    /**
     * Get user's permissions by module
     */
    public function getPermissionsByModule(Request $request, User $user)
    {
        $module = $request->get('module');
        
        // Get permissions through roles
        $roleIds = $user->roles()->pluck('roles.id');
        $permissions = Permission::whereHas('roles', function($query) use ($roleIds) {
            $query->whereIn('roles.id', $roleIds);
        })->where('name', 'like', $module . '.%')->get();
        
        return response()->json($permissions);
    }

    /**
     * Bulk assign roles to multiple users
     */
    public function bulkAssign(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'role_ids' => 'required|array',
            'role_ids.*' => 'exists:roles,id'
        ]);

        $userIds = $request->user_ids;
        $roleIds = $request->role_ids;

        foreach ($userIds as $userId) {
            $user = User::find($userId);
            $user->roles()->sync($roleIds);
        }

        return response()->json([
            'success' => true,
            'message' => 'Roles assigned to ' . count($userIds) . ' users successfully.'
        ]);
    }
}