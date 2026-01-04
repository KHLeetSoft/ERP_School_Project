<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExamGrade;

class ExamGradeSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['A+', 10, 90, 100, 'Outstanding'],
            ['A',  9,  80, 89.99, 'Excellent'],
            ['B+', 8,  70, 79.99, 'Very Good'],
            ['B',  7,  60, 69.99, 'Good'],
            ['C',  6,  50, 59.99, 'Average'],
            ['D',  5,  40, 49.99, 'Below Average'],
            ['E',  0,  0,  39.99, 'Fail'],
        ];
        foreach ($rows as [$grade,$gp,$min,$max,$remark]) {
            ExamGrade::create([
                'school_id' => null,
                'grade' => $grade,
                'grade_point' => $gp,
                'min_percentage' => $min,
                'max_percentage' => $max,
                'remark' => $remark,
                'description' => null,
                'status' => 'active',
            ]);
        }
    }
}


