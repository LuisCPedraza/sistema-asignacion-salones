@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">✏️ Editar Carrera</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('careers.update', $career) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="code" class="form-label">Código <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                   id="code" name="code" required value="{{ old('code', $career->code) }}">
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre de la Carrera <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" required value="{{ old('name', $career->name) }}">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Descripción</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4">{{ old('description', $career->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="duration_semesters" class="form-label">Duración en Semestres <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('duration_semesters') is-invalid @enderror" 
                                   id="duration_semesters" name="duration_semesters" min="1" max="12" required 
                                   value="{{ old('duration_semesters', $career->duration_semesters) }}">
                            @error('duration_semesters')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" 
                                       {{ old('is_active', $career->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Activa
                                </label>
                            </div>
                        </div>

                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('careers.index') }}" class="btn btn-secondary">
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
