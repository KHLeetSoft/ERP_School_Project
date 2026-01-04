<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HostelFee;
use App\Models\HostelAllocation;
use App\Models\StudentDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HostelFeeController extends Controller
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
    
    public function index()
    {
        $fees = HostelFee::with(['allocation.student.user', 'allocation.hostel'])
            ->when(request('hostel_id'), function($query) {
                $query->whereHas('allocation', function($q) {
                    $q->where('hostel_id', request('hostel_id'));
                });
            })
            ->when(request('student_id'), function($query) {
                $query->whereHas('allocation', function($q) {
                    $q->where('student_id', request('student_id'));
                });
            })
            ->when(request('status'), function($query) {
                $query->where('status', request('status'));
            })
            ->when(request('month'), function($query) {
                $query->where('month', request('month'));
            })
            ->when(request('year'), function($query) {
                $query->where('year', request('year'));
            })
            ->paginate(15);

        $allocations = HostelAllocation::with(['student.user', 'hostel'])
            ->where('status', 'active')
            ->get();
        
        return view('admin.accommodation.fees.index', compact('fees', 'allocations'));
    }

    public function create()
    {
        $allocations = HostelAllocation::with(['student.user', 'hostel'])
            ->where('status', 'active')
            ->get();
        
        return view('admin.accommodation.fees.create', compact('allocations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'allocation_id' => 'required|exists:hostel_allocations,id',
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2020',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'status' => 'nullable|in:pending,paid,overdue,waived',
            'payment_date' => 'nullable|date',
            'payment_method' => 'nullable|string|max:50',
            'transaction_id' => 'nullable|string|max:100',
            'remarks' => 'nullable|string',
        ]);

        // Check if fee already exists for this allocation, month, and year
        $existingFee = HostelFee::where('allocation_id', $request->allocation_id)
            ->where('month', $request->month)
            ->where('year', $request->year)
            ->first();
        
        if ($existingFee) {
            return back()->withErrors(['allocation_id' => 'Fee already exists for this allocation, month, and year.']);
        }

        $data = $request->all();
        $data['school_id'] = auth('admin')->user()->school_id ?? null;
        
        HostelFee::create($data);
        
        return redirect()->route('admin.accommodation.fees.index')
            ->with('success', 'Hostel fee created successfully.');
    }

    public function show($id)
    {
        $fee = HostelFee::with(['allocation.student.user', 'allocation.hostel'])
            ->findOrFail($id);
        
        return view('admin.accommodation.fees.show', compact('fee'));
    }

    public function edit($id)
    {
        $fee = HostelFee::findOrFail($id);
        $allocations = HostelAllocation::with(['student.user', 'hostel'])
            ->where('status', 'active')
            ->get();
        
        return view('admin.accommodation.fees.edit', compact('fee', 'allocations'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'allocation_id' => 'required|exists:hostel_allocations,id',
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2020',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'status' => 'nullable|in:pending,paid,overdue,waived',
            'payment_date' => 'nullable|date',
            'payment_method' => 'nullable|string|max:50',
            'transaction_id' => 'nullable|string|max:100',
            'remarks' => 'nullable|string',
        ]);

        $fee = HostelFee::findOrFail($id);
        
        // Check if fee already exists for this allocation, month, and year (excluding current record)
        $existingFee = HostelFee::where('allocation_id', $request->allocation_id)
            ->where('month', $request->month)
            ->where('year', $request->year)
            ->where('id', '!=', $id)
            ->first();
        
        if ($existingFee) {
            return back()->withErrors(['allocation_id' => 'Fee already exists for this allocation, month, and year.']);
        }

        $fee->update($request->all());
        
        return redirect()->route('admin.accommodation.fees.index')
            ->with('success', 'Hostel fee updated successfully.');
    }

    public function destroy($id)
    {
        $fee = HostelFee::findOrFail($id);
        $fee->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Hostel fee deleted successfully.'
        ]);
    }

    public function dashboard()
    {
        $totalFees = HostelFee::count();
        $paidFees = HostelFee::where('status', 'paid')->count();
        $pendingFees = HostelFee::where('status', 'pending')->count();
        $overdueFees = HostelFee::where('status', 'overdue')->count();
        
        $totalAmount = HostelFee::sum('amount');
        $paidAmount = HostelFee::where('status', 'paid')->sum('amount');
        $pendingAmount = HostelFee::where('status', 'pending')->sum('amount');
        
        $monthlyRevenue = HostelFee::where('status', 'paid')
            ->selectRaw('month, year, sum(amount) as total')
            ->groupBy('month', 'year')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get();
        
        return view('admin.accommodation.fees.dashboard', compact(
            'totalFees', 'paidFees', 'pendingFees', 'overdueFees',
            'totalAmount', 'paidAmount', 'pendingAmount', 'monthlyRevenue'
        ));
    }

    public function export()
    {
        // Implementation for exporting fee data
        return response()->json(['message' => 'Export functionality to be implemented']);
    }

    public function import(Request $request)
    {
        // Implementation for importing fee data
        return response()->json(['message' => 'Import functionality to be implemented']);
    }

    public function markAsPaid($id)
    {
        $fee = HostelFee::findOrFail($id);
        $fee->update([
            'status' => 'paid',
            'payment_date' => now(),
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Fee marked as paid successfully.'
        ]);
    }
}
