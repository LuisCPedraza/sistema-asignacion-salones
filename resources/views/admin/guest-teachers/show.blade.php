@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>👤 {{ $teacher->name }}</h1>
            <p class="text-muted">{{ $teacher->user->email }}</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.guest-teachers.index') }}" class="btn btn-secondary">← Volver</a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Información Principal -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">📋 Información del Profesor</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>👤 Nombre Completo:</strong><br>
                            {{ $teacher->name }}
                        </div>
                        <div class="col-md-6">
                            <strong>📧 Correo Electrónico:</strong><br>
                            {{ $teacher->user->email }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>🏫 Especialidad:</strong><br>
                            {{ $teacher->specialty }}
                        </div>
                        <div class="col-md-6">
                            <strong>📞 Teléfono:</strong><br>
                            {{ $teacher->phone ?? 'No registrado' }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>⏰ Creado:</strong><br>
                            {{ $teacher->created_at->format('d/m/Y H:i') }}
                        </div>
                        <div class="col-md-6">
                            <strong>✏️ Actualizado:</strong><br>
                            {{ $teacher->updated_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Acceso Invitado -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">🔐 Acceso de Invitado</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Estado:</strong><br>
                            @if ($teacher->access_expires_at > now())
                                <span class="badge bg-success fs-6">🟢 Activo</span>
                            @else
                                <span class="badge bg-danger fs-6">🔴 Expirado</span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <strong>⏰ Expira el:</strong><br>
                            <code>{{ $teacher->access_expires_at->format('d/m/Y H:i') }}</code>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>🕐 Tiempo Restante:</strong><br>
                            @php
                                $now = now();
                                if ($teacher->access_expires_at > $now) {
                                    $diff = $now->diffInSeconds($teacher->access_expires_at);
                                    $days = intdiv($diff, 86400);
                                    $hours = intdiv($diff % 86400, 3600);
                                    $minutes = intdiv($diff % 3600, 60);
                                    echo "$days días, $hours horas y $minutes minutos";
                                } else {
                                    echo "⏱️ Acceso expirado";
                                }
                            @endphp
                        </div>
                        <div class="col-md-6">
                            <strong>🌐 IP Permitida:</strong><br>
                            <code>{{ $teacher->ip_address_allowed ?? 'Cualquiera' }}</code>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Disponibilidades -->
            @if ($teacher->availabilities->count() > 0)
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">📅 Disponibilidades Registradas</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Día</th>
                                        <th>Hora Inicio</th>
                                        <th>Hora Fin</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($teacher->availabilities as $availability)
                                        <tr>
                                            <td>{{ $availability->day_of_week }}</td>
                                            <td>{{ $availability->start_time }}</td>
                                            <td>{{ $availability->end_time }}</td>
                                            <td>
                                                @if ($availability->is_available)
                                                    <span class="badge bg-success">Disponible</span>
                                                @else
                                                    <span class="badge bg-secondary">No disponible</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-info">
                    ℹ️ Sin disponibilidades registradas aún.
                </div>
            @endif
        </div>

        <!-- Panel de Acciones -->
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">⚙️ Acciones</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a 
                            href="{{ route('admin.guest-teachers.edit', $teacher) }}" 
                            class="btn btn-primary"
                        >
                            <i class="fas fa-edit"></i> Editar
                        </a>

                        @if ($teacher->access_expires_at > now())
                            <button 
                                type="button" 
                                class="btn btn-danger" 
                                data-bs-toggle="modal" 
                                data-bs-target="#revokeModal"
                            >
                                <i class="fas fa-ban"></i> Revocar Acceso
                            </button>
                        @else
                            <button class="btn btn-secondary" disabled>
                                <i class="fas fa-ban"></i> Acceso Expirado
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Estadísticas -->
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">📊 Estadísticas</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Disponibilidades:</span>
                            <strong>{{ $teacher->availabilities->count() }}</strong>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Tipo de Usuario:</span>
                            <span class="badge bg-info">Invitado</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Activo:</span>
                            @if ($teacher->is_active)
                                <span class="badge bg-success">Sí</span>
                            @else
                                <span class="badge bg-danger">No</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Revocar -->
<div class="modal fade" id="revokeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-danger">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">⚠️ Revocar Acceso</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro que deseas <strong>revocar inmediatamente</strong> el acceso a <strong>{{ $teacher->name }}</strong>?</p>
                <p class="text-muted">Esta acción es inmediata y el profesor invitado no podrá acceder al sistema.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form method="POST" action="{{ route('admin.guest-teachers.revoke', $teacher) }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-ban"></i> Revocar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
