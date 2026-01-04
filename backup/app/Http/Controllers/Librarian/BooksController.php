<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookCategory;
use App\Models\BookIssue;
use App\Models\BookReturn;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class BooksController extends Controller
{
    /**
     * Display a listing of books.
     */
    public function index(Request $request)
    {
        $query = Book::with(['issues' => function($q) {
            $q->where('status', 'issued');
        }]);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%")
                  ->orWhere('genre', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by genre
        if ($request->filled('genre')) {
            $query->where('genre', $request->genre);
        }

        $books = $query->paginate(15);

        // Get statistics
        $stats = [
            'total_books' => Book::count(),
            'available_books' => Book::where('status', 'available')->count(),
            'checked_out_books' => Book::where('status', 'checked_out')->count(),
            'lost_books' => Book::where('status', 'lost')->count(),
            'total_issues' => BookIssue::where('status', 'issued')->count(),
            'overdue_books' => BookIssue::where('status', 'overdue')->count(),
        ];

        // Get genres for filter
        $genres = Book::distinct()->pluck('genre')->filter()->sort();

        return view('librarian.books.index', compact('books', 'stats', 'genres'));
    }

    /**
     * Show the form for creating a new book.
     */
    public function create()
    {
        $categories = BookCategory::where('status', 'active')->get();
        $genres = Book::distinct()->pluck('genre')->filter()->sort();
        
        return view('librarian.books.create', compact('categories', 'genres'));
    }

    /**
     * Store a newly created book.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'nullable|string|unique:books,isbn|max:20',
            'genre' => 'nullable|string|max:100',
            'published_year' => 'nullable|integer|min:1800|max:' . date('Y'),
            'description' => 'nullable|string|max:1000',
            'stock_quantity' => 'required|integer|min:1|max:1000',
            'shelf_location' => 'nullable|string|max:100',
            'status' => 'required|in:available,checked_out,lost',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();
        $data['school_id'] = 1; // Assuming school_id = 1 for now

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            $image = $request->file('cover_image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $path = $image->storeAs('book_covers', $filename, 'public');
            $data['cover_image'] = $path;
        }

        Book::create($data);

        return redirect()->route('librarian.books.index')
            ->with('success', 'Book added successfully!');
    }

    /**
     * Display the specified book.
     */
    public function show(Book $book)
    {
        $book->load(['issues.student', 'returns.student']);
        
        $recent_issues = $book->issues()
            ->with('student')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $stats = [
            'total_issues' => $book->issues()->count(),
            'active_issues' => $book->activeIssues()->count(),
            'total_returns' => $book->returns()->count(),
            'available_quantity' => $book->available_quantity,
        ];

        return view('librarian.books.show', compact('book', 'recent_issues', 'stats'));
    }

    /**
     * Show the form for editing the specified book.
     */
    public function edit(Book $book)
    {
        $categories = BookCategory::where('status', 'active')->get();
        $genres = Book::distinct()->pluck('genre')->filter()->sort();
        
        return view('librarian.books.edit', compact('book', 'categories', 'genres'));
    }

    /**
     * Update the specified book.
     */
    public function update(Request $request, Book $book)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'nullable|string|unique:books,isbn,' . $book->id . '|max:20',
            'genre' => 'nullable|string|max:100',
            'published_year' => 'nullable|integer|min:1800|max:' . date('Y'),
            'description' => 'nullable|string|max:1000',
            'stock_quantity' => 'required|integer|min:1|max:1000',
            'shelf_location' => 'nullable|string|max:100',
            'status' => 'required|in:available,checked_out,lost',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            // Delete old image if exists
            if ($book->cover_image && Storage::disk('public')->exists($book->cover_image)) {
                Storage::disk('public')->delete($book->cover_image);
            }

            $image = $request->file('cover_image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $path = $image->storeAs('book_covers', $filename, 'public');
            $data['cover_image'] = $path;
        }

        $book->update($data);

        return redirect()->route('librarian.books.index')
            ->with('success', 'Book updated successfully!');
    }

    /**
     * Remove the specified book.
     */
    public function destroy(Book $book)
    {
        // Check if book has active issues
        if ($book->activeIssues()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete book with active issues!');
        }

        // Delete cover image if exists
        if ($book->cover_image && Storage::disk('public')->exists($book->cover_image)) {
            Storage::disk('public')->delete($book->cover_image);
        }

        $book->delete();

        return redirect()->route('librarian.books.index')
            ->with('success', 'Book deleted successfully!');
    }

    /**
     * Issue a book to a student.
     */
    public function issueBook(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'book_id' => 'required|exists:books,id',
            'student_id' => 'required|exists:students,id',
            'due_date' => 'required|date|after:today',
            'notes' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $book = Book::findOrFail($request->book_id);
        $student = Student::findOrFail($request->student_id);

        // Check if book is available
        if (!$book->is_available) {
            return redirect()->back()
                ->with('error', 'Book is not available for issue!');
        }

        // Check if student has already issued this book
        $existingIssue = BookIssue::where('book_id', $book->id)
            ->where('student_id', $student->id)
            ->where('status', 'issued')
            ->first();

        if ($existingIssue) {
            return redirect()->back()
                ->with('error', 'Student has already issued this book!');
        }

        // Create book issue
        BookIssue::create([
            'school_id' => 1,
            'book_id' => $book->id,
            'student_id' => $student->id,
            'issued_at' => now(),
            'due_date' => $request->due_date,
            'status' => 'issued',
            'notes' => $request->notes,
            'issued_by' => auth()->id(),
        ]);

        // Update book status
        $book->update(['status' => 'checked_out']);

        return redirect()->route('librarian.books.index')
            ->with('success', 'Book issued successfully!');
    }

    /**
     * Return a book.
     */
    public function returnBook(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'issue_id' => 'required|exists:book_issues,id',
            'condition' => 'required|in:good,fair,poor,damaged',
            'fine_paid' => 'nullable|numeric|min:0',
            'remarks' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $issue = BookIssue::findOrFail($request->issue_id);

        // Create book return record
        BookReturn::create([
            'school_id' => 1,
            'book_issue_id' => $issue->id,
            'book_id' => $issue->book_id,
            'student_id' => $issue->student_id,
            'returned_at' => now(),
            'condition' => $request->condition,
            'fine_paid' => $request->fine_paid ?? 0,
            'remarks' => $request->remarks,
            'received_by' => auth()->id(),
        ]);

        // Update issue status
        $issue->update([
            'status' => 'returned',
            'returned_at' => now(),
            'returned_by' => auth()->id(),
        ]);

        // Update book status
        $book = $issue->book;
        $book->update(['status' => 'available']);

        return redirect()->route('librarian.books.index')
            ->with('success', 'Book returned successfully!');
    }

    /**
     * Get book issue form data.
     */
    public function getIssueFormData(Request $request)
    {
        $bookId = $request->book_id;
        $book = Book::findOrFail($bookId);
        
        $students = Student::where('status', true)
            ->select('id', 'first_name', 'last_name', 'admission_number')
            ->get()
            ->map(function($student) {
                return [
                    'id' => $student->id,
                    'name' => $student->first_name . ' ' . $student->last_name,
                    'admission_number' => $student->admission_number,
                ];
            });

        return response()->json([
            'book' => $book,
            'students' => $students,
        ]);
    }
}