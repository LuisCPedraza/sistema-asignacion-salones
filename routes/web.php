<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Profesor\ProfesorController;
use App\Http\Controllers\Profesor\AsistenciaController;
use App\Http\Controllers\Profesor\EstudianteController;
use App\Modules\Auth\Models\Role;
use App\Http\Middleware\AdminMiddleware;
use App\Modules\Visualization\Controllers\HorarioController;

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

    // Rutas del módulo de profesor
    Route::prefix('profesor')->name('profesor.')->middleware(['auth', 'role:profesor,profesor_invitado'])->group(function () {
        Route::get('/mis-cursos', [ProfesorController::class, 'misCursos'])->name('mis-cursos');
        Route::get('/curso/{assignmentId}', [ProfesorController::class, 'detalleCurso'])->name('detalle-curso');
        
        // Rutas de Control de Asistencias
        Route::prefix('asistencias')->name('asistencias.')->group(function () {
            Route::get('/', [AsistenciaController::class, 'index'])->name('index');
            Route::get('/tomar/{assignmentId}', [AsistenciaController::class, 'tomarAsistencia'])->name('tomar');
            Route::post('/guardar/{assignmentId}', [AsistenciaController::class, 'guardarAsistencia'])->name('guardar');
            Route::get('/historial/{assignmentId}', [AsistenciaController::class, 'historial'])->name('historial');
        });
        
        // Rutas de Gestión de Estudiantes
        Route::prefix('estudiantes')->name('estudiantes.')->group(function () {
            Route::get('/', [EstudianteController::class, 'index'])->name('index');
            Route::get('/crear', [EstudianteController::class, 'create'])->name('create');
            Route::post('/guardar', [EstudianteController::class, 'store'])->name('store');
            Route::get('/editar/{id}', [EstudianteController::class, 'edit'])->name('edit');
            Route::put('/actualizar/{id}', [EstudianteController::class, 'update'])->name('update');
            Route::delete('/eliminar/{id}', [EstudianteController::class, 'destroy'])->name('destroy');
        });
    });

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

// Alias en inglés requerido por las pruebas
Route::middleware(['auth', 'role:coordinador,secretaria_coordinacion'])->get('/visualization/malla-semestral', [HorarioController::class, 'mallaSemestral'])
    ->name('visualization.horario.malla-semestral');