<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\CotizacionExcelMailable;
use App\Models\Cotizacion;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

// ➡️ IMPORTACIONES DE PHPSPREADSHEET
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Font;


class MailCotizacionController extends Controller
{
    /**
     * Genera el archivo Excel usando PHPSpreadsheet y lo envía por correo electrónico.
     */
    public function enviarCotizacionExcel(Request $request, $id)
    {
        // 1. Validar y obtener datos
        $request->validate([
            'correo_destino' => 'required|email',
        ]);

        $cotizacion = Cotizacion::with([
            'especificacionProyecto', 
            'costeoRequisicion', 
            'archivosAdjuntos'
        ])->findOrFail($id);
        
        $correoDestino = $request->correo_destino;
        
        // --- Preparación de archivos y rutas ---
        $fileName = 'Cotizacion_' . $cotizacion->no_proyecto . '.xlsx';
        $storagePath = 'temp/emails/' . $fileName; // Usamos un subdirectorio para emails
        
        // Obtener la ruta ABSOLUTA para guardar y adjuntar
        $absolutePath = Storage::disk('public')->path($storagePath);

        // Asegurarse de que el directorio temporal exista
        if (!Storage::disk('public')->exists('temp/emails')) {
            Storage::disk('public')->makeDirectory('temp/emails');
        }

        try {
            // 2. GENERAR EL EXCEL y GUARDARLO
            $spreadsheet = new Spreadsheet();
            $this->llenarHojaDeCalculo($spreadsheet->getActiveSheet(), $cotizacion);
            
            $writer = new Xlsx($spreadsheet);
            $writer->save($absolutePath); // Guardar el archivo temporalmente

            // 3. ENVIAR CORREO
            Mail::to($correoDestino)
                ->send(new CotizacionExcelMailable($cotizacion, $absolutePath)); 
            
            // 4. LIMPIAR EL ARCHIVO TEMPORAL
            Storage::disk('public')->delete($storagePath);

            return back()->with('success', 'La cotización se envió correctamente a ' . $correoDestino);
        
        } catch (\Exception $e) {
            // Asegurar que el archivo se borre si el envío falla
            if (Storage::disk('public')->exists($storagePath)) {
                Storage::disk('public')->delete($storagePath);
            }
            // Retornar error para debugging
            return back()->with('error', 'Error al enviar el correo. Detalles: ' . $e->getMessage());
        }
    }

    /**
     * Contiene la lógica exacta de tu anterior generarCotizacionExcel para llenar la hoja.
     * @param \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet
     * @param \App\Models\Cotizacion $cotizacion
     */
    private function llenarHojaDeCalculo($sheet, $cotizacion)
    {
        // ⚠️ INICIA COPIA DE LA LÓGICA DE TU ExcelController::generarCotizacionExcel A PARTIR DE AQUÍ

        // Configuración inicial
        $sheet->getDefaultColumnDimension()->setWidth(15);
        $sheet->getDefaultRowDimension()->setRowHeight(25);

        // Estilos reutilizables (Copia todos tus estilos)
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
        $sheet->setCellValue('H' . $currentRow, 'Precio Unitario (USD/MXN)');

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
        $sheet->setCellValue('F' . $currentRow, 'Cristal');

        $sheet->getStyle('B' . $currentRow . ':F' . $currentRow)->applyFromArray($specHeaderStyle);

        $currentRow++;

        // Datos de especificaciones
        $sheet->setCellValue('B' . $currentRow, $cotizacion->especificacionProyecto->pieza_largo . ' x ' .
            $cotizacion->especificacionProyecto->pieza_ancho . ' x ' .
            $cotizacion->especificacionProyecto->pieza_alto);
        $sheet->setCellValue('C' . $currentRow, $cotizacion->especificacionProyecto->frecuencia_compra);
        $sheet->setCellValue('D' . $currentRow, $cotizacion->especificacionProyecto->material);
        $sheet->setCellValue('E' . $currentRow, $cotizacion->especificacionProyecto->calibre);
        $sheet->setCellValue('F' . $currentRow, $cotizacion->especificacionProyecto->color);

        $sheet->getStyle('B' . $currentRow . ':F' . $currentRow)->applyFromArray($specDataStyle);

        // Piezas y precio - abarcan desde fila 9 hasta fila 11 (3 filas)
        $sheet->setCellValue('G9', $cotizacion->especificacionProyecto->lote_compra);
        $sheet->getStyle('G9')->applyFromArray($grayCellStyle);
        $sheet->mergeCells('G9:G11');

        $precioUnitario = $cotizacion->costeoRequisicion->resumen_total_precio_venta / 18;
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
        $sheet->setCellValue('B' . $currentRow, 'Descripción del proyecto');
        $sheet->setCellValue('G' . $currentRow, '');
        $sheet->setCellValue('H' . $currentRow, 'Precio Unitario (USD/MXN)');

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

        $precioHerramentales = $cotizacion->costeoRequisicion->TOTAL_VENTAS_USD;
        $sheet->setCellValue('H' . $herramentalesStartRow, '$ ' . number_format($precioHerramentales, 2));
        $sheet->getStyle('H' . $herramentalesStartRow)->applyFromArray($greenPriceStyle);
        $sheet->mergeCells('H' . $herramentalesStartRow . ':H' . $currentRow);

        $currentRow += 2;

        // SECCIÓN DE IMAGEN ILUSTRATIVA (Se simplificó la búsqueda de ruta)
        $imagenPath = null;
        if ($cotizacion->archivosAdjuntos && $cotizacion->archivosAdjuntos->isNotEmpty()) {
            foreach ($cotizacion->archivosAdjuntos as $archivo) {
                $extension = strtolower(pathinfo($archivo->path, PATHINFO_EXTENSION));
                if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'bmp'])) {
                    // Intenta obtener la ruta a través del disco 'public'
                    $storageFilePath = 'public/' . $archivo->path; // Asume que la ruta se guardó como 'uploads/archivo.png'
                    if (Storage::exists($storageFilePath)) {
                        $imagenPath = Storage::path($storageFilePath); // Obtiene la ruta absoluta
                        break; 
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
    }
}