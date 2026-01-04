<?php

namespace App\Imports;

use App\Models\DocumentExperienceCertificate;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DocumentExperienceCertificateImport implements ToModel, WithHeadingRow
{
    public function __construct(private ?int $schoolId = null)
    {
    }

    public function model(array $row)
    {
        if (!isset($row['employee_name'])) {
            return null;
        }

        return new DocumentExperienceCertificate([
            'school_id' => $this->schoolId,
            'employee_id' => $row['employee_id'] ?? null,
            'employee_name' => $row['employee_name'] ?? null,
            'designation' => $row['designation'] ?? null,
            'department' => $row['department'] ?? null,
            'joining_date' => $row['joining_date'] ?? null,
            'relieving_date' => $row['relieving_date'] ?? null,
            'total_experience' => $row['total_experience'] ?? null,
            'ec_number' => $row['ec_number'] ?? null,
            'issue_date' => $row['issue_date'] ?? null,
            'remarks' => $row['remarks'] ?? null,
            'status' => $row['status'] ?? 'draft',
        ]);
    }
}


