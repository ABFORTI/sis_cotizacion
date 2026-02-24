<?php
// Helper para obtener la última cotización de un cliente
use App\Models\Cotizacion;

if (!function_exists('ultimaCotizacionPorCliente')) {
    function ultimaCotizacionPorCliente($cliente) {
        return Cotizacion::where('cliente', $cliente)
            ->orderByDesc('fecha')
            ->first();
    }
}
