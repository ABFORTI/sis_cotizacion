<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CotizacionController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CosteoRequisicionController;
use App\Http\Controllers\ExcelController;
use App\Http\Controllers\CorreoController;
use App\Http\Controllers\MailCotizacionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\ResumenController;
use App\Http\Controllers\ArchivoAdjuntoController;

/*
|--------------------------------------------------------------------------
| Página principal
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->get('/', [HomeController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| Cotizaciones (CRUD y Excel)
|--------------------------------------------------------------------------
*/

// 🔹 Exportaciones y lineamientos
Route::middleware('auth')->group(function () {
    Route::get('/cotizacion/{id}/excel', [ExcelController::class, 'generarCotizacionExcel'])
        ->name('cotizacion.excel');    

    Route::get('/cotizacion/{id}/excel-completo', [ExcelController::class, 'generarCotizacionLineamientosExcel'])
        ->name('cotizacion.excel.completo');

    Route::get('/cotizacion/{id}/pdf-completo', [ExcelController::class, 'generarCotizacionLineamientosPdf'])
        ->name('cotizacion.pdf.completo');    

    Route::get('/cotizacion/{id}/lineamientos-excel', [ExcelController::class, 'generarLineamientosExcel'])
        ->name('cotizacion.lineamientos.excel');

    Route::get('/cotizacion/{id}/lineamientos', [CotizacionController::class, 'mostrarLineamientos'])
        ->name('cotizacion.lineamientos');

    Route::put('/cotizacion/{id}/lineamientos', [CotizacionController::class, 'guardarLineamientos'])
        ->name('cotizacion.lineamientos.save');
        Route::get('/costeo/{id}/export-resumen', [ExcelController::class, 'generarCosteoResumenExcel'])
    ->name('costeo.export.resumen');

    Route::get('/costeo/{id}/export-resumen-pdf', [ExcelController::class, 'generarCosteoResumenPdf'])
    ->name('costeo.export.resumen.pdf');

    Route::get('/cotizacion/{id}/pdf', [ExcelController::class, 'generarCotizacionPdf'])
        ->name('cotizacion.pdf'); 
    
    Route::get('/cotizacion/{id}/lineamientos-pdf', [ExcelController::class, 'generarLineamientosPdf'])
        ->name('cotizacion.lineamientos.pdf');
    
    Route::get('/cotizacion/{id}/resumen-costos-pdf', [ExcelController::class, 'generarResumenCostosPdf'])
        ->name('cotizacion.resumen.costos.pdf');

    // 🔹 Mostrar formulario general de cotización
    Route::get('/cotizacion/{cotizacion}', [CotizacionController::class, 'showForm'])
        ->name('cotizacion.form');
});


// 🔹 Panel Administrativo (solo admin)
Route::middleware(['auth', 'rol.admin'])
    ->get('administrador/admin', [CotizacionController::class, 'adminIndex'])
    ->name('administrador.admin.index');

// 🔹 CRUD de cotizaciones (todos los roles autenticados)
Route::middleware(['auth', 'rol.todos'])
    ->resource('cotizaciones', CotizacionController::class)
    ->parameters(['cotizaciones' => 'cotizacion']);

// 🔹 Enviar cotización a Costeos (ventas y admin)
Route::middleware(['auth', 'rol.todos'])
    ->patch('cotizaciones/{cotizacion}/enviar', [CotizacionController::class, 'marcarEnviado'])
    ->name('cotizaciones.enviar');

// 🔹 Enviar cotización a Ventas (costeos y admin)
Route::middleware(['auth', 'rol.costeos'])
    ->patch('cotizaciones/{cotizacion}/enviar-a-ventas', [CotizacionController::class, 'enviarAVentas'])
    ->name('cotizaciones.enviarAVentas');

// 🔹 Eliminar archivo adjunto (costeos y admin)
Route::middleware(['auth', 'rol.costeos'])
    ->delete('/cotizaciones/{cotizacion}/eliminar-archivo', [CotizacionController::class, 'eliminarArchivo'])
    ->name('cotizaciones.eliminar-archivo');

   

