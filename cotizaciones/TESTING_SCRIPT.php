<?php
/**
 * Script de Prueba del Sistema de Descargas - MANUAL TESTING GUIDE
 * Ejecutar: php artisan tinker < tests/file_download_tests.php
 * O copiar/pegar comandos uno por uno en: php artisan tinker
 */

echo "\n===============================================\n";
echo "PRUEBAS DEL SISTEMA DE DESCARGA DE ARCHIVOS\n";
echo "===============================================\n\n";

// Test 1: Verificar que el endpoint existe
echo "✓ Test 1: Verificar rutas registradas\n";
echo "Buscando ruta para descargar archivo...\n";
$route = Route::getRoutes()->getByName('archivos.download');
if ($route) {
    echo "✅ Ruta encontrada: {$route->uri()}\n";
} else {
    echo "❌ Ruta NO encontrada\n";
}
echo "\n";

// Test 2: Verificar que almacenamiento existe
echo "✓ Test 2: Verificar almacenamiento de archivos\n";
$storageExists = Storage::disk('public')->exists('cotizaciones_archivos');
echo "Carpeta 'cotizaciones_archivos' existe: " . ($storageExists ? '✅ SI' : '⚠️ NO') . "\n";
$files = Storage::disk('public')->files('cotizaciones_archivos');
echo "Total de archivos en almacenamiento: " . count($files) . "\n";
echo "\n";

// Test 3: Verificar tabla de archivos adjuntos
echo "✓ Test 3: Verificar archivos en base de datos\n";
$archivoCount = \App\Models\ArchivoAdjunto::count();
echo "Total de archivos en tabla 'archivos_adjuntos': " . $archivoCount . "\n";

if ($archivoCount > 0) {
    $archivo = \App\Models\ArchivoAdjunto::first();
    echo "\nPrimer archivo:\n";
    echo "  ID: {$archivo->id}\n";
    echo "  Cotización: {$archivo->cotizacion_id}\n";
    echo "  Path: {$archivo->path}\n";
    echo "  Nombre Original: {$archivo->nombre_original}\n";
    echo "  Tipo Archivo: {$archivo->tipo_archivo}\n";
    echo "  Tamaño: " . ($archivo->tamaño ? round($archivo->tamaño / 1024, 2) . ' KB' : 'N/A') . "\n";

    // Test 4: Verificar que archivo existe en filesystem
    echo "\n✓ Test 4: Verificar existencia de archivo en filesystem\n";
    $exists = Storage::disk('public')->exists($archivo->path);
    echo "Archivo existe en disco: " . ($exists ? '✅ SI' : '❌ NO') . "\n";

    if (!$exists) {
        echo "⚠️ Advertencia: El archivo en BD no existe en el filesystem\n";
    }
}
echo "\n";

// Test 5: Verificar tabla de resumen_archivos
echo "✓ Test 5: Verificar archivos de resumen\n";
$resumenCount = \App\Models\ResumenArchivo::count();
echo "Total de archivos en tabla 'resumen_archivos': " . $resumenCount . "\n";

if ($resumenCount > 0) {
    $resumenArchivo = \App\Models\ResumenArchivo::first();
    echo "\nPrimer archivo de resumen:\n";
    echo "  ID: {$resumenArchivo->id}\n";
    echo "  Resumen: {$resumenArchivo->resumen_id}\n";
    echo "  Path: {$resumenArchivo->path}\n";
    echo "  Nombre Original: {$resumenArchivo->nombre_original}\n";
}
echo "\n";

// Test 6: Verificar políticas de autorización
echo "✓ Test 6: Verificar políticas de autorización\n";
$ventasUser = \App\Models\User::where('role', 'ventas')->first();
if ($ventasUser) {
    echo "Usuario Ventas encontrado: {$ventasUser->name} (ID: {$ventasUser->id})\n";

    // Obtener su cotización
    $cotizacion = \App\Models\Cotizacion::where('user_id', $ventasUser->id)->first();
    if ($cotizacion && $cotizacion->archivosAdjuntos->count() > 0) {
        $archivo = $cotizacion->archivosAdjuntos->first();
        $canView = auth()->user() ? auth()->user()->can('view', $archivo) : false;
        echo "¿Puede ver sus propios archivos?: " . ($canView ? '✅ SI' : 'N/A (requiere login)') . "\n";
    }
} else {
    echo "No hay usuarios con rol Ventas\n";
}
echo "\n";

// Test 7: Verificar metadatos en base de datos
echo "✓ Test 7: Verificar metadatos capturados\n";
$conMetadatos = \App\Models\ArchivoAdjunto::whereNotNull('nombre_original')
    ->whereNotNull('tipo_archivo')
    ->count();
echo "Archivos con metadatos completos: $conMetadatos de $archivoCount\n";

if ($conMetadatos < $archivoCount && $archivoCount > 0) {
    echo "⚠️ Nota: Algunos archivos no tienen metadatos completos\n";
    echo "Estos fueron subidos antes de la actualización\n";
}
echo "\n";

// Test 8: Verificar MIME types
echo "✓ Test 8: Probar función getMimeType\n";
$controller = new \App\Http\Controllers\ArchivoAdjuntoController();
$reflection = new ReflectionClass($controller);
$method = $reflection->getMethod('getMimeType');
$method->setAccessible(true);

$testFiles = [
    'documento.pdf' => 'application/pdf',
    'imagen.jpg' => 'image/jpeg',
    'plano.step' => 'application/stp',
    'archivo.zip' => 'application/zip',
    'desconocido.xyz' => 'application/octet-stream',
];

echo "Probando MIME types:\n";
foreach ($testFiles as $filename => $expectedMime) {
    $mime = $method->invoke($controller, $filename);
    $status = $mime === $expectedMime ? '✅' : '❌';
    echo "  $status $filename -> $mime\n";
}
echo "\n";

// Test 9: Estadísticas
echo "✓ Test 9: Estadísticas del sistema\n";
$cotizacionesTotal = \App\Models\Cotizacion::count();
$cotizacionesConArchivos = \App\Models\Cotizacion::has('archivosAdjuntos')->count();
$cotizacionesConResumen = \App\Models\Cotizacion::has('resumen')->count();

echo "Total de cotizaciones: $cotizacionesTotal\n";
echo "Cotizaciones con archivos adjuntos: $cotizacionesConArchivos\n";
echo "Cotizaciones con resumen: $cotizacionesConResumen\n";
echo "\n";

// Test 10: Verificar estructura de carpetas
echo "✓ Test 10: Estructura de almacenamiento\n";
$publicPath = Storage::disk('public')->path('');
echo "Path del almacenamiento público: $publicPath\n";
echo "Sistema de archivos: " . (is_dir($publicPath) ? '✅ Accesible' : '❌ No accesible') . "\n";
echo "\n";

echo "===============================================\n";
echo "RESUMEN DE PRUEBAS COMPLETADO\n";
echo "===============================================\n\n";

echo "Si deseas probar manualmente en el navegador:\n";
echo "1. Ir a una cotización con archivos\n";
echo "2. Clickear en botón 'Descargar'\n";
echo "3. URL debería ser: /archivos/{id}/download\n";
echo "4. El archivo debería descargar sin errores\n\n";

echo "Para debugging en consola:\n";
echo "php artisan tinker\n";
echo "> \$archivo = App\\Models\\ArchivoAdjunto::first()\n";
echo "> Storage::disk('public')->exists(\$archivo->path)\n";
echo "> auth()->user()->can('view', \$archivo)\n\n";
