<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\TeacherClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AssignmentController extends Controller
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
        
        $assignments = Assignment::with(['teacher', 'schoolClass', 'section', 'subject'])
            ->byTeacher($teacherId)
            ->orderBy('due_date', 'desc')
            ->paginate(20);

        $assignmentStats = [
            'total_assignments' => Assignment::byTeacher($teacherId)->count(),
            'published_assignments' => Assignment::byTeacher($teacherId)->published()->count(),
            'draft_assignments' => Assignment::byTeacher($teacherId)->where('status', 'draft')->count(),
            'overdue_assignments' => Assignment::byTeacher($teacherId)->overdue()->count(),
            'upcoming_assignments' => Assignment::byTeacher($teacherId)->upcoming()->count(),
        ];

        return view('teacher.assignments.index', compact('assignments', 'teacherClasses', 'assignmentStats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $teacherId = Auth::id();
        $teacherClasses = TeacherClass::where('teacher_id', $teacherId)->get();
        
        // Get classes, sections, and subjects for dropdowns
        $classes = \App\Models\SchoolClass::all();
        $sections = \App\Models\Section::all();
        $subjects = \App\Models\Subject::all();
        
        return view('teacher.assignments.create', compact('teacherClasses', 'classes', 'sections', 'subjects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:school_classes,id',
            'section_id' => 'nullable|exists:sections,id',
            'subject_id' => 'required|exists:subjects,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx,txt|max:10240',
            'due_date' => 'required|date|after:now',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:draft,published,assigned',
            'max_marks' => 'required|integer|min:1|max:1000',
            'passing_marks' => 'required|integer|min:0|max:1000|lte:max_marks',
        ]);

        $teacherId = Auth::id();
        $data = $request->all();
        $data['teacher_id'] = $teacherId;
        $data['assigned_date'] = now();

        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('assignments', $filename, 'public');
            $data['file'] = $filename;
        }

        $assignment = Assignment::create($data);

        return redirect()->route('teacher.assignments.index')
            ->with('success', 'Assignment created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Assignment $assignment)
    {
        $assignment->load(['teacher', 'submissions']);
        
        return view('teacher.assignments.show', compact('assignment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Assignment $assignment)
    {
        $teacherId = Auth::id();
        $teacherClasses = TeacherClass::where('teacher_id', $teacherId)->get();
        
        return view('teacher.assignments.edit', compact('assignment', 'teacherClasses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Assignment $assignment)
    {
        $request->validate([
            'class_id' => 'required|exists:school_classes,id',
            'section_id' => 'nullable|exists:sections,id',
            'subject_id' => 'required|exists:subjects,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx,txt|max:10240',
            'due_date' => 'required|date',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:draft,published,assigned,completed,cancelled',
            'max_marks' => 'required|integer|min:1|max:1000',
            'passing_marks' => 'required|integer|min:0|max:1000|lte:max_marks',
        ]);

        $data = $request->all();

        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('assignments', $filename, 'public');
            $data['file'] = $filename;
        }

        $assignment->update($data);

        return redirect()->route('teacher.assignments.index')
            ->with('success', 'Assignment updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Assignment $assignment)
    {
        $assignment->delete();

        return redirect()->route('teacher.assignments.index')
            ->with('success', 'Assignment deleted successfully!');
    }

    /**
     * Publish assignment
     */
    public function publish(Assignment $assignment)
    {
        $assignment->update(['status' => 'published']);

        return redirect()->back()
            ->with('success', 'Assignment published successfully!');
    }

    /**
     * Close assignment
     */
    public function close(Assignment $assignment)
    {
        $assignment->update(['status' => 'closed']);

        return redirect()->back()
            ->with('success', 'Assignment closed successfully!');
    }

    /**
     * Archive assignment
     */
    public function archive(Assignment $assignment)
    {
        $assignment->update(['status' => 'archived']);

        return redirect()->back()
            ->with('success', 'Assignment archived successfully!');
    }

    /**
     * Get assignments by class
     */
    public function getByClass(Request $request)
    {
        $className = $request->get('class_name');
        $teacherId = Auth::id();
        
        $assignments = Assignment::byTeacher($teacherId)
            ->byClass($className)
            ->orderBy('due_date', 'desc')
            ->get();

        return response()->json($assignments);
    }

    /**
     * Get upcoming assignments
     */
    public function upcoming()
    {
        $teacherId = Auth::id();
        
        $assignments = Assignment::with(['teacher'])
            ->byTeacher($teacherId)
            ->upcoming()
            ->published()
            ->orderBy('due_date', 'asc')
            ->get();

        return view('teacher.assignments.upcoming', compact('assignments'));
    }

    /**
     * Get overdue assignments
     */
    public function overdue()
    {
        $teacherId = Auth::id();
        
        $assignments = Assignment::with(['teacher'])
            ->byTeacher($teacherId)
            ->overdue()
            ->orderBy('due_date', 'desc')
            ->get();

        return view('teacher.assignments.overdue', compact('assignments'));
    }
}
