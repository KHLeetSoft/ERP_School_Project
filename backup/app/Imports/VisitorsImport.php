<?php

namespace App\Imports;

use App\Models\Visitor;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class VisitorsImport implements ToModel, WithHeadingRow
{
    protected int $userId;
    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    public function model(array $row)
    {
        return new Visitor([
            'user_id'      => $this->userId,
            'visitor_name' => $row['name'] ?? $row['visitor_name'],
            'purpose'      => $row['purpose'] ?? null,
            'phone'        => $row['phone'] ?? null,
            'date'         => $row['date'] ?? null,
            'in_time'      => $row['in_time'] ?? null,
            'out_time'     => $row['out_time'] ?? null,
            'note'         => $row['note'] ?? null,
        ]);
    }
} 