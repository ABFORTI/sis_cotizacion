<?php

namespace App\Console\Commands;

use App\Models\ArchivoAdjunto;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DiagnoseArchivoAdjuntos extends Command
{
    protected $signature = 'archivos:diagnose
                            {--fix : Reparar automáticamente los problemas encontrados}
                            {--details : Mostrar detalles de cada archivo}';

    protected $description = 'Diagnostica inconsistencias en archivos adjuntos y opcionalmente los repara';

    public function handle()
    {
        $this->info('🔍 Iniciando diagnóstico de archivos adjuntos...');
        $this->newLine();

        $archivos = ArchivoAdjunto::all();

        $problemas = [
            'inexistentes' => [],
            'rutas_invalidas' => [],
            'sin_nombre_original' => [],
        ];

        // Analizar cada archivo
        foreach ($archivos as $archivo) {
            $details = $this->option('details');

            // 1️⃣ Verificar si el archivo existe físicamente
            if (!Storage::disk('public')->exists($archivo->path)) {
                $problemas['inexistentes'][] = $archivo;
                if ($details) {
                    $this->error("  ❌ NO EXISTE: ID {$archivo->id} - {$archivo->path}");
                }
            } else {
                if ($details) {
                    $this->line("  ✓ ID {$archivo->id} - {$archivo->path}");
                }
            }

            // 2️⃣ Verificar rutas inválidas (backslashes escapeados)
            if (strpos($archivo->path, '\\') !== false) {
                $problemas['rutas_invalidas'][] = $archivo;
                if ($details) {
                    $this->warn("  ⚠️  RUTA INVÁLIDA: ID {$archivo->id} - {$archivo->path}");
                }
            }

            // 3️⃣ Verificar si falta nombre original
            if (!$archivo->nombre_original) {
                $problemas['sin_nombre_original'][] = $archivo;
                if ($details) {
                    $this->warn("  ⚠️  SIN NOMBRE: ID {$archivo->id}");
                }
            }
        }

        // 📊 Resumen
        $this->newLine();
        $this->info('═══ RESUMEN DEL DIAGNÓSTICO ═══');
        $this->line("Total de registros: {$archivos->count()}");
        $this->error("Archivos inexistentes: " . count($problemas['inexistentes']));
        $this->warn("Rutas inválidas: " . count($problemas['rutas_invalidas']));
        $this->warn("Sin nombre original: " . count($problemas['sin_nombre_original']));

        // 🔧 Reparar si se solicita
        if ($this->option('fix')) {
            $this->newLine();
            $this->info('🔧 Ejecutando reparaciones...');
            $this->newLine();

            // Eliminar archivos inexistentes
            foreach ($problemas['inexistentes'] as $archivo) {
                $this->line("  Eliminando registro huérfano: ID {$archivo->id} - {$archivo->path}");
                $archivo->delete();
            }

            // Reparar rutas con backslashes
            foreach ($problemas['rutas_invalidas'] as $archivo) {
                $rutaCorregida = str_replace('\\', '/', $archivo->path);

                // Solo si la ruta corregida existe
                if (Storage::disk('public')->exists($rutaCorregida)) {
                    $this->line("  Corrigiendo: ID {$archivo->id}");
                    $this->line("    Antes: {$archivo->path}");
                    $this->line("    Después: {$rutaCorregida}");

                    $archivo->update(['path' => $rutaCorregida]);
                } else {
                    $this->error("  ❌ Ruta corregida no existe: $rutaCorregida");
                }
            }

            // Rellenar nombre original si falta
            foreach ($problemas['sin_nombre_original'] as $archivo) {
                $nombreOriginal = basename($archivo->path);
                $this->line("  Asignando nombre: ID {$archivo->id} → {$nombreOriginal}");

                $archivo->update(['nombre_original' => $nombreOriginal]);
            }

            $this->newLine();
            $this->info('✅ Reparaciones completadas.');
        } else {
            $this->newLine();
            $this->info('💡 Para reparar automáticamente, ejecuta:');
            $this->line('   php artisan archivos:diagnose --fix');
        }

        return 0;
    }
}
