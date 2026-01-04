<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\BookCategory;
use App\Models\Student;
use App\Models\BookIssue;

class BooksController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::query();

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        if ($request->filled('category')) {
            $query->where('genre', $request->get('category'));
        }

        $books = $query->orderBy('title')->paginate(15);

        // Get categories for filter dropdown
        $categories = BookCategory::where('status', 'active')->orderBy('name')->get();

        $stats = [
            'total_books' => Book::count(),
            'available_books' => Book::where('status', 'available')->count(),
            'checked_out_books' => Book::where('status', 'checked_out')->count(),
            'overdue_books' => BookIssue::where('status', 'overdue')->count(),
        ];

        return view('librarian.books.index', compact('books', 'categories', 'stats'));
    }

    public function create()
    {
        $categories = BookCategory::where('status', 'active')->orderBy('name')->get();

        return view('librarian.books.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // Implement as needed
    }

    public function show(Book $book)
    {
        return view('librarian.books.show', compact('book'));
    }

    public function edit(Book $book)
    {
        return view('librarian.books.edit', compact('book'));
    }

    public function update(Request $request, Book $book)
    {
        // Implement as needed
    }

    public function destroy(Book $book)
    {
        // Implement as needed
    }

    public function getIssueFormData(Book $book)
    {
        $students = Student::select('id', 'name', 'admission_number')
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return response()->json(['students' => $students]);
    }

    public function issueBook(Request $request)
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'student_id' => 'required|exists:students,id',
            'due_date' => 'required|date|after:today',
            'notes' => 'nullable|string',
        ]);

        // Minimal placeholder action; integrate with BookIssue if needed
        // BookIssue::create([...]) etc.

        return redirect()->route('librarian.books.index')
            ->with('success', 'Book issue request captured.');
    }
}