<?php

namespace App\Imports;

use App\Models\PostalReceive;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PostalReceiveImport implements ToModel, WithHeadingRow
{
    protected int $userId;
    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    public function model(array $row)
    {
        return new PostalReceive([
            'user_id'      => $this->userId,
            'from_title'   => $row['from_title'] ?? $row['from'] ?? null,
            'reference_no' => $row['reference_no'] ?? null,
            'address'      => $row['address'] ?? null,
            'to_title'     => $row['to_title'] ?? null,
            'date'         => $row['date'] ?? null,
            'note'         => $row['note'] ?? null,
        ]);
    }
} 