<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hostel;
use App\Models\HostelRoom;
use App\Models\StudentHostel;
use App\Models\StudentDetail;
use Yajra\DataTables\Facades\DataTables;

class StudentHostelController extends Controller
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
            $query = StudentHostel::with(['student.user','hostel','room'])->latest();

            if ($request->filled('hostel_id')) {
                $query->where('hostel_id', $request->hostel_id);
            }
            if ($request->filled('room_id')) {
                $query->where('room_id', $request->room_id);
            }
            if ($request->filled('student_id')) {
                $query->where('student_id', $request->student_id);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('student_name', function ($row) {
                    $s = $row->student;
                    $name = trim(($s->first_name ?? '') . ' ' . ($s->last_name ?? ''));
                    return e($name ?: ($s->user->name ?? '-'));
                })
                ->addColumn('hostel', fn($row) => e($row->hostel->name ?? '-'))
                ->addColumn('room', fn($row) => e($row->room->room_no ?? '-'))
                ->addColumn('status', fn($row) => e(ucfirst($row->status)))
                ->addColumn('actions', function ($row) {
                    $buttons = '<div class="d-flex">';
                    $buttons .= '<a href="' . route('admin.students.hostel.show', $row->id) . '" class="text-info me-2" title="View"><i class="bx bx-show"></i></a>';
                    $buttons .= '<a href="' . route('admin.students.hostel.edit', $row->id) . '" class="text-primary me-2" title="Edit"><i class="bx bxs-edit"></i></a>';
                    $buttons .= '<a href="javascript:void(0);" data-id="' . $row->id . '" class="text-danger delete-hostel" title="Delete"><i class="bx bx-trash"></i></a>';
                    $buttons .= '</div>';
                    return $buttons;
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        $hostels = Hostel::all();
        $rooms = HostelRoom::all();
        $students = StudentDetail::all();
        return view('admin.students.hostel.index', compact('hostels','rooms','students'));
    }

    public function create()
    {
        $hostels = Hostel::all();
        $rooms = HostelRoom::all();
        $students = StudentDetail::all();
        return view('admin.students.hostel.create', compact('hostels','rooms','students'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:student_details,id',
            'hostel_id' => 'required|exists:hostels,id',
            'room_id' => 'required|exists:hostel_rooms,id',
            'bed_no' => 'nullable|string|max:50',
            'join_date' => 'nullable|date',
            'leave_date' => 'nullable|date|after_or_equal:join_date',
            'status' => 'nullable|in:active,left',
        ]);

        $data = $request->all();
        $data['school_id'] = auth()->user()->school_id ?? null;
        StudentHostel::create($data);
        return redirect()->route('admin.students.hostel.index')->with('success', 'Hostel assignment created.');
    }

    public function show($id)
    {
        $record = StudentHostel::with(['student.user','hostel','room'])->findOrFail($id);
        return view('admin.students.hostel.show', compact('record'));
    }

    public function edit($id)
    {
        $record = StudentHostel::findOrFail($id);
        $hostels = Hostel::all();
        $rooms = HostelRoom::where('hostel_id', $record->hostel_id)->get();
        $students = StudentDetail::all();
        return view('admin.students.hostel.edit', compact('record','hostels','rooms','students'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'student_id' => 'required|exists:student_details,id',
            'hostel_id' => 'required|exists:hostels,id',
            'room_id' => 'required|exists:hostel_rooms,id',
            'bed_no' => 'nullable|string|max:50',
            'join_date' => 'nullable|date',
            'leave_date' => 'nullable|date|after_or_equal:join_date',
            'status' => 'nullable|in:active,left',
        ]);

        $record = StudentHostel::findOrFail($id);
        $record->update($request->all());
        return redirect()->route('admin.students.hostel.index')->with('success', 'Hostel assignment updated.');
    }

    public function destroy($id)
    {
        StudentHostel::findOrFail($id)->delete();
        return response()->json(['message' => 'Hostel assignment deleted.']);
    }

    // Extra helper to fetch rooms by hostel
    public function getRoomsByHostel(Request $request)
    {
        $request->validate(['hostel_id' => 'required|exists:hostels,id']);
        $rooms = HostelRoom::where('hostel_id', $request->hostel_id)->get(['id','room_no','capacity','status']);
        return response()->json($rooms);
    }
}


