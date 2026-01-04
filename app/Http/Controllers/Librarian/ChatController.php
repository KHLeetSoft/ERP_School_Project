<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        $threads = collect([
            [
                'id' => 1,
                'name' => 'John Doe (Student)'
            ],
            [
                'id' => 2,
                'name' => 'Jane Smith (Teacher)'
            ],
        ]);

        return view('librarian.messages.index', compact('threads'));
    }

    public function show($thread)
    {
        $messages = collect([
            [ 'from' => 'you', 'text' => 'Hello, how can I help you?', 'time' => now()->subMinutes(10) ],
            [ 'from' => 'them', 'text' => 'I want to renew a book.', 'time' => now()->subMinutes(8) ],
        ]);

        $participant = 'Conversation #' . $thread;

        return view('librarian.messages.thread', compact('messages', 'participant', 'thread'));
    }

    public function send(Request $request, $thread)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        // Placeholder: persist the message to storage

        return redirect()->route('librarian.messages.show', $thread)
            ->with('success', 'Message sent');
    }
}


