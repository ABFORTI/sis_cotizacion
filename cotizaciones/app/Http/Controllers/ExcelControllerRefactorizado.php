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

/**
 * Controlador para la generación de reportes en Excel y PDF
 * 
 * Este controlador gestiona la generación de diferentes tipos de 
 * reportes (cotizaciones, lineamientos, costeos) en formatos Excel y PDF.
 * 
 * Métodos públicos:
 * - generarCotizacionExcel(): Genera Excel con cotización
 * - generarCotizacionPdf(): Genera PDF con cotización
 * - generarLineamientosExcel(): Genera Excel con lineamientos
 * - generarLineamientosPdf(): Genera PDF con lineamientos
 * - generarCosteoResumenExcel(): Genera Excel con resumen de costeo
 * - generarCosteoResumenPdf(): Genera PDF con resumen de costeo
 * - generarCotizacionLineamientosExcel(): Genera Excel combinado
 * - generarCotizacionLineamientosPdf(): Genera PDF combinado
 */
class ExcelController extends Controller
{
    /**
     * Generar cotización en formato Excel
     */
    public function generarCotizacionExcel($id)
    {
        $cotizacion = Cotizacion::with([
            'especificacionProyecto',
            'costeoRequisicion',
            'archivosAdjuntos',
            'ventasResumen'
        ])->findOrFail($id);

        $service = new ExcelReportService($cotizacion);
        $this->llenarCotizacionExcel($service);

        $fileName = 'Cotizacion_' . $cotizacion->no_proyecto . '.xlsx';
        $service->download($fileName);
    }

    /**
     * Generar cotización en formato PDF
     */
    public function generarCotizacionPdf($id)
    {
        $cotizacion = Cotizacion::with([
            'especificacionProyecto',
            'costeoRequisicion',
            'archivosAdjuntos',
            'ventasResumen'
        ])->findOrFail($id);

        $service = new PdfReportService($cotizacion);
        $this->llenarCotizacionPdf($service);

        $fileName = 'Cotizacion_' . $cotizacion->no_proyecto . '.pdf';
        $service->download($fileName);
    }

    /**
     * Generar lineamientos en formato Excel
     */
    public function generarLineamientosExcel(Request $request, $id)
    {
        $cotizacion = Cotizacion::with([
            'especificacionProyecto',
            'costeoRequisicion'
        ])->findOrFail($id);

        $service = new ExcelReportService($cotizacion);
        $this->llenarLineamientosExcel($service, $request);

        $fileName = 'Lineamientos_' . $cotizacion->no_proyecto . '.xlsx';
        $service->download($fileName);
    }

    /**
     * Generar lineamientos en formato PDF
     */
    public function generarLineamientosPdf(Request $request, $id)
    {
        $cotizacion = Cotizacion::with([
            'especificacionProyecto',
            'costeoRequisicion'
        ])->findOrFail($id);

        $service = new PdfReportService($cotizacion);
        $this->llenarLineamientosPdf($service, $request);

        $fileName = 'Lineamientos_' . $cotizacion->no_proyecto . '.pdf';
        $service->download($fileName);
    }

    /**
     * Generar resumen de costeo en formato Excel
     */
    public function generarCosteoResumenExcel(Request $request, $id)
    {
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
        $service->download($fileName);
    }

    /**
     * Generar resumen de costeo en formato PDF
     */
    public function generarCosteoResumenPdf(Request $request, $id)
    {
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
        $service->download($fileName);
    }

    /**
     * Generar Excel combinado: Cotización + Lineamientos
     */
    public function generarCotizacionLineamientosExcel(Request $request, $id)
    {
        $cotizacion = Cotizacion::with([
            'especificacionProyecto',
            'costeoRequisicion',
            'archivosAdjuntos',
            'ventasResumen'
        ])->findOrFail($id);

        $spreadsheet = new Spreadsheet();
        
        // Hoja 1: Cotización
        $sheet1 = $spreadsheet->getActiveSheet();
        $sheet1->setTitle('Cotización');
        
        // Hoja 2: Lineamientos
        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle('Lineamientos');
        
        $writer = new Xlsx($spreadsheet);
        $fileName = 'Cotizacion_Completa_' . $cotizacion->no_proyecto . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        $writer->save('php://output');
        exit;
    }

    /**
     * Generar PDF combinado: Cotización + Lineamientos
     */
    public function generarCotizacionLineamientosPdf(Request $request, $id)
    {
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
        $service->download($fileName);
    }

