<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MatrizRiesgo extends Model
{
    protected $table = 'matriz_riesgos';

    protected $fillable = [
        'cotizacion_id',
        'riesgo',
        'severidad',
        'severidad_valor',
        'probabilidad',
        'probabilidad_valor',
        'nivel_riesgo',
        'nivel_riesgo_valor',
        'plan_mitigacion_titulo',
        'plan_mitigacion_descripcion',
        'responsable',
        'fecha_limite',
        'estado_mitigacion',
    ];

    protected $casts = [
        'fecha_limite' => 'date',
        'severidad_valor' => 'integer',
        'probabilidad_valor' => 'integer',
        'nivel_riesgo_valor' => 'integer',
    ];

    /**
     * Relación con Cotización
     */
    public function cotizacion(): BelongsTo
    {
        return $this->belongsTo(Cotizacion::class);
    }

    /**
     * Calcula el nivel de riesgo basado en severidad y probabilidad
     */
    public function calcularNivelRiesgo(): void
    {
        if ($this->severidad_valor && $this->probabilidad_valor) {
            $valor = $this->severidad_valor * $this->probabilidad_valor;
            $this->nivel_riesgo_valor = $valor;

            // Determinar nivel de riesgo según el valor
            if ($valor <= 6) {
                $this->nivel_riesgo = 'verde'; // Bajo: 1-6
            } elseif ($valor <= 12) {
                $this->nivel_riesgo = 'amarillo'; // Medio: 7-12
            } else {
                $this->nivel_riesgo = 'rojo'; // Alto: 13-25
            }
        }
    }

    /**
     * Obtener el color CSS según el nivel de riesgo
     */
    public function getColorNivelRiesgo(): string
    {
        return match($this->nivel_riesgo) {
            'verde' => 'bg-green-500',
            'amarillo' => 'bg-yellow-500',
            'rojo' => 'bg-red-500',
            default => 'bg-gray-500',
        };
    }

    /**
     * Obtener el texto del nivel de riesgo
     */
    public function getTextoNivelRiesgo(): string
    {
        return match($this->nivel_riesgo) {
            'verde' => 'Bajo',
            'amarillo' => 'Medio',
            'rojo' => 'Alto',
            default => 'No definido',
        };
    }
}
