<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AcademicSubject;
use App\Models\School;

class AcademicSubjectSeeder extends Seeder
{
    public function run(): void
    {
        $schoolId = School::query()->value('id') ?? 1;

        $subjects = [
            [ 'name' => 'Mathematics',      'code' => 'MATH101', 'type' => 'theory',   'credit_hours' => 4, 'status' => true ],
            [ 'name' => 'Physics',          'code' => 'PHYS101', 'type' => 'theory',   'credit_hours' => 4, 'status' => true ],
            [ 'name' => 'Chemistry Lab',    'code' => 'CHEM1L',  'type' => 'lab',      'credit_hours' => 1, 'status' => true ],
            [ 'name' => 'English',          'code' => 'ENG101',  'type' => 'theory',   'credit_hours' => 3, 'status' => true ],
            [ 'name' => 'Computer Science', 'code' => 'CS101',   'type' => 'practical','credit_hours' => 3, 'status' => true ],
            [ 'name' => 'Biology',          'code' => 'BIO101',  'type' => 'theory',   'credit_hours' => 3, 'status' => true ],
        ];

        foreach ($subjects as $subject) {
            AcademicSubject::updateOrCreate(
                [ 'school_id' => $schoolId, 'code' => $subject['code'] ],
                array_merge($subject, ['school_id' => $schoolId])
            );
        }
    }
}


