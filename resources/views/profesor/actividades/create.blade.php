<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Actividad</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif; background: #f8fafc; color: #0f172a; margin:0; }
        .header { background: linear-gradient(135deg, #10b981, #059669); color:#fff; padding:1.25rem 2rem; box-shadow:0 4px 16px rgba(16,185,129,0.25); display:flex; justify-content:space-between; align-items:center; }
        .container { max-width: 800px; margin: 0 auto; padding: 2rem; }
        .card { background:#fff; border-radius:12px; box-shadow:0 6px 18px rgba(15,23,42,0.06); padding:1.75rem; }
        label { display:block; font-weight:700; margin-bottom:0.35rem; color:#0f172a; }
        input, textarea { width:100%; padding:0.8rem; border:1px solid #e2e8f0; border-radius:10px; font-size:1rem; margin-bottom:1rem; }
        textarea { min-height:120px; resize:vertical; }
        .actions { display:flex; gap:1rem; justify-content:flex-end; }
        .btn { background:linear-gradient(135deg,#2563eb,#1d4ed8); color:#fff; border:none; padding:0.9rem 1.4rem; border-radius:10px; font-weight:700; cursor:pointer; }
        .btn-secondary { background:#e2e8f0; color:#0f172a; text-decoration:none; padding:0.9rem 1.4rem; border-radius:10px; font-weight:700; }
        .meta { margin-bottom:1rem; color:#475569; }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <div style="font-size:1.1rem;font-weight:700;">➕ Nueva Actividad</div>
            <div style="opacity:0.9;">{{ $assignment->subject->name ?? 'Materia' }} — {{ $assignment->group->name ?? 'Grupo' }}</div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn" style="background:rgba(255,255,255,0.2);box-shadow:none;">Salir</button>
        </form>
    </div>

    <div class="container">
        <div class="meta">Aula: {{ $assignment->classroom->code ?? 'N/A' }} · {{ ucfirst($assignment->day) }} {{ substr($assignment->start_time,0,5) }}-{{ substr($assignment->end_time,0,5) }}</div>

        <div class="card">
            <form method="POST" action="{{ route('profesor.actividades.store') }}">
                @csrf
                <input type="hidden" name="assignment_id" value="{{ $assignment->id }}">

                <label for="title">Titulo</label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" required>

                <label for="description">Descripcion</label>
                <textarea id="description" name="description">{{ old('description') }}</textarea>

                <label for="due_date">Fecha de entrega</label>
                <input type="date" id="due_date" name="due_date" value="{{ old('due_date') }}" min="{{ date('Y-m-d') }}">

                <label for="max_score">Puntaje maximo</label>
                <input type="number" id="max_score" name="max_score" step="0.1" min="1" max="999" value="{{ old('max_score', 100) }}" required>

                <div class="actions">
                    <a class="btn-secondary" href="{{ route('profesor.actividades.index') }}">Cancelar</a>
                    <button type="submit" class="btn">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
