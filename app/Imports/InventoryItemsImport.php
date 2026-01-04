<?php

namespace App\Imports;

use App\Models\InventoryItem;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Validation\Rule;

class InventoryItemsImport implements ToModel, WithHeadingRow, WithValidation, WithBatchInserts, WithChunkReading
{
    protected $updateExisting;

    public function __construct($updateExisting = false)
    {
        $this->updateExisting = $updateExisting;
    }

    public function model(array $row)
    {
        $data = [
            'name' => $row['name'],
            'description' => $row['description'] ?? null,
            'category' => $row['category'] ?? null,
            'sku' => $row['sku'],
            'price' => $row['price'] ?? 0,
            'quantity' => $row['quantity'] ?? 0,
            'min_quantity' => $row['min_quantity'] ?? 0,
            'unit' => $row['unit'] ?? null,
            'supplier' => $row['supplier'] ?? null,
            'purchase_date' => $row['purchase_date'] ? \Carbon\Carbon::parse($row['purchase_date'])->format('Y-m-d') : null,
            'expiry_date' => $row['expiry_date'] ? \Carbon\Carbon::parse($row['expiry_date'])->format('Y-m-d') : null,
            'location' => $row['location'] ?? null,
            'notes' => $row['notes'] ?? null,
            'is_active' => $row['is_active'] ?? true,
        ];

        if ($this->updateExisting) {
            return InventoryItem::updateOrCreate(
                ['sku' => $data['sku']],
                $data
            );
        }

        return new InventoryItem($data);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:255|unique:inventory_items,sku',
            'price' => 'nullable|numeric|min:0',
            'quantity' => 'nullable|integer|min:0',
            'min_quantity' => 'nullable|integer|min:0',
            'purchase_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after_or_equal:purchase_date',
            'is_active' => 'nullable|boolean',
        ];
    }

    public function batchSize(): int
    {
        return 100;
    }

    public function chunkSize(): int
    {
        return 100;
    }
}
