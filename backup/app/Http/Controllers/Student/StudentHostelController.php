<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StudentHostelController extends Controller
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

        // Get hostel information for the student
        $hostelInfo = $this->getHostelInfo($student);
        $roomInfo = $this->getRoomInfo($student);
        $mealInfo = $this->getMealInfo($student);
        $hostelStats = $this->getHostelStats($student);
        $recentActivities = $this->getRecentActivities($student);

        return view('student.hostel.index', compact(
            'hostelInfo',
            'roomInfo',
            'mealInfo',
            'hostelStats',
            'recentActivities'
        ));
    }

    public function rooms()
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

        // Get room information
        $roomDetails = $this->getRoomDetails($student);
        $roommates = $this->getRoommates($student);
        $roomRules = $this->getRoomRules();
        $maintenanceRequests = $this->getMaintenanceRequests($student);

        return view('student.hostel.rooms', compact(
            'roomDetails',
            'roommates',
            'roomRules',
            'maintenanceRequests'
        ));
    }

    public function meals()
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

        // Get meal information
        $mealPlan = $this->getMealPlan($student);
        $weeklyMenu = $this->getWeeklyMenu();
        $mealHistory = $this->getMealHistory($student);
        $mealStats = $this->getMealStats($student);

        return view('student.hostel.meals', compact(
            'mealPlan',
            'weeklyMenu',
            'mealHistory',
            'mealStats'
        ));
    }

    public function complaints()
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

        // Get complaints information
        $complaints = $this->getComplaints($student);
        $complaintCategories = $this->getComplaintCategories();
        $complaintStats = $this->getComplaintStats($student);

        return view('student.hostel.complaints', compact(
            'complaints',
            'complaintCategories',
            'complaintStats'
        ));
    }

    public function submitComplaint(Request $request)
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
            'category' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'priority' => 'required|in:low,medium,high,urgent',
            'attachments' => 'nullable|array|max:3',
            'attachments.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
        ]);

        // Handle file uploads
        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $attachments[] = $file->storeAs('complaint_attachments', $fileName, 'public');
            }
        }

        // Create complaint
        $complaint = $this->createComplaint($student, $request->all(), $attachments);

        return redirect()->route('student.hostel.complaints')
            ->with('success', 'Complaint submitted successfully! Reference ID: ' . $complaint['id']);
    }

    public function profile()
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

        // Get hostel profile information
        $hostelProfile = $this->getHostelProfile($student);
        $paymentHistory = $this->getHostelPaymentHistory($student);
        $visitorLog = $this->getVisitorLog($student);

        return view('student.hostel.profile', compact(
            'hostelProfile',
            'paymentHistory',
            'visitorLog'
        ));
    }

    public function updateProfile(Request $request)
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
            'emergency_contact' => 'required|string|max:20',
            'medical_conditions' => 'nullable|string|max:500',
            'dietary_restrictions' => 'nullable|string|max:500',
            'hostel_notes' => 'nullable|string|max:500',
        ]);

        // Update hostel profile
        $this->updateHostelProfile($student, $request->all());

        return redirect()->route('student.hostel.profile')
            ->with('success', 'Hostel profile updated successfully!');
    }

    private function getHostelInfo($student)
    {
        // Mock data - replace with actual database queries
        return [
            'hostel_name' => 'Greenwood Hostel',
            'hostel_type' => 'Boys Hostel',
            'address' => '123 University Road, Campus Area',
            'contact_phone' => '+1-555-0100',
            'contact_email' => 'hostel@university.edu',
            'warden_name' => 'Dr. Sarah Johnson',
            'warden_phone' => '+1-555-0101',
            'assistant_warden' => 'Mr. Michael Brown',
            'assistant_phone' => '+1-555-0102',
            'check_in_time' => '06:00 AM',
            'check_out_time' => '10:00 PM',
            'status' => 'Active',
        ];
    }

    private function getRoomInfo($student)
    {
        // Mock data - replace with actual database queries
        return [
            'room_number' => 'A-205',
            'floor' => '2nd Floor',
            'block' => 'Block A',
            'room_type' => 'Double Occupancy',
            'capacity' => 2,
            'current_occupancy' => 2,
            'room_condition' => 'Good',
            'furniture' => ['Bed', 'Study Table', 'Wardrobe', 'Chair'],
            'amenities' => ['WiFi', 'Air Conditioning', 'Fan', 'Lighting'],
        ];
    }

    private function getMealInfo($student)
    {
        // Mock data - replace with actual database queries
        return [
            'meal_plan' => 'Full Board',
            'meal_times' => [
                'breakfast' => '07:00 AM - 09:00 AM',
                'lunch' => '12:00 PM - 02:00 PM',
                'dinner' => '07:00 PM - 09:00 PM',
            ],
            'dining_hall' => 'Main Dining Hall',
            'special_diet' => 'Vegetarian',
            'monthly_meal_fee' => 200.00,
        ];
    }

    private function getHostelStats($student)
    {
        // Mock data - replace with actual database queries
        return [
            'days_stayed' => 45,
            'visitors_this_month' => 8,
            'complaints_submitted' => 2,
            'maintenance_requests' => 1,
            'meal_attendance_rate' => 92.5,
        ];
    }

    private function getRecentActivities($student)
    {
        // Mock data - replace with actual database queries
        return [
            [
                'date' => Carbon::now()->subDays(1)->format('Y-m-d'),
                'time' => '08:30 PM',
                'activity' => 'Visitor checked in',
                'details' => 'John Doe visited',
                'type' => 'visitor',
            ],
            [
                'date' => Carbon::now()->subDays(2)->format('Y-m-d'),
                'time' => '02:15 PM',
                'activity' => 'Complaint submitted',
                'details' => 'WiFi connectivity issue',
                'type' => 'complaint',
            ],
            [
                'date' => Carbon::now()->subDays(3)->format('Y-m-d'),
                'time' => '07:45 AM',
                'activity' => 'Meal served',
                'details' => 'Breakfast - Continental',
                'type' => 'meal',
            ],
        ];
    }

    private function getRoomDetails($student)
    {
        // Mock data - replace with actual database queries
        return [
            'room_number' => 'A-205',
            'floor' => '2nd Floor',
            'block' => 'Block A',
            'room_type' => 'Double Occupancy',
            'area' => '120 sq ft',
            'furniture' => [
                ['item' => 'Single Bed', 'condition' => 'Good', 'quantity' => 2],
                ['item' => 'Study Table', 'condition' => 'Good', 'quantity' => 2],
                ['item' => 'Wardrobe', 'condition' => 'Good', 'quantity' => 2],
                ['item' => 'Chair', 'condition' => 'Fair', 'quantity' => 2],
            ],
            'amenities' => [
                'WiFi Internet',
                'Air Conditioning',
                'Ceiling Fan',
                'LED Lighting',
                'Power Outlets',
                'Window with Curtains',
            ],
            'room_condition' => 'Good',
            'last_inspection' => '2024-03-01',
            'next_inspection' => '2024-04-01',
        ];
    }

    private function getRoommates($student)
    {
        // Mock data - replace with actual database queries
        return [
            [
                'name' => 'Alex Johnson',
                'course' => 'Computer Science',
                'year' => '2nd Year',
                'phone' => '+1-555-0201',
                'email' => 'alex.johnson@university.edu',
                'join_date' => '2024-01-15',
                'status' => 'Active',
            ],
        ];
    }

    private function getRoomRules()
    {
        // Mock data - replace with actual database queries
        return [
            'Quiet hours are from 10:00 PM to 6:00 AM',
            'No smoking or alcohol consumption in rooms',
            'Visitors must be registered at the reception',
            'Keep room clean and organized at all times',
            'Report any maintenance issues immediately',
            'No cooking in rooms (use common kitchen)',
            'Respect roommate\'s privacy and space',
            'Follow hostel dress code in common areas',
        ];
    }

    private function getMaintenanceRequests($student)
    {
        // Mock data - replace with actual database queries
        return [
            [
                'id' => 'MNT-001',
                'date' => '2024-03-15',
                'issue' => 'WiFi connectivity problem',
                'status' => 'In Progress',
                'priority' => 'Medium',
                'assigned_to' => 'IT Support',
            ],
            [
                'id' => 'MNT-002',
                'date' => '2024-03-10',
                'issue' => 'Air conditioning not working',
                'status' => 'Completed',
                'priority' => 'High',
                'assigned_to' => 'Maintenance Team',
            ],
        ];
    }

    private function getMealPlan($student)
    {
        // Mock data - replace with actual database queries
        return [
            'plan_type' => 'Full Board',
            'includes' => ['Breakfast', 'Lunch', 'Dinner'],
            'monthly_fee' => 200.00,
            'dining_hall' => 'Main Dining Hall',
            'special_diet' => 'Vegetarian',
            'meal_times' => [
                'breakfast' => '07:00 AM - 09:00 AM',
                'lunch' => '12:00 PM - 02:00 PM',
                'dinner' => '07:00 PM - 09:00 PM',
            ],
        ];
    }

    private function getWeeklyMenu()
    {
        // Mock data - replace with actual database queries
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $menu = [];
        
        foreach ($days as $day) {
            $menu[] = [
                'day' => $day,
                'breakfast' => 'Continental Breakfast',
                'lunch' => 'Indian Thali',
                'dinner' => 'North Indian Cuisine',
                'special' => $day === 'Sunday' ? 'Special Sunday Brunch' : null,
            ];
        }
        
        return $menu;
    }

    private function getMealHistory($student)
    {
        // Mock data - replace with actual database queries
        $history = [];
        for ($i = 0; $i < 7; $i++) {
            $date = Carbon::now()->subDays($i);
            $history[] = [
                'date' => $date->format('Y-m-d'),
                'day' => $date->format('l'),
                'breakfast' => $i % 3 === 0 ? 'Present' : 'Absent',
                'lunch' => $i % 5 === 0 ? 'Absent' : 'Present',
                'dinner' => 'Present',
            ];
        }
        
        return $history;
    }

    private function getMealStats($student)
    {
        // Mock data - replace with actual database queries
        return [
            'total_meals' => 21,
            'attended_meals' => 19,
            'attendance_rate' => 90.5,
            'favorite_meal' => 'Lunch',
            'most_missed' => 'Breakfast',
        ];
    }

    private function getComplaints($student)
    {
        // Mock data - replace with actual database queries
        return [
            [
                'id' => 'CMP-001',
                'date' => '2024-03-15',
                'category' => 'WiFi Issues',
                'subject' => 'Internet connectivity problem',
                'status' => 'Open',
                'priority' => 'Medium',
                'assigned_to' => 'IT Support',
                'response' => null,
            ],
            [
                'id' => 'CMP-002',
                'date' => '2024-03-10',
                'category' => 'Room Maintenance',
                'subject' => 'Air conditioning not working',
                'status' => 'Resolved',
                'priority' => 'High',
                'assigned_to' => 'Maintenance Team',
                'response' => 'Issue resolved. AC unit replaced.',
            ],
        ];
    }

    private function getComplaintCategories()
    {
        // Mock data - replace with actual database queries
        return [
            'WiFi Issues',
            'Room Maintenance',
            'Meal Quality',
            'Security Concerns',
            'Noise Complaints',
            'Cleaning Issues',
            'Visitor Management',
            'Other',
        ];
    }

    private function getComplaintStats($student)
    {
        // Mock data - replace with actual database queries
        return [
            'total_complaints' => 5,
            'open_complaints' => 1,
            'resolved_complaints' => 4,
            'average_resolution_time' => '2.5 days',
        ];
    }

    private function createComplaint($student, $data, $attachments)
    {
        // Mock implementation - replace with actual database insert
        return [
            'id' => 'CMP-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
            'date' => now()->format('Y-m-d'),
            'status' => 'Open',
        ];
    }

    private function getHostelProfile($student)
    {
        // Mock data - replace with actual database queries
        return [
            'hostel_name' => 'Greenwood Hostel',
            'room_number' => 'A-205',
            'check_in_date' => '2024-01-15',
            'expected_check_out' => '2024-12-31',
            'emergency_contact' => '+1-555-0123',
            'medical_conditions' => 'None',
            'dietary_restrictions' => 'Vegetarian',
            'hostel_notes' => 'Prefers quiet environment',
            'monthly_fee' => 500.00,
            'status' => 'Active',
        ];
    }

    private function getHostelPaymentHistory($student)
    {
        // Mock data - replace with actual database queries
        return [
            [
                'month' => 'March 2024',
                'amount' => 500.00,
                'status' => 'Paid',
                'payment_date' => '2024-03-15',
                'method' => 'Bank Transfer',
            ],
            [
                'month' => 'February 2024',
                'amount' => 500.00,
                'status' => 'Paid',
                'payment_date' => '2024-02-15',
                'method' => 'Bank Transfer',
            ],
            [
                'month' => 'January 2024',
                'amount' => 500.00,
                'status' => 'Paid',
                'payment_date' => '2024-01-15',
                'method' => 'Bank Transfer',
            ],
        ];
    }

    private function getVisitorLog($student)
    {
        // Mock data - replace with actual database queries
        return [
            [
                'date' => '2024-03-15',
                'visitor_name' => 'John Doe',
                'relation' => 'Friend',
                'check_in' => '02:30 PM',
                'check_out' => '05:45 PM',
                'purpose' => 'Study session',
                'status' => 'Completed',
            ],
            [
                'date' => '2024-03-10',
                'visitor_name' => 'Jane Smith',
                'relation' => 'Sister',
                'check_in' => '10:00 AM',
                'check_out' => '12:00 PM',
                'purpose' => 'Family visit',
                'status' => 'Completed',
            ],
        ];
    }

    private function updateHostelProfile($student, $data)
    {
        // Mock implementation - replace with actual database update
        return true;
    }
}
