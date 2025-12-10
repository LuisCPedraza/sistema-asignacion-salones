<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Asistencias - Sistema de Asignación</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: #f8fafc;
            color: #334155;
        }
        .header {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            padding: 1.25rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 20px rgba(59, 130, 246, 0.3);
        }
        .logo {
            font-size: 1.5rem;
            font-weight: bold;
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .btn-logout {
            background: rgba(255,255,255,0.2);
            color: white;
            padding: 0.625rem 1.25rem;
            text-decoration: none;
            border-radius: 8px;
            border: 1px solid rgba(255,255,255,0.3);
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-logout:hover {
            background: rgba(255,255,255,0.3);
        }
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }
        .breadcrumb {
            margin-bottom: 2rem;
            font-size: 0.9rem;
        }
        .breadcrumb a {
            color: #3b82f6;
            text-decoration: none;
        }
        .breadcrumb a:hover {
            text-decoration: underline;
        }
        .btn-back {
            background: #64748b;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 2rem;
            transition: all 0.3s;
        }
        .btn-back:hover {
            background: #475569;
        }
        .course-info {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            margin-bottom: 2rem;
            border-left: 5px solid #3b82f6;
        }
        .course-info h1 {
            color: #1e293b;
            margin-bottom: 1rem;
            font-size: 1.8rem;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }
        .info-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #64748b;
        }
        .info-icon {
            color: #3b82f6;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            position: relative;
            overflow: hidden;
        }
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
        }
        .stat-card.presentes::before {
            background: linear-gradient(90deg, #10b981, #059669);
        }
        .stat-card.ausentes::before {
            background: linear-gradient(90deg, #ef4444, #dc2626);
        }
        .stat-card.tardanzas::before {
            background: linear-gradient(90deg, #f59e0b, #ea580c);
        }
        .stat-card.promedio::before {
            background: linear-gradient(90deg, #3b82f6, #2563eb);
        }
        .stat-label {
            color: #64748b;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }
        .stat-value {
            font-size: 2rem;
            font-weight: bold;
            color: #1e293b;
        }
        .stat-trend {
            font-size: 0.85rem;
            margin-top: 0.5rem;
        }
        .trend-positive {
            color: #10b981;
        }
        .trend-negative {
            color: #ef4444;
        }
        .history-table {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        .table-header {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
            padding: 1.5rem;
        }
        .table-header h2 {
            margin: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        thead th {
            background: #f8fafc;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: #475569;
            border-bottom: 2px solid #e2e8f0;
        }
        tbody td {
            padding: 1rem;
            border-bottom: 1px solid #e2e8f0;
            color: #334155;
        }
        tbody tr:hover {
            background: #f8fafc;
        }
        tbody tr:last-child td {
            border-bottom: none;
        }
        .date-cell {
            font-weight: 600;
            color: #1e293b;
        }
        .count-badge {
            display: inline-block;
            padding: 0.375rem 0.75rem;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.9rem;
        }
        .badge-presente {
            background: #d1fae5;
            color: #065f46;
        }
        .badge-ausente {
            background: #fee2e2;
            color: #991b1b;
        }
        .badge-tardanza {
            background: #fed7aa;
            color: #92400e;
        }
        .progress-bar-container {
            width: 100%;
            height: 24px;
            background: #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
            position: relative;
        }
        .progress-bar {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: bold;
            color: white;
            transition: width 0.3s;
        }
        .progress-excellent {
            background: linear-gradient(90deg, #10b981, #059669);
        }
        .progress-good {
            background: linear-gradient(90deg, #3b82f6, #2563eb);
        }
        .progress-warning {
            background: linear-gradient(90deg, #f59e0b, #ea580c);
        }
        .progress-poor {
            background: linear-gradient(90deg, #ef4444, #dc2626);
        }
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #64748b;
        }
        .empty-state-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        .action-buttons {
            margin-top: 2rem;
            display: flex;
            gap: 1rem;
            justify-content: center;
        }
        .btn-action {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            padding: 0.875rem 2rem;
            border-radius: 8px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s;
        }
        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        }
        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            table {
                font-size: 0.85rem;
            }
            thead th, tbody td {
                padding: 0.75rem 0.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">📊 Historial de Asistencias</div>
        <div class="user-info">
            <span>{{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">Cerrar Sesión</button>
            </form>
        </div>
    </div>

    <div class="container">
        <div class="breadcrumb">
            <a href="{{ route('profesor.dashboard') }}">Dashboard</a> / 
            <a href="{{ route('profesor.asistencias.index') }}">Asistencias</a> / 
            <span>Historial</span>
        </div>

        <a href="{{ route('profesor.asistencias.index') }}" class="btn-back">← Volver a Asistencias</a>

        <div class="course-info">
            <h1>{{ $assignment->subject->name ?? 'Materia sin nombre' }}</h1>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-icon">🏷️</span>
                    <span>{{ $assignment->group->name ?? 'Sin grupo' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-icon">🏢</span>
                    <span>{{ $assignment->classroom->code ?? 'Sin aula' }}</span>
                </div>
                @if($assignment->day && $assignment->start_time)
                    <div class="info-item">
                        <span class="info-icon">🕐</span>
                        <span>{{ ucfirst($assignment->day) }}: {{ substr($assignment->start_time, 0, 5) }} - {{ substr($assignment->end_time, 0, 5) }}</span>
                    </div>
                @endif
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card promedio">
                <div class="stat-label">📈 Promedio de Asistencia</div>
                <div class="stat-value">{{ $promedioAsistencia }}%</div>
                <div class="stat-trend {{ $promedioAsistencia >= 85 ? 'trend-positive' : 'trend-negative' }}">
                    {{ $promedioAsistencia >= 85 ? '✅ Excelente' : ($promedioAsistencia >= 70 ? '⚠️ Aceptable' : '❌ Mejorar') }}
                </div>
            </div>
            <div class="stat-card presentes">
                <div class="stat-label">✅ Promedio Presentes</div>
                <div class="stat-value">{{ $promedioPresentes }}</div>
                <div class="stat-trend trend-positive">
                    Por clase
                </div>
            </div>
            <div class="stat-card ausentes">
                <div class="stat-label">❌ Promedio Ausentes</div>
                <div class="stat-value">{{ $promedioAusentes }}</div>
                <div class="stat-trend trend-negative">
                    Por clase
                </div>
            </div>
            <div class="stat-card tardanzas">
                <div class="stat-label">⏰ Promedio Tardanzas</div>
                <div class="stat-value">{{ $promedioTardanzas }}</div>
                <div class="stat-trend">
                    Por clase
                </div>
            </div>
        </div>

        <div class="history-table">
            <div class="table-header">
                <h2>📅 Registro de Asistencias (Últimos 10 días)</h2>
            </div>
            
            @if(empty($historial))
                <div class="empty-state">
                    <div class="empty-state-icon">📭</div>
                    <h3>No hay registros de asistencia</h3>
                    <p>Aún no se ha tomado asistencia para este grupo.</p>
                    <div class="action-buttons">
                        <a href="{{ route('profesor.asistencias.tomar', $assignment->id) }}" class="btn-action">
                            ✅ Tomar Asistencia Ahora
                        </a>
                    </div>
                </div>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>📅 Fecha</th>
                            <th>✅ Presentes</th>
                            <th>❌ Ausentes</th>
                            <th>⏰ Tardanzas</th>
                            <th>📝 Justificados</th>
                            <th>📊 % Asistencia</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($historial as $registro)
                            <tr>
                                <td class="date-cell">
                                    {{ \Carbon\Carbon::parse($registro['fecha'])->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                                </td>
                                <td>
                                    <span class="count-badge badge-presente">{{ $registro['presentes'] }}</span>
                                </td>
                                <td>
                                    <span class="count-badge badge-ausente">{{ $registro['ausentes'] }}</span>
                                </td>
                                <td>
                                    <span class="count-badge badge-tardanza">{{ $registro['tardanzas'] }}</span>
                                </td>
                                <td>
                                    <span class="count-badge badge-presente">{{ $registro['justificados'] }}</span>
                                </td>
                                <td>
                                    <div class="progress-bar-container">
                                        @php
                                            $porcentaje = $registro['porcentaje_asistencia'];
                                            $clase = $porcentaje >= 90 ? 'progress-excellent' : 
                                                    ($porcentaje >= 75 ? 'progress-good' : 
                                                    ($porcentaje >= 60 ? 'progress-warning' : 'progress-poor'));
                                        @endphp
                                        <div class="progress-bar {{ $clase }}" style="width: {{ $porcentaje }}%">
                                            {{ $porcentaje }}%
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="action-buttons">
                    <a href="{{ route('profesor.asistencias.tomar', $assignment->id) }}" class="btn-action">
                        ✅ Tomar Asistencia Nueva
                    </a>
                </div>
            @endif
        </div>
    </div>
</body>
</html>
