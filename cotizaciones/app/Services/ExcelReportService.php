<?php

namespace App\Services;

use App\Support\CotizacionConfig;
use App\Support\ExcelStyleFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class ExcelReportService extends CotizacionReportService
{
    protected $spreadsheet;
    protected $sheet;
    protected $currentRow;

    public function __construct($cotizacion)
    {
        parent::__construct($cotizacion);
        $this->spreadsheet = new Spreadsheet();
        $this->sheet = $this->spreadsheet->getActiveSheet();
        $this->currentRow = 1;
        
        $this->configurarHoja();
    }

    /**
     * Configurar propiedades iniciales de la hoja
     * 
     * @return void
     */
    protected function configurarHoja(): void
    {
        $this->sheet->getDefaultColumnDimension()->setWidth(CotizacionConfig::COLUMN_WIDTH_MEDIUM);
        $this->sheet->getDefaultRowDimension()->setRowHeight(CotizacionConfig::ROW_HEIGHT_DEFAULT);
    }

    /**
     * Agregar logo de la empresa
     * 
     * @param string $coordinate
     * @return void
     */
    public function agregarLogo(string $coordinate = 'A1'): void
    {
        $logoPath = public_path(CotizacionConfig::LOGO_PATH);
        
        if (!file_exists($logoPath)) {
            return;
        }

        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo de INNOVET');
        $drawing->setPath($logoPath);
        $drawing->setHeight(CotizacionConfig::LOGO_HEIGHT);
        $drawing->setCoordinates($coordinate);
        $drawing->setWorksheet($this->sheet);
    }

    /**
     * Agregar imagen ilustrativa
     * 
     * @param string $coordinate
     * @param int $height
     * @return void
     */
    public function agregarImagenIlustrativa(string $coordinate = 'A1', int $height = 200): void
    {
        $imagenPath = $this->getImagenPath();
        
        if (!$imagenPath) {
            return;
        }

        $drawing = new Drawing();
        $drawing->setName('Imagen Ilustrativa');
        $drawing->setDescription('Imagen del proyecto');
        $drawing->setPath($imagenPath);
        $drawing->setHeight($height);
        $drawing->setCoordinates($coordinate);
        $drawing->setOffsetX(50);
        $drawing->setOffsetY(10);
        $drawing->setWorksheet($this->sheet);
    }

    /**
     * Establecer ancho de columnas
     * 
     * @param array $widths ['A' => 15, 'B' => 20, ...]
     * @return void
     */
    public function setColumnWidths(array $widths): void
    {
        foreach ($widths as $column => $width) {
            $this->sheet->getColumnDimension($column)->setWidth($width);
        }
    }

    /**
     * Establecer altura de fila
     * 
     * @param int $row
     * @param float $height
     * @return void
     */
    public function setRowHeight(int $row, float $height): void
    {
        $this->sheet->getRowDimension($row)->setRowHeight($height);
    }

    /**
     * Escribir celda con valor
     * 
     * @param string $cell
     * @param mixed $value
     * @param array|null $style
     * @return void
     */
    public function setCellValue(string $cell, $value, ?array $style = null): void
    {
        $this->sheet->setCellValue($cell, $value);
        
        if ($style) {
            $this->sheet->getStyle($cell)->applyFromArray($style);
        }
    }

    /**
     * Fusion de celdas
     * 
     * @param string $rangeCells
     * @return void
     */
    public function mergeCells(string $rangeCells): void
    {
        $this->sheet->mergeCells($rangeCells);
    }

    /**
     * Agregar encabezado de tabla
     * 
     * @param array $headers
     * @param int $row
     * @param string|null $startColumn
     * @return void
     */
    public function agregarEncabezado(array $headers, ?int $row = null, ?string $startColumn = null): void
    {
        $row = $row ?? $this->currentRow;
        $column = $startColumn ?? 'A';
        
        foreach ($headers as $header) {
            $this->setCellValue($column . $row, $header, ExcelStyleFactory::headerStyle());
            $column++;
        }
        
        $this->currentRow = $row + 1;
    }

    /**
     * Obtener la hoja activa
     * 
     * @return mixed
     */
    public function getSheet()
    {
        return $this->sheet;
    }

    /**
     * Establecer la hoja activa
     * 
     * @param mixed $sheet
     * @return void
     */
    public function setSheet($sheet): void
    {
        $this->sheet = $sheet;
    }

    /**
     * Obtener el spreadsheet
     * 
     * @return Spreadsheet
     */
    public function getSpreadsheet(): Spreadsheet
    {
        return $this->spreadsheet;
    }

    /**
     * Obtener fila actual
     * 
     * @return int
     */
    public function getCurrentRow(): int
    {
        return $this->currentRow;
    }

    /**
     * Incrementar fila actual
     * 
     * @param int $increment
     * @return void
     */
    public function incrementRow(int $increment = 1): void
    {
        $this->currentRow += $increment;
    }

    /**
     * Establecer fila actual
     * 
     * @param int $row
     * @return void
     */
    public function setCurrentRow(int $row): void
    {
        $this->currentRow = $row;
    }

    /**
     * Descargar archivo Excel
     * 
     * @param string $fileName
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function download(string $fileName)
    {
        $writer = new Xlsx($this->spreadsheet);
        $temp = tempnam(sys_get_temp_dir(), 'excel');
        $writer->save($temp);

        return response()->download($temp, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"'
        ])->deleteFileAfterSend(true);
    }
}
