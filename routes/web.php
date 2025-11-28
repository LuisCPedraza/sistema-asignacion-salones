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
    
    // Rutas de registro
    Route::get('register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    
    // Ruta temporal sin middleware admin
    Route::middleware('auth')->prefix('admin')->group(function () {
        Route::get('/dashboard', function () {
            // Verificar manualmente si es admin
            if (!auth()->user()->hasRole('administrador')) {
                abort(403, 'Acceso denegado');
            }
            return view('admin.dashboard', ['user' => auth()->user()]);
        })->name('admin.dashboard');
    });
    
    // Rutas básicas de dashboard (sin middleware de roles específicos por ahora)
    Route::get('/academic/dashboard', function () {
        return view('academic.dashboard', ['user' => auth()->user()]);
    })->name('academic.dashboard');
    
    Route::get('/infraestructura/dashboard', function () {
        return view('infraestructura.dashboard', ['user' => auth()->user()]);
    })->name('infraestructura.dashboard');
    
    Route::get('/profesor/dashboard', function () {
        return view('profesor.dashboard', ['user' => auth()->user()]);
    })->name('profesor.dashboard');

    // Fallback dashboard por rol
    Route::get('/dashboard', function () {
        $user = auth()->user();
        
        if (!$user->role) {
            return redirect('/')->with('error', 'Usuario sin rol asignado');
        }

        return match ($user->role->slug) {
            Role::ADMINISTRADOR, Role::SECRETARIA_ADMINISTRATIVA => redirect()->route('admin.dashboard'),
            Role::COORDINADOR, Role::SECRETARIA_COORDINACION => redirect()->route('academic.dashboard'),
            Role::COORDINADOR_INFRAESTRUCTURA, Role::SECRETARIA_INFRAESTRUCTURA => redirect()->route('infraestructura.dashboard'),
            Role::PROFESOR, Role::PROFESOR_INVITADO => redirect()->route('profesor.dashboard'),
            default => redirect('/')->with('error', 'Rol no reconocido: ' . $user->role->slug)
        };
    })->name('dashboard');
    
    /*
    |--------------------------------------------------------------------------
    | Rutas del módulo Gestión Académica
    |--------------------------------------------------------------------------
    */
    require app_path('Modules/GestionAcademica/Routes/web.php'); 
    /*
    |--------------------------------------------------------------------------
    | Rutas del módulo Infraestructura
    |--------------------------------------------------------------------------
    */
    require app_path('Modules/Infraestructura/Routes/web.php');   
});