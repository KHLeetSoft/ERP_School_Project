<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\MessageRecipient;
use App\Models\MessageFolder;
use App\Models\MessageLabel;
use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;

class MessagesController extends Controller
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
     * Display messages dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Get dashboard statistics
        $stats = [
            'inbox' => Message::inbox($user->id)->unread()->count(),
            'sent' => Message::sent($user->id)->count(),
            'drafts' => Message::drafts($user->id)->count(),
            'starred' => Message::inbox($user->id)->starred()->count(),
            'important' => Message::inbox($user->id)->important()->count(),
            'total_messages' => Message::where(function($q) use ($user) {
                $q->where('sender_id', $user->id)
                  ->orWhere('recipient_id', $user->id)
                  ->orWhereHas('recipients', function($r) use ($user) {
                      $r->where('user_id', $user->id);
                  });
            })->count(),
            'unread_urgent' => Message::inbox($user->id)->unread()->where('priority', 'urgent')->count(),
            'requires_acknowledgment' => Message::inbox($user->id)
                ->where('requires_acknowledgment', true)
                ->whereNull('acknowledged_at')
                ->count(),
        ];

        // Get recent messages
        $recentMessages = Message::inbox($user->id)
            ->with(['sender', 'recipients.user'])
            ->orderBy('sent_at', 'desc')
            ->limit(10)
            ->get();

        // Get user folders
        $folders = MessageFolder::forUser($user->id)->ordered()->get();

        // Get labels
        $labels = MessageLabel::forUser($user->id)->get();

        // Get priority breakdown
        $priorityStats = Message::inbox($user->id)
            ->select('priority', DB::raw('count(*) as count'))
            ->groupBy('priority')
            ->pluck('count', 'priority')
            ->toArray();

        return view('admin.communications.messages.dashboard', compact(
            'stats',
            'recentMessages',
            'folders',
            'labels',
            'priorityStats'
        ));
    }

    /**
     * Display inbox messages
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Message::inbox($user->id)->with(['sender', 'recipients.user', 'labels']);

        // Apply filters
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('priority')) {
            $query->byPriority($request->priority);
        }

        if ($request->filled('type')) {
            $query->byType($request->type);
        }

        if ($request->filled('status')) {
            switch ($request->status) {
                case 'unread':
                    $query->unread();
                    break;
                case 'read':
                    $query->read();
                    break;
                case 'starred':
                    $query->starred();
                    break;
                case 'important':
                    $query->important();
                    break;
                case 'flagged':
                    $query->where('is_flagged', true);
                    break;
            }
        }

        if ($request->filled('label')) {
            $query->whereHas('labels', function($q) use ($request) {
                $q->where('slug', $request->label);
            });
        }

        if ($request->filled('folder')) {
            $query->whereHas('folders', function($q) use ($request, $user) {
                $q->where('slug', $request->folder)
                  ->where('user_id', $user->id);
            });
        }

        if ($request->filled('date_from')) {
            $query->where('sent_at', '>=', Carbon::parse($request->date_from)->startOfDay());
        }

        if ($request->filled('date_to')) {
            $query->where('sent_at', '<=', Carbon::parse($request->date_to)->endOfDay());
        }

        // Sort
        $sortField = $request->get('sort', 'sent_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $messages = $query->paginate(25)->withQueryString();

        // Get filters data
        $folders = MessageFolder::forUser($user->id)->ordered()->get();
        $labels = MessageLabel::forUser($user->id)->get();
        $users = User::orderBy('name')->get();

        return view('admin.communications.messages.index', compact(
            'messages',
            'folders',
            'labels',
            'users'
        ));
    }

    /**
     * Display sent messages
     */
    public function sent(Request $request)
    {
        $user = Auth::user();
        $query = Message::sent($user->id)->with(['recipient', 'recipients.user', 'labels']);

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $messages = $query->orderBy('sent_at', 'desc')->paginate(25)->withQueryString();

        return view('admin.communications.messages.sent', compact('messages'));
    }

    /**
     * Display draft messages
     */
    public function drafts(Request $request)
    {
        $user = Auth::user();
        $query = Message::drafts($user->id)->with(['recipient', 'recipients.user']);

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $messages = $query->orderBy('updated_at', 'desc')->paginate(25)->withQueryString();

        return view('admin.communications.messages.drafts', compact('messages'));
    }

    /**
     * Show the form for creating a new message
     */
    public function create(Request $request)
    {
        $users = User::where('id', '!=', Auth::id())->orderBy('name')->get();
        $departments = Department::orderBy('name')->get();
        $labels = MessageLabel::forUser(Auth::id())->get();
        
        // Handle reply/forward
        $replyTo = null;
        $forwardFrom = null;
        
        if ($request->has('reply_to')) {
            $replyTo = Message::findOrFail($request->reply_to);
        }
        
        if ($request->has('forward_from')) {
            $forwardFrom = Message::findOrFail($request->forward_from);
        }

        return view('admin.communications.messages.create', compact(
            'users',
            'departments',
            'labels',
            'replyTo',
            'forwardFrom'
        ));
    }

    /**
     * Store a newly created message
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'recipient_id' => 'nullable|exists:users,id',
            'department_id' => 'nullable|exists:departments,id',
            'recipients_to' => 'nullable|array',
            'recipients_to.*' => 'exists:users,id',
            'recipients_cc' => 'nullable|array',
            'recipients_cc.*' => 'exists:users,id',
            'recipients_bcc' => 'nullable|array',
            'recipients_bcc.*' => 'exists:users,id',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'priority' => 'required|in:low,normal,high,urgent',
            'type' => 'required|in:direct,broadcast,announcement,system',
            'is_important' => 'boolean',
            'is_encrypted' => 'boolean',
            'requires_acknowledgment' => 'boolean',
            'expires_at' => 'nullable|date|after:now',
            'attachments.*' => 'nullable|file|max:10240',
            'labels' => 'nullable|array',
            'labels.*' => 'exists:message_labels,id',
            'parent_id' => 'nullable|exists:messages,id',
            'thread_id' => 'nullable|exists:messages,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();

        try {
            // Create message
            $message = new Message($request->only([
                'recipient_id',
                'department_id',
                'subject',
                'body',
                'priority',
                'type',
                'is_important',
                'is_encrypted',
                'requires_acknowledgment',
                'expires_at',
                'parent_id',
                'thread_id'
            ]));
            
            $message->sender_id = Auth::id();
            $message->status = $request->input('save_as_draft') ? 'draft' : 'sent';
            $message->sent_at = $message->status === 'sent' ? now() : null;
            
            // Handle thread
            if ($request->parent_id && !$request->thread_id) {
                $parent = Message::find($request->parent_id);
                $message->thread_id = $parent->thread_id ?: $parent->id;
            }
            
            // Handle attachments
            if ($request->hasFile('attachments')) {
                $attachments = [];
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('messages/attachments', 'public');
                    $attachments[] = [
                        'name' => $file->getClientOriginalName(),
                        'path' => $path,
                        'size' => $file->getSize(),
                        'mime' => $file->getMimeType()
                    ];
                }
                $message->attachments = $attachments;
            }
            
            $message->save();
            
            // Add recipients
            if ($message->status === 'sent') {
                $recipients = [];
                
                if ($request->filled('recipients_to')) {
                    foreach ($request->recipients_to as $userId) {
                        $recipients['to'][] = $userId;
                    }
                }
                
                if ($request->filled('recipients_cc')) {
                    foreach ($request->recipients_cc as $userId) {
                        $recipients['cc'][] = $userId;
                    }
                }
                
                if ($request->filled('recipients_bcc')) {
                    foreach ($request->recipients_bcc as $userId) {
                        $recipients['bcc'][] = $userId;
                    }
                }
                
                if (!empty($recipients)) {
                    $message->sendToRecipients($recipients);
                }
            }
            
            // Add labels
            if ($request->filled('labels')) {
                $message->labels()->attach($request->labels);
            }
            
            DB::commit();
            
            $redirectTo = $message->status === 'draft' ? 'drafts' : 'sent';
            
            return redirect()->route('admin.messages.' . $redirectTo)
                ->with('success', 'Message ' . ($message->status === 'draft' ? 'saved as draft' : 'sent') . ' successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Failed to send message: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified message
     */
    public function show($id)
    {
        $message = Message::with([
            'sender',
            'recipient',
            'department',
            'recipients.user',
            'labels',
            'parent',
            'replies.sender',
            'thread'
        ])->findOrFail($id);
        
        $user = Auth::user();
        
        // Check if user can view this message
        if (!$message->canBeViewedBy($user->id)) {
            abort(403, 'Unauthorized access to this message.');
        }
        
        // Mark as read
        $message->markAsRead($user->id);
        
        // Get thread messages if applicable
        $threadMessages = collect();
        if ($message->thread_id) {
            $threadMessages = Message::where('thread_id', $message->thread_id)
                ->orWhere('id', $message->thread_id)
                ->with(['sender', 'recipients.user'])
                ->orderBy('sent_at')
                ->get();
        } elseif ($message->replies->count() > 0) {
            $threadMessages = collect([$message])->merge($message->replies);
        }
        
        // Get user's folders
        $folders = MessageFolder::forUser($user->id)->ordered()->get();
        
        // Get labels
        $labels = MessageLabel::forUser($user->id)->get();
        
        return view('admin.communications.messages.show', compact(
            'message',
            'threadMessages',
            'folders',
            'labels'
        ));
    }

    /**
     * Show the form for editing the specified message
     */
    public function edit($id)
    {
        $message = Message::findOrFail($id);
        
        // Only allow editing drafts by the sender
        if ($message->status !== 'draft' || $message->sender_id !== Auth::id()) {
            abort(403, 'You can only edit your own draft messages.');
        }
        
        $users = User::where('id', '!=', Auth::id())->orderBy('name')->get();
        $departments = Department::orderBy('name')->get();
        $labels = MessageLabel::forUser(Auth::id())->get();
        
        // Get current recipients
        $recipientsTo = $message->recipients()->where('recipient_type', 'to')->pluck('user_id')->toArray();
        $recipientsCc = $message->recipients()->where('recipient_type', 'cc')->pluck('user_id')->toArray();
        $recipientsBcc = $message->recipients()->where('recipient_type', 'bcc')->pluck('user_id')->toArray();
        
        return view('admin.communications.messages.edit', compact(
            'message',
            'users',
            'departments',
            'labels',
            'recipientsTo',
            'recipientsCc',
            'recipientsBcc'
        ));
    }

    /**
     * Update the specified message
     */
    public function update(Request $request, $id)
    {
        $message = Message::findOrFail($id);
        
        // Only allow updating drafts by the sender
        if ($message->status !== 'draft' || $message->sender_id !== Auth::id()) {
            abort(403, 'You can only update your own draft messages.');
        }
        
        $validator = Validator::make($request->all(), [
            'recipient_id' => 'nullable|exists:users,id',
            'department_id' => 'nullable|exists:departments,id',
            'recipients_to' => 'nullable|array',
            'recipients_to.*' => 'exists:users,id',
            'recipients_cc' => 'nullable|array',
            'recipients_cc.*' => 'exists:users,id',
            'recipients_bcc' => 'nullable|array',
            'recipients_bcc.*' => 'exists:users,id',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'priority' => 'required|in:low,normal,high,urgent',
            'type' => 'required|in:direct,broadcast,announcement,system',
            'is_important' => 'boolean',
            'is_encrypted' => 'boolean',
            'requires_acknowledgment' => 'boolean',
            'expires_at' => 'nullable|date|after:now',
            'attachments.*' => 'nullable|file|max:10240',
            'labels' => 'nullable|array',
            'labels.*' => 'exists:message_labels,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();

        try {
            // Update message
            $message->fill($request->only([
                'recipient_id',
                'department_id',
                'subject',
                'body',
                'priority',
                'type',
                'is_important',
                'is_encrypted',
                'requires_acknowledgment',
                'expires_at'
            ]));
            
            if (!$request->input('save_as_draft')) {
                $message->status = 'sent';
                $message->sent_at = now();
            }
            
            // Handle new attachments
            if ($request->hasFile('attachments')) {
                $existingAttachments = $message->attachments ?: [];
                $newAttachments = [];
                
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('messages/attachments', 'public');
                    $newAttachments[] = [
                        'name' => $file->getClientOriginalName(),
                        'path' => $path,
                        'size' => $file->getSize(),
                        'mime' => $file->getMimeType()
                    ];
                }
                
                $message->attachments = array_merge($existingAttachments, $newAttachments);
            }
            
            $message->save();
            
            // Update recipients if sending
            if ($message->status === 'sent') {
                // Clear existing recipients
                $message->recipients()->delete();
                
                $recipients = [];
                
                if ($request->filled('recipients_to')) {
                    foreach ($request->recipients_to as $userId) {
                        $recipients['to'][] = $userId;
                    }
                }
                
                if ($request->filled('recipients_cc')) {
                    foreach ($request->recipients_cc as $userId) {
                        $recipients['cc'][] = $userId;
                    }
                }
                
                if ($request->filled('recipients_bcc')) {
                    foreach ($request->recipients_bcc as $userId) {
                        $recipients['bcc'][] = $userId;
                    }
                }
                
                if (!empty($recipients)) {
                    $message->sendToRecipients($recipients);
                }
            }
            
            // Update labels
            if ($request->has('labels')) {
                $message->labels()->sync($request->labels);
            }
            
            DB::commit();
            
            $redirectTo = $message->status === 'draft' ? 'drafts' : 'sent';
            
            return redirect()->route('admin.messages.' . $redirectTo)
                ->with('success', 'Message updated and ' . ($message->status === 'draft' ? 'saved as draft' : 'sent') . ' successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Failed to update message: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified message
     */
    public function destroy($id)
    {
        $message = Message::findOrFail($id);
        $user = Auth::user();
        
        // Check if user can delete this message
        if (!$message->canBeViewedBy($user->id)) {
            abort(403, 'Unauthorized access to this message.');
        }
        
        // Soft delete
        $message->delete();
        
        return redirect()->back()
            ->with('success', 'Message moved to trash successfully!');
    }

    /**
     * Bulk actions on messages
     */
    public function bulkAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:mark_read,mark_unread,star,unstar,delete,move_to_folder,add_label,remove_label',
            'message_ids' => 'required|array',
            'message_ids.*' => 'exists:messages,id',
            'folder_id' => 'required_if:action,move_to_folder|exists:message_folders,id',
            'label_id' => 'required_if:action,add_label,remove_label|exists:message_labels,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $user = Auth::user();
        $messages = Message::whereIn('id', $request->message_ids)->get();
        
        // Verify user can access all messages
        foreach ($messages as $message) {
            if (!$message->canBeViewedBy($user->id)) {
                return response()->json(['error' => 'Unauthorized access to one or more messages.'], 403);
            }
        }
        
        switch ($request->action) {
            case 'mark_read':
                foreach ($messages as $message) {
                    $message->markAsRead($user->id);
                }
                break;
                
            case 'mark_unread':
                foreach ($messages as $message) {
                    $message->markAsUnread();
                }
                break;
                
            case 'star':
                Message::whereIn('id', $request->message_ids)->update(['is_starred' => true]);
                break;
                
            case 'unstar':
                Message::whereIn('id', $request->message_ids)->update(['is_starred' => false]);
                break;
                
            case 'delete':
                Message::whereIn('id', $request->message_ids)->delete();
                break;
                
            case 'move_to_folder':
                $folder = MessageFolder::findOrFail($request->folder_id);
                foreach ($messages as $message) {
                    $message->moveToFolder($folder->id, $user->id);
                }
                break;
                
            case 'add_label':
                foreach ($messages as $message) {
                    $message->addLabel($request->label_id);
                }
                break;
                
            case 'remove_label':
                foreach ($messages as $message) {
                    $message->removeLabel($request->label_id);
                }
                break;
        }
        
        return response()->json(['success' => 'Bulk action completed successfully!']);
    }

    /**
     * Toggle message star
     */
    public function toggleStar($id)
    {
        $message = Message::findOrFail($id);
        
        if (!$message->canBeViewedBy(Auth::id())) {
            abort(403);
        }
        
        $isStarred = $message->toggleStar();
        
        return response()->json([
            'success' => true,
            'is_starred' => $isStarred
        ]);
    }

    /**
     * Toggle message important
     */
    public function toggleImportant($id)
    {
        $message = Message::findOrFail($id);
        
        if (!$message->canBeViewedBy(Auth::id())) {
            abort(403);
        }
        
        $isImportant = $message->toggleImportant();
        
        return response()->json([
            'success' => true,
            'is_important' => $isImportant
        ]);
    }

    /**
     * Acknowledge message
     */
    public function acknowledge($id)
    {
        $message = Message::findOrFail($id);
        $user = Auth::user();
        
        if (!$message->canBeViewedBy($user->id)) {
            abort(403);
        }
        
        $message->acknowledge($user->id);
        
        return response()->json([
            'success' => true,
            'acknowledged_at' => $message->acknowledged_at->format('Y-m-d H:i:s')
        ]);
    }

    /**
     * Download attachment
     */
    public function downloadAttachment($messageId, $index)
    {
        $message = Message::findOrFail($messageId);
        
        if (!$message->canBeViewedBy(Auth::id())) {
            abort(403);
        }
        
        if (!isset($message->attachments[$index])) {
            abort(404, 'Attachment not found.');
        }
        
        $attachment = $message->attachments[$index];
        
        return Storage::disk('public')->download($attachment['path'], $attachment['name']);
    }

    /**
     * Search messages
     */
    public function search(Request $request)
    {
        $user = Auth::user();
        $query = $request->get('q');
        
        if (strlen($query) < 3) {
            return response()->json([]);
        }
        
        $messages = Message::where(function($q) use ($user) {
                $q->where('sender_id', $user->id)
                  ->orWhere('recipient_id', $user->id)
                  ->orWhereHas('recipients', function($r) use ($user) {
                      $r->where('user_id', $user->id);
                  });
            })
            ->search($query)
            ->with(['sender', 'recipients.user'])
            ->limit(10)
            ->get();
        
        return response()->json($messages->map(function($message) {
            return [
                'id' => $message->id,
                'subject' => $message->subject,
                'sender' => $message->sender->name,
                'sent_at' => $message->sent_at->format('M d, Y'),
                'preview' => Str::limit(strip_tags($message->body), 100),
                'is_unread' => $message->is_unread,
                'priority' => $message->priority,
                'url' => route('admin.messages.show', $message->id)
            ];
        }));
    }
}