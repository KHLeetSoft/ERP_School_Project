<?php

namespace App\Imports;

use App\Models\DocumentLeavingCertificate;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DocumentLeavingCertificateImport implements ToModel, WithHeadingRow
{
    public function __construct(private ?int $schoolId = null)
    {
    }

    public function model(array $row)
    {
        if (!isset($row['student_name'])) {
            return null;
        }

        return new DocumentLeavingCertificate([
            'school_id' => $this->schoolId,
            'student_id' => $row['student_id'] ?? null,
            'student_name' => $row['student_name'] ?? null,
            'admission_no' => $row['admission_no'] ?? null,
            'class_name' => $row['class_name'] ?? null,
            'section_name' => $row['section_name'] ?? null,
            'date_of_birth' => $row['date_of_birth'] ?? null,
            'father_name' => $row['father_name'] ?? null,
            'mother_name' => $row['mother_name'] ?? null,
            'reason_for_leaving' => $row['reason_for_leaving'] ?? null,
            'conduct' => $row['conduct'] ?? null,
            'lc_number' => $row['lc_number'] ?? null,
            'issue_date' => $row['issue_date'] ?? null,
            'remarks' => $row['remarks'] ?? null,
            'status' => $row['status'] ?? 'draft',
        ]);
    }
}


