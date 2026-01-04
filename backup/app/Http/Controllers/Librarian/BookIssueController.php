<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use App\Models\BookIssue;
use App\Models\Book;
use App\Models\Student;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookIssueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bookIssues = BookIssue::with(['book', 'student'])
            ->where('school_id', auth()->user()->school_id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $stats = [
            'total_issues' => BookIssue::where('school_id', auth()->user()->school_id)->count(),
            'active_issues' => BookIssue::where('school_id', auth()->user()->school_id)
                ->whereIn('status', ['issued', 'overdue'])->count(),
            'overdue_books' => BookIssue::where('school_id', auth()->user()->school_id)
                ->where('status', 'overdue')->count(),
            'issued_books' => BookIssue::where('school_id', auth()->user()->school_id)
                ->where('status', 'issued')->count(),
            'returned_books' => BookIssue::where('school_id', auth()->user()->school_id)
                ->where('status', 'returned')->count(),
        ];

        return view('librarian.books.issues.index', compact('bookIssues', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $books = Book::where('school_id', auth()->user()->school_id)
            ->where('status', 'available')
            ->orderBy('title')
            ->get();
        
        $students = Student::where('school_id', auth()->user()->school_id)
            ->where('status', 'active')
            ->orderBy('first_name')
            ->get();

        return view('librarian.books.issues.create', compact('books', 'students'));
    }

    /**
     * Store a newly created resource in storage.
     */
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

        // Update book status
        $book = Book::find($validated['book_id']);
        $book->update(['status' => 'checked_out']);

        return redirect()->route('librarian.book-issues.index')
            ->with('success', 'Book issued successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(BookIssue $bookIssue)
    {
        $bookIssue->load(['book', 'student']);
        return view('librarian.books.issues.show', compact('bookIssue'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BookIssue $bookIssue)
    {
        $books = Book::where('school_id', auth()->user()->school_id)
            ->orderBy('title')
            ->get();
        
        $students = Student::where('school_id', auth()->user()->school_id)
            ->orderBy('first_name')
            ->get();

        return view('librarian.books.issues.edit', compact('bookIssue', 'books', 'students'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BookIssue $bookIssue)
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'student_id' => 'required|exists:students,id',
            'issued_at' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issued_at',
            'notes' => 'nullable|string',
            'fine_amount' => 'nullable|numeric|min:0',
        ]);

        $validated['status'] = Carbon::parse($validated['due_date'])->isPast() && !$bookIssue->returned_at 
            ? 'overdue' 
            : ($bookIssue->returned_at ? 'returned' : 'issued');

        $bookIssue->update($validated);

        return redirect()->route('librarian.book-issues.index')
            ->with('success', 'Book issue updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BookIssue $bookIssue)
    {
        $bookIssue->delete();
        return redirect()->route('librarian.book-issues.index')
            ->with('success', 'Book issue deleted successfully');
    }

    /**
     * Show overdue books list
     */
    public function overdue()
    {
        $overdueBooks = BookIssue::with(['book', 'student'])
            ->where('school_id', auth()->user()->school_id)
            ->where('status', 'overdue')
            ->orderBy('due_date', 'asc')
            ->paginate(20);

        $stats = [
            'overdue_count' => $overdueBooks->total(),
            'total_fine' => BookIssue::where('school_id', auth()->user()->school_id)
                ->where('status', 'overdue')
                ->sum('fine_amount'),
        ];

        return view('librarian.books.issues.overdue', compact('overdueBooks', 'stats'));
    }

    /**
     * Mark books as overdue
     */
    public function markOverdue(Request $request)
    {
        $overdueBooks = BookIssue::where('school_id', auth()->user()->school_id)
            ->where('status', 'issued')
            ->where('due_date', '<', Carbon::now())
            ->get();

        foreach ($overdueBooks as $bookIssue) {
            $bookIssue->update([
                'status' => 'overdue',
                'fine_amount' => $this->calculateFine($bookIssue->due_date),
            ]);
        }

        return redirect()->route('librarian.book-issues.overdue')
            ->with('success', "Marked {$overdueBooks->count()} books as overdue");
    }

    /**
     * Show return form
     */
    public function showReturnForm(BookIssue $bookIssue)
    {
        $bookIssue->load(['book', 'student']);
        return view('librarian.books.issues.return', compact('bookIssue'));
    }

    /**
     * Process book return
     */
    public function processReturn(Request $request, BookIssue $bookIssue)
    {
        $validated = $request->validate([
            'returned_at' => 'required|date',
            'condition' => 'required|in:good,fair,poor,damaged',
            'fine_paid' => 'nullable|numeric|min:0',
            'remarks' => 'nullable|string',
        ]);

        $bookIssue->update([
            'status' => 'returned',
            'returned_at' => Carbon::parse($validated['returned_at']),
            'returned_by' => auth()->id(),
            'fine_amount' => $validated['fine_paid'] ?? 0,
            'notes' => $validated['remarks'],
        ]);

        // Update book status
        $book = $bookIssue->book;
        $book->update(['status' => 'available']);

        return redirect()->route('librarian.book-issues.index')
            ->with('success', 'Book returned successfully');
    }

    /**
     * Show student history
     */
    public function studentHistory(Student $student)
    {
        $bookIssues = BookIssue::with(['book'])
            ->where('student_id', $student->id)
            ->where('school_id', auth()->user()->school_id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('librarian.books.issues.student-history', compact('bookIssues', 'student'));
    }

    /**
     * Show book history
     */
    public function bookHistory(Book $book)
    {
        $bookIssues = BookIssue::with(['student'])
            ->where('book_id', $book->id)
            ->where('school_id', auth()->user()->school_id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('librarian.books.issues.book-history', compact('bookIssues', 'book'));
    }

    /**
     * Calculate fine amount
     */
    private function calculateFine($dueDate, $returnDate = null)
    {
        $returnDate = $returnDate ? Carbon::parse($returnDate) : Carbon::now();
        $dueDate = Carbon::parse($dueDate);
        
        if ($returnDate->lte($dueDate)) {
            return 0;
        }

        $daysOverdue = $dueDate->diffInDays($returnDate);
        return $daysOverdue * 5; // ₹5 per day fine
    }
}
