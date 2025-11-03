@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Editar Asignación: {{ $asignacion->id }}</h1>
    <form method="POST" action="{{ route('admin.asignaciones.update', $asignacion) }}">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label for="grupo_id" class="block font-medium mb-1">Grupo</label>
            <select name="grupo_id" id="grupo_id" class="border rounded px-2 py-1 w-full @error('grupo_id') border-red-500 @enderror" required>
                <option value="" disabled>Selecciona un grupo</option>
                @foreach ($grupos as $grupo)
                    <option value="{{ $grupo->id }}" {{ old('grupo_id', $asignacion->grupo_id) == $grupo->id ? 'selected' : '' }}>{{ $grupo->nombre }}</option>
                @endforeach
            </select>
            @error('grupo_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label for="salon_id" class="block font-medium mb-1">Salón</label>
            <select name="salon_id" id="salon_id" class="border rounded px-2 py-1 w-full @error('salon_id') border-red-500 @enderror" required>
                <option value="" disabled>Selecciona un salón</option>
                @foreach ($salones as $salon)
                    <option value="{{ $salon->id }}" {{ old('salon_id', $asignacion->salon_id) == $salon->id ? 'selected' : '' }}>{{ $salon->codigo }}</option>
                @endforeach
            </select>
            @error('salon_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label for="profesor_id" class="block font-medium mb-1">Profesor</label>
            <select name="profesor_id" id="profesor_id" class="border rounded px-2 py-1 w-full @error('profesor_id') border-red-500 @enderror" required>
                <option value="" disabled>Selecciona un profesor</option>
                @foreach ($profesores as $profesor)
                    <option value="{{ $profesor->id }}" {{ old('profesor_id', $asignacion->profesor_id) == $profesor->id ? 'selected' : '' }}>{{ $profesor->user->name }}</option>
                @endforeach
            </select>
            @error('profesor_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label for="fecha" class="block font-medium mb-1">Fecha</label>
            <input type="date" name="fecha" id="fecha" value="{{ old('fecha', $asignacion->fecha) }}" class="border rounded px-2 py-1 w-full @error('fecha') border-red-500 @enderror" required>
            @error('fecha') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label for="hora" class="block font-medium mb-1">Hora</label>
            <input type="time" name="hora" id="hora" value="{{ old('hora', $asignacion->hora) }}" class="border rounded px-2 py-1 w-full @error('hora') border-red-500 @enderror" required>
            @error('hora') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label for="estado" class="block font-medium mb-1">Estado</label>
            <select name="estado" id="estado" class="border rounded px-2 py-1 w-full @error('estado') border-red-500 @enderror" required>
                <option value="propuesta" {{ old('estado', $asignacion->estado) == 'propuesta' ? 'selected' : '' }}>Propuesta</option>
                <option value="confirmada" {{ old('estado', $asignacion->estado) == 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                <option value="cancelada" {{ old('estado', $asignacion->estado) == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
            </select>
            @error('estado') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label for="score" class="block font-medium mb-1">Score</label>
            <input type="number" name="score" id="score" step="0.01" min="0" max="100" value="{{ old('score', $asignacion->score) }}" class="border rounded px-2 py-1 w-full @error('score') border-red-500 @enderror">
            @error('score') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label for="conflictos" class="block font-medium mb-1">Conflictos (JSON)</label>
            <textarea name="conflictos" id="conflictos" rows="3" class="border rounded px-2 py-1 w-full @error('conflictos') border-red-500 @enderror" placeholder='["horario", "salon"]'>{{ old('conflictos', json_encode($asignacion->conflictos ?? [], JSON_PRETTY_PRINT)) }}</textarea>
            @error('conflictos') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label for="activo" class="block font-medium mb-1">Activo</label>
            <input type="checkbox" name="activo" id="activo" value="1" {{ old('activo', $asignacion->activo) ? 'checked' : '' }} class="rounded @error('activo') border-red-500 @enderror">
            @error('activo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Actualizar Asignación</button>
        <a href="{{ route('admin.asignaciones.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 ml-2">Cancelar</a>
    </form>
</div>
@endsection
