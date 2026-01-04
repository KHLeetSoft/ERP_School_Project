<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Grade;
use App\Models\User;
use App\Models\Student;

class GradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating sample grades...');

        // Get teacher user
        $teacher = User::where('email', 'teacher@example.com')->first();
        if (!$teacher) {
            $this->command->warn('Teacher user not found. Please run UserSeeder first.');
            return;
        }

        // Get students
        $students = Student::take(5)->get();
        if ($students->isEmpty()) {
            $this->command->warn('No students found. Please run StudentSeeder first.');
            return;
        }

        $assignments = [
            ['name' => 'Math Quiz 1', 'type' => 'quiz', 'total_points' => 20],
            ['name' => 'Science Project', 'type' => 'project', 'total_points' => 100],
            ['name' => 'English Essay', 'type' => 'assignment', 'total_points' => 50],
            ['name' => 'History Test', 'type' => 'exam', 'total_points' => 80],
            ['name' => 'Physics Lab Report', 'type' => 'assignment', 'total_points' => 30],
        ];

        $subjects = ['Mathematics', 'Science', 'English', 'History', 'Physics'];
        $classes = ['Grade 10A', 'Grade 11B', 'Grade 9C', 'Grade 12A'];

        foreach ($students as $index => $student) {
            $assignment = $assignments[$index % count($assignments)];
            $subject = $subjects[$index % count($subjects)];
            $className = $classes[$index % count($classes)];
            
            // Generate random points (60-100% of total)
            $pointsEarned = rand(60, 100) / 100 * $assignment['total_points'];
            $percentage = ($pointsEarned / $assignment['total_points']) * 100;
            $letterGrade = $this->calculateLetterGrade($percentage);

            Grade::create([
                'teacher_id' => $teacher->id,
                'student_id' => $student->id,
                'class_name' => $className,
                'subject_name' => $subject,
                'assignment_name' => $assignment['name'],
                'assignment_type' => $assignment['type'],
                'points_earned' => round($pointsEarned, 2),
                'total_points' => $assignment['total_points'],
                'percentage' => round($percentage, 2),
                'letter_grade' => $letterGrade,
                'comments' => $this->getRandomComment($letterGrade),
                'graded_date' => now()->subDays(rand(1, 30)),
                'status' => rand(0, 1) ? 'published' : 'draft',
            ]);
        }

        $this->command->info('Sample grades created successfully!');
    }

    private function calculateLetterGrade($percentage)
    {
        if ($percentage >= 97) return 'A+';
        if ($percentage >= 93) return 'A';
        if ($percentage >= 90) return 'A-';
        if ($percentage >= 87) return 'B+';
        if ($percentage >= 83) return 'B';
        if ($percentage >= 80) return 'B-';
        if ($percentage >= 77) return 'C+';
        if ($percentage >= 73) return 'C';
        if ($percentage >= 70) return 'C-';
        if ($percentage >= 67) return 'D+';
        if ($percentage >= 65) return 'D';
        return 'F';
    }

    private function getRandomComment($letterGrade)
    {
        $comments = [
            'A+' => ['Excellent work!', 'Outstanding performance!', 'Perfect!'],
            'A' => ['Great job!', 'Well done!', 'Excellent work!'],
            'B+' => ['Good work!', 'Nice effort!', 'Well done!'],
            'B' => ['Good job!', 'Nice work!', 'Keep it up!'],
            'C+' => ['Satisfactory work.', 'Good effort.', 'Keep practicing.'],
            'C' => ['Average work.', 'Room for improvement.', 'Keep trying.'],
            'D' => ['Needs improvement.', 'Please review the material.', 'Work harder.'],
            'F' => ['Please see me for help.', 'Needs significant improvement.', 'Please retake.'],
        ];

        $gradeComments = $comments[$letterGrade] ?? ['No comment.'];
        return $gradeComments[array_rand($gradeComments)];
    }
}
