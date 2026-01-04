<?php

namespace App\Imports;

use App\Models\RfidAttendance;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class RfidAttendanceImport implements ToModel, WithHeadingRow
{
    public function __construct(private int $schoolId)
    {
    }

    public function model(array $row)
    {
        if (empty($row['user_id']) || empty($row['card_uid']) || empty($row['timestamp']) || empty($row['direction'])) {
            return null;
        }

        return new RfidAttendance([
            'school_id' => $this->schoolId,
            'user_id' => (int)$row['user_id'],
            'card_uid' => (string)$row['card_uid'],
            'timestamp' => $row['timestamp'],
            'direction' => in_array($row['direction'], ['in','out']) ? $row['direction'] : 'in',
            'device_name' => $row['device_name'] ?? null,
            'remarks' => $row['remarks'] ?? null,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);
    }
}


