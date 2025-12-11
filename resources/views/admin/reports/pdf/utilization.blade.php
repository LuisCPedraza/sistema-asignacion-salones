<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Utilización</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin: 15px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #7e22ce;
        }
        .header h1 {
            color: #7e22ce;
            margin: 0 0 5px 0;
            font-size: 20px;
        }
        .filters {
            background: #f3f4f6;
            padding: 10px;
            margin: 15px 0;
            border-radius: 5px;
        }
        .filters p {
            margin: 3px 0;
            font-size: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        th {
            background: #7e22ce;
            color: white;
            padding: 8px;
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
        }
        td {
            padding: 6px 8px;
            border-bottom: 1px solid #e5e7eb;
        }
        tr:hover {
            background: #f9fafb;
        }
        .badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            display: inline-block;
        }
        .badge-success { background: #d1fae5; color: #065f46; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .badge-danger { background: #fee2e2; color: #991b1b; }
        .progress-bar {
            background: #e5e7eb;
            height: 12px;
            border-radius: 3px;
            overflow: hidden;
            display: inline-block;
            width: 60px;
            vertical-align: middle;
        }
        .progress-fill {
            background: linear-gradient(90deg, #7e22ce, #a855f7);
            height: 100%;
        }
        h2 {
            color: #374151;
            font-size: 14px;
            margin: 20px 0 10px 0;
            border-left: 4px solid #7e22ce;
            padding-left: 10px;
        }
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            color: #9ca3af;
            font-size: 9px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📋 Reporte de Utilización de Recursos</h1>
        <p><strong>Fecha:</strong> {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    @if(isset($filters) && (isset($filters['career_name']) || isset($filters['semester_number'])))
    <div class="filters">
        <strong>Filtros aplicados:</strong>
        @if(isset($filters['career_name']))
            <p>🎓 Carrera: {{ $filters['career_name'] }}</p>
        @endif
        @if(isset($filters['semester_number']))
            <p>📚 Semestre: {{ $filters['semester_number'] }}</p>
        @endif
    </div>
    @endif

    @if(isset($groupStats))
    <h2>📊 Estadísticas de Grupos</h2>
    <table>
        <tr>
            <th>Total Grupos</th>
            <th>Total Estudiantes</th>
            <th>Tamaño Promedio</th>
        </tr>
        <tr>
            <td><strong>{{ number_format($groupStats['total_groups'] ?? 0) }}</strong></td>
            <td><strong>{{ number_format($groupStats['total_students'] ?? 0) }}</strong></td>
            <td><strong>{{ number_format($groupStats['avg_group_size'] ?? 0, 1) }}</strong> estudiantes/grupo</td>
        </tr>
    </table>
    @endif

    @if(isset($classroomUtilization) && count($classroomUtilization) > 0)
    <h2>🏫 Utilización de Salones</h2>
    <table>
        <thead>
            <tr>
                <th>Salón</th>
                <th>Capacidad</th>
                <th>Asignaciones</th>
                <th>Utilización</th>
                <th>Calidad Promedio</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($classroomUtilization as $classroom)
            <tr>
                @php
                    $cName = is_array($classroom) ? ($classroom['classroom_name'] ?? 'N/A') : ($classroom->classroom_name ?? 'N/A');
                    $cCap  = is_array($classroom) ? ($classroom['classroom_capacity'] ?? $classroom['capacity'] ?? 0) : ($classroom->classroom_capacity ?? $classroom->capacity ?? 0);
                    $cCount = is_array($classroom) ? ($classroom['assignment_count'] ?? $classroom['assignments_count'] ?? 0) : ($classroom->assignment_count ?? $classroom->assignments_count ?? 0);
                    $cUtil = is_array($classroom) ? ($classroom['utilization_percentage'] ?? 0) : ($classroom->utilization_percentage ?? 0);
                    $cAvgQ = is_array($classroom) ? ($classroom['avg_score'] ?? $classroom['avg_quality'] ?? 0) : ($classroom->avg_score ?? $classroom->avg_quality ?? 0);
                @endphp
                <td><strong>{{ $cName }}</strong></td>
                <td>{{ $cCap }} personas</td>
                <td>{{ $cCount }}</td>
                <td>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: {{ $cUtil }}%"></div>
                    </div>
                    {{ number_format($cUtil, 1) }}%
                </td>
                <td>
                    @php
                        $qualityPercent = $cAvgQ * 100;
                        $qualityClass = $qualityPercent >= 80 ? 'success' : ($qualityPercent >= 70 ? 'warning' : 'danger');
                    @endphp
                    <span class="badge badge-{{ $qualityClass }}">
                        {{ number_format($qualityPercent, 1) }}%
                    </span>
                </td>
                <td>
                    @php
                        $status = $cUtil >= 80 ? ['Óptimo', 'success'] : 
                                 ($cUtil >= 50 ? ['Revisar', 'warning'] : ['Crítico', 'danger']);
                    @endphp
                    <span class="badge badge-{{ $status[1] }}">{{ $status[0] }}</span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    @if(isset($teacherUtilization) && count($teacherUtilization) > 0)
    <h2>👨‍🏫 Utilización de Profesores</h2>
    <table>
        <thead>
            <tr>
                <th>Profesor</th>
                <th>Asignaciones</th>
                <th>Calidad Promedio</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($teacherUtilization as $teacher)
            <tr>
                @php
                    $tName = is_array($teacher) ? ($teacher['teacher_name'] ?? 'N/A') : ($teacher->teacher_name ?? 'N/A');
                    $tCount = is_array($teacher) ? ($teacher['assignment_count'] ?? $teacher['assignments_count'] ?? 0) : ($teacher->assignment_count ?? $teacher->assignments_count ?? 0);
                    $tAvgQ = is_array($teacher) ? ($teacher['avg_score'] ?? $teacher['avg_quality'] ?? 0) : ($teacher->avg_score ?? $teacher->avg_quality ?? 0);
                    $tUtil = is_array($teacher) ? ($teacher['utilization_percentage'] ?? 0) : ($teacher->utilization_percentage ?? 0);
                @endphp
                <td><strong>{{ $tName }}</strong></td>
                <td>{{ $tCount }} grupos</td>
                <td>
                    @php
                        $qualityPercent = $tAvgQ * 100;
                        $qualityClass = $qualityPercent >= 80 ? 'success' : ($qualityPercent >= 70 ? 'warning' : 'danger');
                    @endphp
                    <span class="badge badge-{{ $qualityClass }}">
                        {{ number_format($qualityPercent, 1) }}%
                    </span>
                </td>
                <td>
                    @php
                        $status = $tUtil >= 80 ? ['Excelente', 'success'] : 
                                 ($tUtil >= 70 ? ['Bueno', 'warning'] : ['Revisar', 'danger']);
                    @endphp
                    <span class="badge badge-{{ $status[1] }}">{{ $status[0] }}</span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="footer">
        <p>Documento generado automáticamente - Sistema de Asignación de Salones</p>
    </div>
</body>
</html>
