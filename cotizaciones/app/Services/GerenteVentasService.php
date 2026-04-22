<?php

namespace App\Services;

use App\Models\User;

class GerenteVentasService
{
    public function obtenerUsuariosVentas()
    {
        return User::query()
            ->where('role', 'ventas')
            ->orderBy('name')
            ->get(['id', 'name', 'email']);
    }

    public function obtenerActividadUsuario(User $user): array
    {
        abort_unless($user->role === 'ventas', 404, 'Usuario de ventas no encontrado.');

        $cotizaciones = $user->cotizaciones()
            ->whereNotNull('cliente')
            ->where('cliente', '!=', '')
            ->orderBy('cliente')
            ->orderBy('no_proyecto')
            ->get(['id', 'cliente', 'no_proyecto', 'nombre_del_proyecto', 'enviado_a_costeos', 'enviado_a_ventas']);

        $clientes = $cotizaciones
            ->groupBy('cliente')
            ->map(function ($items, $cliente) {
                return [
                    'nombre' => $cliente,
                    'total_cotizaciones' => $items->count(),
                    'proyectos' => $items->map(function ($cotizacion) {
                        return [
                            'id' => $cotizacion->id,
                            'folio' => $cotizacion->no_proyecto,
                            'nombre_proyecto' => $cotizacion->nombre_del_proyecto,
                            'estado_flujo' => $this->resolverEstadoFlujo($cotizacion),
                        ];
                    })->values()->all(),
                ];
            })
            ->values()
            ->all();

        return [
            'nombre' => $user->name,
            'correo' => $user->email,
            'total_requisiciones' => $user->cotizaciones()->count(),
            'clientes' => $clientes,
        ];
    }

    private function resolverEstadoFlujo($cotizacion): string
    {
        if ($cotizacion->enviado_a_ventas) {
            return 'Regresada al usuario';
        }

        if ($cotizacion->enviado_a_costeos) {
            return 'En revisión de costeos';
        }

        return 'Aún no se manda';
    }
}
