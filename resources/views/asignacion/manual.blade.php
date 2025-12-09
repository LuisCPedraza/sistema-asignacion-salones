@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>👆 Asignación Manual - Drag & Drop</h1>
        <div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevaAsignacionModal">
                ➕ Nueva Asignación
            </button>
            <a href="{{ route('academic.dashboard') }}" class="btn btn-secondary">← Volver</a>
        </div>
    </div>

    <!-- Selector de Período Académico -->
    @if($periods->count() > 0)
        <div class="card mb-3 border-info">
            <div class="card-body">
                <form method="GET" action="{{ route('asignacion.manual') }}" class="d-flex gap-2 align-items-end">
                    <div class="flex-grow-1">
                        <label for="period_id" class="form-label fw-semibold">
                            <i class="fas fa-calendar-alt"></i> Período Académico / Semestre
                        </label>
                        <select id="period_id" name="period_id" class="form-select" onchange="this.form.submit()">
                            <option value="">-- Todos los períodos --</option>
                            @foreach($periods as $p)
                                <option value="{{ $p->id }}" {{ $period?->id === $p->id ? 'selected' : '' }}>
                                    {{ $p->name }} ({{ $p->start_date->format('d/m/Y') }} - {{ $p->end_date->format('d/m/Y') }})
                                    @if($p->is_active) <span class="badge bg-success">Activo</span> @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @if($period)
                        <div>
                            <small class="text-muted">
                                <i class="fas fa-info-circle"></i>
                                <strong>{{ $period->name }}</strong>: {{ $period->start_date->format('d/m/Y') }} → {{ $period->end_date->format('d/m/Y') }}
                            </small>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    @endif

    <!-- Estadísticas -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted">Total Asignaciones</h6>
                    <h3>{{ $assignments->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted">Grupos Activos</h6>
                    <h3>{{ $groups->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted">Salones Disponibles</h6>
                    <h3>{{ $classrooms->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted">Profesores Activos</h6>
                    <h3>{{ $teachers->count() }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros (similar a asignación automática) -->
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="fas fa-filter"></i> Filtros</h5>
        </div>
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-2">
                    <label for="filter_day" class="form-label">Día</label>
                    <select id="filter_day" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        <option value="monday">Lunes</option>
                        <option value="tuesday">Martes</option>
                        <option value="wednesday">Miércoles</option>
                        <option value="thursday">Jueves</option>
                        <option value="friday">Viernes</option>
                        <option value="saturday">Sábado</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filter_group" class="form-label">Grupo</label>
                    <select id="filter_group" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        @foreach($groups as $group)
                            <option value="{{ $group->id }}">{{ $group->semester->career->name ?? '' }} - Sem {{ $group->semester->number ?? '' }} - {{ $group->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filter_teacher" class="form-label">Profesor</label>
                    <select id="filter_teacher" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}">{{ $teacher->first_name }} {{ $teacher->last_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="filter_classroom" class="form-label">Salón</label>
                    <select id="filter_classroom" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        @foreach($classrooms as $classroom)
                            <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="filter_subject" class="form-label">Materia</label>
                    <select id="filter_subject" class="form-select form-select-sm">
                        <option value="">Todas</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 d-flex gap-2">
                    <button id="applyFilters" type="button" class="btn btn-primary btn-sm">
                        <i class="fas fa-search"></i> Aplicar filtros
                    </button>
                    <button id="clearFilters" type="button" class="btn btn-secondary btn-sm">
                        <i class="fas fa-times"></i> Limpiar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Calendario con Drag & Drop -->
    <div class="card">
        <div class="card-body">
            <div id="calendar"></div>
        </div>
    </div>
</div>

<!-- Modal: Nueva Asignación -->
<div class="modal fade" id="nuevaAsignacionModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">➕ Nueva Asignación Manual</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formNuevaAsignacion">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Grupo de Estudiantes</label>
                            <select name="student_group_id" class="form-select" required>
                                <option value="">Seleccionar...</option>
                                @foreach($groups as $group)
                                    <option value="{{ $group->id }}">
                                        {{ $group->semester->career->name ?? '' }} - Sem {{ $group->semester->number ?? '' }} - {{ $group->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Materia</label>
                            <select name="subject_id" class="form-select" required>
                                <option value="">Seleccionar...</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Profesor</label>
                            <select name="teacher_id" class="form-select" required>
                                <option value="">Seleccionar...</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}">{{ $teacher->first_name }} {{ $teacher->last_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Salón</label>
                            <select name="classroom_id" class="form-select" required>
                                <option value="">Seleccionar...</option>
                                @foreach($classrooms as $classroom)
                                    <option value="{{ $classroom->id }}">{{ $classroom->name }} (Cap: {{ $classroom->capacity }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Día</label>
                            <select name="day" class="form-select" required>
                                <option value="monday">Lunes</option>
                                <option value="tuesday">Martes</option>
                                <option value="wednesday">Miércoles</option>
                                <option value="thursday">Jueves</option>
                                <option value="friday">Viernes</option>
                                <option value="saturday">Sábado</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Hora Inicio</label>
                            <input type="time" name="start_time" class="form-control" value="08:00" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Hora Fin</label>
                            <input type="time" name="end_time" class="form-control" value="10:00" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Crear Asignación</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Ver Detalles de Asignación -->
<div class="modal fade" id="detalleAsignacionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">📋 Detalles de Asignación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detalleAsignacionBody">
                <!-- Se llena dinámicamente con JavaScript -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="btnEliminarAsignacion">🗑️ Eliminar</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<!-- FullCalendar CSS -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">
<style>
    #calendar {
        min-height: 800px;
        background: white;
    }
    .fc {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", "Roboto", "Oxygen", "Ubuntu", "Cantarell", "Fira Sans", "Droid Sans", "Helvetica Neue", sans-serif;
    }
    .fc-event {
        cursor: move;
    }
    .fc-event:hover {
        opacity: 0.9;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }
    .fc-col-header-cell {
        padding: 10px 0;
    }
    .fc-daygrid-day,
    .fc-timegrid-slot {
        border-color: #dee2e6;
    }
</style>
@endpush

@push('scripts')
<!-- FullCalendar JS -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    let currentAssignmentId = null;
    const eventsData = @json($assignments);
    const baseEvents = (eventsData || []).map((event) => {
        const base = JSON.parse(JSON.stringify(event));
        const props = base.extendedProps || {};
        const startDate = base.start ? new Date(base.start) : null;
        const normalizedDay = props.day || (startDate ? getDayFromDate(startDate) : '');

        return {
            ...base,
            extendedProps: {
                ...props,
                day: normalizedDay,
                group_id: props.group_id ?? base.group_id ?? null,
                teacher_id: props.teacher_id ?? base.teacher_id ?? null,
                classroom_id: props.classroom_id ?? base.classroom_id ?? null,
                subject_id: props.subject_id ?? base.subject_id ?? null,
            },
        };
    });

    console.log('Total eventos base:', baseEvents.length);
    if (baseEvents.length > 0) {
        console.log('Ejemplo de evento base:', baseEvents[0]);
    }

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'timeGridWeek,timeGridDay'
        },
        locale: 'es',
        slotMinTime: '06:00:00',
        slotMaxTime: '23:00:00',
        allDaySlot: false,
        editable: true,
        droppable: true,
        eventResizableFromStart: true,
        height: 'auto',
        contentHeight: 'auto',
        
        // Eventos desde Laravel
        events: baseEvents,
        
        eventDidMount: function(info) {
            console.log('Evento montado:', info.event.title);
        },

        // Cuando se hace clic en un evento
        eventClick: function(info) {
            currentAssignmentId = info.event.id;
            const props = info.event.extendedProps;
            
            document.getElementById('detalleAsignacionBody').innerHTML = `
                <p><strong>Título:</strong> ${info.event.title}</p>
                <p><strong>Grupo:</strong> ${props.group}</p>
                <p><strong>Profesor:</strong> ${props.teacher}</p>
                <p><strong>Salón:</strong> ${props.classroom}</p>
                <p><strong>Horario:</strong> ${info.event.start.toLocaleTimeString('es-ES', {hour: '2-digit', minute: '2-digit'})} - ${info.event.end.toLocaleTimeString('es-ES', {hour: '2-digit', minute: '2-digit'})}</p>
                <p><strong>Puntaje:</strong> ${props.score}</p>
            `;
            
            new bootstrap.Modal(document.getElementById('detalleAsignacionModal')).show();
        },

        // Cuando se mueve o redimensiona un evento (Drag & Drop)
        eventDrop: function(info) {
            updateAssignment(info.event);
        },
        
        eventResize: function(info) {
            updateAssignment(info.event);
        }
    });

    calendar.render();

    const filters = {
        day: document.getElementById('filter_day'),
        group: document.getElementById('filter_group'),
        teacher: document.getElementById('filter_teacher'),
        classroom: document.getElementById('filter_classroom'),
        subject: document.getElementById('filter_subject'),
    };

    document.getElementById('applyFilters').addEventListener('click', function() {
        const day = filters.day.value;
        const groupId = filters.group.value;
        const teacherId = filters.teacher.value;
        const classroomId = filters.classroom.value;
        const subjectId = filters.subject.value;

        const filtered = baseEvents.filter(event => {
            const props = event.extendedProps || {};
            const matchesDay = !day || props.day === day;
            const matchesGroup = !groupId || String(props.group_id) === groupId;
            const matchesTeacher = !teacherId || String(props.teacher_id) === teacherId;
            const matchesClassroom = !classroomId || String(props.classroom_id) === classroomId;
            const matchesSubject = !subjectId || String(props.subject_id) === subjectId;
            return matchesDay && matchesGroup && matchesTeacher && matchesClassroom && matchesSubject;
        });

        console.log('Filtros -> day:', day, 'group:', groupId, 'teacher:', teacherId, 'classroom:', classroomId, 'subject:', subjectId, 'Resultado:', filtered.length);

        calendar.removeAllEvents();
        calendar.addEventSource(filtered);
    });

    document.getElementById('clearFilters').addEventListener('click', function() {
        Object.values(filters).forEach(select => select.value = '');
        calendar.removeAllEvents();
        calendar.addEventSource(baseEvents);
    });

    // Función para verificar conflictos
    function checkConflicts(event) {
        const eventStart = new Date(event.start);
        const eventEnd = new Date(event.end);
        const classroomId = event.extendedProps.classroom_id;
        const teacherId = event.extendedProps.teacher_id;
        const groupId = event.extendedProps.group_id;
        const eventId = event.id;
        
        const conflicts = {
            classroom: [],
            teacher: [],
            group: []
        };
        
        // Revisar todos los eventos en el calendario
        calendar.getEvents().forEach(otherEvent => {
            if (otherEvent.id === eventId) return; // Ignorar el evento mismo
            
            const otherStart = new Date(otherEvent.start);
            const otherEnd = new Date(otherEvent.end);
            const otherClassroomId = otherEvent.extendedProps.classroom_id;
            const otherTeacherId = otherEvent.extendedProps.teacher_id;
            const otherGroupId = otherEvent.extendedProps.group_id;
            
            // Verificar solapamiento de tiempo
            const hasTimeOverlap = eventStart < otherEnd && eventEnd > otherStart;
            
            if (hasTimeOverlap) {
                // Conflicto: mismo salón
                if (classroomId === otherClassroomId) {
                    conflicts.classroom.push({
                        subject: otherEvent.title,
                        teacher: otherEvent.extendedProps.teacher,
                        time: otherEvent.start.toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit' })
                    });
                }
                
                // Conflicto: mismo profesor
                if (teacherId === otherTeacherId) {
                    conflicts.teacher.push({
                        subject: otherEvent.title,
                        classroom: otherEvent.extendedProps.classroom,
                        time: otherEvent.start.toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit' })
                    });
                }
                
                // Conflicto: mismo grupo
                if (groupId === otherGroupId) {
                    conflicts.group.push({
                        subject: otherEvent.title,
                        teacher: otherEvent.extendedProps.teacher,
                        time: otherEvent.start.toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit' })
                    });
                }
            }
        });
        
        return conflicts;
    }
    
    // Función para mostrar conflictos
    function showConflictAlert(conflicts) {
        let message = '<div class="text-start">';
        let hasConflicts = false;
        
        if (conflicts.classroom.length > 0) {
            hasConflicts = true;
            message += '<div class="mb-2"><strong>⚠️ Conflicto de Salón:</strong><br>';
            conflicts.classroom.forEach(c => {
                message += `${c.subject} (${c.teacher}) a las ${c.time}<br>`;
            });
            message += '</div>';
        }
        
        if (conflicts.teacher.length > 0) {
            hasConflicts = true;
            message += '<div class="mb-2"><strong>⚠️ Conflicto de Profesor:</strong><br>';
            conflicts.teacher.forEach(c => {
                message += `${c.subject} (${c.classroom}) a las ${c.time}<br>`;
            });
            message += '</div>';
        }
        
        if (conflicts.group.length > 0) {
            hasConflicts = true;
            message += '<div class="mb-2"><strong>⚠️ Conflicto de Grupo:</strong><br>';
            conflicts.group.forEach(c => {
                message += `${c.subject} (${c.teacher}) a las ${c.time}<br>`;
            });
            message += '</div>';
        }
        
        message += '</div>';
        
        if (hasConflicts) {
            showAlert(message, 'warning');
            return false;
        }
        
        return true;
    }
    
    // Función para actualizar asignación vía AJAX
    function updateAssignment(event) {
        // Verificar conflictos primero
        const conflicts = checkConflicts(event);
        const hasWarnings = conflicts.classroom.length > 0 || conflicts.teacher.length > 0 || conflicts.group.length > 0;
        
        if (hasWarnings) {
            const canContinue = confirm('Se detectaron conflictos. ¿Deseas continuar?');
            if (!canContinue) {
                event.revert();
                showConflictAlert(conflicts);
                return;
            }
        }
        
        const day = getDayFromDate(event.start);
        const start_time = event.start.toTimeString().split(' ')[0].substring(0, 5);
        const end_time = event.end.toTimeString().split(' ')[0].substring(0, 5);

        fetch(`/asignacion/manual/${event.id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                day: day,
                start_time: start_time,
                end_time: end_time
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('✅ Asignación actualizada exitosamente', 'success');
                if (hasWarnings) {
                    showConflictAlert(conflicts);
                }
            } else {
                showAlert('❌ Error al actualizar', 'danger');
                event.revert();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('❌ Error de conexión', 'danger');
            event.revert();
        });
    }

    // Formulario de nueva asignación
    document.getElementById('formNuevaAsignacion').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch('{{ route("asignacion.manual.store") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('✅ Asignación creada exitosamente', 'success');
                bootstrap.Modal.getInstance(document.getElementById('nuevaAsignacionModal')).hide();
                this.reset();
                location.reload(); // Recargar para mostrar el nuevo evento
            } else {
                showAlert('❌ Error al crear asignación', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('❌ Error de conexión', 'danger');
        });
    });

    // Eliminar asignación
    document.getElementById('btnEliminarAsignacion').addEventListener('click', function() {
        if (!currentAssignmentId) return;
        
        if (confirm('¿Está seguro de eliminar esta asignación?')) {
            fetch(`/asignacion/manual/${currentAssignmentId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('✅ Asignación eliminada', 'success');
                    bootstrap.Modal.getInstance(document.getElementById('detalleAsignacionModal')).hide();
                    location.reload();
                } else {
                    showAlert('❌ Error al eliminar', 'danger');
                }
            });
        }
    });

    // Utilidades
    function getDayFromDate(date) {
        const days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        return days[date.getDay()];
    }

    function showAlert(message, type) {
        const alert = document.createElement('div');
        alert.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 end-0 m-3`;
        alert.style.zIndex = '9999';
        alert.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(alert);
        
        setTimeout(() => {
            alert.remove();
        }, 3000);
    }
});
</script>
@endpush