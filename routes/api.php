<?php

use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\N8nWebhookController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Career;
use App\Models\Subject;
use App\Modules\GestionAcademica\Models\StudentGroup;
use App\Modules\Asignacion\Models\Assignment;
use App\Modules\Infraestructura\Models\Classroom;
use App\Modules\Infraestructura\Models\Reservation;
use App\Modules\GestionAcademica\Models\TeacherAvailability;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Rutas API para integración con n8n y servicios externos
|
*/

// Rutas para n8n (requieren token de API)
Route::prefix('webhooks/n8n')->middleware('validate.n8n.token')->group(function () {
    // Webhook principal para recibir eventos de n8n
    Route::post('/notify', [N8nWebhookController::class, 'notify']);
    
    // Endpoints para obtener datos (consultados por n8n)
    Route::get('/next-day-assignments', [N8nWebhookController::class, 'getNextDayAssignments']);
    Route::get('/conflicts', [N8nWebhookController::class, 'getConflicts']);
    Route::get('/expiring-guests', [N8nWebhookController::class, 'getExpiringGuests']);
    
    // Endpoint para Chatbot: estadísticas generales del sistema
    Route::get('/stats', function (Request $request) {
        return response()->json([
            'teachers' => [
                'total' => Teacher::count(),
                'active' => Teacher::where('estado', 'activo')->count(),
                'guest' => Teacher::where('is_guest', true)->count(),
            ],
            'students' => [
                'total' => Student::count() ?? 0,
            ],
            'careers' => Career::select('id', 'name')
                ->withCount('studentGroups')
                ->get(),
            'groups' => [
                'total' => StudentGroup::count(),
                'list' => StudentGroup::select('id', 'name', 'student_count', 'number_of_students')
                    ->orderBy('name')
                    ->get(),
            ],
            'summary' => [
                'total_professors' => Teacher::count(),
                'total_students' => Student::count() ?? 0,
                'total_careers' => Career::count(),
                'total_groups' => StudentGroup::count(),
            ],
        ]);
    });

    // Endpoint: Información de salones/aulas
    Route::get('/classrooms', function (Request $request) {
        return response()->json([
            'total' => Classroom::count(),
            'active' => Classroom::where('estado', 'activo')->count(),
            'classrooms' => Classroom::select('id', 'name', 'capacity', 'building', 'location', 'estado')
                ->orderBy('building')
                ->orderBy('name')
                ->get(),
            'by_building' => Classroom::select('building', DB::raw('count(*) as total'))
                ->groupBy('building')
                ->get(),
        ]);
    });

    // Endpoint: Asignaciones actuales
    Route::get('/assignments', function (Request $request) {
        $assignments = Assignment::with([
            'teacher:id,first_name,last_name',
            'classroom:id,name,building',
            'subject:id,name'
        ])->get();

        return response()->json([
            'total' => $assignments->count(),
            'assignments' => $assignments->map(function ($assignment) {
                return [
                    'id' => $assignment->id,
                    'teacher' => $assignment->teacher ? 
                        ($assignment->teacher->first_name . ' ' . $assignment->teacher->last_name) : 'Sin asignar',
                    'group_id' => $assignment->student_group_id ?? null,
                    'classroom' => $assignment->classroom->name ?? 'Sin salón',
                    'building' => $assignment->classroom->building ?? null,
                    'subject' => $assignment->subject->name ?? 'Sin materia',
                ];
            }),
        ]);
    });

    // Endpoint: Materias/Asignaturas
    Route::get('/subjects', function (Request $request) {
        return response()->json([
            'total' => Subject::count(),
            'subjects' => Subject::select('id', 'name', 'code')
                ->orderBy('name')
                ->get(),
        ]);
    });

    // Endpoint: Búsqueda flexible de materias por texto
    Route::get('/subjects/search', function (Request $request) {
        $query = trim($request->query('q', ''));
        if ($query === '') {
            return response()->json([
                'success' => false,
                'message' => 'Parámetro q requerido',
                'results' => [],
            ], 400);
        }

        // Coincidencia por LIKE en minúsculas
        $like = '%' . strtolower($query) . '%';
        $results = Subject::select('id', 'name', 'code')
            ->whereRaw('LOWER(name) LIKE ?', [$like])
            ->orWhereRaw('LOWER(code) LIKE ?', [$like])
            ->orderBy('name')
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'query' => $query,
            'count' => $results->count(),
            'results' => $results,
        ]);
    });

    // Endpoint: Profesores detallados
    Route::get('/teachers', function (Request $request) {
        $teachers = Teacher::with(['user:id,email'])
            ->get()
            ->map(function ($teacher) {
                return [
                    'id' => $teacher->id,
                    'name' => $teacher->first_name . ' ' . $teacher->last_name,
                    'email' => $teacher->user->email ?? null,
                    'estado' => $teacher->estado,
                    'is_guest' => $teacher->is_guest,
                    'access_expires_at' => $teacher->access_expires_at,
                ];
            });

        return response()->json([
            'total' => $teachers->count(),
            'active' => $teachers->where('estado', 'activo')->count(),
            'guest' => $teachers->where('is_guest', true)->count(),
            'teachers' => $teachers,
        ]);
    });

    // Endpoint: Disponibilidad de profesores
    Route::get('/teacher-availability', function (Request $request) {
        $teacherId = $request->query('teacher_id');

        $query = TeacherAvailability::with('teacher:id,first_name,last_name');
        if ($teacherId) {
            $query->where('teacher_id', $teacherId);
        }

        $availabilities = $query->get()->map(function ($avail) {
            return [
                'teacher' => $avail->teacher ?
                    $avail->teacher->first_name . ' ' . $avail->teacher->last_name : null,
                'day_of_week' => $avail->day_of_week,
                'start_time' => $avail->start_time,
                'end_time' => $avail->end_time,
            ];
        });

        return response()->json([
            'total' => $availabilities->count(),
            'availabilities' => $availabilities,
        ]);
    });

    // Endpoint: Reservas de salones
    Route::get('/reservations', function (Request $request) {
        $reservations = Reservation::with([
            'classroom:id,name,building',
            'user:id,name,email'
        ])->get();

        return response()->json([
            'total' => $reservations->count(),
            'pending' => $reservations->where('status', 'pendiente')->count(),
            'approved' => $reservations->where('status', 'aprobada')->count(),
            'reservations' => $reservations->map(function ($reservation) {
                return [
                    'id' => $reservation->id,
                    'classroom' => $reservation->classroom->name ?? null,
                    'building' => $reservation->classroom->building ?? null,
                    'user' => $reservation->user->name ?? null,
                    'date' => $reservation->date,
                    'start_time' => $reservation->start_time,
                    'end_time' => $reservation->end_time,
                    'status' => $reservation->status,
                    'purpose' => $reservation->purpose,
                ];
            }),
        ]);
    });

    // Endpoint: Carreras con detalles completos
    Route::get('/careers', function (Request $request) {
        $careers = Career::with(['studentGroups', 'semesters'])->get();
        return response()->json([
            'total' => $careers->count(),
            'careers' => $careers->map(function ($career) {
                return [
                    'id' => $career->id,
                    'name' => $career->name,
                    'total_groups' => $career->studentGroups->count(),
                    'total_semesters' => $career->semesters->count(),
                    'groups' => $career->studentGroups->map(function ($g) {
                        return [
                            'id' => $g->id,
                            'name' => $g->name,
                            'student_count' => $g->student_count ?? null,
                        ];
                    }),
                    'semesters' => $career->semesters->map(function ($s) {
                        return [
                            'id' => $s->id,
                            'name' => $s->name,
                        ];
                    }),
                ];
            }),
        ]);
    });

    // Endpoint: Obtener semestres de una carrera específica
    Route::get('/careers/{career}/semesters', function (Request $request, $careerId) {
        $career = Career::with('semesters')->find($careerId);
        
        if (!$career) {
            return response()->json([
                'error' => 'Carrera no encontrada'
            ], 404);
        }
        
        return response()->json([
            'career_id' => $career->id,
            'career_name' => $career->name,
            'total' => $career->semesters->count(),
            'semesters' => $career->semesters->map(function ($semester) {
                return [
                    'id' => $semester->id,
                    'number' => $semester->number,
                    'description' => $semester->description,
                    'is_active' => $semester->is_active,
                ];
            }),
        ]);
    });

    // Endpoint: Estadísticas agregadas por carrera
    Route::get('/stats-by-career', function (Request $request) {
        $careers = Career::with(['studentGroups', 'semesters'])->get();
        return response()->json([
            'total_careers' => $careers->count(),
            'stats' => $careers->map(function ($career) {
                $totalStudents = $career->studentGroups->sum(function ($group) {
                    return $group->student_count ?? $group->number_of_students ?? 0;
                });

                return [
                    'career_name' => $career->name,
                    'total_groups' => $career->studentGroups->count(),
                    'total_semesters' => $career->semesters->count(),
                    'total_students' => $totalStudents,
                    'avg_students_per_group' => $career->studentGroups->count() > 0
                        ? round($totalStudents / $career->studentGroups->count(), 1)
                        : 0,
                ];
            }),
        ]);
    });

    // Endpoint: Estadísticas agregadas por edificio
    Route::get('/stats-by-building', function (Request $request) {
        $classrooms = Classroom::select('building', DB::raw('count(*) as total_rooms'),
                                                      DB::raw('sum(capacity) as total_capacity'))
            ->groupBy('building')
            ->get();

        return response()->json([
            'total_buildings' => $classrooms->count(),
            'stats' => $classrooms->map(function ($building) {
                return [
                    'building_name' => $building->building ?? 'Sin edificio',
                    'total_classrooms' => $building->total_rooms,
                    'total_capacity' => $building->total_capacity,
                ];
            }),
        ]);
    });

    // Endpoint: Estadísticas de distribución de estudiantes por semestre
    Route::get('/stats-by-semester', function (Request $request) {
        $careers = Career::with(['semesters.studentGroups'])->get();
        $semesterStats = [];
        foreach ($careers as $career) {
            foreach ($career->semesters as $semester) {
                $totalStudents = $semester->studentGroups->sum(function ($group) {
                    return $group->student_count ?? $group->number_of_students ?? 0;
                });

                $semesterStats[] = [
                    'career_name' => $career->name,
                    'semester_name' => $semester->name,
                    'total_groups' => $semester->studentGroups->count(),
                    'total_students' => $totalStudents,
                ];
            }
        }

        return response()->json([
            'total_semesters' => count($semesterStats),
            'stats' => $semesterStats,
        ]);
    });
});

