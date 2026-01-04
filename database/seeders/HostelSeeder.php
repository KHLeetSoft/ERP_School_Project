<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Hostel;
use App\Models\HostelRoom;
use App\Models\StudentHostel;
use App\Models\StudentDetail;

class HostelSeeder extends Seeder
{
    public function run(): void
    {
        $hostelA = Hostel::updateOrCreate(['code' => 'H001'], [
            'school_id' => optional(StudentDetail::first())->school_id,
            'name' => 'Boys Hostel',
            'address' => 'North Campus Lane',
            'warden_name' => 'Mr. Raj',
            'warden_phone' => '9000000101',
            'status' => 'active',
        ]);

        $hostelB = Hostel::updateOrCreate(['code' => 'H002'], [
            'school_id' => optional(StudentDetail::first())->school_id,
            'name' => 'Girls Hostel',
            'address' => 'South Campus Lane',
            'warden_name' => 'Ms. Neha',
            'warden_phone' => '9000000102',
            'status' => 'active',
        ]);

        foreach ([['hostel' => $hostelA, 'prefix' => 'A'], ['hostel' => $hostelB, 'prefix' => 'B']] as $set) {
            for ($i=1; $i<=5; $i++) {
                HostelRoom::updateOrCreate([
                    'hostel_id' => $set['hostel']->id,
                    'room_no' => $set['prefix'] . str_pad((string)$i, 3, '0', STR_PAD_LEFT),
                ], [
                    'school_id' => $set['hostel']->school_id,
                    'type' => 'dorm',
                    'capacity' => 6,
                    'gender' => $set['hostel']->name === 'Girls Hostel' ? 'Female' : 'Male',
                    'floor' => (string) ceil($i/2),
                    'status' => 'available',
                ]);
            }
        }

        $room = HostelRoom::first();
        $students = StudentDetail::limit(10)->get();
        foreach ($students as $idx => $student) {
            StudentHostel::updateOrCreate([
                'student_id' => $student->id,
            ], [
                'school_id' => $student->school_id,
                'hostel_id' => $room->hostel_id,
                'room_id' => $room->id,
                'bed_no' => 'B' . ($idx + 1),
                'join_date' => now()->subDays(rand(1, 365))->format('Y-m-d'),
                'status' => 'active',
                'remarks' => 'Seeded hostel assignment',
            ]);
        }
    }
}


