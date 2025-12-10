@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h4 mb-1">📄 Detalle de Reserva</h1>
            <p class="text-muted mb-0">{{ $reservation->title }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('infraestructura.reservations.edit', $reservation) }}" class="btn btn-outline-primary">Editar</a>
            <a href="{{ route('infraestructura.reservations.index') }}" class="btn btn-outline-secondary">Volver</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <h5 class="mb-3">Información General</h5>
                    <p class="mb-1"><strong>Salón:</strong> {{ $reservation->classroom->name ?? 'N/D' }}</p>
                    <p class="mb-1"><strong>Solicitante:</strong> {{ $reservation->requester_name }}</p>
                    <p class="mb-1"><strong>Correo:</strong> {{ $reservation->requester_email ?? 'N/D' }}</p>
                    <p class="mb-1"><strong>Inicio:</strong> {{ $reservation->start_time->format('Y-m-d H:i') }}</p>
                    <p class="mb-1"><strong>Fin:</strong> {{ $reservation->end_time->format('Y-m-d H:i') }}</p>
                    <p class="mb-1"><strong>Estado:</strong>
                        @php
                            $badge = [
                                'pendiente' => 'warning',
                                'aprobada' => 'success',
                                'rechazada' => 'danger',
                                'cancelada' => 'secondary',
                            ][$reservation->status] ?? 'secondary';
                        @endphp
                        <span class="badge bg-{{ $badge }} text-uppercase">{{ $reservation->status }}</span>
                    </p>
                    @if($reservation->description)
                        <p class="mt-3"><strong>Descripción:</strong><br>{{ $reservation->description }}</p>
                    @endif
                    @if($reservation->notes)
                        <p class="mt-2"><strong>Notas:</strong><br>{{ $reservation->notes }}</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <h5 class="mb-3">Acciones rápidas</h5>
                    @if($reservation->status === 'pendiente')
                        <form action="{{ route('infraestructura.reservations.approve', $reservation) }}" method="POST" class="d-grid gap-2 mb-2">
                            @csrf
                            <button class="btn btn-success">Aprobar</button>
                        </form>
                        <form action="{{ route('infraestructura.reservations.reject', $reservation) }}" method="POST" class="d-grid gap-2 mb-2">
                            @csrf
                            <button class="btn btn-warning">Rechazar</button>
                        </form>
                    @endif
                    @if($reservation->status !== 'cancelada')
                        <form action="{{ route('infraestructura.reservations.cancel', $reservation) }}" method="POST" class="d-grid gap-2 mb-2">
                            @csrf
                            <button class="btn btn-secondary">Cancelar</button>
                        </form>
                    @endif
                    <form action="{{ route('infraestructura.reservations.destroy', $reservation) }}" method="POST" onsubmit="return confirm('¿Eliminar reserva?');" class="d-grid gap-2">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-outline-danger">Eliminar</button>
                    </form>
                </div>
            </div>
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="mb-3">Metadatos</h5>
                    <p class="mb-1"><strong>ID:</strong> {{ $reservation->id }}</p>
                    <p class="mb-1"><strong>Creado:</strong> {{ $reservation->created_at->format('Y-m-d H:i') }}</p>
                    <p class="mb-1"><strong>Actualizado:</strong> {{ $reservation->updated_at->format('Y-m-d H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
