<?php

namespace App\Services;

use App\Support\CotizacionConfig;
use Dompdf\Dompdf;
use Dompdf\Options;


class PdfReportService extends CotizacionReportService
{
    protected $html;
    protected $css;

    public function __construct($cotizacion)
    {
        parent::__construct($cotizacion);
        $this->html = '';
        $this->css = $this->obtenerCssBase();
    }

    /**
     * Obtener CSS base para el PDF
     * 
     * @return string
     */
    protected function obtenerCssBase(): string
    {
        return '
        <style>
            * {
                margin: 0.5cm;
                padding: 0;
                box-sizing: border-box ;
            }
            
            body {
                font-family: ' . CotizacionConfig::FONT_FAMILY . ';
                font-size: ' . CotizacionConfig::FONT_SIZE_NORMAL . 'px;
                line-height: 1.5;
                color: #333;
                padding: 0;
            }
            
            table {
                width: 100%;
                border-collapse: collapse;
                margin: 10px 0;
                page-break-inside: avoid;
            }
            
            th {
                background-color: #' . CotizacionConfig::COLOR_PRIMARY_DARK . ';
                color: #' . CotizacionConfig::COLOR_WHITE . ';
                padding: 10px;
                text-align: center;
                vertical-align: middle;
                font-weight: bold;
                border: 1px solid #000;
            }
            
            td {
                padding: 8px;
                border: 1px solid #ddd;
                text-align: left;
                vertical-align: middle;
            }
            
            .header-style {
                background-color: #' . CotizacionConfig::COLOR_PRIMARY_DARK . ';
                color: #' . CotizacionConfig::COLOR_WHITE . ';
                font-weight: bold;
                text-align: center;
                font-size: ' . CotizacionConfig::FONT_SIZE_HEADER . 'px;
            }
            
            .gray-cell {
                background-color: #' . CotizacionConfig::COLOR_GRAY . ';
                font-weight: bold;
                text-align: center;
            }
            
            .light-gray-cell {
                background-color: #' . CotizacionConfig::COLOR_LIGHT_GRAY . ';
                text-align: center;
            }
            
            .green-price {
                background-color: #' . CotizacionConfig::COLOR_GREEN_PRICE . ';
                color: #' . CotizacionConfig::COLOR_WHITE . ';
                font-weight: bold;
                text-align: center;
                font-size: ' . (CotizacionConfig::FONT_SIZE_HEADER) . 'px;
            }
            
            .spec-header {
                background-color: #' . CotizacionConfig::COLOR_LIGHT_GRAY . ';
                font-weight: bold;
                text-align: center;
                font-size: 11px;
            }
            
            .spec-data {
                background-color: #' . CotizacionConfig::COLOR_GRAY . ';
                text-align: center;
                font-size: 11px;
            }
            
            .title-red {
                color: #' . CotizacionConfig::COLOR_ACCENT_RED . ';
                font-weight: bold;
                text-align: center;
            }
            
            .text-red {
                color: #' . CotizacionConfig::COLOR_ACCENT_RED . ';
            }
            
            .text-red-bold {
                color: #' . CotizacionConfig::COLOR_ACCENT_RED . ';
                font-weight: bold;
            }
            
            .align-center {
                text-align: center;
            }
            
            .align-left {
                text-align: left;
            }
            
            .bold {
                font-weight: bold;
            }
            
            .footer-text {
                font-size: ' . CotizacionConfig::FONT_SIZE_SMALL . 'px;
                text-align: center;
                padding: 2px 0;
            }
            
            .page-break {
                page-break-after: always;
            }
            
            .no-border {
                border: none;
            }
            
            .image-container {
                text-align: center;
                margin: 20px 0;
            }
            
            .image-container img {
                max-width: 100%;
                max-height: 300px;
                border: 1px solid #ddd;
                padding: 10px;
            }
            
            ul, ol {
                margin-left: 20px;
                margin-bottom: 5px;
            }
            
            li {
                margin-bottom: 8px;
                line-height: 1.6;
            }
        </style>
        ';
    }

    /**
     * Agregar HTML contenido
     * 
     * @param string $html
     * @return void
     */
    public function agregarHTML(string $html): void
    {
        $this->html .= $html;
    }

