<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Muestra la lista de usuarios (solo para Admin).
     */
    public function index(Request $request)
    {
        $query = User::query();

        // 🔍 Búsqueda por nombre o email
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // 🎯 Filtro por rol
        if ($roleFilter = $request->input('role_filter')) {
            $query->where('role', $roleFilter);
        }

        // 📊 Ordenamiento
        $sort = $request->input('sort', 'name_asc');
        switch ($sort) {
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'email_asc':
                $query->orderBy('email', 'asc');
                break;
            case 'email_desc':
                $query->orderBy('email', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            default:
                $query->orderBy('name', 'asc');
        }

        // ✅ Paginación (mantener parámetros de búsqueda en la URL)
        $usuarios = $query->paginate(10)->withQueryString();

        return view('administrador.index', compact('usuarios'));
    }

    /**
     * Formulario para crear nuevo usuario.
     */
    public function create()
    {
        return view('administrador.create');
    }

    /**
     * Guarda un nuevo usuario en la base de datos.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:admin,ventas,costeos',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        return redirect()->route('administrador.index')->with('success', 'Usuario creado correctamente.');
    }

    /**
     * Formulario para editar usuario existente.
     */
    public function edit(User $usuario)
    {
        return view('administrador.edit', compact('usuario'));
    }

    /**
     * Actualiza los datos del usuario.
     */
    public function update(Request $request, User $usuario)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $usuario->id,
            'role' => 'required|in:admin,ventas,costeos',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $usuario->name = $validated['name'];
        $usuario->email = $validated['email'];
        $usuario->role = $validated['role'];

        if (!empty($validated['password'])) {
            $usuario->password = Hash::make($validated['password']);
        }

        $usuario->save();

        return redirect()->route('administrador.index')->with('success', 'Usuario actualizado correctamente.');
    }

    /**
     * Elimina un usuario.
     */
    public function destroy(User $usuario)
    {
        if ($usuario->id === auth()->id()) {
            return redirect()->route('administrador.index')->with('error', 'No puedes eliminar tu propio usuario.');
        }

        $usuario->delete();
        return redirect()->route('administrador.index')->with('success', 'Usuario eliminado correctamente.');
    }
}
