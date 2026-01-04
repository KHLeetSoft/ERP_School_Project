<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StudentTransportController extends Controller
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

        // Get transport information for the student
        $transportInfo = $this->getTransportInfo($student);
        $upcomingTrips = $this->getUpcomingTrips($student);
        $recentTrips = $this->getRecentTrips($student);
        $transportStats = $this->getTransportStats($student);

        return view('student.transport.index', compact(
            'transportInfo',
            'upcomingTrips',
            'recentTrips',
            'transportStats'
        ));
    }

    public function routes()
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

        // Get available routes
        $routes = $this->getAvailableRoutes();
        $studentRoute = $this->getStudentRoute($student);

        return view('student.transport.routes', compact('routes', 'studentRoute'));
    }

    public function schedule()
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

        // Get schedule for student's route
        $schedule = $this->getStudentSchedule($student);
        $currentWeek = $this->getCurrentWeekSchedule($student);

        return view('student.transport.schedule', compact('schedule', 'currentWeek'));
    }

    public function history()
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

        // Get trip history
        $trips = $this->getTripHistory($student);
        $monthlyStats = $this->getMonthlyStats($student);

        return view('student.transport.history', compact('trips', 'monthlyStats'));
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

        // Get transport profile information
        $transportProfile = $this->getTransportProfile($student);
        $paymentHistory = $this->getPaymentHistory($student);

        return view('student.transport.profile', compact('transportProfile', 'paymentHistory'));
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
            'pickup_point' => 'required|string|max:255',
            'drop_point' => 'required|string|max:255',
            'emergency_contact' => 'required|string|max:20',
            'transport_notes' => 'nullable|string|max:500',
        ]);

        // Update transport profile
        $this->updateTransportProfile($student, $request->all());

        return redirect()->route('student.transport.profile')
            ->with('success', 'Transport profile updated successfully!');
    }

    private function getTransportInfo($student)
    {
        // Mock data - replace with actual database queries
        return [
            'route_name' => 'Route A - Downtown',
            'vehicle_number' => 'BUS-001',
            'driver_name' => 'John Smith',
            'driver_phone' => '+1-555-0123',
            'conductor_name' => 'Jane Doe',
            'conductor_phone' => '+1-555-0124',
            'pickup_time' => '07:30 AM',
            'drop_time' => '03:30 PM',
            'pickup_point' => 'Main Street Bus Stop',
            'drop_point' => 'School Main Gate',
            'status' => 'Active',
            'monthly_fee' => 150.00,
            'next_payment_due' => Carbon::now()->addDays(15)->format('Y-m-d'),
        ];
    }

    private function getUpcomingTrips($student)
    {
        // Mock data - replace with actual database queries
        return [
            [
                'date' => Carbon::tomorrow()->format('Y-m-d'),
                'pickup_time' => '07:30 AM',
                'drop_time' => '03:30 PM',
                'status' => 'Scheduled',
                'route' => 'Route A - Downtown',
            ],
            [
                'date' => Carbon::tomorrow()->addDay()->format('Y-m-d'),
                'pickup_time' => '07:30 AM',
                'drop_time' => '03:30 PM',
                'status' => 'Scheduled',
                'route' => 'Route A - Downtown',
            ],
        ];
    }

    private function getRecentTrips($student)
    {
        // Mock data - replace with actual database queries
        return [
            [
                'date' => Carbon::yesterday()->format('Y-m-d'),
                'pickup_time' => '07:30 AM',
                'drop_time' => '03:30 PM',
                'status' => 'Completed',
                'route' => 'Route A - Downtown',
            ],
            [
                'date' => Carbon::yesterday()->subDay()->format('Y-m-d'),
                'pickup_time' => '07:30 AM',
                'drop_time' => '03:30 PM',
                'status' => 'Completed',
                'route' => 'Route A - Downtown',
            ],
        ];
    }

    private function getTransportStats($student)
    {
        // Mock data - replace with actual database queries
        return [
            'total_trips' => 45,
            'this_month' => 18,
            'attendance_rate' => 95.5,
            'punctuality_rate' => 88.2,
        ];
    }

    private function getAvailableRoutes()
    {
        // Mock data - replace with actual database queries
        return [
            [
                'id' => 1,
                'name' => 'Route A - Downtown',
                'pickup_points' => ['Main Street', 'Central Park', 'City Center'],
                'drop_points' => ['School Main Gate', 'Library Entrance'],
                'distance' => '12.5 km',
                'duration' => '25 minutes',
                'capacity' => 40,
                'available_seats' => 5,
                'monthly_fee' => 150.00,
            ],
            [
                'id' => 2,
                'name' => 'Route B - Suburbs',
                'pickup_points' => ['Oak Street', 'Pine Avenue', 'Maple Drive'],
                'drop_points' => ['School Main Gate'],
                'distance' => '18.2 km',
                'duration' => '35 minutes',
                'capacity' => 35,
                'available_seats' => 12,
                'monthly_fee' => 180.00,
            ],
            [
                'id' => 3,
                'name' => 'Route C - East Side',
                'pickup_points' => ['East Mall', 'Riverside', 'Harbor View'],
                'drop_points' => ['School Main Gate', 'Gym Entrance'],
                'distance' => '15.8 km',
                'duration' => '30 minutes',
                'capacity' => 30,
                'available_seats' => 8,
                'monthly_fee' => 165.00,
            ],
        ];
    }

    private function getStudentRoute($student)
    {
        // Mock data - replace with actual database queries
        return [
            'id' => 1,
            'name' => 'Route A - Downtown',
            'pickup_point' => 'Main Street Bus Stop',
            'drop_point' => 'School Main Gate',
            'monthly_fee' => 150.00,
            'status' => 'Active',
        ];
    }

    private function getStudentSchedule($student)
    {
        // Mock data - replace with actual database queries
        return [
            'monday' => [
                'pickup' => '07:30 AM',
                'drop' => '03:30 PM',
                'status' => 'Active',
            ],
            'tuesday' => [
                'pickup' => '07:30 AM',
                'drop' => '03:30 PM',
                'status' => 'Active',
            ],
            'wednesday' => [
                'pickup' => '07:30 AM',
                'drop' => '03:30 PM',
                'status' => 'Active',
            ],
            'thursday' => [
                'pickup' => '07:30 AM',
                'drop' => '03:30 PM',
                'status' => 'Active',
            ],
            'friday' => [
                'pickup' => '07:30 AM',
                'drop' => '03:30 PM',
                'status' => 'Active',
            ],
            'saturday' => [
                'pickup' => '08:00 AM',
                'drop' => '12:00 PM',
                'status' => 'Weekend',
            ],
            'sunday' => [
                'pickup' => null,
                'drop' => null,
                'status' => 'No Service',
            ],
        ];
    }

    private function getCurrentWeekSchedule($student)
    {
        // Mock data - replace with actual database queries
        $week = [];
        for ($i = 0; $i < 7; $i++) {
            $date = Carbon::now()->startOfWeek()->addDays($i);
            $week[] = [
                'date' => $date->format('Y-m-d'),
                'day' => $date->format('l'),
                'pickup' => $i < 5 ? '07:30 AM' : ($i === 5 ? '08:00 AM' : null),
                'drop' => $i < 5 ? '03:30 PM' : ($i === 5 ? '12:00 PM' : null),
                'status' => $i < 5 ? 'Scheduled' : ($i === 5 ? 'Weekend' : 'No Service'),
            ];
        }
        return $week;
    }

    private function getTripHistory($student)
    {
        // Mock data - replace with actual database queries
        $trips = [];
        for ($i = 0; $i < 30; $i++) {
            $date = Carbon::now()->subDays($i);
            $trips[] = [
                'date' => $date->format('Y-m-d'),
                'day' => $date->format('l'),
                'pickup_time' => '07:30 AM',
                'drop_time' => '03:30 PM',
                'status' => $i % 20 === 0 ? 'Missed' : 'Completed',
                'route' => 'Route A - Downtown',
                'driver' => 'John Smith',
                'notes' => $i % 20 === 0 ? 'Student absent' : null,
            ];
        }
        return $trips;
    }

    private function getMonthlyStats($student)
    {
        // Mock data - replace with actual database queries
        return [
            'total_trips' => 22,
            'completed' => 20,
            'missed' => 2,
            'attendance_rate' => 90.9,
            'punctuality_rate' => 85.0,
        ];
    }

    private function getTransportProfile($student)
    {
        // Mock data - replace with actual database queries
        return [
            'route_name' => 'Route A - Downtown',
            'pickup_point' => 'Main Street Bus Stop',
            'drop_point' => 'School Main Gate',
            'emergency_contact' => '+1-555-0123',
            'transport_notes' => 'Please call if running late',
            'monthly_fee' => 150.00,
            'payment_method' => 'Monthly',
            'status' => 'Active',
            'enrollment_date' => '2024-01-15',
        ];
    }

    private function getPaymentHistory($student)
    {
        // Mock data - replace with actual database queries
        return [
            [
                'month' => 'January 2024',
                'amount' => 150.00,
                'status' => 'Paid',
                'payment_date' => '2024-01-15',
                'method' => 'Bank Transfer',
            ],
            [
                'month' => 'February 2024',
                'amount' => 150.00,
                'status' => 'Paid',
                'payment_date' => '2024-02-15',
                'method' => 'Bank Transfer',
            ],
            [
                'month' => 'March 2024',
                'amount' => 150.00,
                'status' => 'Pending',
                'payment_date' => null,
                'method' => 'Bank Transfer',
            ],
        ];
    }

    private function updateTransportProfile($student, $data)
    {
        // Mock implementation - replace with actual database update
        return true;
    }
}
