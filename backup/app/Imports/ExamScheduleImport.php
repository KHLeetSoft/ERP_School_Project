<?php

namespace App\Imports;

use App\Models\ExamSchedule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ExamScheduleImport implements ToModel, WithHeadingRow
{
    public function __construct(private ?int $schoolId = null)
    {
    }

    public function model(array $row)
    {
        if (!isset($row['exam_id']) || !isset($row['subject_name'])) {
            return null;
        }

        return new ExamSchedule([
            'school_id' => $this->schoolId,
            'exam_id' => $row['exam_id'] ?? null,
            'class_name' => $row['class_name'] ?? null,
            'section_name' => $row['section_name'] ?? null,
            'subject_name' => $row['subject_name'] ?? null,
            'exam_date' => $row['exam_date'] ?? null,
            'start_time' => $row['start_time'] ?? null,
            'end_time' => $row['end_time'] ?? null,
            'room_no' => $row['room_no'] ?? null,
            'max_marks' => $row['max_marks'] ?? null,
            'pass_marks' => $row['pass_marks'] ?? null,
            'invigilator_name' => $row['invigilator_name'] ?? null,
            'status' => $row['status'] ?? 'scheduled',
            'notes' => $row['notes'] ?? null,
        ]);
    }
}


