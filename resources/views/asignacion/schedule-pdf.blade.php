<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horario de {{ $teacher->first_name }} {{ $teacher->last_name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        h1 {
            text-align: center;
            color: #2c3e50;
            border-bottom: 3px solid #3498db;
            padding-bottom: 10px;
        }
        .info {
            background-color: #ecf0f1;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .day-section {
            margin-bottom: 30px;
        }
        .day-title {
            background-color: #3498db;
            color: white;
            padding: 10px;
            border-radius: 5px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #bdc3c7;
        }
        th {
            background-color: #34495e;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        tr:hover {
            background-color: #e8f4f8;
        }
        .no-classes {
            text-align: center;
            padding: 20px;
            color: #7f8c8d;
            font-style: italic;
        }
        .badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.9em;
            font-weight: bold;
        }
        .badge-success {
            background-color: #27ae60;
            color: white;
        }
        .badge-warning {
            background-color: #f39c12;
            color: white;
        }
        .badge-danger {
            background-color: #e74c3c;
            color: white;
        }
        .summary {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 10px;
        }
        .summary-item {
            text-align: center;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .summary-item strong {
            display: block;
            font-size: 24px;
            color: #2980b9;
        }
        .summary-item span {
            font-size: 12px;
            color: #7f8c8d;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #bdc3c7;
            text-align: center;
            color: #7f8c8d;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <h1>📅 Horario Personal de Clases</h1>
    
    <div class="info">
        <strong>Profesor:</strong> {{ $teacher->first_name }} {{ $teacher->last_name }}<br>
        <strong>Cédula:</strong> {{ $teacher->identification ?? 'N/A' }}<br>
        <strong>Generado:</strong> {{ now()->format('d/m/Y H:i') }}
    </div>

    @forelse(['monday' => 'Lunes', 'tuesday' => 'Martes', 'wednesday' => 'Miércoles', 'thursday' => 'Jueves', 'friday' => 'Viernes', 'saturday' => 'Sábado', 'sunday' => 'Domingo'] as $dayKey => $dayName)
        @if($assignments->has($dayKey))
            <div class="day-section">
                <div class="day-title">{{ $dayName }}</div>
                <table>
                    <thead>
                        <tr>
                            <th>Materia</th>
                            <th>Grupo</th>
                            <th>Salón</th>
                            <th>Horario</th>
                            <th>Calidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($assignments[$dayKey] as $assignment)
                            <tr>
                                <td><strong>{{ $assignment->subject->name ?? 'Sin materia' }}</strong></td>
                                <td>{{ $assignment->group->name ?? 'Sin grupo' }}</td>
                                <td>{{ $assignment->classroom->name ?? 'Sin salón' }}</td>
                                <td>
                                    {{ \Carbon\Carbon::parse($assignment->start_time)->format('H:i') }} - 
                                    {{ \Carbon\Carbon::parse($assignment->end_time)->format('H:i') }}
                                </td>
                                <td>
                                    @php
                                        $score = round($assignment->score * 100, 1);
                                        $badgeClass = match(true) {
                                            $score >= 80 => 'success',
                                            $score >= 60 => 'warning',
                                            $score >= 40 => 'danger',
                                            default => 'secondary'
                                        };
                                    @endphp
                                    <span class="badge badge-{{ $badgeClass }}">{{ $score }}%</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    @empty
        <div class="no-classes">No hay clases asignadas</div>
    @endforelse

    <div class="summary">
        <div class="summary-item">
            <strong>{{ count($assignments) }}</strong>
            <span>Días de Clase</span>
        </div>
        <div class="summary-item">
            <strong>{{ $assignments->sum(fn($day) => $day->count()) }}</strong>
            <span>Clases Totales</span>
        </div>
        <div class="summary-item">
            <strong>{{ $assignments->sum(fn($day) => $day->sum(fn($a) => \Carbon\Carbon::parse($a->end_time)->diffInMinutes(\Carbon\Carbon::parse($a->start_time)))) / 60 }}h</strong>
            <span>Horas Totales</span>
        </div>
    </div>

    <div class="footer">
        <p>Sistema de Asignación de Salones - Generado automáticamente</p>
    </div>
</body>
</html>
