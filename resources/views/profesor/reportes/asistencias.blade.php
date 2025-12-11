@extends('layouts.app')

@section('content')
<style>
    .reportes-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        color: #2563eb;
        text-decoration: none;
        font-size: 0.95rem;
        margin-bottom: 1.5rem;
        transition: color 0.3s;
    }

    .back-link:hover {
        color: #1d4ed8;
    }

    .back-link svg {
        width: 1.25rem;
        height: 1.25rem;
        margin-right: 0.5rem;
    }

    .page-header {
        margin-bottom: 2rem;
    }

    .page-header h1 {
        font-size: 2rem;
        font-weight: bold;
        color: #111827;
        margin-bottom: 0.5rem;
    }

    .page-header p {
        color: #6b7280;
        font-size: 0.95rem;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 0.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        padding: 1.5rem;
    }

    .stat-label {
        color: #6b7280;
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
    }

    .stat-value {
        font-size: 1.875rem;
        font-weight: bold;
        color: #111827;
    }

    .stat-value.blue {
        color: #2563eb;
    }

    .stat-value.red {
        color: #dc2626;
    }

    .action-bar {
        margin-bottom: 2rem;
    }

    .btn-primary {
        display: inline-flex;
        align-items: center;
        background: #dc2626;
        color: white;
        text-decoration: none;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 600;
        font-size: 0.95rem;
        transition: background 0.3s;
    }

    .btn-primary:hover {
        background: #991b1b;
    }

    .btn-primary svg {
        width: 1.25rem;
        height: 1.25rem;
        margin-right: 0.5rem;
    }

    .table-container {
        background: white;
        border-radius: 0.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .table-container table {
        width: 100%;
        border-collapse: collapse;
    }

    .table-container thead {
        background: #f3f4f6;
        border-bottom: 1px solid #e5e7eb;
    }

    .table-container th {
        padding: 1rem;
        text-align: left;
        font-size: 0.875rem;
        font-weight: 600;
        color: #111827;
    }

    .table-container tbody tr {
        border-bottom: 1px solid #e5e7eb;
        transition: background 0.2s;
    }

    .table-container tbody tr:hover {
        background: #f9fafb;
    }

    .table-container td {
        padding: 1rem;
        font-size: 0.875rem;
        color: #374151;
    }

    .badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .badge.green {
        background: #d1fae5;
        color: #065f46;
    }

    .badge.red {
        background: #fee2e2;
        color: #7f1d1d;
    }

    .badge.yellow {
        background: #fef3c7;
        color: #92400e;
    }

    .progress-bar {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .progress-track {
        flex: 1;
        height: 0.5rem;
        background: #e5e7eb;
        border-radius: 9999px;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        background: #22c55e;
        transition: width 0.3s;
    }

    .progress-fill.warning {
        background: #f59e0b;
    }

    .progress-fill.danger {
        background: #dc2626;
    }

    .percentage {
        min-width: 3.5rem;
        text-align: right;
        font-weight: 600;
    }
</style>

<div class="reportes-container">
    <!-- Back Link -->
    <a href="{{ route('profesor.reportes.index') }}" class="back-link">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
        Volver a Reportes
    </a>

    <!-- Header -->
    <div class="page-header">
        <h1>Reporte de Asistencias</h1>
        <p>{{ $assignment->subject->nombre }} - {{ $assignment->group->nombre }}</p>
    </div>

    <!-- Estadísticas -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Total de Clases</div>
            <div class="stat-value">{{ $estadisticas['totalClases'] }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Promedio Asistencia</div>
            <div class="stat-value blue">{{ round($estadisticas['promedioAsistencia'], 2) }}%</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Estudiantes en Alerta</div>
            <div class="stat-value red">{{ $estadisticas['estudiantesAlerta'] }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Total Estudiantes</div>
            <div class="stat-value">{{ $asistenciasPorEstudiante->count() }}</div>
        </div>
    </div>

    <!-- Exportar PDF -->
    <div class="action-bar">
        <a href="{{ route('profesor.reportes.asistencias.pdf', $assignment->id) }}" class="btn-primary">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Descargar PDF
        </a>
    </div>

    <!-- Tabla de Asistencias -->
    <div class="table-container">
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
                        <td>
                            <span class="badge green">
                                {{ $asistencia['presentes'] }}
                            </span>
                        </td>
                        <td>
                            <span class="badge red">
                                {{ $asistencia['ausentes'] }}
                            </span>
                        </td>
                        <td>
                            <span class="badge yellow">
                                {{ $asistencia['tardanzas'] }}
                            </span>
                        </td>
                        <td>
                            <div class="progress-bar">
                                <div class="progress-track">
                                    <div class="progress-fill {{ $asistencia['porcentaje'] < 60 ? 'danger' : ($asistencia['porcentaje'] < 75 ? 'warning' : '') }}" 
                                         style="width: {{ $asistencia['porcentaje'] }}%"></div>
                                </div>
                                <div class="percentage">{{ $asistencia['porcentaje'] }}%</div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
