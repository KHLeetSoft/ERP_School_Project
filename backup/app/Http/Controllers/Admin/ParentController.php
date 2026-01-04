<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Role;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\Rules\Password;

class ParentController extends Controller
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
        
            $query = User::where('role_id', 7);

            if ($adminId) {
                $query->where('admin_id', $adminId);
            }

            $rows = $query->orderBy('id', 'desc')->get();

            return DataTables::of($rows)
                ->addIndexColumn()
                ->addColumn('name', function ($data) {
                    return '<a href="' . route('admin.users.parents.show', $data->id) . '" class="link me-2" title="View Parent Details">' . e($data->name) . '</a>';
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

                    $buttons .= '<a href="' . route('admin.users.parents.show', $data->id) . '" class="text-info me-2" title="View">
                                    <i class="bx bx-show"></i>
                                </a>';

                    $buttons .= '<a href="' . route('admin.users.parents.edit', $data->id) . '" class="text-primary me-2" title="Edit">
                                    <i class="bx bxs-edit"></i>
                                </a>';

                    $buttons .= '<a href="javascript:void(0);" data-id="' . $data->id . '" class="text-danger delete-parent-btn" title="Delete">
                                    <i class="bx bx-trash"></i>
                                </a>';
                    $buttons .= '<a href="javascript:void(0);" data-id="' . $data->id . '" class="text-warning reset-password-btn" title="Reset Password">
                                <i class="bx bx-key"></i>
                            </a>';
                    $buttons .= '</div>';
                    return $buttons;
                })
                ->rawColumns(['name', 'email', 'status',  'actions'])
                ->make(true);
        }
        return view('admin.users.parents.index');
    }

    public function create()
    {
        return view('admin.users.parents.create');
    }

    public function store(Request $request)
    {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => 4,
           'admin_id' => auth()->guard('admin')->id(),
            'password' => bcrypt($request->password),
            'status' => 1,
        ]);

        return redirect()->route('admin.users.parents.index')->with('success', 'Parent added');
    }

    public function edit($id)
    {
        $parent = User::findOrFail($id);
        return view('admin.users.parents.edit', compact('parent'));
    }

    public function update(Request $request, $id)
    {
        $parent = User::findOrFail($id);
        $parent->update([
            'name' => $request->name,
            'email' => $request->email,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.users.parents.index')->with('success', 'Updated');
    }
    public function show($id)
    {
        $parent = User::where('role_id', 7)->findOrFail($id); // 4 is parent role ID
        return view('admin.users.parents.show', compact('parent'));
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return redirect()->route('admin.users.parents.index')->with('success', 'Deleted');
    }
    
    /**
     * Display accountant dashboard with transaction summary and key metrics
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        // Get the authenticated accountant user
        $parent = auth()->guard('admin')->user();
       // $accountantId = $accountant->id;
        
        // Get current month and year
        $currentMonth = now()->month;
        $currentYear = now()->year;
        
        // Placeholder for actual transaction data
        // In a real application, you would fetch this from your transactions table
        $monthlyTransactions = [
            'income' => 0,
            'expense' => 0,
            'pending' => 0
        ];
        
        // Placeholder for recent transactions
        // In a real application, you would fetch this from your transactions table
        $recentTransactions = collect();
        
        // Placeholder for monthly comparison data
        // In a real application, you would calculate this from your transactions table
        $monthlyComparison = [
            'current' => 0,
            'previous' => 0,
            'percentage' => 0
        ];
        
        // Calculate percentage change
        if ($monthlyComparison['previous'] > 0) {
            $monthlyComparison['percentage'] = (($monthlyComparison['current'] - $monthlyComparison['previous']) / $monthlyComparison['previous']) * 100;
        }
        
        return view('admin.users.parents.dashboard', compact(
            'parent',
            'monthlyTransactions',
            'recentTransactions',
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
            'parent_id' => 'required|exists:users,id',
            'password' => ['required', 'string', Password::min(8)],
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        $accountant = User::where('role_id', 7)->findOrFail($request->parent_id);
        $accountant->update([
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'Password reset successfully']);
    }
} 