@extends('layouts.app')

@section('content')
<style>
    h1 { font-size: 32px; }
    h5 { font-size: 20px; }
    strong, p, small, code, table, th, td { font-size: 20px; }
    .badge { font-size: 18px; }
    .text-muted { font-size: 18px; }
    .btn { font-size: 20px; }
    code {
        background-color: #f4f4f4;
        padding: 2px 6px;
        border-radius: 3px;
        font-family: 'Courier New', monospace;
        font-size: 0.875rem;
    }
</style>
<div class="container mt-4">
    <div class="row mb-4">
        <div class="col">
            <h1>📋 Detalle de Auditoría</h1>
        </div>
        <div class="col-auto">
            <a href="{{ route('admin.audit.index') }}" class="btn btn-secondary btn-sm">⬅️ Volver</a>
        </div>
    </div>

    <div class="row">
        <!-- Panel Izquierdo: Información General -->
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Información General</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-5">
                            <strong>ID del Log:</strong>
                        </div>
                        <div class="col-7">
                            <span class="badge bg-secondary">{{ $auditLog->id }}</span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-5">
                            <strong>Fecha/Hora:</strong>
                        </div>
                        <div class="col-7">
                            <small class="text-muted">
                                {{ $auditLog->created_at->format('d/m/Y H:i:s') }}
                            </small>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-5">
                            <strong>Acción:</strong>
                        </div>
                        <div class="col-7">
                            @php
                                $actionBadgeClass = match($auditLog->action) {
                                    'create' => 'bg-success',
                                    'update' => 'bg-primary',
                                    'delete' => 'bg-danger',
                                    'restore' => 'bg-warning',
                                    'export' => 'bg-secondary',
                                    default => 'bg-secondary'
                                };
                            @endphp
                            <span class="badge {{ $actionBadgeClass }}">
                                {{ $auditLog->getActionLabel() }}
                            </span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-5">
                            <strong>Modelo:</strong>
                        </div>
                        <div class="col-7">
                            <span class="badge bg-info">{{ $auditLog->model }}</span>
                        </div>
                    </div>

                    @if($auditLog->model_id)
                    <div class="row mb-3">
                        <div class="col-5">
                            <strong>ID del Registro:</strong>
                        </div>
                        <div class="col-7">
                            <code>#{{ $auditLog->model_id }}</code>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Usuario -->
            <div class="card mb-3">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Usuario Responsable</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-5">
                            <strong>Nombre:</strong>
                        </div>
                        <div class="col-7">
                            {{ $auditLog->user->name ?? 'Sistema' }}
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-5">
                            <strong>Email:</strong>
                        </div>
                        <div class="col-7">
                            <a href="mailto:{{ $auditLog->user->email }}">
                                {{ $auditLog->user->email ?? 'N/A' }}
                            </a>
                        </div>
                    </div>

                    @if($auditLog->user && $auditLog->user->role)
                    <div class="row mb-2">
                        <div class="col-5">
                            <strong>Rol:</strong>
                        </div>
                        <div class="col-7">
                            <span class="badge bg-primary">{{ $auditLog->user->role->name ?? 'Sin rol' }}</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Contexto Técnico -->
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Contexto Técnico</h5>
                </div>
                <div class="card-body">
                    @if($auditLog->ip_address)
                    <div class="row mb-2">
                        <div class="col-5">
                            <strong>IP Address:</strong>
                        </div>
                        <div class="col-7">
                            <code>{{ $auditLog->ip_address }}</code>
                        </div>
                    </div>
                    @endif

                    @if($auditLog->user_agent)
                    <div class="row mb-2">
                        <div class="col-12">
                            <strong>User Agent:</strong>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <small class="text-muted">
                                <code style="word-break: break-all;">{{ $auditLog->user_agent }}</code>
                            </small>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Panel Derecho: Cambios -->
        <div class="col-md-6">
            <!-- Descripción -->
            @if($auditLog->description)
            <div class="card mb-3">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Descripción</h5>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $auditLog->description }}</p>
                </div>
            </div>
            @endif

            <!-- Cambios: Valores Anteriores -->
            @if($auditLog->old_values && count($auditLog->old_values) > 0)
            <div class="card mb-3">
                <div class="card-header bg-danger bg-opacity-10">
                    <h5 class="mb-0">🔴 Valores Anteriores</h5>
                </div>
                <div class="card-body">
                    <div style="max-height: 300px; overflow-y: auto; background: #f8f9fa; padding: 10px; border-radius: 4px;">
                        @foreach($auditLog->old_values as $key => $value)
                        <div class="mb-2">
                            <strong class="text-muted">{{ $key }}:</strong><br>
                            <code style="color: #721c24;">{{ is_array($value) || is_object($value) ? json_encode($value, JSON_PRETTY_PRINT) : $value }}</code>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Cambios: Valores Nuevos -->
            @if($auditLog->new_values && count($auditLog->new_values) > 0)
            <div class="card mb-3">
                <div class="card-header bg-success bg-opacity-10">
                    <h5 class="mb-0">🟢 Valores Nuevos</h5>
                </div>
                <div class="card-body">
                    <div style="max-height: 300px; overflow-y: auto; background: #f8f9fa; padding: 10px; border-radius: 4px;">
                        @foreach($auditLog->new_values as $key => $value)
                        <div class="mb-2">
                            <strong class="text-muted">{{ $key }}:</strong><br>
                            <code style="color: #155724;">{{ is_array($value) || is_object($value) ? json_encode($value, JSON_PRETTY_PRINT) : $value }}</code>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Resumen de Cambios -->
            @if($auditLog->getFormattedChanges())
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">📊 Resumen de Cambios</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 30%;">Campo</th>
                                <th style="width: 35%;">Anterior</th>
                                <th style="width: 35%;">Nuevo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($auditLog->getFormattedChanges() as $field => $change)
                            <tr>
                                <td><strong>{{ $field }}</strong></td>
                                <td>
                                    <small>
                                        @if($change['old'] === null)
                                            <span class="badge bg-secondary">null</span>
                                        @else
                                            <code>{{ Str::limit($change['old'], 30) }}</code>
                                        @endif
                                    </small>
                                </td>
                                <td>
                                    <small>
                                        @if($change['new'] === null)
                                            <span class="badge bg-secondary">null</span>
                                        @else
                                            <code style="color: #155724;">{{ Str::limit($change['new'], 30) }}</code>
                                        @endif
                                    </small>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    code {
        background-color: #f4f4f4;
        padding: 2px 6px;
        border-radius: 3px;
        font-family: 'Courier New', monospace;
        font-size: 0.875rem;
    }
</style>
@endsection