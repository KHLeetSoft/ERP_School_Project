<?php

namespace App\Imports;

use App\Models\ExamTabulation;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ExamTabulationImport implements ToModel, WithHeadingRow
{
	public function __construct(private ?int $schoolId = null)
	{
	}

	public function model(array $row)
	{
		if (!isset($row['student_name'])) {
			return null;
		}

		return new ExamTabulation([
			'school_id' => $this->schoolId,
			'exam_id' => $row['exam_id'] ?? null,
			'class_name' => $row['class_name'] ?? null,
			'section_name' => $row['section_name'] ?? null,
			'student_id' => $row['student_id'] ?? null,
			'student_name' => $row['student_name'] ?? null,
			'admission_no' => $row['admission_no'] ?? null,
			'roll_no' => $row['roll_no'] ?? null,
			'total_marks' => $row['total_marks'] ?? null,
			'max_total_marks' => $row['max_total_marks'] ?? null,
			'percentage' => $row['percentage'] ?? null,
			'grade' => $row['grade'] ?? null,
			'result_status' => $row['result_status'] ?? null,
			'rank' => $row['rank'] ?? null,
			'remarks' => $row['remarks'] ?? null,
			'status' => $row['status'] ?? 'draft',
		]);
	}
}



