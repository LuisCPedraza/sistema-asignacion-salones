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

    public function index()
    {
        $users = User::with('role')->latest()->paginate(10);
        return view('admin.users.index', compact('users'));
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
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = $request->has('is_active');
        $validated['email_verified_at'] = now();

        User::create($validated);

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