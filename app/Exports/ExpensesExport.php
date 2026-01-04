<?php

namespace App\Exports;

use App\Models\Expense;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExpensesExport implements FromCollection, WithHeadings
{
    public function __construct(private int $schoolId)
    {
    }

    public function collection(): Collection
    {
        return Expense::where('school_id', $this->schoolId)->orderByDesc('expense_date')->get()
            ->map(fn($e) => [
                'expense_date' => (string)$e->expense_date,
                'category' => $e->category,
                'vendor' => $e->vendor,
                'description' => $e->description,
                'amount' => $e->amount,
                'method' => $e->method,
                'status' => $e->status,
                'reference' => $e->reference,
                'notes' => $e->notes,
            ]);
    }

    public function headings(): array
    {
        return ['expense_date','category','vendor','description','amount','method','status','reference','notes'];
    }
}


