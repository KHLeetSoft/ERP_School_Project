<?php

namespace App\Exports;

use App\Models\DocumentEmployeeConductCertificate;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DocumentEmployeeConductCertificateExport implements FromCollection, WithHeadings
{
    public function __construct(private ?int $schoolId = null)
    {
    }

    public function collection()
    {
        return DocumentEmployeeConductCertificate::select(
            'employee_id','employee_name','designation','department','conduct','ecc_number','issue_date','remarks','status'
        )
            ->when($this->schoolId, fn($q) => $q->where('school_id', $this->schoolId))
            ->orderByDesc('issue_date')
            ->get();
    }

    public function headings(): array
    {
        return [
            'employee_id','employee_name','designation','department','conduct','ecc_number','issue_date','remarks','status'
        ];
    }
}


