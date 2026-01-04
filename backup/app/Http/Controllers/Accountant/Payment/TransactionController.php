<?php

namespace App\Http\Controllers\Accountant\Payment;

use App\Http\Controllers\Controller;
use App\Models\PaymentGatewaySetting;
use App\Models\QrCodeSetting;
use App\Models\StudentPayment;
use App\Models\StudentDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class TransactionController extends Controller
{
    protected $accountantUser;
    protected $schoolId;

    public function __construct()
    {
        $this->middleware('auth:accountant');
        $this->accountantUser = auth()->guard('accountant')->user();
        $this->schoolId = $this->accountantUser ? $this->accountantUser->school_id : 1;
    }

    /**
     * Display QR code scanner page
     */
    public function qrScanner()
    {
        $activeQrCodes = QrCodeSetting::where('school_id', $this->schoolId)
            ->where('is_active', true)
            ->where(function($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            })
            ->get();

        return view('accountant.payment.qr-scanner', compact('activeQrCodes'));
    }

    /**
     * Process QR code scan
     */
    public function processQrScan(Request $request)
    {
        $request->validate([
            'qr_code_id' => 'required|exists:qr_code_settings,id',
            'student_id' => 'required|exists:student_details,id',
            'amount' => 'nullable|numeric|min:0.01'
        ]);

        $qrCode = QrCodeSetting::findOrFail($request->qr_code_id);
        
        if (!$qrCode->canBeUsed()) {
            return response()->json([
                'success' => false,
                'message' => 'QR code is not available for use.'
            ], 400);
        }

        $student = StudentDetail::findOrFail($request->student_id);
        
        // Use QR code amount if no amount specified
        $amount = $request->amount ?? $qrCode->amount;
        
        if (!$amount) {
            return response()->json([
                'success' => false,
                'message' => 'Amount is required for this QR code.'
            ], 400);
        }

        // Create payment record
        $payment = StudentPayment::create([
            'school_id' => $this->schoolId,
            'student_id' => $student->id,
            'payment_date' => now()->toDateString(),
            'amount' => $amount,
            'method' => 'upi',
            'reference' => 'QR-' . $qrCode->id . '-' . time(),
            'status' => 'completed',
            'notes' => 'Payment via QR Code: ' . $qrCode->title,
            'created_by' => $this->accountantUser->id
        ]);

        // Increment QR code usage
        $qrCode->incrementUsage();

        return response()->json([
            'success' => true,
            'message' => 'Payment processed successfully!',
            'payment' => $payment->load('student')
        ]);
    }

    /**
     * Display online payment form
     */
    public function onlinePaymentForm(Request $request)
    {
        $studentId = $request->student_id;
        $amount = $request->amount;
        
        $activeGateways = PaymentGatewaySetting::where('school_id', $this->schoolId)
            ->where('is_active', true)
            ->get();

        if ($studentId) {
            $student = StudentDetail::findOrFail($studentId);
        } else {
            $student = null;
        }

        return view('accountant.payment.online-payment', compact('activeGateways', 'student', 'amount'));
    }

    /**
     * Process online payment
     */
    public function processOnlinePayment(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:student_details,id',
            'amount' => 'required|numeric|min:0.01',
            'gateway_id' => 'required|exists:payment_gateway_settings,id',
            'payment_method' => 'required|string'
        ]);

        $student = StudentDetail::findOrFail($request->student_id);
        $gateway = PaymentGatewaySetting::findOrFail($request->gateway_id);

        if (!$gateway->isGatewayActive()) {
            return response()->json([
                'success' => false,
                'message' => 'Selected payment gateway is not active.'
            ], 400);
        }

        if (!$gateway->supportsPaymentMethod($request->payment_method)) {
            return response()->json([
                'success' => false,
                'message' => 'Selected payment method is not supported by this gateway.'
            ], 400);
        }

        // Check amount limits
        if ($request->amount < $gateway->minimum_amount) {
            return response()->json([
                'success' => false,
                'message' => 'Amount is below minimum limit of ₹' . $gateway->minimum_amount
            ], 400);
        }

        if ($gateway->maximum_amount && $request->amount > $gateway->maximum_amount) {
            return response()->json([
                'success' => false,
                'message' => 'Amount exceeds maximum limit of ₹' . $gateway->maximum_amount
            ], 400);
        }

        // Calculate transaction fee
        $transactionFee = ($request->amount * $gateway->transaction_fee_percentage) / 100;
        $totalAmount = $request->amount + $transactionFee;

        // Create pending payment record
        $payment = StudentPayment::create([
            'school_id' => $this->schoolId,
            'student_id' => $student->id,
            'payment_date' => now()->toDateString(),
            'amount' => $request->amount,
            'method' => 'online',
            'reference' => 'ONLINE-' . $gateway->gateway_name . '-' . time(),
            'status' => 'pending',
            'notes' => 'Online payment via ' . $gateway->display_name . ' (Fee: ₹' . number_format($transactionFee, 2) . ')',
            'created_by' => $this->accountantUser->id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payment initiated successfully!',
            'payment_id' => $payment->id,
            'gateway' => $gateway->gateway_name,
            'amount' => $totalAmount,
            'transaction_fee' => $transactionFee
        ]);
    }

    /**
     * Handle payment gateway callback
     */
    public function paymentCallback(Request $request, $gateway)
    {
        $paymentId = $request->payment_id;
        $status = $request->status; // success, failed, cancelled
        
        $payment = StudentPayment::findOrFail($paymentId);
        
        if ($status === 'success') {
            $payment->update([
                'status' => 'completed',
                'notes' => ($payment->notes ? $payment->notes . ' | ' : '') . 'Payment completed via ' . $gateway
            ]);
        } else {
            $payment->update([
                'status' => 'failed',
                'notes' => ($payment->notes ? $payment->notes . ' | ' : '') . 'Payment failed via ' . $gateway
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Payment status updated successfully!'
        ]);
    }

    /**
     * Display transaction history
     */
    public function transactionHistory(Request $request)
    {
        if ($request->ajax()) {
            $query = StudentPayment::where('school_id', $this->schoolId)
                ->whereIn('method', ['online', 'upi'])
                ->with(['student'])
                ->orderBy('created_at', 'desc');

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('student_info', function ($data) {
                    return '<div>
                                <h6 class="mb-0">' . e($data->student->first_name . ' ' . $data->student->last_name) . '</h6>
                                <small class="text-muted">ID: ' . e($data->student->admission_no) . '</small>
                            </div>';
                })
                ->addColumn('amount', function ($data) {
                    return '<strong>₹' . number_format($data->amount, 2) . '</strong>';
                })
                ->addColumn('gateway', function ($data) {
                    $gateway = 'Unknown';
                    if (str_contains($data->reference, 'ONLINE-')) {
                        $parts = explode('-', $data->reference);
                        $gateway = $parts[1] ?? 'Online';
                    } elseif (str_contains($data->reference, 'QR-')) {
                        $gateway = 'QR Code';
                    }
                    
                    return '<span class="badge badge-pill badge-light-info">' . ucfirst($gateway) . '</span>';
                })
                ->addColumn('status', function ($data) {
                    $statusColors = [
                        'pending' => 'badge-light-warning',
                        'completed' => 'badge-light-success',
                        'failed' => 'badge-light-danger',
                        'refunded' => 'badge-light-info'
                    ];
                    
                    $color = $statusColors[$data->status] ?? 'badge-light-secondary';
                    return '<span class="badge badge-pill ' . $color . '">' . ucfirst($data->status) . '</span>';
                })
                ->addColumn('created_at', function ($data) {
                    return $data->created_at->format('M d, Y H:i');
                })
                ->addColumn('action', function ($data) {
                    $buttons = '<div class="d-flex justify-content-center">';

                    $buttons .= '<a href="' . route('accountant.payments.show', $data->id) . '" class="text-info me-2" title="View">
                                    <i class="bx bx-show"></i>
                                </a>';

                    if ($data->status === 'failed') {
                        $buttons .= '<a href="javascript:void(0);" data-id="' . $data->id . '" class="text-primary retry-payment-btn me-2" title="Retry Payment">
                                        <i class="bx bx-refresh"></i>
                                    </a>';
                    }

                    $buttons .= '</div>';
                    return $buttons;
                })
                ->rawColumns(['student_info', 'amount', 'gateway', 'status', 'action'])
                ->make(true);
        }

        return view('accountant.payment.transaction-history');
    }

    /**
     * Retry failed payment
     */
    public function retryPayment(StudentPayment $payment)
    {
        if ($payment->status !== 'failed') {
            return response()->json([
                'success' => false,
                'message' => 'Only failed payments can be retried.'
            ], 400);
        }

        $payment->update([
            'status' => 'pending',
            'notes' => ($payment->notes ? $payment->notes . ' | ' : '') . 'Payment retry initiated'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payment retry initiated successfully!'
        ]);
    }

    /**
     * Get available QR codes for scanning
     */
    public function getAvailableQrCodes()
    {
        $qrCodes = QrCodeSetting::where('school_id', $this->schoolId)
            ->where('is_active', true)
            ->where(function($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            })
            ->where(function($query) {
                $query->whereNull('max_usage')
                      ->orWhereRaw('usage_count < max_usage');
            })
            ->get();

        return response()->json($qrCodes);
    }
}
