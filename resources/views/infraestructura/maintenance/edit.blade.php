@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="mb-4">
        <h1>✏️ Editar Mantenimiento (HU20)</h1>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>❌ Errores de Validación:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('infraestructura.maintenance.update', $maintenance) }}">
                        @csrf
                        @method('PUT')

                        <!-- Salón -->
                        <div class="mb-3">
                            <label for="classroom_id" class="form-label">
                                🏢 Salón <span class="text-danger">*</span>
                            </label>
                            <select name="classroom_id" id="classroom_id" class="form-select @error('classroom_id') is-invalid @enderror" required>
                                <option value="">Seleccionar Salón</option>
                                @foreach ($classrooms as $classroom)
                                    <option value="{{ $classroom->id }}" 
                                        {{ old('classroom_id', $maintenance->classroom_id) == $classroom->id ? 'selected' : '' }}>
                                        {{ $classroom->name }} ({{ $classroom->code }})
                                    </option>
                                @endforeach
                            </select>
                            @error('classroom_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tipo -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="type" class="form-label">
                                        📋 Tipo <span class="text-danger">*</span>
                                    </label>
                                    <select name="type" id="type" class="form-select @error('type') is-invalid @enderror" required>
                                        <option value="preventivo" {{ old('type', $maintenance->type) == 'preventivo' ? 'selected' : '' }}>
                                            🛡️ Preventivo
                                        </option>
                                        <option value="correctivo" {{ old('type', $maintenance->type) == 'correctivo' ? 'selected' : '' }}>
                                            🔨 Correctivo
                                        </option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Estado -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">
                                        ⏳ Estado <span class="text-danger">*</span>
                                    </label>
                                    <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                        <option value="pendiente" {{ old('status', $maintenance->status) == 'pendiente' ? 'selected' : '' }}>
                                            ⏳ Pendiente
                                        </option>
                                        <option value="en_progreso" {{ old('status', $maintenance->status) == 'en_progreso' ? 'selected' : '' }}>
                                            🔄 En Progreso
                                        </option>
                                        <option value="completado" {{ old('status', $maintenance->status) == 'completado' ? 'selected' : '' }}>
                                            ✅ Completado
                                        </option>
                                        <option value="cancelado" {{ old('status', $maintenance->status) == 'cancelado' ? 'selected' : '' }}>
                                            🚫 Cancelado
                                        </option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Título -->
                        <div class="mb-3">
                            <label for="title" class="form-label">
                                🏷️ Título <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="title" id="title" 
                                   class="form-control @error('title') is-invalid @enderror" 
                                   value="{{ old('title', $maintenance->title) }}" 
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Descripción -->
                        <div class="mb-3">
                            <label for="description" class="form-label">
                                📝 Descripción
                            </label>
                            <textarea name="description" id="description" 
                                      class="form-control @error('description') is-invalid @enderror" 
                                      rows="3">{{ old('description', $maintenance->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr>

                        <!-- Fecha Programada -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="scheduled_date" class="form-label">
                                        📅 Fecha Programada
                                    </label>
                                    <input type="date" name="scheduled_date" id="scheduled_date" 
                                           class="form-control @error('scheduled_date') is-invalid @enderror" 
                                           value="{{ old('scheduled_date', $maintenance->scheduled_date?->format('Y-m-d')) }}">
                                    @error('scheduled_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Responsable -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="responsible" class="form-label">
                                        👤 Responsable
                                    </label>
                                    <input type="text" name="responsible" id="responsible" 
                                           class="form-control @error('responsible') is-invalid @enderror" 
                                           value="{{ old('responsible', $maintenance->responsible) }}">
                                    @error('responsible')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Fecha Inicio y Fin -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="start_date" class="form-label">
                                        🕐 Fecha y Hora de Inicio
                                    </label>
                                    <input type="datetime-local" name="start_date" id="start_date" 
                                           class="form-control @error('start_date') is-invalid @enderror" 
                                           value="{{ old('start_date', $maintenance->start_date?->format('Y-m-d\TH:i')) }}">
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="end_date" class="form-label">
                                        🕐 Fecha y Hora de Fin
                                    </label>
                                    <input type="datetime-local" name="end_date" id="end_date" 
                                           class="form-control @error('end_date') is-invalid @enderror" 
                                           value="{{ old('end_date', $maintenance->end_date?->format('Y-m-d\TH:i')) }}">
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Costo -->
                        <div class="mb-3">
                            <label for="cost" class="form-label">
                                💰 Costo
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="cost" id="cost" 
                                       class="form-control @error('cost') is-invalid @enderror" 
                                       value="{{ old('cost', $maintenance->cost) }}"
                                       step="0.01" min="0">
                            </div>
                            @error('cost')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Notas -->
                        <div class="mb-3">
                            <label for="notes" class="form-label">
                                📌 Notas Adicionales
                            </label>
                            <textarea name="notes" id="notes" 
                                      class="form-control @error('notes') is-invalid @enderror" 
                                      rows="2">{{ old('notes', $maintenance->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('infraestructura.maintenance.show', $maintenance) }}" class="btn btn-secondary">
                                ← Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                💾 Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-light">
                <div class="card-body">
                    <h5 class="card-title">📊 Información Actual</h5>
                    <ul class="small text-muted">
                        <li><strong>Salón:</strong> {{ $maintenance->classroom->name ?? 'N/A' }}</li>
                        <li><strong>Tipo:</strong> {{ $maintenance->type }}</li>
                        <li><strong>Estado:</strong> {{ $maintenance->status }}</li>
                        <li><strong>Creado:</strong> {{ $maintenance->created_at->format('d/m/Y H:i') }}</li>
                        <li><strong>Modificado:</strong> {{ $maintenance->updated_at->format('d/m/Y H:i') }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('infraestructura.maintenance.index') }}" class="btn btn-secondary">
            ← Volver a Mantenimientos
        </a>
    </div>
</div>
@endsection
