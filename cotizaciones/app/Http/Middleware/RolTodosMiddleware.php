<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RolTodosMiddleware
{
    /**
     * Handle an incoming request.
     * Permite acceso a ventas, costeos y admin
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, \Closure $next)
    {
        $rolesPermitidos = ['ventas', 'gerente_ventas', 'costeos', 'admin'];

        if (Auth::check() && in_array(Auth::user()->role, $rolesPermitidos, true)) {
            return $next($request);
        }

        abort(403, 'Acceso restringido.');
    }
}