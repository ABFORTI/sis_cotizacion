<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Termoformado extends Model
{
    protected $table = 'termoformado';
    protected $primaryKey = 'id';
    protected $guarded = [];
    // Relación inversa con Cotizacion
    public function cotizacion()
    {
        return $this->belongsTo(Cotizacion::class);
    }
}
