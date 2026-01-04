<?php

namespace App\Http\Controllers\Admin\AI;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AIChatbotController extends Controller
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
        return view('admin.ai.chatbot.index');
    }

    public function sendMessage(Request $request)
    {
        $userMessage = $request->input('message');

        // Example: OpenAI API call
        $response = Http::withToken(env('OPENAI_API_KEY'))
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o-mini',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a helpful school management assistant.'],
                    ['role' => 'user', 'content' => $userMessage],
                ],
            ]);

        $botReply = $response->json()['choices'][0]['message']['content'] ?? "⚠️ Sorry, no response.";

        return response()->json(['reply' => $botReply]);
    }
}
