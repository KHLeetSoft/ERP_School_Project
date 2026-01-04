<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Visitor;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class VisitorController extends Controller
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
            try {
                $adminUser = auth()->guard('admin')->user();
                $query = Visitor::query()->latest();

                if ($adminUser) {
                    $query->where('user_id', $adminUser->id)
                        ->where('school_id', $adminUser->school_id);
                }

                if ($request->filled('purpose')) {
                    $query->where('purpose', $request->purpose);
                }

                if ($request->filled('date_from') && $request->filled('date_to')) {
                    $query->whereBetween('date', [$request->date_from, $request->date_to]);
                }

                $rows = $query->get();
                
                // Debug: Log the data
                \Log::info('Visitor Data:', [
                    'count' => $rows->count(),
                    'admin_user' => $adminUser ? $adminUser->id : 'null',
                    'school_id' => $adminUser ? $adminUser->school_id : 'null',
                    'query_sql' => $query->toSql(),
                    'query_bindings' => $query->getBindings()
                ]);

                return DataTables::of($rows)
                ->addIndexColumn()
                ->addColumn('visitor_name', fn($row) =>
                    '<a href="' . route('admin.office.visitors.show', $row->id) . '" class="link">' . e($row->visitor_name) . '</a>'
                )
                ->addColumn('purpose', fn($row) => e($row->purpose))
                ->addColumn('phone', fn($row) => e($row->phone))
                ->addColumn('date', fn($row) => $row->date)
                ->addColumn('in_time', fn($row) => $row->in_time)
                ->addColumn('out_time', fn($row) => $row->out_time)
                ->addColumn('note', fn($row) => e($row->note))
                ->addColumn('action', function ($row) {
                    $buttons = '<div class="d-flex justify-content">';
                    $buttons .= '<a href="' . route('admin.office.visitors.show', $row->id) . '" class="text-info me-2"><i class="bx bx-show"></i></a>';
                    $buttons .= '<a href="' . route('admin.office.visitors.edit', $row->id) . '" class="text-primary me-2"><i class="bx bxs-edit"></i></a>';
                    $buttons .= '<a href="javascript:void(0);" data-id="' . $row->id . '" class="text-success me-2 mark-out-btn" title="Mark Out"><i class="bx bx-log-out"></i></a>';
                    $buttons .= '<a href="javascript:void(0);" data-id="' . $row->id . '" class="text-danger delete-visitor-btn" title="Delete"><i class="bx bx-trash"></i></a>';
                    $buttons .= '</div>';
                    return $buttons;
                })
                ->rawColumns(['visitor_name', 'action'])
                ->make(true);
            } catch (\Exception $e) {
                return response()->json([
                    'error' => 'An error occurred while loading data: ' . $e->getMessage()
                ], 500);
            }
        }
    
        return view('admin.office.visitors.index');
    }
    
    
    public function create()
    {
        return view('admin.office.visitors.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'visitor_name' => 'required|string|max:255',
            'purpose'      => 'nullable|string',
            'phone'        => 'nullable|string',
            'date'         => 'nullable|date',
            'in_time'      => 'nullable',
            'out_time'     => 'nullable',
            'note'         => 'nullable|string',
        ]);
        if($validator->fails()) return back()->withErrors($validator)->withInput();
        $data = $validator->validated();
        $data['user_id'] = auth()->guard('admin')->id();
        Visitor::create($data);
        return redirect()->route('admin.office.visitors.index')->with('success','Visitor added');
    }

    public function show($id)
    {
        $visitor = Visitor::where('user_id', auth()->guard('admin')->id())->findOrFail($id);
        return view('admin.office.visitors.show', compact('visitor'));
    }

    public function edit($id)
    {
        $visitor = Visitor::where('user_id', auth()->guard('admin')->id())->findOrFail($id);
        return view('admin.office.visitors.edit', compact('visitor'));
    }

    public function update(Request $request, $id)
    {
        $visitor = Visitor::where('user_id', auth()->guard('admin')->id())->findOrFail($id);
        $validator = Validator::make($request->all(), [
            'visitor_name' => 'required|string|max:255',
            'purpose'      => 'nullable|string',
            'phone'        => 'nullable|string',
            'date'         => 'nullable|date',
            'in_time'      => 'nullable',
            'out_time'     => 'nullable',
            'note'         => 'nullable|string',
        ]);
        if($validator->fails()) return back()->withErrors($validator)->withInput();
        $visitor->update($validator->validated());
        return redirect()->route('admin.office.visitors.index')->with('success','Updated');
    }

    public function destroy($id)
    {
        $visitor = Visitor::where('user_id', auth()->guard('admin')->id())->findOrFail($id);
        $visitor->delete();
        return response()->json(['message'=>'Deleted']);
    }

    /**
     * Mark visitor out (set out_time to now) via AJAX.
     */
    public function markOut($id)
    {
        $visitor = Visitor::where('user_id', auth()->guard('admin')->id())->findOrFail($id);
        $visitor->update(['out_time' => Carbon::now()->format('H:i:s')]);
        return response()->json(['message' => 'Marked out']);
    }

    public function export(Request $request)
    {
        $userId = auth()->guard('admin')->id();
        $file = 'visitors_' . now()->format('Ymd_His') . '.xlsx';
        return Excel::download(new \App\Exports\VisitorsExport($userId), $file);
    }

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:xlsx,csv']);
        $userId = auth()->guard('admin')->id();
        Excel::import(new \App\Imports\VisitorsImport($userId), $request->file('file'));
        return back()->with('success','Import completed');
    }
}