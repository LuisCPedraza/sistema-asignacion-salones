@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">✏️ Editar Semestre</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('semesters.update', $semester) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="career_id" class="form-label">Carrera <span class="text-danger">*</span></label>
                            <select class="form-select @error('career_id') is-invalid @enderror" 
                                    id="career_id" name="career_id" required>
                                <option value="">Seleccionar carrera</option>
                                @foreach($careers as $career)
                                    <option value="{{ $career->id }}" {{ old('career_id', $semester->career_id) == $career->id ? 'selected' : '' }}>
                                        {{ $career->code }} - {{ $career->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('career_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="number" class="form-label">Número de Semestre <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('number') is-invalid @enderror" 
                                   id="number" name="number" min="1" max="12" required 
                                   value="{{ old('number', $semester->number) }}">
                            @error('number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Descripción</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description', $semester->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" 
                                       {{ old('is_active', $semester->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Activo
                                </label>
                            </div>
                        </div>

                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('semesters.index') }}" class="btn btn-secondary">
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
