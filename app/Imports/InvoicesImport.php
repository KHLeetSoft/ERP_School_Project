<?php

namespace App\Imports;

use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class InvoicesImport implements ToModel, WithHeadingRow
{
    public function __construct(private int $schoolId)
    {
    }

    public function model(array $row)
    {
        if (empty($row['invoice_number']) || empty($row['bill_to']) || empty($row['issue_date'])) return null;
        return new Invoice([
            'school_id' => $this->schoolId,
            'invoice_number' => (string)$row['invoice_number'],
            'bill_to' => (string)$row['bill_to'],
            'issue_date' => $row['issue_date'],
            'due_date' => $row['due_date'] ?? null,
            'status' => in_array($row['status'] ?? 'draft', ['draft','sent','paid','overdue','cancelled']) ? $row['status'] : 'draft',
            'items' => [],
            'subtotal' => (float)($row['subtotal'] ?? 0),
            'tax' => (float)($row['tax'] ?? 0),
            'discount' => (float)($row['discount'] ?? 0),
            'total' => (float)($row['total'] ?? 0),
            'notes' => $row['notes'] ?? null,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);
    }
}


