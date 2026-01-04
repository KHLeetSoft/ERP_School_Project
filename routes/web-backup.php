<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use OpenAI\Laravel\Facades\OpenAI;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Superadmin\SuperAdminController;
use App\Http\Controllers\Superadmin\AdminController;
use App\Http\Controllers\Superadmin\PurchaseController;
use App\Http\Controllers\Superadmin\ProductPlanController;
use App\Http\Controllers\Superadmin\SchoolController;
use App\Http\Controllers\Superadmin\ThemeSettingController;
use App\Http\Controllers\Superadmin\SuperadminSettingController;

//List Of Admin Controller----------------

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\SmsController;
use App\Http\Controllers\Admin\EmailTemplateController;
use App\Http\Controllers\Admin\NewsletterController;
use App\Http\Controllers\Admin\TransportRouteController;
use App\Http\Controllers\Admin\TransportVehicleController;
use App\Http\Controllers\Admin\TransportAssignmentController;
use App\Http\Controllers\Admin\TransportDriverController;
use App\Http\Controllers\Admin\TransportTrackingController;
use App\Http\Controllers\Admin\HostelCategoryController;
use App\Http\Controllers\Admin\HostelRoomController;
use App\Http\Controllers\Admin\HostelAllocationController;
use App\Http\Controllers\Admin\HostelFeeController;
use App\Http\Controllers\Admin\HostelAttendanceController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\LibrarianController;
use App\Http\Controllers\Admin\AccountantController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\ParentController;
use App\Http\Controllers\Student\StudentController as StudentAuthController;
use App\Http\Controllers\Admin\AdmissionEnquiryController;
use App\Http\Controllers\Admin\VisitorController;
use App\Http\Controllers\Admin\CallLogController;
use App\Http\Controllers\Admin\PostalDispatchController;
use App\Http\Controllers\Admin\PostalReceiveController;
use App\Http\Controllers\Admin\AdminComplaintBoxController;
use App\Http\Controllers\Admin\VisitorsPurposeController;
use App\Http\Controllers\Admin\StudentDetailsController;
use App\Http\Controllers\Admin\ClassSectionController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\StudentResultsController;
use App\Http\Controllers\Admin\StudentFeeController;
use App\Http\Controllers\Admin\StudentPromotionController;
use App\Http\Controllers\Admin\StudentHealthController;
use App\Http\Controllers\Admin\StudentDocumentController;
use App\Http\Controllers\Admin\StudentTransportController;
use App\Http\Controllers\Admin\StudentCommunicationController;
use App\Http\Controllers\Admin\StudentPortalAccessController;
use App\Http\Controllers\Admin\StudentHostelController;
use App\Http\Controllers\Admin\ParentDetailsController;
use App\Http\Controllers\Admin\ParentCommunicationController;
use App\Http\Controllers\Admin\ParentPortalAccessController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\BookCategoryController;
use App\Http\Controllers\Admin\BookIssueController;
use App\Http\Controllers\Admin\BookReturnController;
use App\Http\Controllers\Admin\LibraryMemberController;
use App\Http\Controllers\Admin\Academic\CoverageController;
use App\Http\Controllers\Admin\Academic\SubjectController as AcademicSubjectController;
use App\Http\Controllers\Admin\Academic\SyllabusController as AcademicSyllabusController;
use App\Http\Controllers\Admin\Academic\LessonPlanController as AcademicLessonPlanController;
use App\Http\Controllers\Admin\Academic\TimetableController;
use App\Http\Controllers\Admin\Academic\SubstitutionController;
use App\Http\Controllers\Admin\Academic\AssignmentController;
use App\Http\Controllers\Admin\ResourceBookingController;
use App\Http\Controllers\Admin\Academic\PtmController;
use App\Http\Controllers\Admin\Academic\AcademicCalendarController;
use App\Http\Controllers\Admin\Academic\AcademicReportController;
use App\Http\Controllers\Admin\Documents\IdCardController;
use App\Http\Controllers\Admin\Documents\TransferCertificateController;
use App\Http\Controllers\Admin\Documents\LeavingCertificateController;
use App\Http\Controllers\Admin\Documents\MarksheetController;
use App\Http\Controllers\Admin\Documents\ExperienceCertificateController;
use App\Http\Controllers\Admin\Documents\BonafideCertificateController;
use App\Http\Controllers\Admin\Documents\StudyCertificateController;
use App\Http\Controllers\Admin\Exams\ExamController;
use App\Http\Controllers\Admin\OnlineExamController;
use App\Http\Controllers\Admin\Exams\ExamScheduleController;
use App\Http\Controllers\Admin\Documents\ConductCertificateController;
use App\Http\Controllers\Admin\Documents\EmployeeConductCertificateController;
use App\Http\Controllers\Admin\Exams\ExamGradeController;
use App\Http\Controllers\Admin\Exams\ExamMarkController;
use App\Http\Controllers\Admin\Exams\ExamSmsController;
use App\Http\Controllers\Admin\Exams\ExamTabulationController;
use App\Http\Controllers\Admin\Exams\ExamAttendanceController;
use App\Http\Controllers\Admin\Exams\ExamProgressCardController;
use App\Http\Controllers\Admin\Exams\QuestionCategoryController;
use App\Http\Controllers\Admin\Exams\QuestionController;
use App\Http\Controllers\Admin\Exams\AiQuestionGeneratorController;
use App\Http\Controllers\Admin\Exams\QuestionPaperController;
use App\Http\Controllers\Admin\AI\PaperGeneratorController;
use App\Http\Controllers\Admin\AI\PerformancePredictionController;
use App\Http\Controllers\Admin\AI\ChatbotController;
use App\Http\Controllers\Admin\AI\AIChatbotController;
use App\Http\Controllers\Admin\AI\PlagiarismCheckerController;
use App\Http\Controllers\Admin\ResultAnnouncement\ResultAnnouncementController;
use App\Http\Controllers\Admin\ResultAnnouncement\ResultPublicationController;
use App\Http\Controllers\Admin\ResultAnnouncement\ResultNotificationController;
use App\Http\Controllers\Admin\ResultAnnouncement\ResultStatisticsController;
use App\Http\Controllers\Admin\OnlineExamResultController;
use App\Http\Controllers\Admin\StaffAttendanceController;
use App\Http\Controllers\Admin\BulkAttendanceController;
use App\Http\Controllers\Admin\Exams\ExamSmsRecipientController;
use App\Http\Controllers\Admin\RfidAttendanceController;
use App\Http\Controllers\Admin\AttendanceReportsController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\StudentPaymentController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\ExpenseCategoryController;
use App\Http\Controllers\Admin\ScholarshipController;
use App\Http\Controllers\Admin\FeeStructureController;
use App\Http\Controllers\Admin\NoticeboardController;
use App\Http\Controllers\Admin\MessagesController;

// Utility Routes
Route::get('/clear-cache', function () {
// Academic Substitution Module
    Artisan::call('optimize:clear');
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('config:cache');
    return 'Cache Cleared';
});

Route::get('/linkstorage', function () {
    Artisan::call('storage:link');
    return 'Storage Linked';
});

// Main welcome route with authentication check
Route::get('/', function () {
    // Check if user is authenticated
    if (auth()->check()) {
        $user = auth()->user();
        $roleName = optional($user->userRole)->name;
        
        if ($roleName === 'Super Admin') {
            return redirect()->route('superadmin.dashboard');
        } elseif ($roleName === 'Admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($roleName === 'Teacher') {
            return redirect()->route('teacher.dashboard');
        } elseif ($roleName === 'Parent') {
            return redirect()->route('parent.dashboard');
        } elseif ($roleName === 'Librarian') {
            return redirect()->route('librarian.dashboard');
        } elseif ($roleName === 'Student') {
            return redirect()->route('student.dashboard');
        } elseif ($roleName === 'Accountant') {
            return redirect()->route('accountant.dashboard');
        }
    }
    
     // If not authenticated, show welcome page
    return view('welcome');
});

