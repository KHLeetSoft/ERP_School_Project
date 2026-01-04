<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PurchaseExport;
use Yajra\DataTables\Facades\DataTables;


class PurchaseController extends Controller
{ 
    public function index()
    {
        $purchases = Purchase::latest()->paginate(10);
        return view('superadmin.purchases.index', compact('purchases'));
    }
    
    public function serverSideDataTable(Request $request)
    {
        if ($request->ajax()) {
            $query = Purchase::with(['school', 'purchaseItems'])->latest();

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('school_name', function ($purchase) {
                    return $purchase->school->name ?? '-';
                })
                ->addColumn('item_name', function ($purchase) {
                    if ($purchase->purchaseItems->count() > 0) {
                        $firstItem = $purchase->purchaseItems->first();
                        $itemName = $firstItem->item_name ?? 'N/A';
                        if ($purchase->purchaseItems->count() > 1) {
                            $itemName .= ' (+' . ($purchase->purchaseItems->count() - 1) . ' more)';
                        }
                        return $itemName;
                    }
                    return 'No items';
                })
                ->addColumn('quantity', function ($purchase) {
                    if ($purchase->purchaseItems->count() > 0) {
                        return $purchase->purchaseItems->sum('quantity_ordered');
                    }
                    return '0';
                })
                ->addColumn('price', function ($purchase) {
                    return '₹' . number_format($purchase->total_amount, 2);
                })
                ->addColumn('purchase_date', function ($purchase) {
                    return $purchase->purchase_date ? $purchase->purchase_date->format('Y-m-d') : '-';
                })
                ->addColumn('status', function ($purchase) {
                    $badgeClass = match($purchase->status) {
                        'draft' => 'secondary',
                        'pending' => 'warning',
                        'approved' => 'info',
                        'ordered' => 'primary',
                        'received' => 'success',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        default => 'secondary'
                    };
                    return '<span class="badge bg-' . $badgeClass . '">' . ucfirst($purchase->status ?? 'Pending') . '</span>';
                })
                ->addColumn('action', function ($purchase) {
                    $show = route('superadmin.purchases.show', $purchase->id);
                    $edit = route('superadmin.purchases.edit', $purchase->id);
                    $delete = route('superadmin.purchases.destroy', $purchase->id);

                    return '
                        <div class="btn-group" role="group" aria-label="Actions">
                            <a href="' . $show . '" class="btn btn-sm btn-outline-info" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="' . $edit . '" class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form method="POST" action="' . $delete . '" style="display:inline;" onsubmit="return confirm(\'Are you sure?\')">
                                ' . csrf_field() . method_field('DELETE') . '
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    ';
                })

                ->rawColumns(['status', 'action'])
                ->make(true);
        }
    }


    public function create()
    {
        return view('superadmin.purchases.create');
    }

  public function store(Request $request)
{
    $request->validate([
        'school_id' => 'required|exists:schools,id',
        'item_name' => 'required|string',
        'quantity' => 'required|integer|min:1',
        'price' => 'required|numeric|min:0',
        'purchase_date' => 'required|date',
        'status' => 'required|in:Pending,Completed',
    ]);

    Purchase::create($request->all());

    return redirect()->route('superadmin.purchases.index')->with('success', 'Purchase created successfully.');
}

    public function show(Purchase $purchase)
    {
        return view('superadmin.purchases.show', compact('purchase'));
    }

    public function edit(Purchase $purchase)
    {
        return view('superadmin.purchases.edit', compact('purchase'));
    }

    public function update(Request $request, Purchase $purchase)
{
    $request->validate([
        'school_id' => 'required|exists:schools,id',
        'item_name' => 'required|string',
        'quantity' => 'required|integer|min:1',
        'price' => 'required|numeric|min:0',
        'purchase_date' => 'required|date',
        'status' => 'required|in:Pending,Completed',
    ]);

    $purchase->update($request->all());

    return redirect()->route('superadmin.purchases.index')->with('success', 'Purchase updated successfully.');
}

    public function destroy(Purchase $purchase)
    {
        $purchase->delete();
        return redirect()->route('superadmin.purchases.index')->with('success', 'Purchase deleted.');
    }
}
