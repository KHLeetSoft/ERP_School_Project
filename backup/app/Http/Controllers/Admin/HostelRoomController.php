<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HostelRoom;
use App\Models\Hostel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HostelRoomController extends Controller
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
        $rooms = HostelRoom::with(['hostel'])
            ->when(request('hostel_id'), function($query) {
                $query->where('hostel_id', request('hostel_id'));
            })
            ->when(request('status'), function($query) {
                $query->where('status', request('status'));
            })
            ->when(request('gender'), function($query) {
                $query->where('gender', request('gender'));
            })
            ->paginate(15);

        $hostels = Hostel::where('status', 'active')->get();
        
        return view('admin.accommodation.rooms.index', compact('rooms', 'hostels'));
    }

    public function create()
    {
        $hostels = Hostel::where('status', 'active')->get();
        return view('admin.accommodation.rooms.create', compact('hostels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'hostel_id' => 'required|exists:hostels,id',
            'room_no' => 'required|string|max:50',
            'type' => 'nullable|string|max:50',
            'capacity' => 'required|integer|min:1',
            'gender' => 'nullable|in:Male,Female,Other',
            'floor' => 'nullable|string|max:50',
            'status' => 'nullable|in:available,maintenance,full',
            'notes' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['school_id'] = auth('admin')->user()->school_id ?? null;
        
        HostelRoom::create($data);
        
        return redirect()->route('admin.accommodation.rooms.index')
            ->with('success', 'Room created successfully.');
    }

    public function show($id)
    {
        $room = HostelRoom::with(['hostel', 'assignments.student.user'])
            ->findOrFail($id);
        
        return view('admin.accommodation.rooms.show', compact('room'));
    }

    public function edit($id)
    {
        $room = HostelRoom::findOrFail($id);
        $hostels = Hostel::where('status', 'active')->get();
        
        return view('admin.accommodation.rooms.edit', compact('room', 'hostels'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'hostel_id' => 'required|exists:hostels,id',
            'room_no' => 'required|string|max:50',
            'type' => 'nullable|string|max:50',
            'capacity' => 'required|integer|min:1',
            'gender' => 'nullable|in:Male,Female,Other',
            'floor' => 'nullable|string|max:50',
            'status' => 'nullable|in:available,maintenance,full',
            'notes' => 'nullable|string',
        ]);

        $room = HostelRoom::findOrFail($id);
        $room->update($request->all());
        
        return redirect()->route('admin.accommodation.rooms.index')
            ->with('success', 'Room updated successfully.');
    }

    public function destroy($id)
    {
        $room = HostelRoom::findOrFail($id);
        
        // Check if room has active assignments
        if ($room->assignments()->where('status', 'active')->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete room with active assignments.'
            ], 400);
        }
        
        $room->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Room deleted successfully.'
        ]);
    }

    public function dashboard()
    {
        $totalRooms = HostelRoom::count();
        $availableRooms = HostelRoom::where('status', 'available')->count();
        $maintenanceRooms = HostelRoom::where('status', 'maintenance')->count();
        $fullRooms = HostelRoom::where('status', 'full')->count();
        
        $roomsByHostel = HostelRoom::with('hostel')
            ->selectRaw('hostel_id, count(*) as total')
            ->groupBy('hostel_id')
            ->get();
        
        return view('admin.accommodation.rooms.dashboard', compact(
            'totalRooms', 'availableRooms', 'maintenanceRooms', 'fullRooms', 'roomsByHostel'
        ));
    }

    public function export()
    {
        // Implementation for exporting rooms data
        return response()->json(['message' => 'Export functionality to be implemented']);
    }

    public function import(Request $request)
    {
        // Implementation for importing rooms data
        return response()->json(['message' => 'Import functionality to be implemented']);
    }

    public function getRoomsByHostel(Request $request)
    {
        $request->validate(['hostel_id' => 'required|exists:hostels,id']);
        
        $rooms = HostelRoom::where('hostel_id', $request->hostel_id)
            ->where('status', 'available')
            ->get(['id', 'room_no', 'capacity', 'status']);
        
        return response()->json($rooms);
    }
}
