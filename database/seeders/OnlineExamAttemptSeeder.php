<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OnlineExam;
use App\Models\OnlineExamAttempt;
use App\Models\User;
use App\Models\School;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class OnlineExamAttemptSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing data
        $onlineExams = OnlineExam::all();
        
        // Check if we have the necessary data
        if ($onlineExams->isEmpty()) {
            $this->command->error('No online exams found!');
            $this->command->info('Please run the OnlineExamSeeder first:');
            $this->command->line('php artisan db:seed --class=OnlineExamSeeder');
            return;
        }

        // Get or create students
        $students = $this->getOrCreateStudents();

        if ($students->isEmpty()) {
            $this->command->error('Could not find or create students!');
            return;
        }

        $this->command->info("Found {$onlineExams->count()} exams and {$students->count()} students");

        $createdCount = 0;
        $skippedCount = 0;

        foreach ($onlineExams as $exam) {
            // Skip draft exams for attempts
            if ($exam->status === 'draft') {
                $this->command->warn("Skipping draft exam: {$exam->title}");
                $skippedCount++;
                continue;
            }

            // Create attempts for random students (30-80% of students)
            $participationRate = rand(30, 80) / 100;
            $participantCount = (int) ($students->count() * $participationRate);
            $participantCount = max(1, min($participantCount, $students->count()));

            $participants = $students->random($participantCount);

            $this->command->info("Creating attempts for exam: {$exam->title} ({$participantCount} students)");

            foreach ($participants as $student) {
                // Determine number of attempts (most students take 1, some take multiple)
                $maxAttempts = min($exam->max_attempts, 3);
                $numAttempts = 1;
                
                if ($maxAttempts > 1 && rand(1, 100) <= 20) { // 20% chance of multiple attempts
                    $numAttempts = rand(2, $maxAttempts);
                }

                for ($attemptNum = 1; $attemptNum <= $numAttempts; $attemptNum++) {
                    try {
                        $attempt = $this->createAttempt($exam, $student, $attemptNum);
                        $createdCount++;
                        
                        if ($attemptNum === 1) {
                            $status = $attempt->is_passed ? '✅ Passed' : '❌ Failed';
                            $this->command->line("  - {$student->name}: {$attempt->percentage}% ({$status})");
                        }
                    } catch (\Exception $e) {
                        $this->command->error("Failed to create attempt for {$student->name}: " . $e->getMessage());
                    }
                }
            }
        }

        $this->command->info("OnlineExamAttempt seeder completed!");
        $this->command->info("Created: {$createdCount} attempts");
        $this->command->info("Skipped: {$skippedCount} draft exams");
    }

    /**
     * Get existing students or create some if none exist
     */
    private function getOrCreateStudents()
    {
        $students = collect();
        
        // Get existing users to use as students
        $students = User::take(20)->get(); // Get up to 20 users
        
        if ($students->count() > 0) {
            $this->command->info('Using existing users as students for exam attempts.');
        }

        // If we have very few students, create some more
        if ($students->count() < 5) {
            $this->command->info("Only {$students->count()} students found. Creating additional sample students...");
            $newStudents = $this->createSampleStudents(10 - $students->count());
            $students = $students->concat($newStudents);
        }

        return $students;
    }

    /**
     * Create sample students
     */
    private function createSampleStudents(int $count): \Illuminate\Support\Collection
    {
        $students = collect();
        $firstSchool = School::first();
        
        for ($i = 1; $i <= $count; $i++) {
            try {
                $student = User::create([
                    'name' => "Student Test {$i}",
                    'email' => "student{$i}@example.com",
                    'password' => Hash::make('password'),
                    'school_id' => $firstSchool?->id,
                    'status' => true,
                ]);
                
                $students->push($student);
                $this->command->line("Created student: {$student->name}");
            } catch (\Exception $e) {
                $this->command->warn("Failed to create student {$i}: " . $e->getMessage());
            }
        }

        return $students;
    }

    /**
     * Create a single exam attempt
     */
    private function createAttempt(OnlineExam $exam, User $student, int $attemptNumber): OnlineExamAttempt
    {
        // Check if attempt already exists
        $existingAttempt = OnlineExamAttempt::where('online_exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->where('attempt_number', $attemptNumber)
            ->first();

        if ($existingAttempt) {
            return $existingAttempt; // Return existing attempt instead of creating duplicate
        }

        // Generate realistic attempt timing
        $examStartTime = $exam->start_datetime;
        $examEndTime = $exam->end_datetime;
        
        // Student starts exam within the exam window
        $startedAt = Carbon::parse($examStartTime)->addMinutes(rand(0, 30));
        
        // Calculate time taken (between 50% and 100% of allocated time, or early submission)
        $allocatedMinutes = $exam->duration_minutes;
        $timeTakenMinutes = rand((int)($allocatedMinutes * 0.5), $allocatedMinutes);
        
        // Some students submit early (20% chance)
        if (rand(1, 100) <= 20) {
            $timeTakenMinutes = rand((int)($allocatedMinutes * 0.3), (int)($allocatedMinutes * 0.8));
        }
        
        $submittedAt = $startedAt->copy()->addMinutes($timeTakenMinutes);
        
        // Ensure submission is within exam window
        if ($submittedAt > $examEndTime) {
            $submittedAt = $examEndTime;
            $timeTakenMinutes = $startedAt->diffInMinutes($submittedAt);
        }

        // Generate performance based on attempt number (usually improves)
        $basePerformance = $this->generatePerformance($attemptNumber);
        $marksObtained = ($basePerformance / 100) * $exam->total_marks;
        
        // Apply negative marking if enabled
        if ($exam->negative_marking) {
            $wrongAnswers = rand(1, 5); // Assume some wrong answers
            $negativeDeduction = $wrongAnswers * $exam->negative_marks;
            $marksObtained = max(0, $marksObtained - $negativeDeduction);
            $basePerformance = ($marksObtained / $exam->total_marks) * 100;
        }

        // Generate sample answers
        $answers = $this->generateAnswers($exam);

        // Generate proctoring data if enabled
        $proctoringData = null;
        if ($exam->enable_proctoring) {
            $proctoringData = $this->generateProctoringData($basePerformance);
        }

        // Determine submission status
        $status = rand(1, 100) <= 95 ? 'submitted' : 'auto_submitted'; // 5% auto-submitted

        return OnlineExamAttempt::create([
            'online_exam_id' => $exam->id,
            'student_id' => $student->id,
            'started_at' => $startedAt,
            'submitted_at' => $submittedAt,
            'time_taken_minutes' => $timeTakenMinutes,
            'total_marks_obtained' => round($marksObtained, 2),
            'percentage' => round($basePerformance, 2),
            'status' => $status,
            'answers' => $answers,
            'proctoring_data' => $proctoringData,
            'is_passed' => $basePerformance >= (($exam->passing_marks / $exam->total_marks) * 100),
            'attempt_number' => $attemptNumber,
        ]);
    }

    /**
     * Generate performance percentage based on attempt number
     */
    private function generatePerformance(int $attemptNumber): float
    {
        // Base performance ranges
        $performanceRanges = [
            1 => [25, 95],  // First attempt: wider range
            2 => [35, 98],  // Second attempt: usually better
            3 => [40, 100], // Third attempt: often best
        ];

        $range = $performanceRanges[$attemptNumber] ?? [30, 90];
        
        // Weight towards higher scores for multiple attempts
        if ($attemptNumber > 1) {
            // 70% chance of improvement
            if (rand(1, 100) <= 70) {
                $range[0] = max($range[0], 50); // Minimum 50% on retakes
            }
        }

        return rand($range[0] * 10, $range[1] * 10) / 10; // One decimal place
    }

    /**
     * Generate sample answers for the exam
     */
    private function generateAnswers(OnlineExam $exam): array
    {
        $answers = [];
        $questions = $exam->questions;

        if ($questions->isEmpty()) {
            // Generate dummy answers if no questions attached
            for ($i = 1; $i <= 10; $i++) {
                $answers[$i] = $this->getRandomAnswer();
            }
            return $answers;
        }

        foreach ($questions as $question) {
            $answers[$question->id] = $this->getRandomAnswer($question->type ?? 'mcq');
        }

        return $answers;
    }

    /**
     * Get a random answer based on question type
     */
    private function getRandomAnswer(string $type = 'mcq'): string
    {
        switch ($type) {
            case 'mcq':
            case 'multiple_choice':
                return ['A', 'B', 'C', 'D'][rand(0, 3)];
            
            case 'true_false':
            case 'boolean':
                return ['True', 'False'][rand(0, 1)];
            
            case 'short_answer':
            case 'text':
                return 'Sample answer for short question';
            
            case 'essay':
            case 'long_answer':
                return 'This is a sample essay answer that demonstrates student understanding of the topic.';
            
            default:
                return ['A', 'B', 'C', 'D'][rand(0, 3)];
        }
    }

    /**
     * Generate proctoring data based on performance
     */
    private function generateProctoringData(float $performance): array
    {
        // Students with lower performance might have more violations
        $suspicionLevel = $performance < 40 ? 'high' : ($performance < 70 ? 'medium' : 'low');

        $data = [
            'session_start' => now()->subMinutes(rand(30, 120))->toISOString(),
            'total_duration' => rand(30, 120),
        ];

        switch ($suspicionLevel) {
            case 'high':
                $data = array_merge($data, [
                    'tab_switches' => rand(5, 15),
                    'copy_attempts' => rand(2, 8),
                    'paste_attempts' => rand(1, 5),
                    'focus_lost_count' => rand(3, 10),
                    'face_detection_failures' => rand(2, 6),
                    'multiple_monitors_detected' => rand(0, 1) == 1,
                    'suspicious_browser_activity' => true,
                ]);
                break;

            case 'medium':
                $data = array_merge($data, [
                    'tab_switches' => rand(1, 5),
                    'copy_attempts' => rand(0, 2),
                    'paste_attempts' => rand(0, 1),
                    'focus_lost_count' => rand(1, 3),
                    'face_detection_failures' => rand(0, 2),
                    'multiple_monitors_detected' => false,
                    'suspicious_browser_activity' => false,
                ]);
                break;

            case 'low':
            default:
                $data = array_merge($data, [
                    'tab_switches' => rand(0, 2),
                    'copy_attempts' => 0,
                    'paste_attempts' => 0,
                    'focus_lost_count' => rand(0, 1),
                    'face_detection_failures' => 0,
                    'multiple_monitors_detected' => false,
                    'suspicious_browser_activity' => false,
                ]);
                break;
        }

        return $data;
    }
}