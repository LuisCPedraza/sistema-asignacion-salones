@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>👁️ Detalle de Mantenimiento (HU20)</h1>
        <div>
            <a href="{{ route('infraestructura.maintenance.edit', $maintenance) }}" class="btn btn-warning">
                ✏️ Editar
            </a>
            <form method="POST" action="{{ route('infraestructura.maintenance.destroy', $maintenance) }}" 
                  style="display: inline;"
                  onsubmit="return confirm('¿Eliminar este mantenimiento?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">🗑️ Eliminar</button>
            </form>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>✅ Éxito:</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">📋 Información General</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="text-muted small">Título</p>
                            <h5>{{ $maintenance->title }}</h5>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small">Salón</p>
                            <h5>{{ $maintenance->classroom->name }}</h5>
                            <small class="text-muted">Código: {{ $maintenance->classroom->code }}</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <p class="text-muted small">Descripción</p>
                        <p>{{ $maintenance->description ?? 'Sin descripción' }}</p>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">🔧 Detalles Técnicos</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="text-muted small">Tipo de Mantenimiento</p>
                            @if ($maintenance->type === 'preventivo')
                                <span class="badge bg-info" style="font-size: 1rem;">🛡️ Preventivo</span>
                            @else
                                <span class="badge bg-warning" style="font-size: 1rem;">🔨 Correctivo</span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small">Estado</p>
                            @switch($maintenance->status)
                                @case('pendiente')
                                    <span class="badge bg-warning" style="font-size: 1rem;">⏳ Pendiente</span>
                                    @break
                                @case('en_progreso')
                                    <span class="badge bg-primary" style="font-size: 1rem;">🔄 En Progreso</span>
                                    @break
                                @case('completado')
                                    <span class="badge bg-success" style="font-size: 1rem;">✅ Completado</span>
                                    @break
                                @case('cancelado')
                                    <span class="badge bg-danger" style="font-size: 1rem;">🚫 Cancelado</span>
                                    @break
                            @endswitch
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <p class="text-muted small">Responsable</p>
                            <p>{{ $maintenance->responsible ?? 'No asignado' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small">Costo</p>
                            <p>
                                @if ($maintenance->cost)
                                    <strong>${{ number_format($maintenance->cost, 2) }}</strong>
                                @else
                                    Sin especificar
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">📅 Fechas y Tiempos</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="text-muted small">Fecha Programada</p>
                            <p>
                                @if ($maintenance->scheduled_date)
                                    {{ $maintenance->scheduled_date->format('d/m/Y') }}
                                @else
                                    No especificada
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small">Creado el</p>
                            <p>{{ $maintenance->created_at->format('d/m/Y H:i:s') }}</p>
                        </div>
                    </div>

                    @if ($maintenance->start_date || $maintenance->end_date)
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="text-muted small">Fecha de Inicio</p>
                                <p>
                                    @if ($maintenance->start_date)
                                        {{ $maintenance->start_date->format('d/m/Y H:i:s') }}
                                    @else
                                        No iniciado
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted small">Fecha de Finalización</p>
                                <p>
                                    @if ($maintenance->end_date)
                                        {{ $maintenance->end_date->format('d/m/Y H:i:s') }}
                                    @else
                                        No finalizado
                                    @endif
                                </p>
                            </div>
                        </div>

                        @if ($maintenance->start_date && $maintenance->end_date)
                            <hr>
                            <p class="text-muted small">Duración Total</p>
                            <p><strong>{{ $maintenance->duration ?? 'N/A' }}</strong></p>
                        @endif
                    @endif
                </div>
            </div>

            @if ($maintenance->notes)
                <div class="card mb-4">
                    <div class="card-header bg-warning">
                        <h5 class="mb-0">📌 Notas Adicionales</h5>
                    </div>
                    <div class="card-body">
                        <p>{{ $maintenance->notes }}</p>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">⚡ Acciones Rápidas</h5>
                </div>
                <div class="card-body">
                    @if ($maintenance->status === 'pendiente')
                        <form method="POST" action="{{ route('infraestructura.maintenance.mark-in-progress', $maintenance) }}" class="mb-2">
                            @csrf
                            <button type="submit" class="btn btn-primary w-100">
                                🔄 Marcar como En Progreso
                            </button>
                        </form>
                    @endif

                    @if ($maintenance->status !== 'completado' && $maintenance->status !== 'cancelado')
                        <form method="POST" action="{{ route('infraestructura.maintenance.mark-completed', $maintenance) }}" class="mb-2">
                            @csrf
                            <button type="submit" class="btn btn-success w-100">
                                ✅ Marcar como Completado
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('infraestructura.maintenance.edit', $maintenance) }}" class="btn btn-warning w-100">
                        ✏️ Editar Información
                    </a>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">📊 Resumen</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled small">
                        <li class="mb-2">
                            <strong>ID:</strong> #{{ $maintenance->id }}
                        </li>
                        <li class="mb-2">
                            <strong>Creado:</strong> {{ $maintenance->created_at->diffForHumans() }}
                        </li>
                        <li class="mb-2">
                            <strong>Actualizado:</strong> {{ $maintenance->updated_at->diffForHumans() }}
                        </li>
                        <li>
                            <strong>Salón Capacidad:</strong> {{ $maintenance->classroom->capacity ?? 'N/A' }} personas
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('infraestructura.maintenance.index') }}" class="btn btn-secondary">
            ← Volver a Mantenimientos
        </a>
        <a href="{{ route('infraestructura.dashboard') }}" class="btn btn-secondary">
            ← Volver al Dashboard
        </a>
    </div>
</div>
@endsection
