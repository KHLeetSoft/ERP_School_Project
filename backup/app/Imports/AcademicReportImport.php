<?php

namespace App\Imports;

use App\Models\AcademicReport;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AcademicReportImport implements ToModel, WithHeadingRow
{
    public function __construct(private ?int $schoolId = null)
    {
    }

    public function model(array $row)
    {
        if (!isset($row['title']) || !isset($row['report_date'])) {
            return null;
        }

        return new AcademicReport([
            'school_id' => $this->schoolId,
            'title' => $row['title'] ?? null,
            'description' => $row['description'] ?? null,
            'report_date' => $row['report_date'] ?? null,
            'type' => $row['type'] ?? null,
            'status' => in_array(($row['status'] ?? 'draft'), ['draft','published','archived']) ? ($row['status'] ?? 'draft') : 'draft',
        ]);
    }
}


