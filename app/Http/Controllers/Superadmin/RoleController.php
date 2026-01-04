<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of roles
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Role::withCount(['users', 'permissions'])->latest();

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('role_info', function ($role) {
                    $badgeClass = $role->is_active ? 'success' : 'danger';
                    $systemBadge = $role->is_system ? '<span class="badge bg-info ms-1">System</span>' : '';
                    
                    return '
                        <div class="d-flex align-items-center">
                            <div>
                                <h6 class="mb-0">' . $role->name . '</h6>
                                <small class="text-muted">' . $role->slug . '</small>
                            </div>
                            <div class="ms-auto">
                                <span class="badge bg-' . $badgeClass . '">' . ($role->is_active ? 'Active' : 'Inactive') . '</span>
                                ' . $systemBadge . '
                            </div>
                        </div>
                    ';
                })
                ->addColumn('permissions_count', function ($role) {
                    return '<span class="badge bg-primary">' . $role->permissions_count . '</span>';
                })
                ->addColumn('users_count', function ($role) {
                    return '<span class="badge bg-secondary">' . $role->users_count . '</span>';
                })
                ->addColumn('action', function ($role) {
                    $editBtn = '<a href="' . route('superadmin.roles.edit', $role->id) . '" class="btn btn-sm btn-outline-primary" title="Edit">
                        <i class="fas fa-edit"></i>
                    </a>';
                    
                    $permissionsBtn = '<a href="' . route('superadmin.roles.permissions', $role->id) . '" class="btn btn-sm btn-outline-info" title="Manage Permissions">
                        <i class="fas fa-key"></i>
                    </a>';
                    
                    $deleteBtn = '';
                    if (!$role->is_system) {
                        $deleteBtn = '<form method="POST" action="' . route('superadmin.roles.destroy', $role->id) . '" style="display:inline;" onsubmit="return confirm(\'Are you sure?\')">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>';
                    }

                    return '<div class="btn-group" role="group">' . $editBtn . $permissionsBtn . $deleteBtn . '</div>';
                })
                ->rawColumns(['role_info', 'permissions_count', 'users_count', 'action'])
                ->make(true);
        }

        return view('superadmin.roles.index');
    }

    /**
     * Show the form for creating a new role
     */
    public function create()
    {
        return view('superadmin.roles.create');
    }

    /**
     * Store a newly created role
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean'
        ]);

        $role = Role::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'is_active' => $request->has('is_active'),
            'is_system' => false
        ]);

        return redirect()->route('superadmin.roles.index')
            ->with('success', 'Role created successfully.');
    }

    /**
     * Display the specified role
     */
    public function show(Role $role)
    {
        $role->load(['permissions', 'users']);
        return view('superadmin.roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified role
     */
    public function edit(Role $role)
    {
        return view('superadmin.roles.edit', compact('role'));
    }

    /**
     * Update the specified role
     */
    public function update(Request $request, Role $role)
    {
        if ($role->is_system) {
            return redirect()->back()->with('error', 'System roles cannot be modified.');
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean'
        ]);

        $role->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('superadmin.roles.index')
            ->with('success', 'Role updated successfully.');
    }

    /**
     * Remove the specified role
     */
    public function destroy(Role $role)
    {
        if ($role->is_system) {
            return redirect()->back()->with('error', 'System roles cannot be deleted.');
        }

        if ($role->users()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete role. It is assigned to users.');
        }

        $role->delete();

        return redirect()->route('superadmin.roles.index')
            ->with('success', 'Role deleted successfully.');
    }

    /**
     * Show role permissions management
     */
    public function permissions(Role $role)
    {
        // Get all permissions and group by module
        $allPermissions = Permission::all();
        $permissions = $allPermissions->groupBy(function($permission) {
            $parts = explode('.', $permission->name);
            return $parts[0] ?? 'unknown';
        });
        
        $modules = $permissions->keys();
        $rolePermissions = $role->permissions()->pluck('permissions.id')->toArray();

        return view('superadmin.roles.permissions', compact('role', 'modules', 'permissions', 'rolePermissions'));
    }

    /**
     * Update role permissions
     */
    public function updatePermissions(Request $request, Role $role)
    {
        $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        $permissionIds = $request->permissions ?? [];
        $role->syncPermissions($permissionIds);

        return redirect()->route('superadmin.roles.permissions', $role->id)
            ->with('success', 'Role permissions updated successfully.');
    }

    /**
     * Toggle role status
     */
    public function toggleStatus(Role $role)
    {
        if ($role->is_system) {
            return response()->json(['error' => 'System roles cannot be deactivated.'], 400);
        }

        $role->update(['is_active' => !$role->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $role->is_active,
            'message' => 'Role status updated successfully.'
        ]);
    }
}