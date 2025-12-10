<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reporte de Actividades y Calificaciones</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; color: #333; }
        .header p { margin: 5px 0; color: #666; }
        .stats { margin: 20px 0; display: flex; gap: 20px; }
        .stat-box { flex: 1; border: 1px solid #ddd; padding: 10px; text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f5f5f5; font-weight: bold; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .footer { margin-top: 30px; text-align: right; font-size: 12px; color: #666; }
        .activity { margin: 15px 0; padding: 10px; border-left: 4px solid #007bff; }
    </style>
</head>
<body>
    <div class="header">
        <h1>📊 Reporte de Actividades y Calificaciones</h1>
        <p><strong>{{ $assignment->subject->nombre }}</strong> - {{ $assignment->group->nombre }}</p>
        <p>Generado: {{ $fechaExporte }}</p>
    </div>

    <div class="stats">
        <div class="stat-box">
            <strong>Total Actividades</strong><br>{{ $estadisticas['totalActividades'] }}
        </div>
        <div class="stat-box">
            <strong>Promedio General</strong><br>{{ round($estadisticas['promedioGeneral'], 2) }}%
        </div>
        <div class="stat-box">
            <strong>Mejor Calificación</strong><br>{{ round($estadisticas['mejorCalificacion'], 2) }}%
        </div>
        <div class="stat-box">
            <strong>Peor Calificación</strong><br>{{ round($estadisticas['peorCalificacion'], 2) }}%
        </div>
    </div>

    <h2>Actividades Registradas</h2>
    @foreach($activities as $activity)
        <div class="activity">
            <strong>{{ $activity->title }}</strong><br>
            {{ $activity->description }}<br>
            <small>Puntaje máximo: {{ $activity->max_score }} | Vencimiento: {{ $activity->due_date->format('d/m/Y') }}</small>
        </div>
    @endforeach

    <h2>Calificaciones por Estudiante</h2>
    <table>
        <thead>
            <tr>
                <th>Estudiante</th>
                <th>Total Obtenido</th>
                <th>Total Posible</th>
                <th>Promedio</th>
            </tr>
        </thead>
        <tbody>
            @foreach($calificacionesPorEstudiante as $calificacion)
                <tr>
                    <td>{{ $calificacion['estudiante']->nombre_completo }}</td>
                    <td>{{ $calificacion['totalObtenido'] }}</td>
                    <td>{{ $calificacion['totalPosible'] }}</td>
                    <td>{{ $calificacion['promedio'] }}%</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Este reporte fue generado automáticamente por el sistema de asignación de salones.</p>
    </div>
</body>
</html>
