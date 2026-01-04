<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StudentNotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        
        // Check if user has student role
        if (!$user->userRole || $user->userRole->name !== 'Student') {
            Auth::logout();
            return redirect()->route('student.login')->with('error', 'Access denied. Student role required.');
        }
        
        $student = $user->student;
        
        // Share student data with all views
        view()->share('student', $student);
        view()->share('studentUser', $user);

        // Get notification information for the student
        $recentNotifications = $this->getRecentNotifications($student);
        $unreadCount = $this->getUnreadCount($student);
        $notificationStats = $this->getNotificationStats($student);
        $importantNotifications = $this->getImportantNotifications($student);
        $upcomingEvents = $this->getUpcomingEvents($student);

        return view('student.notifications.index', compact(
            'recentNotifications',
            'unreadCount',
            'notificationStats',
            'importantNotifications',
            'upcomingEvents'
        ));
    }

    public function all(Request $request)
    {
        $user = Auth::user();
        
        // Check if user has student role
        if (!$user->userRole || $user->userRole->name !== 'Student') {
            Auth::logout();
            return redirect()->route('student.login')->with('error', 'Access denied. Student role required.');
        }
        
        $student = $user->student;
        
        // Share student data with all views
        view()->share('student', $student);
        view()->share('studentUser', $user);

        // Get filter parameters
        $type = $request->get('type', 'all');
        $status = $request->get('status', 'all');
        $priority = $request->get('priority', 'all');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        // Get all notifications based on filters
        $notifications = $this->getAllNotifications($student, $type, $status, $priority, $dateFrom, $dateTo);
        $notificationTypes = $this->getNotificationTypes();
        $priorities = $this->getPriorities();

        return view('student.notifications.all', compact(
            'notifications',
            'notificationTypes',
            'priorities',
            'type',
            'status',
            'priority',
            'dateFrom',
            'dateTo'
        ));
    }

    public function show($id)
    {
        $user = Auth::user();
        
        // Check if user has student role
        if (!$user->userRole || $user->userRole->name !== 'Student') {
            Auth::logout();
            return redirect()->route('student.login')->with('error', 'Access denied. Student role required.');
        }
        
        $student = $user->student;
        
        // Share student data with all views
        view()->share('student', $student);
        view()->share('studentUser', $user);

        // Get notification details
        $notification = $this->getNotificationDetails($id);
        
        if (!$notification) {
            return redirect()->route('student.notifications.index')->with('error', 'Notification not found.');
        }

        // Mark as read
        $this->markAsRead($id);

        return view('student.notifications.show', compact('notification'));
    }

    public function markAsRead($id)
    {
        $user = Auth::user();
        
        // Check if user has student role
        if (!$user->userRole || $user->userRole->name !== 'Student') {
            Auth::logout();
            return redirect()->route('student.login')->with('error', 'Access denied. Student role required.');
        }
        
        $student = $user->student;
        
        // Share student data with all views
        view()->share('student', $student);
        view()->share('studentUser', $user);

        // Mark notification as read
        $this->updateNotificationStatus($id, 'read');

        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        $user = Auth::user();
        
        // Check if user has student role
        if (!$user->userRole || $user->userRole->name !== 'Student') {
            Auth::logout();
            return redirect()->route('student.login')->with('error', 'Access denied. Student role required.');
        }
        
        $student = $user->student;
        
        // Share student data with all views
        view()->share('student', $student);
        view()->share('studentUser', $user);

        // Mark all notifications as read
        $this->markAllNotificationsAsRead($student);

        return response()->json(['success' => true]);
    }

    public function delete($id)
    {
        $user = Auth::user();
        
        // Check if user has student role
        if (!$user->userRole || $user->userRole->name !== 'Student') {
            Auth::logout();
            return redirect()->route('student.login')->with('error', 'Access denied. Student role required.');
        }
        
        $student = $user->student;
        
        // Share student data with all views
        view()->share('student', $student);
        view()->share('studentUser', $user);

        // Delete notification
        $this->deleteNotification($id);

        return response()->json(['success' => true]);
    }

    public function settings()
    {
        $user = Auth::user();
        
        // Check if user has student role
        if (!$user->userRole || $user->userRole->name !== 'Student') {
            Auth::logout();
            return redirect()->route('student.login')->with('error', 'Access denied. Student role required.');
        }
        
        $student = $user->student;
        
        // Share student data with all views
        view()->share('student', $student);
        view()->share('studentUser', $user);

        // Get notification settings
        $settings = $this->getNotificationSettings($student);
        $notificationTypes = $this->getNotificationTypes();
        $deliveryMethods = $this->getDeliveryMethods();

        return view('student.notifications.settings', compact(
            'settings',
            'notificationTypes',
            'deliveryMethods'
        ));
    }

    public function updateSettings(Request $request)
    {
        $user = Auth::user();
        
        // Check if user has student role
        if (!$user->userRole || $user->userRole->name !== 'Student') {
            Auth::logout();
            return redirect()->route('student.login')->with('error', 'Access denied. Student role required.');
        }
        
        $student = $user->student;
        
        // Share student data with all views
        view()->share('student', $student);
        view()->share('studentUser', $user);

        $request->validate([
            'email_notifications' => 'boolean',
            'sms_notifications' => 'boolean',
            'push_notifications' => 'boolean',
            'notification_types' => 'array',
            'quiet_hours_start' => 'nullable|date_format:H:i',
            'quiet_hours_end' => 'nullable|date_format:H:i',
        ]);

        // Update notification settings
        $this->updateNotificationSettings($student, $request->all());

        return redirect()->route('student.notifications.settings')
            ->with('success', 'Notification settings updated successfully!');
    }

    public function compose()
    {
        $user = Auth::user();
        
        // Check if user has student role
        if (!$user->userRole || $user->userRole->name !== 'Student') {
            Auth::logout();
            return redirect()->route('student.login')->with('error', 'Access denied. Student role required.');
        }
        
        $student = $user->student;
        
        // Share student data with all views
        view()->share('student', $student);
        view()->share('studentUser', $user);

        // Get compose form data
        $recipients = $this->getRecipients();
        $notificationTypes = $this->getNotificationTypes();
        $templates = $this->getTemplates();

        return view('student.notifications.compose', compact(
            'recipients',
            'notificationTypes',
            'templates'
        ));
    }

    public function sendNotification(Request $request)
    {
        $user = Auth::user();
        
        // Check if user has student role
        if (!$user->userRole || $user->userRole->name !== 'Student') {
            Auth::logout();
            return redirect()->route('student.login')->with('error', 'Access denied. Student role required.');
        }
        
        $student = $user->student;
        
        // Share student data with all views
        view()->share('student', $student);
        view()->share('studentUser', $user);

        $request->validate([
            'recipients' => 'required|array|min:1',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
            'type' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'schedule' => 'nullable|date|after:now',
        ]);

        // Send notification
        $notification = $this->createNotification($student, $request->all());

        return redirect()->route('student.notifications.index')
            ->with('success', 'Notification sent successfully!');
    }

    private function getRecentNotifications($student)
    {
        // Mock data - replace with actual database queries
        return [
            [
                'id' => 'NOT-001',
                'title' => 'Assignment Due Tomorrow',
                'message' => 'Your Computer Science assignment is due tomorrow at 11:59 PM.',
                'type' => 'assignment',
                'priority' => 'high',
                'status' => 'unread',
                'created_at' => Carbon::now()->subHours(2)->format('Y-m-d H:i:s'),
                'sender' => 'Dr. Smith',
                'sender_type' => 'instructor',
            ],
            [
                'id' => 'NOT-002',
                'title' => 'Library Book Due Soon',
                'message' => 'Your borrowed book "Introduction to Algorithms" is due in 2 days.',
                'type' => 'library',
                'priority' => 'medium',
                'status' => 'unread',
                'created_at' => Carbon::now()->subHours(5)->format('Y-m-d H:i:s'),
                'sender' => 'Library System',
                'sender_type' => 'system',
            ],
            [
                'id' => 'NOT-003',
                'title' => 'Hostel Maintenance Notice',
                'message' => 'Scheduled maintenance in your hostel block tomorrow from 9 AM to 12 PM.',
                'type' => 'hostel',
                'priority' => 'low',
                'status' => 'read',
                'created_at' => Carbon::now()->subDays(1)->format('Y-m-d H:i:s'),
                'sender' => 'Hostel Management',
                'sender_type' => 'admin',
            ],
        ];
    }

    private function getUnreadCount($student)
    {
        // Mock data - replace with actual database queries
        return 2;
    }

    private function getNotificationStats($student)
    {
        // Mock data - replace with actual database queries
        return [
            'total_notifications' => 25,
            'unread_notifications' => 2,
            'read_notifications' => 23,
            'important_notifications' => 5,
            'this_week' => 8,
            'this_month' => 25,
        ];
    }

    private function getImportantNotifications($student)
    {
        // Mock data - replace with actual database queries
        return [
            [
                'id' => 'NOT-004',
                'title' => 'Exam Schedule Released',
                'message' => 'Final exam schedule has been released. Check your exam dates and times.',
                'type' => 'exam',
                'priority' => 'urgent',
                'created_at' => Carbon::now()->subHours(1)->format('Y-m-d H:i:s'),
            ],
            [
                'id' => 'NOT-005',
                'title' => 'Fee Payment Reminder',
                'message' => 'Your monthly fee payment is due in 3 days. Please make payment to avoid late fees.',
                'type' => 'fees',
                'priority' => 'high',
                'created_at' => Carbon::now()->subHours(3)->format('Y-m-d H:i:s'),
            ],
        ];
    }

    private function getUpcomingEvents($student)
    {
        // Mock data - replace with actual database queries
        return [
            [
                'title' => 'Midterm Exam - Mathematics',
                'date' => Carbon::tomorrow()->format('Y-m-d'),
                'time' => '10:00 AM',
                'location' => 'Room 101',
                'type' => 'exam',
            ],
            [
                'title' => 'Library Book Due',
                'date' => Carbon::now()->addDays(2)->format('Y-m-d'),
                'time' => '11:59 PM',
                'location' => 'Library',
                'type' => 'library',
            ],
            [
                'title' => 'Assignment Submission',
                'date' => Carbon::now()->addDays(3)->format('Y-m-d'),
                'time' => '11:59 PM',
                'location' => 'Online Portal',
                'type' => 'assignment',
            ],
        ];
    }

    private function getAllNotifications($student, $type, $status, $priority, $dateFrom, $dateTo)
    {
        // Mock data - replace with actual database queries
        $notifications = [
            [
                'id' => 'NOT-001',
                'title' => 'Assignment Due Tomorrow',
                'message' => 'Your Computer Science assignment is due tomorrow at 11:59 PM.',
                'type' => 'assignment',
                'priority' => 'high',
                'status' => 'unread',
                'created_at' => Carbon::now()->subHours(2)->format('Y-m-d H:i:s'),
                'sender' => 'Dr. Smith',
                'sender_type' => 'instructor',
            ],
            [
                'id' => 'NOT-002',
                'title' => 'Library Book Due Soon',
                'message' => 'Your borrowed book "Introduction to Algorithms" is due in 2 days.',
                'type' => 'library',
                'priority' => 'medium',
                'status' => 'unread',
                'created_at' => Carbon::now()->subHours(5)->format('Y-m-d H:i:s'),
                'sender' => 'Library System',
                'sender_type' => 'system',
            ],
            [
                'id' => 'NOT-003',
                'title' => 'Hostel Maintenance Notice',
                'message' => 'Scheduled maintenance in your hostel block tomorrow from 9 AM to 12 PM.',
                'type' => 'hostel',
                'priority' => 'low',
                'status' => 'read',
                'created_at' => Carbon::now()->subDays(1)->format('Y-m-d H:i:s'),
                'sender' => 'Hostel Management',
                'sender_type' => 'admin',
            ],
            [
                'id' => 'NOT-004',
                'title' => 'Exam Schedule Released',
                'message' => 'Final exam schedule has been released. Check your exam dates and times.',
                'type' => 'exam',
                'priority' => 'urgent',
                'status' => 'read',
                'created_at' => Carbon::now()->subDays(2)->format('Y-m-d H:i:s'),
                'sender' => 'Examination Office',
                'sender_type' => 'admin',
            ],
            [
                'id' => 'NOT-005',
                'title' => 'Fee Payment Reminder',
                'message' => 'Your monthly fee payment is due in 3 days. Please make payment to avoid late fees.',
                'type' => 'fees',
                'priority' => 'high',
                'status' => 'read',
                'created_at' => Carbon::now()->subDays(3)->format('Y-m-d H:i:s'),
                'sender' => 'Finance Office',
                'sender_type' => 'admin',
            ],
        ];

        // Apply filters
        if ($type !== 'all') {
            $notifications = array_filter($notifications, function($notification) use ($type) {
                return $notification['type'] === $type;
            });
        }

        if ($status !== 'all') {
            $notifications = array_filter($notifications, function($notification) use ($status) {
                return $notification['status'] === $status;
            });
        }

        if ($priority !== 'all') {
            $notifications = array_filter($notifications, function($notification) use ($priority) {
                return $notification['priority'] === $priority;
            });
        }

        return $notifications;
    }

    private function getNotificationTypes()
    {
        // Mock data - replace with actual database queries
        return [
            'assignment' => 'Assignment',
            'exam' => 'Exam',
            'library' => 'Library',
            'hostel' => 'Hostel',
            'fees' => 'Fees',
            'transport' => 'Transport',
            'general' => 'General',
            'urgent' => 'Urgent',
        ];
    }

    private function getPriorities()
    {
        // Mock data - replace with actual database queries
        return [
            'low' => 'Low',
            'medium' => 'Medium',
            'high' => 'High',
            'urgent' => 'Urgent',
        ];
    }

    private function getNotificationDetails($id)
    {
        // Mock data - replace with actual database queries
        $notifications = [
            'NOT-001' => [
                'id' => 'NOT-001',
                'title' => 'Assignment Due Tomorrow',
                'message' => 'Your Computer Science assignment is due tomorrow at 11:59 PM. Please ensure you submit your work on time to avoid any penalties.',
                'type' => 'assignment',
                'priority' => 'high',
                'status' => 'unread',
                'created_at' => Carbon::now()->subHours(2)->format('Y-m-d H:i:s'),
                'sender' => 'Dr. Smith',
                'sender_type' => 'instructor',
                'attachments' => ['assignment_guidelines.pdf'],
                'action_required' => true,
                'action_url' => '/assignments/submit',
                'expires_at' => Carbon::tomorrow()->format('Y-m-d H:i:s'),
            ],
        ];

        return $notifications[$id] ?? null;
    }

    private function updateNotificationStatus($id, $status)
    {
        // Mock implementation - replace with actual database update
        return true;
    }

    private function markAllNotificationsAsRead($student)
    {
        // Mock implementation - replace with actual database update
        return true;
    }

    private function deleteNotification($id)
    {
        // Mock implementation - replace with actual database delete
        return true;
    }

    private function getNotificationSettings($student)
    {
        // Mock data - replace with actual database queries
        return [
            'email_notifications' => true,
            'sms_notifications' => false,
            'push_notifications' => true,
            'notification_types' => ['assignment', 'exam', 'library', 'fees'],
            'quiet_hours_start' => '22:00',
            'quiet_hours_end' => '08:00',
            'digest_frequency' => 'daily',
            'marketing_emails' => false,
        ];
    }

    private function getDeliveryMethods()
    {
        // Mock data - replace with actual database queries
        return [
            'email' => 'Email',
            'sms' => 'SMS',
            'push' => 'Push Notification',
            'in_app' => 'In-App Notification',
        ];
    }

    private function updateNotificationSettings($student, $data)
    {
        // Mock implementation - replace with actual database update
        return true;
    }

    private function getRecipients()
    {
        // Mock data - replace with actual database queries
        return [
            'instructors' => 'Instructors',
            'classmates' => 'Classmates',
            'hostel_mates' => 'Hostel Mates',
            'all_students' => 'All Students',
        ];
    }

    private function getTemplates()
    {
        // Mock data - replace with actual database queries
        return [
            'assignment_reminder' => 'Assignment Reminder',
            'exam_notice' => 'Exam Notice',
            'general_announcement' => 'General Announcement',
            'urgent_alert' => 'Urgent Alert',
        ];
    }

    private function createNotification($student, $data)
    {
        // Mock implementation - replace with actual database insert
        return [
            'id' => 'NOT-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
            'title' => $data['subject'],
            'message' => $data['message'],
            'type' => $data['type'],
            'priority' => $data['priority'],
            'status' => 'sent',
            'created_at' => now()->format('Y-m-d H:i:s'),
        ];
    }
}
