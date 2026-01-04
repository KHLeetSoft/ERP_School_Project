<?php

namespace App\Imports;

use App\Models\Exam;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ExamImport implements ToModel, WithHeadingRow
{
    public function __construct(private ?int $schoolId = null)
    {
    }

    public function model(array $row)
    {
        if (!isset($row['title'])) {
            return null;
        }

        return new Exam([
            'school_id' => $this->schoolId,
            'title' => $row['title'] ?? null,
            'exam_type' => $row['exam_type'] ?? null,
            'academic_year' => $row['academic_year'] ?? null,
            'description' => $row['description'] ?? null,
            'start_date' => $row['start_date'] ?? null,
            'end_date' => $row['end_date'] ?? null,
            'status' => $row['status'] ?? 'draft',
        ]);
    }
}


