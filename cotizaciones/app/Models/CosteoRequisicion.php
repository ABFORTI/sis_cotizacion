<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class CosteoRequisicion extends Model
{
    protected $table = 'costeo_requisiciones';
    protected $primaryKey = 'id';
    protected $guarded = [];

    // Relación con cotización
    public function cotizacion()
    {
        return $this->belongsTo(Cotizacion::class, 'cotizaciones', 'id');
    }

    // Relación con usuario
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    // Relación procesos de costeo
    public function procesosCosteo()
    {
        return $this->hasMany(ProcesosCosteo::class, 'costeo_requisiciones_id');
    }

    // Relación 1:1 con ventas_resumen_de_costos
    public function ventasResumen()
    {
        return $this->hasOne(\App\Models\VentasResumenDeCostos::class, 'costeo_requisicion_id');
    }

    // Calcular costo total de procesos calculado
    public function getCostoTotalProcesosCalculadoAttribute()
    {
        return $this->procesosCosteo->sum('costo');
    }
}
