<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Superadmin\SuperAdminController;
use App\Http\Controllers\Superadmin\ThemeSettingController;
use App\Http\Controllers\Superadmin\SchoolController;
use App\Http\Controllers\Superadmin\AdminController;
use App\Http\Controllers\Superadmin\SuperadminSettingController;
use App\Http\Controllers\Superadmin\PurchaseController;
use App\Http\Controllers\Superadmin\ProductPlanController;
use App\Http\Controllers\Admin\AdminAuthController;

// 🔧 Utility Routes
Route::get('/clear-cache', function () {
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

// 🌐 Default Redirect to Login
Route::get('/', function () {
    if (auth()->check() && auth()->user()->role === 'superadmin') {
        return redirect()->route('superadmin.dashboard');
    }
    return redirect('/login');
});

// 🔐 Superadmin Login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// 🛡️ Superadmin Dashboard
Route::middleware(['auth', 'checkrole:superadmin'])->group(function () {
  Route::get('/superadmin', [SuperAdminController::class, 'index'])->name('superadmin.dashboard');
});
Route::prefix('superadmin')->name('superadmin.')->group(function () {
    

    /// Admin route is define /////

    Route::get('admins', [AdminController::class, 'index'])->name('admins.index');
    Route::post('admins/datatable', [AdminController::class, 'serverSideDataTable'])->name('admins.datatable');
    Route::get('admins/create', [AdminController::class, 'create'])->name('admins.create');
    Route::post('admins', [AdminController::class, 'store'])->name('admins.store');
    Route::get('/admins/{id}', [AdminController::class, 'show'])->name('admins.show');
    Route::get('/admins/{id}/edit', [AdminController::class, 'edit'])->name('admins.edit');
    Route::put('admins/{admin}', [AdminController::class, 'update'])->name('admins.update');
    Route::delete('admins/{admin}', [AdminController::class, 'destroy'])->name('admins.destroy');

    
    Route::resource('schools', SchoolController::class);
    
     Route::get('theme-settings', [ThemeSettingController::class, 'index'])->name('theme-settings.index');
    Route::put('theme-settings/{school}', [ThemeSettingController::class, 'update'])->name('theme-settings.update');
  
  
    Route::get('settings', [SuperadminSettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [SuperadminSettingController::class, 'update'])->name('settings.update');
    Route::get('settings/system-info', [SuperadminSettingController::class, 'getSystemInfo'])->name('settings.system');

    
// Purchase routes
    Route::get('purchases', [PurchaseController::class, 'index'])->name('purchases.index');
    Route::get('purchases/datatable', [PurchaseController::class, 'serverSideDataTable'])->name('purchases.datatable');
    Route::get('purchases/create', [PurchaseController::class, 'create'])->name('purchases.create');
    Route::post('purchases', [PurchaseController::class, 'store'])->name('purchases.store');
    Route::get('purchases/{purchase}', [PurchaseController::class, 'show'])->name('purchases.show');
    Route::get('purchases/{purchase}/edit', [PurchaseController::class, 'edit'])->name('purchases.edit');
    Route::put('purchases/{purchase}', [PurchaseController::class, 'update'])->name('purchases.update');
    Route::delete('purchases/{purchase}', [PurchaseController::class, 'destroy'])->name('purchases.destroy');

    // ✅ This is the missing route you need to fix the error:

    ///productplans routes

   Route::get('productplans', [ProductPlanController::class, 'index'])->name('productplans.index');
    Route::post('productplans/send', [ProductPlanController::class, 'sendNotification'])->name('productplans.notify');
    Route::post('productplans', [ProductPlanController::class, 'store'])->name('productplans.store');
    Route::get('productplans/{id}/edit', [ProductPlanController::class, 'edit'])->name('productplans.edit');
    Route::put('productplans/{id}', [ProductPlanController::class, 'update'])->name('productplans.update');
    Route::delete('productplans/{id}', [ProductPlanController::class, 'destroy'])->name('productplans.destroy');

   

});
Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', function () {
        if (auth()->check() && auth()->user()->role === 'admin') {
            return app(\App\Http\Controllers\Admin\DashboardController::class)->dashboard();
        }

        abort(403, 'Unauthorized access.');
    })->name('admin.dashboard');
});
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AdminAuthController::class, 'login'])->name('login.submit');
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('admin.notifications.index');
    Route::post('/notifications/mark-as-read/{id}', [NotificationController::class, 'markAsRead'])->name('admin.notifications.read');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('admin.notifications.readall');

    Route::middleware(['auth', 'is.admin:admin'])->group(function () {
        Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    });
});
