<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResumenArchivo extends Model
{
    protected $table = 'resumen_archivos';

    protected $fillable = [
        'resumen_id',
        'nombre_original',
        'path',
    ];

    public function resumen()
    {
        return $this->belongsTo(Resumen::class, 'resumen_id');
    }
}