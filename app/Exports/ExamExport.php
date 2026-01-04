<?php

namespace App\Exports;

use App\Models\Exam;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExamExport implements FromCollection, WithHeadings
{
    public function __construct(private ?int $schoolId = null)
    {
    }

    public function collection()
    {
        return Exam::select('title','exam_type','academic_year','description','start_date','end_date','status')
            ->when($this->schoolId, fn($q) => $q->where('school_id', $this->schoolId))
            ->orderByDesc('start_date')
            ->get();
    }

    public function headings(): array
    {
        return ['title','exam_type','academic_year','description','start_date','end_date','status'];
    }
}


