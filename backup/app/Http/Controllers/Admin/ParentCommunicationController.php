<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ParentCommunication;
use App\Models\ParentDetail;
use App\Models\StudentDetail;
use App\Models\User;
use App\Exports\ParentCommunicationExport;
use App\Imports\ParentCommunicationImport;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ParentCommunicationController extends Controller
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
            $query = ParentCommunication::with(['parentDetail.user', 'student.user', 'admin'])
                ->latest();

            // Apply filters
            if ($request->filled('communication_type')) {
                $query->byType($request->communication_type);
            }
            if ($request->filled('status')) {
                $query->byStatus($request->status);
            }
            if ($request->filled('priority')) {
                $query->byPriority($request->priority);
            }
            if ($request->filled('category')) {
                $query->byCategory($request->category);
            }
            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }
            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('parent_name', function ($row) {
                    return e($row->parentDetail->primary_contact_name ?? $row->parentDetail->user->name ?? 'N/A');
                })
                ->addColumn('student_name', function ($row) {
                    return $row->student ? e($row->student->first_name . ' ' . $row->student->last_name) : 'N/A';
                })
                ->addColumn('admin_name', function ($row) {
                    return $row->admin ? e($row->admin->name) : 'N/A';
                })
                ->addColumn('communication_type_badge', function ($row) {
                    $icons = [
                        'email' => '<i class="bx bx-envelope text-primary"></i>',
                        'sms' => '<i class="bx bx-message-square text-success"></i>',
                        'phone' => '<i class="bx bx-phone text-info"></i>',
                        'meeting' => '<i class="bx bx-calendar text-warning"></i>',
                        'letter' => '<i class="bx bx-file text-secondary"></i>',
                    ];
                    $icon = $icons[$row->communication_type] ?? '<i class="bx bx-message text-muted"></i>';
                    return $icon . ' ' . ucfirst($row->communication_type);
                })
                ->addColumn('status_badge', function ($row) {
                    return '<span class="badge ' . $row->status_badge . '">' . ucfirst($row->status) . '</span>';
                })
                ->addColumn('priority_badge', function ($row) {
                    return '<span class="badge ' . $row->priority_badge . '">' . ucfirst($row->priority) . '</span>';
                })
                ->addColumn('sent_date', function ($row) {
                    return $row->sent_at ? $row->sent_at->format('M d, Y H:i') : 'N/A';
                })
                ->addColumn('actions', function ($row) {
                    $buttons = '<div class="d-flex">';
                    $buttons .= '<a href="' . route('admin.parents.communication.show', $row->id) . '" class="text-info me-2" title="View"><i class="bx bx-show"></i></a>';
                    $buttons .= '<a href="' . route('admin.parents.communication.edit', $row->id) . '" class="text-primary me-2" title="Edit"><i class="bx bxs-edit"></i></a>';
                    $buttons .= '<a href="javascript:void(0);" data-id="' . $row->id . '" class="text-danger delete-communication" title="Delete"><i class="bx bx-trash"></i></a>';
                    $buttons .= '</div>';
                    return $buttons;
                })
                ->rawColumns(['communication_type_badge', 'status_badge', 'priority_badge', 'actions'])
                ->make(true);
        }

        $communicationTypes = ['email', 'sms', 'phone', 'meeting', 'letter'];
        $statuses = ['sent', 'delivered', 'read', 'failed'];
        $priorities = ['low', 'normal', 'high', 'urgent'];
        $categories = ['academic', 'behavior', 'attendance', 'fee', 'general'];

        return view('admin.parents.communication.index', compact('communicationTypes', 'statuses', 'priorities', 'categories'));
    }

    public function create()
    {
        $parents = ParentDetail::with('user')->get();
        $students = StudentDetail::with('user')->get();
        $admins = User::where('role', 'admin')->orWhere('role_id', 1)->get();
        
        $communicationTypes = ['email', 'sms', 'phone', 'meeting', 'letter'];
        $priorities = ['low', 'normal', 'high', 'urgent'];
        $categories = ['academic', 'behavior', 'attendance', 'fee', 'general'];

        return view('admin.parents.communication.create', compact('parents', 'students', 'admins', 'communicationTypes', 'priorities', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'parent_detail_id' => 'required|exists:parent_details,id',
            'student_id' => 'nullable|exists:student_details,id',
            'admin_id' => 'nullable|exists:users,id',
            'communication_type' => 'required|in:email,sms,phone,meeting,letter',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
            'priority' => 'required|in:low,normal,high,urgent',
            'category' => 'nullable|in:academic,behavior,attendance,fee,general',
            'communication_channel' => 'nullable|string|max:255',
            'cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['status'] = 'sent';
        $data['sent_at'] = now();
        $data['admin_id'] = $data['admin_id'] ?? auth()->id();

        $communication = ParentCommunication::create($data);

        // Here you would typically trigger the actual communication
        // (send email, SMS, etc.) based on the communication_type

        return redirect()->route('admin.parents.communication.index')
            ->with('success', 'Communication record created successfully.');
    }

    public function show($id)
    {
        $communication = ParentCommunication::with(['parentDetail.user', 'student.user', 'admin'])
            ->findOrFail($id);
        
        return view('admin.parents.communication.show', compact('communication'));
    }

    public function edit($id)
    {
        $communication = ParentCommunication::findOrFail($id);
        $parents = ParentDetail::with('user')->get();
        $students = StudentDetail::with('user')->get();
        $admins = User::where('role', 'admin')->orWhere('role_id', 1)->get();
        
        $communicationTypes = ['email', 'sms', 'phone', 'meeting', 'letter'];
        $priorities = ['low', 'normal', 'high', 'urgent'];
        $categories = ['academic', 'behavior', 'attendance', 'fee', 'general'];
        $statuses = ['sent', 'delivered', 'read', 'failed'];

        return view('admin.parents.communication.edit', compact('communication', 'parents', 'students', 'admins', 'communicationTypes', 'priorities', 'categories', 'statuses'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'parent_detail_id' => 'required|exists:parent_details,id',
            'student_id' => 'nullable|exists:student_details,id',
            'admin_id' => 'nullable|exists:users,id',
            'communication_type' => 'required|in:email,sms,phone,meeting,letter',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
            'status' => 'required|in:sent,delivered,read,failed',
            'priority' => 'required|in:low,normal,high,urgent',
            'category' => 'nullable|in:academic,behavior,attendance,fee,general',
            'communication_channel' => 'nullable|string|max:255',
            'cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'response' => 'nullable|string',
        ]);

        $communication = ParentCommunication::findOrFail($id);
        $data = $request->all();

        // Update timestamps based on status changes
        if ($request->status === 'delivered' && $communication->status !== 'delivered') {
            $data['delivered_at'] = now();
        }
        if ($request->status === 'read' && $communication->status !== 'read') {
            $data['read_at'] = now();
        }
        if ($request->filled('response') && !$communication->response) {
            $data['response_at'] = now();
        }

        $communication->update($data);

        return redirect()->route('admin.parents.communication.index')
            ->with('success', 'Communication record updated successfully.');
    }

    public function destroy($id)
    {
        $communication = ParentCommunication::findOrFail($id);
        $communication->delete();

        return response()->json(['message' => 'Communication record deleted successfully.']);
    }

    public function export(Request $request)
    {
        $fileName = 'parent_communications_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        return Excel::download(new ParentCommunicationExport($request), $fileName);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new ParentCommunicationImport, $request->file('file'));
            
            return redirect()->route('admin.parents.communication.index')
                ->with('success', 'Communications imported successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.parents.communication.index')
                ->with('error', 'Error importing communications: ' . $e->getMessage());
        }
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,update_status,update_priority',
            'ids' => 'required|array',
            'ids.*' => 'exists:parent_communications,id',
        ]);

        $ids = $request->ids;

        switch ($request->action) {
            case 'delete':
                ParentCommunication::whereIn('id', $ids)->delete();
                $message = 'Selected communications deleted successfully.';
                break;

            case 'update_status':
                $request->validate(['status' => 'required|in:sent,delivered,read,failed']);
                $status = $request->status;
                
                ParentCommunication::whereIn('id', $ids)->update([
                    'status' => $status,
                    'delivered_at' => $status === 'delivered' ? now() : null,
                    'read_at' => $status === 'read' ? now() : null,
                ]);
                $message = 'Status updated successfully.';
                break;

            case 'update_priority':
                $request->validate(['priority' => 'required|in:low,normal,high,urgent']);
                
                ParentCommunication::whereIn('id', $ids)->update(['priority' => $request->priority]);
                $message = 'Priority updated successfully.';
                break;
        }

        return response()->json(['message' => $message]);
    }

    public function dashboard()
    {
        // Communication statistics
        $totalCommunications = ParentCommunication::count();
        $recentCommunications = ParentCommunication::recent(7)->count();
        $pendingResponses = ParentCommunication::whereNull('response')->count();
        $failedCommunications = ParentCommunication::byStatus('failed')->count();

        // Communication by type
        $communicationsByType = ParentCommunication::select('communication_type', DB::raw('count(*) as count'))
            ->groupBy('communication_type')
            ->get();

        // Communication by status
        $communicationsByStatus = ParentCommunication::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        // Recent communications
        $latestCommunications = ParentCommunication::with(['parentDetail.user', 'student.user'])
            ->latest()
            ->limit(10)
            ->get();

        // Cost analysis
        $totalCost = ParentCommunication::sum('cost');
        $monthlyCost = ParentCommunication::whereMonth('created_at', now()->month)
            ->sum('cost');

        return view('admin.parents.communication.dashboard', compact(
            'totalCommunications',
            'recentCommunications',
            'pendingResponses',
            'failedCommunications',
            'communicationsByType',
            'communicationsByStatus',
            'latestCommunications',
            'totalCost',
            'monthlyCost'
        ));
    }

    public function resend($id)
    {
        $communication = ParentCommunication::findOrFail($id);
        
        // Reset status and timestamps
        $communication->update([
            'status' => 'sent',
            'sent_at' => now(),
            'delivered_at' => null,
            'read_at' => null,
        ]);

        // Here you would trigger the actual resend logic
        // based on communication_type

        return redirect()->route('admin.parents.communication.show', $id)
            ->with('success', 'Communication resent successfully.');
    }

    public function markAsRead($id)
    {
        $communication = ParentCommunication::findOrFail($id);
        
        $communication->update([
            'status' => 'read',
            'read_at' => now(),
        ]);

        return response()->json(['message' => 'Communication marked as read.']);
    }

    public function getParentStudents($parentId)
    {
        $parent = ParentDetail::with('students.user')->findOrFail($parentId);
        $students = $parent->students->map(function ($student) {
            return [
                'id' => $student->id,
                'name' => $student->first_name . ' ' . $student->last_name,
                'class' => $student->class ?? 'N/A',
            ];
        });

        return response()->json($students);
    }
}
