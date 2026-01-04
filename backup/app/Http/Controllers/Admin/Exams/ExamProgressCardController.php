<?php

namespace App\Http\Controllers\Admin\Exams;

use App\Http\Controllers\Controller;
use App\Models\ExamProgressCard;
use App\Models\Exam;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExamProgressCardExport;
use App\Imports\ExamProgressCardImport;

class ExamProgressCardController extends Controller
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
	public function dashboard()
	{
		$schoolId = auth()->user()->school_id ?? null;
		$totals = [
			'all' => ExamProgressCard::where('school_id', $schoolId)->count(),
			'published' => ExamProgressCard::where('school_id', $schoolId)->where('status','published')->count(),
			'draft' => ExamProgressCard::where('school_id', $schoolId)->where('status','draft')->count(),
		];
		$recent = ExamProgressCard::with('exam')->where('school_id', $schoolId)->orderByDesc('created_at')->limit(10)->get();
		return view('admin.exams.progresscard.dashboard', compact('totals','recent'));
	}

	public function index(Request $request)
	{
		if ($request->ajax()) {
			$schoolId = auth()->user()->school_id ?? null;
			$query = ExamProgressCard::with('exam')->where('school_id', $schoolId)
				->when($request->exam_id, fn($q)=>$q->where('exam_id', $request->exam_id))
				->when($request->class_name, fn($q)=>$q->where('class_name', 'like', "%{$request->class_name}%"))
				->when($request->student_name, fn($q)=>$q->where('student_name', 'like', "%{$request->student_name}%"));
			return DataTables::of($query)
				->addIndexColumn()
				->addColumn('exam_title', fn($r)=> optional($r->exam)->title)
				->addColumn('actions', function ($r) {
					$show = route('admin.exams.progress-card.show', $r->id);
					$edit = route('admin.exams.progress-card.edit', $r->id);
					$destroy = route('admin.exams.progress-card.destroy', $r->id);
					return '<div class="btn-group" role="group">'
						. '<a href="' . $show . '" class="btn btn-sm" title="View"><i class="bx bx-show"></i></a>'
						. '<a href="' . $edit . '" class="btn btn-sm" title="Edit"><i class="bx bxs-edit"></i></a>'
						. '<button type="button" class="btn btn-sm delete-card-btn" data-action="' . e($destroy) . '" title="Delete"><i class="bx bx-trash"></i></button>'
						. '</div>';
				})
				->rawColumns(['actions'])
				->make(true);
		}
		$exams = Exam::orderByDesc('start_date')->get(['id','title']);
		return view('admin.exams.progresscard.index', compact('exams'));
	}

	public function create()
	{
		$exams = Exam::orderByDesc('start_date')->get(['id','title']);
		return view('admin.exams.progresscard.create', compact('exams'));
	}

	public function store(Request $request)
	{
		$data = $request->validate([
			'exam_id' => 'required|exists:exams,id',
			'class_name' => 'nullable|string|max:100',
			'section_name' => 'nullable|string|max:50',
			'student_id' => 'nullable|integer',
			'student_name' => 'required|string|max:255',
			'admission_no' => 'nullable|string|max:100',
			'roll_no' => 'nullable|string|max:50',
			'overall_percentage' => 'nullable|numeric',
			'overall_grade' => 'nullable|string|max:10',
			'overall_result_status' => 'nullable|in:pass,fail',
			'remarks' => 'nullable|string',
			'status' => 'required|in:published,draft',
			'data' => 'nullable|array',
		]);
		$data['school_id'] = auth()->user()->school_id ?? null;
		ExamProgressCard::create($data);
		return redirect()->route('admin.exams.progress-card.index')->with('success', 'Progress card saved.');
	}

	public function show(ExamProgressCard $progress_card)
	{
		return view('admin.exams.progresscard.show', ['card' => $progress_card]);
	}

	public function edit(ExamProgressCard $progress_card)
	{
		$exams = Exam::orderByDesc('start_date')->get(['id','title']);
		return view('admin.exams.progresscard.edit', ['card' => $progress_card, 'exams' => $exams]);
	}

	public function update(Request $request, ExamProgressCard $progress_card)
	{
		$data = $request->validate([
			'exam_id' => 'required|exists:exams,id',
			'class_name' => 'nullable|string|max:100',
			'section_name' => 'nullable|string|max:50',
			'student_id' => 'nullable|integer',
			'student_name' => 'required|string|max:255',
			'admission_no' => 'nullable|string|max:100',
			'roll_no' => 'nullable|string|max:50',
			'overall_percentage' => 'nullable|numeric',
			'overall_grade' => 'nullable|string|max:10',
			'overall_result_status' => 'nullable|in:pass,fail',
			'remarks' => 'nullable|string',
			'status' => 'required|in:published,draft',
			'data' => 'nullable|array',
		]);
		$progress_card->update($data);
		return redirect()->route('admin.exams.progress-card.index')->with('success', 'Progress card updated.');
	}

	public function destroy(ExamProgressCard $progress_card)
	{
		$progress_card->delete();
		return back()->with('success', 'Progress card deleted.');
	}

	public function export()
	{
		$schoolId = auth()->user()->school_id ?? null;
		return Excel::download(new ExamProgressCardExport($schoolId), 'progress_cards.xlsx');
	}

	public function import(Request $request)
	{
		$request->validate(['file' => ['required','file','mimes:xlsx,csv,txt']]);
		$schoolId = auth()->user()->school_id ?? null;
		Excel::import(new ExamProgressCardImport($schoolId), $request->file('file'));
		return back()->with('success', 'Import completed.');
	}
}



