<td class="text-center">
    @php
        use Carbon\Carbon;

        $diasTranscurridos = 0;

        if ($cotizacion->fecha_envio_costos) {

            $fechaInicio = Carbon::parse($cotizacion->fecha_envio_costos)->startOfDay();

            if ($cotizacion->fecha_retorno_ventas) {
                $fechaFin = Carbon::parse($cotizacion->fecha_retorno_ventas)->startOfDay();
            } else {
                $fechaFin = Carbon::now()->startOfDay();
            }

            $diasTranscurridos = $fechaInicio->diffInDays($fechaFin);
        }
    @endphp

    {{ $diasTranscurridos }}
</td>
