<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OnlineExam;
use App\Models\OnlineExamAttempt;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Question;
use App\Models\QuestionCategory;
use App\Models\Question as QuestionModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class OnlineExamController extends Controller
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
    /**
     * Display a listing of online exams.
     */
    public function index()
    {
        $onlineExams = OnlineExam::with(['schoolClass', 'section', 'subject'])
                                ->orderBy('created_at', 'desc')
                                ->paginate(15);

        return view('admin.exams.online-exam.index', compact('onlineExams'));
    }

    /**
     * Manage listing with filters and bulk actions.
     */
    public function manage(Request $request)
    {
        $query = OnlineExam::with(['schoolClass', 'section', 'subject'])->orderByDesc('created_at');

        // Filters
        if ($request->filled('class_id')) {
            $query->where('class_id', $request->input('class_id'));
        }
        if ($request->filled('section_id')) {
            $query->where('section_id', $request->input('section_id'));
        }
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->input('subject_id'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('start_date')) {
            $query->whereDate('start_datetime', '>=', $request->input('start_date'));
        }
        if ($request->filled('end_date')) {
            $query->whereDate('end_datetime', '<=', $request->input('end_date'));
        }

        $onlineExams = $query->paginate(20)->withQueryString();
        $classes = SchoolClass::select('id', 'name')->get();
        $sections = Section::select('id', 'name')->get();
        // subjects table uses `subject_name` column
        $subjects = Subject::select('id', 'subject_name as name')->get();

        return view('admin.exams.online-exam.manage', compact('onlineExams', 'classes', 'sections', 'subjects'));
    }

    /**
     * Perform bulk actions on selected exams.
     */
    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|in:publish,cancel,delete',
            'selected_ids' => 'required|array|min:1',
            'selected_ids.*' => 'integer|exists:online_exams,id',
        ]);

        $action = $validated['action'];
        $ids = $validated['selected_ids'];

        $affected = 0; $skipped = 0;

        DB::beginTransaction();
        try {
            $exams = OnlineExam::withCount('attempts')->whereIn('id', $ids)->get();
            foreach ($exams as $exam) {
                if ($action === 'publish') {
                    if ($exam->status === 'draft') {
                        $exam->update(['status' => 'published']);
                        $affected++;
                    } else {
                        $skipped++;
                    }
                } elseif ($action === 'cancel') {
                    if (!$exam->isCompleted()) {
                        $exam->update(['status' => 'cancelled']);
                        $affected++;
                    } else {
                        $skipped++;
                    }
                } elseif ($action === 'delete') {
                    if ($exam->attempts_count === 0) {
                        $exam->delete();
                        $affected++;
                    } else {
                        $skipped++;
                    }
                }
            }
            DB::commit();
            return back()->with('success', "Bulk action '{$action}' completed. Affected: {$affected}, Skipped: {$skipped}.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Bulk action failed. Please try again.']);
        }
    }

    /**
     * Duplicate an online exam (as draft) including questions and marks.
     */
    public function duplicate(OnlineExam $onlineExam)
    {
        DB::beginTransaction();
        try {
            $newExam = $onlineExam->replicate([
                'created_at', 'updated_at'
            ]);
            $newExam->title = $onlineExam->title . ' (Copy)';
            $newExam->status = 'draft';
            $newExam->save();

            // Copy questions pivot
            $syncData = [];
            foreach ($onlineExam->questions as $q) {
                $syncData[$q->id] = [
                    'marks' => $q->pivot->marks,
                    'order_number' => $q->pivot->order_number,
                ];
            }
            if (!empty($syncData)) {
                $newExam->questions()->sync($syncData);
            }

            DB::commit();
            return redirect()->route('admin.online-exam.edit', $newExam)->with('success', 'Exam duplicated as draft.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to duplicate exam.']);
        }
    }

    /**
     * Show the form for creating a new online exam.
     */
    public function create()
    {
        $classes = SchoolClass::all();
        $sections = Section::all();
        $subjects = Subject::all();
        $questionCategories = QuestionCategory::all();

        return view('admin.exams.online-exam.create', compact('classes', 'sections', 'subjects', 'questionCategories'));
    }

    /**
     * Store a newly created online exam.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'class_id' => 'required|exists:school_classes,id',
            'section_id' => 'required|exists:sections,id',
            'subject_id' => 'required|exists:subjects,id',
            'duration_minutes' => 'required|integer|min:1',
            'total_marks' => 'required|integer|min:1',
            'passing_marks' => 'required|integer|min:1',
            'start_datetime' => 'required|date|after:now',
            'end_datetime' => 'required|date|after:start_datetime',
            'negative_marking' => 'boolean',
            'negative_marks' => 'nullable|numeric|min:0',
            'randomize_questions' => 'boolean',
            'show_result_immediately' => 'boolean',
            'instructions' => 'nullable|string',
            'allow_calculator' => 'boolean',
            'allow_notes' => 'boolean',
            'max_attempts' => 'required|integer|min:1|max:10',
            'enable_proctoring' => 'boolean',
            'questions' => 'required|array|min:1',
            'questions.*.question_id' => 'required|exists:questions,id',
            'questions.*.marks' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        // Validate that passing marks doesn't exceed total marks
        if ($request->passing_marks > $request->total_marks) {
            return redirect()->back()
                           ->withErrors(['passing_marks' => 'Passing marks cannot exceed total marks.'])
                           ->withInput();
        }

        // Validate that question marks sum matches total marks
        $questionMarksSum = collect($request->questions)->sum('marks');
        if ($questionMarksSum != $request->total_marks) {
            return redirect()->back()
                           ->withErrors(['total_marks' => 'Total marks must equal the sum of all question marks.'])
                           ->withInput();
        }

        try {
            DB::beginTransaction();

            $onlineExam = OnlineExam::create($request->except(['questions']));

            // Attach questions with marks and order
            foreach ($request->questions as $index => $questionData) {
                $onlineExam->questions()->attach($questionData['question_id'], [
                    'marks' => $questionData['marks'],
                    'order_number' => $index + 1,
                ]);
            }

            DB::commit();

            return redirect()->route('admin.online-exam.index')
                           ->with('success', 'Online exam created successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                           ->withErrors(['error' => 'Failed to create online exam. Please try again.'])
                           ->withInput();
        }
    }

    /**
     * Display the specified online exam.
     */
    public function show(OnlineExam $onlineExam)
    {
        $onlineExam->load(['schoolClass', 'section', 'subject', 'questions', 'attempts.student']);
        $statistics = $onlineExam->getStatistics();

        return view('admin.exams.online-exam.show', compact('onlineExam', 'statistics'));
    }

    /**
     * Show the form for editing the specified online exam.
     */
    public function edit(OnlineExam $onlineExam)
    {
        $onlineExam->load(['questions']);
        $classes = SchoolClass::all();
        $sections = Section::all();
        $subjects = Subject::all();
        $questionCategories = QuestionCategory::all();

        return view('admin.exams.online-exam.edit', compact('onlineExam', 'classes', 'sections', 'subjects', 'questionCategories'));
    }

    /**
     * Update the specified online exam.
     */
    public function update(Request $request, OnlineExam $onlineExam)
    {
        // Prevent editing if exam has already started or has attempts
        if ($onlineExam->isActive() || $onlineExam->isCompleted() || $onlineExam->attempts()->exists()) {
            return redirect()->back()
                           ->withErrors(['error' => 'Cannot edit exam that has started or has student attempts.']);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'class_id' => 'required|exists:school_classes,id',
            'section_id' => 'required|exists:sections,id',
            'subject_id' => 'required|exists:subjects,id',
            'duration_minutes' => 'required|integer|min:1',
            'total_marks' => 'required|integer|min:1',
            'passing_marks' => 'required|integer|min:1',
            'start_datetime' => 'required|date|after:now',
            'end_datetime' => 'required|date|after:start_datetime',
            'negative_marking' => 'boolean',
            'negative_marks' => 'nullable|numeric|min:0',
            'randomize_questions' => 'boolean',
            'show_result_immediately' => 'boolean',
            'instructions' => 'nullable|string',
            'allow_calculator' => 'boolean',
            'allow_notes' => 'boolean',
            'max_attempts' => 'required|integer|min:1|max:10',
            'enable_proctoring' => 'boolean',
            'questions' => 'required|array|min:1',
            'questions.*.question_id' => 'required|exists:questions,id',
            'questions.*.marks' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        try {
            DB::beginTransaction();

            $onlineExam->update($request->except(['questions']));

            // Sync questions
            $syncData = [];
            foreach ($request->questions as $index => $questionData) {
                $syncData[$questionData['question_id']] = [
                    'marks' => $questionData['marks'],
                    'order_number' => $index + 1,
                ];
            }
            $onlineExam->questions()->sync($syncData);

            DB::commit();

            return redirect()->route('admin.online-exam.show', $onlineExam)
                           ->with('success', 'Online exam updated successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                           ->withErrors(['error' => 'Failed to update online exam. Please try again.'])
                           ->withInput();
        }
    }

    /**
     * Remove the specified online exam.
     */
    public function destroy(OnlineExam $onlineExam)
    {
        // Prevent deletion if exam has attempts
        if ($onlineExam->attempts()->exists()) {
            return redirect()->back()
                           ->withErrors(['error' => 'Cannot delete exam that has student attempts.']);
        }

        try {
            $onlineExam->delete();
            return redirect()->route('admin.online-exam.index')
                           ->with('success', 'Online exam deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->withErrors(['error' => 'Failed to delete online exam.']);
        }
    }

    /**
     * Publish the exam.
     */
    public function publish(OnlineExam $onlineExam)
    {
        if ($onlineExam->status !== 'draft') {
            return redirect()->back()
                           ->withErrors(['error' => 'Only draft exams can be published.']);
        }

        $onlineExam->update(['status' => 'published']);

        return redirect()->back()
                       ->with('success', 'Online exam published successfully.');
    }

    /**
     * Cancel the exam.
     */
    public function cancel(OnlineExam $onlineExam)
    {
        if ($onlineExam->isCompleted()) {
            return redirect()->back()
                           ->withErrors(['error' => 'Cannot cancel completed exam.']);
        }

        $onlineExam->update(['status' => 'cancelled']);

        return redirect()->back()
                       ->with('success', 'Online exam cancelled successfully.');
    }

    /**
     * View exam results.
     */
    public function results(OnlineExam $onlineExam)
    {
        $attempts = $onlineExam->completedAttempts()
                              ->with('student')
                              ->orderBy('percentage', 'desc')
                              ->get();

        $statistics = $onlineExam->getStatistics();

        return view('admin.exams.online-exam.results', compact('onlineExam', 'attempts', 'statistics'));
    }

    /**
     * Get questions by category (AJAX).
     */
    public function getQuestionsByCategory(Request $request)
    {
        $categoryId = $request->category_id;
        $questions = Question::where('category_id', $categoryId)
                           ->select('id', 'question_text', 'type', 'difficulty_level')
                           ->get();

        return response()->json($questions);
    }

    /**
     * Get sections by class (AJAX).
     */
    public function getSectionsByClass(Request $request)
    {
        $classId = $request->class_id;
        $sections = Section::where('class_id', $classId)
                          ->select('id', 'name')
                          ->get();

        return response()->json($sections);
    }

    /**
     * Questions index
     */
    public function questionsIndex(Request $request)
    {
        $query = QuestionModel::with('category')->orderByDesc('id');

        if ($request->filled('category_id')) {
            $query->where('question_category_id', $request->input('category_id'));
        }
        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }
        if ($request->filled('difficulty')) {
            $query->where('difficulty', $request->input('difficulty'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $questions = $query->paginate(20)->withQueryString();
        $categories = QuestionCategory::select('id', 'name')->get();

        return view('admin.exams.online-exam.questions.index', compact('questions', 'categories'));
    }

    public function questionsCreate()
    {
        $categories = QuestionCategory::select('id', 'name')->get();
        return view('admin.exams.online-exam.questions.create', compact('categories'));
    }

    public function questionsStore(Request $request)
    {
        $data = $request->validate([
            'question_category_id' => 'required|exists:question_categories,id',
            'type' => 'required|in:mcq,true_false,short_answer,essay',
            'difficulty' => 'nullable|in:easy,medium,hard',
            'question_text' => 'required|string',
            'options' => 'nullable|array',
            'correct_answer' => 'required|string',
            'explanation' => 'nullable|string',
            'marks' => 'required|numeric|min:1',
            'status' => 'nullable|in:active,inactive',
        ]);

        QuestionModel::create($data);
        return redirect()->route('admin.online-exam.questions.index')->with('success', 'Question created successfully.');
    }

    public function questionsEdit(QuestionModel $question)
    {
        $categories = QuestionCategory::select('id', 'name')->get();
        return view('admin.exams.online-exam.questions.edit', compact('question', 'categories'));
    }

    public function questionsUpdate(Request $request, QuestionModel $question)
    {
        $data = $request->validate([
            'question_category_id' => 'required|exists:question_categories,id',
            'type' => 'required|in:mcq,true_false,short_answer,essay',
            'difficulty' => 'nullable|in:easy,medium,hard',
            'question_text' => 'required|string',
            'options' => 'nullable|array',
            'correct_answer' => 'required|string',
            'explanation' => 'nullable|string',
            'marks' => 'required|numeric|min:1',
            'status' => 'nullable|in:active,inactive',
        ]);

        $question->update($data);
        return redirect()->route('admin.online-exam.questions.index')->with('success', 'Question updated successfully.');
    }

    public function questionsDestroy(QuestionModel $question)
    {
        $question->delete();
        return redirect()->route('admin.online-exam.questions.index')->with('success', 'Question deleted.');
    }
}
