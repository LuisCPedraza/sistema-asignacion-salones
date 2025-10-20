@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Detalle de Grupo: {{ $grupo->nombre }}</h1>
    <div class="bg-white p-6 rounded shadow">
        <p><strong>ID:</strong> {{ $grupo->id }}</p>
        <p><strong>Nombre:</strong> {{ $grupo->nombre }}</p>
        <p><strong>Nivel:</strong> {{ ucfirst($grupo->nivel) }}</p>
        <p><strong>Num Estudiantes:</strong> {{ $grupo->num_estudiantes }}</p>
        <p><strong>Activo:</strong> <span class="px-2 py-1 bg-{{ $grupo->activo ? 'green' : 'red' }}-200 rounded">{{ $grupo->activo ? 'Sí' : 'No' }}</span></p>
        <p><strong>Creado:</strong> {{ $grupo->created_at->format('d/m/Y H:i') }}</p>
        <p><strong>Actualizado:</strong> {{ $grupo->updated_at->format('d/m/Y H:i') }}</p>
    </div>
    <div class="mt-4">
        <a href="{{ route('admin.grupos.edit', $grupo) }}" class="bg-blue-500 text-white px-4 py-2 rounded mr-2">Editar</a>
        <a href="{{ route('admin.grupos.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Volver a Lista</a>
    </div>
</div>
@endsection
