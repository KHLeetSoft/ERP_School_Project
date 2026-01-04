<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\BooksImport;
use App\Exports\BooksExport;
use Yajra\DataTables\Facades\DataTables;

class BookController extends Controller
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

            $query = Book::query()->when($adminSchoolId, function ($q) use ($adminSchoolId) {
                $q->where('school_id', $adminSchoolId);
            });

             return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('select', function ($book) {
                    return '<input type="checkbox" class="row-select" value="' . e($book->id) . '">';
                })
                ->addColumn('action', function ($book) {
                    $buttons = '<div class="d-flex justify-content">';
                    $buttons .= '<a href="' . route('admin.library.books.show', $book->id) . '" class="text-info me-2"><i class="bx bx-show"></i></a>';

                    // Edit button - same style as class_sections
                    $buttons .= '<a href="' . route('admin.library.books.edit', $book->id) . '" 
                                    class="text-primary me-2" title="Edit">
                                    <i class="bx bxs-edit"></i>
                                </a>';

                    // Delete button with modal trigger
                    $buttons .= '<a href="javascript:void(0);" 
                                    data-id="' . $book->id . '" 
                                    class="text-danger delete-book-btn" 
                                    title="Delete">
                                    <i class="bx bx-trash"></i>
                                </a>';

                    $buttons .= '</div>';
                    return $buttons;
                })
                ->rawColumns(['select', 'action']) // ✅ fixed here
                ->make(true);
        }

        return view('admin.library.books.index');
    }


    public function create()
    {
        return view('admin.library.books.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'genre' => 'nullable|string|max:255',
            'published_year' => 'nullable|integer|min:0|max:'.(date('Y') + 1),
            'isbn' => 'nullable|string|max:50|unique:books,isbn',
            'description' => 'nullable|string',
            'stock_quantity' => 'nullable|integer|min:0',
            'shelf_location' => 'nullable|string|max:255',
            'status' => 'required|in:available,checked_out,lost',
        ]);
        $validated['school_id'] = auth()->user()->school_id;
        Book::create($validated);
        return redirect()->route('admin.library.books.index')->with('success', 'Book created successfully');
    }

    public function show(Book $book)
    {
        return view('admin.library.books.show', compact('book'));
    }

    public function edit(Book $book)
    {
        return view('admin.library.books.edit', compact('book'));
    }

    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'genre' => 'nullable|string|max:255',
            'published_year' => 'nullable|integer|min:0|max:'.(date('Y') + 1),
            'isbn' => 'nullable|string|max:50|unique:books,isbn,' . $book->id,
            'description' => 'nullable|string',
            'stock_quantity' => 'nullable|integer|min:0',
            'shelf_location' => 'nullable|string|max:255',
            'status' => 'required|in:available,checked_out,lost',
        ]);
        $book->update($validated);
        return redirect()->route('admin.library.books.index')->with('success', 'Book updated successfully');
    }

    public function destroy(Book $book)
    {
        $book->delete();
        return redirect()->route('admin.library.books.index')->with('success', 'Book deleted successfully');
    }

    public function export()
    {
        return Excel::download(new BooksExport(auth()->user()->school_id), 'books.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv,txt',
        ]);
        Excel::import(new BooksImport(auth()->user()->school_id), $request->file('file'));
        return redirect()->route('admin.library.books.index')->with('success', 'Books imported successfully');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!empty($ids)) {
            Book::whereIn('id', $ids)->delete();
        }
        return redirect()->route('admin.library.books.index')->with('success', 'Selected books deleted');
    }
}


