<?php

namespace App\Exports;

use App\Models\ParentPortalAccess;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;

class ParentPortalAccessExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = ParentPortalAccess::with(['parentDetail']);

        if (isset($this->filters['parent_id']) && $this->filters['parent_id']) {
            $query->where('parent_detail_id', $this->filters['parent_id']);
        }

        if (isset($this->filters['is_enabled']) && $this->filters['is_enabled'] !== '') {
            $query->where('is_enabled', (bool) $this->filters['is_enabled']);
        }

        if (isset($this->filters['access_level']) && $this->filters['access_level']) {
            $query->where('access_level', $this->filters['access_level']);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Parent Name',
            'Username',
            'Email',
            'Access Level',
            'Status',
            'Force Password Reset',
            'Last Login',
            'Notes',
            'Created At',
            'Updated At'
        ];
    }

    public function map($row): array
    {
        $parentName = '';
        if ($row->parentDetail) {
            $parentName = trim(($row->parentDetail->primary_contact_name ?? '') . ' ' . ($row->parentDetail->secondary_contact_name ?? ''));
        }

        return [
            $row->id,
            $parentName ?: 'N/A',
            $row->username,
            $row->email ?: 'N/A',
            ucfirst($row->access_level),
            $row->is_enabled ? 'Enabled' : 'Disabled',
            $row->force_password_reset ? 'Yes' : 'No',
            $row->last_login_at ? $row->last_login_at->format('Y-m-d H:i:s') : 'Never',
            $row->notes ?: 'N/A',
            $row->created_at->format('Y-m-d H:i:s'),
            $row->updated_at->format('Y-m-d H:i:s')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style the header row
        $sheet->getStyle('A1:K1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Auto-size columns
        foreach (range('A', 'K') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Style data rows
        $sheet->getStyle('A2:K' . ($sheet->getHighestRow()))->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Add borders
        $sheet->getStyle('A1:K' . ($sheet->getHighestRow()))->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        return $sheet;
    }
}
