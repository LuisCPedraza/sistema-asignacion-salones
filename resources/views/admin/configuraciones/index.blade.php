@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Gestión de Configuraciones</h1>
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
    @endif
    <div class="mb-4">
        <a href="{{ route('admin.configuraciones.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Crear Configuración</a>
        <a href="{{ route('admin.dashboard') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 ml-2">Regresar al Dashboard</a>
    </div>
    <table class="min-w-full bg-white border border-gray-300">
        <thead class="bg-gray-50">
            <tr>
                <th class="py-2 px-4 border-b">ID</th>
                <th class="py-2 px-4 border-b">Clave</th>
                <th class="py-2 px-4 border-b">Valor (JSON)</th>
                <th class="py-2 px-4 border-b">Activo</th>
                <th class="py-2 px-4 border-b">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($configuraciones as $configuracion)
            <tr>
                <td class="py-2 px-4 border-b">{{ $configuracion->id }}</td>
                <td class="py-2 px-4 border-b">{{ $configuracion->key }}</td>
                <td class="py-2 px-4 border-b"><pre>{{ json_encode($configuracion->value, JSON_PRETTY_PRINT) }}</pre></td>
                <td class="py-2 px-4 border-b">
                    <span class="px-2 py-1 bg-{{ $configuracion->activo ? 'green' : 'red' }}-200 rounded">
                        {{ $configuracion->activo ? 'Sí' : 'No' }}
                    </span>
                </td>
                <td class="py-2 px-4 border-b">
                    <a href="{{ route('admin.configuraciones.show', $configuracion) }}" class="text-blue-500 mr-2">Ver</a>
                    <a href="{{ route('admin.configuraciones.edit', $configuracion) }}" class="text-green-500 mr-2">Editar</a>
                    <form method="POST" action="{{ route('admin.configuraciones.destroy', $configuracion) }}" class="inline" onsubmit="return confirm('¿Eliminar esta configuración?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500">Eliminar</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="py-2 px-4 border-b text-center">No hay configuraciones.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">
        {{ $configuraciones->links() }}
    </div>
</div>
@endsection