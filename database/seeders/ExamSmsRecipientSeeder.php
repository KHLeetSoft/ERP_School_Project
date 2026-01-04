<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExamSms;
use App\Models\ExamSmsRecipient;
use App\Models\StudentDetail;
use App\Models\ParentDetail;

class ExamSmsRecipientSeeder extends Seeder
{
    public function run(): void
    {
        $sms = ExamSms::first();
        if (!$sms) {
            return;
        }

        $students = StudentDetail::limit(5)->get();
        foreach ($students as $student) {
            ExamSmsRecipient::create([
                'exam_sms_id' => $sms->id,
                'recipient_type' => 'student',
                'recipient_id' => $student->id,
                'phone' => $student->guardian_contact,
                'status' => 'pending',
            ]);
        }

        $parents = ParentDetail::limit(5)->get();
        foreach ($parents as $parent) {
            ExamSmsRecipient::create([
                'exam_sms_id' => $sms->id,
                'recipient_type' => 'parent',
                'recipient_id' => $parent->id,
                'phone' => $parent->phone_primary,
                'status' => 'sent',
                'sent_at' => now(),
            ]);
        }
    }
}