    private function llenarCotizacionExcel(ExcelReportService $service): void
    {
        $sheet = $service->getSheet();
        $cotizacion = $service->getCotizacion();
        $especificacion = $service->getEspecificacionProyecto();

        // Configurar columnas
        $service->setColumnWidths([
            'A' => CotizacionConfig::COLUMN_WIDTH_NARROW,
            'B' => CotizacionConfig::COLUMN_WIDTH_EXTRA_WIDE,
            'C' => CotizacionConfig::COLUMN_WIDTH_MEDIUM,
            'D' => CotizacionConfig::COLUMN_WIDTH_MEDIUM,
            'E' => CotizacionConfig::COLUMN_WIDTH_MEDIUM,
            'F' => CotizacionConfig::COLUMN_WIDTH_MEDIUM,
            'G' => CotizacionConfig::COLUMN_WIDTH_MEDIUM,
            'H' => CotizacionConfig::COLUMN_WIDTH_WIDE,
        ]);

        // Agregar logo
        $service->agregarLogo('A1');

        // Información folio y fecha
        $service->setCellValue('C1', 'Folio:');
        $service->setCellValue('D1', $cotizacion->no_proyecto);
        $service->setCellValue('C2', 'Fecha:');
        $service->setCellValue('D2', $cotizacion->fecha);

        // Información del cliente
        $row = 4;
        $service->setCellValue('A' . $row, $cotizacion->cliente, ExcelStyleFactory::custom([
            'fontSize' => CotizacionConfig::FONT_SIZE_TITLE,
            'bold' => true,
        ]));
        $service->mergeCells('A' . $row . ':H' . $row);

        $row++;
        $service->setCellValue('A' . $row, $cotizacion->puesto, ExcelStyleFactory::centeredStyle());
        $service->mergeCells('A' . $row . ':H' . $row);

        $row++;
        $service->setCellValue('A' . $row, $cotizacion->correo, ExcelStyleFactory::custom([
            'color' => CotizacionConfig::COLOR_ACCENT_RED,
        ]));
        $service->mergeCells('A' . $row . ':F' . $row);

        $service->setCellValue('G' . $row, 'Tel.');
        $service->setCellValue('H' . $row, $cotizacion->telefono, ExcelStyleFactory::custom([
            'bold' => true,
            'color' => CotizacionConfig::COLOR_ACCENT_RED,
        ]));

        // Tabla de cotización - Encabezado
        $row = 8;
        $this->agregarEncabezadoCotizacion($service, $row);
        $row++;

        // Tabla de cotización - Datos
        $this->agregarDatosCotizacion($service, $cotizacion, $especificacion, $row);
        $row += 3;

        // Tabla de herramentales
        $this->agregarTablaHerramentales($service, $cotizacion, $row);
        $row += 3;

        // Imagen ilustrativa
        if ($service->getImagenPath()) {
            $service->agregarImagenIlustrativa('A' . $row, CotizacionConfig::IMAGE_HEIGHT);
            $row += 11;
        }

        // Footer
        $service->setCellValue('A' . $row, CotizacionConfig::COMPANY_ADDRESS);
        $service->mergeCells('A' . $row . ':H' . $row);
        $row++;

        $service->setCellValue('A' . $row, CotizacionConfig::COMPANY_FOOTER);
        $service->mergeCells('A' . $row . ':H' . $row);
    }

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

    private function llenarLineamientosExcel(ExcelReportService $service, Request $request): void
    {
        $sheet = $service->getSheet();
        $cotizacion = $service->getCotizacion();
        $lineamientos = $service->getLineamientos();
        $contacto = $service->getDatosContacto(Auth::user()?->name);

        // Configurar columnas
        $service->setColumnWidths([
            'A' => 120,
            'B' => 15,
            'C' => 15,
            'D' => 15,
            'E' => 15,
            'F' => 15,
            'G' => 15,
        ]);

        $service->agregarLogo('A1');

        $service->setCellValue('F1', 'Folio:');
        $service->setCellValue('G1', $cotizacion->no_proyecto);
        $service->setCellValue('F2', 'Fecha:');
        $service->setCellValue('G2', $cotizacion->fecha);

        // Título
        $row = 4;
        $service->setCellValue('A' . $row, 'Lineamientos del Proyecto', ExcelStyleFactory::custom([
            'bold' => true,
            'color' => CotizacionConfig::COLOR_ACCENT_RED,
            'fontSize' => 16,
        ]));
        $service->mergeCells('A' . $row . ':G' . $row);

        // Lineamientos
        $row += 2;
        foreach ($lineamientos as $lineamiento) {
            $service->setCellValue('A' . $row, $lineamiento, ExcelStyleFactory::normalStyle());
            $service->mergeCells('A' . $row . ':G' . $row);
            $service->setRowHeight($row, 40);
            $row++;
        }

        // Atentamente
        $row += 2;
        $service->setCellValue('A' . $row, 'Atentamente', ExcelStyleFactory::custom([
            'bold' => true,
            'color' => CotizacionConfig::COLOR_ACCENT_RED,
            'fontSize' => 14,
        ]));
        $service->mergeCells('A' . $row . ':G' . $row);

        // Datos de contacto
        $row += 2;
        $service->setCellValue('A' . $row, $contacto['nombre'], ExcelStyleFactory::normalStyle());
        $service->mergeCells('A' . $row . ':G' . $row);

        $row++;
        $service->setCellValue('A' . $row, $contacto['puesto'], ExcelStyleFactory::normalStyle());
        $service->mergeCells('A' . $row . ':G' . $row);

        // Footer
        $row += 3;
        $service->setCellValue('A' . $row, CotizacionConfig::COMPANY_ADDRESS);
        $service->getSheet()->getStyle('A' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $service->mergeCells('A' . $row . ':G' . $row);

        $row++;
        $service->setCellValue('A' . $row, CotizacionConfig::COMPANY_FOOTER);
        $service->getSheet()->getStyle('A' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $service->mergeCells('A' . $row . ':G' . $row);
    }

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

    private function llenarCosteoExcel(ExcelReportService $service, Request $request): void
    {
        // Implementar lógica de costeo aquí
        // Por ahora es un placeholder
    }

    /**
     * Llenar PDF de costeo (stub para implementación futura)
     */
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
        $service->setCellValue('H' . $row, 'Precio Unitario (MXN)', ExcelStyleFactory::headerStyle());

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

    /**
     * Generar tabla de producto en PDF
     */
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
