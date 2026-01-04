<?php

namespace App\Exports;

use App\Models\Staff;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StaffExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(private int $schoolId) {}

    public function collection()
    {
        return Staff::with(['school'])
            ->where('school_id', $this->schoolId)
            ->orderBy('department')
            ->orderBy('designation')
            ->orderBy('first_name')
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Employee ID',
            'First Name',
            'Last Name',
            'Email',
            'Phone',
            'Date of Birth',
            'Gender',
            'Address',
            'City',
            'State',
            'Country',
            'Postal Code',
            'Designation',
            'Department',
            'Joining Date',
            'Contract End Date',
            'Salary',
            'Employment Type',
            'Status',
            'Emergency Contact Name',
            'Emergency Contact Phone',
            'Bank Name',
            'Bank Account Number',
            'IFSC Code',
            'PAN Number',
            'Aadhar Number',
            'Created At'
        ];
    }

    public function map($staff): array
    {
        return [
            $staff->id,
            $staff->employee_id,
            $staff->first_name,
            $staff->last_name,
            $staff->email,
            $staff->phone ?? 'N/A',
            $staff->date_of_birth ? $staff->date_of_birth->format('d/m/Y') : 'N/A',
            $staff->gender_label ?? 'N/A',
            $staff->address ?? 'N/A',
            $staff->city ?? 'N/A',
            $staff->state ?? 'N/A',
            $staff->country ?? 'N/A',
            $staff->postal_code ?? 'N/A',
            $staff->designation,
            $staff->department,
            $staff->joining_date ? $staff->joining_date->format('d/m/Y') : 'N/A',
            $staff->contract_end_date ? $staff->contract_end_date->format('d/m/Y') : 'N/A',
            $staff->formatted_salary,
            $staff->employment_type_label,
            $staff->status ? 'Active' : 'Inactive',
            $staff->emergency_contact_name ?? 'N/A',
            $staff->emergency_contact_phone ?? 'N/A',
            $staff->bank_name ?? 'N/A',
            $staff->bank_account_number ?? 'N/A',
            $staff->ifsc_code ?? 'N/A',
            $staff->pan_number ?? 'N/A',
            $staff->aadhar_number ?? 'N/A',
            $staff->created_at->format('d/m/Y H:i')
        ];
    }
}
