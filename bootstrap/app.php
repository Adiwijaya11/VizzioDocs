<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'quota' => \App\Http\Middleware\CheckQuota::class,
            'deduct' => \App\Http\Middleware\DeductQuota::class,
            'tool.lock' => \App\Http\Middleware\CheckToolLock::class,
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'premium.expired' => \App\Http\Middleware\CheckPremiumExpiration::class,
        ]);

        $middleware->appendToGroup('web', 'premium.expired');

        // Exclude Midtrans webhook from CSRF (Midtrans doesn't send CSRF token)
        $middleware->validateCsrfTokens(except: [
            '/payment/notification',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
