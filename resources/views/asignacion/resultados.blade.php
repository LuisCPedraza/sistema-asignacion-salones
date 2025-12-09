@extends('layouts.app')

@section('title', 'Resultados de Asignación')

@section('content')
<div class="container-fluid py-4">
    
    <!-- ENCABEZADO -->
    <div class="bg-success text-white p-4 rounded mb-4 shadow">
        <h1 class="mb-3"><i class="fas fa-check-circle"></i> Asignación Automática Completada</h1>
        
        <!-- ESTADÍSTICAS -->
        <div class="row g-3">
            <div class="col-md-3">
                <div class="bg-white bg-opacity-25 rounded p-3">
                    <div class="fs-2 fw-bold">{{ $totalAsignaciones }}</div>
                    <small>Asignaciones</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="bg-white bg-opacity-25 rounded p-3">
                    <div class="fs-2 fw-bold">{{ number_format($scorePromedio * 100, 1) }}%</div>
                    <small>Calidad Promedio</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="bg-white bg-opacity-25 rounded p-3">
                    <div class="fs-2 fw-bold">{{ $asignacionesExcelentes }}</div>
                    <small>Excelentes (80%+)</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="bg-white bg-opacity-25 rounded p-3">
                    <div class="fs-2 fw-bold">{{ $asignacionesRegulares }}</div>
                    <small>Por Revisar (&lt;40%)</small>
                </div>
            </div>
        </div>
    </div>

    <!-- ACCIONES -->
    <div class="d-flex gap-2 mb-4 flex-wrap">
        <a href="{{ route('asignacion.automatica') }}" class="btn btn-primary">
            <i class="fas fa-sync-alt"></i> Nueva Asignación
        </a>
        <a href="{{ route('visualizacion.horario.semestral') }}" class="btn btn-secondary">
            <i class="fas fa-calendar"></i> Ver Horario
        </a>
        <a href="{{ route('asignacion.reglas') }}" class="btn btn-info">
            <i class="fas fa-cog"></i> Configurar Reglas
        </a>
    </div>

    <!-- TABLA -->
    <div class="card shadow">
        <div class="card-header bg-dark text-white">
            <h2 class="mb-0 h5"><i class="fas fa-list"></i> Detalle de Asignaciones</h2>
            <small class="text-white-50">Ordenadas por calidad (mayor a menor)</small>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Grupo</th>
                        <th>Profesor</th>
                        <th>Salón</th>
                        <th>Día</th>
                        <th>Horario</th>
                        <th class="text-center">Calidad</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($asignaciones as $assignment)
                        @php
                            $percentage = $algorithm->getScorePercentage($assignment);
                            $color = $algorithm->getScoreColor($assignment);
                            
                            $rowClass = match($color) {
                                'green' => 'table-success',
                                'yellow' => 'table-warning',
                                'orange' => 'table-warning',
                                'red' => 'table-danger',
                                default => ''
                            };
                            
                            $badgeColor = match($color) {
                                'green' => 'bg-success',
                                'yellow' => 'bg-warning text-dark',
                                'orange' => 'bg-warning text-dark',
                                'red' => 'bg-danger',
                                default => 'bg-secondary'
                            };
                        @endphp
                        <tr class="{{ $rowClass }}">
                            <td>
                                <div class="fw-bold text-dark">{{ $assignment->group->name ?? 'N/A' }}</div>
                                <small class="text-muted">
                                    {{ $assignment->group->level ?? 'N/A' }} • 
                                    <strong>{{ $assignment->group->student_count ?? 0 }} estudiantes</strong>
                                </small>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $assignment->teacher->full_name ?? 'N/A' }}</div>
                                <small class="text-muted">{{ $assignment->teacher->specialty ?? 'N/A' }}</small>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $assignment->classroom->name ?? 'N/A' }}</div>
                                <small class="text-muted">Cap: {{ $assignment->classroom->capacity ?? '0' }}</small>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ ucfirst($assignment->day) }}</span>
                            </td>
                            <td>
                                <code class="text-dark">
                                    {{ \Carbon\Carbon::parse($assignment->start_time)->format('H:i') }} - 
                                    {{ \Carbon\Carbon::parse($assignment->end_time)->format('H:i') }}
                                </code>
                            </td>
                            <td class="text-center">
                                <span class="badge {{ $badgeColor }} fs-6">
                                    {{ $percentage }}%
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="fas fa-inbox fs-1 mb-2 d-block"></i>
                                No hay asignaciones disponibles. 
                                <a href="{{ route('asignacion.automatica') }}" class="text-primary">Crear asignación</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- LEYENDA -->
        <div class="card-footer bg-light">
            <h6 class="mb-3"><i class="fas fa-chart-bar"></i> Escala de Calidad:</h6>
            <div class="row g-2">
                <div class="col-md-3">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-success">80-100%</span>
                        <span>Excelente</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-warning text-dark">60-79%</span>
                        <span>Bueno</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-warning text-dark">40-59%</span>
                        <span>Regular</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-danger">0-39%</span>
                        <span>Por Revisar</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
