@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Editar Registro de Historial de Asignación: {{ $historialAsignacion->id }}</h1>
    <form method="POST" action="{{ route('admin.historial_asignacion.update', $historialAsignacion) }}">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label for="asignacion_id" class="block font-medium mb-1">Asignación</label>
            <select name="asignacion_id" id="asignacion_id" class="border rounded px-2 py-1 w-full @error('asignacion_id') border-red-500 @enderror" required>
                <option value="" disabled>Selecciona una asignación</option>
                @foreach ($asignaciones as $asignacion)
                    <option value="{{ $asignacion->id }}" {{ old('asignacion_id', $historialAsignacion->asignacion_id) == $asignacion->id ? 'selected' : '' }}>{{ $asignacion->id }} - {{ $asignacion->grupo->nombre }}</option>
                @endforeach
            </select>
            @error('asignacion_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label for="user_id" class="block font-medium mb-1">Usuario</label>
            <select name="user_id" id="user_id" class="border rounded px-2 py-1 w-full @error('user_id') border-red-500 @enderror" required>
                <option value="" disabled>Selecciona un usuario</option>
                @foreach ($usuarios as $usuario)
                    <option value="{{ $usuario->id }}" {{ old('user_id', $historialAsignacion->user_id) == $usuario->id ? 'selected' : '' }}>{{ $usuario->name }} ({{ $usuario->email }})</option>
                @endforeach
            </select>
            @error('user_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label for="accion" class="block font-medium mb-1">Acción</label>
            <select name="accion" id="accion" class="border rounded px-2 py-1 w-full @error('accion') border-red-500 @enderror" required>
                <option value="" disabled>Selecciona acción</option>
                <option value="create" {{ old('accion', $historialAsignacion->accion) == 'create' ? 'selected' : '' }}>Crear</option>
                <option value="update" {{ old('accion', $historialAsignacion->accion) == 'update' ? 'selected' : '' }}>Actualizar</option>
                <option value="delete" {{ old('accion', $historialAsignacion->accion) == 'delete' ? 'selected' : '' }}>Eliminar</option>
            </select>
            @error('accion') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label for="cambios" class="block font-medium mb-1">Cambios (JSON)</label>
            <textarea name="cambios" id="cambios" rows="3" class="border rounded px-2 py-1 w-full @error('cambios') border-red-500 @enderror" placeholder='{"old_estado": "propuesta", "new_estado": "confirmada"}'>{{ old('cambios', json_encode($historialAsignacion->cambios ?? [], JSON_PRETTY_PRINT)) }}</textarea>
            @error('cambios') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label for="fecha" class="block font-medium mb-1">Fecha</label>
            <input type="datetime-local" name="fecha" id="fecha" value="{{ old('fecha', $historialAsignacion->fecha) }}" class="border rounded px-2 py-1 w-full @error('fecha') border-red-500 @enderror" required>
            @error('fecha') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label for="activo" class="block font-medium mb-1">Activo</label>
            <input type="checkbox" name="activo" id="activo" value="1" {{ old('activo', $historialAsignacion->activo) ? 'checked' : '' }} class="rounded @error('activo') border-red-500 @enderror">
            @error('activo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Actualizar Registro</button>
        <a href="{{ route('admin.historial_asignacion.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 ml-2">Cancelar</a>
    </form>
</div>
@endsection