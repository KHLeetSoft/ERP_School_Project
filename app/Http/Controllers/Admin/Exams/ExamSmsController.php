<?php

namespace App\Http\Controllers\Admin\Exams;

use App\Http\Controllers\Controller;
use App\Models\ExamSms;
use App\Models\Exam;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Jobs\SendExamSmsJob;

class ExamSmsController extends Controller
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
    
    public function dashboard()
    {
        $schoolId = auth()->user()->school_id ?? null;
        $totals = [
            'all' => ExamSms::where('school_id', $schoolId)->count(),
            'draft' => ExamSms::where('school_id', $schoolId)->where('status','draft')->count(),
            'scheduled' => ExamSms::where('school_id', $schoolId)->where('status','scheduled')->count(),
            'sent' => ExamSms::where('school_id', $schoolId)->where('status','sent')->count(),
        ];
        $recent = ExamSms::with('exam')->where('school_id', $schoolId)->latest()->limit(10)->get();
        $byAudience = ExamSms::where('school_id', $schoolId)
            ->selectRaw('audience_type, COUNT(*) as total')
            ->groupBy('audience_type')->pluck('total','audience_type');
        $byStatus = ExamSms::where('school_id', $schoolId)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')->pluck('total','status');
        $sentOverTime = ExamSms::where('school_id', $schoolId)
            ->where('status','sent')
            ->selectRaw('DATE(updated_at) as day, SUM(sent_count) as total')
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('total','day');
        $topCampaigns = ExamSms::with('exam')
            ->where('school_id', $schoolId)
            ->orderByDesc('sent_count')
            ->limit(10)
            ->get(['id','title','exam_id','sent_count','status','schedule_at']);
        return view('admin.exams.sms.dashboard', compact('totals','recent','byAudience','byStatus','sentOverTime','topCampaigns'));
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $schoolId = auth()->user()->school_id ?? null;
            $query = ExamSms::with('exam')->where('school_id', $schoolId);
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('exam_title', fn($r)=> optional($r->exam)->title)
                ->editColumn('schedule_at', fn($r)=> optional($r->schedule_at)?->format('Y-m-d H:i'))
                ->addColumn('actions', function ($r) {
                    $show = route('admin.exams.sms.show', $r->id);
                    $edit = route('admin.exams.sms.edit', $r->id);
                    $destroy = route('admin.exams.sms.destroy', $r->id);
                    $send = route('admin.exams.sms.send', $r->id);
                    return '<div class="btn-group" role="group">'
                        . '<a href="' . $show . '" class="btn btn-sm" title="View"><i class="bx bx-show"></i></a>'
                        . '<a href="' . $edit . '" class="btn btn-sm" title="Edit"><i class="bx bxs-edit"></i></a>'
                        . '<form action="' . e($send) . '" method="POST" style="display:inline-block; margin:0 4px;">'
                        . csrf_field()
                        . '<button type="submit" class="btn btn-sm" title="Send Now"><i class="bx bx-send"></i></button>'
                        . '</form>'
                        . '<button type="button" class="btn btn-sm delete-sms-btn" data-action="' . e($destroy) . '" title="Delete"><i class="bx bx-trash"></i></button>'
                        . '</div>';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('admin.exams.sms.index');
    }

    public function create()
    {
        $exams = Exam::orderByDesc('start_date')->get(['id','title']);
        return view('admin.exams.sms.create', compact('exams'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'message_template' => 'required|string',
            'audience_type' => 'required|in:students,parents,staff,custom',
            'exam_id' => 'nullable|exists:exams,id',
            'class_name' => 'nullable|string|max:100',
            'section_name' => 'nullable|string|max:50',
            'schedule_at' => 'nullable|date',
            'status' => 'required|in:draft,scheduled,sent',
        ]);
        $data['school_id'] = auth()->user()->school_id ?? null;
        ExamSms::create($data);
        return redirect()->route('admin.exams.sms.index')->with('success','SMS campaign created.');
    }

    public function show(ExamSms $sms)
    {
        $sms->loadCount(['recipients as sent_count_total' => function($q){$q->where('status','sent');}]);
        return view('admin.exams.sms.show', compact('sms'));
    }

    public function edit(ExamSms $sms)
    {
        $exams = Exam::orderByDesc('start_date')->get(['id','title']);
        return view('admin.exams.sms.edit', compact('sms','exams'));
    }

    public function update(Request $request, ExamSms $sms)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'message_template' => 'required|string',
            'audience_type' => 'required|in:students,parents,staff,custom',
            'exam_id' => 'nullable|exists:exams,id',
            'class_name' => 'nullable|string|max:100',
            'section_name' => 'nullable|string|max:50',
            'schedule_at' => 'nullable|date',
            'status' => 'required|in:draft,scheduled,sent',
        ]);
        $sms->update($data);
        return redirect()->route('admin.exams.sms.index')->with('success','SMS campaign updated.');
    }

    public function destroy(ExamSms $sms)
    {
        $sms->delete();
        return back()->with('success','SMS campaign deleted.');
    }

    public function send(ExamSms $sms)
    {
        if ($sms->schedule_at) {
            SendExamSmsJob::dispatch($sms->id)->delay($sms->schedule_at);
            return back()->with('success', 'SMS scheduled.');
        }
        SendExamSmsJob::dispatch($sms->id);
        return back()->with('success', 'SMS sending started.');
    }
}


