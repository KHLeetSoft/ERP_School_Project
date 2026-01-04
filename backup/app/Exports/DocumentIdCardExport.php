<?php

namespace App\Exports;

use App\Models\DocumentIdCard;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DocumentIdCardExport implements FromCollection, WithHeadings
{
    public function __construct(private ?int $schoolId = null)
    {
    }

    public function collection()
    {
        return DocumentIdCard::select(
            'student_id','student_name','class_name','section_name','roll_number','date_of_birth','blood_group','address','phone','guardian_name','issue_date','expiry_date','status'
        )
            ->when($this->schoolId, fn($q) => $q->where('school_id', $this->schoolId))
            ->orderBy('class_name')
            ->orderBy('section_name')
            ->orderBy('roll_number')
            ->get();
    }

    public function headings(): array
    {
        return ['student_id','student_name','class_name','section_name','roll_number','date_of_birth','blood_group','address','phone','guardian_name','issue_date','expiry_date','status'];
    }
}


