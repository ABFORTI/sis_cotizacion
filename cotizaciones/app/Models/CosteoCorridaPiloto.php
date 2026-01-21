<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CosteoCorridaPiloto extends Model
{
    use HasFactory;

    protected $table = 'costeo_corrida_piloto';

    protected $guarded = [];

    // Relación con Cotizacion
    public function cotizacion()
    {
        return $this->belongsTo(Cotizacion::class, 'cotizaciones', 'id');
    }

    // Relación con Usuario
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
