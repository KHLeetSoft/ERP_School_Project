<?php

namespace App\Exports;

use App\Models\ExamMark;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExamMarkExport implements FromCollection, WithHeadings
{
    public function __construct(private ?int $schoolId = null)
    {
    }

    public function collection()
    {
        return ExamMark::select('exam_id','class_name','section_name','student_id','student_name','admission_no','roll_no','subject_name','max_marks','obtained_marks','percentage','grade','result_status','remarks','status')
            ->when($this->schoolId, fn($q) => $q->where('school_id', $this->schoolId))
            ->orderByDesc('id')->get();
    }

    public function headings(): array
    {
        return ['exam_id','class_name','section_name','student_id','student_name','admission_no','roll_no','subject_name','max_marks','obtained_marks','percentage','grade','result_status','remarks','status'];
    }
}


