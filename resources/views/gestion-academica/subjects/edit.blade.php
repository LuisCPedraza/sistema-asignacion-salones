@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">✏️ Editar Materia</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('subjects.update', $subject) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="code" class="form-label">Código <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                       id="code" name="code" required value="{{ old('code', $subject->code) }}">
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Nombre <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" required value="{{ old('name', $subject->name) }}">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="career_id" class="form-label">Carrera <span class="text-danger">*</span></label>
                            <select class="form-select @error('career_id') is-invalid @enderror" 
                                    id="career_id" name="career_id" required>
                                <option value="">Seleccionar carrera</option>
                                @foreach($careers as $career)
                                    <option value="{{ $career->id }}" {{ old('career_id', $subject->career_id) == $career->id ? 'selected' : '' }}>
                                        {{ $career->code }} - {{ $career->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('career_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Descripción</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description', $subject->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="specialty" class="form-label">Especialidad/Énfasis</label>
                                <input type="text" class="form-control @error('specialty') is-invalid @enderror" 
                                       id="specialty" name="specialty" value="{{ old('specialty', $subject->specialty) }}">
                                @error('specialty')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="semester_level" class="form-label">Semestre <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('semester_level') is-invalid @enderror" 
                                       id="semester_level" name="semester_level" min="1" max="12" required 
                                       value="{{ old('semester_level', $subject->semester_level) }}">
                                @error('semester_level')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="credit_hours" class="form-label">Créditos <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('credit_hours') is-invalid @enderror" 
                                       id="credit_hours" name="credit_hours" min="1" max="20" required 
                                       value="{{ old('credit_hours', $subject->credit_hours) }}">
                                @error('credit_hours')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="lecture_hours" class="form-label">Horas Teóricas <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('lecture_hours') is-invalid @enderror" 
                                       id="lecture_hours" name="lecture_hours" min="0" max="40" required 
                                       value="{{ old('lecture_hours', $subject->lecture_hours) }}">
                                @error('lecture_hours')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="lab_hours" class="form-label">Horas de Laboratorio <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('lab_hours') is-invalid @enderror" 
                                       id="lab_hours" name="lab_hours" min="0" max="40" required 
                                       value="{{ old('lab_hours', $subject->lab_hours) }}">
                                @error('lab_hours')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" 
                                       {{ old('is_active', $subject->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Activa
                                </label>
                            </div>
                        </div>

                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('subjects.index') }}" class="btn btn-secondary">
                                ✗ Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                ✓ Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
