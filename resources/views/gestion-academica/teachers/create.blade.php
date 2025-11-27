@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <h1>Crear Profesor (HU7)</h1>
    
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('gestion-academica.teachers.store') }}">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Nombre:</label>
                    <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name') }}" required>
                    @error('first_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Apellido:</label>
                    <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name') }}" required>
                    @error('last_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Email:</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Teléfono:</label>
                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}">
                    @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Especialidad Principal:</label>
                    <input type="text" name="specialty" class="form-control @error('specialty') is-invalid @enderror" value="{{ old('specialty') }}" required>
                    @error('specialty') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Años de Experiencia:</label>
                    <input type="number" name="years_experience" class="form-control @error('years_experience') is-invalid @enderror" value="{{ old('years_experience', 0) }}" min="0" required>
                    @error('years_experience') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Otras Especialidades (separadas por coma):</label>
            <input type="text" name="specialties" class="form-control @error('specialties') is-invalid @enderror" value="{{ old('specialties') }}" placeholder="Ej: Matemáticas, Programación, Estadística">
            @error('specialties') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Grado Académico:</label>
            <input type="text" name="academic_degree" class="form-control @error('academic_degree') is-invalid @enderror" value="{{ old('academic_degree') }}">
            @error('academic_degree') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Hoja de Vida (Resumen):</label>
            <textarea name="curriculum" class="form-control @error('curriculum') is-invalid @enderror" rows="3">{{ old('curriculum') }}</textarea>
            @error('curriculum') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Notas de Disponibilidad:</label>
            <textarea name="availability_notes" class="form-control @error('availability_notes') is-invalid @enderror" rows="2">{{ old('availability_notes') }}</textarea>
            @error('availability_notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Asignaciones Especiales:</label>
            <textarea name="special_assignments" class="form-control @error('special_assignments') is-invalid @enderror" rows="2">{{ old('special_assignments') }}</textarea>
            @error('special_assignments') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3 form-check">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" class="form-check-input" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
            <label class="form-check-label">Activo</label>
        </div>

        <button type="submit" class="btn btn-success">Crear Profesor</button>
        <a href="{{ route('gestion-academica.teachers.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection