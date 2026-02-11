<td class="text-center">
    @php
        use Carbon\Carbon;

        $diasTranscurridos = 0;

        if ($cotizacion->fecha_envio_costos) {

            $fechaInicio = Carbon::parse($cotizacion->fecha_envio_costos)->startOfDay();

            // Si ya regresó de costos, se detiene el conteo
            if ($cotizacion->fecha_retorno_ventas) {
                $fechaFin = Carbon::parse($cotizacion->fecha_retorno_ventas)->startOfDay();
            } else {
                // Si aún está en costos, se cuenta hasta hoy
                $fechaFin = Carbon::now()->startOfDay();
            }

            $diasTranscurridos = $fechaInicio->diffInDays($fechaFin);
        }
    @endphp

    {{ $diasTranscurridos }}
</td>
