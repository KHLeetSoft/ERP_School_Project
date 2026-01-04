<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of permissions
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Permission::withCount('roles')->latest();

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('permission_info', function ($permission) {
                    $parts = explode('.', $permission->name);
                    $module = $parts[0] ?? 'unknown';
                    $action = $parts[1] ?? 'unknown';
                    
                    return '
                        <div class="d-flex align-items-center">
                            <div>
                                <h6 class="mb-0">' . ucfirst($module) . ' - ' . ucfirst($action) . '</h6>
                                <small class="text-muted">' . $permission->name . '</small>
                            </div>
                            <div class="ms-auto">
                                <span class="badge bg-success">Active</span>
                            </div>
                        </div>
                    ';
                })
                ->addColumn('module_badge', function ($permission) {
                    $parts = explode('.', $permission->name);
                    $module = $parts[0] ?? 'unknown';
                    return '<span class="badge bg-info">' . ucfirst($module) . '</span>';
                })
                ->addColumn('action_badge', function ($permission) {
                    $parts = explode('.', $permission->name);
                    $action = $parts[1] ?? 'unknown';
                    return '<span class="badge bg-warning">' . ucfirst($action) . '</span>';
                })
                ->addColumn('roles_count', function ($permission) {
                    return '<span class="badge bg-primary">' . $permission->roles_count . '</span>';
                })
                ->addColumn('action', function ($permission) {
                    $editBtn = '<a href="' . route('superadmin.permissions.edit', $permission->id) . '" class="btn btn-sm btn-outline-primary" title="Edit">
                        <i class="fas fa-edit"></i>
                    </a>';
                    
                    $deleteBtn = '<form method="POST" action="' . route('superadmin.permissions.destroy', $permission->id) . '" style="display:inline;" onsubmit="return confirm(\'Are you sure?\')">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>';

                    return '<div class="btn-group" role="group">' . $editBtn . $deleteBtn . '</div>';
                })
                ->rawColumns(['permission_info', 'module_badge', 'action_badge', 'roles_count', 'action'])
                ->make(true);
        }

        // Get modules from permission names
        $permissions = Permission::all();
        $modules = $permissions->map(function($permission) {
            $parts = explode('.', $permission->name);
            return $parts[0] ?? 'unknown';
        })->unique()->values();
        
        return view('superadmin.permissions.index', compact('modules'));
    }

    /**
     * Show the form for creating a new permission
     */
    public function create()
    {
        $modules = [
            'teacher' => 'Teacher',
            'student' => 'Student',
            'parent' => 'Parent',
            'accountant' => 'Accountant',
            'librarian' => 'Librarian',
            'payment' => 'Payment',
            'attendance' => 'Attendance',
            'exam' => 'Exam',
            'library' => 'Library',
            'transport' => 'Transport',
            'hostel' => 'Hostel',
            'report' => 'Report',
            'setting' => 'Setting',
            'role' => 'Role & Permission'
        ];
        
        $actions = [
            'view' => 'View',
            'create' => 'Create',
            'edit' => 'Edit',
            'delete' => 'Delete',
            'export' => 'Export',
            'import' => 'Import',
            'approve' => 'Approve',
            'reject' => 'Reject'
        ];
        
        return view('superadmin.permissions.create', compact('modules', 'actions'));
    }

    /**
     * Store a newly created permission
     */
    public function store(Request $request)
    {
        $request->validate([
            'module_name' => 'required|string|max:255',
            'action_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000'
        ]);

        $permissionName = strtolower($request->module_name) . '.' . strtolower($request->action_name);

        Permission::create([
            'name' => $permissionName,
            'guard_name' => 'web'
        ]);

        return redirect()->route('superadmin.permissions.index')
            ->with('success', 'Permission created successfully.');
    }

    /**
     * Display the specified permission
     */
    public function show(Permission $permission)
    {
        $permission->load('roles');
        return view('superadmin.permissions.show', compact('permission'));
    }

    /**
     * Show the form for editing the specified permission
     */
    public function edit(Permission $permission)
    {
        $modules = [
            'teacher' => 'Teacher',
            'student' => 'Student',
            'parent' => 'Parent',
            'accountant' => 'Accountant',
            'librarian' => 'Librarian',
            'payment' => 'Payment',
            'attendance' => 'Attendance',
            'exam' => 'Exam',
            'library' => 'Library',
            'transport' => 'Transport',
            'hostel' => 'Hostel',
            'report' => 'Report',
            'setting' => 'Setting',
            'role' => 'Role & Permission'
        ];
        
        $actions = [
            'view' => 'View',
            'create' => 'Create',
            'edit' => 'Edit',
            'delete' => 'Delete',
            'export' => 'Export',
            'import' => 'Import',
            'approve' => 'Approve',
            'reject' => 'Reject'
        ];
        
        // Parse current permission name
        $parts = explode('.', $permission->name);
        $currentModule = $parts[0] ?? '';
        $currentAction = $parts[1] ?? '';
        
        return view('superadmin.permissions.edit', compact('permission', 'modules', 'actions', 'currentModule', 'currentAction'));
    }

    /**
     * Update the specified permission
     */
    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'module_name' => 'required|string|max:255',
            'action_name' => 'required|string|max:255'
        ]);

        $permissionName = strtolower($request->module_name) . '.' . strtolower($request->action_name);

        $permission->update([
            'name' => $permissionName
        ]);

        return redirect()->route('superadmin.permissions.index')
            ->with('success', 'Permission updated successfully.');
    }

    /**
     * Remove the specified permission
     */
    public function destroy(Permission $permission)
    {
        if ($permission->roles()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete permission. It is assigned to roles.');
        }

        $permission->delete();

        return redirect()->route('superadmin.permissions.index')
            ->with('success', 'Permission deleted successfully.');
    }

    /**
     * Toggle permission status
     */
    public function toggleStatus(Permission $permission)
    {
        $permission->update(['is_active' => !$permission->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $permission->is_active,
            'message' => 'Permission status updated successfully.'
        ]);
    }

    /**
     * Get permissions by module
     */
    public function getByModule(Request $request)
    {
        $module = $request->get('module');
        $permissions = Permission::where('name', 'like', $module . '.%')->get();
        
        return response()->json($permissions);
    }
}