<?php

namespace App\Http\Controllers\Admin\Exams;

use App\Http\Controllers\Controller;
use App\Models\ExamTabulation;
use App\Models\Exam;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExamTabulationExport;
use App\Imports\ExamTabulationImport;

class ExamTabulationController extends Controller
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
			'all' => ExamTabulation::where('school_id', $schoolId)->count(),
			'published' => ExamTabulation::where('school_id', $schoolId)->where('status','published')->count(),
			'draft' => ExamTabulation::where('school_id', $schoolId)->where('status','draft')->count(),
		];

		$topperByClass = ExamTabulation::where('school_id', $schoolId)
			->selectRaw('class_name, MIN(rank) as best_rank')
			->groupBy('class_name')
			->pluck('best_rank','class_name')
			->toArray(); // ✅ Collection ko plain array me convert kiya

		$recent = ExamTabulation::with('exam')
			->where('school_id', $schoolId)
			->latest()
			->limit(10)
			->get();

		return view('admin.exams.tabulation.dashboard', compact('totals','topperByClass','recent'));
	}


	public function index(Request $request)
	{
		if ($request->ajax()) {
			$schoolId = auth()->user()->school_id ?? null;
			$query = ExamTabulation::with('exam')->where('school_id', $schoolId)
				->when($request->exam_id, fn($q)=>$q->where('exam_id', $request->exam_id))
				->when($request->class_name, fn($q)=>$q->where('class_name', 'like', "%{$request->class_name}%"))
				->when($request->student_name, fn($q)=>$q->where('student_name', 'like', "%{$request->student_name}%"));
			return DataTables::of($query)
				->addIndexColumn()
				->addColumn('exam_title', fn($r)=> optional($r->exam)->title)
				->addColumn('actions', function ($r) {
					$show = route('admin.exams.tabulation.show', $r->id);
					$edit = route('admin.exams.tabulation.edit', $r->id);
					$destroy = route('admin.exams.tabulation.destroy', $r->id);
					return '<div class="btn-group" role="group">'
						. '<a href="' . $show . '" class="btn btn-sm" title="View"><i class="bx bx-show"></i></a>'
						. '<a href="' . $edit . '" class="btn btn-sm" title="Edit"><i class="bx bxs-edit"></i></a>'
						. '<button type="button" class="btn btn-sm delete-tabulation-btn" data-action="' . e($destroy) . '" title="Delete"><i class="bx bx-trash"></i></button>'
						. '</div>';
				})
				->rawColumns(['actions'])
				->make(true);
		}
		$exams = Exam::orderByDesc('start_date')->get(['id','title']);
		return view('admin.exams.tabulation.index', compact('exams'));
	}

	public function create()
	{
		$exams = Exam::orderByDesc('start_date')->get(['id','title']);
		return view('admin.exams.tabulation.create', compact('exams'));
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
			'total_marks' => 'nullable|numeric',
			'max_total_marks' => 'nullable|numeric',
			'percentage' => 'nullable|numeric',
			'grade' => 'nullable|string|max:10',
			'result_status' => 'nullable|in:pass,fail',
			'rank' => 'nullable|integer',
			'remarks' => 'nullable|string',
			'status' => 'required|in:published,draft',
		]);
		$data['school_id'] = auth()->user()->school_id ?? null;
		ExamTabulation::create($data);
		return redirect()->route('admin.exams.tabulation.index')->with('success', 'Tabulation saved.');
	}

	public function show(ExamTabulation $tabulation)
	{
		return view('admin.exams.tabulation.show', compact('tabulation'));
	}

	public function edit(ExamTabulation $tabulation)
	{
		$exams = Exam::orderByDesc('start_date')->get(['id','title']);
		return view('admin.exams.tabulation.edit', compact('tabulation','exams'));
	}

	public function update(Request $request, ExamTabulation $tabulation)
	{
		$data = $request->validate([
			'exam_id' => 'required|exists:exams,id',
			'class_name' => 'nullable|string|max:100',
			'section_name' => 'nullable|string|max:50',
			'student_id' => 'nullable|integer',
			'student_name' => 'required|string|max:255',
			'admission_no' => 'nullable|string|max:100',
			'roll_no' => 'nullable|string|max:50',
			'total_marks' => 'nullable|numeric',
			'max_total_marks' => 'nullable|numeric',
			'percentage' => 'nullable|numeric',
			'grade' => 'nullable|string|max:10',
			'result_status' => 'nullable|in:pass,fail',
			'rank' => 'nullable|integer',
			'remarks' => 'nullable|string',
			'status' => 'required|in:published,draft',
		]);
		$tabulation->update($data);
		return redirect()->route('admin.exams.tabulation.index')->with('success', 'Tabulation updated.');
	}

	public function destroy(ExamTabulation $tabulation)
	{
		$tabulation->delete();
		return back()->with('success', 'Tabulation deleted.');
	}

	public function export()
	{
		$schoolId = auth()->user()->school_id ?? null;
		return Excel::download(new ExamTabulationExport($schoolId), 'exam_tabulations.xlsx');
	}

	public function import(Request $request)
	{
		$request->validate(['file' => ['required','file','mimes:xlsx,csv,txt']]);
		$schoolId = auth()->user()->school_id ?? null;
		Excel::import(new ExamTabulationImport($schoolId), $request->file('file'));
		return back()->with('success', 'Import completed.');
	}
}



