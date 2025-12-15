<?php

namespace App\Modules\Visualization\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Asignacion\Models\Assignment;
use App\Models\Teacher; // Este sí está en app/Models
use App\Models\Career;
use App\Models\Semester;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Modules\Visualization\Exports\HorarioExport;

class HorarioController extends Controller
{
    /**
     * HU13: Horario semestral completo (Coordinadores)
     */
    public function semestral(Request $request)
    {
        $query = Assignment::with(['group', 'teacher', 'classroom', 'timeSlot', 'subject']);

        // Filtro por carrera (a través del grupo de estudiantes)
        if ($request->filled('career_id')) {
            $query->whereHas('group', function ($q) use ($request) {
                $q->whereHas('semester', function ($subQ) use ($request) {
                    $subQ->where('career_id', $request->career_id);
                });
            });
        }

        // Filtro por día
        if ($request->filled('day')) {
            $query->where('day', $request->day);
        }

        // Filtro por grupo
        if ($request->filled('group_id')) {
            $query->where('student_group_id', $request->group_id);
        }

        // Filtro por profesor
        if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }

        // Filtro por salón/aula
        if ($request->filled('classroom_id')) {
            $query->where('classroom_id', $request->classroom_id);
        }

        // Filtro por ubicación (localización del edificio del salón)
        if ($request->filled('location')) {
            $query->whereHas('classroom.building', function ($q) use ($request) {
                $q->where('location', 'like', '%' . $request->location . '%');
            });
        }

        $assignments = $query
            ->orderBy('day')
            ->orderBy('start_time')
            ->get();

        // Obtener datos para los dropdowns
        $careers = \App\Models\Career::where('is_active', true)
            ->orderBy('name')
            ->pluck('name', 'id');

        // Filtrar grupos según la carrera seleccionada
        $groups = \App\Modules\GestionAcademica\Models\StudentGroup::where('is_active', true);
        if ($request->filled('career_id')) {
            $groups = $groups->whereHas('semester', function ($q) use ($request) {
                $q->where('career_id', $request->career_id);
            });
        }
        $groups = $groups->pluck('name', 'id');

        // Filtrar profesores según asignaciones de la carrera/grupo seleccionados
        $teachers = \App\Models\Teacher::where('is_active', true);
        if ($request->filled('career_id') || $request->filled('group_id')) {
            $teachers = $teachers->whereHas('courseSchedules', function ($q) use ($request) {
                if ($request->filled('group_id')) {
                    $q->whereHas('semester.studentGroups', function ($subQ) use ($request) {
                        $subQ->where('student_groups.id', $request->group_id);
                    });
                } elseif ($request->filled('career_id')) {
                    $q->whereHas('semester', function ($subQ) use ($request) {
                        $subQ->where('career_id', $request->career_id);
                    });
                }
            });
        }
        $teachers = $teachers->get()
            ->mapWithKeys(function ($teacher) {
                return [$teacher->id => $teacher->first_name . ' ' . $teacher->last_name];
            });

        // Filtrar salones según carrera/grupo
        $classrooms = \App\Modules\Infraestructura\Models\Classroom::where('is_active', true);
        if ($request->filled('career_id') || $request->filled('group_id')) {
            $classroomIds = Assignment::query();
            if ($request->filled('group_id')) {
                $classroomIds = $classroomIds->where('student_group_id', $request->group_id);
            } elseif ($request->filled('career_id')) {
                $classroomIds = $classroomIds->whereHas('group', function ($q) use ($request) {
                    $q->whereHas('semester', function ($subQ) use ($request) {
                        $subQ->where('career_id', $request->career_id);
                    });
                });
            }
            $classroomIds = $classroomIds->distinct()->pluck('classroom_id');
            $classrooms = $classrooms->whereIn('id', $classroomIds);
        }
        $classrooms = $classrooms->pluck('name', 'id');

        // Ubicaciones: obtenerlas de los edificios relacionados con las aulas en las asignaciones
        $assignmentsWithBuilding = Assignment::with(['classroom.building', 'group'])->orderBy('day')->orderBy('start_time');
        if ($request->filled('career_id')) {
            $assignmentsWithBuilding->whereHas('group', function ($q) use ($request) {
                $q->whereHas('semester', function ($subQ) use ($request) {
                    $subQ->where('career_id', $request->career_id);
                });
            });
        }
        if ($request->filled('group_id')) {
            $assignmentsWithBuilding->where('student_group_id', $request->group_id);
        }
        $assignmentsForLocations = $assignmentsWithBuilding->get();

