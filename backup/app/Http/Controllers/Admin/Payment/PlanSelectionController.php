<?php

namespace App\Http\Controllers\Admin\Payment;

use App\Http\Controllers\Controller;
use App\Models\PaymentPlan;
use App\Models\PaymentTransaction;
use App\Models\Invoice;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PlanSelectionController extends Controller
{
    protected $schoolId;

    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->schoolId = Auth::guard('admin')->user()->school_id;
    }

    /**
     * Display available plans for selection
     */
    public function index(Request $request)
    {
        $query = PaymentPlan::with(['gateway'])
            ->whereHas('schools', function($q) {
                $q->where('school_id', $this->schoolId);
            })
            ->where('is_active', true);

        // Filter by price type
        if ($request->has('price_type') && $request->price_type) {
            $query->where('price_type', $request->price_type);
        }

        // Filter by gateway
        if ($request->has('gateway_id') && $request->gateway_id) {
            $query->where('gateway_id', $request->gateway_id);
        }

        $plans = $query->get();
        $gateways = $query->with('gateway')->get()->pluck('gateway')->unique('id');

        return view('admin.payment.plan-selection.index', compact('plans', 'gateways'));
    }

    /**
     * Show plan details
     */
    public function show(PaymentPlan $plan)
    {
        // Check if plan is available for this school
        if (!$plan->schools()->where('school_id', $this->schoolId)->exists()) {
            abort(403, 'This plan is not available for your school.');
        }

        $plan->load('gateway');
        return view('admin.payment.plan-selection.show', compact('plan'));
    }

    /**
     * Process plan selection and initiate payment
     */
    public function selectPlan(Request $request, PaymentPlan $plan)
    {
        // Check if plan is available for this school
        if (!$plan->schools()->where('school_id', $this->schoolId)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'This plan is not available for your school.'
            ], 403);
        }

        $request->validate([
            'payment_method' => 'required|string',
            'notes' => 'nullable|string|max:500'
        ]);

        try {
            // Create transaction
            $transaction = PaymentTransaction::create([
                'transaction_id' => 'TXN_' . time() . '_' . Str::random(8),
                'gateway_id' => $plan->gateway_id,
                'plan_id' => $plan->id,
                'school_id' => $this->schoolId,
                'user_id' => Auth::guard('admin')->id(),
                'amount' => $plan->price,
                'currency' => $plan->gateway->currency,
                'status' => 'pending',
                'payment_method' => $request->payment_method,
                'created_by' => Auth::guard('admin')->id()
            ]);

            // Process payment based on gateway
            $paymentResult = $this->processPayment($transaction, $plan);

            if ($paymentResult['success']) {
                // Update transaction with gateway response
                $transaction->update([
                    'gateway_transaction_id' => $paymentResult['gateway_transaction_id'],
                    'gateway_response' => $paymentResult['response'],
                    'status' => 'success',
                    'processed_at' => now()
                ]);

                // Generate invoice
                $invoice = $this->generateInvoice($transaction);

                return response()->json([
                    'success' => true,
                    'message' => 'Payment processed successfully!',
                    'transaction_id' => $transaction->transaction_id,
                    'invoice_id' => $invoice->invoice_number,
                    'redirect_url' => $paymentResult['redirect_url'] ?? null
                ]);
            } else {
                // Update transaction as failed
                $transaction->update([
                    'gateway_response' => $paymentResult['response'],
                    'status' => 'failed'
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Payment failed: ' . $paymentResult['message'],
                    'transaction_id' => $transaction->transaction_id
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process payment through gateway
     */
    private function processPayment(PaymentTransaction $transaction, PaymentPlan $plan)
    {
        // This is a placeholder implementation
        // In real implementation, integrate with actual payment gateways
        
        $gateway = $plan->gateway;
        
        // Simulate payment processing
        $success = rand(0, 1); // 50% success rate for demo
        
        if ($success) {
            return [
                'success' => true,
                'gateway_transaction_id' => 'GW_' . time() . '_' . Str::random(8),
                'response' => [
                    'status' => 'success',
                    'message' => 'Payment processed successfully',
                    'timestamp' => now()->toISOString()
                ],
                'redirect_url' => route('admin.payment.success', $transaction->transaction_id)
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Payment gateway error',
                'response' => [
                    'status' => 'failed',
                    'message' => 'Payment processing failed',
                    'timestamp' => now()->toISOString()
                ]
            ];
        }
    }

    /**
     * Generate invoice for successful payment
     */
    private function generateInvoice(PaymentTransaction $transaction)
    {
        $invoice = Invoice::create([
            'invoice_number' => (new Invoice())->generateInvoiceNumber(),
            'transaction_id' => $transaction->id,
            'school_id' => $transaction->school_id,
            'user_id' => $transaction->user_id,
            'amount' => $transaction->amount,
            'currency' => $transaction->currency,
            'status' => 'paid',
            'due_date' => now()->addDays(30),
            'paid_at' => now(),
            'notes' => 'Payment for ' . $transaction->plan->name,
            'created_by' => $transaction->created_by
        ]);

        // Create invoice item
        $invoice->items()->create([
            'description' => $transaction->plan->name,
            'quantity' => 1,
            'unit_price' => $transaction->amount,
            'total_price' => $transaction->amount
        ]);

        return $invoice;
    }

    /**
     * Payment success page
     */
    public function success($transactionId)
    {
        $transaction = PaymentTransaction::where('transaction_id', $transactionId)
            ->where('school_id', $this->schoolId)
            ->firstOrFail();

        $transaction->load('plan', 'invoice');

        return view('admin.payment.plan-selection.success', compact('transaction'));
    }

    /**
     * Payment failure page
     */
    public function failure($transactionId)
    {
        $transaction = PaymentTransaction::where('transaction_id', $transactionId)
            ->where('school_id', $this->schoolId)
            ->firstOrFail();

        $transaction->load('plan');

        return view('admin.payment.plan-selection.failure', compact('transaction'));
    }

    /**
     * Retry failed payment
     */
    public function retryPayment(PaymentTransaction $transaction)
    {
        if (!$transaction->canRetry()) {
            return response()->json([
                'success' => false,
                'message' => 'This transaction cannot be retried.'
            ], 400);
        }

        try {
            // Increment retry count
            $transaction->incrementRetryCount();

            // Process payment again
            $paymentResult = $this->processPayment($transaction, $transaction->plan);

            if ($paymentResult['success']) {
                $transaction->update([
                    'gateway_transaction_id' => $paymentResult['gateway_transaction_id'],
                    'gateway_response' => $paymentResult['response'],
                    'status' => 'success',
                    'processed_at' => now()
                ]);

                // Generate invoice
                $this->generateInvoice($transaction);

                return response()->json([
                    'success' => true,
                    'message' => 'Payment retry successful!'
                ]);
            } else {
                $transaction->update([
                    'gateway_response' => $paymentResult['response'],
                    'status' => 'failed'
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Payment retry failed: ' . $paymentResult['message']
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Retry failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
