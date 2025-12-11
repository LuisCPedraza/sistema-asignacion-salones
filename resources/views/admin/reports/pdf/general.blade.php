<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte General - Sistema de Asignación</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 3px solid #7e22ce;
        }
        .header h1 {
            color: #7e22ce;
            margin: 0 0 5px 0;
            font-size: 24px;
        }
        .header p {
            margin: 3px 0;
            color: #666;
        }
        .stats-grid {
            display: table;
            width: 100%;
            margin: 20px 0;
        }
        .stat-card {
            display: table-cell;
            width: 25%;
            padding: 15px;
            text-align: center;
            border: 1px solid #e5e7eb;
            background: #f9fafb;
        }
        .stat-value {
            font-size: 28px;
            font-weight: bold;
            color: #7e22ce;
            margin: 10px 0;
        }
        .stat-label {
            color: #6b7280;
            font-size: 11px;
            text-transform: uppercase;
        }
        .quality-section {
            margin: 30px 0;
            padding: 20px;
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
        }
        .quality-bar {
            background: #e5e7eb;
            height: 25px;
            border-radius: 5px;
            overflow: hidden;
            margin: 10px 0;
        }
        .quality-fill {
            background: linear-gradient(90deg, #7e22ce, #a855f7);
            height: 100%;
            line-height: 25px;
            color: white;
            text-align: center;
            font-weight: bold;
        }
        .footer {
            margin-top: 40px;
            padding-top: 15px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            color: #9ca3af;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📊 Reporte General del Sistema</h1>
        <p><strong>Fecha de generación:</strong> {{ now()->format('d/m/Y H:i') }}</p>
        <p>Sistema de Asignación de Salones</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Grupos Activos</div>
            <div class="stat-value">{{ number_format($data['total_groups'] ?? 0) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Salones Disponibles</div>
            <div class="stat-value">{{ number_format($data['total_classrooms'] ?? 0) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Profesores Activos</div>
            <div class="stat-value">{{ number_format($data['total_teachers'] ?? 0) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Asignaciones</div>
            <div class="stat-value">{{ number_format($data['total_assignments'] ?? 0) }}</div>
        </div>
    </div>

    @if(isset($data['avg_quality_score']))
    <div class="quality-section">
        <h3 style="margin-top: 0; color: #92400e;">📈 Calidad Promedio</h3>
        <p>Puntuación promedio de asignaciones: <strong>{{ number_format($data['avg_quality_score'] * 100, 1) }}%</strong></p>
        <div class="quality-bar">
            <div class="quality-fill" style="width: {{ $data['avg_quality_score'] * 100 }}%">
                {{ number_format($data['avg_quality_score'] * 100, 1) }}%
            </div>
        </div>
        @if(isset($data['assignments_with_good_quality']))
        <p style="margin: 10px 0 0 0; font-size: 11px;">
            <strong>{{ $data['assignments_with_good_quality'] }}</strong> asignaciones con calidad superior al 70%
        </p>
        @endif
    </div>
    @endif

    <div class="footer">
        <p>Documento generado automáticamente por el Sistema de Asignación de Salones</p>
        <p>Universidad - Departamento de Gestión Académica</p>
    </div>
</body>
</html>
