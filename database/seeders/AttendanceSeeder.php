<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attendance;
use App\Models\User;
use App\Models\Student;
use App\Models\ClassSection;
use Carbon\Carbon;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin user with role_id = 2
        $admin = User::where('role_id', 2)->first();

        if (!$admin) {
            $this->command->info('❌ Admin user not found. Skipping attendance seeding.');
            return;
        }

        // Get first 3 active class sections
        $classSections = ClassSection::where('status', 'active')->take(3)->get();

        if ($classSections->isEmpty()) {
            $this->command->info('❌ No active class sections found. Skipping attendance seeding.');
            return;
        }

        // Attendance statuses
        $statuses = ['present', 'absent', 'late', 'half_day', 'leave'];

        // Attendance for last 30 days
        $startDate = Carbon::now()->subDays(30);
        $endDate = Carbon::now();

        foreach ($classSections as $classSection) {
            // Get up to 10 students from each class section
            $students = Student::where('class_section_id', $classSection->id)
                ->where('status', 'active')
                ->take(10)
                ->get();

            if ($students->isEmpty()) {
                $this->command->info("⚠️ No active students found for class section ID: {$classSection->id}. Skipping.");
                continue;
            }

            // Create attendance for each non-weekend day
            for ($date = clone $startDate; $date->lte($endDate); $date->addDay()) {
                if ($date->isWeekend()) {
                    continue;
                }

                foreach ($students as $student) {
                    $status = (rand(1, 10) <= 7) ? 'present' : $statuses[array_rand(array_slice($statuses, 1))];

                    $remarks = null;
                    if ($status !== 'present') {
                        $remarkOptions = [
                            'absent' => ['Not feeling well', 'Family emergency', 'No reason provided'],
                            'late' => ['Traffic delay', 'Overslept', 'Doctor appointment'],
                            'half_day' => ['Doctor appointment', 'Family event', 'Not feeling well'],
                            'leave' => ['Family vacation', 'Medical leave', 'Personal reasons']
                        ];
                        $remarks = $remarkOptions[$status][array_rand($remarkOptions[$status])];
                    }

                    Attendance::create([
                        'school_id'        => $classSection->school_id,
                        'class_section_id' => $classSection->id,
                        'student_id'       => $student->id,
                        'attendance_date'  => $date->format('Y-m-d'),
                        'status'           => $status,
                        'remarks'          => $remarks,
                        'created_by'       => $admin->id,
                        'updated_by'       => $admin->id,
                    ]);
                }
            }

            $this->command->info("✅ Attendance created for class_section_id: {$classSection->id}");
        }

        $this->command->info('🎉 Attendance seeding completed successfully!');
    }
}
