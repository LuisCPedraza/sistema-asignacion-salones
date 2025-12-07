{{-- resources/views/visualization/horario-semestral.blade.php --}}
@extends('layouts.app')

@section('title', 'Horario Semestral Completo')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="display-6 fw-bold text-primary">
            Horario Semestral Completo
        </h1>
        <div class="d-flex gap-2 align-items-center">
            <form method="GET" action="{{ route('visualizacion.horario.semestral') }}" class="d-inline">
                <select name="day" class="form-select w-auto" onchange="this.form.submit()">
                    <option value="">Todos los días</option>
                    <option value="monday" {{ request('day') == 'monday' ? 'selected' : '' }}>Lunes</option>
                    <option value="tuesday" {{ request('day') == 'tuesday' ? 'selected' : '' }}>Martes</option>
                    <option value="wednesday" {{ request('day') == 'wednesday' ? 'selected' : '' }}>Miércoles</option>
                    <option value="thursday" {{ request('day') == 'thursday' ? 'selected' : '' }}>Jueves</option>
                    <option value="friday" {{ request('day') == 'friday' ? 'selected' : '' }}>Viernes</option>
                    <option value="saturday" {{ request('day') == 'saturday' ? 'selected' : '' }}>Sábado</option>
                </select>
            </form>

            <a href="{{ route('visualizacion.horario.semestral.export') }}" class="btn btn-success">
                Exportar Excel
            </a>
            <a href="{{ route('academic.dashboard') }}" class="btn btn-outline-primary">
                ← Volver al Dashboard
            </a>
        </div>
    </div>

    <!-- FullCalendar -->
    <div class="card shadow-lg mb-5 border-0">
        <div class="card-header bg-gradient bg-primary text-white text-center py-3">
            <h4 class="mb-0">Calendario Semanal de Clases</h4>
        </div>
        <div class="card-body p-4 bg-light">
            <div id="calendar" style="min-height: 700px;"></div>
        </div>
    </div>

    <!-- Tabla detallada -->
    <div class="card shadow">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Detalle Completo de Asignaciones</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-primary text-dark">
                        <tr>
                            <th><strong>Grupo</strong></th>
                            <th><strong>Profesor</strong></th>
                            <th><strong>Salón</strong></th>
                            <th><strong>Día</strong></th>
                            <th><strong>Horario</strong></th>
                            <th class="text-center"><strong>Calidad</strong></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $diasEspanol = [
                                'monday'    => 'Lunes',
                                'tuesday'   => 'Martes',
                                'wednesday' => 'Miércoles',
                                'thursday'  => 'Jueves',
                                'friday'    => 'Viernes',
                                'saturday'  => 'Sábado'
                            ];
                        @endphp

                        @forelse($assignments as $assignment)
                            @php
                                $inicio = \Carbon\Carbon::parse($assignment->start_time);
                                $fin    = \Carbon\Carbon::parse($assignment->end_time);
                                $diaEsp = $diasEspanol[$assignment->day] ?? 'Desconocido';
                            @endphp
                            <tr>
                                <td>
                                    <strong class="text-primary">{{ $assignment->group->name }}</strong>
                                    @if($assignment->group->level ?? false)
                                        <br><small class="text-muted fst-italic">{{ $assignment->group->level }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($assignment->teacher)
                                        <strong>{{ $assignment->teacher->first_name }} {{ $assignment->teacher->last_name }}</strong>
                                    @else
                                        <em class="text-danger">Sin profesor asignado</em>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-info text-dark px-3 py-2 fs-6">
                                        {{ $assignment->classroom->name }}
                                    </span>
                                </td>
                                <td>
                                    <strong class="text-success">{{ $diaEsp }}</strong>
                                </td>
                                <td class="text-center font-monospace fw-bold text-dark">
                                    {{ $inicio->format('h:i A') }} → {{ $fin->format('h:i A') }}
                                </td>
                                <td class="text-center">
                                    @if($assignment->score >= 0.9)
                                        <span class="badge bg-success fs-6">EXCELENTE {{ round($assignment->score * 100) }}%</span>
                                    @elseif($assignment->score >= 0.8)
                                        <span class="badge bg-primary fs-6">MUY BUENO {{ round($assignment->score * 100) }}%</span>
                                    @elseif($assignment->score >= 0.7)
                                        <span class="badge bg-warning text-dark fs-6">Bueno {{ round($assignment->score * 100) }}%</span>
                                    @else
                                        <span class="badge bg-danger fs-6">Revisar {{ round($assignment->score * 100) }}%</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <h4 class="text-muted">No hay asignaciones aún</h4>
                                    <p>Genera el horario desde <strong>Asignación Automática</strong></p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'timeGridWeek,dayGridMonth'
        },
        slotMinTime: '07:00:00',
        slotMaxTime: '22:00:00',
        weekends: true,
        height: 'auto',
        locale: 'es',
        events: [
            @foreach($assignments as $assignment)
            @php
                $inicio = \Carbon\Carbon::parse($assignment->start_time);
                $fin = \Carbon\Carbon::parse($assignment->end_time);
                $profesor = $assignment->teacher ? $assignment->teacher->first_name . ' ' . $assignment->teacher->last_name : 'Sin profesor';
            @endphp
            {
                title: '{{ addslashes($assignment->group->name) }}',
                start: '{{ $assignment->day }}T{{ $inicio->format('H:i:s') }}',
                end: '{{ $assignment->day }}T{{ $fin->format('H:i:s') }}',
                backgroundColor: '{{ $assignment->score >= 0.9 ? "#0d6efd" : ($assignment->score >= 0.8 ? "#198754" : "#ffc107") }}',
                textColor: 'white',
                extendedProps: {
                    salon: '{{ addslashes($assignment->classroom->name) }}',
                    profesor: '{{ addslashes($profesor) }}',
                    score: '{{ round($assignment->score * 100) }}%'
                }
            },
            @endforeach
        ],
        eventContent: function(arg) {
            return {
                html: `<div class="fc-event-title fc-sticky"><strong>${arg.event.title}</strong><br><small>${arg.event.extendedProps.salon}</small><br><small>${arg.event.extendedProps.profesor}</small></div>`
            };
        }
    });
    calendar.render();
});
</script>
@endsection