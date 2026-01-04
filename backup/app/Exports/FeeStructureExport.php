<?php

namespace App\Exports;

use App\Models\FeeStructure;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class FeeStructureExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(private int $schoolId) {}

    public function collection()
    {
        return FeeStructure::with(['class'])
            ->where('school_id', $this->schoolId)
            ->orderBy('class_id')
            ->orderBy('academic_year')
            ->orderBy('fee_type')
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Class',
            'Academic Year',
            'Fee Type',
            'Amount',
            'Frequency',
            'Due Date',
            'Late Fee',
            'Discount Applicable',
            'Max Discount',
            'Description',
            'Status',
            'Created At'
        ];
    }

    public function map($feeStructure): array
    {
        return [
            $feeStructure->id,
            $feeStructure->class->name ?? 'N/A',
            $feeStructure->academic_year,
            $feeStructure->fee_type,
            $feeStructure->amount,
            $feeStructure->frequency_label,
            $feeStructure->due_date ? $feeStructure->due_date->format('d/m/Y') : 'N/A',
            $feeStructure->late_fee,
            $feeStructure->discount_applicable ? 'Yes' : 'No',
            $feeStructure->max_discount,
            $feeStructure->description ?? 'N/A',
            $feeStructure->is_active ? 'Active' : 'Inactive',
            $feeStructure->created_at->format('d/m/Y H:i')
        ];
    }
}
