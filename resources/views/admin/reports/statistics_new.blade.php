@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="bg-gradient-primary text-white rounded p-4 shadow d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-1"><i class="fas fa-chart-pie"></i> Estadísticas Detalladas</h1>
                    <p class="mb-0 opacity-75">Análisis de calidad, tendencias y métricas del sistema</p>
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

    <!-- Distribución de Calidad -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0">
                    <h5 class="mb-0"><i class="fas fa-chart-bar"></i> Distribución de Calidad de Asignaciones</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center mb-3">
                            <div class="mb-2">
                                <span class="badge bg-success" style="font-size: 16px; padding: 10px 15px;">
                                    {{ $qualityDistribution['excellent'] }}%
                                </span>
                            </div>
                            <small class="text-muted d-block">Excelente (90-100%)</small>
                            <div class="progress mt-2" style="height: 8px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $qualityDistribution['excellent'] }}%"></div>
                            </div>
                        </div>
                        <div class="col-md-3 text-center mb-3">
                            <div class="mb-2">
                                <span class="badge bg-info" style="font-size: 16px; padding: 10px 15px;">
                                    {{ $qualityDistribution['good'] }}%
                                </span>
                            </div>
                            <small class="text-muted d-block">Buena (80-89%)</small>
                            <div class="progress mt-2" style="height: 8px;">
                                <div class="progress-bar bg-info" role="progressbar" style="width: {{ $qualityDistribution['good'] }}%"></div>
                            </div>
                        </div>
                        <div class="col-md-3 text-center mb-3">
                            <div class="mb-2">
                                <span class="badge bg-warning" style="font-size: 16px; padding: 10px 15px;">
                                    {{ $qualityDistribution['fair'] }}%
                                </span>
                            </div>
                            <small class="text-muted d-block">Regular (70-79%)</small>
                            <div class="progress mt-2" style="height: 8px;">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $qualityDistribution['fair'] }}%"></div>
                            </div>
                        </div>
                        <div class="col-md-3 text-center mb-3">
                            <div class="mb-2">
                                <span class="badge bg-danger" style="font-size: 16px; padding: 10px 15px;">
                                    {{ $qualityDistribution['poor'] }}%
                                </span>
                            </div>
                            <small class="text-muted d-block">Baja (&lt;70%)</small>
                            <div class="progress mt-2" style="height: 8px;">
                                <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $qualityDistribution['poor'] }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tendencias Mensuales -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0">
                    <h5 class="mb-0"><i class="fas fa-chart-line"></i> Tendencias Mensuales (Últimos 6 Meses)</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    @foreach($monthlyTrends as $trend)
                                        <th class="text-center text-muted small">{{ $trend['month'] }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    @foreach($monthlyTrends as $trend)
                                        <td class="text-center">
                                            <div style="display: flex; justify-content: center; align-items: flex-end; height: 60px; gap: 5px;">
                                                <div style="background: #667eea; width: 80%; height: {{ max(($trend['assignments'] / 10), 5) }}px; border-radius: 3px;" 
                                                     title="{{ $trend['assignments'] }} asignaciones"></div>
                                            </div>
                                            <strong class="d-block mt-2">{{ $trend['assignments'] }}</strong>
                                        </td>
                                    @endforeach
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas de Conflictos -->
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0">
                    <h5 class="mb-0"><i class="fas fa-exclamation-triangle"></i> Conflictos Detectados</h5>
                </div>
                <div class="card-body text-center">
                    @if($conflictStats['total_conflicts'] > 0)
                        <div class="h1 text-warning mb-2">{{ $conflictStats['total_conflicts'] }}</div>
                        <small class="text-muted d-block">Conflictos Totales</small>
                        <div class="alert alert-warning mt-3 mb-0">
                            <strong>{{ $conflictStats['conflict_percentage'] }}%</strong> de todas las asignaciones tienen conflictos
                        </div>
                    @else
                        <div class="h1 text-success mb-2">✓</div>
                        <small class="text-muted d-block">Sin Conflictos</small>
                        <div class="alert alert-success mt-3 mb-0">
                            Excelente - No se detectaron conflictos en el sistema
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Resumen de Asignaciones -->
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0">
                    <h5 class="mb-0"><i class="fas fa-list-check"></i> Resumen de Asignaciones</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 border-end">
                            <small class="text-muted d-block">Asignaciones</small>
                            <div class="h3 fw-bold text-primary">{{ $generalStats['total_assignments'] }}</div>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Calidad Promedio</small>
                            <div class="h3 fw-bold text-success">{{ round($generalStats['avg_quality_score'] * 100, 1) }}%</div>
                        </div>
                    </div>
                    <div class="progress mt-3" style="height: 25px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ round($generalStats['avg_quality_score'] * 100, 1) }}%;" 
                             aria-valuenow="{{ round($generalStats['avg_quality_score'] * 100, 1) }}" aria-valuemin="0" aria-valuemax="100">
                            {{ round($generalStats['avg_quality_score'] * 100, 1) }}%
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recomendaciones -->
    <div class="row">
        <div class="col-12">
            <div class="alert alert-info border-0" role="alert">
                <h6 class="alert-heading"><i class="fas fa-lightbulb"></i> Recomendaciones</h6>
                <ul class="mb-0 ps-3">
                    @if($qualityDistribution['poor'] > 20)
                        <li>Se recomienda revisar las asignaciones con baja calidad (&lt;70%) y ajustar los parámetros del algoritmo.</li>
                    @endif
                    @if($conflictStats['total_conflicts'] > 0)
                        <li>Existen {{ $conflictStats['total_conflicts'] }} conflictos detectados. Considere ejecutar el algoritmo nuevamente con ajustes.</li>
                    @endif
                    @if($generalStats['avg_quality_score'] >= 0.8)
                        <li>✓ Excelente calidad de asignaciones. El sistema está funcionando óptimamente.</li>
                    @endif
                    <li>Monitoree regularmente estas métricas para mantener la efectividad del sistema.</li>
                </ul>
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
