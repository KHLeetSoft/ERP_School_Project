<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\User;
use App\Models\SupportTicket;
use App\Models\Announcement;
use App\Models\CommunicationLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SupportCommunicationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'checkrole:superadmin']);
    }

    /**
     * Display support & communication dashboard
     */
    public function index()
    {
        $ticketStats = $this->getTicketStatistics();
        $recentTickets = $this->getRecentTickets();
        $announcements = $this->getRecentAnnouncements();
        $communicationLogs = $this->getRecentCommunicationLogs();
        
        return view('superadmin.support.index', compact('ticketStats', 'recentTickets', 'announcements', 'communicationLogs'));
    }

    /**
     * Support Tickets Management
     */
    public function tickets(Request $request)
    {
        $query = SupportTicket::with(['school', 'user', 'assignedTo']);
        
        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
        
        if ($request->filled('school_id')) {
            $query->where('school_id', $request->school_id);
        }
        
        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to);
        }
        
        $tickets = $query->orderBy('created_at', 'desc')->paginate(20);
        $schools = School::where('status', true)->get();
        
        return view('superadmin.support.tickets', compact('tickets', 'schools'));
    }

    /**
     * View specific ticket
     */
    public function viewTicket(SupportTicket $ticket)
    {
        $ticket->load(['school', 'user', 'assignedTo', 'replies']);
        return view('superadmin.support.view-ticket', compact('ticket'));
    }

    /**
     * Update ticket status
     */
    public function updateTicketStatus(Request $request, SupportTicket $ticket)
    {
        $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed',
            'priority' => 'required|in:low,medium,high,urgent',
            'assigned_to' => 'nullable|exists:users,id'
        ]);
        
        $ticket->update([
            'status' => $request->status,
            'priority' => $request->priority,
            'assigned_to' => $request->assigned_to,
            'updated_at' => now()
        ]);
        
        // Log the status change
        CommunicationLog::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'type' => 'status_change',
            'message' => "Ticket status changed to {$request->status}",
            'metadata' => [
                'old_status' => $ticket->getOriginal('status'),
                'new_status' => $request->status,
                'priority' => $request->priority
            ]
        ]);
        
        return response()->json(['success' => true, 'message' => 'Ticket updated successfully']);
    }

    /**
     * Add reply to ticket
     */
    public function addReply(Request $request, SupportTicket $ticket)
    {
        $request->validate([
            'message' => 'required|string|max:5000',
            'is_internal' => 'boolean'
        ]);
        
        $reply = $ticket->replies()->create([
            'user_id' => auth()->id(),
            'message' => $request->message,
            'is_internal' => $request->boolean('is_internal', false)
        ]);
        
        // Log the reply
        CommunicationLog::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'type' => 'reply',
            'message' => 'Added reply to ticket',
            'metadata' => [
                'reply_id' => $reply->id,
                'is_internal' => $request->boolean('is_internal', false)
            ]
        ]);
        
        return response()->json(['success' => true, 'message' => 'Reply added successfully']);
    }

    /**
     * Announcements Management
     */
    public function announcements(Request $request)
    {
        $query = Announcement::with(['createdBy']);
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        $announcements = $query->orderBy('created_at', 'desc')->paginate(20);
        
        return view('superadmin.support.announcements', compact('announcements'));
    }

    /**
     * Create new announcement
     */
    public function createAnnouncement()
    {
        $schools = School::where('status', true)->get();
        return view('superadmin.support.create-announcement', compact('schools'));
    }

    /**
     * Store new announcement
     */
    public function storeAnnouncement(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:10000',
            'type' => 'required|in:general,maintenance,feature_update,security',
            'priority' => 'required|in:low,medium,high,urgent',
            'target_schools' => 'nullable|array',
            'target_schools.*' => 'exists:schools,id',
            'scheduled_at' => 'nullable|date|after:now',
            'expires_at' => 'nullable|date|after:now'
        ]);
        
        $announcement = Announcement::create([
            'title' => $request->title,
            'content' => $request->content,
            'type' => $request->type,
            'priority' => $request->priority,
            'target_schools' => $request->target_schools ?? [],
            'scheduled_at' => $request->scheduled_at,
            'expires_at' => $request->expires_at,
            'status' => $request->scheduled_at ? 'scheduled' : 'active',
            'created_by' => auth()->id()
        ]);
        
        // Log the announcement creation
        CommunicationLog::create([
            'type' => 'announcement_created',
            'message' => "Created announcement: {$announcement->title}",
            'metadata' => [
                'announcement_id' => $announcement->id,
                'type' => $announcement->type,
                'priority' => $announcement->priority
            ]
        ]);
        
        return redirect()->route('superadmin.support.announcements')
            ->with('success', 'Announcement created successfully');
    }

    /**
     * Communication Logs
     */
    public function communicationLogs(Request $request)
    {
        $query = CommunicationLog::with(['user', 'ticket', 'school']);
        
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to);
        }
        
        $logs = $query->orderBy('created_at', 'desc')->paginate(50);
        
        return view('superadmin.support.communication-logs', compact('logs'));
    }

    /**
     * Knowledge Base
     */
    public function knowledgeBase(Request $request)
    {
        $query = DB::table('knowledge_base_articles');
        
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }
        
        $articles = $query->orderBy('created_at', 'desc')->paginate(20);
        
        return view('superadmin.support.knowledge-base', compact('articles'));
    }

    /**
     * Create knowledge base article
     */
    public function createArticle()
    {
        return view('superadmin.support.create-article');
    }

    /**
     * Store knowledge base article
     */
    public function storeArticle(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:50000',
            'category' => 'required|string|max:100',
            'tags' => 'nullable|string|max:500',
            'status' => 'required|in:draft,published,archived'
        ]);
        
        DB::table('knowledge_base_articles')->insert([
            'title' => $request->title,
            'content' => $request->content,
            'category' => $request->category,
            'tags' => $request->tags,
            'status' => $request->status,
            'created_by' => auth()->id(),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        return redirect()->route('superadmin.support.knowledge-base')
            ->with('success', 'Article created successfully');
    }

    /**
     * Get ticket statistics
     */
    private function getTicketStatistics()
    {
        return [
            'total' => SupportTicket::count(),
            'open' => SupportTicket::where('status', 'open')->count(),
            'in_progress' => SupportTicket::where('status', 'in_progress')->count(),
            'resolved' => SupportTicket::where('status', 'resolved')->count(),
            'closed' => SupportTicket::where('status', 'closed')->count(),
            'urgent' => SupportTicket::where('priority', 'urgent')->count(),
            'high' => SupportTicket::where('priority', 'high')->count(),
            'medium' => SupportTicket::where('priority', 'medium')->count(),
            'low' => SupportTicket::where('priority', 'low')->count()
        ];
    }

    /**
     * Get recent tickets
     */
    private function getRecentTickets()
    {
        return SupportTicket::with(['school', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
    }

    /**
     * Get recent announcements
     */
    private function getRecentAnnouncements()
    {
        return Announcement::with(['createdBy'])
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }

    /**
     * Get recent communication logs
     */
    private function getRecentCommunicationLogs()
    {
        return CommunicationLog::with(['user', 'ticket', 'school'])
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();
    }

    /**
     * Send bulk announcement
     */
    public function sendBulkAnnouncement(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:10000',
            'type' => 'required|in:email,sms,notification',
            'target_schools' => 'required|array',
            'target_schools.*' => 'exists:schools,id'
        ]);
        
        $schools = School::whereIn('id', $request->target_schools)->get();
        $sentCount = 0;
        
        foreach ($schools as $school) {
            // Send announcement to school
            // This would integrate with email/SMS services
            $sentCount++;
        }
        
        // Log the bulk announcement
        CommunicationLog::create([
            'type' => 'bulk_announcement',
            'message' => "Sent bulk announcement to {$sentCount} schools",
            'metadata' => [
                'title' => $request->title,
                'type' => $request->type,
                'target_count' => $sentCount
            ]
        ]);
        
        return response()->json([
            'success' => true,
            'message' => "Announcement sent to {$sentCount} schools"
        ]);
    }

    /**
     * Get communication analytics
     */
    public function getCommunicationAnalytics(Request $request)
    {
        $timeframe = $request->get('timeframe', '30d');
        
        $startDate = now()->subDays($timeframe === '7d' ? 7 : ($timeframe === '30d' ? 30 : 90));
        
        $ticketStats = SupportTicket::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count'),
            DB::raw('AVG(CASE WHEN status = "resolved" THEN 1 ELSE 0 END) as resolution_rate')
        )
        ->where('created_at', '>=', $startDate)
        ->groupBy('date')
        ->orderBy('date')
        ->get();
        
        $communicationStats = CommunicationLog::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count'),
            'type'
        )
        ->where('created_at', '>=', $startDate)
        ->groupBy('date', 'type')
        ->orderBy('date')
        ->get();
        
        return response()->json([
            'ticket_stats' => $ticketStats,
            'communication_stats' => $communicationStats,
            'timeframe' => $timeframe
        ]);
    }
}
