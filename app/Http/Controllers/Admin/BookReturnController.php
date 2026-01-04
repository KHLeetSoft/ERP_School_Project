<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookReturn;
use App\Models\BookIssue;
use App\Models\Book;
use App\Models\Student;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BookReturnsExport;
use App\Imports\BookReturnsImport;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class BookReturnController extends Controller
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
			$adminSchoolId = auth()->user()->school_id;
			$query = BookReturn::with(['issue.book', 'issue.student'])
				->when($adminSchoolId, fn($q) => $q->where('school_id', $adminSchoolId))
				->when($request->filled('student_name'), function ($q) use ($request) {
					$name = trim($request->input('student_name'));
					$q->whereHas('issue.student', function ($s) use ($name) {
						$s->where('first_name', 'like', "%$name%")
							->orWhere('last_name', 'like', "%$name%");
					});
				});
			return DataTables::of($query)
				->addIndexColumn()
				->addColumn('select', fn($ret) => '<input type="checkbox" class="row-select" value="' . e($ret->id) . '">')
				->addColumn('book_title', fn($ret) => e($ret->issue?->book?->title))
				->addColumn('student_name', fn($ret) => e($ret->issue?->student?->full_name))
				->addColumn('action', function ($ret) {
					$buttons = '<div class="d-flex align-items-center gap-2">';
					$buttons .= '<a href="' . route('admin.library.returns.show', $ret->id) . '" class="text-info" title="View"><i class="bx bx-show"></i></a>';
					$buttons .= '<a href="' . route('admin.library.returns.print', $ret->id) . '" class="text-secondary" title="Print Receipt"><i class="bx bx-printer"></i></a>';
					$buttons .= '<a href="javascript:void(0);" data-id="' . $ret->id . '" class="text-danger delete-return-btn" title="Delete"><i class="bx bx-trash"></i></a>';
					$buttons .= '</div>';
					return $buttons;
				})
				->rawColumns(['select', 'action'])
				->make(true);
		}
		$issues = BookIssue::with(['book', 'student'])->whereNull('returned_at')->orderByDesc('issued_at')->get();
		return view('admin.library.returns.index', compact('issues'));
	}

	public function create()
	{
		$issues = BookIssue::with(['book', 'student'])->whereNull('returned_at')->orderByDesc('issued_at')->get();
		return view('admin.library.returns.create', compact('issues'));
	}

	public function store(Request $request)
	{
		$validated = $request->validate([
			'book_issue_id' => 'required|exists:book_issues,id',
			'returned_at' => 'required|date',
			'condition' => 'nullable|string|max:255',
			'fine_paid' => 'nullable|numeric|min:0',
			'remarks' => 'nullable|string',
		]);
		$issue = BookIssue::findOrFail($validated['book_issue_id']);
		$schoolId = auth()->user()->school_id;
		$return = BookReturn::create([
			'school_id' => $schoolId,
			'book_issue_id' => $issue->id,
			'book_id' => $issue->book_id,
			'student_id' => $issue->student_id,
			'returned_at' => Carbon::parse($validated['returned_at']),
			'condition' => $validated['condition'] ?? null,
			'fine_paid' => $validated['fine_paid'] ?? 0,
			'remarks' => $validated['remarks'] ?? null,
			'received_by' => auth()->id(),
		]);

		// Update issue
		$issue->returned_at = $return->returned_at;
		$issue->returned_by = auth()->id();
		$issue->status = 'returned';
		$issue->fine_amount = $validated['fine_paid'] ?? $issue->fine_amount;
		$issue->save();

		return redirect()->route('admin.library.returns.index')->with('success', 'Book return recorded');
	}

	public function show(BookReturn $return)
	{
		$return->load(['issue.book', 'issue.student']);
		return view('admin.library.returns.show', compact('return'));
	}

	public function destroy(BookReturn $return)
	{
		$return->delete();
		return redirect()->route('admin.library.returns.index')->with('success', 'Return deleted');
	}

	public function export()
	{
		return Excel::download(new BookReturnsExport(auth()->user()->school_id), 'book_returns.xlsx');
	}

	public function import(Request $request)
	{
		$request->validate(['file' => 'required|file|mimes:xlsx,csv,txt']);
		Excel::import(new BookReturnsImport(auth()->user()->school_id), $request->file('file'));
		return redirect()->route('admin.library.returns.index')->with('success', 'Returns imported successfully');
	}

	public function bulkDelete(Request $request)
	{
		$ids = $request->input('ids', []);
		if (!empty($ids)) { BookReturn::whereIn('id', $ids)->delete(); }
		return redirect()->route('admin.library.returns.index')->with('success', 'Selected returns deleted');
	}

	public function print(BookReturn $return)
	{
		$return->load(['issue.book', 'issue.student']);
		return view('admin.library.returns.print', compact('return'));
	}
}


