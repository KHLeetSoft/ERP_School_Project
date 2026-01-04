<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

use OpenAI\Laravel\Facades\OpenAI;
use App\Http\Controllers\Teacher\AuthController;

/*
|--------------------------------------------------------------------------
| Teacher Routes
|--------------------------------------------------------------------------
|
| Here are all the routes for the Teacher module. These routes are
| protected by authentication and role-based access control.
|
*/

// Teacher Authentication Routes (outside middleware)
Route::prefix('teacher')->name('teacher.')->group(function () {
    // Authentication Routes
    Route::get('/login', [App\Http\Controllers\Teacher\AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [App\Http\Controllers\Teacher\AuthController::class, 'login']);
    Route::post('/logout', [App\Http\Controllers\Teacher\AuthController::class, 'logout'])->name('logout');
    // Graceful GET logout to avoid method errors when accessed via link/address bar
    Route::get('/logout', function () {
        if (auth()->check()) {
            auth()->logout();
        }
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('teacher.login');
    })->name('logout.get');
    Route::get('/register', [App\Http\Controllers\Teacher\AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [App\Http\Controllers\Teacher\AuthController::class, 'register']);
});

// Protected Teacher Routes (require teacher authentication)
Route::middleware(['auth', 'teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Teacher\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [App\Http\Controllers\Teacher\DashboardController::class, 'profile'])->name('profile');
    Route::put('/profile', [App\Http\Controllers\Teacher\DashboardController::class, 'updateProfile'])->name('profile.update');
    Route::put('/password', [App\Http\Controllers\Teacher\DashboardController::class, 'updatePassword'])->name('password.update');
    
    // Test route
    Route::get('/test', function() {
        return view('teacher.test');
    })->name('test');
    
    // Teacher Modules
    Route::resource('classes', App\Http\Controllers\Teacher\ClassController::class);
    Route::get('/classes-by-day', [App\Http\Controllers\Teacher\ClassController::class, 'getClassesByDay'])->name('classes.by-day');
    
    Route::resource('students', App\Http\Controllers\Teacher\StudentController::class)->only(['index', 'show', 'edit', 'update']);
    Route::get('/students-by-class', [App\Http\Controllers\Teacher\StudentController::class, 'getStudentsByClass'])->name('students.by-class');
    Route::get('/students-search', [App\Http\Controllers\Teacher\StudentController::class, 'search'])->name('students.search');
    
    Route::resource('attendance', App\Http\Controllers\Teacher\AttendanceController::class);
    Route::get('/attendance-mark-today', [App\Http\Controllers\Teacher\AttendanceController::class, 'markToday'])->name('attendance.mark-today');
    Route::get('/attendance-students-by-class', [App\Http\Controllers\Teacher\AttendanceController::class, 'getStudentsByClass'])->name('attendance.students-by-class');
    Route::get('/attendance-by-date', [App\Http\Controllers\Teacher\AttendanceController::class, 'getAttendanceByDate'])->name('attendance.by-date');
    
    // Teacher Grades Routes
    Route::resource('grades', App\Http\Controllers\Teacher\GradeController::class);
    Route::get('/grades/students-by-class', [App\Http\Controllers\Teacher\GradeController::class, 'getStudentsByClass'])->name('grades.students-by-class');
    Route::get('/grades/bulk-create', [App\Http\Controllers\Teacher\GradeController::class, 'bulkCreate'])->name('grades.bulk-create');
    Route::post('/grades/bulk-store', [App\Http\Controllers\Teacher\GradeController::class, 'storeBulk'])->name('grades.bulk-store');
    
    // Teacher Schedule Routes
    Route::resource('schedule', App\Http\Controllers\Teacher\ScheduleController::class);
    Route::get('/schedule/today', [App\Http\Controllers\Teacher\ScheduleController::class, 'today'])->name('schedule.today');
    Route::get('/schedule/weekly', [App\Http\Controllers\Teacher\ScheduleController::class, 'weekly'])->name('schedule.weekly');
    
    // Teacher Reports Routes
    Route::get('/reports', [App\Http\Controllers\Teacher\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/grades', [App\Http\Controllers\Teacher\ReportController::class, 'grades'])->name('reports.grades');
    Route::get('/reports/attendance', [App\Http\Controllers\Teacher\ReportController::class, 'attendance'])->name('reports.attendance');
    Route::get('/reports/schedule', [App\Http\Controllers\Teacher\ReportController::class, 'schedule'])->name('reports.schedule');
    Route::get('/reports/student-performance', [App\Http\Controllers\Teacher\ReportController::class, 'studentPerformance'])->name('reports.student-performance');
    Route::post('/reports/export', [App\Http\Controllers\Teacher\ReportController::class, 'export'])->name('reports.export');
    
    // Teacher Assignments Routes
    Route::resource('assignments', App\Http\Controllers\Teacher\AssignmentController::class);
    Route::post('/assignments/{assignment}/publish', [App\Http\Controllers\Teacher\AssignmentController::class, 'publish'])->name('assignments.publish');
    Route::post('/assignments/{assignment}/close', [App\Http\Controllers\Teacher\AssignmentController::class, 'close'])->name('assignments.close');
    Route::post('/assignments/{assignment}/archive', [App\Http\Controllers\Teacher\AssignmentController::class, 'archive'])->name('assignments.archive');
    Route::get('/assignments/upcoming', [App\Http\Controllers\Teacher\AssignmentController::class, 'upcoming'])->name('assignments.upcoming');
    Route::get('/assignments/overdue', [App\Http\Controllers\Teacher\AssignmentController::class, 'overdue'])->name('assignments.overdue');
    Route::get('/assignments/by-class', [App\Http\Controllers\Teacher\AssignmentController::class, 'getByClass'])->name('assignments.by-class');

    // Teacher Exams Routes
    Route::resource('exams', App\Http\Controllers\Teacher\ExamController::class);
    Route::get('/exams/{exam}/schedules', [App\Http\Controllers\Teacher\ExamController::class, 'schedules'])->name('exams.schedules');
    Route::get('/exams/{exam}/marks', [App\Http\Controllers\Teacher\ExamController::class, 'marks'])->name('exams.marks');
    Route::get('/exams/{exam}/results', [App\Http\Controllers\Teacher\ExamController::class, 'results'])->name('exams.results');
    Route::get('/exams/{exam}/statistics', [App\Http\Controllers\Teacher\ExamController::class, 'statistics'])->name('exams.statistics');
    Route::get('/exams/{exam}/report', [App\Http\Controllers\Teacher\ExamController::class, 'report'])->name('exams.report');
    Route::get('/exams/{exam}/export', [App\Http\Controllers\Teacher\ExamController::class, 'export'])->name('exams.export');
    Route::post('/exams/{exam}/publish-results', [App\Http\Controllers\Teacher\ExamController::class, 'publishResults'])->name('exams.publish-results');
    Route::post('/exams/{exam}/unpublish-results', [App\Http\Controllers\Teacher\ExamController::class, 'unpublishResults'])->name('exams.unpublish-results');
    Route::get('/exams/upcoming', [App\Http\Controllers\Teacher\ExamController::class, 'upcoming'])->name('exams.upcoming');
    Route::get('/exams/active', [App\Http\Controllers\Teacher\ExamController::class, 'active'])->name('exams.active');
    Route::get('/exams/completed', [App\Http\Controllers\Teacher\ExamController::class, 'completed'])->name('exams.completed');

    // Teacher Exam Schedules Routes
    Route::resource('exam-schedules', App\Http\Controllers\Teacher\ExamScheduleController::class);
    Route::get('/exam-schedules/today', [App\Http\Controllers\Teacher\ExamScheduleController::class, 'today'])->name('exam-schedules.today');
    Route::get('/exam-schedules/upcoming', [App\Http\Controllers\Teacher\ExamScheduleController::class, 'upcoming'])->name('exam-schedules.upcoming');
    Route::get('/exam-schedules/by-class', [App\Http\Controllers\Teacher\ExamScheduleController::class, 'byClass'])->name('exam-schedules.by-class');
    Route::get('/exam-schedules/by-subject', [App\Http\Controllers\Teacher\ExamScheduleController::class, 'bySubject'])->name('exam-schedules.by-subject');
    Route::get('/exam-schedules/by-date', [App\Http\Controllers\Teacher\ExamScheduleController::class, 'byDate'])->name('exam-schedules.by-date');
    Route::post('/exam-schedules/{examSchedule}/complete', [App\Http\Controllers\Teacher\ExamScheduleController::class, 'complete'])->name('exam-schedules.complete');
    Route::post('/exam-schedules/{examSchedule}/cancel', [App\Http\Controllers\Teacher\ExamScheduleController::class, 'cancel'])->name('exam-schedules.cancel');
    Route::post('/exam-schedules/{examSchedule}/reschedule', [App\Http\Controllers\Teacher\ExamScheduleController::class, 'reschedule'])->name('exam-schedules.reschedule');
    Route::get('/exam-schedules/{examSchedule}/statistics', [App\Http\Controllers\Teacher\ExamScheduleController::class, 'statistics'])->name('exam-schedules.statistics');
    Route::get('/exam-schedules/{examSchedule}/export', [App\Http\Controllers\Teacher\ExamScheduleController::class, 'export'])->name('exam-schedules.export');

    // Teacher Resources Routes
    Route::resource('resources', App\Http\Controllers\Teacher\ResourceController::class);
    Route::get('/resources/{resource}/download', [App\Http\Controllers\Teacher\ResourceController::class, 'download'])->name('resources.download');
    Route::post('/resources/{resource}/toggle-featured', [App\Http\Controllers\Teacher\ResourceController::class, 'toggleFeatured'])->name('resources.toggle-featured');
    Route::post('/resources/{resource}/toggle-pinned', [App\Http\Controllers\Teacher\ResourceController::class, 'togglePinned'])->name('resources.toggle-pinned');
    Route::post('/resources/{resource}/publish', [App\Http\Controllers\Teacher\ResourceController::class, 'publish'])->name('resources.publish');
    Route::post('/resources/{resource}/unpublish', [App\Http\Controllers\Teacher\ResourceController::class, 'unpublish'])->name('resources.unpublish');
    Route::post('/resources/{resource}/archive', [App\Http\Controllers\Teacher\ResourceController::class, 'archive'])->name('resources.archive');
    Route::get('/resources/public', [App\Http\Controllers\Teacher\ResourceController::class, 'public'])->name('resources.public');
    Route::get('/resources/featured', [App\Http\Controllers\Teacher\ResourceController::class, 'featured'])->name('resources.featured');
    Route::get('/resources/category/{category}', [App\Http\Controllers\Teacher\ResourceController::class, 'byCategory'])->name('resources.by-category');
    Route::get('/resources/search', [App\Http\Controllers\Teacher\ResourceController::class, 'search'])->name('resources.search');
    Route::get('/resources/statistics', [App\Http\Controllers\Teacher\ResourceController::class, 'statistics'])->name('resources.statistics');
    
    // Lesson Plans Routes
    Route::resource('lesson-plans', App\Http\Controllers\Teacher\LessonPlanController::class)->names('lessonplans');
    Route::get('/lesson-plans', [App\Http\Controllers\Teacher\LessonPlanController::class, 'index'])->name('lessonplans.index');
    
    // Announcements Routes
    Route::resource('announcements', App\Http\Controllers\Teacher\AnnouncementController::class)->names('announcements');
    Route::get('/announcements', [App\Http\Controllers\Teacher\AnnouncementController::class, 'index'])->name('announcements.index');
    
    // Feedback Routes
    Route::resource('feedback', App\Http\Controllers\Teacher\FeedbackController::class)->names('feedback');
    Route::get('/feedback', [App\Http\Controllers\Teacher\FeedbackController::class, 'index'])->name('feedback.index');
    
    // Whiteboard Routes
    Route::resource('whiteboard', App\Http\Controllers\Teacher\WhiteboardController::class)->names('whiteboard');
    Route::get('/whiteboard', [App\Http\Controllers\Teacher\WhiteboardController::class, 'index'])->name('whiteboard.index');
    
    // Diary
    Route::resource('diary', App\Http\Controllers\Teacher\DiaryController::class)->parameters([
        'diary' => 'diary'
    ])->names('diary');
    Route::post('/diary/{diary}/toggle-pin', [App\Http\Controllers\Teacher\DiaryController::class, 'togglePin'])->name('diary.toggle-pin');

    // AI Assistant Routes
    Route::prefix('ai')->name('ai.')->group(function () {
        Route::get('/chat', [App\Http\Controllers\Teacher\AI\AIChatController::class, 'index'])->name('chat');
        Route::post('/chat/send', [App\Http\Controllers\Teacher\AI\AIChatController::class, 'sendMessage'])->name('chat.send');
        Route::get('/chat/suggestions', [App\Http\Controllers\Teacher\AI\AIChatController::class, 'getSuggestions'])->name('chat.suggestions');
        
        Route::get('/lesson-planner', [App\Http\Controllers\Teacher\AI\LessonPlannerController::class, 'index'])->name('lesson-planner');
        Route::post('/lesson-planner/generate', [App\Http\Controllers\Teacher\AI\LessonPlannerController::class, 'generateLessonPlan'])->name('lesson-planner.generate');
        Route::get('/lesson-planner/subjects', [App\Http\Controllers\Teacher\AI\LessonPlannerController::class, 'getSubjectSuggestions'])->name('lesson-planner.subjects');
        Route::get('/lesson-planner/grade-levels', [App\Http\Controllers\Teacher\AI\LessonPlannerController::class, 'getGradeLevels'])->name('lesson-planner.grade-levels');
        
        Route::get('/assessment-generator', [App\Http\Controllers\Teacher\AI\AssessmentGeneratorController::class, 'index'])->name('assessment-generator');
        Route::post('/assessment-generator/generate', [App\Http\Controllers\Teacher\AI\AssessmentGeneratorController::class, 'generateAssessment'])->name('assessment-generator.generate');
        Route::get('/assessment-generator/question-types', [App\Http\Controllers\Teacher\AI\AssessmentGeneratorController::class, 'getQuestionTypes'])->name('assessment-generator.question-types');
        Route::get('/assessment-generator/difficulty-levels', [App\Http\Controllers\Teacher\AI\AssessmentGeneratorController::class, 'getDifficultyLevels'])->name('assessment-generator.difficulty-levels');
        
        Route::get('/grade-analyzer', [App\Http\Controllers\Teacher\AI\GradeAnalyzerController::class, 'index'])->name('grade-analyzer');
        Route::post('/grade-analyzer/analyze', [App\Http\Controllers\Teacher\AI\GradeAnalyzerController::class, 'analyzeGrades'])->name('grade-analyzer.analyze');
    });
});
