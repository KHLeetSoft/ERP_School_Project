<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use App\Models\Book;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $query = Notification::query()
            ->where(function ($q) {
                $q->whereNull('user_id')
                  ->orWhere('user_id', Auth::id());
            })
            ->orderByDesc('sent_at')
            ->orderByDesc('created_at');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('is_read', $request->status === 'read');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        $notifications = $query->paginate(20);

        // Add genres for any potential use in the view
        $genres = Book::query()
            ->whereNotNull('genre')
            ->distinct()
            ->pluck('genre')
            ->filter()
            ->values();

        return view('librarian.notifications.index', compact('notifications', 'genres'));
    }

    public function overdue()
    {
        $notifications = Notification::ofType('danger')
            ->where(function ($q) {
                $q->whereNull('user_id')->orWhere('user_id', Auth::id());
            })
            ->orderByDesc('sent_at')
            ->paginate(20);

        return view('librarian.notifications.overdue-books', compact('notifications'));
    }

    public function dueToday()
    {
        $notifications = Notification::ofType('warning')
            ->where(function ($q) {
                $q->whereNull('user_id')->orWhere('user_id', Auth::id());
            })
            ->orderByDesc('sent_at')
            ->paginate(20);

        return view('librarian.notifications.due-today', compact('notifications'));
    }

    public function markRead(Request $request)
    {
        Notification::where(function ($q) {
                $q->whereNull('user_id')->orWhere('user_id', Auth::id());
            })
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return back()->with('success', 'Notifications marked as read');
    }
}


