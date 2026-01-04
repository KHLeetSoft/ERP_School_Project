<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Schedule;
use App\Models\StaffAttendance;
use App\Models\Student;
use App\Models\TeacherClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!Auth::check()) {
                return redirect()->route('teacher.login')->with('error', 'Please login to access this page.');
            }

            $user = Auth::user();
            if (!$user->userRole || $user->userRole->name !== 'Teacher') {
                Auth::logout();
                return redirect()->route('teacher.login')->with('error', 'Access denied. Teacher role required.');
            }

            if (!$user->status) {
                Auth::logout();
                return redirect()->route('teacher.login')->with('error', 'Your account is inactive. Please contact administrator.');
            }

            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teacherId = Auth::id();
        
        // Get overview statistics
        $overviewStats = [
            'total_classes' => TeacherClass::where('teacher_id', $teacherId)->count(),
            'total_students' => Student::whereIn('class_name', TeacherClass::where('teacher_id', $teacherId)->pluck('class_name'))->count(),
            'total_grades' => Grade::byTeacher($teacherId)->count(),
            'attendance_rate' => $this->getAttendanceRate($teacherId),
            'average_grade' => Grade::byTeacher($teacherId)->avg('percentage') ?? 0,
            'total_schedules' => Schedule::byTeacher($teacherId)->active()->count(),
        ];

        // Get recent activity
        $recentGrades = Grade::with(['student'])
            ->byTeacher($teacherId)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $recentAttendance = StaffAttendance::where('user_id', $teacherId)
            ->orderBy('attendance_date', 'desc')
            ->take(5)
            ->get();

        return view('teacher.reports.index', compact('overviewStats', 'recentGrades', 'recentAttendance'));
    }

    /**
     * Grade Reports
     */
    public function grades(Request $request)
    {
        $teacherId = Auth::id();
        $teacherClasses = TeacherClass::where('teacher_id', $teacherId)->get();
        
        $query = Grade::with(['student'])->byTeacher($teacherId);

        // Apply filters
        if ($request->filled('class_name')) {
            $query->where('class_name', $request->class_name);
        }

        if ($request->filled('subject_name')) {
            $query->where('subject_name', $request->subject_name);
        }

        if ($request->filled('assignment_type')) {
            $query->where('assignment_type', $request->assignment_type);
        }

        if ($request->filled('date_from')) {
            $query->where('graded_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('graded_date', '<=', $request->date_to);
        }

        $grades = $query->orderBy('graded_date', 'desc')->paginate(20);

        // Grade distribution
        $gradeDistribution = Grade::byTeacher($teacherId)
            ->selectRaw('letter_grade, COUNT(*) as count')
            ->groupBy('letter_grade')
            ->pluck('count', 'letter_grade');

        // Class performance
        $classPerformance = Grade::byTeacher($teacherId)
            ->selectRaw('class_name, AVG(percentage) as average_grade, COUNT(*) as total_grades')
            ->groupBy('class_name')
            ->get();

        return view('teacher.reports.grades', compact('grades', 'teacherClasses', 'gradeDistribution', 'classPerformance'));
    }

    /**
     * Attendance Reports
     */
    public function attendance(Request $request)
    {
        $teacherId = Auth::id();
        
        $query = StaffAttendance::where('user_id', $teacherId);

        // Apply filters
        if ($request->filled('date_from')) {
            $query->where('attendance_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('attendance_date', '<=', $request->date_to);
        }

        $attendance = $query->orderBy('attendance_date', 'desc')->paginate(20);

        // Attendance statistics
        $attendanceStats = [
            'total_days' => StaffAttendance::where('user_id', $teacherId)->count(),
            'present_days' => StaffAttendance::where('user_id', $teacherId)->where('status', 'present')->count(),
            'absent_days' => StaffAttendance::where('user_id', $teacherId)->where('status', 'absent')->count(),
            'late_days' => StaffAttendance::where('user_id', $teacherId)->where('status', 'late')->count(),
        ];

        // Monthly attendance trend
        $monthlyTrend = StaffAttendance::where('user_id', $teacherId)
            ->selectRaw('DATE_FORMAT(attendance_date, "%Y-%m") as month, 
                        SUM(CASE WHEN status = "present" THEN 1 ELSE 0 END) as present,
                        SUM(CASE WHEN status = "absent" THEN 1 ELSE 0 END) as absent,
                        SUM(CASE WHEN status = "late" THEN 1 ELSE 0 END) as late')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('teacher.reports.attendance', compact('attendance', 'attendanceStats', 'monthlyTrend'));
    }

    /**
     * Schedule Reports
     */
    public function schedule(Request $request)
    {
        $teacherId = Auth::id();
        $teacherClasses = TeacherClass::where('teacher_id', $teacherId)->get();
        
        $query = Schedule::byTeacher($teacherId);

        // Apply filters
        if ($request->filled('class_name')) {
            $query->where('class_name', $request->class_name);
        }

        if ($request->filled('subject_name')) {
            $query->where('subject_name', $request->subject_name);
        }

        if ($request->filled('day_of_week')) {
            $query->where('day_of_week', $request->day_of_week);
        }

        $schedules = $query->orderBy('day_of_week')->orderBy('start_time')->get();

        // Schedule statistics
        $scheduleStats = [
            'total_periods' => Schedule::byTeacher($teacherId)->count(),
            'active_periods' => Schedule::byTeacher($teacherId)->active()->count(),
            'weekly_hours' => $this->getWeeklyHours($teacherId),
            'most_busy_day' => $this->getMostBusyDay($teacherId),
        ];

        // Weekly schedule breakdown
        $weeklyBreakdown = Schedule::byTeacher($teacherId)
            ->active()
            ->selectRaw('day_of_week, COUNT(*) as periods, 
                        SUM(TIMESTAMPDIFF(MINUTE, start_time, end_time)) as total_minutes')
            ->groupBy('day_of_week')
            ->get();

        return view('teacher.reports.schedule', compact('schedules', 'teacherClasses', 'scheduleStats', 'weeklyBreakdown'));
    }

    /**
     * Student Performance Report
     */
    public function studentPerformance(Request $request)
    {
        $teacherId = Auth::id();
        $teacherClasses = TeacherClass::where('teacher_id', $teacherId)->get();
        
        $className = $request->get('class_name');
        $studentId = $request->get('student_id');

        $query = Grade::with(['student'])->byTeacher($teacherId);

        if ($className) {
            $query->where('class_name', $className);
        }

        if ($studentId) {
            $query->where('student_id', $studentId);
        }

        $grades = $query->orderBy('student_id')->orderBy('graded_date', 'desc')->get();

        // Group by student
        $studentPerformance = $grades->groupBy('student_id')->map(function ($studentGrades) {
            $student = $studentGrades->first()->student;
            return [
                'student' => $student,
                'grades' => $studentGrades,
                'average_grade' => $studentGrades->avg('percentage'),
                'total_assignments' => $studentGrades->count(),
                'grade_distribution' => $studentGrades->groupBy('letter_grade')->map->count(),
            ];
        });

        return view('teacher.reports.student-performance', compact('studentPerformance', 'teacherClasses'));
    }

    /**
     * Export Reports
     */
    public function export(Request $request)
    {
        $type = $request->get('type', 'grades');
        $format = $request->get('format', 'pdf');
        
        // This would implement actual export functionality
        // For now, return a placeholder response
        return response()->json([
            'message' => "Export functionality for {$type} in {$format} format would be implemented here"
        ]);
    }

    /**
     * Get attendance rate
     */
    private function getAttendanceRate($teacherId)
    {
        $totalDays = StaffAttendance::where('user_id', $teacherId)->count();
        $presentDays = StaffAttendance::where('user_id', $teacherId)->where('status', 'present')->count();
        
        if ($totalDays == 0) return 0;
        
        return round(($presentDays / $totalDays) * 100, 2);
    }

    /**
     * Get weekly hours
     */
    private function getWeeklyHours($teacherId)
    {
        $totalMinutes = Schedule::byTeacher($teacherId)
            ->active()
            ->get()
            ->sum(function ($schedule) {
                return $schedule->getDuration();
            });

        return round($totalMinutes / 60, 2);
    }

    /**
     * Get most busy day
     */
    private function getMostBusyDay($teacherId)
    {
        $busyDay = Schedule::byTeacher($teacherId)
            ->active()
            ->selectRaw('day_of_week, COUNT(*) as periods')
            ->groupBy('day_of_week')
            ->orderBy('periods', 'desc')
            ->first();

        return $busyDay ? ucfirst($busyDay->day_of_week) : 'None';
    }
}
