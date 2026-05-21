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
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
            'lwp.not.level1' => \App\Http\Middleware\LwpBlockLevelOne::class,
        ]);

        $middleware->redirectTo(
            guests: function ($request) {
                if ($request->is('lwp/*') || $request->is('lwp')) {
                    return route('lwp.login');
                }
                return route('login');
            },
            users: '/dashboard'
        );

        // Custom redirect for LWP guard
        $middleware->authenticateSessions();
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
