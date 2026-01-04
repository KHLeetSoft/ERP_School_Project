<?php

namespace App\Exports;

use App\Models\StaffAttendance;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StaffAttendanceExport implements FromCollection, WithHeadings
{
    public function __construct(private int $schoolId, private ?string $month)
    {
    }

    public function collection(): Collection
    {
        $query = StaffAttendance::with('staff')
            ->where('school_id', $this->schoolId)
            ->orderByDesc('attendance_date');

        if ($this->month) {
            $query->whereRaw('DATE_FORMAT(attendance_date, "%Y-%m") = ?', [$this->month]);
        }

        return $query->get()->map(function ($row) {
            return [
                'staff_id' => $row->user_id,
                'staff_name' => optional($row->staff)->name,
                'attendance_date' => $row->attendance_date,
                'status' => $row->status,
                'remarks' => $row->remarks,
            ];
        });
    }

    public function headings(): array
    {
        return ['staff_id', 'staff_name', 'attendance_date', 'status', 'remarks'];
    }
}


