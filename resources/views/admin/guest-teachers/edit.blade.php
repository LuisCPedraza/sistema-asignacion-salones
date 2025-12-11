@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>✏️ Editar Profesor Invitado</h1>
            <p class="text-muted">{{ $teacher->name }} ({{ $teacher->user->email }})</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.guest-teachers.show', $teacher) }}" class="btn btn-secondary">← Volver</a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <strong>❌ Errores en el formulario:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Datos del Profesor Invitado</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.guest-teachers.update', $teacher) }}">
                        @csrf
                        @method('PUT')

                        <!-- Nombre -->
                        <div class="mb-3">
                            <label for="name" class="form-label">👤 Nombre Completo *</label>
                            <input 
                                type="text" 
                                name="name" 
                                id="name" 
                                class="form-control @error('name') is-invalid @enderror" 
                                value="{{ old('name', $teacher->name) }}" 
                                placeholder="Ej: Juan Pérez García"
                                required
                            >
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">📧 Correo Electrónico *</label>
                            <input 
                                type="email" 
                                name="email" 
                                id="email" 
                                class="form-control @error('email') is-invalid @enderror" 
                                value="{{ old('email', $teacher->user->email) }}" 
                                placeholder="profesor@universidad.edu"
                                required
                            >
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password (Opcional en edición) -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label">🔐 Nueva Contraseña (Opcional)</label>
                                    <input 
                                        type="password" 
                                        name="password" 
                                        id="password" 
                                        class="form-control @error('password') is-invalid @enderror"
                                        placeholder="Dejar en blanco para mantener la actual"
                                    >
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Mínimo 8 caracteres, mayúsculas, minúsculas, números y símbolos</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">🔐 Confirmar Contraseña</label>
                                    <input 
                                        type="password" 
                                        name="password_confirmation" 
                                        id="password_confirmation" 
                                        class="form-control @error('password_confirmation') is-invalid @enderror"
                                        placeholder="Repetir contraseña"
                                    >
                                    @error('password_confirmation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Access Expires At -->
                        <div class="mb-3">
                            <label for="access_expires_at" class="form-label">⏰ Fecha y Hora de Expiración *</label>
                            <input 
                                type="datetime-local" 
                                name="access_expires_at" 
                                id="access_expires_at" 
                                class="form-control @error('access_expires_at') is-invalid @enderror" 
                                value="{{ old('access_expires_at', $teacher->access_expires_at->format('Y-m-d\TH:i')) }}" 
                                required
                            >
                            @error('access_expires_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">El acceso expirará en esta fecha y hora.</small>
                        </div>

                        <!-- IP Address Allowed -->
                        <div class="mb-4">
                            <label for="ip_address_allowed" class="form-label">🌐 Dirección IP Permitida (Opcional)</label>
                            <input 
                                type="text" 
                                name="ip_address_allowed" 
                                id="ip_address_allowed" 
                                class="form-control @error('ip_address_allowed') is-invalid @enderror" 
                                value="{{ old('ip_address_allowed', $teacher->ip_address_allowed) }}" 
                                placeholder="Ej: 192.168.1.100 o 192.168.1.* para rango"
                            >
                            @error('ip_address_allowed')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Dejar vacío para permitir acceso desde cualquier IP.</small>
                        </div>

                        <!-- Botones -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.guest-teachers.show', $teacher) }}" class="btn btn-secondary">❌ Cancelar</a>
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-save"></i> Actualizar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Panel de Estado -->
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">📋 Estado Actual</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Nombre:</strong><br>
                        {{ $teacher->name }}
                    </div>
                    <div class="mb-3">
                        <strong>Correo:</strong><br>
                        {{ $teacher->user->email }}
                    </div>
                    <div class="mb-3">
                        <strong>Acceso:</strong><br>
                        @if ($teacher->access_expires_at > now())
                            <span class="badge bg-success">🟢 Activo</span>
                        @else
                            <span class="badge bg-danger">🔴 Expirado</span>
                        @endif
                    </div>
                    <div class="mb-3">
                        <strong>Expira el:</strong><br>
                        {{ $teacher->access_expires_at->format('d/m/Y H:i') }}
                    </div>
                    <div class="mb-3">
                        <strong>IP Permitida:</strong><br>
                        <code>{{ $teacher->ip_address_allowed ?? 'Cualquiera' }}</code>
                    </div>
                    <div class="mb-3">
                        <strong>Creado:</strong><br>
                        <small>{{ $teacher->created_at->format('d/m/Y H:i') }}</small>
                    </div>
                </div>
            </div>

            <!-- Botón Revocar -->
            @if ($teacher->access_expires_at > now())
                <div class="card border-danger">
                    <div class="card-body">
                        <p class="text-muted mb-3">¿Deseas revocar el acceso inmediatamente?</p>
                        <button 
                            type="button" 
                            class="btn btn-danger w-100" 
                            data-bs-toggle="modal" 
                            data-bs-target="#revokeModal"
                        >
                            <i class="fas fa-ban"></i> Revocar Acceso
                        </button>
                    </div>
                </div>
            @else
                <div class="alert alert-warning">
                    ⚠️ El acceso ya está expirado. No se puede revocar.
                </div>
            @endif
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
                <p>¿Estás seguro que deseas <strong>revocar inmediatamente</strong> el acceso a {{ $teacher->name }}?</p>
                <p class="text-muted">Esta acción es inmediata y no se puede deshacer.</p>
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

<script>
    // Sincronizar validación de contraseña
    document.addEventListener('DOMContentLoaded', function() {
        const passwordInput = document.getElementById('password');
        const confirmInput = document.getElementById('password_confirmation');
        
        passwordInput.addEventListener('input', function() {
            if (this.value) {
                confirmInput.required = true;
            } else {
                confirmInput.required = false;
            }
        });
    });
</script>
@endsection
