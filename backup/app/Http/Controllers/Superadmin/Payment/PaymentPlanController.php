<?php

namespace App\Http\Controllers\Superadmin\Payment;

use App\Http\Controllers\Controller;
use App\Models\PaymentPlan;
use App\Models\PaymentGateway;
use App\Models\School;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PaymentPlanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display payment plans management
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Handle statistics request
            if ($request->has('stats_only') && $request->stats_only) {
                $query = PaymentPlan::query();
                
                // Apply filters
                if ($request->has('gateway_id') && $request->gateway_id) {
                    $query->where('gateway_id', $request->gateway_id);
                }
                if ($request->has('price_type') && $request->price_type) {
                    $query->where('price_type', $request->price_type);
                }
                if ($request->has('status') && $request->status !== '') {
                    $query->where('is_active', $request->status);
                }
                
                return response()->json([
                    'total' => $query->count(),
                    'active' => $query->where('is_active', true)->count(),
                    'schools' => $query->withCount('schools')->get()->sum('schools_count'),
                    'revenue' => 0 // This would need to be calculated from transactions
                ]);
            }

            $query = PaymentPlan::with(['gateway', 'schools'])
                ->orderBy('created_at', 'desc');

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('plan_info', function ($data) {
                    $statusBadge = $data->is_active ? 
                        '<span class="badge bg-success">Active</span>' : 
                        '<span class="badge bg-danger">Inactive</span>';
                    
                    return '<div class="d-flex align-items-center">
                                <div class="me-3">
                                    <h6 class="mb-0">' . e($data->name) . '</h6>
                                    <small class="text-muted">' . e($data->description) . '</small>
                                </div>
                                <div class="ms-auto">
                                    ' . $statusBadge . '
                                </div>
                            </div>';
                })
                ->addColumn('pricing', function ($data) {
                    if ($data->price_type === 'fixed') {
                        return '<strong>₹' . number_format($data->price, 2) . '</strong><br><small class="text-muted">Fixed Price</small>';
                    } else {
                        return '<strong>₹' . number_format($data->price, 2) . '</strong><br><small class="text-muted">Per ' . $data->billing_cycle . '</small>';
                    }
                })
                ->addColumn('gateway_info', function ($data) {
                    return '<div>
                                <strong>' . e($data->gateway->name) . '</strong><br>
                                <small class="text-muted">' . e($data->gateway->provider) . '</small>
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

                    $buttons .= '<a href="' . route('superadmin.payment.plans.show', $data->id) . '" class="btn btn-sm btn-outline-info me-1" title="View">
                                    <i class="bx bx-show"></i>
                                </a>';

                    $buttons .= '<a href="' . route('superadmin.payment.plans.edit', $data->id) . '" class="btn btn-sm btn-outline-primary me-1" title="Edit">
                                    <i class="bx bx-edit"></i>
                                </a>';

                    $buttons .= '<button class="btn btn-sm btn-outline-warning me-1 toggle-status-btn" data-id="' . $data->id . '" title="Toggle Status">
                                    <i class="bx bx-' . ($data->is_active ? 'pause' : 'play') . '"></i>
                                </button>';

                    $buttons .= '<button class="btn btn-sm btn-outline-danger delete-plan-btn" data-id="' . $data->id . '" title="Delete">
                                    <i class="bx bx-trash"></i>
                                </button>';

                    $buttons .= '</div>';
                    return $buttons;
                })
                ->rawColumns(['plan_info', 'pricing', 'gateway_info', 'schools_count', 'created_at', 'action'])
                ->make(true);
        }

        $gateways = PaymentGateway::where('is_active', true)->get();
        $schools = School::select('id', 'name')->get();
        return view('superadmin.payment.plans.index', compact('gateways', 'schools'));
    }

    /**
     * Show the form for creating a new plan
     */
    public function create()
    {
        $gateways = PaymentGateway::where('is_active', true)->get();
        $schools = School::select('id', 'name')->get();
        return view('superadmin.payment.plans.create', compact('gateways', 'schools'));
    }

    /**
     * Store a newly created plan
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'gateway_id' => 'required|exists:payment_gateways,id',
            'price_type' => 'required|in:fixed,recurring',
            'price' => 'required|numeric|min:0',
            'billing_cycle' => 'required_if:price_type,recurring|in:monthly,yearly',
            'features' => 'nullable|array',
            'is_active' => 'boolean',
            'schools' => 'array',
            'schools.*' => 'exists:schools,id'
        ]);

        $plan = PaymentPlan::create([
            'name' => $request->name,
            'description' => $request->description,
            'gateway_id' => $request->gateway_id,
            'price_type' => $request->price_type,
            'price' => $request->price,
            'billing_cycle' => $request->billing_cycle,
            'features' => $request->features ?? [],
            'is_active' => $request->has('is_active'),
            'created_by' => auth()->id()
        ]);

        // Attach schools
        if ($request->has('schools')) {
            $plan->schools()->attach($request->schools);
        }

        return redirect()->route('superadmin.payment.plans.index')
            ->with('success', 'Payment plan created successfully!');
    }

    /**
     * Display the specified plan
     */
    public function show(PaymentPlan $plan)
    {
        $plan->load('gateway', 'schools');
        return view('superadmin.payment.plans.show', compact('plan'));
    }

    /**
     * Show the form for editing the plan
     */
    public function edit(PaymentPlan $plan)
    {
        $gateways = PaymentGateway::where('is_active', true)->get();
        $schools = School::select('id', 'name')->get();
        $plan->load('schools');
        return view('superadmin.payment.plans.edit', compact('plan', 'gateways', 'schools'));
    }

    /**
     * Update the plan
     */
    public function update(Request $request, PaymentPlan $plan)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'gateway_id' => 'required|exists:payment_gateways,id',
            'price_type' => 'required|in:fixed,recurring',
            'price' => 'required|numeric|min:0',
            'billing_cycle' => 'required_if:price_type,recurring|in:monthly,yearly',
            'features' => 'nullable|array',
            'is_active' => 'boolean',
            'schools' => 'array',
            'schools.*' => 'exists:schools,id'
        ]);

        $plan->update([
            'name' => $request->name,
            'description' => $request->description,
            'gateway_id' => $request->gateway_id,
            'price_type' => $request->price_type,
            'price' => $request->price,
            'billing_cycle' => $request->billing_cycle,
            'features' => $request->features ?? [],
            'is_active' => $request->has('is_active'),
            'updated_by' => auth()->id()
        ]);

        // Sync schools
        $plan->schools()->sync($request->schools ?? []);

        return redirect()->route('superadmin.payment.plans.index')
            ->with('success', 'Payment plan updated successfully!');
    }

    /**
     * Toggle plan status
     */
    public function toggleStatus(PaymentPlan $plan)
    {
        $plan->update([
            'is_active' => !$plan->is_active,
            'updated_by' => auth()->id()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Plan status updated successfully!',
            'is_active' => $plan->is_active
        ]);
    }

    /**
     * Delete plan
     */
    public function destroy(PaymentPlan $plan)
    {
        $plan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Payment plan deleted successfully!'
        ]);
    }
}
