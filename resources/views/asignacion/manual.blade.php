@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>👆 Asignación Manual - Drag & Drop</h1>
        <div class="d-flex gap-2">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevaAsignacionModal">
                ➕ Nueva Asignación
            </button>
            <button id="reloadEventsBtn" class="btn btn-outline-primary">
                ↻ Recargar eventos
            </button>
            <form method="GET" action="{{ route('asignacion.manual.pdf') }}" class="d-inline">
                @if($period)
                    <input type="hidden" name="period_id" value="{{ $period->id }}">
                @endif
                @if($selectedCareer)
                    <input type="hidden" name="career_id" value="{{ $selectedCareer }}">
                @endif
                @if($selectedSemester)
                    <input type="hidden" name="semester_id" value="{{ $selectedSemester }}">
                @endif
                <button type="submit" class="btn btn-outline-success">
                    ⬇️ Exportar PDF
                </button>
            </form>
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

    <!-- Filtros Jerárquicos: Carrera → Semestre -->
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="fas fa-filter"></i> Filtros por Estructura Académica</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('asignacion.manual') }}" id="filterForm">
                <!-- Mantener el período académico si existe -->
                @if($period)
                    <input type="hidden" name="period_id" value="{{ $period->id }}">
                @endif
                
                <div class="row g-3 align-items-end">
                    <!-- Filtro de Carrera -->
                    <div class="col-md-4">
                        <label for="career_id" class="form-label fw-semibold">
                            <i class="fas fa-graduation-cap"></i> 1. Seleccionar Carrera
                        </label>
                        <select id="career_id" name="career_id" class="form-select">
                            <option value="">-- Seleccione una carrera --</option>
                            @foreach($careers as $career)
                                <option value="{{ $career->id }}" {{ $selectedCareer == $career->id ? 'selected' : '' }}>
                                    {{ $career->name }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Primero seleccione la carrera</small>
                    </div>
                    
                    <!-- Filtro de Semestre (dependiente de Carrera) -->
                    <div class="col-md-4">
                        <label for="semester_id" class="form-label fw-semibold">
                            <i class="fas fa-list-ol"></i> 2. Seleccionar Semestre
                        </label>
                        <select id="semester_id" name="semester_id" class="form-select" {{ !$selectedCareer ? 'disabled' : '' }}>
                            <option value="">-- Seleccione un semestre --</option>
                            @foreach($semesters as $semester)
                                <option value="{{ $semester->id }}" 
                                        data-career-id="{{ $semester->career_id }}"
                                        {{ $selectedSemester == $semester->id ? 'selected' : '' }}>
                                    Semestre {{ $semester->number }}
                                    @if($semester->description) - {{ $semester->description }} @endif
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Luego seleccione el semestre</small>
                    </div>
                    
                    <!-- Botones de acción -->
                    <div class="col-md-4">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary" id="applyHierarchicalFilter">
                                <i class="fas fa-calendar-week"></i> Ver Horario
                            </button>
                            <a href="{{ route('asignacion.manual') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Limpiar
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Información del filtro activo -->
                @if($selectedCareer || $selectedSemester)
                    <div class="alert alert-info mt-3 mb-0">
                        <i class="fas fa-info-circle"></i>
                        <strong>Filtro activo:</strong>
                        @if($selectedCareer)
                            @php
                                $career = $careers->firstWhere('id', $selectedCareer);
                            @endphp
                            Carrera: <strong>{{ $career?->name }}</strong>
                        @endif
                        @if($selectedSemester)
                            @php
                                $semester = $semesters->firstWhere('id', $selectedSemester);
                            @endphp
                            → Semestre: <strong>{{ $semester?->number }}</strong>
                        @endif
                        <br>
                        <small>Mostrando {{ $groups->count() }} grupo(s) y {{ $assignments->count() }} asignación(es)</small>
                    </div>
                @else
                    <div class="alert alert-warning mt-3 mb-0">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Seleccione una carrera y un semestre</strong> para visualizar y editar el horario semanal.
                    </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Calendario con Drag & Drop -->
    <div class="card shadow-sm">
        <div class="card-header bg-gradient">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-calendar-check text-primary"></i> 
                    Calendario de Asignaciones
                </h5>
                <div class="btn-group btn-group-sm" role="group">
                    <button type="button" class="btn btn-outline-secondary" id="calView-dayGrid">
                        <i class="fas fa-th"></i> Mes
                    </button>
                    <button type="button" class="btn btn-outline-secondary" id="calView-timeGrid">
                        <i class="fas fa-bars"></i> Semana
                    </button>
                    <button type="button" class="btn btn-outline-secondary" id="calView-list">
                        <i class="fas fa-list"></i> Lista
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div id="calendar-status" class="alert alert-info mb-3">
                <i class="fas fa-spinner fa-spin"></i> Cargando calendario...
            </div>
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
    /* Mejoras de Calendario */
    .card-header.bg-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
    }

    .card-header.bg-gradient h5 {
        color: white;
    }

    .card-header.bg-gradient .btn-outline-secondary {
        color: white;
        border-color: rgba(255, 255, 255, 0.5);
    }

    .card-header.bg-gradient .btn-outline-secondary:hover,
    .card-header.bg-gradient .btn-outline-secondary.active {
        background-color: rgba(255, 255, 255, 0.2);
        border-color: white;
        color: white;
    }

    .card-body {
        display: flex;
        flex-direction: column;
    }

    #calendar {
        height: 700px;
        background: white;
        flex: 1;
        border-radius: 0.375rem;
    }

    /* FullCalendar Customization */
    .fc {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", "Roboto", "Oxygen", "Ubuntu", "Cantarell", "Fira Sans", "Droid Sans", "Helvetica Neue", sans-serif;
    }

    .fc-button-primary {
        background-color: #667eea;
        border-color: #667eea;
    }

    .fc-button-primary:hover {
        background-color: #5568d3;
        border-color: #5568d3;
    }

    .fc-button-primary.fc-button-active {
        background-color: #764ba2;
        border-color: #764ba2;
    }

    .fc-event {
        cursor: move;
        border-radius: 0.25rem;
        transition: all 0.2s ease;
    }

    .fc-event:hover {
        opacity: 0.95;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        transform: translateY(-2px);
    }

    .fc-event-title {
        font-weight: 500;
        font-size: 0.85rem;
    }

    .fc-col-header-cell {
        padding: 12px 0;
        font-weight: 600;
        background-color: #f8f9fa;
    }

    .fc-daygrid-day,
    .fc-timegrid-slot {
        border-color: #e9ecef;
    }

    .fc-daygrid-day:hover {
        background-color: rgba(102, 126, 234, 0.05);
    }

    .fc-daygrid-day.fc-day-other {
        background-color: #fafbfc;
    }

    .fc-daygrid-day-number {
        padding: 8px 4px;
        font-weight: 500;
    }

    .fc-daygrid-day-frame {
        min-height: 100px;
    }

    /* Vista de Lista */
    .fc-list-event:hover {
        background-color: rgba(102, 126, 234, 0.08);
    }

    .fc-list-event-graphic {
        width: 8px;
        background-color: #667eea;
    }

    /* Responsive */
    @media (max-width: 768px) {
        #calendar {
            height: 500px;
        }
    }
