<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CajaCliente extends Model
{
    protected $table = 'cajas_cliente';
    protected $primaryKey = 'id';
    protected $guarded = [];
    // Relación inversa con Cotizacion
    public function cotizacion()
    {
        return $this->belongsTo(Cotizacion::class);
    }
}
