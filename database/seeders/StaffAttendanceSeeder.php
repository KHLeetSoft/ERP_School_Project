<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\StaffAttendance;
use Carbon\Carbon;

class StaffAttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $schoolId = 1;

        $staffUsers = User::where('school_id', $schoolId)
            ->where('status', true)
            ->take(10)
            ->get(['id','name']);

        if ($staffUsers->isEmpty()) {
            $this->command?->warn('No staff users found for school_id=1. Skipping StaffAttendanceSeeder.');
            return;
        }

        $statuses = [
            'present' => 85,
            'absent' => 5,
            'late' => 5,
            'half_day' => 3,
            'leave' => 2,
        ];

        $dates = collect(range(0, 6))
            ->map(fn($i) => Carbon::today()->subDays($i)->toDateString());

        foreach ($dates as $date) {
            foreach ($staffUsers as $user) {
                $status = $this->weightedRandom($statuses);
                $remarks = match ($status) {
                    'absent' => 'Absent - personal reason',
                    'late' => 'Late by 15 minutes',
                    'half_day' => 'Half day - medical',
                    'leave' => 'On approved leave',
                    default => null,
                };

                StaffAttendance::firstOrCreate(
                    [
                        'user_id' => $user->id,
                        'attendance_date' => $date,
                    ],
                    [
                        'school_id' => $schoolId,
                        'status' => $status,
                        'remarks' => $remarks,
                        'created_by' => null,
                        'updated_by' => null,
                    ]
                );
            }
        }

        $this->command?->info('Staff attendance seeded for last 7 days.');
    }

    private function weightedRandom(array $weights): string
    {
        $total = array_sum($weights);
        $rand = random_int(1, max(1, $total));
        $cumulative = 0;
        foreach ($weights as $key => $weight) {
            $cumulative += (int)$weight;
            if ($rand <= $cumulative) {
                return (string)$key;
            }
        }
        return array_key_first($weights);
    }
}


