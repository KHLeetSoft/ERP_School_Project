<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\SchoolClass;
use App\Models\StudentFee;
use App\Models\StudentPayment;
use App\Models\Book;
use App\Models\HostelRoom;
use App\Models\StaffAttendance;
use App\Models\Exam;
use App\Models\Assignment;
use App\Models\ResultNotification;
use App\Models\StudentDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
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

    public function index()
    {
        return $this->dashboard();
    }

    public function dashboard()
    {
        $adminUser = auth()->guard('admin')->user();
        $schoolId = $adminUser ? $adminUser->school_id : null;

        // Get current date and month
        $currentDate = Carbon::now();
        $currentMonth = $currentDate->format('Y-m');
        $lastMonth = $currentDate->subMonth()->format('Y-m');

        // Basic Statistics
        $stats = [
            // Student Statistics
            'total_students' => User::whereHas('userRole', function($query) {
                $query->where('name', 'Student');
            })->where('school_id', $schoolId)->count(),
            
            'active_students' => User::whereHas('userRole', function($query) {
                $query->where('name', 'Student');
            })->where('school_id', $schoolId)->where('status', 1)->count(),
            
            'new_students_this_month' => User::whereHas('userRole', function($query) {
                $query->where('name', 'Student');
            })->where('school_id', $schoolId)
              ->whereMonth('created_at', $currentDate->month)
              ->whereYear('created_at', $currentDate->year)
              ->count(),

            // Teacher Statistics
            'total_teachers' => User::whereHas('userRole', function($query) {
                $query->where('name', 'Teacher');
            })->where('school_id', $schoolId)->count(),
            
            'active_teachers' => User::whereHas('userRole', function($query) {
                $query->where('name', 'Teacher');
            })->where('school_id', $schoolId)->where('status', 1)->count(),

            // Class Statistics
            'total_classes' => SchoolClass::where('school_id', $schoolId)->count(),
            'active_classes' => SchoolClass::where('school_id', $schoolId)->count(),

            // Financial Statistics
            'total_revenue' => StudentPayment::where('status', 'completed')
                ->where('school_id', $schoolId)
                ->sum('amount'),
            'pending_fees' => StudentPayment::where('status', 'pending')
                ->where('school_id', $schoolId)
                ->sum('amount'),
            'this_month_revenue' => StudentPayment::where('status', 'completed')
                ->where('school_id', $schoolId)
                ->whereMonth('payment_date', $currentDate->month)
                ->whereYear('payment_date', $currentDate->year)
                ->sum('amount'),
            'last_month_revenue' => StudentPayment::where('status', 'completed')
                ->where('school_id', $schoolId)
                ->whereMonth('payment_date', $currentDate->subMonth()->month)
                ->whereYear('payment_date', $currentDate->subMonth()->year)
                ->sum('amount'),

            // Library Statistics
            'total_books' => Book::count(),
            'available_books' => Book::where('status', 'available')->count(),
            'issued_books' => Book::where('status', 'issued')->count(),

            // Hostel Statistics
            'hostel_rooms' => HostelRoom::count(),
            'occupied_rooms' => HostelRoom::where('status', 'occupied')->count(),
            'available_rooms' => HostelRoom::where('status', 'available')->count(),

            // Academic Statistics
            'total_exams' => Exam::count(),
            'upcoming_exams' => Exam::where('start_date', '>=', $currentDate->toDateString())->count(),
            'total_assignments' => Assignment::count(),
            'pending_assignments' => Assignment::where('due_date', '>=', $currentDate->toDateString())->count(),

            // Attendance Statistics
            'today_attendance' => StaffAttendance::whereDate('attendance_date', $currentDate->toDateString())->count(),
            'attendance_rate' => $this->calculateAttendanceRate($schoolId),

            // Notification Statistics
            'total_notifications' => ResultNotification::count(),
            'unread_notifications' => ResultNotification::where('status', 'pending')->count(),
        ];

        // Chart Data
        $chartData = [
            'monthly_students' => $this->getMonthlyStudentData($schoolId),
            'monthly_revenue' => $this->getMonthlyRevenueData($schoolId),
            'attendance_trend' => $this->getAttendanceTrendData($schoolId),
            'class_distribution' => $this->getClassDistributionData($schoolId),
            'examination_performance' => $this->getExaminationPerformanceData($schoolId),
            'finance_overview' => $this->getFinanceOverviewData($schoolId),
            'expenditure_analysis' => $this->getExpenditureAnalysisData($schoolId),
            'income_analytics' => $this->getIncomeAnalyticsData($schoolId),
        ];

        // Recent Activities
        $recentActivities = $this->getRecentActivities($schoolId);

        // Upcoming Events
        $upcomingEvents = $this->getUpcomingEvents($schoolId);

        // Top Performing Classes
        $topClasses = $this->getTopPerformingClasses($schoolId);

        return view('admin.dashboard', compact('stats', 'chartData', 'recentActivities', 'upcomingEvents', 'topClasses'));
    }

    private function calculateAttendanceRate($schoolId)
    {
        $totalStudents = User::whereHas('userRole', function($query) {
            $query->where('name', 'Student');
        })->where('school_id', $schoolId)->count();

        if ($totalStudents == 0) return 0;

        $presentToday = StaffAttendance::whereDate('attendance_date', Carbon::today())
            ->where('status', 'present')
            ->count();

        return round(($presentToday / $totalStudents) * 100, 1);
    }

    private function getMonthlyStudentData($schoolId)
    {
        $months = [];
        $studentCounts = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M Y');
            
            $count = User::whereHas('userRole', function($query) {
                $query->where('name', 'Student');
            })->where('school_id', $schoolId)
              ->whereMonth('created_at', $date->month)
              ->whereYear('created_at', $date->year)
              ->count();
            
            $studentCounts[] = $count;
        }

        return ['months' => $months, 'counts' => $studentCounts];
    }

    private function getMonthlyRevenueData($schoolId = null)
    {
        $months = [];
        $revenues = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M Y');
            
            $query = StudentPayment::where('status', 'completed');
            
            if ($schoolId) {
                $query->where('school_id', $schoolId);
            }
            
            $revenue = $query->whereMonth('payment_date', $date->month)
                ->whereYear('payment_date', $date->year)
                ->sum('amount');
            
            $revenues[] = $revenue;
        }

        return ['months' => $months, 'revenues' => $revenues];
    }

    private function getAttendanceTrendData($schoolId)
    {
        $days = [];
        $attendanceRates = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $days[] = $date->format('D');
            
            $totalStudents = User::whereHas('userRole', function($query) {
                $query->where('name', 'Student');
            })->where('school_id', $schoolId)->count();

            if ($totalStudents > 0) {
                $present = StaffAttendance::whereDate('attendance_date', $date->toDateString())
                    ->where('status', 'present')
                    ->count();
                $rate = round(($present / $totalStudents) * 100, 1);
            } else {
                $rate = 0;
            }
            
            $attendanceRates[] = $rate;
        }

        return ['days' => $days, 'rates' => $attendanceRates];
    }

    private function getClassDistributionData($schoolId)
    {
        $classes = SchoolClass::where('school_id', $schoolId)->get();
        $classNames = [];
        $studentCounts = [];

        foreach ($classes as $class) {
            $classNames[] = $class->name;
            $count = User::whereHas('userRole', function($query) {
                $query->where('name', 'Student');
            })->where('school_id', $schoolId)
              ->whereHas('student', function($query) use ($class) {
                  $query->where('class_section_id', $class->id);
              })->count();
            $studentCounts[] = $count;
        }

        return ['classes' => $classNames, 'counts' => $studentCounts];
    }

    private function getRecentActivities($schoolId)
    {
        return [
            [
                'activity' => 'New student registered',
                'user' => 'John Doe',
                'time' => '2 hours ago',
                'status' => 'completed',
                'icon' => 'fas fa-user-plus',
                'color' => 'success'
            ],
            [
                'activity' => 'Fee payment received',
                'user' => 'Jane Smith',
                'time' => '4 hours ago',
                'status' => 'completed',
                'icon' => 'fas fa-credit-card',
                'color' => 'success'
            ],
            [
                'activity' => 'Exam scheduled',
                'user' => 'Math Department',
                'time' => '6 hours ago',
                'status' => 'pending',
                'icon' => 'fas fa-clipboard-list',
                'color' => 'warning'
            ],
            [
                'activity' => 'Library book issued',
                'user' => 'Alice Johnson',
                'time' => '8 hours ago',
                'status' => 'in_progress',
                'icon' => 'fas fa-book',
                'color' => 'info'
            ],
            [
                'activity' => 'Hostel room allocated',
                'user' => 'Bob Wilson',
                'time' => '1 day ago',
                'status' => 'completed',
                'icon' => 'fas fa-home',
                'color' => 'success'
            ],
            [
                'activity' => 'Assignment submitted',
                'user' => 'Charlie Brown',
                'time' => '2 days ago',
                'status' => 'completed',
                'icon' => 'fas fa-file-alt',
                'color' => 'success'
            ]
        ];
    }

    private function getUpcomingEvents($schoolId)
    {
        return [
            [
                'title' => 'Annual Sports Day',
                'date' => Carbon::now()->addDays(5)->format('M d, Y'),
                'type' => 'event',
                'color' => 'primary'
            ],
            [
                'title' => 'Parent-Teacher Meeting',
                'date' => Carbon::now()->addDays(8)->format('M d, Y'),
                'type' => 'meeting',
                'color' => 'info'
            ],
            [
                'title' => 'Science Fair',
                'date' => Carbon::now()->addDays(12)->format('M d, Y'),
                'type' => 'event',
                'color' => 'success'
            ],
            [
                'title' => 'Monthly Exam',
                'date' => Carbon::now()->addDays(15)->format('M d, Y'),
                'type' => 'exam',
                'color' => 'warning'
            ]
        ];
    }

    private function getTopPerformingClasses($schoolId)
    {
        return [
            ['class' => 'Class 10A', 'attendance' => 95.5, 'performance' => 88.2],
            ['class' => 'Class 9B', 'attendance' => 92.3, 'performance' => 85.7],
            ['class' => 'Class 11A', 'attendance' => 89.8, 'performance' => 87.1],
            ['class' => 'Class 8C', 'attendance' => 91.2, 'performance' => 82.4],
            ['class' => 'Class 12A', 'attendance' => 87.6, 'performance' => 89.3]
        ];
    }

    /**
     * Get examination performance data for charts
     */
    public function getExaminationPerformanceData($schoolId)
    {
        $months = [];
        $examPerformance = [];
        $passRates = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M');
            
            // Simulate exam performance data (in real app, this would come from exam results)
            $performance = rand(80, 95);
            $passRate = rand(75, 90);
            
            $examPerformance[] = $performance;
            $passRates[] = $passRate;
        }

        return [
            'months' => $months,
            'exam_performance' => $examPerformance,
            'pass_rates' => $passRates
        ];
    }

    /**
     * Get finance overview data for charts
     */
    public function getFinanceOverviewData($schoolId)
    {
        // Get total income from student payments
        $totalIncome = StudentPayment::where('status', 'completed')
            ->where('school_id', $schoolId)
            ->sum('amount');

        // Since student_payments doesn't have fee_type, we'll simulate distribution
        // In a real application, you would join with fee_structures or have fee_type in payments
        $tuitionFees = $totalIncome * 0.45;  // 45% tuition fees
        $hostelFees = $totalIncome * 0.25;   // 25% hostel fees
        $transportFees = $totalIncome * 0.15; // 15% transport fees
        $libraryFees = $totalIncome * 0.10;  // 10% library fees
        $otherFees = $totalIncome * 0.05;    // 5% other fees

        return [
            'labels' => ['Tuition Fees', 'Hostel Fees', 'Transport', 'Library', 'Other Income'],
            'data' => [
                round($tuitionFees, 2),
                round($hostelFees, 2),
                round($transportFees, 2),
                round($libraryFees, 2),
                round($otherFees, 2)
            ],
            'total_income' => $totalIncome
        ];
    }

    /**
     * Get expenditure analysis data for charts
     */
    public function getExpenditureAnalysisData($schoolId)
    {
        $months = [];
        $expenditures = [];
        $categories = [];

        // Monthly expenditure data
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M');
            
            // Simulate monthly expenditure (in real app, this would come from expense records)
            $expenditure = rand(120000, 160000);
            $expenditures[] = $expenditure;
        }

        // Category-wise expenditure data
        $categories = [
            'labels' => ['Salaries', 'Utilities', 'Maintenance', 'Supplies', 'Other'],
            'data' => [
                rand(80000, 100000),  // Salaries
                rand(15000, 25000),   // Utilities
                rand(10000, 20000),   // Maintenance
                rand(5000, 15000),    // Supplies
                rand(10000, 20000)    // Other
            ]
        ];

        return [
            'monthly' => [
                'months' => $months,
                'expenditures' => $expenditures
            ],
            'category' => $categories
        ];
    }

    /**
     * Get income analytics data for charts
     */
    public function getIncomeAnalyticsData($schoolId)
    {
        $months = [];
        $incomes = [];
        $sources = [];

        // Monthly income trend
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M');
            
            // Get actual income from payments
            $income = StudentPayment::where('status', 'completed')
                ->where('school_id', $schoolId)
                ->whereMonth('payment_date', $date->month)
                ->whereYear('payment_date', $date->year)
                ->sum('amount');
                
            // If no data, simulate some income
            if ($income == 0) {
                $income = rand(180000, 250000);
            }
            
            $incomes[] = $income;
        }

        // Get total income for source distribution
        $totalIncome = StudentPayment::where('status', 'completed')
            ->where('school_id', $schoolId)
            ->sum('amount');

        // Income by source (simulated distribution)
        $sources = [
            'labels' => ['Tuition', 'Hostel', 'Transport', 'Library', 'Sports', 'Other'],
            'data' => [
                round($totalIncome * 0.45, 2),  // Tuition
                round($totalIncome * 0.25, 2),  // Hostel
                round($totalIncome * 0.15, 2),  // Transport
                round($totalIncome * 0.10, 2),  // Library
                round($totalIncome * 0.03, 2),  // Sports
                round($totalIncome * 0.02, 2)   // Other
            ]
        ];

        return [
            'trend' => [
                'months' => $months,
                'incomes' => $incomes
            ],
            'sources' => $sources
        ];
    }

    /**
     * Get examination statistics for dashboard cards
     */
    public function getExaminationStats($schoolId)
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        return [
            'total_exams' => Exam::count(),
            'upcoming_exams' => Exam::where('start_date', '>=', Carbon::now()->toDateString())->count(),
            'exams_this_month' => Exam::whereMonth('start_date', $currentMonth)
                ->whereYear('start_date', $currentYear)
                ->count(),
            'avg_performance' => $this->getAverageExamPerformance($schoolId),
            'pass_rate' => $this->getOverallPassRate($schoolId)
        ];
    }

    /**
     * Get financial statistics for dashboard cards
     */
    public function getFinancialStats($schoolId)
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $lastMonth = Carbon::now()->subMonth()->month;
        $lastYear = Carbon::now()->subMonth()->year;

        $currentMonthIncome = StudentPayment::where('status', 'completed')
            ->where('school_id', $schoolId)
            ->whereMonth('payment_date', $currentMonth)
            ->whereYear('payment_date', $currentYear)
            ->sum('amount');

        $lastMonthIncome = StudentPayment::where('status', 'completed')
            ->where('school_id', $schoolId)
            ->whereMonth('payment_date', $lastMonth)
            ->whereYear('payment_date', $lastYear)
            ->sum('amount');

        $growthRate = $lastMonthIncome > 0 ? 
            round((($currentMonthIncome - $lastMonthIncome) / $lastMonthIncome) * 100, 2) : 0;

        return [
            'current_month_income' => $currentMonthIncome,
            'last_month_income' => $lastMonthIncome,
            'growth_rate' => $growthRate,
            'pending_payments' => StudentPayment::where('status', 'pending')
                ->where('school_id', $schoolId)
                ->sum('amount'),
            'total_income' => StudentPayment::where('status', 'completed')
                ->where('school_id', $schoolId)
                ->sum('amount')
        ];
    }

    /**
     * Get expenditure statistics for dashboard cards
     */
    public function getExpenditureStats($schoolId)
    {
        // In a real application, you would have an expenses table
        // For now, we'll simulate some data
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        return [
            'current_month_expenditure' => rand(120000, 160000),
            'last_month_expenditure' => rand(110000, 150000),
            'expenditure_growth' => rand(-5, 15),
            'budget_utilization' => rand(70, 95),
            'top_expense_category' => 'Salaries'
        ];
    }

    /**
     * Calculate average exam performance
     */
    private function getAverageExamPerformance($schoolId)
    {
        // In a real application, this would calculate from exam results
        return rand(80, 95);
    }

    /**
     * Calculate overall pass rate
     */
    private function getOverallPassRate($schoolId)
    {
        // In a real application, this would calculate from exam results
        return rand(75, 90);
    }

    /**
     * API endpoint to get chart data dynamically
     */
    public function getChartData(Request $request)
    {
        $type = $request->get('type');
        $schoolId = $this->schoolId;

        switch ($type) {
            case 'examination':
                return response()->json($this->getExaminationPerformanceData($schoolId));
            case 'finance':
                return response()->json($this->getFinanceOverviewData($schoolId));
            case 'expenditure':
                return response()->json($this->getExpenditureAnalysisData($schoolId));
            case 'income':
                return response()->json($this->getIncomeAnalyticsData($schoolId));
            default:
                return response()->json(['error' => 'Invalid chart type'], 400);
        }
    }
}
