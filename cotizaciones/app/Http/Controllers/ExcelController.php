<?php

namespace App\Http\Controllers;

use App\Models\Cotizacion;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Font;
use Illuminate\Support\Facades\Auth;
use Dompdf\Dompdf;
use Dompdf\Options;

class ExcelController extends Controller
{
    public function generarCotizacionExcel($id)
    {
        $cotizacion = Cotizacion::with([
            'especificacionProyecto',
            'costeoRequisicion',
            'archivosAdjuntos'
        ])->findOrFail($id);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Configuración inicial
        $sheet->getDefaultColumnDimension()->setWidth(15);
        $sheet->getDefaultRowDimension()->setRowHeight(25);

        // Estilos reutilizables
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '2B2B2B']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ]
        ];

        $titleRedStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'B50B0B']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true
            ]
        ];

        $grayCellStyle = [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'BFBFBF']
            ],
            'font' => [
                'bold' => true
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ];

        $lightGrayCellStyle = [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'D9D9D9']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ];

        $greenPriceStyle = [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '92D050']
            ],
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ];

        $specHeaderStyle = [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'D9D9D9']
            ],
            'font' => [
                'bold' => true
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN
                ]
            ]
        ];

        $specDataStyle = [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'BFBFBF']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN
                ]
            ]
        ];

        // Mostrar el logo de INNOVET
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo de INNOVET');
        $drawing->setPath(public_path('images/innovet-logo.png')); // Ruta al logo
        $drawing->setHeight(80); // Altura del logo
        $drawing->setCoordinates('A1'); // Posición del logo
        $drawing->setWorksheet($sheet);

        $sheet->setCellValue('F1', 'Folio:');
        $sheet->setCellValue('G1', $cotizacion->no_proyecto);
        $sheet->setCellValue('F2', 'Fecha:');
        $sheet->setCellValue('G2', $cotizacion->fecha);

        // Información del cliente
        $sheet->setCellValue('A4', $cotizacion->cliente);
        $sheet->getStyle('A4')->getFont()->setSize(14)->setBold(true);
        $sheet->getStyle('A4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->mergeCells('A4:H4');

        $sheet->setCellValue('A5', $cotizacion->puesto);
        $sheet->getStyle('A5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->mergeCells('A5:H5');

        $sheet->setCellValue('A6', $cotizacion->correo);
        $sheet->getStyle('A6')->getFont()->getColor()->setRGB('B50B0B');
        $sheet->mergeCells('A6:F6');

        $sheet->setCellValue('G6', 'Tel.');
        $sheet->setCellValue('H6', $cotizacion->telefono);
        $sheet->getStyle('H6')->getFont()->setBold(true)->getColor()->setRGB('B50B0B');

        // PRIMERA TABLA - Charola 15 cavidades
        $currentRow = 8;

        // Encabezado de la tabla principal
        $sheet->setCellValue('A' . $currentRow, '');
        $sheet->setCellValue('B' . $currentRow, 'Descripción del proyecto');
        $sheet->setCellValue('G' . $currentRow, 'Piezas (MOQ)');
        $sheet->setCellValue('H' . $currentRow, 'Precio Unitario (MXN)');

        $sheet->getStyle('A' . $currentRow . ':H' . $currentRow)->applyFromArray($headerStyle);
        $sheet->mergeCells('B' . $currentRow . ':F' . $currentRow);

        $currentRow++;

        // Fila 1 - Charola (A9 hasta A11)
        $sheet->setCellValue('A9', '1');
        $sheet->getStyle('A9')->applyFromArray($lightGrayCellStyle);
        $sheet->mergeCells('A9:A11');

        // Título del proyecto
        $sheet->setCellValue('B' . $currentRow, $cotizacion->nombre_del_proyecto);
        $sheet->getStyle('B' . $currentRow)->applyFromArray($titleRedStyle);
        $sheet->mergeCells('B' . $currentRow . ':F' . $currentRow);
        $sheet->getStyle('B' . $currentRow . ':F' . $currentRow)->applyFromArray($grayCellStyle);

        $currentRow++;

        // Tabla de especificaciones
        $specStartRow = $currentRow;

        // Encabezado de especificaciones
        $sheet->setCellValue('B' . $currentRow, 'Dimensiones');
        $sheet->setCellValue('C' . $currentRow, 'Frecuencia de compra');
        $sheet->setCellValue('D' . $currentRow, 'Especificación del material');
        $sheet->setCellValue('E' . $currentRow, 'Espesor');
        $sheet->setCellValue('F' . $currentRow, 'Color');

        $sheet->getStyle('B' . $currentRow . ':F' . $currentRow)->applyFromArray($specHeaderStyle);

        $currentRow++;

        // Datos de especificaciones
        $sheet->setCellValue('B' . $currentRow, $cotizacion->especificacionProyecto->pieza_largo . ' x ' .
            $cotizacion->especificacionProyecto->pieza_ancho . ' x ' .
            $cotizacion->especificacionProyecto->pieza_alto . ' mm');
        $sheet->setCellValue('C' . $currentRow, $cotizacion->especificacionProyecto->frecuencia_compra);
        $sheet->setCellValue('D' . $currentRow, $cotizacion->especificacionProyecto->material);
        $sheet->setCellValue('E' . $currentRow, $cotizacion->especificacionProyecto->calibre);
        $sheet->setCellValue('F' . $currentRow, $cotizacion->especificacionProyecto->color);

        $sheet->getStyle('B' . $currentRow . ':F' . $currentRow)->applyFromArray($specDataStyle);

        // Piezas y precio - abarcan desde fila 9 hasta fila 11 (3 filas)
        $sheet->setCellValue('G9', $cotizacion->especificacionProyecto->lote_compra);
        $sheet->getStyle('G9')->applyFromArray($grayCellStyle);
        $sheet->mergeCells('G9:G11');

        // Preferir el resumen guardado si existe
        $ventasResumen = $cotizacion->ventasResumen ?? null;
        $precioUnitario = $ventasResumen->resumen_total_costo_unit ?? $cotizacion->costeoRequisicion->resumen_total_costo_unit ?? 0;

        $sheet->setCellValue('H9', '$ ' . number_format($precioUnitario, 2));
        $sheet->getStyle('H9')->applyFromArray($greenPriceStyle);
        $sheet->mergeCells('H9:H11');

        // Ajustar dimensiones de columnas para la tabla de especificaciones
        $sheet->getColumnDimension('A')->setWidth(8);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(25);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(15);
        $sheet->getColumnDimension('G')->setWidth(18);
        $sheet->getColumnDimension('H')->setWidth(22);

        // Ajustar altura de filas para acomodar texto envuelto
        $sheet->getRowDimension($specStartRow)->setRowHeight(30);
        $sheet->getRowDimension($specStartRow + 1)->setRowHeight(30);
        $sheet->getRowDimension(8)->setRowHeight(30); // Encabezado principal
        $sheet->getRowDimension(9)->setRowHeight(25); // Título del proyecto

        $currentRow += 3;

        // SEGUNDA TABLA - Desarrollo de Herramientas
        $sheet->setCellValue('A' . $currentRow, '');
        $sheet->setCellValue('B' . $currentRow, 'Desarrollo de Herramentales.');
        $sheet->setCellValue('G' . $currentRow, '');
        $sheet->setCellValue('H' . $currentRow, 'Precio Unitario (MXN)');

        $sheet->getStyle('A' . $currentRow . ':H' . $currentRow)->applyFromArray($headerStyle);
        $sheet->mergeCells('B' . $currentRow . ':F' . $currentRow);

        $currentRow++;

        // Fila 2 - Desarrollo de Herramentales (abarcar 2 filas)
        $herramentalesNumRow = $currentRow;
        $sheet->setCellValue('A' . $herramentalesNumRow, '2');
        $sheet->getStyle('A' . $herramentalesNumRow)->applyFromArray($lightGrayCellStyle);
        $sheet->mergeCells('A' . $herramentalesNumRow . ':A' . ($herramentalesNumRow + 1));

        $sheet->setCellValue('B' . $currentRow, 'Desarrollo de Herramentales');
        $sheet->getStyle('B' . $currentRow)->applyFromArray($titleRedStyle);
        $sheet->mergeCells('B' . $currentRow . ':F' . $currentRow);
        $sheet->getStyle('B' . $currentRow . ':F' . $currentRow)->applyFromArray($grayCellStyle);

        $currentRow++;

        $sheet->setCellValue('B' . $currentRow, 'Se considera entrega de 3 muestras para liberación');
        $sheet->getStyle('B' . $currentRow)->applyFromArray($lightGrayCellStyle);
        $sheet->getStyle('B' . $currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setWrapText(true);
        $sheet->mergeCells('B' . $currentRow . ':F' . $currentRow);
        $sheet->getRowDimension($currentRow)->setRowHeight(25);

        // Cantidad y precio para herramentales 
        $herramentalesStartRow = $currentRow - 1;
        $sheet->setCellValue('G' . $herramentalesStartRow, '0');
        $sheet->getStyle('G' . $herramentalesStartRow)->applyFromArray($grayCellStyle);
        $sheet->mergeCells('G' . $herramentalesStartRow . ':G' . $currentRow);

        $precioHerramentales = $ventasResumen->resumen_total_precio_venta ?? $cotizacion->costeoRequisicion->TOTAL_VENTAS ?? 0;

        $sheet->setCellValue('H' . $herramentalesStartRow, '$ ' . number_format($precioHerramentales, 2));
        $sheet->getStyle('H' . $herramentalesStartRow)->applyFromArray($greenPriceStyle);
        $sheet->mergeCells('H' . $herramentalesStartRow . ':H' . $currentRow);

        $currentRow += 2;

        // SECCIÓN DE IMAGEN ILUSTRATIVA
        // Verificar si existe una imagen adjunta en la cotización
        $imagenPath = null;
        if ($cotizacion->archivosAdjuntos && $cotizacion->archivosAdjuntos->isNotEmpty()) {
            // Buscar la primera imagen en los archivos adjuntos
            foreach ($cotizacion->archivosAdjuntos as $archivo) {
                $extension = strtolower(pathinfo($archivo->path, PATHINFO_EXTENSION));
                if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'bmp'])) {
                    // Usar storage_path para acceder a los archivos en storage/app/public
                    $fullPath = storage_path('app/public/' . $archivo->path);
                    if (file_exists($fullPath)) {
                        $imagenPath = $fullPath;
                        break; // Sale del loop
                    }
                }
            }
        }

        // Si se encontró una imagen, insertarla
        if ($imagenPath && file_exists($imagenPath)) {
            // Crear espacio para la imagen
            $sheet->mergeCells('A' . $currentRow . ':H' . ($currentRow + 8));

            // Insertar la imagen
            $imageDrawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $imageDrawing->setName('Imagen Ilustrativa');
            $imageDrawing->setDescription('Imagen ilustrativa del proyecto');
            $imageDrawing->setPath($imagenPath);
            $imageDrawing->setHeight(200); // Altura de la imagen
            $imageDrawing->setCoordinates('A' . $currentRow);
            $imageDrawing->setOffsetX(50); // Centrar horizontalmente
            $imageDrawing->setOffsetY(10);
            $imageDrawing->setWorksheet($sheet);

            // Agregar texto descriptivo
            $sheet->setCellValue('A' . ($currentRow + 9), 'Imagen ilustrativa:');
            $sheet->getStyle('A' . ($currentRow + 9))->getFont()->setColor(new Color('B50B0B'))->setBold(true);
            $sheet->getStyle('A' . ($currentRow + 9))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->mergeCells('A' . ($currentRow + 9) . ':H' . ($currentRow + 9));

            $currentRow += 11; // Ajustar la fila actual después de la imagen
        } else {
            // Si no hay imagen, agregar espacio en blanco o mensaje
            $sheet->setCellValue('A' . $currentRow, 'Imagen ilustrativa: No disponible');
            $sheet->getStyle('A' . $currentRow)->getFont()->setColor(new Color('B50B0B'))->setBold(true);
            $sheet->getStyle('A' . $currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->mergeCells('A' . $currentRow . ':H' . $currentRow);
            $currentRow += 2;
        }

        // Información de pie de página
        $sheet->setCellValue('A' . $currentRow, 'Av Del Marqués lote 7. Parque industrial Bernardo Quintana. El Marqués, Querétaro, C.P 76246');
        $sheet->mergeCells('A' . $currentRow . ':H' . $currentRow);

        $currentRow++;

        $sheet->setCellValue('A' . $currentRow, 'ACF06 | Fecha de efectividad: 28-Mayo-2024 | Revisión: 05');
        $sheet->mergeCells('A' . $currentRow . ':H' . $currentRow);

        // Ajustar bordes generales
        $sheet->getStyle('A1:H' . $currentRow)->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN);

        // Aplicar ajuste automático de altura para todas las filas con contenido
        for ($row = 1; $row <= $currentRow; $row++) {
            $sheet->getRowDimension($row)->setRowHeight(-1); // -1 = auto height
        }

        // Generar y descargar el archivo
        $fileName = 'Cotizacion_' . $cotizacion->no_proyecto . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function generarCotizacionPdf($id)
    {
        // --- 1. LÓGICA DE DATOS (Copiada de la función de Excel) ---
        $cotizacion = Cotizacion::with([
            'especificacionProyecto',
            'costeoRequisicion',
            'archivosAdjuntos'
        ])->findOrFail($id);

        // Calcular precios
        $ventasResumen = $cotizacion->ventasResumen ?? null;
        $precioUnitario = $ventasResumen->resumen_total_costo_unit ?? $cotizacion->costeoRequisicion->resumen_total_costo_unit ?? 0;
        $precioHerramentales = $ventasResumen->resumen_total_precio_venta ?? $cotizacion->costeoRequisicion->TOTAL_VENTAS ?? 0;

        // --- 2. LÓGICA DE IMÁGENES (Convertir a Base64) ---

        // Logo de Innovet
        $logoPath = public_path('images/innovet-logo.png');
        $logoBase64 = '';
        if (file_exists($logoPath)) {
            $logoType = pathinfo($logoPath, PATHINFO_EXTENSION);
            $logoData = file_get_contents($logoPath);
            $logoBase64 = 'data:image/' . $logoType . ';base64,' . base64_encode($logoData);
        }
        $logoHtml = $logoBase64 ? '<img src="' . $logoBase64 . '" style="height: 60px;">' : 'INNOVET';

        // Imagen Ilustrativa
        $imagenPath = null;
        if ($cotizacion->archivosAdjuntos && $cotizacion->archivosAdjuntos->isNotEmpty()) {
            foreach ($cotizacion->archivosAdjuntos as $archivo) {
                $extension = strtolower(pathinfo($archivo->path, PATHINFO_EXTENSION));
                if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'bmp'])) {
                    // Usar storage_path para acceder a los archivos en storage/app/public
                    $fullPath = storage_path('app/public/' . $archivo->path);
                    if (file_exists($fullPath)) {
                        $imagenPath = $fullPath;
                        break;
                    }
                }
            }
        }

        $imagenHtml = '';
        if ($imagenPath && file_exists($imagenPath)) {
            $imgType = pathinfo($imagenPath, PATHINFO_EXTENSION);
            $imgData = file_get_contents($imagenPath);
            $imgBase64 = 'data:image/' . $imgType . ';base64,' . base64_encode($imgData);
            $imagenHtml = '<img src="' . $imgBase64 . '" style="max-height: 200px; max-width: 100%; display: block; margin: 10px auto;">';
        } else {
            $imagenHtml = '<p class="title-red" style="text-align: center; padding: 50px 0;">Imagen ilustrativa: No disponible</p>';
        }


        // --- 3. CONSTRUCCIÓN DEL HTML ---

        // CSS (Traducción de los estilos de Excel)
        $html = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>';
        $html .= '<style>
            body { font-family: sans-serif; font-size: 10px; }
            table { width: 100%; border-collapse: collapse; page-break-inside: auto; }
            th, td { border: 1px solid #000000; padding: 8px; height: 25px; vertical-align: middle; word-wrap: break-word; }
            tr { page-break-inside: avoid; page-break-after: auto; }
            .no-border { border: none; }
            .align-center { text-align: center; }
            .align-left { text-align: left; }
            .bold { font-weight: bold; }
            .title-red { color: #B50B0B; font-weight: bold; text-align: center; }
            .text-red { color: #B50B0B; }
            .text-red-bold { color: #B50B0B; font-weight: bold; }
            
            .header-style {
                background-color: #2B2B2B;
                color: #FFFFFF;
                font-weight: bold;
                text-align: center;
            }
            .gray-cell {
                background-color: #BFBFBF;
                font-weight: bold;
                text-align: center;
            }
            .light-gray-cell {
                background-color: #D9D9D9;
                text-align: center;
            }
            .green-price {
                background-color: #92D050;
                color: #FFFFFF;
                font-weight: bold;
                text-align: center;
                font-size: 14px;
            }
            .spec-header {
                background-color: #D9D9D9;
                font-weight: bold;
                text-align: center;
            }
            .spec-data {
                background-color: #BFBFBF;
                text-align: center;
            }
            .footer-text {
                font-size: 9px;
                text-align: left;
                padding: 2px 0;
            }
        </style></head><body>';

        // --- Bloque 1: Logo y Folio ---
        $html .= '<table class="no-border" style="width: 100%; margin-bottom: 10px;">
            <tr>
                <td class="no-border" style="width: 70%; vertical-align: top;">' . $logoHtml . '</td>
                <td class="no-border" style="width: 30%; vertical-align: top;">
                    <table class="no-border">
                        <tr><td class="no-border bold">Folio:</td><td class="no-border">' . htmlspecialchars($cotizacion->no_proyecto) . '</td></tr>
                        <tr><td class="no-border bold">Fecha:</td><td class="no-border">' . htmlspecialchars($cotizacion->fecha) . '</td></tr>
                    </table>
                </td>
            </tr>
        </table>';

        // --- Bloque 2: Info Cliente ---
        $html .= '<div style="text-align: center; margin-bottom: 15px;">
            <div style="font-size: 14px; font-weight: bold;">' . htmlspecialchars($cotizacion->cliente) . '</div>
            <div>' . htmlspecialchars($cotizacion->puesto) . '</div>
            <span>' . htmlspecialchars($cotizacion->correo) . '</span>
            <span style="margin-left: 20px;">Tel. <span class="text-red-bold">' . htmlspecialchars($cotizacion->telefono) . '</span></span>
        </div>';

        // --- Bloque 3: Tabla 1 (Charola) ---
        $html .= '<table style="margin-bottom: 15px;">
            <thead>
                <tr>
                    <th class="header-style" style="width: 5%;"></th>
                    <th class="header-style" style="width: 50%;" colspan="5">Descripción del proyecto</th>
                    <th class="header-style" style="width: 20%;">Piezas (MOQ)</th>
                    <th class="header-style" style="width: 25%;">Precio Unitario (MXN)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td rowspan="3" class="light-gray-cell bold">1</td>
                    <td colspan="5" class="gray-cell title-red">' . htmlspecialchars($cotizacion->nombre_del_proyecto) . '</td>
                    <td rowspan="3" class="gray-cell bold">' . htmlspecialchars($cotizacion->especificacionProyecto->lote_compra) . '</td>
                    <td rowspan="3" class="green-price">$ ' . number_format($precioUnitario, 2) . '</td>
                </tr>
                <tr>
                    <td class="spec-header">Dimensiones</td>
                    <td class="spec-header">Frecuencia de compra</td>
                    <td class="spec-header">Especificación del material</td>
                    <td class="spec-header">Espesor</td>
                    <td class="spec-header">Color</td>
                </tr>
                <tr>
                    <td class="spec-data">' . htmlspecialchars($cotizacion->especificacionProyecto->pieza_largo . ' x ' . $cotizacion->especificacionProyecto->pieza_ancho . ' x ' . $cotizacion->especificacionProyecto->pieza_alto . ' mm') . '</td>
                    <td class="spec-data">' . htmlspecialchars($cotizacion->especificacionProyecto->frecuencia_compra) . '</td>
                    <td class="spec-data">' . htmlspecialchars($cotizacion->especificacionProyecto->material) . '</td>
                    <td class="spec-data">' . htmlspecialchars($cotizacion->especificacionProyecto->calibre) . '</td>
                    <td class="spec-data">' . htmlspecialchars($cotizacion->especificacionProyecto->color) . '</td>
                </tr>
            </tbody>
        </table>';

        // --- Bloque 4: Tabla 2 (Herramentales) ---
        $html .= '<table style="margin-bottom: 15px;">
            <thead>
                <tr>
                    <th class="header-style" style="width: 5%;"></th>
                    <th class="header-style" style="width: 50%;" colspan="5">Desarrollo de Herramientas.</th>
                    <th class="header-style" style="width: 20%;"></th>
                    <th class="header-style" style="width: 25%;">Precio Unitario (MXN)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td rowspan="2" class="light-gray-cell bold">2</td>
                    <td colspan="5" class="gray-cell title-red">Desarrollo de Herramentales</td>
                    <td rowspan="2" class="gray-cell bold">0</td>
                    <td rowspan="2" class="green-price">$ ' . number_format($precioHerramentales, 2) . '</td>
                </tr>
                <tr>
                    <td colspan="5" class="light-gray-cell align-center">Se considera entrega de 3 muestras para liberación</td>
                </tr>
            </tbody>
        </table>';

        // --- Bloque 5: Imagen ---
        // CAMBIO: Añadido text-align: center al div contenedor y movido el <p> adentro
        $html .= '<div style="margin: 20px 0; page-break-inside: avoid; text-align: center;">';
        $html .= $imagenHtml;
        $html .= '<p class="title-red">Imagen ilustrativa:</p>'; // La clase title-red ya centra el texto
        $html .= '</div>';

        // --- Bloque 6: Footer ---
        // (Forzar al final de la página si es posible, aunque en Dompdf es complejo. Por ahora, solo lo ponemos al final del contenido)
        $html .= '<div style="margin-top: 30px; border-top: 1px solid #000; padding-top: 10px;">
            <p class="footer-text">Av Del Marqués lote 7. Parque industrial Bernardo Quintana. El Marqués, Querétaro, C.P 76246</p>
            <p class="footer-text">ACF10 | Fecha de efectividad: 01-septiembre-2025 | Revisión: 03</p>
        </div>';

        $html .= '</body></html>';

        // --- 4. GENERACIÓN DE PDF CON DOMPDF ---

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true); // Para cargar la imagen

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);

        // Orientación 'portrait' (vertical)
        $dompdf->setPaper('A4', 'portrait');

        // Renderizar el HTML a PDF
        $dompdf->render();

        // Enviar el PDF al navegador
        $fileName = 'Cotizacion_' . ($cotizacion->no_proyecto ?? $cotizacion->id) . '.pdf';
        $dompdf->stream($fileName, ["Attachment" => false]); // "Attachment" => false para ver en navegador
        exit;
    }


    public function generarLineamientosExcel(Request $request, $id)
    {
        $cotizacion = Cotizacion::with([
            'especificacionProyecto',
            'costeoRequisicion'
        ])->findOrFail($id);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Configuración inicial
        $sheet->getDefaultColumnDimension()->setWidth(15);
        $sheet->getDefaultRowDimension()->setRowHeight(25);

        // Estilos reutilizables
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '2B2B2B']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ]
        ];

        $titleRedStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'B50B0B']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true
            ]
        ];

        $normalTextStyle = [
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true
            ]
        ];

        // Logo de INNOVET
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo de INNOVET');
        $drawing->setPath(public_path('images/innovet-logo.png'));
        $drawing->setHeight(80);
        $drawing->setCoordinates('A1');
        $drawing->setWorksheet($sheet);

        // Información del folio y fecha
        $sheet->setCellValue('F1', 'Folio:');
        $sheet->setCellValue('G1', $cotizacion->no_proyecto);
        $sheet->setCellValue('F2', 'Fecha:');
        $sheet->setCellValue('G2', $cotizacion->fecha);

        // Título principal
        $currentRow = 4;
        $sheet->setCellValue('A' . $currentRow, 'Lineamientos del Proyecto');
        $sheet->getStyle('A' . $currentRow)->applyFromArray($titleRedStyle);
        $sheet->getStyle('A' . $currentRow)->getFont()->setSize(16);
        $sheet->mergeCells('A' . $currentRow . ':G' . $currentRow);

        $currentRow += 2;

        // Calcular tiempo de entrega dinámico
        $tiempoEntrega = ceil((is_numeric($cotizacion->costeoRequisicion->tiempo_pt ?? 0) ? $cotizacion->costeoRequisicion->tiempo_pt : 0) / 5);
        $lugarEntrega = $cotizacion->lugar_entrega ?? '0';

        // Lineamientos y condiciones
        $lineamientos = [
            'Precios en USD. No incluyen I.V.A. Se considera fabricación, facturación y entrega en una sola exhibición.',
            'Los precios pueden ajustarse en respuesta a cambios en aranceles, impuestos o restricciones fiscales y comerciales establecidos por la autoridad.',
            'La vigencia de la presente cotización es de 12 meses y/o incrementos en MP superior al 5%.',
            'Condiciones de pago son por anticipado.',
            'Tiempo de desarrollo de herramentales y muestras para liberación ( ) semanas.',
            'Tiempo de entrega de producto terminado: ' . $tiempoEntrega . ' semanas (todos los tiempos se confirman con disponibilidad de maquinaria).',
            'El producto se entrega en: ' . $lugarEntrega,
            'Considerar una variación ±10% en la entrega de producto terminado, sobre lote de producción (MOQ cotizado).',
            'Especificación de empaque: se confirma después de la 1ª. producción.',
            'Cualquier condición distinta al escenario cotizado implica una revisión de costos.',
            'La responsabilidad respecto de la mercancía producida por INNOVET, es única y exclusivamente por defectos de fabricación. La inspección de la pieza deformada o fuera de calor, causa deformaciones e invalida garantías. Es responsabilidad del CLIENTE aquellos desperfectos que sufra el producto por mal uso, transportación, almacenamiento o análogas derivadas de la actividad del CLIENTE.'
        ];

        // Insertar cada lineamiento
        foreach ($lineamientos as $index => $lineamiento) {
            $sheet->setCellValue('A' . $currentRow, $lineamiento);
            $sheet->getStyle('A' . $currentRow)->applyFromArray($normalTextStyle);
            $sheet->mergeCells('A' . $currentRow . ':G' . $currentRow);
            $sheet->getRowDimension($currentRow)->setRowHeight(40);

            $currentRow++;
        }

        $currentRow += 2;

        // Sección "Atentamente"
        $sheet->setCellValue('A' . $currentRow, 'Atentamente');
        $sheet->getStyle('A' . $currentRow)->applyFromArray($titleRedStyle);
        $sheet->getStyle('A' . $currentRow)->getFont()->setSize(14);
        $sheet->mergeCells('A' . $currentRow . ':G' . $currentRow);

        $currentRow += 2;

        // --- CAMBIO: Leer datos desde la Request (URL) ---
        // Obtener datos del usuario autenticado si existe, o usar el default
        $defaultName = Auth::check() ? Auth::user()->name : 'Colocar aquí nombre de quien atiende la cuenta';
        // Leer 'nombre_contacto' de la URL, si no existe, usar $defaultName
        $nombreContacto = $request->input('nombre_contacto', $defaultName);
        // Leer 'puesto_contacto' de la URL, si no existe, usar 'Puesto'
        $puestoContacto = $request->input('puesto_contacto', 'Puesto');

        // Usar las variables leídas de la Request
        $sheet->setCellValue('A' . $currentRow, $nombreContacto);
        $sheet->getStyle('A' . $currentRow)->applyFromArray($normalTextStyle);
        $sheet->mergeCells('A' . $currentRow . ':G' . $currentRow);

        $currentRow++;

        $sheet->setCellValue('A' . $currentRow, $puestoContacto); // <-- CAMBIO: Usar $puestoContacto
        $sheet->getStyle('A' . $currentRow)->applyFromArray($normalTextStyle);
        $sheet->mergeCells('A' . $currentRow . ':G' . $currentRow);

        $currentRow += 3;

        // Información de pie de página
        $sheet->setCellValue('A' . $currentRow, 'Av Del Marqués lote 7. Parque industrial Bernardo Quintana. El Marqués, Querétaro, C.P 76246');
        $sheet->getStyle('A' . $currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->mergeCells('A' . $currentRow . ':G' . $currentRow);

        $currentRow++;

        $sheet->setCellValue('A' . $currentRow, 'ACF06 | Fecha de efectividad: 28-Mayo-2024 | Revisión: 05');
        $sheet->getStyle('A' . $currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->mergeCells('A' . $currentRow . ':G' . $currentRow);

        // Ajustar ancho de columnas
        $sheet->getColumnDimension('A')->setWidth(120);
        for ($col = 'B'; $col <= 'G'; $col++) {
            $sheet->getColumnDimension($col)->setWidth(15);
        }

        // Generar y descargar el archivo
        $fileName = 'Lineamientos_' . $cotizacion->no_proyecto . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function generarLineamientosPdf(Request $request, $id)
    {
        // --- 1. LÓGICA DE DATOS (Copiada de la función de Excel) ---
        $cotizacion = Cotizacion::with([
            'especificacionProyecto',
            'costeoRequisicion'
        ])->findOrFail($id);

        // Calcular tiempo de entrega dinámico
        $tiempoEntrega = ceil((is_numeric($cotizacion->costeoRequisicion->tiempo_pt ?? 0) ? $cotizacion->costeoRequisicion->tiempo_pt : 0) / 5);
        $lugarEntrega = $cotizacion->lugar_entrega ?? '0';

        // Lineamientos y condiciones
        $lineamientos = [
            'Precios en USD. No incluyen I.V.A. Se considera fabricación, facturación y entrega en una sola exhibición.',
            'Los precios pueden ajustarse en respuesta a cambios en aranceles, impuestos o restricciones fiscales y comerciales establecidos por la autoridad.',
            'La vigencia de la presente cotización es de 12 meses y/o incrementos en MP superior al 5%.',
            'Condiciones de pago son por anticipado.',
            'Tiempo de desarrollo de herramentales y muestras para liberación ( ) semanas.',
            'Tiempo de entrega de producto terminado: ' . $tiempoEntrega . ' semanas (todos los tiempos se confirman con disponibilidad de maquinaria).',
            'El producto se entrega en: ' . $lugarEntrega,
            'Considerar una variación ±10% en la entrega de producto terminado, sobre lote de producción (MOQ cotizado).',
            'Especificación de empaque: se confirma después de la 1ª. producción.',
            'Cualquier condición distinta al escenario cotizado implica una revisión de costos.',
            'La responsabilidad respecto de la mercancía producida por INNOVET, es única y exclusivamente por defectos de fabricación. La inspección de la pieza deformada o fuera de calor, causa deformaciones e invalida garantías. Es responsabilidad del CLIENTE aquellos desperfectos que sufra el producto por mal uso, transportación, almacenamiento o análogas derivadas de la actividad del CLIENTE.'
        ];

        // --- CAMBIO: Leer datos desde la Request (URL) ---
        // Obtener datos del usuario autenticado si existe, o usar el default
        $defaultName = Auth::check() ? Auth::user()->name : 'Colocar aquí nombre de quien atiende la cuenta';
        // Leer 'nombre_contacto' de la URL, si no existe, usar $defaultName
        $nombreContacto = $request->input('nombre_contacto', $defaultName);
        // Leer 'puesto_contacto' de la URL, si no existe, usar 'Puesto'
        $puestoContacto = $request->input('puesto_contacto', 'Puesto');


        // --- 2. LÓGICA DE IMÁGENES (Solo Logo) ---
        $logoPath = public_path('images/innovet-logo.png');
        $logoBase64 = '';
        if (file_exists($logoPath)) {
            $logoType = pathinfo($logoPath, PATHINFO_EXTENSION);
            $logoData = file_get_contents($logoPath);
            $logoBase64 = 'data:image/' . $logoType . ';base64,' . base64_encode($logoData);
        }
        $logoHtml = $logoBase64 ? '<img src="' . $logoBase64 . '" style="height: 60px;">' : 'INNOVET';


        // --- 3. CONSTRUCCIÓN DEL HTML ---
        $html = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>';
        $html .= '<style>
            body { font-family: sans-serif; font-size: 11px; line-height: 1.5; }
            table { width: 100%; border-collapse: collapse; }
            td { border: none; padding: 2px; vertical-align: top; }
            .bold { font-weight: bold; }
            .title-red { color: #B50B0B; font-weight: bold; font-size: 16px; }
            .title-red-small { color: #B50B0B; font-weight: bold; font-size: 14px; }
            .lineamientos-list { list-style-type: disc; padding-left: 20px; }
            .lineamientos-list li { margin-bottom: 12px; }
            .footer-text { font-size: 9px; text-align: center; padding: 2px 0; }
        </style></head><body>';

        // --- Bloque 1: Logo y Folio ---
        $html .= '<table style="width: 100%; margin-bottom: 20px;">
            <tr>
                <td style="width: 70%;">' . $logoHtml . '</td>
                <td style="width: 30%;">
                    <table>
                        <tr><td class="bold">Folio:</td><td>' . htmlspecialchars($cotizacion->no_proyecto) . '</td></tr>
                        <tr><td class="bold">Fecha:</td><td>' . htmlspecialchars($cotizacion->fecha) . '</td></tr>
                    </table>
                </td>
            </tr>
        </table>';

        // --- Bloque 2: Título ---
        $html .= '<div class="title-red" style="margin-bottom: 20px;">Lineamientos del Proyecto</div>';

        // --- Bloque 3: Lineamientos ---
        $html .= '<ul class="lineamientos-list">';
        foreach ($lineamientos as $lineamiento) {
            $html .= '<li>' . htmlspecialchars($lineamiento) . '</li>';
        }
        $html .= '</ul>';

        // --- Bloque 4: Atentamente ---
        // --- CAMBIO: Usar las variables $nombreContacto y $puestoContacto ---
        $html .= '<div style="margin-top: 30px;">
            <p class="title-red-small">Atentamente</p>
            <p style="margin-top: 20px;">' . htmlspecialchars($nombreContacto) . '</p>
            <p>' . htmlspecialchars($puestoContacto) . '</p>
        </div>';

        // --- Bloque 5: Footer ---
        $html .= '<div style="margin-top: 50px; border-top: 1px solid #000; padding-top: 10px;">
            <p class="footer-text">Av Del Marqués lote 7. Parque industrial Bernardo Quintana. El Marqués, Querétaro, C.P 76246</p>
            <p class="footer-text">ACF06 | Fecha de efectividad: 28-Mayo-2024 | Revisión: 05</p>
        </div>';

        $html .= '</body></html>';

        // --- 4. GENERACIÓN DE PDF CON DOMPDF ---
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);

        // Orientación 'portrait' (vertical)
        $dompdf->setPaper('A4', 'portrait');

        // Renderizar el HTML a PDF
        $dompdf->render();

        // Enviar el PDF al navegador
        $fileName = 'Lineamientos_' . ($cotizacion->no_proyecto ?? $cotizacion->id) . '.pdf';
        $dompdf->stream($fileName, ["Attachment" => false]);
        exit;
    }

    public function generarCosteoResumenExcel(Request $request, $id)
    {
        // --- INICIO DE TU LÓGICA (SIN CAMBIOS) ---
        $cotizacion = \App\Models\Cotizacion::with([
            'requisicionCotizacion',
            'especificacionProyecto',
            'cotizacionAdicional',
            'cajaCliente',
            'costeoRequisicion',
            'especificacionEmpaque',
            'resumen',
            'archivosAdjuntos' // <-- CAMBIO: Cargar los archivos
        ])->findOrFail($id);

        // --- CAMBIO 2: LEER TODOS LOS DATOS EDITABLES DE LA URL ---

        // 1. Sobrescribir "Otra Información"
        if ($request->has('datos_criticos_adicionales')) {
            if ($cotizacion->especificacionEmpaque) { // Evitar error si no existe
                $cotizacion->especificacionEmpaque->datos_criticos = $request->input('datos_criticos_adicionales');
            }
        }

        // 2. Sobrescribir los campos del Resumen
        $resumen = $cotizacion->resumen;
        // Asegurarse que $resumen sea un objeto, incluso si no existe en BD
        if (is_null($resumen)) {
            // Creamos un objeto temporal para que el Excel no falle
            $resumen = new \stdClass(); // Objeto vacío
            $resumen->poka_yoke = '';
            $resumen->acomodo_pieza = '';
            $resumen->contenedor_cliente = '';
            $resumen->medidas_contenedor = '';
            $resumen->estiba_contenedor = '';
            $resumen->cliente_proporciona = '';
            $cotizacion->resumen = $resumen; // Lo re-asociamos temporalmente
        }

        if ($request->has('poka_yoke')) {
            $resumen->poka_yoke = $request->input('poka_yoke');
        }
        if ($request->has('acomodo_pieza')) {
            $resumen->acomodo_pieza = $request->input('acomodo_pieza');
        }
        if ($request->has('contenedor_cliente')) {
            $resumen->contenedor_cliente = $request->input('contenedor_cliente');
        }
        if ($request->has('medidas_contenedor')) {
            $resumen->medidas_contenedor = $request->input('medidas_contenedor');
        }
        if ($request->has('estiba_contenedor')) {
            $resumen->estiba_contenedor = $request->input('estiba_contenedor');
        }
        if ($request->has('cliente_proporciona')) {
            $resumen->cliente_proporciona = $request->input('cliente_proporciona');
        }
        // --- FIN DE CAMBIOS ---


        // alias rápido
        $c = $cotizacion;
        // $resumen ya está actualizado

        // ... (Tu lógica de placas, grabados, etc. sin cambios) ...
        $placas = [
            1 => "320 x 420 mm",
            2 => "350 x 560 mm",
            3 => "355 x 590 mm",
            4 => "420 x 420 mm",
            5 => "420 x 700 mm",
            6 => "455 x 480 mm",
            7 => "455 x 610 mm",
            8 => "450 x 620 mm",
            9 => "460 x 520 mm",
            10 => "480 x 630 mm",
            11 => "490 x 600 mm",
            12 => "520 x 455 mm",
            13 => "520 x 1000 mm",
            14 => "600 x 650 mm",
            15 => "650 x 592 mm",
            16 => "700 x 1200 mm",
            17 => "800 x 940 mm",
            18 => "1175 x 1390 mm",
            19 => "1450 x 1630 mm",
            20 => "1450 x 3000 mm"
        ];

        $indicePlaca = $c->costeoRequisicion->placa_de_enfriamiento ?? null;
        $valorPlaca = $placas[$indicePlaca] ?? "No aplica";

        // grabados (misma lógica)
        $grabadosMap = [
            'numero_parte' => 'Número de parte',
            'tipo_material' => 'Tipo de material',
            'logo_cliente' => 'Logo cliente',
            'logo_innovet' => 'Logo Innovet',
        ];

        $req = $c->requisicionCotizacion;
        $grabadosSeleccionados = [];
        foreach ($grabadosMap as $campo => $etiqueta) {
            if (!empty($req->$campo) && $req->$campo == 1) {
                $grabadosSeleccionados[] = $etiqueta;
            }
        }
        if (!empty($req->sin_grabado) && $req->sin_grabado == 1 && empty($grabadosSeleccionados)) {
            $grabadoFinal = "Sin grabado";
        } else {
            $grabadoFinal = !empty($grabadosSeleccionados) ? implode(', ', $grabadosSeleccionados) : "Sin grabado";
        }


        // Spreadsheet
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // ... (Toda tu lógica de estilos y celdas del Excel sin cambios) ...
        // ... ($styleTitle, $styleHeaderRow, etc.) ...

        // 1. Establecer anchos de columna (A-H)
        $sheet->getColumnDimension('A')->setWidth(26);
        $sheet->getColumnDimension('B')->setWidth(28);
        $sheet->getColumnDimension('C')->setWidth(22);
        $sheet->getColumnDimension('D')->setWidth(28);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(15);
        $sheet->getColumnDimension('G')->setWidth(12);
        $sheet->getColumnDimension('H')->setWidth(18);

        // Altura de fila por defecto
        $sheet->getDefaultRowDimension()->setRowHeight(18);

        // 2. Definición de estilos (basado en la imagen)
        $styleTitle = [
            'font' => ['bold' => true, 'size' => 20, 'color' => ['rgb' => 'CC0000']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ];

        $styleHeaderRow = [
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'DDEBF7']], // Fondo azul claro para toda la fila
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]]
        ];

        $styleHeaderLabel = [
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFFFFF']], // Etiquetas en blanco
        ];

        // Nuevo estilo para los VALORES de la cabecera (azul oscuro, negrita)
        $styleHeaderValue = [
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'B4C6E7']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
        ];

        $styleSectionHeader = [
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E7E6E6']], // Gris
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]]
        ];

        // Estilos para las filas de datos
        $styleRowEven = [ // Azul muy claro
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E9F1FA']],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]]
        ];
        $styleRowOdd = [ // Gris muy claro
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F2F2F2']],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]]
        ];

        // Estilo específico para los valores en las columnas C o B con fondo gris
        $styleValueShaded = [
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F2F2F2']], // Gris muy claro
        ];

        $styleAllBorders = [
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]]
        ];

        // Nuevo estilo para centrar
        $styleCenterAlign = [
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER]
        ];


        // Logo (opcional, si existe)
        $logoPath = public_path('images/innovet-logo.png');
        if (file_exists($logoPath)) {
            $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $drawing->setPath($logoPath);
            $drawing->setHeight(60);
            $drawing->setCoordinates('A1');
            $drawing->setWorksheet($sheet);
            $sheet->getRowDimension('1')->setRowHeight(25);
            $sheet->getRowDimension('2')->setRowHeight(25);
        }

        // Título
        $sheet->setCellValue('C1', 'RESUMEN');
        $sheet->mergeCells('C1:H2');
        $sheet->getStyle('C1:H2')->applyFromArray($styleTitle);
        $sheet->getRowDimension('3')->setRowHeight(25);

        // Header row (Cliente, Proyecto, Folio, Fecha) - Fila 4
        $headerRow = 4;
        $sheet->setCellValue("A{$headerRow}", 'Cliente:');
        $sheet->setCellValue("B{$headerRow}", $c->cliente);
        $sheet->setCellValue("C{$headerRow}", 'Nombre del Proyecto:');
        $sheet->setCellValue("D{$headerRow}", $c->nombre_del_proyecto);
        $sheet->setCellValue("E{$headerRow}", 'Folio:');
        $sheet->setCellValue("F{$headerRow}", $c->no_proyecto);
        $sheet->setCellValue("G{$headerRow}", 'Fecha:');
        $sheet->setCellValue("H{$headerRow}", $c->fecha);

        // Aplicar estilos al Header row
        $sheet->getStyle("A{$headerRow}:H{$headerRow}")->applyFromArray($styleHeaderRow);

        // Estilos específicos para etiquetas
        $sheet->getStyle("A{$headerRow}")->applyFromArray($styleHeaderLabel);
        $sheet->getStyle("C{$headerRow}")->applyFromArray($styleHeaderLabel);
        $sheet->getStyle("E{$headerRow}")->applyFromArray($styleHeaderLabel);
        $sheet->getStyle("G{$headerRow}")->applyFromArray($styleHeaderLabel);

        // Estilos específicos para valores
        $sheet->getStyle("B{$headerRow}")->applyFromArray($styleHeaderValue);
        $sheet->getStyle("D{$headerRow}")->applyFromArray($styleHeaderValue);
        $sheet->getStyle("F{$headerRow}")->applyFromArray($styleHeaderValue);
        $sheet->getStyle("H{$headerRow}")->applyFromArray($styleHeaderValue);


        // Encabezado de sección
        $startRow = 6; // Iniciamos en la fila 6 (dejando un espacio)
        $sheet->setCellValue("A{$startRow}", 'Descripción del proyecto');
        $sheet->mergeCells("A{$startRow}:D{$startRow}");
        $sheet->setCellValue("E{$startRow}", 'Datos críticos');
        $sheet->mergeCells("E{$startRow}:H{$startRow}");

        // Aplicar estilo de encabezado de sección
        $sheet->getStyle("A{$startRow}:H{$startRow}")->applyFromArray($styleSectionHeader);

        $r = $startRow + 1; // Fila actual para datos
        $rowIndex = 0; // Para alternar colores

        // Añadir filas que replican tu vista (cada fila: etiqueta y valor)
        // El valor de $c->especificacionEmpaque->datos_criticos YA ESTÁ ACTUALIZADO por el código de arriba
        $rows = [
            ['Tipo de producto', $c->tipo_de_empaque, 'Estiba', ($c->requisicionCotizacion->tipo_estiba ?? '')],
            ['MOQ cotizado', ($c->especificacionProyecto->lote_compra ? $c->especificacionProyecto->lote_compra . ' piezas' : ''), 'Flujo de carga', ($c->requisicionCotizacion->tipo_flujo_carga ?? '')],
            ['Frecuencia de compra', ($c->especificacionProyecto->frecuencia_compra ?? ''), 'Dedales', ($c->cajaCliente->dedales ?? '')],
            ['Dimensiones finales de pieza', ($c->especificacionProyecto->pieza_largo . ' x ' . $c->especificacionProyecto->pieza_ancho . ' x ' . $c->especificacionProyecto->pieza_alto . ' mm'), 'Tipo de pared', ($c->requisicionCotizacion->pared ?? '')],
            [
                'Dimensiones finales de molde',
                (optional($c->costeoRequisicion)->insertos == 1
                    ? (
                        (optional($c->especificacionProyecto)->pieza_largo ?? '') . ' x ' .
                        (optional($c->especificacionProyecto)->pieza_ancho ?? '') . ' x ' .
                        (optional($c->especificacionProyecto)->pieza_alto ?? '') . ' mm'
                    )
                    : (
                        (optional($c->costeoRequisicion)->molde_ancho ?? '') . ' x ' .
                        (optional($c->costeoRequisicion)->molde_avance ?? '') . ' x ' .
                        (optional($c->especificacionProyecto)->pieza_alto ?? '') . ' mm'
                    )
                ),
                '# Cavidades',
                (optional($c->cotizacionAdicional)->componentes_por_charola ?? '')
            ],
            ['Fabricación de prototipo', ($c->cotizacionAdicional->prototipo ?? ''), 'Otra Información', ($c->especificacionEmpaque->datos_criticos ?? '')],
            ['Especificación de material', ($c->especificacionProyecto->material ?? ''), '', ''],
            ['Color', ($c->especificacionProyecto->color ?? ''), '', ''],
            ['Franja de color', ($c->especificacionProyecto->franja_color ?? ''), '', ''],
            ['Calibre', ($c->especificacionProyecto->calibre ?? ''), '', ''],
            ['Ancho de material', ($c->costeoRequisicion->hoja_ancho ?? ''), 'mm', ''],
        ];

        // Guardar la fila donde empiezan los merges del cuadro blanco de la derecha
        $startMergeRightBoxRow = 0;

        foreach ($rows as $rowData) {
            $sheet->setCellValue("A{$r}", $rowData[0]); // Label A
            $sheet->setCellValue("B{$r}", $rowData[1]); // Value B

            // Determinar el estilo de la fila
            $currentStyleRow = ($rowIndex % 2 == 0) ? $styleRowEven : $styleRowOdd;
            $sheet->getStyle("A{$r}:H{$r}")->applyFromArray($currentStyleRow);
            $sheet->getStyle("A{$r}")->getFont()->setBold(true); // Label en negrita

            if ($rowData[0] == 'Ancho de material') {
                // Caso especial para 'Ancho de material'
                $sheet->setCellValue("C{$r}", $rowData[2]); // 'mm' en C
                $sheet->setCellValue("D{$r}", $rowData[3]); // (vacío) en D

                // Aplicar estilo de sombreado al valor del ancho
                $sheet->getStyle("B{$r}")->applyFromArray($styleValueShaded);
            } else if ($rowData[0] == 'Calibre') {
                // Caso especial para 'Calibre'
                $sheet->setCellValue("C{$r}", 'mm'); // Poner 'mm' en C

                // Aplicar estilo de sombreado al valor del calibre
                $sheet->getStyle("B{$r}")->applyFromArray($styleValueShaded);
            } else if (empty($rowData[2])) {
                // Filas sin datos críticos (ej. Color)
                $sheet->mergeCells("B{$r}:D{$r}"); // Unir B-D (para el valor)

                // --- CAMBIO AQUÍ: Guardar la primera fila donde empieza el merge gigante ---
                if ($startMergeRightBoxRow == 0) {
                    $startMergeRightBoxRow = $r; // Guardar el número de esta fila (ej. 13 para 'Especificación de material')
                }

                // Aplicar estilo de sombreado al valor principal
                $sheet->getStyle("B{$r}")->applyFromArray($styleValueShaded);
            } else {
                // Caso estándar con datos críticos
                $sheet->mergeCells("B{$r}:D{$r}"); // Unir B-D para valor 1
                $sheet->setCellValue("E{$r}", $rowData[2]); // Label E
                $sheet->setCellValue("F{$r}", $rowData[3]); // Value F
                $sheet->mergeCells("F{$r}:H{$r}"); // Unir F-H para valor 2

                // Aplicar estilo de sombreado a los valores
                $sheet->getStyle("B{$r}")->applyFromArray($styleValueShaded); // Valor principal
                $sheet->getStyle("F{$r}")->applyFromArray($styleValueShaded); // Valor crítico
            }

            $r++;
            $rowIndex++;
        }

        // Orillas (subtabla)
        $sheet->setCellValue("A{$r}", 'Orillas'); // Poner título en A
        $sheet->getStyle("A{$r}")->getFont()->setBold(true);

        $startOrillasData = $r; // Guardamos la fila donde empiezan los datos de orillas

        // Fila de Vertical (cadenas)
        // Usar el mismo estilo de fila que la anterior para alternancia, o definir uno específico
        $currentStyleRow = ($rowIndex % 2 == 0) ? $styleRowEven : $styleRowOdd;
        $sheet->getStyle("A{$r}:H{$r}")->applyFromArray($currentStyleRow);
        $sheet->setCellValue("B{$r}", 'Vertical (cadenas):');
        $sheet->setCellValue("C{$r}", $c->costeoRequisicion->acomodo_ancho_orillas_mm ?? '');
        $sheet->setCellValue("D{$r}", 'mm');
        $sheet->getStyle("A{$r}")->getFont()->setBold(true);
        $sheet->getStyle("B{$r}")->getFont()->setBold(true); // Poner 'Vertical (cadenas)' en negrita
        $sheet->getStyle("C{$r}")->applyFromArray($styleValueShaded); // Sombreado para el valor numérico
        $sheet->getStyle("C{$r}")->applyFromArray($styleCenterAlign); // Centrar el valor numérico
        $r++;
        $rowIndex++;

        // Fila de Medianil vertical
        $currentStyleRow = ($rowIndex % 2 == 0) ? $styleRowEven : $styleRowOdd;
        $sheet->getStyle("A{$r}:H{$r}")->applyFromArray($currentStyleRow);
        $sheet->setCellValue("B{$r}", 'Medianil vertical:');
        $sheet->setCellValue("C{$r}", $c->costeoRequisicion->acomodo_ancho_medianiles_mm ?? '');
        $sheet->setCellValue("D{$r}", 'mm');
        $sheet->getStyle("B{$r}")->getFont()->setBold(true);
        $sheet->getStyle("C{$r}")->applyFromArray($styleValueShaded); // Sombreado para el valor numérico
        $sheet->getStyle("C{$r}")->applyFromArray($styleCenterAlign); // Centrar el valor numérico
        $r++;
        $rowIndex++;

        // Fila de Horizontal
        $currentStyleRow = ($rowIndex % 2 == 0) ? $styleRowEven : $styleRowOdd;
        $sheet->getStyle("A{$r}:H{$r}")->applyFromArray($currentStyleRow);
        $sheet->setCellValue("B{$r}", 'Horizontal:');
        $sheet->setCellValue("C{$r}", $c->costeoRequisicion->acomodo_avance_orillas_mm ?? '');
        $sheet->setCellValue("D{$r}", 'mm');
        $sheet->getStyle("B{$r}")->getFont()->setBold(true);
        $sheet->getStyle("C{$r}")->applyFromArray($styleValueShaded); // Sombreado para el valor numérico
        $sheet->getStyle("C{$r}")->applyFromArray($styleCenterAlign); // Centrar el valor numérico
        $r++;
        $rowIndex++;

        // Fila de Medianil horizontal
        $currentStyleRow = ($rowIndex % 2 == 0) ? $styleRowEven : $styleRowOdd;
        $sheet->getStyle("A{$r}:H{$r}")->applyFromArray($currentStyleRow);
        $sheet->setCellValue("B{$r}", 'Medianil horizontal:');
        $sheet->setCellValue("C{$r}", $c->costeoRequisicion->acomodo_avance_medianiles_mm ?? '');
        $sheet->setCellValue("D{$r}", 'mm');
        $sheet->getStyle("B{$r}")->getFont()->setBold(true);
        $sheet->getStyle("C{$r}")->applyFromArray($styleValueShaded); // Sombreado para el valor numérico
        $sheet->getStyle("C{$r}")->applyFromArray($styleCenterAlign); // Centrar el valor numérico
        $endOrillasData = $r; // Fila final de datos de orillas
        $r++;
        $rowIndex++;

        // La unión de A ahora es solo para las filas de datos *después* de la primera
        if ($startOrillasData < $endOrillasData) {
            $sheet->mergeCells("A" . ($startOrillasData) . ":A{$endOrillasData}"); // Une desde la fila del título 'Orillas'
        }


        // Insertos, placa, máquinas...
        // El objeto $resumen YA ESTÁ ACTUALIZADO por el código de arriba
        $lastRowBeforeFinalMerge = $r; // Guardamos esta fila para el merge final de la derecha

        $dataRows = [
            ['Insertos', $c->costeoRequisicion->insertos ?? ''],
            ['Placa de refrigeración', $valorPlaca],
            ['Máquina donde se produce', (($c->costeoRequisicion->nombre_maquina_termoformado ?? '') . ', ' . ($c->costeoRequisicion->nombre_maquina_suaje ?? ''))],
            ['Broche', $c->requisicionCotizacion->movimiento ?? ''],
            ['Pestaña', $c->cotizacionAdicional->pestana ?? ''],
            ['Grabados', $grabadoFinal],
            ['Poka yoke', $resumen->poka_yoke ?? ''],
            ['Acomodo de pieza', $resumen->acomodo_pieza ?? ''],
            ['Contenedor del cliente', $resumen->contenedor_cliente ?? ''],
            ['Medidas de contenedor', $resumen->medidas_contenedor ?? (($c->cajaCliente->caja_largo ?? '') . ' x ' . ($c->cajaCliente->caja_ancho ?? '') . ' x ' . ($c->cajaCliente->caja_alto ?? ''))],
            ['Estiba por contenedor', $resumen->estiba_contenedor ?? ''],
            ['Cliente proporciona', $resumen->cliente_proporciona ?? ''],
        ];

        foreach ($dataRows as $data) {
            $currentStyleRow = ($rowIndex % 2 == 0) ? $styleRowEven : $styleRowOdd;
            $sheet->getStyle("A{$r}:H{$r}")->applyFromArray($currentStyleRow);

            $sheet->setCellValue("A{$r}", $data[0]);
            $sheet->getStyle("A{$r}")->getFont()->setBold(true); // Etiqueta en negrita

            $sheet->setCellValue("B{$r}", $data[1]);
            // Unir C a H para el espacio vacío después del valor
            // Para 'Grabados', unimos B a D, y C y D para el valor, pero en la imagen Grabados tiene el valor en B
            if ($data[0] == 'Grabados') {
                $sheet->mergeCells("B{$r}:D{$r}"); // El valor de grabados ocupa B,C,D
                $sheet->getStyle("B{$r}")->applyFromArray($styleValueShaded);
            } else {
                $sheet->mergeCells("B{$r}:D{$r}"); // El valor ocupa solo B
                $sheet->getStyle("B{$r}")->applyFromArray($styleValueShaded);
            }

            // --- CAMBIO AQUÍ: Re-aplicar el centrado que te gustaba ---
            $sheet->getStyle("B{$r}")->applyFromArray($styleCenterAlign);

            // --- CAMBIO AQUÍ: Esto crea el espacio blanco de la derecha ---
            // Aquí NO unimos E:H, ya que lo haremos con el merge gigante al final

            $r++;
            $rowIndex++;
        }

        // --- CAMBIO AQUÍ: Añadida la unión gigante ---
        $lastRow = $r - 1; // La última fila generada es $r-1

        // $startMergeRightBoxRow se definió arriba (ej. 13)
        // Asegurarse de que el rango sea válido
        if ($startMergeRightBoxRow > 0 && $startMergeRightBoxRow <= $lastRow) {
            $sheet->mergeCells("E{$startMergeRightBoxRow}:H{$lastRow}");
            // Aplicar el borde a la celda gigante
            $sheet->getStyle("E{$startMergeRightBoxRow}:H{$lastRow}")->applyFromArray($styleAllBorders);
            // Asegurar que el fondo sea blanco, si alguna celda base tenía color
            $sheet->getStyle("E{$startMergeRightBoxRow}:H{$lastRow}")->applyFromArray(
                ['fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFFFFF']]]
            );
        }

        // --- INICIO DE CAMBIOS: AÑADIR ARCHIVOS ADJUNTOS ---

        $r = $lastRow + 2; // Dejar un espacio

        if ($c->resumen && $c->resumen->archivos->count() > 0) {

            $sheet->setCellValue("A{$r}", 'Archivos Adjuntos');
            $sheet->mergeCells("A{$r}:H{$r}");
            $sheet->getStyle("A{$r}")->applyFromArray($styleSectionHeader);
            $r++;

            foreach ($c->resumen->archivos as $archivo) {
                // Usar storage_path para acceder a los archivos en storage/app/public
                $fullPath = storage_path('app/public/' . $archivo->path);

                // Poner el nombre del archivo
                $sheet->setCellValue("A{$r}", $archivo->nombre_original ?? basename($archivo->path));
                $sheet->mergeCells("A{$r}:H{$r}");
                $sheet->getStyle("A{$r}")->getFont()->setBold(true);
                $r++;

                // Comprobar si es una imagen e insertarla
                if (file_exists($fullPath) && @is_array(getimagesize($fullPath))) {
                    $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                    $drawing->setPath($fullPath);
                    $drawing->setHeight(150); // Altura de 150px
                    $drawing->setCoordinates("A{$r}");

                    // Ajustar la altura de la fila para que quepa la imagen
                    $sheet->getRowDimension($r)->setRowHeight(120);

                    $drawing->setWorksheet($sheet);
                    $r++; // Incrementar la fila para la imagen
                } else if (file_exists($fullPath)) {
                    // No es imagen, solo dejamos el nombre
                    $sheet->setCellValue("A{$r}", '(Archivo no compatible para vista previa en Excel)');
                    $sheet->mergeCells("A{$r}:H{$r}");
                    $r++;
                }

                $r++; // Dejar un espacio
            }
        }
        // --- FIN DE CAMBIOS: AÑADIR ARCHIVOS ADJUNTOS ---


        // Generar y enviar (Sin cambios)
        $fileName = 'Resumen_Cotizacion_' . ($c->no_proyecto ?? $c->id) . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    /**
     * Genera un archivo PDF con el resumen de costeo de una cotización.
     *
     * @param int $id El ID de la cotización
     */
    // --- CAMBIO 1: Añadir Request $request ---
    public function generarCosteoResumenPdf(Request $request, $id)
    {
        // Cargar la cotización con sus relaciones
        $cotizacion = \App\Models\Cotizacion::with([
            'requisicionCotizacion',
            'especificacionProyecto',
            'cotizacionAdicional',
            'cajaCliente',
            'costeoRequisicion',
            'especificacionEmpaque',
            'resumen',
            'archivosAdjuntos'
        ])->findOrFail($id);

        // 1. Sobrescribir "Otra Información"
        if ($request->has('datos_criticos_adicionales')) {
            if (is_null($cotizacion->especificacionEmpaque)) {
                $cotizacion->especificacionEmpaque = new \stdClass();
            }
            $cotizacion->especificacionEmpaque->datos_criticos = $request->input('datos_criticos_adicionales');
        }

        // 2. Sobrescribir los campos del Resumen
        $resumen = $cotizacion->resumen;
        if (is_null($resumen)) {
            $resumen = new \stdClass();
            $resumen->poka_yoke = ''; $resumen->acomodo_pieza = ''; $resumen->contenedor_cliente = '';
            $resumen->medidas_contenedor = ''; $resumen->estiba_contenedor = ''; $resumen->cliente_proporciona = '';
            $cotizacion->resumen = $resumen;
        }

        if ($request->has('poka_yoke')) { $resumen->poka_yoke = $request->input('poka_yoke'); }
        if ($request->has('acomodo_pieza')) { $resumen->acomodo_pieza = $request->input('acomodo_pieza'); }
        if ($request->has('contenedor_cliente')) { $resumen->contenedor_cliente = $request->input('contenedor_cliente'); }
        if ($request->has('medidas_contenedor')) { $resumen->medidas_contenedor = $request->input('medidas_contenedor'); }
        if ($request->has('estiba_contenedor')) { $resumen->estiba_contenedor = $request->input('estiba_contenedor'); }
        if ($request->has('cliente_proporciona')) { $resumen->cliente_proporciona = $request->input('cliente_proporciona'); }
        
        $c = $cotizacion;

        $placas = [
            1 => "320 x 420 mm", 2 => "350 x 560 mm", 3 => "355 x 590 mm", 4 => "420 x 420 mm",
            5 => "420 x 700 mm", 6 => "455 x 480 mm", 7 => "455 x 610 mm", 8 => "450 x 620 mm",
            9 => "460 x 520 mm", 10 => "480 x 630 mm", 11 => "490 x 600 mm", 12 => "520 x 455 mm",
            13 => "520 x 1000 mm", 14 => "600 x 650 mm", 15 => "650 x 592 mm", 16 => "700 x 1200 mm",
            17 => "800 x 940 mm", 18 => "1175 x 1390 mm", 19 => "1450 x 1630 mm", 20 => "1450 x 3000 mm"
        ];
        $indicePlaca = $c->costeoRequisicion->placa_de_enfriamiento ?? null;
        $valorPlaca = $placas[$indicePlaca] ?? "No aplica";

        $grabadosMap = [
            'numero_parte' => 'Número de parte', 'tipo_material' => 'Tipo de material',
            'logo_cliente' => 'Logo cliente', 'logo_innovet' => 'Logo Innovet',
        ];
        $req = $c->requisicionCotizacion;
        $grabadosSeleccionados = [];
        foreach ($grabadosMap as $campo => $etiqueta) {
            if (!empty($req->$campo) && $req->$campo == 1) {
                $grabadosSeleccionados[] = $etiqueta;
            }
        }
        if (!empty($req->sin_grabado) && $req->sin_grabado == 1 && empty($grabadosSeleccionados)) {
            $grabadoFinal = "Sin grabado";
        } else {
            $grabadoFinal = !empty($grabadosSeleccionados) ? implode(', ', $grabadosSeleccionados) : "Sin grabado";
        }

        // --- LÓGICA PARA EL LOGO EN BASE64 ---
        $logoPath = public_path('images/innovet-logo.png');
        $logoBase64 = '';
        if (file_exists($logoPath)) {
            $logoType = pathinfo($logoPath, PATHINFO_EXTENSION);
            $logoData = file_get_contents($logoPath);
            $logoBase64 = 'data:image/' . $logoType . ';base64,' . base64_encode($logoData);
        }
        $logoHtml = $logoBase64 ? '<img src="' . $logoBase64 . '" style="height: 60px;">' : '';


        // --- CONSTRUCCIÓN DEL HTML ---

        // 1. Estilos CSS 
        $html = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>';
        $html .= '<style>
            body { font-family: sans-serif; font-size: 9px; }
            table { width: 100%; border-collapse: collapse; }
            th, td { border: 1px solid #000000; padding: 3px; height: 18px; vertical-align: middle; word-wrap: break-word; }
            .page-break { page-break-after: always; }
            .no-break { page-break-inside: avoid; }
            
            .align-center { text-align: center; }
            .align-left { text-align: left !important; }
            .bold { font-weight: bold; }
            
            .title { 
                font-size: 20px; 
                color: #CC0000; 
                font-weight: bold; 
                text-align: center; /* Asegura centrado horizontal */
                vertical-align: middle; 
            }
            
            .header-row { background-color: #DDEBF7; }
            .header-label { background-color: #FFFFFF; font-weight: bold; }
            .header-value { background-color: #B4C6E7; font-weight: bold; text-align: left; }
            
            .section-header { 
                background-color: #E7E6E6; 
                font-weight: bold; 
                text-align: center; 
                border-bottom: 1px solid #000000;
            }
            
            .row-even { background-color: #E9F1FA; }
            .row-odd { background-color: #F2F2F2; }
            
            .value-shaded { 
                background-color: #F2F2F2; 
                text-align: left; 
            }
            .value-shaded-center {
                background-color: #F2F2F2; 
                text-align: center; 
            }
            
            .large-white-box {
                background-color: #FFFFFF;
                border-left: 1px solid #000000;
                border-top: none; 
                border-bottom: 1px solid #000000;
                border-right: 1px solid #000000;
            }
            
            .no-border { border: none; }
            
            /* Estilos para la sección de adjuntos */
            .attachments-container { margin-top: 20px; page-break-before: auto; }
            .attachment-item { margin-bottom: 15px; text-align: center; page-break-inside: avoid; }
            .attachment-image { max-height: 150px; max-width: 90%; height: auto; border: 1px solid #ccc; margin-top: 5px; display: block; margin-left: auto; margin-right: auto; }
            
        </style></head><body>';

        // 2. Logo (izquierda) y Título (centrado debajo)
        $html .= '<table style="margin-bottom: 10px;">
            <tr>
                <td class="no-border" style="width: 25%; vertical-align: top;">' . $logoHtml . '</td>
                <td class="no-border" style="width: 75%;"></td>
            </tr>
            <br>
            <tr>
                <td class="no-border title" colspan="2">RESUMEN</td>
            </tr>
            <br>
            <br>
            <br>
            <br>
        </table>';

        // 3. Tabla de Cabecera (Cliente, Proyecto...)
        $html .= '<table style="margin-bottom: 10px;">
            <tr class="header-row">
                <td class="header-label" style="width: 15%;">Cliente:</td>
                <td class="header-value" style="width: 25%;">' . htmlspecialchars($c->cliente) . '</td>
                <td class="header-label" style="width: 20%;">Nombre del Proyecto:</td>
                <td class="header-value" style="width: 40%;">' . htmlspecialchars($c->nombre_del_proyecto) . '</td>
            </tr>
            <tr class="header-row">
                <td class="header-label">Folio:</td>
                <td class="header-value">' . htmlspecialchars($c->no_proyecto) . '</td>
                <td class="header-label">Fecha:</td>
                <td class="header-value">' . htmlspecialchars($c->fecha) . '</td>
            </tr>
        </table>';

        // 4. Tabla Principal de Datos (Contiene encabezados y datos)
        $html .= '<table style="table-layout: fixed;" class="no-break">';

        // Definir anchos (8 columnas)
        $html .= '<colgroup>
            <col style="width: 20%">
            <col style="width: 15%">
            <col style="width: 7%">
            <col style="width: 8%">
            <col style="width: 15%">
            <col style="width: 10%">
            <col style="width: 10%">
            <col style="width: 15%">
        </colgroup>';

        // FILA: ENCABEZADOS DE SECCIÓN
        $html .= '<tr>
            <td class="section-header" colspan="4">Descripción del proyecto</td>
            <td class="section-header" colspan="4">Datos críticos</td>
        </tr>';


        // Datos principales (estas 6 filas mantienen la estructura de 8 columnas normal)
        $dataRowsMain = [
            ['Tipo de producto', $c->tipo_de_empaque, 'Estiba', ($c->requisicionCotizacion->tipo_estiba ?? '')],
            ['MOQ cotizado', ($c->especificacionProyecto->lote_compra ? $c->especificacionProyecto->lote_compra . ' piezas' : ''), 'Flujo de carga', ($c->requisicionCotizacion->tipo_flujo_carga ?? '')],
            ['Frecuencia de compra', ($c->especificacionProyecto->frecuencia_compra ?? ''), 'Dedales', ($c->cajaCliente->dedales ?? '')],
            ['Dimensiones finales de pieza', ($c->especificacionProyecto->pieza_largo . ' x ' . $c->especificacionProyecto->pieza_ancho . ' x ' . $c->especificacionProyecto->pieza_alto . ' mm'), 'Tipo de pared', ($c->requisicionCotizacion->pared ?? '')],
            [
                'Dimensiones finales de molde',
                (optional($c->costeoRequisicion)->insertos == 1
                    ? (optional($c->especificacionProyecto)->pieza_largo ?? '') . ' x ' . (optional($c->especificacionProyecto)->pieza_ancho ?? '') . ' x ' . (optional($c->especificacionProyecto)->pieza_alto ?? '') . ' mm'
                    : (optional($c->costeoRequisicion)->molde_ancho ?? '') . ' x ' . (optional($c->costeoRequisicion)->molde_avance ?? '') . ' x ' . (optional($c->especificacionProyecto)->pieza_alto ?? '') . ' mm'
                ),
                '# Cavidades',
                (optional($c->cotizacionAdicional)->componentes_por_charola ?? '')
            ],
            ['Fabricación de prototipo', ($c->cotizacionAdicional->prototipo ?? ''), 'Otra Información', (optional($c->especificacionEmpaque)->datos_criticos ?? '')],
        ];

        $rowIndex = 0;
        foreach ($dataRowsMain as $row) {
            $style = ($rowIndex % 2 == 0) ? 'row-even' : 'row-odd';
            $cellValue = htmlspecialchars($row[3]);
            if ($row[2] == 'Otra Información') {
                $cellValue = nl2br(htmlspecialchars($row[3]));
            }

            $html .= '<tr class="' . $style . '">
                <td class="bold">' . htmlspecialchars($row[0]) . '</td>
                <td class="value-shaded" colspan="3">' . htmlspecialchars($row[1]) . '</td>
                <td class="bold">' . htmlspecialchars($row[2]) . '</td>
                <td class="value-shaded" colspan="3">' . $cellValue . '</td>
            </tr>';
            $rowIndex++;
        }

        // --- DEFINICIÓN DE DATOS PARA CÁLCULO DE ROWSPAN ---
        $dataRowsSpan = [
            ['Especificación de material', ($c->especificacionProyecto->material ?? '')],
            ['Color', ($c->especificacionProyecto->color ?? '')],
            ['Franja de color', ($c->especificacionProyecto->franja_color ?? '')],
            ['Calibre', ($c->especificacionProyecto->calibre ?? ''), 'mm', 'value-shaded-center'],
            ['Ancho de material', ($c->costeoRequisicion->hoja_ancho ?? ''), 'mm', 'value-shaded-center'],
        ];
        $dataOrillas = [
            ['Vertical (cadenas):', $c->costeoRequisicion->acomodo_ancho_orillas_mm ?? '', 'mm'],
            ['Medianil vertical:', $c->costeoRequisicion->acomodo_ancho_medianiles_mm ?? '', 'mm'],
            ['Horizontal:', $c->costeoRequisicion->acomodo_avance_orillas_mm ?? '', 'mm'],
            ['Medianil horizontal:', $c->costeoRequisicion->acomodo_avance_medianiles_mm ?? '', 'mm'],
        ];
        $dataRowsFinal = [
            ['Insertos', $c->costeoRequisicion->insertos ?? ''],
            ['Placa de refrigeración', $valorPlaca],
            ['Máquina donde se produce', (($c->costeoRequisicion->nombre_maquina_termoformado ?? '') . ', ' . ($c->costeoRequisicion->nombre_maquina_suaje ?? ''))],
            ['Broche', $c->requisicionCotizacion->movimiento ?? ''],
            ['Pestaña', $c->cotizacionAdicional->pestana ?? ''],
            ['Grabados', $grabadoFinal],
            ['Poka yoke', $resumen->poka_yoke ?? ''],
            ['Acomodo de pieza', $resumen->acomodo_pieza ?? ''],
            ['Contenedor del cliente', $resumen->contenedor_cliente ?? ''],
            ['Medidas de contenedor', $resumen->medidas_contenedor ?? (($c->cajaCliente->caja_largo ?? '') . ' x ' . ($c->cajaCliente->caja_ancho ?? '') . ' x ' . ($c->cajaCliente->caja_alto ?? ''))],
            ['Estiba por contenedor', $resumen->estiba_contenedor ?? ''],
            ['Cliente proporciona', $resumen->cliente_proporciona ?? ''],
        ];

        // CÁLCULO DINÁMICO DEL ROWSPAN
        $totalRowsForLargeBox = count($dataRowsSpan) + count($dataOrillas) + count($dataRowsFinal); // Quitamos el +1 porque eliminamos la fila de título vacía en Orillas

        // --- INICIO DEL BLOQUE CON EL GRAN CUADRO BLANCO ---
        
        // 1. Abrimos la primera fila del bloque: Especificación de material
        $html .= '<tr class="' . (($rowIndex % 2 == 0) ? 'row-even' : 'row-odd') . '">';
        $row = $dataRowsSpan[0];
        
        $html .= '<td class="bold">' . htmlspecialchars($row[0]) . '</td>';
        $html .= '<td class="value-shaded" colspan="3">' . htmlspecialchars($row[1]) . '</td>';
        
        // * LA GRAN CELDA BLANCA CON ROWSPAN *
        $html .= '<td class="large-white-box" rowspan="' . $totalRowsForLargeBox . '" colspan="4"></td>';
        $html .= '</tr>';
        $rowIndex++;


        // 2. Resto de Filas de Especificación de Material
        for ($i = 1; $i < count($dataRowsSpan); $i++) {
            $row = $dataRowsSpan[$i];
            $style = ($rowIndex % 2 == 0) ? 'row-even' : 'row-odd';
            $html .= '<tr class="' . $style . '">';
            $html .= '<td class="bold">' . htmlspecialchars($row[0]) . '</td>';
            
            $valueClass = $row[3] ?? 'value-shaded';

            if (isset($row[2])) { // Caso de Calibre y Ancho
                $html .= '<td class="' . $valueClass . '">' . htmlspecialchars($row[1]) . '</td>';
                $html .= '<td class="' . $valueClass . '" colspan="2">' . htmlspecialchars($row[2]) . '</td>';
            } else { // Caso normal (Color, Franja)
                $html .= '<td class="value-shaded" colspan="3">' . htmlspecialchars($row[1]) . '</td>';
            }
            $html .= '</tr>';
            $rowIndex++;
        }

        // 3. Detalles de Orillas (SIN FILA DE TÍTULO VACÍA ANTERIOR)
        foreach ($dataOrillas as $index => $row) {
            $style = ($rowIndex % 2 == 0) ? 'row-even' : 'row-odd';
            $html .= '<tr class="' . $style . '">';

            // La primera celda de la fila es "Orillas" y tiene rowspan
            if ($index == 0) {
                $html .= '<td class="bold" rowspan="' . count($dataOrillas) . '">Orillas</td>';
            }
            
            $html .= '<td class="bold">' . htmlspecialchars($row[0]) . '</td>';
            $html .= '<td class="value-shaded-center">' . htmlspecialchars($row[1]) . '</td>';
            $html .= '<td class="value-shaded-center">' . htmlspecialchars($row[2]) . '</td>';
            $html .= '</tr>';
            $rowIndex++;
        }


        // 4. Imprimir filas finales
        foreach ($dataRowsFinal as $row) {
            $style = ($rowIndex % 2 == 0) ? 'row-even' : 'row-odd';
            $html .= '<tr class="' . $style . '">';
            $html .= '<td class="bold">' . htmlspecialchars($row[0]) . '</td>';

            $html .= '<td class="value-shaded-center" colspan="3">' . htmlspecialchars($row[1]) . '</td>';
            
            $html .= '</tr>';
            $rowIndex++;
        }

        $html .= '</table>';

        // --- SECCIÓN DE ADJUNTOS (Sólo imágenes) ---
        // --- SECCIÓN DE ADJUNTOS (RESUMEN - sólo imágenes) ---
if ($c->resumen && $c->resumen->archivos->count() > 0) {

    $html .= '<div class="attachments-container no-break">';
    $html .= '<h3 class="section-header" style="padding: 8px; margin-top: 20px; border-top: 1px solid #000;">
                Archivos Adjuntos
              </h3>'; 
    $html .= '<div style="padding: 10px; border: 1px solid #000; border-top: none;">';

    foreach ($c->resumen->archivos as $archivo) {

        $fullPath = storage_path('app/public/' . $archivo->path);

        if (file_exists($fullPath) && @is_array(getimagesize($fullPath))) {

            $imgType = pathinfo($fullPath, PATHINFO_EXTENSION);
            $imgData = file_get_contents($fullPath);
            $imgBase64 = 'data:image/' . $imgType . ';base64,' . base64_encode($imgData);

            $html .= '<div class="attachment-item">';
            $html .= '<img src="' . $imgBase64 . '" class="attachment-image">';
            $html .= '</div>';
        }
    }

    

            $html .= '</div>';
            $html .= '</div>';
        }
        // --- FIN DE SECCIÓN DE ADJUNTOS ---

        $html .= '</body></html>';

        // --- GENERACIÓN DE PDF CON DOMPDF ---

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('tabloid', 'portrait');

        $dompdf->render();

        $fileName = 'Resumen_Cotizacion_' . ($c->no_proyecto ?? $c->id) . '.pdf';
        $dompdf->stream($fileName, ["Attachment" => false]);
        exit;
    }

     public function generarResumenCostosPdf($id)
    {
        // Obtener datos
        $cotizacion = \App\Models\Cotizacion::with([
            'costeoRequisicion',
            'costeoCorridaPiloto'
        ])->findOrFail($id);

        // Usar corrida piloto si existe, si no usar costeo normal
        $costeo = $cotizacion->costeoCorridaPiloto ?? $cotizacion->costeoRequisicion;

        // Logo en Base64
        $logoPath = public_path('images/innovet-logo.png');
        $logoBase64 = '';
        if (file_exists($logoPath)) {
            $logoType = pathinfo($logoPath, PATHINFO_EXTENSION);
            $logoData = file_get_contents($logoPath);
            $logoBase64 = 'data:image/' . $logoType . ';base64,' . base64_encode($logoData);
        }
        $logoHtml = $logoBase64 ? '<img src="' . $logoBase64 . '" style="height: 60px;">' : 'INNOVET';

        // Construcción del HTML
        $html = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>';
        $html .= '<style>
            body { font-family: sans-serif; font-size: 10px; }
            table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
            th, td { border: 1px solid #333333; padding: 8px; text-align: center; vertical-align: middle; }
            .bold { font-weight: bold; }
            .bg-gray { background-color: #BFBFBF; }
            .bg-yellow { background-color: #FFFACD; }
            .bg-blue { background-color: #BFBFBF; }
            .title { font-size: 20px; font-weight: bold; color: #A41C24; margin: 20px 0; text-align: center; }
            .comment-box { 
                background-color: #BFBFBF; 
                border: 2px solid #5e5e5e; 
                padding: 15px; 
                margin: 20px 0; 
                border-radius: 5px;
            }
            .comment-title { 
                color: #242424; 
                font-size: 14px; 
                font-weight: bold; 
                margin-bottom: 8px;
            }
            .comment-text { 
                color: #242424; 
                font-size: 11px; 
                line-height: 1.5;
            }
            .header-cell { background-color: #BFBFBF; font-weight: bold; }
            .readonly { background-color: #F2F2F2; }
        </style></head><body>';

        // Logo y título
        $html .= '<div style="text-align: center; margin-bottom: 20px;">' . $logoHtml . '</div>';

        // Comentario de corrida piloto
        $html .= '<div class="comment-box">
            <div class="comment-title">Comentarios de costeo</div>
            <div class="comment-text">Los costos de la Fabricación de CP no se reflejan en el resumen de costos final. Se puede encontrar en la tabla inferior "COSTOS DE CORRIDA PILOTO".</div>
        </div>';

        // Título de la sección
        $html .= '<div class="title">Resumen de Costos Corrida Piloto</div>';

        // Tabla principal (SIN columna Margen)
        $html .= '<table>
            <thead>
                <tr>
                    <th class="header-cell">Concepto</th>
                    <th class="header-cell">Costo total</th>
                    <th class="header-cell">Piezas</th>
                    <th class="header-cell">Costo Unit</th>
                </tr>
            </thead>
            <tbody>';

        // Filas de datos
        $conceptos = [
            ['label' => 'Procesos de Maquinaria', 'prefix' => 'procesos'],
            ['label' => 'Empaque', 'prefix' => 'empaque'],
            ['label' => 'Flete', 'prefix' => 'flete', 'costo_field' => 'resumen_costo_flete_total'],
            ['label' => 'Pedimento', 'prefix' => 'pedimento'],
        ];

        foreach ($conceptos as $concepto) {
            $prefix = $concepto['prefix'];
            $costoField = $concepto['costo_field'] ?? "resumen_costo_{$prefix}";
            
            $html .= '<tr>
                <td class="bold">' . $concepto['label'] . '</td>
                <td>' . number_format($costeo->$costoField ?? 0, 4) . '</td>
                <td>' . number_format($costeo->{"resumen_piezas_{$prefix}"} ?? 0, 0) . '</td>
                <td class="readonly">' . number_format($costeo->{"resumen_costo_unit_{$prefix}"} ?? 0, 4) . '</td>
            </tr>';
        }

        // Fila de Total Procesos Adicionales
        $html .= '<tr>
            <td class="bold">Total Procesos Adicionales</td>
            <td class="readonly">' . number_format($costeo->resumen_total_costo_adicionales ?? 0, 4) . '</td>
            <td class="readonly">1</td>
            <td class="readonly">' . number_format($costeo->resumen_total_costo_unit_adicionales ?? 0, 4) . '</td>
        </tr>';

        $html .= '</tbody><tfoot>';

        // Margen Administrativo
        $html .= '<tr class="bg-blue">
            <td colspan="3" class="bold" style="text-align: right;">Margen Administrativo</td>
            <td class="readonly bold">' . number_format($costeo->resumen_margen_administrativo ?? 0, 4) . '</td>
        </tr>';

        // Costo Unitario
        $html .= '<tr class="bg-blue">
            <td colspan="3" class="bold" style="text-align: right;">Costo Unitario</td>
            <td class="readonly bold">' . number_format($costeo->resumen_total_costo_unit ?? 0, 4) . '</td>
        </tr>';

        $html .= '</tfoot></table>';

        // Footer
        $html .= '<div style="margin-top: 30px; border-top: 1px solid #000; padding-top: 10px; font-size: 9px; text-align: center;">
            <p>Av Del Marqués lote 7. Parque industrial Bernardo Quintana. El Marqués, Querétaro, C.P 76246</p>
        </div>';
        // <p>ACF06 | Fecha de efectividad: 28-Mayo-2024 | Revisión: 05</p>

        $html .= '</body></html>';

        // Generar PDF
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        $fileName = 'Resumen_Costos_CP_' . ($cotizacion->no_proyecto ?? $cotizacion->id) . '.pdf';
        $dompdf->stream($fileName, ["Attachment" => false]);
        exit;
    }

}
