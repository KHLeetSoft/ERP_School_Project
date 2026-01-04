<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Excel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Notification;
use Yajra\DataTables\Facades\DataTables;
use App\Models\AdmissionEnquiry;
use App\Models\User;

class AdmissionEnquiryController extends Controller
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
     * List enquiries in DataTable or show index view.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $adminUser = auth()->guard('admin')->user();
            $adminId   = $request->input('admin_id', $adminUser ? $adminUser->id : null);

            $query = AdmissionEnquiry::query()->latest();
            // For now, let's show all enquiries regardless of admin_id for testing
            // if ($adminId) {
            //     $query->where('admin_id', $adminId);
            // }
            // Filters
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            if ($request->filled('class')) {
                $query->where('class', $request->class);
            }
            if ($request->filled('date_from') && $request->filled('date_to')) {
                $query->whereBetween('created_at', [$request->date_from, $request->date_to]);
            }
            $rows = $query->orderBy('id', 'desc')->get();
            
            // Debug: Log the data
            \Log::info('AdmissionEnquiry Data:', [
                'count' => $rows->count(),
                'admin_id' => $adminId,
                'admin_user' => $adminUser ? $adminUser->id : 'null'
            ]);

            return DataTables::of($rows)
                ->addIndexColumn()
                ->addColumn('student_name', function ($row) {
                    return '<a href="' . route('admin.office.enquiry.show', $row->id) . '" class="link me-2" title="Details">' . e($row->student_name) . '</a>';
                })
                ->addColumn('parent_name', fn($row) => e($row->parent_name))
                ->addColumn('class', fn($row) => e($row->class ?? '-'))
                ->addColumn('status', function ($row) {
                    $badge = match($row->status) {
                        'Converted'   => 'badge-light-success',
                        'In Progress' => 'badge-light-warning',
                        'Closed'      => 'badge-light-secondary',
                        default       => 'badge-light-info',
                    };
                    return '<span class="badge badge-pill ' . $badge . '">' . e($row->status) . '</span>';
                })
                ->editColumn('created_at', fn($row) => $row->created_at?->format('Y-m-d'))
                ->addColumn('action', function ($row) {
                    $html  = '<div class="d-flex align-items-center gap-1">';
                    // View
                    $html .= '<a href="' . route('admin.office.enquiry.show', $row->id) . '" class="text-info" title="View"><i class="bx bx-show"></i></a>';
                    // Edit
                    $html .= '<a href="' . route('admin.office.enquiry.edit', $row->id) . '" class="text-primary" title="Edit"><i class="bx bxs-edit"></i></a>';
                    // Mark Contacted
                    $html .= '<a href="javascript:void(0);" data-id="' . $row->id . '" class="text-success mark-contacted-btn" title="Mark Contacted"><i class="bx bx-phone-call"></i></a>';
                    // Change Status dropdown trigger
                    $html .= '<div class="dropdown table-dropdown">';
                    $html .= '  <a class="text-warning dropdown-toggle" id="statusDD' . $row->id . '" data-bs-toggle="dropdown" aria-expanded="false" title="Change Status"><i class="bx bx-transfer"></i></a>';
                    $html .= '  <ul class="dropdown-menu" aria-labelledby="statusDD' . $row->id . '">';
                    foreach (["New","In Progress","Converted","Closed"] as $status) {
                        $html .= '<li><a class="dropdown-item change-status-btn" href="javascript:void(0);" data-id="' . $row->id . '" data-status="' . $status . '">' . $status . '</a></li>';
                    }
                    $html .= '  </ul></div>';
                    // Delete
                    $html .= '<a href="javascript:void(0);" data-id="' . $row->id . '" class="text-danger delete-enquiry-btn" title="Delete"><i class="bx bx-trash"></i></a>';
                    $html .= '</div>';
                    return $html;
                })
                ->rawColumns(['student_name','status','action'])
                ->make(true);
        }
        return view('admin.office.enquiry.index');
    }

    public function create()
    {
        return view('admin.office.enquiry.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_name'   => 'required|string|max:255',
            'parent_name'    => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
            'email'          => 'nullable|email',
            'address'        => 'nullable|string',
            'class'          => 'nullable|string|max:50',
            'date'           => 'nullable|date',
            'note'           => 'nullable|string',
            'status'         => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();
        $data['admin_id'] = auth()->guard('admin')->id();
        AdmissionEnquiry::create($data);

        return redirect()->route('admin.office.enquiry.index')->with('success', 'Enquiry added');
    }

    public function show($id)
    {
        $enquiry = AdmissionEnquiry::where('admin_id', auth()->guard('admin')->id())->findOrFail($id);
        return view('admin.office.enquiry.show', compact('enquiry'));
    }

    public function edit($id)
    {
        $enquiry = AdmissionEnquiry::where('admin_id', auth()->guard('admin')->id())->findOrFail($id);
        return view('admin.office.enquiry.edit', compact('enquiry'));
    }

    public function update(Request $request, $id)
    {
        $enquiry = AdmissionEnquiry::where('admin_id', auth()->guard('admin')->id())->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'student_name'   => 'required|string|max:255',
            'parent_name'    => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
            'email'          => 'nullable|email',
            'address'        => 'nullable|string',
            'class'          => 'nullable|string|max:50',
            'date'           => 'nullable|date',
            'note'           => 'nullable|string',
            'status'         => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $enquiry->update($validator->validated());
        return redirect()->route('admin.office.enquiry.index')->with('success', 'Updated');
    }

    public function destroy($id)
    {
        $enquiry = AdmissionEnquiry::where('admin_id', auth()->guard('admin')->id())->findOrFail($id);
        $enquiry->delete();
        return response()->json(['message' => 'Deleted']);
    }

    /* ---------- Extra utilities ---------- */

    public function changeStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|string']);
        $enquiry = AdmissionEnquiry::where('admin_id', auth()->guard('admin')->id())->findOrFail($id);
        $enquiry->update(['status' => $request->status]);
        return response()->json(['message' => 'Status updated']);
    }

    // public function storeFollowUp(Request $request, $id)
    // {
    //     $request->validate([
    //         'note'          => 'required|string',
    //         'next_follow_up'=> 'nullable|date',
    //     ]);
    //     $enquiry = AdmissionEnquiry::where('admin_id', auth()->guard('admin')->id())->findOrFail($id);
    //     $enquiry->followUps()->create([
    //         'note'           => $request->note,
    //         'next_follow_up' => $request->next_follow_up,
    //         'user_id'        => auth()->id(),
    //     ]);
    //     return back()->with('success', 'Follow-up saved');
    // }

    // public function markAsContacted($id)
    // {
    //     $enquiry = AdmissionEnquiry::where('admin_id', auth()->guard('admin')->id())->findOrFail($id);
    //     $enquiry->update(['first_contacted_at' => Carbon::now()]);
    //     return response()->json(['message' => 'Marked contacted']);
    // }

    // public function bulkDelete(Request $request)
    // {
    //     $request->validate(['ids' => 'required|array']);
    //     AdmissionEnquiry::where('admin_id', auth()->guard('admin')->id())->whereIn('id', $request->ids)->delete();
    //     return response()->json(['message' => 'Deleted selected']);
    // }

     public function export(Request $request)
     {
         $file = 'enquiries_' . now()->format('Ymd_His') . '.xlsx, csv';
         return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\AdmissionEnquiryExport($request->all()), $file);
     }

     public function import(Request $request)
     {
             $request->validate(['file' => 'required|file|mimes:xlsx,csv']);
             \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\AdmissionEnquiryImport, $request->file('file'));
             return back()->with('success', 'Import done');
     }

    // public function assignCounselor(Request $request)
    // {
    //     $request->validate([
    //         'enquiry_id'   => 'required|exists:admission_enquiries,id',
    //         'counselor_id' => 'required|exists:users,id',
    //     ]);
    //     $enquiry = AdmissionEnquiry::where('admin_id', auth()->guard('admin')->id())->findOrFail($request->enquiry_id);
    //     $enquiry->update(['counselor_id' => $request->counselor_id]);
    //     return response()->json(['message' => 'Counselor assigned']);
    // }

     public function dashboard()
     {
         $adminId = auth()->guard('admin')->id();
         $base    = AdmissionEnquiry::where('admin_id', $adminId);
        $total      = $base->count();
         $converted  = (clone $base)->where('status', 'Converted')->count();
         $inProgress = (clone $base)->where('status', 'In Progress')->count();
         $closed     = (clone $base)->where('status', 'Closed')->count();

         $monthlyStats = (clone $base)->select(DB::raw('MONTH(created_at) as m'), DB::raw('count(*) as total'))
             ->whereYear('created_at', now()->year)
             ->groupBy('m')
            ->pluck('total', 'm');

        return view('admin.office.enquiry.dashboard', compact('total','converted','inProgress','closed','monthlyStats'));
    }
} 