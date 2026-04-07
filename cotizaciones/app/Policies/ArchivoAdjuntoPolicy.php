<?php

namespace App\Policies;

use App\Models\ArchivoAdjunto;
use App\Models\User;

class ArchivoAdjuntoPolicy
{
    /**
     * Determine if the user can view the archive
     */
    public function view(User $user, ArchivoAdjunto $archivo)
    {
        $cotizacion = $archivo->cotizacion;

        // Admin - puede ver todos
        if ($user->role === 'admin') {
            return true;
        }

        // Ventas - puede ver sus propias cotizaciones
        if ($user->role === 'ventas') {
            return $cotizacion->user_id === $user->id;
        }

        // Costeos - puede ver cotizaciones enviadas a costeos
        if ($user->role === 'costeos') {
            return (bool) $cotizacion->enviado_a_costeos;
        }

        // Cliente - puede ver cotizaciones compartidas
        if ($user->role === 'cliente') {
            return $cotizacion->user_id === $user->id;
        }

        return false;
    }

    /**
     * Determine if the user can delete the archive
     */
    public function delete(User $user, ArchivoAdjunto $archivo)
    {
        $cotizacion = $archivo->cotizacion;

        // Admin - puede eliminar todos
        if ($user->role === 'admin') {
            return true;
        }

        // Ventas - puede eliminar sus propias cotizaciones
        if ($user->role === 'ventas') {
            return $cotizacion->user_id === $user->id;
        }

        // Costeos - no pueden eliminar
        return false;
    }
}
