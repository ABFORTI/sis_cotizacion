<?php

namespace App\Http\Controllers;

use App\Models\ArchivoAdjunto;
use App\Models\Cotizacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArchivoAdjuntoController extends Controller
{

    public function store(Request $request)
    {
        $request->validate([
            'archivo' => 'required|file|max:61440',
            'cotizacion_id' => 'required|exists:cotizaciones,id',
        ]);

        $cotizacion = Cotizacion::with('archivosAdjuntos')
            ->findOrFail($request->cotizacion_id);

        foreach ($cotizacion->archivosAdjuntos as $archivo) {
            if (Storage::disk('public')->exists($archivo->path)) {
                Storage::disk('public')->delete($archivo->path);
            }
            $archivo->delete();
        }

        $file = $request->file('archivo');
        $size = $file->getSize();
        $extension = $file->extension();
        $originalName = $file->getClientOriginalName();

        $path = $file->store('cotizaciones_archivos', 'public');

        ArchivoAdjunto::create([
            'cotizacion_id' => $cotizacion->id,
            'path' => $path,
            'nombre_original' => $originalName,
            'tipo_archivo' => $extension,
            'tamaño' => $size,
        ]);

        return back()->with('success', 'Archivo subido correctamente.');
    }

    /**
     * Descargar archivo de forma segura y robusta
     *
     * - Valida autorización
     * - Verifica existencia previo a descarga
     * - Usa Storage::disk('public')->download() EXCLUSIVAMENTE
     * - Retorna 404 si falta el archivo
     */
    public function download($id)
    {
        $archivo = ArchivoAdjunto::findOrFail($id);

        // Validar que el usuario tenga permiso para descargar este archivo
        $this->authorize('view', $archivo);

        // Verificar existencia del archivo físico
        if (!Storage::disk('public')->exists($archivo->path)) {
            abort(404, 'El archivo no está disponible en el servidor');
        }

        // Nombre para la descarga
        $downloadName = $archivo->nombre_original ?? basename($archivo->path);

        // Storage::disk('public')->download() maneja automáticamente:
        // - Content-Type basado en extensión
        // - Content-Disposition: attachment
        // - Content-Length
        // - Sin necesidad de headers personalizados
        return Storage::disk('public')->download($archivo->path, $downloadName);
    }

    /**
     * Eliminar archivo adjunto de forma segura
     *
     * - Valida autorización
     * - Elimina archivo físico si existe
     * - Elimina registro de BD
     */
    public function destroy(ArchivoAdjunto $archivo)
    {
        $this->authorize('delete', $archivo);

        // Eliminar archivo físico si existe
        if (Storage::disk('public')->exists($archivo->path)) {
            Storage::disk('public')->delete($archivo->path);
        }

        // Eliminar registro de BD
        $archivo->delete();

        return back()->with('success', 'Archivo eliminado correctamente.');
    }
}
