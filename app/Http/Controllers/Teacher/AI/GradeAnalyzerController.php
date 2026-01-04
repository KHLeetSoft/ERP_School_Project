<?php

namespace App\Http\Controllers\Teacher\AI;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;
use App\Models\ExamMark;
use App\Models\Student;
use App\Models\ClassRoom;

class GradeAnalyzerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $teacher = auth()->user()->teacher;
        
        // Get recent exam data for analysis
        $recentExams = ExamMark::whereHas('exam', function($query) use ($teacher) {
            $query->where('teacher_id', $teacher->id ?? 0);
        })->with(['student', 'exam'])->latest()->take(100)->get();

        $classrooms = $teacher ? $teacher->classes : collect();
        $students = $teacher ? $teacher->students : collect();

        return view('teacher.ai.grade-analyzer', compact('recentExams', 'classrooms', 'students'));
    }

    public function analyzeGrades(Request $request)
    {
        $request->validate([
            'class_id' => 'nullable|exists:class_rooms,id',
            'subject' => 'nullable|string|max:100',
            'exam_type' => 'nullable|string|max:100',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'analysis_type' => 'required|string|in:overall,trends,comparison,individual,recommendations',
        ]);

        try {
            $teacher = auth()->user()->teacher;
            $classId = $request->input('class_id');
            $subject = $request->input('subject');
            $examType = $request->input('exam_type');
            $dateFrom = $request->input('date_from');
            $dateTo = $request->input('date_to');
            $analysisType = $request->input('analysis_type');

            // Get grade data based on filters
            $query = ExamMark::whereHas('exam', function($q) use ($teacher) {
                $q->where('teacher_id', $teacher->id ?? 0);
            })->with(['student', 'exam']);

            if ($classId) {
                $query->whereHas('student', function($q) use ($classId) {
                    $q->where('class_id', $classId);
                });
            }

            if ($subject) {
                $query->whereHas('exam', function($q) use ($subject) {
                    $q->where('subject', 'like', "%{$subject}%");
                });
            }

            if ($examType) {
                $query->whereHas('exam', function($q) use ($examType) {
                    $q->where('exam_type', $examType);
                });
            }

            if ($dateFrom) {
                $query->whereHas('exam', function($q) use ($dateFrom) {
                    $q->where('created_at', '>=', $dateFrom);
                });
            }

            if ($dateTo) {
                $query->whereHas('exam', function($q) use ($dateTo) {
                    $q->where('created_at', '<=', $dateTo);
                });
            }

            $grades = $query->get();

            if ($grades->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No grade data found for the selected criteria.'
                ]);
            }

            // Prepare data for AI analysis
            $gradeData = $grades->map(function($grade) {
                return [
                    'student_name' => $grade->student->name ?? 'Unknown',
                    'subject' => $grade->exam->subject ?? 'Unknown',
                    'exam_type' => $grade->exam->exam_type ?? 'Unknown',
                    'marks_obtained' => $grade->marks_obtained ?? 0,
                    'total_marks' => $grade->total_marks ?? 100,
                    'percentage' => $grade->percentage ?? 0,
                    'grade' => $grade->grade ?? 'F',
                    'date' => $grade->exam->created_at->format('Y-m-d') ?? 'Unknown'
                ];
            })->toArray();

            $analysisPrompt = $this->buildAnalysisPrompt($analysisType, $gradeData, $request->all());

            $response = OpenAI::chat()->create([
                'model' => 'gpt-4o-mini',
                'messages' => [
                    ['role' => 'user', 'content' => $analysisPrompt]
                ],
                'max_tokens' => 2000,
                'temperature' => 0.7,
            ]);

            $analysis = $response->choices[0]->message->content ?? "Failed to generate analysis.";

            // Calculate basic statistics
            $statistics = $this->calculateStatistics($grades);

            return response()->json([
                'success' => true,
                'analysis' => $analysis,
                'statistics' => $statistics,
                'metadata' => [
                    'total_students' => $grades->count(),
                    'analysis_type' => $analysisType,
                    'date_range' => $dateFrom && $dateTo ? "{$dateFrom} to {$dateTo}" : 'All time',
                    'generated_at' => now()->format('Y-m-d H:i:s')
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to analyze grades. Please try again.',
                'message' => 'I apologize, but I couldn\'t analyze the grades at this time. Please try again.'
            ], 500);
        }
    }

    private function buildAnalysisPrompt($analysisType, $gradeData, $filters)
    {
        $dataSummary = "Grade Data Summary:\n";
        $dataSummary .= "Total Records: " . count($gradeData) . "\n";
        $dataSummary .= "Date Range: " . ($filters['date_from'] ?? 'N/A') . " to " . ($filters['date_to'] ?? 'N/A') . "\n";
        $dataSummary .= "Subject: " . ($filters['subject'] ?? 'All') . "\n";
        $dataSummary .= "Exam Type: " . ($filters['exam_type'] ?? 'All') . "\n\n";

        $dataSummary .= "Sample Data:\n";
        foreach (array_slice($gradeData, 0, 10) as $record) {
            $dataSummary .= "- {$record['student_name']}: {$record['marks_obtained']}/{$record['total_marks']} ({$record['percentage']}%) - Grade: {$record['grade']}\n";
        }

        $prompts = [
            'overall' => "Analyze the overall performance of students based on the following grade data. Provide insights on:
            1. Overall class performance trends
            2. Grade distribution analysis
            3. Average performance metrics
            4. Key strengths and areas for improvement
            5. Recommendations for the teacher

            " . $dataSummary,

            'trends' => "Analyze performance trends over time based on the following grade data. Focus on:
            1. Performance trends over time
            2. Improvement or decline patterns
            3. Seasonal or periodic variations
            4. Predictions for future performance
            5. Recommendations for addressing trends

            " . $dataSummary,

            'comparison' => "Compare student performance across different criteria based on the following grade data. Analyze:
            1. Performance comparison between students
            2. Subject-wise performance comparison
            3. Exam type performance comparison
            4. Top and bottom performers analysis
            5. Recommendations for differentiated instruction

            " . $dataSummary,

            'individual' => "Provide individual student performance analysis based on the following grade data. Focus on:
            1. Individual student performance patterns
            2. Strengths and weaknesses for each student
            3. Personalized recommendations
            4. Areas needing attention
            5. Strategies for improvement

            " . $dataSummary,

            'recommendations' => "Provide actionable recommendations for improving student performance based on the following grade data. Include:
            1. Teaching strategy recommendations
            2. Assessment method improvements
            3. Student support strategies
            4. Curriculum adjustments
            5. Parent communication suggestions

            " . $dataSummary
        ];

        return $prompts[$analysisType] ?? $prompts['overall'];
    }

    private function calculateStatistics($grades)
    {
        $percentages = $grades->pluck('percentage')->filter()->values();
        
        if ($percentages->isEmpty()) {
            return [
                'average' => 0,
                'median' => 0,
                'highest' => 0,
                'lowest' => 0,
                'pass_rate' => 0,
                'total_students' => 0
            ];
        }

        return [
            'average' => round($percentages->avg(), 2),
            'median' => round($percentages->median(), 2),
            'highest' => $percentages->max(),
            'lowest' => $percentages->min(),
            'pass_rate' => round(($percentages->where('>=', 50)->count() / $percentages->count()) * 100, 2),
            'total_students' => $percentages->count(),
            'grade_distribution' => [
                'A' => $percentages->where('>=', 90)->count(),
                'B' => $percentages->where('>=', 80)->where('<', 90)->count(),
                'C' => $percentages->where('>=', 70)->where('<', 80)->count(),
                'D' => $percentages->where('>=', 60)->where('<', 70)->count(),
                'F' => $percentages->where('<', 60)->count(),
            ]
        ];
    }
}
