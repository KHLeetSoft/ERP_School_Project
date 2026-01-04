<?php

namespace App\Imports;

use App\Models\AdmissionEnquiry;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AdmissionEnquiryImport implements ToModel, WithHeadingRow
{
    protected int $adminId;
    
    public function __construct()
    {
        $this->adminId = auth()->guard('admin')->id();
    }

    public function model(array $row)
    {
        return new AdmissionEnquiry([
            'student_name'    => $row['student_name'] ?? null,
            'parent_name'     => $row['parent_name'] ?? null,
            'contact_number'  => $row['contact_number'] ?? null,
            'email'           => $row['email'] ?? null,
            'address'         => $row['address'] ?? null,
            'class'           => $row['class'] ?? null,
            'date'            => $row['date'] ?? now()->format('Y-m-d'),
            'status'          => $row['status'] ?? 'pending',
            'note'            => $row['note'] ?? null,
            'admin_id'        => $this->adminId,
            'counselor_id'    => $row['counselor_id'] ?? null,
        ]);
    }
}