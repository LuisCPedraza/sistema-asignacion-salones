@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Detalle de Propuesta de Asignación: {{ $propuestaAsignacion->id }}</h1>
    <div class="bg-white p-6 rounded shadow">
        <p><strong>ID:</strong> {{ $propuestaAsignacion->id }}</p>
        <p><strong>Asignación:</strong> {{ $propuestaAsignacion->asignacion->id ?? 'N/A' }}</p>
        <p><strong>Score:</strong> {{ $propuestaAsignacion->score }}</p>
        <p><strong>Conflictos (JSON):</strong></p>
        <pre>{{ json_encode($propuestaAsignacion->conflictos ?? [], JSON_PRETTY_PRINT) }}</pre>
        <p><strong>Orden:</strong> {{ $propuestaAsignacion->orden }}</p>
        <p><strong>Activo:</strong> <span class="px-2 py-1 bg-{{ $propuestaAsignacion->activo ? 'green' : 'red' }}-200 rounded">{{ $propuestaAsignacion->activo ? 'Sí' : 'No' }}</span></p>
        <p><strong>Creado:</strong> {{ $propuestaAsignacion->created_at->format('d/m/Y H:i') }}</p>
        <p><strong>Actualizado:</strong> {{ $propuestaAsignacion->updated_at->format('d/m/Y H:i') }}</p>
    </div>
    <div class="mt-4">
        <a href="{{ route('admin.propuestas_asignacion.edit', $propuestaAsignacion) }}" class="bg-blue-500 text-white px-4 py-2 rounded mr-2">Editar</a>
        <a href="{{ route('admin.propuestas_asignacion.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Volver a Lista</a>
    </div>
</div>
@endsection
