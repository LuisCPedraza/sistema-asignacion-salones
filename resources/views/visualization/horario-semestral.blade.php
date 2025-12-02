@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>📊 Horario Semestral Completo</h1>
        <div>
            <form method="GET" action="{{ route('visualizacion.horario.semestral') }}" class="d-inline">
                <select name="day" class="form-select d-inline w-auto me-2" onchange="this.form.submit()">
                    <option value="">Todos los días</option>
                    <option value="monday" {{ request('day') == 'monday' ? 'selected' : '' }}>Lunes</option>
                    <option value="tuesday" {{ request('day') == 'tuesday' ? 'selected' : '' }}>Martes</option>
                    <option value="wednesday" {{ request('day') == 'wednesday' ? 'selected' : '' }}>Miércoles</option>
                    <option value="thursday" {{ request('day') == 'thursday' ? 'selected' : '' }}>Jueves</option>
                    <option value="friday" {{ request('day') == 'friday' ? 'selected' : '' }}>Viernes</option>
                    <option value="saturday" {{ request('day') == 'saturday' ? 'selected' : '' }}>Sábado</option>
                </select>
            </form>
            <a href="{{ route('visualizacion.horario.semestral.export') }}" class="btn btn-success">📥 Exportar Excel</a>
            <a href="{{ route('academic.dashboard') }}" class="btn btn-secondary">← Dashboard</a>
        </div>
    </div>

    <div id="calendar" class="mb-4" style="height: 600px;"></div>

    <div class="card">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Grupo</th>
                        <th>Profesor</th>
                        <th>Salón</th>
                        <th>Día</th>
                        <th>Hora</th>
                        <th>Score</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($assignments as $assignment)
                    <tr>
                        <td>{{ $assignment->group->name }}</td>
                        <td>{{ $assignment->teacher->name }}</td>
                        <td>{{ $assignment->classroom->name }}</td>
                        <td>{{ ucfirst($assignment->day) }}</td>
                        <td>{{ $assignment->start_time->format('H:i') }} - {{ $assignment->end_time->format('H:i') }}</td>
                        <td><span class="badge {{ $assignment->score > 0.8 ? 'bg-success' : 'bg-warning' }}">{{ round($assignment->score * 100, 0) }}%</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center">No hay asignaciones.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek'
        },
        events: [
            @foreach($assignments as $assignment)
            {
                title: '{{ $assignment->group->name }} ({{ $assignment->classroom->name }})',
                start: '{{ $assignment->day }}T{{ $assignment->start_time->format('H:i:s') }}',
                end: '{{ $assignment->day }}T{{ $assignment->end_time->format('H:i:s') }}',
                backgroundColor: '{{ $assignment->score > 0.8 ? '#28a745' : '#ffc107' }}',
                borderColor: '{{ $assignment->score > 0.8 ? '#28a745' : '#ffc107' }}'
            },
            @endforeach
        ]
    });
    calendar.render();
});
</script>
@endsection