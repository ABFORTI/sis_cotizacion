@php
    $formatNumber = static function ($value): string {
        return number_format((float) ($value ?? 0), 2, '.', ',');
    };

    $logoBase64 = '';
    $logoPath = public_path('images/innovet-logo.png');

    if (file_exists($logoPath)) {
        $logoContents = file_get_contents($logoPath);

        if ($logoContents !== false) {
            $logoBase64 = 'data:image/png;base64,' . base64_encode($logoContents);
        }
    }
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $meta['titulo'] ?? 'Resumen de Costeo' }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #1f2937;
            font-size: 12px;
            margin: 0;
            padding: 28px;
            background: #ffffff;
        }

        .page {
            width: 100%;
        }

        .header {
            margin-bottom: 24px;
            border-bottom: 1px solid #d1d5db;
            padding-bottom: 16px;
        }

        .header-brand {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        .header-brand td {
            vertical-align: middle;
            border: 0;
            padding: 0;
        }

        .header-brand .logo-cell {
            width: 150px;
        }

        .header-brand .title-cell {
            text-align: center;
        }

        .brand-logo {
            max-width: 125px;
            max-height: 58px;
        }

        .eyebrow {
            color: #6b7280;
            font-size: 10px;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 6px;
        }

        h1 {
            margin: 0 0 8px 0;
            font-size: 22px;
            color: #69171d;
            text-align: center;
        }

        .subtitle {
            margin: 0;
            color: #4b5563;
            font-size: 11px;
            text-align: center;
        }

        .meta {
            width: 100%;
            margin-top: 18px;
            border-collapse: collapse;
        }

        .meta td {
            width: 50%;
            padding: 6px 10px;
            border: 1px solid #e5e7eb;
            background: #f9fafb;
        }

        .meta-label {
            display: inline-block;
            min-width: 62px;
            font-weight: bold;
            color: #374151;
        }

        .table-title {
            font-size: 13px;
            font-weight: bold;
            color: #69171d;
            margin: 0 0 10px 0;
            text-align: center;
            font-weight: bold;
        }

        table.cost-table,
        table.summary-table {
            width: 100%;
            border-collapse: collapse;
        }

        .cost-table th,
        .cost-table td,
        .summary-table th,
        .summary-table td {
            border: 1px solid #d1d5db;
            padding: 10px 12px;
        }

        .cost-table th {
            background: #A41C24;
            color: #ffffff;
            text-align: center;
            font-size: 11px;
        }

        .cost-table td {
            background: #ffffff;
        }

        .cost-table .concept {
            font-weight: bold;
            color: #111827;
        }

        .cost-table .number,
        .summary-table .number {
            text-align: right;
        }

        .cost-table .subtotal-row td {
            background: #eef2f7;
            font-weight: bold;
        }

        .summary-wrapper {
            margin-top: 18px;
        }

        .summary-table th {
            width: 55%;
            background: #f3f4f6;
            text-align: left;
            color: #1f2937;
        }

        .summary-table .total-final th,
        .summary-table .total-final td {
            background: #c8e3d1;
            font-weight: bold;
            color: #0f172a;
        }

        .logo-fallback {
            color: #69171d;
            font-size: 18px;
            font-weight: bold;
            letter-spacing: 1px;
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="header">
            <table class="header-brand">
                <tr>
                    <td class="logo-cell">
                        @if($logoBase64 !== '')
                            <img src="{{ $logoBase64 }}" alt="Logo Innovet" class="brand-logo">
                        @else
                            <span class="logo-fallback">INNOVET</span>
                        @endif
                    </td>
                    <td class="title-cell">
                        <h1>{{ $meta['titulo'] ?? 'Resumen de Costeo' }}</h1>
                    </td>
                    <td class="logo-cell"></td>
                </tr>
            </table>
            <p class="subtitle">El PDF refleja los valores finales visibles en pantalla al momento del guardado.</p>
            <table class="meta">
                <tr>
                    <td>
                        <span class="meta-label">Folio:</span>
                        <span>{{ $meta['folio'] ?? 'N/A' }}</span>
                    </td>
                    <td>
                        <span class="meta-label">Fecha:</span>
                        <span>{{ $meta['fecha'] ?? 'N/A' }}</span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <span class="meta-label">Proyecto:</span>
                        <span>{{ $meta['proyecto'] ?? 'N/A' }}</span>
                    </td>
                </tr>
            </table>
        </div>

        <div>
            <p class="table-title">Tabla de resumen</p>
            <table class="cost-table">
                <thead>
                    <tr>
                        <th>Concepto</th>
                        <th>Costo total</th>
                        <th>Piezas</th>
                        <th>Costo unitario</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rows as $row)
                        <tr class="{{ str_contains(strtolower($row['concepto']), 'total') ? 'subtotal-row' : '' }}">
                            <td class="concept">{{ $row['concepto'] }}</td>
                            <td class="number">{{ $formatNumber($row['costo_total']) }}</td>
                            <td class="number">{{ $formatNumber($row['piezas']) }}</td>
                            <td class="number">{{ $formatNumber($row['costo_unitario']) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="summary-wrapper">
            <table class="summary-table">
                <tr>
                    <th>Costo unitario</th>
                    <td class="number">{{ $formatNumber($summary['costo_unitario'] ?? 0) }}</td>
                </tr>
                <tr>
                    <th>Margen administrativo</th>
                    <td class="number">{{ $formatNumber($summary['margen_administrativo'] ?? 0) }}</td>
                </tr>
                <tr class="total-final">
                    <th>Total final</th>
                    <td class="number">{{ $formatNumber($summary['total_final'] ?? 0) }}</td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>