@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <h1>Editar Grupo (HU4)</h1>
    
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('gestion-academica.student-groups.update', $studentGroup) }}">
        @csrf @method('PUT')
        <div class="mb-3">
            <label class="form-label">Nombre:</label>
            <input type="text" name="name" value="{{ old('name', $studentGroup->name) }}" class="form-control @error('name') is-invalid @enderror" required>
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Nivel:</label>
            <input type="text" name="level" value="{{ old('level', $studentGroup->level) }}" class="form-control @error('level') is-invalid @enderror" required>
            @error('level') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label class="form-label"># Estudiantes:</label>
            <input type="number" name="student_count" value="{{ old('student_count', $studentGroup->student_count) }}" class="form-control @error('student_count') is-invalid @enderror" min="1" required>
            @error('student_count') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Características Especiales:</label>
            <textarea name="special_features" class="form-control @error('special_features') is-invalid @enderror">{{ old('special_features', $studentGroup->special_features) }}</textarea>
            @error('special_features') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Período Académico:</label>
            <select name="academic_period_id" class="form-control @error('academic_period_id') is-invalid @enderror">
                <option value="">Selecciona (opcional)</option>
                @foreach($periods as $id => $name)
                    <option value="{{ $id }}" {{ old('academic_period_id', $studentGroup->academic_period_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>
            @error('academic_period_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3 form-check">
            <input type="hidden" name="is_active" value="0"> <!-- Campo oculto para valor por defecto -->
            <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" {{ old('is_active', $studentGroup->is_active) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">Activo</label>
            @error('is_active') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>
        <button type="submit" class="btn btn-success">Actualizar</button>
        <a href="{{ route('gestion-academica.student-groups.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection