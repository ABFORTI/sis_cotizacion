<?php

namespace App\Support;

/**
 * Configuración centralizada para cotizaciones
 * 
 * Contiene constantes de colores, estilos y configuración general
 * para la generación de Excel y PDF
 */
class CotizacionConfig
{
    // Colores
    const COLOR_PRIMARY_DARK = '2B2B2B';
    const COLOR_ACCENT_RED = 'B50B0B';
    const COLOR_GRAY = 'BFBFBF';
    const COLOR_LIGHT_GRAY = 'D9D9D9';
    const COLOR_GREEN_PRICE = '92D050';
    const COLOR_WHITE = 'FFFFFF';
    const COLOR_BLACK = '000000';

    // Fuentes
    const FONT_SIZE_TITLE = 24;
    const FONT_SIZE_HEADER = 14;
    const FONT_SIZE_NORMAL = 12;
    const FONT_SIZE_SMALL = 10;
    const FONT_FAMILY = 'sans-serif';

    // Dimensiones
    const LOGO_HEIGHT = 100;
    const IMAGE_HEIGHT = 200;
    const ROW_HEIGHT_DEFAULT = 25;
    const ROW_HEIGHT_HEADER = 30;
    const ROW_HEIGHT_TEXT = 40;

    // Columnas width en Excel
    const COLUMN_WIDTH_NARROW = 8;
    const COLUMN_WIDTH_MEDIUM = 15;
    const COLUMN_WIDTH_WIDE = 20;

    // Información de empresa
    const COMPANY_NAME = 'INNOVET';
    const COMPANY_ADDRESS = 'Av Del Marqués lote 7. Parque industrial Bernardo Quintana. El Marqués, Querétaro, C.P 76246';
    const COMPANY_FOOTER = 'ACF10 | Fecha de efectividad: 01-septiembre-2025 | Revisión: 03';
    const LOGO_PATH = 'images/innovet-logo.png';

    // Rutas de almacenamiento
    const IMAGE_STORAGE_PATH = 'app/public/';

    // Información de documentos
    const DOCUMENT_TYPE_COTIZACION = 'Cotizacion';
    const DOCUMENT_TYPE_LINEAMIENTOS = 'Lineamientos';
    const DOCUMENT_TYPE_COSTEO = 'Costeo';

    /**
     * Obtener colores como arreglo RGB
     * 
     * @param string $colorHex
     * @return array
     */
    public static function colorToRgb(string $colorHex): array
    {
        return ['rgb' => $colorHex];
    }

    /**
     * Obtener los colores principales como constantes
     * 
     * @return array
     */
    public static function getColorPalette(): array
    {
        return [
            'primary_dark' => self::COLOR_PRIMARY_DARK,
            'accent_red' => self::COLOR_ACCENT_RED,
            'gray' => self::COLOR_GRAY,
            'light_gray' => self::COLOR_LIGHT_GRAY,
            'green_price' => self::COLOR_GREEN_PRICE,
            'white' => self::COLOR_WHITE,
            'black' => self::COLOR_BLACK,
        ];
    }

    /**
     * Validar que un color hexadecimal sea válido
     * 
     * @param string $color
     * @return bool
     */
    public static function isValidColor(string $color): bool
    {
        return preg_match('/^[A-F0-9]{6}$/i', $color) === 1;
    }
}
