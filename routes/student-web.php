<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Student Routes
|--------------------------------------------------------------------------
|
| Here are all the routes for the Student module. These routes are
| protected by authentication and role-based access control.
|
*/

Route::middleware(['auth', 'checkrole:student'])->prefix('student')->name('student.')->group(function () {
    
    // Dashboard - Placeholder route
    Route::get('/dashboard', function () {
        return view('student.dashboard');
    })->name('dashboard');
    
    // Profile Management - Placeholder routes
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', function () {
            return view('student.profile.index');
        })->name('index');
        Route::get('/edit', function () {
            return view('student.profile.edit');
        })->name('edit');
        Route::put('/update', function () {
            return redirect()->back()->with('success', 'Profile updated successfully.');
        })->name('update');
        Route::post('/change-password', function () {
            return redirect()->back()->with('success', 'Password changed successfully.');
        })->name('change-password');
    });
    
    // Attendance - Placeholder routes
    Route::prefix('attendance')->name('attendance.')->group(function () {
        Route::get('/', function () {
            return view('student.attendance.index');
        })->name('index');
        Route::get('/history', function () {
            return view('student.attendance.history');
        })->name('history');
        Route::get('/report', function () {
            return view('student.attendance.report');
        })->name('report');
    });
    
    // Grades & Results - Placeholder routes
    Route::prefix('grades')->name('grades.')->group(function () {
        Route::get('/', function () {
            return view('student.grades.index');
        })->name('index');
        Route::get('/subject/{subject}', function ($subject) {
            return view('student.grades.subject', compact('subject'));
        })->name('subject');
        Route::get('/report-card', function () {
            return view('student.grades.report-card');
        })->name('report-card');
        Route::get('/download-report', function () {
            return response()->download(storage_path('app/reports/student-report.pdf'));
        })->name('download-report');
    });
    
    // Schedule & Timetable - Placeholder routes
    Route::prefix('schedule')->name('schedule.')->group(function () {
        Route::get('/', function () {
            return view('student.schedule.index');
        })->name('index');
        Route::get('/weekly', function () {
            return view('student.schedule.weekly');
        })->name('weekly');
        Route::get('/monthly', function () {
            return view('student.schedule.monthly');
        })->name('monthly');
    });
    
    // Library - Placeholder routes
    Route::prefix('library')->name('library.')->group(function () {
        Route::get('/', function () {
            return view('student.library.index');
        })->name('index');
        Route::get('/search', function () {
            return view('student.library.search');
        })->name('search');
        Route::get('/book/{book}', function ($book) {
            return view('student.library.show', compact('book'));
        })->name('show');
        Route::post('/borrow/{book}', function ($book) {
            return redirect()->back()->with('success', 'Book borrowed successfully.');
        })->name('borrow');
        Route::get('/borrowed-books', function () {
            return view('student.library.borrowed-books');
        })->name('borrowed-books');
        Route::post('/return/{borrowing}', function ($borrowing) {
            return redirect()->back()->with('success', 'Book returned successfully.');
        })->name('return');
    });
    
    // Payments - Placeholder routes
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/', function () {
            return view('student.payments.index');
        })->name('index');
        Route::get('/history', function () {
            return view('student.payments.history');
        })->name('history');
        Route::get('/invoice/{invoice}', function ($invoice) {
            return view('student.payments.invoice', compact('invoice'));
        })->name('invoice');
        Route::post('/pay/{invoice}', function ($invoice) {
            return redirect()->back()->with('success', 'Payment processed successfully.');
        })->name('pay');
        Route::get('/payment-success/{payment}', function ($payment) {
            return view('student.payments.success', compact('payment'));
        })->name('payment-success');
    });
});
