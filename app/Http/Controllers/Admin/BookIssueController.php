<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookIssue;
use App\Models\Book;
use App\Models\Student;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\BookIssuesImport;
use App\Exports\BookIssuesExport;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BookIssueController extends Controller
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
			$query = BookIssue::with(['book', 'student'])
				->when($adminSchoolId, function ($q) use ($adminSchoolId) {
					$q->where('school_id', $adminSchoolId);
				})
				->when($request->filled('student_name'), function ($q) use ($request) {
					$name = trim($request->input('student_name'));
					$q->whereHas('student', function ($s) use ($name) {
						$s->where('first_name', 'like', "%$name%")
							->orWhere('last_name', 'like', "%$name%");
					});
				});
			return DataTables::of($query)
				->addIndexColumn()
				->addColumn('select', fn($issue) => '<input type="checkbox" class="row-select" value="' . e($issue->id) . '">')
				->addColumn('book_title', fn($issue) => e($issue->book?->title))
				->addColumn('student_name', fn($issue) => e($issue->student?->full_name))
				->addColumn('action', function ($issue) {
					$buttons = '<div class="d-flex align-items-center gap-2">';
					$buttons .= '<a href="' . route('admin.library.issues.show', $issue->id) . '" class="text-info" title="View"><i class="bx bx-show"></i></a>';
					$buttons .= '<a href="' . route('admin.library.issues.edit', $issue->id) . '" class="text-primary" title="Edit"><i class="bx bxs-edit"></i></a>';
					$buttons .= '<a href="' . route('admin.library.issues.quick-return', $issue->id) . '" class="text-success" title="Quick Return"><i class="bx bx-log-in"></i></a>';
					$buttons .= '<a href="' . route('admin.library.issues.extend', $issue->id) . '" class="text-warning" title="Extend Due Date"><i class="bx bx-time-five"></i></a>';
					$buttons .= '<a href="javascript:void(0);" data-id="' . $issue->id . '" class="text-danger delete-issue-btn" title="Delete"><i class="bx bx-trash"></i></a>';
					$buttons .= '</div>';
					return $buttons;
				})
				->rawColumns(['select', 'action'])
				->make(true);
		}
		$books = Book::orderBy('title')->pluck('title', 'id');
		$students = Student::orderBy('first_name')->get();
		return view('admin.library.issues.index', compact('books', 'students'));
	}

	public function create()
	{
		$books = Book::orderBy('title')->get();
		$students = Student::orderBy('first_name')->get();
		return view('admin.library.issues.create', compact('books', 'students'));
	}

	public function store(Request $request)
	{
		$validated = $request->validate([
			'book_id' => 'required|exists:books,id',
			'student_id' => 'required|exists:students,id',
			'issued_at' => 'required|date',
			'due_date' => 'required|date|after_or_equal:issued_at',
			'notes' => 'nullable|string',
		]);
		$validated['school_id'] = auth()->user()->school_id;
		$validated['status'] = Carbon::parse($validated['due_date'])->isPast() ? 'overdue' : 'issued';
		$validated['issued_by'] = auth()->id();
		$validated['fine_amount'] = 0;
		BookIssue::create($validated);
		return redirect()->route('admin.library.issues.index')->with('success', 'Book issued successfully');
	}

	public function show(BookIssue $issue)
	{
		$issue->load(['book', 'student']);
		return view('admin.library.issues.show', compact('issue'));
	}

	public function edit(BookIssue $issue)
	{
		$books = Book::orderBy('title')->get();
		$students = Student::orderBy('first_name')->get();
		return view('admin.library.issues.edit', compact('issue', 'books', 'students'));
	}

	public function update(Request $request, BookIssue $issue)
	{
		$validated = $request->validate([
			'book_id' => 'required|exists:books,id',
			'student_id' => 'required|exists:students,id',
			'issued_at' => 'required|date',
			'due_date' => 'required|date|after_or_equal:issued_at',
			'notes' => 'nullable|string',
			'fine_amount' => 'nullable|numeric|min:0',
		]);
		$validated['status'] = Carbon::parse($validated['due_date'])->isPast() && !$issue->returned_at ? 'overdue' : ($issue->returned_at ? 'returned' : 'issued');
		$issue->update($validated);
		return redirect()->route('admin.library.issues.index')->with('success', 'Issue updated successfully');
	}

	public function destroy(BookIssue $issue)
	{
		$issue->delete();
		return redirect()->route('admin.library.issues.index')->with('success', 'Issue deleted successfully');
	}

	public function quickReturn(BookIssue $issue)
	{
		if ($issue->returned_at) {
			return back()->with('success', 'Already returned');
		}
		$issue->returned_at = Carbon::now();
		$issue->returned_by = auth()->id();
		$issue->status = 'returned';
		$issue->fine_amount = $this->calculateFine($issue->due_date, $issue->returned_at);
		$issue->save();
		return redirect()->route('admin.library.issues.index')->with('success', 'Book returned');
	}

	public function extend(BookIssue $issue, Request $request)
	{
		if ($request->filled('new_due_date')) {
			$request->validate(['new_due_date' => 'required|date|after:due_date']);
			$issue->due_date = Carbon::parse($request->input('new_due_date'));
		} else {
			$days = (int) $request->input('days', 7);
			$issue->due_date = Carbon::parse($issue->due_date)->addDays(max(1, $days));
		}
		$issue->status = 'issued';
		$issue->save();
		return back()->with('success', 'Due date extended');
	}

	public function export()
	{
		return Excel::download(new BookIssuesExport(auth()->user()->school_id), 'book_issues.xlsx');
	}

	public function import(Request $request)
	{
		$request->validate(['file' => 'required|file|mimes:xlsx,csv,txt']);
		Excel::import(new BookIssuesImport(auth()->user()->school_id), $request->file('file'));
		return redirect()->route('admin.library.issues.index')->with('success', 'Issues imported successfully');
	}

	public function bulkDelete(Request $request)
	{
		$ids = $request->input('ids', []);
		if (!empty($ids)) {
			BookIssue::whereIn('id', $ids)->delete();
		}
		return redirect()->route('admin.library.issues.index')->with('success', 'Selected issues deleted');
	}

	public function bulkReturn(Request $request)
	{
		$ids = $request->validate(['ids' => 'required|array', 'ids.*' => 'integer|exists:book_issues,id'])['ids'];
		$now = Carbon::now();
		$issues = BookIssue::whereIn('id', $ids)->get();
		foreach ($issues as $issue) {
			if (!$issue->returned_at) {
				$issue->returned_at = $now;
				$issue->returned_by = auth()->id();
				$issue->status = 'returned';
				$issue->fine_amount = $this->calculateFine($issue->due_date, $issue->returned_at);
				$issue->save();
			}
		}
		return redirect()->route('admin.library.issues.index')->with('success', 'Selected issues marked as returned');
	}

	private function calculateFine(Carbon $dueDate, Carbon $returnedAt): float
	{
		$daysLate = max(0, $dueDate->diffInDays($returnedAt, false));
		$ratePerDay = 2.00; // could be pulled from settings later
		return $daysLate * $ratePerDay;
	}
}


