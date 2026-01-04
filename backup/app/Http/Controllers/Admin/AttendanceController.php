<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\StudentDetail;
use App\Models\ClassSection;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AttendanceExport;
use App\Imports\AttendanceImport;

class AttendanceController extends Controller
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
            $query = Attendance::with(['student', 'classSection']);

            // Filter by month
            if ($request->month) {
                $month = Carbon::parse($request->month)->month;
                $query->whereMonth('attendance_date', $month);
            }

            // Filter by class
            if ($request->class_section_id) {
                $query->where('class_section_id', $request->class_section_id);
            }

            return DataTables::of($query)
                ->addColumn('student_name', fn($row) => $row->student->first_name . ' ' . $row->student->last_name)
                ->addColumn('class', fn($row) => optional($row->classSection)->name)
                ->editColumn('attendance_date', fn($row) => Carbon::parse($row->attendance_date)->format('d-m-Y'))
                ->addColumn('actions', function ($row) {
                    return view('admin.students.attendance.partials.actions', compact('row'))->render();
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        $class_sections = ClassSection::all();
        return view('admin.students.attendance.index', compact('class_sections'));
    }

    public function create()
    {
        $students = StudentDetail::all();
        $class_sections = ClassSection::all();
        return view('admin.students.attendance.create', compact('students', 'class_sections'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'attendance_date' => 'required|date',
            'class_section_id' => 'required',
            'attendances' => 'required|array'
        ]);

        foreach ($request->attendances as $student_id => $status) {
            Attendance::updateOrCreate(
                [
                    'student_id' => $student_id,
                    'attendance_date' => $request->attendance_date,
                ],
                [
                    'class_section_id' => $request->class_section_id,
                    'status' => $status,
                ]
            );
        }

        return redirect()->route('admin.attendance.index')->with('success', 'Attendance saved successfully.');
    }

    public function edit($id)
    {
        $attendance = Attendance::findOrFail($id);
        $students = StudentDetail::all();
        $class_sections = ClassSection::all();
        return view('admin.students.attendance.edit', compact('attendance', 'students', 'class_sections'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'attendance_date' => 'required|date',
            'class_section_id' => 'required',
            'student_id' => 'required',
            'status' => 'required'
        ]);

        $attendance = Attendance::findOrFail($id);
        $attendance->update($request->all());

        return redirect()->route('admin.attendance.index')->with('success', 'Attendance updated.');
    }

    public function destroy($id)
    {
        Attendance::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }

    public function export(Request $request)
    {
        return Excel::download(new AttendanceExport($request->month, $request->class_section_id), 'attendance.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv'
        ]);

        Excel::import(new AttendanceImport, $request->file('file'));

        return back()->with('success', 'Attendance imported successfully.');
    }
}
