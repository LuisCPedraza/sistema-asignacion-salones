@extends('layouts.app')

@section('title', 'Asignación Automática')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            <!-- MENSAJE DE ÉXITO -->
            @if(session('success_message'))
                <div class="alert alert-success alert-dismissible fade show shadow-lg" role="alert">
                    <div class="text-center">
                        <h2 class="h2 mb-3"><i class="fas fa-check-circle"></i> ¡Asignación Completada!</h2>
                        <p class="lead mb-4">{{ session('success_message') }}</p>
                        <a href="{{ route('asignacion.resultados') }}" class="btn btn-success btn-lg me-2">
                            <i class="fas fa-eye"></i> Ver Resultados
                        </a>
                        <a href="{{ route('visualizacion.horario.semestral') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-calendar"></i> Ver Horario Completo
                        </a>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error_message'))
                <div class="alert alert-danger alert-dismissible fade show shadow-lg" role="alert">
                    <h5><i class="fas fa-exclamation-triangle"></i> Error en la Asignación</h5>
                    <p class="mb-0">{{ session('error_message') }}</p>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(!session('success_message'))
                <!-- ENCABEZADO -->
                <div class="card shadow-lg border-0 mb-4">
                    <div class="card-header bg-gradient text-white p-4" style="background: linear-gradient(135deg, #0066cc 0%, #0052a3 100%);">
                        <h1 class="mb-2"><i class="fas fa-robot"></i> Asignación Automática de Salones</h1>
                        <p class="mb-0 opacity-90">El sistema asignará grupos a salones según las reglas y pesos configurados</p>
                    </div>

                    <!-- ESTADÍSTICAS -->
                    <div class="card-body">
                        <div class="row g-3 mb-4">
                            <div class="col-md-3">
                                <div class="card text-center border-primary shadow-sm h-100">
                                    <div class="card-body">
                                        <div class="h2 text-primary fw-bold">{{ $gruposCount }}</div>
                                        <p class="text-muted mb-0">Grupos Activos</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-center border-success shadow-sm h-100">
                                    <div class="card-body">
                                        <div class="h2 text-success fw-bold">{{ $salonesCount }}</div>
                                        <p class="text-muted mb-0">Salones</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-center border-info shadow-sm h-100">
                                    <div class="card-body">
                                        <div class="h2 text-info fw-bold">{{ $profesoresCount }}</div>
                                        <p class="text-muted mb-0">Profesores</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-center border-warning shadow-sm h-100">
                                    <div class="card-body">
                                        <div class="h2 text-warning fw-bold">{{ $franjasCount }}</div>
                                        <p class="text-muted mb-0">Franjas</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- REGLAS ACTIVAS -->
                <div class="card shadow-lg border-0 mb-4">
                    <div class="card-header bg-dark text-white p-4">
                        <h5 class="mb-0"><i class="fas fa-sliders-h"></i> Reglas Activas</h5>
                    </div>
                    <div class="card-body">
                        @forelse($reglasActivas as $regla)
                            <div class="row align-items-center mb-3 p-3 border-bottom">
                                <div class="col-md-8">
                                    <h6 class="mb-1 fw-bold">
                                        <i class="fas fa-check-circle text-success"></i>
                                        {{ $regla->name }}
                                    </h6>
                                    <p class="text-muted mb-0 small">{{ $regla->description ?? 'Sin descripción' }}</p>
                                </div>
                                <div class="col-md-4 text-end">
                                    <span class="badge bg-primary fs-6">
                                        Peso: {{ number_format($regla->weight * 100, 0) }}%
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="alert alert-warning mb-0">
                                <i class="fas fa-info-circle"></i> No hay reglas activas configuradas
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- BOTÓN DE EJECUCIÓN -->
                <div class="card shadow-lg border-0 mb-4">
                    <div class="card-body p-5 text-center">
                        <form action="{{ route('asignacion.ejecutar-automatica') }}" method="POST">
                            @csrf
                            <h3 class="mb-4 fw-bold">¿Listo para ejecutar la asignación automática?</h3>
                            <p class="lead text-muted mb-4">
                                Se procesarán <strong>{{ $gruposCount }} grupos</strong> con <strong>{{ $reglasActivas->count() }} reglas activas</strong>
                            </p>
                            <button type="submit" class="btn btn-danger btn-lg px-5 py-3">
                                <i class="fas fa-bolt"></i> EJECUTAR ASIGNACIÓN AUTOMÁTICA
                            </button>
                            <p class="text-muted small mt-4">
                                <i class="fas fa-hourglass-end"></i> Esta acción puede tomar varios segundos
                            </p>
                        </form>
                    </div>
                </div>

                <!-- INFORMACIÓN ÚTIL -->
                <div class="alert alert-info border-0 shadow-sm">
                    <h5 class="alert-heading"><i class="fas fa-lightbulb"></i> Información Importante</h5>
                    <ul class="mb-0 small">
                        <li>Las asignaciones se realizarán según los pesos configurados en las reglas</li>
                        <li>Se respetarán las disponibilidades de profesores y salones</li>
                        <li>Los resultados pueden revisarse en la página de resultados</li>
                        <li>Puedes crear nuevas asignaciones en cualquier momento</li>
                    </ul>
                </div>

            @endif
        </div>
    </div>
</div>

<style>
    .bg-gradient {
        background: linear-gradient(135deg, #0066cc 0%, #0052a3 100%) !important;
    }
    .card {
        transition: all 0.3s ease;
    }
    .card:hover {
        box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
    }
</style>
@endsection