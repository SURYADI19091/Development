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
        // Register custom middleware aliases
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'gate' => \App\Http\Middleware\GateMiddleware::class,
            'maintenance.access' => \App\Http\Middleware\MaintenanceModeMiddleware::class,
        ]);

        // Register middleware groups
        $middleware->group('admin', [
            'auth',
            'gate:access-admin-panel'
        ]);

        // Register global middleware
        $middleware->web(append: [
            \App\Http\Middleware\MaintenanceModeMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
