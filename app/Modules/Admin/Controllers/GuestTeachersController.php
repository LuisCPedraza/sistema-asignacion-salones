<?php

namespace App\Modules\Admin\Controllers;

use App\Events\GuestTeacherAccessChanged;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Modules\Auth\Models\Role;
use App\Modules\GestionAcademica\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class GuestTeachersController extends Controller
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

    /**
     * Mostrar dashboard de profesores invitados
     */
    public function index(Request $request)
    {
        $query = Teacher::where('is_guest', true)
            ->with('user')
            ->orderBy('access_expires_at', 'asc');

        // Filtro por estado
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where(function ($q) {
                    $q->whereNull('access_expires_at')
                      ->orWhere('access_expires_at', '>', now());
                });
            } elseif ($request->status === 'expired') {
                $query->where('access_expires_at', '<=', now());
            } elseif ($request->status === 'expiring_soon') {
                $query->whereBetween('access_expires_at', [
                    now(),
                    now()->addDays(7),
                ]);
            }
        }

        // Filtro por búsqueda
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            })->orWhere('email', 'like', "%{$search}%")
              ->orWhere('first_name', 'like', "%{$search}%")
              ->orWhere('last_name', 'like', "%{$search}%");
        }

        $guestTeachers = $query->paginate(10)->withQueryString();

        // Calcular estadísticas
        $stats = [
            'total' => Teacher::where('is_guest', true)->count(),
            'active' => Teacher::where('is_guest', true)->withValidAccess()->count(),
            'expired' => Teacher::where('is_guest', true)->expiredGuest()->count(),
            'expiring_soon' => Teacher::where('is_guest', true)
                ->whereBetween('access_expires_at', [now(), now()->addDays(7)])
                ->count(),
        ];

        return view('admin.guest-teachers.index', compact('guestTeachers', 'stats'));
    }

    /**
     * Mostrar detalles de un profesor invitado
     */
    public function show(Teacher $teacher)
    {
        if (!$teacher->is_guest) {
            abort(404, 'Profesor no es invitado');
        }

        $teacher->load('user', 'availabilities');

        return view('admin.guest-teachers.show', compact('teacher'));
    }

    /**
     * Mostrar formulario de creación de profesor invitado
     */
    public function create()
    {
        return view('admin.guest-teachers.create');
    }

    /**
     * Guardar nuevo profesor invitado
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'access_expires_at' => 'required|date_format:Y-m-d\TH:i|after_or_equal:now',
            'ip_address_allowed' => 'nullable|string|max:500',
        ]);

        try {
            // 1. Crear usuario con rol de profesor invitado
            $guestRole = Role::where('slug', Role::PROFESOR_INVITADO)->firstOrFail();
            
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role_id' => $guestRole->id,
                'is_active' => true,
                'email_verified_at' => now(),
            ]);

            // 2. Crear registro en tabla teachers
            $nameParts = explode(' ', $validated['name'], 2);
            $teacher = Teacher::create([
                'user_id' => $user->id,
                'first_name' => $nameParts[0],
                'last_name' => $nameParts[1] ?? '',
                'email' => $validated['email'],
                'specialty' => 'Profesor Invitado',
                'is_guest' => true,
                'is_active' => true,
                'access_expires_at' => $validated['access_expires_at'],
                'ip_address_allowed' => $validated['ip_address_allowed'] ?? null,
            ]);

            // 3. Disparar evento de auditoría
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

            return redirect()->route('admin.guest-teachers.show', $teacher)
                ->with('success', '✅ Profesor invitado creado exitosamente. Acceso expira: ' . $teacher->access_expires_at->format('d/m/Y H:i'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', '❌ Error al crear profesor invitado: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(Teacher $teacher)
    {
        if (!$teacher->is_guest) {
            abort(404, 'Profesor no es invitado');
        }

        $teacher->load('user');

        return view('admin.guest-teachers.edit', compact('teacher'));
    }

    /**
     * Actualizar profesor invitado
     */
    public function update(Request $request, Teacher $teacher)
    {
        if (!$teacher->is_guest) {
            abort(404, 'Profesor no es invitado');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $teacher->user_id,
            'access_expires_at' => 'required|date_format:Y-m-d\TH:i|after_or_equal:now',
            'ip_address_allowed' => 'nullable|string|max:500',
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        try {
            // Guardar datos antiguos para auditoría
            $oldData = [
                'access_expires_at' => $teacher->access_expires_at,
                'ip_address_allowed' => $teacher->ip_address_allowed,
            ];

            // 1. Actualizar usuario
            $teacher->user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);

            // Actualizar password si se proporciona
            if ($request->filled('password')) {
                $teacher->user->update([
                    'password' => Hash::make($validated['password']),
                ]);
            }

            // 2. Actualizar profesor invitado
            $nameParts = explode(' ', $validated['name'], 2);
            $teacher->update([
                'first_name' => $nameParts[0],
                'last_name' => $nameParts[1] ?? '',
                'email' => $validated['email'],
                'access_expires_at' => $validated['access_expires_at'],
                'ip_address_allowed' => $validated['ip_address_allowed'] ?? null,
            ]);

            // 3. Disparar evento de auditoría si hay cambios
            $newData = [
                'access_expires_at' => $teacher->access_expires_at,
                'ip_address_allowed' => $teacher->ip_address_allowed,
            ];

            if ($oldData !== $newData) {
                GuestTeacherAccessChanged::dispatch(
                    $teacher->user,
                    GuestTeacherAccessChanged::ACTION_UPDATED,
                    $oldData,
                    $newData,
                    auth()->user()
                );
            }

            return redirect()->route('admin.guest-teachers.show', $teacher)
                ->with('success', '✅ Profesor invitado actualizado. Nuevo acceso expira: ' . $teacher->access_expires_at->format('d/m/Y H:i'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', '❌ Error al actualizar: ' . $e->getMessage());
        }
    }

    /**
     * Revocar acceso inmediato de profesor invitado
     */
    public function revoke(Teacher $teacher)
    {
        if (!$teacher->is_guest) {
            abort(404, 'Profesor no es invitado');
        }

        try {
            $oldData = [
                'access_expires_at' => $teacher->access_expires_at,
            ];

            // Marcar como expirado (ahora mismo)
            $teacher->update([
                'access_expires_at' => now(),
            ]);

            // Disparar evento de auditoría
            GuestTeacherAccessChanged::dispatch(
                $teacher->user,
                GuestTeacherAccessChanged::ACTION_REVOKED,
                $oldData,
                ['access_expires_at' => now()],
                auth()->user()
            );

            return redirect()->route('admin.guest-teachers.index')
                ->with('success', '✅ Acceso revocado para ' . $teacher->user->name . '. Será efectivo inmediatamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', '❌ Error al revocar acceso: ' . $e->getMessage());
        }
    }
}
