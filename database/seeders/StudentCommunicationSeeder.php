<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StudentCommunication;
use App\Models\StudentDetail;
use App\Models\SchoolClass;

class StudentCommunicationSeeder extends Seeder
{
    public function run(): void
    {
        $students = StudentDetail::limit(10)->get();
        $class = SchoolClass::first();

        // Class-wide notice
        StudentCommunication::create([
            'school_id' => optional($class)->school_id,
            'class_id' => optional($class)->id,
            'subject' => 'Welcome Back to School',
            'message' => 'Dear students, welcome back! Please note the timetable changes effective next week.',
            'channel' => 'notice',
            'status' => 'sent',
            'sent_at' => now(),
        ]);

        // Individual SMS/email examples
        foreach ($students as $s) {
            StudentCommunication::create([
                'school_id' => $s->school_id,
                'student_id' => $s->id,
                'class_id' => $s->class_id ?? optional($class)->id,
                'subject' => 'Fee Reminder',
                'message' => 'Please clear your pending fees by the 10th of this month.',
                'channel' => 'sms',
                'status' => 'sent',
                'sent_at' => now()->subDays(rand(1, 15)),
            ]);
            StudentCommunication::create([
                'school_id' => $s->school_id,
                'student_id' => $s->id,
                'class_id' => $s->class_id ?? optional($class)->id,
                'subject' => 'PTM Schedule',
                'message' => 'Parent-Teacher meeting is scheduled for Friday 4 PM in Room 12.',
                'channel' => 'email',
                'status' => 'sent',
                'sent_at' => now()->subDays(rand(1, 30)),
            ]);
        }
    }
}


