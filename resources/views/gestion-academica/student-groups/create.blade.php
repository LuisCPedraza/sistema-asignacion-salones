@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <h1>Crear Grupo (HU3)</h1>
    <form method="POST" action="{{ route('gestion-academica.student-groups.store') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Nombre:</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" required>
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Nivel:</label>
            <input type="text" name="level" class="form-control @error('level') is-invalid @enderror" required>
            @error('level') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label class="form-label"># Estudiantes:</label>
            <input type="number" name="student_count" class="form-control @error('student_count') is-invalid @enderror" min="1" required>
            @error('student_count') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Características Especiales:</label>
            <textarea name="special_features" class="form-control @error('special_features') is-invalid @enderror"></textarea>
            @error('special_features') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Período Académico:</label>
            <select name="academic_period_id" class="form-control @error('academic_period_id') is-invalid @enderror">
                <option value="">Selecciona (opcional)</option>
                @foreach($periods as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </select>
            @error('academic_period_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <button type="submit" class="btn btn-success">Crear</button>
        <a href="{{ route('gestion-academica.student-groups.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection