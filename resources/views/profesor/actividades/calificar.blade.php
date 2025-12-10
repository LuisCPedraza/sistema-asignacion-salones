<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calificar actividad</title>
    <style>
        body { font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; background:#f8fafc; color:#0f172a; margin:0; }
        .header { background:linear-gradient(135deg,#f59e0b,#d97706); color:#fff; padding:1.25rem 2rem; display:flex; justify-content:space-between; align-items:center; box-shadow:0 4px 16px rgba(245,158,11,0.25); }
        .container { max-width: 1100px; margin:0 auto; padding:2rem; }
        .card { background:#fff; border-radius:12px; box-shadow:0 6px 18px rgba(15,23,42,0.06); padding:1.5rem; }
        table { width:100%; border-collapse:collapse; }
        th, td { padding:0.75rem; border-bottom:1px solid #e2e8f0; text-align:left; }
        th { background:#f8fafc; color:#475569; }
        input[type="number"] { width:120px; padding:0.5rem; border:1px solid #e2e8f0; border-radius:8px; }
        textarea { width:100%; min-height:80px; padding:0.6rem; border:1px solid #e2e8f0; border-radius:8px; resize:vertical; }
        .actions { margin-top:1rem; display:flex; justify-content:flex-end; gap:1rem; }
        .btn { background:linear-gradient(135deg,#2563eb,#1d4ed8); color:#fff; border:none; padding:0.85rem 1.3rem; border-radius:10px; font-weight:700; cursor:pointer; }
        .btn-secondary { background:#e2e8f0; color:#0f172a; padding:0.85rem 1.3rem; border-radius:10px; text-decoration:none; font-weight:700; }
        .meta { margin:0.6rem 0 1rem; color:#475569; }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <div style="font-size:1.15rem;font-weight:700;">📝 Calificar: {{ $activity->title }}</div>
            <div style="opacity:0.9;">{{ $activity->assignment->subject->name ?? 'Materia' }} — {{ $activity->assignment->group->name ?? 'Grupo' }}</div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn" style="background:rgba(255,255,255,0.2);box-shadow:none;">Salir</button>
        </form>
    </div>

    <div class="container">
        <div class="meta">Entrega: {{ $activity->due_date ? $activity->due_date->format('d/m/Y') : 'Sin fecha' }} · Puntaje maximo: {{ $activity->max_score }}</div>

        <div class="card">
            <form method="POST" action="{{ route('profesor.actividades.guardar-calificaciones', $activity->id) }}">
                @csrf
                <table>
                    <thead>
                        <tr>
                            <th>Estudiante</th>
                            <th>Codigo</th>
                            <th>Nota</th>
                            <th>Feedback</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($activity->assignment->group->students as $student)
                            @php
                                $grade = $grades[$student->id] ?? null;
                            @endphp
                            <tr>
                                <td>{{ $student->nombre_completo }}</td>
                                <td>{{ $student->codigo }}</td>
                                <td>
                                    <input type="number" name="grades[{{ $student->id }}]" step="0.1" min="0" max="{{ $maxScore }}" value="{{ $grade->score ?? '' }}">
                                </td>
                                <td>
                                    <textarea name="feedback[{{ $student->id }}]">{{ $grade->feedback ?? '' }}</textarea>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="actions">
                    <a class="btn-secondary" href="{{ route('profesor.actividades.index') }}">Cancelar</a>
                    <button type="submit" class="btn">Guardar calificaciones</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
