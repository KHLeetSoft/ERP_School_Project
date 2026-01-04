<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ExamMark;
use App\Models\ExamTabulation;
use App\Models\Exam;
use App\Models\Student;
use App\Models\StudentDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StudentResultController extends Controller
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

        // Get current academic year
        $academicYear = $request->get('academic_year', date('Y'));
        $examType = $request->get('exam_type', 'all');
        
        // Get student's results
        $results = $this->getStudentResults($student, $academicYear, $examType);
        
        // Get available exam types
        $examTypes = $this->getAvailableExamTypes($student);
        
        // Get academic years
        $academicYears = $this->getAcademicYears($student);
        
        // Calculate overall statistics
        $stats = $this->calculateOverallStats($results);
        
        return view('student.results.index', compact(
            'results', 
            'stats', 
            'examTypes', 
            'academicYears',
            'academicYear',
            'examType'
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

        $examId = $request->get('exam_id');
        $exam = Exam::find($examId);
        
        if (!$exam) {
            return redirect()->route('student.results.index')->with('error', 'Exam not found.');
        }
        
        // Get detailed results for specific exam
        $examResults = $this->getExamResults($student, $examId);
        $examStats = $this->calculateExamStats($examResults);
        
        return view('student.results.show', compact('exam', 'examResults', 'examStats'));
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

        $academicYear = $request->get('academic_year', date('Y'));
        
        // Generate comprehensive report
        $report = $this->generateResultReport($student, $academicYear);
        
        return view('student.results.report', compact('report', 'academicYear'));
    }

    public function transcript(Request $request)
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

        $academicYear = $request->get('academic_year', date('Y'));
        
        // Generate academic transcript
        $transcript = $this->generateTranscript($student, $academicYear);
        
        return view('student.results.transcript', compact('transcript', 'academicYear'));
    }

    private function getStudentResults($student, $academicYear, $examType)
    {
        if (!$student) {
            return collect();
        }

        $studentDetail = StudentDetail::where('user_id', $student->user_id)->first();
        if (!$studentDetail) {
            return collect();
        }

        $query = ExamMark::where('student_id', $student->user_id)
            ->whereYear('created_at', $academicYear)
            ->with(['exam']);

        if ($examType !== 'all') {
            $query->whereHas('exam', function($q) use ($examType) {
                $q->where('exam_type', $examType);
            });
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    private function getExamResults($student, $examId)
    {
        if (!$student) {
            return collect();
        }

        return ExamMark::where('student_id', $student->user_id)
            ->where('exam_id', $examId)
            ->with(['exam'])
            ->orderBy('subject_name')
            ->get();
    }

    private function getAvailableExamTypes($student)
    {
        if (!$student) {
            return collect();
        }

        $studentDetail = StudentDetail::where('user_id', $student->user_id)->first();
        if (!$studentDetail) {
            return collect();
        }

        return Exam::whereHas('marks', function($query) use ($student) {
            $query->where('student_id', $student->user_id);
        })->distinct()->pluck('exam_type');
    }

    private function getAcademicYears($student)
    {
        if (!$student) {
            return collect();
        }

        return ExamMark::where('student_id', $student->user_id)
            ->selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');
    }

    private function calculateOverallStats($results)
    {
        if ($results->isEmpty()) {
            return [
                'total_exams' => 0,
                'total_subjects' => 0,
                'average_percentage' => 0,
                'highest_percentage' => 0,
                'lowest_percentage' => 0,
                'pass_count' => 0,
                'fail_count' => 0,
                'pass_percentage' => 0,
            ];
        }

        $totalExams = $results->count();
        $totalSubjects = $results->pluck('subject_name')->unique()->count();
        $percentages = $results->pluck('percentage');
        $passCount = $results->where('result_status', 'pass')->count();
        $failCount = $results->where('result_status', 'fail')->count();

        return [
            'total_exams' => $totalExams,
            'total_subjects' => $totalSubjects,
            'average_percentage' => round($percentages->avg(), 2),
            'highest_percentage' => $percentages->max(),
            'lowest_percentage' => $percentages->min(),
            'pass_count' => $passCount,
            'fail_count' => $failCount,
            'pass_percentage' => $totalExams > 0 ? round(($passCount / $totalExams) * 100, 2) : 0,
        ];
    }

    private function calculateExamStats($examResults)
    {
        if ($examResults->isEmpty()) {
            return [
                'total_subjects' => 0,
                'total_marks' => 0,
                'obtained_marks' => 0,
                'average_percentage' => 0,
                'grade' => 'N/A',
                'result_status' => 'N/A',
            ];
        }

        $totalMarks = $examResults->sum('max_marks');
        $obtainedMarks = $examResults->sum('obtained_marks');
        $averagePercentage = $totalMarks > 0 ? round(($obtainedMarks / $totalMarks) * 100, 2) : 0;
        
        // Calculate overall grade and status
        $grade = $this->calculateGrade($averagePercentage);
        $resultStatus = $averagePercentage >= 40 ? 'pass' : 'fail';

        return [
            'total_subjects' => $examResults->count(),
            'total_marks' => $totalMarks,
            'obtained_marks' => $obtainedMarks,
            'average_percentage' => $averagePercentage,
            'grade' => $grade,
            'result_status' => $resultStatus,
        ];
    }

    private function generateResultReport($student, $academicYear)
    {
        $results = $this->getStudentResults($student, $academicYear, 'all');
        $stats = $this->calculateOverallStats($results);
        
        // Group results by exam
        $examGroups = $results->groupBy('exam_id');
        
        // Get exam details
        $exams = Exam::whereIn('id', $examGroups->keys())->get()->keyBy('id');
        
        $report = [
            'academic_year' => $academicYear,
            'student' => $student,
            'stats' => $stats,
            'exam_groups' => $examGroups,
            'exams' => $exams,
            'subject_performance' => $this->getSubjectPerformance($results),
            'monthly_performance' => $this->getMonthlyPerformance($results),
        ];
        
        return $report;
    }

    private function generateTranscript($student, $academicYear)
    {
        $results = $this->getStudentResults($student, $academicYear, 'all');
        $stats = $this->calculateOverallStats($results);
        
        // Group by subjects
        $subjectGroups = $results->groupBy('subject_name');
        
        $transcript = [
            'academic_year' => $academicYear,
            'student' => $student,
            'overall_stats' => $stats,
            'subject_groups' => $subjectGroups,
            'generated_at' => now(),
        ];
        
        return $transcript;
    }

    private function getSubjectPerformance($results)
    {
        return $results->groupBy('subject_name')->map(function ($subjectResults) {
            $totalMarks = $subjectResults->sum('max_marks');
            $obtainedMarks = $subjectResults->sum('obtained_marks');
            $averagePercentage = $totalMarks > 0 ? round(($obtainedMarks / $totalMarks) * 100, 2) : 0;
            
            return [
                'subject' => $subjectResults->first()->subject_name,
                'total_exams' => $subjectResults->count(),
                'total_marks' => $totalMarks,
                'obtained_marks' => $obtainedMarks,
                'average_percentage' => $averagePercentage,
                'grade' => $this->calculateGrade($averagePercentage),
                'best_percentage' => $subjectResults->max('percentage'),
                'worst_percentage' => $subjectResults->min('percentage'),
            ];
        });
    }

    private function getMonthlyPerformance($results)
    {
        return $results->groupBy(function ($result) {
            return Carbon::parse($result->created_at)->format('Y-m');
        })->map(function ($monthResults) {
            $percentages = $monthResults->pluck('percentage');
            return [
                'month' => Carbon::parse($monthResults->first()->created_at)->format('M Y'),
                'total_exams' => $monthResults->count(),
                'average_percentage' => round($percentages->avg(), 2),
                'best_percentage' => $percentages->max(),
                'worst_percentage' => $percentages->min(),
            ];
        });
    }

    private function calculateGrade($percentage)
    {
        if ($percentage >= 90) return 'A+';
        if ($percentage >= 80) return 'A';
        if ($percentage >= 70) return 'B+';
        if ($percentage >= 60) return 'B';
        if ($percentage >= 50) return 'C+';
        if ($percentage >= 40) return 'C';
        return 'F';
    }
}
