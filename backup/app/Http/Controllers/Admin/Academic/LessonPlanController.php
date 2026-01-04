<?php

namespace App\Http\Controllers\Admin\Academic;

use App\Http\Controllers\Controller;
use App\Models\AcademicLessonPlan;
use App\Models\AcademicSubject;
use App\Models\AcademicSyllabus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Yajra\DataTables\Facades\DataTables;

class LessonPlanController extends Controller
{
    protected $adminUser;
    protected $schoolId;

    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware(function ($request, $next) {
            $this->adminUser = auth()->guard('admin')->user();
            $this->schoolId = $this->adminUser ? $this->adminUser->school_id : null;
            return $next($request);
        });
    }
    public function index(Request $request)
    {
        $adminSchoolId = auth()->user()->school_id ?? null;
        $query = AcademicLessonPlan::query()
            ->with(['subject', 'syllabus'])
            ->forSchool($adminSchoolId);

        if ($request->ajax()) {
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('subject', fn($row) => e(optional($row->subject)->name))
                ->addColumn('syllabus', fn($row) => e(optional($row->syllabus)->title ?? 'Not linked'))
                ->addColumn('lesson_info', function ($row) {
                    $info = "Lesson #{$row->lesson_number}";
                    if ($row->unit_number) {
                        $info .= " | Unit #{$row->unit_number}";
                    }
                    return $info;
                })
                ->addColumn('duration', fn($row) => $row->duration_text)
                ->addColumn('difficulty', fn($row) => $row->difficulty_text)
                ->addColumn('status_badge', fn($row) => $row->status_badge)
                ->addColumn('planned_date', fn($row) => $row->formatted_planned_date)
                ->addColumn('progress', function ($row) {
                    $percentage = $row->progress_percentage;
                    $color = match($percentage) {
                        0 => 'bg-secondary',
                        25 => 'bg-info',
                        50 => 'bg-warning',
                        100 => 'bg-success',
                        default => 'bg-secondary'
                    };
                    return "<div class='progress' style='height: 20px;'>
                                <div class='progress-bar {$color}' style='width: {$percentage}%'>{$percentage}%</div>
                            </div>";
                })
                ->addColumn('action', function ($row) {
                   $buttons = '<div class="d-flex justify-content">';
                    $buttons .= '<a href="' . route('admin.academic.lesson-plans.show', $row->id) . '" class="text-info me-2" title="View"><i class="bx bx-show"></i></a>';
                    $buttons .= '<a href="' . route('admin.academic.lesson-plans.edit', $row->id) . '" class="text-primary me-2" title="Edit"><i class="bx bxs-edit"></i></a>';
                    $buttons .= '<a href="javascript:void(0);" data-id="' . $row->id . '" class="text-danger delete-lesson-plan-btn" title="Delete"><i class="bx bx-trash"></i></a>';
                    $buttons .= '</div>';

                    return $buttons;

                })
                ->rawColumns(['select', 'status_badge', 'progress', 'action'])
                ->make(true);
        }

        $stats = $this->getLessonPlanStats($adminSchoolId);
        return view('admin.academic.lesson-plans.index', compact('stats'));
    }

    public function create()
    {
        $subjects = AcademicSubject::query()->forSchool(auth()->user()->school_id)->orderBy('name')->get();
        $syllabi = AcademicSyllabus::query()->forSchool(auth()->user()->school_id)->orderBy('title')->get();
        
        return view('admin.academic.lesson-plans.create', compact('subjects', 'syllabi'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject_id' => ['required', 'exists:academic_subjects,id'],
            'syllabus_id' => ['nullable', 'exists:academic_syllabi,id'],
            'title' => ['required', 'string', 'max:255'],
            'lesson_number' => ['nullable', 'integer', 'min:1', 'max:999'],
            'unit_number' => ['nullable', 'integer', 'min:1', 'max:999'],
            'learning_objectives' => ['nullable', 'array'],
            'learning_objectives.*' => ['string', 'max:500'],
            'prerequisites' => ['nullable', 'array'],
            'prerequisites.*' => ['string', 'max:500'],
            'materials_needed' => ['nullable', 'array'],
            'materials_needed.*' => ['string', 'max:500'],
            'lesson_duration' => ['nullable', 'integer', 'min:15', 'max:480'],
            'teaching_methods' => ['nullable', 'array'],
            'teaching_methods.*' => ['string', 'max:500'],
            'activities' => ['nullable', 'array'],
            'activities.*' => ['string', 'max:500'],
            'assessment_methods' => ['nullable', 'array'],
            'assessment_methods.*' => ['string', 'max:500'],
            'homework' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'planned_date' => ['nullable', 'date'],
            'difficulty_level' => ['required', 'integer', 'in:1,2,3'],
            'estimated_student_count' => ['nullable', 'integer', 'min:1', 'max:1000'],
            'room_requirements' => ['nullable', 'string'],
            'technology_needed' => ['nullable', 'string'],
            'special_considerations' => ['nullable', 'string'],
            'status' => ['nullable', 'boolean'],
            'completion_status' => ['required', 'in:planned,in_progress,completed,postponed,cancelled'],
        ]);

        $validated['school_id'] = auth()->user()->school_id;
        $validated['status'] = (bool) ($validated['status'] ?? true);
        
        // Clean up empty arrays
        $validated = $this->cleanEmptyArrays($validated);
        
        AcademicLessonPlan::create($validated);
        
        return redirect()->route('admin.academic.lesson-plans.index')
            ->with('success', 'Lesson plan created successfully');
    }

    public function show(AcademicLessonPlan $lessonPlan)
    {
        $lessonPlan->load(['subject', 'syllabus']);
        return view('admin.academic.lesson-plans.show', compact('lessonPlan'));
    }

    public function edit(AcademicLessonPlan $lessonPlan)
    {
        $subjects = AcademicSubject::query()->forSchool(auth()->user()->school_id)->orderBy('name')->get();
        $syllabi = AcademicSyllabus::query()->forSchool(auth()->user()->school_id)->orderBy('title')->get();
        
        return view('admin.academic.lesson-plans.edit', compact('lessonPlan', 'subjects', 'syllabi'));
    }

    public function update(Request $request, AcademicLessonPlan $lessonPlan)
    {
        $validated = $request->validate([
            'subject_id' => ['required', 'exists:academic_subjects,id'],
            'syllabus_id' => ['nullable', 'exists:academic_syllabi,id'],
            'title' => ['required', 'string', 'max:255'],
            'lesson_number' => ['nullable', 'integer', 'min:1', 'max:999'],
            'unit_number' => ['nullable', 'integer', 'min:1', 'max:999'],
            'learning_objectives' => ['nullable', 'array'],
            'learning_objectives.*' => ['string', 'max:500'],
            'prerequisites' => ['nullable', 'array'],
            'prerequisites.*' => ['string', 'max:500'],
            'materials_needed' => ['nullable', 'array'],
            'materials_needed.*' => ['string', 'max:500'],
            'lesson_duration' => ['nullable', 'integer', 'min:15', 'max:480'],
            'teaching_methods' => ['nullable', 'array'],
            'teaching_methods.*' => ['string', 'max:500'],
            'activities' => ['nullable', 'array'],
            'activities.*' => ['string', 'max:500'],
            'assessment_methods' => ['nullable', 'array'],
            'assessment_methods.*' => ['string', 'max:500'],
            'homework' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'planned_date' => ['nullable', 'date'],
            'actual_date' => ['nullable', 'date', 'after_or_equal:planned_date'],
            'difficulty_level' => ['required', 'integer', 'in:1,2,3'],
            'estimated_student_count' => ['nullable', 'integer', 'min:1', 'max:1000'],
            'room_requirements' => ['nullable', 'string'],
            'technology_needed' => ['nullable', 'string'],
            'special_considerations' => ['nullable', 'string'],
            'status' => ['nullable', 'boolean'],
            'completion_status' => ['required', 'in:planned,in_progress,completed,postponed,cancelled'],
        ]);

        $validated['status'] = (bool) ($validated['status'] ?? true);
        
        // Clean up empty arrays
        $validated = $this->cleanEmptyArrays($validated);
        
        $lessonPlan->update($validated);
        
        return redirect()->route('admin.academic.lesson-plans.index')
            ->with('success', 'Lesson plan updated successfully');
    }

    public function destroy(AcademicLessonPlan $lessonPlan)
    {
        $lessonPlan->delete();
        return redirect()->route('admin.academic.lesson-plans.index')
            ->with('success', 'Lesson plan deleted successfully');
    }

    public function export(Request $request): StreamedResponse
    {
        $fileName = 'lesson_plans_' . now()->format('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $adminSchoolId = auth()->user()->school_id ?? null;
        $query = AcademicLessonPlan::query()
            ->forSchool($adminSchoolId)
            ->with(['subject', 'syllabus'])
            ->orderBy('subject_id')
            ->orderBy('lesson_number');

        $callback = function () use ($query) {
            $handle = fopen('php://output', 'w');
            
            // CSV headers
            $headers = [
                'subject_code', 'syllabus_title', 'title', 'lesson_number', 'unit_number',
                'learning_objectives', 'prerequisites', 'materials_needed', 'lesson_duration',
                'teaching_methods', 'activities', 'assessment_methods', 'homework', 'notes',
                'planned_date', 'actual_date', 'completion_status', 'difficulty_level',
                'estimated_student_count', 'room_requirements', 'technology_needed',
                'special_considerations', 'status'
            ];
            fputcsv($handle, $headers);

            $query->chunk(200, function ($rows) use ($handle) {
                foreach ($rows as $row) {
                    fputcsv($handle, [
                        optional($row->subject)->code ?? '',
                        optional($row->syllabus)->title ?? '',
                        $row->title,
                        $row->lesson_number ?? '',
                        $row->unit_number ?? '',
                        is_array($row->learning_objectives) ? implode('; ', $row->learning_objectives) : '',
                        is_array($row->prerequisites) ? implode('; ', $row->prerequisites) : '',
                        is_array($row->materials_needed) ? implode('; ', $row->materials_needed) : '',
                        $row->lesson_duration ?? '',
                        is_array($row->teaching_methods) ? implode('; ', $row->teaching_methods) : '',
                        is_array($row->activities) ? implode('; ', $row->activities) : '',
                        is_array($row->assessment_methods) ? implode('; ', $row->assessment_methods) : '',
                        $row->homework ?? '',
                        $row->notes ?? '',
                        optional($row->planned_date)->format('Y-m-d') ?? '',
                        optional($row->actual_date)->format('Y-m-d') ?? '',
                        $row->completion_status,
                        $row->difficulty_level,
                        $row->estimated_student_count ?? '',
                        $row->room_requirements ?? '',
                        $row->technology_needed ?? '',
                        $row->special_considerations ?? '',
                        $row->status ? 1 : 0,
                    ]);
                }
            });
            
            fclose($handle);
        };

        return response()->streamDownload($callback, $fileName, $headers);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt'],
        ]);

        $path = $request->file('file')->getRealPath();
        $handle = fopen($path, 'r');
        
        if ($handle === false) {
            return back()->withErrors(['file' => 'Unable to read the file.']);
        }

        $header = fgetcsv($handle);
        $expected = [
            'subject_code', 'syllabus_title', 'title', 'lesson_number', 'unit_number',
            'learning_objectives', 'prerequisites', 'materials_needed', 'lesson_duration',
            'teaching_methods', 'activities', 'assessment_methods', 'homework', 'notes',
            'planned_date', 'actual_date', 'completion_status', 'difficulty_level',
            'estimated_student_count', 'room_requirements', 'technology_needed',
            'special_considerations', 'status'
        ];

        if (!$header || array_map('strtolower', $header) !== $expected) {
            fclose($handle);
            return back()->withErrors(['file' => 'Invalid CSV header. Expected: ' . implode(',', $expected)]);
        }

        DB::beginTransaction();
        try {
            $schoolId = auth()->user()->school_id ?? null;
            $subjectsByCode = AcademicSubject::forSchool($schoolId)->pluck('id', 'code');
            $syllabiByTitle = AcademicSyllabus::forSchool($schoolId)->pluck('id', 'title');

            while (($row = fgetcsv($handle)) !== false) {
                if (count($row) < count($expected)) continue;
                
                [$subjectCode, $syllabusTitle, $title, $lessonNumber, $unitNumber, 
                 $learningObjectives, $prerequisites, $materialsNeeded, $lessonDuration,
                 $teachingMethods, $activities, $assessmentMethods, $homework, $notes,
                 $plannedDate, $actualDate, $completionStatus, $difficultyLevel,
                 $estimatedStudentCount, $roomRequirements, $technologyNeeded,
                 $specialConsiderations, $status] = $row;

                $subjectId = $subjectsByCode[trim($subjectCode)] ?? null;
                if (!$subjectId) continue;

                $syllabusId = $syllabiByTitle[trim($syllabusTitle)] ?? null;

                AcademicLessonPlan::updateOrCreate(
                    [
                        'school_id' => $schoolId,
                        'subject_id' => $subjectId,
                        'lesson_number' => trim($lessonNumber) ? (int) $lessonNumber : null
                    ],
                    [
                        'syllabus_id' => $syllabusId,
                        'title' => trim($title),
                        'unit_number' => trim($unitNumber) ? (int) $unitNumber : null,
                        'learning_objectives' => $this->parseArrayField($learningObjectives),
                        'prerequisites' => $this->parseArrayField($prerequisites),
                        'materials_needed' => $this->parseArrayField($materialsNeeded),
                        'lesson_duration' => is_numeric($lessonDuration) ? (int) $lessonDuration : null,
                        'teaching_methods' => $this->parseArrayField($teachingMethods),
                        'activities' => $this->parseArrayField($activities),
                        'assessment_methods' => $this->parseArrayField($assessmentMethods),
                        'homework' => trim($homework) ?: null,
                        'notes' => trim($notes) ?: null,
                        'planned_date' => $plannedDate ? date('Y-m-d', strtotime($plannedDate)) : null,
                        'actual_date' => $actualDate ? date('Y-m-d', strtotime($actualDate)) : null,
                        'completion_status' => trim($completionStatus) ?: 'planned',
                        'difficulty_level' => is_numeric($difficultyLevel) ? (int) $difficultyLevel : 1,
                        'estimated_student_count' => is_numeric($estimatedStudentCount) ? (int) $estimatedStudentCount : null,
                        'room_requirements' => trim($roomRequirements) ?: null,
                        'technology_needed' => trim($technologyNeeded) ?: null,
                        'special_considerations' => trim($specialConsiderations) ?: null,
                        'status' => (int) $status === 1,
                    ]
                );
            }
            
            fclose($handle);
            DB::commit();
        } catch (\Throwable $e) {
            if (is_resource($handle)) fclose($handle);
            DB::rollBack();
            return back()->withErrors(['file' => 'Import failed: ' . $e->getMessage()]);
        }

        return back()->with('success', 'Lesson plans imported successfully.');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        $schoolId = auth()->user()->school_id ?? null;
        
        AcademicLessonPlan::whereIn('id', $ids)->forSchool($schoolId)->delete();
        
        return back()->with('success', 'Selected lesson plans deleted successfully.');
    }

    public function bulkStatus(Request $request)
    {
        $ids = $request->input('ids', []);
        $status = $request->input('status', 'planned');
        $schoolId = auth()->user()->school_id ?? null;
        
        AcademicLessonPlan::whereIn('id', $ids)
            ->forSchool($schoolId)
            ->update(['completion_status' => $status]);
        
        return back()->with('success', 'Status updated for selected lesson plans.');
    }

    public function toggleStatus(AcademicLessonPlan $lessonPlan)
    {
        $lessonPlan->update(['status' => !$lessonPlan->status]);
        return back()->with('success', 'Lesson plan status updated successfully.');
    }

    public function duplicate(AcademicLessonPlan $lessonPlan)
    {
        $newPlan = $lessonPlan->replicate();
        $newPlan->title = $lessonPlan->title . ' (Copy)';
        $newPlan->lesson_number = null;
        $newPlan->planned_date = null;
        $newPlan->actual_date = null;
        $newPlan->completion_status = 'planned';
        $newPlan->save();

        return redirect()->route('admin.academic.lesson-plans.edit', $newPlan)
            ->with('success', 'Lesson plan duplicated successfully. Please review and update the details.');
    }

    private function getLessonPlanStats(?int $schoolId): array
    {
        $query = AcademicLessonPlan::query()->forSchool($schoolId);
        
        return [
            'total' => $query->count(),
            'planned' => $query->byStatus('planned')->count(),
            'in_progress' => $query->byStatus('in_progress')->count(),
            'completed' => $query->byStatus('completed')->count(),
            'overdue' => $query->overdue()->count(),
            'upcoming' => $query->upcoming()->count(),
        ];
    }

    private function cleanEmptyArrays(array $data): array
    {
        $arrayFields = ['learning_objectives', 'prerequisites', 'materials_needed', 
                       'teaching_methods', 'activities', 'assessment_methods'];
        
        foreach ($arrayFields as $field) {
            if (isset($data[$field]) && (empty($data[$field]) || $data[$field] === [''])) {
                $data[$field] = null;
            }
        }
        
        return $data;
    }

    private function parseArrayField(?string $value): ?array
    {
        if (!$value || trim($value) === '') return null;
        
        $items = array_map('trim', explode(';', $value));
        $items = array_filter($items);
        
        return empty($items) ? null : $items;
    }
}
