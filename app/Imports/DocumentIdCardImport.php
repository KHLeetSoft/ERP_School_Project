<?php

namespace App\Imports;

use App\Models\DocumentIdCard;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DocumentIdCardImport implements ToModel, WithHeadingRow
{
    public function __construct(private ?int $schoolId = null)
    {
    }

    public function model(array $row)
    {
        if (!isset($row['student_name'])) {
            return null;
        }

        return new DocumentIdCard([
            'school_id' => $this->schoolId,
            'student_id' => $row['student_id'] ?? null,
            'student_name' => $row['student_name'] ?? null,
            'class_name' => $row['class_name'] ?? null,
            'section_name' => $row['section_name'] ?? null,
            'roll_number' => $row['roll_number'] ?? null,
            'date_of_birth' => $row['date_of_birth'] ?? null,
            'blood_group' => $row['blood_group'] ?? null,
            'address' => $row['address'] ?? null,
            'phone' => $row['phone'] ?? null,
            'guardian_name' => $row['guardian_name'] ?? null,
            'issue_date' => $row['issue_date'] ?? null,
            'expiry_date' => $row['expiry_date'] ?? null,
            'status' => in_array(($row['status'] ?? 'active'), ['active','inactive']) ? ($row['status'] ?? 'active') : 'active',
        ]);
    }
}


