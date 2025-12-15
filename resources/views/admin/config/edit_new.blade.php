@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>✏️ Editar Configuración del Sistema (HU19)</h1>
        <a href="{{ route('admin.config.index') }}" class="btn btn-secondary">← Volver</a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <h4>⚠️ Errores de Validación:</h4>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.config.update') }}">
        @csrf @method('PUT')

        <!-- SECCIÓN: INSTITUCIÓN -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">📋 Información de la Institución</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="institution_name">Nombre de la Institución:</label>
                            <input type="text" id="institution_name" name="institution_name" 
                                   class="form-control @error('institution_name') is-invalid @enderror"
                                   value="{{ old('institution_name', $institution['name'] ?? '') }}" 
                                   placeholder="ej: Universidad Ejemplo" required>
                            @error('institution_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="institution_code">Código de la Institución:</label>
                            <input type="text" id="institution_code" name="institution_code" 
                                   class="form-control @error('institution_code') is-invalid @enderror"
                                   value="{{ old('institution_code', $institution['code'] ?? '') }}" 
                                   placeholder="ej: UNIV-001" required>
                            @error('institution_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECCIÓN: HORARIOS -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">🕐 Horarios de Trabajo</h5>
            </div>
            <div class="card-body">
                <h6 class="mb-3">Jornada Laboral</h6>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="work_start_time">Hora de Inicio:</label>
                            <input type="time" id="work_start_time" name="work_start_time" 
                                   class="form-control @error('work_start_time') is-invalid @enderror"
                                   value="{{ old('work_start_time', substr($schedule['work_start_time'] ?? '08:00:00', 0, 5)) }}" 
                                   required>
                            @error('work_start_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="work_end_time">Hora de Fin:</label>
                            <input type="time" id="work_end_time" name="work_end_time" 
                                   class="form-control @error('work_end_time') is-invalid @enderror"
                                   value="{{ old('work_end_time', substr($schedule['work_end_time'] ?? '17:00:00', 0, 5)) }}" 
                                   required>
                            @error('work_end_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <h6 class="mb-3 mt-4">Período de Almuerzo</h6>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="lunch_start_time">Hora de Inicio:</label>
                            <input type="time" id="lunch_start_time" name="lunch_start_time" 
                                   class="form-control @error('lunch_start_time') is-invalid @enderror"
                                   value="{{ old('lunch_start_time', substr($schedule['lunch_start_time'] ?? '12:00:00', 0, 5)) }}" 
                                   required>
                            @error('lunch_start_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="lunch_end_time">Hora de Fin:</label>
                            <input type="time" id="lunch_end_time" name="lunch_end_time" 
                                   class="form-control @error('lunch_end_time') is-invalid @enderror"
                                   value="{{ old('lunch_end_time', substr($schedule['lunch_end_time'] ?? '13:00:00', 0, 5)) }}" 
                                   required>
                            @error('lunch_end_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECCIÓN: ALGORITMO -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">🔧 Parámetros del Algoritmo de Asignación</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="min_score_threshold">Puntuación Mínima Aceptable (0-1):</label>
                            <input type="number" id="min_score_threshold" name="min_score_threshold" 
                                   class="form-control @error('min_score_threshold') is-invalid @enderror"
                                   value="{{ old('min_score_threshold', $algorithm['min_score_threshold'] ?? '0.6') }}" 
                                   step="0.05" min="0" max="1" required>
                            <small class="form-text text-muted">Rango: 0.0 a 1.0. Recomendado: 0.6 (60%)</small>
                            @error('min_score_threshold') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="max_attempts">Máximo de Intentos de Búsqueda:</label>
                            <input type="number" id="max_attempts" name="max_attempts" 
                                   class="form-control @error('max_attempts') is-invalid @enderror"
                                   value="{{ old('max_attempts', $algorithm['max_attempts'] ?? '15') }}" 
                                   min="1" max="100" required>
                            <small class="form-text text-muted">Rango: 1 a 100. Recomendado: 15</small>
                            @error('max_attempts') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
                <div class="alert alert-info">
                    <strong>ℹ️ Información:</strong> Estos parámetros controlan cómo el algoritmo busca las mejores asignaciones de salones a grupos de estudiantes.
                </div>
            </div>
        </div>

        <!-- SECCIÓN: AUDITORÍA -->
        <div class="card mb-4">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">📊 Configuración de Auditoría</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="form-check">
                        <input type="checkbox" id="audit_enabled" name="audit_enabled" 
                               class="form-check-input" 
                               value="1" 
                               {{ old('audit_enabled', $audit['enabled'] ?? '1') == '1' ? 'checked' : '' }}>
                        <label class="form-check-label" for="audit_enabled">
                            Habilitar Registro de Auditoría
                        </label>
                    </div>
                    <small class="form-text text-muted">Registra todos los cambios en el sistema para trazabilidad</small>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="audit_retention_days">Retención de Logs (días):</label>
                            <input type="number" id="audit_retention_days" name="audit_retention_days" 
                                   class="form-control @error('audit_retention_days') is-invalid @enderror"
                                   value="{{ old('audit_retention_days', $audit['retention_days'] ?? '90') }}" 
                                   min="1" max="365" required>
                            <small class="form-text text-muted">Rango: 1 a 365 días. Recomendado: 90</small>
                            @error('audit_retention_days') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- BOTONES DE ACCIÓN -->
        <div class="d-flex gap-2 mb-4">
            <button type="submit" class="btn btn-primary btn-lg">
                💾 Guardar Cambios
            </button>
            <a href="{{ route('admin.config.index') }}" class="btn btn-secondary btn-lg">
                ❌ Cancelar
            </a>
        </div>
    </form>
</div>

<style>
    h1 { font-size: 32px; }
    h5, h6 { font-size: 20px; }
    p, label, .form-label, .form-control, .form-select, .btn, small, .form-text, .alert, table, th, td { font-size: 20px; }
    .card {
        border: none;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .card-header {
        border-bottom: 2px solid;
    }
    .form-control:focus {
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }
</style>
@endsection
