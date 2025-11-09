@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Detalle de Log de Visualización: {{ $logVisualizacion->id }}</h1>
    <div class="bg-white p-6 rounded shadow">
        <p><strong>ID:</strong> {{ $logVisualizacion->id }}</p>
        <p><strong>Usuario:</strong> {{ $logVisualizacion->user->name ?? 'N/A' }} ({{ $logVisualizacion->user->email ?? 'N/A' }})</p>
        <p><strong>Filtros (JSON):</strong></p>
        <pre>{{ json_encode($logVisualizacion->filtro ?? [], JSON_PRETTY_PRINT) }}</pre>
        <p><strong>Fecha:</strong> {{ $logVisualizacion->fecha }}</p>
        <p><strong>Activo:</strong> <span class="px-2 py-1 bg-{{ $logVisualizacion->activo ? 'green' : 'red' }}-200 rounded">{{ $logVisualizacion->activo ? 'Sí' : 'No' }}</span></p>
        <p><strong>Creado:</strong> {{ $logVisualizacion->created_at->format('d/m/Y H:i') }}</p>
        <p><strong>Actualizado:</strong> {{ $logVisualizacion->updated_at->format('d/m/Y H:i') }}</p>
    </div>
    <div class="mt-4">
        <a href="{{ route('admin.logs_visualizacion.edit', $logVisualizacion) }}" class="bg-blue-500 text-white px-4 py-2 rounded mr-2">Editar</a>
        <a href="{{ route('admin.logs_visualizacion.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Volver a Lista</a>
    </div>
</div>
@endsection