// Superadmin Login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Superadmin Panel (Protected)
Route::middleware(['auth', 'checkrole:superadmin'])->prefix('superadmin') ->name('superadmin.')->group(function () {
        Route::get('/dashboard', [SuperAdminController::class, 'index'])->name('dashboard');
    // Admin Management
    Route::get('admins', [AdminController::class, 'index'])->name('admins.index');
    Route::post('admins/datatable', [AdminController::class, 'serverSideDataTable'])->name('admins.datatable');
    Route::get('admins/create', [AdminController::class, 'create'])->name('admins.create');
    Route::post('admins', [AdminController::class, 'store'])->name('admins.store');
    Route::get('admins/{admin}', [AdminController::class, 'show'])->name('admins.show');
    Route::get('admins/{admin}/edit', [AdminController::class, 'edit'])->name('admins.edit');
    Route::put('admins/{admin}', [AdminController::class, 'update'])->name('admins.update');
    Route::delete('admins/{admin}', [AdminController::class, 'destroy'])->name('admins.destroy');
    // Schools
    Route::resource('schools', SchoolController::class);
    // Theme settings
    Route::get('theme-settings', [ThemeSettingController::class, 'index'])->name('theme-settings.index');
    Route::put('theme-settings/{school}', [ThemeSettingController::class, 'update'])->name('theme-settings.update');
    // System settings
    Route::get('settings', [SuperadminSettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [SuperadminSettingController::class, 'update'])->name('settings.update');
    Route::get('settings/system-info', [SuperadminSettingController::class, 'getSystemInfo'])->name('settings.system');
    
    // New Comprehensive Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        // General Settings
        Route::get('general', [App\Http\Controllers\Superadmin\SuperAdminSettingsController::class, 'general'])->name('general');
        Route::post('general', [App\Http\Controllers\Superadmin\SuperAdminSettingsController::class, 'updateGeneral'])->name('general.update');
        
        // User & Roles Settings
        Route::get('users', [App\Http\Controllers\Superadmin\SuperAdminSettingsController::class, 'users'])->name('users');
        Route::post('users', [App\Http\Controllers\Superadmin\SuperAdminSettingsController::class, 'updateUsers'])->name('users.update');
        
        // Email & Notification Settings
        Route::get('email', [App\Http\Controllers\Superadmin\SuperAdminSettingsController::class, 'email'])->name('email');
        Route::post('email', [App\Http\Controllers\Superadmin\SuperAdminSettingsController::class, 'updateEmail'])->name('email.update');
        
        // System Configuration
        Route::get('system', [App\Http\Controllers\Superadmin\SuperAdminSettingsController::class, 'system'])->name('system');
        Route::post('system', [App\Http\Controllers\Superadmin\SuperAdminSettingsController::class, 'updateSystem'])->name('system.update');
        
        // Database & Backup Settings
        Route::get('database', [App\Http\Controllers\Superadmin\SuperAdminSettingsController::class, 'database'])->name('database');
        Route::post('database', [App\Http\Controllers\Superadmin\SuperAdminSettingsController::class, 'updateDatabase'])->name('database.update');
        Route::post('backup/create', [App\Http\Controllers\Superadmin\SuperAdminSettingsController::class, 'createBackup'])->name('create-backup');
        
        // Security Settings
        Route::get('security', [App\Http\Controllers\Superadmin\SuperAdminSettingsController::class, 'security'])->name('security');
        Route::post('security', [App\Http\Controllers\Superadmin\SuperAdminSettingsController::class, 'updateSecurity'])->name('security.update');
        
        // Payment Settings
        Route::get('payment', [App\Http\Controllers\Superadmin\SuperAdminSettingsController::class, 'payment'])->name('payment');
        Route::post('payment', [App\Http\Controllers\Superadmin\SuperAdminSettingsController::class, 'updatePayment'])->name('payment.update');
        
        // Developer Tools
        Route::get('developer', [App\Http\Controllers\Superadmin\SuperAdminSettingsController::class, 'developer'])->name('developer');
        Route::post('developer', [App\Http\Controllers\Superadmin\SuperAdminSettingsController::class, 'updateDeveloper'])->name('developer.update');
        
        // UI/Theme Settings
        Route::get('theme', [App\Http\Controllers\Superadmin\SuperAdminSettingsController::class, 'theme'])->name('theme');
        Route::post('theme', [App\Http\Controllers\Superadmin\SuperAdminSettingsController::class, 'updateTheme'])->name('theme.update');
        
        // Advanced Settings
        Route::get('advanced', [App\Http\Controllers\Superadmin\SuperAdminSettingsController::class, 'advanced'])->name('advanced');
        Route::post('advanced', [App\Http\Controllers\Superadmin\SuperAdminSettingsController::class, 'updateAdvanced'])->name('advanced.update');
        
        // System Actions
        Route::post('clear-cache', [App\Http\Controllers\Superadmin\SuperAdminSettingsController::class, 'clearCache'])->name('clear-cache');
        Route::post('toggle-maintenance', [App\Http\Controllers\Superadmin\SuperAdminSettingsController::class, 'toggleMaintenance'])->name('toggle-maintenance');
        
        // Export/Import Settings
        Route::get('export', [App\Http\Controllers\Superadmin\SuperAdminSettingsController::class, 'exportSettings'])->name('export');
        Route::post('import', [App\Http\Controllers\Superadmin\SuperAdminSettingsController::class, 'importSettings'])->name('import');
    });
    // Purchases
    Route::get('purchases', [PurchaseController::class, 'index'])->name('purchases.index');
    Route::get('purchases/datatable', [PurchaseController::class, 'serverSideDataTable'])->name('purchases.datatable');
    Route::get('purchases/create', [PurchaseController::class, 'create'])->name('purchases.create');
    Route::post('purchases', [PurchaseController::class, 'store'])->name('purchases.store');
    Route::get('purchases/{purchase}', [PurchaseController::class, 'show'])->name('purchases.show');
    Route::get('purchases/{purchase}/edit', [PurchaseController::class, 'edit'])->name('purchases.edit');
    Route::put('purchases/{purchase}', [PurchaseController::class, 'update'])->name('purchases.update');
    Route::delete('purchases/{purchase}', [PurchaseController::class, 'destroy'])->name('purchases.destroy');
    // Product Plans
    Route::get('productplans', [ProductPlanController::class, 'index'])->name('productplans.index');
    Route::post('productplans/send', [ProductPlanController::class, 'sendNotification'])->name('productplans.notify');
    Route::post('productplans', [ProductPlanController::class, 'store'])->name('productplans.store');
    Route::get('productplans/{id}/edit', [ProductPlanController::class, 'edit'])->name('productplans.edit');
    Route::put('productplans/{id}', [ProductPlanController::class, 'update'])->name('productplans.update');
    Route::delete('productplans/{id}', [ProductPlanController::class, 'destroy'])->name('productplans.destroy');
    
    // Role & Permission Management
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/', [App\Http\Controllers\Superadmin\RoleController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Superadmin\RoleController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Superadmin\RoleController::class, 'store'])->name('store');
        Route::get('/{role}', [App\Http\Controllers\Superadmin\RoleController::class, 'show'])->name('show');
        Route::get('/{role}/edit', [App\Http\Controllers\Superadmin\RoleController::class, 'edit'])->name('edit');
        Route::put('/{role}', [App\Http\Controllers\Superadmin\RoleController::class, 'update'])->name('update');
        Route::delete('/{role}', [App\Http\Controllers\Superadmin\RoleController::class, 'destroy'])->name('destroy');
        Route::get('/{role}/permissions', [App\Http\Controllers\Superadmin\RoleController::class, 'permissions'])->name('permissions');
        Route::post('/{role}/permissions', [App\Http\Controllers\Superadmin\RoleController::class, 'updatePermissions'])->name('update-permissions');
        Route::post('/{role}/toggle-status', [App\Http\Controllers\Superadmin\RoleController::class, 'toggleStatus'])->name('toggle-status');
    });
    
    Route::prefix('permissions')->name('permissions.')->group(function () {
        Route::get('/', [App\Http\Controllers\Superadmin\PermissionController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Superadmin\PermissionController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Superadmin\PermissionController::class, 'store'])->name('store');
        Route::get('/{permission}', [App\Http\Controllers\Superadmin\PermissionController::class, 'show'])->name('show');
        Route::get('/{permission}/edit', [App\Http\Controllers\Superadmin\PermissionController::class, 'edit'])->name('edit');
        Route::put('/{permission}', [App\Http\Controllers\Superadmin\PermissionController::class, 'update'])->name('update');
        Route::delete('/{permission}', [App\Http\Controllers\Superadmin\PermissionController::class, 'destroy'])->name('destroy');
        Route::post('/{permission}/toggle-status', [App\Http\Controllers\Superadmin\PermissionController::class, 'toggleStatus'])->name('toggle-status');
        Route::get('/by-module', [App\Http\Controllers\Superadmin\PermissionController::class, 'getByModule'])->name('by-module');
    });
    
    Route::prefix('user-roles')->name('user-roles.')->group(function () {
        Route::get('/', [App\Http\Controllers\Superadmin\UserRoleController::class, 'index'])->name('index');
        Route::get('/{user}/assign', [App\Http\Controllers\Superadmin\UserRoleController::class, 'assign'])->name('assign');
        Route::post('/{user}', [App\Http\Controllers\Superadmin\UserRoleController::class, 'store'])->name('store');
        Route::get('/{user}', [App\Http\Controllers\Superadmin\UserRoleController::class, 'show'])->name('show');
        Route::delete('/{user}/role/{role}', [App\Http\Controllers\Superadmin\UserRoleController::class, 'removeRole'])->name('remove-role');
        Route::get('/{user}/permissions-by-module', [App\Http\Controllers\Superadmin\UserRoleController::class, 'getPermissionsByModule'])->name('permissions-by-module');
        Route::post('/bulk-assign', [App\Http\Controllers\Superadmin\UserRoleController::class, 'bulkAssign'])->name('bulk-assign');
    });
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
    // Optional: GET fallback to avoid 405 error on direct URL
    Route::get('/logout', function () {
        return redirect()->route('admin.login')
            ->with('error', 'Please use the logout button to log out properly.');
    })->name('logout.get');
    
    
    Route::middleware(['auth:admin', 'checkrole:admin'])->group(function () {
        Route::get('dashboard', [DashboardController::class,'dashboard'])->name('dashboard');
        Route::get('dashboard/chart-data', [DashboardController::class,'getChartData'])->name('dashboard.chart-data');
        Route::get('dashboard/debug', function() {
            $controller = new App\Http\Controllers\Admin\DashboardController();
            $admin = auth()->guard('admin')->user();
            $schoolId = $admin ? $admin->school_id : 1;
            
            $examinationData = $controller->getExaminationPerformanceData($schoolId);
            $financeData = $controller->getFinanceOverviewData($schoolId);
            $expenditureData = $controller->getExpenditureAnalysisData($schoolId);
            $incomeData = $controller->getIncomeAnalyticsData($schoolId);
            
            return response()->json([
                'examination' => $examinationData,
                'finance' => $financeData,
                'expenditure' => $expenditureData,
                'income' => $incomeData
            ]);
        })->name('dashboard.debug');
         // Users Module
         Route::prefix('users')->name('users.')->group(function () {
            //teachers
             Route::get('/teachers', [TeacherController::class, 'index'])->name('teachers.index');           // Show all teachers
             Route::get('/teachers/create', [TeacherController::class, 'create'])->name('teachers.create');   // Show form to create
             Route::post('/teachers', [TeacherController::class, 'store'])->name('teachers.store');           // Save new teacher
             Route::get('/teachers/{id}', [TeacherController::class, 'show'])->name('teachers.show'); // Show teacher details (GET)
             Route::get('/teachers/{id}/edit', [TeacherController::class, 'edit'])->name('teachers.edit'); // Show form to edit
             Route::put('/teachers/{id}', [TeacherController::class, 'update'])->name('teachers.update'); // Update teacher
             Route::delete('/teachers/{id}', [TeacherController::class, 'destroy'])->name('teachers.destroy'); // Delete teacher
             Route::post('/teachers/reset-password', [TeacherController::class, 'resetPassword'])->name('teachers.resetPassword');
             Route::get('/teachers/dashboard', [TeacherController::class, 'dashboard'])->name('teachers.dashboard');

             //librarians
             Route::get('/librarians', [LibrarianController::class, 'index'])->name('librarians.index');             // Show all librarians
             Route::get('/librarians/create', [LibrarianController::class, 'create'])->name('librarians.create');   // Show form to create
             Route::get('/librarians/{id}', [LibrarianController::class, 'show'])->name('librarians.show'); // Show librarian details (GET)
             Route::post('/librarians', [LibrarianController::class, 'store'])->name('librarians.store');           // Save new librarian
             Route::get('/librarians/{id}/edit', [LibrarianController::class, 'edit'])->name('librarians.edit'); // Show form to edit
             Route::put('/librarians/{id}', [LibrarianController::class, 'update'])->name('librarians.update'); // Update librarian
             Route::delete('/librarians/{id}', [LibrarianController::class, 'destroy'])->name('librarians.destroy'); // Delete librarian
             Route::get('/librarians/dashboard', [LibrarianController::class, 'dashboard'])->name('librarians.dashboard');
             Route::post('/librarians/reset-password', [LibrarianController::class, 'resetPassword'])->name('librarians.resetPassword');
              Route::get('/librarians/export', [LibrarianController::class, 'export'])->name('librarians.export');
              Route::post('/librarians/import', [LibrarianController::class, 'import'])->name('librarians.import');
              Route::post('/librarians/bulk-delete', [LibrarianController::class, 'bulkDelete'])->name('librarians.bulkDelete');
              Route::post('/librarians/bulk-status', [LibrarianController::class, 'bulkStatus'])->name('librarians.bulkStatus');
              Route::post('/librarians/{id}/toggle-status', [LibrarianController::class, 'toggleStatus'])->name('librarians.toggleStatus');
             
             // Accountant Routes
            Route::get('/accountants/dashboard', [AccountantController::class, 'dashboard'])->name('accountants.dashboard');
            Route::get('/accountants', [AccountantController::class, 'index'])->name('accountants.index');             // Show all accountants
            Route::get('/accountants/create', [AccountantController::class, 'create'])->name('accountants.create');   // Show form to create
            Route::post('/accountants', [AccountantController::class, 'store'])->name('accountants.store');           // Save new accountant
            Route::get('/accountants/{id}', [AccountantController::class, 'show'])->name('accountants.show'); // Show accountant details (GET)
            Route::get('/accountants/{id}/edit', [AccountantController::class, 'edit'])->name('accountants.edit'); // Show form to edit
            Route::put('/accountants/{id}', [AccountantController::class, 'update'])->name('accountants.update'); // Update accountant
            Route::delete('/accountants/{id}', [AccountantController::class, 'destroy'])->name('accountants.destroy'); // Delete accountant
            Route::post('/accountants/reset-password', [AccountantController::class, 'resetPassword'])->name('accountants.resetPassword');
            Route::get('/accountants/dashboard', [AccountantController::class, 'dashboard'])->name('accountants.dashboard');

            // Student Routes
            Route::get('/students/dashboard', [StudentController::class, 'dashboard'])->name('students.dashboard');
            Route::get('/students', [StudentController::class, 'index'])->name('students.index');
            Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
            Route::post('/students', [StudentController::class, 'store'])->name('students.store');
            Route::get('/students/{id}', [StudentController::class, 'show'])->name('students.show'); // Show student details (GET)
            Route::get('/students/{id}/edit', [StudentController::class, 'edit'])->name('students.edit');
            Route::put('/students/{id}', [StudentController::class, 'update'])->name('students.update');
            Route::delete('/students/{id}', [StudentController::class, 'destroy'])->name('students.destroy');
            Route::get('/students/dashboard', [StudentController::class, 'dashboard'])->name('students.dashboard');
            Route::post('/students/reset-password', [StudentController::class, 'resetPassword'])->name('students.resetPassword');
            
            // Parent Routes
            Route::get('/parents', [ParentController::class, 'index'])->name('parents.index');
            Route::get('/parents/create', [ParentController::class, 'create'])->name('parents.create');
            Route::post('/parents', [ParentController::class, 'store'])->name('parents.store');
            Route::post('/parents/{id}', [ParentController::class, 'show'])->name('parents.show');
            Route::get('/parents/{id}/edit', [ParentController::class, 'edit'])->name('parents.edit');
            Route::put('/parents/{id}', [ParentController::class, 'update'])->name('parents.update');
            Route::delete('/parents/{id}', [ParentController::class, 'destroy'])->name('parents.destroy');
            Route::get('/parents/dashboard', [ParentController::class, 'dashboard'])->name('parents.dashboard');
            Route::post('/parents/reset-password', [ParentController::class, 'resetPassword'])->name('parents.resetPassword');
        });

        // Office - Admission Enquiry routes
        Route::prefix('office')->name('office.')->group(function () {
            // Admission Enquiry CRUD
            Route::get('/enquiry', [AdmissionEnquiryController::class, 'index'])->name('enquiry.index');
            Route::get('/enquiry/create', [AdmissionEnquiryController::class, 'create'])->name('enquiry.create');
            Route::post('/enquiry', [AdmissionEnquiryController::class, 'store'])->name('enquiry.store');
            Route::get('/enquiry/{id}', [AdmissionEnquiryController::class, 'show'])->name('enquiry.show');
            Route::get('/enquiry/{id}/edit', [AdmissionEnquiryController::class, 'edit'])->name('enquiry.edit');
            Route::put('/enquiry/{id}', [AdmissionEnquiryController::class, 'update'])->name('enquiry.update');
            Route::delete('/enquiry/{id}', [AdmissionEnquiryController::class, 'destroy'])->name('enquiry.destroy');
            Route::get('/enquiry/dashboard', [AdmissionEnquiryController::class, 'dashboard'])->name('enquiry.dashboard');
            // Extra actions
            Route::post('/enquiry/{id}/status', [AdmissionEnquiryController::class, 'changeStatus'])->name('enquiry.status');
            Route::post('/enquiry/{id}/follow-up', [AdmissionEnquiryController::class, 'storeFollowUp'])->name('enquiry.followup.store');
            Route::post('/enquiry/{id}/mark-contacted', [AdmissionEnquiryController::class, 'markAsContacted'])->name('enquiry.markContacted');
            Route::delete('/enquiry/bulk-delete', [AdmissionEnquiryController::class, 'bulkDelete'])->name('enquiry.bulkDelete');
            Route::get('/enquiry/export', [AdmissionEnquiryController::class, 'export'])->name('enquiry.export');
            Route::post('/enquiry/import', [AdmissionEnquiryController::class, 'import'])->name('enquiry.import');
            Route::post('/enquiry/assign-counselor', [AdmissionEnquiryController::class, 'assignCounselor'])->name('enquiry.assignCounselor');
            // Visitor Routes
            Route::get('/visitors', [VisitorController::class, 'index'])->name('visitors.index');
            Route::get('/visitors/create', [VisitorController::class, 'create'])->name('visitors.create');
            Route::post('/visitors', [VisitorController::class, 'store'])->name('visitors.store');
            Route::get('/visitors/{id}', [VisitorController::class, 'show'])->name('visitors.show');
            Route::get('/visitors/{id}/edit', [VisitorController::class, 'edit'])->name('visitors.edit');
            Route::put('/visitors/{id}', [VisitorController::class, 'update'])->name('visitors.update');
            Route::delete('/visitors/{id}', [VisitorController::class, 'destroy'])->name('visitors.destroy');
            Route::post('/visitors/{id}/mark-out', [VisitorController::class, 'markOut'])->name('visitors.markOut');
            Route::get('/visitors/export', [VisitorController::class, 'export'])->name('visitors.export');
            Route::post('/visitors/import', [VisitorController::class, 'import'])->name('visitors.import');
            
            // Call Logs Routes
            Route::get('/calllogs', [CallLogController::class, 'index'])->name('calllogs.index');
            Route::get('/calllogs/create', [CallLogController::class, 'create'])->name('calllogs.create');
            Route::post('/calllogs', [CallLogController::class, 'store'])->name('calllogs.store');
            Route::get('/calllogs/{id}', [CallLogController::class, 'show'])->name('calllogs.show');
            Route::get('/calllogs/{id}/edit', [CallLogController::class, 'edit'])->name('calllogs.edit');
            Route::put('/calllogs/{id}', [CallLogController::class, 'update'])->name('calllogs.update');
            Route::delete('/calllogs/{id}', [CallLogController::class, 'destroy'])->name('calllogs.destroy');
            Route::get('/calllogs/export', [CallLogController::class, 'export'])->name('calllogs.export');
            Route::post('/calllogs/import', [CallLogController::class, 'import'])->name('calllogs.import');

            // Visitors Purpose Routes
            Route::get('/visitorspurpose', [VisitorsPurposeController::class, 'index'])->name('visitorspurpose.index');
            Route::get('/visitorspurpose/create', [VisitorsPurposeController::class, 'create'])->name('visitorspurpose.create');
            Route::post('/visitorspurpose', [VisitorsPurposeController::class, 'store'])->name('visitorspurpose.store');
            Route::get('/visitorspurpose/{id}', [VisitorsPurposeController::class, 'show'])->name('visitorspurpose.show');
            Route::get('/visitorspurpose/{id}/edit', [VisitorsPurposeController::class, 'edit'])->name('visitorspurpose.edit');
            Route::put('/visitorspurpose/{id}', [VisitorsPurposeController::class, 'update'])->name('visitorspurpose.update');
            Route::delete('/visitorspurpose/{id}', [VisitorsPurposeController::class, 'destroy'])->name('visitorspurpose.destroy');
            Route::get('/visitorspurpose/export', [VisitorsPurposeController::class, 'export'])->name('visitorspurpose.export');
            Route::post('/visitorspurpose/import', [VisitorsPurposeController::class, 'import'])->name('visitorspurpose.import');
            // Call Log Routes
            Route::get('/call-logs', [CallLogController::class, 'index'])->name('calllogs.index');
            Route::get('/call-logs/create', [CallLogController::class, 'create'])->name('calllogs.create');
            Route::post('/call-logs', [CallLogController::class, 'store'])->name('calllogs.store');
            Route::get('/call-logs/{id}', [CallLogController::class, 'show'])->name('calllogs.show');
            Route::get('/call-logs/{id}/edit', [CallLogController::class, 'edit'])->name('calllogs.edit');
            Route::put('/call-logs/{id}', [CallLogController::class, 'update'])->name('calllogs.update');
            Route::delete('/call-logs/{id}', [CallLogController::class, 'destroy'])->name('calllogs.destroy');
            Route::get('/call-logs/export', [CallLogController::class, 'export'])->name('calllogs.export');
            Route::post('/call-logs/import', [CallLogController::class, 'import'])->name('calllogs.import');
            // Postal Dispatch Routes
            Route::get('/dispatch', [PostalDispatchController::class, 'index'])->name('dispatch.index');
            Route::get('/dispatch/create', [PostalDispatchController::class, 'create'])->name('dispatch.create');
            Route::post('/dispatch', [PostalDispatchController::class, 'store'])->name('dispatch.store');
            Route::get('/dispatch/{id}', [PostalDispatchController::class, 'show'])->name('dispatch.show');
            Route::get('/dispatch/{id}/edit', [PostalDispatchController::class, 'edit'])->name('dispatch.edit');
            Route::put('/dispatch/{id}', [PostalDispatchController::class, 'update'])->name('dispatch.update');
            Route::delete('/dispatch/{id}', [PostalDispatchController::class, 'destroy'])->name('dispatch.destroy');
            Route::get('/dispatch/export', [PostalDispatchController::class, 'export'])->name('dispatch.export');
            Route::post('/dispatch/import', [PostalDispatchController::class, 'import'])->name('dispatch.import');
            // 📬 Postal Receive Routes
            Route::get('/receive', [PostalReceiveController::class, 'index'])->name('receive.index');
            Route::get('/receive/create', [PostalReceiveController::class, 'create'])->name('receive.create');
            Route::post('/receive', [PostalReceiveController::class, 'store'])->name('receive.store');
            Route::get('/receive/{id}', [PostalReceiveController::class, 'show'])->name('receive.show');
            Route::get('/receive/{id}/edit', [PostalReceiveController::class, 'edit'])->name('receive.edit');
            Route::put('/receive/{id}', [PostalReceiveController::class, 'update'])->name('receive.update');
            Route::delete('/receive/{id}', [PostalReceiveController::class, 'destroy'])->name('receive.destroy');
            // 📤 Optional: Import/Export (if needed)
            Route::get('/receive/export', [PostalReceiveController::class, 'export'])->name('receive.export');
            Route::post('/receive/import', [PostalReceiveController::class, 'import'])->name('receive.import');
            // 📦 Complaint Box Routes
            Route::get('/complaintbox', [AdminComplaintBoxController::class, 'index'])->name('complaintbox.index');
            Route::get('/complaintbox/create', [AdminComplaintBoxController::class, 'create'])->name('complaintbox.create');
            Route::post('/complaintbox', [AdminComplaintBoxController::class, 'store'])->name('complaintbox.store');
            Route::get('/complaintbox/{id}', [AdminComplaintBoxController::class, 'show'])->name('complaintbox.show');
            Route::get('/complaintbox/{id}/edit', [AdminComplaintBoxController::class, 'edit'])->name('complaintbox.edit');
            Route::put('/complaintbox/{id}', [AdminComplaintBoxController::class, 'update'])->name('complaintbox.update');
            Route::delete('/complaintbox/{id}', [AdminComplaintBoxController::class, 'destroy'])->name('complaintbox.destroy');
            // 📤 Import/Export
            Route::get('/complaintbox/export', [AdminComplaintBoxController::class, 'export'])->name('complaintbox.export');
            Route::post('/complaintbox/import', [AdminComplaintBoxController::class, 'import'])->name('complaintbox.import');
        });
       
         Route::prefix('students')->name('students.')->group(function () {
             // Student Details 
            Route::get('/details', [StudentDetailsController::class, 'index'])->name('details.index');
             Route::get('/details/create', [StudentDetailsController::class, 'create'])->name('details.create');
             Route::post('/details/store', [StudentDetailsController::class, 'store'])->name('details.store');
             Route::get('/details/{id}', [StudentDetailsController::class, 'show'])->name('details.show');
             Route::get('/details/{id}/edit', [StudentDetailsController::class, 'edit'])->name('details.edit');
             Route::post('/details/{id}', [StudentDetailsController::class, 'update'])->name('details.update');
             Route::delete('/details/{id}', [StudentDetailsController::class, 'destroy'])->name('details.delete');
            //  Export/Import
            Route::get('/details/export', [StudentDetailsController::class, 'export'])->name('details.export');
            Route::post('/details/import', [StudentDetailsController::class, 'import'])->name('details.import');
           
             // Student Details 
             Route::get('/class_sections', [ClassSectionController::class, 'index'])->name('class_sections.index');
             Route::get('/class_sections/create', [ClassSectionController::class, 'create'])->name('class_sections.create');
             Route::post('/class_sections/store', [ClassSectionController::class, 'store'])->name('class_sections.store');
             Route::get('/class_sections/{id}', [ClassSectionController::class, 'show'])->name('class_sections.show');
             Route::get('/class_sections/{id}/edit', [ClassSectionController::class, 'edit'])->name('class_sections.edit');
             Route::post('/class_sections/{id}', [ClassSectionController::class, 'update'])->name('class_sections.update');
             Route::delete('/class_sections/{id}', [ClassSectionController::class, 'destroy'])->name('class_sections.delete');
            //  Export/Import
            Route::get('/class_sections/export', [ClassSectionController::class, 'export'])->name('class_sections.export');
            Route::post('/class_sections/import', [ClassSectionController::class, 'import'])->name('class_sections.import');
            // Attendance routes
            Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
            Route::get('/attendance/create', [AttendanceController::class, 'create'])->name('attendance.create');
            Route::get('/attendance/store', [AttendanceController::class, 'store'])->name('attendance.store');
            Route::get('/attendance/edit', [AttendanceController::class, 'edit'])->name('attendance.edit');
            Route::post('/attendance/delete', [AttendanceController::class, 'delete'])->name('attendance.delete');
            Route::get('/attendance/daily-report', [AttendanceController::class, 'dailyReport'])->name('attendance.daily-report');
            Route::post('/attendance/get-sections-by-class', [AttendanceController::class, 'getSectionsByClass'])->name('attendance.get-sections-by-class');
            Route::post('/attendance/get-students-by-class-section', [AttendanceController::class, 'getStudentsByClassSection'])->name('attendance.get-students-by-class-section');
            Route::post('/attendance/save', [AttendanceController::class, 'saveAttendance'])->name('attendance.save');
            Route::get('/attendance/report', [AttendanceController::class, 'report'])->name('attendance.report');
            Route::post('/attendance/get-report-data', [AttendanceController::class, 'getReportData'])->name('attendance.get-report-data');
            Route::get('/attendance/student/{id}', [AttendanceController::class, 'showStudentAttendance'])->name('attendance.student');
            Route::post('/attendance/get-student-attendance-data', [AttendanceController::class, 'getStudentAttendanceData'])->name('attendance.get-student-attendance-data');
                    
            Route::get('/results', [StudentResultsController::class, 'index'])->name('results.index');
            Route::get('/results/create', [StudentResultsController::class, 'create'])->name('results.create');
            Route::post('/results/store', [StudentResultsController::class, 'store'])->name('results.store');
            Route::get('/results/{id}', [StudentResultsController::class, 'show'])->name('results.show');
            Route::get('/results/{id}/edit', [StudentResultsController::class, 'edit'])->name('results.edit');
            Route::post('/results/{id}', [StudentResultsController::class, 'update'])->name('results.update');
            Route::delete('/results/{id}', [StudentResultsController::class, 'destroy'])->name('results.delete');

            // Export/Import
            Route::get('/results/export', [StudentResultsController::class, 'export'])->name('results.export');
            Route::post('/results/import', [StudentResultsController::class, 'import'])->name('results.import');

            // Fees
            Route::get('/fees', [StudentFeeController::class, 'index'])->name('fees.index');
            Route::get('/fees/create', [StudentFeeController::class, 'create'])->name('fees.create');
            Route::post('/fees', [StudentFeeController::class, 'store'])->name('fees.store');
            Route::get('/fees/{id}', [StudentFeeController::class, 'show'])->name('fees.show');
            Route::get('/fees/{id}/edit', [StudentFeeController::class, 'edit'])->name('fees.edit');
            Route::put('/fees/{id}', [StudentFeeController::class, 'update'])->name('fees.update');
            Route::delete('/fees/{id}', [StudentFeeController::class, 'destroy'])->name('fees.destroy');

            // Promotions
            Route::get('/promotions', [StudentPromotionController::class, 'index'])->name('promotions.index');
            Route::get('/promotions/create', [StudentPromotionController::class, 'create'])->name('promotions.create');
            Route::post('/promotions', [StudentPromotionController::class, 'store'])->name('promotions.store');
            Route::get('/promotions/{id}', [StudentPromotionController::class, 'show'])->name('promotions.show');
            Route::get('/promotions/{id}/edit', [StudentPromotionController::class, 'edit'])->name('promotions.edit');
            Route::put('/promotions/{id}', [StudentPromotionController::class, 'update'])->name('promotions.update');
            Route::delete('/promotions/{id}', [StudentPromotionController::class, 'destroy'])->name('promotions.destroy');

            // Student Health
            Route::get('/health', [StudentHealthController::class, 'index'])->name('health.index');
            Route::get('/health/create', [StudentHealthController::class, 'create'])->name('health.create');
            Route::post('/health', [StudentHealthController::class, 'store'])->name('health.store');
            Route::get('/health/{id}', [StudentHealthController::class, 'show'])->name('health.show');
            Route::get('/health/{id}/edit', [StudentHealthController::class, 'edit'])->name('health.edit');
            Route::put('/health/{id}', [StudentHealthController::class, 'update'])->name('health.update');
            Route::delete('/health/{id}', [StudentHealthController::class, 'destroy'])->name('health.destroy');    
        
            // Student Documents
            Route::get('/documents', [StudentDocumentController::class, 'index'])->name('documents.index');
            Route::get('/documents/create', [StudentDocumentController::class, 'create'])->name('documents.create');
            Route::post('/documents', [StudentDocumentController::class, 'store'])->name('documents.store');
            Route::get('/documents/{id}', [StudentDocumentController::class, 'show'])->name('documents.show');
            Route::get('/documents/{id}/edit', [StudentDocumentController::class, 'edit'])->name('documents.edit');
            Route::put('/documents/{id}', [StudentDocumentController::class, 'update'])->name('documents.update');
            Route::delete('/documents/{id}', [StudentDocumentController::class, 'destroy'])->name('documents.destroy');

            // Student Transport
            Route::get('/transport', [StudentTransportController::class, 'index'])->name('transport.index');
            Route::get('/transport/create', [StudentTransportController::class, 'create'])->name('transport.create');
            Route::post('/transport', [StudentTransportController::class, 'store'])->name('transport.store');
            Route::get('/transport/{id}', [StudentTransportController::class, 'show'])->name('transport.show');
            Route::get('/transport/{id}/edit', [StudentTransportController::class, 'edit'])->name('transport.edit');
            Route::put('/transport/{id}', [StudentTransportController::class, 'update'])->name('transport.update');
            Route::delete('/transport/{id}', [StudentTransportController::class, 'destroy'])->name('transport.destroy');

            // Student Communication
            Route::get('/communication', [StudentCommunicationController::class, 'index'])->name('communication.index');
            Route::get('/communication/create', [StudentCommunicationController::class, 'create'])->name('communication.create');
            Route::post('/communication', [StudentCommunicationController::class, 'store'])->name('communication.store');
            Route::get('/communication/{id}', [StudentCommunicationController::class, 'show'])->name('communication.show');
            Route::get('/communication/{id}/edit', [StudentCommunicationController::class, 'edit'])->name('communication.edit');
            Route::put('/communication/{id}', [StudentCommunicationController::class, 'update'])->name('communication.update');
            Route::delete('/communication/{id}', [StudentCommunicationController::class, 'destroy'])->name('communication.destroy');

            // Student Portal Access
            Route::get('/portal-access', [StudentPortalAccessController::class, 'index'])->name('portal-access.index');
            Route::get('/portal-access/create', [StudentPortalAccessController::class, 'create'])->name('portal-access.create');
            Route::post('/portal-access', [StudentPortalAccessController::class, 'store'])->name('portal-access.store');
            Route::get('/portal-access/{id}', [StudentPortalAccessController::class, 'show'])->name('portal-access.show');
            Route::get('/portal-access/{id}/edit', [StudentPortalAccessController::class, 'edit'])->name('portal-access.edit');
            Route::put('/portal-access/{id}', [StudentPortalAccessController::class, 'update'])->name('portal-access.update');
            Route::delete('/portal-access/{id}', [StudentPortalAccessController::class, 'destroy'])->name('portal-access.destroy');

            // Student Hostel
            Route::get('/hostel', [StudentHostelController::class, 'index'])->name('hostel.index');
            Route::get('/hostel/create', [StudentHostelController::class, 'create'])->name('hostel.create');
            Route::post('/hostel', [StudentHostelController::class, 'store'])->name('hostel.store');
            Route::get('/hostel/{id}', [StudentHostelController::class, 'show'])->name('hostel.show');
            Route::get('/hostel/{id}/edit', [StudentHostelController::class, 'edit'])->name('hostel.edit');
            Route::put('/hostel/{id}', [StudentHostelController::class, 'update'])->name('hostel.update');
            Route::delete('/hostel/{id}', [StudentHostelController::class, 'destroy'])->name('hostel.destroy');
            Route::post('/hostel/get-rooms-by-hostel', [StudentHostelController::class, 'getRoomsByHostel'])->name('hostel.getRoomsByHostel');
        
        });

                 // Parent Details
        Route::prefix('parents')->name('parents.')->group(function () {
            Route::prefix('details')->name('details.')->group(function () {
                Route::get('/', [ParentDetailsController::class, 'index'])->name('index');
                Route::get('/create', [ParentDetailsController::class, 'create'])->name('create');
                Route::post('/store', [ParentDetailsController::class, 'store'])->name('store');
                Route::get('/{id}', [ParentDetailsController::class, 'show'])->name('show');
                Route::get('/{id}/edit', [ParentDetailsController::class, 'edit'])->name('edit');
                Route::post('/{id}', [ParentDetailsController::class, 'update'])->name('update');
                Route::delete('/{id}', [ParentDetailsController::class, 'destroy'])->name('delete');
            });

            // Parent Communication
            Route::prefix('communication')->name('communication.')->group(function () {
                Route::get('/', [ParentCommunicationController::class, 'index'])->name('index');
                Route::get('/create', [ParentCommunicationController::class, 'create'])->name('create');
                Route::post('/store', [ParentCommunicationController::class, 'store'])->name('store');
                Route::get('/dashboard', [ParentCommunicationController::class, 'dashboard'])->name('dashboard');
                Route::post('/bulk-action', [ParentCommunicationController::class, 'bulkAction'])->name('bulk-action');
                Route::get('/parent/{id}/students', [ParentCommunicationController::class, 'getParentStudents'])->name('get-parent-students');
                Route::get('/{id}', [ParentCommunicationController::class, 'show'])->name('show');
                Route::get('/{id}/edit', [ParentCommunicationController::class, 'edit'])->name('edit');
                Route::post('/{id}', [ParentCommunicationController::class, 'update'])->name('update');
                Route::delete('/{id}', [ParentCommunicationController::class, 'destroy'])->name('delete');
                Route::get('/{id}/resend', [ParentCommunicationController::class, 'resend'])->name('resend');
                Route::post('/{id}/mark-read', [ParentCommunicationController::class, 'markAsRead'])->name('mark-read');
           
                Route::get('/export', [ParentCommunicationController::class, 'export'])->name('export');
                Route::post('/import', [ParentCommunicationController::class, 'import'])->name('import');
            });

        });
        
        // Library - Module
        Route::prefix('library')->name('library.')->group(function () {
            //books
            Route::get('/books', [BookController::class, 'index'])->name('books.index');
            Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
            Route::post('/books', [BookController::class, 'store'])->name('books.store');
            Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');
            Route::get('/books/{book}/edit', [BookController::class, 'edit'])->name('books.edit');
            Route::put('/books/{book}', [BookController::class, 'update'])->name('books.update');
            Route::delete('/books/{book}', [BookController::class, 'destroy'])->name('books.destroy');
            Route::post('/books/bulk-delete', [BookController::class, 'bulkDelete'])->name('books.bulk-delete');

            Route::get('/books/export', [BookController::class, 'export'])->name('books.export');
            Route::post('/books/import', [BookController::class, 'import'])->name('books.import');
            // categories
            Route::get('/categories', [BookCategoryController::class, 'index'])->name('categories.index');
            Route::get('/categories/create', [BookCategoryController::class, 'create'])->name('categories.create');
            Route::post('/categories', [BookCategoryController::class, 'store'])->name('categories.store');
            Route::get('/categories/{category}', [BookCategoryController::class, 'show'])->name('categories.show');
            Route::get('/categories/{category}/edit', [BookCategoryController::class, 'edit'])->name('categories.edit');
            Route::put('/categories/{category}', [BookCategoryController::class, 'update'])->name('categories.update');
            Route::delete('/categories/{category}', [BookCategoryController::class, 'destroy'])->name('categories.destroy');
            Route::post('/categories/bulk-delete', [BookCategoryController::class, 'bulkDelete'])->name('categories.bulk-delete');
            Route::post('/categories/bulk-status', [BookCategoryController::class, 'bulkStatus'])->name('categories.bulk-status');
            Route::post('/categories/{category}/toggle-status', [BookCategoryController::class, 'toggleStatus'])->name('categories.toggle-status');
            Route::get('/categories/export', [BookCategoryController::class, 'export'])->name('categories.export');
            Route::post('/categories/import', [BookCategoryController::class, 'import'])->name('categories.import');

            // issues
            Route::get('/issues', [BookIssueController::class, 'index'])->name('issues.index');
            Route::get('/issues/create', [BookIssueController::class, 'create'])->name('issues.create');
            Route::post('/issues', [BookIssueController::class, 'store'])->name('issues.store');
            Route::get('/issues/{issue}', [BookIssueController::class, 'show'])->name('issues.show');
            Route::get('/issues/{issue}/edit', [BookIssueController::class, 'edit'])->name('issues.edit');
            Route::put('/issues/{issue}', [BookIssueController::class, 'update'])->name('issues.update');
            Route::delete('/issues/{issue}', [BookIssueController::class, 'destroy'])->name('issues.destroy');
            Route::post('/issues/bulk-delete', [BookIssueController::class, 'bulkDelete'])->name('issues.bulk-delete');
            Route::post('/issues/bulk-return', [BookIssueController::class, 'bulkReturn'])->name('issues.bulk-return');
            Route::get('/issues/{issue}/quick-return', [BookIssueController::class, 'quickReturn'])->name('issues.quick-return');
            Route::get('/issues/{issue}/extend', [BookIssueController::class, 'extend'])->name('issues.extend');
            Route::get('/issues/export', [BookIssueController::class, 'export'])->name('issues.export');
            Route::post('/issues/import', [BookIssueController::class, 'import'])->name('issues.import');

            // returns
            Route::get('/returns', [BookReturnController::class, 'index'])->name('returns.index');
            Route::get('/returns/create', [BookReturnController::class, 'create'])->name('returns.create');
            Route::post('/returns', [BookReturnController::class, 'store'])->name('returns.store');
            Route::get('/returns/{return}', [BookReturnController::class, 'show'])->name('returns.show');
            Route::delete('/returns/{return}', [BookReturnController::class, 'destroy'])->name('returns.destroy');
            Route::post('/returns/bulk-delete', [BookReturnController::class, 'bulkDelete'])->name('returns.bulk-delete');
            Route::get('/returns/{return}/print', [BookReturnController::class, 'print'])->name('returns.print');
            Route::get('/returns/export', [BookReturnController::class, 'export'])->name('returns.export');
            Route::post('/returns/import', [BookReturnController::class, 'import'])->name('returns.import');

            // members
            Route::get('/members', [LibraryMemberController::class, 'index'])->name('members.index');
            Route::get('/members/create', [LibraryMemberController::class, 'create'])->name('members.create');
            Route::post('/members', [LibraryMemberController::class, 'store'])->name('members.store');
            Route::get('/members/{member}', [LibraryMemberController::class, 'show'])->name('members.show');
            Route::get('/members/{member}/edit', [LibraryMemberController::class, 'edit'])->name('members.edit');
            Route::put('/members/{member}', [LibraryMemberController::class, 'update'])->name('members.update');
            Route::delete('/members/{member}', [LibraryMemberController::class, 'destroy'])->name('members.destroy');
            Route::post('/members/bulk-delete', [LibraryMemberController::class, 'bulkDelete'])->name('members.bulk-delete');
            Route::post('/members/bulk-status', [LibraryMemberController::class, 'bulkStatus'])->name('members.bulk-status');
            Route::post('/members/{member}/toggle-status', [LibraryMemberController::class, 'toggleStatus'])->name('members.toggle-status');
            Route::post('/members/{member}/quick-renew', [LibraryMemberController::class, 'quickRenew'])->name('members.quick-renew');
            Route::get('/members/export', [LibraryMemberController::class, 'export'])->name('members.export');
            Route::post('/members/import', [LibraryMemberController::class, 'import'])->name('members.import');
        });

        // Academic - Management 
        Route::prefix('academic')->name('academic.')->group(function () {
            //Subjects Module
            Route::prefix('subjects')->name('subjects.')->group(function () {
                Route::get('/', [AcademicSubjectController::class, 'index'])->name('index');
                Route::post('/datatable', [AcademicSubjectController::class, 'serverSideDataTable'])->name('datatable');
                Route::get('/create', [AcademicSubjectController::class, 'create'])->name('create');
                Route::post('/', [AcademicSubjectController::class, 'store'])->name('store');
                Route::get('/{subject}', [AcademicSubjectController::class, 'show'])->name('show');
                Route::get('/{subject}/edit', [AcademicSubjectController::class, 'edit'])->name('edit');
                Route::put('/{subject}', [AcademicSubjectController::class, 'update'])->name('update');
                Route::delete('/{subject}', [AcademicSubjectController::class, 'destroy'])->name('destroy');

                // Extra utilities
                Route::get('/export', [AcademicSubjectController::class, 'export'])->name('export');
                Route::post('/import', [AcademicSubjectController::class, 'import'])->name('import');
                Route::post('/bulk-delete', [AcademicSubjectController::class, 'bulkDelete'])->name('bulk-delete');
                Route::post('/bulk-status', [AcademicSubjectController::class, 'bulkStatus'])->name('bulk-status');
                Route::post('/{subject}/toggle-status', [AcademicSubjectController::class, 'toggleStatus'])->name('toggle-status');
            });

            // Syllabus Module
            Route::prefix('syllabus')->name('syllabus.')->group(function () {
                Route::get('/', [AcademicSyllabusController::class, 'index'])->name('index');
                Route::get('/create', [AcademicSyllabusController::class, 'create'])->name('create');
                Route::post('/', [AcademicSyllabusController::class, 'store'])->name('store');
                Route::get('/{syllabus}', [AcademicSyllabusController::class, 'show'])->name('show');
                Route::get('/{syllabus}/edit', [AcademicSyllabusController::class, 'edit'])->name('edit');
                Route::put('/{syllabus}', [AcademicSyllabusController::class, 'update'])->name('update');
                Route::delete('/{syllabus}', [AcademicSyllabusController::class, 'destroy'])->name('destroy');

                // utilities
                Route::get('/export', [AcademicSyllabusController::class, 'export'])->name('export');
                Route::post('/import', [AcademicSyllabusController::class, 'import'])->name('import');
                Route::post('/bulk-delete', [AcademicSyllabusController::class, 'bulkDelete'])->name('bulk-delete');
                Route::post('/bulk-status', [AcademicSyllabusController::class, 'bulkStatus'])->name('bulk-status');
                Route::post('/{syllabus}/toggle-status', [AcademicSyllabusController::class, 'toggleStatus'])->name('toggle-status');
            });

            // Lesson Plans Module
            Route::prefix('lesson-plans')->name('lesson-plans.')->group(function () {
                Route::get('/', [AcademicLessonPlanController::class, 'index'])->name('index');
                Route::get('/create', [AcademicLessonPlanController::class, 'create'])->name('create');
                Route::post('/', [AcademicLessonPlanController::class, 'store'])->name('store');
                Route::get('/{lessonPlan}', [AcademicLessonPlanController::class, 'show'])->name('show');
                Route::get('/{lessonPlan}/edit', [AcademicLessonPlanController::class, 'edit'])->name('edit');
                Route::put('/{lessonPlan}', [AcademicLessonPlanController::class, 'update'])->name('update');
                Route::delete('/{lessonPlan}', [AcademicLessonPlanController::class, 'destroy'])->name('destroy');

                // utilities
                Route::get('/export', [AcademicLessonPlanController::class, 'export'])->name('export');
                Route::post('/import', [AcademicLessonPlanController::class, 'import'])->name('import');
                Route::post('/bulk-delete', [AcademicLessonPlanController::class, 'bulkDelete'])->name('bulk-delete');
                Route::post('/bulk-status', [AcademicLessonPlanController::class, 'bulkStatus'])->name('bulk-status');
                Route::post('/{lessonPlan}/toggle-status', [AcademicLessonPlanController::class, 'toggleStatus'])->name('toggle-status');
                Route::get('/{lessonPlan}/duplicate', [AcademicLessonPlanController::class, 'duplicate'])->name('duplicate');
            });

            Route::prefix('timetable')->name('timetable.')->group(function () {
                Route::get('/', [TimetableController::class, 'index'])->name('index');
                Route::get('/create', [TimetableController::class, 'create'])->name('create');
                Route::post('/', [TimetableController::class, 'store'])->name('store');
                Route::get('/{timetable}', [TimetableController::class, 'show'])->name('show');
                Route::get('/{timetable}/edit', [TimetableController::class, 'edit'])->name('edit');
                Route::put('/{timetable}', [TimetableController::class, 'update'])->name('update');
                Route::delete('/{timetable}', [TimetableController::class, 'destroy'])->name('destroy');

                // utilities
                Route::get('/export', [TimetableController::class, 'export'])->name('export');
                Route::post('/import', [TimetableController::class, 'import'])->name('import');
                Route::post('/bulk-delete', [TimetableController::class, 'bulkDelete'])->name('bulk-delete');
                Route::post('/bulk-status', [TimetableController::class, 'bulkStatus'])->name('bulk-status');
                Route::post('/{timetable}/toggle-status', [TimetableController::class, 'toggleStatus'])->name('toggle-status');
            });
            
            // Substitution Module
            Route::prefix('substitution')->name('substitution.')->group(function () {
                Route::get('/', [SubstitutionController::class, 'index'])->name('index');
                Route::get('/create', [SubstitutionController::class, 'create'])->name('create');

                Route::post('/', [SubstitutionController::class, 'store'])->name('store');
                Route::get('/{substitution}', [SubstitutionController::class, 'show'])->name('show');
                Route::get('/{substitution}/edit', [SubstitutionController::class, 'edit'])->name('edit');
                Route::put('/{substitution}', [SubstitutionController::class, 'update'])->name('update');
                Route::delete('/{substitution}', [SubstitutionController::class, 'destroy'])->name('destroy');

                // utilities
                Route::get('/export', [SubstitutionController::class, 'export'])->name('export');
                Route::post('/import', [SubstitutionController::class, 'import'])->name('import');
                Route::post('/bulk-delete', [SubstitutionController::class, 'bulkDelete'])->name('bulk-delete');
                Route::post('/bulk-status', [SubstitutionController::class, 'bulkStatus'])->name('bulk-status');
                Route::post('/{substitution}/toggle-status', [SubstitutionController::class, 'toggleStatus'])->name('toggle-status');

            });

            // Assignments Module
            Route::prefix('assignments')->name('assignments.')->group(function () {
                    Route::get('/dashboard', [AssignmentController::class, 'dashboard'])->name('dashboard'); // <- pehle
                    Route::get('/', [AssignmentController::class, 'index'])->name('index');
                    Route::get('/create', [AssignmentController::class, 'create'])->name('create');
                    Route::post('/', [AssignmentController::class, 'store'])->name('store');
                    Route::get('/{assignment}/edit', [AssignmentController::class, 'edit'])->name('edit');
                    Route::put('/{assignment}', [AssignmentController::class, 'update'])->name('update');
                    Route::delete('/{assignment}', [AssignmentController::class, 'destroy'])->name('destroy');
                    Route::get('/{assignment}', [AssignmentController::class, 'show'])->name('show'); // <- last me
            
                // utilities
                Route::get('/export', [AssignmentController::class, 'export'])->name('export');
                Route::post('/import', [AssignmentController::class, 'import'])->name('import');
              
            
            
            
            });
            // Coverage Module
            Route::prefix('coverage')->name('coverage.')->group(function () {
                Route::get('/dashboard', [CoverageController::class, 'dashboard'])->name('dashboard');
                Route::get('/', [CoverageController::class, 'index'])->name('index');
                Route::get('/create', [CoverageController::class, 'create'])->name('create');
                Route::post('/', [CoverageController::class, 'store'])->name('store');
                Route::get('/{coverage}/edit', [CoverageController::class, 'edit'])->name('edit');
                Route::put('/{coverage}', [CoverageController::class, 'update'])->name('update');
                Route::delete('/{coverage}', [CoverageController::class, 'destroy'])->name('destroy');
                Route::get('/{coverage}', [CoverageController::class, 'show'])->name('show');

                // utilities    
                Route::get('/export', [CoverageController::class, 'export'])->name('export');
                Route::post('/import', [CoverageController::class, 'import'])->name('import');
                
            });
            
            Route::prefix('resource-bookings')->name('resource-bookings.')->group(function () {
                Route::get('/dashboard', [ResourceBookingController::class, 'dashboard'])->name('dashboard');
                Route::get('/', [ResourceBookingController::class, 'index'])->name('index');
                Route::get('/create', [ResourceBookingController::class, 'create'])->name('create');
                Route::post('/', [ResourceBookingController::class, 'store'])->name('store');

                Route::get('/{resourceBooking}/edit', [ResourceBookingController::class, 'edit'])->name('edit');
                Route::get('/{resourceBooking}', [ResourceBookingController::class, 'show'])->name('show');
                Route::put('/{resourceBooking}', [ResourceBookingController::class, 'update'])->name('update');
                Route::delete('/{resourceBooking}', [ResourceBookingController::class, 'destroy'])->name('destroy');
                
                Route::post('/import', [ResourceBookingController::class, 'import'])->name('import');
                Route::post('/export', [ResourceBookingController::class, 'import'])->name('export');
               
              
            });  // PTM Module
                Route::prefix('ptm')->name('ptm.')->group(function () {
                    
                    Route::get('/dashboard', [PtmController::class, 'dashboard'])->name('dashboard');
                    Route::get('/', [PtmController::class, 'index'])->name('index');
                    Route::get('/create', [PtmController::class, 'create'])->name('create');
                    Route::post('/', [PtmController::class, 'store'])->name('store');
                    // Keep specific routes BEFORE dynamic {ptm}
                    Route::get('/export', [PtmController::class, 'export'])->name('export');
                    Route::post('/import', [PtmController::class, 'import'])->name('import');
                    Route::post('/bulk-delete', [PtmController::class, 'bulkDelete'])->name('bulk-delete');
                    Route::get('/{ptm}', [PtmController::class, 'show'])->name('show');
                    Route::get('/{ptm}/edit', [PtmController::class, 'edit'])->name('edit');
                    Route::put('/{ptm}', [PtmController::class, 'update'])->name('update');
                    Route::delete('/{ptm}', [PtmController::class, 'destroy'])->name('destroy');
                });

                    // Academic Calendar Module
            Route::prefix('calendar')->name('calendar.')->group(function () {
                Route::get('/dashboard', [AcademicCalendarController::class, 'dashboard'])->name('dashboard');
                Route::get('/', [AcademicCalendarController::class, 'index'])->name('index');
                Route::get('/create', [AcademicCalendarController::class, 'create'])->name('create');
                Route::post('/', [AcademicCalendarController::class, 'store'])->name('store');
                // Keep specific routes BEFORE dynamic {calendar}
                Route::get('/export', [AcademicCalendarController::class, 'export'])->name('export');
                Route::post('/import', [AcademicCalendarController::class, 'import'])->name('import');
                Route::post('/bulk-delete', [AcademicCalendarController::class, 'bulkDelete'])->name('bulk-delete');
                Route::get('/{calendar}', [AcademicCalendarController::class, 'show'])->name('show');
                Route::get('/{calendar}/edit', [AcademicCalendarController::class, 'edit'])->name('edit');
                Route::put('/{calendar}', [AcademicCalendarController::class, 'update'])->name('update');
                Route::delete('/{calendar}', [AcademicCalendarController::class, 'destroy'])->name('destroy');
            });

            // Academic Reports Module
            Route::prefix('reports')->name('reports.')->group(function () {
                Route::get('/dashboard', [AcademicReportController::class, 'dashboard'])->name('dashboard');
                Route::get('/', [AcademicReportController::class, 'index'])->name('index');
                Route::get('/create', [AcademicReportController::class, 'create'])->name('create');
                Route::post('/', [AcademicReportController::class, 'store'])->name('store');
                // Keep specific routes BEFORE dynamic {report}
                Route::get('/export', [AcademicReportController::class, 'export'])->name('export');
                Route::get('/download', [AcademicReportController::class, 'export'])->name('download');
                Route::post('/import', [AcademicReportController::class, 'import'])->name('import');
                Route::post('/bulk-delete', [AcademicReportController::class, 'bulkDelete'])->name('bulk-delete');
                Route::get('/{report}', [AcademicReportController::class, 'show'])->name('show');
                Route::get('/{report}/edit', [AcademicReportController::class, 'edit'])->name('edit');
                Route::put('/{report}', [AcademicReportController::class, 'update'])->name('update');
                Route::delete('/{report}', [AcademicReportController::class, 'destroy'])->name('destroy');
            });
        });
        
        // Documents
        Route::prefix('documents')->name('documents.')->group(function () {
            Route::prefix('idcard')->name('idcard.')->group(function () {
                Route::get('/dashboard', [IdCardController::class, 'dashboard'])->name('dashboard');
                Route::get('/', [IdCardController::class, 'index'])->name('index');
                Route::get('/create', [IdCardController::class, 'create'])->name('create');
                Route::post('/', [IdCardController::class, 'store'])->name('store');
                Route::get('/export', [IdCardController::class, 'export'])->name('export');
                Route::post('/import', [IdCardController::class, 'import'])->name('import');
                Route::post('/bulk-delete', [IdCardController::class, 'bulkDelete'])->name('bulk-delete');
                Route::get('/{idcard}/download', [IdCardController::class, 'download'])->name('download');
                Route::get('/{idcard}/print', [IdCardController::class, 'print'])->name('print');
                Route::get('/{idcard}', [IdCardController::class, 'show'])->name('show');
                Route::get('/{idcard}/edit', [IdCardController::class, 'edit'])->name('edit');
                Route::put('/{idcard}', [IdCardController::class, 'update'])->name('update');
                Route::delete('/{idcard}', [IdCardController::class, 'destroy'])->name('destroy');
            });
            Route::prefix('transfer-certificate')->name('transfer-certificate.')->group(function () {
                
                Route::get('/dashboard', [TransferCertificateController::class, 'dashboard'])->name('dashboard');
                Route::get('/', [TransferCertificateController::class, 'index'])->name('index');

                Route::get('/export', [TransferCertificateController::class, 'export'])->name('export');
                Route::post('/import', [TransferCertificateController::class, 'import'])->name('import');
               
                Route::get('/create', [TransferCertificateController::class, 'create'])->name('create');
                Route::post('/', [TransferCertificateController::class, 'store'])->name('store');
                Route::get('/{transfer_certificate}/download', [TransferCertificateController::class, 'download'])->name('download');
                Route::get('/{transfer_certificate}', [TransferCertificateController::class, 'show'])->name('show');
                Route::get('/{transfer_certificate}/edit', [TransferCertificateController::class, 'edit'])->name('edit');
                Route::put('/{transfer_certificate}', [TransferCertificateController::class, 'update'])->name('update');
                Route::delete('/{transfer_certificate}', [TransferCertificateController::class, 'destroy'])->name('destroy');
                Route::get('/{transfer_certificate}/print', [TransferCertificateController::class, 'print'])->name('print');
            });
            Route::prefix('leaving-certificate')->name('leaving-certificate.')->group(function () {
                Route::get('/dashboard', [LeavingCertificateController::class, 'dashboard'])->name('dashboard');
                Route::get('/', [LeavingCertificateController::class, 'index'])->name('index');
                Route::get('/export', [LeavingCertificateController::class, 'export'])->name('export');
                Route::post('/import', [LeavingCertificateController::class, 'import'])->name('import');
                Route::get('/create', [LeavingCertificateController::class, 'create'])->name('create');
                Route::post('/', [LeavingCertificateController::class, 'store'])->name('store');
                Route::get('/{leaving_certificate}/download', [LeavingCertificateController::class, 'download'])->name('download');
                Route::get('/{leaving_certificate}', [LeavingCertificateController::class, 'show'])->name('show');
                Route::get('/{leaving_certificate}/edit', [LeavingCertificateController::class, 'edit'])->name('edit');
                Route::put('/{leaving_certificate}', [LeavingCertificateController::class, 'update'])->name('update');
                Route::delete('/{leaving_certificate}', [LeavingCertificateController::class, 'destroy'])->name('destroy');
                Route::get('/{leaving_certificate}/print', [LeavingCertificateController::class, 'print'])->name('print');
            });
            Route::prefix('marksheet')->name('marksheet.')->group(function () {
                Route::get('/dashboard', [MarksheetController::class, 'dashboard'])->name('dashboard');
                Route::get('/', [MarksheetController::class, 'index'])->name('index');
                Route::get('/export', [MarksheetController::class, 'export'])->name('export');
                Route::post('/import', [MarksheetController::class, 'import'])->name('import');
                Route::get('/create', [MarksheetController::class, 'create'])->name('create');
                Route::post('/', [MarksheetController::class, 'store'])->name('store');
                Route::get('/{marksheet}/download', [MarksheetController::class, 'download'])->name('download');
                Route::get('/{marksheet}', [MarksheetController::class, 'show'])->name('show');
                Route::get('/{marksheet}/edit', [MarksheetController::class, 'edit'])->name('edit');
                Route::put('/{marksheet}', [MarksheetController::class, 'update'])->name('update');
                Route::delete('/{marksheet}', [MarksheetController::class, 'destroy'])->name('destroy');
                Route::get('/{marksheet}/print', [MarksheetController::class, 'print'])->name('print');
            });
            Route::prefix('experience-certificate')->name('experience-certificate.')->group(function () {
                Route::get('/dashboard', [ExperienceCertificateController::class, 'dashboard'])->name('dashboard');
                Route::get('/', [ExperienceCertificateController::class, 'index'])->name('index');
                Route::get('/export', [ExperienceCertificateController::class, 'export'])->name('export');
                Route::post('/import', [ExperienceCertificateController::class, 'import'])->name('import');
                Route::get('/create', [ExperienceCertificateController::class, 'create'])->name('create');
                Route::post('/', [ExperienceCertificateController::class, 'store'])->name('store');
                Route::get('/{experience_certificate}/download', [ExperienceCertificateController::class, 'download'])->name('download');
                Route::get('/{experience_certificate}', [ExperienceCertificateController::class, 'show'])->name('show');
                Route::get('/{experience_certificate}/edit', [ExperienceCertificateController::class, 'edit'])->name('edit');
                Route::put('/{experience_certificate}', [ExperienceCertificateController::class, 'update'])->name('update');
                Route::delete('/{experience_certificate}', [ExperienceCertificateController::class, 'destroy'])->name('destroy');
                Route::get('/{experience_certificate}/print', [ExperienceCertificateController::class, 'print'])->name('print');
            });
            Route::prefix('study-certificate')->name('study-certificate.')->group(function () {
                Route::get('/dashboard', [StudyCertificateController::class, 'dashboard'])->name('dashboard');
                Route::get('/', [StudyCertificateController::class, 'index'])->name('index');
                Route::get('/export', [StudyCertificateController::class, 'export'])->name('export');
                Route::post('/import', [StudyCertificateController::class, 'import'])->name('import');
                Route::get('/create', [StudyCertificateController::class, 'create'])->name('create');
                Route::post('/', [StudyCertificateController::class, 'store'])->name('store');
                Route::get('/{study_certificate}/download', [StudyCertificateController::class, 'download'])->name('download');
                Route::get('/{study_certificate}', [StudyCertificateController::class, 'show'])->name('show');
                Route::get('/{study_certificate}/edit', [StudyCertificateController::class, 'edit'])->name('edit');
                Route::put('/{study_certificate}', [StudyCertificateController::class, 'update'])->name('update');
                Route::delete('/{study_certificate}', [StudyCertificateController::class, 'destroy'])->name('destroy');
                Route::get('/{study_certificate}/print', [StudyCertificateController::class, 'print'])->name('print');
            });
            Route::prefix('conduct-certificate')->name('conduct-certificate.')->group(function () {
                Route::get('/dashboard', [ConductCertificateController::class, 'dashboard'])->name('dashboard');
                Route::get('/', [ConductCertificateController::class, 'index'])->name('index');
                Route::get('/export', [ConductCertificateController::class, 'export'])->name('export');
                Route::post('/import', [ConductCertificateController::class, 'import'])->name('import');
                Route::get('/create', [ConductCertificateController::class, 'create'])->name('create');
                Route::post('/', [ConductCertificateController::class, 'store'])->name('store');
                Route::get('/{conduct_certificate}/download', [ConductCertificateController::class, 'download'])->name('download');
                Route::get('/{conduct_certificate}', [ConductCertificateController::class, 'show'])->name('show');
                Route::get('/{conduct_certificate}/edit', [ConductCertificateController::class, 'edit'])->name('edit');
                Route::put('/{conduct_certificate}', [ConductCertificateController::class, 'update'])->name('update');
                Route::delete('/{conduct_certificate}', [ConductCertificateController::class, 'destroy'])->name('destroy');
                Route::get('/{conduct_certificate}/print', [ConductCertificateController::class, 'print'])->name('print');
            });
            Route::prefix('employee-conduct-certificate')->name('employee-conduct-certificate.')->group(function () {
                Route::get('/dashboard', [EmployeeConductCertificateController::class, 'dashboard'])->name('dashboard');
                Route::get('/', [EmployeeConductCertificateController::class, 'index'])->name('index');
                Route::get('/export', [EmployeeConductCertificateController::class, 'export'])->name('export');
                Route::post('/import', [EmployeeConductCertificateController::class, 'import'])->name('import');
                Route::get('/create', [EmployeeConductCertificateController::class, 'create'])->name('create');
                Route::post('/', [EmployeeConductCertificateController::class, 'store'])->name('store');
                Route::get('/{employee_conduct_certificate}/download', [EmployeeConductCertificateController::class, 'download'])->name('download');
                Route::get('/{employee_conduct_certificate}', [EmployeeConductCertificateController::class, 'show'])->name('show');
                Route::get('/{employee_conduct_certificate}/edit', [EmployeeConductCertificateController::class, 'edit'])->name('edit');
                Route::put('/{employee_conduct_certificate}', [EmployeeConductCertificateController::class, 'update'])->name('update');
                Route::delete('/{employee_conduct_certificate}', [EmployeeConductCertificateController::class, 'destroy'])->name('destroy');
                Route::get('/{employee_conduct_certificate}/print', [EmployeeConductCertificateController::class, 'print'])->name('print');
            });
            Route::prefix('bonafide-certificate')->name('bonafide-certificate.')->group(function () {
                Route::get('/dashboard', [BonafideCertificateController::class, 'dashboard'])->name('dashboard');
                Route::get('/', [BonafideCertificateController::class, 'index'])->name('index');
                Route::get('/export', [BonafideCertificateController::class, 'export'])->name('export');
                Route::post('/import', [BonafideCertificateController::class, 'import'])->name('import');
                Route::get('/create', [BonafideCertificateController::class, 'create'])->name('create');
                Route::post('/', [BonafideCertificateController::class, 'store'])->name('store');
                Route::get('/{bonafide_certificate}/download', [BonafideCertificateController::class, 'download'])->name('download');
                Route::get('/{bonafide_certificate}', [BonafideCertificateController::class, 'show'])->name('show');
                Route::get('/{bonafide_certificate}/edit', [BonafideCertificateController::class, 'edit'])->name('edit');
                Route::put('/{bonafide_certificate}', [BonafideCertificateController::class, 'update'])->name('update');
                Route::delete('/{bonafide_certificate}', [BonafideCertificateController::class, 'destroy'])->name('destroy');
                Route::get('/{bonafide_certificate}/print', [BonafideCertificateController::class, 'print'])->name('print');
            });
        });

        // Finance - Invoice
        Route::prefix('finance')->name('finance.')->group(function(){
            // Finance - Reports
            Route::prefix('reports')->name('reports.')->group(function(){
                Route::get('/dashboard', [\App\Http\Controllers\Admin\FinanceReportsController::class, 'dashboard'])->name('dashboard');
                Route::get('/', [\App\Http\Controllers\Admin\FinanceReportsController::class, 'index'])->name('index');
            });
            Route::prefix('invoice')->name('invoice.')->group(function(){
                Route::get('/dashboard', [InvoiceController::class, 'dashboard'])->name('dashboard');
                Route::get('/', [InvoiceController::class, 'index'])->name('index');
                Route::get('/create', [InvoiceController::class, 'create'])->name('create');
                Route::post('/', [InvoiceController::class, 'store'])->name('store');
                Route::get('/{invoice}', [InvoiceController::class, 'show'])->name('show');
                Route::get('/{invoice}/edit', [InvoiceController::class, 'edit'])->name('edit');
                Route::put('/{invoice}', [InvoiceController::class, 'update'])->name('update');
                Route::delete('/{invoice}', [InvoiceController::class, 'destroy'])->name('destroy');
                Route::get('/export', [InvoiceController::class, 'export'])->name('export');
                Route::post('/import', [InvoiceController::class, 'import'])->name('import');
            });

            // Finance - Student Payments
            Route::prefix('student-payments')->name('student-payments.')->group(function(){
                Route::get('/dashboard', [StudentPaymentController::class, 'dashboard'])->name('dashboard');
                Route::get('/export', [StudentPaymentController::class, 'export'])->name('export');
                Route::post('/import', [StudentPaymentController::class, 'import'])->name('import');
                Route::get('/', [StudentPaymentController::class, 'index'])->name('index');
                Route::get('/create', [StudentPaymentController::class, 'create'])->name('create');
                Route::post('/', [StudentPaymentController::class, 'store'])->name('store');
                Route::get('/{student_payment}', [StudentPaymentController::class, 'show'])->name('show');
                Route::get('/{student_payment}/edit', [StudentPaymentController::class, 'edit'])->name('edit');
                Route::put('/{student_payment}', [StudentPaymentController::class, 'update'])->name('update');
                Route::delete('/{student_payment}', [StudentPaymentController::class, 'destroy'])->name('destroy');
            });

            // Finance - Expenses
            Route::prefix('expenses')->name('expenses.')->group(function(){
                Route::get('/dashboard', [ExpenseController::class, 'dashboard'])->name('dashboard');
                
                Route::get('/export', [ExpenseController::class, 'export'])->name('export');
                Route::post('/import', [ExpenseController::class, 'import'])->name('import');
                
                Route::get('/', [ExpenseController::class, 'index'])->name('index');
                Route::get('/create', [ExpenseController::class, 'create'])->name('create');
                Route::post('/', [ExpenseController::class, 'store'])->name('store');
                Route::get('/{expense}', [ExpenseController::class, 'show'])->name('show');
                Route::get('/{expense}/edit', [ExpenseController::class, 'edit'])->name('edit');
                Route::put('/{expense}', [ExpenseController::class, 'update'])->name('update');
                Route::delete('/{expense}', [ExpenseController::class, 'destroy'])->name('destroy');
            });

            // Finance - Expense Categories
                Route::prefix('expense-categories')->name('expense-categories.')->group(function(){
                    Route::get('/dashboard', [ExpenseCategoryController::class, 'dashboard'])->name('dashboard');
                    
                    Route::get('/export', [ExpenseCategoryController::class, 'export'])->name('export');
                    Route::post('/import', [ExpenseCategoryController::class, 'import'])->name('import');
                    
                    Route::get('/', [ExpenseCategoryController::class, 'index'])->name('index');
                    Route::get('/create', [ExpenseCategoryController::class, 'create'])->name('create');
                    Route::post('/', [ExpenseCategoryController::class, 'store'])->name('store');
                    Route::get('/{expenseCategory}', [ExpenseCategoryController::class, 'show'])->name('show');
                    Route::get('/{expenseCategory}/edit', [ExpenseCategoryController::class, 'edit'])->name('edit');
                    Route::put('/{expenseCategory}', [ExpenseCategoryController::class, 'update'])->name('update');
                    Route::delete('/{expenseCategory}', [ExpenseCategoryController::class, 'destroy'])->name('destroy');
                });

            // Finance - Scholarships
            Route::prefix('scholarships')->name('scholarships.')->group(function(){
                Route::get('/dashboard', [ScholarshipController::class, 'dashboard'])->name('dashboard');
                Route::get('/', [ScholarshipController::class, 'index'])->name('index');
                Route::get('/create', [ScholarshipController::class, 'create'])->name('create');
                Route::post('/', [ScholarshipController::class, 'store'])->name('store');
                Route::get('/{scholarship}', [ScholarshipController::class, 'show'])->name('show');
                Route::get('/{scholarship}/edit', [ScholarshipController::class, 'edit'])->name('edit');
                Route::put('/{scholarship}', [ScholarshipController::class, 'update'])->name('update');
                Route::delete('/{scholarship}', [ScholarshipController::class, 'destroy'])->name('destroy');
                Route::get('/export', [ScholarshipController::class, 'export'])->name('export');
                Route::post('/import', [ScholarshipController::class, 'import'])->name('import');
            });

            // Finance - Fee Structure
            Route::prefix('fee-structure')->name('fee-structure.')->group(function(){
                Route::get('/dashboard', [FeeStructureController::class, 'dashboard'])->name('dashboard');
                Route::get('/', [FeeStructureController::class, 'index'])->name('index');
                Route::get('/create', [FeeStructureController::class, 'create'])->name('create');
                Route::post('/', [FeeStructureController::class, 'store'])->name('store');
                Route::get('/{feeStructure}', [FeeStructureController::class, 'show'])->name('show');
                Route::get('/{feeStructure}/edit', [FeeStructureController::class, 'edit'])->name('edit');
                Route::put('/{feeStructure}', [FeeStructureController::class, 'update'])->name('update');
                Route::delete('/{feeStructure}', [FeeStructureController::class, 'destroy'])->name('destroy');
                Route::get('/export', [FeeStructureController::class, 'export'])->name('export');
                Route::post('/import', [FeeStructureController::class, 'import'])->name('import');
                Route::patch('/{feeStructure}/toggle-status', [FeeStructureController::class, 'toggleStatus'])->name('toggle-status');
                Route::get('/get-by-class', [FeeStructureController::class, 'getByClass'])->name('get-by-class');
            });
        });

        // HR - Staff Management
        Route::prefix('hr')->name('hr.')->group(function(){

            Route::prefix('staff')->name('staff.')->group(function(){
                Route::get('/dashboard', [\App\Http\Controllers\Admin\StaffController::class, 'dashboard'])->name('dashboard');
                Route::get('/', [\App\Http\Controllers\Admin\StaffController::class, 'index'])->name('index');
                Route::get('/create', [\App\Http\Controllers\Admin\StaffController::class, 'create'])->name('create');
                Route::post('/', [\App\Http\Controllers\Admin\StaffController::class, 'store'])->name('store');
                Route::get('/{staff}', [\App\Http\Controllers\Admin\StaffController::class, 'show'])->name('show');
               
                Route::get('/{staff}/edit', [\App\Http\Controllers\Admin\StaffController::class, 'edit'])->name('edit');
                Route::put('/{staff}', [\App\Http\Controllers\Admin\StaffController::class, 'update'])->name('update');
                Route::delete('/{staff}', [\App\Http\Controllers\Admin\StaffController::class, 'destroy'])->name('destroy');
                Route::get('/export', [\App\Http\Controllers\Admin\StaffController::class, 'export'])->name('export');
                Route::post('/import', [\App\Http\Controllers\Admin\StaffController::class, 'import'])->name('import');
                Route::patch('/{staff}/toggle-status', [\App\Http\Controllers\Admin\StaffController::class, 'toggleStatus'])->name('toggle-status');
                Route::get('/get-by-department', [\App\Http\Controllers\Admin\StaffController::class, 'getByDepartment'])->name('get-by-department');
                Route::get('/get-by-designation', [\App\Http\Controllers\Admin\StaffController::class, 'getByDesignation'])->name('get-by-designation');
            });

            // HR - Payroll Management
            Route::prefix('payroll')->name('payroll.')->group(function(){
                Route::get('/dashboard', [\App\Http\Controllers\Admin\PayrollController::class, 'dashboard'])->name('dashboard');
                Route::get('/', [\App\Http\Controllers\Admin\PayrollController::class, 'index'])->name('index');
                Route::get('/create', [\App\Http\Controllers\Admin\PayrollController::class, 'create'])->name('create');
                Route::post('/', [\App\Http\Controllers\Admin\PayrollController::class, 'store'])->name('store');
                Route::get('/{payroll}', [\App\Http\Controllers\Admin\PayrollController::class, 'show'])->name('show');
                Route::get('/{payroll}/edit', [\App\Http\Controllers\Admin\PayrollController::class, 'edit'])->name('edit');
                Route::put('/{payroll}', [\App\Http\Controllers\Admin\PayrollController::class, 'update'])->name('update');
                Route::delete('/{payroll}', [\App\Http\Controllers\Admin\PayrollController::class, 'destroy'])->name('destroy');
                Route::get('/export', [\App\Http\Controllers\Admin\PayrollController::class, 'export'])->name('export');
                Route::post('/import', [\App\Http\Controllers\Admin\PayrollController::class, 'import'])->name('import');
                Route::patch('/{payroll}/toggle-status', [\App\Http\Controllers\Admin\PayrollController::class, 'toggleStatus'])->name('toggle-status');
                Route::get('/staff/{staff}', [\App\Http\Controllers\Admin\PayrollController::class, 'getByStaff'])->name('by-staff');
                Route::get('/period', [\App\Http\Controllers\Admin\PayrollController::class, 'getByPeriod'])->name('by-period');
            });

            // HR - Leave Management
            Route::prefix('leave-management')->name('leave-management.')->group(function(){
                Route::get('/dashboard', [\App\Http\Controllers\Admin\LeaveManagementController::class, 'dashboard'])->name('dashboard');
                Route::get('/', [\App\Http\Controllers\Admin\LeaveManagementController::class, 'index'])->name('index');
                Route::get('/create', [\App\Http\Controllers\Admin\LeaveManagementController::class, 'create'])->name('create');
                Route::post('/', [\App\Http\Controllers\Admin\LeaveManagementController::class, 'store'])->name('store');
                Route::get('/export', [\App\Http\Controllers\Admin\LeaveManagementController::class, 'export'])->name('export');
                Route::post('/import', [\App\Http\Controllers\Admin\LeaveManagementController::class, 'import'])->name('import');
                Route::get('/calendar', [\App\Http\Controllers\Admin\LeaveManagementController::class, 'calendar'])->name('calendar');
                Route::get('/staff/{staff}', [\App\Http\Controllers\Admin\LeaveManagementController::class, 'getByStaff'])->name('by-staff');
                Route::get('/period', [\App\Http\Controllers\Admin\LeaveManagementController::class, 'getByPeriod'])->name('by-period');
                Route::patch('/{leaveManagement}/toggle-status', [\App\Http\Controllers\Admin\LeaveManagementController::class, 'toggleStatus'])->name('toggle-status');
                Route::get('/{leaveManagement}', [\App\Http\Controllers\Admin\LeaveManagementController::class, 'show'])->name('show');
                Route::get('/{leaveManagement}/edit', [\App\Http\Controllers\Admin\LeaveManagementController::class, 'edit'])->name('edit');
                Route::put('/{leaveManagement}', [\App\Http\Controllers\Admin\LeaveManagementController::class, 'update'])->name('update');
                Route::delete('/{leaveManagement}', [\App\Http\Controllers\Admin\LeaveManagementController::class, 'destroy'])->name('destroy');
                Route::get('/data', [\App\Http\Controllers\Admin\LeaveManagementController::class, 'getData'])->name('data');
            });
        });

        // Exams
        Route::prefix('exams')->name('exams.')->group(function () {
            //Exam
            Route::prefix('exam')->name('exam.')->group(function () {
                Route::get('/dashboard', [ExamController::class, 'dashboard'])->name('dashboard');
                Route::get('/', [ExamController::class, 'index'])->name('index');
                Route::get('/create', [ExamController::class, 'create'])->name('create');
                Route::post('/', [ExamController::class, 'store'])->name('store');
                Route::get('/export', [ExamController::class, 'export'])->name('export');
                Route::post('/import', [ExamController::class, 'import'])->name('import');
                Route::get('/{exam}', [ExamController::class, 'show'])->name('show');
                Route::get('/{exam}/edit', [ExamController::class, 'edit'])->name('edit');
                Route::put('/{exam}', [ExamController::class, 'update'])->name('update');
                Route::delete('/{exam}', [ExamController::class, 'destroy'])->name('destroy');
            });
            // Grades
            Route::prefix('grades')->name('grades.')->group(function(){

                Route::get('/dashboard', [ExamGradeController::class, 'dashboard'])->name('dashboard');
                Route::get('/', [ExamGradeController::class, 'index'])->name('index');
                Route::get('/create', [ExamGradeController::class, 'create'])->name('create');
                Route::post('/', [ExamGradeController::class, 'store'])->name('store');
                Route::get('/{grade}/edit', [ExamGradeController::class, 'edit'])->name('edit');
                Route::put('/{grade}', [ExamGradeController::class, 'update'])->name('update');
                Route::put('/{grade}', [ExamGradeController::class, 'show'])->name('show');
                Route::delete('/{grade}', [ExamGradeController::class, 'destroy'])->name('destroy');
                Route::get('/export', [ExamGradeController::class, 'export'])->name('export');
                Route::post('/import', [ExamGradeController::class, 'import'])->name('import');
            });
            // Schedule
            Route::prefix('schedule')->name('schedule.')->group(function(){

                Route::get('/dashboard', [ExamScheduleController::class, 'dashboard'])->name('dashboard');
                Route::get('/', [ExamScheduleController::class, 'index'])->name('index');
                Route::get('/create', [ExamScheduleController::class, 'create'])->name('create');
                Route::post('/', [ExamScheduleController::class, 'store'])->name('store');
                Route::get('/export', [ExamScheduleController::class, 'export'])->name('export');
                Route::post('/import', [ExamScheduleController::class, 'import'])->name('import');
                Route::get('/{schedule}', [ExamScheduleController::class, 'show'])->name('show');
                Route::get('/{schedule}/edit', [ExamScheduleController::class, 'edit'])->name('edit');
                Route::put('/{schedule}', [ExamScheduleController::class, 'update'])->name('update');
                Route::delete('/{schedule}', [ExamScheduleController::class, 'destroy'])->name('destroy');
            });
            // Marks
            Route::prefix('marks')->name('marks.')->group(function(){

                Route::get('/dashboard', [ExamMarkController::class, 'dashboard'])->name('dashboard');
                Route::get('/', [ExamMarkController::class, 'index'])->name('index');
                Route::get('/create', [ExamMarkController::class, 'create'])->name('create');
                Route::post('/', [ExamMarkController::class, 'store'])->name('store');
                Route::get('/export', [ExamMarkController::class, 'export'])->name('export');
                Route::post('/import', [ExamMarkController::class, 'import'])->name('import');
                Route::get('/{mark}', [ExamMarkController::class, 'show'])->name('show');
                Route::get('/{mark}/edit', [ExamMarkController::class, 'edit'])->name('edit');
                Route::put('/{mark}', [ExamMarkController::class, 'update'])->name('update');
                Route::delete('/{mark}', [ExamMarkController::class, 'destroy'])->name('destroy');
                Route::get('/{mark}/print', [ExamMarkController::class, 'print'])->name('print');
                Route::get('/{mark}/download', [ExamMarkController::class, 'download'])->name('download');
            });
            // SMS
            Route::prefix('sms')->name('sms.')->group(function(){
                Route::get('/dashboard', [ExamSmsController::class, 'dashboard'])->name('dashboard');
                Route::get('/', [ExamSmsController::class, 'index'])->name('index');
                Route::get('/create', [ExamSmsController::class, 'create'])->name('create');
                Route::post('/', [ExamSmsController::class, 'store'])->name('store');
                // send must come before dynamic {sms}
                Route::post('/{sms}/send', [ExamSmsController::class, 'send'])->name('send');
                // recipients listing
                Route::get('/{sms}/recipients', [ExamSmsRecipientController::class, 'index'])->name('recipients.index');
                Route::get('/{sms}', [ExamSmsController::class, 'show'])->name('show');
                Route::get('/{sms}/edit', [ExamSmsController::class, 'edit'])->name('edit');
                Route::put('/{sms}', [ExamSmsController::class, 'update'])->name('update');
                Route::delete('/{sms}', [ExamSmsController::class, 'destroy'])->name('destroy');
            });
			// Tabulation
			Route::prefix('tabulation')->name('tabulation.')->group(function(){

				Route::get('/dashboard', [ExamTabulationController::class, 'dashboard'])->name('dashboard');
				Route::get('/', [ExamTabulationController::class, 'index'])->name('index');
				Route::get('/create', [ExamTabulationController::class, 'create'])->name('create');
				Route::post('/', [ExamTabulationController::class, 'store'])->name('store');
				Route::get('/export', [ExamTabulationController::class, 'export'])->name('export');
				Route::post('/import', [ExamTabulationController::class, 'import'])->name('import');
				Route::get('/{tabulation}', [ExamTabulationController::class, 'show'])->name('show');
				Route::get('/{tabulation}/edit', [ExamTabulationController::class, 'edit'])->name('edit');
				Route::put('/{tabulation}', [ExamTabulationController::class, 'update'])->name('update');
				Route::delete('/{tabulation}', [ExamTabulationController::class, 'destroy'])->name('destroy');
			});
			// Exam Attendance
			Route::prefix('attendance')->name('attendance.')->group(function(){
				Route::get('/dashboard', [ExamAttendanceController::class, 'dashboard'])->name('dashboard');
				Route::get('/', [ExamAttendanceController::class, 'index'])->name('index');
				Route::get('/create', [ExamAttendanceController::class, 'create'])->name('create');
				Route::post('/', [ExamAttendanceController::class, 'store'])->name('store');
				Route::get('/export', [ExamAttendanceController::class, 'export'])->name('export');
				Route::post('/import', [ExamAttendanceController::class, 'import'])->name('import');
				Route::get('/{attendance}', [ExamAttendanceController::class, 'show'])->name('show');
				Route::get('/{attendance}/edit', [ExamAttendanceController::class, 'edit'])->name('edit');
				Route::put('/{attendance}', [ExamAttendanceController::class, 'update'])->name('update');
				Route::delete('/{attendance}', [ExamAttendanceController::class, 'destroy'])->name('destroy');
			});
			// Progress Card
			Route::prefix('progress-card')->name('progress-card.')->group(function(){
				Route::get('/dashboard', [ExamProgressCardController::class, 'dashboard'])->name('dashboard');
				Route::get('/', [ExamProgressCardController::class, 'index'])->name('index');
				Route::get('/create', [ExamProgressCardController::class, 'create'])->name('create');
				Route::post('/', [ExamProgressCardController::class, 'store'])->name('store');
				Route::get('/export', [ExamProgressCardController::class, 'export'])->name('export');
				Route::post('/import', [ExamProgressCardController::class, 'import'])->name('import');
				Route::get('/{progress_card}', [ExamProgressCardController::class, 'show'])->name('show');
				Route::get('/{progress_card}/edit', [ExamProgressCardController::class, 'edit'])->name('edit');
				Route::put('/{progress_card}', [ExamProgressCardController::class, 'update'])->name('update');
				Route::delete('/{progress_card}', [ExamProgressCardController::class, 'destroy'])->name('destroy');
			});
			// Question Bank - Categories
			Route::prefix('question-bank/categories')->name('question-bank.categories.')->group(function(){
				Route::get('/', [QuestionCategoryController::class, 'index'])->name('index');
				Route::get('/create', [QuestionCategoryController::class, 'create'])->name('create');
				Route::post('/', [QuestionCategoryController::class, 'store'])->name('store');
				Route::get('/export', [QuestionCategoryController::class, 'export'])->name('export');
				Route::post('/import', [QuestionCategoryController::class, 'import'])->name('import');
				Route::get('/{category}', [QuestionCategoryController::class, 'show'])->name('show');
				Route::get('/{category}/edit', [QuestionCategoryController::class, 'edit'])->name('edit');
				Route::put('/{category}', [QuestionCategoryController::class, 'update'])->name('update');
				Route::delete('/{category}', [QuestionCategoryController::class, 'destroy'])->name('destroy');
			});
			// Question Bank - Questions
			Route::prefix('question-bank/questions')->name('question-bank.questions.')->group(function(){
				Route::get('/', [QuestionController::class, 'index'])->name('index');
				Route::get('/create', [QuestionController::class, 'create'])->name('create');
				Route::post('/', [QuestionController::class, 'store'])->name('store');
				Route::get('/export', [QuestionController::class, 'export'])->name('export');
				Route::post('/import', [QuestionController::class, 'import'])->name('import');
				Route::get('/{question}', [QuestionController::class, 'show'])->name('show');
				Route::get('/{question}/edit', [QuestionController::class, 'edit'])->name('edit');
				Route::put('/{question}', [QuestionController::class, 'update'])->name('update');
				Route::delete('/{question}', [QuestionController::class, 'destroy'])->name('destroy');
			});
			// Question Bank - Papers
			Route::prefix('question-bank/papers')->name('question-bank.papers.')->group(function(){
				Route::get('/', [QuestionPaperController::class, 'index'])->name('index');
				Route::get('/create', [QuestionPaperController::class, 'create'])->name('create');
				Route::post('/', [QuestionPaperController::class, 'store'])->name('store');
				Route::get('/{paper}', [QuestionPaperController::class, 'show'])->name('show');
				Route::get('/{paper}/edit', [QuestionPaperController::class, 'edit'])->name('edit');
			});
			
        });

        // Online Exam Routes (outside of exams prefix to match sidebar paths)
        Route::prefix('online-exam')->name('online-exam.')->group(function () {
           
            Route::get('/', [OnlineExamController::class, 'index'])->name('index');
            Route::get('/create', [OnlineExamController::class, 'create'])->name('create');
            Route::post('/', [OnlineExamController::class, 'store'])->name('store');
           
            // Results Index (all online exams)
            Route::get('/results', [OnlineExamResultController::class, 'index'])->name('results.index');

            // Questions CRUD
            Route::prefix('questions')->name('questions.')->group(function(){
                Route::get('/', [OnlineExamController::class, 'questionsIndex'])->name('index');
                Route::get('/create', [OnlineExamController::class, 'questionsCreate'])->name('create');
                Route::post('/', [OnlineExamController::class, 'questionsStore'])->name('store');
                Route::get('/{question}/edit', [OnlineExamController::class, 'questionsEdit'])->name('edit');
                Route::put('/{question}', [OnlineExamController::class, 'questionsUpdate'])->name('update');
                Route::delete('/{question}', [OnlineExamController::class, 'questionsDestroy'])->name('destroy');
            });
            // Manage
            Route::get('/manage', [OnlineExamController::class, 'manage'])->name('manage');
            Route::post('/manage/bulk-action', [OnlineExamController::class, 'bulkAction'])->name('manage.bulk');
            Route::post('/{onlineExam}/duplicate', [OnlineExamController::class, 'duplicate'])->name('duplicate');
            Route::get('/{onlineExam}', [OnlineExamController::class, 'show'])->name('show');
            Route::get('/{onlineExam}/edit', [OnlineExamController::class, 'edit'])->name('edit');
            Route::put('/{onlineExam}', [OnlineExamController::class, 'update'])->name('update');
            Route::delete('/{onlineExam}', [OnlineExamController::class, 'destroy'])->name('destroy');
            Route::patch('/{onlineExam}/publish', [OnlineExamController::class, 'publish'])->name('publish');
            Route::patch('/{onlineExam}/cancel', [OnlineExamController::class, 'cancel'])->name('cancel');
            Route::get('/{onlineExam}/results', [OnlineExamController::class, 'results'])->name('results');
            
            // AJAX routes
            Route::get('/ajax/questions-by-category', [OnlineExamController::class, 'getQuestionsByCategory'])->name('questions-by-category');
            Route::get('/ajax/sections-by-class', [OnlineExamController::class, 'getSectionsByClass'])->name('sections-by-class');

            // Attempt detail and download
            Route::get('/attempt/{attempt}/details', [OnlineExamResultController::class, 'attemptDetails'])->name('attempt.details');
            Route::get('/attempt/{attempt}/download', [OnlineExamResultController::class, 'attemptDownload'])->name('attempt.download');
        });

         // AI
         Route::prefix('ai')->name('ai.')->group(function(){
            // AI Paper Generator
            Route::prefix('paper-generator')->name('paper-generator.')->group(function () {
                Route::get('/', [PaperGeneratorController::class, 'index'])->name('index');
                Route::get('create', [PaperGeneratorController::class, 'create'])->name('create');
                Route::get('datatable', [PaperGeneratorController::class, 'datatable'])->name('datatable');
                Route::post('/', [PaperGeneratorController::class, 'store'])->name('store');
                Route::get('{paper}', [PaperGeneratorController::class, 'show'])->name('show');
                Route::get('{paper}/download', [PaperGeneratorController::class, 'downloadPdf'])->name('download');
                Route::delete('{paper}', [PaperGeneratorController::class, 'destroy'])->name('destroy');
                Route::get('{paper}/to-question-paper', [PaperGeneratorController::class, 'createQuestionBankPaper'])->name('to-question-paper');
            });
            // Performance Prediction
            Route::get('performance-prediction', [PerformancePredictionController::class, 'index'])->name('performance-prediction.index');
            Route::post('performance-prediction/predict', [PerformancePredictionController::class, 'predict'])->name('performance-prediction.predict');
            Route::get('performance-prediction/dashboard', [PerformancePredictionController::class, 'dashboard'])->name('performance-prediction.dashboard');

            // Chatbot
            Route::get('chatbot', [ChatbotController::class, 'index'])->name('chatbot.index');
            Route::post('chatbot/message', [ChatbotController::class, 'message'])->name('chatbot.message');
            Route::post('chatbot/reset', [ChatbotController::class, 'reset'])->name('chatbot.reset');

            // Plagiarism Checker
            Route::get('plagiarism-checker', [PlagiarismCheckerController::class, 'index'])->name('plagiarism-checker.index');
            Route::post('plagiarism-checker/check', [PlagiarismCheckerController::class, 'check'])->name('plagiarism-checker.check');
            Route::get('plagiarism-checker/history', [PlagiarismCheckerController::class, 'history'])->name('plagiarism-checker.history');
            Route::post('plagiarism-checker/clear-history', [PlagiarismCheckerController::class, 'clearHistory'])->name('plagiarism-checker.clear-history');

            // Route::get('/chatbot', [AIChatbotController::class, 'index'])->name('chatbot.index');
            // Route::post('/chatbot/send', [AIChatbotController::class, 'sendMessage'])->name('chatbot.send');
        });

                // Result Announcements
        Route::prefix('result-announcement')->name('result-announcement.')->group(function () {
            Route::prefix('announcement')->name('announcement.')->group(function () {
                Route::get('/', [ResultAnnouncementController::class, 'index'])->name('index');
                Route::get('/create', [ResultAnnouncementController::class, 'create'])->name('create');
                Route::post('/', [ResultAnnouncementController::class, 'store'])->name('store');
                Route::get('/{resultAnnouncement}', [ResultAnnouncementController::class, 'show'])->name('show');
                Route::get('/{resultAnnouncement}/edit', [ResultAnnouncementController::class, 'edit'])->name('edit');
                Route::put('/{resultAnnouncement}', [ResultAnnouncementController::class, 'update'])->name('update');
                Route::delete('/{resultAnnouncement}', [ResultAnnouncementController::class, 'destroy'])->name('destroy');
                Route::patch('/{resultAnnouncement}/publish', [ResultAnnouncementController::class, 'publish'])->name('publish');
                Route::patch('/{resultAnnouncement}/archive', [ResultAnnouncementController::class, 'archive'])->name('archive');
                Route::post('/{resultAnnouncement}/send-notifications', [ResultAnnouncementController::class, 'sendNotifications'])->name('send-notifications');
            });

            // Result Publications
            Route::prefix('publications')->name('publications.')->group(function () {
                Route::get('/', [ResultPublicationController::class, 'index'])->name('index');
                Route::get('/create', [ResultPublicationController::class, 'create'])->name('create');
                Route::post('/', [ResultPublicationController::class, 'store'])->name('store');
                Route::get('/{publication}', [ResultPublicationController::class, 'show'])->name('show');
                Route::get('/{publication}/edit', [ResultPublicationController::class, 'edit'])->name('edit');
                Route::put('/{publication}', [ResultPublicationController::class, 'update'])->name('update');
                Route::delete('/{publication}', [ResultPublicationController::class, 'destroy'])->name('destroy');
                Route::patch('/{publication}/publish', [ResultPublicationController::class, 'publish'])->name('publish');
                Route::patch('/{publication}/archive', [ResultPublicationController::class, 'archive'])->name('archive');
                Route::patch('/{publication}/toggle-featured', [ResultPublicationController::class, 'toggleFeatured'])->name('toggle-featured');
                Route::post('/{publication}/send-notifications', [ResultPublicationController::class, 'sendNotifications'])->name('send-notifications');
                Route::post('/{publication}/generate-pdf', [ResultPublicationController::class, 'generatePdf'])->name('generate-pdf');
                Route::get('/{publication}/download', [ResultPublicationController::class, 'download'])->name('download');
            });

            // Result Notifications
            Route::prefix('notification')->name('notification.')->group(function () {
                Route::get('/', [ResultNotificationController::class, 'index'])->name('index');
                Route::post('/', [ResultNotificationController::class, 'store'])->name('store');
                Route::post('/{notification}/send', [ResultNotificationController::class, 'send'])->name('send');
            });

            // Result Statistics
            Route::prefix('statistics')->name('statistics.')->group(function () {
                Route::get('/dashboard', [ResultStatisticsController::class, 'dashboard'])->name('dashboard');
                Route::get('/', [ResultStatisticsController::class, 'index'])->name('index');
                Route::get('/create', [ResultStatisticsController::class, 'create'])->name('create');
                Route::post('/', [ResultStatisticsController::class, 'store'])->name('store');
                Route::get('/{statistic}', [ResultStatisticsController::class, 'show'])->name('show');
                Route::get('/{statistic}/edit', [ResultStatisticsController::class, 'edit'])->name('edit');
                Route::put('/{statistic}', [ResultStatisticsController::class, 'update'])->name('update');
                Route::delete('/{statistic}', [ResultStatisticsController::class, 'destroy'])->name('destroy');
            });
        });
        
        Route::prefix('attendance')->name('attendance.')->group(function () {

            Route::prefix('staff')->name('staff.')->group(function () {

                Route::get('/', [StaffAttendanceController::class, 'index'])->name('index');
                Route::get('/dashboard', [StaffAttendanceController::class, 'dashboard'])->name('dashboard');
                Route::get('/create', [StaffAttendanceController::class, 'create'])->name('create');
                Route::post('/', [StaffAttendanceController::class, 'store'])->name('store');
                Route::get('/{staffAttendance}', [StaffAttendanceController::class, 'show'])->name('show'); // 👈 add this
                Route::get('/{staffAttendance}/edit', [StaffAttendanceController::class, 'edit'])->name('edit');
                Route::put('/{staffAttendance}', [StaffAttendanceController::class, 'update'])->name('update');
                Route::delete('/{staffAttendance}', [StaffAttendanceController::class, 'destroy'])->name('destroy');
                Route::get('/export', [StaffAttendanceController::class, 'export'])->name('export');
                Route::post('/import', [StaffAttendanceController::class, 'import'])->name('import');
           });

            // Bulk Attendance
            Route::prefix('bulk')->name('bulk.')->group(function(){
                Route::get('/', [BulkAttendanceController::class, 'index'])->name('index');
                Route::get('/create', [BulkAttendanceController::class, 'create'])->name('create');
                Route::post('/', [BulkAttendanceController::class, 'store'])->name('store');
                Route::get('/export-template', [BulkAttendanceController::class, 'exportTemplate'])->name('export-template');
                Route::get('/export-day', [BulkAttendanceController::class, 'exportDay'])->name('export-day');
            });
             
            // RFID Attendance
            Route::prefix('rfid')->name('rfid.')->group(function(){
                Route::get('/', [RfidAttendanceController::class, 'index'])->name('index');
                Route::get('/dashboard', [RfidAttendanceController::class, 'dashboard'])->name('dashboard');
                Route::get('/create', [RfidAttendanceController::class, 'create'])->name('create');
                Route::post('/', [RfidAttendanceController::class, 'store'])->name('store');
                Route::get('/{rfid}/edit', [RfidAttendanceController::class, 'edit'])->name('edit');
                Route::put('/{rfid}', [RfidAttendanceController::class, 'update'])->name('update');
                Route::delete('/{rfid}', [RfidAttendanceController::class, 'destroy'])->name('destroy');
                Route::get('/export', [RfidAttendanceController::class, 'export'])->name('export');
                Route::post('/import', [RfidAttendanceController::class, 'import'])->name('import');
            });

            // Attendance Reports
            Route::prefix('reports')->name('reports.')->group(function(){
                Route::get('/', [AttendanceReportsController::class, 'index'])->name('index');
                Route::get('/dashboard', [AttendanceReportsController::class, 'dashboard'])->name('dashboard');
                Route::get('/export', [AttendanceReportsController::class, 'export'])->name('export');
            });
            
        });
                
        // Communications Routes
        Route::prefix('communications')->name('communications.')->middleware(['auth'])->group(function () {
            Route::prefix('noticeboard')->name('noticeboard.')->group(function(){
                Route::get('/', [NoticeboardController::class, 'index'])->name('index');
                Route::get('/dashboard', [NoticeboardController::class, 'dashboard'])->name('dashboard');
                Route::get('/create', [NoticeboardController::class, 'create'])->name('create');
                Route::post('/', [NoticeboardController::class, 'store'])->name('store');
                Route::get('/{id}', [NoticeboardController::class, 'show'])->name('show');
                Route::get('/{id}/edit', [NoticeboardController::class, 'edit'])->name('edit');
                Route::put('/{id}', [NoticeboardController::class, 'update'])->name('update');
                Route::delete('/{id}', [NoticeboardController::class, 'destroy'])->name('delete');
                
                // Additional Actions
                Route::post('/{id}/toggle-pin', [NoticeboardController::class, 'togglePin'])->name('toggle-pin');
                Route::post('/{id}/toggle-feature', [NoticeboardController::class, 'toggleFeature'])->name('toggle-feature');
                Route::post('/{id}/publish', [NoticeboardController::class, 'publish'])->name('publish');
                Route::post('/{id}/archive', [NoticeboardController::class, 'archive'])->name('archive');
                Route::post('/{id}/duplicate', [NoticeboardController::class, 'duplicate'])->name('duplicate');
                
                // Bulk Actions
                Route::post('/bulk-action', [NoticeboardController::class, 'bulkAction'])->name('bulk-action');
                
                // Export & Statistics
                Route::get('/export', [NoticeboardController::class, 'export'])->name('export');
                Route::get('/statistics', [NoticeboardController::class, 'statistics'])->name('statistics');

            });

            // Communications Index
            Route::get('/', function () {
                return redirect()->route('admin.communications.noticeboard.index');
            })->name('index');

            // Messages Routes
            Route::prefix('messages')->name('messages.')->group(function(){
                Route::get('/', [MessagesController::class, 'index'])->name('index');
                Route::get('/dashboard', [MessagesController::class, 'dashboard'])->name('dashboard');
                Route::get('/sent', [MessagesController::class, 'sent'])->name('sent');
                Route::get('/drafts', [MessagesController::class, 'drafts'])->name('drafts');
                Route::get('/create', [MessagesController::class, 'create'])->name('create');
                Route::post('/', [MessagesController::class, 'store'])->name('store');
                Route::get('/{id}', [MessagesController::class, 'show'])->name('show');
                Route::get('/{id}/edit', [MessagesController::class, 'edit'])->name('edit');
                Route::put('/{id}', [MessagesController::class, 'update'])->name('update');
                Route::delete('/{id}', [MessagesController::class, 'destroy'])->name('destroy');
                
                // Additional Actions
                Route::post('/{id}/toggle-star', [MessagesController::class, 'toggleStar'])->name('toggle-star');
                Route::post('/{id}/toggle-important', [MessagesController::class, 'toggleImportant'])->name('toggle-important');
                Route::post('/{id}/acknowledge', [MessagesController::class, 'acknowledge'])->name('acknowledge');
                Route::get('/{id}/download-attachment/{index}', [MessagesController::class, 'downloadAttachment'])->name('download-attachment');
                
                // Bulk Actions
                Route::post('/bulk-action', [MessagesController::class, 'bulkAction'])->name('bulk-action');
                
                // Search
                Route::get('/search', [MessagesController::class, 'search'])->name('search');
            });

                // SMS Routes
                Route::prefix('sms')->name('sms.')->group(function(){
                    Route::get('/', [SmsController::class, 'index'])->name('index');
                    Route::get('/dashboard', [SmsController::class, 'dashboard'])->name('dashboard');
                    Route::get('/create', [SmsController::class, 'create'])->name('create');
                    Route::post('/', [SmsController::class, 'store'])->name('store');
                    Route::get('/{id}', [SmsController::class, 'show'])->name('show');
                    Route::get('/{id}/edit', [SmsController::class, 'edit'])->name('edit');
                    Route::put('/{id}', [SmsController::class, 'update'])->name('update');
                    Route::delete('/{id}', [SmsController::class, 'destroy'])->name('destroy');
                    
                    // Additional Actions
                    Route::post('/{id}/send-now', [SmsController::class, 'sendNow'])->name('send-now');
                    Route::post('/{id}/retry', [SmsController::class, 'retry'])->name('retry');
                    
                    // Recipient Suggestions
                    Route::get('/recipient-suggestions', [SmsController::class, 'getRecipientSuggestions'])->name('recipient-suggestions');
                    
                    // Template Content
                    Route::get('/templates/{id}', [SmsController::class, 'getTemplate'])->name('templates.show');
                    
                    // Statistics
                    Route::get('/statistics', [SmsController::class, 'getStatistics'])->name('statistics');
                });

                            // Email Templates Routes
                Route::prefix('email-templates')->name('email-templates.')->group(function(){
                    Route::get('/', [EmailTemplateController::class, 'index'])->name('index');
                    Route::get('/create', [EmailTemplateController::class, 'create'])->name('create');
                    Route::post('/', [EmailTemplateController::class, 'store'])->name('store');
                    Route::get('/{id}', [EmailTemplateController::class, 'show'])->name('show');
                    Route::get('/{id}/edit', [EmailTemplateController::class, 'edit'])->name('edit');
                    Route::put('/{id}', [EmailTemplateController::class, 'update'])->name('update');
                    Route::delete('/{id}', [EmailTemplateController::class, 'destroy'])->name('destroy');
                    
                    // Additional Actions
                    Route::post('/{id}/toggle-status', [EmailTemplateController::class, 'toggleStatus'])->name('toggle-status');
                    Route::post('/{id}/duplicate', [EmailTemplateController::class, 'duplicate'])->name('duplicate');
                    Route::get('/{id}/preview', [EmailTemplateController::class, 'preview'])->name('preview');
                });

                // Newsletter Routes
                Route::prefix('newsletter')->name('newsletter.')->group(function(){
                    Route::get('/', [NewsletterController::class, 'index'])->name('index');
                    Route::get('/dashboard', [NewsletterController::class, 'dashboard'])->name('dashboard');
                    Route::get('/create', [NewsletterController::class, 'create'])->name('create');
                    Route::post('/', [NewsletterController::class, 'store'])->name('store');
                    Route::get('/{id}', [NewsletterController::class, 'show'])->name('show');
                    Route::get('/{id}/edit', [NewsletterController::class, 'edit'])->name('edit');
                    Route::put('/{id}', [NewsletterController::class, 'update'])->name('update');
                    Route::delete('/{id}', [NewsletterController::class, 'destroy'])->name('destroy');
                    
                    // Additional Actions
                    Route::post('/{id}/send-now', [NewsletterController::class, 'sendNow'])->name('send-now');
                    Route::post('/{id}/schedule', [NewsletterController::class, 'schedule'])->name('schedule');
                    Route::post('/{id}/cancel-schedule', [NewsletterController::class, 'cancelSchedule'])->name('cancel-schedule');
                    Route::post('/{id}/duplicate', [NewsletterController::class, 'duplicate'])->name('duplicate');
                    Route::get('/{id}/preview', [NewsletterController::class, 'preview'])->name('preview');
                    Route::get('/{id}/statistics', [NewsletterController::class, 'getStatistics'])->name('statistics');
                    
                    // Bulk Actions
                    Route::post('/bulk-action', [NewsletterController::class, 'bulkAction'])->name('bulk-action');
                });

         

            // Messages Routes with shorter names for backward compatibility
            Route::prefix('messages')->name('messages.')->group(function(){
                Route::get('/', [MessagesController::class, 'index'])->name('index');
                Route::get('/dashboard', [MessagesController::class, 'dashboard'])->name('dashboard');
                Route::get('/sent', [MessagesController::class, 'sent'])->name('sent');
                Route::get('/drafts', [MessagesController::class, 'drafts'])->name('drafts');
                Route::get('/create', [MessagesController::class, 'create'])->name('create');
                Route::post('/', [MessagesController::class, 'store'])->name('store');
                Route::get('/{id}', [MessagesController::class, 'show'])->name('show');
                Route::get('/{id}/edit', [MessagesController::class, 'edit'])->name('edit');
                Route::put('/{id}', [MessagesController::class, 'update'])->name('update');
                Route::delete('/{id}', [MessagesController::class, 'destroy'])->name('destroy');
                
                // Additional Actions
                Route::post('/{id}/toggle-star', [MessagesController::class, 'toggleStar'])->name('toggle-star');
                Route::post('/{id}/toggle-important', [MessagesController::class, 'toggleImportant'])->name('toggle-important');
                Route::post('/{id}/acknowledge', [MessagesController::class, 'acknowledge'])->name('acknowledge');
                Route::get('/{id}/download-attachment/{index}', [MessagesController::class, 'downloadAttachment'])->name('download-attachment');
                
                // Bulk Actions
                Route::post('/bulk-action', [MessagesController::class, 'bulkAction'])->name('bulk-action');
                
                // Search
                Route::get('/search', [MessagesController::class, 'search'])->name('search');
                });
            });
            // Transport Routes
            Route::prefix('transport')->name('transport.')->group(function(){
                Route::prefix('tproutes')->name('tproutes.')->group(function(){
                    Route::get('/', [TransportRouteController::class, 'index'])->name('index');
                    Route::get('/create', [TransportRouteController::class, 'create'])->name('create');
                    Route::post('/', [TransportRouteController::class, 'store'])->name('store');
                    Route::get('/{route}', [TransportRouteController::class, 'show'])->name('show');
                    Route::get('/{route}/edit', [TransportRouteController::class, 'edit'])->name('edit');
                    Route::put('/{route}', [TransportRouteController::class, 'update'])->name('update');
                    Route::delete('/{route}', [TransportRouteController::class, 'destroy'])->name('destroy');
                    
                    // Additional Actions
                    Route::post('/{route}/toggle-status', [TransportRouteController::class, 'toggleStatus'])->name('toggle-status');
                    Route::post('/{route}/duplicate', [TransportRouteController::class, 'duplicate'])->name('duplicate');
                    Route::get('/statistics', [TransportRouteController::class, 'getStatistics'])->name('statistics');
                    
                    // Bulk Actions
                    Route::post('/bulk-action', [TransportRouteController::class, 'bulkAction'])->name('bulk-action');
                });

                // Transport Vehicles
                Route::prefix('vehicles')->name('vehicles.')->group(function(){
                    //Core Funtions
                    Route::get('/', [TransportVehicleController::class, 'index'])->name('index');
                    Route::get('/dashboard', [TransportVehicleController::class, 'dashboard'])->name('dashboard');
                    Route::get('/create', [TransportVehicleController::class, 'create'])->name('create');
                    Route::post('/', [TransportVehicleController::class, 'store'])->name('store');
                    Route::get('/{vehicle}', [TransportVehicleController::class, 'show'])->name('show');
                    Route::get('/{vehicle}/edit', [TransportVehicleController::class, 'edit'])->name('edit');
                    Route::put('/{vehicle}', [TransportVehicleController::class, 'update'])->name('update');
                    Route::delete('/{vehicle}', [TransportVehicleController::class, 'destroy'])->name('destroy');   
                    //Additional Actions
                    Route::post('/{vehicle}/toggle-status', [TransportVehicleController::class, 'toggleStatus'])->name('toggle-status');
                    Route::post('/{vehicle}/duplicate', [TransportVehicleController::class, 'duplicate'])->name('duplicate');
                    Route::get('/statistics', [TransportVehicleController::class, 'getStatistics'])->name('statistics');
                            //Bulk Actions
                        Route::post('/bulk-action', [TransportVehicleController::class, 'bulkAction'])->name('bulk-action');
                        Route::post('/bulk-delete', [TransportVehicleController::class, 'bulkDelete'])->name('bulkDelete');
                });

                // Transport Assignments
                Route::prefix('assign')->name('assign.')->group(function(){
                    // Core Functions
                    Route::get('/', [TransportAssignmentController::class, 'index'])->name('index');
                    Route::get('/dashboard', [TransportAssignmentController::class, 'dashboard'])->name('dashboard');
                    Route::get('/create', [TransportAssignmentController::class, 'create'])->name('create');
                    Route::post('/', [TransportAssignmentController::class, 'store'])->name('store');
                    Route::get('/{assignment}', [TransportAssignmentController::class, 'show'])->name('show');
                    Route::get('/{assignment}/edit', [TransportAssignmentController::class, 'edit'])->name('edit');
                    Route::put('/{assignment}', [TransportAssignmentController::class, 'update'])->name('update');
                    Route::delete('/{assignment}', [TransportAssignmentController::class, 'destroy'])->name('destroy');
                    
                    // Additional Actions
                    Route::post('/{assignment}/toggle-status', [TransportAssignmentController::class, 'toggleStatus'])->name('toggle-status');
                    Route::post('/{assignment}/duplicate', [TransportAssignmentController::class, 'duplicate'])->name('duplicate');
                    Route::get('/statistics', [TransportAssignmentController::class, 'getStatistics'])->name('statistics');
                    
                    // Bulk Actions
                    Route::post('/bulk-action', [TransportAssignmentController::class, 'bulkAction'])->name('bulk-action');
                    Route::post('/bulk-delete', [TransportAssignmentController::class, 'bulkDelete'])->name('bulkDelete');
                });

                // Transport Drivers
                Route::prefix('drivers')->name('drivers.')->group(function(){
                    // Core Functions
                    Route::get('/', [TransportDriverController::class, 'index'])->name('index');
                    Route::get('/dashboard', [TransportDriverController::class, 'dashboard'])->name('dashboard');
                    Route::get('/create', [TransportDriverController::class, 'create'])->name('create');
                    Route::post('/', [TransportDriverController::class, 'store'])->name('store');
                    Route::get('/{driver}', [TransportDriverController::class, 'show'])->name('show');
                    Route::get('/{driver}/edit', [TransportDriverController::class, 'edit'])->name('edit');
                    Route::put('/{driver}', [TransportDriverController::class, 'update'])->name('update');
                    Route::delete('/{driver}', [TransportDriverController::class, 'destroy'])->name('destroy');
                    
                    // Additional Actions
                    Route::post('/{driver}/toggle-status', [TransportDriverController::class, 'toggleStatus'])->name('toggle-status');
                    Route::post('/{driver}/duplicate', [TransportDriverController::class, 'duplicate'])->name('duplicate');
                    Route::get('/statistics', [TransportDriverController::class, 'getStatistics'])->name('statistics');
                    
                    // Bulk Actions
                    Route::post('/bulk-action', [TransportDriverController::class, 'bulkAction'])->name('bulk-action');
                });

                // Transport Tracking
                Route::prefix('tracking')->name('tracking.')->group(function(){
                                // Core Functions
                    Route::get('/', [TransportTrackingController::class, 'index'])->name('index');
                    Route::get('/dashboard', [TransportTrackingController::class, 'dashboard'])->name('dashboard');
                    Route::get('/create', [TransportTrackingController::class, 'create'])->name('create');
                    Route::post('/', [TransportTrackingController::class, 'store'])->name('store');
                    Route::get('/{tracking}', [TransportTrackingController::class, 'show'])->name('show');
                    Route::get('/{tracking}/edit', [TransportTrackingController::class, 'edit'])->name('edit');
                    Route::put('/{tracking}', [TransportTrackingController::class, 'update'])->name('update');
                    Route::delete('/{tracking}', [TransportTrackingController::class, 'destroy'])->name('destroy');
                    
                    // Additional Actions
                    Route::post('/{tracking}/toggle-status', [TransportTrackingController::class, 'toggleStatus'])->name('toggle-status');
                    Route::post('/{tracking}/duplicate', [TransportTrackingController::class, 'duplicate'])->name('duplicate');
                    Route::get('/statistics', [TransportTrackingController::class, 'getStatisticsData'])->name('statistics');
                    
                    // Bulk Actions
                    Route::post('/bulk-action', [TransportTrackingController::class, 'bulkAction'])->name('bulk-action');
                    
                    // Live Tracking
                    Route::get('/live', [TransportTrackingController::class, 'liveTracking'])->name('live');
                    Route::get('/live/data', [TransportTrackingController::class, 'getLiveData'])->name('live.data');
                    Route::post('/live/update-location', [TransportTrackingController::class, 'updateLiveLocation'])->name('live.update-location');
                    Route::post('/live/start', [TransportTrackingController::class, 'startLiveTracking'])->name('live.start');
                    Route::post('/live/stop', [TransportTrackingController::class, 'stopLiveTracking'])->name('live.stop');
                    Route::post('/live/simulate', [TransportTrackingController::class, 'simulateLiveTracking'])->name('live.simulate');
                });
            });

            // Hostel Management
            Route::prefix('accommodation')->name('accommodation.')->group(function(){
                // Hostel Categories
                Route::prefix('categories')->name('categories.')->group(function(){
                    // Core Functions
                    Route::get('/', [HostelCategoryController::class, 'index'])->name('index');
                    Route::get('/dashboard', [HostelCategoryController::class, 'dashboard'])->name('dashboard');
                    Route::get('/create', [HostelCategoryController::class, 'create'])->name('create');
                    Route::post('/', [HostelCategoryController::class, 'store'])->name('store');
                    Route::get('/{category}', [HostelCategoryController::class, 'show'])->name('show');
                    Route::get('/{category}/edit', [HostelCategoryController::class, 'edit'])->name('edit');
                    Route::put('/{category}', [HostelCategoryController::class, 'update'])->name('update');
                    Route::delete('/{category}', [HostelCategoryController::class, 'destroy'])->name('destroy');
                    
                    // Additional Actions
                    Route::post('/{category}/toggle-status', [HostelCategoryController::class, 'toggleStatus'])->name('toggle-status');
                    Route::post('/{category}/duplicate', [HostelCategoryController::class, 'duplicate'])->name('duplicate');
                    
                    // Bulk Actions
                    Route::post('/bulk-action', [HostelCategoryController::class, 'bulkAction'])->name('bulk-action');
                });

                // Hostel Rooms
                Route::prefix('rooms')->name('rooms.')->group(function(){
                    // Core Functions
                    Route::get('/', [HostelRoomController::class, 'index'])->name('index');
                    Route::get('/dashboard', [HostelRoomController::class, 'dashboard'])->name('dashboard');
                    Route::get('/create', [HostelRoomController::class, 'create'])->name('create');
                    Route::post('/', [HostelRoomController::class, 'store'])->name('store');
                    Route::get('/{room}', [HostelRoomController::class, 'show'])->name('show');
                    Route::get('/{room}/edit', [HostelRoomController::class, 'edit'])->name('edit');
                    Route::put('/{room}', [HostelRoomController::class, 'update'])->name('update');
                    Route::delete('/{room}', [HostelRoomController::class, 'destroy'])->name('destroy');
                    
                    // Additional Actions
                    Route::post('/{room}/toggle-status', [HostelRoomController::class, 'toggleStatus'])->name('toggle-status');
                    Route::post('/{room}/duplicate', [HostelRoomController::class, 'duplicate'])->name('duplicate');
                    Route::get('/get-by-hostel', [HostelRoomController::class, 'getRoomsByHostel'])->name('get-by-hostel');
                    
                    // Bulk Actions
                    Route::post('/bulk-action', [HostelRoomController::class, 'bulkAction'])->name('bulk-action');
                });

                // Hostel Allocation
                Route::prefix('allocation')->name('allocation.')->group(function(){
                    // Core Functions
                    Route::get('/', [HostelAllocationController::class, 'index'])->name('index');
                    Route::get('/dashboard', [HostelAllocationController::class, 'dashboard'])->name('dashboard');
                    Route::get('/create', [HostelAllocationController::class, 'create'])->name('create');
                    Route::post('/', [HostelAllocationController::class, 'store'])->name('store');
                    Route::get('/{allocation}', [HostelAllocationController::class, 'show'])->name('show');
                    Route::get('/{allocation}/edit', [HostelAllocationController::class, 'edit'])->name('edit');
                    Route::put('/{allocation}', [HostelAllocationController::class, 'update'])->name('update');
                    Route::delete('/{allocation}', [HostelAllocationController::class, 'destroy'])->name('destroy');
                    
                    // Additional Actions
                    Route::post('/{allocation}/toggle-status', [HostelAllocationController::class, 'toggleStatus'])->name('toggle-status');
                    Route::post('/{allocation}/duplicate', [HostelAllocationController::class, 'duplicate'])->name('duplicate');
                    
                    // Bulk Actions
                    Route::post('/bulk-action', [HostelAllocationController::class, 'bulkAction'])->name('bulk-action');
                });

                // Hostel Fees
                Route::prefix('fees')->name('fees.')->group(function(){
                    // Core Functions
                    Route::get('/', [HostelFeeController::class, 'index'])->name('index');
                    Route::get('/dashboard', [HostelFeeController::class, 'dashboard'])->name('dashboard');
                    Route::get('/create', [HostelFeeController::class, 'create'])->name('create');
                    Route::post('/', [HostelFeeController::class, 'store'])->name('store');
                    Route::get('/{fee}', [HostelFeeController::class, 'show'])->name('show');
                    Route::get('/{fee}/edit', [HostelFeeController::class, 'edit'])->name('edit');
                    Route::put('/{fee}', [HostelFeeController::class, 'update'])->name('update');
                    Route::delete('/{fee}', [HostelFeeController::class, 'destroy'])->name('destroy');
                    
                    // Additional Actions
                    Route::post('/{fee}/mark-paid', [HostelFeeController::class, 'markAsPaid'])->name('mark-paid');
                    
                    // Bulk Actions
                    Route::post('/bulk-action', [HostelFeeController::class, 'bulkAction'])->name('bulk-action');
                });

                // Hostel Attendance
                Route::prefix('attendance')->name('attendance.')->group(function(){
                    // Core Functions
                    Route::get('/', [HostelAttendanceController::class, 'index'])->name('index');
                    Route::get('/dashboard', [HostelAttendanceController::class, 'dashboard'])->name('dashboard');
                    Route::get('/create', [HostelAttendanceController::class, 'create'])->name('create');
                    Route::post('/', [HostelAttendanceController::class, 'store'])->name('store');
                    Route::get('/{attendance}', [HostelAttendanceController::class, 'show'])->name('show');
                    Route::get('/{attendance}/edit', [HostelAttendanceController::class, 'edit'])->name('edit');
                    Route::put('/{attendance}', [HostelAttendanceController::class, 'update'])->name('update');
                    Route::delete('/{attendance}', [HostelAttendanceController::class, 'destroy'])->name('destroy');
                    
                    // Bulk Actions
                    Route::get('/bulk-create', [HostelAttendanceController::class, 'bulkCreate'])->name('bulk-create');
                    Route::post('/bulk-store', [HostelAttendanceController::class, 'bulkStore'])->name('bulk-store');
                    Route::post('/bulk-action', [HostelAttendanceController::class, 'bulkAction'])->name('bulk-action');
                });
            });
             // Inventory Management
           Route::prefix('inventory')->name('inventory.')->group(function () {
            // Categories
            Route::prefix('categories')->name('categories.')->group(function () {
                Route::get('/', [App\Http\Controllers\Admin\Inventory\CategoryController::class, 'index'])->name('index');
                Route::get('/create', [App\Http\Controllers\Admin\Inventory\CategoryController::class, 'create'])->name('create');
                Route::post('/', [App\Http\Controllers\Admin\Inventory\CategoryController::class, 'store'])->name('store');
                Route::get('/{category}', [App\Http\Controllers\Admin\Inventory\CategoryController::class, 'show'])->name('show');
                Route::get('/{category}/edit', [App\Http\Controllers\Admin\Inventory\CategoryController::class, 'edit'])->name('edit');
                Route::put('/{category}', [App\Http\Controllers\Admin\Inventory\CategoryController::class, 'update'])->name('update');
                Route::delete('/{category}', [App\Http\Controllers\Admin\Inventory\CategoryController::class, 'destroy'])->name('destroy');
                Route::patch('/{category}/toggle-status', [App\Http\Controllers\Admin\Inventory\CategoryController::class, 'toggleStatus'])->name('toggle-status');
            });
           });

        Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::post('notifications/mark-as-read/{id}', [NotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::post('notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.readall');
        
       
        
    });
});

