<?php

namespace App\Modules\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Modules\Auth\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->check() || !auth()->user()->hasRole('administrador')) {
                abort(403, 'Acceso denegado. Se requiere rol de administrador.');
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $query = User::with('role');

        // Filtro de búsqueda por nombre o email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filtro por rol
        if ($request->filled('role')) {
            $query->where('role_id', $request->role);
        }

        // Filtro por estado
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active' ? 1 : 0);
        }

        $users = $query->latest()->paginate(10)->withQueryString();
        $roles = Role::all();
        
        return view('admin.users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role_id' => 'required|exists:roles,id',
            'is_active' => 'boolean',
            'access_expires_at' => 'nullable|date_format:Y-m-d\TH:i|after_or_equal:now',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = $request->has('is_active');
        $validated['email_verified_at'] = now();

        $user = User::create($validated);

        // Si es profesor invitado, crear registro en tabla teachers
        $role = Role::find($validated['role_id']);
        if ($role->slug === Role::PROFESOR_INVITADO) {
            \App\Modules\GestionAcademica\Models\Teacher::create([
                'user_id' => $user->id,
                'first_name' => explode(' ', $validated['name'])[0],
                'last_name' => implode(' ', array_slice(explode(' ', $validated['name']), 1)),
                'email' => $validated['email'],
                'is_guest' => true,
                'access_expires_at' => $validated['access_expires_at'] ?? null,
                'is_active' => $validated['is_active'],
            ]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario creado exitosamente (HU1).');
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role_id' => 'required|exists:roles,id',
            'is_active' => 'boolean',
            'access_expires_at' => 'nullable|date_format:Y-m-d\TH:i|after_or_equal:now',
        ]);

        // Solo actualizar password si se proporciona
        if ($request->filled('password')) {
            $request->validate([
                'password' => ['confirmed', Rules\Password::defaults()],
            ]);
            $validated['password'] = Hash::make($request->password);
        }

        $validated['is_active'] = $request->has('is_active');

        $user->update($validated);

        // Actualizar campos de profesor invitado si corresponde
        $role = Role::find($validated['role_id']);
        if ($role->slug === Role::PROFESOR_INVITADO && $user->teacher) {
            $user->teacher->update([
                'is_guest' => true,
                'access_expires_at' => $validated['access_expires_at'] ?? null,
            ]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario actualizado (HU1).');
    }

    public function destroy(User $user)
    {
        // No eliminar, solo desactivar
        $user->update(['is_active' => false]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario desactivado (HU1).');
    }
}