<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EspecificacionEmpaque extends Model
{
    protected $table = 'especificaciones_empaque';
    protected $primaryKey = 'id';
    protected $guarded = [];
    // Relación inversa con Cotizacion
    public function cotizacion()
    {
        return $this->belongsTo(Cotizacion::class);
    }
}
