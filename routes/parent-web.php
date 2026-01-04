<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Parent Routes
|--------------------------------------------------------------------------
|
| Here are all the routes for the Parent module. These routes are
| protected by authentication and role-based access control.
|
*/

Route::middleware(['auth', 'checkrole:parent'])->prefix('parent')->name('parent.')->group(function () {
    
    // Dashboard - Placeholder route
    Route::get('/dashboard', function () {
        return view('parent.dashboard');
    })->name('dashboard');
    
    // Profile Management - Placeholder routes
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', function () {
            return view('parent.profile.index');
        })->name('index');
        Route::get('/edit', function () {
            return view('parent.profile.edit');
        })->name('edit');
        Route::put('/update', function () {
            return redirect()->back()->with('success', 'Profile updated successfully.');
        })->name('update');
        Route::post('/change-password', function () {
            return redirect()->back()->with('success', 'Password changed successfully.');
        })->name('change-password');
    });
    
    // Children Management - Placeholder routes
    Route::prefix('children')->name('children.')->group(function () {
        Route::get('/', function () {
            return view('parent.children.index');
        })->name('index');
        Route::get('/{child}', function ($child) {
            return view('parent.children.show', compact('child'));
        })->name('show');
        Route::get('/{child}/profile', function ($child) {
            return view('parent.children.profile', compact('child'));
        })->name('profile');
    });
    
    // Child Attendance - Placeholder routes
    Route::prefix('attendance')->name('attendance.')->group(function () {
        Route::get('/', function () {
            return view('parent.attendance.index');
        })->name('index');
        Route::get('/child/{child}', function ($child) {
            return view('parent.attendance.child', compact('child'));
        })->name('child');
        Route::get('/report/{child}', function ($child) {
            return view('parent.attendance.report', compact('child'));
        })->name('report');
    });
    
    // Child Grades & Results - Placeholder routes
    Route::prefix('grades')->name('grades.')->group(function () {
        Route::get('/', function () {
            return view('parent.grades.index');
        })->name('index');
        Route::get('/child/{child}', function ($child) {
            return view('parent.grades.child', compact('child'));
        })->name('child');
        Route::get('/subject/{child}/{subject}', function ($child, $subject) {
            return view('parent.grades.subject', compact('child', 'subject'));
        })->name('subject');
        Route::get('/report-card/{child}', function ($child) {
            return view('parent.grades.report-card', compact('child'));
        })->name('report-card');
        Route::get('/download-report/{child}', function ($child) {
            return response()->download(storage_path('app/reports/parent-report.pdf'));
        })->name('download-report');
    });
    
    // Child Schedule & Timetable - Placeholder routes
    Route::prefix('schedule')->name('schedule.')->group(function () {
        Route::get('/', function () {
            return view('parent.schedule.index');
        })->name('index');
        Route::get('/child/{child}', function ($child) {
            return view('parent.schedule.child', compact('child'));
        })->name('child');
        Route::get('/weekly/{child}', function ($child) {
            return view('parent.schedule.weekly', compact('child'));
        })->name('weekly');
        Route::get('/monthly/{child}', function ($child) {
            return view('parent.schedule.monthly', compact('child'));
        })->name('monthly');
    });
    
    // Payments - Placeholder routes
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/', function () {
            return view('parent.payments.index');
        })->name('index');
        Route::get('/child/{child}', function ($child) {
            return view('parent.payments.child', compact('child'));
        })->name('child');
        Route::get('/history', function () {
            return view('parent.payments.history');
        })->name('history');
        Route::get('/invoice/{invoice}', function ($invoice) {
            return view('parent.payments.invoice', compact('invoice'));
        })->name('invoice');
        Route::post('/pay/{invoice}', function ($invoice) {
            return redirect()->back()->with('success', 'Payment processed successfully.');
        })->name('pay');
        Route::get('/payment-success/{payment}', function ($payment) {
            return view('parent.payments.success', compact('payment'));
        })->name('payment-success');
    });
    
    // Communication - Placeholder routes
    Route::prefix('communication')->name('communication.')->group(function () {
        Route::get('/', function () {
            return view('parent.communication.index');
        })->name('index');
        Route::get('/messages', function () {
            return view('parent.communication.messages');
        })->name('messages');
        Route::get('/messages/{message}', function ($message) {
            return view('parent.communication.show-message', compact('message'));
        })->name('show-message');
        Route::post('/messages/{message}/reply', function ($message) {
            return redirect()->back()->with('success', 'Reply sent successfully.');
        })->name('reply');
        Route::get('/announcements', function () {
            return view('parent.communication.announcements');
        })->name('announcements');
        Route::get('/announcements/{announcement}', function ($announcement) {
            return view('parent.communication.show-announcement', compact('announcement'));
        })->name('show-announcement');
    });
});
