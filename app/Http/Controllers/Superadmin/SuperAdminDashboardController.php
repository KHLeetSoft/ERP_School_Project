<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\User;
use App\Models\PaymentPlan;
use App\Models\PaymentTransaction;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SuperAdminDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'checkrole:superadmin']);
    }

    /**
     * Display the super admin dashboard
     */
    public function index(Request $request)
    {
        if ($request->has('action')) {
            return $this->handleAjaxRequest($request);
        }

        return view('superadmin.dashboard');
    }

    /**
     * Handle AJAX requests for dashboard data
     */
    private function handleAjaxRequest(Request $request)
    {
        $action = $request->get('action');

        switch ($action) {
            case 'metrics':
                return $this->getKeyMetrics();
            case 'system-health':
                return $this->getSystemHealth();
            case 'revenue-chart':
                return $this->getRevenueChartData($request);
            case 'ai-insights':
                return $this->getAIInsights();
            case 'recent-activity':
                return $this->getRecentActivity();
            case 'ai-query':
                return $this->handleAIQuery($request);
            default:
                return response()->json(['error' => 'Invalid action'], 400);
        }
    }

    /**
     * Get key metrics for dashboard
     */
    private function getKeyMetrics()
    {
        $totalSchools = School::count();
        $activePlans = PaymentPlan::where('is_active', true)->count();
        
        // Calculate monthly revenue
        $monthlyRevenue = PaymentTransaction::where('status', 'success')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');

        // Calculate active users across all schools
        $activeUsers = User::where('status', true)
            ->where('last_login_at', '>=', now()->subDays(30))
            ->count();

        return response()->json([
            'totalSchools' => $totalSchools,
            'activePlans' => $activePlans,
            'monthlyRevenue' => $monthlyRevenue,
            'activeUsers' => $activeUsers
        ]);
    }

    /**
     * Get system health data
     */
    private function getSystemHealth()
    {
        // Simulate system health data (in real app, this would come from monitoring tools)
        $serverUptime = 99.9; // This would be calculated from actual uptime data
        $storageUsage = 65; // This would be calculated from actual storage usage
        $apiResponse = 120; // This would be calculated from actual API response times
        $errorRate = 0.02; // This would be calculated from actual error logs

        return response()->json([
            'serverUptime' => $serverUptime,
            'storageUsage' => $storageUsage,
            'apiResponse' => $apiResponse,
            'errorRate' => $errorRate
        ]);
    }

    /**
     * Get revenue chart data
     */
    private function getRevenueChartData(Request $request)
    {
        $period = $request->get('period', '6months');
        
        $months = [];
        $revenue = [];
        $predicted = [];

        switch ($period) {
            case '6months':
                for ($i = 5; $i >= 0; $i--) {
                    $date = now()->subMonths($i);
                    $months[] = $date->format('M');
                    
                    $monthRevenue = PaymentTransaction::where('status', 'success')
                        ->whereMonth('created_at', $date->month)
                        ->whereYear('created_at', $date->year)
                        ->sum('amount');
                    
                    $revenue[] = $monthRevenue;
                }
                break;
            case '1year':
                for ($i = 11; $i >= 0; $i--) {
                    $date = now()->subMonths($i);
                    $months[] = $date->format('M');
                    
                    $monthRevenue = PaymentTransaction::where('status', 'success')
                        ->whereMonth('created_at', $date->month)
                        ->whereYear('created_at', $date->year)
                        ->sum('amount');
                    
                    $revenue[] = $monthRevenue;
                }
                break;
        }

        // Generate predicted revenue (simplified AI prediction)
        $lastRevenue = end($revenue);
        for ($i = 0; $i < 3; $i++) {
            $predicted[] = $lastRevenue * (1 + (0.08 * ($i + 1))); // 8% growth prediction
        }

        return response()->json([
            'months' => $months,
            'revenue' => $revenue,
            'predicted' => $predicted
        ]);
    }

    /**
     * Get AI insights and predictions
     */
    private function getAIInsights()
    {
        // Revenue forecasting
        $currentRevenue = PaymentTransaction::where('status', 'success')
            ->whereMonth('created_at', now()->month)
            ->sum('amount');
        
        $predictedRevenue = $currentRevenue * 1.15; // 15% growth prediction

        // Plan upgrade suggestions
        $upgradeSuggestions = School::whereHas('paymentPlans', function($query) {
            $query->where('name', 'like', '%Basic%');
        })->where('status', true)
        ->withCount(['users' => function($query) {
            $query->where('last_login_at', '>=', now()->subDays(7));
        }])
        ->having('users_count', '>', 50)
        ->count();

        // Churn prediction
        $churnRisk = School::where('status', true)
            ->whereDoesntHave('users', function($query) {
                $query->where('last_login_at', '>=', now()->subDays(14));
            })
            ->count();

        return response()->json([
            'revenueForecast' => [
                'current' => $currentRevenue,
                'predicted' => $predictedRevenue,
                'growth' => 15
            ],
            'upgradeSuggestions' => $upgradeSuggestions,
            'churnRisk' => $churnRisk
        ]);
    }

    /**
     * Get recent activity
     */
    private function getRecentActivity()
    {
        $activities = [];

        // Recent school registrations
        $recentSchools = School::with('users')
            ->where('created_at', '>=', now()->subHours(24))
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        foreach ($recentSchools as $school) {
            $activities[] = [
                'type' => 'school_registered',
                'title' => 'New School Registered',
                'description' => $school->name . ' joined the platform',
                'time' => $school->created_at->diffForHumans(),
                'icon' => 'fas fa-plus',
                'color' => 'success'
            ];
        }

        // Recent payments
        $recentPayments = PaymentTransaction::with('school')
            ->where('status', 'success')
            ->where('created_at', '>=', now()->subHours(24))
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        foreach ($recentPayments as $payment) {
            $activities[] = [
                'type' => 'payment_received',
                'title' => 'Payment Received',
                'description' => '₹' . number_format($payment->amount) . ' from ' . ($payment->school->name ?? 'Unknown School'),
                'time' => $payment->created_at->diffForHumans(),
                'icon' => 'fas fa-credit-card',
                'color' => 'warning'
            ];
        }

        // Recent plan upgrades
        $recentUpgrades = DB::table('payment_transactions')
            ->join('schools', 'payment_transactions.school_id', '=', 'schools.id')
            ->join('payment_plans', 'payment_transactions.plan_id', '=', 'payment_plans.id')
            ->where('payment_transactions.status', 'success')
            ->where('payment_transactions.created_at', '>=', now()->subHours(24))
            ->where('payment_plans.name', 'like', '%Premium%')
            ->orderBy('payment_transactions.created_at', 'desc')
            ->limit(3)
            ->get();

        foreach ($recentUpgrades as $upgrade) {
            $activities[] = [
                'type' => 'plan_upgrade',
                'title' => 'Plan Upgrade',
                'description' => $upgrade->name . ' upgraded to ' . $upgrade->plan_name,
                'time' => Carbon::parse($upgrade->created_at)->diffForHumans(),
                'icon' => 'fas fa-arrow-up',
                'color' => 'info'
            ];
        }

        // Sort by time and limit to 10 most recent
        usort($activities, function($a, $b) {
            return strtotime($b['time']) - strtotime($a['time']);
        });

        return response()->json(array_slice($activities, 0, 10));
    }

    /**
     * Handle AI queries
     */
    private function handleAIQuery(Request $request)
    {
        $query = $request->get('query', '');
        $query = strtolower(trim($query));

        // Simple AI query processing
        if (strpos($query, 'school') !== false && strpos($query, 'expire') !== false) {
            $expiringSchools = School::whereHas('paymentPlans', function($q) {
                $q->where('expires_at', '<=', now()->addDays(10))
                  ->where('expires_at', '>', now());
            })->with('paymentPlans')->get();

            return response()->json([
                'type' => 'expiring_schools',
                'data' => $expiringSchools,
                'message' => "Found {$expiringSchools->count()} schools with plans expiring in the next 10 days."
            ]);
        }

        if (strpos($query, 'revenue') !== false && strpos($query, 'trend') !== false) {
            $quarterlyRevenue = PaymentTransaction::where('status', 'success')
                ->where('created_at', '>=', now()->subMonths(3))
                ->sum('amount');

            $previousQuarterRevenue = PaymentTransaction::where('status', 'success')
                ->whereBetween('created_at', [now()->subMonths(6), now()->subMonths(3)])
                ->sum('amount');

            $growth = $previousQuarterRevenue > 0 ? 
                (($quarterlyRevenue - $previousQuarterRevenue) / $previousQuarterRevenue) * 100 : 0;

            return response()->json([
                'type' => 'revenue_trend',
                'data' => [
                    'current_quarter' => $quarterlyRevenue,
                    'previous_quarter' => $previousQuarterRevenue,
                    'growth' => round($growth, 2)
                ],
                'message' => "Revenue trend for last quarter shows " . round($growth, 2) . "% growth compared to previous quarter."
            ]);
        }

        if (strpos($query, 'activity') !== false || strpos($query, 'active') !== false) {
            $activeSchools = School::withCount(['users' => function($query) {
                $query->where('last_login_at', '>=', now()->subDays(7));
            }])
            ->having('users_count', '>', 0)
            ->orderBy('users_count', 'desc')
            ->limit(5)
            ->get();

            return response()->json([
                'type' => 'active_schools',
                'data' => $activeSchools,
                'message' => "Top 5 schools with highest user activity in the last 7 days."
            ]);
        }

        // Default response
        return response()->json([
            'type' => 'general',
            'message' => "I understand you're asking about: '{$query}'. I can help you with school management, revenue analysis, user activity, and system insights. Please try rephrasing your question or ask about specific data."
        ]);
    }

    /**
     * Get schools expiring soon
     */
    public function getExpiringSchools()
    {
        $expiringSchools = School::whereHas('paymentPlans', function($q) {
            $q->where('expires_at', '<=', now()->addDays(30))
              ->where('expires_at', '>', now());
        })->with(['paymentPlans' => function($q) {
            $q->where('expires_at', '<=', now()->addDays(30))
              ->where('expires_at', '>', now());
        }])->get();

        return response()->json($expiringSchools);
    }

    /**
     * Get revenue forecasting data
     */
    public function getRevenueForecast()
    {
        $months = [];
        $revenue = [];
        $predictions = [];

        // Get last 6 months data
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M Y');
            
            $monthRevenue = PaymentTransaction::where('status', 'success')
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->sum('amount');
            
            $revenue[] = $monthRevenue;
        }

        // Generate predictions for next 3 months
        $lastRevenue = end($revenue);
        for ($i = 1; $i <= 3; $i++) {
            $futureDate = now()->addMonths($i);
            $months[] = $futureDate->format('M Y');
            $predictions[] = $lastRevenue * (1 + (0.08 * $i)); // 8% growth prediction
        }

        return response()->json([
            'months' => $months,
            'revenue' => $revenue,
            'predictions' => $predictions
        ]);
    }

    /**
     * Get churn prediction data
     */
    public function getChurnPrediction()
    {
        $churnRisk = School::where('status', true)
            ->whereDoesntHave('users', function($query) {
                $query->where('last_login_at', '>=', now()->subDays(14));
            })
            ->with(['paymentPlans', 'users'])
            ->get();

        return response()->json([
            'churn_risk_count' => $churnRisk->count(),
            'schools' => $churnRisk
        ]);
    }
}
