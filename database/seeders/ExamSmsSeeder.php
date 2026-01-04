<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExamSms;
use App\Models\Exam;
use Faker\Factory as Faker;
use Illuminate\Support\Carbon;

class ExamSmsSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $exam = Exam::inRandomOrder()->first();
        for ($i = 0; $i < 12; $i++) {
            ExamSms::create([
                'school_id' => $exam->school_id ?? null,
                'exam_id' => $exam->id ?? null,
                'title' => 'Exam SMS #'.($i+1),
                'message_template' => $faker->sentence(12),
                'audience_type' => $faker->randomElement(['students','parents','staff']),
                'class_name' => 'Class '.$faker->numberBetween(1,12),
                'section_name' => $faker->randomElement(['A','B','C','D']),
                'schedule_at' => $faker->optional()->dateTimeBetween('-3 days', '+10 days'),
                'status' => $faker->randomElement(['draft','scheduled','sent']),
                'sent_count' => $faker->numberBetween(0, 200),
                'failed_count' => $faker->numberBetween(0, 20),
            ]);
        }
    }
}


