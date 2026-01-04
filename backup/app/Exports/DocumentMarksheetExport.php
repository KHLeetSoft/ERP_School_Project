<?php

namespace App\Exports;

use App\Models\DocumentMarksheet;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DocumentMarksheetExport implements FromCollection, WithHeadings
{
    public function __construct(private ?int $schoolId = null)
    {
    }

    public function collection()
    {
        return DocumentMarksheet::select(
            'student_id','student_name','admission_no','roll_no','class_name','section_name','exam_name','term','academic_year',
            'ms_number','issue_date','total_marks','obtained_marks','percentage','grade','result_status','remarks','status'
        )
            ->when($this->schoolId, fn($q) => $q->where('school_id', $this->schoolId))
            ->orderByDesc('issue_date')
            ->get();
    }

    public function headings(): array
    {
        return [
            'student_id','student_name','admission_no','roll_no','class_name','section_name','exam_name','term','academic_year',
            'ms_number','issue_date','total_marks','obtained_marks','percentage','grade','result_status','remarks','status'
        ];
    }
}


