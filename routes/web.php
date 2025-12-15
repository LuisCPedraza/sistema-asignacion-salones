<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Profesor\ProfesorController;
use App\Http\Controllers\Profesor\AsistenciaController;
use App\Http\Controllers\Profesor\EstudianteController;
use App\Http\Controllers\Profesor\ActividadController;
use App\Http\Controllers\Profesor\ReporteController;
use App\Http\Controllers\Profesor\HorarioController as ProfesorHorarioController;
use App\Http\Controllers\CareerController;
use App\Http\Controllers\SemesterController;
use App\Http\Controllers\SubjectController;
use App\Modules\Auth\Models\Role;
use App\Http\Middleware\AdminMiddleware;
use App\Modules\Visualization\Controllers\HorarioController;

// Rutas públicas
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Chatbot público: embebido del chat Hosted de n8n
Route::get('/chatbot', function () {
    // Opción A: Vista embebida
    return view('chatbot_hosted');
    // Opción B (alternativa): redirección directa al Hosted
    // return redirect()->away(env('N8N_WEBHOOK_CHATBOT'));
})->name('chatbot');

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
    Route::get('/profesor/dashboard', function() {
        $user = auth()->user();
        \Log::info('Dashboard - User ID: ' . $user->id . ', Teacher ID: ' . ($user->teacher_id ?? 'NULL'));
        
        $teacher = $user->teacher_id ? \App\Models\Teacher::find($user->teacher_id) : null;
        \Log::info('Dashboard - Teacher found: ' . ($teacher ? 'YES (ID: '.$teacher->id.')' : 'NO'));
        
        $assignments = $teacher ? \App\Modules\Asignacion\Models\Assignment::where('teacher_id', $teacher->id)
            ->with(['subject', 'group', 'timeSlot', 'classroom'])
            ->get() : collect();
        
        \Log::info('Dashboard - Assignments count: ' . $assignments->count());
        
        $totalHours = $assignments->sum(function($a) {
            if (!$a->start_time || !$a->end_time) return 0;
            return \Carbon\Carbon::parse($a->start_time)->diffInHours(\Carbon\Carbon::parse($a->end_time));
        });
        
        $subjects = $assignments->pluck('subject')->filter()->unique('id');
        $groups = $assignments->pluck('group')->filter()->unique('id');
        $totalStudents = $groups->sum('number_of_students');
        
        \Log::info('Dashboard - Stats: Subjects='.$subjects->count().', Hours='.$totalHours.', Students='.$totalStudents.', Groups='.$groups->count());
        
        return view('profesor.dashboard', [
            'user' => $user,
            'teacher' => $teacher,
            'assignments' => $assignments,
            'totalSubjects' => $subjects->count(),
            'totalHours' => $totalHours,
            'totalStudents' => $totalStudents,
            'totalGroups' => $groups->count()
        ]);
    })->name('profesor.dashboard');

    // Rutas de Gestión Académica (Carreras, Semestres, Materias)
    Route::middleware('role:coordinador,secretaria_coordinacion')->group(function () {
        Route::resource('careers', CareerController::class);
        Route::resource('semesters', SemesterController::class);
        Route::resource('subjects', SubjectController::class);
    });

    // Rutas del módulo de profesor
    Route::prefix('profesor')->name('profesor.')->middleware(['auth', 'role:profesor,profesor_invitado'])->group(function () {
        // Alias: mis-cursos redirige al horario (la vista principal)
        Route::get('/mis-cursos', fn() => redirect()->route('profesor.horario'))->name('mis-cursos');
        Route::get('/curso/{assignmentId}', [ProfesorController::class, 'detalleCurso'])->name('detalle-curso');
        
        // Ruta para ver el horario del profesor y exportar a PDF
        Route::get('/horario', [ProfesorHorarioController::class, 'index'])->name('horario');
        Route::get('/horario/pdf', [ProfesorHorarioController::class, 'exportPdf'])->name('horario.pdf');
        
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

        // Rutas de Actividades y Calificaciones
        Route::prefix('actividades')->name('actividades.')->group(function () {
            Route::get('/', [ActividadController::class, 'index'])->name('index');
            Route::get('/crear', [ActividadController::class, 'create'])->name('create');
            Route::post('/guardar', [ActividadController::class, 'store'])->name('store');
            Route::get('/{id}/calificaciones', [ActividadController::class, 'calificar'])->name('calificar');
            Route::post('/{id}/calificaciones', [ActividadController::class, 'guardarCalificaciones'])->name('guardar-calificaciones');
        });

        // Rutas de Reportes Académicos
        Route::prefix('reportes')->name('reportes.')->group(function () {
            Route::get('/', [ReporteController::class, 'index'])->name('index');
            Route::get('/asistencias/{assignmentId}', [ReporteController::class, 'asistencias'])->name('asistencias');
            Route::get('/asistencias/{assignmentId}/pdf', [ReporteController::class, 'exportAsistenciasPdf'])->name('asistencias.pdf');
            Route::get('/actividades/{assignmentId}', [ReporteController::class, 'actividades'])->name('actividades');
            Route::get('/actividades/{assignmentId}/pdf', [ReporteController::class, 'exportActividadesPdf'])->name('actividades.pdf');
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