<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Horario - Sistema de Asignación</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            color: #1e293b;
            min-height: 100vh;
            padding: 2rem;
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .header {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 2rem;
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 1rem;
            align-items: start;
        }
        
        .header h1 {
            color: #1e293b;
            font-size: 2rem;
            font-weight: 700;
        }
        
        .header p {
            color: #64748b;
            margin-top: 0.5rem;
        }
        
        .btn-back { background: linear-gradient(135deg, #f59e0b 0%, #ea580c 100%); }
        .btn-export { background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%); }
        .btn-back, .btn-export {
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        
        .btn-back:hover, .btn-export:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }

        .header-actions { display: flex; gap: .75rem; justify-self: end; }
        
        /* 3 columnas arriba y 3 abajo, responsive */
        .schedule-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
        }
        @media (max-width: 1100px) { .schedule-grid { grid-template-columns: repeat(2, 1fr);} }
        @media (max-width: 720px) { .schedule-grid { grid-template-columns: 1fr; } }
        
        .day-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            transition: all 0.3s;
        }
        
        .day-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(245, 158, 11, 0.2);
        }
        
        .day-header {
            background: linear-gradient(135deg, #f59e0b 0%, #ea580c 100%);
            color: white;
            padding: 1.25rem;
            font-weight: 700;
            font-size: 1.1rem;
            text-align: center;
        }
        
        .day-content {
            padding: 1.5rem;
        }
        
        .assignment-item {
            background: #f8fafc;
            border-left: 4px solid #f59e0b;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            transition: all 0.3s;
        }
        
        .assignment-item:hover {
            background: #fef3c7;
            transform: translateX(5px);
        }
        
        .assignment-time {
            font-weight: 700;
            color: #f59e0b;
            font-size: 1rem;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .assignment-subject {
            font-weight: 600;
            color: #1e293b;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }
        
        .assignment-details {
            color: #64748b;
            font-size: 0.9rem;
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }
        
        .assignment-details span {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .no-assignments {
            text-align: center;
            color: #94a3b8;
            padding: 2rem;
            font-style: italic;
        }
        
        .empty-state {
            background: white;
            padding: 3rem;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            text-align: center;
        }
        
        .empty-state h2 {
            color: #64748b;
            margin-bottom: 1rem;
        }
        
        .empty-state p {
            color: #94a3b8;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div>
                <h1>🕐 Mi Horario</h1>
                <p>Profesor: <strong>{{ $teacher->full_name ?? ($teacher->first_name.' '.$teacher->last_name) }}</strong></p>
                <p style="color:#64748b; margin-top:.35rem;">
                    @if($teacher->email) ✉️ {{ $teacher->email }} @endif
                    @if($teacher->phone) · ☎️ {{ $teacher->phone }} @endif
                    @if($teacher->academic_degree) · 🎓 {{ $teacher->academic_degree }} @endif
                    @if($teacher->specialty) · 🔧 {{ $teacher->specialty }} @endif
                </p>
                @if(isset($weeklyTotals))
                <div style="margin-top:.6rem; display:flex; gap:1rem; color:#1f2937;">
                    <div style="background:#fff7ed;border:1px solid #fed7aa;border-radius:8px;padding:.5rem .75rem;font-weight:600;">📚 Clases totales: {{ $weeklyTotals['classes'] }}</div>
                    <div style="background:#ecfeff;border:1px solid #a5f3fc;border-radius:8px;padding:.5rem .75rem;font-weight:600;">⏱️ Horas semanales: {{ $weeklyTotals['hours'] }}</div>
                </div>
                @endif
            </div>
            <div class="header-actions">
                <a href="{{ route('profesor.horario.pdf') }}" target="_blank" class="btn-export">⬇︎ Exportar PDF</a>
                <a href="{{ route('profesor.dashboard') }}" class="btn-back">← Volver al Dashboard</a>
            </div>
        </div>

        @php
            $dayNames = [
                'monday' => 'Lunes',
                'tuesday' => 'Martes',
                'wednesday' => 'Miércoles',
                'thursday' => 'Jueves',
                'friday' => 'Viernes',
                'saturday' => 'Sábado',
            ];
        @endphp

        <div class="schedule-grid">
            @foreach($schedule as $day => $dayAssignments)
                <div class="day-card">
                    <div class="day-header">{{ $dayNames[$day] ?? ucfirst($day) }}
                        @if(isset($dayTotals[$day]))
                            <div style="font-size:.8rem;font-weight:500;margin-top:.25rem;opacity:.95;">
                                📚 {{ $dayTotals[$day]['classes'] }} clases · ⏱️ {{ $dayTotals[$day]['hours'] }} h
                            </div>
                        @endif
                    </div>
                    <div class="day-content">
                        @if(empty($dayAssignments))
                            <div class="no-assignments">No tienes clases este día</div>
                        @else
                            @foreach($dayAssignments as $assignment)
                                <div class="assignment-item">
                                    <div class="assignment-time">
                                        🕐 {{ \Carbon\Carbon::parse($assignment->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($assignment->end_time)->format('H:i') }}
                                    </div>
                                    <div class="assignment-subject">
                                        {{ $assignment->subject->nombre ?? $assignment->subject->name ?? 'Sin nombre' }}
                                    </div>
                                    <div class="assignment-details">
                                        <span>👥 Grupo: {{ $assignment->group->nombre ?? $assignment->group->name ?? 'N/A' }}</span>
                                        <span>🚪 Aula: {{ $assignment->classroom->nombre ?? $assignment->classroom->name ?? 'N/A' }}</span>
                                        @if(optional($assignment->group)->number_of_students)
                                            <span>📊 Estudiantes: {{ $assignment->group->number_of_students }}</span>
                                        @endif
                                        <span>
                                            <a href="{{ route('profesor.detalle-curso', $assignment->id) }}" style="color:#b91c1c; font-weight:600; text-decoration:none;">🔎 Ver curso</a>
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        @if(isset($dayTotals) && isset($weeklyTotals))
        <div style="margin-top:2rem; background:white; padding:1.5rem; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.08);">
            <h3 style="margin-bottom:1rem; color:#1e293b;">Resumen por día</h3>
            <div style="overflow-x:auto;">
                <table style="width:100%; border-collapse:collapse;">
                    <thead>
                        <tr>
                            <th style="text-align:left; padding:.75rem; border-bottom:1px solid #e2e8f0;">Día</th>
                            <th style="text-align:right; padding:.75rem; border-bottom:1px solid #e2e8f0;">Clases</th>
                            <th style="text-align:right; padding:.75rem; border-bottom:1px solid #e2e8f0;">Horas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $order = ['monday','tuesday','wednesday','thursday','friday','saturday']; @endphp
                        @foreach($order as $d)
                        <tr>
                            <td style="padding:.75rem; border-bottom:1px solid #f1f5f9;">{{ $dayNames[$d] }}</td>
                            <td style="padding:.75rem; border-bottom:1px solid #f1f5f9; text-align:right;">{{ $dayTotals[$d]['classes'] ?? 0 }}</td>
                            <td style="padding:.75rem; border-bottom:1px solid #f1f5f9; text-align:right;">{{ $dayTotals[$d]['hours'] ?? 0 }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th style="text-align:left; padding:.75rem; border-top:2px solid #e2e8f0;">Total semanal</th>
                            <th style="text-align:right; padding:.75rem; border-top:2px solid #e2e8f0;">{{ $weeklyTotals['classes'] }}</th>
                            <th style="text-align:right; padding:.75rem; border-top:2px solid #e2e8f0;">{{ $weeklyTotals['hours'] }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        @endif
    </div>
</body>
</html>
