<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\StaffAttendance;
use App\Models\Student; 
use App\Models\TeacherClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
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
        $teacherClasses = TeacherClass::where('teacher_id', $teacherId)->get();
        
        // Get today's attendance records
        $today = Carbon::today();
        $attendanceRecords = StaffAttendance::where('user_id', $teacherId)
            ->whereDate('attendance_date', $today)
            ->with(['staff'])
            ->get();

        $attendanceStats = [
            'total_records' => $attendanceRecords->count(),
            'present_today' => $attendanceRecords->where('status', 'present')->count(),
            'absent_today' => $attendanceRecords->where('status', 'absent')->count(),
            'late_today' => $attendanceRecords->where('status', 'late')->count(),
        ];

        return view('teacher.attendance.index', compact('teacherClasses', 'attendanceRecords', 'attendanceStats', 'today'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $teacherId = Auth::id();
        $teacherClasses = TeacherClass::where('teacher_id', $teacherId)->get();
        $today = Carbon::today();
        
        return view('teacher.attendance.create', compact('teacherClasses', 'today'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:teacher_classes,id',
            'attendance_date' => 'required|date',
            'students' => 'required|array',
            'students.*.student_id' => 'required|exists:students,id',
            'students.*.status' => 'required|in:present,absent,late',
            'students.*.notes' => 'nullable|string|max:255',
        ]);

        $teacherId = Auth::id();
        $teacherClass = TeacherClass::findOrFail($request->class_id);
        
        // Check if attendance already exists for this date
        $existingAttendance = StaffAttendance::where('user_id', $teacherId)
            ->whereDate('attendance_date', $request->attendance_date)
            ->exists();

        if ($existingAttendance) {
            return redirect()->back()
                ->with('error', 'Attendance for this date already exists. Please edit the existing record.');
        }

        // Create staff attendance record
        StaffAttendance::create([
            'school_id' => 1, // Default school ID
            'user_id' => $teacherId,
            'attendance_date' => $request->attendance_date,
            'status' => $request->status ?? 'present',
            'remarks' => $request->remarks ?? null,
            'created_by' => $teacherId,
        ]);

        return redirect()->route('teacher.attendance.index')
            ->with('success', 'Attendance recorded successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(StaffAttendance $attendance)
    {
        $attendance->load(['staff']);
        
        return view('teacher.attendance.show', compact('attendance'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StaffAttendance $attendance)
    {
        $teacherId = Auth::id();
        $teacherClasses = TeacherClass::where('teacher_id', $teacherId)->get();
        
        return view('teacher.attendance.edit', compact('attendance', 'teacherClasses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StaffAttendance $attendance)
    {
        $request->validate([
            'status' => 'required|in:present,absent,late',
            'remarks' => 'nullable|string|max:255',
        ]);

        $attendance->update([
            'status' => $request->status,
            'remarks' => $request->remarks,
        ]);

        return redirect()->route('teacher.attendance.index')
            ->with('success', 'Attendance updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StaffAttendance $attendance)
    {
        $attendance->delete();

        return redirect()->route('teacher.attendance.index')
            ->with('success', 'Attendance record deleted successfully!');
    }

    /**
     * Get students for a specific class
     */
    public function getStudentsByClass(Request $request)
    {
        $classId = $request->get('class_id');
        $teacherId = Auth::id();
        
        $teacherClass = TeacherClass::where('teacher_id', $teacherId)
            ->where('id', $classId)
            ->first();
            
        if (!$teacherClass) {
            return response()->json(['error' => 'Class not found'], 404);
        }
        
        $students = Student::where('class_name', $teacherClass->class_name)
            ->with(['user'])
            ->orderBy('first_name')
            ->get();

        return response()->json($students);
    }

    /**
     * Get attendance records for a specific date and class
     */
    public function getAttendanceByDate(Request $request)
    {
        $classId = $request->get('class_id');
        $date = $request->get('date');
        $teacherId = Auth::id();
        
        $teacherClass = TeacherClass::where('teacher_id', $teacherId)
            ->where('id', $classId)
            ->first();
            
        if (!$teacherClass) {
            return response()->json(['error' => 'Class not found'], 404);
        }
        
        $attendanceRecords = StaffAttendance::where('user_id', $teacherId)
            ->whereDate('attendance_date', $date)
            ->with(['staff'])
            ->get();

        return response()->json($attendanceRecords);
    }

    /**
     * Mark attendance for today
     */
    public function markToday()
    {
        $teacherId = Auth::id();
        $today = Carbon::today();
        
        $teacherClasses = TeacherClass::where('teacher_id', $teacherId)
            ->where('day_of_week', $today->format('l'))
            ->get();

        return view('teacher.attendance.mark-today', compact('teacherClasses', 'today'));
    }
}
