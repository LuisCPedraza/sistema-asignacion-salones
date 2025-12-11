<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reporte de Asistencias</title>
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
    </style>
</head>
<body>
    <div class="header">
        <h1>📋 Reporte de Asistencias</h1>
        <p><strong>{{ $assignment->subject->nombre }}</strong> - {{ $assignment->group->nombre }}</p>
        <p>Generado: {{ $fechaExporte }}</p>
    </div>

    <div class="stats">
        <div class="stat-box">
            <strong>Total Clases</strong><br>{{ $estadisticas['totalClases'] }}
        </div>
        <div class="stat-box">
            <strong>Promedio Asistencia</strong><br>{{ round($estadisticas['promedioAsistencia'], 2) }}%
        </div>
        <div class="stat-box">
            <strong>Estudiantes en Alerta</strong><br>{{ $estadisticas['estudiantesAlerta'] }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Estudiante</th>
                <th>Total</th>
                <th>Presentes</th>
                <th>Ausentes</th>
                <th>Tardanzas</th>
                <th>Porcentaje</th>
            </tr>
        </thead>
        <tbody>
            @foreach($asistenciasPorEstudiante as $asistencia)
                <tr>
                    <td>{{ $asistencia['estudiante']->nombre_completo }}</td>
                    <td>{{ $asistencia['total'] }}</td>
                    <td>{{ $asistencia['presentes'] }}</td>
                    <td>{{ $asistencia['ausentes'] }}</td>
                    <td>{{ $asistencia['tardanzas'] }}</td>
                    <td>{{ $asistencia['porcentaje'] }}%</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Este reporte fue generado automáticamente por el sistema de asignación de salones.</p>
    </div>
</body>
</html>
