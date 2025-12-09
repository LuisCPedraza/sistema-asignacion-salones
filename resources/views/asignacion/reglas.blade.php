@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>⚙️ Configuración de Reglas</h1>
        <a href="{{ route('asignacion.automatica') }}" class="btn btn-secondary">← Volver</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-sliders-h"></i> Ajustar Pesos de las Reglas</h5>
            <small>Los pesos determinan la importancia de cada criterio en el algoritmo de asignación</small>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('asignacion.reglas.actualizar') }}">
                @csrf
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 40%">Regla</th>
                                <th style="width: 25%">Peso (%)</th>
                                <th style="width: 20%">Estado</th>
                                <th style="width: 15%" class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rules as $index => $rule)
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $rule->name }}</div>
                                    <small class="text-muted">{{ $rule->description ?? 'Sin descripción' }}</small>
                                </td>
                                <td>
                                    <input type="hidden" name="rules[{{ $index }}][id]" value="{{ $rule->id }}">
                                    <div class="input-group">
                                        <input 
                                            type="number" 
                                            name="rules[{{ $index }}][weight]" 
                                            class="form-control" 
                                            value="{{ number_format($rule->weight * 100, 0) }}" 
                                            min="0" 
                                            max="100" 
                                            step="5"
                                            required
                                        >
                                        <span class="input-group-text">%</span>
                                    </div>
                                    <small class="text-muted">Actual: {{ number_format($rule->weight * 100, 0) }}%</small>
                                </td>
                                <td>
                                    <span class="badge {{ $rule->is_active ? 'bg-success' : 'bg-secondary' }} fs-6">
                                        {{ $rule->is_active ? '✓ Activa' : '✗ Inactiva' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <form method="POST" action="{{ route('asignacion.reglas.toggle', $rule->id) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $rule->is_active ? 'btn-warning' : 'btn-success' }}" title="{{ $rule->is_active ? 'Desactivar' : 'Activar' }}">
                                            <i class="fas fa-{{ $rule->is_active ? 'toggle-on' : 'toggle-off' }}"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="4" class="text-end">
                                    <strong>Total debe sumar 100%</strong>
                                    <span id="totalWeight" class="badge bg-info ms-2">0%</span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        <small class="text-muted">
                            <i class="fas fa-info-circle"></i> 
                            El total de los pesos debe sumar 100% para un funcionamiento óptimo
                        </small>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Calcular total de pesos en tiempo real
    document.addEventListener('DOMContentLoaded', function() {
        const weightInputs = document.querySelectorAll('input[name*="[weight]"]');
        const totalBadge = document.getElementById('totalWeight');
        
        function updateTotal() {
            let total = 0;
            weightInputs.forEach(input => {
                total += parseFloat(input.value) || 0;
            });
            
            totalBadge.textContent = total.toFixed(0) + '%';
            
            // Cambiar color según si suma 100%
            if (Math.abs(total - 100) < 1) {
                totalBadge.className = 'badge bg-success ms-2';
            } else if (total > 100) {
                totalBadge.className = 'badge bg-danger ms-2';
            } else {
                totalBadge.className = 'badge bg-warning text-dark ms-2';
            }
        }
        
        weightInputs.forEach(input => {
            input.addEventListener('input', updateTotal);
        });
        
        updateTotal();
    });
</script>
@endsection