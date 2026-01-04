<?php

namespace App\Imports;

use App\Models\ExamGrade;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ExamGradeImport implements ToModel, WithHeadingRow
{
    public function __construct(private ?int $schoolId = null)
    {
    }

    public function model(array $row)
    {
        if (!isset($row['grade'])) {
            return null;
        }

        return new ExamGrade([
            'school_id' => $this->schoolId,
            'grade' => $row['grade'] ?? null,
            'grade_point' => $row['grade_point'] ?? null,
            'min_percentage' => $row['min_percentage'] ?? null,
            'max_percentage' => $row['max_percentage'] ?? null,
            'remark' => $row['remark'] ?? null,
            'description' => $row['description'] ?? null,
            'status' => $row['status'] ?? 'active',
        ]);
    }
}


