<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamSchedule;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ExamScheduleController extends Controller
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
     * Display a listing of exam schedules.
     */
    public function index()
    {
        $schoolId = Auth::user()->school_id;
        
        $schedules = ExamSchedule::with(['exam'])
            ->where('school_id', $schoolId)
            ->orderBy('exam_date', 'desc')
            ->paginate(20);

        $scheduleStats = [
            'total_schedules' => ExamSchedule::where('school_id', $schoolId)->count(),
            'upcoming_schedules' => ExamSchedule::where('school_id', $schoolId)->upcoming()->count(),
            'today_schedules' => ExamSchedule::where('school_id', $schoolId)->today()->count(),
            'completed_schedules' => ExamSchedule::where('school_id', $schoolId)->where('exam_date', '<', now()->toDateString())->count(),
        ];

        return view('teacher.exam-schedules.index', compact('schedules', 'scheduleStats'));
    }

    /**
     * Show the form for creating a new exam schedule.
     */
    public function create()
    {
        $schoolId = Auth::user()->school_id;
        $exams = Exam::where('school_id', $schoolId)->get();
        $classes = SchoolClass::where('school_id', $schoolId)->get();
        $sections = Section::where('school_id', $schoolId)->get();
        $subjects = Subject::all();

        return view('teacher.exam-schedules.create', compact('exams', 'classes', 'sections', 'subjects'));
    }

    /**
     * Store a newly created exam schedule.
     */
    public function store(Request $request)
    {
        $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'class_name' => 'required|string|max:255',
            'section_name' => 'nullable|string|max:255',
            'subject_name' => 'required|string|max:255',
            'exam_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room_no' => 'nullable|string|max:50',
            'max_marks' => 'required|numeric|min:0',
            'pass_marks' => 'required|numeric|min:0|lte:max_marks',
            'invigilator_name' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'status' => 'required|in:scheduled,completed,cancelled',
        ]);

        $data = $request->all();
        $data['school_id'] = Auth::user()->school_id;

        $schedule = ExamSchedule::create($data);

        return redirect()->route('teacher.exam-schedules.index')
            ->with('success', 'Exam schedule created successfully!');
    }

    /**
     * Display the specified exam schedule.
     */
    public function show(ExamSchedule $examSchedule)
    {
        $examSchedule->load(['exam', 'marks', 'attendances']);
        
        $scheduleStats = [
            'total_students' => $examSchedule->getTotalStudents(),
            'present_students' => $examSchedule->getPresentStudents(),
            'absent_students' => $examSchedule->getAbsentStudents(),
            'pass_percentage' => $examSchedule->getPassPercentage(),
            'average_marks' => $examSchedule->getAverageMarks(),
            'attendance_percentage' => $examSchedule->getAttendancePercentage(),
        ];

        return view('teacher.exam-schedules.show', compact('examSchedule', 'scheduleStats'));
    }

    /**
     * Show the form for editing the specified exam schedule.
     */
    public function edit(ExamSchedule $examSchedule)
    {
        $schoolId = Auth::user()->school_id;
        $exams = Exam::where('school_id', $schoolId)->get();
        $classes = SchoolClass::where('school_id', $schoolId)->get();
        $sections = Section::where('school_id', $schoolId)->get();
        $subjects = Subject::all();

        return view('teacher.exam-schedules.edit', compact('examSchedule', 'exams', 'classes', 'sections', 'subjects'));
    }

    /**
     * Update the specified exam schedule.
     */
    public function update(Request $request, ExamSchedule $examSchedule)
    {
        $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'class_name' => 'required|string|max:255',
            'section_name' => 'nullable|string|max:255',
            'subject_name' => 'required|string|max:255',
            'exam_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room_no' => 'nullable|string|max:50',
            'max_marks' => 'required|numeric|min:0',
            'pass_marks' => 'required|numeric|min:0|lte:max_marks',
            'invigilator_name' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'status' => 'required|in:scheduled,completed,cancelled',
        ]);

        $examSchedule->update($request->all());

        return redirect()->route('teacher.exam-schedules.index')
            ->with('success', 'Exam schedule updated successfully!');
    }

    /**
     * Remove the specified exam schedule.
     */
    public function destroy(ExamSchedule $examSchedule)
    {
        $examSchedule->delete();

        return redirect()->route('teacher.exam-schedules.index')
            ->with('success', 'Exam schedule deleted successfully!');
    }

    /**
     * Display today's exam schedules.
     */
    public function today()
    {
        $schoolId = Auth::user()->school_id;
        
        $schedules = ExamSchedule::with(['exam'])
            ->where('school_id', $schoolId)
            ->today()
            ->orderBy('start_time')
            ->get();

        return view('teacher.exam-schedules.today', compact('schedules'));
    }

    /**
     * Display upcoming exam schedules.
     */
    public function upcoming()
    {
        $schoolId = Auth::user()->school_id;
        
        $schedules = ExamSchedule::with(['exam'])
            ->where('school_id', $schoolId)
            ->upcoming()
            ->orderBy('exam_date')
            ->orderBy('start_time')
            ->get();

        return view('teacher.exam-schedules.upcoming', compact('schedules'));
    }

    /**
     * Display exam schedules by class.
     */
    public function byClass(Request $request)
    {
        $schoolId = Auth::user()->school_id;
        $className = $request->get('class_name');
        
        $schedules = ExamSchedule::with(['exam'])
            ->where('school_id', $schoolId)
            ->when($className, function($query) use ($className) {
                return $query->where('class_name', $className);
            })
            ->orderBy('exam_date', 'desc')
            ->paginate(20);

        $classes = SchoolClass::where('school_id', $schoolId)->get();

        return view('teacher.exam-schedules.by-class', compact('schedules', 'classes', 'className'));
    }

    /**
     * Display exam schedules by subject.
     */
    public function bySubject(Request $request)
    {
        $schoolId = Auth::user()->school_id;
        $subjectName = $request->get('subject_name');
        
        $schedules = ExamSchedule::with(['exam'])
            ->where('school_id', $schoolId)
            ->when($subjectName, function($query) use ($subjectName) {
                return $query->where('subject_name', $subjectName);
            })
            ->orderBy('exam_date', 'desc')
            ->paginate(20);

        $subjects = Subject::all();

        return view('teacher.exam-schedules.by-subject', compact('schedules', 'subjects', 'subjectName'));
    }

    /**
     * Display exam schedules by date.
     */
    public function byDate(Request $request)
    {
        $schoolId = Auth::user()->school_id;
        $date = $request->get('date', now()->toDateString());
        
        $schedules = ExamSchedule::with(['exam'])
            ->where('school_id', $schoolId)
            ->where('exam_date', $date)
            ->orderBy('start_time')
            ->get();

        return view('teacher.exam-schedules.by-date', compact('schedules', 'date'));
    }

    /**
     * Mark exam schedule as completed.
     */
    public function complete(ExamSchedule $examSchedule)
    {
        $examSchedule->update(['status' => 'completed']);

        return redirect()->back()
            ->with('success', 'Exam schedule marked as completed!');
    }

    /**
     * Cancel exam schedule.
     */
    public function cancel(ExamSchedule $examSchedule)
    {
        $examSchedule->update(['status' => 'cancelled']);

        return redirect()->back()
            ->with('success', 'Exam schedule cancelled!');
    }

    /**
     * Reschedule exam.
     */
    public function reschedule(Request $request, ExamSchedule $examSchedule)
    {
        $request->validate([
            'exam_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $examSchedule->update([
            'exam_date' => $request->exam_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'status' => 'scheduled',
        ]);

        return redirect()->back()
            ->with('success', 'Exam rescheduled successfully!');
    }

    /**
     * Get exam schedule statistics.
     */
    public function statistics(ExamSchedule $examSchedule)
    {
        $stats = [
            'total_students' => $examSchedule->getTotalStudents(),
            'present_students' => $examSchedule->getPresentStudents(),
            'absent_students' => $examSchedule->getAbsentStudents(),
            'pass_percentage' => $examSchedule->getPassPercentage(),
            'average_marks' => $examSchedule->getAverageMarks(),
            'attendance_percentage' => $examSchedule->getAttendancePercentage(),
            'top_performers' => $examSchedule->getTopPerformers(10),
        ];

        return view('teacher.exam-schedules.statistics', compact('examSchedule', 'stats'));
    }

    /**
     * Export exam schedule data.
     */
    public function export(ExamSchedule $examSchedule)
    {
        $marks = $examSchedule->marks()->with('student')->get();
        
        // Implementation for Excel/PDF export
        // This would typically use Laravel Excel or similar package
        
        return response()->json([
            'message' => 'Export functionality will be implemented',
            'data' => $marks
        ]);
    }
}