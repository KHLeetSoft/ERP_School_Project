<?php

namespace App\Exports;

use App\Models\Visitor;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class VisitorsExport implements FromCollection, WithHeadings
{
    protected int $userId;

    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    public function collection(): Collection
    {
        return Visitor::where('user_id', $this->userId)
            ->select('visitor_name','purpose','phone','date','in_time','out_time','note')
            ->get();
    }

    public function headings(): array
    {
        return ['Name','Purpose','Phone','Date','In Time','Out Time','Note'];
    }
} 