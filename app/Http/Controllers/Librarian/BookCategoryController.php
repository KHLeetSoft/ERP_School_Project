<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use App\Models\BookCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BookCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = BookCategory::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        $categories = $query->orderBy('name')->paginate(15);

        return view('librarian.book-categories.index', compact('categories'));
    }

    public function create()
    {
        return view('librarian.book-categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:book_categories,name',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['school_id'] = Auth::user()->school_id ?? null;

        BookCategory::create($validated);

        return redirect()->route('librarian.book-categories.index')
            ->with('success', 'Book category created successfully.');
    }

    public function show(BookCategory $bookCategory)
    {
        $books = $bookCategory->books()->paginate(10);
        
        return view('librarian.book-categories.show', compact('bookCategory', 'books'));
    }

    public function edit(BookCategory $bookCategory)
    {
        return view('librarian.book-categories.edit', compact('bookCategory'));
    }

    public function update(Request $request, BookCategory $bookCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:book_categories,name,' . $bookCategory->id,
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $bookCategory->update($validated);

        return redirect()->route('librarian.book-categories.index')
            ->with('success', 'Book category updated successfully.');
    }

    public function destroy(BookCategory $bookCategory)
    {
        // Check if category has books
        if ($bookCategory->books()->count() > 0) {
            return redirect()->route('librarian.book-categories.index')
                ->with('error', 'Cannot delete category that has books assigned to it.');
        }

        $bookCategory->delete();

        return redirect()->route('librarian.book-categories.index')
            ->with('success', 'Book category deleted successfully.');
    }

    public function toggleStatus(BookCategory $bookCategory)
    {
        $bookCategory->update([
            'status' => $bookCategory->status === 'active' ? 'inactive' : 'active'
        ]);

        $status = $bookCategory->status === 'active' ? 'activated' : 'deactivated';
        
        return redirect()->route('librarian.book-categories.index')
            ->with('success', "Book category {$status} successfully.");
    }
}