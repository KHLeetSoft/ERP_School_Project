<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExamProgressCard;
use App\Models\Exam;

class ExamProgressCardSeeder extends Seeder
{
	public function run(): void
	{
		$exam = Exam::query()->inRandomOrder()->first();
		for ($i = 1; $i <= 20; $i++) {
			$percentage = rand(5000, 9500) / 100; // 50 to 95
			ExamProgressCard::create([
				'school_id' => 1,
				'exam_id' => $exam?->id,
				'class_name' => 'Class 10',
				'section_name' => 'A',
				'student_id' => $i,
				'student_name' => 'Student '.$i,
				'admission_no' => 'ADM'.str_pad((string)$i, 4, '0', STR_PAD_LEFT),
				'roll_no' => (string)$i,
				'overall_percentage' => $percentage,
				'overall_grade' => $percentage >= 90 ? 'A+' : ($percentage >= 80 ? 'A' : ($percentage >= 70 ? 'B' : 'C')),
				'overall_result_status' => $percentage >= 40 ? 'pass' : 'fail',
				'remarks' => null,
				'status' => 'published',
				'data' => [
					['subject' => 'Math', 'marks' => 88, 'grade' => 'A'],
					['subject' => 'Science', 'marks' => 82, 'grade' => 'A'],
					['subject' => 'English', 'marks' => 76, 'grade' => 'B'],
				],
			]);
		}
	}
}



