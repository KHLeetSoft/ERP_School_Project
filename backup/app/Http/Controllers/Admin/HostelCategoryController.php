<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HostelCategory;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class HostelCategoryController extends Controller
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
            $adminUser = auth()->guard('admin')->user();
            $schoolId = $adminUser ? $adminUser->school_id : null;

            $query = HostelCategory::with(['school', 'createdBy', 'updatedBy'])
                ->bySchool($schoolId);

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('category_info', function ($category) {
                    return view('admin.accommodation.categories.partials.category-info', compact('category'))->render();
                })
                ->addColumn('pricing_info', function ($category) {
                    return view('admin.accommodation.categories.partials.pricing-info', compact('category'))->render();
                })
                ->addColumn('capacity_info', function ($category) {
                    return view('admin.accommodation.categories.partials.capacity-info', compact('category'))->render();
                })
                ->addColumn('status_badge', function ($category) {
                    return view('admin.accommodation.categories.partials.status-badge', compact('category'))->render();
                })
                ->addColumn('actions', function ($category) {
                    return view('admin.accommodation.categories.partials.actions', compact('category'))->render();
                })
                ->rawColumns(['category_info', 'pricing_info', 'capacity_info', 'status_badge', 'actions'])
                ->make(true);
        }

        $adminUser = auth()->guard('admin')->user();
        $schoolId = $adminUser ? $adminUser->school_id : null;

        $stats = $this->getStatistics($schoolId);

        return view('admin.accommodation.categories.index', compact('stats'));
    }

    public function create()
    {
        $adminUser = auth()->guard('admin')->user();
        $schoolId = $adminUser ? $adminUser->school_id : null;
        
        $school = School::find($schoolId);
        
        return view('admin.accommodation.categories.create', compact('school'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'monthly_fee' => 'required|numeric|min:0',
            'security_deposit' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:0',
            'facilities' => 'nullable|array',
            'facilities.*' => 'string|max:255',
            'rules' => 'nullable|array',
            'rules.*' => 'string|max:255',
            'status' => 'required|in:active,inactive,maintenance',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $adminUser = auth()->guard('admin')->user();
        $schoolId = $adminUser ? $adminUser->school_id : null;

        $data = $request->all();
        $data['school_id'] = $schoolId;
        $data['created_by'] = Auth::id();
        $data['available_rooms'] = $request->capacity;

        // Handle main image upload
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('hostel-categories', 'public');
        }

        // Handle multiple images upload
        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('hostel-categories', 'public');
            }
            $data['images'] = $images;
        }

        HostelCategory::create($data);

        return redirect()->route('admin.accommodation.categories.index')
            ->with('success', 'Hostel category created successfully.');
    }

    public function show(HostelCategory $category)
    {
        $category->load(['school', 'createdBy', 'updatedBy', 'rooms', 'allocations']);
        
        return view('admin.accommodation.categories.show', compact('category'));
    }

    public function edit(HostelCategory $category)
    {
        $adminUser = auth()->guard('admin')->user();
        $schoolId = $adminUser ? $adminUser->school_id : null;
        
        $school = School::find($schoolId);
        
        return view('admin.accommodation.categories.edit', compact('category', 'school'));
    }

    public function update(Request $request, HostelCategory $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'monthly_fee' => 'required|numeric|min:0',
            'security_deposit' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:0',
            'facilities' => 'nullable|array',
            'facilities.*' => 'string|max:255',
            'rules' => 'nullable|array',
            'rules.*' => 'string|max:255',
            'status' => 'required|in:active,inactive,maintenance',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();
        $data['updated_by'] = Auth::id();

        // Handle main image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $data['image'] = $request->file('image')->store('hostel-categories', 'public');
        }

        // Handle multiple images upload
        if ($request->hasFile('images')) {
            // Delete old images
            if ($category->images) {
                foreach ($category->images as $image) {
                    Storage::disk('public')->delete($image);
                }
            }
            $images = [];
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('hostel-categories', 'public');
            }
            $data['images'] = $images;
        }

        $category->update($data);

        return redirect()->route('admin.accommodation.categories.index')
            ->with('success', 'Hostel category updated successfully.');
    }

    public function destroy(HostelCategory $category)
    {
        // Delete images
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }
        if ($category->images) {
            foreach ($category->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $category->delete();

        return redirect()->route('admin.accommodation.categories.index')
            ->with('success', 'Hostel category deleted successfully.');
    }

    public function toggleStatus(HostelCategory $category)
    {
        $newStatus = $category->status === 'active' ? 'inactive' : 'active';
        $category->update(['status' => $newStatus]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully.',
            'new_status' => $newStatus
        ]);
    }

    public function duplicate(HostelCategory $category)
    {
        $newCategory = $category->replicate();
        $newCategory->name = $category->name . ' (Copy)';
        $newCategory->slug = Str::slug($newCategory->name);
        $newCategory->created_by = Auth::id();
        $newCategory->updated_by = null;
        $newCategory->save();

        return redirect()->route('admin.accommodation.categories.index')
            ->with('success', 'Hostel category duplicated successfully.');
    }

    public function dashboard()
    {
        $adminUser = auth()->guard('admin')->user();
        $schoolId = $adminUser ? $adminUser->school_id : null;

        $stats = $this->getStatistics($schoolId);
        $recentCategories = HostelCategory::bySchool($schoolId)
            ->with(['school', 'createdBy'])
            ->latest()
            ->limit(10)
            ->get();

        $categoriesByStatus = HostelCategory::bySchool($schoolId)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        return view('admin.accommodation.categories.dashboard', compact('stats', 'recentCategories', 'categoriesByStatus'));
    }

    public function getStatistics($schoolId)
    {
        $query = HostelCategory::bySchool($schoolId);

        $stats = [
            'total_categories' => $query->count(),
            'active_categories' => $query->active()->count(),
            'inactive_categories' => $query->inactive()->count(),
            'maintenance_categories' => $query->maintenance()->count(),
            'total_capacity' => $query->sum('capacity'),
            'total_available_rooms' => $query->sum('available_rooms'),
            'total_occupied_rooms' => $query->sum('capacity') - $query->sum('available_rooms'),
            'average_monthly_fee' => $query->avg('monthly_fee'),
            'total_monthly_revenue' => $query->sum('monthly_fee'),
            'occupancy_rate' => 0,
        ];

        if ($stats['total_capacity'] > 0) {
            $stats['occupancy_rate'] = round(($stats['total_occupied_rooms'] / $stats['total_capacity']) * 100, 2);
        }

        // Monthly data for charts
        $monthlyData = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyData[] = $query->whereMonth('created_at', $i)->count();
        }
        $stats['monthly_data'] = $monthlyData;

        return $stats;
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'category_ids' => 'required|array',
            'category_ids.*' => 'exists:hostel_categories,id',
        ]);

        $categories = HostelCategory::whereIn('id', $request->category_ids);

        switch ($request->action) {
            case 'activate':
                $categories->update(['status' => 'active']);
                $message = 'Selected categories activated successfully.';
                break;
            case 'deactivate':
                $categories->update(['status' => 'inactive']);
                $message = 'Selected categories deactivated successfully.';
                break;
            case 'delete':
                $categories->delete();
                $message = 'Selected categories deleted successfully.';
                break;
        }

        return response()->json(['success' => true, 'message' => $message]);
    }
}