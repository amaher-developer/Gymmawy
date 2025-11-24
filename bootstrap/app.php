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
        // Register module middleware aliases
        $middleware->alias([
            'front' => \Modules\Generic\Http\Middleware\Lang::class,
            'under_maintenance' => \Modules\Generic\Http\Middleware\UnderMaintenance::class,
            'check_api_token' => \Modules\Generic\Http\Middleware\CheckApiAuthToken::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
