<?php

namespace App\Http\Controllers\Admin\Canteen;

use App\Http\Controllers\Controller;
use App\Models\CanteenSale;
use App\Models\CanteenItem;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Yajra\DataTables\Facades\DataTables;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = CanteenSale::with('item');

            if ($request->filled('item_id')) { $query->where('canteen_item_id', $request->integer('item_id')); }
            if ($request->filled('buyer_type')) { $query->where('buyer_type', $request->input('buyer_type')); }
            if ($search = (string)$request->input('q')) {
                $query->whereHas('item', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            }
            if ($request->filled('date_from')) { $query->whereDate('sold_at', '>=', $request->input('date_from')); }
            if ($request->filled('date_to')) { $query->whereDate('sold_at', '<=', $request->input('date_to')); }

            $rows = $query->orderByDesc('sold_at')->orderByDesc('id')->get();

            return DataTables::of($rows)
                ->addColumn('checkbox', fn(CanteenSale $s) => '<input type="checkbox" name="ids[]" value="' . $s->id . '" class="row-check">')
                ->addColumn('id', fn(CanteenSale $s) => (string)$s->id)
                ->addColumn('item', fn(CanteenSale $s) => e(optional($s->item)->name) ?: '-')
                ->addColumn('quantity', fn(CanteenSale $s) => (string)$s->quantity)
                ->addColumn('unit_price', fn(CanteenSale $s) => number_format($s->unit_price, 2))
                ->addColumn('total_amount', fn(CanteenSale $s) => number_format($s->total_amount, 2))
                ->addColumn('buyer', fn(CanteenSale $s) => e($s->buyer_type ?: '-') )
                ->addColumn('sold_at', fn(CanteenSale $s) => optional($s->sold_at)->format('Y-m-d H:i'))
                ->addColumn('action', function (CanteenSale $s) {
                    $viewUrl = route('admin.canteen.sales.show', $s);
                    $editUrl = route('admin.canteen.sales.edit', $s);
                    $deleteForm = '<form action="' . route('admin.canteen.sales.destroy', $s) . '" method="POST" class="d-inline" onsubmit="return confirm(\'Delete this sale?\')">' . csrf_field() . method_field('DELETE') . '<button type="submit" class="btn btn-link p-0 text-danger" title="Delete"><i class="bx bx-trash"></i></button></form>';
                    return '<div class="d-flex justify-content-start align-items-center">'
                        . '<a href="' . $viewUrl . '" class="text-info me-2" title="View"><i class="bx bx-show"></i></a>'
                        . '<a href="' . $editUrl . '" class="text-primary me-2" title="Edit"><i class="bx bxs-edit"></i></a>'
                        . $deleteForm
                    . '</div>';
                })
                ->rawColumns(['checkbox','action'])
                ->make(true);
        }

        $items = CanteenItem::orderBy('name')->get(['id','name']);
        return view('admin.canteen.sales.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $items = CanteenItem::orderBy('name')->get(['id','name','price']);
        return view('admin.canteen.sales.create', compact('items'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'canteen_item_id' => ['required','exists:canteen_items,id'],
            'quantity' => ['required','integer','min:1'],
            'unit_price' => ['required','numeric','min:0'],
            'sold_at' => ['nullable','date'],
            'buyer_type' => ['nullable','string','max:50'],
            'buyer_id' => ['nullable','integer'],
            'notes' => ['nullable','string'],
        ]);

        $validated['total_amount'] = (float)$validated['unit_price'] * (int)$validated['quantity'];
        CanteenSale::create($validated);
        return redirect()->route('admin.canteen.sales.index')->with('success','Sale recorded');
    }

    /**
     * Display the specified resource.
     */
    public function show(CanteenSale $canteenSale)
    {
        $sale = $canteenSale->load('item');
        return view('admin.canteen.sales.show', compact('sale'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CanteenSale $canteenSale)
    {
        $items = CanteenItem::orderBy('name')->get(['id','name','price']);
        $sale = $canteenSale;
        return view('admin.canteen.sales.edit', compact('sale','items'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CanteenSale $canteenSale)
    {
        $validated = $request->validate([
            'canteen_item_id' => ['required','exists:canteen_items,id'],
            'quantity' => ['required','integer','min:1'],
            'unit_price' => ['required','numeric','min:0'],
            'sold_at' => ['nullable','date'],
            'buyer_type' => ['nullable','string','max:50'],
            'buyer_id' => ['nullable','integer'],
            'notes' => ['nullable','string'],
        ]);
        $validated['total_amount'] = (float)$validated['unit_price'] * (int)$validated['quantity'];
        $canteenSale->update($validated);
        return redirect()->route('admin.canteen.sales.index')->with('success','Sale updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CanteenSale $canteenSale)
    {
        $canteenSale->delete();
        return redirect()->back()->with('success','Sale deleted');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!is_array($ids) || empty($ids)) {
            return redirect()->back()->with('error', 'No sales selected');
        }
        CanteenSale::whereIn('id', $ids)->delete();
        return redirect()->back()->with('success','Selected sales deleted');
    }

    public function export(Request $request): StreamedResponse
    {
        $filename = 'canteen_sales_' . now()->format('Ymd_His') . '.csv';
        $query = CanteenSale::with('item');
        if ($request->filled('item_id')) { $query->where('canteen_item_id', $request->integer('item_id')); }
        if ($request->filled('buyer_type')) { $query->where('buyer_type', $request->input('buyer_type')); }
        if ($search = (string)$request->input('q')) {
            $query->whereHas('item', function($q) use ($search) { $q->where('name','like',"%{$search}%"); });
        }
        if ($request->filled('date_from')) { $query->whereDate('sold_at', '>=', $request->input('date_from')); }
        if ($request->filled('date_to')) { $query->whereDate('sold_at', '<=', $request->input('date_to')); }

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];
        return response()->stream(function () use ($query) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['id','sold_at','item','quantity','unit_price','total_amount','buyer_type','buyer_id','notes']);
            $query->orderBy('sold_at','desc')->chunk(500, function($chunk) use ($out) {
                foreach ($chunk as $s) {
                    fputcsv($out, [
                        $s->id,
                        optional($s->sold_at)->format('Y-m-d H:i:s'),
                        optional($s->item)->name,
                        $s->quantity,
                        $s->unit_price,
                        $s->total_amount,
                        $s->buyer_type,
                        $s->buyer_id,
                        $s->notes,
                    ]);
                }
            });
            fclose($out);
        }, 200, $headers);
    }

    public function sample(): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="canteen_sales_sample.csv"',
        ];
        return response()->stream(function () {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['canteen_item_id','quantity','unit_price','sold_at','buyer_type','buyer_id','notes']);
            fputcsv($out, [1, 2, 50.00, now()->format('Y-m-d H:i:s'), 'student', 1001, 'example']);
            fclose($out);
        }, 200, $headers);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => ['required','file','mimes:csv,txt','max:10240']
        ]);
        $file = $request->file('file');
        $created=0; $updated=0; $skipped=0;
        if (($handle = fopen($file->getRealPath(), 'r')) !== false) {
            $header = fgetcsv($handle);
            if (!$header) { return redirect()->back()->with('error','Empty CSV'); }
            $normalized = array_map(fn($h) => strtolower(trim((string)$h)), $header);
            $map = array_flip($normalized);
            $required = ['canteen_item_id','quantity','unit_price','sold_at','buyer_type','buyer_id','notes'];
            foreach ($required as $col) { if (!array_key_exists($col, $map)) { fclose($handle); return redirect()->back()->with('error', "Missing column: {$col}"); } }
            while (($row = fgetcsv($handle)) !== false) {
                try {
                    $data = [
                        'canteen_item_id' => (int)($row[$map['canteen_item_id']] ?? 0),
                        'quantity' => (int)($row[$map['quantity']] ?? 1),
                        'unit_price' => (float)($row[$map['unit_price']] ?? 0),
                        'sold_at' => ($row[$map['sold_at']] ?? null),
                        'buyer_type' => (string)($row[$map['buyer_type']] ?? null),
                        'buyer_id' => (int)($row[$map['buyer_id']] ?? null),
                        'notes' => (string)($row[$map['notes']] ?? null),
                    ];
                    if ($data['canteen_item_id'] <= 0) { $skipped++; continue; }
                    $data['total_amount'] = $data['unit_price'] * $data['quantity'];
                    CanteenSale::create($data);
                    $created++;
                } catch (\Throwable $e) { $skipped++; }
            }
            fclose($handle);
        }
        return redirect()->back()->with('success', "Import finished. Created: {$created}, Skipped: {$skipped}");
    }
}
