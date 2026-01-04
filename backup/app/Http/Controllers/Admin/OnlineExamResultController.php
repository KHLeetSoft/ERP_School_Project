<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OnlineExam;
use App\Models\OnlineExamAttempt;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Subject;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class OnlineExamResultController extends Controller
{
    protected $adminUser;
    protected $schoolId;

    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware(function ($request, $next) {
            $this->adminUser = auth()->guard('admin')->user();
            $this->schoolId = $this->adminUser ? $this->adminUser->school_id : null;
            return $next($request);
        });
    }
    
    /**
     * List exams with aggregated results overview.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $schoolId = auth()->user()->school_id ?? null;
    
            $query = OnlineExam::with([
                    'schoolClass:id,name',
                    'section:id,name',
                    'subject:id,subject_name as name'
                ])
                ->withCount([
                    'attempts as completed_attempts_count' => function ($q) {
                        $q->whereIn('status', ['submitted', 'auto_submitted']);
                    }
                ])
                ->where('school_id', $schoolId)
                ->when($request->class_id, fn($q) => $q->where('class_id', $request->class_id))
                ->when($request->section_id, fn($q) => $q->where('section_id', $request->section_id))
                ->when($request->subject_id, fn($q) => $q->where('subject_id', $request->subject_id))
                ->when($request->status, fn($q) => $q->where('status', $request->status));
    
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('class_name', fn($r) => optional($r->schoolClass)->name)
                ->addColumn('section_name', fn($r) => optional($r->section)->name)
                ->addColumn('subject_name', fn($r) => optional($r->subject)->name)
                ->addColumn('start_datetime', fn($r) => $r->start_datetime ? $r->start_datetime->format('d M Y h:i A') : 'N/A')
                ->addColumn('end_datetime', fn($r) => $r->end_datetime ? $r->end_datetime->format('d M Y h:i A') : 'N/A')
                ->addColumn('completed_attempts', fn($r) => $r->completed_attempts_count)
                ->addColumn('status', function ($r) {
                    $badgeClass = match($r->status) {
                        'published' => 'success',
                        'draft' => 'secondary',
                        'cancelled' => 'danger',
                        default => 'info',
                    };
                    return '<span class="badge bg-' . $badgeClass . '">' . ucfirst($r->status) . '</span>';
                })
                ->addColumn('actions', function ($r) {
                    $show = route('admin.online-exam.results', $r->id);
                    return '<a class="btn btn-sm " href="' . $show . '"  data-bs-toggle="tooltip" 
                            data-bs-placement="top" 
                            title="View Result">
                                <i class="bx bx-bar-chart"></i> 
                            </a>';
                })
                ->rawColumns(['status','actions'])
                ->make(true);
        }
    
        // Load filter dropdown data
        $classes = SchoolClass::orderBy('name')->get(['id','name']);
        $sections = Section::orderBy('name')->get(['id','name']);
        $subjects = Subject::orderBy('subject_name')->get(['id','subject_name as name']);
    
        return view('admin.exams.online-exam.results.index', compact('classes','sections','subjects'));
    }
    

    /**
     * Render attempt details (for modal via AJAX).
     */
    public function attemptDetails(OnlineExamAttempt $attempt)
    {
        $attempt->load(['onlineExam:id,title,total_marks,passing_marks', 'student:id,name,email']);
        return view('admin.exams.online-exam.results.partials.attempt-details', compact('attempt'));
    }

    /**
     * Download/print a single attempt result as HTML (basic). In real setup generate PDF.
     */
    public function attemptDownload(OnlineExamAttempt $attempt)
    {
        $attempt->load(['onlineExam:id,title,total_marks,passing_marks', 'student:id,name,email']);
        return view('admin.exams.online-exam.results.partials.attempt-download', compact('attempt'));
    }
}


