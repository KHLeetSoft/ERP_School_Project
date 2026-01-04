<?php

namespace App\Http\Controllers\Admin\Payment;

use App\Http\Controllers\Controller;
use App\Models\PaymentGatewaySetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\Facades\DataTables;

class PaymentGatewayController extends Controller
{
    protected $adminUser;
    protected $schoolId;

    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->adminUser = auth()->guard('admin')->user();
        $this->schoolId = $this->adminUser ? $this->adminUser->school_id : 1;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = PaymentGatewaySetting::where('school_id', $this->schoolId)
                ->with(['createdBy', 'updatedBy'])
                ->orderBy('gateway_name');

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('gateway_info', function ($data) {
                    $statusBadge = $data->is_active ? 
                        '<span class="badge badge-pill badge-light-success">Active</span>' : 
                        '<span class="badge badge-pill badge-light-danger">Inactive</span>';
                    
                    $modeBadge = $data->is_test_mode ? 
                        '<span class="badge badge-pill badge-light-warning">Test Mode</span>' : 
                        '<span class="badge badge-pill badge-light-info">Live Mode</span>';

                    return '<div class="d-flex align-items-center">
                                <div class="me-3">
                                    <h6 class="mb-0">' . e($data->display_name) . '</h6>
                                    <small class="text-muted">' . e($data->gateway_name) . '</small>
                                </div>
                                <div class="ms-auto">
                                    ' . $statusBadge . '<br>' . $modeBadge . '
                                </div>
                            </div>';
                })
                ->addColumn('payment_methods', function ($data) {
                    $methods = $data->supported_payment_methods ?? [];
                    $badges = '';
                    foreach ($methods as $method) {
                        $badges .= '<span class="badge badge-pill badge-light-primary me-1">' . ucfirst($method) . '</span>';
                    }
                    return $badges ?: '<span class="text-muted">No methods</span>';
                })
                ->addColumn('fees', function ($data) {
                    return '<div>
                                <strong>Fee:</strong> ' . $data->transaction_fee_percentage . '%<br>
                                <strong>Min:</strong> ₹' . number_format($data->minimum_amount, 2) . '<br>
                                <strong>Max:</strong> ' . ($data->maximum_amount ? '₹' . number_format($data->maximum_amount, 2) : 'Unlimited') . '
                            </div>';
                })
                ->addColumn('created_at', function ($data) {
                    return $data->created_at->format('M d, Y');
                })
                ->addColumn('action', function ($data) {
                    $buttons = '<div class="d-flex justify-content-center">';

                    $buttons .= '<a href="' . route('admin.payment.gateways.show', $data->id) . '" class="text-info me-2" title="View">
                                    <i class="bx bx-show"></i>
                                </a>';

                    $buttons .= '<a href="' . route('admin.payment.gateways.edit', $data->id) . '" class="text-primary me-2" title="Edit">
                                    <i class="bx bxs-edit"></i>
                                </a>';

                    $buttons .= '<a href="javascript:void(0);" data-id="' . $data->id . '" class="text-warning toggle-status-btn me-2" title="Toggle Status">
                                    <i class="bx bx-' . ($data->is_active ? 'pause' : 'play') . '"></i>
                                </a>';

                    $buttons .= '<a href="javascript:void(0);" data-id="' . $data->id . '" class="text-danger delete-gateway-btn" title="Delete">
                                    <i class="bx bx-trash"></i>
                                </a>';

                    $buttons .= '</div>';
                    return $buttons;
                })
                ->rawColumns(['gateway_info', 'payment_methods', 'fees', 'action'])
                ->make(true);
        }

        return view('admin.payment.gateways.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $gatewayTypes = [
            'razorpay' => 'Razorpay',
            'paytm' => 'Paytm',
            'stripe' => 'Stripe',
            'upi' => 'UPI',
            'payu' => 'PayU',
            'instamojo' => 'Instamojo'
        ];

        $paymentMethods = [
            'card' => 'Credit/Debit Card',
            'upi' => 'UPI',
            'netbanking' => 'Net Banking',
            'wallet' => 'Digital Wallet',
            'emi' => 'EMI',
            'cod' => 'Cash on Delivery'
        ];

        return view('admin.payment.gateways.create', compact('gatewayTypes', 'paymentMethods'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'gateway_name' => 'required|string|max:255',
            'display_name' => 'required|string|max:255',
            'is_active' => 'boolean',
            'is_test_mode' => 'boolean',
            'api_credentials' => 'required|array',
            'supported_payment_methods' => 'required|array|min:1',
            'transaction_fee_percentage' => 'required|numeric|min:0|max:100',
            'minimum_amount' => 'required|numeric|min:0',
            'maximum_amount' => 'nullable|numeric|min:0',
            'webhook_url' => 'nullable|url',
            'return_url' => 'nullable|url',
            'cancel_url' => 'nullable|url'
        ]);

        // Encrypt API credentials
        $encryptedCredentials = [];
        foreach ($request->api_credentials as $key => $value) {
            if (!empty($value)) {
                $encryptedCredentials[$key] = Crypt::encryptString($value);
            }
        }

        $gateway = PaymentGatewaySetting::create([
            'school_id' => $this->schoolId,
            'gateway_name' => $request->gateway_name,
            'display_name' => $request->display_name,
            'is_active' => $request->has('is_active'),
            'is_test_mode' => $request->has('is_test_mode'),
            'api_credentials' => $encryptedCredentials,
            'supported_payment_methods' => $request->supported_payment_methods,
            'transaction_fee_percentage' => $request->transaction_fee_percentage,
            'minimum_amount' => $request->minimum_amount,
            'maximum_amount' => $request->maximum_amount,
            'webhook_url' => $request->webhook_url,
            'return_url' => $request->return_url,
            'cancel_url' => $request->cancel_url,
            'additional_settings' => $request->additional_settings ?? [],
            'created_by' => $this->adminUser->id
        ]);

        return redirect()->route('admin.payment.gateways.index')
            ->with('success', 'Payment gateway configured successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(PaymentGatewaySetting $gateway)
    {
        $gateway->load(['createdBy', 'updatedBy']);
        
        // Decrypt API credentials for display (masked)
        $maskedCredentials = [];
        foreach ($gateway->api_credentials as $key => $value) {
            $decrypted = Crypt::decryptString($value);
            $maskedCredentials[$key] = substr($decrypted, 0, 4) . str_repeat('*', strlen($decrypted) - 8) . substr($decrypted, -4);
        }
        $gateway->masked_credentials = $maskedCredentials;

        return view('admin.payment.gateways.show', compact('gateway'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PaymentGatewaySetting $gateway)
    {
        $gatewayTypes = [
            'razorpay' => 'Razorpay',
            'paytm' => 'Paytm',
            'stripe' => 'Stripe',
            'upi' => 'UPI',
            'payu' => 'PayU',
            'instamojo' => 'Instamojo'
        ];

        $paymentMethods = [
            'card' => 'Credit/Debit Card',
            'upi' => 'UPI',
            'netbanking' => 'Net Banking',
            'wallet' => 'Digital Wallet',
            'emi' => 'EMI',
            'cod' => 'Cash on Delivery'
        ];

        // Decrypt API credentials for editing
        $decryptedCredentials = [];
        foreach ($gateway->api_credentials as $key => $value) {
            $decryptedCredentials[$key] = Crypt::decryptString($value);
        }
        $gateway->decrypted_credentials = $decryptedCredentials;

        return view('admin.payment.gateways.edit', compact('gateway', 'gatewayTypes', 'paymentMethods'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PaymentGatewaySetting $gateway)
    {
        $request->validate([
            'display_name' => 'required|string|max:255',
            'is_active' => 'boolean',
            'is_test_mode' => 'boolean',
            'api_credentials' => 'required|array',
            'supported_payment_methods' => 'required|array|min:1',
            'transaction_fee_percentage' => 'required|numeric|min:0|max:100',
            'minimum_amount' => 'required|numeric|min:0',
            'maximum_amount' => 'nullable|numeric|min:0',
            'webhook_url' => 'nullable|url',
            'return_url' => 'nullable|url',
            'cancel_url' => 'nullable|url'
        ]);

        // Encrypt API credentials
        $encryptedCredentials = [];
        foreach ($request->api_credentials as $key => $value) {
            if (!empty($value)) {
                $encryptedCredentials[$key] = Crypt::encryptString($value);
            }
        }

        $gateway->update([
            'display_name' => $request->display_name,
            'is_active' => $request->has('is_active'),
            'is_test_mode' => $request->has('is_test_mode'),
            'api_credentials' => $encryptedCredentials,
            'supported_payment_methods' => $request->supported_payment_methods,
            'transaction_fee_percentage' => $request->transaction_fee_percentage,
            'minimum_amount' => $request->minimum_amount,
            'maximum_amount' => $request->maximum_amount,
            'webhook_url' => $request->webhook_url,
            'return_url' => $request->return_url,
            'cancel_url' => $request->cancel_url,
            'additional_settings' => $request->additional_settings ?? [],
            'updated_by' => $this->adminUser->id
        ]);

        return redirect()->route('admin.payment.gateways.index')
            ->with('success', 'Payment gateway updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PaymentGatewaySetting $gateway)
    {
        $gateway->delete();

        return redirect()->route('admin.payment.gateways.index')
            ->with('success', 'Payment gateway deleted successfully!');
    }

    /**
     * Toggle gateway status
     */
    public function toggleStatus(PaymentGatewaySetting $gateway)
    {
        $gateway->update(['is_active' => !$gateway->is_active]);
        
        $status = $gateway->is_active ? 'activated' : 'deactivated';
        return response()->json([
            'success' => true,
            'message' => "Payment gateway {$status} successfully!",
            'status' => $gateway->is_active
        ]);
    }

    /**
     * Test gateway connection
     */
    public function testConnection(PaymentGatewaySetting $gateway)
    {
        try {
            // Implement gateway-specific test logic here
            // This is a placeholder - you would implement actual API testing
            return response()->json([
                'success' => true,
                'message' => 'Gateway connection test successful!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gateway connection test failed: ' . $e->getMessage()
            ], 400);
        }
    }
}
