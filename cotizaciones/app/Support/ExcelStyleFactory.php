<?php

namespace App\Support;

use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;


class ExcelStyleFactory
{
    /**
     * Estilo para encabezados (fondo oscuro, texto blanco)
     * 
     * @return array
     */
    public static function headerStyle(): array
    {
        return [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => CotizacionConfig::COLOR_WHITE],
                'size' => CotizacionConfig::FONT_SIZE_HEADER,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => CotizacionConfig::COLOR_PRIMARY_DARK],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => CotizacionConfig::COLOR_BLACK],
                ]
            ],
        ];
    }

    /**
     * Estilo para títulos en rojo
     * 
     * @return array
     */
    public static function titleRedStyle(): array
    {
        return [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => CotizacionConfig::COLOR_ACCENT_RED],
                'size' => CotizacionConfig::FONT_SIZE_TITLE,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
        ];
    }

    /**
     * Estilo para celdas grises
     * 
     * @return array
     */
    public static function grayCellStyle(): array
    {
        return [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => CotizacionConfig::COLOR_GRAY],
            ],
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ];
    }

    /**
     * Estilo para celdas gris claro
     * 
     * @return array
     */
    public static function lightGrayCellStyle(): array
    {
        return [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => CotizacionConfig::COLOR_LIGHT_GRAY],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ];
    }

    /**
     * Estilo para precios en verde
     * 
     * @return array
     */
    public static function greenPriceStyle(): array
    {
        return [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => CotizacionConfig::COLOR_GREEN_PRICE],
            ],
            'font' => [
                'bold' => true,
                'color' => ['rgb' => CotizacionConfig::COLOR_WHITE],
                'size' => CotizacionConfig::FONT_SIZE_HEADER,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ];
    }

    /**
     * Estilo para encabezados de especificaciones
     * 
     * @return array
     */
    public static function specHeaderStyle(): array
    {
        return [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => CotizacionConfig::COLOR_LIGHT_GRAY],
            ],
            'font' => [
                'bold' => true,
                'size' => CotizacionConfig::FONT_SIZE_NORMAL,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ]
            ],
        ];
    }

    /**
     * Estilo para datos de especificaciones
     * 
     * @return array
     */
    public static function specDataStyle(): array
    {
        return [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => CotizacionConfig::COLOR_GRAY],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ]
            ],
        ];
    }

    /**
     * Estilo normal sin formato
     * 
     * @return array
     */
    public static function normalStyle(): array
    {
        return [
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
        ];
    }

    /**
     * Estilo para centrado
     * 
     * @return array
     */
    public static function centeredStyle(): array
    {
        return [
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
        ];
    }

    /**
     * Estilo para bordes simples
     * 
     * @return array
     */
    public static function borderStyle(): array
    {
        return [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => CotizacionConfig::COLOR_BLACK],
                ]
            ],
        ];
    }

    /**
     * Crear estilos personalizados combinando opciones
     * 
     * @param array $options
     * @return array
     */
    public static function custom(array $options = []): array
    {
        $defaults = [
            'bold' => false,
            'color' => CotizacionConfig::COLOR_BLACK,
            'backgroundColor' => null,
            'fontSize' => CotizacionConfig::FONT_SIZE_NORMAL,
            'align' => Alignment::HORIZONTAL_LEFT,
            'verticalAlign' => Alignment::VERTICAL_CENTER,
            'wrapText' => true,
        ];

        $settings = array_merge($defaults, $options);
        $style = [];

        if ($settings['fontSize'] || $settings['bold'] || $settings['color']) {
            $style['font'] = [];
            if ($settings['bold']) {
                $style['font']['bold'] = true;
            }
            if ($settings['color']) {
                $style['font']['color'] = ['rgb' => $settings['color']];
            }
            if ($settings['fontSize']) {
                $style['font']['size'] = $settings['fontSize'];
            }
        }

        if ($settings['backgroundColor']) {
            $style['fill'] = [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => $settings['backgroundColor']],
            ];
        }

        $style['alignment'] = [
            'horizontal' => $settings['align'],
            'vertical' => $settings['verticalAlign'],
            'wrapText' => $settings['wrapText'],
        ];

        return $style;
    }
}
