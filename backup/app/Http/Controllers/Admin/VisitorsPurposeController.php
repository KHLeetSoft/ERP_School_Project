<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use App\Models\VisitorsPurpose;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\VisitorsPurposeExport;
use App\Imports\VisitorsPurposeImport;

class VisitorsPurposeController extends Controller
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

            // ✅ Allow only logged-in Admins (role_id = 2)
            if (!$adminUser || $adminUser->role_id != 2) {
                return DataTables::of(collect([]))->make(true);
            }
    
            // ✅ Get visitors of same school as admin
            $userId = auth()->guard('admin')->id();
            $query = VisitorsPurpose::where('user_id', $userId)->latest();
            
            // Apply filters if provided
            if ($request->has('status') && $request->status != '') {
                $query->where('status', $request->status == 'active' ? 1 : 0);
            }
            
            $rows = $query->get();
            
            // Debug: Log the data
            \Log::info('VisitorsPurpose Data:', [
                'count' => $rows->count(),
                'admin_user' => $adminUser ? $adminUser->id : 'null',
                'user_id' => $userId,
                'query_sql' => $query->toSql(),
                'query_bindings' => $query->getBindings()
            ]);
            
            return DataTables::of($rows)
                ->addIndexColumn()
                ->addColumn('name', fn($row) =>
                    '<a href="' . route('admin.office.visitorspurpose.show', $row->id) . '" class="link">' . e($row->name) . '</a>'
                )
                ->addColumn('description', fn($row) => e($row->description))
                ->addColumn('status', function($row) {
                    $statusClass = $row->status ? 'success' : 'danger';
                    $statusText = $row->status ? 'Active' : 'Inactive';
                    return '<span class="badge bg-' . $statusClass . '">' . $statusText . '</span>';
                })
                ->addColumn('action', function($row) {
                    $buttons = '<div class="d-flex justify-content">';
                    $buttons .= '<a href="' . route('admin.office.visitorspurpose.show', $row->id) . '" class="text-info me-2"><i class="bx bx-show"></i></a>';
                    $buttons .= '<a href="' . route('admin.office.visitorspurpose.edit', $row->id) . '" class="text-primary me-2"><i class="bx bxs-edit"></i></a>';
                    $buttons .= '<a href="javascript:void(0);" data-id="' . $row->id . '" class="text-success me-2 mark-out-btn" title="Mark Out"><i class="bx bx-log-out"></i></a>';
                    $buttons .= '<a href="javascript:void(0);" data-id="' . $row->id . '" class="text-danger delete-visitor-btn" title="Delete"><i class="bx bx-trash"></i></a>';
                    $buttons .= '</div>';
                    return $buttons;
                })
                ->rawColumns(['name', 'status', 'action'])
                ->make(true);
        }
    
        return view('admin.office.visitorspurpose.index');
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
                return view('admin.office.visitorspurpose.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
                
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable',
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        
        $data = $validator->validated();
        $data['user_id'] = auth()->guard('admin')->id();
        $data['school_id'] = auth()->guard('admin')->user()->school_id;
        $data['status'] = $request->has('status') ? 1 : 0;
        
        VisitorsPurpose::create($data);
        
        return redirect()->route('admin.office.visitorspurpose.index')
            ->with('success', 'Visitor purpose created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $purpose = VisitorsPurpose::where('user_id', auth()->guard('admin')->id())->findOrFail($id);
        return view('admin.office.visitorspurpose.show', compact('purpose'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {      
        $purpose = VisitorsPurpose::where('user_id', auth()->guard('admin')->id())->findOrFail($id);
        return view('admin.office.visitorspurpose.edit', compact('purpose'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {       
        $purpose = VisitorsPurpose::where('user_id', auth()->guard('admin')->id())->findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable',
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        
        $data = $validator->validated();
        $data['status'] = $request->has('status') ? 1 : 0;
        
        $purpose->update($data);
        
        return redirect()->route('admin.office.visitorspurpose.index')
            ->with('success', 'Visitor purpose updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
              
        $purpose = VisitorsPurpose::where('user_id', auth()->guard('admin')->id())->findOrFail($id);
        $purpose->delete();
        
        return redirect()->route('admin.office.visitorspurpose.index')
            ->with('success', 'Visitor purpose deleted successfully');
    }

    /**
     * Export visitor purposes to Excel.
     */
    public function export()
    {       
        $userId = auth()->guard('admin')->id();
        $file = 'visitors_purposes_' . now()->format('Ymd_His') . '.xlsx';
        return Excel::download(new VisitorsPurposeExport($userId), $file);
    }

    /**
     * Import visitor purposes from Excel.
     */
    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx,csv'
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator);
        }
        
        $userId = auth()->guard('admin')->id();
        Excel::import(new VisitorsPurposeImport($userId), $request->file('file'));
        
        return back()->with('success', 'Visitor purposes imported successfully');
    }
}