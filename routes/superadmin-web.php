<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Superadmin\SuperAdminDashboardController;
use App\Http\Controllers\Superadmin\SchoolController;
use App\Http\Controllers\Superadmin\AdminController;
use App\Http\Controllers\Superadmin\SuperAdminSettingsController;
use App\Http\Controllers\Superadmin\PurchaseController;
use App\Http\Controllers\Superadmin\ProductPlanController;
use App\Http\Controllers\Superadmin\Payment\SchoolQrCodeController;
use App\Http\Controllers\Superadmin\Payment\PaymentGatewayController;
use App\Http\Controllers\Superadmin\Payment\PaymentPlanController;
use App\Http\Controllers\Superadmin\Payment\ReportsController;
use App\Http\Controllers\Superadmin\Payment\QrCodePaymentController;
use App\Http\Controllers\Superadmin\RoleController;
use App\Http\Controllers\Superadmin\PermissionController;
use App\Http\Controllers\Superadmin\UserRoleController;
use App\Http\Controllers\Superadmin\AIAutomationController;
use App\Http\Controllers\Superadmin\MonitoringAnalyticsController;
use App\Http\Controllers\Superadmin\SupportCommunicationController;
use App\Http\Controllers\Superadmin\DeveloperToolsController;

/*
|--------------------------------------------------------------------------
| Super Admin Routes
|--------------------------------------------------------------------------
|
| Here are all the routes for the Super Admin module. These routes are
| protected by authentication and role-based access control.
|
*/

