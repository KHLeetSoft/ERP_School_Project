<?php

namespace App\Exports;

use App\Models\DocumentStudyCertificate;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DocumentStudyCertificateExport implements FromCollection, WithHeadings
{
    public function __construct(private ?int $schoolId = null)
    {
    }

    public function collection()
    {
        return DocumentStudyCertificate::select(
            'student_id','student_name','admission_no','roll_no','class_name','section_name','date_of_birth','father_name','mother_name','academic_year','sc_number','issue_date','remarks','status'
        )
            ->when($this->schoolId, fn($q) => $q->where('school_id', $this->schoolId))
            ->orderByDesc('issue_date')
            ->get();
    }

    public function headings(): array
    {
        return [
            'student_id','student_name','admission_no','roll_no','class_name','section_name','date_of_birth','father_name','mother_name','academic_year','sc_number','issue_date','remarks','status'
        ];
    }
}


