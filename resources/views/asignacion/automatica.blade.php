@extends('layouts.app')

@section('title', 'Asignación Automática')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">

            <!-- MENSAJE DE ÉXITO (ahora con texto negro visible) -->
            @if(session('success_message'))
                <div class="bg-gradient-to-r from-green-500 to-emerald-600 text-black p-12 text-center">
                    <div class="text-8xl mb-6">¡ÉXITO TOTAL!</div>
                    <h1 class="text-4xl font-bold mb-4 text-black">¡Asignación Automática Completada!</h1>
                    <p class="text-2xl mb-8 text-black font-semibold">{{ session('success_message') }}</p>
                    
                    <a href="{{ route('visualizacion.horario.semestral') }}"
                       class="inline-flex items-center px-10 py-5 bg-white text-green-700 text-xl font-bold rounded-x2 hover:bg-gray-100 transform hover:scale-105 transition-all shadow-2xl">
                        VER HORARIO SEMESTRAL COMPLETO
                    </a>
                </div>
            @endif

            @if(session('error_message'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-8 py-6 rounded-lg mb-8 text-center">
                    <p class="text-2xl font-bold">Error</p>
                    <p class="text-lg mt-2">{{ session('error_message') }}</p>
                </div>
            @endif

            @if(!session('success_message'))
                <div class="bg-gradient-to-r from-blue-600 to-indigo-700 text-black p-6 text-center">
                    <h1 class="text-3xl font-bold">Asignación Automática de Salones</h1>
                    <p class="mt-2 opacity-90">El sistema asignará grupos a salones según las reglas y pesos configurados</p>
                </div>

                <div class="p-8">
                    <!-- Estadísticas -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                        <div class="bg-blue-50 rounded-lg p-6 text-center border border-blue-200">
                            <div class="text-3xl font-bold text-blue-700">{{ $gruposCount }}</div>
                            <p class="text-sm text-gray-600">Grupos activos</p>
                        </div>
                        <div class="bg-green-50 rounded-lg p-6 text-center border border-green-200">
                            <div class="text-3xl font-bold text-green-700">{{ $salonesCount }}</div>
                            <p class="text-sm text-gray-600">Salones</p>
                        </div>
                        <div class="bg-purple-50 rounded-lg p-6 text-center border border-purple-200">
                            <div class="text-3xl font-bold text-purple-700">{{ $profesoresCount }}</div>
                            <p class="text-sm text-gray-600">Profesores</p>
                        </div>
                        <div class="bg-amber-50 rounded-lg p-6 text-center border border-amber-200">
                            <div class="text-3xl font-bold text-amber-700">{{ $franjasCount }}</div>
                            <p class="text-sm text-gray-600">Franjas</p>
                        </div>
                    </div>

                    <!-- Reglas -->
                    <div class="bg-gray-50 rounded-lg p-6 mb-8 border">
                        <h3 class="text-lg font-semibold mb-4">Reglas activas</h3>
                        @forelse($reglasActivas as $regla)
                            <div class="flex justify-between items-center bg-white p-4 rounded-lg shadow mb-3">
                                <div class="flex items-center gap-3">
                                    <span class="text-2xl">{{ $regla->icono ?? 'Checkmark' }}</span>
                                    <div>
                                        <div class="font-semibold">{{ $regla->name }}</div>
                                        <div class="text-sm text-gray-600">{{ $regla->description }}</div>
                                    </div>
                                </div>
                                <span class="bg-indigo-100 text-indigo-800 px-4 py-2 rounded-full font-bold">
                                    Peso: {{ $regla->weight }}
                                </span>
                            </div>
                        @empty
                            <p class="text-center text-gray-500 py-8">No hay reglas activas</p>
                        @endforelse
                    </div>

                    <!-- BOTÓN CON TEXTO NEGRO VISIBLE -->
                    <form action="{{ route('asignacion.asignacion.ejecutar-automatica') }}" method="POST">
                        @csrf
                        <div class="bg-gradient-to-r from-red-600 to-pink-600 rounded-xl p-10 text-center">
                            <h2 class="text-3xl font-bold mb-6 text-black">¿Listo para ejecutar la asignación automática?</h2>
                            <p class="text-xl mb-10 text-black font-bold">
                                Se procesarán {{ $gruposCount }} grupos con {{ $reglasActivas->count() }} reglas activas
                            </p>
                            <button type="submit" class="px-12 py-6 bg-white text-red-600 text-2xl font-bold rounded-xl hover:bg-gray-100 transform hover:scale-110 transition-all shadow-2xl">
                                EJECUTAR ASIGNACIÓN AUTOMÁTICA
                            </button>
                        </div>
                    </form>

                        <p class="text-center text-gray-500 mt-8 text-sm">
                            Esta acción puede tomar varios segundos.
                        </p>                    
                </div>
            @endif
        </div>
    </div>
</div>
@endsection