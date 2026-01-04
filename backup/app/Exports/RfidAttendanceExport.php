<?php

namespace App\Exports;

use App\Models\RfidAttendance;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RfidAttendanceExport implements FromCollection, WithHeadings
{
    public function __construct(private int $schoolId)
    {
    }

    public function collection(): Collection
    {
        return RfidAttendance::with('user')
            ->where('school_id', $this->schoolId)
            ->orderByDesc('timestamp')
            ->limit(2000)
            ->get()
            ->map(function ($r) {
                return [
                    'user_id' => $r->user_id,
                    'user_name' => optional($r->user)->name,
                    'card_uid' => $r->card_uid,
                    'timestamp' => (string)$r->timestamp,
                    'direction' => $r->direction,
                    'device_name' => $r->device_name,
                    'remarks' => $r->remarks,
                ];
            });
    }

    public function headings(): array
    {
        return ['user_id','user_name','card_uid','timestamp','direction','device_name','remarks'];
    }
}


