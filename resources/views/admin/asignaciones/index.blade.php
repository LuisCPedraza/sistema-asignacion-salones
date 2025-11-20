@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 max-w-screen-2xl">
    <h1 class="text-5xl font-bold mb-10 text-center text-gray-800 tracking-tight">
        Asignación Semestral de Salones<br>
        <span class="text-3xl font-normal text-gray-600">Horario Fijo Semanal</span>
    </h1>

    <div class="mb-8 text-center">
        <a href="{{ route('admin.dashboard') }}" class="inline-block bg-gradient-to-r from-indigo-600 to-purple-700 hover:from-indigo-700 hover:to-purple-800 text-white px-10 py-4 rounded-xl text-xl font-bold shadow-xl hover:shadow-2xl transition transform hover:-translate-y-1">
            ← Volver al Dashboard
        </a>
    </div>

    <!-- Panel de Grupos y Profesores arrastrables -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
        <!-- Grupos -->
        <div class="bg-gradient-to-br from-blue-50 to-indigo-100 p-8 rounded-2xl shadow-xl">
            <h3 class="font-bold text-2xl mb-6 text-indigo-900 text-center">
                Grupos / Materias (Arrastra a cualquier casilla)
            </h3>
            <div id="draggable-groups" class="flex flex-wrap gap-4 justify-center">
                @foreach($grupos as $g)
                    <div class="draggable-item bg-white hover:bg-blue-100 border-2 border-blue-400 px-8 py-5 rounded-xl cursor-grab active:cursor-grabbing text-lg font-bold text-blue-900 shadow-lg hover:shadow-2xl transition-all transform hover:-translate-y-1"
                         data-tipo="grupo"
                         data-id="{{ $g->id }}"
                         data-nombre="{{ $g->nombre }}">
                        {{ $g->nombre }}
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Profesores -->
        <div class="bg-gradient-to-br from-green-50 to-emerald-100 p-8 rounded-2xl shadow-xl">
            <h3 class="font-bold text-2xl mb-6 text-emerald-900 text-center">
                Profesores (Arrastra si deseas asignar uno específico)
            </h3>
            <div id="draggable-profesores" class="flex flex-wrap gap-4 justify-center">
                @foreach($profesores as $p)
                    <div class="draggable-item bg-white hover:bg-green-100 border-2 border-green-400 px-8 py-5 rounded-xl cursor-grab active:cursor-grabbing text-lg font-bold text-green-900 shadow-lg hover:shadow-2xl transition-all transform hover:-translate-y-1"
                         data-tipo="profesor"
                         data-id="{{ $p->id }}"
                         data-nombre="{{ $p->user->name }}">
                        {{ $p->user->name }}
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Horario semanal -->
    <div class="overflow-x-auto bg-white rounded-2xl shadow-2xl border border-gray-200">
        <table class="min-w-max w-full border-collapse">
            <thead>
                <tr class="text-white text-xl">
                    <th class="border-r-4 border-gray-800 px-10 py-6 bg-gray-900 font-bold">Salón</th>
                    @php
                        $coloresDias = [
                            '#dbeafe', // lunes
                            '#d1fae5', // martes
                            '#fef3c7', // miércoles
                            '#f3e8e', // jueves
                            '#e0f2fe', // viernes
                            '#fce7f3', // sábado
                        ];
                    @endphp
                    @foreach($dias as $i => $dia)
                        <th colspan="{{ count($horas) }}" class="px-12 py-6 text-center font-bold text-gray-800"
                            style="background-color: {{ $coloresDias[$i] }};">
                            {{ ucfirst($dia) }}
                        </th>
                    @endforeach
                </tr>
                <tr class="bg-gray-100 text-gray-700 font-bold">
                    <th class="border border-gray-300 px-10 py-4">Hora</th>
                    @foreach($dias as $dia)
                        @foreach($horas as $hora)
                            <th class="border border-gray-300 px-5 py-3 text-sm">
                                {{ substr($hora, 0, 5) }}
                            </th>
                        @endforeach
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($salones as $salon)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="border border-gray-300 px-4 px-10 py-8 font-bold text-gray-800 bg-gray-50 text-xl text-center">
                            {{ $salon->codigo }}
                            <div class="text-sm font-normal text-gray-600 mt-1">
                                {{ $salon->capacidad }} puestos
                            </div>
                        </td>
                        @foreach($dias as $dia)
                            @foreach($horas as $hora)
                                @php
                                    $asignacion = $asignaciones->first(function($a) use ($salon, $dia, $hora) {
                                        return $a->salon_id == $salon->id &&
                                               $a->dia_semana == $dia &&
                                               $a->hora_inicio <= $hora.':00' &&
                                               $a->hora_fin > $hora.':00';
                                    });
                                @endphp
                                <td class="border border-gray-300 h-32 relative bg-white hover:bg-blue-50 transition">
                                    <div class="drop-zone w-full h-full flex items-center justify-center p-2"
                                         data-salon-id="{{ $salon->id }}"
                                         data-dia="{{ $dia }}"
                                         data-hora="{{ $hora }}:00">
                                        @if($asignacion)
                                            <div class="assigned-event bg-gradient-to-br from-emerald-500 to-teal-600 text-white rounded-xl p-4 text-sm font-bold w-full text-center shadow-xl relative group transform hover:scale-105 transition">
                                                <div class="mb-1">{{ $asignacion->grupo->nombre }}</div>
                                                <div class="text-xs opacity-90">
                                                    {{ optional($asignacion->profesor->user)->name ?? 'Sin profesor' }}
                                                </div>
                                                <button class="absolute -top-3 -right-3 bg-red-600 hover:bg-red-700 text-white w-10 h-10 rounded-full font-bold opacity-0 group-hover:opacity-100 transition delete-asignacion"
                                                        data-id="{{ $asignacion->id }}">
                                                    ×
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            @endforeach
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Hacer arrastrables ambos paneles
    ['draggable-groups', 'draggable-profesores'].forEach(id => {
        new Sortable(document.getElementById(id), {
            group: { name: 'asignables', pull: 'clone', put: false },
            animation: 180,
            ghostClass: 'bg-yellow-300',
            sort: false
        });
    });

    document.querySelectorAll('.drop-zone').forEach(zone => {
        new Sortable(zone, {
            group: 'asignables',
            animation: 200,
            onAdd: function (evt) {
                const item = evt.item;
                const tipo = item.dataset.tipo;
                const id = item.dataset.id;
                const nombre = item.dataset.nombre;
                const salonId = zone.dataset.salonId;
                const dia = zone.dataset.dia;
                const hora = zone.dataset.hora.substring(0,5);

                if (zone.querySelector('.assigned-event')) {
                    Swal.fire('Ocupado', 'Ya hay una clase aquí', 'warning');
                    item.remove();
                    return;
                }

                fetch('{{ route("admin.asignaciones.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        grupo_id: tipo === 'grupo' ? id : null,
                        profesor_id: tipo === 'profesor' ? id : null,
                        salon_id: salonId,
                        dia_semana: dia,
                        hora_inicio: hora,
                        hora_fin: (parseInt(hora.split(':')[0]) + 2) + ':00'
                    })
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        const bg = tipo === 'grupo' ? 'from-emerald-500 to-teal-600' : 'from-purple-600 to-pink-600';
                        item.outerHTML = `
                            <div class="assigned-event bg-gradient-to-br ${bg} text-white rounded-xl p-4 text-sm font-bold w-full text-center shadow-2xl relative group">
                                <div class="text-lg">${data.asignacion.titulo}</div>
                                <button class="absolute -top-3 -right-3 bg-red-600 hover:bg-red-800 text-white w-10 h-10 rounded-full text-2xl opacity-0 group-hover:opacity-100 transition delete-asignacion" data-id="${data.asignacion.id}">×</button>
                            </div>`;
                        Swal.fire('¡Asignado!', 'Todo el semestre', 'success');
                    } else {
                        Swal.fire('Error', data.message, 'error');
                        item.remove();
                    }
                })
                .catch(() => {
                    Swal.fire('Error', 'No se pudo guardar', 'error');
                    item.remove();
                });
            }
        });
    });

    // Eliminar
    document.addEventListener('click', e => {
        if (e.target.classList.contains('delete-asignacion')) {
            const id = e.target.dataset.id;
            Swal.fire({
                title: '¿Eliminar esta asignación?',
                icon: 'warning',
                showCancelButton: true
            }).then(r => {
                if (r.isConfirmed) {
                    fetch(`/admin/asignaciones/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })
                    .then(() => {
                        e.target.closest('.assigned-event').remove();
                        Swal.fire('Eliminada', '', 'success');
                    });
                }
            });
        }
    });
</script>
@endpush

@push('styles')
<style>
    .draggable-item { user-select: none; }
    .drop-zone:hover { background-color: #f0fdf4 !important; }
    .sortable-ghost { opacity: 0.5; }
</style>
@endpush
