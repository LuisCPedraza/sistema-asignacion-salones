<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Modules\Auth\Models\Role;
use App\Http\Middleware\AdminMiddleware;

// Rutas públicas
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Autenticación
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    Route::get('register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // Admin dashboard (temporal)
    Route::middleware('auth')->prefix('admin')->group(function () {
        Route::get('/dashboard', function () {
            if (!auth()->user()->hasRole('administrador')) {
                abort(403, 'Acceso denegado');
            }
            return view('admin.dashboard', ['user' => auth()->user()]);
        })->name('admin.dashboard');
    });

    // Dashboards por rol
    Route::get('/academic/dashboard', fn() => view('academic.dashboard', ['user' => auth()->user()]))->name('academic.dashboard');
    Route::get('/infraestructura/dashboard', fn() => view('infraestructura.dashboard', ['user' => auth()->user()]))->name('infraestructura.dashboard');
    Route::get('/profesor/dashboard', fn() => view('profesor.dashboard', ['user' => auth()->user()]))->name('profesor.dashboard');

    // Fallback dashboard
    Route::get('/dashboard', function () {
        $user = auth()->user();
        if (!$user->role) return redirect('/')->with('error', 'Usuario sin rol asignado');

        return match ($user->role->slug) {
            'administrador', 'secretaria_administrativa' => redirect()->route('admin.dashboard'),
            'coordinador', 'secretaria_coordinacion' => redirect()->route('academic.dashboard'),
            'coordinador_infraestructura', 'secretaria_infraestructura' => redirect()->route('infraestructura.dashboard'),
            'profesor', 'profesor_invitado' => redirect()->route('profesor.dashboard'),
            default => redirect('/')->with('error', 'Rol no reconocido'),
        };
    })->name('dashboard');

// Módulos protegidos
    require app_path('Modules/GestionAcademica/Routes/web.php');
    require app_path('Modules/Infraestructura/Routes/web.php');
    require __DIR__.'/../app/Modules/Admin/Routes/web.php';

    // Módulo Asignación
    Route::prefix('asignacion')->name('asignacion.')->group(function () {
        require __DIR__.'/../app/Modules/Asignacion/Routes/web.php';
    });
});

// MÓDULO VISUALIZACIÓN: FUERA DEL middleware('auth'), pero dentro del prefix
Route::prefix('visualizacion')->name('visualizacion.')->group(function () {
    require __DIR__.'/../app/Modules/Visualization/Routes/web.php';
});