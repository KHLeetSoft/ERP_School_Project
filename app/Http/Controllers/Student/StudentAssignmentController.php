<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Student;
use App\Models\StudentDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class StudentAssignmentController extends Controller
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

        // Get filters
        $status = $request->get('status', 'all');
        $subject = $request->get('subject', 'all');
        $priority = $request->get('priority', 'all');
        
        // Get student's assignments
        $assignments = $this->getStudentAssignments($student, $status, $subject, $priority);
        
        // Get available subjects
        $subjects = $this->getAvailableSubjects($student);
        
        // Calculate statistics
        $stats = $this->calculateAssignmentStats($assignments);
        
        return view('student.assignments.index', compact(
            'assignments', 
            'stats', 
            'subjects',
            'status',
            'subject',
            'priority'
        ));
    }

    public function show($id)
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

        $assignment = Assignment::with(['teacher', 'subject', 'schoolClass', 'section'])
            ->findOrFail($id);
        
        // Check if assignment is for student's class
        if (!$this->isAssignmentForStudent($assignment, $student)) {
            return redirect()->route('student.assignments.index')->with('error', 'Assignment not found.');
        }
        
        // Get student's submission for this assignment
        $submission = AssignmentSubmission::where('assignment_id', $id)
            ->where('student_id', $user->id)
            ->first();
        
        return view('student.assignments.show', compact('assignment', 'submission'));
    }

    public function submit(Request $request, $id)
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

        $assignment = Assignment::findOrFail($id);
        
        // Check if assignment is for student's class
        if (!$this->isAssignmentForStudent($assignment, $student)) {
            return redirect()->route('student.assignments.index')->with('error', 'Assignment not found.');
        }
        
        // Check if assignment is still open for submission
        if ($assignment->due_date && Carbon::now()->gt($assignment->due_date)) {
            return redirect()->route('student.assignments.show', $id)
                ->with('error', 'Assignment submission deadline has passed.');
        }
        
        $request->validate([
            'submission_text' => 'nullable|string|max:5000',
            'submission_file' => 'nullable|file|mimes:pdf,doc,docx,txt,jpg,jpeg,png|max:10240',
        ]);
        
        // Handle file upload
        $submissionFile = null;
        if ($request->hasFile('submission_file')) {
            $file = $request->file('submission_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $submissionFile = $file->storeAs('assignment_submissions', $fileName, 'public');
        }
        
        // Create or update submission
        $submission = AssignmentSubmission::updateOrCreate(
            [
                'assignment_id' => $id,
                'student_id' => $user->id,
            ],
            [
                'submission_text' => $request->submission_text,
                'submission_file' => $submissionFile,
                'submitted_at' => now(),
                'status' => 'submitted',
            ]
        );
        
        return redirect()->route('student.assignments.show', $id)
            ->with('success', 'Assignment submitted successfully!');
    }

    public function history(Request $request)
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

        $status = $request->get('status', 'all');
        $subject = $request->get('subject', 'all');
        
        // Get student's submission history
        $submissions = $this->getSubmissionHistory($user->id, $status, $subject);
        
        // Get available subjects
        $subjects = $this->getAvailableSubjects($student);
        
        // Calculate statistics
        $stats = $this->calculateSubmissionStats($submissions);
        
        return view('student.assignments.history', compact(
            'submissions', 
            'stats', 
            'subjects',
            'status',
            'subject'
        ));
    }

    public function download($id)
    {
        $user = Auth::user();
        
        // Check if user has student role
        if (!$user->userRole || $user->userRole->name !== 'Student') {
            Auth::logout();
            return redirect()->route('student.login')->with('error', 'Access denied. Student role required.');
        }
        
        $submission = AssignmentSubmission::where('id', $id)
            ->where('student_id', $user->id)
            ->firstOrFail();
        
        if (!$submission->submission_file) {
            return redirect()->back()->with('error', 'No file found for this submission.');
        }
        
        $filePath = storage_path('app/public/' . $submission->submission_file);
        
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File not found.');
        }
        
        return response()->download($filePath);
    }

    private function getStudentAssignments($student, $status, $subject, $priority)
    {
        if (!$student) {
            return collect();
        }

        $studentDetail = StudentDetail::where('user_id', $student->user_id)->first();
        if (!$studentDetail) {
            return collect();
        }

        $query = Assignment::with(['teacher', 'subject', 'schoolClass', 'section', 'submissions' => function($q) use ($student) {
            $q->where('student_id', $student->user_id);
        }])
        ->where('class_id', $studentDetail->class_id ?? 0)
        ->where('section_id', $studentDetail->section_id ?? 0);

        // Apply filters
        if ($status !== 'all') {
            if ($status === 'pending') {
                $query->whereDoesntHave('submissions', function($q) use ($student) {
                    $q->where('student_id', $student->user_id);
                });
            } elseif ($status === 'submitted') {
                $query->whereHas('submissions', function($q) use ($student) {
                    $q->where('student_id', $student->user_id);
                });
            }
        }

        if ($subject !== 'all') {
            $query->where('subject_id', $subject);
        }

        if ($priority !== 'all') {
            $query->where('priority', $priority);
        }

        return $query->orderBy('due_date', 'asc')->get();
    }

    private function getSubmissionHistory($studentId, $status, $subject)
    {
        $query = AssignmentSubmission::with(['assignment.teacher', 'assignment.subject', 'assignment.schoolClass'])
            ->where('student_id', $studentId);

        // Apply filters
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($subject !== 'all') {
            $query->whereHas('assignment', function($q) use ($subject) {
                $q->where('subject_id', $subject);
            });
        }

        return $query->orderBy('submitted_at', 'desc')->get();
    }

    private function getAvailableSubjects($student)
    {
        if (!$student) {
            return collect();
        }

        $studentDetail = StudentDetail::where('user_id', $student->user_id)->first();
        if (!$studentDetail) {
            return collect();
        }

        return Assignment::where('class_id', $studentDetail->class_id ?? 0)
            ->where('section_id', $studentDetail->section_id ?? 0)
            ->with('subject')
            ->get()
            ->pluck('subject')
            ->unique('id')
            ->filter();
    }

    private function calculateAssignmentStats($assignments)
    {
        $total = $assignments->count();
        $submitted = $assignments->filter(function($assignment) {
            return $assignment->submissions->isNotEmpty();
        })->count();
        $pending = $total - $submitted;
        $overdue = $assignments->filter(function($assignment) {
            return $assignment->due_date && Carbon::now()->gt($assignment->due_date) && $assignment->submissions->isEmpty();
        })->count();

        return [
            'total' => $total,
            'submitted' => $submitted,
            'pending' => $pending,
            'overdue' => $overdue,
            'submission_rate' => $total > 0 ? round(($submitted / $total) * 100, 2) : 0,
        ];
    }

    private function calculateSubmissionStats($submissions)
    {
        $total = $submissions->count();
        $graded = $submissions->where('status', 'graded')->count();
        $pending = $submissions->where('status', 'submitted')->count();
        $averageGrade = $submissions->whereNotNull('grade')->avg('grade');

        return [
            'total' => $total,
            'graded' => $graded,
            'pending' => $pending,
            'average_grade' => $averageGrade ? round($averageGrade, 2) : 0,
        ];
    }

    private function isAssignmentForStudent($assignment, $student)
    {
        if (!$student) {
            return false;
        }

        $studentDetail = StudentDetail::where('user_id', $student->user_id)->first();
        if (!$studentDetail) {
            return false;
        }

        return $assignment->class_id == $studentDetail->class_id && 
               $assignment->section_id == $studentDetail->section_id;
    }
}
