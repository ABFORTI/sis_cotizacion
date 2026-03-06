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

    public function getMaterialMostradoAttribute(): ?string
    {
        $material = trim((string) ($this->material ?? ''));
        $materialOtro = trim((string) ($this->material_otro ?? ''));

        if (in_array(strtolower($material), ['otro', 'otros'], true) && $materialOtro !== '') {
            return $materialOtro;
        }

        return $material !== '' ? $material : null;
    }
}
