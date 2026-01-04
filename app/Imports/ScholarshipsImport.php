<?php

namespace App\Imports;

use App\Models\Scholarship;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ScholarshipsImport implements ToModel, WithHeadingRow
{
    public function __construct(private int $schoolId) {}

    public function model(array $row)
    {
        return new Scholarship([
            'school_id' => $this->schoolId,
            'student_id' => $row['student_id'] ?? null,
            'name' => $row['name'] ?? 'Unnamed',
            'code' => strtoupper($row['code'] ?? uniqid('SCH-')),
            'amount' => (float)($row['amount'] ?? 0),
            'status' => $row['status'] ?? 'pending',
            'awarded_date' => $row['awarded_date'] ?? null,
            'notes' => $row['notes'] ?? null,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);
    }
}


