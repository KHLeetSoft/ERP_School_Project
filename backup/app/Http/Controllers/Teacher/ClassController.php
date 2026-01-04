<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\TeacherClass;
use App\Models\SchoolClass;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClassController extends Controller
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
        $classes = TeacherClass::with(['schoolClass', 'subject'])
            ->byTeacher($teacherId)
            ->active()
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        $classStats = [
            'total_classes' => $classes->count(),
            'total_students' => $classes->sum('total_students'),
            'active_classes' => $classes->where('status', 'active')->count(),
            'this_week_classes' => $classes->whereIn('day_of_week', ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'])->count(),
        ];

        return view('teacher.classes.index', compact('classes', 'classStats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $schoolClasses = SchoolClass::where('status', 'active')->get();
        $subjects = Subject::where('status', 'active')->get();
        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        return view('teacher.classes.create', compact('schoolClasses', 'subjects', 'daysOfWeek'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:school_classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'room_number' => 'nullable|string|max:50',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'day_of_week' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'description' => 'nullable|string|max:500',
        ]);

        $schoolClass = SchoolClass::findOrFail($request->class_id);
        $subject = Subject::findOrFail($request->subject_id);

        $teacherClass = TeacherClass::create([
            'teacher_id' => Auth::id(),
            'class_id' => $request->class_id,
            'subject_id' => $request->subject_id,
            'class_name' => $schoolClass->name,
            'subject_name' => $subject->name,
            'room_number' => $request->room_number,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'day_of_week' => $request->day_of_week,
            'total_students' => $schoolClass->students_count ?? 0,
            'description' => $request->description,
            'status' => 'active',
        ]);

        return redirect()->route('teacher.classes.index')
            ->with('success', 'Class created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(TeacherClass $class)
    {
        $class->load(['schoolClass', 'subject']);
        
        // Get students for this class
        $students = $class->schoolClass->students ?? collect();
        
        return view('teacher.classes.show', compact('class', 'students'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TeacherClass $class)
    {
        $schoolClasses = SchoolClass::where('status', 'active')->get();
        $subjects = Subject::where('status', 'active')->get();
        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        return view('teacher.classes.edit', compact('class', 'schoolClasses', 'subjects', 'daysOfWeek'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TeacherClass $class)
    {
        $request->validate([
            'class_id' => 'required|exists:school_classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'room_number' => 'nullable|string|max:50',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'day_of_week' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'description' => 'nullable|string|max:500',
            'status' => 'required|in:active,inactive,completed',
        ]);

        $schoolClass = SchoolClass::findOrFail($request->class_id);
        $subject = Subject::findOrFail($request->subject_id);

        $class->update([
            'class_id' => $request->class_id,
            'subject_id' => $request->subject_id,
            'class_name' => $schoolClass->name,
            'subject_name' => $subject->name,
            'room_number' => $request->room_number,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'day_of_week' => $request->day_of_week,
            'total_students' => $schoolClass->students_count ?? 0,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return redirect()->route('teacher.classes.index')
            ->with('success', 'Class updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TeacherClass $class)
    {
        $class->delete();

        return redirect()->route('teacher.classes.index')
            ->with('success', 'Class deleted successfully!');
    }

    /**
     * Get classes for a specific day
     */
    public function getClassesByDay(Request $request)
    {
        $day = $request->get('day', now()->format('l'));
        $teacherId = Auth::id();
        
        $classes = TeacherClass::with(['schoolClass', 'subject'])
            ->byTeacher($teacherId)
            ->byDay($day)
            ->active()
            ->orderBy('start_time')
            ->get();

        return response()->json($classes);
    }
}