// ========================================================
// CHATBOT N8N + SQLITE
// ========================================================

// 1. Endpoint principal: enviar mensaje al chatbot (n8n)
Route::post('/chatbot/message', function (Request $request) {
    $message = $request->input('message');
    $sessionId = $request->input('session_id', uniqid());

    if (!$message) {
        return response()->json(['error' => 'message is required'], 400);
    }

    try {
        $resp = Http::timeout(15)->post(env('N8N_WEBHOOK_CHATBOT'), [
            'message' => $message,
            'session_id' => $sessionId,
        ]);

        if (!$resp->successful()) {
            return response()->json([
                'error' => 'n8n returned an error',
                'status' => $resp->status(),
                'body' => $resp->json(),
            ], 502);
        }

        return response()->json($resp->json());
    } catch (\Throwable $e) {
        return response()->json([
            'error' => 'failed to reach n8n',
            'message' => $e->getMessage(),
        ], 502);
    }
});

// 2. Endpoint interno: buscar en base de conocimiento (llamado desde n8n)
Route::post('/chatbot/search-knowledge', function (Request $request) {
    $query = $request->input('query');

    $results = DB::table('chat_knowledge_base')
        ->whereRaw('LOWER(question) LIKE ?', ['%' . strtolower($query) . '%'])
        ->orderBy('usage_count', 'desc')
        ->limit(5)
        ->get();

    return response()->json([
        'found' => $results->isNotEmpty(),
        'results' => $results,
        'best_match' => $results->first()
    ]);
});

// 3. Endpoint interno: guardar conversación (llamado desde n8n)
Route::post('/chatbot/save-conversation', function (Request $request) {
    $sessionId = $request->input('session_id');
    $userMessage = $request->input('user_message');
    $botMessage = $request->input('bot_message');

    // Crear o encontrar conversación
    $conversation = DB::table('chat_conversations')
        ->where('session_id', $sessionId)
        ->first();

    if (!$conversation) {
        DB::table('chat_conversations')->insert([
            'session_id' => $sessionId,
            'last_activity' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $conversation = DB::table('chat_conversations')
            ->where('session_id', $sessionId)
            ->first();
    } else {
        // Actualizar última actividad
        DB::table('chat_conversations')
            ->where('id', $conversation->id)
            ->update(['last_activity' => now()]);
    }

    // Guardar mensajes
    DB::table('chat_messages')->insert([
        ['conversation_id' => $conversation->id, 'sender' => 'user', 'message' => $userMessage, 'created_at' => now(), 'updated_at' => now()],
        ['conversation_id' => $conversation->id, 'sender' => 'bot', 'message' => $botMessage, 'created_at' => now(), 'updated_at' => now()],
    ]);

    return response()->json(['success' => true, 'conversation_id' => $conversation->id]);
});
