<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Horario - {{ $teacher->full_name ?? ($teacher->first_name.' '.$teacher->last_name) }}</title>
    <style>
        @page { margin: 20px 25px; }
        body { font-family: DejaVu Sans, Arial, Helvetica, sans-serif; color: #1e293b; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; border-bottom: 2px solid #fecaca; padding-bottom: 8px; }
        .brand { display:flex; align-items:center; gap:8px; }
        .logo { width: 28px; height: 28px; object-fit: contain; }
        .title { font-size: 20px; font-weight: 700; }
        .meta { font-size: 12px; color: #475569; }
        .badge { display:inline-block; background:#ef4444; color:#fff; padding:2px 6px; border-radius:4px; font-size:10px; font-weight:700; vertical-align:middle; }
        .grid { display: table; width: 100%; border-spacing: 10px; }
        .col { display: table-cell; width: 33%; vertical-align: top; }
        .day { border: 1px solid #e2e8f0; border-radius: 6px; }
        .day-header { background: #f59e0b; color: #fff; padding: 8px 10px; font-weight: 700; font-size: 12px; }
        .day-body { padding: 8px 10px; }
        .card { border: 1px solid #f1f5f9; border-left: 3px solid #f59e0b; padding: 8px; margin-bottom: 8px; border-radius: 4px; }
        .time { color: #f59e0b; font-weight: 700; font-size: 12px; }
        .subject { font-weight: 700; margin: 4px 0; font-size: 13px; }
        .detail { font-size: 11px; color: #475569; }
        .muted { color: #94a3b8; font-style: italic; font-size: 11px; }
        .footer { margin-top: 8px; font-size: 10px; color: #64748b; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <div class="brand">
                @php $logo = env('APP_LOGO_URL'); @endphp
                @if(!empty($logo))
                    <img src="{{ $logo }}" class="logo" alt="Logo" />
                @endif
                <div class="title">Horario Académico <span class="badge">PDF</span></div>
            </div>
            <div class="meta">
                Profesor: <strong>{{ $teacher->full_name ?? ($teacher->first_name.' '.$teacher->last_name) }}</strong>
                @if($teacher->academic_degree) · {{ $teacher->academic_degree }} @endif
                @if($teacher->specialty) · {{ $teacher->specialty }} @endif
                @if($teacher->email) · {{ $teacher->email }} @endif
            </div>
        </div>
        <div style="text-align:right">
            <div class="meta">Generado: {{ ($generatedAt ?? now())->format('d/m/Y H:i') }}</div>
            @if(isset($weeklyTotals))
                <div class="meta"><strong>Totales:</strong> 📚 {{ $weeklyTotals['classes'] }} clases · ⏱️ {{ $weeklyTotals['hours'] }} h</div>
            @endif
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
        // Para PDF: 2 filas de 3 columnas
        $daysOrder = ['monday','tuesday','wednesday','thursday','friday','saturday'];
    @endphp

    <div class="grid">
        @foreach(array_chunk($daysOrder, 3) as $row)
            <div class="row" style="display: table-row;">
                @foreach($row as $day)
                    <div class="col">
                        <div class="day">
                            <div class="day-header">{{ $dayNames[$day] }}</div>
                            <div class="day-body">
                                @if(empty($schedule[$day]))
                                    <div class="muted">No tienes clases este día</div>
                                @else
                                    @foreach($schedule[$day] as $assignment)
                                        <div class="card">
                                            <div class="time">{{ \Carbon\Carbon::parse($assignment->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($assignment->end_time)->format('H:i') }}</div>
                                            <div class="subject">{{ $assignment->subject->nombre ?? $assignment->subject->name ?? 'Sin nombre' }}</div>
                                            <div class="detail">👥 Grupo: {{ $assignment->group->nombre ?? $assignment->group->name ?? 'N/A' }}</div>
                                            <div class="detail">🚪 Aula: {{ $assignment->classroom->nombre ?? $assignment->classroom->name ?? 'N/A' }}</div>
                                            @if(optional($assignment->group)->number_of_students)
                                                <div class="detail">📊 Estudiantes: {{ $assignment->group->number_of_students }}</div>
                                            @endif
                                        </div>
                                    @endforeach
                                @endif
                                @if(isset($dayTotals[$day]))
                                    <div class="muted" style="margin-top:6px;">Totales del día: 📚 {{ $dayTotals[$day]['classes'] }} · ⏱️ {{ $dayTotals[$day]['hours'] }} h</div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>

    <div class="footer">Sistema de Asignación · {{ config('app.name') }}</div>
</body>
</html>
