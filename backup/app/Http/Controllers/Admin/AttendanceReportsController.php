<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AttendanceReportsExport;

class AttendanceReportsController extends Controller
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
        $schoolId = auth()->user()->school_id ?? 1;

        $start = $request->get('start_date') ? Carbon::parse($request->get('start_date'))->startOfDay() : Carbon::today()->subDays(6)->startOfDay();
        $end = $request->get('end_date') ? Carbon::parse($request->get('end_date'))->endOfDay() : Carbon::today()->endOfDay();
        $type = $request->get('type', 'both'); // staff|students|both

        $days = collect(Carbon::parse($start)->toPeriod($end, '1 day'))->map(fn($d) => Carbon::parse($d->format('Y-m-d')));

        $data = [];
        foreach ($days as $d) {
            $date = $d->toDateString();

            $row = ['date' => $date];
            if (in_array($type, ['both','staff'])) {
                $row['staff_present'] = DB::table('staff_attendances')->where('school_id', $schoolId)->where('attendance_date', $date)->where('status','present')->count();
                $row['staff_absent'] = DB::table('staff_attendances')->where('school_id', $schoolId)->where('attendance_date', $date)->where('status','absent')->count();
            }
            if (in_array($type, ['both','students'])) {
                $row['student_present'] = DB::table('attendances')->where('school_id', $schoolId)->where('attendance_date', $date)->where('status','present')->count();
                $row['student_absent'] = DB::table('attendances')->where('school_id', $schoolId)->where('attendance_date', $date)->where('status','absent')->count();
            }
            $data[] = $row;
        }

        return view('admin.attendance.reports.index', [
            'start' => $start->toDateString(),
            'end' => $end->toDateString(),
            'type' => $type,
            'rows' => $data,
        ]);
    }

    public function export(Request $request)
    {
        $schoolId = auth()->user()->school_id ?? 1;
        $start = $request->get('start_date');
        $end = $request->get('end_date');
        $type = $request->get('type', 'both');
        return Excel::download(new AttendanceReportsExport($schoolId, $start, $end, $type), 'attendance_report.xlsx');
    }

    public function dashboard(Request $request)
    {
        $schoolId = auth()->user()->school_id ?? 1;
        $start = $request->get('start_date') ? Carbon::parse($request->get('start_date'))->startOfDay() : Carbon::today()->subDays(6)->startOfDay();
        $end = $request->get('end_date') ? Carbon::parse($request->get('end_date'))->endOfDay() : Carbon::today()->endOfDay();

        $days = collect(Carbon::parse($start)->toPeriod($end, '1 day'))->map(fn($d) => Carbon::parse($d->format('Y-m-d')));

        $labels = [];
        $staffPresent = [];
        $studentPresent = [];
        $staffAbsent = [];
        $studentAbsent = [];
        $staffRate = [];
        $studentRate = [];
        foreach ($days as $d) {
            $date = $d->toDateString();
            $labels[] = $d->format('d M');
            $staffPresent[] = DB::table('staff_attendances')->where('school_id', $schoolId)->where('attendance_date', $date)->where('status','present')->count();
            $studentPresent[] = DB::table('attendances')->where('school_id', $schoolId)->where('attendance_date', $date)->where('status','present')->count();
            $staffAbsent[] = DB::table('staff_attendances')->where('school_id', $schoolId)->where('attendance_date', $date)->where('status','absent')->count();
            $studentAbsent[] = DB::table('attendances')->where('school_id', $schoolId)->where('attendance_date', $date)->where('status','absent')->count();
            $staffTotal = DB::table('staff_attendances')->where('school_id', $schoolId)->where('attendance_date', $date)->count();
            $studentTotal = DB::table('attendances')->where('school_id', $schoolId)->where('attendance_date', $date)->count();
            $staffRate[] = $staffTotal > 0 ? round(($staffPresent[count($staffPresent)-1] / max(1, $staffTotal)) * 100, 1) : 0;
            $studentRate[] = $studentTotal > 0 ? round(($studentPresent[count($studentPresent)-1] / max(1, $studentTotal)) * 100, 1) : 0;
        }

        // KPIs
        $totals = [
            'staff_present' => array_sum($staffPresent),
            'staff_absent' => array_sum($staffAbsent),
            'student_present' => array_sum($studentPresent),
            'student_absent' => array_sum($studentAbsent),
        ];

        // Last day splits (for donut)
        $lastCarbon = $days->last();
        $lastDate = $lastCarbon instanceof \Carbon\Carbon ? $lastCarbon->toDateString() : Carbon::today()->toDateString();
        $lastStaffPresent = DB::table('staff_attendances')->where('school_id', $schoolId)->where('attendance_date', $lastDate)->where('status','present')->count();
        $lastStaffAbsent = DB::table('staff_attendances')->where('school_id', $schoolId)->where('attendance_date', $lastDate)->where('status','absent')->count();
        $lastStudentPresent = DB::table('attendances')->where('school_id', $schoolId)->where('attendance_date', $lastDate)->where('status','present')->count();
        $lastStudentAbsent = DB::table('attendances')->where('school_id', $schoolId)->where('attendance_date', $lastDate)->where('status','absent')->count();

        return view('admin.attendance.reports.dashboard', compact(
            'labels','staffPresent','studentPresent','staffAbsent','studentAbsent','staffRate','studentRate','totals','start','end',
            'lastStaffPresent','lastStaffAbsent','lastStudentPresent','lastStudentAbsent'
        ));
    }
}


