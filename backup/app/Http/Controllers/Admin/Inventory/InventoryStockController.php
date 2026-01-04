<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use App\Models\InventoryItem;
use App\Models\InventoryStockMovement;
use Illuminate\Http\Request;
use Carbon\Carbon;

class InventoryStockController extends Controller
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
     * Display stock management dashboard
     */
    public function index(Request $request)
    {
        $query = InventoryItem::with(['stockMovements' => function($q) {
            $q->orderBy('movement_date', 'desc')->limit(5);
        }]);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by stock status
        if ($request->filled('stock_status')) {
            switch ($request->stock_status) {
                case 'low_stock':
                    $query->whereRaw('quantity <= min_quantity');
                    break;
                case 'out_of_stock':
                    $query->where('quantity', 0);
                    break;
                case 'overstocked':
                    $query->whereRaw('quantity > (min_quantity * 3)');
                    break;
            }
        }

        $inventoryItems = $query->orderBy('name')->paginate(20);

        // Get filter options
        $categories = InventoryItem::distinct()->pluck('category')->filter()->sort()->values();

        // Statistics
        $stats = [
            'total_items' => InventoryItem::count(),
            'low_stock_items' => InventoryItem::whereRaw('quantity <= min_quantity')->count(),
            'out_of_stock_items' => InventoryItem::where('quantity', 0)->count(),
            'total_value' => InventoryItem::sum(\DB::raw('quantity * price')),
            'recent_movements' => InventoryStockMovement::with('inventoryItem')
                ->orderBy('movement_date', 'desc')
                ->limit(10)
                ->get(),
        ];

        return view('admin.inventory.stock.index', compact('inventoryItems', 'categories', 'stats'));
    }

    /**
     * Show stock adjustment form
     */
    public function adjust(InventoryItem $item)
    {
        $movementTypes = InventoryStockMovement::MOVEMENT_TYPES;
        $referenceTypes = InventoryStockMovement::REFERENCE_TYPES;
        
        return view('admin.inventory.stock.adjust', compact('item', 'movementTypes', 'referenceTypes'));
    }

    /**
     * Process stock adjustment
     */
    public function processAdjustment(Request $request, InventoryItem $item)
    {
        $request->validate([
            'movement_type' => 'required|in:' . implode(',', array_keys(InventoryStockMovement::MOVEMENT_TYPES)),
            'quantity' => 'required|integer|min:1',
            'movement_date' => 'required|date|before_or_equal:today',
            'notes' => 'nullable|string|max:1000',
            'reference_type' => 'nullable|in:' . implode(',', array_keys(InventoryStockMovement::REFERENCE_TYPES)),
            'reference_number' => 'nullable|string|max:255',
            'unit_cost' => 'nullable|numeric|min:0',
            'location_from' => 'nullable|string|max:255',
            'location_to' => 'nullable|string|max:255',
        ]);

        // Check if quantity is available for outbound movements
        if (in_array($request->movement_type, ['out', 'damage', 'loss', 'transfer']) && 
            $item->quantity < $request->quantity) {
            return redirect()->back()
                ->with('error', 'Insufficient stock. Available quantity: ' . $item->quantity);
        }

        // Create stock movement
        $movement = InventoryStockMovement::createMovement(
            $item->id,
            $request->movement_type,
            $request->quantity,
            [
                'movement_date' => $request->movement_date,
                'notes' => $request->notes,
                'reference_type' => $request->reference_type,
                'reference_number' => $request->reference_number,
                'unit_cost' => $request->unit_cost,
                'total_cost' => $request->unit_cost ? $request->unit_cost * $request->quantity : null,
                'location_from' => $request->location_from,
                'location_to' => $request->location_to,
                'performed_by' => $this->adminUser->name ?? 'Admin',
            ]
        );

        return redirect()->route('admin.inventory.stock.history', $item)
            ->with('success', 'Stock adjustment processed successfully.');
    }

    /**
     * Show stock movement history for an item
     */
    public function history(Request $request, InventoryItem $item)
    {
        $query = $item->stockMovements()->orderBy('movement_date', 'desc');

        // Filter by movement type
        if ($request->filled('movement_type')) {
            $query->where('movement_type', $request->movement_type);
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->where('movement_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('movement_date', '<=', $request->end_date);
        }

        $movements = $query->paginate(20);

        $movementTypes = InventoryStockMovement::MOVEMENT_TYPES;

        return view('admin.inventory.stock.history', compact('item', 'movements', 'movementTypes'));
    }

    /**
     * Show all stock movements
     */
    public function movements(Request $request)
    {
        $query = InventoryStockMovement::with('inventoryItem')->orderBy('movement_date', 'desc');

        // Filter by movement type
        if ($request->filled('movement_type')) {
            $query->where('movement_type', $request->movement_type);
        }

        // Filter by item
        if ($request->filled('item_id')) {
            $query->where('inventory_item_id', $request->item_id);
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->where('movement_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('movement_date', '<=', $request->end_date);
        }

        $movements = $query->paginate(20);

        $movementTypes = InventoryStockMovement::MOVEMENT_TYPES;
        $inventoryItems = InventoryItem::orderBy('name')->get();

        return view('admin.inventory.stock.movements', compact('movements', 'movementTypes', 'inventoryItems'));
    }

    /**
     * Get stock statistics
     */
    public function statistics()
    {
        $stats = [
            'total_items' => InventoryItem::count(),
            'low_stock_items' => InventoryItem::whereRaw('quantity <= min_quantity')->count(),
            'out_of_stock_items' => InventoryItem::where('quantity', 0)->count(),
            'total_value' => InventoryItem::sum(\DB::raw('quantity * price')),
            'movements_today' => InventoryStockMovement::whereDate('movement_date', today())->count(),
            'movements_this_month' => InventoryStockMovement::whereMonth('movement_date', now()->month)->count(),
            'movements_by_type' => InventoryStockMovement::selectRaw('movement_type, COUNT(*) as count')
                ->groupBy('movement_type')
                ->pluck('count', 'movement_type'),
            'recent_movements' => InventoryStockMovement::with('inventoryItem')
                ->orderBy('movement_date', 'desc')
                ->limit(10)
                ->get(),
        ];

        return response()->json($stats);
    }

    /**
     * Bulk stock adjustment
     */
    public function bulkAdjust(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:inventory_items,id',
            'items.*.movement_type' => 'required|in:' . implode(',', array_keys(InventoryStockMovement::MOVEMENT_TYPES)),
            'items.*.quantity' => 'required|integer|min:1',
            'movement_date' => 'required|date|before_or_equal:today',
            'notes' => 'nullable|string|max:1000',
        ]);

        $processed = 0;
        $errors = [];

        foreach ($request->items as $itemData) {
            try {
                $item = InventoryItem::findOrFail($itemData['id']);
                
                // Check stock availability for outbound movements
                if (in_array($itemData['movement_type'], ['out', 'damage', 'loss', 'transfer']) && 
                    $item->quantity < $itemData['quantity']) {
                    $errors[] = "Insufficient stock for {$item->name}. Available: {$item->quantity}";
                    continue;
                }

                InventoryStockMovement::createMovement(
                    $item->id,
                    $itemData['movement_type'],
                    $itemData['quantity'],
                    [
                        'movement_date' => $request->movement_date,
                        'notes' => $request->notes,
                        'performed_by' => $this->adminUser->name ?? 'Admin',
                    ]
                );

                $processed++;
            } catch (\Exception $e) {
                $errors[] = "Error processing {$item->name}: " . $e->getMessage();
            }
        }

        $message = "Bulk adjustment completed. {$processed} items processed.";
        if (!empty($errors)) {
            $message .= " Errors: " . implode(', ', $errors);
        }

        return redirect()->route('admin.inventory.stock.index')
            ->with($errors ? 'warning' : 'success', $message);
    }
}
