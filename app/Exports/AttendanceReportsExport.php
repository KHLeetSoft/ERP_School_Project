<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class AttendanceReportsExport implements FromCollection, WithHeadings
{
    public function __construct(private int $schoolId, private ?string $start, private ?string $end, private string $type)
    {
    }

    public function collection(): Collection
    {
        $start = $this->start ? Carbon::parse($this->start) : Carbon::today()->subDays(6);
        $end = $this->end ? Carbon::parse($this->end) : Carbon::today();
        $days = collect(Carbon::parse($start)->toPeriod($end, '1 day'))->map(fn($d) => Carbon::parse($d->format('Y-m-d')));

        return $days->map(function ($d) {
            $date = $d->toDateString();
            $row = ['date' => $date];
            if (in_array($this->type, ['both','staff'])) {
                $row['staff_present'] = DB::table('staff_attendances')->where('school_id', $this->schoolId)->where('attendance_date', $date)->where('status','present')->count();
                $row['staff_absent'] = DB::table('staff_attendances')->where('school_id', $this->schoolId)->where('attendance_date', $date)->where('status','absent')->count();
            }
            if (in_array($this->type, ['both','students'])) {
                $row['student_present'] = DB::table('attendances')->where('school_id', $this->schoolId)->where('attendance_date', $date)->where('status','present')->count();
                $row['student_absent'] = DB::table('attendances')->where('school_id', $this->schoolId)->where('attendance_date', $date)->where('status','absent')->count();
            }
            return $row;
        });
    }

    public function headings(): array
    {
        $base = ['date'];
        $cols = [];
        if (in_array($this->type, ['both','staff'])) { $cols = array_merge($cols, ['staff_present','staff_absent']); }
        if (in_array($this->type, ['both','students'])) { $cols = array_merge($cols, ['student_present','student_absent']); }
        return array_merge($base, $cols);
    }
}


