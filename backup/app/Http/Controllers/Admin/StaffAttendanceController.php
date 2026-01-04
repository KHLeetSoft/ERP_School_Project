<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StaffAttendance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StaffAttendanceExport;
use App\Imports\StaffAttendanceImport;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;

class StaffAttendanceController extends Controller
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
            $query = StaffAttendance::with(['staff'])
                ->where('school_id', auth()->user()->school_id ?? 1);

            if ($request->month) {
                $month = Carbon::parse($request->month)->month;
                $query->whereMonth('attendance_date', $month);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('staff_name', fn($row) => optional($row->staff)->name)
                ->editColumn('attendance_date', fn($row) => Carbon::parse($row->attendance_date)->format('d-m-Y'))
                ->addColumn('actions', function ($row) {
                    $buttons = '<div class="d-flex">';
                    $buttons .= '<a href="' . route('admin.attendance.staff.show', $row->id) . '" class="text-info me-2" title="View"><i class="bx bx-show"></i></a>';
                    $buttons .= '<a href="' . route('admin.attendance.staff.edit', $row->id) . '" class="text-primary me-2" title="Edit"><i class="bx bxs-edit"></i></a>';
                    $buttons .= '<a href="javascript:void(0);" data-id="' . $row->id . '" class="text-danger delete-staff-attendance" title="Delete"><i class="bx bx-trash"></i></a>';
                    $buttons .= '</div>';
                    return $buttons;
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        $staff = User::where('school_id', auth()->user()->school_id ?? 1)->get(['id','name']);
        return view('admin.attendance.staff.index', compact('staff'));
    }

    public function create()
    {
        $staff = User::where('school_id', auth()->user()->school_id ?? 1)->get(['id','name']);
        return view('admin.attendance.staff.create', compact('staff'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'attendance_date' => 'required|date',
            'status' => 'required|in:present,absent,late,half_day,leave',
            'remarks' => 'nullable|string',
        ]);

        StaffAttendance::create([
            'school_id' => auth()->user()->school_id ?? 1,
            'user_id' => $request->user_id,
            'attendance_date' => $request->attendance_date,
            'status' => $request->status,
            'remarks' => $request->remarks,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('admin.attendance.staff.index')->with('success', 'Attendance recorded.');
    }

    public function edit(StaffAttendance $staffAttendance)
    {
        $this->authorizeSchool($staffAttendance);
        $staff = User::where('school_id', auth()->user()->school_id ?? 1)->get(['id','name']);
        return view('admin.attendance.staff.edit', [
            'attendance' => $staffAttendance,
            'staff' => $staff,
        ]);
    }

    public function update(Request $request, StaffAttendance $staffAttendance)
    {
        $this->authorizeSchool($staffAttendance);
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'attendance_date' => 'required|date',
            'status' => 'required|in:present,absent,late,half_day,leave',
            'remarks' => 'nullable|string',
        ]);

        $staffAttendance->update([
            'user_id' => $request->user_id,
            'attendance_date' => $request->attendance_date,
            'status' => $request->status,
            'remarks' => $request->remarks,
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('admin.attendance.staff.index')->with('success', 'Attendance updated.');
    }

    public function destroy(StaffAttendance $staffAttendance)
    {
        $this->authorizeSchool($staffAttendance);
        $staffAttendance->delete();
        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('admin.attendance.staff.index')->with('success', 'Attendance deleted.');
    }

    public function show(StaffAttendance $staffAttendance)
    {
        $this->authorizeSchool($staffAttendance);
        return view('admin.attendance.staff.show', ['attendance' => $staffAttendance]);
    }

    public function export(Request $request)
    {
        $schoolId = auth()->user()->school_id ?? 1;
        $month = $request->get('month');
        return Excel::download(new StaffAttendanceExport($schoolId, $month), 'staff_attendance.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv',
        ]);
        $schoolId = auth()->user()->school_id ?? 1;
        Excel::import(new StaffAttendanceImport($schoolId), $request->file('file'));
        return back()->with('success', 'Staff attendance imported successfully.');
    }

    public function dashboard()
    {
        $schoolId = auth()->user()->school_id ?? 1;
        $today = Carbon::today()->toDateString();
        $todayCounts = [
            'present' => StaffAttendance::where('school_id', $schoolId)->where('attendance_date', $today)->where('status', 'present')->count(),
            'absent' => StaffAttendance::where('school_id', $schoolId)->where('attendance_date', $today)->where('status', 'absent')->count(),
            'late' => StaffAttendance::where('school_id', $schoolId)->where('attendance_date', $today)->where('status', 'late')->count(),
            'half_day' => StaffAttendance::where('school_id', $schoolId)->where('attendance_date', $today)->where('status', 'half_day')->count(),
            'leave' => StaffAttendance::where('school_id', $schoolId)->where('attendance_date', $today)->where('status', 'leave')->count(),
        ];

        $days = collect(range(6,0))->map(function($i){ return Carbon::today()->subDays($i)->format('Y-m-d'); });
        $series = [
            'present' => [], 'absent' => [], 'late' => [], 'half_day' => [], 'leave' => []
        ];
        foreach ($days as $d) {
            foreach (array_keys($series) as $status) {
                $series[$status][] = StaffAttendance::where('school_id', $schoolId)
                    ->where('attendance_date', $d)
                    ->where('status', $status)
                    ->count();
            }
        }

        // Attendance rate (present / total) for each day (percentage)
        $attendanceRateSeries = [];
        foreach ($days as $d) {
            $present = StaffAttendance::where('school_id', $schoolId)->where('attendance_date', $d)->where('status', 'present')->count();
            $total = StaffAttendance::where('school_id', $schoolId)->where('attendance_date', $d)->count();
            $attendanceRateSeries[] = $total > 0 ? round(($present / max(1, $total)) * 100, 1) : 0;
        }

        // Top 5 absentees over last 30 days
        $fromDate = Carbon::today()->subDays(29)->toDateString();
        $topAbsent = StaffAttendance::with('staff:id,name')
            ->where('school_id', $schoolId)
            ->whereBetween('attendance_date', [$fromDate, $today])
            ->where('status', 'absent')
            ->selectRaw('user_id, COUNT(*) as cnt')
            ->groupBy('user_id')
            ->orderByDesc('cnt')
            ->limit(5)
            ->get();

        $topAbsentLabels = $topAbsent->map(fn($r) => optional($r->staff)->name ?: ('User #'.$r->user_id));
        $topAbsentValues = $topAbsent->pluck('cnt');

        return view('admin.attendance.staff.dashboard', [
            'todayCounts' => $todayCounts,
            'days' => $days,
            'series' => $series,
            'attendanceRateSeries' => $attendanceRateSeries,
            'topAbsentLabels' => $topAbsentLabels,
            'topAbsentValues' => $topAbsentValues,
        ]);
    }

    private function authorizeSchool(StaffAttendance $attendance): void
    {
        if (($attendance->school_id ?? null) !== (auth()->user()->school_id ?? 1)) {
            abort(403);
        }
    }
}


