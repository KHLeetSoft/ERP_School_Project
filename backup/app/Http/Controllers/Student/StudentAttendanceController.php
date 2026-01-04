<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\StudentDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StudentAttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Check if user has student role
        if (!$user->userRole || $user->userRole->name !== 'Student') {
            Auth::logout();
            return redirect()->route('student.login')->with('error', 'Access denied. Student role required.');
        }
        
        $student = $user->student;
        
        // Share student data with all views
        view()->share('student', $student);
        view()->share('studentUser', $user);

        // Get current month and year
        $currentMonth = $request->get('month', Carbon::now()->format('Y-m'));
        $selectedDate = Carbon::parse($currentMonth);
        
        // Get student's attendance for the selected month
        $attendances = $this->getStudentAttendance($student, $selectedDate);
        
        // Calculate attendance statistics
        $stats = $this->calculateAttendanceStats($attendances, $selectedDate);
        
        // Get attendance calendar data
        $calendarData = $this->getAttendanceCalendar($attendances, $selectedDate);
        
        return view('student.attendance.index', compact(
            'attendances', 
            'stats', 
            'calendarData', 
            'currentMonth',
            'selectedDate'
        ));
    }

    public function show(Request $request)
    {
        $user = Auth::user();
        
        // Check if user has student role
        if (!$user->userRole || $user->userRole->name !== 'Student') {
            Auth::logout();
            return redirect()->route('student.login')->with('error', 'Access denied. Student role required.');
        }
        
        $student = $user->student;
        
        // Share student data with all views
        view()->share('student', $student);
        view()->share('studentUser', $user);

        $date = $request->get('date', Carbon::now()->format('Y-m-d'));
        $selectedDate = Carbon::parse($date);
        
        // Get attendance for specific date
        $attendance = $this->getAttendanceForDate($student, $selectedDate);
        
        return view('student.attendance.show', compact('attendance', 'selectedDate'));
    }

    public function calendar(Request $request)
    {
        $user = Auth::user();
        
        // Check if user has student role
        if (!$user->userRole || $user->userRole->name !== 'Student') {
            Auth::logout();
            return redirect()->route('student.login')->with('error', 'Access denied. Student role required.');
        }
        
        $student = $user->student;
        
        // Share student data with all views
        view()->share('student', $student);
        view()->share('studentUser', $user);

        $year = $request->get('year', Carbon::now()->year);
        $month = $request->get('month', Carbon::now()->month);
        
        // Get attendance for the entire year
        $yearlyAttendance = $this->getYearlyAttendance($student, $year);
        
        return view('student.attendance.calendar', compact('yearlyAttendance', 'year', 'month'));
    }

    public function report(Request $request)
    {
        $user = Auth::user();
        
        // Check if user has student role
        if (!$user->userRole || $user->userRole->name !== 'Student') {
            Auth::logout();
            return redirect()->route('student.login')->with('error', 'Access denied. Student role required.');
        }
        
        $student = $user->student;
        
        // Share student data with all views
        view()->share('student', $student);
        view()->share('studentUser', $user);

        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        
        // Get attendance report for the date range
        $report = $this->generateAttendanceReport($student, $start, $end);
        
        return view('student.attendance.report', compact('report', 'startDate', 'endDate'));
    }

    private function getStudentAttendance($student, $selectedDate)
    {
        if (!$student) {
            return collect();
        }

        // Get attendance records for the student
        // This assumes the attendance table has student_id, date, and status columns
        $startOfMonth = $selectedDate->copy()->startOfMonth();
        $endOfMonth = $selectedDate->copy()->endOfMonth();
        
        // Try to get from StudentDetail first (if using student_details table)
        if ($student->user_id) {
            $studentDetail = StudentDetail::where('user_id', $student->user_id)->first();
            if ($studentDetail) {
                return Attendance::where('student_id', $studentDetail->id)
                    ->whereBetween('date', [$startOfMonth, $endOfMonth])
                    ->orderBy('date', 'desc')
                    ->get();
            }
        }
        
        // Fallback: return empty collection if no student detail found
        return collect();
    }

    private function getAttendanceForDate($student, $date)
    {
        if (!$student) {
            return null;
        }

        $studentDetail = StudentDetail::where('user_id', $student->user_id)->first();
        if ($studentDetail) {
            return Attendance::where('student_id', $studentDetail->id)
                ->whereDate('date', $date)
                ->first();
        }
        
        return null;
    }

    private function getYearlyAttendance($student, $year)
    {
        if (!$student) {
            return collect();
        }

        $studentDetail = StudentDetail::where('user_id', $student->user_id)->first();
        if ($studentDetail) {
            return Attendance::where('student_id', $studentDetail->id)
                ->whereYear('date', $year)
                ->get()
                ->groupBy(function ($item) {
                    return Carbon::parse($item->date)->format('Y-m');
                });
        }
        
        return collect();
    }

    private function calculateAttendanceStats($attendances, $selectedDate)
    {
        $totalDays = $selectedDate->daysInMonth;
        $presentDays = $attendances->where('status', 'present')->count();
        $absentDays = $attendances->where('status', 'absent')->count();
        $lateDays = $attendances->where('status', 'late')->count();
        $leaveDays = $attendances->where('status', 'leave')->count();
        
        $attendancePercentage = $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 2) : 0;
        
        return [
            'total_days' => $totalDays,
            'present_days' => $presentDays,
            'absent_days' => $absentDays,
            'late_days' => $lateDays,
            'leave_days' => $leaveDays,
            'attendance_percentage' => $attendancePercentage,
            'working_days' => $presentDays + $absentDays + $lateDays + $leaveDays,
        ];
    }

    private function getAttendanceCalendar($attendances, $selectedDate)
    {
        $calendar = [];
        $startOfMonth = $selectedDate->copy()->startOfMonth();
        $endOfMonth = $selectedDate->copy()->endOfMonth();
        
        // Create calendar grid
        $current = $startOfMonth->copy()->startOfWeek();
        $end = $endOfMonth->copy()->endOfWeek();
        
        while ($current->lte($end)) {
            $week = [];
            for ($i = 0; $i < 7; $i++) {
                $attendance = $attendances->where('date', $current->format('Y-m-d'))->first();
                $week[] = [
                    'date' => $current->copy(),
                    'attendance' => $attendance,
                    'is_current_month' => $current->month === $selectedDate->month,
                ];
                $current->addDay();
            }
            $calendar[] = $week;
        }
        
        return $calendar;
    }

    private function generateAttendanceReport($student, $startDate, $endDate)
    {
        $attendances = $this->getStudentAttendance($student, $startDate);
        
        $report = [
            'period' => $startDate->format('M d, Y') . ' - ' . $endDate->format('M d, Y'),
            'total_days' => $startDate->diffInDays($endDate) + 1,
            'present_days' => $attendances->where('status', 'present')->count(),
            'absent_days' => $attendances->where('status', 'absent')->count(),
            'late_days' => $attendances->where('status', 'late')->count(),
            'leave_days' => $attendances->where('status', 'leave')->count(),
            'attendance_records' => $attendances->sortBy('date'),
        ];
        
        $report['attendance_percentage'] = $report['total_days'] > 0 
            ? round(($report['present_days'] / $report['total_days']) * 100, 2) 
            : 0;
        
        return $report;
    }
}
