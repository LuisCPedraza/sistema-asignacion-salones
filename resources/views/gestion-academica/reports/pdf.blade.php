<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte Académico</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: Arial, sans-serif; 
            color: #333; 
            line-height: 1.6;
            padding: 20px;
        }
        .header { 
            text-align: center; 
            margin-bottom: 30px; 
            border-bottom: 3px solid #48bb78; 
            padding-bottom: 15px; 
        }
        .header h1 { 
            color: #48bb78; 
            font-size: 24px; 
            margin-bottom: 5px; 
        }
        .header p { 
            font-size: 12px; 
            color: #666; 
        }
        .metadata { 
            margin-bottom: 20px; 
            font-size: 11px; 
            color: #666;
            background: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
        }
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }
        .metric-card {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            border-left: 4px solid #48bb78;
        }
        .metric-value {
            font-size: 28px;
            font-weight: bold;
            color: #48bb78;
            margin-bottom: 5px;
        }
        .metric-label {
            font-size: 11px;
            color: #666;
            text-transform: uppercase;
        }
        .section-title {
            font-size: 16px;
            color: #48bb78;
            margin: 25px 0 15px 0;
            padding-bottom: 5px;
            border-bottom: 2px solid #e2e8f0;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 10px; 
            font-size: 10px; 
        }
        table thead { 
            background-color: #48bb78; 
            color: white; 
        }
        table th { 
            padding: 8px; 
            text-align: left; 
            font-weight: bold; 
            border: 1px solid #dee2e6; 
        }
        table td { 
            padding: 6px 8px; 
            border: 1px solid #dee2e6; 
        }
        table tbody tr:nth-child(even) { 
            background-color: #f9f9f9; 
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: bold;
        }
        .badge-success {
            background-color: #d4edda;
            color: #155724;
        }
        .badge-secondary {
            background-color: #e2e3e5;
            color: #383d41;
        }
        .footer { 
            margin-top: 30px; 
            padding-top: 15px; 
            border-top: 2px solid #e2e8f0; 
            font-size: 9px; 
            color: #999; 
            text-align: center; 
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📊 Reporte Académico</h1>
        <p>Sistema de Asignación de Salones - Coordinación Académica</p>
    </div>

    <div class="metadata">
        <strong>Generado:</strong> {{ $generated_at }}
        @if($filters['start_date'] || $filters['end_date'])
            <br>
            <strong>Período:</strong> 
            {{ $filters['start_date'] ? 'Desde ' . \Carbon\Carbon::parse($filters['start_date'])->format('d/m/Y') : '' }}
            {{ $filters['end_date'] ? ' hasta ' . \Carbon\Carbon::parse($filters['end_date'])->format('d/m/Y') : '' }}
        @endif
    </div>

    <h2 class="section-title">Resumen General</h2>
    <div class="metrics-grid">
        <div class="metric-card">
            <div class="metric-value">{{ $metrics['total_groups'] }}</div>
            <div class="metric-label">Grupos Totales</div>
        </div>
        <div class="metric-card">
            <div class="metric-value">{{ $metrics['active_groups'] }}</div>
            <div class="metric-label">Grupos Activos</div>
        </div>
        <div class="metric-card">
            <div class="metric-value">{{ $metrics['total_teachers'] }}</div>
            <div class="metric-label">Profesores Totales</div>
        </div>
        <div class="metric-card">
            <div class="metric-value">{{ $metrics['active_teachers'] }}</div>
            <div class="metric-label">Profesores Activos</div>
        </div>
        <div class="metric-card">
            <div class="metric-value">{{ $metrics['total_students'] }}</div>
            <div class="metric-label">Total Estudiantes</div>
        </div>
        <div class="metric-card">
            <div class="metric-value">{{ $metrics['total_assignments'] }}</div>
            <div class="metric-label">Asignaciones</div>
        </div>
        <div class="metric-card">
            <div class="metric-value">{{ $metrics['total_class_hours'] }}</div>
            <div class="metric-label">Horas de Clase</div>
        </div>
        <div class="metric-card">
            <div class="metric-value">{{ $metrics['avg_quality'] }}%</div>
            <div class="metric-label">Calidad Promedio</div>
        </div>
    </div>

    <h2 class="section-title">Detalle de Grupos</h2>
    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Carrera</th>
                <th>Estudiantes</th>
                <th>Tipo</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @forelse($groups as $group)
                <tr>
                    <td>{{ $group->name }}</td>
                    <td>{{ $group->career->name ?? 'N/D' }}</td>
                    <td>{{ $group->student_count ?? 0 }}</td>
                    <td>{{ $group->group_type ?? 'N/D' }}</td>
                    <td>
                        <span class="badge {{ $group->is_active ? 'badge-success' : 'badge-secondary' }}">
                            {{ $group->is_active ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" style="text-align: center;">Sin grupos registrados.</td></tr>
            @endforelse
        </tbody>
    </table>

    <h2 class="section-title">Detalle de Profesores</h2>
    <table>
        <thead>
            <tr>
                <th>Nombre Completo</th>
                <th>Email</th>
                <th>Especialización</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @forelse($teachers as $teacher)
                <tr>
                    <td>{{ $teacher->first_name }} {{ $teacher->last_name }}</td>
                    <td>{{ $teacher->user->email ?? 'N/D' }}</td>
                    <td>{{ $teacher->specialization ?? 'N/D' }}</td>
                    <td>
                        <span class="badge {{ $teacher->is_active ? 'badge-success' : 'badge-secondary' }}">
                            {{ $teacher->is_active ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" style="text-align: center;">Sin profesores registrados.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Generado automáticamente por el Sistema de Asignación de Salones - Coordinación Académica</p>
    </div>
</body>
</html>
