<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if (!$user->canAccessSystem()) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Su cuenta no tiene acceso al sistema o ha expirado.',
                ]);
            }

            $request->session()->regenerate();

            return $this->redirectToRoleDashboard($user);
        }

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no son válidas.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    private function redirectToRoleDashboard(User $user)
    {
        return match ($user->role->slug) {
            'administrador', 'secretaria_administrativa' => redirect('/admin/dashboard'),
            'coordinador', 'secretaria_coordinacion' => redirect('/coordinador/dashboard'),
            'coordinador_infraestructura', 'secretaria_infraestructura' => redirect('/infraestructura/dashboard'),
            'profesor', 'profesor_invitado' => redirect('/profesor/dashboard'),
            default => redirect('/dashboard'),
        };
    }
}
