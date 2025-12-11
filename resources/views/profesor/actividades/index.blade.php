<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actividades y Calificaciones</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif; background: #f8fafc; color: #0f172a; margin: 0; }
        .header { background: linear-gradient(135deg, #2563eb, #1d4ed8); color: #fff; padding: 1.25rem 2rem; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 20px rgba(37,99,235,0.25); }
        .container { max-width: 1200px; margin: 0 auto; padding: 2rem; }
        .breadcrumb { margin-bottom: 1rem; font-size: 0.95rem; }
        .breadcrumb a { color: #2563eb; text-decoration: none; }
        .breadcrumb a:hover { text-decoration: underline; }
        .assignment-card { background: #fff; border-radius: 12px; box-shadow: 0 6px 18px rgba(15,23,42,0.06); padding: 1.5rem; margin-bottom: 1.5rem; border-left: 4px solid #2563eb; }
        .card-header { display: flex; justify-content: space-between; align-items: center; gap: 1rem; flex-wrap: wrap; }
        .title { font-size: 1.2rem; font-weight: 700; color: #0f172a; }
        .meta { color: #475569; font-size: 0.95rem; }
        .btn { display: inline-block; background: linear-gradient(135deg, #10b981, #059669); color: #fff; padding: 0.7rem 1.1rem; border-radius: 10px; text-decoration: none; font-weight: 600; box-shadow: 0 6px 18px rgba(16,185,129,0.25); }
        .btn:hover { transform: translateY(-2px); }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        th, td { padding: 0.85rem; text-align: left; border-bottom: 1px solid #e2e8f0; }
        th { background: #f8fafc; color: #475569; font-weight: 700; }
        .empty { color: #94a3b8; text-align: center; padding: 1rem; }
        .tag { background: #eff6ff; color: #1d4ed8; padding: 0.25rem 0.6rem; border-radius: 8px; font-weight: 600; font-size: 0.85rem; }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <div style="font-size:1.2rem;font-weight:700;">📚 Actividades y Calificaciones</div>
            <div style="opacity:0.9;">Gestiona tareas, proyectos y notas de tus grupos.</div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn" style="background:rgba(255,255,255,0.2);box-shadow:none;">Cerrar sesión</button>
        </form>
    </div>

    <div class="container">
        <div class="breadcrumb">
            <a href="{{ route('profesor.dashboard') }}">Dashboard</a> / <span>Actividades</span>
        </div>

        @foreach($assignments as $assignment)
            @php
                $activities = $assignment->activities ?? collect();
                $grupo = $assignment->group;
            @endphp
            <div class="assignment-card">
                <div class="card-header">
                    <div>
                        <div class="title">{{ $assignment->subject->name ?? 'Materia' }} — {{ $grupo->name ?? 'Grupo' }}</div>
                        <div class="meta">Aula: {{ $assignment->classroom->code ?? 'N/A' }} · {{ ucfirst($assignment->day) }} {{ substr($assignment->start_time,0,5) }}-{{ substr($assignment->end_time,0,5) }}</div>
                    </div>
                    <a class="btn" href="{{ route('profesor.actividades.create', ['assignment_id' => $assignment->id]) }}">➕ Nueva actividad</a>
                </div>

                @if($activities->isEmpty())
                    <div class="empty">Aun no hay actividades registradas para este curso.</div>
                @else
                    <table>
                        <thead>
                            <tr>
                                <th>Titulo</th>
                                <th>Entrega</th>
                                <th>Puntaje</th>
                                <th>Calificadas</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($activities as $activity)
                                @php
                                    $totalStudents = $grupo->students->count();
                                    $graded = $activity->grades->count();
                                @endphp
                                <tr>
                                    <td>{{ $activity->title }}</td>
                                    <td>{{ $activity->due_date ? $activity->due_date->format('d/m/Y') : 'Sin fecha' }}</td>
                                    <td><span class="tag">Max {{ $activity->max_score }}</span></td>
                                    <td>{{ $graded }} / {{ $totalStudents }}</td>
                                    <td><a class="btn" href="{{ route('profesor.actividades.calificar', $activity->id) }}">Calificar</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        @endforeach
    </div>
</body>
</html>
