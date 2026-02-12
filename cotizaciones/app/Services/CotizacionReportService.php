<?php

namespace App\Services;

use App\Models\Cotizacion;
use Illuminate\Support\Facades\Storage;


class CotizacionReportService
{
    protected $cotizacion;

    public function __construct(Cotizacion $cotizacion)
    {
        $this->cotizacion = $cotizacion;
    }

    /**
     * Obtener precio unitario de la cotización
     * 
     * @return float
     */
    public function getPrecioUnitario(): float
    {
        $ventasResumen = $this->cotizacion->ventasResumen;
        
        if ($ventasResumen && $ventasResumen->resumen_total_costo_unit) {
            return (float) $ventasResumen->resumen_total_costo_unit;
        }

        if ($this->cotizacion->costeoRequisicion && $this->cotizacion->costeoRequisicion->resumen_total_costo_unit) {
            return (float) $this->cotizacion->costeoRequisicion->resumen_total_costo_unit;
        }

        return 0;
    }

    /**
     * Obtener precio de herramentales
     * 
     * @return float
     */
    public function getPrecioHerramentales(): float
    {
        $ventasResumen = $this->cotizacion->ventasResumen;
        
        if ($ventasResumen && $ventasResumen->resumen_total_precio_venta) {
            return (float) $ventasResumen->resumen_total_precio_venta;
        }

        if ($this->cotizacion->costeoRequisicion && $this->cotizacion->costeoRequisicion->TOTAL_VENTAS) {
            return (float) $this->cotizacion->costeoRequisicion->TOTAL_VENTAS;
        }

        return 0;
    }

    /**
     * Obtener ruta de la imagen ilustrativa
     * 
     * @return string|null
     */
    public function getImagenPath(): ?string
    {
        if (!$this->cotizacion->archivosAdjuntos || $this->cotizacion->archivosAdjuntos->isEmpty()) {
            return null;
        }

        foreach ($this->cotizacion->archivosAdjuntos as $archivo) {
            $extension = strtolower(pathinfo($archivo->path, PATHINFO_EXTENSION));
            
            if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'bmp'])) {
                $fullPath = storage_path('app/public/' . $archivo->path);
                
                if (file_exists($fullPath)) {
                    return $fullPath;
                }
            }
        }

        return null;
    }

    /**
     * Obtener contenido en base64 de un archivo imagen
     * 
     * @param string $filePath
     * @return string
     */
    public function getImagenBase64(string $filePath): string
    {
        if (!file_exists($filePath)) {
            return '';
        }

        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $mimeType = match($extension) {
            'jpg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            default => 'image/png',
        };

        $imageData = file_get_contents($filePath);
        return 'data:' . $mimeType . ';base64,' . base64_encode($imageData);
    }

    /**
     * Obtener logo de la empresa en base64
     * 
     * @return string
     */
    public function getLogoBase64(): string
    {
        $logoPath = public_path('images/innovet-logo.png');
        
        if (!file_exists($logoPath)) {
            return '';
        }

        return $this->getImagenBase64($logoPath);
    }

    /**
     * Obtener datos de especificación del proyecto
     * 
     * @return array
     */
    public function getEspecificacionProyecto(): array
    {
        $spec = $this->cotizacion->especificacionProyecto;
        
        if (!$spec) {
            return [];
        }

        return [
            'largo' => $spec->pieza_largo ?? 0,
            'ancho' => $spec->pieza_ancho ?? 0,
            'alto' => $spec->pieza_alto ?? 0,
            'material' => $spec->material ?? 'N/C',
            'color' => $spec->color ?? 'N/C',
            'calibre' => $spec->calibre ?? 'N/C',
            'frecuencia' => $spec->frecuencia_compra ?? 'N/C',
            'lote_compra' => $spec->lote_compra ?? 'N/C',
        ];
    }

    /**
     * Obtener dimensiones formateadas
     * 
     * @return string
     */
    public function getDimensionesFormato(): string
    {
        $spec = $this->getEspecificacionProyecto();
        
        if (empty($spec)) {
            return 'N/C';
        }

        return "{$spec['largo']} x {$spec['ancho']} x {$spec['alto']} mm";
    }

    /**
     * Obtener tiempo de entrega calculado
     * 
     * @return int
     */
    public function getTiempoEntrega(): int
    {
        $tiempoBase = $this->cotizacion->costeoRequisicion->tiempo_pt ?? 0;
        
        if (!is_numeric($tiempoBase)) {
            return 0;
        }

        return ceil($tiempoBase / 5);
    }

    /**
     * Obtener lineamientos del proyecto
     * 
     * @return array
     */
    public function getLineamientos(): array
    {
        $tiempoEntrega = $this->getTiempoEntrega();
        $lugarEntrega = $this->cotizacion->lugar_entrega ?? '0';
        
        return [
            $this->cotizacion->lineamiento_1 ?? 'Precios en USD. No incluyen I.V.A. Se considera fabricación, facturación y entrega en una sola exhibición.',
            $this->cotizacion->lineamiento_2 ?? 'Los precios pueden ajustarse en respuesta a cambios en aranceles, impuestos o restricciones fiscales y comerciales establecidos por la autoridad.',
            $this->cotizacion->lineamiento_3 ?? 'La vigencia de la presente cotización es de 12 meses y/o incrementos en MP superior al 5%.',
            $this->cotizacion->lineamiento_4 ?? 'Condiciones de pago son por anticipado.',
            'Tiempo de desarrollo de herramentales y muestras para liberación (' . ($this->cotizacion->tiempo_herramentales ?? '') . ') semanas.',
            $this->cotizacion->lineamiento_5 ?? 'Tiempo de entrega de producto terminado: ' . $tiempoEntrega . ' semanas (todos los tiempos se confirman con disponibilidad de maquinaria).',
            $this->cotizacion->lineamiento_6 ?? 'El producto se entrega en: ' . $lugarEntrega,
            $this->cotizacion->lineamiento_7 ?? 'Considerar una variación ±10% en la entrega de producto terminado, sobre lote de producción (MOQ cotizado).',
            $this->cotizacion->lineamiento_8 ?? 'Especificación de empaque: se confirma después de la 1ª. producción.',
            $this->cotizacion->lineamiento_9 ?? 'Cualquier condición distinta al escenario cotizado implica una revisión de costos.',
            $this->cotizacion->lineamiento_10 ?? 'La responsabilidad respecto de la mercancía producida por INNOVET, es única y exclusivamente por defectos de fabricación. La inspección de la pieza deformada o fuera de calor, causa deformaciones e invalida garantías. Es responsabilidad del CLIENTE aquellos desperfectos que sufra el producto por mal uso, transportación, almacenamiento o análogas derivadas de la actividad del CLIENTE.'
        ];
    }

    /**
     * Obtener datos de contacto
     * 
     * @param string|null $defaultName
     * @return array
     */
    public function getDatosContacto(?string $defaultName = null): array
    {
        return [
            'nombre' => $this->cotizacion->nombre_contacto ?? $defaultName ?? 'Nombre',
            'puesto' => $this->cotizacion->puesto_contacto ?? 'Puesto',
        ];
    }

    /**
     * Obtener el modelo de cotización
     * 
     * @return Cotizacion
     */
    public function getCotizacion(): Cotizacion
    {
        return $this->cotizacion;
    }
}
