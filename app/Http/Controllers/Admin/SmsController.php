<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SmsMessage;
use App\Models\SmsRecipient;
use App\Models\SmsTemplate;
use App\Models\SmsGateway;
use App\Models\Student;
use App\Models\ParentDetail;
use App\Models\Staff;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\User;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SmsController extends Controller
{
    protected $smsService;
    protected $adminUser;
    protected $schoolId;

    public function __construct(SmsService $smsService)
    {
        $this->middleware('auth:admin');
        $this->middleware(function ($request, $next) {
            $this->adminUser = auth()->guard('admin')->user();
            $this->schoolId = $this->adminUser ? $this->adminUser->school_id : null;
            return $next($request);
        });
        $this->smsService = $smsService;
    }

    /**
     * Display SMS dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Get dashboard statistics
        $stats = [
            'total_sent' => SmsMessage::sent()->count(),
            'total_delivered' => SmsMessage::delivered()->count(),
            'total_failed' => SmsMessage::failed()->count(),
            'total_scheduled' => SmsMessage::scheduled()->count(),
            'total_drafts' => SmsMessage::draft()->count(),
            'total_cost' => SmsMessage::sum('cost'),
            'today_sent' => SmsMessage::sent()->whereDate('sent_at', today())->count(),
            'today_cost' => SmsMessage::sent()->whereDate('sent_at', today())->sum('cost'),
            'monthly_sent' => SmsMessage::sent()->whereMonth('sent_at', now()->month)->count(),
            'monthly_cost' => SmsMessage::sent()->whereMonth('sent_at', now()->month)->sum('cost'),
        ];

        // Get recent messages
        $recentMessages = SmsMessage::with(['sender', 'recipients'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Get delivery statistics by date
        $deliveryStats = SmsMessage::selectRaw('DATE(created_at) as date, COUNT(*) as total, SUM(CASE WHEN status = "delivered" THEN 1 ELSE 0 END) as delivered')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Get gateway statistics
        $gatewayStats = SmsGateway::active()->get()->map(function($gateway) {
            $gateway->message_count = SmsMessage::where('gateway_id', $gateway->id)->count();
            $gateway->success_rate = SmsMessage::where('gateway_id', $gateway->id)
                ->whereIn('status', ['sent', 'delivered'])
                ->count() / max(SmsMessage::where('gateway_id', $gateway->id)->count(), 1) * 100;
            return $gateway;
        });

        return view('admin.communications.sms.dashboard', compact(
            'stats',
            'recentMessages',
            'deliveryStats',
            'gatewayStats'
        ));
    }

    /**
     * Display SMS messages list
     */
    public function index(Request $request)
    {
        $query = SmsMessage::with(['sender', 'recipients', 'template']);

        // Apply filters
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('message', 'like', '%' . $request->search . '%')
                  ->orWhereHas('sender', function($sender) use ($request) {
                      $sender->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('recipient_type')) {
            $query->where('recipient_type', $request->recipient_type);
        }

        // Apply sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $messages = $query->paginate(20);

        // Get filter options
        $statuses = ['draft', 'scheduled', 'sent', 'delivered', 'failed'];
        $priorities = ['low', 'normal', 'high', 'urgent'];
        $categories = ['notification', 'reminder', 'alert', 'marketing'];
        $recipientTypes = ['student', 'parent', 'staff', 'class', 'section', 'all'];

        return view('admin.communications.sms.index', compact(
            'messages',
            'statuses',
            'priorities',
            'categories',
            'recipientTypes'
        ));
    }

    /**
     * Show the form for creating a new SMS
     */
    public function create()
    {
        $templates = SmsTemplate::active()->get();
        $gateways = SmsGateway::active()->get();
        $classes = SchoolClass::all();
        $sections = Section::all();
        
        // Get recipient counts
        $recipientCounts = [
            'students' => Student::count(),
            'parents' => ParentDetail::count(),
            'staff' => Staff::count()
        ];

        return view('admin.communications.sms.create', compact(
            'templates',
            'gateways',
            'classes',
            'sections',
            'recipientCounts'
        ));
    }

    /**
     * Store a newly created SMS
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'recipient_type' => 'required|in:student,parent,staff,class,section,all',
            'recipient_ids' => 'required_if:recipient_type,student,parent,staff,class,section',
            'message' => 'required|string|max:1600',
            'priority' => 'required|in:low,normal,high,urgent',
            'category' => 'required|in:notification,reminder,alert,marketing',
            'scheduled_at' => 'nullable|date|after:now',
            'template_id' => 'nullable|exists:sms_templates,id',
            'gateway_id' => 'nullable|exists:sms_gateways,id',
            'requires_confirmation' => 'boolean',
            'expires_at' => 'nullable|date|after:now'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Calculate SMS count
            $smsCount = ceil(strlen($request->message) / 160);

            // Create SMS message
            $smsMessage = SmsMessage::create([
                'sender_id' => Auth::id(),
                'recipient_type' => $request->recipient_type,
                'recipient_ids' => $request->recipient_ids ?? [],
                'message' => $request->message,
                'status' => $request->scheduled_at ? 'scheduled' : 'draft',
                'priority' => $request->priority,
                'scheduled_at' => $request->scheduled_at,
                'sms_count' => $smsCount,
                'template_id' => $request->template_id,
                'gateway_id' => $request->gateway_id,
                'category' => $request->category,
                'requires_confirmation' => $request->requires_confirmation ?? false,
                'expires_at' => $request->expires_at,
                'max_retries' => 3,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id()
            ]);

            // Create recipients
            $recipients = $this->getRecipients($request->recipient_type, $request->recipient_ids);
            
            foreach ($recipients as $recipient) {
                SmsRecipient::create([
                    'sms_message_id' => $smsMessage->id,
                    'recipient_id' => $recipient['id'],
                    'recipient_type' => $recipient['type'],
                    'phone_number' => $recipient['phone'],
                    'status' => 'pending',
                    'cost' => $smsCount * config('sms.rate_per_sms', 0.01)
                ]);
            }

            // Calculate total cost
            $smsMessage->calculateCost();

            // If immediate send is requested
            if (!$request->scheduled_at && $request->send_immediately) {
                $this->smsService->sendSms($smsMessage);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'SMS created successfully',
                'data' => $smsMessage
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('SMS creation failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to create SMS: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified SMS
     */
    public function show(SmsMessage $smsMessage)
    {
        $smsMessage->load(['sender', 'recipients.recipient', 'template', 'gateway']);
        
        return view('admin.communications.sms.show', compact('smsMessage'));
    }

    /**
     * Show the form for editing the specified SMS
     */
    public function edit(SmsMessage $smsMessage)
    {
        if ($smsMessage->status !== 'draft') {
            return redirect()->route('admin.sms.index')
                ->with('error', 'Only draft messages can be edited');
        }

        $templates = SmsTemplate::active()->get();
        $gateways = SmsGateway::active()->get();
        $classes = SchoolClass::all();
        $sections = Section::all();
        
        $recipientCounts = [
            'students' => Student::count(),
            'parents' => ParentDetail::count(),
            'staff' => Staff::count()
        ];

        return view('admin.communications.sms.edit', compact(
            'smsMessage',
            'templates',
            'gateways',
            'classes',
            'sections',
            'recipientCounts'
        ));
    }

    /**
     * Update the specified SMS
     */
    public function update(Request $request, SmsMessage $smsMessage)
    {
        if ($smsMessage->status !== 'draft') {
            return response()->json([
                'success' => false,
                'message' => 'Only draft messages can be edited'
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            'recipient_type' => 'required|in:student,parent,staff,class,section,all',
            'recipient_ids' => 'required_if:recipient_type,student,parent,staff,class,section',
            'message' => 'required|string|max:1600',
            'priority' => 'required|in:low,normal,high,urgent',
            'category' => 'required|in:notification,reminder,alert,marketing',
            'scheduled_at' => 'nullable|date|after:now',
            'template_id' => 'nullable|exists:sms_templates,id',
            'gateway_id' => 'nullable|exists:sms_gateways,id',
            'requires_confirmation' => 'boolean',
            'expires_at' => 'nullable|date|after:now'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Update SMS message
            $smsMessage->update([
                'recipient_type' => $request->recipient_type,
                'recipient_ids' => $request->recipient_ids ?? [],
                'message' => $request->message,
                'priority' => $request->priority,
                'scheduled_at' => $request->scheduled_at,
                'sms_count' => ceil(strlen($request->message) / 160),
                'template_id' => $request->template_id,
                'gateway_id' => $request->gateway_id,
                'category' => $request->category,
                'requires_confirmation' => $request->requires_confirmation ?? false,
                'expires_at' => $request->expires_at,
                'updated_by' => Auth::id()
            ]);

            // Remove existing recipients
            $smsMessage->recipients()->delete();

            // Create new recipients
            $recipients = $this->getRecipients($request->recipient_type, $request->recipient_ids);
            
            foreach ($recipients as $recipient) {
                SmsRecipient::create([
                    'sms_message_id' => $smsMessage->id,
                    'recipient_id' => $recipient['id'],
                    'recipient_type' => $recipient['type'],
                    'phone_number' => $recipient['phone'],
                    'status' => 'pending',
                    'cost' => $smsMessage->sms_count * config('sms.rate_per_sms', 0.01)
                ]);
            }

            // Recalculate cost
            $smsMessage->calculateCost();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'SMS updated successfully',
                'data' => $smsMessage
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('SMS update failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to update SMS: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified SMS
     */
    public function destroy(SmsMessage $smsMessage)
    {
        try {
            if ($smsMessage->status === 'sent') {
                return response()->json([
                    'success' => false,
                    'message' => 'Sent messages cannot be deleted'
                ], 422);
            }

            $smsMessage->delete();

            return response()->json([
                'success' => true,
                'message' => 'SMS deleted successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('SMS deletion failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete SMS: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send SMS immediately
     */
    public function sendNow(SmsMessage $smsMessage)
    {
        try {
            if ($smsMessage->status !== 'draft') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only draft messages can be sent'
                ], 422);
            }

            $result = $this->smsService->sendSms($smsMessage);

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => 'SMS sent successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send SMS: ' . $result['message']
                ], 500);
            }

        } catch (\Exception $e) {
            Log::error('SMS send failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to send SMS: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Retry failed SMS
     */
    public function retry(SmsMessage $smsMessage)
    {
        try {
            if (!$smsMessage->canRetry) {
                return response()->json([
                    'success' => false,
                    'message' => 'SMS cannot be retried'
                ], 422);
            }

            $result = $this->smsService->sendSms($smsMessage);

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => 'SMS retry successful'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'SMS retry failed: ' . $result['message']
                ], 500);
            }

        } catch (\Exception $e) {
            Log::error('SMS retry failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'SMS retry failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get recipients based on type and IDs
     */
    private function getRecipients($type, $ids)
    {
        $recipients = [];

        switch ($type) {
            case 'student':
                $students = Student::whereIn('id', $ids)->get();
                foreach ($students as $student) {
                    if ($student->phone_number) {
                        $recipients[] = [
                            'id' => $student->id,
                            'type' => 'student',
                            'phone' => $student->phone_number
                        ];
                    }
                }
                break;

            case 'parent':
                $parents = ParentDetail::whereIn('id', $ids)->get();
                foreach ($parents as $parent) {
                    if ($parent->phone_number) {
                        $recipients[] = [
                            'id' => $parent->id,
                            'type' => 'parent',
                            'phone' => $parent->phone_number
                        ];
                    }
                }
                break;

            case 'staff':
                $staff = Staff::whereIn('id', $ids)->get();
                foreach ($staff as $member) {
                    if ($member->phone_number) {
                        $recipients[] = [
                            'id' => $member->id,
                            'type' => 'staff',
                            'phone' => $member->phone_number
                        ];
                    }
                }
                break;

            case 'class':
                $students = Student::whereIn('school_class_id', $ids)->get();
                foreach ($students as $student) {
                    if ($student->phone_number) {
                        $recipients[] = [
                            'id' => $student->id,
                            'type' => 'student',
                            'phone' => $student->phone_number
                        ];
                    }
                }
                break;

            case 'section':
                $students = Student::whereIn('section_id', $ids)->get();
                foreach ($students as $student) {
                    if ($student->phone_number) {
                        $recipients[] = [
                            'id' => $student->id,
                            'type' => 'student',
                            'phone' => $student->phone_number
                        ];
                    }
                }
                break;

            case 'all':
                // Get all students, parents, and staff with phone numbers
                $students = Student::whereNotNull('phone_number')->get();
                $parents = ParentDetail::whereNotNull('phone_number')->get();
                $staff = Staff::whereNotNull('phone_number')->get();

                foreach ($students as $student) {
                    $recipients[] = [
                        'id' => $student->id,
                        'type' => 'student',
                        'phone' => $student->phone_number
                    ];
                }

                foreach ($parents as $parent) {
                    $recipients[] = [
                        'id' => $parent->id,
                        'type' => 'parent',
                        'phone' => $parent->phone_number
                    ];
                }

                foreach ($staff as $member) {
                    $recipients[] = [
                        'id' => $member->id,
                        'type' => 'staff',
                        'phone' => $member->phone_number
                    ];
                }
                break;
        }

        return $recipients;
    }

    /**
     * Get recipient suggestions for autocomplete
     */
    public function getRecipientSuggestions(Request $request)
    {
        $type = $request->get('type');
        $search = $request->get('search');

        $suggestions = [];

        switch ($type) {
            case 'student':
                $students = Student::where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('admission_number', 'like', "%{$search}%")
                    ->limit(10)
                    ->get();

                foreach ($students as $student) {
                    $suggestions[] = [
                        'id' => $student->id,
                        'text' => $student->first_name . ' ' . $student->last_name . ' (' . $student->admission_number . ')',
                        'phone' => $student->phone_number
                    ];
                }
                break;

            case 'parent':
                $parents = ParentDetail::where('father_name', 'like', "%{$search}%")
                    ->orWhere('mother_name', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%")
                    ->limit(10)
                    ->get();

                foreach ($parents as $parent) {
                    $suggestions[] = [
                        'id' => $parent->id,
                        'text' => ($parent->father_name ?? $parent->mother_name) . ' (' . $parent->phone_number . ')',
                        'phone' => $parent->phone_number
                    ];
                }
                break;

            case 'staff':
                $staff = Staff::where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('employee_id', 'like', "%{$search}%")
                    ->limit(10)
                    ->get();

                foreach ($staff as $member) {
                    $suggestions[] = [
                        'id' => $member->id,
                        'text' => $member->first_name . ' ' . $member->last_name . ' (' . $member->employee_id . ')',
                        'phone' => $member->phone_number
                    ];
                }
                break;

            case 'class':
                $classes = SchoolClass::where('name', 'like', "%{$search}%")
                    ->orWhere('class_name', 'like', "%{$search}%")
                    ->limit(10)
                    ->get();

                foreach ($classes as $class) {
                    $suggestions[] = [
                        'id' => $class->id,
                        'text' => $class->name ?? $class->class_name . ' (Class)',
                        'phone' => null
                    ];
                }
                break;

            case 'section':
                $sections = Section::where('name', 'like', "%{$search}%")
                    ->orWhere('section_name', 'like', "%{$search}%")
                    ->limit(10)
                    ->get();

                foreach ($sections as $section) {
                    $suggestions[] = [
                        'id' => $section->id,
                        'text' => $section->name ?? $section->section_name . ' (Section)',
                        'phone' => null
                    ];
                }
                break;
        }

        return response()->json($suggestions);
    }

    /**
     * Get SMS statistics
     */
    public function getStatistics(Request $request)
    {
        $period = $request->get('period', 'month');
        
        switch ($period) {
            case 'week':
                $startDate = now()->startOfWeek();
                $endDate = now()->endOfWeek();
                break;
            case 'month':
                $startDate = now()->startOfMonth();
                $endDate = now()->endOfMonth();
                break;
            case 'year':
                $startDate = now()->startOfYear();
                $endDate = now()->endOfYear();
                break;
            default:
                $startDate = now()->subDays(30);
                $endDate = now();
        }

        $stats = SmsMessage::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('
                DATE(created_at) as date,
                COUNT(*) as total,
                SUM(CASE WHEN status = "sent" THEN 1 ELSE 0 END) as sent,
                SUM(CASE WHEN status = "delivered" THEN 1 ELSE 0 END) as delivered,
                SUM(CASE WHEN status = "failed" THEN 1 ELSE 0 END) as failed,
                SUM(cost) as total_cost
            ')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json($stats);
    }

    /**
     * Get template content by ID
     */
    public function getTemplate($id)
    {
        try {
            $template = SmsTemplate::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'template' => [
                    'id' => $template->id,
                    'name' => $template->name,
                    'content' => $template->content,
                    'variables' => $template->variables,
                    'category' => $template->category
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Template not found'
            ], 404);
        }
    }
}
