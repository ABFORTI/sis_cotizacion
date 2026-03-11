<?php

namespace App\Http\Controllers;

use App\Models\Cotizacion;
use App\Services\ExcelReportService;
use App\Services\PdfReportService;
use App\Support\CotizacionConfig;
use App\Support\ExcelStyleFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Dompdf\Dompdf;
use Dompdf\Options;


class ExcelController extends Controller
{

    public function generarCotizacionExcel($id)
    {
        try {
            $cotizacion = Cotizacion::with([
                'especificacionProyecto',
                'costeoRequisicion',
                'archivosAdjuntos',
                'ventasResumen'
            ])->findOrFail($id);

            $service = new ExcelReportService($cotizacion);
            $this->llenarCotizacionExcel($service);

            $fileName = 'Cotizacion_' . $cotizacion->no_proyecto . '.xlsx';
            return $service->download($fileName);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al generar Excel',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    /**
     * Generar cotización en formato PDF
     */
    public function generarCotizacionPdf($id)
    {
        try {
            $cotizacion = Cotizacion::with([
                'especificacionProyecto',
                'costeoRequisicion',
                'archivosAdjuntos',
                'ventasResumen'
            ])->findOrFail($id);

            $service = new PdfReportService($cotizacion);
            $this->llenarCotizacionPdf($service);

            $fileName = 'Cotizacion_' . $cotizacion->no_proyecto . '.pdf';
            return $service->download($fileName);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al generar PDF',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    /**
     * Generar lineamientos en formato Excel
     */
    public function generarLineamientosExcel(Request $request, $id)
    {
        try {
            $cotizacion = Cotizacion::with([
                'especificacionProyecto',
                'costeoRequisicion'
            ])->findOrFail($id);

            $service = new ExcelReportService($cotizacion);
            $this->llenarLineamientosExcel($service, $request);

            $fileName = 'Lineamientos_' . $cotizacion->no_proyecto . '.xlsx';
            return $service->download($fileName);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al generar Excel de lineamientos',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    /**
     * Generar lineamientos en formato PDF
     */
    public function generarLineamientosPdf(Request $request, $id)
    {
        try {
            $cotizacion = Cotizacion::with([
                'especificacionProyecto',
                'costeoRequisicion'
            ])->findOrFail($id);

            $service = new PdfReportService($cotizacion);
            $this->llenarLineamientosPdf($service, $request);

            $fileName = 'Lineamientos_' . $cotizacion->no_proyecto . '.pdf';
            return $service->download($fileName);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al generar PDF de lineamientos',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    /**
     * Generar resumen de costeo en formato Excel
     */
    public function generarCosteoResumenExcel(Request $request, $id)
    {
        try {
            $cotizacion = Cotizacion::with([
                'requisicionCotizacion',
                'especificacionProyecto',
                'cotizacionAdicional',
                'cajaCliente',
                'costeoRequisicion',
                'especificacionEmpaque',
                'resumen',
                'archivosAdjuntos',
                'ventasResumen'
            ])->findOrFail($id);

            $service = new ExcelReportService($cotizacion);
            $this->llenarCosteoExcel($service, $request);

            $fileName = 'Costeo_' . $cotizacion->no_proyecto . '.xlsx';
            return $service->download($fileName);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al generar Excel de costeo',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    /**
     * Generar resumen de costeo en formato PDF
     */
    public function generarCosteoResumenPdf(Request $request, $id)
    {
        try {
            $cotizacion = Cotizacion::with([
                'requisicionCotizacion',
                'especificacionProyecto',
                'cotizacionAdicional',
                'cajaCliente',
                'costeoRequisicion',
                'especificacionEmpaque',
                'resumen',
                'archivosAdjuntos'
            ])->findOrFail($id);

            $service = new PdfReportService($cotizacion);
            $this->llenarCostoePdf($service, $request);

            $fileName = 'Costeo_' . $cotizacion->no_proyecto . '.pdf';
            return $service->download($fileName);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al generar PDF de costeo',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    /**
     * Generar Excel combinado: Cotización + Lineamientos
     */
    public function generarCotizacionLineamientosExcel(Request $request, $id)
    {
        try {
            $cotizacion = Cotizacion::with([
                'especificacionProyecto',
                'costeoRequisicion',
                'archivosAdjuntos',
                'ventasResumen'
            ])->findOrFail($id);

            $service = new ExcelReportService($cotizacion);
            
            // Llenar primera hoja: Cotización
            $this->llenarCotizacionExcel($service);
            $service->getSheet()->setTitle('Cotización');
            
            // Crear segunda hoja: Lineamientos
            $sheet2 = $service->getSpreadsheet()->createSheet();
            $sheet2->setTitle('Lineamientos');
            
            // Establecer la segunda hoja como activa en el servicio
            $service->setSheet($sheet2);
            
            $this->llenarLineamientosExcel($service, $request);
            
            $fileName = 'Cotizacion_Completa_' . $cotizacion->no_proyecto . '.xlsx';
            return $service->download($fileName);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al generar Excel combinado',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    /**
     * Generar PDF combinado: Cotización + Lineamientos
     */
    public function generarCotizacionLineamientosPdf(Request $request, $id)
    {
        try {
            $cotizacion = Cotizacion::with([
                'especificacionProyecto',
                'costeoRequisicion',
                'archivosAdjuntos',
                'ventasResumen'
            ])->findOrFail($id);

            $service = new PdfReportService($cotizacion);
            
            // Encabezado y cotización
            $service->agregarEncabezado();
            $service->agregarInfoCliente();
            
            $html = $this->generarTablaProductoPdf($service);
            $service->agregarHTML($html);
            
            $html = $this->generarTablaHerramentalesPdf($service);
            $service->agregarHTML($html);
            
            $service->agregarImagenIlustrativa();
            
            // Lineamientos
            $html = $this->generarSeccionLineamientosPdf($service);
            $service->agregarHTML('<div style="page-break-before: always;"></div>' . $html);
            
            $service->agregarFooter();

            $fileName = 'Cotizacion_Completa_' . $cotizacion->no_proyecto . '.pdf';
            return $service->download($fileName);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al generar PDF combinado',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    /**
     * ========== MÉTODOS PRIVADOS PARA LLENAR REPORTES ==========
     */

    /**
     * Llenar Excel de cotización con diseño profesional
     */
    private function llenarCotizacionExcel(ExcelReportService $service): void
    {
        try {
            $cotizacion = $service->getCotizacion();
            $especificacion = $service->getEspecificacionProyecto();
            $sheet = $service->getSheet();

            // Configurar ancho de columnas - SIN MERGES
            $service->setColumnWidths([
                'A' => 35,
                'B' => 30,
                'C' => 30,
                'D' => 14,
                'E' => 14,
                'F' => 14,
                'G' => 11,
                'H' => 19,
            ]);

            // ===== ENCABEZADO CON LOGO =====
            $service->agregarLogo('A1');
            
            // Aumentar altura de filas para que se vea el logo (altura total ~120)
            $sheet->getRowDimension(1)->setRowHeight(40);
            $sheet->getRowDimension(2)->setRowHeight(40);
            $sheet->getRowDimension(3)->setRowHeight(20);
            $sheet->getRowDimension(4)->setRowHeight(20);
            
            // Folio y Fecha
            $sheet->setCellValue('F1', 'Folio:');
            $sheet->getStyle('F1')->applyFromArray(ExcelStyleFactory::custom(['bold' => true, 'fontSize' => 11]));
            $sheet->setCellValue('G1', $cotizacion->no_proyecto);

            $sheet->setCellValue('F2', 'Fecha:');
            $sheet->getStyle('F2')->applyFromArray(ExcelStyleFactory::custom(['bold' => true, 'fontSize' => 11]));
            $sheet->setCellValue('G2', $cotizacion->fecha);

            // ===== INFORMACIÓN DEL CLIENTE =====
            $row = 4;
            
            // Encabezado CLIENTE
            for ($col = 'A'; $col <= 'H'; $col++) {
                $sheet->getStyle($col . $row)->applyFromArray(ExcelStyleFactory::headerStyle());
            }
            $sheet->setCellValue('A' . $row, 'CLIENTE');
            $sheet->getRowDimension($row)->setRowHeight(18);
            
            // Nombre del cliente
            $row++;
            $sheet->setCellValue('A' . $row, htmlspecialchars($cotizacion->cliente ?? 'N/A'));
            $sheet->getStyle('A' . $row)->applyFromArray(ExcelStyleFactory::custom(['bold' => true, 'fontSize' => 13]));
            for ($col = 'B'; $col <= 'H'; $col++) {
                $sheet->getStyle($col . $row)->applyFromArray(ExcelStyleFactory::custom(['fontSize' => 11]));
            }
            $sheet->getRowDimension($row)->setRowHeight(22);

            // Puesto
            $row++;
            $sheet->setCellValue('A' . $row, 'Puesto:');
            $sheet->getStyle('A' . $row)->applyFromArray(ExcelStyleFactory::custom(['bold' => true, 'fontSize' => 10]));
            $sheet->setCellValue('B' . $row, htmlspecialchars($cotizacion->puesto ?? 'N/A'));
            
            // Correo
            $sheet->setCellValue('G' . $row, 'Email:');
            $sheet->getStyle('G' . $row)->applyFromArray(ExcelStyleFactory::custom(['bold' => true, 'fontSize' => 10]));
            $sheet->setCellValue('H' . $row, htmlspecialchars($cotizacion->correo ?? 'N/A'));
            $sheet->getStyle('H' . $row)->applyFromArray(ExcelStyleFactory::custom(['color' => 'B50B0B', 'fontSize' => 10]));

            // Teléfono
            $sheet->setCellValue('D' . $row, 'Teléfono:');
            $sheet->getStyle('D' . $row)->applyFromArray(ExcelStyleFactory::custom(['bold' => true, 'fontSize' => 10]));
            $sheet->setCellValue('E' . $row, htmlspecialchars($cotizacion->telefono ?? 'N/A'));
            $sheet->getStyle('E' . $row)->applyFromArray(ExcelStyleFactory::custom(['color' => 'B50B0B', 'fontSize' => 10]));

            // ===== TABLA DE PRODUCTOS (Formato similar al PDF) =====
            $row += 2;
            
            // ROW 1: Encabezado principal
            $sheet->setCellValue('A' . $row, '#');
            $sheet->setCellValue('B' . $row, 'Descripción del Proyecto');
            $sheet->setCellValue('C' . $row, 'Dimensiones');
            $sheet->setCellValue('D' . $row, 'Frecuencia');
            $sheet->setCellValue('E' . $row, 'Material');
            $sheet->setCellValue('F' . $row, 'Espesor');
            $sheet->setCellValue('G' . $row, 'MOQ');
            $sheet->setCellValue('H' . $row, 'Precio Unit. (MXN)');

            for ($col = 'A'; $col <= 'H'; $col++) {
                $sheet->getStyle($col . $row)->applyFromArray(ExcelStyleFactory::headerStyle());
            }
            $sheet->getRowDimension($row)->setRowHeight(18);

            // ROW 2: Datos principales
            $row++;
            $sheet->setCellValue('A' . $row, '1');
            $sheet->getStyle('A' . $row)->applyFromArray(ExcelStyleFactory::custom(['bold' => true, 'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]]));
            $sheet->getStyle('A' . $row)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            
            $sheet->setCellValue('B' . $row, htmlspecialchars($cotizacion->nombre_del_proyecto ?? 'N/A'));
            $sheet->getStyle('B' . $row)->applyFromArray(ExcelStyleFactory::custom(['bold' => true, 'color' => 'B50B0B']));
            
            $sheet->setCellValue('C' . $row, $service->getDimensionesFormato());
            $sheet->setCellValue('D' . $row, htmlspecialchars($especificacion['frecuencia'] ?? 'N/C'));
            $sheet->setCellValue('E' . $row, htmlspecialchars($especificacion['material'] ?? 'N/C'));
            $sheet->setCellValue('F' . $row, htmlspecialchars($especificacion['calibre'] ?? 'N/C'));
            $sheet->setCellValue('G' . $row, htmlspecialchars($especificacion['lote_compra'] ?? 'N/C'));
            
            $precioUnitario = $service->getPrecioUnitario();
            $sheet->setCellValue('H' . $row, '$ ' . number_format($precioUnitario, 2));
            $sheet->getStyle('H' . $row)->applyFromArray(ExcelStyleFactory::greenPriceStyle());

            // Aplicar bordes y estilos a todas las celdas de esta fila
            for ($col = 'A'; $col <= 'H'; $col++) {
                if ($col !== 'H') {
                    $sheet->getStyle($col . $row)->applyFromArray(ExcelStyleFactory::normalStyle());
                }
                $sheet->getStyle($col . $row)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            }
            $sheet->getRowDimension($row)->setRowHeight(18);

            // ROW 3: Etiquetas de especificaciones (hidden conceptually, for reference)
            $row++;
            $sheet->setCellValue('A' . $row, '');
            $sheet->setCellValue('B' . $row, 'Color');
            $sheet->setCellValue('C' . $row, 'Especificación');
            $sheet->setCellValue('D' . $row, '');
            $sheet->setCellValue('E' . $row, '');
            $sheet->setCellValue('F' . $row, '');
            $sheet->setCellValue('G' . $row, '');
            $sheet->setCellValue('H' . $row, '');

            for ($col = 'B'; $col <= 'C'; $col++) {
                $sheet->getStyle($col . $row)->applyFromArray(ExcelStyleFactory::custom([
                    'bold' => true,
                    'bgColor' => 'D9D9D9',
                    'fontSize' => 9,
                ]));
                $sheet->getStyle($col . $row)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            }
            $sheet->getRowDimension($row)->setRowHeight(14);

            // ROW 4: Valores de especificaciones
            $row++;
            $sheet->setCellValue('A' . $row, '');
            $sheet->setCellValue('B' . $row, htmlspecialchars($especificacion['color'] ?? 'N/C'));
            $sheet->setCellValue('C' . $row, htmlspecialchars($especificacion['especificacion'] ?? 'N/C'));
            
            for ($col = 'B'; $col <= 'C'; $col++) {
                $sheet->getStyle($col . $row)->applyFromArray(ExcelStyleFactory::normalStyle());
                $sheet->getStyle($col . $row)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            }
            $sheet->getRowDimension($row)->setRowHeight(16);

            // ===== TABLA DE HERRAMENTALES =====
            $row += 2;
            
            // Encabezado herramentales
            $sheet->setCellValue('A' . $row, '#');
            $sheet->setCellValue('B' . $row, 'Descripción');
            $sheet->setCellValue('C' . $row, 'Detalles');
            $sheet->setCellValue('H' . $row, 'Precio Total (MXN)');

            for ($col = 'A'; $col <= 'H'; $col++) {
                $sheet->getStyle($col . $row)->applyFromArray(ExcelStyleFactory::headerStyle());
            }
            $sheet->getRowDimension($row)->setRowHeight(18);

            // Datos herramentales
            $row++;
            $sheet->setCellValue('A' . $row, '2');
            $sheet->getStyle('A' . $row)->applyFromArray(ExcelStyleFactory::custom(['bold' => true, 'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]]));
            
            $sheet->setCellValue('B' . $row, 'Desarrollo de Herramentales');
            $sheet->getStyle('B' . $row)->applyFromArray(ExcelStyleFactory::custom(['bold' => true]));
            
            $sheet->setCellValue('C' . $row, 'Incluye 3 muestras para liberación');
            $sheet->getStyle('C' . $row)->getAlignment()->setWrapText(true);
            
            // Celdas vacías
            for ($col = 'D'; $col <= 'G'; $col++) {
                $sheet->setCellValue($col . $row, '');  
            }
            
            $precioHerramentales = $service->getPrecioHerramentales();
            $sheet->setCellValue('H' . $row, '$ ' . number_format($precioHerramentales, 2));
            $sheet->getStyle('H' . $row)->applyFromArray(ExcelStyleFactory::greenPriceStyle());

            // Bordes
            for ($col = 'A'; $col <= 'H'; $col++) {
                $sheet->getStyle($col . $row)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            }
            $sheet->getRowDimension($row)->setRowHeight(20);

            // ===== IMAGEN ILUSTRATIVA =====
            $row += 2;
            $service->agregarImagenIlustrativa('B' . $row, 180);

            // ===== FOOTER =====
            $row += 6;
            $sheet->setCellValue('A' . $row, CotizacionConfig::COMPANY_ADDRESS);
            $sheet->getStyle('A' . $row)->applyFromArray(ExcelStyleFactory::custom(['fontSize' => 9]));

            $row++;
            $sheet->setCellValue('A' . $row, CotizacionConfig::COMPANY_FOOTER);
            $sheet->getStyle('A' . $row)->applyFromArray(ExcelStyleFactory::custom(['fontSize' => 9]));

        } catch (\Exception $e) {
            error_log('Error en llenarCotizacionExcel: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Llenar PDF de cotización
     */
    private function llenarCotizacionPdf(PdfReportService $service): void
    {
        $service->agregarEncabezado();
        $service->agregarInfoCliente();

        $html = $this->generarTablaProductoPdf($service);
        $service->agregarHTML($html);

        $html = $this->generarTablaHerramentalesPdf($service);
        $service->agregarHTML($html);

        $service->agregarImagenIlustrativa();

        $html = $this->generarSeccionLineamientosPdf($service);
        $service->agregarHTML('<div style="page-break-before: always;"></div>' . $html);

        $service->agregarFooter();
    }

    /**
     * Llenar Excel de lineamientos
     */
    private function llenarLineamientosExcel(ExcelReportService $service, Request $request): void
    {
        try {
            $cotizacion = $service->getCotizacion();
            $lineamientos = $service->getLineamientos();
            $contacto = $service->getDatosContacto(Auth::user()?->name);
            $sheet = $service->getSheet();

            // Configurar columnas - SIN MERGES
            $service->setColumnWidths(['A' => 34, 'B' => 80]);

            // ===== ENCABEZADO =====
            $service->agregarLogo('A1');
            
            // Aumentar altura de filas para que se vea el logo
            $sheet->getRowDimension(1)->setRowHeight(40);
            $sheet->getRowDimension(2)->setRowHeight(40);
            $sheet->getRowDimension(3)->setRowHeight(5);
            $sheet->getRowDimension(4)->setRowHeight(34);
            
            // Folio y Fecha
            $sheet->setCellValue('F1', 'Folio:');
            $sheet->getStyle('F1')->applyFromArray(ExcelStyleFactory::custom(['bold' => true]));
            $sheet->setCellValue('G1', $cotizacion->no_proyecto);

            $sheet->setCellValue('F2', 'Fecha:');
            $sheet->getStyle('F2')->applyFromArray(ExcelStyleFactory::custom(['bold' => true]));
            $sheet->setCellValue('G2', $cotizacion->fecha);

            // ===== TÍTULO =====
            $row = 4;
            $sheet->setCellValue('A' . $row, 'LINEAMIENTOS DEL PROYECTO');
            $sheet->getStyle('A' . $row)->applyFromArray(ExcelStyleFactory::custom([
                'bold' => true,
                'color' => 'B50B0B',
                'fontSize' => 14,
            ]));
            for ($col = 'A'; $col <= 'B'; $col++) {
                $sheet->getStyle($col . $row)->applyFromArray(ExcelStyleFactory::custom([
                    'bold' => true,
                    'color' => 'B50B0B',
                    'fontSize' => 14,
                ]));
            }
            $sheet->getRowDimension($row)->setRowHeight(25);

            // ===== LINEAMIENTOS EN TABLA =====
            $row += 2;

            // Encabezado de tabla
            $sheet->setCellValue('A' . $row, '#');
            $sheet->setCellValue('B' . $row, 'Lineamiento');

            for ($col = 'A'; $col <= 'B'; $col++) {
                $sheet->getStyle($col . $row)->applyFromArray(ExcelStyleFactory::headerStyle());
            }
            $sheet->getRowDimension($row)->setRowHeight(18);

            // Lineamientos
            $row++;
            $itemNum = 1;
            foreach ($lineamientos as $lineamiento) {
                $sheet->setCellValue('A' . $row, $itemNum);
                $sheet->setCellValue('B' . $row, htmlspecialchars($lineamiento));

                // Aplicar estilos
                $sheet->getStyle('A' . $row)->applyFromArray(ExcelStyleFactory::custom(['bold' => true]));
                $sheet->getStyle('A' . $row)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('B' . $row)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('B' . $row)->getAlignment()->setWrapText(true);

                $sheet->getRowDimension($row)->setRowHeight(-1); // Auto height
                $row++;
                $itemNum++;
            }

            // ===== FIRMA =====
            $row += 2;
            $sheet->setCellValue('A' . $row, 'Atentamente');
            $sheet->getStyle('A' . $row)->applyFromArray(ExcelStyleFactory::custom([
                'bold' => true,
                'color' => 'B50B0B',
                'fontSize' => 12,
            ]));

            $row ++;
            $sheet->setCellValue('A' . $row, htmlspecialchars($contacto['nombre']));
            $sheet->getStyle('A' . $row)->applyFromArray(ExcelStyleFactory::custom(['bold' => true, 'fontSize' => 11]));

            $row++;
            $sheet->setCellValue('A' . $row, htmlspecialchars($contacto['puesto']));

            // ===== FOOTER =====
            $row += 2;
            $sheet->setCellValue('B' . $row, CotizacionConfig::COMPANY_ADDRESS);
            $sheet->getStyle('B' . $row)->applyFromArray(ExcelStyleFactory::custom(['fontSize' => 9]));

            $row++;
            $sheet->setCellValue('B' . $row, CotizacionConfig::COMPANY_FOOTER);
            $sheet->getStyle('B' . $row)->applyFromArray(ExcelStyleFactory::custom(['fontSize' => 9]));

        } catch (\Exception $e) {
            error_log('Error en llenarLineamientosExcel: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Llenar PDF de lineamientos
     */
    private function llenarLineamientosPdf(PdfReportService $service, Request $request): void
    {
        $service->agregarEncabezado();

        $html = $this->generarSeccionLineamientosPdf($service);
        $service->agregarHTML($html);

        $contacto = $service->getDatosContacto(Auth::user()?->name);
        $htmlFirma = '<div style="margin-top: 50px;">
            <p style="margin: 0;"><strong style="color: #' . CotizacionConfig::COLOR_ACCENT_RED . ';">Atentamente,</strong></p>
            <p style="margin: 20px 0 0 0; border-top: 1px solid #000; padding-top: 10px;">
                <strong>' . htmlspecialchars($contacto['nombre']) . '</strong><br>
                ' . htmlspecialchars($contacto['puesto']) . '
            </p>
        </div>';
        $service->agregarHTML($htmlFirma);

        $service->agregarFooter();
    }

    /**
     * Llenar Excel de costeo
     */
    private function llenarCosteoExcel(ExcelReportService $service, Request $request): void
    {
        try {
            $cotizacion = $service->getCotizacion();
            $contacto = $service->getDatosContacto(Auth::user()?->name);
            $sheet = $service->getSheet();

            // Configurar columnas - SIN MERGES
            $service->setColumnWidths([
                'A' => 50,
                'B' => 35,
                'C' => 15,
                'D' => 12,
                'E' => 12,
            ]);

            // ===== ENCABEZADO =====
            $service->agregarLogo('A1');
            
            // Aumentar altura de filas para que se vea el logo
            $sheet->getRowDimension(1)->setRowHeight(40);
            $sheet->getRowDimension(2)->setRowHeight(40);
            $sheet->getRowDimension(3)->setRowHeight(40);
            $sheet->getRowDimension(4)->setRowHeight(20);
            
            $sheet->setCellValue('D1', 'Folio:');
            $sheet->getStyle('D1')->applyFromArray(ExcelStyleFactory::custom(['bold' => true]));
            $sheet->setCellValue('E1', $cotizacion->no_proyecto);

            $sheet->setCellValue('D2', 'Fecha:');
            $sheet->getStyle('D2')->applyFromArray(ExcelStyleFactory::custom(['bold' => true]));
            $sheet->setCellValue('E2', $cotizacion->fecha);

            // ===== TÍTULO =====
            $row = 5;
            $sheet->setCellValue('A' . $row, 'DESGLOSE DE COSTOS');
            $sheet->getStyle('A' . $row)->applyFromArray(ExcelStyleFactory::custom([
                'bold' => true,
                'color' => 'B50B0B',
                'fontSize' => 14,
            ]));
            for ($col = 'B'; $col <= 'E'; $col++) {
                $sheet->getStyle($col . $row)->applyFromArray(ExcelStyleFactory::custom([
                    'bold' => true,
                    'color' => 'B50B0B',
                    'fontSize' => 14,
                ]));
            }
            $sheet->getRowDimension($row)->setRowHeight(25);

            // Obtener costeos
            $costeos = \App\Models\CosteoRequisicion::where('cotizaciones', $cotizacion->id)->get();

            if ($costeos->isEmpty()) {
                $row += 2;
                $sheet->setCellValue('A' . $row, 'No hay datos de costeo disponibles');
                return;
            }

            // ===== TABLA DE PROCESOS =====
            $row += 2;

            // Encabezados
            $sheet->setCellValue('A' . $row, '#');
            $sheet->setCellValue('B' . $row, 'Concepto');
            $sheet->setCellValue('C' . $row, 'Cantidad');
            $sheet->setCellValue('D' . $row, 'Unitario');
            $sheet->setCellValue('E' . $row, 'Total');

            for ($col = 'A'; $col <= 'E'; $col++) {
                $sheet->getStyle($col . $row)->applyFromArray(ExcelStyleFactory::headerStyle());
            }
            $sheet->getRowDimension($row)->setRowHeight(18);

            // Datos de procesos
            $row++;
            $itemNum = 1;
            $costoTotalGral = 0;

            foreach ($costeos as $costeo) {
                $procesos = $costeo->procesosCosteo;

                foreach ($procesos as $proceso) {
                    $costoTotal = $proceso->costo ?? 0;
                    $costoTotalGral += $costoTotal;

                    $sheet->setCellValue('A' . $row, $itemNum);
                    $sheet->setCellValue('B' . $row, htmlspecialchars($proceso->nombre ?? 'Proceso S/N'));
                    $sheet->setCellValue('C' . $row, htmlspecialchars($proceso->cantidad ?? ''));
                    $sheet->setCellValue('D' . $row, $proceso->costo_unitario ?? 0);
                    $sheet->setCellValue('E' . $row, $costoTotal);

                    // Aplicar estilos
                    $sheet->getStyle('A' . $row)->applyFromArray(ExcelStyleFactory::custom(['bold' => true]));
                    $sheet->getStyle('D' . $row)->applyFromArray(ExcelStyleFactory::greenPriceStyle());
                    $sheet->getStyle('E' . $row)->applyFromArray(ExcelStyleFactory::greenPriceStyle());

                    for ($col = 'A'; $col <= 'E'; $col++) {
                        $sheet->getStyle($col . $row)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    }

                    $sheet->getRowDimension($row)->setRowHeight(18);
                    $row++;
                    $itemNum++;
                }
            }

            // ===== TOTAL =====
            $row++;
            $sheet->setCellValue('D' . $row, 'COSTO TOTAL:');
            $sheet->getStyle('D' . $row)->applyFromArray(ExcelStyleFactory::custom([
                'bold' => true,
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT],
            ]));

            $sheet->setCellValue('E' . $row, $costoTotalGral);
            $sheet->getStyle('E' . $row)->applyFromArray(ExcelStyleFactory::custom([
                'bold' => true,
                'color' => 'FFFFFF',
                'bgColor' => 'B50B0B',
                'fontSize' => 12,
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT],
            ]));
            $sheet->getRowDimension($row)->setRowHeight(20);

            // ===== FIRMA =====
            $row += 3;
            $sheet->setCellValue('A' . $row, 'Atentamente');
            $sheet->getStyle('A' . $row)->applyFromArray(ExcelStyleFactory::custom([
                'bold' => true,
                'color' => 'B50B0B',
                'fontSize' => 12,
            ]));

            $row += 2;
            $sheet->setCellValue('A' . $row, htmlspecialchars($contacto['nombre']));
            $sheet->getStyle('A' . $row)->applyFromArray(ExcelStyleFactory::custom(['bold' => true, 'fontSize' => 11]));

            $row++;
            $sheet->setCellValue('A' . $row, htmlspecialchars($contacto['puesto']));

        } catch (\Exception $e) {
            error_log('Error en llenarCosteoExcel: ' . $e->getMessage());
            throw $e;
        }
    }

    
    private function llenarCostoePdf(PdfReportService $service, Request $request): void
    {
        // Implementar lógica de costeo aquí
        // Por ahora es un placeholder
    }


    private function agregarEncabezadoCotizacion(ExcelReportService $service, int $row): void
    {
        $headers = ['', 'Descripción del proyecto', '', '', '', '', 'Piezas (MOQ)', 'Precio Unitario (MXN)'];

        $service->setCellValue('A' . $row, $headers[0], ExcelStyleFactory::headerStyle());
        $service->setCellValue('B' . $row, $headers[1], ExcelStyleFactory::headerStyle());
        $service->mergeCells('B' . $row . ':F' . $row);
        $service->setCellValue('G' . $row, $headers[6], ExcelStyleFactory::headerStyle());
        $service->setCellValue('H' . $row, $headers[7], ExcelStyleFactory::headerStyle());

        $service->setRowHeight($row, CotizacionConfig::ROW_HEIGHT_HEADER);
    }

    /**
     * Agregar datos de cotización
     */
    private function agregarDatosCotizacion(ExcelReportService $service, $cotizacion, array $especificacion, int $row): void
    {
        $precioUnitario = $service->getPrecioUnitario();

        $service->setCellValue('A' . $row, '1', ExcelStyleFactory::lightGrayCellStyle());
        $service->mergeCells('A' . $row . ':A' . ($row + 2));

        $service->setCellValue('B' . $row, $cotizacion->nombre_del_proyecto, ExcelStyleFactory::titleRedStyle());
        $service->mergeCells('B' . $row . ':F' . $row);

        $service->setCellValue('G' . $row, $especificacion['lote_compra'] ?? 'N/C', ExcelStyleFactory::grayCellStyle());
        $service->mergeCells('G' . $row . ':G' . ($row + 2));

        $service->setCellValue('H' . $row, '$ ' . number_format($precioUnitario, 2), ExcelStyleFactory::greenPriceStyle());
        $service->mergeCells('H' . $row . ':H' . ($row + 2));

        $row++;

        $service->setCellValue('B' . $row, 'Dimensiones', ExcelStyleFactory::specHeaderStyle());
        $service->setCellValue('C' . $row, 'Frecuencia de compra', ExcelStyleFactory::specHeaderStyle());
        $service->setCellValue('D' . $row, 'Especificación del material', ExcelStyleFactory::specHeaderStyle());
        $service->setCellValue('E' . $row, 'Espesor', ExcelStyleFactory::specHeaderStyle());
        $service->setCellValue('F' . $row, 'Color', ExcelStyleFactory::specHeaderStyle());

        $row++;

        $service->setCellValue('B' . $row, $service->getDimensionesFormato(), ExcelStyleFactory::specDataStyle());
        $service->setCellValue('C' . $row, $especificacion['frecuencia'] ?? 'N/C', ExcelStyleFactory::specDataStyle());
        $service->setCellValue('D' . $row, $especificacion['material'] ?? 'N/C', ExcelStyleFactory::specDataStyle());
        $service->setCellValue('E' . $row, $especificacion['calibre'] ?? 'N/C', ExcelStyleFactory::specDataStyle());
        $service->setCellValue('F' . $row, $especificacion['color'] ?? 'N/C', ExcelStyleFactory::specDataStyle());
    }

    /**
     * Agregar tabla de herramentales en Excel
     */
    private function agregarTablaHerramentales(ExcelReportService $service, $cotizacion, int $row): void
    {
        $precioHerramentales = $service->getPrecioHerramentales();

        $service->setCellValue('A' . $row, '', ExcelStyleFactory::headerStyle());
        $service->setCellValue('B' . $row, 'Desarrollo de Herramentales.', ExcelStyleFactory::headerStyle());
        $service->mergeCells('B' . $row . ':F' . $row);
        $service->setCellValue('G' . $row, '', ExcelStyleFactory::headerStyle());
        $service->setCellValue('H' . $row, 'Precio Total (MXN)', ExcelStyleFactory::headerStyle());

        $row++;

        $service->setCellValue('A' . $row, '2', ExcelStyleFactory::lightGrayCellStyle());
        $service->mergeCells('A' . $row . ':A' . ($row + 1));

        $service->setCellValue('B' . $row, 'Desarrollo de Herramentales', ExcelStyleFactory::titleRedStyle());
        $service->mergeCells('B' . $row . ':F' . $row);

        $service->setCellValue('G' . $row, '0', ExcelStyleFactory::grayCellStyle());
        $service->mergeCells('G' . $row . ':G' . ($row + 1));

        $service->setCellValue('H' . $row, '$ ' . number_format($precioHerramentales, 2), ExcelStyleFactory::greenPriceStyle());
        $service->mergeCells('H' . $row . ':H' . ($row + 1));

        $row++;

        $service->setCellValue('B' . $row, 'Se considera entrega de 3 muestras para liberación', ExcelStyleFactory::lightGrayCellStyle());
        $service->mergeCells('B' . $row . ':F' . $row);
        $service->setRowHeight($row, CotizacionConfig::ROW_HEIGHT_TEXT);
    }

    private function generarTablaProductoPdf(PdfReportService $service): string
    {
        $cotizacion = $service->getCotizacion();
        $especificacion = $service->getEspecificacionProyecto();
        $precioUnitario = $service->getPrecioUnitario();

        return '
        <table>
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
                    <td rowspan="3" class="gray-cell bold">' . htmlspecialchars($especificacion['lote_compra'] ?? 'N/C') . '</td>
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
                    <td class="spec-data">' . htmlspecialchars($service->getDimensionesFormato()) . '</td>
                    <td class="spec-data">' . htmlspecialchars($especificacion['frecuencia'] ?? 'N/C') . '</td>
                    <td class="spec-data">' . htmlspecialchars($especificacion['material'] ?? 'N/C') . '</td>
                    <td class="spec-data">' . htmlspecialchars($especificacion['calibre'] ?? 'N/C') . '</td>
                    <td class="spec-data">' . htmlspecialchars($especificacion['color'] ?? 'N/C') . '</td>
                </tr>
            </tbody>
        </table>';
    }

    /**
     * Generar tabla de herramentales en PDF
     */
    private function generarTablaHerramentalesPdf(PdfReportService $service): string
    {
        $precioHerramentales = $service->getPrecioHerramentales();

        return '
        <table>
            <thead>
                <tr>
                    <th class="header-style" style="width: 5%;"></th>
                    <th class="header-style" style="width: 50%;" colspan="5">Desarrollo de Herramentales.</th>
                    <th class="header-style" style="width: 20%;"></th>
                    <th class="header-style" style="width: 25%;">Precio Total (MXN)</th>
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
    }

    /**
     * Generar sección de lineamientos en PDF
     */
    private function generarSeccionLineamientosPdf(PdfReportService $service): string
    {
        $lineamientos = $service->getLineamientos();
        $html = '<div class="title-red" style="font-size: 16px; margin-bottom: 15px;">Lineamientos del Proyecto</div>';
        $html .= '<ol style="line-height: 1.8; font-size: 11px;">';

        foreach ($lineamientos as $item) {
            $html .= '<li>' . htmlspecialchars($item) . '</li>';
        }

        $html .= '</ol>';

        return $html;
    }
}
