<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes Académicos</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,'Helvetica Neue',Arial,sans-serif; background:#f8fafc; color:#334155; }
        .header { background:linear-gradient(135deg,#8b5cf6 0%,#7c3aed 100%); color:#fff; padding:1.25rem 2rem; box-shadow:0 4px 20px rgba(139,92,246,0.3); }
        .header-top { display:flex; justify-content:space-between; align-items:center; margin-bottom:0.75rem; }
        .logo { font-size:1.5rem; font-weight:bold; }
        .user-info { display:flex; align-items:center; gap:1rem; }
        .btn-logout { background:rgba(255,255,255,0.2); color:#fff; padding:0.625rem 1.25rem; border:none; border-radius:8px; cursor:pointer; transition:all 0.3s; }
        .btn-logout:hover { background:rgba(255,255,255,0.3); }
        .header-info { display:flex; flex-wrap:wrap; gap:1.5rem; font-size:0.9rem; opacity:0.95; }
        .container { max-width:1400px; margin:0 auto; padding:2rem; }
        .breadcrumb { margin-bottom:1.5rem; font-size:0.9rem; }
        .breadcrumb a { color:#8b5cf6; text-decoration:none; }
        .breadcrumb a:hover { text-decoration:underline; }
        .page-header { background:#fff; padding:2rem; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.08); margin-bottom:2rem; border-left:5px solid #8b5cf6; }
        .page-header h1 { color:#1e293b; font-size:1.75rem; margin-bottom:0.5rem; }
        .page-header p { color:#64748b; font-size:1.05rem; line-height:1.6; }
        .stats-row { display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:1rem; margin-bottom:2rem; }
        .stat-box { background:#fff; padding:1.25rem; border-radius:10px; box-shadow:0 4px 15px rgba(0,0,0,0.08); text-align:center; }
        .stat-box .icon { font-size:2rem; margin-bottom:0.5rem; }
        .stat-box .number { font-size:1.75rem; font-weight:bold; color:#8b5cf6; }
        .stat-box .label { font-size:0.85rem; color:#64748b; margin-top:0.25rem; }
        .course-card { background:#fff; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.08); margin-bottom:1.5rem; overflow:hidden; transition:all 0.3s; }
        .course-card:hover { box-shadow:0 8px 25px rgba(0,0,0,0.12); transform:translateY(-2px); }
        .course-header { background:linear-gradient(135deg,#8b5cf6 0%,#7c3aed 100%); color:#fff; padding:1.5rem; }
        .course-title { font-size:1.3rem; font-weight:700; margin-bottom:0.5rem; }
        .course-code { font-family:'Courier New',monospace; background:rgba(255,255,255,0.2); padding:0.25rem 0.5rem; border-radius:4px; font-size:0.85rem; display:inline-block; margin-bottom:0.75rem; }
        .course-meta { display:flex; flex-wrap:wrap; gap:1.25rem; font-size:0.9rem; opacity:0.95; margin-top:0.75rem; }
        .context-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:1rem; padding:1.5rem; background:#f8fafc; border-top:1px solid #e2e8f0; }
        .context-item { display:flex; align-items:center; gap:0.5rem; font-size:0.9rem; color:#475569; }
        .context-icon { color:#8b5cf6; }
        .report-actions { display:grid; grid-template-columns:repeat(auto-fit,minmax(280px,1fr)); gap:1.25rem; padding:1.5rem; }
        .report-link { display:block; padding:1.5rem; border-radius:10px; text-decoration:none; transition:all 0.3s; border:2px solid; position:relative; overflow:hidden; }
        .report-link::before { content:''; position:absolute; top:0; left:0; width:0; height:100%; background:rgba(255,255,255,0.1); transition:width 0.3s; }
        .report-link:hover::before { width:100%; }
        .report-link:hover { transform:translateY(-3px); }
        .report-link.asistencias { background:#eff6ff; border-color:#3b82f6; color:#1e40af; }
        .report-link.asistencias:hover { box-shadow:0 6px 20px rgba(59,130,246,0.3); }
        .report-link.calificaciones { background:#f0fdf4; border-color:#10b981; color:#065f46; }
        .report-link.calificaciones:hover { box-shadow:0 6px 20px rgba(16,185,129,0.3); }
        .report-icon { font-size:2rem; margin-bottom:0.75rem; display:block; }
        .report-title { font-size:1.15rem; font-weight:700; margin-bottom:0.5rem; }
        .report-desc { font-size:0.9rem; opacity:0.8; line-height:1.5; }
        .empty-state { background:#fff; border:2px dashed #e2e8f0; border-radius:12px; padding:3rem 2rem; text-align:center; }
        .empty-icon { font-size:4rem; margin-bottom:1rem; opacity:0.4; }
        .empty-state p { color:#64748b; font-size:1.1rem; }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-top">
            <div class="logo">📊 Reportes Académicos</div>
            <div class="user-info">
                <span>{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn-logout">Cerrar Sesión</button>
                </form>
            </div>
        </div>
        <div class="header-info">
            <span>👨‍🏫 {{ $teacher->nombre }} {{ $teacher->apellido }}</span>
            <span>📧 {{ $teacher->email }}</span>
            <span>📚 {{ $assignments->count() }} {{ $assignments->count() === 1 ? 'curso' : 'cursos' }}</span>
            <span>👥 {{ $assignments->sum(fn($a) => $a->group->students->count()) }} estudiantes totales</span>
        </div>
    </div>

    <div class="container">
        <div class="breadcrumb">
            <a href="{{ route('profesor.dashboard') }}">Dashboard</a> /
            <span>Reportes</span>
        </div>

        <div class="page-header">
            <h1>📊 Centro de Reportes y Análisis</h1>
            <p>Accede a reportes detallados de asistencias y calificaciones de todos tus cursos. Genera estadísticas, visualiza el desempeño académico y exporta datos en formato PDF para análisis o presentaciones.</p>
        </div>

        @php
            $totalEstudiantes = $assignments->sum(fn($a) => $a->group->students->count());
            $totalCursos = $assignments->count();
            $totalHorasSemana = $assignments->sum(function($a) {
                $start = \Carbon\Carbon::parse($a->start_time);
                $end = \Carbon\Carbon::parse($a->end_time);
                return $start->diffInHours($end);
            });
        @endphp

        <div class="stats-row">
            <div class="stat-box">
                <div class="icon">📚</div>
                <div class="number">{{ $totalCursos }}</div>
                <div class="label">Cursos Activos</div>
            </div>
            <div class="stat-box">
                <div class="icon">👥</div>
                <div class="number">{{ $totalEstudiantes }}</div>
                <div class="label">Estudiantes Totales</div>
            </div>
            <div class="stat-box">
                <div class="icon">⏰</div>
                <div class="number">{{ $totalHorasSemana }}</div>
                <div class="label">Horas Semanales</div>
            </div>
            <div class="stat-box">
                <div class="icon">📈</div>
                <div class="number">{{ $totalCursos * 2 }}</div>
                <div class="label">Reportes Disponibles</div>
            </div>
        </div>

        @if($assignments->isEmpty())
            <div class="empty-state">
                <div class="empty-icon">📋</div>
                <p>No tienes cursos asignados para generar reportes.</p>
            </div>
        @else
            @foreach($assignments as $assignment)
                @php
                    $subject = $assignment->subject;
                    $group = $assignment->group;
                    $career = $subject->career ?? optional($group->semester)->career ?? $group->career;
                    $semester = optional($group)->semester;
                    $classroom = optional($assignment)->classroom;
                    $building = optional($classroom)->building;
                    $turno = ($group->group_type ?? 'A') === 'B' ? 'Nocturno' : 'Diurno';
                    $studentCount = $group->students->count();
                @endphp
                <div class="course-card">
                    <div class="course-header">
                        <div>
                            <div class="course-title">{{ $subject->name ?? 'Materia sin nombre' }}</div>
                            @if(!empty($subject->code))
                                <span class="course-code">{{ $subject->code }}</span>
                            @endif
                            <div class="course-meta">
                                @if($career)
                                    <span>🎯 {{ $career->name }}</span>
                                @endif
                                <span>👥 {{ $group->name ?? 'Grupo' }}</span>
                                @if($semester)
                                    <span>📅 {{ $semester->name }}</span>
                                @endif
                                <span>🌙 {{ $turno }}</span>
                                @if($subject && $subject->credits)
                                    <span>📖 {{ $subject->credits }} créditos</span>
                                @endif
                                <span>📆 {{ ucfirst($assignment->day) }}</span>
                                <span>🕐 {{ substr($assignment->start_time, 0, 5) }} - {{ substr($assignment->end_time, 0, 5) }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="context-grid">
                        <div class="context-item">
                            <span class="context-icon">👥</span>
                            <span><strong>{{ $studentCount }}</strong> estudiantes</span>
                        </div>
                        @if($classroom)
                            <div class="context-item">
                                <span class="context-icon">🏢</span>
                                <span>Aula: <strong>{{ $classroom->code }}</strong></span>
                            </div>
                        @endif
                        @if($building)
                            <div class="context-item">
                                <span class="context-icon">🏛️</span>
                                <span>Edificio: <strong>{{ $building->name }}</strong></span>
                            </div>
                        @endif
                        <div class="context-item">
                            <span class="context-icon">⏱️</span>
                            <span><strong>{{ \Carbon\Carbon::parse($assignment->start_time)->diffInHours(\Carbon\Carbon::parse($assignment->end_time)) }}</strong> horas por sesión</span>
                        </div>
                    </div>

                    <div class="report-actions">
                        <a href="{{ route('profesor.reportes.asistencias', $assignment->id) }}" 
                           class="report-link asistencias">
                            <span class="report-icon">📋</span>
                            <div class="report-title">Reporte de Asistencias</div>
                            <div class="report-desc">Consulta el control y estadísticas de presencia de estudiantes. Incluye porcentajes, totales y gráficos de tendencias.</div>
                        </a>

                        <a href="{{ route('profesor.reportes.actividades', $assignment->id) }}"
                           class="report-link calificaciones">
                            <span class="report-icon">📊</span>
                            <div class="report-title">Reporte de Calificaciones</div>
                            <div class="report-desc">Actividades, evaluaciones y desempeño académico. Visualiza promedios, distribución de notas y análisis detallado.</div>
                        </a>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</body>
</html>
