<?php

namespace App\Exports;

use App\Models\DocumentTransferCertificate;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DocumentTransferCertificateExport implements FromCollection, WithHeadings
{
    public function __construct(private ?int $schoolId = null)
    {
    }

    public function collection()
    {
        return DocumentTransferCertificate::select(
            'student_id',
            'student_name',
            'admission_no',
            'class_name',
            'section_name',
            'date_of_birth',
            'father_name',
            'mother_name',
            'admission_date',
            'leaving_date',
            'reason_for_leaving',
            'conduct',
            'tc_number',
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
            'admission_date',
            'leaving_date',
            'reason_for_leaving',
            'conduct',
            'tc_number',
            'issue_date',
            'remarks',
            'status',
        ];
    }
}



