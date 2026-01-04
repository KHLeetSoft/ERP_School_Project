<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class TeacherController extends Controller
{
     public function index(Request $request)
    {
          if ($request->ajax()) {
          $adminUser = auth()->guard('admin')->user();

            if ($adminUser && $adminUser->role_id == 1) {
                // Super Admin
                $query = User::where('role_id', 3);
            } else {
                // School Admin or fallback
                $adminId = $adminUser?->id; // Safe access
                $query = User::where('role_id', 3)->where('admin_id', $adminId);
            }
        
            $rows = $query->orderBy('id', 'DESC')->get();

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
                
                ->addColumn('actions', function ($data) {
                    $buttons = '<div class="d-flex justify-content-end">';

                    $buttons .= '<a href="' . route('admin.users.teachers.show', $data->id) . '" class="text-info me-2" title="View">
                                    <i class="bx bx-show fa-2x"></i>
                                </a>';

                    $buttons .= '<a href="' . route('admin.users.teachers.edit', $data->id) . '" class="text-primary me-2" title="Edit">
                                    <i class="bx bxs-edit fa-2x"></i>
                                </a>';

                    $buttons .= '<a href="javascript:void(0);" data-id="' . $data->id . '" class="text-danger delete-teacher-btn" title="Delete">
                                    <i class="bx bx-trash fa-2x"></i>
                                </a>';

                    $buttons .= '</div>';
                    return $buttons;
                })
                //->rawColumns(['actions'])
                ->rawColumns(['name', 'email', 'status',  'actions'])
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
            'admin_id' => auth()->id(),
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
    
}

