<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Redirección basada en rol (HU2)
        $role = Auth::user()->rol;  // Cambiado 'role' a 'rol' para coincidir con DB/model
        //dd($role);  // Debug temporal: Imprime rol y para (borra después)

        return match ($role) {
            'admin', 'superadmin' => redirect()->intended('/admin/dashboard'),  // Jerarquía: ambos a admin dashboard
            'profesor' => redirect()->intended('/profesor/perfil'),
            'coordinador', 'coordinador_infra' => redirect()->intended('/coordinador/asignaciones'),  // Jerarquía: ambos a coordinador
            'secretaria' => redirect()->intended('/profile'),  // Secretaría a profile (o /secretaria/dashboard si existe)
            default => redirect()->intended('/dashboard'),
        };
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
