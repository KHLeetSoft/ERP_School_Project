<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AcademicSyllabus;
use App\Models\AcademicSubject;
use App\Models\School;

class AcademicSyllabusSeeder extends Seeder
{
    public function run(): void
    {
        $schoolId = School::query()->value('id') ?? 1;
        $subject = AcademicSubject::query()->forSchool($schoolId)->first();
        if (!$subject) {
            return;
        }

        $records = [
            [
                'subject_id' => $subject->id,
                'term' => 'Term 1',
                'title' => 'Foundations and Basics',
                'description' => 'Core concepts and introductory units for the subject.',
                'total_units' => 10,
                'completed_units' => 3,
                'start_date' => now()->startOfMonth(),
                'end_date' => now()->addMonths(2)->endOfMonth(),
                'status' => true,
            ],
            [
                'subject_id' => $subject->id,
                'term' => 'Term 2',
                'title' => 'Advanced Topics',
                'description' => 'Deeper exploration and projects.',
                'total_units' => 8,
                'completed_units' => 0,
                'start_date' => now()->addMonths(3)->startOfMonth(),
                'end_date' => now()->addMonths(5)->endOfMonth(),
                'status' => true,
            ],
        ];

        foreach ($records as $data) {
            AcademicSyllabus::updateOrCreate(
                [ 'school_id' => $schoolId, 'subject_id' => $data['subject_id'], 'title' => $data['title'] ],
                array_merge($data, [ 'school_id' => $schoolId ])
            );
        }
    }
}


