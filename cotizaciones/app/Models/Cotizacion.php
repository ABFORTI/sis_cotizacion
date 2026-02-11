<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cotizacion extends Model
{
    protected $table = 'cotizaciones';
    protected $primaryKey = 'id';
    protected $guarded = [];
    // Relaciones con los hijos
/**
 * Indica si la cotización está oculta para el rol de Costeos
 */
protected $casts = [
    'oculta_para_costeos' => 'boolean',
];

    public function especificacionProyecto()
    {
        return $this->hasOne(EspecificacionProyecto::class);
    }

    public function especificacionEmpaque()
    {
        return $this->hasOne(EspecificacionEmpaque::class);
    }

    public function cotizacionAdicional()
    {
        return $this->hasOne(CotizacionAdicional::class);
    }

    public function requisicionCotizacion()
    {
        return $this->hasOne(RequisicionCotizacion::class);
    }

    public function termoformado()
    {
        return $this->hasOne(Termoformado::class);
    }

    public function usoCliente()
    {
        return $this->hasOne(UsoCliente::class);
    }

    public function cajaCliente()
    {
        return $this->hasOne(CajaCliente::class);
    }

    public function archivosAdjuntos()
    {
        return $this->hasMany(ArchivoAdjunto::class);
    }

    public function costeoRequisicion()
    {
        // migration uses column name `cotizaciones` as the FK on costeo_requisiciones
        return $this->hasOne(CosteoRequisicion::class, 'cotizaciones', 'id');
    }

    /**
     * Relación 1:1 con la tabla ventas_resumen_de_costos
     */
    public function ventasResumen()
    {
        return $this->hasOne(VentasResumenDeCostos::class, 'cotizacion_id');
    }

    public function costeoCorridaPiloto()
    {
        // Relación para los datos de corrida piloto
        return $this->hasOne(CosteoCorridaPiloto::class, 'cotizaciones', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación: Usuario de Ventas que envió la cotización a Costeos
    public function enviadoPorVentas()
    {
        return $this->belongsTo(User::class, 'enviado_por_ventas');
    }

    // Relación: Usuario de Costeos que envió la cotización de vuelta a Ventas
    public function enviadoPorCosteos()
    {
        return $this->belongsTo(User::class, 'enviado_por_costeos');
    }
    
    public function resumen()
    {
        return $this->hasOne(\App\Models\Resumen::class, 'resumen_id', 'id');
    }

    // Relación: Matriz de Riesgos (puede tener múltiples riesgos)
    public function matrizRiesgos()
    {
        return $this->hasMany(MatrizRiesgo::class, 'cotizacion_id');
    }
    
    public function getDiasEnCostosAttribute(): int
{
    // Aún no se ha enviado a Costeos → no hay conteo
    if (!$this->enviado_a_costeos || !$this->fecha_envio_ventas) {
        return 0;
    }

    $inicio = \Carbon\Carbon::parse($this->fecha_envio_ventas)->startOfDay();

    $fin = $this->fecha_envio_costeos
        ? \Carbon\Carbon::parse($this->fecha_envio_costeos)->startOfDay()
        : \Carbon\Carbon::now()->startOfDay();

    return max(0, $inicio->diffInDays($fin));
}

}
