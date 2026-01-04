<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use App\Models\ComplaintBox;
use App\Models\User;
use App\Models\School;

class AdminComplaintBoxController extends Controller
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

        $userId = auth()->guard('admin')->id();
        $query = ComplaintBox::where('user_id', $userId)->latest();

        if ($request->filled('purpose')) {
            $query->where('purpose', $request->purpose);
        }

        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('date', [$request->date_from, $request->date_to]);
        }

        // Debug: Log the data
        \Log::info('ComplaintBox Data:', [
            'admin_user' => $adminUser ? $adminUser->id : 'null',
            'user_id' => $userId,
            'query_sql' => $query->toSql(),
            'query_bindings' => $query->getBindings(),
            'request_data' => $request->all()
        ]);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('complain_by', function($row) {
                $cleaned = preg_replace('/[^a-zA-Z0-9\s]/', '', $row->complain_by); // Remove special chars
                $withDash = preg_replace('/\s+/', '-', trim($cleaned));             // Replace space with dash
                return e($withDash);
             })
            ->addColumn('title', fn($row) => e($row->title))
            ->addColumn('description', fn($row) => \Str::limit(strip_tags($row->description), 50)) // short preview
            ->addColumn('status', fn($row) => ucfirst($row->status))
            ->addColumn('created_at', fn($row) => $row->created_at->format('d-m-Y H:i'))
            ->addColumn('action', function ($row) {
                $buttons = '<div class="btn-group">';
                $buttons .= '<a href="' . route('admin.office.complaintbox.show', $row->id) . '" class="text-info me-2"><i class="bx bx-show"></i></a>';
                $buttons .= '<a href="' . route('admin.office.complaintbox.edit', $row->id) . '" class="text-primary me-2"><i class="bx bxs-edit"></i></a>';
                $buttons .= '<a href="javascript:void(0);" data-id="' . $row->id . '" class="text-danger delete-complaint-btn" title="Delete"><i class="bx bx-trash"></i></a>';
                $buttons .= '</div>';
                return $buttons;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    return view('admin.office.complaintbox.index');
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
{
    $adminUser = auth()->guard('admin')->user();

    if (!$adminUser || $adminUser->role_id != 2) {
        abort(403, 'Unauthorized action.');
    }

    return view('admin.office.complaintbox.create');
}


    /**
     * Store a newly created resource in storage.
     */
  public function store(Request $request)
{
    $adminUser = auth()->guard('admin')->user();

    if (!$adminUser || $adminUser->role_id != 2) {
        abort(403, 'Unauthorized action.');
    }

    $validated = $request->validate([
        'complain_by' => 'required|string|max:255',
        'phone'       => 'nullable|string|max:20',
        'purpose'     => 'required|string|max:255',
        'date'        => 'required|date',
        'note'        => 'nullable|string',
        'attachment'  => 'nullable|file|mimes:jpg,jpeg,png,pdf,docx|max:2048',
    ]);

    $complaint = new ComplaintBox();
    $complaint->user_id     = $adminUser->id;
    $complaint->complain_by = $validated['complain_by'];
    $complaint->phone       = $validated['phone'];
    $complaint->purpose     = $validated['purpose'];
    $complaint->date        = $validated['date'];
    $complaint->note        = $validated['note'];

    // Handle attachment upload
    if ($request->hasFile('attachment')) {
        $file = $request->file('attachment');
        $filename = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('complaint_attachments', $filename, 'public');
        $complaint->attachment = $filePath;
    }

    $complaint->save();

    return redirect()->route('admin.office.complaintbox.index')
                     ->with('success', 'Complaint submitted successfully!');
}


    /**
     * Display the specified resource.
     */
   public function show(string $id)
{
    $adminUser = auth()->guard('admin')->user();

    if (!$adminUser || $adminUser->role_id != 2) {
        abort(403, 'Unauthorized action.');
    }

    $complaint = ComplaintBox::where('id', $id)
                    ->where('user_id', $adminUser->id)
                    ->firstOrFail();

    return view('admin.office.complaintbox.show', compact('complaint'));
}


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $adminUser = auth()->guard('admin')->user();

        if (!$adminUser || $adminUser->role_id != 2) {
            abort(403, 'Unauthorized action.');
        }

        $complaint = ComplaintBox::where('id', $id)
                        ->where('user_id', $adminUser->id)
                        ->firstOrFail();

        return view('admin.office.complaintbox.edit', compact('complaint'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
{
    $adminUser = auth()->guard('admin')->user();

    if (!$adminUser || $adminUser->role_id != 2) {
        abort(403, 'Unauthorized action.');
    }

    $complaint = ComplaintBox::where('id', $id)
                    ->where('user_id', $adminUser->id)
                    ->firstOrFail();

    $validated = $request->validate([
        'complain_by' => 'required|string|max:255',
        'phone'       => 'nullable|string|max:20',
        'purpose'     => 'required|string|max:255',
        'date'        => 'required|date',
        'note'        => 'nullable|string',
        'attachment'  => 'nullable|file|mimes:jpg,jpeg,png,pdf,docx|max:2048',
    ]);

    $complaint->complain_by = $validated['complain_by'];
    $complaint->phone       = $validated['phone'];
    $complaint->purpose     = $validated['purpose'];
    $complaint->date        = $validated['date'];
    $complaint->note        = $validated['note'];

    // If new file uploaded, replace old
    if ($request->hasFile('attachment')) {
        // Delete old file if exists
        if ($complaint->attachment && \Storage::disk('public')->exists($complaint->attachment)) {
            \Storage::disk('public')->delete($complaint->attachment);
        }

        $file = $request->file('attachment');
        $filename = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('complaint_attachments', $filename, 'public');
        $complaint->attachment = $filePath;
    }

    $complaint->save();

    return redirect()->route('admin.office.complaintbox.index')
                     ->with('success', 'Complaint updated successfully!');
}


    /**
     * Remove the specified resource from storage.
     */
  public function destroy(string $id)
        {
            $adminUser = auth()->guard('admin')->user();

            if (!$adminUser || $adminUser->role_id != 2) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $complaint = ComplaintBox::where('id', $id)
                            ->where('user_id', $adminUser->id)
                            ->firstOrFail();

            // Delete attached file if exists
            if ($complaint->attachment && \Storage::disk('public')->exists($complaint->attachment)) {
                \Storage::disk('public')->delete($complaint->attachment);
            }

            $complaint->delete();

            return response()->json(['success' => 'Complaint deleted successfully!']);
        }

    public function export(Request $request)
    {
        $userId = auth()->guard('admin')->id();
        $file = 'complaints_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new \App\Exports\ComplaintBoxExport($userId), $file);
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv',
        ]);

        $userId = auth()->guard('admin')->id();

        Excel::import(new \App\Imports\ComplaintBoxImport($userId), $request->file('file'));

        return back()->with('success', 'Complaint import completed.');
    }
}