</style>
@endpush

@push('scripts')
<!-- FullCalendar JS -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>

<!-- Script de Filtrado Jerárquico (ejecutar primero) -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ========================================
    // Filtrado Jerárquico: Carrera → Semestre
    // ========================================
    const careerSelect = document.getElementById('career_id');
    const semesterSelect = document.getElementById('semester_id');
    
    console.log('=== FILTRO JERÁRQUICO ===');
    console.log('Career Select encontrado:', !!careerSelect);
    console.log('Semester Select encontrado:', !!semesterSelect);
    
    if (careerSelect && semesterSelect) {
        // Guardar todas las opciones de semestre al cargar (excepto la primera que es el placeholder)
        const allSemesterOptions = [];
        for (let i = 1; i < semesterSelect.options.length; i++) {
            const opt = semesterSelect.options[i];
            allSemesterOptions.push({
                value: opt.value,
                text: opt.text,
                careerId: opt.getAttribute('data-career-id')
            });
        }
        
        console.log('Total semestres disponibles:', allSemesterOptions.length);
        console.log('Semestres:', allSemesterOptions);
        
        // Función para actualizar semestres según carrera seleccionada
        function updateSemesters() {
            const selectedCareerId = careerSelect.value;
            console.log('Carrera seleccionada ID:', selectedCareerId);
            
            // Limpiar selección de semestre
            semesterSelect.value = '';
            
            if (!selectedCareerId) {
                // Si no hay carrera seleccionada, deshabilitar semestres
                semesterSelect.disabled = true;
                // Remover todas las opciones excepto la primera
                while (semesterSelect.options.length > 1) {
                    semesterSelect.remove(1);
                }
                console.log('✗ Semestres deshabilitados - no hay carrera seleccionada');
            } else {
                // Habilitar semestres
                semesterSelect.disabled = false;
                
                // Remover todas las opciones excepto la primera
                while (semesterSelect.options.length > 1) {
                    semesterSelect.remove(1);
                }
                
                // Agregar solo los semestres de la carrera seleccionada
                let semestresAgregados = 0;
                allSemesterOptions.forEach(optionData => {
                    console.log(`Comparando: ${optionData.careerId} == ${selectedCareerId}`, optionData.careerId == selectedCareerId);
                    if (optionData.careerId == selectedCareerId) { // Comparación no estricta
                        const newOption = document.createElement('option');
                        newOption.value = optionData.value;
                        newOption.text = optionData.text;
                        newOption.setAttribute('data-career-id', optionData.careerId);
                        semesterSelect.add(newOption);
                        semestresAgregados++;
                    }
                });
                
                console.log('✓ Semestres agregados:', semestresAgregados);
                
                // Si solo hay un semestre disponible, seleccionarlo automáticamente
                if (semesterSelect.options.length === 2) {
                    semesterSelect.selectedIndex = 1;
                    console.log('✓ Auto-seleccionado único semestre disponible');
                }
            }
        }
        
        // Cuando cambia la carrera
        careerSelect.addEventListener('change', function() {
            console.log('Evento change disparado en carrera');
            updateSemesters();
        });
        
        // Inicializar el filtro al cargar la página
        const initialCareerValue = careerSelect.value;
        console.log('Carrera inicial:', initialCareerValue);
        if (initialCareerValue) {
            console.log('Inicializando con carrera ya seleccionada');
            updateSemesters();
            
            // Restaurar la selección del semestre si existía
            const selectedSemesterId = '{{ $selectedSemester ?? "" }}';
            if (selectedSemesterId) {
                setTimeout(() => {
                    semesterSelect.value = selectedSemesterId;
                    console.log('Semestre restaurado:', selectedSemesterId);
                }, 100);
            }
        }
    } else {
        console.error('✗ No se encontraron los selectores de carrera o semestre');
    }
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const calendarStatus = document.getElementById('calendar-status');
    let currentAssignmentId = null;
    let calendar = null;
    
    const eventsData = @json($assignments);
    
    // Log para verificar los datos
    console.log('=== DEBUG CALENDAR ===');
    console.log('Datos crudos recibidos:', eventsData);
    console.log('Total de eventos:', eventsData ? eventsData.length : 0);
    
    if (eventsData && eventsData.length > 0) {
        console.log('Primer evento:', eventsData[0]);
    }
    
    const baseEvents = (eventsData || []).map((event) => {
        const base = JSON.parse(JSON.stringify(event));
        const props = base.extendedProps || {};
        const startDate = base.start ? new Date(base.start) : null;
        const normalizedDay = props.day || (startDate ? getDayFromDate(startDate) : '');

        const mappedEvent = {
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
        
        return mappedEvent;
    });

    console.log('Total eventos mapeados:', baseEvents.length);
    if (baseEvents.length > 0) {
        console.log('Primer evento mapeado:', baseEvents[0]);
    }
    
    if (calendarStatus) {
        calendarStatus.innerHTML = `<strong>Cargando calendario...</strong> (${baseEvents.length} eventos encontrados)`;
        calendarStatus.classList.remove('alert-info', 'alert-success', 'alert-danger');
        calendarStatus.classList.add('alert-info');
    }

    try {
        if (!FullCalendar || !FullCalendar.Calendar) {
            throw new Error('FullCalendar no está disponible en el entorno global');
        }

        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'timeGridWeek',
            initialDate: new Date(),
            firstDay: 0,
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'timeGridDay,timeGridWeek'
            },
            locale: 'es',
            slotMinTime: '06:00:00',
            slotMaxTime: '23:00:00',
            slotDuration: '00:30:00',
            allDaySlot: false,
            editable: true,
            droppable: true,
            eventResizableFromStart: true,
            height: 'auto',
            contentHeight: 800,
            
            // Fuente de eventos: la añadiremos explícitamente tras renderizar
            events: [],
            
            eventDidMount: function(info) {
                console.log('✓ Evento renderizado:', info.event.title, 'ID:', info.event.id);
            },

            // Contenido visible en el bloque del calendario
            eventContent: function(arg) {
                const props = arg.event.extendedProps || {};
                const div = document.createElement('div');
                div.className = 'fc-event-body';
                div.innerHTML = `
                    <div class="fw-semibold">${arg.event.title || 'Sin materia'}</div>
                    <div class="small text-muted">${props.classroom || 'Sin salón'}</div>
                    <div class="small">${props.teacher || 'Sin profesor'}</div>
                    <div class="small">${props.group || 'Sin grupo'}</div>
                `;
                return { domNodes: [div] };
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
            },
            
            // Callback para cuando el calendario esté completamente renderizado
            datesSet: function(info) {
                const renderedEvents = calendar.getEvents();
                console.log('Calendario renderizado. Eventos visibles:', renderedEvents.length);
            }
        });

        calendar.render();

        // Añadir eventos explícitamente y verificar
        if (Array.isArray(baseEvents)) {
            console.log('Añadiendo eventos al calendario:', baseEvents.length);
            calendar.addEventSource(baseEvents);
            const afterAdd = calendar.getEvents();
            console.log('Eventos en calendario tras addEventSource:', afterAdd.length);
        } else {
            console.warn('baseEvents no es un arreglo válido:', typeof baseEvents);
        }

        // Botón para recargar eventos explícitamente
        const reloadBtn = document.getElementById('reloadEventsBtn');
        if (reloadBtn) {
            reloadBtn.addEventListener('click', function() {
                try {
                    console.log('↻ Recargando eventos...');
                    // Eliminar todas las fuentes y eventos actuales
                    calendar.getEventSources().forEach(src => src.remove());
                    calendar.getEvents().forEach(evt => evt.remove());
                    // Reagregar baseEvents
                    calendar.addEventSource(baseEvents);
                    const count = calendar.getEvents().length;
                    console.log('✓ Eventos tras recarga:', count);
                    showAlert(`Eventos recargados: ${count}`, 'info');
                } catch (e) {
                    console.error('Error recargando eventos', e);
                    showAlert('Error recargando eventos', 'danger');
                }
            });
        }
        setTimeout(() => {
            const renderedEvents = calendar.getEvents();
            console.log('Eventos después del render:', renderedEvents.length);
            
            if (calendarStatus) {
                if (baseEvents.length > 0) {
                    calendarStatus.innerHTML = `<strong>✓ Calendario listo</strong> (${renderedEvents.length} eventos visibles)`;
                    calendarStatus.classList.remove('alert-info', 'alert-danger');
                    calendarStatus.classList.add('alert-success');
                } else {
                    calendarStatus.innerHTML = `<strong>⚠️ No hay asignaciones</strong> para este período`;
                    calendarStatus.classList.remove('alert-info', 'alert-danger');
                    calendarStatus.classList.add('alert-warning');
                }
                setTimeout(() => calendarStatus.remove(), 3000);
            }
        }, 500);
        
    } catch (error) {
        console.error('Error inicializando calendario:', error);
        if (calendarStatus) {
            calendarStatus.innerHTML = `<strong>❌ Error:</strong> ${error.message}. Revisa la consola.`;
            calendarStatus.classList.remove('alert-info', 'alert-success');
            calendarStatus.classList.add('alert-danger');
        }
    }

    const filters = {
        day: document.getElementById('filter_day'),
        group: document.getElementById('filter_group'),
        teacher: document.getElementById('filter_teacher'),
        classroom: document.getElementById('filter_classroom'),
        subject: document.getElementById('filter_subject'),
    };

    const applyFiltersBtn = document.getElementById('applyFilters');
    if (applyFiltersBtn) applyFiltersBtn.addEventListener('click', function() {
        if (!calendar) return;
        
        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Filtrando...';
        
        const day = filters.day.value;
        const groupId = filters.group.value;
        const teacherId = filters.teacher.value;
        const classroomId = filters.classroom.value;
        const subjectId = filters.subject.value;

        const startTime = performance.now();
        
        const filtered = baseEvents.filter(event => {
            const props = event.extendedProps || {};
            const matchesDay = !day || props.day === day;
            const matchesGroup = !groupId || String(props.group_id) === groupId;
            const matchesTeacher = !teacherId || String(props.teacher_id) === teacherId;
            const matchesClassroom = !classroomId || String(props.classroom_id) === classroomId;
            const matchesSubject = !subjectId || String(props.subject_id) === subjectId;
            return matchesDay && matchesGroup && matchesTeacher && matchesClassroom && matchesSubject;
        });

        // Usar batchRendering para optimizar el renderizado
        calendar.batchRendering(() => {
            calendar.getEvents().forEach(event => event.remove());
            filtered.forEach(event => {
                calendar.addEvent(event);
            });
        });
        
        const duration = (performance.now() - startTime).toFixed(2);
        console.log(`✓ Filtros aplicados en ${duration}ms ->, { day, groupId, teacherId, classroomId, subjectId }, '| Resultado:', filtered.length, 'eventos`);

        // Restaurar botón
        setTimeout(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-search"></i> Aplicar filtros';
        }, 300);
    });

    const clearFiltersBtn = document.getElementById('clearFilters');
    if (clearFiltersBtn) clearFiltersBtn.addEventListener('click', function() {
        if (!calendar) return;
        
        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Limpiando...';
        
        Object.values(filters).forEach(select => select.value = '');
        
        const startTime = performance.now();
        
        // Usar batchRendering para optimizar
        calendar.batchRendering(() => {
            calendar.getEvents().forEach(event => event.remove());
            baseEvents.forEach(event => {
                calendar.addEvent(event);
            });
        });
        
        const duration = (performance.now() - startTime).toFixed(2);
        console.log(`✓ Filtros limpiados en ${duration}ms. Total eventos: ${baseEvents.length}`);
        
        // Restaurar botón
        setTimeout(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-times"></i> Limpiar';
        }, 300);
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

    // Botones para cambiar vista del calendario
    document.getElementById('calView-dayGrid')?.addEventListener('click', function() {
        if (calendar) {
            calendar.changeView('dayGridMonth');
            updateViewButtons(this);
        }
    });

    document.getElementById('calView-timeGrid')?.addEventListener('click', function() {
        if (calendar) {
            calendar.changeView('timeGridWeek');
            updateViewButtons(this);
        }
    });

    document.getElementById('calView-list')?.addEventListener('click', function() {
        if (calendar) {
            calendar.changeView('listWeek');
            updateViewButtons(this);
        }
    });

    function updateViewButtons(activeButton) {
        document.querySelectorAll('[id^="calView-"]').forEach(btn => {
            btn.classList.remove('active');
            btn.style.backgroundColor = 'transparent';
            btn.style.color = 'white';
        });
        if (activeButton) {
            activeButton.classList.add('active');
            activeButton.style.backgroundColor = 'rgba(255, 255, 255, 0.2)';
            activeButton.style.borderColor = 'white';
        }
    }
});
</script>
@endpush