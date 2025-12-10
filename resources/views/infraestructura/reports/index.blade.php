@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">📈 Reportes de Infraestructura</h1>
            <p class="text-muted mb-0">Uso de salones, reservas y mantenimientos.</p>
        </div>
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
                    <a href="{{ route('infraestructura.reports.index') }}" class="btn btn-outline-secondary">Limpiar</a>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-2">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-muted small">Salones Activos</div>
                    <div class="fs-3 fw-bold text-primary">{{ $metrics['active_classrooms'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-muted small">Reservas Totales</div>
                    <div class="fs-3 fw-bold text-primary">{{ $metrics['reservations_total'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-muted small">Reservas Aprobadas</div>
                    <div class="fs-3 fw-bold text-success">{{ $metrics['reservations_approved'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-muted small">Reservas Pendientes</div>
                    <div class="fs-3 fw-bold text-warning">{{ $metrics['reservations_pending'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-muted small">Horas Reservadas</div>
                    <div class="fs-3 fw-bold text-secondary">{{ $metrics['hours_reserved'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-muted small">Mtos en progreso</div>
                    <div class="fs-3 fw-bold text-info">{{ $metrics['maintenance_in_progress'] ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-light fw-semibold">Reservas recientes</div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Título</th>
                                    <th>Salón</th>
                                    <th>Inicio</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reservations as $reservation)
                                    <tr>
                                        <td class="fw-semibold">{{ $reservation->title }}</td>
                                        <td>{{ $reservation->classroom->name ?? 'N/D' }}</td>
                                        <td>{{ $reservation->start_time?->format('Y-m-d H:i') }}</td>
                                        <td>
                                            @php
                                                $badge = [
                                                    'pendiente' => 'warning',
                                                    'aprobada' => 'success',
                                                    'rechazada' => 'danger',
                                                    'cancelada' => 'secondary',
                                                ][$reservation->status] ?? 'secondary';
                                            @endphp
                                            <span class="badge bg-{{ $badge }} text-uppercase">{{ $reservation->status }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center text-muted py-3">Sin reservas en el rango.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-light fw-semibold">Mantenimientos recientes</div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Título</th>
                                    <th>Salón</th>
                                    <th>Inicio</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($maintenances as $maintenance)
                                    <tr>
                                        <td class="fw-semibold">{{ $maintenance->title }}</td>
                                        <td>{{ $maintenance->classroom->name ?? 'N/D' }}</td>
                                        <td>{{ $maintenance->start_date?->format('Y-m-d H:i') ?? '—' }}</td>
                                        <td>
                                            @php
                                                $badge = [
                                                    'pendiente' => 'warning',
                                                    'en_progreso' => 'info',
                                                    'completado' => 'success',
                                                    'cancelado' => 'secondary',
                                                ][$maintenance->status] ?? 'secondary';
                                            @endphp
                                            <span class="badge bg-{{ $badge }} text-uppercase">{{ $maintenance->status }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center text-muted py-3">Sin mantenimientos en el rango.</td></tr>
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
