<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Question;
use App\Models\QuestionCategory;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure a default category exists
        $category = QuestionCategory::first() ?? QuestionCategory::create([
            'name' => 'General Knowledge',
            'description' => 'Default question category',
        ]);

        $samples = [
            [
                'type' => 'mcq',
                'difficulty' => 'easy',
                'question_text' => 'What is the capital of France?',
                'options' => ['A' => 'Berlin', 'B' => 'Paris', 'C' => 'Madrid', 'D' => 'Rome'],
                'correct_answer' => 'B',
                'marks' => 2,
                'status' => 'active',
            ],
            [
                'type' => 'mcq',
                'difficulty' => 'medium',
                'question_text' => 'Which planet is known as the Red Planet?',
                'options' => ['A' => 'Earth', 'B' => 'Venus', 'C' => 'Mars', 'D' => 'Jupiter'],
                'correct_answer' => 'C',
                'marks' => 2,
                'status' => 'active',
            ],
            [
                'type' => 'true_false',
                'difficulty' => 'easy',
                'question_text' => 'The chemical symbol for water is H2O.',
                'options' => ['A' => 'True', 'B' => 'False'],
                'correct_answer' => 'A',
                'marks' => 1,
                'status' => 'active',
            ],
            [
                'type' => 'short_answer',
                'difficulty' => 'medium',
                'question_text' => 'Name the process by which plants make their food.',
                'correct_answer' => 'Photosynthesis',
                'marks' => 3,
                'status' => 'active',
            ],
        ];

        foreach ($samples as $q) {
            Question::firstOrCreate(
                [
                    'question_category_id' => $category->id,
                    'question_text' => $q['question_text'],
                ],
                [
                    'type' => $q['type'],
                    'difficulty' => $q['difficulty'] ?? 'medium',
                    'options' => $q['options'] ?? null,
                    'correct_answer' => $q['correct_answer'],
                    'explanation' => $q['explanation'] ?? null,
                    'marks' => $q['marks'],
                    'status' => $q['status'] ?? 'active',
                ]
            );
        }
    }
}



