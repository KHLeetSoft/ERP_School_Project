<?php

namespace App\Http\Controllers\Admin\Exams;

use App\Http\Controllers\Controller;
use App\Models\QuestionCategory;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\QuestionCategoryExport;
use App\Imports\QuestionCategoryImport;

class QuestionCategoryController extends Controller
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
			$query = QuestionCategory::where('school_id', $schoolId);
			return DataTables::of($query)
				->addIndexColumn()
				->addColumn('actions', function($r){
					$edit = route('admin.exams.question-bank.categories.edit', $r->id);
					$destroy = route('admin.exams.question-bank.categories.destroy', $r->id);
					return '<div class="btn-group">'
						. '<a href="'.$edit.'" class="btn btn-sm" title="Edit"><i class="bx bxs-edit"></i></a>'
						. '<button type="button" class="btn btn-sm delete-cat-btn" data-action="'.e($destroy).'" title="Delete"><i class="bx bx-trash"></i></button>'
						. '</div>';
				})
				->rawColumns(['actions'])
				->make(true);
		}
		return view('admin.exams.questionbank.categories.index');
	}

	public function create()
	{
		return view('admin.exams.questionbank.categories.create');
	}

	public function store(Request $request)
	{
		$data = $request->validate([
			'name' => 'required|string|max:255',
			'description' => 'nullable|string',
			'icon' => 'nullable|string|max:100',
			'status' => 'required|in:active,inactive',
		]);
		$data['school_id'] = auth()->user()->school_id ?? null;
		QuestionCategory::create($data);
		return redirect()->route('admin.exams.question-bank.categories.index')->with('success','Category created.');
	}

	public function edit(QuestionCategory $category)
	{
		return view('admin.exams.questionbank.categories.edit', compact('category'));
	}

	public function show(QuestionCategory $category)
	{
		return view('admin.exams.questionbank.categories.show', compact('category'));
	}

	public function update(Request $request, QuestionCategory $category)
	{
		$data = $request->validate([
			'name' => 'required|string|max:255',
			'description' => 'nullable|string',
			'icon' => 'nullable|string|max:100',
			'status' => 'required|in:active,inactive',
		]);
		$category->update($data);
		return redirect()->route('admin.exams.question-bank.categories.index')->with('success','Category updated.');
	}

	public function destroy(QuestionCategory $category)
	{
		$category->delete();
		return back()->with('success','Category deleted.');
	}

	public function export()
	{
		$schoolId = auth()->user()->school_id ?? null;
		return Excel::download(new QuestionCategoryExport($schoolId), 'question_categories.xlsx');
	}

	public function import(Request $request)
	{
		$request->validate(['file' => ['required','file','mimes:xlsx,csv,txt']]);
		$schoolId = auth()->user()->school_id ?? null;
		Excel::import(new QuestionCategoryImport($schoolId), $request->file('file'));
		return back()->with('success','Import completed.');
	}
}



