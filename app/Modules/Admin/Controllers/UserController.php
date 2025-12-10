<?php

namespace App\Modules\Admin\Controllers;

use App\Events\GuestTeacherAccessChanged;
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
            'ip_address_allowed' => 'nullable|string|max:500', // Ej: "192.168.1.*,10.0.0.*"
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = $request->has('is_active');
        $validated['email_verified_at'] = now();

        $user = User::create($validated);

        // Si es profesor invitado, crear registro en tabla teachers
        $role = Role::find($validated['role_id']);
        if ($role->slug === Role::PROFESOR_INVITADO) {
            $teacher = \App\Modules\GestionAcademica\Models\Teacher::create([
                'user_id' => $user->id,
                'first_name' => explode(' ', $validated['name'])[0],
                'last_name' => implode(' ', array_slice(explode(' ', $validated['name']), 1)),
                'email' => $validated['email'],
                'specialty' => 'Profesor Invitado',
                'is_guest' => true,
                'access_expires_at' => $validated['access_expires_at'] ?? null,
                'ip_address_allowed' => $validated['ip_address_allowed'] ?? null,
                'is_active' => $validated['is_active'],
            ]);

            // Disparar evento de auditoría
            GuestTeacherAccessChanged::dispatch(
                $user,
                GuestTeacherAccessChanged::ACTION_CREATED,
                null,
                [
                    'access_expires_at' => $teacher->access_expires_at,
                    'ip_address_allowed' => $teacher->ip_address_allowed,
                ],
                auth()->user()
            );
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
            'ip_address_allowed' => 'nullable|string|max:500', // Ej: "192.168.1.*,10.0.0.*"
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
            $oldData = [
                'access_expires_at' => $user->teacher->getOriginal('access_expires_at'),
                'ip_address_allowed' => $user->teacher->getOriginal('ip_address_allowed'),
            ];

            $user->teacher->update([
                'is_guest' => true,
                'access_expires_at' => $validated['access_expires_at'] ?? null,
                'ip_address_allowed' => $validated['ip_address_allowed'] ?? null,
            ]);

            $newData = [
                'access_expires_at' => $user->teacher->access_expires_at,
                'ip_address_allowed' => $user->teacher->ip_address_allowed,
            ];

            // Disparar evento de auditoría si hay cambios
            if ($oldData !== $newData) {
                GuestTeacherAccessChanged::dispatch(
                    $user,
                    GuestTeacherAccessChanged::ACTION_UPDATED,
                    $oldData,
                    $newData,
                    auth()->user()
                );
            }
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

    /**
     * Revocar acceso de profesor invitado
     */
    public function revokeGuestAccess(User $user)
    {
        // Verificar que sea profesor invitado
        $role = $user->role;
        if ($role->slug !== Role::PROFESOR_INVITADO) {
            return redirect()->route('admin.users.edit', $user)
                ->with('error', 'Solo se puede revocar acceso a profesores invitados.');
        }

        if (!$user->teacher) {
            return redirect()->route('admin.users.edit', $user)
                ->with('error', 'No hay registro de profesor para este usuario.');
        }

        $oldData = [
            'access_expires_at' => $user->teacher->access_expires_at,
            'is_guest' => $user->teacher->is_guest,
        ];

        // Revocar acceso
        $user->teacher->update([
            'is_guest' => false,
            'access_expires_at' => null,
        ]);

        $newData = [
            'access_expires_at' => null,
            'is_guest' => false,
        ];

        // Disparar evento de auditoría
        GuestTeacherAccessChanged::dispatch(
            $user,
            GuestTeacherAccessChanged::ACTION_REVOKED,
            $oldData,
            $newData,
            auth()->user()
        );

        return redirect()->route('admin.users.edit', $user)
            ->with('success', 'Acceso de profesor invitado revocado exitosamente.');
    }
}