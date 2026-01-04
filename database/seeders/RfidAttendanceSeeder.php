<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RfidAttendance;
use App\Models\User;
use Carbon\Carbon;

class RfidAttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $schoolId = 1;
        $users = User::where('school_id', $schoolId)->take(5)->get(['id']);
        if ($users->isEmpty()) return;

        foreach (range(0, 6) as $d) {
            $date = Carbon::today()->subDays($d);
            foreach ($users as $u) {
                // In at 9:00, Out at 17:00
                RfidAttendance::firstOrCreate([
                    'school_id' => $schoolId,
                    'user_id' => $u->id,
                    'card_uid' => 'UID-'.$u->id,
                    'timestamp' => $date->copy()->setTime(9, 0),
                    'direction' => 'in',
                ], [
                    'device_name' => 'Gate-1',
                ]);
                RfidAttendance::firstOrCreate([
                    'school_id' => $schoolId,
                    'user_id' => $u->id,
                    'card_uid' => 'UID-'.$u->id,
                    'timestamp' => $date->copy()->setTime(17, 0),
                    'direction' => 'out',
                ], [
                    'device_name' => 'Gate-1',
                ]);
            }
        }
    }
}


