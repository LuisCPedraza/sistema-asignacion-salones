<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calificar Actividad - {{ $activity->title }}</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,'Helvetica Neue',Arial,sans-serif; background:#f8fafc; color:#334155; }
        .header { background:linear-gradient(135deg,#6366f1 0%,#4f46e5 100%); color:#fff; padding:1.25rem 2rem; box-shadow:0 4px 20px rgba(99,102,241,0.3); }
        .header-top { display:flex; justify-content:space-between; align-items:center; margin-bottom:0.75rem; }
        .logo { font-size:1.5rem; font-weight:bold; }
        .user-info { display:flex; align-items:center; gap:1rem; }
        .btn-logout { background:rgba(255,255,255,0.2); color:#fff; padding:0.625rem 1.25rem; border:none; border-radius:8px; cursor:pointer; transition:all 0.3s; }
        .btn-logout:hover { background:rgba(255,255,255,0.3); }
        .header-info { display:flex; flex-wrap:wrap; gap:1.5rem; font-size:0.9rem; opacity:0.95; }
        .container { max-width:1400px; margin:0 auto; padding:2rem; }
        .breadcrumb { margin-bottom:1.5rem; font-size:0.9rem; }
        .breadcrumb a { color:#6366f1; text-decoration:none; }
        .breadcrumb a:hover { text-decoration:underline; }
        .page-header { background:#fff; padding:2rem; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.08); margin-bottom:2rem; border-left:5px solid #6366f1; }
        .page-header h1 { color:#1e293b; font-size:1.75rem; margin-bottom:0.5rem; }
        .page-header .activity-title { font-size:1.25rem; color:#6366f1; margin-bottom:1rem; font-weight:600; }
        .page-header p { color:#64748b; line-height:1.6; }
        .context-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(250px,1fr)); gap:1rem; margin-top:1rem; padding-top:1rem; border-top:1px solid #e2e8f0; }
        .context-item { display:flex; align-items:center; gap:0.5rem; font-size:0.9rem; color:#475569; }
        .context-icon { color:#6366f1; }
        .stats-row { display:grid; grid-template-columns:repeat(auto-fit,minmax(180px,1fr)); gap:1rem; margin-bottom:2rem; }
        .stat-box { background:#fff; padding:1.25rem; border-radius:10px; box-shadow:0 4px 15px rgba(0,0,0,0.08); text-align:center; }
        .stat-box .icon { font-size:2rem; margin-bottom:0.5rem; }
        .stat-box .number { font-size:1.75rem; font-weight:bold; color:#6366f1; }
        .stat-box .label { font-size:0.85rem; color:#64748b; margin-top:0.25rem; }
        .card { background:#fff; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.08); padding:1.5rem; }
        .card-title { font-size:1.1rem; font-weight:600; color:#1e293b; margin-bottom:1.25rem; padding-bottom:0.75rem; border-bottom:2px solid #e2e8f0; }
        table { width:100%; border-collapse:collapse; }
        thead { background:linear-gradient(135deg,#f8fafc 0%,#f1f5f9 100%); }
        th { padding:1rem 0.875rem; text-align:left; font-weight:600; color:#475569; font-size:0.9rem; border-bottom:2px solid #e2e8f0; }
        td { padding:1rem 0.875rem; border-bottom:1px solid #e2e8f0; color:#334155; }
        tbody tr:hover { background:#faf5ff; }
        .student-codigo { font-family:'Courier New',monospace; background:#f1f5f9; padding:0.25rem 0.5rem; border-radius:4px; font-size:0.85rem; }
        input[type="number"] { width:100%; max-width:130px; padding:0.625rem; border:2px solid #e2e8f0; border-radius:8px; font-size:0.95rem; transition:all 0.3s; }
        input[type="number"]:focus { outline:none; border-color:#6366f1; box-shadow:0 0 0 3px rgba(99,102,241,0.1); }
        textarea { width:100%; min-height:70px; padding:0.625rem; border:2px solid #e2e8f0; border-radius:8px; resize:vertical; font-family:inherit; font-size:0.9rem; transition:all 0.3s; }
        textarea:focus { outline:none; border-color:#6366f1; box-shadow:0 0 0 3px rgba(99,102,241,0.1); }
        .actions { margin-top:1.5rem; display:flex; justify-content:flex-end; gap:1rem; }
        .btn { background:linear-gradient(135deg,#6366f1,#4f46e5); color:#fff; border:none; padding:0.875rem 1.5rem; border-radius:10px; font-weight:600; cursor:pointer; transition:all 0.3s; font-size:0.95rem; }
        .btn:hover { transform:translateY(-2px); box-shadow:0 6px 20px rgba(99,102,241,0.3); }
        .btn-secondary { background:#64748b; color:#fff; padding:0.875rem 1.5rem; border-radius:10px; text-decoration:none; font-weight:600; display:inline-block; transition:all 0.3s; font-size:0.95rem; }
        .btn-secondary:hover { background:#475569; transform:translateY(-2px); }
        .success-message { background:#d1fae5; border:1px solid #10b981; color:#065f46; padding:1rem; border-radius:8px; margin-bottom:1.5rem; }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-top">
            <div class="logo">📝 Calificar Actividad</div>
            <div class="user-info">
                <span>{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn-logout">Cerrar Sesión</button>
                </form>
            </div>
        </div>
        @php
            $subject = $activity->assignment->subject;
            $group = $activity->assignment->group;
            $career = $subject->career ?? optional($group->semester)->career ?? $group->career;
            $semester = optional($group)->semester;
            $classroom = optional($activity->assignment)->classroom;
            $building = optional($classroom)->building;
            $turno = ($group->group_type ?? 'A') === 'B' ? 'Nocturno' : 'Diurno';
        @endphp
        <div class="header-info">
            <span>📚 {{ $subject->name ?? 'Materia' }}</span>
            @if(!empty($subject->code))
                <span>🔖 Código: {{ $subject->code }}</span>
            @endif
            @if($career)
                <span>🎯 {{ $career->name }}</span>
            @endif
            <span>👥 {{ $group->name ?? 'Grupo' }}</span>
            @if($semester)
                <span>📅 {{ $semester->name }}</span>
            @endif
            <span>🌙 {{ $turno }}</span>
        </div>
    </div>

    <div class="container">
        <div class="breadcrumb">
            <a href="{{ route('profesor.dashboard') }}">Dashboard</a> /
            <a href="{{ route('profesor.actividades.index') }}">Actividades</a> /
            <span>Calificar</span>
        </div>

        @if(session('success'))
            <div class="success-message">
                ✅ {{ session('success') }}
            </div>
        @endif

        <div class="page-header">
            <h1>📝 Calificación de Actividad</h1>
            <div class="activity-title">{{ $activity->title }}</div>
            @if(!empty($activity->description))
                <p>{{ $activity->description }}</p>
            @endif
            <div class="context-grid">
                <div class="context-item">
                    <span class="context-icon">📅</span>
                    <span>Fecha de entrega: <strong>{{ $activity->due_date ? $activity->due_date->format('d/m/Y') : 'Sin fecha límite' }}</strong></span>
                </div>
                <div class="context-item">
                    <span class="context-icon">⭐</span>
                    <span>Puntaje máximo: <strong>{{ $activity->max_score }} puntos</strong></span>
                </div>
                @if($classroom)
                    <div class="context-item">
                        <span class="context-icon">🏢</span>
                        <span>Aula: <strong>{{ $classroom->code }}{{ $building ? ' - '.$building->name : '' }}</strong></span>
                    </div>
                @endif
                <div class="context-item">
                    <span class="context-icon">👥</span>
                    <span>Estudiantes: <strong>{{ $activity->assignment->group->students->count() }}</strong></span>
                </div>
            </div>
        </div>

        @php
            $calificados = $grades->filter(fn($g) => !is_null($g->score))->count();
            $pendientes = $activity->assignment->group->students->count() - $calificados;
            $promedio = $grades->filter(fn($g) => !is_null($g->score))->avg('score');
        @endphp

        <div class="stats-row">
            <div class="stat-box">
                <div class="icon">✅</div>
                <div class="number">{{ $calificados }}</div>
                <div class="label">Calificados</div>
            </div>
            <div class="stat-box">
                <div class="icon">⏳</div>
                <div class="number">{{ $pendientes }}</div>
                <div class="label">Pendientes</div>
            </div>
            <div class="stat-box">
                <div class="icon">📊</div>
                <div class="number">{{ $promedio ? number_format($promedio, 1) : '-' }}</div>
                <div class="label">Promedio Actual</div>
            </div>
            <div class="stat-box">
                <div class="icon">🎯</div>
                <div class="number">{{ $activity->max_score }}</div>
                <div class="label">Puntaje Máximo</div>
            </div>
        </div>

        <div class="card">
            <div class="card-title">📋 Registro de Calificaciones</div>
            <form method="POST" action="{{ route('profesor.actividades.guardar-calificaciones', $activity->id) }}">
                @csrf
                <table>
                    <thead>
                        <tr>
                            <th style="width:5%">#</th>
                            <th style="width:30%">Estudiante</th>
                            <th style="width:15%">Código</th>
                            <th style="width:15%">Calificación</th>
                            <th style="width:35%">Retroalimentación</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($activity->assignment->group->students as $index => $student)
                            @php
                                $grade = $grades[$student->id] ?? null;
                            @endphp
                            <tr>
                                <td style="text-align:center; font-weight:600; color:#64748b;">{{ $index + 1 }}</td>
                                <td style="font-weight:500;">{{ $student->nombre_completo }}</td>
                                <td><span class="student-codigo">{{ $student->codigo }}</span></td>
                                <td>
                                    <input 
                                        type="number" 
                                        name="grades[{{ $student->id }}]" 
                                        step="0.1" 
                                        min="0" 
                                        max="{{ $maxScore }}" 
                                        value="{{ $grade->score ?? '' }}"
                                        placeholder="0 - {{ $maxScore }}"
                                    >
                                </td>
                                <td>
                                    <textarea 
                                        name="feedback[{{ $student->id }}]" 
                                        placeholder="Comentarios opcionales para el estudiante..."
                                    >{{ $grade->feedback ?? '' }}</textarea>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="actions">
                    <a class="btn-secondary" href="{{ route('profesor.actividades.index') }}">← Volver a Actividades</a>
                    <button type="submit" class="btn">💾 Guardar Calificaciones</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
