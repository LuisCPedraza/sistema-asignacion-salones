<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Estadísticas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
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
        .section {
            margin: 25px 0;
            page-break-inside: avoid;
        }
        h2 {
            color: #374151;
            font-size: 15px;
            margin: 15px 0 10px 0;
            border-left: 4px solid #7e22ce;
            padding-left: 10px;
        }
        .quality-grid {
            display: table;
            width: 100%;
            margin: 15px 0;
        }
        .quality-item {
            display: table-cell;
            width: 25%;
            padding: 10px;
            text-align: center;
        }
        .quality-badge {
            padding: 8px 12px;
            border-radius: 5px;
            font-weight: bold;
            display: block;
            margin: 5px 0;
        }
        .badge-excellent { background: #d1fae5; color: #065f46; }
        .badge-good { background: #dbeafe; color: #1e40af; }
        .badge-fair { background: #fef3c7; color: #92400e; }
        .badge-poor { background: #fee2e2; color: #991b1b; }
        .quality-percent {
            font-size: 24px;
            font-weight: bold;
            margin: 10px 0;
        }
        .quality-label {
            font-size: 10px;
            text-transform: uppercase;
            color: #6b7280;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        th {
            background: #f3f4f6;
            padding: 8px;
            text-align: left;
            font-size: 11px;
            border-bottom: 2px solid #7e22ce;
        }
        td {
            padding: 8px;
            border-bottom: 1px solid #e5e7eb;
        }
        .trend-bar {
            background: #e5e7eb;
            height: 15px;
            border-radius: 3px;
            overflow: hidden;
            display: inline-block;
            width: 100px;
            vertical-align: middle;
        }
        .trend-fill {
            background: linear-gradient(90deg, #7e22ce, #a855f7);
            height: 100%;
        }
        .conflict-box {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 15px 0;
        }
        .conflict-box.success {
            background: #d1fae5;
            border-left-color: #059669;
        }
        .alert {
            padding: 12px;
            margin: 10px 0;
            border-radius: 5px;
            font-size: 11px;
        }
        .alert-warning { background: #fef3c7; border-left: 3px solid #f59e0b; }
        .alert-info { background: #dbeafe; border-left: 3px solid #3b82f6; }
        .footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            color: #9ca3af;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📈 Reporte de Estadísticas y Tendencias</h1>
        <p><strong>Fecha:</strong> {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    @if(isset($qualityDistribution))
    <div class="section">
        <h2>🎯 Distribución de Calidad</h2>
        <div class="quality-grid">
            <div class="quality-item">
                <div class="quality-badge badge-excellent">Excelente</div>
                <div class="quality-percent" style="color: #065f46;">
                    {{ number_format($qualityDistribution['excellent'] ?? 0, 1) }}%
                </div>
                <div class="quality-label">90-100%</div>
            </div>
            <div class="quality-item">
                <div class="quality-badge badge-good">Buena</div>
                <div class="quality-percent" style="color: #1e40af;">
                    {{ number_format($qualityDistribution['good'] ?? 0, 1) }}%
                </div>
                <div class="quality-label">80-89%</div>
            </div>
            <div class="quality-item">
                <div class="quality-badge badge-fair">Regular</div>
                <div class="quality-percent" style="color: #92400e;">
                    {{ number_format($qualityDistribution['fair'] ?? 0, 1) }}%
                </div>
                <div class="quality-label">70-79%</div>
            </div>
            <div class="quality-item">
                <div class="quality-badge badge-poor">Baja</div>
                <div class="quality-percent" style="color: #991b1b;">
                    {{ number_format($qualityDistribution['poor'] ?? 0, 1) }}%
                </div>
                <div class="quality-label">&lt; 70%</div>
            </div>
        </div>
    </div>
    @endif

    @if(isset($monthlyTrends) && is_array($monthlyTrends) && count($monthlyTrends) > 0)
    <div class="section">
        <h2>📊 Tendencias Mensuales (Últimos 6 Meses)</h2>
        <table>
            <thead>
                <tr>
                    <th>Mes</th>
                    <th>Asignaciones</th>
                    <th>Tendencia</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $counts = array_column($monthlyTrends, 'assignments');
                    $maxAssignments = count($counts) > 0 ? max($counts) : 1;
                @endphp
                @foreach($monthlyTrends as $trend)
                <tr>
                    <td><strong>{{ $trend['month'] }}</strong></td>
                    <td>{{ number_format($trend['assignments']) }}</td>
                    <td>
                        @if($maxAssignments > 0)
                        <div class="trend-bar">
                            <div class="trend-fill" style="width: {{ ($trend['assignments'] / $maxAssignments) * 100 }}%"></div>
                        </div>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    @if(isset($conflictStats))
    <div class="section">
        <h2>⚠️ Estadísticas de Conflictos</h2>
        <div class="conflict-box {{ ($conflictStats['total_conflicts'] ?? 0) == 0 ? 'success' : '' }}">
            <p style="margin: 5px 0;">
                <strong style="font-size: 14px;">Total de conflictos:</strong> 
                <span style="font-size: 20px; font-weight: bold; color: {{ ($conflictStats['total_conflicts'] ?? 0) == 0 ? '#065f46' : '#92400e' }};">
                    {{ $conflictStats['total_conflicts'] ?? 0 }}
                </span>
            </p>
            <p style="margin: 5px 0;">
                <strong>Porcentaje de conflictos:</strong> {{ number_format($conflictStats['conflict_percentage'] ?? 0, 2) }}%
            </p>
            @if(($conflictStats['total_conflicts'] ?? 0) == 0)
            <p style="margin: 10px 0 0 0; color: #065f46; font-weight: bold;">
                ✓ No se detectaron conflictos en las asignaciones actuales
            </p>
            @else
            <p style="margin: 10px 0 0 0; color: #92400e;">
                ⚠ Se recomienda revisar las asignaciones con conflictos
            </p>
            @endif
        </div>
    </div>
    @endif

    @if(isset($qualityDistribution))
    <div class="section">
        <h2>💡 Recomendaciones</h2>
        
        @if(($qualityDistribution['poor'] ?? 0) > 20)
        <div class="alert alert-warning">
            <strong>⚠ Atención:</strong> Más del 20% de asignaciones tienen calidad baja. 
            Se recomienda revisar los criterios de asignación.
        </div>
        @endif

        @if(($qualityDistribution['excellent'] ?? 0) > 50)
        <div class="alert alert-info">
            <strong>✓ Excelente:</strong> Más del 50% de asignaciones tienen calidad excelente. 
            El sistema está funcionando óptimamente.
        </div>
        @endif

        @if(isset($conflictStats) && ($conflictStats['conflict_percentage'] ?? 0) > 5)
        <div class="alert alert-warning">
            <strong>⚠ Conflictos detectados:</strong> El {{ number_format($conflictStats['conflict_percentage'], 2) }}% 
            de asignaciones presentan conflictos. Se sugiere ejecutar el algoritmo de optimización.
        </div>
        @endif
    </div>
    @endif

    <div class="footer">
        <p>Documento generado automáticamente - Sistema de Asignación de Salones</p>
        <p>Universidad - Departamento de Gestión Académica</p>
    </div>
</body>
</html>
