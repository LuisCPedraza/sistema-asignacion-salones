@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>✏️ Editar Disponibilidad para {{ $classroom->name }} ({{ $classroom->code }})</h1>
        <a href="{{ route('infraestructura.classrooms.availabilities.index', $classroom) }}" class="btn btn-secondary">
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
            <form method="POST" action="{{ route('infraestructura.classrooms.availabilities.update', [$classroom, $availability]) }}">
                @csrf @method('PUT')
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Día de la Semana:</label>
                            <select name="day_of_week" class="form-select @error('day_of_week') is-invalid @enderror" required>
                                <option value="">Seleccionar día</option>
                                <option value="monday" {{ old('day_of_week', $availability->day_of_week) == 'monday' ? 'selected' : '' }}>Lunes</option>
                                <option value="tuesday" {{ old('day_of_week', $availability->day_of_week) == 'tuesday' ? 'selected' : '' }}>Martes</option>
                                <option value="wednesday" {{ old('day_of_week', $availability->day_of_week) == 'wednesday' ? 'selected' : '' }}>Miércoles</option>
                                <option value="thursday" {{ old('day_of_week', $availability->day_of_week) == 'thursday' ? 'selected' : '' }}>Jueves</option>
                                <option value="friday" {{ old('day_of_week', $availability->day_of_week) == 'friday' ? 'selected' : '' }}>Viernes</option>
                                <option value="saturday" {{ old('day_of_week', $availability->day_of_week) == 'saturday' ? 'selected' : '' }}>Sábado</option>
                            </select>
                            @error('day_of_week') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Tipo de Disponibilidad:</label>
                            <select name="availability_type" class="form-select @error('availability_type') is-invalid @enderror" required>
                                <option value="regular" {{ old('availability_type', $availability->availability_type) == 'regular' ? 'selected' : '' }}>Regular</option>
                                <option value="maintenance" {{ old('availability_type', $availability->availability_type) == 'maintenance' ? 'selected' : '' }}>Mantenimiento</option>
                                <option value="reserved" {{ old('availability_type', $availability->availability_type) == 'reserved' ? 'selected' : '' }}>Reservado</option>
                                <option value="special_event" {{ old('availability_type', $availability->availability_type) == 'special_event' ? 'selected' : '' }}>Evento Especial</option>
                            </select>
                            @error('availability_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Hora de Inicio:</label>
                            <input type="time" name="start_time" class="form-control @error('start_time') is-invalid @enderror" 
                                value="{{ old('start_time', $availability->start_time->format('H:i')) }}" min="08:00" max="21:00" required>
                            @error('start_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <small class="form-text text-muted">Horario universitario: 8:00 AM - 9:00 PM</small>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Hora de Fin:</label>
                            <input type="time" name="end_time" class="form-control @error('end_time') is-invalid @enderror" 
                                value="{{ old('end_time', $availability->end_time->format('H:i')) }}" min="08:00" max="21:00" required>
                            @error('end_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <small class="form-text text-muted">Debe ser posterior a la hora de inicio</small>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Disponible:</label>
                            <div class="form-check form-switch mt-2">
                                <input type="hidden" name="is_available" value="0">
                                <input type="checkbox" name="is_available" class="form-check-input" value="1" 
                                    {{ old('is_available', $availability->is_available) ? 'checked' : '' }}>
                                <label class="form-check-label">Marcar si está disponible en este horario</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Notas (Opcional):</label>
                    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" 
                        rows="3" placeholder="Ej: En mantenimiento, reservado para evento, etc.">{{ old('notes', $availability->notes) }}</textarea>
                    @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-success">💾 Actualizar Disponibilidad</button>
                    <a href="{{ route('infraestructura.classrooms.availabilities.index', $classroom) }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Información actual -->
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0">📊 Información Actual</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Día:</strong> {{ $availability->day_name }}</p>
                    <p><strong>Horario:</strong> {{ $availability->time_range }}</p>
                    <p><strong>Tipo:</strong> {{ $availability->availability_type_name }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Estado:</strong> 
                        <span class="badge {{ $availability->is_available ? 'bg-success' : 'bg-secondary' }}">
                            {{ $availability->is_available ? 'Disponible' : 'No Disponible' }}
                        </span>
                    </p>
                    <p><strong>Notas:</strong> {{ $availability->notes ?? 'Ninguna' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const startTime = document.querySelector('input[name="start_time"]');
    const endTime = document.querySelector('input[name="end_time"]');
    
    // Actualizar min/max de end_time cuando cambia start_time
    startTime.addEventListener('change', function() {
        endTime.min = this.value;
        if (endTime.value && endTime.value < this.value) {
            endTime.value = this.value;
        }
    });
});
</script>
@endsection