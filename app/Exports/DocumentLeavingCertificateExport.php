<?php

namespace App\Exports;

use App\Models\DocumentLeavingCertificate;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DocumentLeavingCertificateExport implements FromCollection, WithHeadings
{
    public function __construct(private ?int $schoolId = null)
    {
    }

    public function collection()
    {
        return DocumentLeavingCertificate::select(
            'student_id',
            'student_name',
            'admission_no',
            'class_name',
            'section_name',
            'date_of_birth',
            'father_name',
            'mother_name',
            'reason_for_leaving',
            'conduct',
            'lc_number',
            'issue_date',
            'remarks',
            'status'
        )
            ->when($this->schoolId, fn($q) => $q->where('school_id', $this->schoolId))
            ->orderByDesc('issue_date')
            ->get();
    }

    public function headings(): array
    {
        return [
            'student_id',
            'student_name',
            'admission_no',
            'class_name',
            'section_name',
            'date_of_birth',
            'father_name',
            'mother_name',
            'reason_for_leaving',
            'conduct',
            'lc_number',
            'issue_date',
            'remarks',
            'status',
        ];
    }
}


