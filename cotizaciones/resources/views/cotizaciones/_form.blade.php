<!-- 1. Datos Generales -->
<x-form-datos-generales :cotizacion="$cotizacion ?? null" />
<!-- 2. Especificaciones del Proyecto -->
<x-form-especificaciones-proyecto :cotizacion="$cotizacion ?? null" />
<!-- 3. Especificaciones de Empaque -->
<x-form-especificaciones-empaque :cotizacion="$cotizacion ?? null" />
<!-- 4. Cotización Adicional -->
<x-form-cotizacion-adicional :cotizacion="$cotizacion ?? null" />
<!-- 5. Requisición de Cotización -->
<x-form-requisicion-cotizacion :cotizacion="$cotizacion ?? null" />
<!-- 6. Carga de Archivos -->
<x-form-carga-archivos :cotizacion="$cotizacion ?? null" />
<!-- 7. Fecha de Efectividad -->
<x-form-fecha-efectividad :cotizacion="$cotizacion ?? null" />