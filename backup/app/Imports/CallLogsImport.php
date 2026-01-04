<?php

namespace App\Imports;

use App\Models\CallLog;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CallLogsImport implements ToModel, WithHeadingRow
{
    protected int $userId;

    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    public function model(array $row)
    {
        return new CallLog([
            'user_id'      => $this->userId,
            'caller_name'  => $row['caller_name'] ?? $row['name'] ?? null,
            'purpose'      => $row['purpose'] ?? null,
            'phone'        => $row['phone'] ?? null,
            'date'         => $row['date'] ?? null,
            'time'         => $row['time'] ?? null,
            'duration'     => $row['duration'] ?? null,
            'note'         => $row['note'] ?? null,
        ]);
    }
} 