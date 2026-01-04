<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use App\Models\InventoryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InventoryItemsExport;
use App\Imports\InventoryItemsImport;

class InventoryItemController extends Controller
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
        $query = InventoryItem::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhere('supplier', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'low_stock') {
                $query->lowStock();
            } elseif ($request->status === 'expired') {
                $query->expired();
            } elseif ($request->status === 'expiring_soon') {
                $query->expiringSoon();
            } elseif ($request->status === 'active') {
                $query->active();
            }
        }

        $inventoryItems = $query->orderBy('created_at', 'desc')->paginate(15);

        // Get categories for filter
        $categories = InventoryItem::distinct()->pluck('category')->filter()->sort()->values();

        // Statistics
        $stats = [
            'total_items' => InventoryItem::count(),
            'active_items' => InventoryItem::active()->count(),
            'low_stock_items' => InventoryItem::lowStock()->count(),
            'expired_items' => InventoryItem::expired()->count(),
            'expiring_soon' => InventoryItem::expiringSoon()->count(),
        ];

        return view('admin.inventory.items.index', compact('inventoryItems', 'categories', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.inventory.items.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'sku' => 'required|string|max:255|unique:inventory_items,sku',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'min_quantity' => 'required|integer|min:0',
            'unit' => 'nullable|string|max:50',
            'supplier' => 'nullable|string|max:255',
            'purchase_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after:purchase_date',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = Str::slug($request->name) . '_' . time() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('inventory_items', $imageName, 'public');
            $data['image'] = $imagePath;
        }

        InventoryItem::create($data);

        return redirect()->route('admin.inventory.items.index')
                        ->with('success', 'Inventory item created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(InventoryItem $inventoryItem)
    {
        return view('admin.inventory.items.show', compact('inventoryItem'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InventoryItem $inventoryItem)
    {
        return view('admin.inventory.items.edit', compact('inventoryItem'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InventoryItem $inventoryItem)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'sku' => 'required|string|max:255|unique:inventory_items,sku,' . $inventoryItem->id,
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'min_quantity' => 'required|integer|min:0',
            'unit' => 'nullable|string|max:50',
            'supplier' => 'nullable|string|max:255',
            'purchase_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after:purchase_date',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($inventoryItem->image && Storage::disk('public')->exists($inventoryItem->image)) {
                Storage::disk('public')->delete($inventoryItem->image);
            }

            $image = $request->file('image');
            $imageName = Str::slug($request->name) . '_' . time() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('inventory_items', $imageName, 'public');
            $data['image'] = $imagePath;
        }

        $inventoryItem->update($data);

        return redirect()->route('admin.inventory.items.index')
                        ->with('success', 'Inventory item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InventoryItem $inventoryItem)
    {
        // Delete image if exists
        if ($inventoryItem->image && Storage::disk('public')->exists($inventoryItem->image)) {
            Storage::disk('public')->delete($inventoryItem->image);
        }

        $inventoryItem->delete();

        return redirect()->route('admin.inventory.items.index')
                        ->with('success', 'Inventory item deleted successfully.');
    }

    /**
     * Toggle active status
     */
    public function toggleStatus(InventoryItem $inventoryItem)
    {
        $inventoryItem->update(['is_active' => !$inventoryItem->is_active]);

        $status = $inventoryItem->is_active ? 'activated' : 'deactivated';
        return response()->json([
            'success' => true,
            'message' => "Inventory item {$status} successfully.",
            'is_active' => $inventoryItem->is_active
        ]);
    }

    /**
     * Export inventory items to Excel
     */
    public function export(Request $request)
    {
        $format = $request->get('format', 'xlsx');
        $filename = 'inventory_items_' . now()->format('Y-m-d_H-i-s') . '.' . $format;
        
        return Excel::download(new InventoryItemsExport($request->all()), $filename);
    }

    /**
     * Show import form
     */
    public function importForm()
    {
        return view('admin.inventory.items.import');
    }

    /**
     * Import inventory items from Excel
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
            'update_existing' => 'boolean'
        ]);

        try {
            $updateExisting = $request->boolean('update_existing', false);
            
            Excel::import(new InventoryItemsImport($updateExisting), $request->file('file'));

            return redirect()->route('admin.inventory.items.index')
                ->with('success', 'Inventory items imported successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    /**
     * Download sample import file
     */
    public function downloadSample()
    {
        $sampleData = [
            [
                'name' => 'Sample Item 1',
                'description' => 'This is a sample item description',
                'category' => 'Electronics',
                'sku' => 'SAMPLE-001',
                'price' => 1000.00,
                'quantity' => 10,
                'min_quantity' => 2,
                'unit' => 'pieces',
                'supplier' => 'Sample Supplier',
                'purchase_date' => now()->format('Y-m-d'),
                'expiry_date' => now()->addYear()->format('Y-m-d'),
                'location' => 'Sample Location',
                'notes' => 'Sample notes',
                'is_active' => 1
            ]
        ];

        return Excel::download(new InventoryItemsExport($sampleData, true), 'inventory_items_sample.xlsx');
    }
}
