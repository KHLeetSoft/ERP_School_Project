<?php

namespace App\Http\Controllers\Accountant\Payment;

use App\Http\Controllers\Controller;
use App\Models\PaymentTransaction;
use App\Models\Invoice;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class VerificationController extends Controller
{
    protected $schoolId;

    public function __construct()
    {
        $this->middleware('auth:accountant');
    }

    protected function getSchoolId()
    {
        return Auth::guard('accountant')->user()->school_id ?? null;
    }

    /**
     * Display transactions for verification
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = PaymentTransaction::with(['plan', 'gateway', 'user', 'invoice'])
                ->where('school_id', $this->getSchoolId())
                ->orderBy('created_at', 'desc');

            // Filter by status
            if ($request->has('status') && $request->status) {
                $query->where('status', $request->status);
            }

            // Filter by date range
            if ($request->has('date_from') && $request->date_from) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }
            if ($request->has('date_to') && $request->date_to) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('transaction_info', function ($data) {
                    $statusBadge = $this->getStatusBadge($data->status);
                    
                    return '<div class="d-flex align-items-center">
                                <div class="me-3">
                                    <h6 class="mb-0">' . e($data->transaction_id) . '</h6>
                                    <small class="text-muted">' . e($data->plan->name) . '</small>
                                </div>
                                <div class="ms-auto">
                                    ' . $statusBadge . '
                                </div>
                            </div>';
                })
                ->addColumn('amount_info', function ($data) {
                    return '<div>
                                <strong>₹' . number_format($data->amount, 2) . '</strong><br>
                                <small class="text-muted">' . $data->currency . '</small>
                            </div>';
                })
                ->addColumn('gateway_info', function ($data) {
                    return '<div>
                                <strong>' . e($data->gateway->name) . '</strong><br>
                                <small class="text-muted">' . e($data->gateway->provider) . '</small>
                            </div>';
                })
                ->addColumn('user_info', function ($data) {
                    return '<div>
                                <strong>' . e($data->user->name) . '</strong><br>
                                <small class="text-muted">' . e($data->user->email) . '</small>
                            </div>';
                })
                ->addColumn('invoice_status', function ($data) {
                    if ($data->invoice) {
                        $invoiceStatus = $data->invoice->is_paid() ? 'Paid' : 'Unpaid';
                        $badgeClass = $data->invoice->is_paid() ? 'bg-success' : 'bg-warning';
                        return '<span class="badge ' . $badgeClass . '">' . $invoiceStatus . '</span>';
                    }
                    return '<span class="badge bg-secondary">No Invoice</span>';
                })
                ->addColumn('created_at', function ($data) {
                    return $data->created_at->format('M d, Y H:i');
                })
                ->addColumn('action', function ($data) {
                    $buttons = '<div class="d-flex justify-content-center">';

                    $buttons .= '<a href="' . route('accountant.payment.verification.show', $data->id) . '" class="btn btn-sm btn-outline-info me-1" title="View Details">
                                    <i class="bx bx-show"></i>
                                </a>';

                    if ($data->status === 'success' && !$data->isProcessed()) {
                        $buttons .= '<button class="btn btn-sm btn-outline-success me-1 verify-btn" data-id="' . $data->id . '" title="Verify & Record">
                                        <i class="bx bx-check"></i>
                                    </button>';
                    }

                    if ($data->status === 'failed' && $data->canRetry()) {
                        $buttons .= '<button class="btn btn-sm btn-outline-warning me-1 retry-btn" data-id="' . $data->id . '" title="Retry Payment">
                                        <i class="bx bx-refresh"></i>
                                    </button>';
                    }

                    $buttons .= '</div>';
                    return $buttons;
                })
                ->rawColumns(['transaction_info', 'amount_info', 'gateway_info', 'user_info', 'invoice_status', 'created_at', 'action'])
                ->make(true);
        }

        return view('accountant.payment.verification.index');
    }

    /**
     * Show transaction details
     */
    public function show(PaymentTransaction $transaction)
    {
        // Check if transaction belongs to this school
        if ($transaction->school_id !== $this->getSchoolId()) {
            abort(403, 'Unauthorized access to this transaction.');
        }

        $transaction->load(['plan', 'gateway', 'user', 'invoice', 'invoice.items']);
        return view('accountant.payment.verification.show', compact('transaction'));
    }

    /**
     * Verify and record transaction
     */
    public function verify(Request $request, PaymentTransaction $transaction)
    {
        // Check if transaction belongs to this school
        if ($transaction->school_id !== $this->getSchoolId()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to this transaction.'
            ], 403);
        }

        if ($transaction->status !== 'success') {
            return response()->json([
                'success' => false,
                'message' => 'Only successful transactions can be verified.'
            ], 400);
        }

        if ($transaction->isProcessed()) {
            return response()->json([
                'success' => false,
                'message' => 'This transaction has already been processed.'
            ], 400);
        }

        $request->validate([
            'verification_notes' => 'nullable|string|max:500'
        ]);

        try {
            // Mark transaction as processed
            $transaction->markAsProcessed();

            // Update invoice if exists
            if ($transaction->invoice) {
                $transaction->invoice->markAsPaid();
            }

            // Create verification record
            $transaction->verifications()->create([
                'verified_by' => Auth::guard('accountant')->id(),
                'verification_notes' => $request->verification_notes,
                'verified_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Transaction verified and recorded successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Verification failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Retry failed payment
     */
    public function retryPayment(Request $request, PaymentTransaction $transaction)
    {
        // Check if transaction belongs to this school
        if ($transaction->school_id !== $this->getSchoolId()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to this transaction.'
            ], 403);
        }

        if (!$transaction->canRetry()) {
            return response()->json([
                'success' => false,
                'message' => 'This transaction cannot be retried.'
            ], 400);
        }

        $request->validate([
            'retry_notes' => 'nullable|string|max:500'
        ]);

        try {
            // Increment retry count
            $transaction->incrementRetryCount();

            // Process payment again (simplified for demo)
            $success = rand(0, 1); // 50% success rate for demo

            if ($success) {
                $transaction->update([
                    'status' => 'success',
                    'processed_at' => now(),
                    'gateway_transaction_id' => 'RETRY_' . time() . '_' . \Str::random(8),
                    'gateway_response' => [
                        'status' => 'success',
                        'message' => 'Payment retry successful',
                        'timestamp' => now()->toISOString()
                    ]
                ]);

                // Generate invoice
                $this->generateInvoice($transaction);

                return response()->json([
                    'success' => true,
                    'message' => 'Payment retry successful!'
                ]);
            } else {
                $transaction->update([
                    'status' => 'failed',
                    'gateway_response' => [
                        'status' => 'failed',
                        'message' => 'Payment retry failed',
                        'timestamp' => now()->toISOString()
                    ]
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Payment retry failed. Please try again later.'
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Retry failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate invoice for transaction
     */
    private function generateInvoice(PaymentTransaction $transaction)
    {
        if ($transaction->invoice) {
            return $transaction->invoice;
        }

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
            'created_by' => Auth::guard('accountant')->id()
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
     * Get status badge HTML
     */
    private function getStatusBadge($status)
    {
        $badges = [
            'pending' => '<span class="badge bg-warning">Pending</span>',
            'success' => '<span class="badge bg-success">Success</span>',
            'failed' => '<span class="badge bg-danger">Failed</span>',
            'cancelled' => '<span class="badge bg-secondary">Cancelled</span>'
        ];

        return $badges[$status] ?? '<span class="badge bg-secondary">Unknown</span>';
    }
}
