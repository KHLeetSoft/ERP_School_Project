<?php

namespace App\Http\Controllers\Admin\Canteen;

use App\Http\Controllers\Controller;
use App\Models\CanteenItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Yajra\DataTables\Facades\DataTables;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = CanteenItem::query();

            if ($search = (string)$request->input('q')) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }
            if ($request->filled('status')) {
                $status = $request->input('status');
                if ($status === 'active') { $query->where('is_active', 1); }
                if ($status === 'inactive') { $query->where('is_active', 0); }
            }
            if ($request->filled('min_price')) { $query->where('price', '>=', (float)$request->input('min_price')); }
            if ($request->filled('max_price')) { $query->where('price', '<=', (float)$request->input('max_price')); }

            $rows = $query->orderBy('id', 'desc')->get();

            return DataTables::of($rows)
                ->addColumn('checkbox', function (CanteenItem $item) {
                    return '<input type="checkbox" name="ids[]" value="' . $item->id . '" class="row-check">';
                })
                ->addColumn('id', function (CanteenItem $item) {
                    return (string)$item->id;
                })
                ->addColumn('name', function (CanteenItem $item) {
                    $showUrl = route('admin.canteen.items.show', $item);
                    return '<a href="' . $showUrl . '">' . e($item->name) . '</a>';
                })
                ->addColumn('price', function (CanteenItem $item) {
                    return number_format($item->price, 2);
                })
                ->addColumn('stock_quantity', function (CanteenItem $item) {
                    return (string)$item->stock_quantity;
                })
                ->addColumn('status', function (CanteenItem $item) {
                    return '<span class="badge ' . ($item->is_active ? 'bg-success' : 'bg-secondary') . '">' . ($item->is_active ? 'Active' : 'Inactive') . '</span>';
                })
                ->addColumn('action', function (CanteenItem $item) {
                    $editUrl = route('admin.canteen.items.edit', $item);

                    $toggleIcon = $item->is_active ? 'bx-toggle-right' : 'bx-toggle-left';
                    $toggleTitle = $item->is_active ? 'Deactivate' : 'Activate';
                    $toggleColor = $item->is_active ? 'text-secondary' : 'text-success';
                    $toggleForm = '<form action="' . route('admin.canteen.items.toggle-status', $item) . '" method="POST" class="me-2 d-inline">'
                        . csrf_field() .
                        '<button type="submit" class="btn btn-link p-0 ' . $toggleColor . '" title="' . $toggleTitle . '"><i class="bx ' . $toggleIcon . '"></i></button>' .
                    '</form>';

                    $editBtn = '<a href="' . $editUrl . '" class="text-primary me-2" title="Edit"><i class="bx bxs-edit"></i></a>';

                    $deleteForm = '<form action="' . route('admin.canteen.items.destroy', $item) . '" method="POST" class="d-inline" onsubmit="return confirm(\'Delete this item?\')">'
                        . csrf_field() . method_field('DELETE') .
                        '<button type="submit" class="btn btn-link p-0 text-danger" title="Delete"><i class="bx bx-trash"></i></button>' .
                    '</form>';

                    $viewUrl = route('admin.canteen.items.show', $item);
                    $viewBtn = '<a href="' . $viewUrl . '" class="text-info me-2" title="View"><i class="bx bx-show"></i></a>';

                    return '<div class="d-flex justify-content-start align-items-center">' . $viewBtn . $editBtn . $toggleForm . $deleteForm . '</div>';
                })
                ->rawColumns(['checkbox', 'name', 'status', 'action'])
                ->make(true);
        }

        return view('admin.canteen.items.index');
    }

    public function data(Request $request)
    {
        $columns = [
            'id', 'name', 'price', 'stock_quantity', 'is_active'
        ];

        $baseQuery = CanteenItem::query();

        // Filters
        if ($search = (string)$request->input('q')) {
            $baseQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        if ($request->filled('status')) {
            $status = $request->input('status');
            if ($status === 'active') { $baseQuery->where('is_active', 1); }
            if ($status === 'inactive') { $baseQuery->where('is_active', 0); }
        }
        if ($request->filled('min_price')) { $baseQuery->where('price', '>=', (float)$request->input('min_price')); }
        if ($request->filled('max_price')) { $baseQuery->where('price', '<=', (float)$request->input('max_price')); }

        $recordsTotal = CanteenItem::count();

        // Global search
        $query = clone $baseQuery;
        if ($globalSearch = $request->input('search.value')) {
            $query->where(function ($q) use ($globalSearch) {
                $q->where('name', 'like', "%{$globalSearch}%")
                  ->orWhere('description', 'like', "%{$globalSearch}%");
            });
        }

        // Ordering
        $order = $request->input('order', []);
        if (is_array($order) && count($order) > 0) {
            foreach ($order as $ord) {
                $colIdx = (int)($ord['column'] ?? 0);
                $dir = strtolower($ord['dir'] ?? 'asc') === 'desc' ? 'desc' : 'asc';
                $colName = $columns[$colIdx - 1] ?? 'id'; // -1 because first column is checkbox
                $query->orderBy($colName, $dir);
            }
        } else {
            $query->orderByDesc('id');
        }

        $start = (int)$request->input('start', 0);
        $length = (int)$request->input('length', 10);
        if ($length <= 0) { $length = 10; }

        $recordsFiltered = (clone $query)->count();
        $items = $query->skip($start)->take($length)->get();

        $data = $items->map(function (CanteenItem $item) {
            $statusBadge = '<span class="badge ' . ($item->is_active ? 'bg-success' : 'bg-secondary') . '">' . ($item->is_active ? 'Active' : 'Inactive') . '</span>';
            $toggleBtn = '<form action="' . route('admin.canteen.items.toggle-status', $item) . '" method="POST">' . csrf_field() . '<button type="submit" class="btn btn-sm ' . ($item->is_active ? 'btn-outline-secondary' : 'btn-outline-success') . '">' . ($item->is_active ? 'Deactivate' : 'Activate') . '</button></form>';
            $editUrl = route('admin.canteen.items.edit', $item);
            $showUrl = route('admin.canteen.items.show', $item);
            $deleteForm = '<form action="' . route('admin.canteen.items.destroy', $item) . '" method="POST" onsubmit="return confirm(\'Delete this item?\')">' . csrf_field() . method_field('DELETE') . '<button type="submit" class="btn btn-sm btn-danger">Delete</button></form>';
            return [
                '<input type="checkbox" name="ids[]" value="' . $item->id . '" class="row-check">',
                (string)$item->id,
                '<a href="' . $showUrl . '">' . e($item->name) . '</a>',
                number_format($item->price, 2),
                (string)$item->stock_quantity,
                $statusBadge,
                '<div class="d-flex gap-2">' . $toggleBtn . ' <a href="' . $editUrl . '" class="btn btn-sm btn-warning">Edit</a> ' . $deleteForm . '</div>',
            ];
        });

        return response()->json([
            'draw' => (int)$request->input('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.canteen.items.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'description' => ['nullable', 'string'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $item = CanteenItem::create($validated);

        return redirect()
            ->route('admin.canteen.items.index')
            ->with('success', 'Item created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(CanteenItem $canteenItem)
    {
        return view('admin.canteen.items.show', ['item' => $canteenItem]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CanteenItem $canteenItem)
    {
        return view('admin.canteen.items.edit', ['item' => $canteenItem]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CanteenItem $canteenItem)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'description' => ['nullable', 'string'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $canteenItem->update($validated);

        return redirect()
            ->route('admin.canteen.items.index')
            ->with('success', 'Item updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CanteenItem $canteenItem)
    {
        $canteenItem->delete();
        return redirect()->back()->with('success', 'Item deleted successfully');
    }

    public function toggleStatus(CanteenItem $canteenItem)
    {
        $canteenItem->is_active = !$canteenItem->is_active;
        $canteenItem->save();
        return redirect()->back()->with('success', 'Status updated');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!is_array($ids) || empty($ids)) {
            return redirect()->back()->with('error', 'No items selected');
        }
        CanteenItem::whereIn('id', $ids)->delete();
        return redirect()->back()->with('success', 'Selected items deleted');
    }

    public function export(Request $request): StreamedResponse
    {
        $filename = 'canteen_items_' . now()->format('Ymd_His') . '.csv';

        $query = CanteenItem::query();
        if ($search = $request->string('q')->toString()) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }
        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float)$request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float)$request->max_price);
        }

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        return response()->stream(function () use ($query) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['id', 'name', 'price', 'stock_quantity', 'is_active', 'description']);
            $query->orderBy('id')->chunk(500, function ($chunk) use ($out) {
                foreach ($chunk as $item) {
                    fputcsv($out, [
                        $item->id,
                        $item->name,
                        $item->price,
                        $item->stock_quantity,
                        $item->is_active ? 1 : 0,
                        $item->description,
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
            'Content-Disposition' => 'attachment; filename="canteen_items_sample.csv"',
        ];
        return response()->stream(function () {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['name', 'price', 'stock_quantity', 'is_active', 'description']);
            fputcsv($out, ['Burger', '75.00', '50', '1', 'Tasty veg burger']);
            fputcsv($out, ['Juice', '30.00', '120', '1', 'Fresh mango juice']);
            fclose($out);
        }, 200, $headers);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt', 'max:10240'],
        ]);

        $file = $request->file('file');
        $created = 0; $updated = 0; $skipped = 0;

        if (($handle = fopen($file->getRealPath(), 'r')) !== false) {
            $header = fgetcsv($handle);
            if (!$header) {
                return redirect()->back()->with('error', 'Empty CSV');
            }
            $normalized = array_map(fn($h) => strtolower(trim((string)$h)), $header);
            $map = array_flip($normalized);

            $required = ['name','price','stock_quantity','is_active','description'];
            foreach ($required as $col) {
                if (!array_key_exists($col, $map)) {
                    fclose($handle);
                    return redirect()->back()->with('error', "Missing column: {$col}");
                }
            }

            while (($row = fgetcsv($handle)) !== false) {
                try {
                    $name = (string)($row[$map['name']] ?? '');
                    if ($name === '') { $skipped++; continue; }
                    $data = [
                        'name' => $name,
                        'price' => (float)($row[$map['price']] ?? 0),
                        'stock_quantity' => (int)($row[$map['stock_quantity']] ?? 0),
                        'is_active' => (int)($row[$map['is_active']] ?? 1) ? 1 : 0,
                        'description' => (string)($row[$map['description']] ?? null),
                    ];

                    $item = CanteenItem::firstOrNew(['name' => $data['name']]);
                    $item->fill($data);
                    $item->exists ? $updated++ : $created++;
                    $item->save();
                } catch (\Throwable $e) {
                    $skipped++;
                }
            }
            fclose($handle);
        }

        return redirect()->back()->with('success', "Import finished. Created: {$created}, Updated: {$updated}, Skipped: {$skipped}");
    }
}
