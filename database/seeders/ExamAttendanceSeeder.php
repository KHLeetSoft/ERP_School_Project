<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExamAttendance;
use App\Models\Exam;

class ExamAttendanceSeeder extends Seeder
{
	public function run(): void
	{
		$exam = Exam::query()->inRandomOrder()->first();
		$subjects = ['Math','Science','English','History','Geography'];
		$statuses = ['present','absent','late'];
		for ($i = 1; $i <= 30; $i++) {
			ExamAttendance::create([
				'school_id' => 1,
				'exam_id' => $exam?->id,
				'class_name' => 'Class 10',
				'section_name' => 'A',
				'student_id' => $i,
				'student_name' => 'Student '.$i,
				'admission_no' => 'ADM'.str_pad((string)$i, 4, '0', STR_PAD_LEFT),
				'roll_no' => (string)$i,
				'exam_date' => now()->subDays(rand(0,10))->toDateString(),
				'subject_name' => $subjects[array_rand($subjects)],
				'attendance_status' => $statuses[array_rand($statuses)],
				'remarks' => null,
				'status' => 'published',
			]);
		}
	}
}



