<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BulkAttendanceBatch;
use App\Models\StaffAttendance;
use Carbon\Carbon;

class BulkAttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $schoolId = 1;
        // Create summary batches for last 3 days if attendance exists
        foreach (range(0,2) as $i) {
            $date = Carbon::today()->subDays($i)->toDateString();

            $present = StaffAttendance::where('school_id', $schoolId)->where('attendance_date', $date)->where('status','present')->count();
            $absent = StaffAttendance::where('school_id', $schoolId)->where('attendance_date', $date)->where('status','absent')->count();
            $late = StaffAttendance::where('school_id', $schoolId)->where('attendance_date', $date)->where('status','late')->count();
            $half = StaffAttendance::where('school_id', $schoolId)->where('attendance_date', $date)->where('status','half_day')->count();
            $leave = StaffAttendance::where('school_id', $schoolId)->where('attendance_date', $date)->where('status','leave')->count();
            $total = $present + $absent + $late + $half + $leave;

            if ($total === 0) continue;

            BulkAttendanceBatch::firstOrCreate([
                'school_id' => $schoolId,
                'batch_date' => $date,
            ], [
                'file_name' => 'seeded.xlsx',
                'total' => $total,
                'present' => $present,
                'absent' => $absent,
                'late' => $late,
                'half_day' => $half,
                'leave' => $leave,
                'created_by' => null,
                'updated_by' => null,
            ]);
        }
    }
}


