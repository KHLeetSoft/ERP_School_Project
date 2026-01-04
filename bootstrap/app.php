<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
        'auth' => \App\Http\Middleware\Authenticate::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'is.admin' => \App\Http\Middleware\IsAdmin::class,
        'checkrole' => \App\Http\Middleware\CheckRole::class,
        'teacher' => \App\Http\Middleware\TeacherMiddleware::class,
        'student' => \App\Http\Middleware\StudentMiddleware::class,
        'parent' => \App\Http\Middleware\ParentMiddleware::class,
        'librarian' => \App\Http\Middleware\LibrarianMiddleware::class,
        'accountant' => \App\Http\Middleware\AccountantMiddleware::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
    ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
