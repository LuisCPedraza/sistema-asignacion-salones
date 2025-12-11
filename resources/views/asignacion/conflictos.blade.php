@extends('layouts.app')
@section('content')
<div class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen py-8">
    <div class="container max-w-6xl mx-auto px-4">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-4xl font-bold text-gray-900 mb-2">⚠️ Detección de Conflictos</h1>
                <p class="text-gray-600">Análisis en tiempo real de solapamientos de horarios</p>
            </div>
            <a href="{{ route('academic.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition">
                ← Volver
            </a>
        </div>

        <!-- Summary Cards -->
        @if($conflictReport['total_conflicts'] > 0)
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-red-500">
                    <div class="text-sm font-semibold text-gray-600">CONFLICTOS TOTALES</div>
                    <div class="text-3xl font-bold text-red-600">{{ $conflictReport['total_conflicts'] }}</div>
                </div>

                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-red-700">
                    <div class="text-sm font-semibold text-gray-600">🔴 CRÍTICOS</div>
                    <div class="text-3xl font-bold text-red-700">{{ $conflictReport['critical_conflicts'] }}</div>
                </div>

                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-orange-500">
                    <div class="text-sm font-semibold text-gray-600">🟠 ALTOS</div>
                    <div class="text-3xl font-bold text-orange-500">{{ $conflictReport['high_conflicts'] }}</div>
                </div>

                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
                    <div class="text-sm font-semibold text-gray-600">🟡 MEDIOS</div>
                    <div class="text-3xl font-bold text-yellow-500">{{ $conflictReport['medium_conflicts'] }}</div>
                </div>
            </div>
        @endif

        <!-- No Conflicts Message -->
        @if($conflictReport['total_conflicts'] === 0)
            <div class="bg-green-50 border-2 border-green-200 rounded-lg p-8 text-center">
                <div class="mb-4">
                    <svg class="w-16 h-16 mx-auto text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-green-800 mb-2">🎉 ¡Excelente!</h2>
                <p class="text-green-700 text-lg">No hay conflictos detectados. El horario está libre de solapamientos.</p>
            </div>
        @else
            <!-- Conflicts List -->
            <div class="space-y-6">
                @foreach($allConflicts as $index => $conflict)
                    @php
                        $severityClass = match($conflict['severity']) {
                            3 => 'border-l-4 border-red-700 bg-red-50',
                            2 => 'border-l-4 border-orange-500 bg-orange-50',
                            default => 'border-l-4 border-yellow-500 bg-yellow-50',
                        };
                        $severityIcon = match($conflict['severity']) {
                            3 => '🔴',
                            2 => '🟠',
                            default => '🟡',
                        };
                        $severityLabel = match($conflict['severity']) {
                            3 => 'CRÍTICO',
                            2 => 'ALTO',
                            default => 'MEDIO',
                        };
                        $severityTextColor = match($conflict['severity']) {
                            3 => 'text-red-700',
                            2 => 'text-orange-700',
                            default => 'text-yellow-700',
                        };
                    @endphp

                    <div class="bg-white rounded-lg shadow-md overflow-hidden {{ $severityClass }}">
                        <!-- Header -->
                        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="text-2xl">{{ $severityIcon }}</span>
                                    <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold {{ $severityTextColor }} {{ match($conflict['severity']) {
                                        3 => 'bg-red-100',
                                        2 => 'bg-orange-100',
                                        default => 'bg-yellow-100',
                                    } }}">
                                        {{ $severityLabel }}
                                    </span>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900">
                                    {{ $conflict['assignment']->group->name ?? 'Grupo Desconocido' }}
                                </h3>
                                <p class="text-sm text-gray-600 mt-1">
                                    📅 {{ ucfirst($conflict['assignment']->day) }} · 🕐 {{ $conflict['assignment']->start_time->format('H:i') }} - {{ $conflict['assignment']->end_time->format('H:i') }}
                                    @if($conflict['assignment']->teacher)
                                        · 👨‍🏫 {{ $conflict['assignment']->teacher->full_name }}
                                    @endif
                                    @if($conflict['assignment']->classroom)
                                        · 🏫 {{ $conflict['assignment']->classroom->name }}
                                    @endif
                                </p>
                            </div>
                            <div class="text-right">
                                <div class="text-sm font-semibold text-gray-600">{{ count($conflict['conflicts']) }} conflicto{{ count($conflict['conflicts']) !== 1 ? 's' : '' }}</div>
                            </div>
                        </div>

                        <!-- Conflicts Details -->
                        <div class="px-6 py-4">
                            @foreach($conflict['conflicts'] as $detail)
                                <div class="mb-4 last:mb-0 p-4 rounded-lg {{ match($detail['type']) {
                                    'teacher' => 'bg-red-50 border border-red-200',
                                    'classroom' => 'bg-orange-50 border border-orange-200',
                                    'group' => 'bg-red-100 border border-red-300',
                                    default => 'bg-yellow-50 border border-yellow-200',
                                } }}">
                                    <div class="font-semibold text-gray-900 mb-2">
                                        {{ match($detail['type']) {
                                            'teacher' => '👨‍🏫 Conflicto de Profesor',
                                            'classroom' => '🏫 Conflicto de Salón',
                                            'group' => '👥 Conflicto de Grupo',
                                            default => '⚠️ Conflicto',
                                        } }}
                                    </div>
                                    <div class="text-sm text-gray-700 whitespace-pre-wrap">{{ $detail['message'] }}</div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Capacity Issue (if any) -->
                        @php
                            $capacityIssue = collect($conflict['conflicts'])->firstWhere('type', 'capacity');
                        @endphp
                        @if($capacityIssue)
                            <div class="px-6 py-3 bg-red-50 border-t border-red-200">
                                <div class="text-sm text-red-700">
                                    ⚠️ {{ $capacityIssue['message'] }}
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Footer Actions -->
        <div class="mt-8 p-6 bg-white rounded-lg shadow">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">💡 Acciones Recomendadas</h3>
            <ul class="space-y-2 text-gray-700">
                <li>✅ Revisar los conflictos críticos (👥 Grupo) primero - afectan directamente a estudiantes</li>
                <li>✅ Luego resolver los conflictos altos (👨‍🏫 Profesor y 🏫 Salón)</li>
                <li>✅ Considerar cambiar horarios, días o salones para las asignaciones con menor prioridad</li>
                <li>✅ Verificar la capacidad de los salones para grupos grandes</li>
                <li>✅ Ejecutar nuevamente el algoritmo automático después de resolver conflictos manualmente</li>
            </ul>

            <div class="mt-6 flex gap-3">
                <a href="{{ route('asignacion.automatica') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition font-semibold">
                    🔄 Ejecutar Asignación Automática
                </a>
                <a href="{{ route('asignacion.manual') }}" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition font-semibold">
                    ✏️ Ir a Asignación Manual
                </a>
                <button onclick="location.reload()" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition font-semibold">
                    🔃 Actualizar
                </button>
            </div>
        </div>
    </div>
</div>
@endsection