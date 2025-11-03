@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Editar Propuesta de Asignación: {{ $propuestaAsignacion->id }}</h1>
    <form method="POST" action="{{ route('admin.propuestas_asignacion.update', $propuestaAsignacion) }}">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label for="asignacion_id" class="block font-medium mb-1">Asignación</label>
            <select name="asignacion_id" id="asignacion_id" class="border rounded px-2 py-1 w-full @error('asignacion_id') border-red-500 @enderror" required>
                <option value="" disabled>Selecciona una asignación</option>
                @foreach ($asignaciones as $asignacion)
                    <option value="{{ $asignacion->id }}" {{ old('asignacion_id', $propuestaAsignacion->asignacion_id) == $asignacion->id ? 'selected' : '' }}>{{ $asignacion->id }} - {{ $asignacion->grupo->nombre }}</option>
                @endforeach
            </select>
            @error('asignacion_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label for="score" class="block font-medium mb-1">Score</label>
            <input type="number" name="score" id="score" step="0.01" min="0" max="100" value="{{ old('score', $propuestaAsignacion->score) }}" class="border rounded px-2 py-1 w-full @error('score') border-red-500 @enderror" required>
            @error('score') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label for="conflictos" class="block font-medium mb-1">Conflictos (JSON)</label>
            <textarea name="conflictos" id="conflictos" rows="3" class="border rounded px-2 py-1 w-full @error('conflictos') border-red-500 @enderror" placeholder='["horario", "salon"]'>{{ old('conflictos', json_encode($propuestaAsignacion->conflictos ?? [], JSON_PRETTY_PRINT)) }}</textarea>
            @error('conflictos') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label for="orden" class="block font-medium mb-1">Orden</label>
            <input type="number" name="orden" id="orden" min="1" max="10" value="{{ old('orden', $propuestaAsignacion->orden) }}" class="border rounded px-2 py-1 w-full @error('orden') border-red-500 @enderror" required>
            @error('orden') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label for="activo" class="block font-medium mb-1">Activo</label>
            <input type="checkbox" name="activo" id="activo" value="1" {{ old('activo', $propuestaAsignacion->activo) ? 'checked' : '' }} class="rounded @error('activo') border-red-500 @enderror">
            @error('activo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Actualizar Propuesta</button>
        <a href="{{ route('admin.propuestas_asignacion.index') }}" class="bg-gray-500 text-white px-4 py-4 py-2 rounded hover:bg-gray-600 ml-2">Cancelar</a>
    </form>
</div>
@endsection
