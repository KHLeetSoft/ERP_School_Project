<?php

namespace App\Imports;

use App\Models\DocumentTransferCertificate;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DocumentTransferCertificateImport implements ToModel, WithHeadingRow
{
    public function __construct(private ?int $schoolId = null)
    {
    }

    public function model(array $row)
    {
        if (!isset($row['student_name'])) {
            return null;
        }

        return new DocumentTransferCertificate([
            'school_id' => $this->schoolId,
            'student_id' => $row['student_id'] ?? null,
            'student_name' => $row['student_name'] ?? null,
            'admission_no' => $row['admission_no'] ?? null,
            'class_name' => $row['class_name'] ?? null,
            'section_name' => $row['section_name'] ?? null,
            'date_of_birth' => $row['date_of_birth'] ?? null,
            'father_name' => $row['father_name'] ?? null,
            'mother_name' => $row['mother_name'] ?? null,
            'admission_date' => $row['admission_date'] ?? null,
            'leaving_date' => $row['leaving_date'] ?? null,
            'reason_for_leaving' => $row['reason_for_leaving'] ?? null,
            'conduct' => $row['conduct'] ?? null,
            'tc_number' => $row['tc_number'] ?? null,
            'issue_date' => $row['issue_date'] ?? null,
            'remarks' => $row['remarks'] ?? null,
            'status' => $row['status'] ?? 'draft',
        ]);
    }
}



