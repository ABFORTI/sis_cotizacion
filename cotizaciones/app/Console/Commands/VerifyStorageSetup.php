<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class VerifyStorageSetup extends Command
{
    protected $signature = 'storage:verify {--create-link : Crear symlink si no existe}';
    protected $description = 'Verifica la configuración completa del Storage y symlinks';

    public function handle()
    {
        $this->info('Verificando configuración de Storage...');
        $this->newLine();

        $issues = [];
        $appUrl = config('app.url');
        $storagePath = storage_path('app/public');
        $publicStoragePath = public_path('storage');

        if (!is_dir($storagePath)) {
            $this->error("No existe: {$storagePath}");
            $issues[] = 'storage_dir_missing';
        } else {
            $this->line("✓ Directorio exists: storage/app/public");
        }

        $archivosDir = $storagePath . '/cotizaciones_archivos';
        if (!is_dir($archivosDir)) {
            $this->warn("No existe: storage/app/public/cotizaciones_archivos");
            $this->line("   Creando directorio...");
            @mkdir($archivosDir, 0755, true);
            $this->info("✓ Directorio creado");
        } else {
            $this->line("✓ Directorio exists: storage/app/public/cotizaciones_archivos");
        }

        $publicStoragePath = public_path('storage');
        $target = @readlink($publicStoragePath);

        if ($target) {
            $this->info("✓ Symlink exists: public/storage → $target");
        } else {
            $this->error("Symlink NO existe: public/storage");
            $issues[] = 'symlink_missing';

            if ($this->option('create-link')) {
                $this->line('   Creando symlink...');
                try {
                    \Illuminate\Support\Facades\Artisan::call('storage:link');
                    $this->info('   ✓ Symlink creado correctamente');
                } catch (\Exception $e) {
                    $this->error("Error: {$e->getMessage()}");
                }
            }
        }

        $this->newLine();
        $this->info('📋 Configuración de Filesystems:');

        $publicDisk = config('filesystems.disks.public');
        $this->line("  Root: " . $publicDisk['root']);
        $this->line("  URL: " . $publicDisk['url']);
        $this->line("  Visibility: " . $publicDisk['visibility']);

        $this->newLine();
        $this->info('Verificando permisos:');

        if (is_writable($storagePath)) {
            $this->line(" ✓ storage/app/public es escribible");
        } else {
            $this->error("storage/app/public NO es escribible");
            $issues[] = 'permissions';
        }

        if (is_writable($archivosDir)) {
            $this->line(" ✓ storage/app/public/cotizaciones_archivos es escribible");
        } else {
            $this->error("storage/app/public/cotizaciones_archivos NO es escribible");
            $issues[] = 'permissions';
        }

        $this->newLine();
        $this->info('Test de Storage::disk(\'public\'):');

        try {
            $testFile = 'test-' . uniqid() . '.txt';
            Storage::disk('public')->put($testFile, 'test');
            $this->line("✓ Puede escribir archivos");

            $exists = Storage::disk('public')->exists($testFile);
            if ($exists) {
                $this->line("✓ Puede verificar existencia de archivos");
            }

            Storage::disk('public')->delete($testFile);
            $this->line("✓ Puede eliminar archivos");
        } catch (\Exception $e) {
            $this->error("Error en operación de Storage: {$e->getMessage()}");
            $issues[] = 'storage_operations';
        }

        $this->newLine();
        if (empty($issues)) {
            $this->info('Todo está correctamente configurado');
        } else {
            $this->warn('Se encontraron problemas:');
            foreach ($issues as $issue) {
                $this->line("  - $issue");
            }
        }

        return empty($issues) ? 0 : 1;
    }
}
