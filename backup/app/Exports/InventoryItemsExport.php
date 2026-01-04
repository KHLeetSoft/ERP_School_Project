<?php

namespace App\Exports;

use App\Models\InventoryItem;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InventoryItemsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $filters;
    protected $isSample;

    public function __construct($filters = [], $isSample = false)
    {
        $this->filters = $filters;
        $this->isSample = $isSample;
    }

    public function collection()
    {
        if ($this->isSample) {
            return collect($this->filters);
        }

        $query = InventoryItem::query();

        // Apply filters
        if (isset($this->filters['search']) && $this->filters['search']) {
            $search = $this->filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhere('supplier', 'like', "%{$search}%");
            });
        }

        if (isset($this->filters['category']) && $this->filters['category']) {
            $query->where('category', $this->filters['category']);
        }

        if (isset($this->filters['status']) && $this->filters['status']) {
            if ($this->filters['status'] === 'low_stock') {
                $query->whereRaw('quantity <= min_quantity');
            } elseif ($this->filters['status'] === 'expired') {
                $query->where('expiry_date', '<', now());
            } elseif ($this->filters['status'] === 'expiring_soon') {
                $query->where('expiry_date', '<=', now()->addDays(30))
                      ->where('expiry_date', '>', now());
            }
        }

        return $query->orderBy('name')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Description',
            'Category',
            'SKU',
            'Price (₹)',
            'Quantity',
            'Min Quantity',
            'Unit',
            'Supplier',
            'Purchase Date',
            'Expiry Date',
            'Location',
            'Notes',
            'Status',
            'Created At',
            'Updated At'
        ];
    }

    public function map($item): array
    {
        return [
            $item->id,
            $item->name,
            $item->description,
            $item->category,
            $item->sku,
            $item->price,
            $item->quantity,
            $item->min_quantity,
            $item->unit,
            $item->supplier,
            $item->purchase_date?->format('Y-m-d'),
            $item->expiry_date?->format('Y-m-d'),
            $item->location,
            $item->notes,
            $item->is_active ? 'Active' : 'Inactive',
            $item->created_at->format('Y-m-d H:i:s'),
            $item->updated_at->format('Y-m-d H:i:s'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,   // ID
            'B' => 25,  // Name
            'C' => 40,  // Description
            'D' => 15,  // Category
            'E' => 15,  // SKU
            'F' => 12,  // Price
            'G' => 10,  // Quantity
            'H' => 12,  // Min Quantity
            'I' => 10,  // Unit
            'J' => 20,  // Supplier
            'K' => 15,  // Purchase Date
            'L' => 15,  // Expiry Date
            'M' => 20,  // Location
            'N' => 30,  // Notes
            'O' => 10,  // Status
            'P' => 20,  // Created At
            'Q' => 20,  // Updated At
        ];
    }
}
