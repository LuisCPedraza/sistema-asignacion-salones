@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1"><i class="bi bi-person-workspace text-primary"></i> Gestión de Profesores</h2>
            <p class="text-muted mb-0">Administración del cuerpo docente</p>
        </div>
        <a href="{{ route('gestion-academica.teachers.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nuevo Profesor
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
        <div class="col-lg-2 col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Total</p>
                            <h4 class="mb-0">{{ $stats['total'] ?? 0 }}</h4>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                            <i class="bi bi-person-workspace fs-5 text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Activos</p>
                            <h4 class="mb-0 text-success">{{ $stats['active'] ?? 0 }}</h4>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="bi bi-check-circle fs-5 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Con Materias</p>
                            <h4 class="mb-0 text-info">{{ $stats['with_assignments'] ?? 0 }}</h4>
                        </div>
                        <div class="bg-info bg-opacity-10 p-3 rounded">
                            <i class="bi bi-book fs-5 text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Sobrecargados</p>
                            <h4 class="mb-0 text-danger">{{ $stats['overloaded'] ?? 0 }}</h4>
                        </div>
                        <div class="bg-danger bg-opacity-10 p-3 rounded">
                            <i class="bi bi-exclamation-triangle fs-5 text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Prom. Materias</p>
                            <h4 class="mb-0 text-warning">{{ $stats['avg_subjects'] ?? 0 }}</h4>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded">
                            <i class="bi bi-bar-chart fs-5 text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Con Disponib.</p>
                            <h4 class="mb-0 text-secondary">{{ $stats['with_availability'] ?? 0 }}</h4>
                        </div>
                        <div class="bg-secondary bg-opacity-10 p-3 rounded">
                            <i class="bi bi-calendar-check fs-5 text-secondary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros y Búsqueda -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('gestion-academica.teachers.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small text-muted">Buscar</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-start-0" 
                               placeholder="Nombre, email o especialidad..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted">Estado</label>
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="">Todos</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Activos</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactivos</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted">Carga Docente</label>
                    <select name="workload" class="form-select" onchange="this.form.submit()">
                        <option value="">Todos</option>
                        <option value="available" {{ request('workload') == 'available' ? 'selected' : '' }}>Disponibles</option>
                        <option value="normal" {{ request('workload') == 'normal' ? 'selected' : '' }}>Carga Normal</option>
                        <option value="overloaded" {{ request('workload') == 'overloaded' ? 'selected' : '' }}>Sobrecargados</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted">Disponibilidad</label>
                    <select name="availability" class="form-select" onchange="this.form.submit()">
                        <option value="">Todos</option>
                        <option value="configured" {{ request('availability') == 'configured' ? 'selected' : '' }}>Configurada</option>
                        <option value="pending" {{ request('availability') == 'pending' ? 'selected' : '' }}>Sin Configurar</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted">Ordenar</label>
                    <select name="sort" class="form-select" onchange="this.form.submit()">
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nombre</option>
                        <option value="workload" {{ request('sort') == 'workload' ? 'selected' : '' }}>Carga Horaria</option>
                        <option value="subjects" {{ request('sort') == 'subjects' ? 'selected' : '' }}>N° Materias</option>
                        <option value="experience" {{ request('sort') == 'experience' ? 'selected' : '' }}>Experiencia</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <label class="form-label small text-muted">&nbsp;</label>
                    <div class="d-grid">
                        <a href="{{ route('gestion-academica.teachers.index') }}" class="btn btn-outline-secondary btn-sm d-flex align-items-center justify-content-center" title="Limpiar filtros">
                            <i class="bi bi-x-circle me-1"></i> Limpiar
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de Profesores -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3 border-0">
                                <i class="bi bi-person text-muted"></i> Profesor
                            </th>
                            <th class="py-3 border-0">
                                <i class="bi bi-briefcase text-muted"></i> Especialidad
                            </th>
                            <th class="py-3 border-0">
                                <i class="bi bi-envelope text-muted"></i> Contacto
                            </th>
                            <th class="py-3 border-0">
                                <i class="bi bi-calendar-check text-muted"></i> Experiencia
                            </th>
                            <th class="py-3 border-0">
                                <i class="bi bi-mortarboard text-muted"></i> Grado
                            </th>
                            <th class="py-3 border-0 text-center">
                                <i class="bi bi-book text-muted"></i> Materias
                            </th>
                            <th class="py-3 border-0 text-center">
                                <i class="bi bi-clock text-muted"></i> Horas/Sem
                            </th>
                            <th class="py-3 border-0 text-center">
                                <i class="bi bi-toggle-on text-muted"></i> Estado
                            </th>
                            <th class="py-3 border-0 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($teachers as $teacher)
                            <tr>
                                <td class="px-4">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                            <i class="bi bi-person-fill text-primary fs-5"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $teacher->first_name }} {{ $teacher->last_name }}</h6>
                                            <small class="text-muted">ID: #{{ $teacher->id }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info bg-opacity-10 text-info">
                                        {{ $teacher->specialty ?? 'Sin especialidad' }}
                                    </span>
                                </td>
                                <td>
                                    <div>
                                        <small class="text-muted d-block">
                                            <i class="bi bi-envelope-fill"></i> {{ $teacher->email }}
                                        </small>
                                        @if($teacher->phone)
                                            <small class="text-muted">
                                                <i class="bi bi-telephone-fill"></i> {{ $teacher->phone }}
                                            </small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($teacher->years_experience >= 10)
                                            <i class="bi bi-star-fill text-warning me-2"></i>
                                        @elseif($teacher->years_experience >= 5)
                                            <i class="bi bi-star-half text-warning me-2"></i>
                                        @else
                                            <i class="bi bi-star text-muted me-2"></i>
                                        @endif
                                        <strong>{{ $teacher->years_experience }}</strong> años
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $badgeColor = match($teacher->academic_degree) {
                                            'Doctorado' => 'danger',
                                            'Maestría' => 'warning',
                                            'Licenciatura' => 'info',
                                            default => 'secondary'
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $badgeColor }}">
                                        {{ $teacher->academic_degree ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @php
                                        $subjectCount = $teacher->assignments_count ?? $teacher->course_schedules_count ?? 0;
                                        $subjectBadge = match(true) {
                                            $subjectCount >= 5 => 'danger',
                                            $subjectCount >= 3 => 'warning',
                                            $subjectCount >= 1 => 'success',
                                            default => 'secondary'
                                        };
                                    @endphp
                                    @if($subjectCount > 0)
                                        <span class="badge bg-{{ $subjectBadge }} rounded-pill fs-6" data-bs-toggle="tooltip" 
                                              title="{{ $subjectCount >= 5 ? 'Sobrecarga' : ($subjectCount >= 3 ? 'Carga alta' : 'Carga normal') }}">
                                            {{ $subjectCount }}
                                            @if($subjectCount >= 5)<i class="bi bi-exclamation-triangle-fill ms-1"></i>@endif
                                        </span>
                                    @else
                                        <span class="badge bg-secondary rounded-pill" data-bs-toggle="tooltip" title="Sin asignaciones">
                                            <i class="bi bi-dash-circle"></i>
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @php
                                        // Calcular horas semanales basado en número de materias (estimado: 4h por materia)
                                        $subjectCount = $teacher->assignments_count ?? $teacher->course_schedules_count ?? 0;
                                        $hours = $subjectCount * 4;
                                        $hoursBadge = match(true) {
                                            $hours >= 20 => 'danger',
                                            $hours >= 12 => 'warning',
                                            $hours >= 4 => 'info',
                                            $hours > 0 => 'success',
                                            default => 'secondary'
                                        };
                                    @endphp
                                    @if($hours > 0)
                                        <span class="badge bg-{{ $hoursBadge }} rounded-pill fs-6" data-bs-toggle="tooltip" 
                                              title="Estimado: {{ $hours }} horas semanales ({{ $subjectCount }} materias x 4h)">
                                            {{ $hours }}h <small>(est.)</small>
                                            @if($hours >= 20)<i class="bi bi-exclamation-triangle-fill ms-1"></i>@endif
                                        </span>
                                        @if($teacher->availabilities_count > 0)
                                            <div class="mt-1">
                                                <small class="badge bg-success bg-opacity-25 text-success">
                                                    <i class="bi bi-calendar-check"></i> {{ $teacher->availabilities_count }}
                                                </small>
                                            </div>
                                        @endif
                                    @else
                                        <span class="badge bg-secondary rounded-pill" data-bs-toggle="tooltip" title="Sin horas asignadas">
                                            <i class="bi bi-dash-circle"></i>
                                        </span>
                                        @if($teacher->availabilities_count > 0)
                                            <div class="mt-1">
                                                <small class="badge bg-primary bg-opacity-25 text-primary">
                                                    <i class="bi bi-calendar-check"></i> Configurada
                                                </small>
                                            </div>
                                        @else
                                            <div class="mt-1">
                                                <small class="badge bg-warning bg-opacity-25 text-warning">
                                                    <i class="bi bi-calendar-x"></i> Pendiente
                                                </small>
                                            </div>
                                        @endif
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($teacher->is_active)
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
                                        <a href="{{ route('gestion-academica.teachers.show', $teacher) }}" 
                                           class="btn btn-sm btn-primary"
                                           data-bs-toggle="tooltip" title="Ver detalles">
                                            Ver
                                        </a>
                                        <a href="{{ route('gestion-academica.teachers.edit', $teacher) }}" 
                                           class="btn btn-sm btn-warning text-dark"
                                           data-bs-toggle="tooltip" title="Editar">
                                            Editar
                                        </a>
                                        <a href="{{ route('gestion-academica.teachers.availabilities.index', $teacher) }}" 
                                           class="btn btn-sm btn-success"
                                           data-bs-toggle="tooltip" title="Disponibilidades">
                                            Dispon.
                                        </a>
                                        <form method="POST" 
                                              action="{{ route('gestion-academica.teachers.destroy', $teacher) }}" 
                                              class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-danger" 
                                                    onclick="return confirm('¿Desactivar este profesor?')"
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
                                        <i class="bi bi-people fs-1 d-block mb-3"></i>
                                        <h5>No hay profesores registrados</h5>
                                        <p class="mb-0">Comienza agregando tu primer profesor</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($teachers->hasPages())
            <div class="card-footer bg-white border-top">
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        Mostrando {{ $teachers->firstItem() }} - {{ $teachers->lastItem() }} de {{ $teachers->total() }} profesores
                    </small>
                    
                    <!-- Paginación Personalizada Pequeña -->
                    <nav aria-label="Paginación">
                        <ul class="pagination pagination-sm mb-0">
                            {{-- Previous Page Link --}}
                            @if ($teachers->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link">‹</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $teachers->previousPageUrl() }}" rel="prev">‹</a>
                                </li>
                            @endif

                            {{-- Pagination Elements --}}
                            @foreach ($teachers->getUrlRange(1, $teachers->lastPage()) as $page => $url)
                                @if ($page == $teachers->currentPage())
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
                            @if ($teachers->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $teachers->nextPageUrl() }}" rel="next">›</a>
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