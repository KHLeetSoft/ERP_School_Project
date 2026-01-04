<?php

namespace App\Imports;

use App\Models\ExamProgressCard;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ExamProgressCardImport implements ToModel, WithHeadingRow
{
	public function __construct(private ?int $schoolId = null)
	{
	}

	public function model(array $row)
	{
		if (!isset($row['student_name'])) {
			return null;
		}

		return new ExamProgressCard([
			'school_id' => $this->schoolId,
			'exam_id' => $row['exam_id'] ?? null,
			'class_name' => $row['class_name'] ?? null,
			'section_name' => $row['section_name'] ?? null,
			'student_id' => $row['student_id'] ?? null,
			'student_name' => $row['student_name'] ?? null,
			'admission_no' => $row['admission_no'] ?? null,
			'roll_no' => $row['roll_no'] ?? null,
			'overall_percentage' => $row['overall_percentage'] ?? null,
			'overall_grade' => $row['overall_grade'] ?? null,
			'overall_result_status' => $row['overall_result_status'] ?? null,
			'remarks' => $row['remarks'] ?? null,
			'status' => $row['status'] ?? 'draft',
		]);
	}
}



