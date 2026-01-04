<?php

namespace App\Http\Controllers\Admin\AI;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Storage;

class PlagiarismCheckerController extends Controller
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
        $history = session('ai.plagiarism.history', []);
        return view('admin.ai.plagiarism-checker.index', [
            'history' => $history,
        ]);
    }

    public function check(Request $request)
    {
        $data = $request->validate([
            'text_content' => 'required_without:upload_file|string|max:50000',
            'upload_file' => 'required_without:text_content|file|mimes:txt,pdf,doc,docx|max:10240',
            'assignment_title' => 'nullable|string|max:200',
            'student_name' => 'nullable|string|max:100',
            'check_type' => 'required|in:general,academic,technical',
        ]);

        $content = '';
        $fileName = null;
        $filePath = null;

        // Handle file upload
        if ($request->hasFile('upload_file')) {
            $file = $request->file('upload_file');
            $fileName = $file->getClientOriginalName();
            $filePath = $file->store('public/plagiarism-checks');
            
            // Extract text from file (basic text extraction)
            $ext = strtolower($file->getClientOriginalExtension());
            if ($ext === 'txt') {
                try {
                    $content = Storage::get($filePath);
                } catch (\Throwable $e) {
                    return back()->withInput()->with('error', 'Could not read the uploaded file.');
                }
            } else {
                // For other file types, use filename as indicator
                $content = "Document: " . $fileName . "\n[File content analysis would require additional processing libraries]";
            }
        } else {
            $content = $data['text_content'];
        }

        if (empty(trim($content))) {
            return back()->withInput()->with('error', 'No content to analyze.');
        }

        $checkType = $data['check_type'];
        $assignmentTitle = $data['assignment_title'] ?? '';
        $studentName = $data['student_name'] ?? '';

        $prompts = [
            'general' => 'Analyze this text for potential plagiarism. Look for patterns that suggest copied content, lack of original thought, or suspicious writing style changes.',
            'academic' => 'Analyze this academic text for plagiarism. Check for improperly cited sources, copied passages, and academic integrity issues.',
            'technical' => 'Analyze this technical document for plagiarism. Look for copied code snippets, technical explanations, or documentation that may be plagiarized.',
        ];

        $systemPrompt = $prompts[$checkType] ?? $prompts['general'];
        
        $prompt = $systemPrompt . "\n\n" .
                  "Content to analyze:\n'''\n" . substr($content, 0, 8000) . "\n'''\n\n" .
                  "Provide analysis in JSON format: " .
                  '{"plagiarism_score":0-100,"risk_level":"low|medium|high","issues_found":["issue1","issue2"],"recommendations":["rec1","rec2"],"summary":"brief summary"}';

        $result = null;
        $error = null;
        
        try {
            $response = OpenAI::chat()->create([
                'model' => 'gpt-4o-mini',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a plagiarism detection expert. Analyze text and return only JSON responses.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'temperature' => 0.1,
            ]);
            
            $rawResponse = $response->choices[0]->message->content ?? '';
            $result = json_decode($rawResponse, true);
            
            if (!is_array($result)) {
                $error = 'Invalid AI response format.';
                $result = null;
            }
        } catch (\Throwable $e) {
            $error = 'Plagiarism check failed: ' . $e->getMessage();
        }

        // Store in session history
        $history = session('ai.plagiarism.history', []);
        $historyItem = [
            'id' => uniqid(),
            'timestamp' => now()->format('Y-m-d H:i:s'),
            'assignment_title' => $assignmentTitle,
            'student_name' => $studentName,
            'check_type' => $checkType,
            'file_name' => $fileName,
            'content_preview' => substr($content, 0, 200) . (strlen($content) > 200 ? '...' : ''),
            'result' => $result,
            'error' => $error,
        ];
        
        $history[] = $historyItem;
        session(['ai.plagiarism.history' => array_slice($history, -20)]); // Keep last 20 checks

        return view('admin.ai.plagiarism-checker.result', [
            'result' => $result,
            'error' => $error,
            'input' => $data,
            'fileName' => $fileName,
            'contentPreview' => substr($content, 0, 500),
        ]);
    }

    public function history()
    {
        $history = session('ai.plagiarism.history', []);
        return view('admin.ai.plagiarism-checker.history', [
            'history' => array_reverse($history), // Most recent first
        ]);
    }

    public function clearHistory()
    {
        session()->forget('ai.plagiarism.history');
        return redirect()->route('admin.ai.plagiarism-checker.index')->with('success', 'History cleared.');
    }
}
