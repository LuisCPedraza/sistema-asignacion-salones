@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>➕ Agregar Disponibilidad para {{ $teacher->first_name }} {{ $teacher->last_name }}</h1>
        <a href="{{ route('gestion-academica.teachers.availabilities.index', $teacher) }}" class="btn btn-secondary">
            ← Volver
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('gestion-academica.teachers.availabilities.store', $teacher) }}">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Día de la Semana:</label>
                            <select name="day" class="form-select @error('day') is-invalid @enderror" required> {{-- Fix: name="day" --}}
                                <option value="">Seleccionar día</option>
                                <option value="monday" {{ old('day') ?: 'selected' }}>Lunes</option> {{-- Default --}}
                                <option value="tuesday" {{ old('day') == 'tuesday' ? 'selected' : '' }}>Martes</option>
                                <option value="wednesday" {{ old('day') == 'wednesday' ? 'selected' : '' }}>Miércoles</option>
                                <option value="thursday" {{ old('day') == 'thursday' ? 'selected' : '' }}>Jueves</option>
                                <option value="friday" {{ old('day') == 'friday' ? 'selected' : '' }}>Viernes</option>
                                <option value="saturday" {{ old('day') == 'saturday' ? 'selected' : '' }}>Sábado</option>
                            </select>
                            @error('day') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Disponible:</label>
                            <div class="form-check form-switch mt-2">
                                <input type="hidden" name="is_available" value="0">
                                <input type="checkbox" name="is_available" class="form-check-input" value="1" 
                                    {{ old('is_available', true) ? 'checked' : '' }}>
                                <label class="form-check-label">Marcar si está disponible en este horario</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Hora de Inicio:</label>
                            <input type="time" name="start_time" class="form-control @error('start_time') is-invalid @enderror" 
                                value="{{ old('start_time') }}" min="08:00" max="21:00" required>
                            @error('start_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <small class="form-text text-muted">Horario universitario: 8:00 AM - 9:00 PM</small>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Hora de Fin:</label>
                            <input type="time" name="end_time" class="form-control @error('end_time') is-invalid @enderror" 
                                value="{{ old('end_time') }}" min="08:00" max="21:00" required>
                            @error('end_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <small class="form-text text-muted">Debe ser posterior a la hora de inicio</small>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Notas (Opcional):</label>
                    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" 
                        rows="3" placeholder="Ej: Preferencia para clases teóricas, necesita proyector, etc.">{{ old('notes') }}</textarea>
                    @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-success">💾 Guardar Disponibilidad</button>
                    <a href="{{ route('gestion-academica.teachers.availabilities.index', $teacher) }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Información de horarios universitarios -->
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0">ℹ️ Horario Universitario de Referencia</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6>Jornada Diurna</h6>
                    <ul class="list-unstyled">
                        <li>🕗 8:00 - 10:00</li>
                        <li>🕙 10:00 - 12:00</li>
                        <li>🕐 14:00 - 16:00</li>
                        <li>🕓 16:00 - 18:00</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h6>Jornada Nocturna</h6>
                    <ul class="list-unstyled">
                        <li>🕔 18:00 - 20:00</li>
                        <li>🕗 20:00 - 21:00</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const startTime = document.querySelector('input[name="start_time"]');
    const endTime = document.querySelector('input[name="end_time"]');
    
    startTime.addEventListener('change', function() {
        endTime.min = this.value;
        if (endTime.value && endTime.value < this.value) {
            endTime.value = '';
        }
    });
});
</script>
@endsection