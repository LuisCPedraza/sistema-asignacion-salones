@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>📅 Mi Horario Personal</h1>
        <div>
            <a href="{{ route('visualizacion.horario.personal.export') }}" class="btn btn-success me-2">📥 Exportar Excel</a>
            <a href="{{ route('profesor.dashboard') }}" class="btn btn-secondary">← Dashboard</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h5>Profesor: {{ $teacher->first_name }} {{ $teacher->last_name }}</h5>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Asignatura/Grupo</th>
                        <th>Salón</th>
                        <th>Día</th>
                        <th>Hora</th>
                        <th>Notas</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($assignments as $assignment)
                    <tr>
                        <td>{{ $assignment->group->name }}</td>
                        <td>{{ $assignment->classroom->name }}</td>
                        <td>{{ ucfirst($assignment->day) }}</td>
                        <td>{{ $assignment->start_time->format('H:i') }} - {{ $assignment->end_time->format('H:i') }}</td>
                        <td>{{ $assignment->notes ?? 'N/A' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center">No hay asignaciones asignadas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection