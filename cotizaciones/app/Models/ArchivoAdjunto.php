<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArchivoAdjunto extends Model
{
    protected $table = 'archivos_adjuntos';
    protected $fillable = ['cotizacion_id', 'path'];

    // Relación inversa con Cotizacion
    public function cotizacion()
    {
        return $this->belongsTo(Cotizacion::class);
    }
}
