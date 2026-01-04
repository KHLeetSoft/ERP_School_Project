<?php

namespace App\Exports;

use App\Models\AcademicReport;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AcademicReportExport implements FromCollection, WithHeadings
{
    public function __construct(private ?int $schoolId = null)
    {
    }

    public function collection()
    {
        return AcademicReport::select('title','description','report_date','type','status')
            ->when($this->schoolId, function ($q) {
                $q->where('school_id', $this->schoolId);
            })
            ->orderByDesc('report_date')
            ->get();
    }

    public function headings(): array
    {
        return ['title','description','report_date','type','status'];
    }
}


