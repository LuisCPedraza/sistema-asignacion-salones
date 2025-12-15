@extends('layouts.app')

@section('content')
<style>
    h1 { font-size: 32px; }
    h5 { font-size: 20px; }
    .form-label, .form-check-label, .form-text { font-size: 20px; }
    .form-control, .form-select, .btn { font-size: 20px; }
    .invalid-feedback { font-size: 20px; }
    .alert { font-size: 20px; }
    .alert strong { font-size: 20px; }
    .alert p { font-size: 20px; }
</style>
<div class="container mt-4">
    <h1>✏️ Editar Usuario: {{ $user->name }}</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.users.update', $user) }}">
        @csrf @method('PUT')
        
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Nombre:</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                           value="{{ old('name', $user->name) }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Email:</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                           value="{{ old('email', $user->email) }}" required>
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Contraseña (dejar en blanco para no cambiar):</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <small class="form-text text-muted">Mínimo 8 caracteres</small>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Confirmar Contraseña:</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Rol:</label>
                    <select name="role_id" id="role_id" class="form-select @error('role_id') is-invalid @enderror" required>
                        <option value="">Seleccionar rol</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('role_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3 form-check">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" class="form-check-input" value="1" 
                           {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label">Usuario Activo</label>
                </div>
            </div>
        </div>

        <!-- Campos para Profesor Invitado -->
        <div id="guest_teacher_section" style="display: none;" class="mt-4 p-4 border rounded bg-light">
            <h5 class="mb-3">🎓 Configuración de Profesor Invitado</h5>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Fecha de Expiración de Acceso:</label>
                        <input type="datetime-local" name="access_expires_at" id="access_expires_at" 
                               class="form-control @error('access_expires_at') is-invalid @enderror" 
                               value="{{ old('access_expires_at', $user->teacher?->access_expires_at?->format('Y-m-d\TH:i')) }}">
                        @error('access_expires_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <small class="form-text text-muted">El profesor no podrá acceder después de esta fecha y hora</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Duración Rápida:</label>
                        <div class="btn-group w-100" role="group">
                            <button type="button" class="btn btn-outline-secondary quick-duration" data-days="30">1 mes</button>
                            <button type="button" class="btn btn-outline-secondary quick-duration" data-days="90">3 meses</button>
                            <button type="button" class="btn btn-outline-secondary quick-duration" data-days="365">1 año</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label">🔒 Restricción de IP (Opcional):</label>
                        <input type="text" name="ip_address_allowed" id="ip_address_allowed" 
                               class="form-control @error('ip_address_allowed') is-invalid @enderror" 
                               placeholder="Ej: 192.168.1.*, 10.0.0.5, 172.16.*"
                               value="{{ old('ip_address_allowed', $user->teacher?->ip_address_allowed) }}">
                        @error('ip_address_allowed') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <small class="form-text text-muted">
                            Deja en blanco para permitir acceso desde cualquier IP. 
                            Soporta: IPs exactas (192.168.1.5), rangos (192.168.1.*) y múltiples separadas por coma.
                        </small>
                    </div>
                </div>
            </div>

            @if($user->teacher && $user->teacher->is_guest)
                <div class="alert alert-info">
                    <strong>Estado actual:</strong> 
                    @if($user->teacher->isAccessValid())
                        ✅ Acceso válido hasta {{ $user->teacher->access_expires_at?->format('d/m/Y H:i') ?? 'Sin expiración' }}
                    @else
                        ❌ Acceso expirado desde {{ $user->teacher->access_expires_at?->format('d/m/Y H:i') }}
                    @endif
                </div>

                <div class="alert alert-warning">
                    <strong>⚠️ Revocar acceso:</strong>
                    <p class="mb-2">Si revocar el acceso, el profesor invitado será convertido a profesor regular y perderá su estado de acceso temporal.</p>
                    <form action="{{ route('admin.users.revoke-guest-access', $user) }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="button" class="btn btn-danger btn-sm" onclick="if(confirm('¿Estás seguro de que deseas revocar el acceso a este profesor invitado?')) { this.closest('form').submit(); }">
                            🔒 Revocar acceso inmediatamente
                        </button>
                    </form>
                </div>
            @endif
        </div>

        <button type="submit" class="btn btn-success">Actualizar Usuario</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.getElementById('role_id');
    const guestSection = document.getElementById('guest_teacher_section');
    const accessExpiresInput = document.getElementById('access_expires_at');
    const quickDurationButtons = document.querySelectorAll('.quick-duration');

    // Función para mostrar/ocultar sección de profesor invitado
    function toggleGuestSection() {
        const selectedOption = roleSelect.options[roleSelect.selectedIndex];
        const isGuestTeacher = selectedOption.text.toLowerCase().includes('invitado');
        guestSection.style.display = isGuestTeacher ? 'block' : 'none';
    }

    // Mostrar/ocultar al cambiar rol
    roleSelect.addEventListener('change', toggleGuestSection);

    // Botones de duración rápida
    quickDurationButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const days = parseInt(this.dataset.days);
            const expirationDate = new Date();
            expirationDate.setDate(expirationDate.getDate() + days);
            
            // Formatear a datetime-local: YYYY-MM-DDTHH:mm
            const year = expirationDate.getFullYear();
            const month = String(expirationDate.getMonth() + 1).padStart(2, '0');
            const day = String(expirationDate.getDate()).padStart(2, '0');
            const hours = String(expirationDate.getHours()).padStart(2, '0');
            const minutes = String(expirationDate.getMinutes()).padStart(2, '0');
            
            accessExpiresInput.value = `${year}-${month}-${day}T${hours}:${minutes}`;
            
            // Feedback visual
            this.classList.remove('btn-outline-secondary');
            this.classList.add('btn-primary');
            setTimeout(() => {
                this.classList.remove('btn-primary');
                this.classList.add('btn-outline-secondary');
            }, 300);
        });
    });

    // Mostrar sección al cargar si es profesor invitado
    toggleGuestSection();
});
</script>
@endsection