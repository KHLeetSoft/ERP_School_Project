<?php

namespace App\Imports;

use App\Models\DocumentEmployeeConductCertificate;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DocumentEmployeeConductCertificateImport implements ToModel, WithHeadingRow
{
    public function __construct(private ?int $schoolId = null)
    {
    }

    public function model(array $row)
    {
        if (!isset($row['employee_name'])) {
            return null;
        }

        return new DocumentEmployeeConductCertificate([
            'school_id' => $this->schoolId,
            'employee_id' => $row['employee_id'] ?? null,
            'employee_name' => $row['employee_name'] ?? null,
            'designation' => $row['designation'] ?? null,
            'department' => $row['department'] ?? null,
            'conduct' => $row['conduct'] ?? null,
            'ecc_number' => $row['ecc_number'] ?? null,
            'issue_date' => $row['issue_date'] ?? null,
            'remarks' => $row['remarks'] ?? null,
            'status' => $row['status'] ?? 'draft',
        ]);
    }
}


