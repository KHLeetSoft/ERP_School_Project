<?php

namespace App\Http\Controllers\Admin\AI;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;

class PerformancePredictionController extends Controller
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
        return view('admin.ai.performance-prediction.index');
    }

    public function predict(Request $request)
    {
        $data = $request->validate([
            'class_name' => 'nullable|string|max:100',
            'subject' => 'nullable|string|max:100',
            'difficulty' => 'nullable|in:easy,medium,hard',
            'notes' => 'nullable|string|max:5000',
            'students' => 'nullable|string|max:20000', // lines: name,attendance,avg_score
        ]);

        $className = $data['class_name'] ?? '';
        $subject = $data['subject'] ?? '';
        $difficulty = $data['difficulty'] ?? 'medium';
        $notes = trim($data['notes'] ?? '');
        $studentsRaw = trim($data['students'] ?? '');

        $schema = '{"predictions":[{"name":"string","predicted_score":0-100,"risk_level":"low|medium|high","advice":"string"}]}' ;

        $prompt = "Predict student performance for subject '$subject' in class '$className' (difficulty: $difficulty).\n".
                  "Use this student list (CSV lines: name,attendance_percent,avg_score):\n".
                  "'''\n$studentsRaw\n'''\n".
                  ( $notes ? ("Consider these notes: \n".$notes."\n") : '' ).
                  "Return STRICT JSON only, matching this schema: $schema.\n".
                  "predicted_score is 0-100 integer; risk_level based on likelihood of underperforming; advice concise.";

        $raw = '';
        $decoded = null;
        $error = null;
        try {
            $res = OpenAI::chat()->create([
                'model' => 'gpt-4o-mini',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a strict JSON generator. Output only JSON without markdown.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'temperature' => 0.2,
            ]);
            $raw = $res->choices[0]->message->content ?? '';
            $decoded = json_decode($raw, true);
        } catch (\Throwable $e) {
            $error = 'Prediction failed: '.$e->getMessage();
        }

        $predictions = [];
        if (is_array($decoded) && isset($decoded['predictions']) && is_array($decoded['predictions'])) {
            $predictions = $decoded['predictions'];
        }

        // store for dashboard
        try { session(['performance.predictions' => $predictions]); } catch (\Throwable $e) {}

        return view('admin.ai.performance-prediction.index', [
            'input' => $data,
            'predictions' => $predictions,
            'raw' => $raw,
            'error' => $error,
        ]);
    }

    public function dashboard()
    {
        return view('admin.ai.performance-prediction.dashboard');
    }
}


