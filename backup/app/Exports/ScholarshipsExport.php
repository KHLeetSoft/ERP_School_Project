<?php

namespace App\Exports;

use App\Models\Scholarship;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ScholarshipsExport implements FromCollection, WithHeadings
{
    public function __construct(private int $schoolId) {}

    public function collection()
    {
        return Scholarship::where('school_id', $this->schoolId)
            ->select(['id','name','code','amount','status','awarded_date','notes'])
            ->orderBy('name')
            ->get();
    }

    public function headings(): array
    {
        return ['ID','Name','Code','Amount','Status','Awarded Date','Notes'];
    }
}


