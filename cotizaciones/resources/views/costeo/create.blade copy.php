@php
    echo view('costeo.create', [
        'cotizacion' => $cotizacion,
        'forzarCorridaPiloto' => true,
    ])->render();
@endphp
