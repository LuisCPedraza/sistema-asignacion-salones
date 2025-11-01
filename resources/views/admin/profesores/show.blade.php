@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Detalle de Profesor: {{ $profesor->user->name }}</h1>
    <div class="bg-white p-6 rounded shadow">
        <p><strong>ID:</strong> {{ $profesor->id }}</p>
        <p><strong>Usuario:</strong> {{ $profesor->user->name }} ({{ $profesor->user->email }})</p>
        <p><strong>Especialidades:</strong> {{ $profesor->especialidades }}</p>
        <p><strong>Activo:</strong> <span class="px-2 py-1 bg-{{ $profesor->activo ? 'green' : 'red' }}-200 rounded">{{ $profesor->activo ? 'Sí' : 'No' }}</span></p>
        <p><strong>Creado:</strong> {{ $profesor->created_at->format('d/m/Y H:i') }}</p>
        <p><strong>Actualizado:</strong> {{ $profesor->updated_at->format('d/m/Y H:i') }}</p>
    </div>
    <div class="mt-4">
        <a href="{{ route('admin.profesores.edit', $profesor) }}" class="bg-blue-500 text-white px-4 py-2 rounded mr-2">Editar</a>
        <a href="{{ route('admin.profesores.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Volver a Lista</a>
    </div>
</div>
@endsection
