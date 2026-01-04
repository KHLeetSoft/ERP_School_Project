<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeaveManagement;
use App\Models\Staff;
use App\Exports\LeaveManagementExport;
use App\Imports\LeaveManagementImport;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;

class LeaveManagementController extends Controller
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
        $this->middleware('checkrole:admin');
    }
    
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = LeaveManagement::with(['staff', 'approvedBy', 'rejectedBy', 'createdBy'])
                ->where('school_id', auth()->user()->school_id);

            // Apply filters
            if ($request->filled('leave_type')) {
                $query->where('leave_type', $request->leave_type);
            }
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            if ($request->filled('staff_id')) {
                $query->where('staff_id', $request->staff_id);
            }
            if ($request->filled('date_from')) {
                $query->where('start_date', '>=', $request->date_from);
            }
            if ($request->filled('date_to')) {
                $query->where('end_date', '<=', $request->date_to);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('staff_name', function ($row) {
                    return $row->staff ? $row->staff->first_name . ' ' . $row->staff->last_name : 'N/A';
                })
                ->addColumn('leave_period', function ($row) {
                    return $row->leave_period;
                })
                ->addColumn('duration', function ($row) {
                    return $row->duration;
                })
                ->addColumn('status_badge', function ($row) {
                    return $row->status_badge;
                })
                ->addColumn('leave_type_label', function ($row) {
                    return $row->leave_type_label;
                })
                ->addColumn('actions', function ($row) {
                    return view('admin.hr.leave-management.partials.actions', compact('row'))->render();
                })
                ->rawColumns(['status_badge', 'actions'])
                ->make(true);
        }

        $staff = Staff::where('school_id', auth()->user()->school_id)
            ->where('status', 1)
            ->orderBy('first_name')
            ->get();

        $leaveTypes = [
            'casual' => 'Casual Leave',
            'sick' => 'Sick Leave',
            'annual' => 'Annual Leave',
            'maternity' => 'Maternity Leave',
            'paternity' => 'Paternity Leave',
            'bereavement' => 'Bereavement Leave',
            'study' => 'Study Leave',
            'other' => 'Other Leave'
        ];

        $statuses = ['pending', 'approved', 'rejected', 'cancelled'];

        return view('admin.hr.leave-management.index', compact('staff', 'leaveTypes', 'statuses'));
    }

    public function create()
    {
        $staff = Staff::where('school_id', auth()->user()->school_id)
            ->where('status', 1)
            ->orderBy('first_name')
            ->get();

        $leaveTypes = [
            'casual' => 'Casual Leave',
            'sick' => 'Sick Leave',
            'annual' => 'Annual Leave',
            'maternity' => 'Maternity Leave',
            'paternity' => 'Paternity Leave',
            'bereavement' => 'Bereavement Leave',
            'study' => 'Study Leave',
            'other' => 'Other Leave'
        ];

        return view('admin.hr.leave-management.create', compact('staff', 'leaveTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'staff_id' => 'required|exists:staff,id',
            'leave_type' => 'required|in:casual,sick,annual,maternity,paternity,bereavement,study,other',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:1000',
            'half_day' => 'boolean',
            'half_day_type' => 'required_if:half_day,true|in:morning,afternoon',
            'emergency_contact' => 'nullable|string|max:100',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'address_during_leave' => 'nullable|string|max:500'
        ]);

        // Check for overlapping leaves
        $overlappingLeave = LeaveManagement::where('staff_id', $request->staff_id)
            ->where('school_id', auth()->user()->school_id)
            ->where('status', '!=', 'rejected')
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                    ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('start_date', '<=', $request->start_date)
                          ->where('end_date', '>=', $request->end_date);
                    });
            })
            ->first();

        if ($overlappingLeave) {
            return back()->withErrors(['date_range' => 'Leave request overlaps with existing approved or pending leave.'])->withInput();
        }

        // Calculate total days
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $totalDays = $startDate->diffInDays($endDate) + 1;

        if ($request->half_day && $startDate->isSameDay($endDate)) {
            $totalDays = 0.5;
        }

        $leave = LeaveManagement::create([
            'school_id' => auth()->user()->school_id,
            'staff_id' => $request->staff_id,
            'leave_type' => $request->leave_type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'total_days' => $totalDays,
            'reason' => $request->reason,
            'half_day' => $request->half_day ?? false,
            'half_day_type' => $request->half_day_type,
            'emergency_contact' => $request->emergency_contact,
            'emergency_contact_phone' => $request->emergency_contact_phone,
            'address_during_leave' => $request->address_during_leave,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id()
        ]);

        return redirect()->route('admin.hr.leave-management.index')
            ->with('success', 'Leave request submitted successfully.');
    }

    public function show(LeaveManagement $leaveManagement)
    {
        $this->authorize('view', $leaveManagement);
        
        $leaveManagement->load(['staff', 'approvedBy', 'rejectedBy', 'createdBy', 'updatedBy']);
        
        return view('admin.hr.leave-management.show', compact('leaveManagement'));
    }

    public function edit(LeaveManagement $leaveManagement)
    {
        $this->authorize('update', $leaveManagement);
        
        $staff = Staff::where('school_id', auth()->user()->school_id)
            ->where('status', 1)
            ->orderBy('first_name')
            ->get();

        $leaveTypes = [
            'casual' => 'Casual Leave',
            'sick' => 'Sick Leave',
            'annual' => 'Annual Leave',
            'maternity' => 'Maternity Leave',
            'paternity' => 'Paternity Leave',
            'bereavement' => 'Bereavement Leave',
            'study' => 'Study Leave',
            'other' => 'Other Leave'
        ];

        return view('admin.hr.leave-management.edit', compact('leaveManagement', 'staff', 'leaveTypes'));
    }

    public function update(Request $request, LeaveManagement $leaveManagement)
    {
        $this->authorize('update', $leaveManagement);

        $request->validate([
            'leave_type' => 'required|in:casual,sick,annual,maternity,paternity,bereavement,study,other',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:1000',
            'half_day' => 'boolean',
            'half_day_type' => 'required_if:half_day,true|in:morning,afternoon',
            'emergency_contact' => 'nullable|string|max:100',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'address_during_leave' => 'nullable|string|max:500'
        ]);

        // Check for overlapping leaves (excluding current leave)
        $overlappingLeave = LeaveManagement::where('staff_id', $leaveManagement->staff_id)
            ->where('school_id', auth()->user()->school_id)
            ->where('id', '!=', $leaveManagement->id)
            ->where('status', '!=', 'rejected')
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                    ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('start_date', '<=', $request->start_date)
                          ->where('end_date', '>=', $request->end_date);
                    });
            })
            ->first();

        if ($overlappingLeave) {
            return back()->withErrors(['date_range' => 'Leave request overlaps with existing approved or pending leave.'])->withInput();
        }

        // Calculate total days
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $totalDays = $startDate->diffInDays($endDate) + 1;

        if ($request->half_day && $startDate->isSameDay($endDate)) {
            $totalDays = 0.5;
        }

        $leaveManagement->update([
            'leave_type' => $request->leave_type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'total_days' => $totalDays,
            'reason' => $request->reason,
            'half_day' => $request->half_day ?? false,
            'half_day_type' => $request->half_day_type,
            'emergency_contact' => $request->emergency_contact,
            'emergency_contact_phone' => $request->emergency_contact_phone,
            'address_during_leave' => $request->address_during_leave,
            'updated_by' => Auth::id()
        ]);

        return redirect()->route('admin.hr.leave-management.index')
            ->with('success', 'Leave request updated successfully.');
    }

    public function destroy(LeaveManagement $leaveManagement)
    {
        $this->authorize('delete', $leaveManagement);

        if ($leaveManagement->status === 'approved' && $leaveManagement->start_date <= now()) {
            return back()->withErrors(['delete' => 'Cannot delete approved leave that has already started.']);
        }

        $leaveManagement->delete();

        return redirect()->route('admin.hr.leave-management.index')
            ->with('success', 'Leave request deleted successfully.');
    }

    public function dashboard()
    {
        $schoolId = auth()->user()->school_id;
        $currentYear = now()->year;

        // Get staff data for the dashboard
        $staff = Staff::where('school_id', $schoolId)
            ->where('status', 1)
            ->orderBy('first_name')
            ->get();

        // KPI Statistics
        $totalLeaves = LeaveManagement::where('school_id', $schoolId)->count();
        $pendingLeaves = LeaveManagement::where('school_id', $schoolId)->where('status', 'pending')->count();
        $approvedLeaves = LeaveManagement::where('school_id', $schoolId)->where('status', 'approved')->count();
        $rejectedLeaves = LeaveManagement::where('school_id', $schoolId)->where('status', 'rejected')->count();
        $cancelledLeaves = LeaveManagement::where('school_id', $schoolId)->where('status', 'cancelled')->count();

        // Current and upcoming leaves
        $currentLeaves = LeaveManagement::where('school_id', $schoolId)->current()->count();
        $upcomingLeaves = LeaveManagement::where('school_id', $schoolId)->upcoming()->count();

        // Monthly trends
        $monthlyData = LeaveManagement::where('school_id', $schoolId)
            ->whereYear('start_date', $currentYear)
            ->selectRaw('MONTH(start_date) as month, COUNT(*) as count, SUM(total_days) as total_days')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $monthlyLabels = [];
        $monthlyCounts = [];
        $monthlyDays = [];

        for ($i = 1; $i <= 12; $i++) {
            $monthlyLabels[] = date('M', mktime(0, 0, 0, $i, 1));
            $monthData = $monthlyData->where('month', $i)->first();
            $monthlyCounts[] = $monthData ? $monthData->count : 0;
            $monthlyDays[] = $monthData ? round($monthData->total_days, 1) : 0;
        }

        // Leave type distribution
        $leaveTypeStats = LeaveManagement::where('school_id', $schoolId)
            ->selectRaw('leave_type, COUNT(*) as count')
            ->groupBy('leave_type')
            ->get();

        // Department-wise leave distribution
        $departmentStats = LeaveManagement::where('leave_management.school_id', $schoolId)
            ->join('staff', 'leave_management.staff_id', '=', 'staff.id')
            ->selectRaw('staff.department, COUNT(*) as count, SUM(leave_management.total_days) as total_days')
            ->groupBy('staff.department')
            ->get();

        // Recent leaves
        $recentLeaves = LeaveManagement::with(['staff'])
            ->where('school_id', $schoolId)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Staff with most leaves
        $topStaffLeaves = LeaveManagement::where('leave_management.school_id', $schoolId)
            ->join('staff', 'leave_management.staff_id', '=', 'staff.id')
            ->selectRaw('staff.first_name, staff.last_name, COUNT(*) as leave_count, SUM(leave_management.total_days) as total_days')
            ->groupBy('staff.id', 'staff.first_name', 'staff.last_name')
            ->orderBy('leave_count', 'desc')
            ->limit(5)
            ->get();

        return view('admin.hr.leave-management.dashboard', compact(
            'staff', 'totalLeaves', 'pendingLeaves', 'approvedLeaves', 'rejectedLeaves', 'cancelledLeaves',
            'currentLeaves', 'upcomingLeaves', 'monthlyLabels', 'monthlyCounts', 'monthlyDays',
            'leaveTypeStats', 'departmentStats', 'recentLeaves', 'topStaffLeaves'
        ));
    }

    public function export(Request $request)
    {
        $filters = $request->only(['leave_type', 'status', 'staff_id', 'date_from', 'date_to']);
        
        return Excel::download(
            new LeaveManagementExport(auth()->user()->school_id, $filters),
            'leave_management_' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:2048'
        ]);

        try {
            $import = new LeaveManagementImport(auth()->user()->school_id);
            Excel::import($import, $request->file('file'));

            $errors = $import->getErrors();
            if (!empty($errors)) {
                return back()->withErrors(['import' => $errors]);
            }

            return back()->with('success', 'Leave data imported successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['import' => 'Import failed: ' . $e->getMessage()]);
        }
    }

    public function toggleStatus(Request $request, LeaveManagement $leaveManagement)
    {
        $this->authorize('update', $leaveManagement);

        $request->validate([
            'status' => 'required|in:approved,rejected,cancelled',
            'rejection_reason' => 'required_if:status,rejected|nullable|string|max:500'
        ]);

        $status = $request->status;
        $userId = Auth::id();

        switch ($status) {
            case 'approved':
                if ($leaveManagement->approve($userId)) {
                    $message = 'Leave request approved successfully.';
                } else {
                    return back()->withErrors(['status' => 'Leave request cannot be approved.']);
                }
                break;

            case 'rejected':
                if ($leaveManagement->reject($userId, $request->rejection_reason)) {
                    $message = 'Leave request rejected successfully.';
                } else {
                    return back()->withErrors(['status' => 'Leave request cannot be rejected.']);
                }
                break;

            case 'cancelled':
                if ($leaveManagement->cancel($userId)) {
                    $message = 'Leave request cancelled successfully.';
                } else {
                    return back()->withErrors(['status' => 'Leave request cannot be cancelled.']);
                }
                break;

            default:
                return back()->withErrors(['status' => 'Invalid status.']);
        }

        return back()->with('success', $message);
    }

    public function getByStaff(Staff $staff)
    {
        $leaves = LeaveManagement::where('school_id', auth()->user()->school_id)
            ->where('staff_id', $staff->id)
            ->with(['approvedBy', 'rejectedBy'])
            ->orderBy('start_date', 'desc')
            ->get();

        return response()->json($leaves);
    }

    public function getByPeriod(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date'
        ]);

        $leaves = LeaveManagement::where('school_id', auth()->user()->school_id)
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                    ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('start_date', '<=', $request->start_date)
                          ->where('end_date', '>=', $request->end_date);
                    });
            })
            ->with(['staff', 'approvedBy', 'rejectedBy'])
            ->orderBy('start_date')
            ->get();

        return response()->json($leaves);
    }

    public function calendar(Request $request)
    {
        // If it's an AJAX request, return JSON data for the calendar
        if ($request->ajax()) {
            $schoolId = auth()->user()->school_id;
            $year = $request->get('year', now()->year);
            $month = $request->get('month', now()->month);

            $leaves = LeaveManagement::where('school_id', $schoolId)
                ->whereYear('start_date', $year)
                ->whereMonth('start_date', $month)
                ->with(['staff'])
                ->get();

            $calendarData = [];
            foreach ($leaves as $leave) {
                $startDate = Carbon::parse($leave->start_date);
                $endDate = Carbon::parse($leave->end_date);
                
                $currentDate = $startDate->copy();
                while ($currentDate->lte($endDate)) {
                    $day = $currentDate->day;
                    if (!isset($calendarData[$day])) {
                        $calendarData[$day] = [];
                    }
                    
                    $calendarData[$day][] = [
                        'id' => $leave->id,
                        'staff_name' => $leave->staff ? ($leave->staff->first_name . ' ' . $leave->staff->last_name) : 'Unknown Staff',
                        'leave_type' => $leave->leave_type_label ?? ucfirst($leave->leave_type ?? 'Unknown'),
                        'status' => $leave->status,
                        'half_day' => $leave->half_day,
                        'half_day_type' => $leave->half_day_type
                    ];
                    
                    $currentDate->addDay();
                }
            }

            return response()->json($calendarData);
        }

        // For regular requests, return the calendar view
        return view('admin.hr.leave-management.calendar');
    }

    public function getData()
    {
        $leaves = LeaveManagement::with('staff')
            ->select('id', 'staff_id', 'leave_type', 'status', 'start_date')
            ->get()
            ->map(function ($leave) {
                return [
                    'id' => $leave->id,
                    'staff_name' => $leave->staff->name,
                    'leave_type' => $leave->leave_type,
                    'status' => $leave->status,
                    'start_date' => $leave->start_date->format('Y-m-d'),
                ];
            });

        return response()->json($leaves);
    }
}
