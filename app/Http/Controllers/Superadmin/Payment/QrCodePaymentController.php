<?php

namespace App\Http\Controllers\Superadmin\Payment;

use App\Http\Controllers\Controller;
use App\Models\QrCodePayment;
use App\Models\QrCodePricing;
use App\Models\School;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class QrCodePaymentController extends Controller
{
    /**
     * Display QR code payments management
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = QrCodePayment::with(['school', 'admin'])
                ->select('qr_code_payments.*')
                ->latest();

            // Search functionality
            if ($request->has('search') && !empty($request->search)) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('payment_id', 'like', "%{$search}%")
                      ->orWhereHas('school', function($schoolQuery) use ($search) {
                          $schoolQuery->where('name', 'like', "%{$search}%");
                      })
                      ->orWhereHas('admin', function($adminQuery) use ($search) {
                          $adminQuery->where('name', 'like', "%{$search}%");
                      });
                });
            }

            // Filter by status
            if ($request->has('status') && !empty($request->status)) {
                $query->where('status', $request->status);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('school_name', function ($payment) {
                    return $payment->school ? $payment->school->name : 'N/A';
                })
                ->addColumn('admin_name', function ($payment) {
                    return $payment->admin ? $payment->admin->name : 'N/A';
                })
                ->addColumn('amount_formatted', function ($payment) {
                    return '₹' . number_format($payment->amount, 2);
                })
                ->addColumn('status_badge', function ($payment) {
                    $badgeClass = match($payment->status) {
                        'completed' => 'bg-success',
                        'pending' => 'bg-warning',
                        'failed' => 'bg-danger',
                        'cancelled' => 'bg-secondary',
                        'refunded' => 'bg-info',
                        default => 'bg-secondary'
                    };
                    return '<span class="badge ' . $badgeClass . '">' . ucfirst($payment->status) . '</span>';
                })
                ->addColumn('payment_date', function ($payment) {
                    return $payment->paid_at ? $payment->paid_at->format('d M Y H:i A') : '-';
                })
                ->addColumn('actions', function ($payment) {
                    $viewUrl = route('superadmin.payment.qr-code-payments.show', $payment);
                    $refundUrl = route('superadmin.payment.qr-code-payments.refund', $payment);
                    
                    $actions = '<div class="d-flex justify-content-center align-items-center">';
                    $actions .= '<a href="'.$viewUrl.'" class="btn btn-sm btn-gradient-primary btn-icon waves-effect waves-float waves-light" data-bs-toggle="tooltip" data-bs-placement="top" title="View Details">
                                    <i data-feather="eye"></i>
                                </a>';
                    
                    if ($payment->status === 'completed') {
                        $actions .= '<button type="button" class="btn btn-sm btn-gradient-warning btn-icon waves-effect waves-float waves-light confirm-refund" data-bs-toggle="tooltip" data-bs-placement="top" title="Refund" data-id="'.$payment->id.'" data-action="'.$refundUrl.'">
                                        <i data-feather="refresh-cw"></i>
                                    </button>';
                    }
                    
                    $actions .= '</div>';
                    return $actions;
                })
                ->rawColumns(['status_badge', 'actions'])
                ->make(true);
        }

        return view('superadmin.payment.qr-code-payments.index');
    }

    /**
     * Show payment details
     */
    public function show(QrCodePayment $payment)
    {
        $payment->load(['school', 'admin']);
        return view('superadmin.payment.qr-code-payments.show', compact('payment'));
    }

    /**
     * Process refund
     */
    public function refund(Request $request, QrCodePayment $payment)
    {
        if ($payment->status !== 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'Only completed payments can be refunded.'
            ], 400);
        }

        $request->validate([
            'refund_reason' => 'required|string|max:500'
        ]);

        // Update payment status
        $payment->update([
            'status' => 'refunded',
            'failure_reason' => $request->refund_reason,
            'metadata' => array_merge($payment->metadata ?? [], [
                'refunded_at' => now()->toISOString(),
                'refunded_by' => auth()->id(),
                'refund_reason' => $request->refund_reason
            ])
        ]);

        // Reduce school's QR code limit
        $payment->school->update([
            'qr_code_limit' => max(1, $payment->school->qr_code_limit - $payment->qr_codes_purchased)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payment refunded successfully. School QR code limit has been reduced.'
        ]);
    }

    /**
     * Get payment statistics
     */
    public function statistics()
    {
        $totalPayments = QrCodePayment::count();
        $completedPayments = QrCodePayment::where('status', 'completed')->count();
        $pendingPayments = QrCodePayment::where('status', 'pending')->count();
        $failedPayments = QrCodePayment::where('status', 'failed')->count();
        $totalRevenue = QrCodePayment::where('status', 'completed')->sum('amount');
        $totalQrCodesSold = QrCodePayment::where('status', 'completed')->sum('qr_codes_purchased');

        return response()->json([
            'total_payments' => $totalPayments,
            'completed_payments' => $completedPayments,
            'pending_payments' => $pendingPayments,
            'failed_payments' => $failedPayments,
            'total_revenue' => $totalRevenue,
            'total_qr_codes_sold' => $totalQrCodesSold
        ]);
    }

    /**
     * Manage pricing tiers
     */
    public function pricing()
    {
        $pricingTiers = QrCodePricing::ordered()->get();
        return view('superadmin.payment.qr-code-payments.pricing', compact('pricingTiers'));
    }

    /**
     * Update pricing tier
     */
    public function updatePricing(Request $request, QrCodePricing $pricing)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'min_qr_codes' => 'required|integer|min:1',
            'max_qr_codes' => 'nullable|integer|min:1',
            'price_per_qr_code' => 'required|numeric|min:0',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'is_active' => 'boolean'
        ]);

        $pricing->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Pricing tier updated successfully.'
        ]);
    }

    /**
     * Create new pricing tier
     */
    public function createPricing(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'min_qr_codes' => 'required|integer|min:1',
            'max_qr_codes' => 'nullable|integer|min:1',
            'price_per_qr_code' => 'required|numeric|min:0',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'is_active' => 'boolean'
        ]);

        $pricing = QrCodePricing::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Pricing tier created successfully.',
            'pricing' => $pricing
        ]);
    }
}