@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">📊 Reportes Académicos</h1>
            <p class="text-muted mb-0">Estadísticas de grupos, profesores y asignaciones.</p>
        </div>
        <a href="{{ route('gestion-academica.reports.export', ['start_date' => $filters['start_date'], 'end_date' => $filters['end_date']]) }}" 
           class="btn btn-danger">
            📄 Exportar PDF
        </a>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Desde</label>
                    <input type="date" name="start_date" value="{{ $filters['start_date'] ?? '' }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Hasta</label>
                    <input type="date" name="end_date" value="{{ $filters['end_date'] ?? '' }}" class="form-control">
                </div>
                <div class="col-md-2 d-grid">
                    <button class="btn btn-primary">Filtrar</button>
                </div>
                <div class="col-md-2 d-grid">
                    <a href="{{ route('gestion-academica.reports.index') }}" class="btn btn-outline-secondary">Limpiar</a>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-muted small">Grupos Totales</div>
                    <div class="fs-3 fw-bold text-primary">{{ $metrics['total_groups'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-muted small">Grupos Activos</div>
                    <div class="fs-3 fw-bold text-success">{{ $metrics['active_groups'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-muted small">Profesores Totales</div>
                    <div class="fs-3 fw-bold text-primary">{{ $metrics['total_teachers'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-muted small">Profesores Activos</div>
                    <div class="fs-3 fw-bold text-success">{{ $metrics['active_teachers'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-muted small">Total Estudiantes</div>
                    <div class="fs-3 fw-bold text-info">{{ $metrics['total_students'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-muted small">Asignaciones</div>
                    <div class="fs-3 fw-bold text-secondary">{{ $metrics['total_assignments'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-muted small">Horas de Clase</div>
                    <div class="fs-3 fw-bold text-warning">{{ $metrics['total_class_hours'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-muted small">Calidad Promedio</div>
                    <div class="fs-3 fw-bold text-success">{{ $metrics['avg_quality'] ?? 0 }}%</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-light fw-semibold">Grupos Recientes</div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Nombre</th>
                                    <th>Carrera</th>
                                    <th>Estudiantes</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentGroups as $group)
                                    <tr>
                                        <td class="fw-semibold">{{ $group->name }}</td>
                                        <td>{{ $group->career->name ?? 'N/D' }}</td>
                                        <td>{{ $group->student_count ?? 0 }}</td>
                                        <td>
                                            <span class="badge bg-{{ $group->is_active ? 'success' : 'secondary' }}">
                                                {{ $group->is_active ? 'Activo' : 'Inactivo' }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center text-muted py-3">Sin grupos en el rango.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-light fw-semibold">Profesores Recientes</div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th>Especialización</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentTeachers as $teacher)
                                    <tr>
                                        <td class="fw-semibold">{{ $teacher->first_name }} {{ $teacher->last_name }}</td>
                                        <td>{{ $teacher->user->email ?? 'N/D' }}</td>
                                        <td>{{ Str::limit($teacher->specialization ?? 'N/D', 20) }}</td>
                                        <td>
                                            <span class="badge bg-{{ $teacher->is_active ? 'success' : 'secondary' }}">
                                                {{ $teacher->is_active ? 'Activo' : 'Inactivo' }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center text-muted py-3">Sin profesores en el rango.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
