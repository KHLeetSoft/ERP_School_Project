<?php

namespace App\Http\Controllers\Admin\Payment;

use App\Http\Controllers\Controller;
use App\Models\QrCodePayment;
use App\Models\QrCodePricing;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class QrCodePaymentController extends Controller
{
    /**
     * Display QR code payment page
     */
    public function index()
    {
        $school = Auth::user()->managedSchool;
        
        if (!$school) {
            return redirect()->route('admin.dashboard')->with('error', 'No school assigned to your account.');
        }

        $pricingTiers = QrCodePricing::getAvailableTiers();
        $recentPayments = QrCodePayment::where('school_id', $school->id)
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.payment.qr-code-payment.index', compact('school', 'pricingTiers', 'recentPayments'));
    }

    /**
     * Calculate pricing for QR codes
     */
    public function calculatePricing(Request $request)
    {
        $request->validate([
            'qr_codes_count' => 'required|integer|min:1|max:1000'
        ]);

        $qrCodesCount = $request->qr_codes_count;
        $pricingTier = QrCodePricing::findTierForQrCodes($qrCodesCount);

        if (!$pricingTier) {
            return response()->json([
                'success' => false,
                'message' => 'No pricing tier available for the requested number of QR codes.'
            ], 400);
        }

        $totalPrice = $pricingTier->calculateTotalPrice($qrCodesCount);
        $discountAmount = $qrCodesCount * $pricingTier->price_per_qr_code - $totalPrice;

        return response()->json([
            'success' => true,
            'pricing' => [
                'tier_name' => $pricingTier->name,
                'qr_codes_count' => $qrCodesCount,
                'price_per_qr_code' => $pricingTier->price_per_qr_code,
                'discount_percentage' => $pricingTier->discount_percentage,
                'discount_amount' => $discountAmount,
                'total_price' => $totalPrice,
                'currency' => 'INR'
            ]
        ]);
    }

    /**
     * Create payment intent
     */
    public function createPayment(Request $request)
    {
        $school = Auth::user()->managedSchool;
        
        if (!$school) {
            return response()->json(['error' => 'No school assigned to your account.'], 400);
        }

        $request->validate([
            'qr_codes_count' => 'required|integer|min:1|max:1000',
            'payment_method' => 'required|in:razorpay,stripe,paypal,upi,bank_transfer,cash'
        ]);

        $qrCodesCount = $request->qr_codes_count;
        $pricingTier = QrCodePricing::findTierForQrCodes($qrCodesCount);

        if (!$pricingTier) {
            return response()->json([
                'success' => false,
                'message' => 'No pricing tier available for the requested number of QR codes.'
            ], 400);
        }

        $totalPrice = $pricingTier->calculateTotalPrice($qrCodesCount);

        // Create payment record
        $payment = QrCodePayment::create([
            'school_id' => $school->id,
            'admin_id' => Auth::id(),
            'payment_id' => QrCodePayment::generatePaymentId(),
            'amount' => $totalPrice,
            'currency' => 'INR',
            'payment_method' => $request->payment_method,
            'status' => 'pending',
            'qr_codes_purchased' => $qrCodesCount,
            'price_per_qr_code' => $pricingTier->price_per_qr_code,
            'description' => "Purchase of {$qrCodesCount} QR codes for {$school->name}",
            'expires_at' => now()->addHours(24), // Payment expires in 24 hours
            'metadata' => [
                'pricing_tier_id' => $pricingTier->id,
                'pricing_tier_name' => $pricingTier->name,
                'discount_percentage' => $pricingTier->discount_percentage
            ]
        ]);

        // For demo purposes, we'll simulate payment processing
        // In real implementation, integrate with payment gateways
        if ($request->payment_method === 'cash') {
            // For cash payments, mark as completed immediately
            $payment->markAsCompleted([
                'transaction_id' => 'CASH_' . time(),
                'payment_id' => $payment->payment_id,
                'method' => 'cash'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment completed successfully! Your QR code limit has been increased.',
                'payment_id' => $payment->payment_id,
                'redirect_url' => route('admin.payment.qr-code-payment.success', $payment)
            ]);
        }

        // For online payments, redirect to payment gateway
        return response()->json([
            'success' => true,
            'payment_id' => $payment->payment_id,
            'amount' => $totalPrice,
            'currency' => 'INR',
            'redirect_url' => $this->getPaymentGatewayUrl($payment, $request->payment_method)
        ]);
    }

    /**
     * Handle payment success callback
     */
    public function paymentSuccess(QrCodePayment $payment)
    {
        if ($payment->admin_id !== Auth::id()) {
            abort(403, 'Unauthorized access to payment.');
        }

        return view('admin.payment.qr-code-payment.success', compact('payment'));
    }

    /**
     * Handle payment failure callback
     */
    public function paymentFailed(QrCodePayment $payment)
    {
        if ($payment->admin_id !== Auth::id()) {
            abort(403, 'Unauthorized access to payment.');
        }

        return view('admin.payment.qr-code-payment.failed', compact('payment'));
    }

    /**
     * Payment history
     */
    public function history()
    {
        $school = Auth::user()->managedSchool;
        
        if (!$school) {
            return redirect()->route('admin.dashboard')->with('error', 'No school assigned to your account.');
        }

        $payments = QrCodePayment::where('school_id', $school->id)
            ->latest()
            ->paginate(15);

        return view('admin.payment.qr-code-payment.history', compact('school', 'payments'));
    }

    /**
     * Get payment gateway URL
     */
    private function getPaymentGatewayUrl(QrCodePayment $payment, $method)
    {
        // This is a placeholder - integrate with actual payment gateways
        switch ($method) {
            case 'razorpay':
                return route('admin.payment.qr-code-payment.razorpay', $payment);
            case 'stripe':
                return route('admin.payment.qr-code-payment.stripe', $payment);
            case 'paypal':
                return route('admin.payment.qr-code-payment.paypal', $payment);
            case 'upi':
                return route('admin.payment.qr-code-payment.upi', $payment);
            case 'bank_transfer':
                return route('admin.payment.qr-code-payment.bank-transfer', $payment);
            default:
                return route('admin.payment.qr-code-payment.failed', $payment);
        }
    }

    /**
     * Simulate payment completion (for demo purposes)
     */
    public function simulatePayment(QrCodePayment $payment)
    {
        if ($payment->admin_id !== Auth::id()) {
            abort(403, 'Unauthorized access to payment.');
        }

        if ($payment->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Payment is not in pending status.'
            ], 400);
        }

        // Simulate successful payment
        $payment->markAsCompleted([
            'transaction_id' => 'SIM_' . time(),
            'payment_id' => $payment->payment_id,
            'method' => 'simulation'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payment completed successfully! Your QR code limit has been increased.',
            'redirect_url' => route('admin.payment.qr-code-payment.success', $payment)
        ]);
    }
}