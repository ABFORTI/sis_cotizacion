<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\AuthenticationException;

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
            'rol.gerenteventas' => \App\Http\Middleware\RolGerenteVentasMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (\Throwable $e, \Illuminate\Http\Request $request) {
            if ($request->expectsJson()) {
                return null; // Deja que Laravel maneje errores de API normalmente
            }

            // Mantener el flujo normal de autenticacion (redirect a login)
            if ($e instanceof AuthenticationException) {
                return null;
            }

            $status = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;

            return response()->view('errors.error-general', [], $status);
        });
    })->create();
