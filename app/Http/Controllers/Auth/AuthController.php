<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Modules\Auth\Models\Role;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegisterForm()
    {
        // Ya no pasamos roles al formulario de registro
        return view('auth.register');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Verificar acceso (de modelo User)
            if (!$user->canAccessSystem()) {
                Auth::logout();
                return back()->withErrors(['email' => 'Tu cuenta está pendiente de aprobación. Por favor, contacta al administrador.']);
            }

            // ← AGREGAR ESTA VERIFICACIÓN NUEVA
            // Verificar acceso temporal expirado
            if ($user->temporary_access && $user->isTemporaryAccessExpired()) {
                Auth::logout();
                return back()->withErrors(['email' => 'Tu acceso temporal ha expirado. Por favor, contacta al administrador.']);
            }

            // Redirección dinámica por rol (HU2)
            return match ($user->role->slug) {
                Role::ADMINISTRADOR, Role::SECRETARIA_ADMINISTRATIVA => redirect()->route('admin.dashboard'),
                Role::COORDINADOR, Role::SECRETARIA_COORDINACION => redirect()->route('academic.dashboard'),
                Role::COORDINADOR_INFRAESTRUCTURA, Role::SECRETARIA_INFRAESTRUCTURA => redirect()->route('infraestructura.dashboard'),
                Role::PROFESOR, Role::PROFESOR_INVITADO => redirect()->route('profesor.dashboard'),
                default => redirect()->route('dashboard')
            };
        }

        return back()->withErrors([
            'email' => 'Credenciales inválidas.',
        ])->onlyInput('email');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        \Log::info('Attempting to create user', $request->all());

        try {
            // Crear el usuario sin rol asignado (pendiente de aprobación)
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => null, // Sin rol asignado inicialmente
                'is_active' => false, // Requiere activación por administrador
                'temporary_access' => false,
                'access_expires_at' => null,
            ]);

            \Log::info('User created successfully', $user->toArray());
        } catch (\Exception $e) {
            \Log::error('Error creating user: ' . $e->getMessage());
            throw $e;
        }

        // Redirigir con mensaje de éxito
        return redirect()->route('login')->with('success', 'Registro exitoso. Tu cuenta está pendiente de aprobación por un administrador. Te contactaremos cuando tu cuenta esté activa.');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}