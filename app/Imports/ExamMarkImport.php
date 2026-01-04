<?php

namespace App\Imports;

use App\Models\ExamMark;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ExamMarkImport implements ToModel, WithHeadingRow
{
    public function __construct(private ?int $schoolId = null)
    {
    }

    public function model(array $row)
    {
        if (!isset($row['student_name']) || !isset($row['subject_name'])) {
            return null;
        }

        return new ExamMark([
            'school_id' => $this->schoolId,
            'exam_id' => $row['exam_id'] ?? null,
            'class_name' => $row['class_name'] ?? null,
            'section_name' => $row['section_name'] ?? null,
            'student_id' => $row['student_id'] ?? null,
            'student_name' => $row['student_name'] ?? null,
            'admission_no' => $row['admission_no'] ?? null,
            'roll_no' => $row['roll_no'] ?? null,
            'subject_name' => $row['subject_name'] ?? null,
            'max_marks' => $row['max_marks'] ?? null,
            'obtained_marks' => $row['obtained_marks'] ?? null,
            'percentage' => $row['percentage'] ?? null,
            'grade' => $row['grade'] ?? null,
            'result_status' => $row['result_status'] ?? null,
            'remarks' => $row['remarks'] ?? null,
            'status' => $row['status'] ?? 'draft',
        ]);
    }
}


