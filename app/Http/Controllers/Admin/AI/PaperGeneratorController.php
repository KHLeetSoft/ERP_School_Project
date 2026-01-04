<?php

namespace App\Http\Controllers\Admin\AI;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use OpenAI\Laravel\Facades\OpenAI;
use App\Models\AiPaper;
use App\Models\Question;
use App\Models\QuestionPaper;
use App\Models\QuestionPaperQuestion;
use Barryvdh\DomPDF\Facade\Pdf;
use Yajra\DataTables\Facades\DataTables;

class PaperGeneratorController extends Controller
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
    
    public function index(Request $request)
    {
        return view('admin.ai.paper-generator.index');
    }

    public function datatable(Request $request)
    {
        $schoolId = auth()->user()->school_id ?? null;
        $query = AiPaper::query()
            ->when($schoolId, fn($q)=>$q->where('school_id', $schoolId))
            ->orderByDesc('id');

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('created_at', fn($r)=>optional($r->created_at)->format('Y-m-d H:i'))
            ->addColumn('actions', function ($r) {
                $show = route('admin.ai.paper-generator.show', $r->id);
                $pdf = route('admin.ai.paper-generator.download', $r->id);
                $toPaper = route('admin.ai.paper-generator.to-question-paper', $r->id);
                $del = route('admin.ai.paper-generator.destroy', $r->id);
                return '<div class="btn-group">'
                    .'<a href="'.$show.'" class="btn btn-sm btn-outline-secondary" title="View"><i class="bx bx-show"></i></a>'
                    .'<a href="'.$pdf.'" class="btn btn-sm btn-outline-primary" title="PDF"><i class="bx bxs-file-pdf"></i></a>'
                    .'<a href="'.$toPaper.'" class="btn btn-sm btn-success" title="Make Paper"><i class="bx bx-file"></i></a>'
                    .'<form method="POST" action="'.$del.'" style="display:inline-block;" onsubmit="return confirm(\'Delete this AI paper?\')">'
                    .csrf_field().method_field('DELETE')
                    .'<button class="btn btn-sm btn-outline-danger" title="Delete"><i class="bx bx-trash"></i></button>'
                    .'</form>'
                    .'</div>';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function create()
    {
        return view('admin.ai.paper-generator.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'subject' => 'required|string|max:120',
            'topic' => 'nullable|string|max:200',
            'type' => 'required|in:mcq,subjective,mixed',
            'difficulty' => 'required|in:easy,medium,hard',
            'num_questions' => 'required|integer|min:1|max:50',
            'notes' => 'nullable|string|max:5000',
            'source_file' => 'nullable|file|max:10240',
        ]);

        $subject = $data['subject'];
        $topic = $data['topic'] ?? '';
        $type = $data['type'];
        $difficulty = $data['difficulty'];
        $num = (int)$data['num_questions'];
        $notes = trim($data['notes'] ?? '');

        // Try to read plain text if a file was uploaded
        $fileText = '';
        $storedSourcePath = null;
        if ($request->hasFile('source_file')) {
            $uploaded = $request->file('source_file');
            $ext = strtolower($uploaded->getClientOriginalExtension());
            $storedPath = $uploaded->store('public/ai-papers/sources');
            $storedSourcePath = $storedPath;
            // Simple text extraction for .txt only (pdf/docx extraction would require extra libs)
            if ($ext === 'txt') {
                try { $fileText = trim(Storage::get($storedPath)); } catch (\Throwable $e) {}
            }
        }

        $schema = '{"title":"string","instructions":"string","questions":[{"type":"mcq|short|long","question":"string","options":["string"],"answer":"string","marks":1,"difficulty":"easy|medium|hard"}]}' ;

        $prompt = "Generate an exam paper as strict JSON for subject '$subject'".
                  ($topic ? ", topic '$topic'" : '').
                  ". Paper type: $type. Overall difficulty: $difficulty. Number of questions: $num.\n".
                  ($notes ? ("Additional constraints:\n".$notes."\n") : '').
                  ($fileText ? ("Use this syllabus/content as reference (verbatim):\n'''\n".$fileText."\n'''\n") : '').
                  "Rules:\n".
                  "- Return STRICT JSON matching this schema: $schema\n".
                  "- For type=mcq include exactly 4 options and a single correct 'answer' letter (A-D).\n".
                  "- For subjective types, set 'type' to 'short' or 'long' and omit 'options'; keep concise model answers in 'answer'.\n".
                  "- Each question must have 'marks' as a small integer and 'difficulty' one of easy, medium, hard.\n".
                  "- Title and instructions should be concise.";

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
            $error = 'Generation failed: '.$e->getMessage();
        }

        if (!is_array($decoded) || !isset($decoded['questions']) || !is_array($decoded['questions'])) {
            return back()->withInput()->with('error', $error ?: 'AI did not return valid JSON.');
        }

        $paper = AiPaper::create([
            'school_id' => auth()->user()->school_id ?? null,
            'created_by' => auth()->id(),
            'subject' => $subject,
            'topic' => $topic,
            'type' => $type,
            'difficulty' => $difficulty,
            'num_questions' => $num,
            'payload' => [
                'raw' => $raw,
                'parsed' => $decoded,
                'notes' => $notes,
                'source_file_path' => $storedSourcePath,
            ],
        ]);

        return redirect()->route('admin.ai.paper-generator.show', $paper->id)->with('success', 'Paper generated.');
    }

    public function show(AiPaper $paper)
    {
        $this->authorizePaper($paper);
        $data = $paper->payload['parsed'] ?? [];
        return view('admin.ai.paper-generator.show', compact('paper', 'data'));
    }

    public function downloadPdf(AiPaper $paper)
    {
        $this->authorizePaper($paper);
        $data = $paper->payload['parsed'] ?? [];

        $pdf = Pdf::loadView('admin.ai.paper-generator.pdf', [
            'paper' => $paper,
            'data' => $data,
        ])->setPaper('a4');

        $path = 'public/ai-papers/paper-'.$paper->id.'.pdf';
        Storage::put($path, $pdf->output());

        // Update if column exists
        try { $paper->update(['pdf_path' => $path]); } catch (\Throwable $e) {}

        return response()->download(storage_path('app/'.$path))->deleteFileAfterSend(false);
    }

    public function destroy(AiPaper $paper)
    {
        $this->authorizePaper($paper);
        try {
            if (!empty($paper->pdf_path)) {
                Storage::delete($paper->pdf_path);
            }
        } catch (\Throwable $e) {}
        $paper->delete();
        return redirect()->route('admin.ai.paper-generator.index')->with('success', 'Paper deleted.');
    }

    public function createQuestionBankPaper(AiPaper $paper)
    {
        $this->authorizePaper($paper);
        $parsed = $paper->payload['parsed'] ?? [];
        $questions = $parsed['questions'] ?? [];
        if (empty($questions)) {
            return back()->with('error', 'No questions found in payload.');
        }

        $qp = QuestionPaper::create([
            'school_id' => auth()->user()->school_id ?? null,
            'title' => ($parsed['title'] ?? ($paper->subject.' Paper')),
            'subject_id' => null,
            'total_marks' => collect($questions)->sum(fn($q)=> (int)($q['marks'] ?? 1)),
            'duration_mins' => 60,
            'generator_payload' => ['ai_paper_id' => $paper->id],
            'status' => 'draft',
        ]);

        $ordering = 1;
        foreach ($questions as $q) {
            $type = $q['type'] ?? 'short';
            $isMcq = strtolower($type) === 'mcq';
            $options = $isMcq ? ($q['options'] ?? []) : [];
            $marks = (int)($q['marks'] ?? 1);

            $question = Question::create([
                'school_id' => auth()->user()->school_id ?? null,
                'question_category_id' => null,
                'type' => $isMcq ? 'mcq' : 'subjective',
                'difficulty' => $q['difficulty'] ?? $paper->difficulty,
                'question_text' => $q['question'] ?? '',
                'options' => $isMcq ? array_values($options) : null,
                'correct_answer' => $isMcq ? ($q['answer'] ?? null) : ($q['answer'] ?? null),
                'explanation' => null,
                'marks' => $marks,
                'status' => 'active',
            ]);

            QuestionPaperQuestion::create([
                'question_paper_id' => $qp->id,
                'question_id' => $question->id,
                'marks' => $marks,
                'ordering' => $ordering++,
            ]);
        }

        return redirect()->route('admin.exams.question-bank.papers.show', $qp->id)
            ->with('success', 'Question Paper created from AI paper.');
    }

    private function authorizePaper(AiPaper $paper): void
    {
        $schoolId = auth()->user()->school_id ?? null;
        if ($schoolId && (int)$paper->school_id !== (int)$schoolId) {
            abort(403);
        }
    }
}


