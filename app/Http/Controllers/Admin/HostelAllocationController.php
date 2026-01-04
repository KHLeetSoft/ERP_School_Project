<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HostelAllocation;
use App\Models\HostelRoom;
use App\Models\Hostel;
use App\Models\StudentDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HostelAllocationController extends Controller
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
        $allocations = HostelAllocation::with(['student.user', 'hostel', 'room'])
            ->when(request('hostel_id'), function($query) {
                $query->where('hostel_id', request('hostel_id'));
            })
            ->when(request('status'), function($query) {
                $query->where('status', request('status'));
            })
            ->when(request('student_id'), function($query) {
                $query->where('student_id', request('student_id'));
            })
            ->paginate(15);

        $hostels = Hostel::where('status', 'active')->get();
        $students = StudentDetail::with('user')->get();
        
        return view('admin.accommodation.allocation.index', compact('allocations', 'hostels', 'students'));
    }

    public function create()
    {
        $hostels = Hostel::where('status', 'active')->get();
        $students = StudentDetail::with('user')->get();
        
        return view('admin.accommodation.allocation.create', compact('hostels', 'students'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:student_details,id',
            'hostel_id' => 'required|exists:hostels,id',
            'room_id' => 'required|exists:hostel_rooms,id',
            'bed_no' => 'nullable|string|max:50',
            'join_date' => 'required|date',
            'leave_date' => 'nullable|date|after_or_equal:join_date',
            'status' => 'nullable|in:active,left',
            'monthly_fee' => 'required|numeric|min:0',
            'security_deposit' => 'nullable|numeric|min:0',
            'remarks' => 'nullable|string',
        ]);

        // Check if room is available
        $room = HostelRoom::findOrFail($request->room_id);
        if ($room->status !== 'available') {
            return back()->withErrors(['room_id' => 'Selected room is not available.']);
        }

        // Check if student already has active allocation
        $existingAllocation = HostelAllocation::where('student_id', $request->student_id)
            ->where('status', 'active')
            ->first();
        
        if ($existingAllocation) {
            return back()->withErrors(['student_id' => 'Student already has an active hostel allocation.']);
        }

        $data = $request->all();
        $data['school_id'] = auth('admin')->user()->school_id ?? null;
        
        HostelAllocation::create($data);
        
        // Update room status if full
        $this->updateRoomStatus($room);
        
        return redirect()->route('admin.accommodation.allocation.index')
            ->with('success', 'Hostel allocation created successfully.');
    }

    public function show($id)
    {
        $allocation = HostelAllocation::with(['student.user', 'hostel', 'room'])
            ->findOrFail($id);
        
        return view('admin.accommodation.allocation.show', compact('allocation'));
    }

    public function edit($id)
    {
        $allocation = HostelAllocation::findOrFail($id);
        $hostels = Hostel::where('status', 'active')->get();
        $students = StudentDetail::with('user')->get();
        $rooms = HostelRoom::where('hostel_id', $allocation->hostel_id)->get();
        
        return view('admin.accommodation.allocation.edit', compact('allocation', 'hostels', 'students', 'rooms'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'student_id' => 'required|exists:student_details,id',
            'hostel_id' => 'required|exists:hostels,id',
            'room_id' => 'required|exists:hostel_rooms,id',
            'bed_no' => 'nullable|string|max:50',
            'join_date' => 'required|date',
            'leave_date' => 'nullable|date|after_or_equal:join_date',
            'status' => 'nullable|in:active,left',
            'monthly_fee' => 'required|numeric|min:0',
            'security_deposit' => 'nullable|numeric|min:0',
            'remarks' => 'nullable|string',
        ]);

        $allocation = HostelAllocation::findOrFail($id);
        $oldRoomId = $allocation->room_id;
        
        $allocation->update($request->all());
        
        // Update old and new room status
        if ($oldRoomId != $request->room_id) {
            $this->updateRoomStatus(HostelRoom::find($oldRoomId));
        }
        $this->updateRoomStatus(HostelRoom::find($request->room_id));
        
        return redirect()->route('admin.accommodation.allocation.index')
            ->with('success', 'Hostel allocation updated successfully.');
    }

    public function destroy($id)
    {
        $allocation = HostelAllocation::findOrFail($id);
        $roomId = $allocation->room_id;
        
        $allocation->delete();
        
        // Update room status
        $this->updateRoomStatus(HostelRoom::find($roomId));
        
        return response()->json([
            'success' => true,
            'message' => 'Hostel allocation deleted successfully.'
        ]);
    }

    public function dashboard()
    {
        $totalAllocations = HostelAllocation::count();
        $activeAllocations = HostelAllocation::where('status', 'active')->count();
        $leftAllocations = HostelAllocation::where('status', 'left')->count();
        
        $allocationsByHostel = HostelAllocation::with('hostel')
            ->selectRaw('hostel_id, count(*) as total')
            ->groupBy('hostel_id')
            ->get();
        
        return view('admin.accommodation.allocation.dashboard', compact(
            'totalAllocations', 'activeAllocations', 'leftAllocations', 'allocationsByHostel'
        ));
    }

    public function export()
    {
        // Implementation for exporting allocation data
        return response()->json(['message' => 'Export functionality to be implemented']);
    }

    public function import(Request $request)
    {
        // Implementation for importing allocation data
        return response()->json(['message' => 'Import functionality to be implemented']);
    }

    private function updateRoomStatus($room)
    {
        if (!$room) return;
        
        $activeAllocations = HostelAllocation::where('room_id', $room->id)
            ->where('status', 'active')
            ->count();
        
        if ($activeAllocations >= $room->capacity) {
            $room->update(['status' => 'full']);
        } else {
            $room->update(['status' => 'available']);
        }
    }
}
