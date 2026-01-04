<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use App\Models\CallLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class CallLogController extends Controller
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
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $adminUser = auth()->guard('admin')->user();

            if (!$adminUser || $adminUser->role_id != 2) {
                return DataTables::of(collect([]))->make(true);
            }

            $adminUser = auth()->guard('admin')->user();
            $schoolId = $adminUser ? $adminUser->school_id : null;
            $query = CallLog::where('school_id', $schoolId)->latest();

            if ($request->filled('purpose')) {
                $query->where('purpose', $request->purpose);
            }
            if ($request->filled('date_from') && $request->filled('date_to')) {
                $query->whereBetween('date', [$request->date_from, $request->date_to]);
            }

            $rows = $query->get();
            
            // Debug: Log the data
            \Log::info('CallLog Data:', [
                'count' => $rows->count(),
                'admin_user' => $adminUser ? $adminUser->id : 'null',
                'school_id' => $schoolId,
                'query_sql' => $query->toSql(),
                'query_bindings' => $query->getBindings()
            ]);

            return DataTables::of($rows)
                ->addIndexColumn()
                ->addColumn('caller_name', function ($row) {
                    return '<a href="' . route('admin.office.calllogs.show', $row->id) . '" class="link">' . e($row->caller_name) . '</a>';
                })
                ->addColumn('purpose', fn($row) => e($row->purpose))
                ->addColumn('phone', fn($row) => e($row->phone))
                ->addColumn('date', fn($row) => $row->date)
                ->addColumn('time', fn($row) => $row->time)
                ->addColumn('duration', fn($row) => $row->duration)
                ->addColumn('note', fn($row) => e($row->note))
                ->addColumn('action', function ($row) {
                    $buttons = '<div class="d-flex justify-content">';
                    $buttons .= '<a href="' . route('admin.office.calllogs.show', $row->id) . '" class="text-info me-2"><i class="bx bx-show"></i></a>';
                    $buttons .= '<a href="' . route('admin.office.calllogs.edit', $row->id) . '" class="text-primary me-2"><i class="bx bxs-edit"></i></a>';
                    $buttons .= '<a href="javascript:void(0);" data-id="' . $row->id . '" class="text-danger delete-calllog-btn" title="Delete"><i class="bx bx-trash"></i></a>';
                    $buttons .= '</div>';
                    return $buttons;
                })
                ->rawColumns(['caller_name','action'])
                ->make(true);
        }

        return view('admin.office.calllogs.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.office.calllogs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'caller_name' => 'required|string|max:255',
            'purpose'     => 'nullable|string',
            'phone'       => 'nullable|string',
            'date'        => 'nullable|date',
            'time'        => 'nullable',
            'duration'    => 'nullable',
            'note'        => 'nullable|string',
        ]);
        if ($validator->fails()) return back()->withErrors($validator)->withInput();
        $data = $validator->validated();
        $adminUser = auth()->guard('admin')->user();
        $data['user_id'] = $adminUser->id;
        $data['school_id'] = $adminUser->school_id;
        CallLog::create($data);
        return redirect()->route('admin.office.calllogs.index')->with('success','Call log added');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $adminUser = auth()->guard('admin')->user();
        $schoolId = $adminUser ? $adminUser->school_id : null;
        $log = CallLog::where('school_id', $schoolId)->findOrFail($id);
        return view('admin.office.calllogs.show', compact('log'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $adminUser = auth()->guard('admin')->user();
        $schoolId = $adminUser ? $adminUser->school_id : null;
        $log = CallLog::where('school_id', $schoolId)->findOrFail($id);
        return view('admin.office.calllogs.edit', compact('log'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $adminUser = auth()->guard('admin')->user();
        $schoolId = $adminUser ? $adminUser->school_id : null;
        $log = CallLog::where('school_id', $schoolId)->findOrFail($id);
        $validator = Validator::make($request->all(), [
            'caller_name' => 'required|string|max:255',
            'purpose'     => 'nullable|string',
            'phone'       => 'nullable|string',
            'date'        => 'nullable|date',
            'time'        => 'nullable',
            'duration'    => 'nullable',
            'note'        => 'nullable|string',
        ]);
        if ($validator->fails()) return back()->withErrors($validator)->withInput();
        $log->update($validator->validated());
        return redirect()->route('admin.office.calllogs.index')->with('success','Updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $adminUser = auth()->guard('admin')->user();
        $schoolId = $adminUser ? $adminUser->school_id : null;
        $log = CallLog::where('school_id', $schoolId)->findOrFail($id);
        $log->delete();
        return response()->json(['message'=>'Deleted']);
    }

    /**
     * Export logs to Excel.
     */
    public function export(Request $request)
    {
        $adminUser = auth()->guard('admin')->user();
        $schoolId = $adminUser ? $adminUser->school_id : null;
        $file = 'call_logs_' . now()->format('Ymd_His') . '.xlsx';
        return Excel::download(new \App\Exports\CallLogsExport($schoolId), $file);
    }

    /**
     * Import logs from Excel.
     */
    public function import(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:xlsx,csv']);
        $adminUser = auth()->guard('admin')->user();
        $schoolId = $adminUser ? $adminUser->school_id : null;
        Excel::import(new \App\Imports\CallLogsImport($schoolId), $request->file('file'));
        return back()->with('success','Import completed');
    }
} 