<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\GerenteVentasService;

class GerenteVentasController extends Controller
{
    public function __construct(private GerenteVentasService $gerenteVentasService)
    {
    }

    public function index()
    {
        $usuariosVentas = $this->gerenteVentasService->obtenerUsuariosVentas();

        return view('gerente-ventas.index', compact('usuariosVentas'));
    }

    public function actividad(User $user)
    {
        return response()->json(
            $this->gerenteVentasService->obtenerActividadUsuario($user)
        );
    }
}
