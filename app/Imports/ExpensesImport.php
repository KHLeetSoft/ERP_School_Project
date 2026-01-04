<?php

namespace App\Imports;

use App\Models\Expense;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;

class ExpensesImport implements ToCollection, WithHeadingRow
{
    public function __construct(private int $schoolId)
    {
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            Expense::create([
                'school_id' => $this->schoolId,
                'expense_date' => $row['expense_date'] ?? now()->toDateString(),
                'category' => $row['category'] ?? null,
                'vendor' => $row['vendor'] ?? null,
                'description' => $row['description'] ?? null,
                'amount' => (float) ($row['amount'] ?? 0),
                'method' => in_array(($row['method'] ?? 'cash'), ['cash','card','bank','online','cheque']) ? $row['method'] : 'cash',
                'status' => in_array(($row['status'] ?? 'approved'), ['pending','approved','paid','void']) ? $row['status'] : 'approved',
                'reference' => $row['reference'] ?? null,
                'notes' => $row['notes'] ?? null,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);
        }
    }
}


