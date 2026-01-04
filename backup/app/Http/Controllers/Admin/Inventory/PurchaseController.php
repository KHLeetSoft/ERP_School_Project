<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use App\Models\InventoryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use DB;

class PurchaseController extends Controller
{
    protected $adminUser;
    protected $schoolId;

    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware(function ($request, $next) {
            $this->adminUser = auth()->guard('admin')->user();
            $this->schoolId = $this->adminUser ? $this->adminUser->school_id : null;
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Purchase::with(['supplier', 'purchaseItems']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('purchase_number', 'like', "%{$search}%")
                  ->orWhere('reference_number', 'like', "%{$search}%")
                  ->orWhereHas('supplier', function($supplierQuery) use ($search) {
                      $supplierQuery->where('name', 'like', "%{$search}%")
                                   ->orWhere('company', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        // Filter by payment status
        if ($request->filled('payment_status')) {
            $query->byPaymentStatus($request->payment_status);
        }

        // Filter by supplier
        if ($request->filled('supplier_id')) {
            $query->bySupplier($request->supplier_id);
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->where('purchase_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('purchase_date', '<=', $request->end_date);
        }

        $purchases = $query->orderBy('purchase_date', 'desc')->paginate(20);

        // Statistics
        $stats = [
            'total_purchases' => Purchase::count(),
            'draft_purchases' => Purchase::draft()->count(),
            'pending_purchases' => Purchase::pending()->count(),
            'approved_purchases' => Purchase::approved()->count(),
            'received_purchases' => Purchase::received()->count(),
            'total_amount' => Purchase::sum('total_amount'),
            'pending_amount' => Purchase::where('payment_status', 'pending')->sum('balance_amount'),
        ];

        $suppliers = Supplier::active()->orderBy('name')->get();
        $statuses = Purchase::STATUSES;
        $paymentStatuses = Purchase::PAYMENT_STATUSES;

        return view('admin.inventory.purchases.index', compact('purchases', 'stats', 'suppliers', 'statuses', 'paymentStatuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = Supplier::active()->orderBy('name')->get();
        $inventoryItems = InventoryItem::active()->orderBy('name')->get();
        $statuses = Purchase::STATUSES;
        $paymentStatuses = Purchase::PAYMENT_STATUSES;
        $paymentMethods = Purchase::PAYMENT_METHODS;

        return view('admin.inventory.purchases.create', compact('suppliers', 'inventoryItems', 'statuses', 'paymentStatuses', 'paymentMethods'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_date' => 'required|date',
            'expected_delivery_date' => 'nullable|date|after_or_equal:purchase_date',
            'status' => 'required|in:' . implode(',', array_keys(Purchase::STATUSES)),
            'payment_status' => 'required|in:' . implode(',', array_keys(Purchase::PAYMENT_STATUSES)),
            'payment_method' => 'nullable|in:' . implode(',', array_keys(Purchase::PAYMENT_METHODS)),
            'reference_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
            'terms_conditions' => 'nullable|string|max:1000',
            'delivery_address' => 'nullable|string|max:500',
            'billing_address' => 'nullable|string|max:500',
            'shipping_cost' => 'nullable|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.inventory_item_id' => 'required|exists:inventory_items,id',
            'items.*.quantity_ordered' => 'required|integer|min:1',
            'items.*.unit_cost' => 'required|numeric|min:0',
            'items.*.discount_percentage' => 'nullable|numeric|min:0|max:100',
            'items.*.tax_percentage' => 'nullable|numeric|min:0|max:100',
            'items.*.expiry_date' => 'nullable|date|after:today',
            'items.*.notes' => 'nullable|string|max:500',
            'attachments.*' => 'nullable|file|mimes:pdf,doc,docx,jpeg,png,jpg|max:5120',
        ]);

        DB::beginTransaction();
        try {
            // Create purchase
            $purchase = Purchase::create([
                'purchase_number' => (new Purchase())->generatePurchaseNumber(),
                'supplier_id' => $request->supplier_id,
                'purchase_date' => $request->purchase_date,
                'expected_delivery_date' => $request->expected_delivery_date,
                'status' => $request->status,
                'payment_status' => $request->payment_status,
                'payment_method' => $request->payment_method,
                'reference_number' => $request->reference_number,
                'notes' => $request->notes,
                'terms_conditions' => $request->terms_conditions,
                'delivery_address' => $request->delivery_address,
                'billing_address' => $request->billing_address,
                'shipping_cost' => $request->shipping_cost ?? 0,
                'prepared_by' => $this->adminUser->name ?? 'Admin',
            ]);

            // Create purchase items
            foreach ($request->items as $itemData) {
                $inventoryItem = InventoryItem::find($itemData['inventory_item_id']);
                
                $purchaseItem = PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'inventory_item_id' => $itemData['inventory_item_id'],
                    'item_name' => $inventoryItem->name,
                    'item_sku' => $inventoryItem->sku,
                    'description' => $inventoryItem->description,
                    'quantity_ordered' => $itemData['quantity_ordered'],
                    'unit_cost' => $itemData['unit_cost'],
                    'discount_percentage' => $itemData['discount_percentage'] ?? 0,
                    'tax_percentage' => $itemData['tax_percentage'] ?? 0,
                    'unit' => $inventoryItem->unit,
                    'expiry_date' => $itemData['expiry_date'],
                    'notes' => $itemData['notes'],
                ]);

                $purchaseItem->calculateTotals();
            }

            // Handle file attachments
            if ($request->hasFile('attachments')) {
                $attachments = [];
                foreach ($request->file('attachments') as $file) {
                    $filename = Str::slug($purchase->purchase_number) . '_doc_' . time() . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('purchases/documents', $filename, 'public');
                    $attachments[] = $path;
                }
                $purchase->update(['attachments' => $attachments]);
            }

            // Calculate totals
            $purchase->calculateTotals();

            DB::commit();

            return redirect()->route('admin.inventory.purchases.show', $purchase)
                            ->with('success', 'Purchase order created successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                            ->with('error', 'Error creating purchase order: ' . $e->getMessage())
                            ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Purchase $purchase)
    {
        $purchase->load(['supplier', 'purchaseItems.inventoryItem']);
        return view('admin.inventory.purchases.show', compact('purchase'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Purchase $purchase)
    {
        if ($purchase->status !== 'draft') {
            return redirect()->route('admin.inventory.purchases.show', $purchase)
                            ->with('error', 'Only draft purchases can be edited.');
        }

        $suppliers = Supplier::active()->orderBy('name')->get();
        $inventoryItems = InventoryItem::active()->orderBy('name')->get();
        $statuses = Purchase::STATUSES;
        $paymentStatuses = Purchase::PAYMENT_STATUSES;
        $paymentMethods = Purchase::PAYMENT_METHODS;

        $purchase->load('purchaseItems');

        return view('admin.inventory.purchases.edit', compact('purchase', 'suppliers', 'inventoryItems', 'statuses', 'paymentStatuses', 'paymentMethods'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Purchase $purchase)
    {
        if ($purchase->status !== 'draft') {
            return redirect()->route('admin.inventory.purchases.show', $purchase)
                            ->with('error', 'Only draft purchases can be edited.');
        }

        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_date' => 'required|date',
            'expected_delivery_date' => 'nullable|date|after_or_equal:purchase_date',
            'status' => 'required|in:' . implode(',', array_keys(Purchase::STATUSES)),
            'payment_status' => 'required|in:' . implode(',', array_keys(Purchase::PAYMENT_STATUSES)),
            'payment_method' => 'nullable|in:' . implode(',', array_keys(Purchase::PAYMENT_METHODS)),
            'reference_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
            'terms_conditions' => 'nullable|string|max:1000',
            'delivery_address' => 'nullable|string|max:500',
            'billing_address' => 'nullable|string|max:500',
            'shipping_cost' => 'nullable|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.inventory_item_id' => 'required|exists:inventory_items,id',
            'items.*.quantity_ordered' => 'required|integer|min:1',
            'items.*.unit_cost' => 'required|numeric|min:0',
            'items.*.discount_percentage' => 'nullable|numeric|min:0|max:100',
            'items.*.tax_percentage' => 'nullable|numeric|min:0|max:100',
            'items.*.expiry_date' => 'nullable|date|after:today',
            'items.*.notes' => 'nullable|string|max:500',
            'attachments.*' => 'nullable|file|mimes:pdf,doc,docx,jpeg,png,jpg|max:5120',
        ]);

        DB::beginTransaction();
        try {
            // Update purchase
            $purchase->update([
                'supplier_id' => $request->supplier_id,
                'purchase_date' => $request->purchase_date,
                'expected_delivery_date' => $request->expected_delivery_date,
                'status' => $request->status,
                'payment_status' => $request->payment_status,
                'payment_method' => $request->payment_method,
                'reference_number' => $request->reference_number,
                'notes' => $request->notes,
                'terms_conditions' => $request->terms_conditions,
                'delivery_address' => $request->delivery_address,
                'billing_address' => $request->billing_address,
                'shipping_cost' => $request->shipping_cost ?? 0,
            ]);

            // Delete existing items
            $purchase->purchaseItems()->delete();

            // Create new purchase items
            foreach ($request->items as $itemData) {
                $inventoryItem = InventoryItem::find($itemData['inventory_item_id']);
                
                $purchaseItem = PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'inventory_item_id' => $itemData['inventory_item_id'],
                    'item_name' => $inventoryItem->name,
                    'item_sku' => $inventoryItem->sku,
                    'description' => $inventoryItem->description,
                    'quantity_ordered' => $itemData['quantity_ordered'],
                    'unit_cost' => $itemData['unit_cost'],
                    'discount_percentage' => $itemData['discount_percentage'] ?? 0,
                    'tax_percentage' => $itemData['tax_percentage'] ?? 0,
                    'unit' => $inventoryItem->unit,
                    'expiry_date' => $itemData['expiry_date'],
                    'notes' => $itemData['notes'],
                ]);

                $purchaseItem->calculateTotals();
            }

            // Handle file attachments
            if ($request->hasFile('attachments')) {
                $existingAttachments = $purchase->attachments ?? [];
                $newAttachments = [];
                
                foreach ($request->file('attachments') as $file) {
                    $filename = Str::slug($purchase->purchase_number) . '_doc_' . time() . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('purchases/documents', $filename, 'public');
                    $newAttachments[] = $path;
                }
                
                $purchase->update(['attachments' => array_merge($existingAttachments, $newAttachments)]);
            }

            // Calculate totals
            $purchase->calculateTotals();

            DB::commit();

            return redirect()->route('admin.inventory.purchases.show', $purchase)
                            ->with('success', 'Purchase order updated successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                            ->with('error', 'Error updating purchase order: ' . $e->getMessage())
                            ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Purchase $purchase)
    {
        if ($purchase->status !== 'draft') {
            return redirect()->route('admin.inventory.purchases.index')
                            ->with('error', 'Only draft purchases can be deleted.');
        }

        // Delete attachments if they exist
        if ($purchase->attachments) {
            foreach ($purchase->attachments as $attachment) {
                if (Storage::disk('public')->exists($attachment)) {
                    Storage::disk('public')->delete($attachment);
                }
            }
        }

        $purchase->delete();

        return redirect()->route('admin.inventory.purchases.index')
                        ->with('success', 'Purchase order deleted successfully.');
    }

    /**
     * Approve purchase order
     */
    public function approve(Purchase $purchase)
    {
        if ($purchase->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Only pending purchases can be approved.'
            ]);
        }

        $purchase->approve($this->adminUser->name ?? 'Admin');

        return response()->json([
            'success' => true,
            'message' => 'Purchase order approved successfully.',
            'status' => $purchase->status
        ]);
    }

    /**
     * Mark as ordered
     */
    public function markAsOrdered(Purchase $purchase)
    {
        if ($purchase->status !== 'approved') {
            return response()->json([
                'success' => false,
                'message' => 'Only approved purchases can be marked as ordered.'
            ]);
        }

        $purchase->markAsOrdered();

        return response()->json([
            'success' => true,
            'message' => 'Purchase order marked as ordered.',
            'status' => $purchase->status
        ]);
    }

    /**
     * Mark as received
     */
    public function markAsReceived(Purchase $purchase)
    {
        if (!in_array($purchase->status, ['ordered', 'partially_received'])) {
            return response()->json([
                'success' => false,
                'message' => 'Only ordered or partially received purchases can be marked as received.'
            ]);
        }

        $purchase->markAsReceived($this->adminUser->name ?? 'Admin');

        return response()->json([
            'success' => true,
            'message' => 'Purchase order marked as received.',
            'status' => $purchase->status
        ]);
    }

    /**
     * Cancel purchase order
     */
    public function cancel(Purchase $purchase)
    {
        if (in_array($purchase->status, ['received', 'completed', 'cancelled'])) {
            return response()->json([
                'success' => false,
                'message' => 'This purchase order cannot be cancelled.'
            ]);
        }

        $purchase->cancel();

        return response()->json([
            'success' => true,
            'message' => 'Purchase order cancelled successfully.',
            'status' => $purchase->status
        ]);
    }

    /**
     * Get inventory items for AJAX
     */
    public function getInventoryItems()
    {
        $items = InventoryItem::active()
            ->select('id', 'name', 'sku', 'description', 'unit', 'price')
            ->orderBy('name')
            ->get();

        return response()->json($items);
    }
}
