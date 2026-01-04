<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ResultAnnouncementsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('result_announcements')->insert([
            [
                'school_id' => 1,
                'title' => 'Annual Exam Results',
                'description' => 'Annual exam results are now available for all classes.',
                'exam_id' => 1,
                'online_exam_id' => null,
                'announcement_type' => 'exam_result',
                'status' => 'published',
                'publish_at' => Carbon::now()->subDay(),
                'expires_at' => Carbon::now()->addMonth(),
                'target_audience' => json_encode(['students', 'parents']),
                'class_ids' => json_encode([1,2,3]),
                'section_ids' => json_encode([1,2]),
                'send_sms' => true,
                'send_email' => true,
                'send_push_notification' => true,
                'notification_settings' => json_encode(['priority' => 'high']),
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'school_id' => 1,
                'title' => 'Merit List Published',
                'description' => 'Merit list for top performers has been released.',
                'exam_id' => null,
                'online_exam_id' => null,
                'announcement_type' => 'merit_list',
                'status' => 'published',
                'publish_at' => Carbon::now(),
                'expires_at' => Carbon::now()->addWeeks(2),
                'target_audience' => json_encode(['students', 'teachers']),
                'class_ids' => json_encode([4,5]),
                'section_ids' => json_encode([3]),
                'send_sms' => false,
                'send_email' => true,
                'send_push_notification' => false,
                'notification_settings' => json_encode(['highlight' => true]),
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
