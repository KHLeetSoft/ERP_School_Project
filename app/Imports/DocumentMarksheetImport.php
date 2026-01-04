<?php

namespace App\Imports;

use App\Models\DocumentMarksheet;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DocumentMarksheetImport implements ToModel, WithHeadingRow
{
    public function __construct(private ?int $schoolId = null)
    {
    }

    public function model(array $row)
    {
        if (!isset($row['student_name'])) {
            return null;
        }

        return new DocumentMarksheet([
            'school_id' => $this->schoolId,
            'student_id' => $row['student_id'] ?? null,
            'student_name' => $row['student_name'] ?? null,
            'admission_no' => $row['admission_no'] ?? null,
            'roll_no' => $row['roll_no'] ?? null,
            'class_name' => $row['class_name'] ?? null,
            'section_name' => $row['section_name'] ?? null,
            'exam_name' => $row['exam_name'] ?? null,
            'term' => $row['term'] ?? null,
            'academic_year' => $row['academic_year'] ?? null,
            'ms_number' => $row['ms_number'] ?? null,
            'issue_date' => $row['issue_date'] ?? null,
            'total_marks' => $row['total_marks'] ?? null,
            'obtained_marks' => $row['obtained_marks'] ?? null,
            'percentage' => $row['percentage'] ?? null,
            'grade' => $row['grade'] ?? null,
            'result_status' => $row['result_status'] ?? null,
            'remarks' => $row['remarks'] ?? null,
            'marks_json' => $row['marks_json'] ?? null,
            'status' => $row['status'] ?? 'draft',
        ]);
    }
}


