<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resumen extends Model
{
    use HasFactory;

    protected $table = 'resumen';
    protected $primaryKey = 'resumen_id';

    protected $fillable = [
        'cotizacion_id',
        'poka_yoke',
        'acomodo_pieza',
        'contenedor_cliente',
        'medidas_contenedor',
        'estiba_contenedor',
        'cliente_proporciona',
    ];

    public function cotizacion()
    {
        return $this->belongsTo(Cotizacion::class, 'cotizacion_id');
    }

    public function archivos()
    {
        return $this->hasMany(ResumenArchivo::class, 'resumen_id');
    }
}