Route::middleware(['auth', 'checkrole:superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [SuperAdminDashboardController::class, 'index'])->name('dashboard');
    
    // Dashboard AJAX endpoints
    Route::get('/dashboard/metrics', [SuperAdminDashboardController::class, 'index'])->name('dashboard.metrics');
    Route::get('/dashboard/system-health', [SuperAdminDashboardController::class, 'index'])->name('dashboard.system-health');
    Route::get('/dashboard/revenue-chart', [SuperAdminDashboardController::class, 'index'])->name('dashboard.revenue-chart');
    Route::get('/dashboard/ai-insights', [SuperAdminDashboardController::class, 'index'])->name('dashboard.ai-insights');
    Route::get('/dashboard/recent-activity', [SuperAdminDashboardController::class, 'index'])->name('dashboard.recent-activity');
    Route::post('/dashboard/ai-query', [SuperAdminDashboardController::class, 'index'])->name('dashboard.ai-query');
    Route::get('/dashboard/expiring-schools', [SuperAdminDashboardController::class, 'getExpiringSchools'])->name('dashboard.expiring-schools');
    Route::get('/dashboard/revenue-forecast', [SuperAdminDashboardController::class, 'getRevenueForecast'])->name('dashboard.revenue-forecast');
    Route::get('/dashboard/churn-prediction', [SuperAdminDashboardController::class, 'getChurnPrediction'])->name('dashboard.churn-prediction');

    // School Management
    Route::prefix('schools')->name('schools.')->group(function () {
        Route::get('/', [SchoolController::class, 'index'])->name('index');
        Route::get('/create', [SchoolController::class, 'create'])->name('create');
        Route::post('/', [SchoolController::class, 'store'])->name('store');
        Route::get('/{school}', [SchoolController::class, 'show'])->name('show');
        Route::get('/{school}/edit', [SchoolController::class, 'edit'])->name('edit');
        Route::put('/{school}', [SchoolController::class, 'update'])->name('update');
        Route::delete('/{school}', [SchoolController::class, 'destroy'])->name('destroy');
        Route::get('/{school}/folder-structure', [SchoolController::class, 'folderStructure'])->name('folder-structure');
        Route::post('/{school}/toggle-status', [SchoolController::class, 'toggleStatus'])->name('toggle-status');
    });

    // Admin Management
    Route::prefix('admins')->name('admins.')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('index');
        Route::get('/datatable', [AdminController::class, 'datatable'])->name('datatable');
        Route::get('/create', [AdminController::class, 'create'])->name('create');
        Route::post('/', [AdminController::class, 'store'])->name('store');
        Route::get('/{admin}', [AdminController::class, 'show'])->name('show');
        Route::get('/{admin}/edit', [AdminController::class, 'edit'])->name('edit');
        Route::put('/{admin}', [AdminController::class, 'update'])->name('update');
        Route::delete('/{admin}', [AdminController::class, 'destroy'])->name('destroy');
        Route::post('/{admin}/toggle-status', [AdminController::class, 'toggleStatus'])->name('toggle-status');
    });

    // Settings Management
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SuperAdminSettingsController::class, 'index'])->name('index');
        Route::get('/general', [SuperAdminSettingsController::class, 'general'])->name('general');
        Route::post('/general/update', [SuperAdminSettingsController::class, 'updateGeneral'])->name('general.update');
        Route::get('/payment', [SuperAdminSettingsController::class, 'payment'])->name('payment');
        Route::post('/payment/update', [SuperAdminSettingsController::class, 'updatePayment'])->name('payment.update');
        Route::get('/email', [SuperAdminSettingsController::class, 'email'])->name('email');
        Route::post('/email/update', [SuperAdminSettingsController::class, 'updateEmail'])->name('email.update');
        Route::get('/sms', [SuperAdminSettingsController::class, 'sms'])->name('sms');
        Route::get('/system', [SuperAdminSettingsController::class, 'system'])->name('system');
        Route::post('/system/update', [SuperAdminSettingsController::class, 'updateSystem'])->name('system.update');
        Route::get('/database', [SuperAdminSettingsController::class, 'database'])->name('database');
        Route::post('/database/update', [SuperAdminSettingsController::class, 'updateDatabase'])->name('database.update');
        Route::get('/security', [SuperAdminSettingsController::class, 'security'])->name('security');
        Route::post('/security/update', [SuperAdminSettingsController::class, 'updateSecurity'])->name('security.update');
        Route::get('/advanced', [SuperAdminSettingsController::class, 'advanced'])->name('advanced');
        Route::post('/advanced/update', [SuperAdminSettingsController::class, 'updateAdvanced'])->name('advanced.update');
        Route::get('/users', [SuperAdminSettingsController::class, 'users'])->name('users');
        Route::post('/users/update', [SuperAdminSettingsController::class, 'updateUsers'])->name('users.update');
        Route::get('/theme', [SuperAdminSettingsController::class, 'theme'])->name('theme');
        Route::post('/theme/update', [SuperAdminSettingsController::class, 'updateTheme'])->name('theme.update');
        Route::get('/developer', [SuperAdminSettingsController::class, 'developer'])->name('developer');
        Route::post('/developer/update', [SuperAdminSettingsController::class, 'updateDeveloper'])->name('developer.update');
        Route::get('/backup', [SuperAdminSettingsController::class, 'backup'])->name('backup');
        Route::get('/logs', [SuperAdminSettingsController::class, 'logs'])->name('logs');
        Route::post('/update', [SuperAdminSettingsController::class, 'update'])->name('update');
        Route::post('/create-backup', [SuperAdminSettingsController::class, 'createBackup'])->name('create-backup');
        Route::post('/export', [SuperAdminSettingsController::class, 'exportSettings'])->name('export');
        Route::post('/import', [SuperAdminSettingsController::class, 'importSettings'])->name('import');
        Route::post('/clear-cache', [SuperAdminSettingsController::class, 'clearCache'])->name('clear-cache');
        Route::post('/toggle-maintenance', [SuperAdminSettingsController::class, 'toggleMaintenance'])->name('toggle-maintenance');
    });

    // Purchase Management
    Route::prefix('purchases')->name('purchases.')->group(function () {
        Route::get('/', [PurchaseController::class, 'index'])->name('index');
        Route::get('/datatable', [PurchaseController::class, 'serverSideDataTable'])->name('datatable');
        Route::get('/create', [PurchaseController::class, 'create'])->name('create');
        Route::post('/', [PurchaseController::class, 'store'])->name('store');
        Route::get('/{purchase}', [PurchaseController::class, 'show'])->name('show');
        Route::get('/{purchase}/edit', [PurchaseController::class, 'edit'])->name('edit');
        Route::put('/{purchase}', [PurchaseController::class, 'update'])->name('update');
        Route::delete('/{purchase}', [PurchaseController::class, 'destroy'])->name('destroy');
        Route::get('/data/table', [PurchaseController::class, 'serverSideDataTable'])->name('data.table');
    });

    // Product Plans
    Route::prefix('productplans')->name('productplans.')->group(function () {
        Route::get('/', [ProductPlanController::class, 'index'])->name('index');
        Route::get('/create', [ProductPlanController::class, 'create'])->name('create');
        Route::post('/', [ProductPlanController::class, 'store'])->name('store');
        Route::get('/{productplan}', [ProductPlanController::class, 'show'])->name('show');
        Route::get('/{productplan}/edit', [ProductPlanController::class, 'edit'])->name('edit');
        Route::put('/{productplan}', [ProductPlanController::class, 'update'])->name('update');
        Route::delete('/{productplan}', [ProductPlanController::class, 'destroy'])->name('destroy');
        Route::post('/{productplan}/toggle-status', [ProductPlanController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/{productplan}/notify', [ProductPlanController::class, 'notify'])->name('notify');
    });

    // Payment System
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
            
            // QR Code Limits Management
            Route::get('/limits', [SchoolQrCodeController::class, 'limits'])->name('limits');
            Route::get('/{school}/edit-limit', [SchoolQrCodeController::class, 'editLimit'])->name('edit-limit');
            Route::put('/{school}/update-limit', [SchoolQrCodeController::class, 'updateLimit'])->name('update-limit');
            Route::get('/{school}/by-school', [SchoolQrCodeController::class, 'bySchool'])->name('by-school');
            Route::post('/limit-requests/{requestId}/process', [SchoolQrCodeController::class, 'processLimitRequest'])->name('process-limit-request');
        });
        // QR Code Payments Management
        Route::prefix('qr-code-payments')->name('qr-code-payments.')->group(function () {
            Route::get('/', [QrCodePaymentController::class, 'index'])->name('index');
            Route::get('/{payment}', [QrCodePaymentController::class, 'show'])->name('show');
            Route::post('/{payment}/refund', [QrCodePaymentController::class, 'refund'])->name('refund');
            Route::get('/statistics', [QrCodePaymentController::class, 'statistics'])->name('statistics');
            Route::get('/pricing', [QrCodePaymentController::class, 'pricing'])->name('pricing');
            Route::post('/pricing', [QrCodePaymentController::class, 'createPricing'])->name('create-pricing');
            Route::put('/pricing/{pricing}', [QrCodePaymentController::class, 'updatePricing'])->name('update-pricing');
        });
        // Payment Gateways
        Route::prefix('gateways')->name('gateways.')->group(function () {
            Route::get('/', [PaymentGatewayController::class, 'index'])->name('index');
            Route::get('/create', [PaymentGatewayController::class, 'create'])->name('create');
            Route::post('/', [PaymentGatewayController::class, 'store'])->name('store');
            Route::get('/{paymentGateway}', [PaymentGatewayController::class, 'show'])->name('show');
            Route::get('/{paymentGateway}/edit', [PaymentGatewayController::class, 'edit'])->name('edit');
            Route::put('/{paymentGateway}', [PaymentGatewayController::class, 'update'])->name('update');
            Route::delete('/{paymentGateway}', [PaymentGatewayController::class, 'destroy'])->name('destroy');
            Route::post('/{paymentGateway}/toggle-status', [PaymentGatewayController::class, 'toggleStatus'])->name('toggle-status');
            Route::post('/{paymentGateway}/assign-schools', [PaymentGatewayController::class, 'assignSchools'])->name('assign-schools');
            Route::post('/{paymentGateway}/test-connection', [PaymentGatewayController::class, 'testConnection'])->name('test-connection');
        });

        // Payment Plans
        Route::prefix('plans')->name('plans.')->group(function () {
            Route::get('/', [PaymentPlanController::class, 'index'])->name('index');
            Route::get('/create', [PaymentPlanController::class, 'create'])->name('create');
            Route::post('/', [PaymentPlanController::class, 'store'])->name('store');
            Route::get('/{paymentPlan}', [PaymentPlanController::class, 'show'])->name('show');
            Route::get('/{paymentPlan}/edit', [PaymentPlanController::class, 'edit'])->name('edit');
            Route::put('/{paymentPlan}', [PaymentPlanController::class, 'update'])->name('update');
            Route::delete('/{paymentPlan}', [PaymentPlanController::class, 'destroy'])->name('destroy');
            Route::post('/{paymentPlan}/toggle-status', [PaymentPlanController::class, 'toggleStatus'])->name('toggle-status');
            Route::post('/{paymentPlan}/assign-schools', [PaymentPlanController::class, 'assignSchools'])->name('assign-schools');
        });

        // Payment Reports
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [ReportsController::class, 'index'])->name('index');
            Route::get('/overview', [ReportsController::class, 'overview'])->name('overview');
            Route::get('/transactions', [ReportsController::class, 'transactions'])->name('transactions');
            Route::get('/schools', [ReportsController::class, 'schools'])->name('schools');
            Route::get('/gateways', [ReportsController::class, 'gateways'])->name('gateways');
            Route::get('/plans', [ReportsController::class, 'plans'])->name('plans');
            Route::get('/export', [ReportsController::class, 'export'])->name('export');
            Route::get('/data/overview', [ReportsController::class, 'getOverviewData'])->name('data.overview');
            Route::get('/data/transactions', [ReportsController::class, 'getTransactionsData'])->name('data.transactions');
            Route::get('/data/schools', [ReportsController::class, 'getSchoolsData'])->name('data.schools');
            Route::get('/data/gateways', [ReportsController::class, 'getGatewaysData'])->name('data.gateways');
            Route::get('/data/plans', [ReportsController::class, 'getPlansData'])->name('data.plans');
        });
    });

    // Role & Permission Management
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('index');
        Route::get('/create', [RoleController::class, 'create'])->name('create');
        Route::post('/', [RoleController::class, 'store'])->name('store');
        Route::get('/{role}', [RoleController::class, 'show'])->name('show');
        Route::get('/{role}/edit', [RoleController::class, 'edit'])->name('edit');
        Route::put('/{role}', [RoleController::class, 'update'])->name('update');
        Route::delete('/{role}', [RoleController::class, 'destroy'])->name('destroy');
        Route::get('/{role}/permissions', [RoleController::class, 'permissions'])->name('permissions');
        Route::post('/{role}/permissions', [RoleController::class, 'updatePermissions'])->name('update-permissions');
        Route::post('/{role}/toggle-status', [RoleController::class, 'toggleStatus'])->name('toggle-status');
    });

    Route::prefix('permissions')->name('permissions.')->group(function () {
        Route::get('/', [PermissionController::class, 'index'])->name('index');
        Route::get('/create', [PermissionController::class, 'create'])->name('create');
        Route::post('/', [PermissionController::class, 'store'])->name('store');
        Route::get('/{permission}', [PermissionController::class, 'show'])->name('show');
        Route::get('/{permission}/edit', [PermissionController::class, 'edit'])->name('edit');
        Route::put('/{permission}', [PermissionController::class, 'update'])->name('update');
        Route::delete('/{permission}', [PermissionController::class, 'destroy'])->name('destroy');
        Route::post('/{permission}/toggle-status', [PermissionController::class, 'toggleStatus'])->name('toggle-status');
        Route::get('/by-module', [PermissionController::class, 'getByModule'])->name('by-module');
    });

    Route::prefix('user-roles')->name('user-roles.')->group(function () {
        Route::get('/', [UserRoleController::class, 'index'])->name('index');
        Route::get('/{user}/assign', [UserRoleController::class, 'assign'])->name('assign');
        Route::post('/{user}', [UserRoleController::class, 'store'])->name('store');
        Route::get('/{user}', [UserRoleController::class, 'show'])->name('show');
        Route::delete('/{user}/role/{role}', [UserRoleController::class, 'removeRole'])->name('remove-role');
        Route::get('/{user}/permissions-by-module', [UserRoleController::class, 'getPermissionsByModule'])->name('permissions-by-module');
        Route::post('/bulk-assign', [UserRoleController::class, 'bulkAssign'])->name('bulk-assign');
    });

    // AI & Automation Tools
    Route::prefix('ai-automation')->name('ai-automation.')->group(function () {
        Route::get('/', [AIAutomationController::class, 'index'])->name('index');
        Route::get('/report-generator', [AIAutomationController::class, 'reportGenerator'])->name('report-generator');
        Route::get('/chatbot', [AIAutomationController::class, 'chatbot'])->name('chatbot');
        Route::post('/chatbot', [AIAutomationController::class, 'chatbot'])->name('chatbot.query');
        Route::get('/fraud-detection', [AIAutomationController::class, 'fraudDetection'])->name('fraud-detection');
    });

    // Monitoring & Analytics
    Route::prefix('monitoring')->name('monitoring.')->group(function () {
        Route::get('/', [MonitoringAnalyticsController::class, 'index'])->name('index');
        Route::get('/active-users', [MonitoringAnalyticsController::class, 'getActiveUsers'])->name('active-users');
        Route::get('/api-usage', [MonitoringAnalyticsController::class, 'getAPIUsage'])->name('api-usage');
        Route::get('/server-performance', [MonitoringAnalyticsController::class, 'getServerPerformance'])->name('server-performance');
        Route::get('/error-logs', [MonitoringAnalyticsController::class, 'getErrorLogs'])->name('error-logs');
        Route::get('/anomaly-detection', [MonitoringAnalyticsController::class, 'getAnomalyDetection'])->name('anomaly-detection');
        Route::get('/export-data', [MonitoringAnalyticsController::class, 'exportData'])->name('export-data');
    });

    // Support & Communication
    Route::prefix('support')->name('support.')->group(function () {
        Route::get('/', [SupportCommunicationController::class, 'index'])->name('index');
        Route::get('/tickets', [SupportCommunicationController::class, 'tickets'])->name('tickets');
        Route::get('/tickets/{ticket}', [SupportCommunicationController::class, 'viewTicket'])->name('view-ticket');
        Route::post('/tickets/{ticket}/update-status', [SupportCommunicationController::class, 'updateTicketStatus'])->name('update-ticket-status');
        Route::post('/tickets/{ticket}/add-reply', [SupportCommunicationController::class, 'addReply'])->name('add-reply');
        Route::get('/announcements', [SupportCommunicationController::class, 'announcements'])->name('announcements');
        Route::get('/announcements/create', [SupportCommunicationController::class, 'createAnnouncement'])->name('create-announcement');
        Route::post('/announcements', [SupportCommunicationController::class, 'storeAnnouncement'])->name('store-announcement');
        Route::get('/communication-logs', [SupportCommunicationController::class, 'communicationLogs'])->name('communication-logs');
        Route::get('/knowledge-base', [SupportCommunicationController::class, 'knowledgeBase'])->name('knowledge-base');
        Route::get('/knowledge-base/create', [SupportCommunicationController::class, 'createArticle'])->name('create-article');
        Route::post('/knowledge-base', [SupportCommunicationController::class, 'storeArticle'])->name('store-article');
        Route::post('/bulk-announcement', [SupportCommunicationController::class, 'sendBulkAnnouncement'])->name('bulk-announcement');
        Route::get('/analytics', [SupportCommunicationController::class, 'getCommunicationAnalytics'])->name('analytics');
    });

    // Developer Tools
    Route::prefix('developer-tools')->name('developer-tools.')->group(function () {
        Route::get('/', [DeveloperToolsController::class, 'index'])->name('index');
        Route::get('/deployment', [DeveloperToolsController::class, 'deploymentStatus'])->name('deployment');
        Route::post('/deploy', [DeveloperToolsController::class, 'deployToProduction'])->name('deploy');
        Route::get('/error-logs', [DeveloperToolsController::class, 'errorLogs'])->name('error-logs');
        Route::post('/clear-logs', [DeveloperToolsController::class, 'clearErrorLogs'])->name('clear-logs');
        Route::get('/feature-toggles', [DeveloperToolsController::class, 'featureToggles'])->name('feature-toggles');
        Route::post('/feature-toggles', [DeveloperToolsController::class, 'createFeatureToggle'])->name('create-feature-toggle');
        Route::post('/feature-toggles/{toggle}/toggle', [DeveloperToolsController::class, 'toggleFeature'])->name('toggle-feature');
        Route::get('/version-control', [DeveloperToolsController::class, 'versionControl'])->name('version-control');
        Route::post('/schools/{school}/update-version', [DeveloperToolsController::class, 'updateSchoolVersion'])->name('update-school-version');
        Route::get('/system-health', [DeveloperToolsController::class, 'systemHealthCheck'])->name('system-health');
        Route::post('/artisan-command', [DeveloperToolsController::class, 'runArtisanCommand'])->name('artisan-command');
    });
});
