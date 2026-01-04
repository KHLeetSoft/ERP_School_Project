<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Student;
use App\Models\TeacherClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GradeController extends Controller
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
        
        $grades = Grade::with(['student'])
            ->byTeacher($teacherId)
            ->orderBy('graded_date', 'desc')
            ->paginate(20);

        $gradeStats = [
            'total_grades' => Grade::byTeacher($teacherId)->count(),
            'published_grades' => Grade::byTeacher($teacherId)->published()->count(),
            'draft_grades' => Grade::byTeacher($teacherId)->where('status', 'draft')->count(),
            'average_grade' => Grade::byTeacher($teacherId)->avg('percentage') ?? 0,
        ];

        return view('teacher.grades.index', compact('grades', 'teacherClasses', 'gradeStats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $teacherId = Auth::id();
        $teacherClasses = TeacherClass::where('teacher_id', $teacherId)->get();
        
        return view('teacher.grades.create', compact('teacherClasses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'class_name' => 'required|string|max:255',
            'subject_name' => 'required|string|max:255',
            'assignment_name' => 'required|string|max:255',
            'assignment_type' => 'required|in:assignment,quiz,exam,project',
            'points_earned' => 'required|numeric|min:0',
            'total_points' => 'required|numeric|min:1',
            'comments' => 'nullable|string|max:500',
            'graded_date' => 'required|date',
            'status' => 'required|in:draft,published,archived',
        ]);

        $teacherId = Auth::id();
        $student = Student::findOrFail($request->student_id);

        // Calculate percentage and letter grade
        $percentage = ($request->points_earned / $request->total_points) * 100;
        $letterGrade = $this->calculateLetterGrade($percentage);

        $grade = Grade::create([
            'teacher_id' => $teacherId,
            'student_id' => $request->student_id,
            'class_name' => $request->class_name,
            'subject_name' => $request->subject_name,
            'assignment_name' => $request->assignment_name,
            'assignment_type' => $request->assignment_type,
            'points_earned' => $request->points_earned,
            'total_points' => $request->total_points,
            'percentage' => $percentage,
            'letter_grade' => $letterGrade,
            'comments' => $request->comments,
            'graded_date' => $request->graded_date,
            'status' => $request->status,
        ]);

        return redirect()->route('teacher.grades.index')
            ->with('success', 'Grade created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Grade $grade)
    {
        $grade->load(['student', 'teacher']);
        
        return view('teacher.grades.show', compact('grade'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Grade $grade)
    {
        $teacherId = Auth::id();
        $teacherClasses = TeacherClass::where('teacher_id', $teacherId)->get();
        
        return view('teacher.grades.edit', compact('grade', 'teacherClasses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Grade $grade)
    {
        $request->validate([
            'assignment_name' => 'required|string|max:255',
            'assignment_type' => 'required|in:assignment,quiz,exam,project',
            'points_earned' => 'required|numeric|min:0',
            'total_points' => 'required|numeric|min:1',
            'comments' => 'nullable|string|max:500',
            'graded_date' => 'required|date',
            'status' => 'required|in:draft,published,archived',
        ]);

        // Calculate percentage and letter grade
        $percentage = ($request->points_earned / $request->total_points) * 100;
        $letterGrade = $this->calculateLetterGrade($percentage);

        $grade->update([
            'assignment_name' => $request->assignment_name,
            'assignment_type' => $request->assignment_type,
            'points_earned' => $request->points_earned,
            'total_points' => $request->total_points,
            'percentage' => $percentage,
            'letter_grade' => $letterGrade,
            'comments' => $request->comments,
            'graded_date' => $request->graded_date,
            'status' => $request->status,
        ]);

        return redirect()->route('teacher.grades.index')
            ->with('success', 'Grade updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Grade $grade)
    {
        $grade->delete();

        return redirect()->route('teacher.grades.index')
            ->with('success', 'Grade deleted successfully!');
    }

    /**
     * Get students by class
     */
    public function getStudentsByClass(Request $request)
    {
        $className = $request->get('class_name');
        $teacherId = Auth::id();
        
        $students = Student::where('class_name', $className)
            ->orderBy('first_name')
            ->get();

        return response()->json($students);
    }

    /**
     * Bulk grade creation
     */
    public function bulkCreate()
    {
        $teacherId = Auth::id();
        $teacherClasses = TeacherClass::where('teacher_id', $teacherId)->get();
        
        return view('teacher.grades.bulk-create', compact('teacherClasses'));
    }

    /**
     * Store bulk grades
     */
    public function storeBulk(Request $request)
    {
        $request->validate([
            'class_name' => 'required|string|max:255',
            'subject_name' => 'required|string|max:255',
            'assignment_name' => 'required|string|max:255',
            'assignment_type' => 'required|in:assignment,quiz,exam,project',
            'total_points' => 'required|numeric|min:1',
            'graded_date' => 'required|date',
            'grades' => 'required|array',
            'grades.*.student_id' => 'required|exists:students,id',
            'grades.*.points_earned' => 'required|numeric|min:0',
            'grades.*.comments' => 'nullable|string|max:500',
        ]);

        $teacherId = Auth::id();
        $grades = [];

        foreach ($request->grades as $gradeData) {
            $percentage = ($gradeData['points_earned'] / $request->total_points) * 100;
            $letterGrade = $this->calculateLetterGrade($percentage);

            $grades[] = [
                'teacher_id' => $teacherId,
                'student_id' => $gradeData['student_id'],
                'class_name' => $request->class_name,
                'subject_name' => $request->subject_name,
                'assignment_name' => $request->assignment_name,
                'assignment_type' => $request->assignment_type,
                'points_earned' => $gradeData['points_earned'],
                'total_points' => $request->total_points,
                'percentage' => $percentage,
                'letter_grade' => $letterGrade,
                'comments' => $gradeData['comments'] ?? null,
                'graded_date' => $request->graded_date,
                'status' => 'draft',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Grade::insert($grades);

        return redirect()->route('teacher.grades.index')
            ->with('success', 'Bulk grades created successfully!');
    }

    /**
     * Calculate letter grade based on percentage
     */
    private function calculateLetterGrade($percentage)
    {
        if ($percentage >= 97) return 'A+';
        if ($percentage >= 93) return 'A';
        if ($percentage >= 90) return 'A-';
        if ($percentage >= 87) return 'B+';
        if ($percentage >= 83) return 'B';
        if ($percentage >= 80) return 'B-';
        if ($percentage >= 77) return 'C+';
        if ($percentage >= 73) return 'C';
        if ($percentage >= 70) return 'C-';
        if ($percentage >= 67) return 'D+';
        if ($percentage >= 65) return 'D';
        return 'F';
    }
}
