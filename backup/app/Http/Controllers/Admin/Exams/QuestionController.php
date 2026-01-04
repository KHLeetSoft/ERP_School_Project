<?php

namespace App\Http\Controllers\Admin\Exams;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\QuestionCategory;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\QuestionExport;
use App\Imports\QuestionImport;

class QuestionController extends Controller
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
		if ($request->ajax()) {
			$schoolId = auth()->user()->school_id ?? null;
			$query = Question::with('category')->where('school_id', $schoolId);
			return DataTables::of($query)
				->addIndexColumn()
				->addColumn('category_name', fn($r)=> optional($r->category)->name)
				->addColumn('actions', function($r){
					$show = route('admin.exams.question-bank.questions.show', $r->id);
					$edit = route('admin.exams.question-bank.questions.edit', $r->id);
					$destroy = route('admin.exams.question-bank.questions.destroy', $r->id);
					return '<div class="btn-group">'
						. '<a href="'.$show.'" class="btn btn-sm" title="View"><i class="bx bx-show"></i></a>'
						. '<a href="'.$edit.'" class="btn btn-sm" title="Edit"><i class="bx bxs-edit"></i></a>'
						. '<button type="button" class="btn btn-sm delete-q-btn" data-action="'.e($destroy).'" title="Delete"><i class="bx bx-trash"></i></button>'
						. '</div>';
				})
				->rawColumns(['actions'])
				->make(true);
		}
		$categories = QuestionCategory::orderBy('name')->get(['id','name']);
		return view('admin.exams.questionbank.questions.index', compact('categories'));
	}

	public function create()
	{
		$categories = QuestionCategory::orderBy('name')->get(['id','name']);
		return view('admin.exams.questionbank.questions.create', compact('categories'));
	}

	public function store(Request $request)
	{
		$data = $request->validate([
			'question_category_id' => 'nullable|exists:question_categories,id',
			'type' => 'required|in:mcq,boolean,short,long',
			'difficulty' => 'nullable|in:easy,medium,hard',
			'question_text' => 'required|string',
			'options' => 'nullable|array',
			'correct_answer' => 'nullable|string',
			'explanation' => 'nullable|string',
			'marks' => 'nullable|numeric',
			'status' => 'required|in:active,inactive',
		]);
		$data['school_id'] = auth()->user()->school_id ?? null;
		Question::create($data);
		return redirect()->route('admin.exams.question-bank.questions.index')->with('success','Question created.');
	}

	public function show(Question $question)
	{
		$question->load('category');
		return view('admin.exams.questionbank.questions.show', compact('question'));
	}

	public function edit(Question $question)
	{
		$categories = QuestionCategory::orderBy('name')->get(['id','name']);
		return view('admin.exams.questionbank.questions.edit', compact('question','categories'));
	}

	public function update(Request $request, Question $question)
	{
		$data = $request->validate([
			'question_category_id' => 'nullable|exists:question_categories,id',
			'type' => 'required|in:mcq,boolean,short,long',
			'difficulty' => 'nullable|in:easy,medium,hard',
			'question_text' => 'required|string',
			'options' => 'nullable|array',
			'correct_answer' => 'nullable|string',
			'explanation' => 'nullable|string',
			'marks' => 'nullable|numeric',
			'status' => 'required|in:active,inactive',
		]);
		$question->update($data);
		return redirect()->route('admin.exams.question-bank.questions.index')->with('success','Question updated.');
	}

	public function destroy(Question $question)
	{
		$question->delete();
		return back()->with('success','Question deleted.');
	}

	public function export()
	{
		$schoolId = auth()->user()->school_id ?? null;
		return Excel::download(new QuestionExport($schoolId), 'questions.xlsx');
	}

	public function import(Request $request)
	{
		$request->validate(['file' => ['required','file','mimes:xlsx,csv,txt']]);
		$schoolId = auth()->user()->school_id ?? null;
		Excel::import(new QuestionImport($schoolId), $request->file('file'));
		return back()->with('success','Import completed.');
	}
}