        $locations = $assignmentsForLocations
            ->map(function ($a) {
                return optional(optional($a->classroom)->building)->location;
            })
            ->filter(function ($loc) { return !empty($loc); })
            ->unique()
            ->sort()
            ->values();

        // Si por algún motivo viene vacío, hacer fallback a todos los edificios activos
        if ($locations->isEmpty()) {
            $locations = \App\Modules\Infraestructura\Models\Building::where('is_active', true)
                ->whereNotNull('location')
                ->where('location', '!=', '')
                ->pluck('location')
                ->unique()
                ->sort()
                ->values();
        }

        return view('visualization.horario-semestral', compact('assignments', 'careers', 'groups', 'teachers', 'classrooms', 'locations'));
    }

    /**
     * HU13: Malla horaria semestral (vista tipo cuadrícula)
     * Filtros: Carrera → Semestre → Grupo (A/B)
     */
    public function mallaSemestral(Request $request)
    {
        // 1. Obtener todas las carreras activas
        $careers = Career::where('is_active', true)
            ->with('semesters')
            ->get();

        // 2. Valores seleccionados
        $selectedCareeId = $request->get('career_id');
        $selectedSemesterId = $request->get('semester_id');
        $selectedGroupId = $request->get('group_id');

        // 3. Obtener semestres de la carrera seleccionada
        $semesters = collect();
        if ($selectedCareeId) {
            $career = Career::find($selectedCareeId);
            if ($career) {
                $semesters = $career->semesters()->orderBy('number')->get();
            }
        }

        // 4. Obtener grupos del semestre seleccionado
        $groups = collect();
        if ($selectedSemesterId) {
            $semester = \App\Models\Semester::find($selectedSemesterId);
            if ($semester) {
                // Obtener Grupo A (Diurno) y Grupo B (Nocturno) de este semestre
                $groups = $semester->studentGroups()
                    ->orderBy('group_type')
                    ->get();
            }
        }

        // 5. Construir bloques horarios según tipo de grupo seleccionado
        $timeBlocks = [];
        $scheduleType = 'day'; // Por defecto diurno

        if ($selectedGroupId) {
            $group = \App\Modules\GestionAcademica\Models\StudentGroup::find($selectedGroupId);
            if ($group) {
                $scheduleType = $group->schedule_type; // 'day' o 'night'
            }
        }

        // Bloques DIURNOS (8:00-18:00): Grupo A
        if ($scheduleType === 'day' || !$selectedGroupId) {
            $timeBlocks = [
                ['id' => 1, 'name' => 'Bloque 1', 'start' => '08:00', 'end' => '10:00'],
                ['id' => 2, 'name' => 'Bloque 2', 'start' => '10:00', 'end' => '12:00'],
                ['id' => 3, 'name' => 'Bloque 3', 'start' => '14:00', 'end' => '16:00'],
                ['id' => 4, 'name' => 'Bloque 4', 'start' => '16:00', 'end' => '18:00'],
            ];
        }

        // Bloques NOCTURNOS (18:00-22:00): Grupo B
        if ($scheduleType === 'night') {
            $timeBlocks = [
                ['id' => 5, 'name' => 'Bloque 5', 'start' => '18:00', 'end' => '20:00'],
                ['id' => 6, 'name' => 'Bloque 6', 'start' => '20:00', 'end' => '22:00'],
            ];
        }

        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];

        // 6. Obtener asignaciones filtradas
        $query = Assignment::with(['subject', 'teacher', 'classroom']);

        if ($selectedGroupId) {
            $query->where('student_group_id', $selectedGroupId);
        } elseif ($selectedSemesterId) {
            // Si se selecciona semestre pero no grupo, mostrar todos los grupos del semestre
            $groupIds = \App\Modules\GestionAcademica\Models\StudentGroup::where('semester_id', $selectedSemesterId)
                ->pluck('id');
            $query->whereIn('student_group_id', $groupIds);
        }

        $assignments = $query->orderBy('day')->orderBy('start_time')->get();

        // 7. Crear matriz de horario [bloque][día] = array de asignaciones
        $schedule = [];
        foreach ($timeBlocks as $block) {
            $schedule[$block['id']] = [];
            foreach ($days as $day) {
                $schedule[$block['id']][$day] = []; // Array para múltiples asignaciones
            }
        }

        // 8. Llenar matriz con asignaciones
        foreach ($assignments as $assignment) {
            $startTimeStr = is_object($assignment->start_time)
                ? $assignment->start_time->format('H:i')
                : substr((string)$assignment->start_time, 0, 5);

            // Encontrar bloque correspondiente
            foreach ($timeBlocks as $block) {
                if ($block['start'] === $startTimeStr) {
                    $schedule[$block['id']][$assignment->day][] = $assignment;
                    break;
                }
            }
        }

        return view('visualization.malla-semestral', compact(
            'schedule',
            'timeBlocks',
            'days',
            'careers',
            'semesters',
            'groups',
            'selectedCareeId',
            'selectedSemesterId',
            'selectedGroupId'
        ));
    }

    /**
     * HU13: Export horario semestral
     */
    public function exportSemestral(Request $request)
    {
        $query = Assignment::with(['group', 'teacher', 'classroom', 'timeSlot']);

        // Aplicar los mismos filtros que en la vista
        if ($request->filled('career_id')) {
            $query->whereHas('group', function ($q) use ($request) {
                $q->whereHas('semester', function ($subQ) use ($request) {
                    $subQ->where('career_id', $request->career_id);
                });
            });
        }

        if ($request->filled('day')) {
            $query->where('day', $request->day);
        }

        if ($request->filled('group_id')) {
            $query->where('student_group_id', $request->group_id);
        }

        if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }

        if ($request->filled('classroom_id')) {
            $query->where('classroom_id', $request->classroom_id);
        }

        if ($request->filled('location')) {
            $query->whereHas('classroom.building', function ($q) use ($request) {
                $q->where('location', 'like', '%' . $request->location . '%');
            });
        }

        $assignments = $query
            ->orderBy('day')
            ->orderBy('start_time')
            ->get();

        // Generar PDF
        $pdf = app('dompdf.wrapper')
            ->loadView('visualization.horario-semestral-pdf', ['assignments' => $assignments])
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isPhpEnabled', true);

        return $pdf->download('horario-semestral-' . now()->format('Y-m-d_His') . '.pdf');
    }

    /**
     * HU14: Horario personal (Profesores y Coordinadores)
     */
    public function personal(Request $request)
    {
        $user = Auth::user();
        
        // Si es coordinador, puede seleccionar qué profesor ver
        if ($user->hasRole('coordinador') || $user->hasRole('secretaria_coordinacion')) {
            $teacherId = $request->input('teacher_id');
            
            if ($teacherId) {
                $teacher = Teacher::findOrFail($teacherId);
            } else {
                // Si no hay teacher_id, mostrar lista de profesores para seleccionar
                $teachers = Teacher::with('user')->where('is_active', true)->get();
                return view('visualization.horario-personal-select', compact('teachers'));
            }
        } else {
            // Si es profesor, mostrar solo su horario
            $teacher = $user->teacher;

            if (!$teacher) {
                abort(404, 'No perfil de profesor asociado.');
            }
        }

        $assignments = Assignment::where('teacher_id', $teacher->id)
            ->with(['group', 'classroom', 'timeSlot'])
            ->orderBy('day')
            ->orderBy('start_time')
            ->get();

        return view('visualization.horario-personal', compact('assignments', 'teacher'));
    }

    /**
     * HU14: Export horario personal a PDF
     */
    public function exportPersonal(Request $request)
    {
        $teacherId = $request->input('teacher_id');
        
        // Si es coordinador y especifica teacher_id, usar ese; si no, usar el del usuario autenticado
        if ($teacherId && (auth()->user()->hasRole('coordinador') || auth()->user()->hasRole('secretaria_coordinacion'))) {
            $userId = Teacher::findOrFail($teacherId)->user_id;
        } else {
            $userId = auth()->id();
        }
        
        $export = new HorarioExport('personal', $userId);
        $pdf = $export->toPdf();
        $fileName = $export->getPdfFileName();
        
        return response()->streamDownload(
            function () use ($pdf) {
                echo $pdf;
            },
            $fileName,
            ['Content-Type' => 'application/pdf']
        );
    }
}