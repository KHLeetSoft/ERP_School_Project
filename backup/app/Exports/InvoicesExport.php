<?php

namespace App\Exports;

use App\Models\Invoice;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class InvoicesExport implements FromCollection, WithHeadings
{
    public function __construct(private int $schoolId)
    {
    }

    public function collection(): Collection
    {
        return Invoice::where('school_id', $this->schoolId)->orderByDesc('issue_date')->get()
            ->map(fn($i) => [
                'invoice_number' => $i->invoice_number,
                'bill_to' => $i->bill_to,
                'issue_date' => (string)$i->issue_date,
                'due_date' => (string)$i->due_date,
                'status' => $i->status,
                'subtotal' => $i->subtotal,
                'tax' => $i->tax,
                'discount' => $i->discount,
                'total' => $i->total,
                'notes' => $i->notes,
            ]);
    }

    public function headings(): array
    {
        return ['invoice_number','bill_to','issue_date','due_date','status','subtotal','tax','discount','total','notes'];
    }
}


