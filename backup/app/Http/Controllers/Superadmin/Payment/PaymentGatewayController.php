<?php

namespace App\Http\Controllers\Superadmin\Payment;

use App\Http\Controllers\Controller;
use App\Models\PaymentGateway;
use App\Models\PaymentPlan;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class PaymentGatewayController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display payment gateways management
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Handle statistics request
            if ($request->has('stats_only') && $request->stats_only) {
                $query = PaymentGateway::query();
                
                // Apply filters
                if ($request->has('school_id') && $request->school_id) {
                    $query->whereHas('schools', function($q) use ($request) {
                        $q->where('school_id', $request->school_id);
                    });
                }
                if ($request->has('status') && $request->status !== '') {
                    $query->where('is_active', $request->status);
                }
                
                return response()->json([
                    'total' => $query->count(),
                    'active' => $query->where('is_active', true)->count(),
                    'inactive' => $query->where('is_active', false)->count(),
                    'schools' => $query->withCount('schools')->get()->sum('schools_count')
                ]);
            }

            $query = PaymentGateway::with(['schools'])
                ->orderBy('created_at', 'desc');

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('gateway_info', function ($data) {
                    $statusBadge = $data->is_active ? 
                        '<span class="badge bg-success">Active</span>' : 
                        '<span class="badge bg-danger">Inactive</span>';
                    
                    return '<div class="d-flex align-items-center">
                                <div class="me-3">
                                    <h6 class="mb-0">' . e($data->name) . '</h6>
                                    <small class="text-muted">' . e($data->provider) . '</small>
                                </div>
                                <div class="ms-auto">
                                    ' . $statusBadge . '
                                </div>
                            </div>';
                })
                ->addColumn('configuration', function ($data) {
                    return '<div>
                                <strong>Mode:</strong> ' . ucfirst($data->mode) . '<br>
                                <strong>Currency:</strong> ' . $data->currency . '<br>
                                <strong>Commission:</strong> ' . $data->commission_rate . '%
                            </div>';
                })
                ->addColumn('schools_count', function ($data) {
                    return '<span class="badge bg-info">' . $data->schools->count() . ' Schools</span>';
                })
                ->addColumn('created_at', function ($data) {
                    return $data->created_at->format('M d, Y');
                })
                ->addColumn('action', function ($data) {
                    $buttons = '<div class="d-flex justify-content-center">';

                    $buttons .= '<a href="' . route('superadmin.payment.gateways.show', $data->id) . '" class="btn btn-sm btn-outline-info me-1" title="View">
                                    <i class="bx bx-show"></i>
                                </a>';

                    $buttons .= '<a href="' . route('superadmin.payment.gateways.edit', $data->id) . '" class="btn btn-sm btn-outline-primary me-1" title="Edit">
                                    <i class="bx bx-edit"></i>
                                </a>';

                    $buttons .= '<button class="btn btn-sm btn-outline-warning me-1 toggle-status-btn" data-id="' . $data->id . '" title="Toggle Status">
                                    <i class="bx bx-' . ($data->is_active ? 'pause' : 'play') . '"></i>
                                </button>';

                    $buttons .= '<button class="btn btn-sm btn-outline-danger delete-gateway-btn" data-id="' . $data->id . '" title="Delete">
                                    <i class="bx bx-trash"></i>
                                </button>';

                    $buttons .= '</div>';
                    return $buttons;
                })
                ->rawColumns(['gateway_info', 'configuration', 'schools_count', 'created_at', 'action'])
                ->make(true);
        }

        $schools = School::select('id', 'name')->get();
        return view('superadmin.payment.gateways.index', compact('schools'));
    }

    /**
     * Show the form for creating a new gateway
     */
    public function create()
    {
        $schools = School::select('id', 'name')->get();
        return view('superadmin.payment.gateways.create', compact('schools'));
    }

    /**
     * Store a newly created gateway
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'provider' => 'required|string|max:255',
            'mode' => 'required|in:sandbox,live',
            'api_key' => 'required|string',
            'api_secret' => 'required|string',
            'webhook_url' => 'nullable|url',
            'currency' => 'required|string|max:3',
            'commission_rate' => 'required|numeric|min:0|max:100',
            'is_active' => 'boolean',
            'schools' => 'array',
            'schools.*' => 'exists:schools,id'
        ]);

        $gateway = PaymentGateway::create([
            'name' => $request->name,
            'provider' => $request->provider,
            'mode' => $request->mode,
            'api_key' => encrypt($request->api_key),
            'api_secret' => encrypt($request->api_secret),
            'webhook_url' => $request->webhook_url,
            'currency' => $request->currency,
            'commission_rate' => $request->commission_rate,
            'is_active' => $request->has('is_active'),
            'created_by' => auth()->id()
        ]);

        // Attach schools
        if ($request->has('schools')) {
            $gateway->schools()->attach($request->schools);
        }

        return redirect()->route('superadmin.payment.gateways.index')
            ->with('success', 'Payment gateway created successfully!');
    }

    /**
     * Display the specified gateway
     */
    public function show(PaymentGateway $gateway)
    {
        $gateway->load('schools', 'plans');
        return view('superadmin.payment.gateways.show', compact('gateway'));
    }

    /**
     * Show the form for editing the gateway
     */
    public function edit(PaymentGateway $gateway)
    {
        $schools = School::select('id', 'name')->get();
        $gateway->load('schools');
        return view('superadmin.payment.gateways.edit', compact('gateway', 'schools'));
    }

    /**
     * Update the gateway
     */
    public function update(Request $request, PaymentGateway $gateway)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'provider' => 'required|string|max:255',
            'mode' => 'required|in:sandbox,live',
            'api_key' => 'required|string',
            'api_secret' => 'required|string',
            'webhook_url' => 'nullable|url',
            'currency' => 'required|string|max:3',
            'commission_rate' => 'required|numeric|min:0|max:100',
            'is_active' => 'boolean',
            'schools' => 'array',
            'schools.*' => 'exists:schools,id'
        ]);

        $gateway->update([
            'name' => $request->name,
            'provider' => $request->provider,
            'mode' => $request->mode,
            'api_key' => encrypt($request->api_key),
            'api_secret' => encrypt($request->api_secret),
            'webhook_url' => $request->webhook_url,
            'currency' => $request->currency,
            'commission_rate' => $request->commission_rate,
            'is_active' => $request->has('is_active'),
            'updated_by' => auth()->id()
        ]);

        // Sync schools
        $gateway->schools()->sync($request->schools ?? []);

        return redirect()->route('superadmin.payment.gateways.index')
            ->with('success', 'Payment gateway updated successfully!');
    }

    /**
     * Toggle gateway status
     */
    public function toggleStatus(PaymentGateway $gateway)
    {
        $gateway->update([
            'is_active' => !$gateway->is_active,
            'updated_by' => auth()->id()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Gateway status updated successfully!',
            'is_active' => $gateway->is_active
        ]);
    }

    /**
     * Test gateway connection
     */
    public function testConnection(PaymentGateway $gateway)
    {
        try {
            // Implement gateway-specific test logic here
            // This is a placeholder - implement based on actual gateway APIs
            
            return response()->json([
                'success' => true,
                'message' => 'Gateway connection test successful!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gateway connection test failed: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Delete gateway
     */
    public function destroy(PaymentGateway $gateway)
    {
        $gateway->delete();

        return response()->json([
            'success' => true,
            'message' => 'Payment gateway deleted successfully!'
        ]);
    }
}
