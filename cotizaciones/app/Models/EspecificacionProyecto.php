<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EspecificacionProyecto extends Model
{
    protected $table = 'especificaciones_proyecto';
    protected $primaryKey = 'id';
    protected $guarded = [];

    // Relación inversa con Cotizacion
    public function cotizacion()
    {
        return $this->belongsTo(Cotizacion::class);
    }
}
