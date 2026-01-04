<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use OpenAI\Laravel\Facades\OpenAI;

use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\StudentDocumentController;
use App\Http\Controllers\Admin\Payment\SchoolQrCodeController;
use App\Http\Controllers\Admin\Payment\PlanSelectionController;
use App\Http\Controllers\Admin\Payment\QrLimitRequestController;
use App\Http\Controllers\Admin\Payment\QrCodePaymentController;
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
use App\Http\Controllers\Admin\FeeHeadController;
// use App\Http\Controllers\Admin\FeeStructureController; // Duplicate import removed
use App\Http\Controllers\Admin\FeeCollectionController;
use App\Http\Controllers\Admin\FeeReceiptController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\LibrarianController;
use App\Http\Controllers\Admin\AccountantController;
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
// use App\Http\Controllers\Admin\StudentFeeController; // Duplicate import removed
use App\Http\Controllers\Admin\StudentPromotionController;
use App\Http\Controllers\Admin\StudentHealthController;
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
use App\Http\Controllers\Admin\Canteen\ItemController as CanteenItemController;
use App\Http\Controllers\Admin\Canteen\SaleController as CanteenSaleController;
/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here are all the routes for the Admin module. These routes are
| protected by authentication and role-based access control.
|
*/

Route::middleware(['auth:admin', 'checkrole:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Canteen Management
    Route::prefix('canteen')->name('canteen.')->group(function () {
        Route::get('items/data', [CanteenItemController::class, 'data'])->name('items.data');
        Route::get('items/export', [CanteenItemController::class, 'export'])->name('items.export');
        Route::get('items/sample', [CanteenItemController::class, 'sample'])->name('items.sample');
        Route::post('items/import', [CanteenItemController::class, 'import'])->name('items.import');
        Route::post('items/bulk-destroy', [CanteenItemController::class, 'bulkDestroy'])->name('items.bulk-destroy');
        Route::post('items/{canteenItem}/toggle-status', [CanteenItemController::class, 'toggleStatus'])->name('items.toggle-status');
        Route::resource('items', CanteenItemController::class);

        // Sales
        Route::get('sales/export', [CanteenSaleController::class, 'export'])->name('sales.export');
        Route::get('sales/sample', [CanteenSaleController::class, 'sample'])->name('sales.sample');
        Route::post('sales/import', [CanteenSaleController::class, 'import'])->name('sales.import');
        Route::post('sales/bulk-destroy', [CanteenSaleController::class, 'bulkDestroy'])->name('sales.bulk-destroy');
        Route::resource('sales', CanteenSaleController::class);
    });
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Student Management
    Route::prefix('students')->name('students.')->group(function () {
        Route::get('/', [StudentController::class, 'index'])->name('index');
        Route::get('/create', [StudentController::class, 'create'])->name('create');
        Route::post('/', [StudentController::class, 'store'])->name('store');
        Route::get('/{student}', [StudentController::class, 'show'])->name('show');
        Route::get('/{student}/edit', [StudentController::class, 'edit'])->name('edit');
        Route::put('/{student}', [StudentController::class, 'update'])->name('update');
        Route::delete('/{student}', [StudentController::class, 'destroy'])->name('destroy');
        Route::post('/{student}/toggle-status', [StudentController::class, 'toggleStatus'])->name('toggle-status');
        Route::get('/{student}/documents', [StudentController::class, 'documents'])->name('documents');
        Route::get('/data/table', [StudentController::class, 'serverSideDataTable'])->name('data.table');
    });

    // Student Details (handled within StudentController)
    Route::prefix('student-details')->name('student-details.')->group(function () {
        Route::get('/{student}', [StudentController::class, 'show'])->name('show');
        Route::get('/{student}/edit', [StudentController::class, 'edit'])->name('edit');
        Route::put('/{student}', [StudentController::class, 'update'])->name('update');
    });

    // Student Documents
    Route::prefix('student-documents')->name('student-documents.')->group(function () {
        Route::get('/{student}', [StudentDocumentController::class, 'index'])->name('index');
        Route::post('/{student}', [StudentDocumentController::class, 'store'])->name('store');
        Route::delete('/{document}', [StudentDocumentController::class, 'destroy'])->name('destroy');
        Route::get('/{document}/download', [StudentDocumentController::class, 'download'])->name('download');
    });

    // Payment Management
    Route::prefix('payment')->name('payment.')->group(function () {
        // School QR Codes
        Route::prefix('school-qr-codes')->name('school-qr-codes.')->group(function () {
            Route::get('/', [SchoolQrCodeController::class, 'index'])->name('index');
            Route::get('/create', [SchoolQrCodeController::class, 'create'])->name('create');
            Route::post('/', [SchoolQrCodeController::class, 'store'])->name('store');
            Route::get('/{schoolQrCode}', [SchoolQrCodeController::class, 'show'])->name('show');
            Route::get('/{schoolQrCode}/edit', [SchoolQrCodeController::class, 'edit'])->name('edit');
            Route::put('/{schoolQrCode}', [SchoolQrCodeController::class, 'update'])->name('update');
            Route::delete('/{schoolQrCode}', [SchoolQrCodeController::class, 'destroy'])->name('destroy');
            Route::post('/{schoolQrCode}/toggle-status', [SchoolQrCodeController::class, 'toggleStatus'])->name('toggle-status');
            Route::get('/{schoolQrCode}/download-qr', [SchoolQrCodeController::class, 'downloadQr'])->name('download-qr');
        });

        // Plan Selection
        Route::prefix('plan-selection')->name('plan-selection.')->group(function () {
            Route::get('/', [PlanSelectionController::class, 'index'])->name('index');
            Route::get('/available-plans', [PlanSelectionController::class, 'getAvailablePlans'])->name('available-plans');
            Route::post('/select-plan', [PlanSelectionController::class, 'selectPlan'])->name('select-plan');
            Route::post('/process-payment', [PlanSelectionController::class, 'processPayment'])->name('process-payment');
            Route::get('/payment-success', [PlanSelectionController::class, 'paymentSuccess'])->name('payment-success');
            Route::get('/payment-failed', [PlanSelectionController::class, 'paymentFailed'])->name('payment-failed');
        });

        // Payment History
        Route::get('/history', function () {
            return view('admin.payment.history.index');
        })->name('history');

        // QR Limit Requests
        Route::prefix('qr-limit-requests')->name('qr-limit-requests.')->group(function () {
            Route::get('/', [QrLimitRequestController::class, 'index'])->name('index');
            Route::post('/', [QrLimitRequestController::class, 'store'])->name('store');
            Route::get('/history', [QrLimitRequestController::class, 'history'])->name('history');
            Route::post('/{requestId}/cancel', [QrLimitRequestController::class, 'cancel'])->name('cancel');
            Route::get('/status', [QrLimitRequestController::class, 'status'])->name('status');
        });

        // QR Code Payments
        Route::prefix('qr-code-payment')->name('qr-code-payment.')->group(function () {
            Route::get('/', [QrCodePaymentController::class, 'index'])->name('index');
            Route::post('/calculate', [QrCodePaymentController::class, 'calculatePricing'])->name('calculate');
            Route::post('/create', [QrCodePaymentController::class, 'createPayment'])->name('create');
            Route::get('/history', [QrCodePaymentController::class, 'history'])->name('history');
            Route::get('/{payment}/success', [QrCodePaymentController::class, 'paymentSuccess'])->name('success');
            Route::get('/{payment}/failed', [QrCodePaymentController::class, 'paymentFailed'])->name('failed');
            Route::post('/{payment}/simulate', [QrCodePaymentController::class, 'simulatePayment'])->name('simulate');
            
            // Payment Gateway Routes
            Route::get('/{payment}/razorpay', [QrCodePaymentController::class, 'getPaymentGatewayUrl'])->name('razorpay');
            Route::get('/{payment}/stripe', [QrCodePaymentController::class, 'getPaymentGatewayUrl'])->name('stripe');
            Route::get('/{payment}/paypal', [QrCodePaymentController::class, 'getPaymentGatewayUrl'])->name('paypal');
            Route::get('/{payment}/upi', [QrCodePaymentController::class, 'getPaymentGatewayUrl'])->name('upi');
            Route::get('/{payment}/bank-transfer', [QrCodePaymentController::class, 'getPaymentGatewayUrl'])->name('bank-transfer');
        });
    });

    // Fee Management
    Route::prefix('fees')->name('fees.')->group(function () {
        // Fee Heads
        Route::prefix('fee-heads')->name('fee-heads.')->group(function () {
            Route::get('/', [FeeHeadController::class, 'index'])->name('index');
            Route::get('/create', [FeeHeadController::class, 'create'])->name('create');
            Route::post('/', [FeeHeadController::class, 'store'])->name('store');
            Route::get('/{feeHead}', [FeeHeadController::class, 'show'])->name('show');
            Route::get('/{feeHead}/edit', [FeeHeadController::class, 'edit'])->name('edit');
            Route::put('/{feeHead}', [FeeHeadController::class, 'update'])->name('update');
            Route::delete('/{feeHead}', [FeeHeadController::class, 'destroy'])->name('destroy');
            Route::post('/{feeHead}/toggle-status', [FeeHeadController::class, 'toggleStatus'])->name('toggle-status');
        });

        // Fee Structures
        Route::prefix('fee-structures')->name('fee-structures.')->group(function () {
            Route::get('/', [FeeStructureController::class, 'index'])->name('index');
            Route::get('/create', [FeeStructureController::class, 'create'])->name('create');
            Route::post('/', [FeeStructureController::class, 'store'])->name('store');
            Route::get('/{feeStructure}', [FeeStructureController::class, 'show'])->name('show');
            Route::get('/{feeStructure}/edit', [FeeStructureController::class, 'edit'])->name('edit');
            Route::put('/{feeStructure}', [FeeStructureController::class, 'update'])->name('update');
            Route::delete('/{feeStructure}', [FeeStructureController::class, 'destroy'])->name('destroy');
        });

        // Student Fees
        Route::prefix('student-fees')->name('student-fees.')->group(function () {
            Route::get('/', [StudentFeeController::class, 'index'])->name('index');
            Route::get('/create', [StudentFeeController::class, 'create'])->name('create');
            Route::post('/', [StudentFeeController::class, 'store'])->name('store');
            Route::get('/{studentFee}', [StudentFeeController::class, 'show'])->name('show');
            Route::get('/{studentFee}/edit', [StudentFeeController::class, 'edit'])->name('edit');
            Route::put('/{studentFee}', [StudentFeeController::class, 'update'])->name('update');
            Route::delete('/{studentFee}', [StudentFeeController::class, 'destroy'])->name('destroy');
        });

        // Fee Collections
        Route::prefix('fee-collections')->name('fee-collections.')->group(function () {
            Route::get('/', [FeeCollectionController::class, 'index'])->name('index');
            Route::get('/create', [FeeCollectionController::class, 'create'])->name('create');
            Route::post('/', [FeeCollectionController::class, 'store'])->name('store');
            Route::get('/{feeCollection}', [FeeCollectionController::class, 'show'])->name('show');
            Route::get('/{feeCollection}/edit', [FeeCollectionController::class, 'edit'])->name('edit');
            Route::put('/{feeCollection}', [FeeCollectionController::class, 'update'])->name('update');
            Route::delete('/{feeCollection}', [FeeCollectionController::class, 'destroy'])->name('destroy');
        });

        // Fee Receipts
        Route::prefix('fee-receipts')->name('fee-receipts.')->group(function () {
            Route::get('/', [FeeReceiptController::class, 'index'])->name('index');
            Route::get('/{feeReceipt}', [FeeReceiptController::class, 'show'])->name('show');
            Route::get('/{feeReceipt}/print', [FeeReceiptController::class, 'print'])->name('print');
            Route::post('/{feeReceipt}/cancel', [FeeReceiptController::class, 'cancel'])->name('cancel');
        });
    });

    // User Management
    Route::prefix('users')->name('users.')->group(function () {
        // Teachers under users
        Route::prefix('teachers')->name('teachers.')->group(function () {
            Route::get('/', function () {
                return view('admin.teachers.index');
            })->name('index');
            Route::get('/create', function () {
                return view('admin.teachers.create');
            })->name('create');
            Route::get('/{teacher}', function () {
                return view('admin.teachers.show');
            })->name('show');
            Route::get('/{teacher}/edit', function () {
                return view('admin.teachers.edit');
            })->name('edit');
        });
    });

    // Teacher Management (Direct access)
    Route::prefix('teachers')->name('teachers.')->group(function () {
        Route::get('/', function () {
            return view('admin.teachers.index');
        })->name('index');
        Route::get('/create', function () {
            return view('admin.teachers.create');
        })->name('create');
        Route::get('/{teacher}', function () {
            return view('admin.teachers.show');
        })->name('show');
        Route::get('/{teacher}/edit', function () {
            return view('admin.teachers.edit');
        })->name('edit');
    });

    // Parent Management
    Route::prefix('parents')->name('parents.')->group(function () {
        Route::get('/', function () {
            return view('admin.parents.index');
        })->name('index');
        Route::get('/create', function () {
            return view('admin.parents.create');
        })->name('create');
        Route::get('/{parent}', function () {
            return view('admin.parents.show');
        })->name('show');
        Route::get('/{parent}/edit', function () {
            return view('admin.parents.edit');
        })->name('edit');
    });

    // Attendance Management
    Route::prefix('attendance')->name('attendance.')->group(function () {
        Route::get('/', function () {
            return view('admin.attendance.index');
        })->name('index');
        Route::get('/mark', function () {
            return view('admin.attendance.mark');
        })->name('mark');
        Route::get('/reports', function () {
            return view('admin.attendance.reports');
        })->name('reports');
    });

    // Exam Management
    Route::prefix('exams')->name('exams.')->group(function () {
        Route::get('/', function () {
            return view('admin.exams.index');
        })->name('index');
        Route::get('/create', function () {
            return view('admin.exams.create');
        })->name('create');
        Route::get('/{exam}', function () {
            return view('admin.exams.show');
        })->name('show');
        Route::get('/{exam}/edit', function () {
            return view('admin.exams.edit');
        })->name('edit');
    });

    // Library Management
    Route::prefix('library')->name('library.')->group(function () {
        Route::get('/', function () {
            return view('admin.library.index');
        })->name('index');
        Route::get('/books', function () {
            return view('admin.library.books');
        })->name('books');
        Route::get('/borrow', function () {
            return view('admin.library.borrow');
        })->name('borrow');
        Route::get('/return', function () {
            return view('admin.library.return');
        })->name('return');
    });

    // Transport Management
    Route::prefix('transport')->name('transport.')->group(function () {
        Route::get('/', function () {
            return view('admin.transport.index');
        })->name('index');
        Route::get('/vehicles', function () {
            return view('admin.transport.vehicles');
        })->name('vehicles');
        Route::get('/routes', function () {
            return view('admin.transport.routes');
        })->name('routes');
        Route::get('/assignments', function () {
            return view('admin.transport.assignments');
        })->name('assignments');
    });

    // Hostel Management
    Route::prefix('hostel')->name('hostel.')->group(function () {
        Route::get('/', function () {
            return view('admin.hostel.index');
        })->name('index');
        Route::get('/rooms', function () {
            return view('admin.hostel.rooms');
        })->name('rooms');
        Route::get('/allotments', function () {
            return view('admin.hostel.allotments');
        })->name('allotments');
    });

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', function () {
            return view('admin.reports.index');
        })->name('index');
        Route::get('/students', function () {
            return view('admin.reports.students');
        })->name('students');
        Route::get('/attendance', function () {
            return view('admin.reports.attendance');
        })->name('attendance');
        Route::get('/exams', function () {
            return view('admin.reports.exams');
        })->name('exams');
        Route::get('/financial', function () {
            return view('admin.reports.financial');
        })->name('financial');
    });

    // Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', function () {
            return view('admin.settings.index');
        })->name('index');
        Route::get('/general', function () {
            return view('admin.settings.general');
        })->name('general');
        Route::get('/academic', function () {
            return view('admin.settings.academic');
        })->name('academic');
        Route::get('/notifications', function () {
            return view('admin.settings.notifications');
        })->name('notifications');
    });
});
