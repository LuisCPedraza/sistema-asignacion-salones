@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>⚙️ Configuración del Sistema (HU19)</h1>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">← Volver al Dashboard</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <strong>✅ Éxito:</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <strong>❌ Error:</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Tarjetas de resumen -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">📋 Institución</h5>
                    <p><strong>Nombre:</strong> {{ $groupedConfigs['institution']->firstWhere('key', 'institution.name')?->value ?? 'N/A' }}</p>
                    <p><strong>Código:</strong> {{ $groupedConfigs['institution']->firstWhere('key', 'institution.code')?->value ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">🕐 Horario Laboral</h5>
                    <p><strong>Inicio:</strong> {{ $groupedConfigs['schedule']->firstWhere('key', 'schedule.work_start_time')?->value ?? 'N/A' }}</p>
                    <p><strong>Fin:</strong> {{ $groupedConfigs['schedule']->firstWhere('key', 'schedule.work_end_time')?->value ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">🍽️ Almuerzo</h5>
                    <p><strong>Inicio:</strong> {{ $groupedConfigs['schedule']->firstWhere('key', 'schedule.lunch_start_time')?->value ?? 'N/A' }}</p>
                    <p><strong>Fin:</strong> {{ $groupedConfigs['schedule']->firstWhere('key', 'schedule.lunch_end_time')?->value ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">🔧 Algoritmo</h5>
                    <p><strong>Score Mínimo:</strong> {{ (floatval($groupedConfigs['algorithm']->firstWhere('key', 'algorithm.min_score_threshold')?->value ?? 0.6) * 100) }}%</p>
                    <p><strong>Intentos Máx:</strong> {{ $groupedConfigs['algorithm']->firstWhere('key', 'algorithm.max_attempts')?->value ?? '15' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de todas las configuraciones -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">📑 Todas las Configuraciones</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Clave</th>
                        <th>Valor</th>
                        <th>Tipo</th>
                        <th>Descripción</th>
                        <th>Última Actualización</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($configs as $key => $config)
                        <tr>
                            <td>
                                <code class="bg-light px-2 py-1 rounded">{{ $config->key }}</code>
                            </td>
                            <td>
                                <strong>{{ $config->value }}</strong>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $config->type }}</span>
                            </td>
                            <td>{{ $config->description ?? '-' }}</td>
                            <td>{{ $config->updated_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Botón de edición -->
    <div class="mb-4">
        <a href="{{ route('admin.config.edit') }}" class="btn btn-primary btn-lg">
            ✏️ Editar Configuración
        </a>
    </div>
</div>

<style>
    .card {
        border: none;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        margin-bottom: 1rem;
    }
    .card-header {
        border-bottom: 2px solid #0d6efd;
    }
</style>
@endsection