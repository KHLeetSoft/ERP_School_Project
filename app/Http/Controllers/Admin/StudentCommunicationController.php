<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentCommunication;
use App\Models\StudentDetail;
use App\Models\SchoolClass;
use Yajra\DataTables\Facades\DataTables;

class StudentCommunicationController extends Controller
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
            $adminUser = auth()->user();

            if (!$adminUser || !$adminUser->id || $adminUser->role_id != 2) {
                return DataTables::of(collect([]))->make(true);
            }
            $query = StudentCommunication::with(['student.user','schoolClass'])->latest();

            if ($request->filled('class_id')) {
                $query->where('class_id', $request->class_id);
            }
            if ($request->filled('student_id')) {
                $query->where('student_id', $request->student_id);
            }
            if ($request->filled('channel')) {
                $query->where('channel', $request->channel);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('student_name', function ($row) {
                    $s = $row->student;
                    $name = trim(($s->first_name ?? '') . ' ' . ($s->last_name ?? ''));
                    return e($name ?: ($s->user->name ?? '-'));
                })
                ->addColumn('class_name', fn($row) => e($row->schoolClass->name ?? '-'))
                ->addColumn('channel', fn($row) => e(ucfirst($row->channel)))
                ->addColumn('status', fn($row) => e(ucfirst($row->status)))
                ->addColumn('sent_at', fn($row) => e(optional($row->sent_at)->format('Y-m-d H:i'))) 
                ->addColumn('actions', function ($row) {
                    $buttons = '<div class="d-flex">';
                    $buttons .= '<a href="' . route('admin.students.communication.show', $row->id) . '" class="text-info me-2" title="View"><i class="bx bx-show"></i></a>';
                    $buttons .= '<a href="' . route('admin.students.communication.edit', $row->id) . '" class="text-primary me-2" title="Edit"><i class="bx bxs-edit"></i></a>';
                    $buttons .= '<a href="javascript:void(0);" data-id="' . $row->id . '" class="text-danger delete-comm" title="Delete"><i class="bx bx-trash"></i></a>';
                    $buttons .= '</div>';
                    return $buttons;
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        $classes = SchoolClass::all();
        $students = StudentDetail::all();
        return view('admin.students.communication.index', compact('classes','students'));
    }

    public function create()
    {
        $classes = SchoolClass::all();
        $students = StudentDetail::all();
        return view('admin.students.communication.create', compact('classes','students'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'nullable|exists:student_details,id',
            'class_id' => 'nullable|exists:school_classes,id',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
            'channel' => 'required|in:sms,email,notice',
            'status' => 'nullable|in:draft,sent,scheduled',
            'sent_at' => 'nullable|date',
        ]);

        $data = $request->only(['student_id','class_id','subject','message','channel','status','sent_at']);
        $data['school_id'] = auth()->user()->school_id ?? null;
        StudentCommunication::create($data);
        return redirect()->route('admin.students.communication.index')->with('success', 'Communication created.');
    }

    public function show($id)
    {
        $record = StudentCommunication::with(['student.user','schoolClass'])->findOrFail($id);
        return view('admin.students.communication.show', compact('record'));
    }

    public function edit($id)
    {
        $record = StudentCommunication::findOrFail($id);
        $classes = SchoolClass::all();
        $students = StudentDetail::all();
        return view('admin.students.communication.edit', compact('record','classes','students'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'student_id' => 'nullable|exists:student_details,id',
            'class_id' => 'nullable|exists:school_classes,id',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
            'channel' => 'required|in:sms,email,notice',
            'status' => 'nullable|in:draft,sent,scheduled',
            'sent_at' => 'nullable|date',
        ]);

        $record = StudentCommunication::findOrFail($id);
        $record->update($request->only(['student_id','class_id','subject','message','channel','status','sent_at']));
        return redirect()->route('admin.students.communication.index')->with('success', 'Communication updated.');
    }

    public function destroy($id)
    {
        StudentCommunication::findOrFail($id)->delete();
        return response()->json(['message' => 'Communication deleted.']);
    }
}


