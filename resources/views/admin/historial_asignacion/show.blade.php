@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Detalle de Registro de Historial de Asignación: {{ $historialAsignacion->id }}</h1>
    <div class="bg-white p-6 rounded shadow">
        <p><strong>ID:</strong> {{ $historialAsignacion->id }}</p>
        <p><strong>Asignación:</strong> {{ $historialAsignacion->asignacion->id ?? 'N/A' }}</p>
        <p><strong>Usuario:</strong> {{ $historialAsignacion->user->name ?? 'N/A' }} ({{ $historialAsignacion->user->email ?? 'N/A' }})</p>
        <p><strong>Acción:</strong> {{ ucfirst($historialAsignacion->accion) }}</p>
        <p><strong>Cambios (JSON):</strong></p>
        <pre>{{ json_encode($historialAsignacion->cambios ?? [], JSON_PRETTY_PRINT) }}</pre>
        <p><strong>Fecha:</strong> {{ $historialAsignacion->fecha->format('d/m/Y H:i') }}</p>
        <p><strong>Activo:</strong> <span class="px-2 py-1 bg-{{ $historialAsignacion->activo ? 'green' : 'red' }}-200 rounded">{{ $historialAsignacion->activo ? 'Sí' : 'No' }}</span></p>
        <p><strong>Creado:</strong> {{ $historialAsignacion->created_at->format('d/m/Y H:i') }}</p>
        <p><strong>Actualizado:</strong> {{ $historialAsignacion->updated_at->format('d/m/Y H:i') }}</p>
    </div>
    <div class="mt-4">
        <a href="{{ route('admin.historial_asignacion.edit', $historialAsignacion) }}" class="bg-blue-500 text-white px-4 py-2 rounded mr-2">Editar</a>
        <a href="{{ route('admin.historial_asignacion.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Volver a Lista</a>
    </div>
</div>
@endsection