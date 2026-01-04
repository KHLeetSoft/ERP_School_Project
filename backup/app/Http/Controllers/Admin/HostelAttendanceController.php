<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HostelAttendance;
use App\Models\HostelAllocation;
use App\Models\StudentDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HostelAttendanceController extends Controller
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
        $attendances = HostelAttendance::with(['allocation.student.user', 'allocation.hostel'])
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
            ->when(request('date'), function($query) {
                $query->whereDate('date', request('date'));
            })
            ->when(request('status'), function($query) {
                $query->where('status', request('status'));
            })
            ->orderBy('date', 'desc')
            ->paginate(15);

        $allocations = HostelAllocation::with(['student.user', 'hostel'])
            ->where('status', 'active')
            ->get();
        
        return view('admin.accommodation.attendance.index', compact('attendances', 'allocations'));
    }

    public function create()
    {
        $allocations = HostelAllocation::with(['student.user', 'hostel'])
            ->where('status', 'active')
            ->get();
        
        return view('admin.accommodation.attendance.create', compact('allocations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'allocation_id' => 'required|exists:hostel_allocations,id',
            'date' => 'required|date',
            'status' => 'required|in:present,absent,late,leave',
            'check_in_time' => 'nullable|date_format:H:i',
            'check_out_time' => 'nullable|date_format:H:i|after:check_in_time',
            'remarks' => 'nullable|string',
        ]);

        // Check if attendance already exists for this allocation and date
        $existingAttendance = HostelAttendance::where('allocation_id', $request->allocation_id)
            ->whereDate('date', $request->date)
            ->first();
        
        if ($existingAttendance) {
            return back()->withErrors(['allocation_id' => 'Attendance already exists for this allocation and date.']);
        }

        $data = $request->all();
        $data['school_id'] = auth('admin')->user()->school_id ?? null;
        
        HostelAttendance::create($data);
        
        return redirect()->route('admin.accommodation.attendance.index')
            ->with('success', 'Hostel attendance recorded successfully.');
    }

    public function show($id)
    {
        $attendance = HostelAttendance::with(['allocation.student.user', 'allocation.hostel'])
            ->findOrFail($id);
        
        return view('admin.accommodation.attendance.show', compact('attendance'));
    }

    public function edit($id)
    {
        $attendance = HostelAttendance::findOrFail($id);
        $allocations = HostelAllocation::with(['student.user', 'hostel'])
            ->where('status', 'active')
            ->get();
        
        return view('admin.accommodation.attendance.edit', compact('attendance', 'allocations'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'allocation_id' => 'required|exists:hostel_allocations,id',
            'date' => 'required|date',
            'status' => 'required|in:present,absent,late,leave',
            'check_in_time' => 'nullable|date_format:H:i',
            'check_out_time' => 'nullable|date_format:H:i|after:check_in_time',
            'remarks' => 'nullable|string',
        ]);

        $attendance = HostelAttendance::findOrFail($id);
        
        // Check if attendance already exists for this allocation and date (excluding current record)
        $existingAttendance = HostelAttendance::where('allocation_id', $request->allocation_id)
            ->whereDate('date', $request->date)
            ->where('id', '!=', $id)
            ->first();
        
        if ($existingAttendance) {
            return back()->withErrors(['allocation_id' => 'Attendance already exists for this allocation and date.']);
        }

        $attendance->update($request->all());
        
        return redirect()->route('admin.accommodation.attendance.index')
            ->with('success', 'Hostel attendance updated successfully.');
    }

    public function destroy($id)
    {
        $attendance = HostelAttendance::findOrFail($id);
        $attendance->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Hostel attendance deleted successfully.'
        ]);
    }

    public function dashboard()
    {
        $totalAttendance = HostelAttendance::count();
        $presentCount = HostelAttendance::where('status', 'present')->count();
        $absentCount = HostelAttendance::where('status', 'absent')->count();
        $lateCount = HostelAttendance::where('status', 'late')->count();
        $leaveCount = HostelAttendance::where('status', 'leave')->count();
        
        $attendanceByMonth = HostelAttendance::selectRaw('MONTH(date) as month, YEAR(date) as year, count(*) as total')
            ->groupBy('month', 'year')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get();
        
        $attendanceByHostel = HostelAttendance::with('allocation.hostel')
            ->selectRaw('allocation_id, count(*) as total')
            ->groupBy('allocation_id')
            ->get();
        
        return view('admin.accommodation.attendance.dashboard', compact(
            'totalAttendance', 'presentCount', 'absentCount', 'lateCount', 'leaveCount',
            'attendanceByMonth', 'attendanceByHostel'
        ));
    }

    public function export()
    {
        // Implementation for exporting attendance data
        return response()->json(['message' => 'Export functionality to be implemented']);
    }

    public function import(Request $request)
    {
        // Implementation for importing attendance data
        return response()->json(['message' => 'Import functionality to be implemented']);
    }

    public function bulkCreate()
    {
        $allocations = HostelAllocation::with(['student.user', 'hostel'])
            ->where('status', 'active')
            ->get();
        
        return view('admin.accommodation.attendance.bulk-create', compact('allocations'));
    }

    public function bulkStore(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'attendances' => 'required|array',
            'attendances.*.allocation_id' => 'required|exists:hostel_allocations,id',
            'attendances.*.status' => 'required|in:present,absent,late,leave',
            'attendances.*.check_in_time' => 'nullable|date_format:H:i',
            'attendances.*.check_out_time' => 'nullable|date_format:H:i',
            'attendances.*.remarks' => 'nullable|string',
        ]);

        $date = $request->date;
        $created = 0;
        $skipped = 0;

        foreach ($request->attendances as $attendanceData) {
            // Check if attendance already exists
            $existingAttendance = HostelAttendance::where('allocation_id', $attendanceData['allocation_id'])
                ->whereDate('date', $date)
                ->first();
            
            if ($existingAttendance) {
                $skipped++;
                continue;
            }

            $data = $attendanceData;
            $data['date'] = $date;
            $data['school_id'] = auth('admin')->user()->school_id ?? null;
            
            HostelAttendance::create($data);
            $created++;
        }

        return redirect()->route('admin.accommodation.attendance.index')
            ->with('success', "Bulk attendance created: {$created} records created, {$skipped} skipped (already exists).");
    }
}
