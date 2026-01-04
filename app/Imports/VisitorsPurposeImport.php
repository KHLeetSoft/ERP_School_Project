<?php

namespace App\Imports;

use App\Models\VisitorsPurpose;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class VisitorsPurposeImport implements ToModel, WithHeadingRow
{
    protected int $userId;
    
    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    public function model(array $row)
    {
        $status = isset($row['status']) ? strtolower($row['status']) : 'active';
        $statusValue = in_array($status, ['active', 'yes', '1', 'true']) ? 1 : 0;
        
        return new VisitorsPurpose([
            'user_id'     => $this->userId,
            'school_id'   => auth()->guard('admin')->user()->school_id,
            'name'        => $row['name'],
            'description' => $row['description'] ?? null,
            'status'      => $statusValue,
        ]);
    }
}