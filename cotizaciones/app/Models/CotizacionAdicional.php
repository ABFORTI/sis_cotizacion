<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CotizacionAdicional extends Model
{
    protected $table = 'cotizacion_adicional';
    protected $primaryKey = 'id';
    protected $guarded = [];
    // Relación inversa con Cotizacion
    public function cotizacion()
    {
        return $this->belongsTo(Cotizacion::class);
    }
}
