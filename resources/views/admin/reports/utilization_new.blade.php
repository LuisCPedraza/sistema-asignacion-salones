@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="bg-gradient-primary text-white rounded p-4 shadow d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-1"><i class="fas fa-building"></i> Utilización de Recursos</h1>
                    <p class="mb-0 opacity-75">Análisis detallado de uso de salones y disponibilidad de profesores</p>
                </div>
                <a href="{{ route('admin.reports.index') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-5">
                            <label for="career_id" class="form-label">Carrera</label>
                            <select name="career_id" id="career_id" class="form-select">
                                <option value="">Todas las carreras</option>
                                @foreach($careers as $career)
                                    <option value="{{ $career->id }}" {{ $careerId == $career->id ? 'selected' : '' }}>
                                        {{ $career->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label for="semester_id" class="form-label">Semestre</label>
                            <select name="semester_id" id="semester_id" class="form-select">
                                <option value="">Todos los semestres</option>
                                @foreach($semesters as $semester)
                                    <option value="{{ $semester->id }}" {{ $semesterId == $semester->id ? 'selected' : '' }}>
                                        {{ $semester->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-filter"></i> Filtrar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas de Grupos -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="h1 text-primary mb-2"><i class="fas fa-graduation-cap"></i></div>
                    <div class="h3 fw-bold">{{ $groupStats['total_groups'] }}</div>
                    <small class="text-muted">Grupos en Filtro</small>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="h1 text-info mb-2"><i class="fas fa-users"></i></div>
                    <div class="h3 fw-bold">{{ $groupStats['total_students'] }}</div>
                    <small class="text-muted">Estudiantes Totales</small>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="h1 text-success mb-2"><i class="fas fa-chart-line"></i></div>
                    <div class="h3 fw-bold">{{ $groupStats['avg_group_size'] }}</div>
                    <small class="text-muted">Tamaño Promedio</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Utilización de Salones -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0">
                    <h5 class="mb-0"><i class="fas fa-door-open"></i> Utilización de Salones</h5>
                </div>
                <div class="card-body">
                    @if($classroomUtilization->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
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
                                    @foreach($classroomUtilization as $utilization)
                                        <tr>
                                            <td>
                                                <strong>{{ $utilization['classroom_name'] }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $utilization['classroom_code'] }}</small>
                                            </td>
                                            <td>{{ $utilization['classroom_capacity'] }} personas</td>
                                            <td>{{ $utilization['assignment_count'] }}</td>
                                            <td>
                                                <div class="progress" style="height: 20px;">
                                                    <div class="progress-bar" role="progressbar" style="width: {{ min($utilization['utilization_percentage'], 100) }}%;" 
                                                         aria-valuenow="{{ $utilization['utilization_percentage'] }}" aria-valuemin="0" aria-valuemax="100">
                                                        {{ $utilization['utilization_percentage'] }}%
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge {{ $utilization['avg_score'] >= 80 ? 'bg-success' : ($utilization['avg_score'] >= 70 ? 'bg-warning' : 'bg-danger') }}">
                                                    {{ $utilization['avg_score'] }}%
                                                </span>
                                            </td>
                                            <td>
                                                @if($utilization['avg_score'] >= 80)
                                                    <span class="badge bg-success">✓ Óptimo</span>
                                                @elseif($utilization['avg_score'] >= 70)
                                                    <span class="badge bg-warning">⚠ Revisar</span>
                                                @else
                                                    <span class="badge bg-danger">✗ Crítico</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle"></i> No hay datos de utilización para los filtros seleccionados.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Utilización de Profesores -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0">
                    <h5 class="mb-0"><i class="fas fa-chalkboard-user"></i> Utilización de Profesores</h5>
                </div>
                <div class="card-body">
                    @if($teacherUtilization->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Profesor</th>
                                        <th>Asignaciones</th>
                                        <th>Calidad Promedio</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($teacherUtilization as $utilization)
                                        <tr>
                                            <td><strong>{{ $utilization['teacher_name'] }}</strong></td>
                                            <td>{{ $utilization['assignment_count'] }}</td>
                                            <td>
                                                <span class="badge {{ $utilization['avg_score'] >= 80 ? 'bg-success' : ($utilization['avg_score'] >= 70 ? 'bg-warning' : 'bg-danger') }}">
                                                    {{ $utilization['avg_score'] }}%
                                                </span>
                                            </td>
                                            <td>
                                                @if($utilization['avg_score'] >= 80)
                                                    <span class="badge bg-success">✓ Óptimo</span>
                                                @elseif($utilization['avg_score'] >= 70)
                                                    <span class="badge bg-warning">⚠ Revisar</span>
                                                @else
                                                    <span class="badge bg-danger">✗ Crítico</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle"></i> No hay datos de profesores para los filtros seleccionados.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    }
</style>
@endsection
