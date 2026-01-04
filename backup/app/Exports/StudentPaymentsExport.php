<?php

namespace App\Exports;

use App\Models\StudentPayment;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StudentPaymentsExport implements FromCollection, WithHeadings
{
    public function __construct(private int $schoolId)
    {
    }

    public function collection(): Collection
    {
        return StudentPayment::with(['student.user'])
            ->where('school_id', $this->schoolId)
            ->orderByDesc('payment_date')
            ->get()
            ->map(function ($p) {
                return [
                    'admission_no' => (string) optional($p->student)->admission_no,
                    'student_name' => (string) optional(optional($p->student)->user)->name,
                    'payment_date' => (string) $p->payment_date,
                    'amount' => $p->amount,
                    'method' => $p->method,
                    'status' => $p->status,
                    'reference' => $p->reference,
                    'notes' => $p->notes,
                ];
            });
    }

    public function headings(): array
    {
        return ['admission_no','student_name','payment_date','amount','method','status','reference','notes'];
    }
}


