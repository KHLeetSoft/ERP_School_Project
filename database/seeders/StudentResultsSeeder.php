<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\StudentResult;
use App\Models\School;
use App\Models\User;
use App\Models\StudentDetail;
use App\Models\SchoolClass;
use App\Models\Subject;
use Faker\Factory as Faker;


class StudentResultsSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 10) as $i) {
            $schoolId = School::inRandomOrder()->first()->id ?? 1;
            $adminId = User::where('role_id', 2)->inRandomOrder()->first()->id ?? 1; // admin role_id = 2
            $studentIds = DB::table('student_details')->pluck('id')->toArray();
            $studentId = $faker->randomElement($studentIds);
            $classId = SchoolClass::inRandomOrder()->first()->id ?? 1;
            $subjectId  = Subject::inRandomOrder()->value('id') ?? 1;;

            $totalMarks = 100;
            $obtainedMarks = $faker->numberBetween(3, 100);
            $resultStatus = $obtainedMarks >= 33 ? 'Pass' : 'Fail';
            $grade          = $this->getGrade($obtainedMarks);

            StudentResult::create([
                'school_id'      => $schoolId,
                'admin_id'       => $adminId,
                'student_id'     => $studentId,
                'class_id'       => $classId,
                'subject_id'     => $subjectId,
                'exam_type'      => $faker->randomElement(['Unit Test', 'Midterm', 'Final Exam']),
                'marks_obtained' => $obtainedMarks,
                'total_marks'    => $totalMarks,
                'result_status'  => $resultStatus,
                'grade'          => $grade,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        }
    }
    private function getGrade(int $marks): string
    {
        if ($marks >= 90) return 'A+';
        if ($marks >= 80) return 'A';
        if ($marks >= 70) return 'B+';
        if ($marks >= 60) return 'B';
        if ($marks >= 50) return 'C';
        if ($marks >= 33) return 'D';
        return 'F';
    }
}
