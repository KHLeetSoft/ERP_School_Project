<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Accountant\Payment\VerificationController;

/*
|--------------------------------------------------------------------------
| Accountant Routes
|--------------------------------------------------------------------------
|
| Here are all the routes for the Accountant module. These routes are
| protected by authentication and role-based access control.
|
*/

Route::middleware(['auth', 'checkrole:accountant'])->prefix('accountant')->name('accountant.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', function () {
        return view('accountant.dashboard');
    })->name('dashboard');

    // Payment Verification
    Route::prefix('payment')->name('payment.')->group(function () {
        Route::prefix('verification')->name('verification.')->group(function () {
            Route::get('/', [VerificationController::class, 'index'])->name('index');
            Route::get('/pending', [VerificationController::class, 'pending'])->name('pending');
            Route::get('/verified', [VerificationController::class, 'verified'])->name('verified');
            Route::get('/rejected', [VerificationController::class, 'rejected'])->name('rejected');
            Route::get('/{transaction}', [VerificationController::class, 'show'])->name('show');
            Route::post('/{transaction}/verify', [VerificationController::class, 'verify'])->name('verify');
            Route::post('/{transaction}/reject', [VerificationController::class, 'reject'])->name('reject');
            Route::get('/data/table', [VerificationController::class, 'serverSideDataTable'])->name('data.table');
        });
    });

    // Fee Management
    Route::prefix('fees')->name('fees.')->group(function () {
        Route::get('/', function () {
            return view('accountant.fees.index');
        })->name('index');
        Route::get('/create', function () {
            return view('accountant.fees.create');
        })->name('create');
        Route::post('/', function () {
            // Handle fee creation
        })->name('store');
        Route::get('/{fee}', function () {
            return view('accountant.fees.show');
        })->name('show');
        Route::get('/{fee}/edit', function () {
            return view('accountant.fees.edit');
        })->name('edit');
        Route::put('/{fee}', function () {
            // Handle fee update
        })->name('update');
        Route::delete('/{fee}', function () {
            // Handle fee deletion
        })->name('destroy');
        Route::post('/{fee}/toggle-status', function () {
            // Handle fee status toggle
        })->name('toggle-status');
    });

    // Student Payments
    Route::prefix('student-payments')->name('student-payments.')->group(function () {
        Route::get('/', function () {
            return view('accountant.student-payments.index');
        })->name('index');
        Route::get('/pending', function () {
            return view('accountant.student-payments.pending');
        })->name('pending');
        Route::get('/paid', function () {
            return view('accountant.student-payments.paid');
        })->name('paid');
        Route::get('/overdue', function () {
            return view('accountant.student-payments.overdue');
        })->name('overdue');
        Route::get('/{payment}', function () {
            return view('accountant.student-payments.show');
        })->name('show');
        Route::post('/{payment}/mark-paid', function () {
            // Handle payment marking
        })->name('mark-paid');
        Route::post('/{payment}/add-fine', function () {
            // Handle fine addition
        })->name('add-fine');
    });

    // Financial Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', function () {
            return view('accountant.reports.index');
        })->name('index');
        Route::get('/daily', function () {
            return view('accountant.reports.daily');
        })->name('daily');
        Route::get('/monthly', function () {
            return view('accountant.reports.monthly');
        })->name('monthly');
        Route::get('/yearly', function () {
            return view('accountant.reports.yearly');
        })->name('yearly');
        Route::get('/student-wise', function () {
            return view('accountant.reports.student-wise');
        })->name('student-wise');
        Route::get('/fee-wise', function () {
            return view('accountant.reports.fee-wise');
        })->name('fee-wise');
        Route::get('/export', function () {
            // Handle report export
        })->name('export');
    });

    // Invoice Management
    Route::prefix('invoices')->name('invoices.')->group(function () {
        Route::get('/', function () {
            return view('accountant.invoices.index');
        })->name('index');
        Route::get('/create', function () {
            return view('accountant.invoices.create');
        })->name('create');
        Route::post('/', function () {
            // Handle invoice creation
        })->name('store');
        Route::get('/{invoice}', function () {
            return view('accountant.invoices.show');
        })->name('show');
        Route::get('/{invoice}/edit', function () {
            return view('accountant.invoices.edit');
        })->name('edit');
        Route::put('/{invoice}', function () {
            // Handle invoice update
        })->name('update');
        Route::delete('/{invoice}', function () {
            // Handle invoice deletion
        })->name('destroy');
        Route::get('/{invoice}/print', function () {
            // Handle invoice printing
        })->name('print');
        Route::get('/{invoice}/pdf', function () {
            // Handle invoice PDF generation
        })->name('pdf');
    });

    // Expense Management
    Route::prefix('expenses')->name('expenses.')->group(function () {
        Route::get('/', function () {
            return view('accountant.expenses.index');
        })->name('index');
        Route::get('/create', function () {
            return view('accountant.expenses.create');
        })->name('create');
        Route::post('/', function () {
            // Handle expense creation
        })->name('store');
        Route::get('/{expense}', function () {
            return view('accountant.expenses.show');
        })->name('show');
        Route::get('/{expense}/edit', function () {
            return view('accountant.expenses.edit');
        })->name('edit');
        Route::put('/{expense}', function () {
            // Handle expense update
        })->name('update');
        Route::delete('/{expense}', function () {
            // Handle expense deletion
        })->name('destroy');
        Route::post('/{expense}/approve', function () {
            // Handle expense approval
        })->name('approve');
        Route::post('/{expense}/reject', function () {
            // Handle expense rejection
        })->name('reject');
    });

    // Budget Management
    Route::prefix('budget')->name('budget.')->group(function () {
        Route::get('/', function () {
            return view('accountant.budget.index');
        })->name('index');
        Route::get('/create', function () {
            return view('accountant.budget.create');
        })->name('create');
        Route::post('/', function () {
            // Handle budget creation
        })->name('store');
        Route::get('/{budget}', function () {
            return view('accountant.budget.show');
        })->name('show');
        Route::get('/{budget}/edit', function () {
            return view('accountant.budget.edit');
        })->name('edit');
        Route::put('/{budget}', function () {
            // Handle budget update
        })->name('update');
        Route::delete('/{budget}', function () {
            // Handle budget deletion
        })->name('destroy');
    });

    // Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', function () {
            return view('accountant.settings.index');
        })->name('index');
        Route::get('/fee-structure', function () {
            return view('accountant.settings.fee-structure');
        })->name('fee-structure');
        Route::get('/payment-methods', function () {
            return view('accountant.settings.payment-methods');
        })->name('payment-methods');
        Route::get('/fine-rules', function () {
            return view('accountant.settings.fine-rules');
        })->name('fine-rules');
        Route::post('/update', function () {
            // Handle settings update
        })->name('update');
    });
});
