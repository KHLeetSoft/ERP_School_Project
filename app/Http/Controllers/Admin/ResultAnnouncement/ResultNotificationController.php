<?php

namespace App\Http\Controllers\Admin\ResultAnnouncement;

use App\Http\Controllers\Controller;
use App\Models\ResultNotification;
use App\Models\ResultAnnouncement;
use App\Models\Student;
use App\Models\ParentDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;
use Yajra\DataTables\Facades\DataTables;
use App\Notifications\ResultPublicationNotification;

class ResultNotificationController extends Controller
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
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = ResultNotification::with(['creator', 'resultAnnouncement'])
                ->where('school_id', auth()->user()->school_id ?? 1)
                ->orderByDesc('created_at');

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('announcement', fn($r) => $r->resultAnnouncement->title ?? '-')
                ->addColumn('created_by', fn($r) => $r->creator->name ?? '-')
                ->addColumn('created_by', fn($r) => $r->created_at ? $r->created_at->format('d M Y') : '-')
                ->addColumn('scheduled_at', function ($r) {
                    return $r->scheduled_at 
                        ? $r->scheduled_at->format('d M Y') 
                        : '-';
                })
                ->addColumn('sent_at', function ($r) {
                    return $r->sent_at 
                        ? $r->sent_at->format('d M Y') 
                        : '-';
                })
                 ->rawColumns(['scheduled_at', 'sent_at'])
                ->make(true);
        }

        $announcements = ResultAnnouncement::where('school_id', auth()->user()->school_id ?? 1)
            ->orderByDesc('created_at')->get(['id','title']);

        return view('admin.result-announcement.notification.index', compact('announcements'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'result_announcement_id' => 'nullable|exists:result_announcements,id',
            'target_audience' => 'array',
            'target_audience.*' => 'in:students,parents',
            'channels' => 'array',
            'channels.*' => 'in:database,email,sms',
            'scheduled_at' => 'nullable|date|after:now',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $notification = ResultNotification::create([
            'school_id' => auth()->user()->school_id ?? 1,
            'result_announcement_id' => $request->result_announcement_id,
            'title' => $request->title,
            'message' => $request->message,
            'status' => $request->scheduled_at ? 'scheduled' : 'draft',
            'scheduled_at' => $request->scheduled_at,
            'target_audience' => $request->target_audience ?? ['students','parents'],
            'channels' => $request->channels ?? ['database'],
            'stats' => [],
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('admin.result-announcement.notification.index')
            ->with('success', 'Notification saved.');
    }

    public function send(ResultNotification $notification)
    {
        if ($notification->status === 'sent') {
            return back()->withErrors(['error' => 'Already sent.']);
        }

        $schoolId = auth()->user()->school_id ?? 1;
        $sendToStudents = in_array('students', $notification->target_audience ?? []);
        $sendToParents = in_array('parents', $notification->target_audience ?? []);

        $dbCount = 0;
        $noti = new ResultPublicationNotification((object)[
            'id' => $notification->id,
            'publication_title' => $notification->title,
            'publication_type' => 'announcement_notification',
            'published_at' => now(),
            'result_announcement_id' => $notification->result_announcement_id,
        ]);

        if ($sendToStudents) {
            $students = Student::where('school_id', $schoolId)->where('status', 'active')->pluck('user_id')->filter();
            $users = User::whereIn('id', $students)->get();
            Notification::send($users, $noti);
            $dbCount += $users->count();
        }

        if ($sendToParents) {
            $parents = ParentDetail::where('school_id', $schoolId)->pluck('user_id')->filter();
            $users = User::whereIn('id', $parents)->get();
            Notification::send($users, $noti);
            $dbCount += $users->count();
        }

        $notification->update([
            'status' => 'sent',
            'sent_at' => now(),
            'stats' => ['database_sent' => $dbCount],
            'updated_by' => Auth::id(),
        ]);

        return back()->with('success', 'Notification sent to recipients.');
    }
}
