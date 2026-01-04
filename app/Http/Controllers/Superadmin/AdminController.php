<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\School;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AdminController extends Controller
{
    public function index()
    {
        return view('superadmin.admins.index');
    }

    public function serverSideDataTable(Request $request)
    {
        if ($request->ajax()) 
        {
            // Filter only admin users (role_id = 2)
            $query = User::with('managedSchool')
                ->where('role_id', 2)
                ->latest();

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('school', function ($admin) {
                    return $admin->managedSchool->name ?? '-';
                })
                ->addColumn('name', function ($admin) {
                    return $admin->name;
                })
                ->addColumn('email', function ($admin) {
                    return $admin->email;
                })
                ->addColumn('created_at', function ($admin) {
                    return $admin->created_at ? $admin->created_at->format('d-m-Y H:i A') : '';
                })
                ->addColumn('status', function ($admin) {
                    if ($admin->status) {
                        return '<span class="badge badge-pill badge-light-success">Active</span>';
                    } else {
                        return '<span class="badge badge-pill badge-light-danger">Inactive</span>';
                    }
                })
               ->addColumn('actions', function ($admin) {
                    $edit = route('superadmin.admins.edit', $admin);
                    $show = route('superadmin.admins.show', $admin);

                    return '
                        <div class="d-flex justify-content-center gap-1">
                            <a href="' . $show . '" class="btn btn-sm btn-outline-info btn-action" title="View Details">
                                <i class="bx bx-show"></i>
                            </a>
                            <a href="' . $edit . '" class="btn btn-sm btn-outline-primary btn-action" title="Edit Admin">
                                <i class="bx bx-edit-alt"></i>
                            </a>
                            <button data-id="' . $admin->id . '" class="btn btn-sm btn-outline-danger btn-action delete-admin-btn" title="Delete Admin">
                                <i class="bx bx-trash"></i>
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['status', 'actions'])
                ->make(true);
        }
    }

    public function create()
    {
        return view('superadmin.admins.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'school_id' => 'nullable|exists:schools,id',
        ]);

        $admin = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role_id' => 2, // Admin role
            'school_id' => $request->school_id,
            'status' => true,
        ]);

        $admin->assignRole('admin');

        return redirect()->route('superadmin.admins.index')
            ->with('success', 'Admin created successfully.');
    }

    public function show(User $admin)
    {
        $admin->load('managedSchool');
        return view('superadmin.admins.show', compact('admin'));
    }

    public function edit(User $admin)
    {
        return view('superadmin.admins.edit', compact('admin'));
    }

    public function update(Request $request, User $admin)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $admin->id,
            'password' => 'nullable|string|min:8|confirmed',
            'school_id' => 'nullable|exists:schools,id',
            'status' => 'boolean',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'school_id' => $request->school_id,
            'status' => $request->has('status'),
        ];

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $admin->update($data);

        return redirect()->route('superadmin.admins.index')
            ->with('success', 'Admin updated successfully.');
    }

    public function destroy(User $admin)
    {
        // Remove admin from school if assigned
        if ($admin->managedSchool) {
            $admin->managedSchool->update(['admin_id' => null]);
        }

        $admin->delete();

        return redirect()->route('superadmin.admins.index')
            ->with('success', 'Admin deleted successfully.');
    }

}
