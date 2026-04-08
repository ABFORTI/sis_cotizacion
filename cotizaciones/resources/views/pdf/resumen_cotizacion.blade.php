@php
    $logoBase64 = '';
    $logoPath = public_path('images/innovet-logo.png');

    if (file_exists($logoPath)) {
        $logoContents = file_get_contents($logoPath);

        if ($logoContents !== false) {
            $logoBase64 = 'data:image/png;base64,' . base64_encode($logoContents);
        }
    }

    $placas = [
        1 => '320 x 420 mm',
        2 => '350 x 560 mm',
        3 => '355 x 590 mm',
        4 => '420 x 420 mm',
        5 => '420 x 700 mm',
        6 => '455 x 480 mm',
        7 => '455 x 610 mm',
        8 => '450 x 620 mm',
        9 => '460 x 520 mm',
        10 => '480 x 630 mm',
        11 => '490 x 600 mm',
        12 => '520 x 455 mm',
        13 => '520 x 1000 mm',
        14 => '600 x 650 mm',
        15 => '650 x 592 mm',
        16 => '700 x 1200 mm',
        17 => '800 x 940 mm',
        18 => '1175 x 1390 mm',
        19 => '1450 x 1630 mm',
        20 => '1450 x 3000 mm',
    ];

    $indicePlaca = optional(optional($cotizacion)->costeoRequisicion)->placa_de_enfriamiento ?? '';
    $valorPlaca = $placas[$indicePlaca] ?? 'No aplica';

    $grabadosMap = [
        'numero_parte' => 'Numero de parte',
        'tipo_material' => 'Tipo de material',
        'logo_cliente' => 'Logo cliente',
        'logo_innovet' => 'Logo Innovet',
    ];

    $req = $cotizacion->requisicionCotizacion;
    $grabadosSeleccionados = [];

    foreach ($grabadosMap as $campo => $etiqueta) {
        if (!empty($req->$campo) && (int) $req->$campo === 1) {
            $grabadosSeleccionados[] = $etiqueta;
        }
    }

    if (!empty($req->sin_grabado) && (int) $req->sin_grabado === 1 && empty($grabadosSeleccionados)) {
        $grabadoFinal = 'Sin grabado';
    } else {
        $grabadoFinal = !empty($grabadosSeleccionados) ? implode(', ', $grabadosSeleccionados) : 'Sin grabado';
    }

    $largo = optional($cotizacion->cajaCliente)->caja_largo;
    $ancho = optional($cotizacion->cajaCliente)->caja_ancho;
    $alto = optional($cotizacion->cajaCliente)->caja_alto;
    $tieneMedidas = !empty($largo) || !empty($ancho) || !empty($alto);
    $defaultMedidas = $tieneMedidas ? trim(($largo ?? '') . ' x ' . ($ancho ?? '') . ' x ' . ($alto ?? '')) : '';
    $defaultContenedor = $tieneMedidas ? 'Si' : 'No';

    $proporcionaMap = [
        'pieza_mejorar' => 'Pieza a mejorar',
        'pieza_fisica_proteger' => 'Pieza fisica a proteger',
        'plano_pieza_termoformada' => 'Plano pieza termoformada',
        'igs_componente' => 'IGS componente',
        'igs_pieza_termoformada' => 'IGS pieza termoformada',
        'contenedor' => 'Contenedor',
        'plano_pieza_pdf' => 'Plano de la Pieza PDF',
        'nc' => 'NC',
        'na' => 'NA',
    ];

    $clienteProporcionaItems = [];
    $infoTermoformado = $cotizacion->termoformado;

    if ($infoTermoformado) {
        foreach ($proporcionaMap as $campo => $etiqueta) {
            if (!empty($infoTermoformado->$campo) && (int) $infoTermoformado->$campo === 1) {
                $clienteProporcionaItems[] = $etiqueta;
            }
        }

        if (!empty($infoTermoformado->termoformado_otro_checkbox)
            && (int) $infoTermoformado->termoformado_otro_checkbox === 1
            && !empty($infoTermoformado->termoformado_otro_info)) {
            $clienteProporcionaItems[] = $infoTermoformado->termoformado_otro_info;
        }
    }

    $defaultClienteProporciona = implode(', ', $clienteProporcionaItems);

    $costoUnitario = optional($cotizacion->ventasResumen)->resumen_total_costo_unit
        ?? optional($cotizacion->costeoRequisicion)->resumen_total_costo_unit;

    $resumenRows = [
        ['Cliente', $cotizacion->cliente],
        ['Nombre del Proyecto', $cotizacion->nombre_del_proyecto],
        ['Folio', $cotizacion->no_proyecto],
        ['Fecha', $cotizacion->fecha],
        ['Tipo de producto', $cotizacion->tipo_de_empaque],
        ['MOQ cotizado', optional($cotizacion->especificacionProyecto)->lote_compra ? optional($cotizacion->especificacionProyecto)->lote_compra . ' piezas' : ''],
        ['Frecuencia de compra', optional($cotizacion->especificacionProyecto)->frecuencia_compra],
        ['Dimensiones finales de pieza', trim((optional($cotizacion->especificacionProyecto)->pieza_largo ?? '') . ' x ' . (optional($cotizacion->especificacionProyecto)->pieza_ancho ?? '') . ' x ' . (optional($cotizacion->especificacionProyecto)->pieza_alto ?? '') . ' mm')],
        ['Dimensiones finales de molde', ((optional($cotizacion->costeoRequisicion)->insertos == 1)
            ? trim((optional($cotizacion->especificacionProyecto)->pieza_largo ?? '') . ' x ' . (optional($cotizacion->especificacionProyecto)->pieza_ancho ?? '') . ' x ' . (optional($cotizacion->especificacionProyecto)->pieza_alto ?? '') . ' mm')
            : trim((optional($cotizacion->costeoRequisicion)->molde_ancho ?? '') . ' x ' . (optional($cotizacion->costeoRequisicion)->molde_avance ?? '') . ' x ' . (optional($cotizacion->especificacionProyecto)->pieza_alto ?? '') . ' mm'))],
        ['Costo unitario', $costoUnitario !== null ? '$ ' . number_format((float) $costoUnitario, 2) : 'N/C'],
        ['Fabricacion de prototipo', optional($cotizacion->cotizacionAdicional)->prototipo],
        ['Especificacion de material', optional($cotizacion->especificacionProyecto)->material],
        ['Color', optional($cotizacion->especificacionProyecto)->color],
        ['Franja de color en caso de aplicar', optional($cotizacion->especificacionProyecto)->franja_color],
        ['Calibre', optional($cotizacion->especificacionProyecto)->calibre],
        ['Ancho de material', optional($cotizacion->costeoRequisicion)->hoja_ancho ? optional($cotizacion->costeoRequisicion)->hoja_ancho . ' mm' : ''],
        ['Orillas vertical (cadenas)', optional($cotizacion->costeoRequisicion)->acomodo_ancho_orillas_mm ? optional($cotizacion->costeoRequisicion)->acomodo_ancho_orillas_mm . ' mm' : ''],
        ['Medianil vertical', optional($cotizacion->costeoRequisicion)->acomodo_ancho_medianiles_mm ? optional($cotizacion->costeoRequisicion)->acomodo_ancho_medianiles_mm . ' mm' : ''],
        ['Horizontal', optional($cotizacion->costeoRequisicion)->acomodo_avance_orillas_mm ? optional($cotizacion->costeoRequisicion)->acomodo_avance_orillas_mm . ' mm' : ''],
        ['Medianil horizontal', optional($cotizacion->costeoRequisicion)->acomodo_avance_medianiles_mm ? optional($cotizacion->costeoRequisicion)->acomodo_avance_medianiles_mm . ' mm' : ''],
        ['Insertos', optional($cotizacion->costeoRequisicion)->insertos],
        ['Placa de refrigeracion', $valorPlaca],
        ['Maquina donde se produce', trim((optional($cotizacion->costeoRequisicion)->nombre_maquina_termoformado ?? '') . ', ' . (optional($cotizacion->costeoRequisicion)->nombre_maquina_suaje ?? ''), ' ,')],
        ['Broche', optional($cotizacion->requisicionCotizacion)->movimiento],
        ['Pestana', optional($cotizacion->cotizacionAdicional)->pestana],
        ['Grabados', $grabadoFinal],
        ['Estiba', optional($cotizacion->requisicionCotizacion)->tipo_estiba],
        ['Flujo de carga', optional($cotizacion->requisicionCotizacion)->tipo_flujo_carga],
        ['Dedales', optional($cotizacion->cajaCliente)->dedales],
        ['Tipo de pared', optional($cotizacion->requisicionCotizacion)->pared],
        ['Otra informacion', $datosCriticosAdicionales],
        ['Cavidades', $resumenPdfData['cavidades'] ?? optional($cotizacion->especificacionProyecto)->cavidades],
        ['Poka yoke', $resumenPdfData['poka_yoke'] ?? (optional($resumen)->poka_yoke ?? 'No')],
        ['Acomodo de pieza', $resumenPdfData['acomodo_pieza'] ?? optional($resumen)->acomodo_pieza],
        ['Contenedor del cliente', $resumenPdfData['contenedor_cliente'] ?? (optional($resumen)->contenedor_cliente ?? $defaultContenedor)],
        ['Medidas de contenedor', $resumenPdfData['medidas_contenedor'] ?? (optional($resumen)->medidas_contenedor ?? $defaultMedidas)],
        ['Estiba por contenedor', $resumenPdfData['estiba_contenedor'] ?? optional($resumen)->estiba_contenedor],
        ['Cliente proporciona', $resumenPdfData['cliente_proporciona'] ?? (optional($resumen)->cliente_proporciona ?? $defaultClienteProporciona)],
    ];

    $archivosResumen = optional($resumen)->archivos ?? collect();
    $archivosVentas = $cotizacion->archivosAdjuntos ?? collect();
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resumen de Costeo</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #1f2937;
            margin: 0;
            padding: 22px;
        }

        .header-table,
        .detail-table,
        .files-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            border: none;
            vertical-align: middle;
        }

        .logo {
            max-width: 150px;
            max-height: 60px;
        }

        .title {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            color: #6b7280;
        }

        .subtitle {
            text-align: center;
            font-size: 16px;
            margin-top: 4px;
            color: #111827;
        }

        .section-title {
            margin: 18px 0 8px;
            font-size: 13px;
            font-weight: bold;
            color: #111827;
        }

        .detail-table td,
        .detail-table th,
        .files-table td,
        .files-table th {
            border: 1px solid #000;
            padding: 6px 8px;
            vertical-align: top;
        }

        .detail-table th,
        .files-table th {
            background: #d1d5db;
            text-align: left;
        }

        .label {
            width: 33%;
            background: #f3f4f6;
            font-weight: bold;
        }

        .value {
            width: 67%;
            word-break: break-word;
        }

        .muted {
            color: #6b7280;
            font-style: italic;
        }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td style="width: 25%;">
                @if($logoBase64 !== '')
                    <img src="{{ $logoBase64 }}" alt="Innovet" class="logo">
                @endif
            </td>
            <td style="width: 50%;">
                <div class="title">RESUMEN</div>
                <div class="subtitle">{{ $cotizacion->nombre_del_proyecto }}</div>
            </td>
            <td style="width: 25%; text-align: right;">
                <strong>Folio:</strong> {{ $cotizacion->no_proyecto }}<br>
                <strong>Fecha:</strong> {{ $cotizacion->fecha }}
            </td>
        </tr>
    </table>

    <div class="section-title">Informacion general y de costeo</div>
    <table class="detail-table">
        @foreach($resumenRows as [$label, $value])
            <tr>
                <td class="label">{{ $label }}</td>
                <td class="value">{{ filled($value) ? $value : 'N/C' }}</td>
            </tr>
        @endforeach
    </table>

    <div class="section-title">Archivos adjuntos del resumen</div>
    <table class="files-table">
        <tr>
            <th style="width: 75%;">Nombre</th>
            <th style="width: 25%;">Tipo</th>
        </tr>
        @forelse($archivosResumen as $archivo)
            <tr>
                <td>{{ $archivo->nombre_original ?? basename($archivo->path) }}</td>
                <td>{{ strtoupper(pathinfo($archivo->path, PATHINFO_EXTENSION)) ?: 'N/A' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="2" class="muted">No hay archivos adjuntos del resumen.</td>
            </tr>
        @endforelse
    </table>

    <div class="section-title">Archivos de la requisicion (ventas)</div>
    <table class="files-table">
        <tr>
            <th style="width: 75%;">Nombre</th>
            <th style="width: 25%;">Tipo</th>
        </tr>
        @forelse($archivosVentas as $archivo)
            <tr>
                <td>{{ $archivo->nombre_original ?? basename($archivo->path) }}</td>
                <td>{{ strtoupper($archivo->tipo_archivo ?? pathinfo($archivo->path, PATHINFO_EXTENSION)) ?: 'N/A' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="2" class="muted">No hay archivos adjuntos por parte de comercial</td>
            </tr>
        @endforelse
    </table>
</body>
</html>