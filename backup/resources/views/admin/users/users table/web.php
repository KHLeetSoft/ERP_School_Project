
<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Superadmin\SuperAdminController;
use App\Http\Controllers\Superadmin\AdminController;
use App\Http\Controllers\Superadmin\PurchaseController;
use App\Http\Controllers\Superadmin\ProductPlanController;
use App\Http\Controllers\Superadmin\SchoolController;
use App\Http\Controllers\Superadmin\ThemeSettingController;
use App\Http\Controllers\Superadmin\SuperadminSettingController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\TeacherController;
// use App\Http\Controllers\Admin\LibrarianController;
// use App\Http\Controllers\Admin\StudentController;
// use App\Http\Controllers\Admin\ParentController;

// Utility Routes
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

// Redirect to login
Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->role === 'superadmin'
            ? redirect()->route('superadmin.dashboard')
            : redirect()->route('admin.dashboard');
    }
    return redirect('/login');
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
    Route::get('admins/{id}', [AdminController::class, 'show'])->name('admins.show');
    Route::get('admins/{id}/edit', [AdminController::class, 'edit'])->name('admins.edit');
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
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AdminAuthController::class, 'login'])->name('login.submit');

    // POST logout (actual logout logic)
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');

    // Optional: GET fallback to avoid 405 error on direct URL
    Route::get('logout', function () {
        return redirect()->route('admin.login');
    })->name('logout.get');

    
    Route::middleware(['auth', 'checkrole:admin'])->group(function () {
        Route::get('dashboard', [DashboardController::class,'dashboard'])->name('dashboard');

         // Users Module
         Route::prefix('users')->name('users.')->group(function () {

            
            Route::resource('teachers', TeacherController::class);
          

            // Route::resource('librarians', LibrarianController::class);
            // Route::get('librarians/datatable', [LibrarianController::class, 'getDatatable'])->name('librarians.datatable');
     
            //   // ✅ StudentController route added
            // Route::resource('students', StudentController::class);
            // Route::get('students/datatable', [StudentController::class, 'getDatatable'])->name('students.datatable');

            // Route::resource('parents', ParentController::class);
            // Route::get('parents/datatable', [ParentController::class, 'getDatatable'])->name('parents.datatable');
        });
        Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::post('notifications/mark-as-read/{id}', [NotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::post('notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.readall');
   
        
 
      
    });
});

// Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
//     Route::get('/notifications', [NotificationController::class, 'index'])->name('admin.notifications.index');
//     Route::post('/notifications/mark-as-read/{id}', [NotificationController::class, 'markAsRead'])->name('admin.notifications.read');
//     Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('admin.notifications.readall');
// });
