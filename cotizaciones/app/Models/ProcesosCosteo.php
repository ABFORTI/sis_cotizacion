<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcesosCosteo extends Model
{
    protected $table = 'procesos_costeo';
    protected $guarded = [];

    public function costeoRequisicion()
    {
        return $this->belongsTo(CosteoRequisicion::class, 'costeo_requisiciones_id');
    }
}
