<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MateriaPrimaProces extends Model
{
    protected $table = 'materia_prima_procesos';
    protected $guarded = [];
    protected $casts = [];

    public function costeoRequisicion()
    {
        return $this->belongsTo(CosteoRequisicion::class, 'costeo_requisicion_id');
    }
}
