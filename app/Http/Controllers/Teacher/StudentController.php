<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\TeacherClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
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
        
        // Get all students from teacher's classes
        $teacherClasses = TeacherClass::where('teacher_id', $teacherId)->pluck('class_name');
        
        $students = Student::whereIn('class_name', $teacherClasses)
            ->orderBy('class_name')
            ->orderBy('first_name')
            ->get();

        $studentStats = [
            'total_students' => $students->count(),
            'active_students' => $students->where('status', 'active')->count(),
            'inactive_students' => $students->where('status', 'inactive')->count(),
            'classes_count' => $students->groupBy('class_name')->count(),
        ];

        return view('teacher.students.index', compact('students', 'studentStats'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        $student->load(['user', 'class']);
        
        // Get student's attendance records
        $attendanceRecords = $student->attendanceRecords ?? collect();
        
        // Get student's grades
        $grades = $student->grades ?? collect();
        
        return view('teacher.students.show', compact('student', 'attendanceRecords', 'grades'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        $student->load(['user', 'class']);
        return view('teacher.students.edit', compact('student'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $student->user_id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'student_id' => 'nullable|string|max:50',
            'parent_name' => 'nullable|string|max:255',
            'parent_phone' => 'nullable|string|max:20',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'emergency_contact_relation' => 'nullable|string|max:100',
        ]);

        // Update student record
        $student->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'address' => $request->address,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'student_id' => $request->student_id,
            'parent_name' => $request->parent_name,
            'parent_phone' => $request->parent_phone,
            'emergency_contact_name' => $request->emergency_contact_name,
            'emergency_contact_phone' => $request->emergency_contact_phone,
            'emergency_contact_relation' => $request->emergency_contact_relation,
        ]);

        // Update user record
        $student->user->update([
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
        ]);

        return redirect()->route('teacher.students.index')
            ->with('success', 'Student updated successfully!');
    }

    /**
     * Get students by class
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
     * Search students
     */
    public function search(Request $request)
    {
        $query = $request->get('q');
        $teacherId = Auth::id();
        
        $teacherClasses = TeacherClass::where('teacher_id', $teacherId)->pluck('class_name');
        
        $students = Student::whereIn('class_name', $teacherClasses)
            ->where(function($q) use ($query) {
                $q->where('first_name', 'like', "%{$query}%")
                  ->orWhere('last_name', 'like', "%{$query}%")
                  ->orWhere('student_id', 'like', "%{$query}%");
            })
            ->with(['user', 'class'])
            ->orderBy('first_name')
            ->limit(10)
            ->get();

        return response()->json($students);
    }
}
