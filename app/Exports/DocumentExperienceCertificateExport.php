<?php

namespace App\Exports;

use App\Models\DocumentExperienceCertificate;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DocumentExperienceCertificateExport implements FromCollection, WithHeadings
{
    public function __construct(private ?int $schoolId = null)
    {
    }

    public function collection()
    {
        return DocumentExperienceCertificate::select(
            'employee_id','employee_name','designation','department','joining_date','relieving_date','total_experience',
            'ec_number','issue_date','remarks','status'
        )
            ->when($this->schoolId, fn($q) => $q->where('school_id', $this->schoolId))
            ->orderByDesc('issue_date')
            ->get();
    }

    public function headings(): array
    {
        return [
            'employee_id','employee_name','designation','department','joining_date','relieving_date','total_experience',
            'ec_number','issue_date','remarks','status'
        ];
    }
}


