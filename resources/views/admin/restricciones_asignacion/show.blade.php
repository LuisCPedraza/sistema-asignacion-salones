@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Detalle de Restricción de Asignación: {{ $restriccionAsignacion->id }}</h1>
    <div class="bg-white p-6 rounded shadow">
        <p><strong>ID:</strong> {{ $restriccionAsignacion->id }}</p>
        <p><strong>Recurso:</strong> {{ $restriccionAsignacion->recurso->codigo ?? $restriccionAsignacion->recurso->user->name ?? 'N/A' }}</p>
        <p><strong>Tipo de Restricción:</strong> {{ ucfirst($restriccionAsignacion->tipo_restriccion) }}</p>
        <p><strong>Valor (JSON):</strong></p>
        <pre>{{ json_encode($restriccionAsignacion->valor ?? [], JSON_PRETTY_PRINT) }}</pre>
        <p><strong>Activo:</strong> <span class="px-2 py-1 bg-{{ $restriccionAsignacion->activo ? 'green' : 'red' }}-200 rounded">{{ $restriccionAsignacion->activo ? 'Sí' : 'No' }}</span></p>
        <p><strong>Creado:</strong> {{ $restriccionAsignacion->created_at->format('d/m/Y H:i') }}</p>
        <p><strong>Actualizado:</strong> {{ $restriccionAsignacion->updated_at->format('d/m/Y H:i') }}</p>
    </div>
    <div class="mt-4">
        <a href="{{ route('admin.restricciones_asignacion.edit', $restriccionAsignacion) }}" class="bg-blue-500 text-white px-4 py-2 rounded mr-2">Editar</a>
        <a href="{{ route('admin.restricciones_asignacion.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Volver a Lista</a>
    </div>
</div>
@endsection
