<?php

namespace App\Http\Controllers\Admin\AI;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;

class ChatbotController extends Controller
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
    public function index()
    {
        $history = session('ai.chat.history', []);
        return view('admin.ai.chatbot.index', [
            'history' => $history,
        ]);
    }

    public function message(Request $request)
    {
        $data = $request->validate([
            'message' => 'required|string|max:4000',
            'persona' => 'nullable|in:general,student,parent,teacher,admin',
        ]);

        $persona = $data['persona'] ?? 'general';
        $userText = trim($data['message']);

        $history = session('ai.chat.history', []);

        $systemPrompts = [
            'general' => 'You are a helpful school assistant. Be concise and friendly.',
            'student' => 'You are a helpful assistant for students. Explain concepts simply and provide step-by-step guidance.',
            'parent' => 'You assist parents with school processes, schedules, and policies. Be clear and empathetic.',
            'teacher' => 'You assist teachers with lesson planning, assessments, and classroom management. Provide practical tips.',
            'admin' => 'You assist school admins with workflows, reporting, and data policies. Provide structured guidance.',
        ];

        $messages = [
            ['role' => 'system', 'content' => $systemPrompts[$persona] ?? $systemPrompts['general']],
        ];

        foreach ($history as $h) {
            $messages[] = ['role' => 'user', 'content' => (string)($h['q'] ?? '')];
            if (!empty($h['a'])) {
                $messages[] = ['role' => 'assistant', 'content' => (string)($h['a'])];
            }
        }

        $messages[] = ['role' => 'user', 'content' => $userText];

        $reply = '';
        $error = null;
        try {
            $res = OpenAI::chat()->create([
                'model' => 'gpt-4o-mini',
                'messages' => $messages,
                'temperature' => 0.3,
            ]);
            $reply = $res->choices[0]->message->content ?? '';
        } catch (\Throwable $e) {
            $error = 'Chat failed: '.$e->getMessage();
        }

        $history[] = [
            'q' => $userText,
            'a' => $reply,
            't' => now()->format('Y-m-d H:i'),
            'persona' => $persona,
        ];
        session(['ai.chat.history' => array_slice($history, -50)]);

        return response()->json([
            'ok' => empty($error),
            'reply' => $reply,
            'error' => $error,
        ]);
    }

    public function reset()
    {
        session()->forget('ai.chat.history');
        return redirect()->route('admin.ai.chatbot.index')->with('success', 'Conversation cleared.');
    }
}