    /**
     * Agregar encabezado con logo y folio
     * 
     * @param bool $mostrarLogo
     * @return void
     */
    public function agregarEncabezado(bool $mostrarLogo = true): void
    {
        $logoHtml = '';
        if ($mostrarLogo) {
            $logoBase64 = $this->getLogoBase64();
            $logoHtml = $logoBase64 ? '<img src="' . $logoBase64 . '" style="height: 60px;">' : 'INNOVET';
        }

        $html = '
        <table class="no-border" style="width: 100%; margin-bottom: 15px;">
            <tr>
                <td class="no-border" style="width: 70%; vertical-align: top;">' . $logoHtml . '</td>
                <td class="no-border" style="width: 30%; vertical-align: top;">
                    <table class="no-border">
                        <tr>
                            <td class="no-border bold">Folio:</td>
                            <td class="no-border">' . htmlspecialchars($this->cotizacion->no_proyecto) . '</td>
                        </tr>
                        <tr>
                            <td class="no-border bold">Fecha:</td>
                            <td class="no-border">' . htmlspecialchars($this->cotizacion->fecha) . '</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>';

        $this->agregarHTML($html);
    }

    /**
     * Agregar información del cliente
     * 
     * @return void
     */
    public function agregarInfoCliente(): void
    {
        $html = '
        <div style="text-align: center; margin-bottom: 15px;">
            <div style="font-size: ' . CotizacionConfig::FONT_SIZE_TITLE . 'px; font-weight: bold;">' 
                . htmlspecialchars($this->cotizacion->cliente) . 
            '</div>
            <div>' . htmlspecialchars($this->cotizacion->puesto) . '</div>
            <span class="text-red">' . htmlspecialchars($this->cotizacion->correo) . '</span>
            <span style="margin-left: 20px;">Tel. <span class="text-red-bold">' . htmlspecialchars($this->cotizacion->telefono) . '</span></span>
        </div>';

        $this->agregarHTML($html);
    }

    /**
     * Agregar imagen ilustrativa
     * 
     * @return void
     */
    public function agregarImagenIlustrativa(): void
    {
        $imagenPath = $this->getImagenPath();
        
        if (!$imagenPath) {
            $html = '<p class="title-red" style="text-align: center; padding: 50px 0;">Imagen ilustrativa: No disponible</p>';
        } else {
            $imagenBase64 = $this->getImagenBase64($imagenPath);
            $html = '<div class="image-container">
                <img src="' . $imagenBase64 . '" alt="Imagen ilustrativa">
                <p class="title-red" style="margin-top: 10px;">Imagen ilustrativa:</p>
            </div>';
        }

        $this->agregarHTML($html);
    }

    /**
     * Agregar pie de página
     * 
     * @return void
     */
    public function agregarFooter(): void
    {
        $html = '
        <div style="margin-top: 30px; border-top: 1px solid #000; padding-top: 10px;">
            <p class="footer-text">' . CotizacionConfig::COMPANY_ADDRESS . '</p>
            <p class="footer-text">' . CotizacionConfig::COMPANY_FOOTER . '</p>
        </div>';

        $this->agregarHTML($html);
    }

    /**
     * Generar HTML final
     * 
     * @return string
     */
    public function generarHtml(): string
    {
        // Separar el encabezado (tabla no-border) del resto del contenido
        $htmlConEncabezado = $this->html;
        
        // Buscar el final de la tabla del encabezado (que tiene class no-border y contiene Folio y Fecha)
        $partes = explode('</table>', $htmlConEncabezado, 2);
        
        if (count($partes) === 2) {
            $encabezado = $partes[0] . '</table>';
            $contenido = $partes[1];
        } else {
            $encabezado = '';
            $contenido = $htmlConEncabezado;
        }
        
        // Construir HTML final con márgenes en el contenido
        $html = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>';
        $html .= $this->css;
        $html .= '</head><body>';
        $html .= $encabezado;
        $html .= '<div class="content-wrapper">' . $contenido . '</div>';
        $html .= '</body></html>';

        return $html;
    }

    /**
     * Generar y descargar PDF
     * 
     * @param string $fileName
     * @return void
     */
    public function download(string $fileName)
    {
        $html = $this->generarHtml();

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $pdfContent = $dompdf->output();

        return response()->make($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"'
        ]);
    }
}
