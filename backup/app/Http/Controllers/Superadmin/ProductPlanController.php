<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\ProductPlan;
use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\ProductPlanNotification;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class ProductPlanController extends Controller
{
    /**
     * Show the Product Plans list view.
     */
    public function index(Request $request)
{
    if ($request->ajax()) {
        $plans = ProductPlan::latest();

        return DataTables::of($plans)
            ->addIndexColumn()
            ->editColumn('price', fn($row) => '₹ ' . number_format($row->price, 2))
            ->editColumn('features', fn($row) => Str::limit($row->features, 60))
            ->editColumn('status', function ($row) {
                $badge = $row->status === 'Active' 
                    ? '<span class="badge bg-success">Active</span>' 
                    : '<span class="badge bg-secondary">Inactive</span>';
                return $badge;
            })
            ->addColumn('action', function ($row) {
              return  '<div class="btn-group" role="group">
                        <button class="btn btn-sm btn-info edit-btn" data-id="'.$row->id.'"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-sm btn-danger delete-btn" data-id="'.$row->id.'"><i class="fas fa-trash"></i></button>
                        <button class="btn btn-sm btn-primary send-btn" data-id="'.$row->id.'"><i class="fas fa-bell"></i></button>
                    </div>';
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    return view('superadmin.productplans.index');
}

    /**
     * Send Notification to all admins for a selected product plan.
     */
    public function sendNotification(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:product_plans,id',
        ]);

        $productPlan = ProductPlan::findOrFail($request->id);

        // Fetch all users with 'admin' role
        $admins = User::role('admin')->get();

        foreach ($admins as $admin) {
            $admin->notify(new ProductPlanNotification($productPlan));
        }

        return response()->json([
            'success' => true,
            'message' => 'Notification sent to all admins successfully!',
        ]);
    }

    /**
     * Store a new Product Plan (if needed)
     */
    public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'price' => 'required|numeric',
        'features' => 'nullable|string',
        'max_users' => 'required|integer',
        'status' => 'required|in:Active,Inactive',
    ]);

    $plan = ProductPlan::create($request->only('title', 'price', 'features', 'max_users', 'status'));

    // Notify all admins
    $admins = User::role('admin')->get(); // if using Spatie Roles
    foreach ($admins as $admin) {
        $admin->notify(new ProductPlanNotification($plan));
    }

    return redirect()->route('superadmin.productplans.index')->with('success', 'Product Plan created and admins notified.');
}


    /**
     * Edit a Product Plan (return JSON for modal use)
     */
    public function edit($id)
    {
        $plan = ProductPlan::findOrFail($id);
        return response()->json($plan);
    }

    /**
     * Update a Product Plan
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric',
            'features' => 'nullable|string',
            'max_users' => 'required|integer|min:1',
        ]);

        $plan = ProductPlan::findOrFail($id);
        $plan->update($request->only('title', 'price', 'features', 'max_users'));

        return redirect()->route('superadmin.productplans.index')
            ->with('success', 'Product Plan updated successfully.');
    }

    /**
     * Delete a Product Plan
     */
    public function destroy($id)
    {
        $plan = ProductPlan::findOrFail($id);
        $plan->delete();

        return response()->json(['success' => true, 'message' => 'Product Plan deleted successfully.']);
    }
}
