<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class EmailTemplateController extends Controller
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
     * Display a listing of email templates for the current school
     */
    public function index(Request $request)
    {
        $schoolId = Auth::user()->school_id;
        
        $query = EmailTemplate::bySchool($schoolId);

        // Apply filters
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('subject', 'like', "%{$request->search}%")
                  ->orWhere('content', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Apply sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $templates = $query->paginate(15);

        // Get filter options
        $categories = ['notification', 'reminder', 'alert', 'marketing', 'welcome', 'general'];
        $statuses = ['active', 'inactive'];

        return view('admin.communications.email-templates.index', compact(
            'templates',
            'categories',
            'statuses'
        ));
    }

    /**
     * Show the form for creating a new email template
     */
    public function create()
    {
        $categories = ['notification', 'reminder', 'alert', 'marketing', 'welcome', 'general'];
        $variables = $this->getAvailableVariables();
        
        return view('admin.communications.email-templates.create', compact('categories', 'variables'));
    }

    /**
     * Store a newly created email template
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'content' => 'required|string|max:10000',
            'variables' => 'nullable|array',
            'variables.*' => 'string|max:50',
            'category' => 'required|in:notification,reminder,alert,marketing,welcome,general',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $template = EmailTemplate::create([
                'school_id' => Auth::user()->school_id,
                'name' => $request->name,
                'subject' => $request->subject,
                'content' => $request->content,
                'variables' => $request->variables ?? [],
                'category' => $request->category,
                'is_active' => $request->boolean('is_active', true),
                'created_by' => Auth::id(),
                'updated_by' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Email template created successfully',
                'template' => $template
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to create email template: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create email template'
            ], 500);
        }
    }

    /**
     * Display the specified email template
     */
    public function show($id)
    {
        $schoolId = Auth::user()->school_id;
        $template = EmailTemplate::bySchool($schoolId)->findOrFail($id);
        
        return view('admin.communications.email-templates.show', compact('template'));
    }

    /**
     * Show the form for editing the specified email template
     */
    public function edit($id)
    {
        $schoolId = Auth::user()->school_id;
        $template = EmailTemplate::bySchool($schoolId)->findOrFail($id);
        $categories = ['notification', 'reminder', 'alert', 'marketing', 'welcome', 'general'];
        $variables = $this->getAvailableVariables();
        
        return view('admin.communications.email-templates.edit', compact('template', 'categories', 'variables'));
    }

    /**
     * Update the specified email template
     */
    public function update(Request $request, $id)
    {
        $schoolId = Auth::user()->school_id;
        $template = EmailTemplate::bySchool($schoolId)->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'content' => 'required|string|max:10000',
            'variables' => 'nullable|array',
            'variables.*' => 'string|max:50',
            'category' => 'required|in:notification,reminder,alert,marketing,welcome,general',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $template->update([
                'name' => $request->name,
                'subject' => $request->subject,
                'content' => $request->content,
                'variables' => $request->variables ?? [],
                'category' => $request->category,
                'is_active' => $request->boolean('is_active', true),
                'updated_by' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Email template updated successfully',
                'template' => $template
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to update email template: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update email template'
            ], 500);
        }
    }

    /**
     * Remove the specified email template
     */
    public function destroy($id)
    {
        $schoolId = Auth::user()->school_id;
        $template = EmailTemplate::bySchool($schoolId)->findOrFail($id);

        try {
            $template->delete();

            return response()->json([
                'success' => true,
                'message' => 'Email template deleted successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to delete email template: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete email template'
            ], 500);
        }
    }

    /**
     * Toggle template active status
     */
    public function toggleStatus($id)
    {
        $schoolId = Auth::user()->school_id;
        $template = EmailTemplate::bySchool($schoolId)->findOrFail($id);

        try {
            $template->update([
                'is_active' => !$template->is_active,
                'updated_by' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Template status updated successfully',
                'is_active' => $template->is_active
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to toggle template status: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update template status'
            ], 500);
        }
    }

    /**
     * Duplicate template
     */
    public function duplicate($id)
    {
        $schoolId = Auth::user()->school_id;
        $template = EmailTemplate::bySchool($schoolId)->findOrFail($id);

        try {
            $newTemplate = $template->replicate();
            $newTemplate->name = $template->name . ' (Copy)';
            $newTemplate->is_active = false;
            $newTemplate->created_by = Auth::id();
            $newTemplate->updated_by = Auth::id();
            $newTemplate->save();

            return response()->json([
                'success' => true,
                'message' => 'Template duplicated successfully',
                'template' => $newTemplate
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to duplicate template: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to duplicate template'
            ], 500);
        }
    }

    /**
     * Get template content for preview
     */
    public function preview($id)
    {
        $schoolId = Auth::user()->school_id;
        $template = EmailTemplate::bySchool($schoolId)->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'template' => [
                'id' => $template->id,
                'name' => $template->name,
                'subject' => $template->subject,
                'content' => $template->content,
                'variables' => $template->variables,
                'category' => $template->category
            ]
        ]);
    }

    /**
     * Get available variables for templates
     */
    private function getAvailableVariables()
    {
        return [
            'student' => [
                '{{student_name}}' => 'Student Full Name',
                '{{student_id}}' => 'Student ID',
                '{{student_class}}' => 'Student Class',
                '{{student_section}}' => 'Student Section',
                '{{student_roll_no}}' => 'Student Roll Number'
            ],
            'parent' => [
                '{{parent_name}}' => 'Parent Full Name',
                '{{parent_phone}}' => 'Parent Phone Number',
                '{{parent_email}}' => 'Parent Email'
            ],
            'staff' => [
                '{{staff_name}}' => 'Staff Full Name',
                '{{staff_designation}}' => 'Staff Designation',
                '{{staff_department}}' => 'Staff Department'
            ],
            'school' => [
                '{{school_name}}' => 'School Name',
                '{{school_address}}' => 'School Address',
                '{{school_phone}}' => 'School Phone',
                '{{school_email}}' => 'School Email'
            ],
            'general' => [
                '{{current_date}}' => 'Current Date',
                '{{current_time}}' => 'Current Time',
                '{{admin_name}}' => 'Admin Name',
                '{{year}}' => 'Current Year'
            ]
        ];
    }
}
