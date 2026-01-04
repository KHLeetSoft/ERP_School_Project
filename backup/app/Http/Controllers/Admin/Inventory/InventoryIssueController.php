<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use App\Models\InventoryIssue;
use App\Models\InventoryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InventoryIssuesExport;
use App\Imports\InventoryIssuesImport;

class InventoryIssueController extends Controller
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
        $query = InventoryIssue::with('inventoryItem');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('reported_by', 'like', "%{$search}%")
                  ->orWhere('assigned_to', 'like', "%{$search}%")
                  ->orWhereHas('inventoryItem', function($itemQuery) use ($search) {
                      $itemQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('sku', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Filter by issue type
        if ($request->filled('issue_type')) {
            $query->where('issue_type', $request->issue_type);
        }

        // Filter by overdue issues
        if ($request->filled('overdue') && $request->overdue == '1') {
            $query->where(function($q) {
                $q->where('status', '!=', 'closed')
                  ->where('status', '!=', 'resolved')
                  ->where(function($subQ) {
                      $subQ->where('priority', 'critical')
                           ->whereRaw('DATEDIFF(NOW(), issue_date) > 1')
                           ->orWhere('priority', 'high')
                           ->whereRaw('DATEDIFF(NOW(), issue_date) > 3')
                           ->orWhere('priority', 'medium')
                           ->whereRaw('DATEDIFF(NOW(), issue_date) > 7')
                           ->orWhere('priority', 'low')
                           ->whereRaw('DATEDIFF(NOW(), issue_date) > 14');
                  });
            });
        }

        $inventoryIssues = $query->orderBy('priority', 'desc')
                                ->orderBy('created_at', 'desc')
                                ->paginate(15);

        // Get filter options
        $statuses = InventoryIssue::STATUSES;
        $priorities = InventoryIssue::PRIORITIES;
        $issueTypes = InventoryIssue::ISSUE_TYPES;

        // Statistics
        $stats = [
            'total_issues' => InventoryIssue::count(),
            'open_issues' => InventoryIssue::open()->count(),
            'in_progress_issues' => InventoryIssue::inProgress()->count(),
            'resolved_issues' => InventoryIssue::resolved()->count(),
            'closed_issues' => InventoryIssue::closed()->count(),
            'critical_issues' => InventoryIssue::byPriority('critical')->whereIn('status', ['open', 'in_progress'])->count(),
            'overdue_issues' => $this->getOverdueIssuesCount(),
        ];

        return view('admin.inventory.issues.index', compact('inventoryIssues', 'statuses', 'priorities', 'issueTypes', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $inventoryItems = InventoryItem::active()->orderBy('name')->get();
        $issueTypes = InventoryIssue::ISSUE_TYPES;
        $priorities = InventoryIssue::PRIORITIES;
        
        return view('admin.inventory.issues.create', compact('inventoryItems', 'issueTypes', 'priorities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'inventory_item_id' => 'required|exists:inventory_items,id',
            'issue_type' => 'required|in:' . implode(',', array_keys(InventoryIssue::ISSUE_TYPES)),
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:' . implode(',', array_keys(InventoryIssue::PRIORITIES)),
            'quantity_affected' => 'required|integer|min:1',
            'estimated_cost' => 'nullable|numeric|min:0',
            'issue_date' => 'required|date|before_or_equal:today',
            'reported_by' => 'nullable|string|max:255',
            'assigned_to' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'attachments.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf,doc,docx|max:5120'
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        // Handle file attachments
        if ($request->hasFile('attachments')) {
            $attachments = [];
            foreach ($request->file('attachments') as $file) {
                $filename = Str::slug($request->title) . '_' . time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('inventory_issues', $filename, 'public');
                $attachments[] = $path;
            }
            $data['attachments'] = $attachments;
        }

        InventoryIssue::create($data);

        return redirect()->route('admin.inventory.issues.index')
                        ->with('success', 'Inventory issue created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(InventoryIssue $issue)
    {
        $issue->load('inventoryItem');
        $inventoryIssue = $issue; // For view compatibility
        return view('admin.inventory.issues.show', compact('inventoryIssue'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InventoryIssue $issue)
    {
        $inventoryItems = InventoryItem::active()->orderBy('name')->get();
        $issueTypes = InventoryIssue::ISSUE_TYPES;
        $priorities = InventoryIssue::PRIORITIES;
        $statuses = InventoryIssue::STATUSES;
        $inventoryIssue = $issue; // For view compatibility
        
        return view('admin.inventory.issues.edit', compact('inventoryIssue', 'inventoryItems', 'issueTypes', 'priorities', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InventoryIssue $issue)
    {
        $request->validate([
            'inventory_item_id' => 'required|exists:inventory_items,id',
            'issue_type' => 'required|in:' . implode(',', array_keys(InventoryIssue::ISSUE_TYPES)),
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:' . implode(',', array_keys(InventoryIssue::PRIORITIES)),
            'status' => 'required|in:' . implode(',', array_keys(InventoryIssue::STATUSES)),
            'quantity_affected' => 'required|integer|min:1',
            'estimated_cost' => 'nullable|numeric|min:0',
            'issue_date' => 'required|date',
            'resolved_date' => 'nullable|date|after_or_equal:issue_date',
            'resolution_notes' => 'nullable|string',
            'reported_by' => 'nullable|string|max:255',
            'assigned_to' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'attachments.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf,doc,docx|max:5120'
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        // Handle new file attachments
        if ($request->hasFile('attachments')) {
            $existingAttachments = $issue->attachments ?? [];
            $newAttachments = [];
            
            foreach ($request->file('attachments') as $file) {
                $filename = Str::slug($request->title) . '_' . time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('inventory_issues', $filename, 'public');
                $newAttachments[] = $path;
            }
            
            $data['attachments'] = array_merge($existingAttachments, $newAttachments);
        }

        $issue->update($data);

        return redirect()->route('admin.inventory.issues.index')
                        ->with('success', 'Inventory issue updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InventoryIssue $issue)
    {
        // Delete attachments if they exist
        if ($issue->attachments) {
            foreach ($issue->attachments as $attachment) {
                if (Storage::disk('public')->exists($attachment)) {
                    Storage::disk('public')->delete($attachment);
                }
            }
        }

        $issue->delete();

        return redirect()->route('admin.inventory.issues.index')
                        ->with('success', 'Inventory issue deleted successfully.');
    }

    /**
     * Update issue status
     */
    public function updateStatus(Request $request, InventoryIssue $issue)
    {
        $request->validate([
            'status' => 'required|in:' . implode(',', array_keys(InventoryIssue::STATUSES)),
            'resolution_notes' => 'nullable|string'
        ]);

        $data = $request->only(['status', 'resolution_notes']);
        
        if ($request->status === 'resolved' || $request->status === 'closed') {
            $data['resolved_date'] = now()->toDateString();
        }

        $issue->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Issue status updated successfully.',
            'status' => $issue->status
        ]);
    }

    /**
     * Get overdue issues count
     */
    private function getOverdueIssuesCount()
    {
        return InventoryIssue::whereIn('status', ['open', 'in_progress'])
            ->where(function($query) {
                $query->where(function($q) {
                    $q->where('priority', 'critical')
                      ->whereRaw('DATEDIFF(NOW(), issue_date) > 1');
                })->orWhere(function($q) {
                    $q->where('priority', 'high')
                      ->whereRaw('DATEDIFF(NOW(), issue_date) > 3');
                })->orWhere(function($q) {
                    $q->where('priority', 'medium')
                      ->whereRaw('DATEDIFF(NOW(), issue_date) > 7');
                })->orWhere(function($q) {
                    $q->where('priority', 'low')
                      ->whereRaw('DATEDIFF(NOW(), issue_date) > 14');
                });
            })->count();
    }

    /**
     * Export inventory issues to Excel
     */
    public function export(Request $request)
    {
        $format = $request->get('format', 'xlsx');
        $filename = 'inventory_issues_' . now()->format('Y-m-d_H-i-s') . '.' . $format;
        
        return Excel::download(new InventoryIssuesExport($request->all()), $filename);
    }

    /**
     * Show import form
     */
    public function importForm()
    {
        $inventoryItems = InventoryItem::active()->orderBy('name')->get();
        return view('admin.inventory.issues.import', compact('inventoryItems'));
    }

    /**
     * Import inventory issues from Excel
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
            'update_existing' => 'boolean'
        ]);

        try {
            $updateExisting = $request->boolean('update_existing', false);
            
            Excel::import(new InventoryIssuesImport($updateExisting), $request->file('file'));

            return redirect()->route('admin.inventory.issues.index')
                ->with('success', 'Inventory issues imported successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    /**
     * Download sample import file
     */
    public function downloadSample()
    {
        $sampleData = [
            [
                'inventory_item_name' => 'Sample Item',
                'issue_type' => 'damaged',
                'title' => 'Sample Issue',
                'description' => 'This is a sample issue description',
                'priority' => 'medium',
                'status' => 'open',
                'quantity_affected' => 1,
                'estimated_cost' => 500.00,
                'issue_date' => now()->format('Y-m-d'),
                'reported_by' => 'Sample Reporter',
                'assigned_to' => 'Sample Assignee',
                'location' => 'Sample Location',
                'is_active' => 1
            ]
        ];

        return Excel::download(new InventoryIssuesExport($sampleData, true), 'inventory_issues_sample.xlsx');
    }
}
