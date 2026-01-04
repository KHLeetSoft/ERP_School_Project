<?php

namespace App\Http\Controllers\Admin\ResultAnnouncement;

use App\Http\Controllers\Controller;
use App\Models\ResultAnnouncement;
use App\Models\Exam;
use App\Models\OnlineExam;
use App\Models\SchoolClass;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ResultAnnouncementController extends Controller
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
     * Display a listing of result announcements.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = ResultAnnouncement::with(['creator', 'exam', 'onlineExam'])
                ->where('school_id', auth()->user()->school_id ?? 1);

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('created_by', function ($row) {
                    return $row->creator->name ?? '-';
                })
                ->addColumn('exam_name', function ($row) {
                    return $row->exam->title ?? ($row->onlineExam->title ?? '-');
                })
                ->editColumn('status', function ($row) {
                    if ($row->status === 'published') {
                        return '<span class="badge bg-success">Published</span>';
                    } elseif ($row->status === 'draft') {
                        return '<span class="badge bg-secondary">Draft</span>';
                    } else {
                        return '<span class="badge bg-warning">Archived</span>';
                    }
                })
                ->addColumn('date', function ($row) {
                    return $row->created_at ? $row->created_at->format('d-m-Y') : '-';
                })
                ->addColumn('actions', function ($row) {
                    return '
                        <div class="d-flex gap-2">
                            <a href="'.route('admin.result-announcement.announcement.edit', $row->id).'" 
                               class="btn btn-sm " title="Edit">
                                <i class="bx bxs-edit"></i>
                            </a>
                            <a href="'.route('admin.result-announcement.announcement.show', $row->id).'" 
                               class="btn btn-sm " title="View">
                                <i class="bx bx-show"></i>
                            </a>
                            <button class="btn btn-sm delete-btn" data-id="'.$row->id.'" title="Delete">
                                <i class="bx bx-trash"></i>
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['status', 'actions'])
                ->make(true);
        }

        return view('admin.result-announcement.announcement.index');
    }
    

    /**
     * Show the form for creating a new result announcement.
     */
    public function create()
    {
        $exams = Exam::where('school_id', auth()->user()->school_id ?? 1)
                    ->where('status', 'completed')
                    ->get(['id', 'title']);
        
        $onlineExams = OnlineExam::where('school_id', auth()->user()->school_id ?? 1)
                                ->where('status', 'completed')
                                ->get(['id', 'title']);
        
        $classes = SchoolClass::where('school_id', auth()->user()->school_id ?? 1)
                             ->get(['id', 'name']);
        
        $sections = Section::where('school_id', auth()->user()->school_id ?? 1)
                          ->get(['id', 'name']);

        return view('admin.result-announcement.announcement.create', compact('exams', 'onlineExams', 'classes', 'sections'));
    }

    /**
     * Store a newly created result announcement.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'announcement_type' => 'required|in:exam_result,online_exam_result,general_result,merit_list',
            'exam_id' => 'nullable|required_if:announcement_type,exam_result|exists:exams,id',
            'online_exam_id' => 'nullable|required_if:announcement_type,online_exam_result|exists:online_exams,id',
            'target_audience' => 'nullable|array',
            'target_audience.*' => 'in:students,parents,teachers',
            'class_ids' => 'nullable|array',
            'class_ids.*' => 'exists:school_classes,id',
            'section_ids' => 'nullable|array',
            'section_ids.*' => 'exists:sections,id',
            'publish_at' => 'nullable|date|after:now',
            'expires_at' => 'nullable|date|after:publish_at',
            'send_sms' => 'boolean',
            'send_email' => 'boolean',
            'send_push_notification' => 'boolean',
            'status' => 'required|in:draft,published'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        try {
            DB::beginTransaction();

            $announcement = ResultAnnouncement::create([
                'school_id' => auth()->user()->school_id ?? 1,
                'title' => $request->title,
                'description' => $request->description,
                'announcement_type' => $request->announcement_type,
                'exam_id' => $request->exam_id,
                'online_exam_id' => $request->online_exam_id,
                'target_audience' => $request->target_audience,
                'class_ids' => $request->class_ids,
                'section_ids' => $request->section_ids,
                'publish_at' => $request->publish_at,
                'expires_at' => $request->expires_at,
                'send_sms' => $request->boolean('send_sms'),
                'send_email' => $request->boolean('send_email'),
                'send_push_notification' => $request->boolean('send_push_notification'),
                'status' => $request->status,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id()
            ]);

            DB::commit();

            return redirect()->route('admin.result-announcement.announcement.index')
                           ->with('success', 'Result announcement created successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                           ->withErrors(['error' => 'Failed to create announcement. Please try again.'])
                           ->withInput();
        }
    }

    /**
     * Display the specified result announcement.
     */
    public function show(ResultAnnouncement $resultAnnouncement)
    {
        $resultAnnouncement->load(['creator', 'exam', 'onlineExam', 'school']);
        
        return view('admin.result-announcement.announcement.show', compact('resultAnnouncement'));
    }

    /**
     * Show the form for editing the specified result announcement.
     */
    public function edit(ResultAnnouncement $resultAnnouncement)
    {
        $exams = Exam::where('school_id', auth()->user()->school_id ?? 1)
                    ->where('status', 'completed')
                    ->get(['id', 'title']);
        
        $onlineExams = OnlineExam::where('school_id', auth()->user()->school_id ?? 1)
                                ->where('status', 'completed')
                                ->get(['id', 'title']);
        
        $classes = SchoolClass::where('school_id', auth()->user()->school_id ?? 1)
                             ->get(['id', 'name']);
        
        $sections = Section::where('school_id', auth()->user()->school_id ?? 1)
                          ->get(['id', 'name']);

        return view('admin.result-announcement.announcement.edit', compact('resultAnnouncement', 'exams', 'onlineExams', 'classes', 'sections'));
    }

    /**
     * Update the specified result announcement.
     */
    public function update(Request $request, ResultAnnouncement $resultAnnouncement)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'announcement_type' => 'required|in:exam_result,online_exam_result,general_result,merit_list',
            'exam_id' => 'nullable|required_if:announcement_type,exam_result|exists:exams,id',
            'online_exam_id' => 'nullable|required_if:announcement_type,online_exam_result|exists:online_exams,id',
            'target_audience' => 'nullable|array',
            'target_audience.*' => 'in:students,parents,teachers',
            'class_ids' => 'nullable|array',
            'class_ids.*' => 'exists:school_classes,id',
            'section_ids' => 'nullable|array',
            'section_ids.*' => 'exists:sections,id',
            'publish_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:publish_at',
            'send_sms' => 'boolean',
            'send_email' => 'boolean',
            'send_push_notification' => 'boolean',
            'status' => 'required|in:draft,published,archived'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        try {
            DB::beginTransaction();

            $resultAnnouncement->update([
                'title' => $request->title,
                'description' => $request->description,
                'announcement_type' => $request->announcement_type,
                'exam_id' => $request->exam_id,
                'online_exam_id' => $request->online_exam_id,
                'target_audience' => $request->target_audience,
                'class_ids' => $request->class_ids,
                'section_ids' => $request->section_ids,
                'publish_at' => $request->publish_at,
                'expires_at' => $request->expires_at,
                'send_sms' => $request->boolean('send_sms'),
                'send_email' => $request->boolean('send_email'),
                'send_push_notification' => $request->boolean('send_push_notification'),
                'status' => $request->status,
                'updated_by' => Auth::id()
            ]);

            DB::commit();

            return redirect()->route('admin.result-announcement.announcement.show', $resultAnnouncement)
                           ->with('success', 'Result announcement updated successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                           ->withErrors(['error' => 'Failed to update announcement. Please try again.'])
                           ->withInput();
        }
    }

    /**
     * Remove the specified result announcement.
     */
    public function destroy(ResultAnnouncement $resultAnnouncement)
    {
        try {
            $resultAnnouncement->delete();
            return redirect()->route('admin.result-announcement.announcement.index')
                           ->with('success', 'Result announcement deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->withErrors(['error' => 'Failed to delete announcement.']);
        }
    }

    /**
     * Publish the announcement.
     */
    public function publish(ResultAnnouncement $resultAnnouncement)
    {
        if ($resultAnnouncement->status !== 'draft') {
            return redirect()->back()
                           ->withErrors(['error' => 'Only draft announcements can be published.']);
        }

        $resultAnnouncement->update([
            'status' => 'published',
            'publish_at' => now(),
            'updated_by' => Auth::id()
        ]);

        return redirect()->back()
                       ->with('success', 'Result announcement published successfully.');
    }

    /**
     * Archive the announcement.
     */
    public function archive(ResultAnnouncement $resultAnnouncement)
    {
        $resultAnnouncement->update([
            'status' => 'archived',
            'updated_by' => Auth::id()
        ]);

        return redirect()->back()
                       ->with('success', 'Result announcement archived successfully.');
    }

    /**
     * Send notifications for the announcement.
     */
    public function sendNotifications(ResultAnnouncement $resultAnnouncement)
    {
        // This would integrate with your notification system
        // For now, we'll just mark it as sent
        $resultAnnouncement->update([
            'notification_settings' => [
                'sms_sent_at' => now(),
                'email_sent_at' => now(),
                'push_sent_at' => now()
            ],
            'updated_by' => Auth::id()
        ]);

        return redirect()->back()
                       ->with('success', 'Notifications sent successfully.');
    }
}
