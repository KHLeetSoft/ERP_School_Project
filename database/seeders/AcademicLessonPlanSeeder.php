<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AcademicLessonPlan;
use App\Models\AcademicSubject;
use App\Models\AcademicSyllabus;
use App\Models\School;

class AcademicLessonPlanSeeder extends Seeder
{
    public function run(): void
    {
        $schoolId = School::query()->value('id') ?? 1;
        $subjects = AcademicSubject::query()->forSchool($schoolId)->take(3)->get();
        
        if ($subjects->isEmpty()) {
            return;
        }

        foreach ($subjects as $subject) {
            $syllabus = AcademicSyllabus::query()->forSchool($schoolId)->where('subject_id', $subject->id)->first();
            
            $this->createLessonPlans($schoolId, $subject->id, $syllabus?->id);
        }
    }

    private function createLessonPlans(int $schoolId, int $subjectId, ?int $syllabusId): void
    {
        $lessonPlans = [
            [
                'title' => 'Introduction to Basic Concepts',
                'lesson_number' => 1,
                'unit_number' => 1,
                'learning_objectives' => [
                    'Understand fundamental principles',
                    'Identify key terminology',
                    'Apply basic concepts to simple problems'
                ],
                'prerequisites' => ['Basic reading skills', 'Elementary math knowledge'],
                'materials_needed' => ['Textbook', 'Whiteboard markers', 'Handouts'],
                'lesson_duration' => 45,
                'teaching_methods' => ['Lecture', 'Group discussion', 'Hands-on activities'],
                'activities' => [
                    'Ice-breaker introduction',
                    'Concept explanation with examples',
                    'Group problem-solving exercise'
                ],
                'assessment_methods' => ['Class participation', 'Quick quiz', 'Homework assignment'],
                'homework' => 'Complete practice problems 1-10 from textbook',
                'notes' => 'Focus on building confidence and engagement',
                'planned_date' => now()->addDays(1),
                'difficulty_level' => 1,
                'estimated_student_count' => 25,
                'room_requirements' => 'Standard classroom with projector',
                'technology_needed' => 'Projector, computer, internet access',
                'special_considerations' => 'Some students may need additional support'
            ],
            [
                'title' => 'Advanced Problem Solving Techniques',
                'lesson_number' => 2,
                'unit_number' => 1,
                'learning_objectives' => [
                    'Master complex problem-solving strategies',
                    'Apply analytical thinking',
                    'Develop critical reasoning skills'
                ],
                'prerequisites' => ['Completion of basic concepts', 'Problem-solving foundation'],
                'materials_needed' => ['Advanced worksheets', 'Calculators', 'Reference materials'],
                'lesson_duration' => 60,
                'teaching_methods' => ['Problem-based learning', 'Peer teaching', 'Individual practice'],
                'activities' => [
                    'Complex problem demonstration',
                    'Student-led problem solving',
                    'Individual challenge problems'
                ],
                'assessment_methods' => ['Problem-solving test', 'Class presentation', 'Portfolio review'],
                'homework' => 'Solve advanced problems and prepare presentation',
                'notes' => 'Encourage creative thinking and multiple solution approaches',
                'planned_date' => now()->addDays(3),
                'difficulty_level' => 2,
                'estimated_student_count' => 20,
                'room_requirements' => 'Classroom with movable desks for group work',
                'technology_needed' => 'Smart board, tablets for interactive work',
                'special_considerations' => 'Provide extension activities for advanced students'
            ],
            [
                'title' => 'Practical Application and Real-world Examples',
                'lesson_number' => 3,
                'unit_number' => 2,
                'learning_objectives' => [
                    'Connect theory to real-world applications',
                    'Analyze practical case studies',
                    'Develop practical implementation skills'
                ],
                'prerequisites' => ['Understanding of core concepts', 'Basic application skills'],
                'materials_needed' => ['Case study materials', 'Real-world examples', 'Field trip resources'],
                'lesson_duration' => 90,
                'teaching_methods' => ['Case study analysis', 'Field work', 'Project-based learning'],
                'activities' => [
                    'Case study discussion',
                    'Field observation and data collection',
                    'Project planning and execution'
                ],
                'assessment_methods' => ['Case study report', 'Field work assessment', 'Project evaluation'],
                'homework' => 'Complete case study analysis and prepare field report',
                'notes' => 'Emphasize practical relevance and career connections',
                'planned_date' => now()->addDays(5),
                'difficulty_level' => 3,
                'estimated_student_count' => 18,
                'room_requirements' => 'Flexible space for project work and presentations',
                'technology_needed' => 'Video conferencing equipment, data analysis software',
                'special_considerations' => 'Arrange for guest speakers from industry'
            ]
        ];

        foreach ($lessonPlans as $planData) {
            AcademicLessonPlan::updateOrCreate(
                [
                    'school_id' => $schoolId,
                    'subject_id' => $subjectId,
                    'lesson_number' => $planData['lesson_number']
                ],
                array_merge($planData, [
                    'school_id' => $schoolId,
                    'subject_id' => $subjectId,
                    'syllabus_id' => $syllabusId,
                    'status' => true,
                    'completion_status' => 'planned'
                ])
            );
        }
    }
}
