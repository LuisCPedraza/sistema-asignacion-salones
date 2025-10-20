@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Editar Grupo: {{ $grupo->nombre }}</h1>
    <form method="POST" action="{{ route('admin.grupos.update', $grupo) }}">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label for="nombre" class="block font-medium">Nombre</label>
            <input type="text" name="nombre" id="nombre" value="{{ $grupo->nombre }}" class="border rounded px-2 py-1 w-full @error('nombre') border-red-500 @enderror" required>
            @error('nombre') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label for="nivel" class="block font-medium">Nivel</label>
            <select name="nivel" id="nivel" class="border rounded px-2 py-1 w-full @error('nivel') border-red-500 @enderror" required>
                <option value="basico" {{ $grupo->nivel == 'basico' ? 'selected' : '' }}>Básico</option>
                <option value="intermedio" {{ $grupo->nivel == 'intermedio' ? 'selected' : '' }}>Intermedio</option>
                <option value="avanzado" {{ $grupo->nivel == 'avanzado' ? 'selected' : '' }}>Avanzado</option>
            </select>
            @error('nivel') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label for="num_estudiantes" class="block font-medium">Num Estudiantes</label>
            <input type="number" name="num_estudiantes" id="num_estudiantes" value="{{ $grupo->num_estudiantes }}" min="1" class="border rounded px-2 py-1 w-full @error('num_estudiantes') border-red-500 @enderror" required>
            @error('num_estudiantes') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label for="activo" class="block font-medium">Activo</label>
            <input type="checkbox" name="activo" id="activo" value="1" {{ $grupo->activo ? 'checked' : '' }} class="rounded @error('activo') border-red-500 @enderror">
            @error('activo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Actualizar Grupo</button>
        <a href="{{ route('admin.grupos.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 ml-2">Cancelar</a>
    </form>
</div>
@endsection
