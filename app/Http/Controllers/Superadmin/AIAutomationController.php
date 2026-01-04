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

class AIAutomationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'checkrole:superadmin']);
    }

    /**
     * Display AI & Automation Tools dashboard
     */
    public function index()
    {
        return view('superadmin.ai-automation.index');
    }

    /**
     * AI Report Generator
     */
    public function reportGenerator(Request $request)
    {
        $query = $request->get('query', '');
        
        if (empty($query)) {
            return view('superadmin.ai-automation.report-generator');
        }

        // Process natural language query
        $result = $this->processAIQuery($query);
        
        return view('superadmin.ai-automation.report-generator', compact('result', 'query'));
    }

    /**
     * AI Chatbot interface
     */
    public function chatbot(Request $request)
    {
        if ($request->isMethod('post')) {
            $query = $request->get('query', '');
            $response = $this->processAIQuery($query);
            
            return response()->json($response);
        }

        return view('superadmin.ai-automation.chatbot');
    }

    /**
     * AI Fraud Detection
     */
    public function fraudDetection()
    {
        $suspiciousActivities = $this->detectSuspiciousActivities();
        
        return view('superadmin.ai-automation.fraud-detection', compact('suspiciousActivities'));
    }

    /**
     * Process AI queries
     */
    private function processAIQuery($query)
    {
        $query = strtolower(trim($query));
        
        // School-related queries
        if (strpos($query, 'school') !== false) {
            if (strpos($query, 'expire') !== false || strpos($query, 'expiring') !== false) {
                return $this->getExpiringSchoolsReport($query);
            }
            
            if (strpos($query, 'active') !== false || strpos($query, 'activity') !== false) {
                return $this->getActiveSchoolsReport($query);
            }
            
            if (strpos($query, 'revenue') !== false || strpos($query, 'income') !== false) {
                return $this->getSchoolRevenueReport($query);
            }
        }
        
        // Revenue-related queries
        if (strpos($query, 'revenue') !== false || strpos($query, 'income') !== false) {
            return $this->getRevenueReport($query);
        }
        
        // User-related queries
        if (strpos($query, 'user') !== false || strpos($query, 'login') !== false) {
            return $this->getUserActivityReport($query);
        }
        
        // Plan-related queries
        if (strpos($query, 'plan') !== false || strpos($query, 'subscription') !== false) {
            return $this->getPlanAnalysisReport($query);
        }
        
        // Default response
        return [
            'type' => 'general',
            'title' => 'AI Assistant Response',
            'message' => "I understand you're asking about: '{$query}'. I can help you analyze schools, revenue, users, and plans. Please try rephrasing your question with more specific terms.",
            'data' => []
        ];
    }

    /**
     * Get expiring schools report
     */
    private function getExpiringSchoolsReport($query)
    {
        $days = 30; // Default to 30 days
        
        // Extract number of days from query
        if (preg_match('/(\d+)\s*days?/', $query, $matches)) {
            $days = (int)$matches[1];
        }
        
        $expiringSchools = School::whereHas('paymentPlans', function($q) use ($days) {
            $q->where('expires_at', '<=', now()->addDays($days))
              ->where('expires_at', '>', now());
        })->with(['paymentPlans' => function($q) use ($days) {
            $q->where('expires_at', '<=', now()->addDays($days))
              ->where('expires_at', '>', now());
        }])->get();

        return [
            'type' => 'expiring_schools',
            'title' => "Schools with Plans Expiring in Next {$days} Days",
            'message' => "Found {$expiringSchools->count()} schools with plans expiring in the next {$days} days.",
            'data' => $expiringSchools,
            'chart_data' => $this->generateExpiringSchoolsChart($expiringSchools)
        ];
    }

    /**
     * Get active schools report
     */
    private function getActiveSchoolsReport($query)
    {
        $days = 7; // Default to 7 days
        
        if (preg_match('/(\d+)\s*days?/', $query, $matches)) {
            $days = (int)$matches[1];
        }
        
        $activeSchools = School::withCount(['users' => function($query) use ($days) {
            $query->where('last_login_at', '>=', now()->subDays($days));
        }])
        ->having('users_count', '>', 0)
        ->orderBy('users_count', 'desc')
        ->limit(10)
        ->get();

        return [
            'type' => 'active_schools',
            'title' => "Most Active Schools (Last {$days} Days)",
            'message' => "Top 10 schools with highest user activity in the last {$days} days.",
            'data' => $activeSchools,
            'chart_data' => $this->generateActiveSchoolsChart($activeSchools)
        ];
    }

    /**
     * Get revenue report
     */
    private function getRevenueReport($query)
    {
        $period = 'month';
        
        if (strpos($query, 'quarter') !== false) {
            $period = 'quarter';
        } elseif (strpos($query, 'year') !== false) {
            $period = 'year';
        }
        
        $revenueData = $this->calculateRevenueData($period);
        
        return [
            'type' => 'revenue',
            'title' => "Revenue Analysis - " . ucfirst($period),
            'message' => "Revenue analysis for the current {$period}.",
            'data' => $revenueData,
            'chart_data' => $this->generateRevenueChart($revenueData, $period)
        ];
    }

    /**
     * Get user activity report
     */
    private function getUserActivityReport($query)
    {
        $days = 30;
        
        if (preg_match('/(\d+)\s*days?/', $query, $matches)) {
            $days = (int)$matches[1];
        }
        
        $userStats = [
            'total_users' => User::count(),
            'active_users' => User::where('last_login_at', '>=', now()->subDays($days))->count(),
            'new_users' => User::where('created_at', '>=', now()->subDays($days))->count(),
            'inactive_users' => User::where('last_login_at', '<', now()->subDays($days))->count()
        ];
        
        $dailyActivity = User::select(
            DB::raw('DATE(last_login_at) as date'),
            DB::raw('COUNT(*) as count')
        )
        ->where('last_login_at', '>=', now()->subDays($days))
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        return [
            'type' => 'user_activity',
            'title' => "User Activity Report - Last {$days} Days",
            'message' => "User activity analysis for the last {$days} days.",
            'data' => $userStats,
            'chart_data' => $dailyActivity
        ];
    }

    /**
     * Get plan analysis report
     */
    private function getPlanAnalysisReport($query)
    {
        $planStats = PaymentPlan::withCount(['schools', 'transactions'])
            ->get()
            ->map(function($plan) {
                $revenue = PaymentTransaction::where('plan_id', $plan->id)
                    ->where('status', 'success')
                    ->sum('amount');
                
                return [
                    'name' => $plan->name,
                    'price' => $plan->price,
                    'schools_count' => $plan->schools_count,
                    'transactions_count' => $plan->transactions_count,
                    'revenue' => $revenue
                ];
            });

        return [
            'type' => 'plan_analysis',
            'title' => 'Plan Analysis Report',
            'message' => 'Analysis of all subscription plans and their performance.',
            'data' => $planStats,
            'chart_data' => $this->generatePlanAnalysisChart($planStats)
        ];
    }

    /**
     * Detect suspicious activities
     */
    private function detectSuspiciousActivities()
    {
        $suspiciousActivities = [];

        // Multiple logins from different IPs
        $multipleIPs = User::select('email', DB::raw('COUNT(DISTINCT last_login_ip) as ip_count'))
            ->where('last_login_at', '>=', now()->subDays(7))
            ->groupBy('email')
            ->having('ip_count', '>', 3)
            ->get();

        foreach ($multipleIPs as $user) {
            $suspiciousActivities[] = [
                'type' => 'multiple_ips',
                'severity' => 'medium',
                'description' => "User {$user->email} logged in from {$user->ip_count} different IPs in the last 7 days",
                'timestamp' => now()
            ];
        }

        // Rapid registrations
        $rapidRegistrations = User::select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->having('count', '>', 10)
            ->get();

        foreach ($rapidRegistrations as $reg) {
            $suspiciousActivities[] = [
                'type' => 'rapid_registrations',
                'severity' => 'high',
                'description' => "{$reg->count} new registrations on {$reg->date} - possible bot activity",
                'timestamp' => now()
            ];
        }

        // Failed payment attempts
        $failedPayments = PaymentTransaction::where('status', 'failed')
            ->where('created_at', '>=', now()->subDays(7))
            ->count();

        if ($failedPayments > 50) {
            $suspiciousActivities[] = [
                'type' => 'high_failed_payments',
                'severity' => 'medium',
                'description' => "{$failedPayments} failed payment attempts in the last 7 days",
                'timestamp' => now()
            ];
        }

        return $suspiciousActivities;
    }

    /**
     * Calculate revenue data
     */
    private function calculateRevenueData($period)
    {
        switch ($period) {
            case 'quarter':
                $startDate = now()->startOfQuarter();
                $endDate = now()->endOfQuarter();
                break;
            case 'year':
                $startDate = now()->startOfYear();
                $endDate = now()->endOfYear();
                break;
            default:
                $startDate = now()->startOfMonth();
                $endDate = now()->endOfMonth();
        }

        $revenue = PaymentTransaction::where('status', 'success')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');

        $previousPeriod = PaymentTransaction::where('status', 'success')
            ->whereBetween('created_at', [
                $startDate->copy()->sub($period === 'quarter' ? 3 : ($period === 'year' ? 12 : 1), $period === 'quarter' ? 'months' : ($period === 'year' ? 'months' : 'month')),
                $startDate->copy()->subDay()
            ])
            ->sum('amount');

        $growth = $previousPeriod > 0 ? (($revenue - $previousPeriod) / $previousPeriod) * 100 : 0;

        return [
            'current_period' => $revenue,
            'previous_period' => $previousPeriod,
            'growth' => round($growth, 2),
            'period' => $period
        ];
    }

    /**
     * Generate charts and visualizations
     */
    private function generateExpiringSchoolsChart($schools)
    {
        return [
            'labels' => $schools->pluck('name')->toArray(),
            'data' => $schools->map(function($school) {
                return $school->paymentPlans->count();
            })->toArray()
        ];
    }

    private function generateActiveSchoolsChart($schools)
    {
        return [
            'labels' => $schools->pluck('name')->toArray(),
            'data' => $schools->pluck('users_count')->toArray()
        ];
    }

    private function generateRevenueChart($data, $period)
    {
        // This would generate chart data for revenue visualization
        return [
            'current' => $data['current_period'],
            'previous' => $data['previous_period'],
            'growth' => $data['growth']
        ];
    }

    private function generatePlanAnalysisChart($plans)
    {
        return [
            'labels' => $plans->pluck('name')->toArray(),
            'revenue' => $plans->pluck('revenue')->toArray(),
            'schools' => $plans->pluck('schools_count')->toArray()
        ];
    }
}
