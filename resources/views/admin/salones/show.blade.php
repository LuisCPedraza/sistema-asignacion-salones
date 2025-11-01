@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Detalle de Salón: {{ $salon->codigo }}</h1>
    <div class="bg-white p-6 rounded shadow">
        <p><strong>ID:</strong> {{ $salon->id }}</p>
        <p><strong>Código:</strong> {{ $salon->codigo }}</p>
        <p><strong>Capacidad:</strong> {{ $salon->capacidad }}</p>
        <p><strong>Ubicación:</strong> {{ $salon->ubicacion }}</p>
        <p><strong>Activo:</strong> <span class="px-2 py-1 bg-{{ $salon->activo ? 'green' : 'red' }}-200 rounded">{{ $salon->activo ? 'Sí' : 'No' }}</span></p>
        <p><strong>Creado:</strong> {{ $salon->created_at->format('d/m/Y H:i') }}</p>
        <p><strong>Actualizado:</strong> {{ $salon->updated_at->format('d/m/Y H:i') }}</p>
    </div>
    <div class="mt-4">
        <a href="{{ route('admin.salones.edit', $salon) }}" class="bg-blue-500 text-white px-4 py-2 rounded mr-2">Editar</a>
        <a href="{{ route('admin.salones.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Volver a Lista</a>
    </div>
</div>
@endsection
