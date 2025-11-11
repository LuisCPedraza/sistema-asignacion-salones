@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Gestión de Historial de Asignaciones</h1>
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
    @endif
    <div class="mb-4">
        <a href="{{ route('admin.historial_asignacion.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Crear Historial</a>
        <a href="{{ route('admin.dashboard') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 ml-2">Regresar al Dashboard</a>
    </div>
    <table class="min-w-full bg-white border border-gray-300">
        <thead class="bg-gray-50">
            <tr>
                <th class="py-2 px-4 border-b">ID</th>
                <th class="py-2 px-4 border-b">Asignación</th>
                <th class="py-2 px-4 border-b">Usuario</th>
                <th class="py-2 px-4 border-b">Acción</th>
                <th class="py-2 px-4 border-b">Cambios (JSON)</th>
                <th class="py-2 px-4 border-b">Fecha</th>
                <th class="py-2 px-4 border-b">Activo</th>
                <th class="py-2 px-4 border-b">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($historial as $registro)
            <tr>
                <td class="py-2 px-4 border-b">{{ $registro->id }}</td>
                <td class="py-2 px-4 border-b">{{ $registro->asignacion->id ?? 'N/A' }}</td>
                <td class="py-2 px-4 border-b">{{ $registro->user->name ?? 'N/A' }}</td>
                <td class="py-2 px-4 border-b">{{ ucfirst($registro->accion) }}</td>
                <td class="py-2 px-4 border-b"><pre>{{ json_encode($registro->cambios ?? [], JSON_PRETTY_PRINT) }}</pre></td>
                <td class="py-2 px-4 border-b">{{ $registro->fecha->format('d/m/Y H:i') }}</td>
                <td class="py-2 px-4 border-b">
                    <span class="px-2 py-1 bg-{{ $registro->activo ? 'green' : 'red' }}-200 rounded">
                        {{ $registro->activo ? 'Sí' : 'No' }}
                    </span>
                </td>
                <td class="py-2 px-4 border-b">
                    <a href="{{ route('admin.historial_asignacion.show', $registro) }}" class="text-blue-500 mr-2">Ver</a>
                    <a href="{{ route('admin.historial_asignacion.edit', $registro) }}" class="text-green-500 mr-2">Editar</a>
                    <form method="POST" action="{{ route('admin.historial_asignacion.destroy', $registro) }}" class="inline" onsubmit="return confirm('¿Eliminar este registro de historial?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500">Eliminar</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="py-2 px-4 border-b text-center">No hay historial de asignaciones.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">
        {{ $historial->links() }}
    </div>
</div>
@endsection