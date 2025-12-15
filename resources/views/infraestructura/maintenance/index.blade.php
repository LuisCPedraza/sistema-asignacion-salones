@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>🔧 Gestión de Mantenimiento (HU20)</h1>
        <a href="{{ route('infraestructura.maintenance.create') }}" class="btn btn-primary">
            ➕ Nuevo Mantenimiento
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>✅ Éxito:</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>❌ Error:</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Tarjetas de resumen -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">⏳ Pendientes</h5>
                    <h2 class="text-warning">{{ $maintenances->where('status', 'pendiente')->count() ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">🔄 En Progreso</h5>
                    <h2 class="text-info">{{ $maintenances->where('status', 'en_progreso')->count() ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">✅ Completados</h5>
                    <h2 class="text-success">{{ $maintenances->where('status', 'completado')->count() ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">🚫 Cancelados</h5>
                    <h2 class="text-danger">{{ $maintenances->where('status', 'cancelado')->count() ?? 0 }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Buscar..." 
                           value="{{ $filters['search'] ?? '' }}">
                </div>
                <div class="col-md-2">
                    <select name="classroom_id" class="form-select">
                        <option value="">Todos los Salones</option>
                        @foreach ($classrooms as $classroom)
                            <option value="{{ $classroom->id }}" 
                                {{ ($filters['classroom_id'] ?? '') == $classroom->id ? 'selected' : '' }}>
                                {{ $classroom->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="type" class="form-select">
                        <option value="">Todos los Tipos</option>
                        <option value="preventivo" {{ ($filters['type'] ?? '') == 'preventivo' ? 'selected' : '' }}>
                            Preventivo
                        </option>
                        <option value="correctivo" {{ ($filters['type'] ?? '') == 'correctivo' ? 'selected' : '' }}>
                            Correctivo
                        </option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">Todos los Estados</option>
                        <option value="pendiente" {{ ($filters['status'] ?? '') == 'pendiente' ? 'selected' : '' }}>
                            ⏳ Pendiente
                        </option>
                        <option value="en_progreso" {{ ($filters['status'] ?? '') == 'en_progreso' ? 'selected' : '' }}>
                            🔄 En Progreso
                        </option>
                        <option value="completado" {{ ($filters['status'] ?? '') == 'completado' ? 'selected' : '' }}>
                            ✅ Completado
                        </option>
                        <option value="cancelado" {{ ($filters['status'] ?? '') == 'cancelado' ? 'selected' : '' }}>
                            🚫 Cancelado
                        </option>
                    </select>
                </div>
                <div class="col-md-3 text-end">
                    <button type="submit" class="btn btn-primary">🔍 Filtrar</button>
                    <a href="{{ route('infraestructura.maintenance.index') }}" class="btn btn-secondary">🔄 Limpiar</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de mantenimientos -->
    @if ($maintenances->count() > 0)
        <div class="card">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Salón</th>
                        <th>Título</th>
                        <th>Tipo</th>
                        <th>Estado</th>
                        <th>Responsable</th>
                        <th>Fecha Programada</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($maintenances as $maintenance)
                        <tr>
                            <td>
                                <strong>{{ $maintenance->classroom->name ?? 'N/A' }}</strong>
                                <br>
                                <small class="text-muted">{{ $maintenance->classroom->code ?? '' }}</small>
                            </td>
                            <td>{{ $maintenance->title }}</td>
                            <td>
                                @if ($maintenance->type === 'preventivo')
                                    <span class="badge bg-info">🛡️ Preventivo</span>
                                @else
                                    <span class="badge bg-warning">🔨 Correctivo</span>
                                @endif
                            </td>
                            <td>
                                @switch($maintenance->status)
                                    @case('pendiente')
                                        <span class="badge bg-warning">⏳ Pendiente</span>
                                        @break
                                    @case('en_progreso')
                                        <span class="badge bg-primary">🔄 En Progreso</span>
                                        @break
                                    @case('completado')
                                        <span class="badge bg-success">✅ Completado</span>
                                        @break
                                    @case('cancelado')
                                        <span class="badge bg-danger">🚫 Cancelado</span>
                                        @break
                                @endswitch
                            </td>
                            <td>{{ $maintenance->responsible ?? '-' }}</td>
                            <td>
                                @if ($maintenance->scheduled_date)
                                    {{ $maintenance->scheduled_date->format('d/m/Y') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('infraestructura.maintenance.show', $maintenance) }}" 
                                   class="btn btn-sm btn-info" title="Ver detalles">
                                    👁️
                                </a>
                                <a href="{{ route('infraestructura.maintenance.edit', $maintenance) }}" 
                                   class="btn btn-sm btn-warning" title="Editar">
                                    ✏️
                                </a>
                                <form method="POST" 
                                      action="{{ route('infraestructura.maintenance.destroy', $maintenance) }}" 
                                      style="display: inline;"
                                      onsubmit="return confirm('¿Eliminar este mantenimiento?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
                                        🗑️
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Paginación numérica sin flechas -->
        <div class="mt-4 d-flex justify-content-end">
            <nav aria-label="Paginación de mantenimiento">
                <ul class="pagination">
                    @php
                        $totalPages = $maintenances->lastPage();
                        $current = $maintenances->currentPage();
                        $range = 2; // Número de páginas a mostrar a cada lado de la actual
                        $start = max(1, $current - $range);
                        $end = min($totalPages, $current + $range);
                    @endphp
                    
                    {{-- Primera página --}}
                    @if($start > 1)
                        <li class="page-item">
                            <a class="page-link" href="{{ $maintenances->url(1) }}">1</a>
                        </li>
                        @if($start > 2)
                            <li class="page-item disabled">
                                <span class="page-link">...</span>
                            </li>
                        @endif
                    @endif
                    
                    {{-- Rango de páginas --}}
                    @for ($i = $start; $i <= $end; $i++)
                        <li class="page-item {{ $i === $current ? 'active' : '' }}">
                            <a class="page-link" href="{{ $maintenances->url($i) }}">{{ $i }}</a>
                        </li>
                    @endfor
                    
                    {{-- Última página --}}
                    @if($end < $totalPages)
                        @if($end < $totalPages - 1)
                            <li class="page-item disabled">
                                <span class="page-link">...</span>
                            </li>
                        @endif
                        <li class="page-item">
                            <a class="page-link" href="{{ $maintenances->url($totalPages) }}">{{ $totalPages }}</a>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>
    @else
        <div class="alert alert-info text-center" role="alert">
            <strong>📭 No hay mantenimientos</strong>
            <p>No se encontraron registros de mantenimiento con los filtros aplicados.</p>
        </div>
    @endif

    <div class="mt-4">
        <a href="{{ route('infraestructura.dashboard') }}" class="btn btn-secondary">← Volver al Dashboard</a>
    </div>
</div>
@endsection
