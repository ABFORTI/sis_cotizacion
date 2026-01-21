<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VentasResumenDeCostos extends Model
{
    protected $table = 'ventas_resumen_de_costos';
    protected $guarded = [];

    public function cotizacion()
    {
        return $this->belongsTo(Cotizacion::class, 'cotizacion_id');
    }

    public function costeoRequisicion()
    {
        return $this->belongsTo(CosteoRequisicion::class, 'costeo_requisicion_id');
    }
}