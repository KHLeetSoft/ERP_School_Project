<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RfidAttendance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RfidAttendanceExport;
use App\Imports\RfidAttendanceImport;

class RfidAttendanceController extends Controller
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
            $query = RfidAttendance::with('user')
                ->where('school_id', auth()->user()->school_id ?? 1)
                ->orderByDesc('timestamp');

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('staff_name', fn($r) => optional($r->user)->name)
                ->editColumn('timestamp', fn($r) => Carbon::parse($r->timestamp)->format('d M Y, h:i A'))
                ->addColumn('actions', function ($r) {
                    return view('admin.attendance.rfid.partials.actions', compact('r'))->render();
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        $users = User::where('school_id', auth()->user()->school_id ?? 1)->get(['id','name']);
        return view('admin.attendance.rfid.index', compact('users'));
    }

    public function create()
    {
        $users = User::where('school_id', auth()->user()->school_id ?? 1)->get(['id','name']);
        return view('admin.attendance.rfid.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'card_uid' => 'required|string|max:191',
            'timestamp' => 'required|date',
            'direction' => 'required|in:in,out',
            'device_name' => 'nullable|string|max:191',
            'remarks' => 'nullable|string',
        ]);

        RfidAttendance::create([
            'school_id' => auth()->user()->school_id ?? 1,
            'user_id' => $request->user_id,
            'card_uid' => $request->card_uid,
            'timestamp' => $request->timestamp,
            'direction' => $request->direction,
            'device_name' => $request->device_name,
            'remarks' => $request->remarks,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('admin.attendance.rfid.index')->with('success', 'RFID record added.');
    }

    public function edit(RfidAttendance $rfid)
    {
        $this->authorizeSchool($rfid);
        $users = User::where('school_id', auth()->user()->school_id ?? 1)->get(['id','name']);
        return view('admin.attendance.rfid.edit', compact('rfid','users'));
    }

    public function update(Request $request, RfidAttendance $rfid)
    {
        $this->authorizeSchool($rfid);
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'card_uid' => 'required|string|max:191',
            'timestamp' => 'required|date',
            'direction' => 'required|in:in,out',
            'device_name' => 'nullable|string|max:191',
            'remarks' => 'nullable|string',
        ]);

        $rfid->update([
            'user_id' => $request->user_id,
            'card_uid' => $request->card_uid,
            'timestamp' => $request->timestamp,
            'direction' => $request->direction,
            'device_name' => $request->device_name,
            'remarks' => $request->remarks,
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('admin.attendance.rfid.index')->with('success', 'RFID record updated.');
    }

    public function destroy(RfidAttendance $rfid)
    {
        $this->authorizeSchool($rfid);
        $rfid->delete();
        if (request()->wantsJson()) return response()->json(['success' => true]);
        return redirect()->route('admin.attendance.rfid.index')->with('success', 'RFID record deleted.');
    }

    public function export(Request $request)
    {
        $schoolId = auth()->user()->school_id ?? 1;
        return Excel::download(new RfidAttendanceExport($schoolId), 'rfid_attendance.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:xlsx,csv']);
        $schoolId = auth()->user()->school_id ?? 1;
        Excel::import(new RfidAttendanceImport($schoolId), $request->file('file'));
        return back()->with('success', 'RFID data imported.');
    }

    public function dashboard()
    {
        $schoolId = auth()->user()->school_id ?? 1;
        $days = collect(range(6,0))->map(fn($i)=> Carbon::today()->subDays($i));
        $inSeries = [];
        $outSeries = [];
        foreach ($days as $d) {
            $date = $d->toDateString();
            $inSeries[] = RfidAttendance::where('school_id', $schoolId)->whereDate('timestamp', $date)->where('direction','in')->count();
            $outSeries[] = RfidAttendance::where('school_id', $schoolId)->whereDate('timestamp', $date)->where('direction','out')->count();
        }

        $topDevices = RfidAttendance::where('school_id', $schoolId)
            ->selectRaw('device_name, COUNT(*) as cnt')
            ->groupBy('device_name')
            ->orderByDesc('cnt')
            ->limit(5)
            ->get();

        return view('admin.attendance.rfid.dashboard', [
            'labels' => $days->map(fn($d)=> $d->format('d M')),
            'inSeries' => $inSeries,
            'outSeries' => $outSeries,
            'topDevices' => $topDevices,
        ]);
    }

    private function authorizeSchool(RfidAttendance $rfid): void
    {
        if (($rfid->school_id ?? null) !== (auth()->user()->school_id ?? 1)) abort(403);
    }
}


