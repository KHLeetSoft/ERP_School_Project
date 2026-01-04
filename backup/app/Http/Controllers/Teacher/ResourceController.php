<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use App\Models\ResourceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ResourceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!Auth::check()) {
                return redirect()->route('teacher.login')->with('error', 'Please login to access this page.');
            }

            $user = Auth::user();
            if (!$user->userRole || $user->userRole->name !== 'Teacher') {
                Auth::logout();
                return redirect()->route('teacher.login')->with('error', 'Access denied. Teacher role required.');
            }

            if (!$user->status) {
                Auth::logout();
                return redirect()->route('teacher.login')->with('error', 'Your account is inactive. Please contact administrator.');
            }

            return $next($request);
        });
    }

    /**
     * Display a listing of resources.
     */
    public function index(Request $request)
    {
        $teacherId = Auth::id();
        $schoolId = Auth::user()->school_id;

        $query = Resource::with(['category', 'teacher'])
            ->where('teacher_id', $teacherId);

        // Apply filters
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('visibility')) {
            $query->where('visibility', $request->visibility);
        }

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $resources = $query->orderBy('created_at', 'desc')->paginate(20);

        $categories = ResourceCategory::where('school_id', $schoolId)->active()->ordered()->get();
        
        $resourceStats = [
            'total_resources' => Resource::where('teacher_id', $teacherId)->count(),
            'published_resources' => Resource::where('teacher_id', $teacherId)->published()->count(),
            'draft_resources' => Resource::where('teacher_id', $teacherId)->draft()->count(),
            'archived_resources' => Resource::where('teacher_id', $teacherId)->archived()->count(),
            'total_downloads' => Resource::where('teacher_id', $teacherId)->sum('download_count'),
            'total_views' => Resource::where('teacher_id', $teacherId)->sum('view_count'),
        ];

        return view('teacher.resources.index', compact('resources', 'categories', 'resourceStats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $schoolId = Auth::user()->school_id;
        $categories = ResourceCategory::where('school_id', $schoolId)->active()->ordered()->get();

        return view('teacher.resources.create', compact('categories'));
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:resource_categories,id',
            'description' => 'nullable|string',
            'type' => 'required|in:file,link,text,video,image,document,presentation,worksheet,quiz,other',
            'file' => 'nullable|file|max:10240', // 10MB max
            'external_url' => 'nullable|url',
            'content' => 'nullable|string',
            'visibility' => 'required|in:private,public,shared',
            'is_featured' => 'boolean',
            'is_pinned' => 'boolean',
            'status' => 'required|in:draft,published,archived',
        ]);

        $data = $request->all();
        $data['teacher_id'] = Auth::id();
        $data['school_id'] = Auth::user()->school_id;
        $data['slug'] = Str::slug($request->title);

        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('resources', $filename, 'public');
            
            $data['file_path'] = $path;
            $data['file_name'] = $file->getClientOriginalName();
            $data['file_size'] = $file->getSize();
            $data['file_extension'] = $file->getClientOriginalExtension();
        }

        // Set published_at if status is published
        if ($request->status === 'published') {
            $data['published_at'] = now();
        }

        $resource = Resource::create($data);

        return redirect()->route('teacher.resources.index')
            ->with('success', 'Resource created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Resource $resource)
    {
        // Increment view count
        $resource->incrementViewCount();

        return view('teacher.resources.show', compact('resource'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Resource $resource)
    {
        $schoolId = Auth::user()->school_id;
        $categories = ResourceCategory::where('school_id', $schoolId)->active()->ordered()->get();

        return view('teacher.resources.edit', compact('resource', 'categories'));
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, Resource $resource)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:resource_categories,id',
            'description' => 'nullable|string',
            'type' => 'required|in:file,link,text,video,image,document,presentation,worksheet,quiz,other',
            'file' => 'nullable|file|max:10240',
            'external_url' => 'nullable|url',
            'content' => 'nullable|string',
            'visibility' => 'required|in:private,public,shared',
            'is_featured' => 'boolean',
            'is_pinned' => 'boolean',
            'status' => 'required|in:draft,published,archived',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->title);

        // Handle file upload
        if ($request->hasFile('file')) {
            // Delete old file
            $resource->deleteFile();
            
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('resources', $filename, 'public');
            
            $data['file_path'] = $path;
            $data['file_name'] = $file->getClientOriginalName();
            $data['file_size'] = $file->getSize();
            $data['file_extension'] = $file->getClientOriginalExtension();
        }

        // Set published_at if status is published
        if ($request->status === 'published' && $resource->status !== 'published') {
            $data['published_at'] = now();
        }

        $resource->update($data);

        return redirect()->route('teacher.resources.index')
            ->with('success', 'Resource updated successfully!');
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Resource $resource)
    {
        $resource->deleteFile();
        $resource->delete();

        return redirect()->route('teacher.resources.index')
            ->with('success', 'Resource deleted successfully!');
    }

    /**
     * Download the resource file.
     */
    public function download(Resource $resource)
    {
        if (!$resource->file_path || !Storage::exists($resource->file_path)) {
            return redirect()->back()->with('error', 'File not found.');
        }

        $resource->incrementDownloadCount();

        return Storage::download($resource->file_path, $resource->file_name);
    }

    /**
     * Toggle featured status.
     */
    public function toggleFeatured(Resource $resource)
    {
        $resource->update(['is_featured' => !$resource->is_featured]);

        return redirect()->back()
            ->with('success', 'Featured status updated successfully!');
    }

    /**
     * Toggle pinned status.
     */
    public function togglePinned(Resource $resource)
    {
        $resource->update(['is_pinned' => !$resource->is_pinned]);

        return redirect()->back()
            ->with('success', 'Pinned status updated successfully!');
    }

    /**
     * Publish resource.
     */
    public function publish(Resource $resource)
    {
        $resource->publish();

        return redirect()->back()
            ->with('success', 'Resource published successfully!');
    }

    /**
     * Unpublish resource.
     */
    public function unpublish(Resource $resource)
    {
        $resource->unpublish();

        return redirect()->back()
            ->with('success', 'Resource unpublished successfully!');
    }

    /**
     * Archive resource.
     */
    public function archive(Resource $resource)
    {
        $resource->archive();

        return redirect()->back()
            ->with('success', 'Resource archived successfully!');
    }

    /**
     * Display public resources.
     */
    public function public()
    {
        $schoolId = Auth::user()->school_id;
        
        $resources = Resource::with(['category', 'teacher'])
            ->where('school_id', $schoolId)
            ->public()
            ->published()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $categories = ResourceCategory::where('school_id', $schoolId)->active()->ordered()->get();

        return view('teacher.resources.public', compact('resources', 'categories'));
    }

    /**
     * Display featured resources.
     */
    public function featured()
    {
        $schoolId = Auth::user()->school_id;
        
        $resources = Resource::with(['category', 'teacher'])
            ->where('school_id', $schoolId)
            ->featured()
            ->published()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('teacher.resources.featured', compact('resources'));
    }

    /**
     * Display resources by category.
     */
    public function byCategory(ResourceCategory $category)
    {
        $schoolId = Auth::user()->school_id;
        
        $resources = Resource::with(['category', 'teacher'])
            ->where('school_id', $schoolId)
            ->where('category_id', $category->id)
            ->published()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $categories = ResourceCategory::where('school_id', $schoolId)->active()->ordered()->get();

        return view('teacher.resources.by-category', compact('resources', 'category', 'categories'));
    }

    /**
     * Search resources.
     */
    public function search(Request $request)
    {
        $schoolId = Auth::user()->school_id;
        $search = $request->get('q');
        
        $resources = Resource::with(['category', 'teacher'])
            ->where('school_id', $schoolId)
            ->published()
            ->search($search)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $categories = ResourceCategory::where('school_id', $schoolId)->active()->ordered()->get();

        return view('teacher.resources.search', compact('resources', 'categories', 'search'));
    }

    /**
     * Get resource statistics.
     */
    public function statistics()
    {
        $teacherId = Auth::id();
        $schoolId = Auth::user()->school_id;

        $stats = [
            'total_resources' => Resource::where('teacher_id', $teacherId)->count(),
            'published_resources' => Resource::where('teacher_id', $teacherId)->published()->count(),
            'draft_resources' => Resource::where('teacher_id', $teacherId)->draft()->count(),
            'archived_resources' => Resource::where('teacher_id', $teacherId)->archived()->count(),
            'total_downloads' => Resource::where('teacher_id', $teacherId)->sum('download_count'),
            'total_views' => Resource::where('teacher_id', $teacherId)->sum('view_count'),
            'featured_resources' => Resource::where('teacher_id', $teacherId)->featured()->count(),
            'pinned_resources' => Resource::where('teacher_id', $teacherId)->pinned()->count(),
        ];

        $categoryStats = Resource::where('teacher_id', $teacherId)
            ->selectRaw('category_id, COUNT(*) as count')
            ->with('category')
            ->groupBy('category_id')
            ->get();

        $typeStats = Resource::where('teacher_id', $teacherId)
            ->selectRaw('type, COUNT(*) as count')
            ->groupBy('type')
            ->get();

        $monthlyStats = Resource::where('teacher_id', $teacherId)
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('teacher.resources.statistics', compact('stats', 'categoryStats', 'typeStats', 'monthlyStats'));
    }
}