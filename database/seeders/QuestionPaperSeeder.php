<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\QuestionPaper;
use App\Models\QuestionPaperQuestion;
use App\Models\Question;

class QuestionPaperSeeder extends Seeder
{
    public function run(): void
    {
        if (Question::count() === 0) {
            return;
        }

        $paperSpecs = [
            ['title' => 'Mathematics Sample Paper', 'total_marks' => 20, 'duration_mins' => 60],
            ['title' => 'Science Sample Paper',     'total_marks' => 15, 'duration_mins' => 45],
            ['title' => 'English Sample Paper',     'total_marks' => 10, 'duration_mins' => 30],
        ];

        foreach ($paperSpecs as $spec) {
            $paper = QuestionPaper::create([
                'school_id' => 1,
                'title' => $spec['title'],
                'subject_id' => null,
                'total_marks' => $spec['total_marks'],
                'duration_mins' => $spec['duration_mins'],
                'generator_payload' => ['seeded' => true],
                'status' => 'draft',
            ]);

            $marksAccumulated = 0;
            $ordering = 1;
            $questions = Question::where('status', 'active')->orderBy('id')->get();
            foreach ($questions as $q) {
                $qMarks = (int)($q->marks ?? 1);
                if ($qMarks <= 0) {
                    $qMarks = 1;
                }
                if ($marksAccumulated + $qMarks > (int)$paper->total_marks) {
                    continue;
                }

                QuestionPaperQuestion::create([
                    'question_paper_id' => $paper->id,
                    'question_id' => $q->id,
                    'marks' => $qMarks,
                    'ordering' => $ordering++,
                ]);

                $marksAccumulated += $qMarks;
                if ($marksAccumulated >= (int)$paper->total_marks) {
                    break;
                }
            }
        }
    }
}


