<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RolGerenteVentasMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === 'gerente_ventas') {
            return $next($request);
        }

        abort(403, 'Acceso restringido. Solo el Gerente de Ventas puede acceder a esta sección.');
    }
}
