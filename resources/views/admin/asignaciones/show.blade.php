@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Detalle de Asignación: {{ $asignacion->id }}</h1>
    <div class="bg-white p-6 rounded shadow">
        <p><strong>ID:</strong> {{ $asignacion->id }}</p>
        <p><strong>Grupo:</strong> {{ $asignacion->grupo->nombre ?? 'N/A' }}</p>
        <p><strong>Salón:</strong> {{ $asignacion->salon->codigo ?? 'N/A' }}</p>
        <p><strong>Profesor:</strong> {{ $asignacion->profesor->user->name ?? 'N/A' }}</p>
        <p><strong>Fecha/Hora:</strong> {{ $asignacion->fecha }} / {{ $asignacion->hora }}</p>
        <p><strong>Estado:</strong> <span class="px-2 py-1 bg-blue-100 rounded text-blue-800">{{ ucfirst($asignacion->estado) }}</span></p>
        <p><strong>Score:</strong> {{ $asignacion->score ?? 'N/A' }}</p>
        <p><strong>Conflictos (JSON):</strong></p>
        <pre>{{ json_encode($asignacion->conflictos ?? [], JSON_PRETTY_PRINT) }}</pre>
        <p><strong>Activo:</strong> <span class="px-2 py-1 bg-{{ $asignacion->activo ? 'green' : 'red' }}-200 rounded">{{ $asignacion->activo ? 'Sí' : 'No' }}</span></p>
        <p><strong>Creado:</strong> {{ $asignacion->created_at->format('d/m/Y H:i') }}</p>
        <p><strong>Actualizado:</strong> {{ $asignacion->updated_at->format('d/m/Y H:i') }}</p>
    </div>
    <div class="mt-4">
        <a href="{{ route('admin.asignaciones.edit', $asignacion) }}" class="bg-blue-500 text-white px-4 py-2 rounded mr-2">Editar</a>
        <a href="{{ route('admin.asignaciones.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Volver a Lista</a>
    </div>
</div>
@endsection
