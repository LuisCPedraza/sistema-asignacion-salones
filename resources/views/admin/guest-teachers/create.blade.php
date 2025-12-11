@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>➕ Crear Profesor Invitado</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.guest-teachers.index') }}" class="btn btn-secondary">← Volver</a>
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

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Datos del Profesor Invitado</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.guest-teachers.store') }}">
                @csrf

                <!-- Nombre -->
                <div class="mb-3">
                    <label for="name" class="form-label">👤 Nombre Completo *</label>
                    <input 
                        type="text" 
                        name="name" 
                        id="name" 
                        class="form-control @error('name') is-invalid @enderror" 
                        value="{{ old('name') }}" 
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
                        value="{{ old('email') }}" 
                        placeholder="profesor@universidad.edu"
                        required
                    >
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="password" class="form-label">🔐 Contraseña *</label>
                            <input 
                                type="password" 
                                name="password" 
                                id="password" 
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Mínimo 8 caracteres"
                                required
                            >
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Mínimo 8 caracteres, letras mayúsculas/minúsculas, números y símbolos</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">🔐 Confirmar Contraseña *</label>
                            <input 
                                type="password" 
                                name="password_confirmation" 
                                id="password_confirmation" 
                                class="form-control @error('password_confirmation') is-invalid @enderror"
                                placeholder="Repetir contraseña"
                                required
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
                        value="{{ old('access_expires_at') }}" 
                        required
                    >
                    @error('access_expires_at')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">El acceso expirará en esta fecha y hora. Debe ser en el futuro.</small>
                </div>

                <!-- IP Address Allowed -->
                <div class="mb-4">
                    <label for="ip_address_allowed" class="form-label">🌐 Dirección IP Permitida (Opcional)</label>
                    <input 
                        type="text" 
                        name="ip_address_allowed" 
                        id="ip_address_allowed" 
                        class="form-control @error('ip_address_allowed') is-invalid @enderror" 
                        value="{{ old('ip_address_allowed') }}" 
                        placeholder="Ej: 192.168.1.100 o 192.168.1.* para rango"
                    >
                    @error('ip_address_allowed')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Dejar vacío para permitir acceso desde cualquier IP. Usa * para rangos.</small>
                </div>

                <!-- Botones -->
                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.guest-teachers.index') }}" class="btn btn-secondary">❌ Cancelar</a>
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="fas fa-save"></i> Crear Profesor Invitado
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Información Adicional -->
    <div class="alert alert-info mt-4">
        <strong>ℹ️ Información:</strong>
        <ul class="mb-0 mt-2">
            <li>Una vez creado, el profesor invitado podrá acceder con su correo y contraseña</li>
            <li>El acceso se revocará automáticamente en la fecha/hora especificada</li>
            <li>Se puede revocar manualmente antes de la expiración</li>
            <li>Se enviará notificación por correo con las credenciales</li>
        </ul>
    </div>
</div>

<script>
    // Establecer fecha/hora mínima a ahora
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('access_expires_at');
        const now = new Date();
        const tomorrow = new Date(now.getTime() + 24 * 60 * 60 * 1000);
        
        const year = tomorrow.getFullYear();
        const month = String(tomorrow.getMonth() + 1).padStart(2, '0');
        const date = String(tomorrow.getDate()).padStart(2, '0');
        const hours = String(tomorrow.getHours()).padStart(2, '0');
        const minutes = String(tomorrow.getMinutes()).padStart(2, '0');
        
        input.min = new Date().toISOString().slice(0, 16);
        input.value = `${year}-${month}-${date}T${hours}:${minutes}`;
    });
</script>
@endsection
