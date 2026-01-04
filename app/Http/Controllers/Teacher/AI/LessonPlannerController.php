<?php

namespace App\Http\Controllers\Teacher\AI;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;

class LessonPlannerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('teacher.ai.lesson-planner');
    }

    public function generateLessonPlan(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:100',
            'topic' => 'required|string|max:200',
            'grade_level' => 'required|string|max:50',
            'duration' => 'required|integer|min:15|max:180',
            'learning_objectives' => 'nullable|string|max:500',
            'teaching_style' => 'nullable|string|max:100',
        ]);

        try {
            $subject = $request->input('subject');
            $topic = $request->input('topic');
            $gradeLevel = $request->input('grade_level');
            $duration = $request->input('duration');
            $learningObjectives = $request->input('learning_objectives');
            $teachingStyle = $request->input('teaching_style', 'interactive');

            $prompt = "Create a detailed lesson plan for the following specifications:

            Subject: {$subject}
            Topic: {$topic}
            Grade Level: {$gradeLevel}
            Duration: {$duration} minutes
            Teaching Style: {$teachingStyle}
            " . ($learningObjectives ? "Learning Objectives: {$learningObjectives}" : "") . "

            Please provide a comprehensive lesson plan that includes:
            1. Learning Objectives (3-5 specific, measurable objectives)
            2. Materials Needed
            3. Lesson Structure with time allocations:
               - Introduction/Warm-up (5-10 minutes)
               - Main Content/Activities (main portion)
               - Practice/Application (10-15 minutes)
               - Assessment/Closure (5-10 minutes)
            4. Teaching Methods and Activities
            5. Assessment Strategies
            6. Differentiation Strategies for different learning needs
            7. Homework/Extension Activities
            8. Resources and References

            Format the response in a clear, structured way that teachers can easily follow and implement.";

            $response = OpenAI::chat()->create([
                'model' => 'gpt-4o-mini',
                'messages' => [
                    ['role' => 'user', 'content' => $prompt]
                ],
                'max_tokens' => 2000,
                'temperature' => 0.7,
            ]);

            $lessonPlan = $response->choices[0]->message->content ?? "Failed to generate lesson plan.";

            return response()->json([
                'success' => true,
                'lesson_plan' => $lessonPlan,
                'metadata' => [
                    'subject' => $subject,
                    'topic' => $topic,
                    'grade_level' => $gradeLevel,
                    'duration' => $duration,
                    'generated_at' => now()->format('Y-m-d H:i:s')
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to generate lesson plan. Please try again.',
                'message' => 'I apologize, but I couldn\'t generate a lesson plan at this time. Please try again.'
            ], 500);
        }
    }

    public function getSubjectSuggestions(Request $request)
    {
        $subjects = [
            'Mathematics', 'English Language Arts', 'Science', 'Social Studies',
            'History', 'Geography', 'Physics', 'Chemistry', 'Biology',
            'Computer Science', 'Art', 'Music', 'Physical Education',
            'Foreign Languages', 'Economics', 'Psychology', 'Literature'
        ];

        return response()->json(['subjects' => $subjects]);
    }

    public function getGradeLevels()
    {
        $gradeLevels = [
            'Elementary' => ['K', '1st Grade', '2nd Grade', '3rd Grade', '4th Grade', '5th Grade'],
            'Middle School' => ['6th Grade', '7th Grade', '8th Grade'],
            'High School' => ['9th Grade', '10th Grade', '11th Grade', '12th Grade'],
            'Higher Education' => ['College Level', 'University Level', 'Graduate Level']
        ];

        return response()->json(['grade_levels' => $gradeLevels]);
    }
}
