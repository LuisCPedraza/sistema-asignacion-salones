@extends('layouts.app')

@section('title', 'Horario Semestral - Visualización')

@section('content')
<div class="container mx-auto px-4 py-8">

    <h1 class="text-3xl font-bold mb-6 text-gray-800">
        Horario Semestral – Visualización Profesional
    </h1>

    <!-- ============================
         FILTROS SUPERIORES PRO
    ============================= -->
    <div class="bg-white p-6 shadow rounded-lg mb-6 grid grid-cols-1 md:grid-cols-6 gap-4">

        <!-- Salón -->
        <div>
            <label class="text-sm font-semibold text-gray-700">Salón</label>
            <select name="salon_id" id="filtro-salon"
                class="w-full mt-1 border-gray-300 rounded-md shadow-sm">
                <option value="">Todos</option>
                @foreach ($salones as $s)
                    <option value="{{ $s->id }}">{{ $s->codigo }}</option>
                @endforeach
            </select>
        </div>

        <!-- Grupo -->
        <div>
            <label class="text-sm font-semibold text-gray-700">Grupo</label>
            <select name="grupo_id" id="filtro-grupo"
                class="w-full mt-1 border-gray-300 rounded-md shadow-sm">
                <option value="">Todos</option>
                @foreach ($grupos as $g)
                    <option value="{{ $g->id }}">{{ $g->nombre }}</option>
                @endforeach
            </select>
        </div>

        <!-- Profesor -->
        <div>
            <label class="text-sm font-semibold text-gray-700">Profesor</label>
            <select name="profesor_id" id="filtro-profesor"
                class="w-full mt-1 border-gray-300 rounded-md shadow-sm">
                <option value="">Todos</option>
                @foreach ($profesores as $p)
                    <option value="{{ $p->id }}">{{ $p->user->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Fecha -->
        <div>
            <label class="text-sm font-semibold text-gray-700">Fecha</label>
            <input type="date" id="filtro-fecha"
                class="w-full mt-1 border-gray-300 rounded-md shadow-sm">
        </div>

        <!-- Tipo de Vista -->
        <div>
            <label class="text-sm font-semibold text-gray-700">Vista</label>
            <select id="tipo-vista" class="w-full mt-1 border-gray-300 rounded-md shadow-sm">
                <option value="salon">Por Salón</option>
                <option value="profesor">Por Profesor</option>
                <option value="grupo">Por Grupo</option>
            </select>
        </div>

        <!-- Botón -->
        <div class="flex items-end">
            <button id="btn-filtrar"
                class="w-full bg-blue-600 text-white py-2 rounded-md shadow hover:bg-blue-700 transition">
                Aplicar
            </button>
        </div>

    </div>

    <!-- ============================
         CALENDARIO
    ============================= -->
    <div id="calendar" class="bg-white p-4 rounded-lg shadow"></div>

</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
<style>
    #calendar {
        min-height: 700px;
        font-size: 14px;
    }
    .fc-event {
        font-size: 12px;
        padding: 2px 4px;
        border-radius: 4px;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/es.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const eventsData = @json($events);

    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',        
        locale: 'es',
        events: eventsData,
        height: 'auto',
        slotMinTime: '07:00:00',
        slotMaxTime: '20:00:00',
        allDaySlot: false,
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'timeGridDay,timeGridWeek,dayGridMonth'
        },
        views: {
            timeGridWeek: {
                type: 'timeGrid',
                duration: { weeks: 1 },
                buttonText: 'Semana'
            },
            timeGridDay: {
                type: 'timeGrid',
                duration: { days: 1 },
                buttonText: 'Día'
            },
            dayGridMonth: {
                type: 'dayGrid',
                duration: { months: 1 },
                buttonText: 'Mes'
            }
        },
        eventClick(info) {
            const e = info.event.extendedProps;
            Swal.fire({
                title: info.event.title,
                html: `
                    <p><strong>Grupo:</strong> ${e.grupo || 'N/A'}</p>
                    <p><strong>Salón:</strong> ${e.salon || 'N/A'}</p>
                    <p><strong>Profesor:</strong> ${e.profesor || 'N/A'}</p>
                    <p><strong>Estado:</strong> ${e.estado || 'N/A'}</p>
                `,
                icon: 'info'
            });
        }
    });
    calendar.render();

    // Filtros
    document.getElementById('btn-filtrar').addEventListener('click', () => {
        const params = new URLSearchParams({
            salon_id: document.getElementById('filtro-salon').value,
            grupo_id: document.getElementById('filtro-grupo').value,
            profesor_id: document.getElementById('filtro-profesor').value,
            fecha: document.getElementById('filtro-fecha').value
        });
        window.location.search = params.toString();
    });
});
</script>
@endpush


