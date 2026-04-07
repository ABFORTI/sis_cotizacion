<?php

namespace App\Console\Commands;

use App\Models\ArchivoAdjunto;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TestDescargas extends Command
{
    protected $signature = 'archivos:test-descargas';
    protected $description = 'Test completo de descargas de archivos';

    public function handle()
    {
        $this->info('🧪 TEST COMPLETO DE DESCARGAS DE ARCHIVOS');
        $this->newLine();

        // 1️⃣ Verificar BD
        $this->info('1️⃣  Verificando Base de Datos...');
        $total = ArchivoAdjunto::count();
        $this->line("   Registros en BD: {$total}");

        if ($total === 0) {
            $this->error('   ❌ No hay archivos en la BD');
            return 1;
        }

        $this->newLine();

        // 2️⃣ Verificar cada archivo
        $this->info('2️⃣  Verificando integridad de archivos...');

        $archivos = ArchivoAdjunto::all();
        $ok = 0;
        $missing = [];

        foreach ($archivos as $archivo) {
            $exists = Storage::disk('public')->exists($archivo->path);

            if ($exists) {
                $size = Storage::disk('public')->size($archivo->path);
                $this->line("   ✓ ID {$archivo->id}: {$archivo->nombre_original} ({$size} bytes)");
                $ok++;
            } else {
                $this->error("   ❌ ID {$archivo->id}: NO EXISTE - {$archivo->path}");
                $missing[] = $archivo->id;
            }
        }

        $this->newLine();
        $this->line("   Archivos OK: {$ok}/{$total}");

        if (!empty($missing)) {
            $this->warn("   Archivos faltantes: " . implode(', ', $missing));
            $this->warn('   💡 Ejecutar: php artisan archivos:diagnose --fix');
        }

        $this->newLine();

        // 3️⃣ Test de Storage::disk('public')->download()
        $this->info('3️⃣  Test de simulación de descarga...');

        $archivo = ArchivoAdjunto::first();
        if ($archivo) {
            try {
                $stream = Storage::disk('public')->response($archivo->path);
                $this->line("   ✓ Storage::response() OK");

                $downloadName = $archivo->nombre_original ?? basename($archivo->path);
                $this->line("   ✓ Nombre para descarga: {$downloadName}");

                $path = storage_path('app/public/' . $archivo->path);
                $this->line("   ✓ Ruta física: $path");
                $this->line("   ✓ Archivo accesible: " . (file_exists($path) ? 'SÍ' : 'NO'));

            } catch (\Exception $e) {
                $this->error("   ❌ Error en Storage: {$e->getMessage()}");
                return 1;
            }
        }

        $this->newLine();

        // 4️⃣ Verificación final
        $this->info('✅ TEST COMPLETADO');
        $this->newLine();

        if (empty($missing)) {
            $this->info("✅ Sistema de descargas FUNCIONA CORRECTAMENTE");
            $this->info("   Todos los {$total} archivos están disponibles y listos para descargar");
            return 0;
        } else {
            $this->error("❌ Sistema detectó inconsistencias");
            $this->error("   Ejecutar: php artisan archivos:diagnose --fix");
            return 1;
        }
    }
}
