@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2">
                <i class="fas fa-calendar-check"></i> Mi Horario Personal
            </h1>
            @if($teacher)
                <p class="text-muted mb-0">
                    <strong>{{ $teacher->first_name }} {{ $teacher->last_name }}</strong> 
                    @if(auth()->user()->email)
                        • {{ auth()->user()->email }}
                    @endif
                </p>
            @else
                <p class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $message ?? 'No hay datos disponibles' }}</p>
            @endif
        </div>
        <div>
            <a href="{{ route('academic.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            @if($teacher)
                <a href="{{ route('asignacion.teacher.schedule.download') }}" class="btn btn-primary">
                    <i class="fas fa-download"></i> Descargar PDF
                </a>
            @endif
        </div>
    </div>

    @if($teacher)
        <!-- Estadísticas -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-3">
                                    <i class="fas fa-book fa-2x"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h3 class="mb-0 fw-bold">{{ $assignmentsCount }}</h3>
                                <p class="text-muted mb-0 small">Clases Asignadas</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-success bg-opacity-10 text-success rounded-3 p-3">
                                    <i class="fas fa-clock fa-2x"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h3 class="mb-0 fw-bold">{{ $totalHours }}h</h3>
                                <p class="text-muted mb-0 small">Horas Totales</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-info bg-opacity-10 text-info rounded-3 p-3">
                                    <i class="fas fa-users fa-2x"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h3 class="mb-0 fw-bold">{{ $groupsCount }}</h3>
                                <p class="text-muted mb-0 small">Grupos Diferentes</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-warning bg-opacity-10 text-warning rounded-3 p-3">
                                    <i class="fas fa-calendar-week fa-2x"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h3 class="mb-0 fw-bold">{{ $rawAssignments->pluck('day')->unique()->count() }}</h3>
                                <p class="text-muted mb-0 small">Días de Clase</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Calendario -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="fas fa-calendar"></i> Vista Semanal
                </h5>
            </div>
            <div class="card-body p-3">
                <div id="calendar"></div>
            </div>
        </div>

        <!-- Tabla de asignaciones -->
        @if($rawAssignments->count() > 0)
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-list"></i> Detalle de Asignaciones
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Materia</th>
                                    <th>Grupo</th>
                                    <th>Salón</th>
                                    <th>Día</th>
                                    <th>Horario</th>
                                    <th>Calidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $dayNames = [
                                        'monday' => 'Lunes',
                                        'tuesday' => 'Martes',
                                        'wednesday' => 'Miércoles',
                                        'thursday' => 'Jueves',
                                        'friday' => 'Viernes',
                                        'saturday' => 'Sábado',
                                        'sunday' => 'Domingo',
                                    ];
                                @endphp
                                @foreach($rawAssignments as $assignment)
                                    <tr>
                                        <td class="ps-4">
                                            <strong>{{ $assignment->subject->name ?? 'Sin materia' }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark">
                                                {{ $assignment->group->name ?? 'Sin grupo' }}
                                            </span>
                                        </td>
                                        <td>
                                            {{ $assignment->classroom->name ?? 'Sin salón' }}
                                        </td>
                                        <td>
                                            {{ $dayNames[strtolower($assignment->day)] ?? $assignment->day }}
                                        </td>
                                        <td>
                                            {{ Carbon\Carbon::parse($assignment->start_time)->format('H:i') }} - 
                                            {{ Carbon\Carbon::parse($assignment->end_time)->format('H:i') }}
                                        </td>
                                        <td>
                                            @php
                                                $score = round($assignment->score * 100, 1);
                                                $badgeColor = match(true) {
                                                    $score >= 80 => 'success',
                                                    $score >= 60 => 'warning',
                                                    $score >= 40 => 'danger',
                                                    default => 'secondary'
                                                };
                                            @endphp
                                            <span class="badge bg-{{ $badgeColor }}">
                                                {{ $score }}%
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-info" role="alert">
                <i class="fas fa-info-circle"></i> No tienes asignaciones de clase en este momento.
            </div>
        @endif
    @endif
</div>

@push('styles')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css' rel='stylesheet' />
<style>
    .fc-event {
        cursor: pointer;
    }
    
    .fc-event:hover {
        opacity: 0.9;
    }
    
    .fc-daygrid-day {
        height: 120px;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }
</style>
@endpush

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar');
        const events = @json($assignments);
        
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'timeGridWeek',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'timeGridWeek,timeGridDay'
            },
            locale: 'es',
            slotLabelFormat: {
                hour: '2-digit',
                minute: '2-digit',
                meridiem: false,
                hour12: false
            },
            eventClick: function(info) {
                const props = info.event.extendedProps;
                alert(
                    'Materia: ' + info.event.title + '\n' +
                    'Grupo: ' + props.group + '\n' +
                    'Salón: ' + props.classroom + '\n' +
                    'Horario: ' + props.startTime + ' - ' + props.endTime + '\n' +
                    'Calidad: ' + props.score
                );
            },
            events: events,
            height: 'auto'
        });
        
        calendar.render();
    });
</script>
@endpush

@endsection
