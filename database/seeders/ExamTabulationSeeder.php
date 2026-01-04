<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExamTabulation;
use App\Models\Exam;

class ExamTabulationSeeder extends Seeder
{
	public function run(): void
	{
		$exam = Exam::query()->inRandomOrder()->first();
		for ($i = 1; $i <= 20; $i++) {
			$obt = rand(200, 500);
			$max = 500;
			ExamTabulation::create([
				'school_id' => 1,
				'exam_id' => $exam?->id,
				'class_name' => 'Class 10',
				'section_name' => 'A',
				'student_id' => $i,
				'student_name' => 'Student '.$i,
				'admission_no' => 'ADM'.str_pad((string)$i, 4, '0', STR_PAD_LEFT),
				'roll_no' => (string)$i,
				'total_marks' => $obt,
				'max_total_marks' => $max,
				'percentage' => round($obt / $max * 100, 2),
				'grade' => $obt >= 450 ? 'A+' : ($obt >= 400 ? 'A' : ($obt >= 300 ? 'B' : 'C')),
				'result_status' => $obt >= 250 ? 'pass' : 'fail',
				'rank' => null,
				'remarks' => null,
				'status' => 'published',
			]);
		}
	}
}



