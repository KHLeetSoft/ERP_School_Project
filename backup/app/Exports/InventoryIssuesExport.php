<?php

namespace App\Exports;

use App\Models\InventoryIssue;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InventoryIssuesExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
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

        $query = InventoryIssue::with('inventoryItem');

        // Apply filters
        if (isset($this->filters['search']) && $this->filters['search']) {
            $search = $this->filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('reported_by', 'like', "%{$search}%")
                  ->orWhere('assigned_to', 'like', "%{$search}%")
                  ->orWhereHas('inventoryItem', function($itemQuery) use ($search) {
                      $itemQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('sku', 'like', "%{$search}%");
                  });
            });
        }

        if (isset($this->filters['status']) && $this->filters['status']) {
            $query->where('status', $this->filters['status']);
        }

        if (isset($this->filters['priority']) && $this->filters['priority']) {
            $query->where('priority', $this->filters['priority']);
        }

        if (isset($this->filters['issue_type']) && $this->filters['issue_type']) {
            $query->where('issue_type', $this->filters['issue_type']);
        }

        return $query->orderBy('priority', 'desc')->orderBy('created_at', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Item Name',
            'Item SKU',
            'Issue Type',
            'Title',
            'Description',
            'Priority',
            'Status',
            'Quantity Affected',
            'Estimated Cost (₹)',
            'Issue Date',
            'Resolved Date',
            'Reported By',
            'Assigned To',
            'Location',
            'Resolution Notes',
            'Days Open',
            'Is Overdue',
            'Created At',
            'Updated At'
        ];
    }

    public function map($issue): array
    {
        return [
            $issue->id,
            $issue->inventoryItem->name ?? 'N/A',
            $issue->inventoryItem->sku ?? 'N/A',
            ucfirst($issue->issue_type),
            $issue->title,
            $issue->description,
            ucfirst($issue->priority),
            ucfirst(str_replace('_', ' ', $issue->status)),
            $issue->quantity_affected,
            $issue->estimated_cost,
            $issue->issue_date->format('Y-m-d'),
            $issue->resolved_date?->format('Y-m-d'),
            $issue->reported_by ?? 'N/A',
            $issue->assigned_to ?? 'N/A',
            $issue->location ?? 'N/A',
            $issue->resolution_notes ?? 'N/A',
            $issue->days_open,
            $issue->is_overdue ? 'Yes' : 'No',
            $issue->created_at->format('Y-m-d H:i:s'),
            $issue->updated_at->format('Y-m-d H:i:s'),
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
            'B' => 25,  // Item Name
            'C' => 15,  // Item SKU
            'D' => 15,  // Issue Type
            'E' => 30,  // Title
            'F' => 40,  // Description
            'G' => 12,  // Priority
            'H' => 15,  // Status
            'I' => 15,  // Quantity Affected
            'J' => 15,  // Estimated Cost
            'K' => 15,  // Issue Date
            'L' => 15,  // Resolved Date
            'M' => 20,  // Reported By
            'N' => 20,  // Assigned To
            'O' => 20,  // Location
            'P' => 30,  // Resolution Notes
            'Q' => 12,  // Days Open
            'R' => 12,  // Is Overdue
            'S' => 20,  // Created At
            'T' => 20,  // Updated At
        ];
    }
}
