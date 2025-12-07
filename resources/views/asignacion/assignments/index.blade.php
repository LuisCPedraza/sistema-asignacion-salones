<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Asignaciones - Sistema de Asignación</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f8fafc; color: #334155; }
        .container { max-width: 1200px; margin: 0 auto; padding: 2rem; }
        .header { background: white; padding: 1rem 2rem; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 2rem; }
        .actions { display: flex; gap: 1rem; margin-bottom: 2rem; }
        .btn { padding: 0.75rem 1.5rem; border: none; border-radius: 6px; cursor: pointer; text-decoration: none; display: inline-block; font-weight: 500; }
        .btn-primary { background: #3b82f6; color: white; }
        .btn-success { background: #10b981; color: white; }
        .btn-danger { background: #ef4444; color: white; }
        .btn-warning { background: #f59e0b; color: white; }
        .assignments-grid { display: grid; gap: 1rem; }
        .assignment-card { background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); border-left: 4px solid #3b82f6; }
        .assignment-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; }
        .assignment-details { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; }
        .detail-item { display: flex; flex-direction: column; }
        .detail-label { font-size: 0.875rem; color: #64748b; }
        .detail-value { font-weight: 500; color: #1e293b; }
        .conflict-warning { border-left-color: #ef4444; background: #fef2f2; }
        .success-message { background: #d1fae5; color: #065f46; padding: 1rem; border-radius: 6px; margin-bottom: 1rem; }
        .error-message { background: #fee2e2; color: #991b1b; padding: 1rem; border-radius: 6px; margin-bottom: 1rem; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏫 Gestión de Asignaciones</h1>
            <p>Gestiona las asignaciones de grupos a salones y profesores</p>
        </div>

        @if(session('success'))
            <div class="success-message">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="error-message">
                {{ session('error') }}
            </div>
        @endif

        <div class="actions">
            <form action="{{ route('asignacion.assignments.generate') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-primary" onclick="return confirm('¿Generar asignaciones automáticamente? Esto puede tomar unos momentos.')">
                    🔄 Generar Asignaciones Automáticas
                </button>
            </form>
            <a href="{{ route('asignacion.assignments.create') }}" class="btn btn-success">
                ➕ Crear Asignación Manual
            </a>
            <a href="{{ route('asignacion.assignments.conflicts') }}" class="btn btn-warning">
                ⚠️ Ver Conflictos
            </a>
        </div>

        <div class="assignments-grid">
            @forelse($assignments as $assignment)
                <div class="assignment-card">
                    <div class="assignment-header">
                        <h3>{{ $assignment->group->name ?? 'Grupo no encontrado' }}</h3>
                        <form action="{{ route('asignacion.assignments.destroy', $assignment) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('¿Eliminar esta asignación?')">
                                🗑️ Eliminar
                            </button>
                        </form>
                    </div>
                    <div class="assignment-details">
                        <div class="detail-item">
                            <span class="detail-label">Profesor</span>
                            <span class="detail-value">{{ $assignment->teacher->name ?? 'N/A' }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Salón</span>
                            <span class="detail-value">{{ $assignment->classroom->name ?? 'N/A' }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Día</span>
                            <span class="detail-value">{{ ucfirst($assignment->day) }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Horario</span>
                            <span class="detail-value">{{ \Carbon\Carbon::parse($assignment->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($assignment->end_time)->format('H:i') }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Estado</span>
                            <span class="detail-value">
                                @if($assignment->is_confirmed)
                                    ✅ Confirmada
                                @else
                                    ⏳ Pendiente
                                @endif
                            </span>
                        </div>
                    </div>
                    @if($assignment->notes)
                        <div style="margin-top: 1rem; padding: 0.5rem; background: #f1f5f9; border-radius: 4px;">
                            <small>{{ $assignment->notes }}</small>
                        </div>
                    @endif
                </div>
            @empty
                <div class="assignment-card">
                    <p>No hay asignaciones registradas.</p>
                </div>
            @endforelse
        </div>
    </div>
</body>
</html>