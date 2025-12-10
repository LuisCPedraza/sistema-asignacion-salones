@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">📅 Reservas</h1>
            <p class="text-muted mb-0">Gestiona reservas de salones y recursos de infraestructura.</p>
        </div>
        <a href="{{ route('infraestructura.reservations.create') }}" class="btn btn-primary">
            ➕ Nueva Reserva
        </a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-muted small">Pendientes</div>
                    <div class="fs-3 fw-bold text-warning">{{ $statusCounts['pendiente'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-muted small">Aprobadas</div>
                    <div class="fs-3 fw-bold text-success">{{ $statusCounts['aprobada'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-muted small">Rechazadas</div>
                    <div class="fs-3 fw-bold text-danger">{{ $statusCounts['rechazada'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-muted small">Canceladas</div>
                    <div class="fs-3 fw-bold text-secondary">{{ $statusCounts['cancelada'] ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Salón</label>
                    <select name="classroom_id" class="form-select">
                        <option value="">Todos</option>
                        @foreach($classrooms as $classroom)
                            <option value="{{ $classroom->id }}" @selected(($filters['classroom_id'] ?? '') == $classroom->id)>
                                {{ $classroom->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Estado</label>
                    <select name="status" class="form-select">
                        <option value="">Todos</option>
                        @foreach(['pendiente' => 'Pendiente', 'aprobada' => 'Aprobada', 'rechazada' => 'Rechazada', 'cancelada' => 'Cancelada'] as $value => $label)
                            <option value="{{ $value }}" @selected(($filters['status'] ?? '') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Desde</label>
                    <input type="date" name="start_from" value="{{ $filters['start_from'] ?? '' }}" class="form-control">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Hasta</label>
                    <input type="date" name="start_to" value="{{ $filters['start_to'] ?? '' }}" class="form-control">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Buscar</label>
                    <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" class="form-control" placeholder="Título, solicitante...">
                </div>
                <div class="col-md-1 d-grid">
                    <button class="btn btn-outline-primary">Filtrar</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Salón</th>
                            <th>Título</th>
                            <th>Solicitante</th>
                            <th>Inicio</th>
                            <th>Fin</th>
                            <th>Estado</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reservations as $reservation)
                            <tr>
                                <td>{{ $reservation->classroom->name ?? 'N/D' }}</td>
                                <td class="fw-semibold">{{ $reservation->title }}</td>
                                <td>
                                    {{ $reservation->requester_name }}<br>
                                    <small class="text-muted">{{ $reservation->requester_email }}</small>
                                </td>
                                <td>{{ $reservation->start_time->format('Y-m-d H:i') }}</td>
                                <td>{{ $reservation->end_time->format('Y-m-d H:i') }}</td>
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
                                <td class="text-end">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('infraestructura.reservations.show', $reservation) }}" class="btn btn-sm btn-outline-secondary">Ver</a>
                                        <a href="{{ route('infraestructura.reservations.edit', $reservation) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                                        <form action="{{ route('infraestructura.reservations.destroy', $reservation) }}" method="POST" onsubmit="return confirm('¿Eliminar reserva?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                                        </form>
                                    </div>
                                    <div class="mt-2 d-flex gap-1 justify-content-end">
                                        @if($reservation->status === 'pendiente')
                                            <form action="{{ route('infraestructura.reservations.approve', $reservation) }}" method="POST">
                                                @csrf
                                                <button class="btn btn-sm btn-success">Aprobar</button>
                                            </form>
                                            <form action="{{ route('infraestructura.reservations.reject', $reservation) }}" method="POST">
                                                @csrf
                                                <button class="btn btn-sm btn-warning">Rechazar</button>
                                            </form>
                                        @endif
                                        @if($reservation->status !== 'cancelada')
                                            <form action="{{ route('infraestructura.reservations.cancel', $reservation) }}" method="POST">
                                                @csrf
                                                <button class="btn btn-sm btn-secondary">Cancelar</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">No hay reservas registradas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3">
                {{ $reservations->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
