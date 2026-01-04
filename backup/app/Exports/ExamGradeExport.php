<?php

namespace App\Exports;

use App\Models\ExamGrade;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExamGradeExport implements FromCollection, WithHeadings
{
    public function __construct(private ?int $schoolId = null)
    {
    }

    public function collection()
    {
        return ExamGrade::select('grade','grade_point','min_percentage','max_percentage','remark','description','status')
            ->when($this->schoolId, fn($q) => $q->where('school_id', $this->schoolId))
            ->orderBy('grade')
            ->get();
    }

    public function headings(): array
    {
        return ['grade','grade_point','min_percentage','max_percentage','remark','description','status'];
    }
}


