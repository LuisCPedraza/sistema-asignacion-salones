@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <h1>Editar Salón (HU5)</h1>
    
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('infraestructura.classrooms.update', $classroom) }}">
        @csrf @method('PUT')
        
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Código del Salón:</label>
                    <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code', $classroom->code) }}" required>
                    @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Nombre del Salón:</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $classroom->name) }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Capacidad:</label>
                    <input type="number" name="capacity" class="form-control @error('capacity') is-invalid @enderror" value="{{ old('capacity', $classroom->capacity) }}" min="1" max="500" required>
                    @error('capacity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Tipo de Salón:</label>
                    <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                        <option value="aula" {{ old('type', $classroom->type) == 'aula' ? 'selected' : '' }}>Aula</option>
                        <option value="laboratorio" {{ old('type', $classroom->type) == 'laboratorio' ? 'selected' : '' }}>Laboratorio</option>
                        <option value="auditorio" {{ old('type', $classroom->type) == 'auditorio' ? 'selected' : '' }}>Auditorio</option>
                        <option value="sala_reuniones" {{ old('type', $classroom->type) == 'sala_reuniones' ? 'selected' : '' }}>Sala de Reuniones</option>
                        <option value="taller" {{ old('type', $classroom->type) == 'taller' ? 'selected' : '' }}>Taller</option>
                    </select>
                    @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Edificio:</label>
                    <select name="building_id" class="form-select @error('building_id') is-invalid @enderror">
                        <option value="">Seleccionar edificio</option>
                        @foreach($buildings as $building)
                            <option value="{{ $building->id }}" {{ old('building_id', $classroom->building_id) == $building->id ? 'selected' : '' }}>
                                {{ $building->name }} ({{ $building->code }})
                            </option>
                        @endforeach
                    </select>
                    @error('building_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label class="form-label">Piso:</label>
                    <input type="number" name="floor" class="form-control @error('floor') is-invalid @enderror" value="{{ old('floor', $classroom->floor) }}" min="0" max="20" required>
                    @error('floor') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label class="form-label">Ala/Sector:</label>
                    <input type="text" name="wing" class="form-control @error('wing') is-invalid @enderror" value="{{ old('wing', $classroom->wing) }}">
                    @error('wing') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Recursos Disponibles:</label>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-check">
                        <input type="checkbox" name="resources[]" value="proyector" class="form-check-input" {{ in_array('proyector', old('resources', $classroom->resources_array)) ? 'checked' : '' }}>
                        <label class="form-check-label">Proyector</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-check">
                        <input type="checkbox" name="resources[]" value="computadoras" class="form-check-input" {{ in_array('computadoras', old('resources', $classroom->resources_array)) ? 'checked' : '' }}>
                        <label class="form-check-label">Computadoras</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-check">
                        <input type="checkbox" name="resources[]" value="pizarra_inteligente" class="form-check-input" {{ in_array('pizarra_inteligente', old('resources', $classroom->resources_array)) ? 'checked' : '' }}>
                        <label class="form-check-label">Pizarra Inteligente</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-check">
                        <input type="checkbox" name="resources[]" value="audio" class="form-check-input" {{ in_array('audio', old('resources', $classroom->resources_array)) ? 'checked' : '' }}>
                        <label class="form-check-label">Sistema de Audio</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Características Especiales:</label>
            <textarea name="special_features" class="form-control @error('special_features') is-invalid @enderror" rows="3">{{ old('special_features', $classroom->special_features) }}</textarea>
            @error('special_features') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Restricciones:</label>
            <textarea name="restrictions" class="form-control @error('restrictions') is-invalid @enderror" rows="2">{{ old('restrictions', $classroom->restrictions) }}</textarea>
            @error('restrictions') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Ubicación (Descripción):</label>
            <input type="text" name="location" class="form-control @error('location') is-invalid @enderror" value="{{ old('location', $classroom->location) }}">
            @error('location') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3 form-check">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" class="form-check-input" value="1" {{ old('is_active', $classroom->is_active) ? 'checked' : '' }}>
            <label class="form-check-label">Activo</label>
        </div>

        <button type="submit" class="btn btn-success">Actualizar Salón</button>
        <a href="{{ route('infraestructura.classrooms.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection

in_array