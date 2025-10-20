@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Gestión de Grupos</h1>
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
    @endif
    <div class="mb-4">
        <a href="{{ route('admin.grupos.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Crear Grupo</a>
    </div>
    <table class="min-w-full bg-white border border-gray-300">
        <thead class="bg-gray-50">
            <tr>
                <th class="py-2 px-4 border-b">ID</th>
                <th class="py-2 px-4 border-b">Nombre</th>
                <th class="py-2 px-4 border-b">Nivel</th>
                <th class="py-2 px-4 border-b">Num Estudiantes</th>
                <th class="py-2 px-4 border-b">Activo</th>
                <th class="py-2 px-4 border-b">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($grupos as $grupo)
            <tr>
                <td class="py-2 px-4 border-b">{{ $grupo->id }}</td>
                <td class="py-2 px-4 border-b">{{ $grupo->nombre }}</td>
                <td class="py-2 px-4 border-b">{{ ucfirst($grupo->nivel) }}</td>
                <td class="py-2 px-4 border-b">{{ $grupo->num_estudiantes }}</td>
                <td class="py-2 px-4 border-b">
                    <span class="px-2 py-1 bg-{{ $grupo->activo ? 'green' : 'red' }}-200 rounded">{{ $grupo->activo ? 'Sí' : 'No' }}</span>
                </td>
                <td class="py-2 px-4 border-b">
                    <a href="{{ route('admin.grupos.show', $grupo) }}" class="text-blue-500 mr-2">Ver</a>
                    <a href="{{ route('admin.grupos.edit', $grupo) }}" class="text-green-500 mr-2">Editar</a>
                    <form method="POST" action="{{ route('admin.grupos.destroy', $grupo) }}" class="inline" onsubmit="return confirm('¿Eliminar este grupo?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500">Eliminar</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="py-2 px-4 border-b text-center">No hay grupos.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">
        {{ $grupos->links() }}
    </div>
</div>
@endsection
