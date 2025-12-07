@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>📅 Disponibilidades de {{ $teacher->first_name }} {{ $teacher->last_name }}</h1>
        <div>
            <a href="{{ route('gestion-academica.teachers.availabilities.create', $teacher) }}" class="btn btn-primary">
                ➕ Agregar Disponibilidad
            </a>
            <a href="{{ route('gestion-academica.teachers.index') }}" class="btn btn-secondary">Volver a Profesores</a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            @if($availabilities->isEmpty())
                <p class="text-center text-muted">No hay disponibilidades registradas.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Día</th>
                                <th>Horario</th>
                                <th>Disponible</th>
                                <th>Notas</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($availabilities as $availability)
                                <tr>
                                    <td>{{ $availability->day_name }}</td>
                                    <td>{{ $availability->time_range }}</td>
                                    <td>
                                        <span class="badge {{ $availability->is_available ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $availability->is_available ? 'Sí' : 'No' }}
                                        </span>
                                    </td>
                                    <td>{{ $availability->notes ?? '-' }}</td>
                                    <td>
                                        <a href="{{ route('gestion-academica.teachers.availabilities.edit', [$teacher, $availability]) }}" 
                                           class="btn btn-sm btn-warning">Editar</a>
                                        <form method="POST" 
                                              action="{{ route('gestion-academica.teachers.availabilities.destroy', [$teacher, $availability]) }}" 
                                              class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" 
                                                    onclick="return confirm('¿Eliminar esta disponibilidad?')">
                                                Eliminar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Resumen semanal -->
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0">📊 Resumen Semanal de Disponibilidad</h5>
        </div>
        <div class="card-body">
            <div class="row">
                @php
                    $days = ['monday' => 'Lunes', 'tuesday' => 'Martes', 'wednesday' => 'Miércoles', 
                            'thursday' => 'Jueves', 'friday' => 'Viernes', 'saturday' => 'Sábado'];
                @endphp
                @foreach($days as $key => $day)
                    @php
                        $dayAvailabilities = $availabilities->where('day_of_week', $key)->where('is_available', true);
                    @endphp
                    <div class="col-md-4 mb-3">
                        <strong>{{ $day }}:</strong>
                        @if($dayAvailabilities->isNotEmpty())
                            <div class="mt-1">
                                @foreach($dayAvailabilities as $avail)
                                    <span class="badge bg-info me-1 mb-1">
                                        {{ $avail->start_time->format('H:i') }}-{{ $avail->end_time->format('H:i') }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <span class="text-muted">No disponible</span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection