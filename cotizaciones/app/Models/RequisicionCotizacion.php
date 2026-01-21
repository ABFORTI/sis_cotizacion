<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequisicionCotizacion extends Model
{
    protected $table = 'requisiciones_cotizacion';
    protected $primaryKey = 'id';
    protected $guarded = [];
    // Relación inversa con Cotizacion
    public function cotizacion()
    {
        return $this->belongsTo(Cotizacion::class);
    }
}