/*
|--------------------------------------------------------------------------
| Rutas para COSTEOS
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'rol.costeos'])->group(function () {
    Route::get('/requisicion/{id}/costeo', [CosteoRequisicionController::class, 'create'])
        ->name('costeo.create');

    Route::post('/requisicion/{id}/costeo', [CosteoRequisicionController::class, 'store'])
        ->name('costeo.store');

    Route::patch('/requisicion/{id}/costeo', [CosteoRequisicionController::class, 'update'])
        ->name('costeo.update');

        Route::delete('/resumen/archivo/{id}', [ResumenController::class, 'eliminarArchivo'])
     ->name('resumen.archivo.eliminar');
     Route::patch('/cotizaciones/{cotizacion}/ocultar-costeos',[CotizacionController::class, 'ocultarParaCosteos'])
     ->name('cotizaciones.ocultarCosteos');


    // Rutas AJAX para procesos dinámicos
    //Route::delete('/costeo/{requisicionId}/proceso/{procesoId}', [CosteoRequisicionController::class, 'eliminarProceso'])
    //    ->name('costeo.eliminarProceso');
    
    //Route::post('/requisicion/{cotizacionId}/proceso', [CosteoRequisicionController::class, 'agregarProceso'])
    //    ->name('costeo.agregarProceso');
    
    //Route::get('/requisicion/{cotizacionId}/procesos', [CosteoRequisicionController::class, 'obtenerProcesos'])
    //    ->name('costeo.obtenerProcesos');

    //Route::put('/costeo/{requisicionId}/proceso/{procesoId}', [CosteoRequisicionController::class, 'actualizarProceso'])
    //    ->name('costeo.actualizarProceso');
});

/*
|--------------------------------------------------------------------------
| Envío por correo
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::post('/cotizacion/{id}/enviar-excel', [CorreoController::class, 'enviarExcel'])
        ->name('cotizacion.enviar.excel');

    Route::post('/cotizacion/{id}/enviar-correo', [MailCotizacionController::class, 'enviarCotizacionExcel'])
        ->name('cotizacion.enviarCorreo');
});

/*
|--------------------------------------------------------------------------
| Autenticación
|--------------------------------------------------------------------------
*/
Route::view('/login', 'auth.login')->name('login');
Route::view('/register', 'auth.register')->name('register');

Route::post('/validar-registro', [LoginController::class, 'register'])->name('validar-registro');
Route::post('/inicia-sesion', [LoginController::class, 'login'])->name('inicia-sesion');
Route::get('/cerrar-sesion', [LoginController::class, 'logout'])->name('cerrar-sesion');

/*
|--------------------------------------------------------------------------
| CRUD de Usuarios (solo Admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'rol.admin'])->group(function () {
    Route::resource('usuarios', UserController::class)
        ->parameters(['usuarios' => 'usuario']);
      Route::get('/administrador', [UserController::class, 'index'])->name('administrador.index');
    Route::get('/administrador/create', [UserController::class, 'create'])->name('administrador.create');
    Route::post('/administrador', [UserController::class, 'store'])->name('administrador.store');
    Route::get('/administrador/{usuario}/edit', [UserController::class, 'edit'])->name('administrador.edit');
    Route::put('/administrador/{usuario}', [UserController::class, 'update'])->name('administrador.update');
    Route::delete('/administrador/{usuario}', [UserController::class, 'destroy'])->name('administrador.destroy');  
});
Route::middleware('auth')->group(function () {
    // 🔹 Vista de Matriz de Riesgos
    Route::get('/cotizaciones/{cotizacion}/matriz-riesgos', [CotizacionController::class, 'verMatrizRiesgos'])
        ->name('cotizaciones.matrizRiesgos');
    Route::patch('/cotizaciones/{cotizacion}/actualizar-estado', [CotizacionController::class, 'actualizarEstado'])
        ->name('cotizaciones.actualizar-estado');
    Route::patch('/cotizaciones/{cotizacion}/actualizar-mitigacion', [CotizacionController::class, 'actualizarMitigacion'])
        ->name('cotizaciones.actualizar-mitigacion');
    Route::patch('/cotizaciones/{cotizacion}/actualizar-mitigacion-general', [CotizacionController::class, 'actualizarMitigacionGeneral'])
        ->name('cotizaciones.actualizar-mitigacion-general');

    // 🔹 Vista de resumen
    Route::get('/costeo/create', [ResumenController::class, 'create'])->name('resumen.create');
    Route::post('/costeo/store', [ResumenController::class, 'store'])->name('resumen.store');   
    Route::post('/costeo/update-field', [ResumenController::class, 'updateField'])
        ->name('costeo.updateField');

    // Página: mostrar Resumen de Costos 
    Route::get('/cotizacion/{id}/resumen', [ResumenController::class, 'showPage'])
        ->name('cotizacion.resumen.page');

    // Guardar Resumen de Costos (ventas) en tabla ventas_resumen_de_costos
    Route::post('/cotizacion/{id}/resumen-store', [ResumenController::class, 'storeVentasResumen'])
        ->name('cotizacion.resumen.store');
});

Route::post('/archivos', [ArchivoAdjuntoController::class, 'store'])
    ->name('archivos.store');

Route::delete('/archivos/{archivo}', [ArchivoAdjuntoController::class, 'destroy'])
    ->name('archivos.destroy');

//rutas protegidas de cotizaciones    
// Ruta CREATE para ambos roles
//Route::middleware(['auth', 'rol.ventasocosteos'])
//    ->get('cotizaciones/create', [CotizacionController::class, 'create'])
//    ->name('cotizaciones.create');
//
//Route::middleware(['auth', 'rol.ventasocosteos'])
//    ->post('cotizaciones', [CotizacionController::class, 'store'])
//    ->name('cotizaciones.store');
//
//// Resto del CRUD solo para costeos
//Route::middleware(['auth', 'rol.costeos'])
//    ->resource('cotizaciones', CotizacionController::class)
//    ->except(['create', 'store'])
//    ->parameters(['cotizaciones' => 'cotizacion']);