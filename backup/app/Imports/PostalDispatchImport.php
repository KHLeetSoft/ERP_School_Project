<?php

namespace App\Imports;

use App\Models\PostalDispatch;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PostalDispatchImport implements ToModel, WithHeadingRow
{
    protected int $userId;
    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    public function model(array $row)
    {
        return new PostalDispatch([
            'user_id'      => $this->userId,
            'to_title'     => $row['to_title'] ?? $row['to'] ?? null,
            'reference_no' => $row['reference_no'] ?? null,
            'address'      => $row['address'] ?? null,
            'from_title'   => $row['from_title'] ?? null,
            'date'         => $row['date'] ?? null,
            'note'         => $row['note'] ?? null,
        ]);
    }
} 