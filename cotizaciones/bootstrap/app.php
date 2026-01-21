<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
        $middleware->alias([
            'rol.ventasocosteos' => \App\Http\Middleware\RolVentasMiddleware::class,
            'rol.costeos' => \App\Http\Middleware\RolCosteosMiddleware::class,
            'rol.admin' => \App\Http\Middleware\RolAdminMiddleware::class,
            'rol.todos' => \App\Http\Middleware\RolTodosMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
