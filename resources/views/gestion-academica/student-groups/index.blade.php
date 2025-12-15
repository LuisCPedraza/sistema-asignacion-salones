@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1"><i class="bi bi-people-fill text-primary"></i> Grupos de Estudiantes</h2>
            <p class="text-muted mb-0">Gestión completa de grupos académicos</p>
        </div>
        <a href="{{ route('gestion-academica.student-groups.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Crear Nuevo Grupo
        </a>
    </div>

    <!-- Alerts -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Estadísticas Rápidas -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Total Grupos</p>
                            <h3 class="mb-0">{{ $stats['total'] ?? 0 }}</h3>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                            <i class="bi bi-people fs-4 text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Grupos Activos</p>
                            <h3 class="mb-0 text-success">{{ $stats['active'] ?? 0 }}</h3>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="bi bi-check-circle fs-4 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Total Estudiantes</p>
                            <h3 class="mb-0 text-info">{{ $stats['students'] ?? 0 }}</h3>
                        </div>
                        <div class="bg-info bg-opacity-10 p-3 rounded">
                            <i class="bi bi-person-badge fs-4 text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Promedio x Grupo</p>
                            <h3 class="mb-0 text-warning">{{ $stats['avg'] ?? 0 }}</h3>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded">
                            <i class="bi bi-calculator fs-4 text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros y Búsqueda -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('gestion-academica.student-groups.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label small text-muted">Carrera</label>
                    <select name="career_id" class="form-select" onchange="this.form.submit()">
                        <option value="">Todas las carreras</option>
                        @foreach($careers as $career)
                            <option value="{{ $career->id }}" {{ request('career_id') == $career->id ? 'selected' : '' }}>
                                {{ $career->code }} - {{ $career->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted">Tipo de Grupo</label>
                    <select name="group_type" class="form-select" onchange="this.form.submit()">
                        <option value="">Todos los grupos</option>
                        <option value="A" {{ request('group_type') == 'A' ? 'selected' : '' }}>Grupo A (Diurno)</option>
                        <option value="B" {{ request('group_type') == 'B' ? 'selected' : '' }}>Grupo B (Nocturno)</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted">Jornada</label>
                    <select name="schedule_type" class="form-select" onchange="this.form.submit()">
                        <option value="">Todas</option>
                        <option value="day" {{ request('schedule_type') == 'day' ? 'selected' : '' }}>Diurna</option>
                        <option value="night" {{ request('schedule_type') == 'night' ? 'selected' : '' }}>Nocturna</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted">Estado</label>
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="">Todos</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Activos</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactivos</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <label class="form-label small text-muted">&nbsp;</label>
                    <div class="d-grid">
                        <a href="{{ route('gestion-academica.student-groups.index') }}" class="btn btn-outline-secondary btn-sm d-flex align-items-center justify-content-center" title="Limpiar filtros">
                            <i class="bi bi-x-circle me-1"></i> Limpiar
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de Grupos -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3 border-0">
                                <i class="bi bi-hash text-muted"></i> Grupo
                            </th>
                            <th class="py-3 border-0">
                                <i class="bi bi-mortarboard text-muted"></i> Carrera
                            </th>
                            <th class="py-3 border-0">
                                <i class="bi bi-bar-chart-steps text-muted"></i> Nivel
                            </th>
                            <th class="py-3 border-0">
                                <i class="bi bi-people text-muted"></i> Estudiantes
                            </th>
                            <th class="py-3 border-0">
                                <i class="bi bi-calendar-event text-muted"></i> Semestre
                            </th>
                            <th class="py-3 border-0">
                                <i class="bi bi-sun text-muted"></i> Jornada
                            </th>
                            <th class="py-3 border-0 text-center">
                                <i class="bi bi-toggle-on text-muted"></i> Estado
                            </th>
                            <th class="py-3 border-0 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($groups as $group)
                            <tr>
                                <td class="px-4">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                            <i class="bi bi-mortarboard text-primary"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $group->name }}</h6>
                                            <small class="text-muted">ID: #{{ $group->id }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($group->semester && $group->semester->career)
                                        <div>
                                            <strong>{{ $group->semester->career->code }}</strong>
                                            <div class="small text-muted text-truncate" style="max-width: 200px;">
                                                {{ $group->semester->career->name }}
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted small">Sin carrera</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-info">Nivel {{ $group->level }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-person-fill-check text-success me-2"></i>
                                        <strong>{{ $group->student_count }}</strong>
                                    </div>
                                </td>
                                <td>
                                    @if($group->semester)
                                        <span class="badge bg-secondary">
                                            {{ $group->semester->name ?? 'N/A' }}
                                        </span>
                                    @else
                                        <span class="text-muted small">Sin semestre</span>
                                    @endif
                                </td>
                                <td>
                                    @if($group->schedule_type === 'day')
                                        <span class="badge bg-warning text-dark">
                                            <i class="bi bi-sun-fill"></i> Diurna
                                        </span>
                                    @elseif($group->schedule_type === 'night')
                                        <span class="badge bg-dark">
                                            <i class="bi bi-moon-stars-fill"></i> Nocturna
                                        </span>
                                    @else
                                        <span class="text-muted small">No definida</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($group->is_active)
                                        <span class="badge bg-success rounded-pill">
                                            <i class="bi bi-check-circle"></i> Activo
                                        </span>
                                    @else
                                        <span class="badge bg-secondary rounded-pill">
                                            <i class="bi bi-x-circle"></i> Inactivo
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('gestion-academica.student-groups.show', $group) }}" 
                                           class="btn btn-sm btn-primary" 
                                           data-bs-toggle="tooltip" title="Ver detalles">
                                            Ver
                                        </a>
                                        <a href="{{ route('gestion-academica.student-groups.edit', $group) }}" 
                                           class="btn btn-sm btn-warning text-dark"
                                           data-bs-toggle="tooltip" title="Editar">
                                            Editar
                                        </a>
                                        <form method="POST" 
                                              action="{{ route('gestion-academica.student-groups.destroy', $group) }}" 
                                              class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-danger" 
                                                    onclick="return confirm('¿Desactivar este grupo?')"
                                                    data-bs-toggle="tooltip" title="Desactivar">
                                                Desactivar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                        <h5>No hay grupos registrados</h5>
                                        <p class="mb-0">Comienza creando tu primer grupo académico</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($groups->hasPages())
            <div class="card-footer bg-white border-top">
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        Mostrando {{ $groups->firstItem() }} - {{ $groups->lastItem() }} de {{ $groups->total() }} grupos
                    </small>
                    
                    <!-- Paginación Personalizada Pequeña -->
                    <nav aria-label="Paginación">
                        <ul class="pagination pagination-sm mb-0">
                            {{-- Previous Page Link --}}
                            @if ($groups->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link">‹</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $groups->previousPageUrl() }}" rel="prev">‹</a>
                                </li>
                            @endif

                            {{-- Pagination Elements --}}
                            @foreach ($groups->getUrlRange(1, $groups->lastPage()) as $page => $url)
                                @if ($page == $groups->currentPage())
                                    <li class="page-item active">
                                        <span class="page-link">{{ $page }}</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                    </li>
                                @endif
                            @endforeach

                            {{-- Next Page Link --}}
                            @if ($groups->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $groups->nextPageUrl() }}" rel="next">›</a>
                                </li>
                            @else
                                <li class="page-item disabled">
                                    <span class="page-link">›</span>
                                </li>
                            @endif
                        </ul>
                    </nav>
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    // Inicializar tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
</script>
@endpush

@push('styles')
<style>
    /* Paginación pequeña personalizada */
    .pagination-sm {
        margin: 0;
        gap: 2px;
    }
    
    .pagination-sm .page-link {
        font-size: 0.7rem;
        padding: 0.25rem 0.4rem;
        line-height: 1;
        border: 1px solid #dee2e6;
        color: #0d6efd;
        background-color: #fff;
        border-radius: 0.2rem;
        transition: all 0.15s ease;
        min-width: 24px;
        text-align: center;
    }
    
    .pagination-sm .page-link:hover:not(.disabled) {
        color: #0b5ed7;
        background-color: #e9ecef;
        border-color: #0b5ed7;
    }
    
    .pagination-sm .page-item.active .page-link {
        background-color: #0d6efd;
        border-color: #0d6efd;
        color: white;
        font-weight: 600;
    }
    
    .pagination-sm .page-item.disabled .page-link {
        opacity: 0.35;
        cursor: not-allowed;
        background-color: #f8f9fa;
    }
</style>
@endpush
@endsection