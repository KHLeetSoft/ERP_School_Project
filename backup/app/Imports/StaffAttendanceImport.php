<?php

namespace App\Imports;

use App\Models\StaffAttendance;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StaffAttendanceImport implements ToModel, WithHeadingRow
{
    public function __construct(private int $schoolId)
    {
    }

    public function model(array $row)
    {
        if (empty($row['staff_id']) || empty($row['attendance_date']) || empty($row['status'])) {
            return null;
        }

        return StaffAttendance::firstOrNew([
            'user_id' => (int)$row['staff_id'],
            'attendance_date' => $row['attendance_date'],
        ], [
            'school_id' => $this->schoolId,
        ])->fill([
            'status' => $row['status'],
            'remarks' => $row['remarks'] ?? null,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);
    }
}


