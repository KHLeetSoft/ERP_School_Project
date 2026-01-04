<?php

namespace App\Imports;

use App\Models\ExamAttendance;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ExamAttendanceImport implements ToModel, WithHeadingRow
{
	public function __construct(private ?int $schoolId = null)
	{
	}

	public function model(array $row)
	{
		if (!isset($row['student_name'])) {
			return null;
		}

		return new ExamAttendance([
			'school_id' => $this->schoolId,
			'exam_id' => $row['exam_id'] ?? null,
			'class_name' => $row['class_name'] ?? null,
			'section_name' => $row['section_name'] ?? null,
			'student_id' => $row['student_id'] ?? null,
			'student_name' => $row['student_name'] ?? null,
			'admission_no' => $row['admission_no'] ?? null,
			'roll_no' => $row['roll_no'] ?? null,
			'exam_date' => $row['exam_date'] ?? null,
			'subject_name' => $row['subject_name'] ?? null,
			'attendance_status' => $row['attendance_status'] ?? 'present',
			'remarks' => $row['remarks'] ?? null,
			'status' => $row['status'] ?? 'draft',
		]);
	}
}



