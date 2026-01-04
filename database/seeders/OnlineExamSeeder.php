<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OnlineExam;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Question;
use Carbon\Carbon;

class OnlineExamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing data
        $classes = SchoolClass::all();
        $sections = Section::all();
        $subjects = Subject::all();
        $questions = Question::all();

        // Check if we have enough data
        if ($classes->count() < 1 || $sections->count() < 1 || $subjects->count() < 1) {
            $this->command->error('Insufficient data found:');
            $this->command->line("Classes: {$classes->count()}");
            $this->command->line("Sections: {$sections->count()}");
            $this->command->line("Subjects: {$subjects->count()}");
            $this->command->line("Questions: {$questions->count()}");
            $this->command->warn('Please create at least one class, section, and subject before running this seeder.');
            
            // Show helpful commands
            $this->command->info('You can create sample data using:');
            $this->command->line('php artisan db:seed --class=SchoolClassSeeder');
            $this->command->line('php artisan db:seed --class=SectionSeeder');
            $this->command->line('php artisan db:seed --class=SubjectSeeder');
            return;
        }

        // Create basic online exams with available data
        $examsData = [
            [
                'title' => 'Sample Mathematics Exam',
                'description' => 'Basic mathematics test covering fundamental concepts',
                'duration_minutes' => 60,
                'total_marks' => 100,
                'passing_marks' => 40,
                'negative_marking' => false,
                'randomize_questions' => true,
                'show_result_immediately' => false,
                'allow_calculator' => false,
                'allow_notes' => false,
                'max_attempts' => 1,
                'enable_proctoring' => false,
                'instructions' => 'Read all questions carefully and answer to the best of your ability.',
                'status' => 'draft',
            ],
            [
                'title' => 'Quick Science Quiz',
                'description' => 'Short quiz on basic science concepts',
                'duration_minutes' => 30,
                'total_marks' => 50,
                'passing_marks' => 25,
                'negative_marking' => false,
                'randomize_questions' => true,
                'show_result_immediately' => true,
                'allow_calculator' => true,
                'allow_notes' => false,
                'max_attempts' => 2,
                'enable_proctoring' => false,
                'instructions' => 'This is a practice quiz. No negative marking applied.',
                'status' => 'published',
            ],
            [
                'title' => 'English Comprehension Test',
                'description' => 'Test your reading and comprehension skills',
                'duration_minutes' => 45,
                'total_marks' => 75,
                'passing_marks' => 30,
                'negative_marking' => false,
                'randomize_questions' => false,
                'show_result_immediately' => false,
                'allow_calculator' => false,
                'allow_notes' => true,
                'max_attempts' => 1,
                'enable_proctoring' => false,
                'instructions' => 'Answer all questions. Notes are allowed for this test.',
                'status' => 'draft',
            ],
        ];

        $createdCount = 0;

        foreach ($examsData as $index => $examData) {
            // Use available data cyclically
            $classIndex = $index % $classes->count();
            $sectionIndex = $index % $sections->count();
            $subjectIndex = $index % $subjects->count();

            // Set dates relative to now
            $startDate = Carbon::now()->addDays(($index + 1) * 3);
            $endDate = $startDate->copy()->addMinutes($examData['duration_minutes']);

            $examData['class_id'] = $classes->get($classIndex)->id;
            $examData['section_id'] = $sections->get($sectionIndex)->id;
            $examData['subject_id'] = $subjects->get($subjectIndex)->id;
            $examData['start_datetime'] = $startDate;
            $examData['end_datetime'] = $endDate;

            try {
                $exam = OnlineExam::create($examData);

                // Attach questions if available
                if ($questions->count() > 0) {
                    $questionCount = min(5, $questions->count()); // Use fewer questions for safety
                    $selectedQuestions = $questions->take($questionCount);
                    $marksPerQuestion = (int) ($examData['total_marks'] / $questionCount);

                    foreach ($selectedQuestions as $qIndex => $question) {
                        $exam->questions()->attach($question->id, [
                            'marks' => $marksPerQuestion,
                            'order_number' => $qIndex + 1,
                        ]);
                    }
                }

                $createdCount++;
                $this->command->info("Created exam: {$exam->title}");
                
            } catch (\Exception $e) {
                $this->command->error("Failed to create exam '{$examData['title']}': " . $e->getMessage());
            }
        }

        $this->command->info("OnlineExam seeder completed! Created {$createdCount} exams.");
        
        if ($questions->count() == 0) {
            $this->command->warn('No questions were attached to exams as no questions exist in the database.');
            $this->command->info('Create questions using: php artisan db:seed --class=QuestionSeeder');
        }
    }
}