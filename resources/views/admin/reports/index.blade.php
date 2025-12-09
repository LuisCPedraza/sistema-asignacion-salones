@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="bg-gradient-primary text-white rounded p-4 shadow">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="mb-1"><i class="fas fa-chart-bar"></i> Centro de Reportes</h1>
                        <p class="mb-0 opacity-75">Análisis y estadísticas del sistema de asignación</p>
                    </div>
                    <div>
                        <a href="{{ route('admin.reports.export.general.pdf') }}" class="btn btn-light btn-sm" title="Exportar reporte general a PDF">
                            <i class="fas fa-file-pdf"></i> Exportar PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas Generales -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="h1 text-primary mb-2"><i class="fas fa-graduation-cap"></i></div>
                    <div class="h3 fw-bold">{{ $stats['total_groups'] }}</div>
                    <small class="text-muted">Grupos Activos</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="h1 text-success mb-2"><i class="fas fa-door-open"></i></div>
                    <div class="h3 fw-bold">{{ $stats['total_classrooms'] }}</div>
                    <small class="text-muted">Salones Disponibles</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="h1 text-info mb-2"><i class="fas fa-chalkboard-user"></i></div>
                    <div class="h3 fw-bold">{{ $stats['total_teachers'] }}</div>
                    <small class="text-muted">Profesores Activos</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="h1 text-warning mb-2"><i class="fas fa-list-check"></i></div>
                    <div class="h3 fw-bold">{{ $stats['total_assignments'] }}</div>
                    <small class="text-muted">Asignaciones Totales</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Calidad Promedio -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3"><i class="fas fa-star"></i> Calidad de Asignaciones</h5>
                    <div class="text-center">
                        <div class="h2 fw-bold text-success">{{ round($stats['avg_quality_score'] * 100, 1) }}%</div>
                        <small class="text-muted">Puntuación Promedio</small>
                    </div>
                    <div class="progress mt-3" style="height: 25px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ round($stats['avg_quality_score'] * 100, 1) }}%" 
                             aria-valuenow="{{ round($stats['avg_quality_score'] * 100, 1) }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3"><i class="fas fa-check-circle"></i> Asignaciones de Calidad</h5>
                    <div class="text-center">
                        <div class="h2 fw-bold text-info">{{ $stats['assignments_with_good_quality'] }}</div>
                        <small class="text-muted">Con puntuación ≥ 0.8</small>
                    </div>
                    <div class="alert alert-info mt-3 mb-0">
                        <strong>{{ round(($stats['assignments_with_good_quality'] / max($stats['total_assignments'], 1)) * 100, 1) }}%</strong> de todas las asignaciones tienen buena calidad
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Opciones de Reportes -->
    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="card border-0 shadow-sm hover" style="cursor: pointer; transition: transform 0.2s;">
                <div class="card-body">
                    <h5 class="card-title mb-2"><i class="fas fa-building"></i> Utilización de Recursos</h5>
                    <p class="card-text text-muted mb-3">Análisis detallado de uso de salones y disponibilidad de profesores. Identifica cuellos de botella y oportunidades de optimización.</p>
                    <a href="{{ route('admin.reports.utilization') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-arrow-right"></i> Ver Reporte
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card border-0 shadow-sm hover" style="cursor: pointer; transition: transform 0.2s;">
                <div class="card-body">
                    <h5 class="card-title mb-2"><i class="fas fa-chart-pie"></i> Estadísticas Detalladas</h5>
                    <p class="card-text text-muted mb-3">Gráficos y métricas de calidad de asignación, distribución por período y tendencias de utilización. Datos para toma de decisiones.</p>
                    <a href="{{ route('admin.reports.statistics') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-arrow-right"></i> Ver Estadísticas
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Información Adicional -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="alert alert-info border-0" role="alert">
                <i class="fas fa-info-circle"></i> 
                <strong>Nota:</strong> Los reportes se actualizan en tiempo real basados en las asignaciones actuales del sistema. 
                Puedes filtrar por carrera y semestre para análisis específicos.
            </div>
        </div>
    </div>
</div>

<style>
    .hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
    }
    
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    }
</style>
@endsection
