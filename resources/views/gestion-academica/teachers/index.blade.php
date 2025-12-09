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
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Total Profesores</p>
                            <h3 class="mb-0">{{ $stats['total'] ?? 0 }}</h3>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                            <i class="bi bi-person-workspace fs-4 text-primary"></i>
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
                            <p class="text-muted mb-1 small">Activos</p>
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
                            <p class="text-muted mb-1 small">Experiencia Prom.</p>
                            <h3 class="mb-0 text-info">{{ $stats['avg_experience'] ?? 0 }} años</h3>
                        </div>
                        <div class="bg-info bg-opacity-10 p-3 rounded">
                            <i class="bi bi-award fs-4 text-info"></i>
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
                            <p class="text-muted mb-1 small">Con Doctorado</p>
                            <h3 class="mb-0 text-warning">{{ $stats['doctorate'] ?? 0 }}</h3>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded">
                            <i class="bi bi-mortarboard fs-4 text-warning"></i>
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
                <div class="col-md-4">
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
                    <select name="status" class="form-select">
                        <option value="">Todos</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Activos</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactivos</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted">Grado Académico</label>
                    <select name="degree" class="form-select">
                        <option value="">Todos</option>
                        <option value="Licenciatura" {{ request('degree') == 'Licenciatura' ? 'selected' : '' }}>Licenciatura</option>
                        <option value="Maestría" {{ request('degree') == 'Maestría' ? 'selected' : '' }}>Maestría</option>
                        <option value="Doctorado" {{ request('degree') == 'Doctorado' ? 'selected' : '' }}>Doctorado</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted">Orden</label>
                    <select name="sort" class="form-select">
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nombre</option>
                        <option value="experience" {{ request('sort') == 'experience' ? 'selected' : '' }}>Experiencia</option>
                        <option value="recent" {{ request('sort') == 'recent' ? 'selected' : '' }}>Más recientes</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted">&nbsp;</label>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-funnel"></i> Filtrar
                        </button>
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
                    {{ $teachers->links() }}
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
@endsection