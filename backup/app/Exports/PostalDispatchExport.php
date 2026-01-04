<?php

namespace App\Exports;

use App\Models\PostalDispatch;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PostalDispatchExport implements FromCollection, WithHeadings
{
    protected int $userId;

    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    public function collection(): Collection
    {
        return PostalDispatch::where('user_id',$this->userId)
            ->select('to_title','reference_no','address','from_title','date','note')
            ->get();
    }

    public function headings(): array
    {
        return ['To Title','Reference No','Address','From Title','Date','Note'];
    }
} 