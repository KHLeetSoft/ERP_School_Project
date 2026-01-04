<?php

namespace App\Http\Controllers\Admin\Academic;

use App\Http\Controllers\Controller;
use App\Models\Timetable;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Excel;
use PDF;
use App\Exports\TimetableExport;
use App\Imports\TimetableImport;

class TimetableController extends Controller
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
            $query = Timetable::with(['class', 'section', 'subject', 'teacher']);

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('class_name', fn($row) => $row->class?->name ?? 'N/A')
                ->addColumn('section_name', fn($row) => $row->section?->name ?? 'N/A')
                ->addColumn('subject_name', fn($row) => $row->subject?->name ?? 'N/A')
                ->addColumn('teacher_name', fn($row) => $row->teacher?->name ?? 'N/A')
                ->addColumn('time_slot', fn($row) =>
                    date('h:i A', strtotime($row->start_time)) . ' - ' . 
                    date('h:i A', strtotime($row->end_time))
                )
                ->addColumn('status_badge', fn($row) =>
                    '<span class="badge bg-' . ($row->status == 'active' ? 'success' : 'danger') . '">' .
                    ucfirst($row->status) . '</span>'
                )
                ->addColumn('action', function ($row) {
                    $buttons = '<div class="d-flex justify-content">';
                    $buttons .= '<a href="' . route('admin.academic.timetable.show', $row->id) . '" class="text-info me-2" title="View"><i class="bx bx-show"></i></a>';
                    $buttons .= '<a href="' . route('admin.academic.timetable.edit', $row->id) . '" class="text-primary me-2" title="Edit"><i class="bx bxs-edit"></i></a>';
                    $buttons .= '<a href="javascript:void(0);" data-id="' . $row->id . '" class="text-danger delete-timetable-btn" title="Delete"><i class="bx bx-trash"></i></a>';
                    $buttons .= '</div>';
                    return $buttons;

                })
                ->rawColumns(['status_badge', 'action'])

                ->rawColumns(['status_badge', 'action'])
                ->make(true);
        }

        return view('admin.academic.timetable.index');
    }

    public function create()
    {
        $classes = SchoolClass::all();
        $sections = Section::all();
        $subjects = Subject::all();
        $teachers = Teacher::all();
        return view('admin.academic.timetable.create', compact('classes', 'sections', 'subjects', 'teachers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required',
            'section_id' => 'required',
            'subject_id' => 'required',
            'teacher_id' => 'required',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'status' => 'required|in:active,inactive',
        ]);

        Timetable::create($request->all());
        return redirect()->route('admin.academic.timetable.index')->with('success', 'Timetable created successfully!');
    }

    public function show($id)
    {
        $timetable = Timetable::with(['class', 'section', 'subject', 'teacher'])->findOrFail($id);
        return view('admin.academic.timetable.show', compact('timetable'));
    }

    public function edit($id)
    {
        $timetable = Timetable::findOrFail($id);
        $classes = SchoolClass::all();
        $sections = Section::all();
        $subjects = Subject::all();
        $teachers = Teacher::all();
        return view('admin.academic.timetable.edit', compact('timetable', 'classes', 'sections', 'subjects', 'teachers'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'class_id' => 'required',
            'section_id' => 'required',
            'subject_id' => 'required',
            'teacher_id' => 'required',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'status' => 'required|in:active,inactive',
        ]);

        $timetable = Timetable::findOrFail($id);
        $timetable->update($request->all());

        return redirect()->route('admin.academic.timetable.index')->with('success', 'Timetable updated successfully!');
    }

    public function destroy($id)
    {
        $timetable = Timetable::findOrFail($id);
        $timetable->delete();
        return response()->json(['success' => 'Timetable deleted successfully!']);
    }

    public function export($format)
    {
        $timetables = Timetable::with(['class', 'section', 'subject', 'teacher'])->get();
        
        if ($format === 'excel') {
            return Excel::download(new TimetableExport($timetables), 'timetables.xlsx');
        } elseif ($format === 'pdf') {
            $pdf = PDF::loadView('admin.academic.timetable.pdf', compact('timetables'));
            return $pdf->download('timetables.pdf');
        }
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new TimetableImport, $request->file('file'));

        return redirect()->back()->with('success', 'Timetable imported successfully!');
    }
}
