<?php

namespace App\Http\Controllers\Teacher\AI;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;

class AssessmentGeneratorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('teacher.ai.assessment-generator');
    }

    public function generateAssessment(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:100',
            'topic' => 'required|string|max:200',
            'grade_level' => 'required|string|max:50',
            'assessment_type' => 'required|string|in:quiz,test,exam,project,assignment',
            'question_count' => 'required|integer|min:5|max:50',
            'difficulty_level' => 'required|string|in:easy,medium,hard,mixed',
            'question_types' => 'required|array',
            'question_types.*' => 'in:multiple_choice,true_false,short_answer,essay,fill_blank,matching',
            'time_limit' => 'nullable|integer|min:10|max:180',
            'learning_objectives' => 'nullable|string|max:500',
        ]);

        try {
            $subject = $request->input('subject');
            $topic = $request->input('topic');
            $gradeLevel = $request->input('grade_level');
            $assessmentType = $request->input('assessment_type');
            $questionCount = $request->input('question_count');
            $difficultyLevel = $request->input('difficulty_level');
            $questionTypes = $request->input('question_types');
            $timeLimit = $request->input('time_limit');
            $learningObjectives = $request->input('learning_objectives');

            $questionTypesText = implode(', ', $questionTypes);
            $timeLimitText = $timeLimit ? "Time Limit: {$timeLimit} minutes" : "No specific time limit";

            $prompt = "Create a comprehensive {$assessmentType} for the following specifications:

            Subject: {$subject}
            Topic: {$topic}
            Grade Level: {$gradeLevel}
            Number of Questions: {$questionCount}
            Difficulty Level: {$difficultyLevel}
            Question Types: {$questionTypesText}
            {$timeLimitText}
            " . ($learningObjectives ? "Learning Objectives: {$learningObjectives}" : "") . "

            Please generate:
            1. A clear title for the assessment
            2. Instructions for students
            3. {$questionCount} questions of the specified types
            4. Answer key with explanations
            5. Grading rubric (if applicable)
            6. Suggestions for differentiation

            Format each question clearly with:
            - Question number
            - Question text
            - Answer choices (for multiple choice)
            - Correct answer
            - Brief explanation

            Ensure questions are appropriate for the grade level and cover the topic comprehensively.";

            $response = OpenAI::chat()->create([
                'model' => 'gpt-4o-mini',
                'messages' => [
                    ['role' => 'user', 'content' => $prompt]
                ],
                'max_tokens' => 3000,
                'temperature' => 0.7,
            ]);

            $assessment = $response->choices[0]->message->content ?? "Failed to generate assessment.";

            return response()->json([
                'success' => true,
                'assessment' => $assessment,
                'metadata' => [
                    'subject' => $subject,
                    'topic' => $topic,
                    'grade_level' => $gradeLevel,
                    'assessment_type' => $assessmentType,
                    'question_count' => $questionCount,
                    'difficulty_level' => $difficultyLevel,
                    'generated_at' => now()->format('Y-m-d H:i:s')
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to generate assessment. Please try again.',
                'message' => 'I apologize, but I couldn\'t generate an assessment at this time. Please try again.'
            ], 500);
        }
    }

    public function getQuestionTypes()
    {
        $questionTypes = [
            'multiple_choice' => 'Multiple Choice',
            'true_false' => 'True/False',
            'short_answer' => 'Short Answer',
            'essay' => 'Essay',
            'fill_blank' => 'Fill in the Blank',
            'matching' => 'Matching',
            'numerical' => 'Numerical',
            'diagram' => 'Diagram/Visual'
        ];

        return response()->json(['question_types' => $questionTypes]);
    }

    public function getDifficultyLevels()
    {
        $difficultyLevels = [
            'easy' => 'Easy - Basic recall and understanding',
            'medium' => 'Medium - Application and analysis',
            'hard' => 'Hard - Synthesis and evaluation',
            'mixed' => 'Mixed - Variety of difficulty levels'
        ];

        return response()->json(['difficulty_levels' => $difficultyLevels]);
    }
}
