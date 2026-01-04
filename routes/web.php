<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public Routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

// General Dashboard Route - redirects based on user role
Route::get('/dashboard', function () {
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
    
    // If not authenticated or no role, redirect to login
    return redirect()->route('login');
})->middleware('auth')->name('dashboard');

// Admin Login Routes (must be before admin-web.php to avoid conflicts)
Route::get('/admin/login', [App\Http\Controllers\Admin\Auth\AdminLoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [App\Http\Controllers\Admin\Auth\AdminLoginController::class, 'login']);

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

// Logout Routes
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::post('/admin/logout', [App\Http\Controllers\Admin\Auth\AdminLoginController::class, 'logout'])->name('admin.logout');

// Include Module Routes
Route::group([], function () {
    require __DIR__ . '/superadmin-web.php';
    require __DIR__ . '/admin-web.php';
    require __DIR__ . '/librarian-web.php';
    require __DIR__ . '/accountant-web.php';
    require __DIR__ . '/teacher-web.php';
    require __DIR__ . '/student-web.php';
    require __DIR__ . '/parent-web.php';
});

// Fallback Route
Route::fallback(function () {
    return view('errors.404');
});
