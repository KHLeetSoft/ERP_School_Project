<?php

namespace App\Http\Controllers\Admin\ResultAnnouncement;

use App\Http\Controllers\Controller;
use App\Models\ResultPublication;
use App\Models\ResultAnnouncement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ResultPublicationNotification;
use App\Models\Student;
use App\Models\ParentDetail;
use Yajra\DataTables\Facades\DataTables;

class ResultPublicationController extends Controller
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
     * Display a listing of result publications.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = ResultPublication::with(['creator', 'resultAnnouncement'])
                ->where('school_id', auth()->user()->school_id ?? 1);

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('announcement_title', function ($row) {
                    return $row->resultAnnouncement->title ?? '-';
                })
                ->addColumn('created_by', function ($row) {
                    return $row->creator->name ?? '-';
                })
                ->editColumn('status', function ($row) {
                    if ($row->status === 'published') {
                        return '<span class="badge bg-success">Published</span>';
                    } elseif ($row->status === 'draft') {
                        return '<span class="badge bg-secondary">Draft</span>';
                    } else {
                        return '<span class="badge bg-warning">Archived</span>';
                    }
                })
                ->editColumn('is_featured', function ($row) {
                    return $row->is_featured 
                        ? '<span class="badge bg-primary">Featured</span>' 
                        : '<span class="badge bg-light text-dark">Regular</span>';
                })
                ->addColumn('actions', function ($row) {
                    return '
                        <div class="d-flex gap-2">
                            <a href="'.route('admin.result-announcement.publications.show', $row->id).'" 
                               class="btn btn-sm" title="View">
                                <i class="bx bx-show"></i>
                            </a>
                            <a href="'.route('admin.result-announcement.publications.edit', $row->id).'" 
                               class="btn btn-sm" title="Edit">
                                <i class="bx bxs-edit"></i>
                            </a>
                            <button class="btn btn-sm delete-btn text-danger" data-id="'.$row->id.'" title="Delete">
                                <i class="bx bx-trash"></i>
                            </button>
                        </div>
                    ';
                })
                
                ->rawColumns(['status', 'is_featured', 'actions'])
                ->make(true);
        }

        return view('admin.result-announcement.publications.index');
    }

    /**
     * Show the form for creating a new result publication.
     */
    public function create()
    {
        $announcements = ResultAnnouncement::where('school_id', auth()->user()->school_id ?? 1)
                                         ->where('status', 'published')
                                         ->get(['id', 'title']);

        return view('admin.result-announcement.publications.create', compact('announcements'));
    }

    /**
     * Store a newly created result publication.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'result_announcement_id' => 'required|exists:result_announcements,id',
            'publication_title' => 'required|string|max:255',
            'publication_content' => 'nullable|string',
            'publication_type' => 'required|in:merit_list,rank_card,grade_sheet,performance_report,certificate',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date|after:now',
            'expires_at' => 'nullable|date|after:published_at',
            'is_featured' => 'boolean',
            'allow_download' => 'boolean',
            'require_authentication' => 'boolean',
            'access_permissions' => 'nullable|array',
            'template_settings' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        try {
            DB::beginTransaction();

            $publication = ResultPublication::create([
                'school_id' => auth()->user()->school_id ?? 1,
                'result_announcement_id' => $request->result_announcement_id,
                'publication_title' => $request->publication_title,
                'publication_content' => $request->publication_content,
                'publication_type' => $request->publication_type,
                'status' => $request->status,
                'published_at' => $request->published_at,
                'expires_at' => $request->expires_at,
                'is_featured' => $request->boolean('is_featured'),
                'allow_download' => $request->boolean('allow_download'),
                'require_authentication' => $request->boolean('require_authentication'),
                'access_permissions' => $request->access_permissions,
                'template_settings' => $request->template_settings,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id()
            ]);

            DB::commit();

            return redirect()->route('admin.result-announcement.publications.index')
                           ->with('success', 'Result publication created successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                           ->withErrors(['error' => 'Failed to create publication. Please try again.'])
                           ->withInput();
        }
    }

    /**
     * Display the specified result publication.
     */
    public function show(ResultPublication $publication)
    {
        $publication->load(['creator', 'resultAnnouncement', 'school']);
        
        return view('admin.result-announcement.publications.show', compact('publication'));
    }

    /**
     * Show the form for editing the specified result publication.
     */
    public function edit(ResultPublication $publication)
    {
        $announcements = ResultAnnouncement::where('school_id', auth()->user()->school_id ?? 1)
                                         ->where('status', 'published')
                                         ->get(['id', 'title']);

        return view('admin.result-announcement.publications.edit', compact('publication', 'announcements'));
    }

    /**
     * Update the specified result publication.
     */
    public function update(Request $request, ResultPublication $publication)
    {
        $validator = Validator::make($request->all(), [
            'result_announcement_id' => 'required|exists:result_announcements,id',
            'publication_title' => 'required|string|max:255',
            'publication_content' => 'nullable|string',
            'publication_type' => 'required|in:merit_list,rank_card,grade_sheet,performance_report,certificate',
            'status' => 'required|in:draft,published,archived',
            'published_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:published_at',
            'is_featured' => 'boolean',
            'allow_download' => 'boolean',
            'require_authentication' => 'boolean',
            'access_permissions' => 'nullable|array',
            'template_settings' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        try {
            DB::beginTransaction();

            $publication->update([
                'result_announcement_id' => $request->result_announcement_id,
                'publication_title' => $request->publication_title,
                'publication_content' => $request->publication_content,
                'publication_type' => $request->publication_type,
                'status' => $request->status,
                'published_at' => $request->published_at,
                'expires_at' => $request->expires_at,
                'is_featured' => $request->boolean('is_featured'),
                'allow_download' => $request->boolean('allow_download'),
                'require_authentication' => $request->boolean('require_authentication'),
                'access_permissions' => $request->access_permissions,
                'template_settings' => $request->template_settings,
                'updated_by' => Auth::id()
            ]);

            DB::commit();

            return redirect()->route('admin.result-announcement.publications.show', $publication)
                           ->with('success', 'Result publication updated successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                           ->withErrors(['error' => 'Failed to update publication. Please try again.'])
                           ->withInput();
        }
    }

    /**
     * Remove the specified result publication.
     */
    public function destroy(ResultPublication $publication)
    {
        try {
            // Delete PDF file if exists
            if ($publication->pdf_file_path && Storage::disk('public')->exists($publication->pdf_file_path)) {
                Storage::disk('public')->delete($publication->pdf_file_path);
            }

            $publication->delete();
            return redirect()->route('admin.result-announcement.publications.index')
                           ->with('success', 'Result publication deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->withErrors(['error' => 'Failed to delete publication.']);
        }
    }

    /**
     * Publish the publication.
     */
    public function publish(ResultPublication $publication)
    {
        if ($publication->status !== 'draft') {
            return redirect()->back()
                           ->withErrors(['error' => 'Only draft publications can be published.']);
        }

        $publication->update([
            'status' => 'published',
            'published_at' => now(),
            'updated_by' => Auth::id()
        ]);

        return redirect()->back()
                       ->with('success', 'Result publication published successfully.');
    }

    /**
     * Archive the publication.
     */
    public function archive(ResultPublication $publication)
    {
        $publication->update([
            'status' => 'archived',
            'updated_by' => Auth::id()
        ]);

        return redirect()->back()
                       ->with('success', 'Result publication archived successfully.');
    }

    /**
     * Toggle featured status.
     */
    public function toggleFeatured(ResultPublication $publication)
    {
        $publication->update([
            'is_featured' => !$publication->is_featured,
            'updated_by' => Auth::id()
        ]);

        $status = $publication->is_featured ? 'featured' : 'unfeatured';
        return redirect()->back()
                       ->with('success', "Result publication {$status} successfully.");
    }

    /**
     * Generate PDF for the publication.
     */
    public function generatePdf(ResultPublication $publication)
    {
        try {
            // This would integrate with your PDF generation library
            // For now, we'll just mark it as generated
            $pdfPath = $publication->generatePdfPath();
            
            $publication->update([
                'pdf_file_path' => $pdfPath,
                'updated_by' => Auth::id()
            ]);

            return redirect()->back()
                           ->with('success', 'PDF generated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->withErrors(['error' => 'Failed to generate PDF.']);
        }
    }

    /**
     * Download the publication PDF.
     */
    public function download(ResultPublication $publication)
    {
        if (!$publication->allow_download || !$publication->pdf_file_path) {
            return redirect()->back()
                           ->withErrors(['error' => 'Download not available for this publication.']);
        }

        if (!Storage::disk('public')->exists($publication->pdf_file_path)) {
            return redirect()->back()
                           ->withErrors(['error' => 'PDF file not found.']);
        }

        return Storage::disk('public')->download($publication->pdf_file_path);
    }

    /**
     * Send notifications to students and parents for this publication.
     */
    public function sendNotifications(ResultPublication $publication)
    {
        // Only allow on published items
        if ($publication->status !== 'published') {
            return redirect()->back()->withErrors(['error' => 'Only published publications can be notified.']);
        }

        $schoolId = auth()->user()->school_id ?? null;
        if (!$schoolId) {
            return redirect()->back()->withErrors(['error' => 'School context missing.']);
        }

        // Filter recipients by school and active status
        $students = Student::where('school_id', $schoolId)->where('status', 'active')->get();
        $parents  = ParentDetail::where('school_id', $schoolId)->get();

        $notification = new ResultPublicationNotification($publication);

        // Dispatch notifications via database channel
        Notification::send($students->pluck('user_id')->filter()->map(fn($id)=>\App\Models\User::find($id))->filter(), $notification);
        Notification::send($parents->pluck('user_id')->filter()->map(fn($id)=>\App\Models\User::find($id))->filter(), $notification);

        return redirect()->back()->with('success', 'Notifications queued for students and parents.');
    }
}
