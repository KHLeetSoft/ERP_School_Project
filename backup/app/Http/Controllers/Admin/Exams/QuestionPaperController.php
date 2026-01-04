<?php

namespace App\Http\Controllers\Admin\Exams;

use App\Http\Controllers\Controller;
use App\Models\QuestionPaper;
use App\Models\QuestionPaperQuestion;
use App\Models\Question;
use App\Models\QuestionCategory;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class QuestionPaperController extends Controller
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
		$schoolId = auth()->user()->school_id ?? null;
		if ($request->ajax()) {
			$query = QuestionPaper::where('school_id', $schoolId)->orderByDesc('id');
			return DataTables::of($query)
				->addIndexColumn()
				->addColumn('actions', function ($r) {
					$show = route('admin.exams.question-bank.papers.show', $r->id);
					$edit = route('admin.exams.question-bank.papers.edit', $r->id);
					return '<div class="btn-group">'
						. '<a href="'.$show.'" class="btn btn-sm" title="View"><i class="bx bx-show"></i></a>'
						. '<a href="'.$edit.'" class="btn btn-sm" title="Edit"><i class="bx bxs-edit"></i></a>'
						. '</div>';
				})
				->rawColumns(['actions'])
				->make(true);
		}
		return view('admin.exams.questionbank.papers.index');
	}

	public function create()
	{
		$categories = QuestionCategory::orderBy('name')->get(['id','name']);
		return view('admin.exams.questionbank.papers.create', compact('categories'));
	}

	public function store(Request $request)
	{
		$data = $request->validate([
			'title' => 'required|string|max:255',
			'subject_name' => 'nullable|string|max:255',
			'total_marks' => 'required|integer|min:1',
			'duration_mins' => 'required|integer|min:1',
			'constraints' => 'nullable|array',
		]);
		$paper = QuestionPaper::create([
			'school_id' => auth()->user()->school_id ?? null,
			'title' => $data['title'],
			'subject_name' => $data['subject_name'] ?? null,
			'total_marks' => $data['total_marks'],
			'duration_mins' => $data['duration_mins'],
			'generator_payload' => $data['constraints'] ?? [],
		]);

		// Simple selection: pick newest active questions until reaching total marks
		$marksAccum = 0;
		$questions = Question::where('status','active')
			->when(($data['constraints']['category_id'] ?? null), fn($q,$cid)=>$q->where('question_category_id', $cid))
			->orderByDesc('id')->get();
		foreach ($questions as $idx => $q) {
			if ($marksAccum + (int)($q->marks ?? 1) > (int)$paper->total_marks) continue;
			QuestionPaperQuestion::create([
				'question_paper_id' => $paper->id,
				'question_id' => $q->id,
				'marks' => (int)($q->marks ?? 1),
				'ordering' => $idx + 1,
			]);
			$marksAccum += (int)($q->marks ?? 1);
			if ($marksAccum >= (int)$paper->total_marks) break;
		}

		return redirect()->route('admin.exams.question-bank.papers.show', $paper->id)->with('success','Paper generated.');
	}

	public function show(QuestionPaper $paper)
	{
		$paper->load(['questions.question']);
		return view('admin.exams.questionbank.papers.show', compact('paper'));
	}

	public function edit(QuestionPaper $paper)
	{
		$paper->load(['questions.question']);
		return view('admin.exams.questionbank.papers.edit', compact('paper'));
	}
}



