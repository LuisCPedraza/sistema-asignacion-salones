@extends('layouts.app')

@section('content')
<style>
    h1 { font-size: 32px; }
    p, label, .form-label, .form-select, .form-control, .btn, table, th, td { font-size: 20px; }
    small { font-size: 18px; }
    .badge { font-size: 18px; }
    .text-muted { font-size: 18px; }
</style>
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>📋 Auditoría del Sistema</h1>
            <p class="text-muted">Historial de cambios en el sistema</p>
        </div>
        <div class="col-md-4 text-end">
            <button class="btn btn-sm btn-secondary" data-bs-toggle="collapse" data-bs-target="#filterForm" aria-expanded="false">
                🔍 Filtros
            </button>
        </div>
    </div>

    <!-- Filtros -->
    <div class="collapse mb-4" id="filterForm">
        <div class="card card-body bg-light">
            <form method="get" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Modelo</label>
                    <select name="model" class="form-select form-select-sm">
                        <option value="">Todos los modelos</option>
                        @foreach($filters['models'] as $model)
                            <option value="{{ $model }}" {{ request('model') == $model ? 'selected' : '' }}>
                                {{ $model }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Acción</label>
                    <select name="action" class="form-select form-select-sm">
                        <option value="">Todas las acciones</option>
                        @foreach($filters['actions'] as $value => $label)
                            <option value="{{ $value }}" {{ request('action') == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Desde</label>
                    <input type="date" name="from_date" class="form-control form-control-sm" value="{{ request('from_date') }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Hasta</label>
                    <input type="date" name="to_date" class="form-control form-control-sm" value="{{ request('to_date') }}">
                </div>

                <div class="col-12">
                    <label class="form-label">Buscar</label>
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Descripción, modelo..." value="{{ request('search') }}">
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary btn-sm">🔎 Buscar</button>
                    <a href="{{ route('admin.audit.index') }}" class="btn btn-secondary btn-sm">Limpiar</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de logs -->
    @if($logs->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover table-sm">
                <thead class="table-light">
                    <tr>
                        <th style="width: 12%;">Fecha/Hora</th>
                        <th style="width: 15%;">Usuario</th>
                        <th style="width: 12%;">Modelo</th>
                        <th style="width: 12%;">Acción</th>
                        <th style="width: 35%;">Descripción</th>
                        <th style="width: 14%;text-align: center;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $log)
                        <tr>
                            <td>
                                <small class="text-muted">
                                    {{ $log->created_at->format('d/m/Y') }}<br>
                                    {{ $log->created_at->format('H:i:s') }}
                                </small>
                            </td>
                            <td>
                                <strong>{{ $log->user->name ?? 'Sistema' }}</strong>
                                <br>
                                <small class="text-muted">{{ $log->user->email ?? 'N/A' }}</small>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $log->model }}</span>
                                @if($log->model_id)
                                    <br><small class="text-muted">#{{ $log->model_id }}</small>
                                @endif
                            </td>
                            <td>
                                @php
                                    $actionBadgeClass = match($log->action) {
                                        'create' => 'bg-success',
                                        'update' => 'bg-primary',
                                        'delete' => 'bg-danger',
                                        'restore' => 'bg-warning',
                                        'export' => 'bg-secondary',
                                        default => 'bg-secondary'
                                    };
                                @endphp
                                <span class="badge {{ $actionBadgeClass }}">
                                    {{ $log->getActionLabel() }}
                                </span>
                            </td>
                            <td>
                                <small>
                                    <strong>{{ $log->description ?? 'Sin descripción' }}</strong>
                                    @if($log->ip_address)
                                        <br><span class="text-muted">IP: {{ $log->ip_address }}</span>
                                    @endif
                                </small>
                            </td>
                            <td style="text-align: center;">
                                <a href="{{ route('admin.audit.show', $log) }}" class="btn btn-sm btn-outline-primary">
                                    👁️ Ver
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-muted small">
                Mostrando {{ $logs->firstItem() }} a {{ $logs->lastItem() }} de {{ $logs->total() }} registros
            </div>
            {{ $logs->links() }}
        </div>
    @else
        <div class="alert alert-info" role="alert">
            <strong>ℹ️ No hay registros</strong><br>
            No se encontraron registros de auditoría que coincidan con los filtros seleccionados.
        </div>
    @endif
</div>

<style>
    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }
</style>
@endsection