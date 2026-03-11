<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cotizacion;
use Illuminate\Support\Facades\Mail;
use App\Mail\CotizacionExcelMailable;
use App\Http\Controllers\ExcelController;

class CorreoController extends Controller
{
    public function enviarExcel(Request $request, $id)
    {
        $request->validate([
            'correo_destino' => 'required|email'
        ]);

        $cotizacion = Cotizacion::findOrFail($id);

        // Generar el Excel temporalmente
        $excelController = new ExcelController();
        $excel = $excelController->generarCotizacionExcel($id, true);

        // Enviar correo
        Mail::to($request->correo_destino)->send(new CotizacionExcelMailable($cotizacion, $excel));

        return back()->with('success', 'Cotización enviada correctamente al correo.');
    }
}
