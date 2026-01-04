<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\BookCategoriesImport;
use App\Exports\BookCategoriesExport;
use Yajra\DataTables\Facades\DataTables;

class BookCategoryController extends Controller
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
			$query = BookCategory::query()->when($adminSchoolId, function ($q) use ($adminSchoolId) {
				$q->where('school_id', $adminSchoolId);
			});

			return DataTables::of($query)
				->addIndexColumn()
				->addColumn('select', function ($category) {
					return '<input type="checkbox" class="row-select" value="' . e($category->id) . '">';
				})
				->addColumn('action', function ($category) {
					$buttons = '<div class="d-flex justify-content align-items-center gap-2">';
					$buttons .= '<a href="' . route('admin.library.categories.show', $category->id) . '" class="text-info" title="View"><i class="bx bx-show"></i></a>';
					$buttons .= '<a href="' . route('admin.library.categories.edit', $category->id) . '" class="text-primary" title="Edit"><i class="bx bxs-edit"></i></a>';
					$buttons .= '<form method="POST" action="' . route('admin.library.categories.toggle-status', $category->id) . '" style="display:inline">' . csrf_field() . '<button type="submit" class="btn btn-link p-0 ' . ($category->status === 'active' ? 'text-success' : 'text-secondary') . '" title="Toggle Status"><i class="bx bx-toggle-' . ($category->status === 'active' ? 'right' : 'left') . '"></i></button></form>';
					$buttons .= '<a href="javascript:void(0);" data-id="' . $category->id . '" class="text-danger delete-category-btn" title="Delete"><i class="bx bx-trash"></i></a>';
					$buttons .= '</div>';
					return $buttons;
				})
				->rawColumns(['select', 'action'])
				->make(true);
		}

		return view('admin.library.categories.index');
	}

	public function create()
	{
		return view('admin.library.categories.create');
	}

	public function store(Request $request)
	{
		$validated = $request->validate([
			'name' => 'required|string|max:255|unique:book_categories,name',
			'slug' => 'nullable|string|max:255|unique:book_categories,slug',
			'description' => 'nullable|string',
			'status' => 'required|in:active,inactive',
		]);
		$validated['slug'] = $validated['slug'] ?: Str::slug($validated['name']);
		$validated['school_id'] = auth()->user()->school_id;
		BookCategory::create($validated);
		return redirect()->route('admin.library.categories.index')->with('success', 'Category created successfully');
	}

	public function show(BookCategory $category)
	{
		return view('admin.library.categories.show', compact('category'));
	}

	public function edit(BookCategory $category)
	{
		return view('admin.library.categories.edit', compact('category'));
	}

	public function update(Request $request, BookCategory $category)
	{
		$validated = $request->validate([
			'name' => 'required|string|max:255|unique:book_categories,name,' . $category->id,
			'slug' => 'nullable|string|max:255|unique:book_categories,slug,' . $category->id,
			'description' => 'nullable|string',
			'status' => 'required|in:active,inactive',
		]);
		$validated['slug'] = $validated['slug'] ?: Str::slug($validated['name']);
		$category->update($validated);
		return redirect()->route('admin.library.categories.index')->with('success', 'Category updated successfully');
	}

	public function destroy(BookCategory $category)
	{
		$category->delete();
		return redirect()->route('admin.library.categories.index')->with('success', 'Category deleted successfully');
	}

	public function export()
	{
		return Excel::download(new BookCategoriesExport(auth()->user()->school_id), 'book_categories.xlsx');
	}

	public function import(Request $request)
	{
		$request->validate([
			'file' => 'required|file|mimes:xlsx,csv,txt',
		]);
		Excel::import(new BookCategoriesImport(auth()->user()->school_id), $request->file('file'));
		return redirect()->route('admin.library.categories.index')->with('success', 'Categories imported successfully');
	}

	public function bulkDelete(Request $request)
	{
		$ids = $request->input('ids', []);
		if (!empty($ids)) {
			BookCategory::whereIn('id', $ids)->delete();
		}
		return redirect()->route('admin.library.categories.index')->with('success', 'Selected categories deleted');
	}

	public function bulkStatus(Request $request)
	{
		$validated = $request->validate([
			'ids' => 'required|array',
			'ids.*' => 'integer|exists:book_categories,id',
			'status' => 'required|in:active,inactive',
		]);
		BookCategory::whereIn('id', $validated['ids'])->update(['status' => $validated['status']] );
		return redirect()->route('admin.library.categories.index')->with('success', 'Status updated for selected categories');
	}

	public function toggleStatus(BookCategory $category)
	{
		$category->status = $category->status === 'active' ? 'inactive' : 'active';
		$category->save();
		return redirect()->route('admin.library.categories.index')->with('success', 'Status updated');
	}
}


