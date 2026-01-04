<?php

namespace App\Exports;

use App\Models\CallLog;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CallLogsExport implements FromCollection, WithHeadings
{
    protected int $userId;

    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    public function collection(): Collection
    {
        return CallLog::where('user_id', $this->userId)
            ->select('caller_name','purpose','phone','date','time','duration','note')
            ->get();
    }

    public function headings(): array
    {
        return ['Caller Name','Purpose','Phone','Date','Time','Duration','Note'];
    }
} 