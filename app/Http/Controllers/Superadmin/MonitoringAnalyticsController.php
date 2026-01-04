<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\User;
use App\Models\PaymentTransaction;
use App\Models\PaymentPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MonitoringAnalyticsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'checkrole:superadmin']);
    }

    /**
     * Display monitoring & analytics dashboard
     */
    public function index()
    {
        $metrics = $this->getSystemMetrics();
        $userStats = $this->getUserStatistics();
        $apiUsage = $this->getAPIUsageStats();
        $serverPerformance = $this->getServerPerformanceData();
        
        return view('superadmin.monitoring.index', compact('metrics', 'userStats', 'apiUsage', 'serverPerformance'));
    }

    /**
     * Get active users across all schools
     */
    public function getActiveUsers(Request $request)
    {
        $timeframe = $request->get('timeframe', '24h');
        
        $query = User::where('status', true);
        
        switch ($timeframe) {
            case '1h':
                $query->where('last_login_at', '>=', now()->subHour());
                break;
            case '24h':
                $query->where('last_login_at', '>=', now()->subDay());
                break;
            case '7d':
                $query->where('last_login_at', '>=', now()->subDays(7));
                break;
            case '30d':
                $query->where('last_login_at', '>=', now()->subDays(30));
                break;
        }
        
        $activeUsers = $query->count();
        
        // Get breakdown by role
        $roleBreakdown = $query->select('role_id', DB::raw('COUNT(*) as count'))
            ->groupBy('role_id')
            ->get();
        
        // Get breakdown by school
        $schoolBreakdown = $query->with('school')
            ->select('school_id', DB::raw('COUNT(*) as count'))
            ->groupBy('school_id')
            ->get();
        
        return response()->json([
            'total' => $activeUsers,
            'role_breakdown' => $roleBreakdown,
            'school_breakdown' => $schoolBreakdown,
            'timeframe' => $timeframe
        ]);
    }

    /**
     * Get API usage statistics
     */
    public function getAPIUsage(Request $request)
    {
        $timeframe = $request->get('timeframe', '24h');
        
        // Simulate API usage data (in real app, this would come from API logs)
        $apiStats = [
            'total_requests' => rand(10000, 50000),
            'successful_requests' => rand(9500, 48000),
            'failed_requests' => rand(100, 2000),
            'average_response_time' => rand(50, 200),
            'peak_requests_per_minute' => rand(100, 500)
        ];
        
        // Calculate success rate
        $apiStats['success_rate'] = round(($apiStats['successful_requests'] / $apiStats['total_requests']) * 100, 2);
        
        // Get hourly breakdown for charts
        $hourlyData = [];
        for ($i = 23; $i >= 0; $i--) {
            $hourlyData[] = [
                'hour' => now()->subHours($i)->format('H:00'),
                'requests' => rand(100, 1000),
                'response_time' => rand(50, 200)
            ];
        }
        
        return response()->json([
            'stats' => $apiStats,
            'hourly_data' => $hourlyData,
            'timeframe' => $timeframe
        ]);
    }

    /**
     * Get server performance metrics
     */
    public function getServerPerformance(Request $request)
    {
        // Simulate server performance data (in real app, this would come from monitoring tools)
        $performance = [
            'cpu_usage' => rand(20, 80),
            'memory_usage' => rand(30, 70),
            'disk_usage' => rand(40, 90),
            'network_io' => rand(100, 1000),
            'uptime' => '99.9%',
            'load_average' => [
                '1min' => rand(0.5, 2.0),
                '5min' => rand(0.5, 2.0),
                '15min' => rand(0.5, 2.0)
            ]
        ];
        
        // Get performance history
        $history = [];
        for ($i = 23; $i >= 0; $i--) {
            $history[] = [
                'time' => now()->subHours($i)->format('H:00'),
                'cpu' => rand(20, 80),
                'memory' => rand(30, 70),
                'disk' => rand(40, 90)
            ];
        }
        
        return response()->json([
            'current' => $performance,
            'history' => $history
        ]);
    }

    /**
     * Get error logs
     */
    public function getErrorLogs(Request $request)
    {
        $severity = $request->get('severity', 'all');
        $timeframe = $request->get('timeframe', '24h');
        
        // Simulate error logs (in real app, this would come from actual log files)
        $errors = [
            [
                'id' => 1,
                'severity' => 'error',
                'message' => 'Database connection timeout',
                'file' => 'app/Http/Controllers/SchoolController.php',
                'line' => 45,
                'timestamp' => now()->subMinutes(30),
                'count' => 5
            ],
            [
                'id' => 2,
                'severity' => 'warning',
                'message' => 'Slow query detected',
                'file' => 'app/Models/User.php',
                'line' => 123,
                'timestamp' => now()->subHour(),
                'count' => 2
            ],
            [
                'id' => 3,
                'severity' => 'error',
                'message' => 'Payment gateway timeout',
                'file' => 'app/Http/Controllers/PaymentController.php',
                'line' => 67,
                'timestamp' => now()->subHours(2),
                'count' => 1
            ]
        ];
        
        // Filter by severity
        if ($severity !== 'all') {
            $errors = array_filter($errors, function($error) use ($severity) {
                return $error['severity'] === $severity;
            });
        }
        
        return response()->json([
            'errors' => array_values($errors),
            'total_count' => count($errors),
            'severity' => $severity,
            'timeframe' => $timeframe
        ]);
    }

    /**
     * Get anomaly detection alerts
     */
    public function getAnomalyDetection(Request $request)
    {
        // Simulate anomaly detection alerts
        $anomalies = [
            [
                'id' => 1,
                'type' => 'user_activity_drop',
                'severity' => 'high',
                'title' => 'Sudden Drop in User Activity',
                'description' => 'User activity dropped by 40% compared to previous day',
                'affected_schools' => ['Delhi Public School', 'ABC School'],
                'timestamp' => now()->subHours(2),
                'status' => 'active'
            ],
            [
                'id' => 2,
                'type' => 'revenue_spike',
                'severity' => 'medium',
                'title' => 'Unusual Revenue Spike',
                'description' => 'Revenue increased by 200% in the last hour',
                'affected_schools' => ['XYZ School'],
                'timestamp' => now()->subHours(4),
                'status' => 'investigating'
            ],
            [
                'id' => 3,
                'type' => 'api_error_rate',
                'severity' => 'high',
                'title' => 'High API Error Rate',
                'description' => 'API error rate increased to 15%',
                'affected_schools' => ['All Schools'],
                'timestamp' => now()->subHours(6),
                'status' => 'resolved'
            ]
        ];
        
        return response()->json([
            'anomalies' => $anomalies,
            'active_count' => count(array_filter($anomalies, fn($a) => $a['status'] === 'active')),
            'total_count' => count($anomalies)
        ]);
    }

    /**
     * Get system metrics
     */
    private function getSystemMetrics()
    {
        return [
            'total_schools' => School::count(),
            'active_schools' => School::where('status', true)->count(),
            'total_users' => User::count(),
            'active_users' => User::where('status', true)->count(),
            'total_revenue' => PaymentTransaction::where('status', 'success')->sum('amount'),
            'monthly_revenue' => PaymentTransaction::where('status', 'success')
                ->whereMonth('created_at', now()->month)
                ->sum('amount')
        ];
    }

    /**
     * Get user statistics
     */
    private function getUserStatistics()
    {
        $userStats = User::select('role_id', DB::raw('COUNT(*) as count'))
            ->groupBy('role_id')
            ->get();
        
        $dailyLogins = User::select(DB::raw('DATE(last_login_at) as date'), DB::raw('COUNT(*) as count'))
            ->where('last_login_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        return [
            'role_breakdown' => $userStats,
            'daily_logins' => $dailyLogins
        ];
    }

    /**
     * Get API usage statistics
     */
    private function getAPIUsageStats()
    {
        // Simulate API usage data
        return [
            'total_requests' => rand(100000, 500000),
            'success_rate' => rand(95, 99),
            'average_response_time' => rand(100, 300),
            'peak_requests' => rand(1000, 5000)
        ];
    }

    /**
     * Get server performance (private helper)
     */
    private function getServerPerformanceData()
    {
        return [
            'cpu_usage' => rand(20, 80),
            'memory_usage' => rand(30, 70),
            'disk_usage' => rand(40, 90),
            'uptime' => '99.9%'
        ];
    }

    /**
     * Export monitoring data
     */
    public function exportData(Request $request)
    {
        $type = $request->get('type', 'users');
        $format = $request->get('format', 'csv');
        
        switch ($type) {
            case 'users':
                $data = $this->exportUserData();
                break;
            case 'api':
                $data = $this->exportAPIData();
                break;
            case 'performance':
                $data = $this->exportPerformanceData();
                break;
            default:
                $data = [];
        }
        
        if ($format === 'csv') {
            return $this->exportToCSV($data, $type);
        } else {
            return $this->exportToJSON($data, $type);
        }
    }

    /**
     * Export user data
     */
    private function exportUserData()
    {
        return User::with('school')
            ->select('id', 'name', 'email', 'role_id', 'school_id', 'last_login_at', 'created_at')
            ->get()
            ->toArray();
    }

    /**
     * Export API data
     */
    private function exportAPIData()
    {
        // Simulate API data export
        return [
            ['endpoint' => '/api/schools', 'requests' => 1000, 'avg_response_time' => 150],
            ['endpoint' => '/api/users', 'requests' => 2000, 'avg_response_time' => 200],
            ['endpoint' => '/api/payments', 'requests' => 500, 'avg_response_time' => 300]
        ];
    }

    /**
     * Export performance data
     */
    private function exportPerformanceData()
    {
        // Simulate performance data export
        $data = [];
        for ($i = 23; $i >= 0; $i--) {
            $data[] = [
                'timestamp' => now()->subHours($i)->toDateTimeString(),
                'cpu_usage' => rand(20, 80),
                'memory_usage' => rand(30, 70),
                'disk_usage' => rand(40, 90)
            ];
        }
        return $data;
    }

    /**
     * Export to CSV
     */
    private function exportToCSV($data, $type)
    {
        $filename = "monitoring_{$type}_" . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\""
        ];
        
        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            
            if (!empty($data)) {
                // Write headers
                fputcsv($file, array_keys($data[0]));
                
                // Write data
                foreach ($data as $row) {
                    fputcsv($file, $row);
                }
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export to JSON
     */
    private function exportToJSON($data, $type)
    {
        $filename = "monitoring_{$type}_" . now()->format('Y-m-d_H-i-s') . '.json';
        
        return response()->json($data)
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }
}
