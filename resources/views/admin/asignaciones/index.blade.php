@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Gestión de Asignaciones</h1>
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
    @endif
    <div class="mb-4">
        <a href="{{ route('admin.asignaciones.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Crear Asignación</a>
        <a href="{{ route('admin.dashboard') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 ml-2">Regresar al Dashboard</a>
    </div>
    <table class="min-w-full bg-white border border-gray-300">
        <thead class="bg-gray-50">
            <tr>
                <th class="py-2 px-4 border-b">ID</th>
                <th class="py-2 px-4 border-b">Grupo</th>
                <th class="py-2 px-4 border-b">Salón</th>
                <th class="py-2 px-4 border-b">Profesor</th>
                <th class="py-2 px-4 border-b">Fecha/Hora</th>
                <th class="py-2 px-4 border-b">Estado</th>
                <th class="py-2 px-4 border-b">Score</th>
                <th class="py-2 px-4 border-b">Activo</th>
                <th class="py-2 px-4 border-b">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($asignaciones as $asignacion)
            <tr>
                <td class="py-2 px-4 border-b">{{ $asignacion->id }}</td>
                <td class="py-2 px-4 border-b">{{ $asignacion->grupo->nombre ?? 'N/A' }}</td>
                <td class="py-2 px-4 border-b">{{ $asignacion->salon->codigo ?? 'N/A' }}</td>
                <td class="py-2 px-4 border-b">{{ $asignacion->profesor->user->name ?? 'N/A' }}</td>
                <td class="py-2 px-4 border-b">{{ $asignacion->fecha }} / {{ $asignacion->hora }}</td>
                <td class="py-2 px-4 border-b"><span class="px-2 py-1 bg-blue-100 rounded text-blue-800">{{ ucfirst($asignacion->estado) }}</span></td>
                <td class="py-2 px-4 border-b">{{ $asignacion->score ?? 'N/A' }}</td>
                <td class="py-2 px-4 border-b">
                    <span class="px-2 py-1 bg-{{ $asignacion->activo ? 'green' : 'red' }}-200 rounded">
                        {{ $asignacion->activo ? 'Sí' : 'No' }}
                    </span>
                </td>
                <td class="py-2 px-4 border-b">
                    <a href="{{ route('admin.asignaciones.show', $asignacion) }}" class="text-blue-500 mr-2">Ver</a>
                    <a href="{{ route('admin.asignaciones.edit', $asignacion) }}" class="text-green-500 mr-2">Editar</a>
                    <form method="POST" action="{{ route('admin.asignaciones.destroy', $asignacion) }}" class="inline" onsubmit="return confirm('¿Eliminar esta asignación?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500">Eliminar</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="py-2 px-4 border-b text-center">No hay asignaciones.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">
        {{ $asignaciones->links() }}
    </div>
</div>
@endsection
