<?php

namespace App\Imports;

use App\Models\InventoryIssue;
use App\Models\InventoryItem;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Validation\Rule;

class InventoryIssuesImport implements ToModel, WithHeadingRow, WithValidation, WithBatchInserts, WithChunkReading
{
    protected $updateExisting;

    public function __construct($updateExisting = false)
    {
        $this->updateExisting = $updateExisting;
    }

    public function model(array $row)
    {
        // Find inventory item by name or SKU
        $inventoryItem = null;
        if (isset($row['inventory_item_name'])) {
            $inventoryItem = InventoryItem::where('name', $row['inventory_item_name'])->first();
        } elseif (isset($row['inventory_item_sku'])) {
            $inventoryItem = InventoryItem::where('sku', $row['inventory_item_sku'])->first();
        }

        if (!$inventoryItem) {
            throw new \Exception("Inventory item not found: " . ($row['inventory_item_name'] ?? $row['inventory_item_sku'] ?? 'Unknown'));
        }

        $data = [
            'inventory_item_id' => $inventoryItem->id,
            'issue_type' => $row['issue_type'],
            'title' => $row['title'],
            'description' => $row['description'] ?? null,
            'priority' => $row['priority'] ?? 'medium',
            'status' => $row['status'] ?? 'open',
            'quantity_affected' => $row['quantity_affected'] ?? 1,
            'estimated_cost' => $row['estimated_cost'] ?? null,
            'issue_date' => $row['issue_date'] ? \Carbon\Carbon::parse($row['issue_date'])->format('Y-m-d') : now()->format('Y-m-d'),
            'resolved_date' => $row['resolved_date'] ? \Carbon\Carbon::parse($row['resolved_date'])->format('Y-m-d') : null,
            'reported_by' => $row['reported_by'] ?? null,
            'assigned_to' => $row['assigned_to'] ?? null,
            'location' => $row['location'] ?? null,
            'resolution_notes' => $row['resolution_notes'] ?? null,
            'is_active' => $row['is_active'] ?? true,
        ];

        if ($this->updateExisting) {
            return InventoryIssue::updateOrCreate(
                [
                    'inventory_item_id' => $data['inventory_item_id'],
                    'title' => $data['title'],
                    'issue_date' => $data['issue_date']
                ],
                $data
            );
        }

        return new InventoryIssue($data);
    }

    public function rules(): array
    {
        return [
            'inventory_item_name' => 'required_without:inventory_item_sku|string|max:255',
            'inventory_item_sku' => 'required_without:inventory_item_name|string|max:255',
            'issue_type' => 'required|in:damaged,lost,stolen,maintenance,other',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'nullable|in:low,medium,high,critical',
            'status' => 'nullable|in:open,in_progress,resolved,closed',
            'quantity_affected' => 'nullable|integer|min:1',
            'estimated_cost' => 'nullable|numeric|min:0',
            'issue_date' => 'nullable|date',
            'resolved_date' => 'nullable|date|after_or_equal:issue_date',
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
