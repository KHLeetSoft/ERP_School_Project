<?php

namespace App\Exports;

use App\Models\ExpenseCategory;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExpenseCategoriesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $schoolId;

    public function __construct($schoolId)
    {
        $this->schoolId = $schoolId;
    }

    public function collection()
    {
        return ExpenseCategory::where('school_id', $this->schoolId)
            ->with(['creator', 'updater'])
            ->orderBy('name')
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Code',
            'Description',
            'Color',
            'Icon',
            'Budget Limit',
            'Budget Period',
            'Status',
            'Total Expenses',
            'Monthly Expenses',
            'Budget Utilization (%)',
            'Created By',
            'Updated By',
            'Created At',
            'Updated At'
        ];
    }

    public function map($expenseCategory): array
    {
        return [
            $expenseCategory->id,
            $expenseCategory->name,
            $expenseCategory->code,
            $expenseCategory->description ?? '',
            $expenseCategory->color,
            $expenseCategory->icon,
            $expenseCategory->budget_limit ?? 0,
            $expenseCategory->budget_period,
            $expenseCategory->is_active ? 'Active' : 'Inactive',
            $expenseCategory->total_expenses,
            $expenseCategory->monthly_expenses,
            $expenseCategory->budget_utilization ?? 0,
            $expenseCategory->creator->name ?? 'System',
            $expenseCategory->updater->name ?? 'System',
            $expenseCategory->created_at->format('Y-m-d H:i:s'),
            $expenseCategory->updated_at->format('Y-m-d H:i:s'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E2E8F0']
                ]
            ],
        ];
    }
}
