<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Noticeboard;
use App\Models\Department;
use App\Models\User;
use App\Models\NoticeboardTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;

class NoticeboardController extends Controller
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
     * Display the noticeboard dashboard
     */
    public function dashboard()
    {
        // Get dashboard statistics
        $stats = [
            'total' => Noticeboard::count(),
            'published' => Noticeboard::where('status', 'published')->count(),
            'draft' => Noticeboard::where('status', 'draft')->count(),
            'archived' => Noticeboard::where('status', 'archived')->count(),
            'featured' => Noticeboard::where('is_featured', true)->count(),
            'pinned' => Noticeboard::where('is_pinned', true)->count(),
            'total_views' => Noticeboard::sum('views_count'),
            'active_users' => User::count(),
        ];

        // Get recent notices
        $recentNotices = Noticeboard::with(['author', 'department'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get top performing notices
        $topNotices = Noticeboard::with(['author', 'department'])
            ->orderBy('views_count', 'desc')
            ->limit(5)
            ->get();

        // Get department performance
        $departmentPerformance = Department::withCount(['noticeboards as total_notices'])
            ->withSum('noticeboards', 'views_count')
            ->get()
            ->map(function ($dept) {
                $dept->published_notices = Noticeboard::where('department_id', $dept->id)
                    ->where('status', 'published')
                    ->count();
                $dept->avg_views = $dept->total_notices > 0 ? round($dept->noticeboards_sum_views_count / $dept->total_notices, 1) : 0;
                $dept->engagement_rate = $dept->total_notices > 0 ? round(($dept->published_notices / $dept->total_notices) * 100) : 0;
                
                // Calculate performance rating
                if ($dept->engagement_rate >= 80) $dept->performance = 'excellent';
                elseif ($dept->engagement_rate >= 60) $dept->performance = 'good';
                elseif ($dept->engagement_rate >= 40) $dept->performance = 'average';
                else $dept->performance = 'poor';
                
                return $dept;
            });

        // Get upcoming events
        $upcomingEvents = Noticeboard::where('type', 'event')
            ->where('status', 'published')
            ->where('start_date', '>=', now())
            ->orderBy('start_date')
            ->limit(5)
            ->get();

        return view('admin.communications.noticeboard.dashboard', compact(
            'stats',
            'recentNotices',
            'topNotices',
            'departmentPerformance',
            'upcomingEvents'
        ));
    }

    /**
     * Display a listing of the noticeboard items
     */
    public function index(Request $request)
    {
        $query = Noticeboard::with(['author', 'department', 'tags'])
            ->orderBy('is_pinned', 'desc')
            ->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc');

        // Apply filters
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('type')) {
            $query->byType($request->type);
        }

        if ($request->filled('priority')) {
            $query->byPriority($request->priority);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('department')) {
            $query->byDepartment($request->department);
        }

        if ($request->filled('date_from')) {
            $query->where('start_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('end_date', '<=', $request->date_to);
        }

        $noticeboards = $query->paginate(15);

        // Get filter options
        $types = ['announcement', 'news', 'event', 'policy', 'general'];
        $priorities = ['low', 'medium', 'high', 'urgent'];
        $statuses = ['draft', 'published', 'archived'];
        $departments = Department::orderBy('name')->get();

        // Statistics
        $stats = [
            'total' => Noticeboard::count(),
            'published' => Noticeboard::where('status', 'published')->count(),
            'draft' => Noticeboard::where('status', 'draft')->count(),
            'archived' => Noticeboard::where('status', 'archived')->count(),
            'featured' => Noticeboard::where('is_featured', true)->count(),
            'pinned' => Noticeboard::where('is_pinned', true)->count(),
        ];

        return view('admin.communications.noticeboard.index', compact(
            'noticeboards',
            'types',
            'priorities',
            'statuses',
            'departments',
            'stats'
        ));
    }

    /**
     * Show the form for creating a new noticeboard item
     */
    public function create()
    {
        $departments = Department::orderBy('name')->get();
        $tags = NoticeboardTag::orderBy('name')->get();
        $types = ['announcement', 'news', 'event', 'policy', 'general'];
        $priorities = ['low', 'medium', 'high', 'urgent'];
        $audiences = ['all', 'staff', 'managers', 'specific_departments'];

        return view('admin.communications.noticeboard.create', compact(
            'departments',
            'tags',
            'types',
            'priorities',
            'audiences'
        ));
    }

    /**
     * Store a newly created noticeboard item
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:announcement,news,event,policy,general',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:draft,published,archived',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'department_id' => 'nullable|exists:departments,id',
            'target_audience' => 'required|in:all,staff,managers,specific_departments',
            'is_featured' => 'boolean',
            'is_pinned' => 'boolean',
            'is_public' => 'boolean',
            'attachments.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,gif|max:10240',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:noticeboard_tags,id'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();
        $data['author_id'] = Auth::id();
        $data['slug'] = Str::slug($request->title);

        // Handle file attachments
        if ($request->hasFile('attachments')) {
            $attachments = [];
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('noticeboard/attachments', 'public');
                $attachments[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                    'type' => $file->getMimeType()
                ];
            }
            $data['attachments'] = $attachments;
        }

        // Set published_at if status is published
        if ($request->status === 'published') {
            $data['published_at'] = now();
        }

        $noticeboard = Noticeboard::create($data);

        // Attach tags
        if ($request->filled('tags')) {
            $noticeboard->tags()->attach($request->tags);
        }

        // Send notifications if published
        if ($request->status === 'published') {
            $this->sendNotifications($noticeboard);
        }

        return redirect()->route('admin.communications.noticeboard.index')
            ->with('success', 'Noticeboard item created successfully!');
    }

    /**
     * Display the specified noticeboard item
     */
    public function show($id)
    {
        $noticeboard = Noticeboard::with(['author', 'department', 'tags', 'comments.user', 'likes'])
            ->findOrFail($id);

        // Increment view count
        $noticeboard->incrementViews();

        // Mark as read for current user
        $noticeboard->markAsRead(Auth::id());

        return view('admin.communications.noticeboard.show', compact('noticeboard'));
    }

    /**
     * Show the form for editing the specified noticeboard item
     */
    public function edit($id)
    {
        $noticeboard = Noticeboard::with('tags')->findOrFail($id);
        $departments = Department::orderBy('name')->get();
        $tags = NoticeboardTag::orderBy('name')->get();
        $types = ['announcement', 'news', 'event', 'policy', 'general'];
        $priorities = ['low', 'medium', 'high', 'urgent'];
        $audiences = ['all', 'staff', 'managers', 'specific_departments'];

        return view('admin.communications.noticeboard.edit', compact(
            'noticeboard',
            'departments',
            'tags',
            'types',
            'priorities',
            'audiences'
        ));
    }

    /**
     * Update the specified noticeboard item
     */
    public function update(Request $request, $id)
    {
        $noticeboard = Noticeboard::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:announcement,news,event,policy,general',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:draft,published,archived',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'department_id' => 'nullable|exists:departments,id',
            'target_audience' => 'required|in:all,staff,managers,specific_departments',
            'is_featured' => 'boolean',
            'is_pinned' => 'boolean',
            'is_public' => 'boolean',
            'attachments.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,gif|max:10240',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:noticeboard_tags,id'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();
        $data['slug'] = Str::slug($request->title);

        // Handle new file attachments
        if ($request->hasFile('attachments')) {
            $existingAttachments = $noticeboard->attachments ?? [];
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('noticeboard/attachments', 'public');
                $existingAttachments[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                    'type' => $file->getMimeType()
                ];
            }
            $data['attachments'] = $existingAttachments;
        }

        // Set published_at if status changed to published
        if ($request->status === 'published' && $noticeboard->status !== 'published') {
            $data['published_at'] = now();
        }

        $noticeboard->update($data);

        // Sync tags
        if ($request->filled('tags')) {
            $noticeboard->tags()->sync($request->tags);
        } else {
            $noticeboard->tags()->detach();
        }

        // Send notifications if newly published
        if ($request->status === 'published' && $noticeboard->status !== 'published') {
            $this->sendNotifications($noticeboard);
        }

        return redirect()->route('admin.communications.noticeboard.index')
            ->with('success', 'Noticeboard item updated successfully!');
    }

    /**
     * Remove the specified noticeboard item
     */
    public function destroy($id)
    {
        $noticeboard = Noticeboard::findOrFail($id);

        // Delete attachments
        if ($noticeboard->attachments) {
            foreach ($noticeboard->attachments as $attachment) {
                if (Storage::disk('public')->exists($attachment['path'])) {
                    Storage::disk('public')->delete($attachment['path']);
                }
            }
        }

        $noticeboard->delete();

        return redirect()->route('admin.communications.noticeboard.index')
            ->with('success', 'Noticeboard item deleted successfully!');
    }

    /**
     * Toggle pin status
     */
    public function togglePin($id)
    {
        $noticeboard = Noticeboard::findOrFail($id);
        $isPinned = $noticeboard->togglePin();

        return response()->json([
            'success' => true,
            'is_pinned' => $isPinned,
            'message' => $isPinned ? 'Notice pinned successfully!' : 'Notice unpinned successfully!'
        ]);
    }

    /**
     * Toggle featured status
     */
    public function toggleFeature($id)
    {
        $noticeboard = Noticeboard::findOrFail($id);
        $isFeatured = $noticeboard->toggleFeature();

        return response()->json([
            'success' => true,
            'is_featured' => $isFeatured,
            'message' => $isFeatured ? 'Notice featured successfully!' : 'Notice unfeatured successfully!'
        ]);
    }

    /**
     * Publish notice
     */
    public function publish($id)
    {
        $noticeboard = Noticeboard::findOrFail($id);
        $noticeboard->publish();

        // Send notifications
        $this->sendNotifications($noticeboard);

        return response()->json([
            'success' => true,
            'message' => 'Notice published successfully!'
        ]);
    }

    /**
     * Archive notice
     */
    public function archive($id)
    {
        $noticeboard = Noticeboard::findOrFail($id);
        $noticeboard->archive();

        return response()->json([
            'success' => true,
            'message' => 'Notice archived successfully!'
        ]);
    }

    /**
     * Duplicate notice
     */
    public function duplicate($id)
    {
        $noticeboard = Noticeboard::findOrFail($id);
        $newNotice = $noticeboard->duplicate();

        // Copy tags
        $newNotice->tags()->attach($noticeboard->tags->pluck('id')->toArray());

        return redirect()->route('admin.communications.noticeboard.edit', $newNotice->id)
            ->with('success', 'Notice duplicated successfully! You can now edit the copy.');
    }

    /**
     * Bulk actions
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,publish,archive,feature,unfeature,pin,unpin',
            'ids' => 'required|array',
            'ids.*' => 'exists:noticeboards,id'
        ]);

        $ids = $request->ids;
        $action = $request->action;

        switch ($action) {
            case 'delete':
                Noticeboard::whereIn('id', $ids)->delete();
                $message = 'Selected notices deleted successfully!';
                break;

            case 'publish':
                Noticeboard::whereIn('id', $ids)->update([
                    'status' => 'published',
                    'published_at' => now()
                ]);
                $message = 'Selected notices published successfully!';
                break;

            case 'archive':
                Noticeboard::whereIn('id', $ids)->update(['status' => 'archived']);
                $message = 'Selected notices archived successfully!';
                break;

            case 'feature':
                Noticeboard::whereIn('id', $ids)->update(['is_featured' => true]);
                $message = 'Selected notices featured successfully!';
                break;

            case 'unfeature':
                Noticeboard::whereIn('id', $ids)->update(['is_featured' => false]);
                $message = 'Selected notices unfeatured successfully!';
                break;

            case 'pin':
                Noticeboard::whereIn('id', $ids)->update(['is_pinned' => true]);
                $message = 'Selected notices pinned successfully!';
                break;

            case 'unpin':
                Noticeboard::whereIn('id', $ids)->update(['is_pinned' => false]);
                $message = 'Selected notices unpinned successfully!';
                break;
        }

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    /**
     * Export notices
     */
    public function export(Request $request)
    {
        $query = Noticeboard::with(['author', 'department']);

        // Apply filters
        if ($request->filled('type')) {
            $query->byType($request->type);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('department')) {
            $query->byDepartment($request->department);
        }

        $noticeboards = $query->get();

        $filename = 'noticeboard_export_' . date('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($noticeboards) {
            $file = fopen('php://output', 'w');
            
            // Headers
            fputcsv($file, [
                'ID', 'Title', 'Type', 'Priority', 'Status', 'Author', 'Department',
                'Start Date', 'End Date', 'Featured', 'Pinned', 'Views', 'Created At'
            ]);

            // Data
            foreach ($noticeboards as $notice) {
                fputcsv($file, [
                    $notice->id,
                    $notice->title,
                    $notice->type,
                    $notice->priority,
                    $notice->status,
                    $notice->author->name ?? 'N/A',
                    $notice->department->name ?? 'N/A',
                    $notice->start_date?->format('Y-m-d'),
                    $notice->end_date?->format('Y-m-d'),
                    $notice->is_featured ? 'Yes' : 'No',
                    $notice->is_pinned ? 'Yes' : 'No',
                    $notice->views_count,
                    $notice->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get noticeboard statistics
     */
    public function statistics()
    {
        $stats = [
            'total' => Noticeboard::count(),
            'published' => Noticeboard::where('status', 'published')->count(),
            'draft' => Noticeboard::where('status', 'draft')->count(),
            'archived' => Noticeboard::where('status', 'archived')->count(),
            'featured' => Noticeboard::where('is_featured', true)->count(),
            'pinned' => Noticeboard::where('is_pinned', true)->count(),
            'by_type' => Noticeboard::selectRaw('type, count(*) as count')
                ->groupBy('type')
                ->pluck('count', 'type'),
            'by_priority' => Noticeboard::selectRaw('priority, count(*) as count')
                ->groupBy('priority')
                ->pluck('count', 'priority'),
            'recent_activity' => Noticeboard::with('author')
                ->orderBy('updated_at', 'desc')
                ->limit(10)
                ->get(),
            'top_viewed' => Noticeboard::orderBy('views_count', 'desc')
                ->limit(5)
                ->get()
        ];

        return response()->json($stats);
    }

    /**
     * Send notifications for published notice
     */
    private function sendNotifications($noticeboard)
    {
        // This would integrate with your notification system
        // For now, we'll just log it
        \Log::info("Noticeboard published: {$noticeboard->title}");
        
        // You can add email notifications, push notifications, etc. here
        // Example:
        // Notification::send($users, new NoticeboardPublished($noticeboard));
    }
}
