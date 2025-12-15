<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Curso</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            color: #1e293b;
            padding: 1.5rem;
        }
        .container { max-width: 1200px; margin: 0 auto; }
        .card {
            background: white;
            border-radius: 14px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.08);
            padding: 1.5rem;
            margin-bottom: 1.25rem;
        }
        .title-row { display:flex; align-items:flex-start; justify-content:space-between; gap:1rem; }
        .title h1 { margin:0 0 .4rem; font-size: 1.6rem; }
        .badge { display:inline-block; padding:.3rem .6rem; border-radius: 8px; font-size:.85rem; background:#f59e0b; color:white; font-weight:700; }
        .muted { color:#94a3b8; }
        .grid { display:grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap:1rem; margin-top:1rem; }
        .label { font-size:.85rem; color:#64748b; }
        .value { font-weight:600; color:#111827; }
        .actions { display:flex; gap:.6rem; flex-wrap:wrap; }
        .btn {
            display:inline-flex; align-items:center; gap:.35rem;
            padding:.65rem 1rem; border-radius:9px; text-decoration:none; font-weight:600;
            color:white; box-shadow:0 4px 12px rgba(0,0,0,0.12);
        }
        .btn-orange { background: linear-gradient(135deg,#f59e0b,#ea580c); }
        .btn-green { background: linear-gradient(135deg,#10b981,#059669); }
        .btn-blue { background: linear-gradient(135deg,#3b82f6,#2563eb); }
        .btn-gray { background: linear-gradient(135deg,#94a3b8,#475569); }
        .section-title { font-size:1.05rem; font-weight:700; margin:1rem 0 .5rem; }
        ul { margin:0; padding-left:1.1rem; color:#475569; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="title-row">
                <div class="title">
                    <h1>{{ $assignment->subject->nombre ?? $assignment->subject->name ?? 'Sin nombre' }}</h1>
                    <div class="muted">Grupo: {{ $assignment->group->nombre ?? $assignment->group->name ?? 'N/A' }} · Aula: {{ $assignment->classroom->nombre ?? $assignment->classroom->name ?? 'N/A' }}</div>
                    <div class="muted">Día: {{ ucfirst($assignment->day) }} · {{ \Carbon\Carbon::parse($assignment->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($assignment->end_time)->format('H:i') }}</div>
                </div>
                <div class="actions">
                    <a href="{{ route('profesor.horario') }}" class="btn btn-gray">← Volver al Horario</a>
                    <a href="{{ route('profesor.asistencias.tomar', $assignment->id) }}" class="btn btn-orange">✅ Tomar asistencia</a>
                    <a href="{{ route('profesor.actividades.index') }}" class="btn btn-blue">📝 Actividades</a>
                    <a href="{{ route('profesor.reportes.index') }}" class="btn btn-green">📈 Reportes</a>
                </div>
            </div>
        </div>

        <div class="grid">
            <div class="card">
                <div class="section-title">Información del curso</div>
                <div class="label">Materia</div>
                <div class="value">{{ $assignment->subject->nombre ?? $assignment->subject->name ?? 'Sin nombre' }}</div>
                <div class="label" style="margin-top:.5rem;">Carrera / Semestre</div>
                <div class="value">{{ $assignment->group->career->name ?? 'N/A' }} · {{ $assignment->group->semester->name ?? 'N/A' }}</div>
                <div class="label" style="margin-top:.5rem;">Créditos</div>
                <div class="value">{{ $assignment->subject->credits ?? 'N/D' }}</div>
            </div>

            <div class="card">
                <div class="section-title">Horario y aula</div>
                <div class="label">Día</div>
                <div class="value">{{ ucfirst($assignment->day) }}</div>
                <div class="label" style="margin-top:.5rem;">Hora</div>
                <div class="value">{{ \Carbon\Carbon::parse($assignment->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($assignment->end_time)->format('H:i') }}</div>
                <div class="label" style="margin-top:.5rem;">Aula</div>
                <div class="value">{{ $assignment->classroom->nombre ?? $assignment->classroom->name ?? 'N/A' }} @if($assignment->classroom && $assignment->classroom->building) ({{ $assignment->classroom->building->name }}) @endif</div>
            </div>

            <div class="card">
                <div class="section-title">Grupo y estudiantes</div>
                <div class="label">Grupo</div>
                <div class="value">{{ $assignment->group->nombre ?? $assignment->group->name ?? 'N/A' }}</div>
                <div class="label" style="margin-top:.5rem;">Estudiantes</div>
                <div class="value">{{ $assignment->group->number_of_students ?? $assignment->group->student_count ?? 'N/D' }}</div>
                <div class="label" style="margin-top:.5rem;">Notas</div>
                <ul>
                    <li>Tomar asistencia y ver historial desde el botón superior.</li>
                    <li>Calificar actividades desde el módulo de actividades.</li>
                    <li>Descargar reportes en la sección de reportes.</li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>