// Teacher Routes
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

    // Protected Routes (require teacher authentication)
    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Teacher\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/profile', [App\Http\Controllers\Teacher\DashboardController::class, 'profile'])->name('profile');
        Route::put('/profile', [App\Http\Controllers\Teacher\DashboardController::class, 'updateProfile'])->name('profile.update');
        
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
        
        // Lesson Plans / Announcements / Feedback / Whiteboard (index routes)
        Route::get('/lesson-plans', [App\Http\Controllers\Teacher\LessonPlanController::class, 'index'])->name('lessonplans.index');
        Route::get('/announcements', [App\Http\Controllers\Teacher\AnnouncementController::class, 'index'])->name('announcements.index');
        Route::get('/feedback', [App\Http\Controllers\Teacher\FeedbackController::class, 'index'])->name('feedback.index');
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
        
        // Route::get('/grades', [App\Http\Controllers\Teacher\GradeController::class, 'index'])->name('grades.index');
        // Route::get('/schedule', [App\Http\Controllers\Teacher\ScheduleController::class, 'index'])->name('schedule.index');
        // Route::get('/reports', [App\Http\Controllers\Teacher\ReportController::class, 'index'])->name('reports.index');
    });
});

//Librarian Routes
Route::prefix('librarian')->name('librarian.')->group(function () {
    // Public routes (no authentication required)
    Route::get('/login', [App\Http\Controllers\Librarian\LibrarianController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [App\Http\Controllers\Librarian\LibrarianController::class, 'login']);
    Route::get('/register', [App\Http\Controllers\Librarian\LibrarianController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [App\Http\Controllers\Librarian\LibrarianController::class, 'register']);
    Route::post('/logout', [App\Http\Controllers\Librarian\LibrarianController::class, 'logout'])->name('logout');

            // Protected routes (authentication required)
            Route::middleware([\App\Http\Middleware\LibrarianMiddleware::class])->group(function () {
                Route::get('/dashboard', [App\Http\Controllers\Librarian\LibrarianController::class, 'dashboard'])->name('dashboard');
                Route::get('/profile', [App\Http\Controllers\Librarian\LibrarianController::class, 'profile'])->name('profile');
                Route::put('/profile', [App\Http\Controllers\Librarian\LibrarianController::class, 'updateProfile']);
                Route::get('/change-password', [App\Http\Controllers\Librarian\LibrarianController::class, 'showChangePasswordForm'])->name('change-password');
                Route::post('/change-password', [App\Http\Controllers\Librarian\LibrarianController::class, 'changePassword']);
                Route::get('/users', [App\Http\Controllers\Librarian\LibrarianController::class, 'users'])->name('users');
                Route::get('/users/{user}', [App\Http\Controllers\Librarian\LibrarianController::class, 'showUser'])->name('users.show');
                Route::get('/settings', [App\Http\Controllers\Librarian\LibrarianController::class, 'settings'])->name('settings');
                
                // Books Management Routes
                Route::resource('books', App\Http\Controllers\Librarian\BooksController::class);
                Route::post('/books/issue', [App\Http\Controllers\Librarian\BooksController::class, 'issueBook'])->name('books.issue');
                Route::post('/books/return', [App\Http\Controllers\Librarian\BooksController::class, 'returnBook'])->name('books.return');
                Route::get('/books/{book}/issue-form-data', [App\Http\Controllers\Librarian\BooksController::class, 'getIssueFormData'])->name('books.issue-form-data');
                
                // Book Issues Management Routes
                Route::resource('book-issues', App\Http\Controllers\Librarian\BookIssueController::class);
                Route::get('/book-issues/{bookIssue}/return', [App\Http\Controllers\Librarian\BookIssueController::class, 'showReturnForm'])->name('book-issues.return');
                Route::post('/book-issues/{bookIssue}/return', [App\Http\Controllers\Librarian\BookIssueController::class, 'processReturn'])->name('book-issues.process-return');
                Route::post('/book-issues/mark-overdue', [App\Http\Controllers\Librarian\BookIssueController::class, 'markOverdue'])->name('book-issues.mark-overdue');
                Route::get('/book-issues/overdue/list', [App\Http\Controllers\Librarian\BookIssueController::class, 'overdue'])->name('book-issues.overdue');
                Route::get('/book-issues/student/{student}/history', [App\Http\Controllers\Librarian\BookIssueController::class, 'studentHistory'])->name('book-issues.student-history');
                Route::get('/book-issues/book/{book}/history', [App\Http\Controllers\Librarian\BookIssueController::class, 'bookHistory'])->name('book-issues.book-history');
            });
});

// Parent Routes
Route::prefix('parent')->name('parent.')->group(function () {
    // Public routes (no authentication required)
    Route::get('/login', [App\Http\Controllers\ParentController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [App\Http\Controllers\ParentController::class, 'login']);
    Route::get('/register', [App\Http\Controllers\ParentController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [App\Http\Controllers\ParentController::class, 'register']);
    Route::post('/logout', [App\Http\Controllers\ParentController::class, 'logout'])->name('logout');

    // Protected routes (authentication required)
    Route::middleware([\App\Http\Middleware\ParentMiddleware::class])->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\ParentController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [App\Http\Controllers\ParentController::class, 'profile'])->name('profile');
        Route::put('/profile', [App\Http\Controllers\ParentController::class, 'updateProfile']);
        
        // Children management
        Route::get('/children', [App\Http\Controllers\ParentController::class, 'children'])->name('children');
        Route::get('/children/{child}', [App\Http\Controllers\ParentController::class, 'showChild'])->name('children.show');
        Route::get('/children/{child}/progress', [App\Http\Controllers\ParentController::class, 'childProgress'])->name('children.progress');
        Route::get('/children/{child}/attendance', [App\Http\Controllers\ParentController::class, 'childAttendance'])->name('children.attendance');
        
        // Attendance
        Route::get('/attendance', [App\Http\Controllers\ParentController::class, 'attendance'])->name('attendance');
        Route::get('/attendance/{child}', [App\Http\Controllers\ParentController::class, 'childAttendanceDetail'])->name('attendance.child');
        
        // Results & Performance
        Route::get('/results', [App\Http\Controllers\ParentController::class, 'results'])->name('results');
        Route::get('/results/{child}', [App\Http\Controllers\ParentController::class, 'childResults'])->name('results.child');
        
        // Homework & Assignments
        Route::get('/homework', [App\Http\Controllers\ParentController::class, 'homework'])->name('homework');
        Route::get('/homework/{child}', [App\Http\Controllers\ParentController::class, 'childHomework'])->name('homework.child');
        
        // Fee Management
        Route::get('/fees', [App\Http\Controllers\ParentController::class, 'fees'])->name('fees');
        Route::get('/fees/{child}', [App\Http\Controllers\ParentController::class, 'childFees'])->name('fees.child');
        Route::post('/fees/payment', [App\Http\Controllers\ParentController::class, 'processPayment'])->name('fees.payment');
        
        // Notices & Circulars
        Route::get('/notices', [App\Http\Controllers\ParentController::class, 'notices'])->name('notices');
        Route::get('/notices/{notice}', [App\Http\Controllers\ParentController::class, 'showNotice'])->name('notices.show');
        
        // Transport
        Route::get('/transport', [App\Http\Controllers\ParentController::class, 'transport'])->name('transport');
        Route::get('/transport/tracking', [App\Http\Controllers\ParentController::class, 'transportTracking'])->name('transport.tracking');
        
        // Library
        Route::get('/library', [App\Http\Controllers\ParentController::class, 'library'])->name('library');
        Route::get('/library/{child}', [App\Http\Controllers\ParentController::class, 'childLibrary'])->name('library.child');
        
        // PTM Meetings
        Route::get('/ptm', [App\Http\Controllers\ParentController::class, 'ptm'])->name('ptm');
        Route::get('/ptm/{meeting}', [App\Http\Controllers\ParentController::class, 'showPtm'])->name('ptm.show');
        Route::post('/ptm/{meeting}/feedback', [App\Http\Controllers\ParentController::class, 'submitFeedback'])->name('ptm.feedback');
        
        // Health Records
        Route::get('/health', [App\Http\Controllers\ParentController::class, 'health'])->name('health');
        Route::get('/health/{child}', [App\Http\Controllers\ParentController::class, 'childHealth'])->name('health.child');
        
        // Communications
        Route::get('/communications', [App\Http\Controllers\ParentController::class, 'communications'])->name('communications');
    });
});

// Accountant Routes
Route::prefix('accountant')->name('accountant.')->group(function () {
    // Public routes (no authentication required)
    Route::get('/login', [App\Http\Controllers\Accountant\AccountantController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [App\Http\Controllers\Accountant\AccountantController::class, 'login']);
    Route::get('/register', [App\Http\Controllers\Accountant\AccountantController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [App\Http\Controllers\Accountant\AccountantController::class, 'register']);
    Route::post('/logout', [App\Http\Controllers\Accountant\AccountantController::class, 'logout'])->name('logout');
    Route::get('/logout', [App\Http\Controllers\Accountant\AccountantController::class, 'logout'])->name('logout.get');

    // Protected routes (authentication required)
    Route::middleware([\App\Http\Middleware\AccountantMiddleware::class])->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Accountant\AccountantController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [App\Http\Controllers\Accountant\AccountantController::class, 'profile'])->name('profile');
        Route::put('/profile', [App\Http\Controllers\Accountant\AccountantController::class, 'updateProfile']);
        Route::get('/change-password', [App\Http\Controllers\Accountant\AccountantController::class, 'changePassword'])->name('change-password');
        Route::post('/change-password', [App\Http\Controllers\Accountant\AccountantController::class, 'updatePassword']);
        
        // Fee Management
        Route::get('/fees', [App\Http\Controllers\Accountant\AccountantController::class, 'fees'])->name('fees');
        Route::get('/fees/create', [App\Http\Controllers\Accountant\AccountantController::class, 'createFee'])->name('fees.create');
        Route::post('/fees', [App\Http\Controllers\Accountant\AccountantController::class, 'storeFee'])->name('fees.store');
        Route::get('/fees/{fee}/edit', [App\Http\Controllers\Accountant\AccountantController::class, 'editFee'])->name('fees.edit');
        Route::put('/fees/{fee}', [App\Http\Controllers\Accountant\AccountantController::class, 'updateFee'])->name('fees.update');
        Route::delete('/fees/{fee}', [App\Http\Controllers\Accountant\AccountantController::class, 'deleteFee'])->name('fees.delete');
        
        // Payment Management
        Route::get('/payments', [App\Http\Controllers\Accountant\AccountantController::class, 'payments'])->name('payments');
        Route::post('/payments/process', [App\Http\Controllers\Accountant\AccountantController::class, 'processPayment'])->name('payments.process');
        
        // Reports
        Route::get('/reports', [App\Http\Controllers\Accountant\AccountantController::class, 'reports'])->name('reports');
        
        // Student Management
        Route::get('/students', [App\Http\Controllers\Accountant\AccountantController::class, 'students'])->name('students');
        Route::get('/students/{student}/fees', [App\Http\Controllers\Accountant\AccountantController::class, 'studentFees'])->name('students.fees');
        
        // QR Code Management
        Route::get('/qr-codes', [App\Http\Controllers\Accountant\QRCodeController::class, 'index'])->name('qr-codes');
        Route::get('/qr-codes/datatable', [App\Http\Controllers\Accountant\QRCodeController::class, 'datatable'])->name('qr-codes.datatable');
        Route::get('/qr-codes/create', [App\Http\Controllers\Accountant\QRCodeController::class, 'create'])->name('qr-codes.create');
        Route::post('/qr-codes', [App\Http\Controllers\Accountant\QRCodeController::class, 'store'])->name('qr-codes.store');
        Route::get('/qr-codes/{qrCode}', [App\Http\Controllers\Accountant\QRCodeController::class, 'show'])->name('qr-codes.show');
        Route::get('/qr-codes/{qrCode}/edit', [App\Http\Controllers\Accountant\QRCodeController::class, 'edit'])->name('qr-codes.edit');
        Route::put('/qr-codes/{qrCode}', [App\Http\Controllers\Accountant\QRCodeController::class, 'update'])->name('qr-codes.update');
        Route::delete('/qr-codes/{qrCode}', [App\Http\Controllers\Accountant\QRCodeController::class, 'destroy'])->name('qr-codes.destroy');
        Route::get('/qr-codes/{qrCode}/download', [App\Http\Controllers\Accountant\QRCodeController::class, 'download'])->name('qr-codes.download');
        Route::post('/qr-codes/bulk-generate', [App\Http\Controllers\Accountant\QRCodeController::class, 'generateBulk'])->name('qr-codes.bulk-generate');
    });
});

