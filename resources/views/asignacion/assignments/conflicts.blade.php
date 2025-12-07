<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conflictos de Asignación - Sistema de Asignación</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f8fafc; color: #334155; }
        .container { max-width: 1200px; margin: 0 auto; padding: 2rem; }
        .header { background: white; padding: 1rem 2rem; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 2rem; }
        .conflict-card { background: #fef2f2; border: 2px solid #fecaca; border-radius: 8px; padding: 1.5rem; margin-bottom: 1.5rem; }
        .conflict-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; }
        .conflict-type { background: #ef4444; color: white; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.875rem; }
        .assignment-card { background: white; border: 1px solid #e5e7eb; border-radius: 6px; padding: 1rem; margin: 0.5rem 0; }
        .btn { padding: 0.5rem 1rem; border: none; border-radius: 6px; cursor: pointer; text-decoration: none; display: inline-block; font-weight: 500; }
        .btn-primary { background: #3b82f6; color: white; }
        .no-conflicts { text-align: center; padding: 3rem; background: white; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>⚠️ Conflictos de Asignación</h1>
            <p>Revision de conflictos y solapamientos en el horario</p>
            <a href="{{ route('asignacion.assignments.index') }}" class="btn btn-primary">← Volver a Asignaciones</a>
        </div>

        @if(empty($conflicts))
            <div class="no-conflicts">
                <h2>🎉 No hay conflictos detectados</h2>
                <p>El horario está libre de solapamientos y conflictos.</p>
            </div>
        @else
            @foreach($conflicts as $conflictInfo)
                <div class="conflict-card">
                    <div class="conflict-header">
                        <h3>Conflicto: {{ $conflictInfo['assignment']->group->name ?? 'Grupo' }} - {{ ucfirst($conflictInfo['assignment']->day) }} {{ \Carbon\Carbon::parse($conflictInfo['assignment']->start_time)->format('H:i') }}</h3>
                        <span class="conflict-type">CONFLICTO</span>
                    </div>
                    
                    <div style="margin-bottom: 1rem;">
                        <strong>Asignación Principal:</strong><br>
                        <div class="assignment-card">
                            <strong>Grupo:</strong> {{ $conflictInfo['assignment']->group->name ?? 'N/A' }}<br>
                            <strong>Profesor:</strong> {{ $conflictInfo['assignment']->teacher->name ?? 'N/A' }}<br>
                            <strong>Salón:</strong> {{ $conflictInfo['assignment']->classroom->name ?? 'N/A' }}<br>
                            <strong>Horario:</strong> {{ ucfirst($conflictInfo['assignment']->day) }} {{ \Carbon\Carbon::parse($conflictInfo['assignment']->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($conflictInfo['assignment']->end_time)->format('H:i') }}
                        </div>
                    </div>

                    <div>
                        <strong>Conflictos con:</strong>
                        @foreach($conflictInfo['conflicts'] as $conflict)
                            <div class="assignment-card" style="border-left: 4px solid #ef4444;">
                                <strong>Grupo:</strong> {{ $conflict['group']['name'] ?? 'N/A' }}<br>
                                <strong>Profesor:</strong> {{ $conflict['teacher']['name'] ?? 'N/A' }}<br>
                                <strong>Salón:</strong> {{ $conflict['classroom']['name'] ?? 'N/A' }}<br>
                                <strong>Horario:</strong> {{ ucfirst($conflict['day']) }} {{ \Carbon\Carbon::parse($conflict['start_time'])->format('H:i') }} - {{ \Carbon\Carbon::parse($conflict['end_time'])->format('H:i') }}
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</body>
</html>