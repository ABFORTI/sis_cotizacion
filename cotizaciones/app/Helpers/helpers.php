<?php

use Illuminate\Support\Arr;

if (!function_exists('oldValue')) {
    /**
     * Devuelve el valor de un campo, usando old() si existe,
     * o el valor del modelo si no.
     *
     * @param  string  $field   Nombre del campo
     * @param  mixed   $model   Instancia del modelo (puede ser null)
     * @param  mixed   $default Valor por defecto
     * @return mixed
     */
    function oldValue(string $field, $model = null, $default = '')
    {
        return old($field, $model?->$field ?? $default);
    }
}

if (!function_exists('eliminarMaquinaEspecifica')) {
    /**
     * Elimina una máquina específica asociada a una requisición de costeo.
     *
     * @param  int    $requisicionId  ID de la requisición de costeo
     * @param  string $tipoMaquina    Tipo de máquina ('suaje' o 'termoformado')
     * @param  int    $maquinaId      ID de la máquina específica
     * @return void
     */
    function eliminarMaquinaEspecifica(int $requisicionId, string $tipoMaquina, int $maquinaId): void
    {
        if ($tipoMaquina === 'suaje') {
            \App\Models\MaquinaSuajeCosteo::where('costeo_requisiciones_id', $requisicionId)
                ->where('id', $maquinaId)
                ->delete();
        } elseif ($tipoMaquina === 'termoformado') {
            \App\Models\MaquinaTermoformadoCosteo::where('costeo_requisiciones_id', $requisicionId)
                ->where('id', $maquinaId)
                ->delete();
        }
    }
}
