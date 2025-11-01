@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Detalle de Configuración: {{ $configuracion->key }}</h1>
    <div class="bg-white p-6 rounded shadow">
        <p><strong>ID:</strong> {{ $configuracion->id }}</p>
        <p><strong>Clave:</strong> {{ $configuracion->key }}</p>
        <p><strong>Valor (JSON):</strong></p>
        <pre>{{ json_encode($configuracion->value, JSON_PRETTY_PRINT) }}</pre>
        <p><strong>Activo:</strong> <span class="px-2 py-1 bg-{{ $configuracion->activo ? 'green' : 'red' }}-200 rounded">{{ $configuracion->activo ? 'Sí' : 'No' }}</span></p>
        <p><strong>Creado:</strong> {{ $configuracion->created_at->format('d/m/Y H:i') }}</p>
        <p><strong>Actualizado:</strong> {{ $configuracion->updated_at->format('d/m/Y H:i') }}</p>
    </div>
    <div class="mt-4">
        <a href="{{ route('admin.configuraciones.edit', $configuracion) }}" class="bg-blue-500 text-white px-4 py-2 rounded mr-2">Editar</a>
        <a href="{{ route('admin.configuraciones.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Volver a Lista</a>
    </div>
</div>
@endsection

