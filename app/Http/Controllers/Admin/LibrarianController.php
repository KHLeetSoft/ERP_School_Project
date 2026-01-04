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
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LibrariansExport;
use App\Imports\LibrariansImport;

class LibrarianController extends Controller
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
            $status = $request->input('status');

            $query = User::where('role_id', 4);

            if ($adminId) {
                $query->where('admin_id', $adminId);
            }

            if ($status && $status !== 'All') {
                $query->where('status', $status === 'Active' ? 1 : 0);
            }

            $rows = $query->orderBy('id', 'desc')->get();
           
            return DataTables::of($rows)
                ->addIndexColumn()
                ->addColumn('name', function ($data) {
                    return '<a href="' . route('admin.users.librarians.show', $data->id) . '" class="link me-2" title="View Librarian Details">' . e($data->name) . '</a>';
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

                    $buttons .= '<a href="' . route('admin.users.librarians.show', $data->id) . '" class="text-info me-2" title="View">
                                    <i class="bx bx-show"></i>
                                </a>';

                    $buttons .= '<a href="' . route('admin.users.librarians.edit', $data->id) . '" class="text-primary me-2" title="Edit">
                                    <i class="bx bxs-edit"></i>
                                </a>';

                    $buttons .= '<a href="javascript:void(0);" data-id="' . $data->id . '" class="text-danger delete-librarian-btn" title="Delete">
                                    <i class="bx bx-trash"></i>
                                </a>';
                    $buttons .= '<a href="javascript:void(0);" data-id="' . $data->id . '" class="text-warning reset-password-btn" title="Reset Password">
                                <i class="bx bx-key"></i>
                            </a>';

                    $buttons .= '</div>';
                    return $buttons;
                })
                ->rawColumns(['name', 'email', 'status', 'actions'])
                ->make(true);
        }
        return view('admin.users.librarians.index');
    }

    public function create()
    {
        return view('admin.users.librarians.create');
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
            'role_id' => 4, // Librarian role ID
            'admin_id' => auth()->guard('admin')->id(),
            'password' => bcrypt($request->password),
            'status' => 1,
        ]);

        return redirect()->route('admin.users.librarians.index')->with('success', 'Librarian added successfully');
    }

    public function show($id)
    {
        $librarian = User::where('role_id', 4)->findOrFail($id); // 4 is librarian role ID
        return view('admin.users.librarians.show', compact('librarian'));
    }

    public function edit($id)
    {
        $librarian = User::findOrFail($id);
        return view('admin.users.librarians.edit', compact('librarian'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'status' => 'required|boolean',
        ]);

        $librarian = User::findOrFail($id);
        $librarian->update([
            'name' => $request->name,
            'email' => $request->email,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.users.librarians.index')->with('success', 'Librarian updated successfully');
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return redirect()->route('admin.users.librarians.index')->with('success', 'Librarian deleted successfully');
    }

    /**
     * Display librarian dashboard with book management summary and key metrics
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        // Get the authenticated librarian user
        $librarian = auth()->guard('admin')->user();
        
        // Get current month and year
        $currentMonth = now()->month;
        $currentYear = now()->year;
        
        // Placeholder for actual book data
        // In a real application, you would fetch this from your books table
        $libraryStats = [
            'total_books' => 0,
            'books_issued' => 0,
            'books_overdue' => 0,
            'books_available' => 0
        ];
        
        // Placeholder for recent book transactions
        // In a real application, you would fetch this from your book_issues table
        $recentTransactions = collect();
        
        // Placeholder for monthly comparison data
        // In a real application, you would calculate this from your book_issues table
        $monthlyComparison = [
            'current' => 0,
            'previous' => 0,
            'percentage' => 0
        ];
        
        // Calculate percentage change
        if ($monthlyComparison['previous'] > 0) {
            $monthlyComparison['percentage'] = (($monthlyComparison['current'] - $monthlyComparison['previous']) / $monthlyComparison['previous']) * 100;
        }
        
        return view('admin.users.librarians.dashboard', compact(
            'librarian',
            'libraryStats',
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
            'librarian_id' => 'required|exists:users,id',
            'password' => ['required', 'string', Password::min(8)],
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        $librarian = User::where('role_id', 4)->findOrFail($request->librarian_id);
        $librarian->update([
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'Password reset successfully']);
    }

    public function export()
    {
        return Excel::download(new LibrariansExport(), 'librarians.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:xlsx,csv,txt']);
        Excel::import(new LibrariansImport(), $request->file('file'));
        return redirect()->route('admin.users.librarians.index')->with('success', 'Librarians imported successfully');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!empty($ids)) {
            User::where('role_id', 4)->whereIn('id', $ids)->delete();
        }
        return redirect()->route('admin.users.librarians.index')->with('success', 'Selected librarians deleted');
    }

    public function bulkStatus(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:users,id',
            'status' => 'required|in:0,1'
        ]);
        User::where('role_id', 4)->whereIn('id', $validated['ids'])->update(['status' => (int)$validated['status']]);
        return redirect()->route('admin.users.librarians.index')->with('success', 'Status updated for selected librarians');
    }

    public function toggleStatus($id)
    {
        $user = User::where('role_id', 4)->findOrFail($id);
        $user->status = $user->status ? 0 : 1;
        $user->save();
        return redirect()->route('admin.users.librarians.index')->with('success', 'Status toggled');
    }
}