<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Reemplazar el middleware de autenticación por defecto
        $middleware->replace(\Illuminate\Auth\Middleware\Authenticate::class, \App\Http\Middleware\Authenticate::class);
        
        $middleware->alias([
            'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
            'account.active' => \App\Http\Middleware\EnsureAccountIsActive::class,
            'not.suspended' => \App\Http\Middleware\EnsureUserNotSuspended::class,
            'admin.seller' => \App\Http\Middleware\EnsureUserIsAdmin::class,
            'admin.owner' => \App\Http\Middleware\EnsureUserIsAdminOrOwner::class,
            'owner' => \App\Http\Middleware\EnsureUserIsOwner::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->withProviders([
        App\Providers\ProfileMiddlewareServiceProvider::class,
    ])
    ->withSchedule(function (\Illuminate\Console\Scheduling\Schedule $schedule) {
        $schedule->command('proformas:expire')->everyMinute();
    })
    ->create();
