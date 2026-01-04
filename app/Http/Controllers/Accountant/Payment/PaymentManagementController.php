<?php

namespace App\Http\Controllers\Accountant\Payment;

use App\Http\Controllers\Controller;
use App\Models\PaymentGatewaySetting;
use App\Models\QrCodeSetting;
use App\Models\StudentPayment;
use App\Models\StudentDetail;
use App\Models\Fee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class PaymentManagementController extends Controller
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
     * Display payment dashboard
     */
    public function dashboard()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        
        // Get payment statistics
        $todayPayments = StudentPayment::where('school_id', $this->schoolId)
            ->whereDate('payment_date', $today)
            ->where('status', 'completed')
            ->sum('amount');

        $monthlyPayments = StudentPayment::where('school_id', $this->schoolId)
            ->where('payment_date', '>=', $thisMonth)
            ->where('status', 'completed')
            ->sum('amount');

        $pendingPayments = StudentPayment::where('school_id', $this->schoolId)
            ->where('status', 'pending')
            ->count();

        $totalStudents = StudentDetail::where('school_id', $this->schoolId)->count();

        // Get recent payments
        $recentPayments = StudentPayment::where('school_id', $this->schoolId)
            ->with(['student'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Get available payment gateways
        $activeGateways = PaymentGatewaySetting::where('school_id', $this->schoolId)
            ->where('is_active', true)
            ->get();

        // Get available QR codes
        $activeQrCodes = QrCodeSetting::where('school_id', $this->schoolId)
            ->where('is_active', true)
            ->where(function($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            })
            ->get();

        return view('accountant.payment.dashboard', compact(
            'todayPayments', 
            'monthlyPayments', 
            'pendingPayments', 
            'totalStudents',
            'recentPayments',
            'activeGateways',
            'activeQrCodes'
        ));
    }

    /**
     * Display a listing of payments
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = StudentPayment::where('school_id', $this->schoolId)
                ->with(['student'])
                ->orderBy('payment_date', 'desc');

            // Apply filters
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            
            if ($request->filled('method')) {
                $query->where('method', $request->method);
            }
            
            if ($request->filled('date_from')) {
                $query->whereDate('payment_date', '>=', $request->date_from);
            }
            
            if ($request->filled('date_to')) {
                $query->whereDate('payment_date', '<=', $request->date_to);
            }

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
                ->addColumn('method', function ($data) {
                    $methodColors = [
                        'cash' => 'badge-light-secondary',
                        'card' => 'badge-light-primary',
                        'bank' => 'badge-light-info',
                        'online' => 'badge-light-success',
                        'upi' => 'badge-light-warning'
                    ];
                    
                    $color = $methodColors[$data->method] ?? 'badge-light-secondary';
                    return '<span class="badge badge-pill ' . $color . '">' . ucfirst($data->method) . '</span>';
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
                ->addColumn('payment_date', function ($data) {
                    return $data->payment_date->format('M d, Y');
                })
                ->addColumn('action', function ($data) {
                    $buttons = '<div class="d-flex justify-content-center">';

                    $buttons .= '<a href="' . route('accountant.payments.show', $data->id) . '" class="text-info me-2" title="View">
                                    <i class="bx bx-show"></i>
                                </a>';

                    if ($data->status === 'pending') {
                        $buttons .= '<a href="' . route('accountant.payments.edit', $data->id) . '" class="text-primary me-2" title="Edit">
                                        <i class="bx bxs-edit"></i>
                                    </a>';
                    }

                    if ($data->status === 'completed' && $data->method !== 'cash') {
                        $buttons .= '<a href="javascript:void(0);" data-id="' . $data->id . '" class="text-warning refund-payment-btn me-2" title="Refund">
                                        <i class="bx bx-undo"></i>
                                    </a>';
                    }

                    $buttons .= '</div>';
                    return $buttons;
                })
                ->rawColumns(['student_info', 'amount', 'method', 'status', 'action'])
                ->make(true);
        }

        return view('accountant.payment.index');
    }

    /**
     * Show the form for creating a new payment
     */
    public function create()
    {
        $students = StudentDetail::where('school_id', $this->schoolId)
            ->where('is_active', true)
            ->orderBy('first_name')
            ->get();

        $fees = Fee::where('school_id', $this->schoolId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $activeGateways = PaymentGatewaySetting::where('school_id', $this->schoolId)
            ->where('is_active', true)
            ->get();

        $activeQrCodes = QrCodeSetting::where('school_id', $this->schoolId)
            ->where('is_active', true)
            ->where(function($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            })
            ->get();

        return view('accountant.payment.create', compact('students', 'fees', 'activeGateways', 'activeQrCodes'));
    }

    /**
     * Store a newly created payment
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:student_details,id',
            'amount' => 'required|numeric|min:0.01',
            'method' => 'required|in:cash,card,bank,online,upi',
            'reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'payment_date' => 'required|date|before_or_equal:today',
            'qr_code_id' => 'nullable|exists:qr_code_settings,id'
        ]);

        // Check if QR code is valid and available
        if ($request->qr_code_id) {
            $qrCode = QrCodeSetting::findOrFail($request->qr_code_id);
            if (!$qrCode->canBeUsed()) {
                return redirect()->back()
                    ->withErrors(['qr_code_id' => 'Selected QR code is not available for use.'])
                    ->withInput();
            }
        }

        $payment = StudentPayment::create([
            'school_id' => $this->schoolId,
            'student_id' => $request->student_id,
            'payment_date' => $request->payment_date,
            'amount' => $request->amount,
            'method' => $request->method,
            'reference' => $request->reference,
            'status' => 'completed',
            'notes' => $request->notes,
            'created_by' => $this->accountantUser->id
        ]);

        // Increment QR code usage if used
        if ($request->qr_code_id) {
            $qrCode->incrementUsage();
        }

        return redirect()->route('accountant.payments.index')
            ->with('success', 'Payment recorded successfully!');
    }

    /**
     * Display the specified payment
     */
    public function show(StudentPayment $payment)
    {
        $payment->load(['student', 'createdBy']);
        return view('accountant.payment.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified payment
     */
    public function edit(StudentPayment $payment)
    {
        if ($payment->status !== 'pending') {
            return redirect()->route('accountant.payments.index')
                ->with('error', 'Only pending payments can be edited.');
        }

        $students = StudentDetail::where('school_id', $this->schoolId)
            ->where('is_active', true)
            ->orderBy('first_name')
            ->get();

        $fees = Fee::where('school_id', $this->schoolId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('accountant.payment.edit', compact('payment', 'students', 'fees'));
    }

    /**
     * Update the specified payment
     */
    public function update(Request $request, StudentPayment $payment)
    {
        if ($payment->status !== 'pending') {
            return redirect()->route('accountant.payments.index')
                ->with('error', 'Only pending payments can be edited.');
        }

        $request->validate([
            'student_id' => 'required|exists:student_details,id',
            'amount' => 'required|numeric|min:0.01',
            'method' => 'required|in:cash,card,bank,online,upi',
            'reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'payment_date' => 'required|date|before_or_equal:today',
            'status' => 'required|in:pending,completed,failed'
        ]);

        $payment->update([
            'student_id' => $request->student_id,
            'amount' => $request->amount,
            'method' => $request->method,
            'reference' => $request->reference,
            'notes' => $request->notes,
            'payment_date' => $request->payment_date,
            'status' => $request->status,
            'updated_by' => $this->accountantUser->id
        ]);

        return redirect()->route('accountant.payments.index')
            ->with('success', 'Payment updated successfully!');
    }

    /**
     * Remove the specified payment
     */
    public function destroy(StudentPayment $payment)
    {
        if ($payment->status === 'completed') {
            return redirect()->route('accountant.payments.index')
                ->with('error', 'Completed payments cannot be deleted. Please create a refund instead.');
        }

        $payment->delete();

        return redirect()->route('accountant.payments.index')
            ->with('success', 'Payment deleted successfully!');
    }

    /**
     * Process refund for a payment
     */
    public function refund(Request $request, StudentPayment $payment)
    {
        if ($payment->status !== 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'Only completed payments can be refunded.'
            ], 400);
        }

        $request->validate([
            'refund_amount' => 'required|numeric|min:0.01|max:' . $payment->amount,
            'refund_reason' => 'required|string|max:500'
        ]);

        // Create refund record
        $refundPayment = StudentPayment::create([
            'school_id' => $this->schoolId,
            'student_id' => $payment->student_id,
            'payment_date' => now()->toDateString(),
            'amount' => -$request->refund_amount, // Negative amount for refund
            'method' => $payment->method,
            'reference' => 'REFUND-' . $payment->reference,
            'status' => 'completed',
            'notes' => 'Refund: ' . $request->refund_reason,
            'created_by' => $this->accountantUser->id
        ]);

        // Update original payment status
        $payment->update([
            'status' => 'refunded',
            'notes' => ($payment->notes ? $payment->notes . ' | ' : '') . 'Refunded: ' . $request->refund_reason
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Refund processed successfully!'
        ]);
    }

    /**
     * Get payment statistics for reports
     */
    public function getStatistics(Request $request)
    {
        $startDate = $request->start_date ?? Carbon::now()->startOfMonth();
        $endDate = $request->end_date ?? Carbon::now()->endOfMonth();

        $payments = StudentPayment::where('school_id', $this->schoolId)
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->where('status', 'completed');

        $totalAmount = $payments->sum('amount');
        $totalCount = $payments->count();

        $methodBreakdown = $payments->selectRaw('method, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('method')
            ->get();

        $dailyPayments = $payments->selectRaw('DATE(payment_date) as date, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json([
            'total_amount' => $totalAmount,
            'total_count' => $totalCount,
            'method_breakdown' => $methodBreakdown,
            'daily_payments' => $dailyPayments
        ]);
    }
}
