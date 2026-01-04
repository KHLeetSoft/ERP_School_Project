<?php

namespace App\Http\Controllers\Superadmin\Payment;

use App\Http\Controllers\Controller;
use App\Models\PaymentTransaction;
use App\Models\PaymentGateway;
use App\Models\PaymentPlan;
use App\Models\Invoice;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display payment reports dashboard
     */
    public function index(Request $request)
    {
        $dateRange = $this->getDateRange($request);
        
        // Get statistics
        $stats = $this->getPaymentStats($dateRange);
        
        // Get top performing gateways
        $topGateways = $this->getTopGateways($dateRange);
        
        // Get top performing plans
        $topPlans = $this->getTopPlans($dateRange);
        
        // Get school performance
        $schoolPerformance = $this->getSchoolPerformance($dateRange);
        
        // Get monthly revenue chart data
        $monthlyRevenue = $this->getMonthlyRevenue($dateRange);
        
        // Get payment status distribution
        $statusDistribution = $this->getStatusDistribution($dateRange);

        return view('superadmin.payment.reports.index', compact(
            'stats',
            'topGateways',
            'topPlans',
            'schoolPerformance',
            'monthlyRevenue',
            'statusDistribution',
            'dateRange'
        ));
    }

    /**
     * Detailed transactions report
     */
    public function transactions(Request $request)
    {
        $query = PaymentTransaction::with(['plan', 'gateway', 'school', 'user', 'invoice'])
            ->orderBy('created_at', 'desc');

        // Apply filters
        if ($request->has('school_id') && $request->school_id) {
            $query->where('school_id', $request->school_id);
        }

        if ($request->has('gateway_id') && $request->gateway_id) {
            $query->where('gateway_id', $request->gateway_id);
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $transactions = $query->paginate(50);
        
        $schools = School::select('id', 'name')->get();
        $gateways = PaymentGateway::select('id', 'name')->get();

        return view('superadmin.payment.reports.transactions', compact(
            'transactions',
            'schools',
            'gateways'
        ));
    }

    /**
     * Revenue analytics report
     */
    public function revenue(Request $request)
    {
        $dateRange = $this->getDateRange($request);
        
        // Daily revenue
        $dailyRevenue = $this->getDailyRevenue($dateRange);
        
        // Gateway revenue breakdown
        $gatewayRevenue = $this->getGatewayRevenue($dateRange);
        
        // Plan revenue breakdown
        $planRevenue = $this->getPlanRevenue($dateRange);
        
        // School revenue breakdown
        $schoolRevenue = $this->getSchoolRevenue($dateRange);
        
        // Commission analysis
        $commissionAnalysis = $this->getCommissionAnalysis($dateRange);

        return view('superadmin.payment.reports.revenue', compact(
            'dailyRevenue',
            'gatewayRevenue',
            'planRevenue',
            'schoolRevenue',
            'commissionAnalysis',
            'dateRange'
        ));
    }

    /**
     * Export reports
     */
    public function export(Request $request)
    {
        $format = $request->get('format', 'excel');
        $type = $request->get('type', 'transactions');
        $dateRange = $this->getDateRange($request);

        switch ($type) {
            case 'transactions':
                return $this->exportTransactions($format, $dateRange);
            case 'revenue':
                return $this->exportRevenue($format, $dateRange);
            case 'gateways':
                return $this->exportGateways($format, $dateRange);
            default:
                return redirect()->back()->with('error', 'Invalid export type.');
        }
    }

    /**
     * Get payment statistics
     */
    private function getPaymentStats($dateRange)
    {
        $query = PaymentTransaction::whereBetween('created_at', $dateRange);

        return [
            'total_transactions' => $query->count(),
            'successful_transactions' => $query->where('status', 'success')->count(),
            'failed_transactions' => $query->where('status', 'failed')->count(),
            'pending_transactions' => $query->where('status', 'pending')->count(),
            'total_revenue' => $query->where('status', 'success')->sum('amount'),
            'average_transaction' => $query->where('status', 'success')->avg('amount'),
            'success_rate' => $query->count() > 0 ? 
                round(($query->where('status', 'success')->count() / $query->count()) * 100, 2) : 0
        ];
    }

    /**
     * Get top performing gateways
     */
    private function getTopGateways($dateRange)
    {
        return PaymentGateway::leftJoin('payment_transactions', 'payment_gateways.id', '=', 'payment_transactions.gateway_id')
            ->whereBetween('payment_transactions.created_at', $dateRange)
            ->where('payment_transactions.status', 'success')
            ->select(
                'payment_gateways.id',
                'payment_gateways.name',
                'payment_gateways.provider',
                'payment_gateways.is_active',
                'payment_gateways.created_at',
                'payment_gateways.updated_at',
                DB::raw('COUNT(payment_transactions.id) as transactions_count'),
                DB::raw('SUM(payment_transactions.amount) as transactions_sum_amount')
            )
            ->groupBy('payment_gateways.id', 'payment_gateways.name', 'payment_gateways.provider', 'payment_gateways.is_active', 'payment_gateways.created_at', 'payment_gateways.updated_at')
            ->orderBy('transactions_sum_amount', 'desc')
            ->limit(5)
            ->get();
    }

    /**
     * Get top performing plans
     */
    private function getTopPlans($dateRange)
    {
        return PaymentPlan::leftJoin('payment_transactions', 'payment_plans.id', '=', 'payment_transactions.plan_id')
            ->whereBetween('payment_transactions.created_at', $dateRange)
            ->where('payment_transactions.status', 'success')
            ->select(
                'payment_plans.id',
                'payment_plans.name',
                'payment_plans.description',
                'payment_plans.price',
                'payment_plans.price_type',
                'payment_plans.billing_cycle',
                'payment_plans.is_active',
                'payment_plans.created_at',
                'payment_plans.updated_at',
                DB::raw('COUNT(payment_transactions.id) as transactions_count'),
                DB::raw('SUM(payment_transactions.amount) as transactions_sum_amount')
            )
            ->groupBy('payment_plans.id', 'payment_plans.name', 'payment_plans.description', 'payment_plans.price', 'payment_plans.price_type', 'payment_plans.billing_cycle', 'payment_plans.is_active', 'payment_plans.created_at', 'payment_plans.updated_at')
            ->orderBy('transactions_sum_amount', 'desc')
            ->limit(5)
            ->get();
    }

    /**
     * Get school performance
     */
    private function getSchoolPerformance($dateRange)
    {
        return School::leftJoin('payment_transactions', 'schools.id', '=', 'payment_transactions.school_id')
            ->whereBetween('payment_transactions.created_at', $dateRange)
            ->where('payment_transactions.status', 'success')
            ->select(
                'schools.id',
                'schools.name',
                'schools.email',
                'schools.phone',
                'schools.address',
                'schools.created_at',
                'schools.updated_at',
                DB::raw('COUNT(payment_transactions.id) as transactions_count'),
                DB::raw('SUM(payment_transactions.amount) as transactions_sum_amount')
            )
            ->groupBy('schools.id', 'schools.name', 'schools.email', 'schools.phone', 'schools.address', 'schools.created_at', 'schools.updated_at')
            ->orderBy('transactions_sum_amount', 'desc')
            ->limit(10)
            ->get();
    }

    /**
     * Get monthly revenue data for charts
     */
    private function getMonthlyRevenue($dateRange)
    {
        return PaymentTransaction::whereBetween('created_at', $dateRange)
            ->where('status', 'success')
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('SUM(amount) as revenue'),
                DB::raw('COUNT(*) as transactions')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    }

    /**
     * Get payment status distribution
     */
    private function getStatusDistribution($dateRange)
    {
        return PaymentTransaction::whereBetween('created_at', $dateRange)
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();
    }

    /**
     * Get daily revenue data
     */
    private function getDailyRevenue($dateRange)
    {
        return PaymentTransaction::whereBetween('created_at', $dateRange)
            ->where('status', 'success')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(amount) as revenue'),
                DB::raw('COUNT(*) as transactions')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    /**
     * Get gateway revenue breakdown
     */
    private function getGatewayRevenue($dateRange)
    {
        return PaymentGateway::leftJoin('payment_transactions', 'payment_gateways.id', '=', 'payment_transactions.gateway_id')
            ->whereBetween('payment_transactions.created_at', $dateRange)
            ->where('payment_transactions.status', 'success')
            ->select(
                'payment_gateways.id',
                'payment_gateways.name',
                'payment_gateways.provider',
                'payment_gateways.is_active',
                'payment_gateways.created_at',
                'payment_gateways.updated_at',
                DB::raw('SUM(payment_transactions.amount) as transactions_sum_amount'),
                DB::raw('COUNT(payment_transactions.id) as transactions_count')
            )
            ->groupBy('payment_gateways.id', 'payment_gateways.name', 'payment_gateways.provider', 'payment_gateways.is_active', 'payment_gateways.created_at', 'payment_gateways.updated_at')
            ->get();
    }

    /**
     * Get plan revenue breakdown
     */
    private function getPlanRevenue($dateRange)
    {
        return PaymentPlan::leftJoin('payment_transactions', 'payment_plans.id', '=', 'payment_transactions.plan_id')
            ->whereBetween('payment_transactions.created_at', $dateRange)
            ->where('payment_transactions.status', 'success')
            ->select(
                'payment_plans.id',
                'payment_plans.name',
                'payment_plans.description',
                'payment_plans.price',
                'payment_plans.price_type',
                'payment_plans.billing_cycle',
                'payment_plans.is_active',
                'payment_plans.created_at',
                'payment_plans.updated_at',
                DB::raw('SUM(payment_transactions.amount) as transactions_sum_amount'),
                DB::raw('COUNT(payment_transactions.id) as transactions_count')
            )
            ->groupBy('payment_plans.id', 'payment_plans.name', 'payment_plans.description', 'payment_plans.price', 'payment_plans.price_type', 'payment_plans.billing_cycle', 'payment_plans.is_active', 'payment_plans.created_at', 'payment_plans.updated_at')
            ->get();
    }

    /**
     * Get school revenue breakdown
     */
    private function getSchoolRevenue($dateRange)
    {
        return School::leftJoin('payment_transactions', 'schools.id', '=', 'payment_transactions.school_id')
            ->whereBetween('payment_transactions.created_at', $dateRange)
            ->where('payment_transactions.status', 'success')
            ->select(
                'schools.id',
                'schools.name',
                'schools.email',
                'schools.phone',
                'schools.address',
                'schools.created_at',
                'schools.updated_at',
                DB::raw('SUM(payment_transactions.amount) as transactions_sum_amount'),
                DB::raw('COUNT(payment_transactions.id) as transactions_count')
            )
            ->groupBy('schools.id', 'schools.name', 'schools.email', 'schools.phone', 'schools.address', 'schools.created_at', 'schools.updated_at')
            ->orderBy('transactions_sum_amount', 'desc')
            ->get();
    }

    /**
     * Get commission analysis
     */
    private function getCommissionAnalysis($dateRange)
    {
        return PaymentGateway::leftJoin('payment_transactions', 'payment_gateways.id', '=', 'payment_transactions.gateway_id')
            ->whereBetween('payment_transactions.created_at', $dateRange)
            ->where('payment_transactions.status', 'success')
            ->select(
                'payment_gateways.id',
                'payment_gateways.name as gateway',
                'payment_gateways.commission_rate',
                DB::raw('SUM(payment_transactions.amount) as total_amount')
            )
            ->groupBy('payment_gateways.id', 'payment_gateways.name', 'payment_gateways.commission_rate')
            ->get()
            ->map(function($item) {
                $commission = $item->total_amount * ($item->commission_rate / 100);
                
                return [
                    'gateway' => $item->gateway,
                    'total_amount' => $item->total_amount,
                    'commission_rate' => $item->commission_rate,
                    'commission_amount' => $commission,
                    'net_amount' => $item->total_amount - $commission
                ];
            });
    }

    /**
     * Get date range from request
     */
    private function getDateRange($request)
    {
        $dateFrom = $request->get('date_from', now()->subDays(30)->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));
        
        return [
            Carbon::parse($dateFrom)->startOfDay(),
            Carbon::parse($dateTo)->endOfDay()
        ];
    }

    /**
     * Export transactions
     */
    private function exportTransactions($format, $dateRange)
    {
        // Implementation for exporting transactions
        // This would generate Excel/CSV files
        return response()->json(['message' => 'Export functionality will be implemented']);
    }

    /**
     * Export revenue data
     */
    private function exportRevenue($format, $dateRange)
    {
        // Implementation for exporting revenue data
        return response()->json(['message' => 'Export functionality will be implemented']);
    }

    /**
     * Export gateway data
     */
    private function exportGateways($format, $dateRange)
    {
        // Implementation for exporting gateway data
        return response()->json(['message' => 'Export functionality will be implemented']);
    }
}