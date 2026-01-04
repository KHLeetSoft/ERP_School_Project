<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Librarian Routes
|--------------------------------------------------------------------------
|
| Here are all the routes for the Librarian module. These routes are
| protected by authentication and role-based access control.
|
*/

// Teacher Authentication Routes (outside middleware)
// Route::prefix('librarian')->name('librarian.')->group(function () {
//     // Authentication Routes
//     Route::get('/login', [App\Http\Controllers\Librarian\LibrarianController::class, 'showLoginForm'])->name('login');
//     Route::post('/login', [App\Http\Controllers\Librarian\LibrarianController::class, 'login']);
//     Route::post('/logout', [App\Http\Controllers\Librarian\LibrarianController::class, 'logout'])->name('logout');
//     // Graceful GET logout to avoid method errors when accessed via link/address bar
//     Route::get('/logout', function () {
//         if (auth()->check()) {
//             auth()->logout();
//         }
//         request()->session()->invalidate();
//         request()->session()->regenerateToken();
//         return redirect()->route('librarian.login');
//     })->name('logout.get');
//     Route::get('/register', [App\Http\Controllers\Librarian\LibrarianController::class, 'showRegisterForm'])->name('register');
//     Route::post('/register', [App\Http\Controllers\Librarian\LibrarianController::class, 'register']);
// });
Route::prefix('librarian')->name('librarian.')->middleware('guest')->group(function () {
    Route::get('/login', [App\Http\Controllers\Librarian\LibrarianController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [App\Http\Controllers\Librarian\LibrarianController::class, 'login']);
    Route::post('/logout', [App\Http\Controllers\Librarian\LibrarianController::class, 'logout'])->name('logout');
    Route::get('/logout', function () {
         if (auth()->check()) {
             auth()->logout();
         }
         request()->session()->invalidate();
         request()->session()->regenerateToken();
         return redirect()->route('librarian.login');
     })->name('logout.get');
    Route::get('/register', [App\Http\Controllers\Librarian\LibrarianController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [App\Http\Controllers\Librarian\LibrarianController::class, 'register']);
});
Route::middleware(['auth', 'checkrole:librarian'])->prefix('librarian')->name('librarian.')->group(function () {
    
    // Dashboard
    
    // Book Categories Routes
    Route::resource('book-categories', App\Http\Controllers\Librarian\BookCategoryController::class);
    Route::get('/dashboard', [App\Http\Controllers\Librarian\LibrarianController::class, 'dashboard'])->name('dashboard');

    // Account & Profile
    Route::get('/profile', [App\Http\Controllers\Librarian\LibrarianController::class, 'profile'])->name('profile');
    Route::put('/profile', [App\Http\Controllers\Librarian\LibrarianController::class, 'updateProfile']);
    Route::get('/change-password', [App\Http\Controllers\Librarian\LibrarianController::class, 'showChangePasswordForm'])->name('change-password');
    Route::post('/change-password', [App\Http\Controllers\Librarian\LibrarianController::class, 'changePassword']);
    Route::get('/settings', [App\Http\Controllers\Librarian\LibrarianController::class, 'settings'])->name('settings');

    // Users (Students & Teachers)
    Route::get('/users', [App\Http\Controllers\Librarian\LibrarianController::class, 'users'])->name('users');
    Route::get('/users/{user}', [App\Http\Controllers\Librarian\LibrarianController::class, 'showUser'])->name('users.show');

    // Book Management
    Route::prefix('books')->name('books.')->group(function () {
        Route::get('/', [App\Http\Controllers\Librarian\BooksController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Librarian\BooksController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Librarian\BooksController::class, 'store'])->name('store');
        Route::get('/{book}', [App\Http\Controllers\Librarian\BooksController::class, 'show'])->name('show');
        Route::get('/{book}/edit', [App\Http\Controllers\Librarian\BooksController::class, 'edit'])->name('edit');
        Route::put('/{book}', [App\Http\Controllers\Librarian\BooksController::class, 'update'])->name('update');
        Route::delete('/{book}', [App\Http\Controllers\Librarian\BooksController::class, 'destroy'])->name('destroy');
        Route::post('/{book}/toggle-status', function () {})->name('toggle-status');

        // Issue-related helpers used by the view
        Route::get('/{book}/issue-form-data', [App\Http\Controllers\Librarian\BooksController::class, 'getIssueFormData'])->name('issue-form-data');
        Route::post('/issue', [App\Http\Controllers\Librarian\BooksController::class, 'issueBook'])->name('issue');
    });

    // Book Categories Management
    Route::prefix('book-categories')->name('book-categories.')->group(function () {
        Route::get('/', [App\Http\Controllers\Librarian\BookCategoryController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Librarian\BookCategoryController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Librarian\BookCategoryController::class, 'store'])->name('store');
        Route::get('/{bookCategory}', [App\Http\Controllers\Librarian\BookCategoryController::class, 'show'])->name('show');
        Route::get('/{bookCategory}/edit', [App\Http\Controllers\Librarian\BookCategoryController::class, 'edit'])->name('edit');
        Route::put('/{bookCategory}', [App\Http\Controllers\Librarian\BookCategoryController::class, 'update'])->name('update');
        Route::delete('/{bookCategory}', [App\Http\Controllers\Librarian\BookCategoryController::class, 'destroy'])->name('destroy');
        Route::patch('/{bookCategory}/toggle-status', [App\Http\Controllers\Librarian\BookCategoryController::class, 'toggleStatus'])->name('toggle-status');
    });

    // Book Categories
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', function () {
            return view('librarian.categories.index');
        })->name('index');
        Route::get('/create', function () {
            return view('librarian.categories.create');
        })->name('create');
        Route::post('/', function () {
            // Handle category creation
        })->name('store');
        Route::get('/{category}', function () {
            return view('librarian.categories.show');
        })->name('show');
        Route::get('/{category}/edit', function () {
            return view('librarian.categories.edit');
        })->name('edit');
        Route::put('/{category}', function () {
            // Handle category update
        })->name('update');
        Route::delete('/{category}', function () {
            // Handle category deletion
        })->name('destroy');
    });

    // Book Borrowing
    Route::prefix('borrowing')->name('borrowing.')->group(function () {
        Route::get('/', function () {
            return view('librarian.borrowing.index');
        })->name('index');
        Route::get('/borrow', function () {
            return view('librarian.borrowing.borrow');
        })->name('borrow');
        Route::post('/borrow', function () {
            // Handle book borrowing
        })->name('store-borrow');
        Route::get('/return', function () {
            return view('librarian.borrowing.return');
        })->name('return');
        Route::post('/return', function () {
            // Handle book return
        })->name('store-return');
        Route::get('/{borrowing}', function () {
            return view('librarian.borrowing.show');
        })->name('show');
        Route::post('/{borrowing}/extend', function () {
            // Handle borrowing extension
        })->name('extend');
    });

    // Book Issues (resource + utilities)
    Route::resource('book-issues', App\Http\Controllers\Librarian\BookIssueController::class);
    Route::get('/book-issues/{bookIssue}/return', [App\Http\Controllers\Librarian\BookIssueController::class, 'showReturnForm'])->name('book-issues.return');
    Route::post('/book-issues/{bookIssue}/return', [App\Http\Controllers\Librarian\BookIssueController::class, 'processReturn'])->name('book-issues.process-return');
    Route::get('/book-issues/overdue', [App\Http\Controllers\Librarian\BookIssueController::class, 'overdue'])->name('book-issues.overdue');
    Route::post('/book-issues/mark-overdue', [App\Http\Controllers\Librarian\BookIssueController::class, 'markOverdue'])->name('book-issues.mark-overdue');
    Route::get('/book-issues/student/{student}/history', [App\Http\Controllers\Librarian\BookIssueController::class, 'studentHistory'])->name('book-issues.student-history');
    Route::get('/book-issues/book/{book}/history', [App\Http\Controllers\Librarian\BookIssueController::class, 'bookHistory'])->name('book-issues.book-history');

    // Student Management (Library Context)
    Route::prefix('students')->name('students.')->group(function () {
        Route::get('/', function () {
            return view('librarian.students.index');
        })->name('index');
        Route::get('/{student}', function () {
            return view('librarian.students.show');
        })->name('show');
        Route::get('/{student}/borrowing-history', function () {
            return view('librarian.students.borrowing-history');
        })->name('borrowing-history');
        Route::get('/{student}/current-books', function () {
            return view('librarian.students.current-books');
        })->name('current-books');
    });

    // Fine Management
    Route::prefix('fines')->name('fines.')->group(function () {
        Route::get('/', function () {
            return view('librarian.fines.index');
        })->name('index');
        Route::get('/{fine}', function () {
            return view('librarian.fines.show');
        })->name('show');
        Route::post('/{fine}/pay', function () {
            // Handle fine payment
        })->name('pay');
        Route::post('/{fine}/waive', function () {
            // Handle fine waiver
        })->name('waive');
    });

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', function () {
            return view('librarian.reports.index');
        })->name('index');
        Route::get('/books', function () {
            return view('librarian.reports.books');
        })->name('books');
        Route::get('/borrowing', function () {
            return view('librarian.reports.borrowing');
        })->name('borrowing');
        Route::get('/fines', function () {
            return view('librarian.reports.fines');
        })->name('fines');
        Route::get('/popular-books', function () {
            return view('librarian.reports.popular-books');
        })->name('popular-books');
        Route::get('/export', function () {
            // Handle report export
        })->name('export');
    });

    // Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/borrowing-rules', function () {
            return view('librarian.settings.borrowing-rules');
        })->name('borrowing-rules');
        Route::get('/fine-rules', function () {
            return view('librarian.settings.fine-rules');
        })->name('fine-rules');
        Route::post('/update', function () {
            // Handle settings update
        })->name('update');
    });

    // Notifications
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [App\Http\Controllers\Librarian\NotificationController::class, 'index'])->name('index');
        Route::get('/overdue-books', [App\Http\Controllers\Librarian\NotificationController::class, 'overdue'])->name('overdue-books');
        Route::get('/due-today', [App\Http\Controllers\Librarian\NotificationController::class, 'dueToday'])->name('due-today');
        Route::post('/mark-read', [App\Http\Controllers\Librarian\NotificationController::class, 'markRead'])->name('mark-read');
    });

    // Messages / Chat
    Route::prefix('messages')->name('messages.')->group(function () {
        Route::get('/', [App\Http\Controllers\Librarian\ChatController::class, 'index'])->name('index');
        Route::get('/{thread}', [App\Http\Controllers\Librarian\ChatController::class, 'show'])->name('show');
        Route::post('/{thread}', [App\Http\Controllers\Librarian\ChatController::class, 'send'])->name('send');
    });
});
