<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcesoAdicional extends Model
{
    protected $table = 'proceso_adicionals';
    protected $guarded = [];
    protected $casts = [
        'costo' => 'decimal:4',
        'total_dias_turnos' => 'decimal:4',
    ];

    public function costeoRequisicion()
    {
        return $this->belongsTo(CosteoRequisicion::class, 'costeo_requisicion_id');
    }
}
