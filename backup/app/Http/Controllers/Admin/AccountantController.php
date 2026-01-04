<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Role;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class AccountantController extends Controller
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
        
            $query = User::where('role_id', 5);

            if ($adminId) {
                $query->where('admin_id', $adminId);
            }

            $rows = $query->orderBy('id', 'desc')->get();
        
            return DataTables::of($rows)
                ->addIndexColumn()
                ->addColumn('name', function ($data) {
                    return '<a href="' . route('admin.users.accountants.show', $data->id) . '" class="link me-2" title="View Accountant Details">' . e($data->name) . '</a>';
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

                    $buttons .= '<a href="' . route('admin.users.accountants.show', $data->id) . '" class="text-info me-2" title="View">
                                    <i class="bx bx-show"></i>
                                </a>';

                    $buttons .= '<a href="' . route('admin.users.accountants.edit', $data->id) . '" class="text-primary me-2" title="Edit">
                                    <i class="bx bxs-edit"></i>
                                </a>';

                    $buttons .= '<a href="javascript:void(0);" data-id="' . $data->id . '" class="text-danger delete-accountant-btn" title="Delete">
                                    <i class="bx bx-trash"></i>
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
        return view('admin.users.accountants.index');
    }

    public function create()
    {
        return view('admin.users.accountants.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => 5, // Correct Accountant role ID
            'admin_id' => auth()->guard('admin')->id(),
            'password' => bcrypt($request->password),
            'status' => 1,
        ]);

        return redirect()->route('admin.users.accountants.index')->with('success', 'Accountant added successfully');
    }

    public function show($id)
    {
        $accountant = User::where('role_id', 5)->findOrFail($id); // 4 is accountant role ID
        return view('admin.users.accountants.show', compact('accountant'));
    }

    public function edit($id)
    {
        $accountant = User::findOrFail($id);
        return view('admin.users.accountants.edit', compact('accountant'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'status' => 'required|boolean',
        ]);

        $accountant = User::findOrFail($id);
        $accountant->update([
            'name' => $request->name,
            'email' => $request->email,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.users.accountants.index')->with('success', 'Accountant updated successfully');
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return redirect()->route('admin.users.accountants.index')->with('success', 'Accountant deleted successfully');
    }

    /**
     * Display accountant dashboard with transaction summary and key metrics
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        // Get the authenticated accountant user
        $accountant = auth()->guard('admin')->user();
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
        
        return view('admin.users.accountants.dashboard', compact(
            'accountant',
            'monthlyTransactions',
            'recentTransactions',
            'monthlyComparison',
            'currentMonth',
            'currentYear'
        ));
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'accountant_id' => 'required|exists:users,id',
            'password' => ['required', 'string', Password::min(8)],
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        $accountant = User::where('role_id', 5)->findOrFail($request->accountant_id);
        $accountant->update([
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'Password reset successfully']);
    }

}