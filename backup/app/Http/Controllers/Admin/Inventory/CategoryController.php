<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use App\Models\InventoryCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
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

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $adminUser = auth()->guard('admin')->user();
            $schoolId = $adminUser ? $adminUser->school_id : 1;
            
            $query = InventoryCategory::query();
            
            // Apply school filter if schoolId exists
            if ($schoolId) {
                $query->where('school_id', $schoolId);
            }
            
            $query->withCount('items')
                  ->orderBy('sort_order')
                  ->orderBy('name');

            $rows = $query->get();
        
            return DataTables::of($rows)
                ->addIndexColumn()
                ->addColumn('category', function ($data) {
                    return '<div class="d-flex align-items-center">
                                <div class="category-icon me-2" style="background-color: ' . $data->color . '20; color: ' . $data->color . ';">
                                    <i class="' . ($data->icon ?? 'fas fa-tag') . '"></i>
                                </div>
                                <div>
                                    <a href="' . route('admin.inventory.categories.show', $data->id) . '" class="link me-2" title="View Category Details">' . e($data->name) . '</a>
                                    <br><small class="text-muted">' . e($data->slug) . '</small>
                                </div>
                            </div>';
                })
                ->addColumn('description', function ($data) {
                    return $data->description ? Str::limit($data->description, 50) : '<span class="text-muted">-</span>';
                })
                ->addColumn('items_count', function ($data) {
                    return '<span class="badge badge-pill badge-light-info">' . ($data->items_count ?? 0) . '</span>';
                })
                ->addColumn('sort_order', function ($data) {
                    return '<span class="badge badge-pill badge-light-secondary">' . $data->sort_order . '</span>';
                })
                ->addColumn('status', function ($data) {
                    if ($data->is_active) {
                        return '<span class="badge badge-pill badge-light-success">Active</span>';
                    } else {
                        return '<span class="badge badge-pill badge-light-danger">Inactive</span>';
                    }
                })
                ->addColumn('created_at', function ($data) {
                    return $data->created_at->format('M d, Y');
                })
                ->addColumn('action', function ($data) {
                    $buttons = '<div class="d-flex justify-content">';

                    $buttons .= '<a href="' . route('admin.inventory.categories.show', $data->id) . '" class="text-info me-2" title="View">
                                    <i class="bx bx-show"></i>
                                </a>';

                    $buttons .= '<a href="' . route('admin.inventory.categories.edit', $data->id) . '" class="text-primary me-2" title="Edit">
                                    <i class="bx bxs-edit"></i>
                                </a>';

                    $buttons .= '<a href="javascript:void(0);" data-id="' . $data->id . '" class="text-warning toggle-status-btn me-2" title="Toggle Status">
                                    <i class="bx bx-' . ($data->is_active ? 'pause' : 'play') . '"></i>
                                </a>';

                    $buttons .= '<a href="javascript:void(0);" data-id="' . $data->id . '" class="text-danger delete-category-btn" title="Delete">
                                    <i class="bx bx-trash"></i>
                                </a>';

                    $buttons .= '</div>';
                    return $buttons;
                })
                ->rawColumns(['category', 'description', 'items_count', 'sort_order', 'status', 'action'])
                ->make(true);
        }
        return view('admin.inventory.categories.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.inventory.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'icon' => 'nullable|string|max:50',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0'
        ]);

        $schoolId = Auth::guard('admin')->user()->school_id ?? 1;

        $category = InventoryCategory::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'color' => $request->color,
            'icon' => $request->icon,
            'is_active' => $request->has('is_active'),
            'sort_order' => $request->sort_order ?? 0,
            'school_id' => $schoolId
        ]);

        return redirect()->route('admin.inventory.categories.index')
            ->with('success', 'Category created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(InventoryCategory $category)
    {
        $category->load('items');
        return view('admin.inventory.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InventoryCategory $category)
    {
        return view('admin.inventory.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InventoryCategory $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'icon' => 'nullable|string|max:50',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0'
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'color' => $request->color,
            'icon' => $request->icon,
            'is_active' => $request->has('is_active'),
            'sort_order' => $request->sort_order ?? 0
        ]);

        return redirect()->route('admin.inventory.categories.index')
            ->with('success', 'Category updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InventoryCategory $category)
    {
        // Check if category has items
        if ($category->items()->count() > 0) {
            return redirect()->route('admin.inventory.categories.index')
                ->with('error', 'Cannot delete category with existing items. Please move or delete items first.');
        }

        $category->delete();

        return redirect()->route('admin.inventory.categories.index')
            ->with('success', 'Category deleted successfully!');
    }

    /**
     * Toggle category status
     */
    public function toggleStatus(InventoryCategory $category)
    {
        $category->update(['is_active' => !$category->is_active]);
        
        $status = $category->is_active ? 'activated' : 'deactivated';
        return redirect()->back()
            ->with('success', "Category {$status} successfully!");
    }
}
