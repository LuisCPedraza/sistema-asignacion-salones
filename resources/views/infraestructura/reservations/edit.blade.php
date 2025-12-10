@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 mb-0">✏️ Editar Reserva</h1>
        <a href="{{ route('infraestructura.reservations.show', $reservation) }}" class="btn btn-outline-secondary">Volver</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('infraestructura.reservations.update', $reservation) }}" method="POST" class="row g-3">
                @csrf
                @method('PUT')

                <div class="col-md-6">
                    <label class="form-label">Salón</label>
                    <select name="classroom_id" class="form-select @error('classroom_id') is-invalid @enderror" required>
                        @foreach($classrooms as $classroom)
                            <option value="{{ $classroom->id }}" @selected(old('classroom_id', $reservation->classroom_id) == $classroom->id)>{{ $classroom->name }}</option>
                        @endforeach
                    </select>
                    @error('classroom_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Título</label>
                    <input type="text" name="title" value="{{ old('title', $reservation->title) }}" class="form-control @error('title') is-invalid @enderror" required>
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Descripción</label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="2">{{ old('description', $reservation->description) }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Solicitante</label>
                    <input type="text" name="requester_name" value="{{ old('requester_name', $reservation->requester_name) }}" class="form-control @error('requester_name') is-invalid @enderror" required>
                    @error('requester_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Correo del solicitante</label>
                    <input type="email" name="requester_email" value="{{ old('requester_email', $reservation->requester_email) }}" class="form-control @error('requester_email') is-invalid @enderror">
                    @error('requester_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Inicio</label>
                    <input type="datetime-local" name="start_time" value="{{ old('start_time', $reservation->start_time?->format('Y-m-d\TH:i')) }}" class="form-control @error('start_time') is-invalid @enderror" required>
                    @error('start_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Fin</label>
                    <input type="datetime-local" name="end_time" value="{{ old('end_time', $reservation->end_time?->format('Y-m-d\TH:i')) }}" class="form-control @error('end_time') is-invalid @enderror" required>
                    @error('end_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Estado</label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                        @foreach(['pendiente' => 'Pendiente', 'aprobada' => 'Aprobada', 'rechazada' => 'Rechazada', 'cancelada' => 'Cancelada'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('status', $reservation->status) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Notas</label>
                    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="1">{{ old('notes', $reservation->notes) }}</textarea>
                    @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12 d-flex justify-content-end gap-2">
                    <a href="{{ route('infraestructura.reservations.show', $reservation) }}" class="btn btn-outline-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
