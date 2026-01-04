<?php

namespace App\Exports;

use App\Models\ExamSchedule;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExamScheduleExport implements FromCollection, WithHeadings
{
    public function __construct(private ?int $schoolId = null)
    {
    }

    public function collection()
    {
        return ExamSchedule::select('exam_id','class_name','section_name','subject_name','exam_date','start_time','end_time','room_no','max_marks','pass_marks','invigilator_name','status','notes')
            ->when($this->schoolId, fn($q) => $q->where('school_id', $this->schoolId))
            ->orderByDesc('exam_date')
            ->get();
    }

    public function headings(): array
    {
        return ['exam_id','class_name','section_name','subject_name','exam_date','start_time','end_time','room_no','max_marks','pass_marks','invigilator_name','status','notes'];
    }
}


