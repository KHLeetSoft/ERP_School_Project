<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamSchedule;
use App\Models\ExamMark;
use App\Models\ExamTabulation;
use App\Models\ExamGrade;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ExamController extends Controller
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
     * Display a listing of exams.
     */
    public function index()
    {
        $teacherId = Auth::id();
        $schoolId = Auth::user()->school_id;

        $exams = Exam::with(['schedules', 'marks'])
            ->where('school_id', $schoolId)
            ->orderBy('start_date', 'desc')
            ->paginate(20);

        $examStats = [
            'total_exams' => Exam::where('school_id', $schoolId)->count(),
            'active_exams' => Exam::where('school_id', $schoolId)->active()->count(),
            'upcoming_exams' => Exam::where('school_id', $schoolId)->upcoming()->count(),
            'completed_exams' => Exam::where('school_id', $schoolId)->completed()->count(),
        ];

        return view('teacher.exams.index', compact('exams', 'examStats'));
    }

    /**
     * Show the form for creating a new exam.
     */
    public function create()
    {
        $schoolId = Auth::user()->school_id;
        $classes = SchoolClass::where('school_id', $schoolId)->get();
        $sections = Section::where('school_id', $schoolId)->get();
        $subjects = Subject::all();

        return view('teacher.exams.create', compact('classes', 'sections', 'subjects'));
    }

    /**
     * Store a newly created exam.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'exam_type' => 'required|string|max:100',
            'academic_year' => 'required|string|max:30',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:scheduled,completed,cancelled,draft',
        ]);

        $data = $request->all();
        $data['school_id'] = Auth::user()->school_id;

        $exam = Exam::create($data);

        return redirect()->route('teacher.exams.index')
            ->with('success', 'Exam created successfully!');
    }

    /**
     * Display the specified exam.
     */
    public function show(Exam $exam)
    {
        $exam->load(['schedules', 'marks', 'tabulations']);
        
        $examStats = [
            'total_schedules' => $exam->total_schedules,
            'total_students' => $exam->total_students,
            'total_subjects' => $exam->total_subjects,
            'pass_percentage' => $exam->getPassPercentage(),
            'average_marks' => $exam->getAverageMarks(),
        ];

        return view('teacher.exams.show', compact('exam', 'examStats'));
    }

    /**
     * Show the form for editing the specified exam.
     */
    public function edit(Exam $exam)
    {
        $schoolId = Auth::user()->school_id;
        $classes = SchoolClass::where('school_id', $schoolId)->get();
        $sections = Section::where('school_id', $schoolId)->get();
        $subjects = Subject::all();

        return view('teacher.exams.edit', compact('exam', 'classes', 'sections', 'subjects'));
    }

    /**
     * Update the specified exam.
     */
    public function update(Request $request, Exam $exam)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'exam_type' => 'required|string|max:100',
            'academic_year' => 'required|string|max:30',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:scheduled,completed,cancelled,draft',
        ]);

        $exam->update($request->all());

        return redirect()->route('teacher.exams.index')
            ->with('success', 'Exam updated successfully!');
    }

    /**
     * Remove the specified exam.
     */
    public function destroy(Exam $exam)
    {
        $exam->delete();

        return redirect()->route('teacher.exams.index')
            ->with('success', 'Exam deleted successfully!');
    }

    /**
     * Display exam schedules.
     */
    public function schedules(Exam $exam)
    {
        $schedules = $exam->schedules()->orderBy('exam_date')->get();
        
        return view('teacher.exams.schedules', compact('exam', 'schedules'));
    }

    /**
     * Display exam marks.
     */
    public function marks(Exam $exam)
    {
        $marks = $exam->marks()
            ->with('student')
            ->orderBy('class_name')
            ->orderBy('student_name')
            ->paginate(50);

        return view('teacher.exams.marks', compact('exam', 'marks'));
    }

    /**
     * Display exam results/tabulation.
     */
    public function results(Exam $exam)
    {
        $results = $exam->tabulations()
            ->orderBy('class_name')
            ->orderBy('rank')
            ->paginate(50);

        $classStats = $exam->tabulations()
            ->selectRaw('class_name, COUNT(*) as total_students, AVG(percentage) as avg_percentage, SUM(CASE WHEN result_status = "pass" THEN 1 ELSE 0 END) as passed_students')
            ->groupBy('class_name')
            ->get();

        return view('teacher.exams.results', compact('exam', 'results', 'classStats'));
    }

    /**
     * Display upcoming exams.
     */
    public function upcoming()
    {
        $schoolId = Auth::user()->school_id;
        
        $exams = Exam::with(['schedules'])
            ->where('school_id', $schoolId)
            ->upcoming()
            ->orderBy('start_date', 'asc')
            ->get();

        return view('teacher.exams.upcoming', compact('exams'));
    }

    /**
     * Display active exams.
     */
    public function active()
    {
        $schoolId = Auth::user()->school_id;
        
        $exams = Exam::with(['schedules'])
            ->where('school_id', $schoolId)
            ->active()
            ->orderBy('start_date', 'asc')
            ->get();

        return view('teacher.exams.active', compact('exams'));
    }

    /**
     * Display completed exams.
     */
    public function completed()
    {
        $schoolId = Auth::user()->school_id;
        
        $exams = Exam::with(['schedules'])
            ->where('school_id', $schoolId)
            ->completed()
            ->orderBy('end_date', 'desc')
            ->get();

        return view('teacher.exams.completed', compact('exams'));
    }

    /**
     * Get exam statistics.
     */
    public function statistics(Exam $exam)
    {
        $stats = [
            'total_students' => $exam->total_students,
            'total_subjects' => $exam->total_subjects,
            'pass_percentage' => $exam->getPassPercentage(),
            'average_marks' => $exam->getAverageMarks(),
            'top_performers' => $exam->getTopPerformers(10),
        ];

        $classWiseStats = $exam->tabulations()
            ->selectRaw('class_name, COUNT(*) as total_students, AVG(percentage) as avg_percentage, SUM(CASE WHEN result_status = "pass" THEN 1 ELSE 0 END) as passed_students')
            ->groupBy('class_name')
            ->get();

        $subjectWiseStats = $exam->marks()
            ->selectRaw('subject_name, COUNT(*) as total_students, AVG(percentage) as avg_percentage, SUM(CASE WHEN result_status = "pass" THEN 1 ELSE 0 END) as passed_students')
            ->groupBy('subject_name')
            ->get();

        return view('teacher.exams.statistics', compact('exam', 'stats', 'classWiseStats', 'subjectWiseStats'));
    }

    /**
     * Export exam results.
     */
    public function export(Exam $exam)
    {
        $results = $exam->tabulations()
            ->orderBy('class_name')
            ->orderBy('rank')
            ->get();

        // Implementation for Excel/PDF export
        // This would typically use Laravel Excel or similar package
        
        return response()->json([
            'message' => 'Export functionality will be implemented',
            'data' => $results
        ]);
    }

    /**
     * Publish exam results.
     */
    public function publishResults(Exam $exam)
    {
        $exam->tabulations()->update(['status' => 'published']);

        return redirect()->back()
            ->with('success', 'Exam results published successfully!');
    }

    /**
     * Unpublish exam results.
     */
    public function unpublishResults(Exam $exam)
    {
        $exam->tabulations()->update(['status' => 'draft']);

        return redirect()->back()
            ->with('success', 'Exam results unpublished successfully!');
    }

    /**
     * Generate exam report.
     */
    public function report(Exam $exam)
    {
        $exam->load(['schedules', 'marks', 'tabulations']);
        
        $reportData = [
            'exam' => $exam,
            'total_students' => $exam->total_students,
            'total_subjects' => $exam->total_subjects,
            'pass_percentage' => $exam->getPassPercentage(),
            'average_marks' => $exam->getAverageMarks(),
            'class_wise_stats' => $exam->tabulations()
                ->selectRaw('class_name, COUNT(*) as total_students, AVG(percentage) as avg_percentage')
                ->groupBy('class_name')
                ->get(),
            'subject_wise_stats' => $exam->marks()
                ->selectRaw('subject_name, COUNT(*) as total_students, AVG(percentage) as avg_percentage')
                ->groupBy('subject_name')
                ->get(),
        ];

        return view('teacher.exams.report', compact('reportData'));
    }
}