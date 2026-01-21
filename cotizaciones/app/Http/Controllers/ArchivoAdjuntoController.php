<?php

namespace App\Http\Controllers;

use App\Models\ArchivoAdjunto;
use App\Models\Cotizacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArchivoAdjuntoController extends Controller
{
    /**
     * Guardar o reemplazar imagen
     */
    public function store(Request $request)
    {
        $request->validate([
            'archivo' => 'required|image|max:4096',
            'cotizacion_id' => 'required|exists:cotizaciones,id',
        ]);

        $cotizacion = Cotizacion::with('archivosAdjuntos')
            ->findOrFail($request->cotizacion_id);

        // 🔥 Eliminar imagen anterior
        foreach ($cotizacion->archivosAdjuntos as $archivo) {
            if (Storage::disk('public')->exists($archivo->path)) {
                Storage::disk('public')->delete($archivo->path);
            }
            $archivo->delete();
        }

        // 📤 Guardar nueva imagen
        $path = $request->file('archivo')->store('cotizaciones', 'public');

        ArchivoAdjunto::create([
            'cotizacion_id' => $cotizacion->id,
            'path' => $path,
        ]);

        return back()->with('success', 'Imagen subida correctamente.');
    }

    /**
     * Eliminar imagen
     */
    public function destroy(ArchivoAdjunto $archivo)
    {
        if (Storage::disk('public')->exists($archivo->path)) {
            Storage::disk('public')->delete($archivo->path);
        }

        $archivo->delete();

        return back()->with('success', 'Imagen eliminada correctamente.');
    }
}