// Student Routes
Route::prefix('student')->name('student.')->group(function () {
    // Authentication Routes
    Route::get('/login', [StudentAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [StudentAuthController::class, 'login']);
    Route::post('/logout', [StudentAuthController::class, 'logout'])->name('logout');
    // Graceful GET logout to avoid method errors when accessed via link/address bar
    Route::get('/logout', function () {
        if (auth()->check()) {
            auth()->logout();
        }
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('student.login');
    })->name('logout.get');

    // Protected Routes (require student authentication)
    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', [StudentAuthController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [StudentAuthController::class, 'profile'])->name('profile');
        Route::put('/profile', [StudentAuthController::class, 'updateProfile'])->name('profile.update');
        Route::post('/change-password', [StudentAuthController::class, 'changePassword'])->name('change-password');
        
        // Attendance Routes
        Route::get('/attendance', [App\Http\Controllers\Student\StudentAttendanceController::class, 'index'])->name('attendance.index');
        Route::get('/attendance/show', [App\Http\Controllers\Student\StudentAttendanceController::class, 'show'])->name('attendance.show');
        Route::get('/attendance/calendar', [App\Http\Controllers\Student\StudentAttendanceController::class, 'calendar'])->name('attendance.calendar');
        Route::get('/attendance/report', [App\Http\Controllers\Student\StudentAttendanceController::class, 'report'])->name('attendance.report');
        
        // Results Routes
        Route::get('/results', [App\Http\Controllers\Student\StudentResultController::class, 'index'])->name('results.index');
        Route::get('/results/show', [App\Http\Controllers\Student\StudentResultController::class, 'show'])->name('results.show');
        Route::get('/results/report', [App\Http\Controllers\Student\StudentResultController::class, 'report'])->name('results.report');
        Route::get('/results/transcript', [App\Http\Controllers\Student\StudentResultController::class, 'transcript'])->name('results.transcript');
        
        // Assignment Routes
        Route::get('/assignments', [App\Http\Controllers\Student\StudentAssignmentController::class, 'index'])->name('assignments.index');
        Route::get('/assignments/{id}', [App\Http\Controllers\Student\StudentAssignmentController::class, 'show'])->name('assignments.show');
        Route::post('/assignments/{id}/submit', [App\Http\Controllers\Student\StudentAssignmentController::class, 'submit'])->name('assignments.submit');
        Route::get('/assignments/history', [App\Http\Controllers\Student\StudentAssignmentController::class, 'history'])->name('assignments.history');
        Route::get('/assignments/download/{id}', [App\Http\Controllers\Student\StudentAssignmentController::class, 'download'])->name('assignments.download');
        
        // Fees Routes
        Route::get('/fees', [App\Http\Controllers\Student\StudentFeesController::class, 'index'])->name('fees.index');
        Route::get('/fees/structure', [App\Http\Controllers\Student\StudentFeesController::class, 'structure'])->name('fees.structure');
        Route::get('/fees/history', [App\Http\Controllers\Student\StudentFeesController::class, 'history'])->name('fees.history');
        Route::get('/fees/{id}', [App\Http\Controllers\Student\StudentFeesController::class, 'show'])->name('fees.show');
        Route::get('/fees/{id}/invoice', [App\Http\Controllers\Student\StudentFeesController::class, 'invoice'])->name('fees.invoice');
        
        // Transport Routes
        Route::get('/transport', [App\Http\Controllers\Student\StudentTransportController::class, 'index'])->name('transport.index');
        Route::get('/transport/routes', [App\Http\Controllers\Student\StudentTransportController::class, 'routes'])->name('transport.routes');
        Route::get('/transport/schedule', [App\Http\Controllers\Student\StudentTransportController::class, 'schedule'])->name('transport.schedule');
        Route::get('/transport/history', [App\Http\Controllers\Student\StudentTransportController::class, 'history'])->name('transport.history');
        Route::get('/transport/profile', [App\Http\Controllers\Student\StudentTransportController::class, 'profile'])->name('transport.profile');
        Route::post('/transport/profile', [App\Http\Controllers\Student\StudentTransportController::class, 'updateProfile'])->name('transport.profile.update');
        
        // Hostel Routes
        Route::get('/hostel', [App\Http\Controllers\Student\StudentHostelController::class, 'index'])->name('hostel.index');
        Route::get('/hostel/rooms', [App\Http\Controllers\Student\StudentHostelController::class, 'rooms'])->name('hostel.rooms');
        Route::get('/hostel/meals', [App\Http\Controllers\Student\StudentHostelController::class, 'meals'])->name('hostel.meals');
        Route::get('/hostel/complaints', [App\Http\Controllers\Student\StudentHostelController::class, 'complaints'])->name('hostel.complaints');
        Route::post('/hostel/complaints', [App\Http\Controllers\Student\StudentHostelController::class, 'submitComplaint'])->name('hostel.complaints.submit');
        Route::get('/hostel/profile', [App\Http\Controllers\Student\StudentHostelController::class, 'profile'])->name('hostel.profile');
        Route::post('/hostel/profile', [App\Http\Controllers\Student\StudentHostelController::class, 'updateProfile'])->name('hostel.profile.update');
        
        // Library Routes
        Route::get('/library', [App\Http\Controllers\Student\StudentLibraryController::class, 'index'])->name('library.index');
        Route::get('/library/books', [App\Http\Controllers\Student\StudentLibraryController::class, 'books'])->name('library.books');
        Route::get('/library/search', [App\Http\Controllers\Student\StudentLibraryController::class, 'search'])->name('library.search');
        Route::get('/library/book/{id}', [App\Http\Controllers\Student\StudentLibraryController::class, 'bookDetails'])->name('library.book.details');
        Route::post('/library/book/{id}/borrow', [App\Http\Controllers\Student\StudentLibraryController::class, 'borrowBook'])->name('library.book.borrow');
        Route::get('/library/history', [App\Http\Controllers\Student\StudentLibraryController::class, 'history'])->name('library.history');
        Route::post('/library/renew/{issueId}', [App\Http\Controllers\Student\StudentLibraryController::class, 'renewBook'])->name('library.book.renew');
        Route::get('/library/profile', [App\Http\Controllers\Student\StudentLibraryController::class, 'profile'])->name('library.profile');
        Route::post('/library/profile', [App\Http\Controllers\Student\StudentLibraryController::class, 'updateProfile'])->name('library.profile.update');
        
        // Notification Routes
        Route::get('/notifications', [App\Http\Controllers\Student\StudentNotificationController::class, 'index'])->name('notifications.index');
        Route::get('/notifications/all', [App\Http\Controllers\Student\StudentNotificationController::class, 'all'])->name('notifications.all');
        Route::get('/notifications/{id}', [App\Http\Controllers\Student\StudentNotificationController::class, 'show'])->name('notifications.show');
        Route::post('/notifications/{id}/read', [App\Http\Controllers\Student\StudentNotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::post('/notifications/mark-all-read', [App\Http\Controllers\Student\StudentNotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
        Route::delete('/notifications/{id}', [App\Http\Controllers\Student\StudentNotificationController::class, 'delete'])->name('notifications.delete');
        Route::get('/notifications/settings', [App\Http\Controllers\Student\StudentNotificationController::class, 'settings'])->name('notifications.settings');
        Route::post('/notifications/settings', [App\Http\Controllers\Student\StudentNotificationController::class, 'updateSettings'])->name('notifications.settings.update');
        Route::get('/notifications/compose', [App\Http\Controllers\Student\StudentNotificationController::class, 'compose'])->name('notifications.compose');
        Route::post('/notifications/send', [App\Http\Controllers\Student\StudentNotificationController::class, 'sendNotification'])->name('notifications.send');
    });
});

// QR Code Access (Public)
Route::get('/accountant/qr/{code}', [App\Http\Controllers\Accountant\QRCodeController::class, 'access'])->name('accountant.qr.access');
Route::post('/accountant/qr/{code}/process', [App\Http\Controllers\Accountant\QRCodeController::class, 'processAccess'])->name('accountant.qr.process');

// SuperAdmin Payment Management Routes
Route::middleware(['auth', 'checkrole:superadmin'])->prefix('superadmin/payment')->name('superadmin.payment.')->group(function () {
    // School QR Code Management (SuperAdmin can manage all schools)
    Route::resource('school-qr-codes', App\Http\Controllers\Superadmin\Payment\SchoolQrCodeController::class);
    Route::post('school-qr-codes/{schoolQrCode}/toggle-status', [App\Http\Controllers\Superadmin\Payment\SchoolQrCodeController::class, 'toggleStatus'])->name('school-qr-codes.toggle-status');
    Route::get('school-qr-codes/{schoolQrCode}/download', [App\Http\Controllers\Superadmin\Payment\SchoolQrCodeController::class, 'download'])->name('school-qr-codes.download');
    
    // Payment Gateway Management
    Route::resource('gateways', App\Http\Controllers\Superadmin\Payment\PaymentGatewayController::class);
    Route::post('gateways/{gateway}/toggle-status', [App\Http\Controllers\Superadmin\Payment\PaymentGatewayController::class, 'toggleStatus'])->name('gateways.toggle-status');
    Route::post('gateways/{gateway}/test-connection', [App\Http\Controllers\Superadmin\Payment\PaymentGatewayController::class, 'testConnection'])->name('gateways.test-connection');
    
    // Payment Plans Management
    Route::resource('plans', App\Http\Controllers\Superadmin\Payment\PaymentPlanController::class);
    Route::post('plans/{plan}/toggle-status', [App\Http\Controllers\Superadmin\Payment\PaymentPlanController::class, 'toggleStatus'])->name('plans.toggle-status');
    
    // Reports & Analytics
    Route::get('reports', [App\Http\Controllers\Superadmin\Payment\ReportsController::class, 'index'])->name('reports.index');
    Route::get('reports/transactions', [App\Http\Controllers\Superadmin\Payment\ReportsController::class, 'transactions'])->name('reports.transactions');
    Route::get('reports/revenue', [App\Http\Controllers\Superadmin\Payment\ReportsController::class, 'revenue'])->name('reports.revenue');
    Route::get('reports/export', [App\Http\Controllers\Superadmin\Payment\ReportsController::class, 'export'])->name('reports.export');
});

// Admin Payment Management Routes
Route::middleware(['auth:admin'])->prefix('admin/payment')->name('admin.payment.')->group(function () {
    // Payment Gateway Settings
    Route::resource('gateways', App\Http\Controllers\Admin\Payment\PaymentGatewayController::class);
    Route::post('gateways/{gateway}/toggle-status', [App\Http\Controllers\Admin\Payment\PaymentGatewayController::class, 'toggleStatus'])->name('gateways.toggle-status');
    Route::post('gateways/{gateway}/test-connection', [App\Http\Controllers\Admin\Payment\PaymentGatewayController::class, 'testConnection'])->name('gateways.test-connection');
    
    // School QR Code (Multiple QR codes allowed)
    Route::resource('school-qr-codes', App\Http\Controllers\Admin\Payment\SchoolQrCodeController::class);
    Route::post('school-qr-codes/{schoolQrCode}/toggle-status', [App\Http\Controllers\Admin\Payment\SchoolQrCodeController::class, 'toggleStatus'])->name('school-qr-codes.toggle-status');
    Route::get('school-qr-codes/{schoolQrCode}/download', [App\Http\Controllers\Admin\Payment\SchoolQrCodeController::class, 'download'])->name('school-qr-codes.download');
    
    // Plan Selection
    Route::get('plan-selection', [App\Http\Controllers\Admin\Payment\PlanSelectionController::class, 'index'])->name('plan-selection.index');
    Route::get('plan-selection/{plan}', [App\Http\Controllers\Admin\Payment\PlanSelectionController::class, 'show'])->name('plan-selection.show');
    Route::post('plan-selection/{plan}/select', [App\Http\Controllers\Admin\Payment\PlanSelectionController::class, 'selectPlan'])->name('plan-selection.select');
    Route::get('payment/success/{transactionId}', [App\Http\Controllers\Admin\Payment\PlanSelectionController::class, 'success'])->name('payment.success');
    Route::get('payment/failure/{transactionId}', [App\Http\Controllers\Admin\Payment\PlanSelectionController::class, 'failure'])->name('payment.failure');
    Route::post('payment/retry/{transaction}', [App\Http\Controllers\Admin\Payment\PlanSelectionController::class, 'retryPayment'])->name('payment.retry');
});

            // Admin Inventory Management Routes
            Route::middleware(['auth:admin'])->prefix('admin/inventory')->name('admin.inventory.')->group(function () {
                Route::resource('items', App\Http\Controllers\Admin\Inventory\InventoryItemController::class);
                Route::post('items/{inventoryItem}/toggle-status', [App\Http\Controllers\Admin\Inventory\InventoryItemController::class, 'toggleStatus'])->name('items.toggle-status');
                
                // Inventory Items Import/Export
                Route::get('items/export', [App\Http\Controllers\Admin\Inventory\InventoryItemController::class, 'export'])->name('items.export');
                Route::get('items/import', [App\Http\Controllers\Admin\Inventory\InventoryItemController::class, 'importForm'])->name('items.import');
                Route::post('items/import', [App\Http\Controllers\Admin\Inventory\InventoryItemController::class, 'import'])->name('items.import.store');
                Route::get('items/sample', [App\Http\Controllers\Admin\Inventory\InventoryItemController::class, 'downloadSample'])->name('items.sample');
                
                // Inventory Issues Routes
                Route::resource('issues', App\Http\Controllers\Admin\Inventory\InventoryIssueController::class);
                Route::post('issues/{issue}/update-status', [App\Http\Controllers\Admin\Inventory\InventoryIssueController::class, 'updateStatus'])->name('issues.update-status');
                
                // Inventory Issues Import/Export
                Route::get('issues/export', [App\Http\Controllers\Admin\Inventory\InventoryIssueController::class, 'export'])->name('issues.export');
                Route::get('issues/import', [App\Http\Controllers\Admin\Inventory\InventoryIssueController::class, 'importForm'])->name('issues.import');
                Route::post('issues/import', [App\Http\Controllers\Admin\Inventory\InventoryIssueController::class, 'import'])->name('issues.import.store');
                Route::get('issues/sample', [App\Http\Controllers\Admin\Inventory\InventoryIssueController::class, 'downloadSample'])->name('issues.sample');
                
                // Inventory Stock Management Routes
                Route::get('stock', [App\Http\Controllers\Admin\Inventory\InventoryStockController::class, 'index'])->name('stock.index');
                Route::get('stock/adjust/{item}', [App\Http\Controllers\Admin\Inventory\InventoryStockController::class, 'adjust'])->name('stock.adjust');
                Route::post('stock/adjust/{item}', [App\Http\Controllers\Admin\Inventory\InventoryStockController::class, 'processAdjustment'])->name('stock.adjust.process');
                Route::get('stock/history/{item}', [App\Http\Controllers\Admin\Inventory\InventoryStockController::class, 'history'])->name('stock.history');
                Route::get('stock/movements', [App\Http\Controllers\Admin\Inventory\InventoryStockController::class, 'movements'])->name('stock.movements');
                Route::get('stock/statistics', [App\Http\Controllers\Admin\Inventory\InventoryStockController::class, 'statistics'])->name('stock.statistics');
                Route::post('stock/bulk-adjust', [App\Http\Controllers\Admin\Inventory\InventoryStockController::class, 'bulkAdjust'])->name('stock.bulk-adjust');
                
                // Inventory Suppliers Routes
                Route::resource('suppliers', App\Http\Controllers\Admin\Inventory\SupplierController::class);
                Route::post('suppliers/{supplier}/toggle-verification', [App\Http\Controllers\Admin\Inventory\SupplierController::class, 'toggleVerification'])->name('suppliers.toggle-verification');
                Route::post('suppliers/{supplier}/toggle-status', [App\Http\Controllers\Admin\Inventory\SupplierController::class, 'toggleStatus'])->name('suppliers.toggle-status');
                
                // Inventory Purchase Management Routes
                Route::resource('purchases', App\Http\Controllers\Admin\Inventory\PurchaseController::class);
                Route::post('purchases/{purchase}/approve', [App\Http\Controllers\Admin\Inventory\PurchaseController::class, 'approve'])->name('purchases.approve');
                Route::post('purchases/{purchase}/mark-ordered', [App\Http\Controllers\Admin\Inventory\PurchaseController::class, 'markAsOrdered'])->name('purchases.mark-ordered');
                Route::post('purchases/{purchase}/mark-received', [App\Http\Controllers\Admin\Inventory\PurchaseController::class, 'markAsReceived'])->name('purchases.mark-received');
                Route::post('purchases/{purchase}/cancel', [App\Http\Controllers\Admin\Inventory\PurchaseController::class, 'cancel'])->name('purchases.cancel');
                Route::get('purchases/inventory-items', [App\Http\Controllers\Admin\Inventory\PurchaseController::class, 'getInventoryItems'])->name('purchases.inventory-items');
            });

// Accountant Payment Management Routes
Route::middleware(['auth:accountant'])->prefix('accountant/payment')->name('accountant.payment.')->group(function () {
    // Payment Management
    Route::get('dashboard', [App\Http\Controllers\Accountant\Payment\PaymentManagementController::class, 'dashboard'])->name('dashboard');
    Route::resource('payments', App\Http\Controllers\Accountant\Payment\PaymentManagementController::class);
    Route::post('payments/{payment}/refund', [App\Http\Controllers\Accountant\Payment\PaymentManagementController::class, 'refund'])->name('payments.refund');
    Route::get('payments/statistics', [App\Http\Controllers\Accountant\Payment\PaymentManagementController::class, 'getStatistics'])->name('payments.statistics');
    
    // Transaction Management
    Route::get('qr-scanner', [App\Http\Controllers\Accountant\Payment\TransactionController::class, 'qrScanner'])->name('qr-scanner');
    Route::post('qr-scan', [App\Http\Controllers\Accountant\Payment\TransactionController::class, 'processQrScan'])->name('qr-scan');
    Route::get('online-payment', [App\Http\Controllers\Accountant\Payment\TransactionController::class, 'onlinePaymentForm'])->name('online-payment');
    Route::post('online-payment', [App\Http\Controllers\Accountant\Payment\TransactionController::class, 'processOnlinePayment'])->name('online-payment.process');
    Route::post('callback/{gateway}', [App\Http\Controllers\Accountant\Payment\TransactionController::class, 'paymentCallback'])->name('callback');
    Route::get('transaction-history', [App\Http\Controllers\Accountant\Payment\TransactionController::class, 'transactionHistory'])->name('transaction-history');
    Route::post('payments/{payment}/retry', [App\Http\Controllers\Accountant\Payment\TransactionController::class, 'retryPayment'])->name('payments.retry');
    Route::get('qr-codes/available', [App\Http\Controllers\Accountant\Payment\TransactionController::class, 'getAvailableQrCodes'])->name('qr-codes.available');
    Route::get('gateways/{gateway}/details', [App\Http\Controllers\Accountant\Payment\TransactionController::class, 'getGatewayDetails'])->name('gateways.details');
    
    // School QR Code (Accountant Access)
    Route::get('school-qr-codes', [App\Http\Controllers\Accountant\Payment\SchoolQrCodeController::class, 'index'])->name('school-qr-codes.index');
    Route::get('school-qr-codes/show', [App\Http\Controllers\Accountant\Payment\SchoolQrCodeController::class, 'show'])->name('school-qr-codes.show');
    Route::get('school-qr-codes/download', [App\Http\Controllers\Accountant\Payment\SchoolQrCodeController::class, 'download'])->name('school-qr-codes.download');
    Route::post('school-qr-codes/process-scan', [App\Http\Controllers\Accountant\Payment\SchoolQrCodeController::class, 'processScan'])->name('school-qr-codes.process-scan');
    
    // Payment Verification & Recording
    Route::get('verification', [App\Http\Controllers\Accountant\Payment\VerificationController::class, 'index'])->name('verification.index');
    Route::get('verification/{transaction}', [App\Http\Controllers\Accountant\Payment\VerificationController::class, 'show'])->name('verification.show');
    Route::post('verification/{transaction}/verify', [App\Http\Controllers\Accountant\Payment\VerificationController::class, 'verify'])->name('verification.verify');
    Route::post('verification/{transaction}/retry', [App\Http\Controllers\Accountant\Payment\VerificationController::class, 'retryPayment'])->name('verification.retry');
});

// Public Payment Routes
Route::get('/payment/qr-redirect/{qr_id}', function($qr_id) {
    return view('payment.qr-redirect', compact('qr_id'));
})->name('payment.qr-redirect');




