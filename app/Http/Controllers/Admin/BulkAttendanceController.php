<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BulkAttendanceBatch;
use App\Models\StaffAttendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StaffAttendanceImport;
use App\Exports\StaffAttendanceExport;
use Carbon\Carbon;

class BulkAttendanceController extends Controller
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
    
    public function index()
    {
        $batches = BulkAttendanceBatch::where('school_id', auth()->user()->school_id ?? 1)
            ->orderByDesc('created_at')
            ->paginate(20);
        return view('admin.attendance.bulk.index', compact('batches'));
    }

    public function create()
    {
        return view('admin.attendance.bulk.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv',
            'batch_date' => 'required|date',
        ]);

        $schoolId = auth()->user()->school_id ?? 1;
        $file = $request->file('file');

        // Import attendance
        Excel::import(new StaffAttendanceImport($schoolId), $file);

        // Aggregate counts for the date
        $date = Carbon::parse($request->batch_date)->toDateString();
        $counts = [
            'present' => StaffAttendance::where('school_id', $schoolId)->where('attendance_date', $date)->where('status','present')->count(),
            'absent' => StaffAttendance::where('school_id', $schoolId)->where('attendance_date', $date)->where('status','absent')->count(),
            'late' => StaffAttendance::where('school_id', $schoolId)->where('attendance_date', $date)->where('status','late')->count(),
            'half_day' => StaffAttendance::where('school_id', $schoolId)->where('attendance_date', $date)->where('status','half_day')->count(),
            'leave' => StaffAttendance::where('school_id', $schoolId)->where('attendance_date', $date)->where('status','leave')->count(),
        ];
        $total = array_sum($counts);

        BulkAttendanceBatch::create([
            'school_id' => $schoolId,
            'batch_date' => $date,
            'file_name' => $file->getClientOriginalName(),
            'total' => $total,
            'present' => $counts['present'],
            'absent' => $counts['absent'],
            'late' => $counts['late'],
            'half_day' => $counts['half_day'],
            'leave' => $counts['leave'],
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('admin.attendance.bulk.index')->with('success', 'Bulk attendance uploaded and processed.');
    }

    public function exportTemplate()
    {
        // Reuse export but filter empty dataset to get headings
        return Excel::download(new StaffAttendanceExport(auth()->user()->school_id ?? 1, null), 'staff_attendance_template.xlsx');
    }

    public function exportDay(Request $request)
    {
        $request->validate(['day' => 'required|date_format:Y-m-d']);
        return Excel::download(new StaffAttendanceExport(auth()->user()->school_id ?? 1, null), 'staff_attendance_'.$request->day.'.xlsx');
    }
}


