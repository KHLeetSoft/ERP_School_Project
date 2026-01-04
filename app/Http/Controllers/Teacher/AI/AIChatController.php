<?php

namespace App\Http\Controllers\Teacher\AI;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use OpenAI\Laravel\Facades\OpenAI;

class AIChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('teacher.ai.chat');
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        try {
            $userMessage = $request->input('message');
            $teacher = auth()->user()->teacher;

            // Create a context-aware system message for teachers
            $systemMessage = "You are an AI teaching assistant specialized in helping teachers with their daily tasks. You can help with:
            - Lesson planning and curriculum development
            - Creating assessments and quizzes
            - Grading strategies and feedback
            - Classroom management tips
            - Student engagement techniques
            - Educational technology recommendations
            - Answering subject-specific questions
            - Providing teaching resources and materials

            Always provide practical, actionable advice that teachers can implement in their classrooms. Be encouraging and supportive.";

            $response = OpenAI::chat()->create([
                'model' => 'gpt-4o-mini',
                'messages' => [
                    ['role' => 'system', 'content' => $systemMessage],
                    ['role' => 'user', 'content' => $userMessage],
                ],
                'max_tokens' => 1000,
                'temperature' => 0.7,
            ]);

            $botReply = $response->choices[0]->message->content ?? "I apologize, but I couldn't generate a response at this time. Please try again.";

            return response()->json([
                'success' => true,
                'reply' => $botReply,
                'timestamp' => now()->format('H:i')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to get AI response. Please try again.',
                'reply' => 'I apologize, but I\'m experiencing technical difficulties. Please try again in a moment.'
            ], 500);
        }
    }

    public function getSuggestions(Request $request)
    {
        $context = $request->input('context', 'general');
        
        $suggestions = [
            'general' => [
                'How can I improve student engagement in my classroom?',
                'What are some effective classroom management strategies?',
                'How do I create engaging lesson plans?',
                'What are the best practices for giving student feedback?'
            ],
            'lesson_planning' => [
                'Help me create a lesson plan for [subject]',
                'What activities can I use to teach [topic]?',
                'How can I make this lesson more interactive?',
                'What are some assessment strategies for this topic?'
            ],
            'assessment' => [
                'How do I create effective quiz questions?',
                'What are different types of assessments I can use?',
                'How can I provide constructive feedback to students?',
                'What are some creative project ideas for [subject]?'
            ],
            'classroom_management' => [
                'How do I handle disruptive students?',
                'What are effective group work strategies?',
                'How can I create a positive classroom environment?',
                'What are some time management tips for teachers?'
            ]
        ];

        return response()->json([
            'suggestions' => $suggestions[$context] ?? $suggestions['general']
        ]);
    }
}
