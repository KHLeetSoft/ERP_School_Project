<?php

namespace App\Exports;

use App\Models\PostalReceive;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PostalReceiveExport implements FromCollection, WithHeadings
{
    protected int $userId;

    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    public function collection(): Collection
    {
        return PostalReceive::where('user_id',$this->userId)
            ->select('from_title','reference_no','address','to_title','date','note')
            ->get();
    }

    public function headings(): array
    {
        return ['From Title','Reference No','Address','To Title','Date','Note'];
    }
} 