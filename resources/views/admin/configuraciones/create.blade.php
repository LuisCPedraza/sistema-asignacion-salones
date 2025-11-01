@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Crear Configuración</h1>
    <form method="POST" action="{{ route('admin.configuraciones.store') }}">
        @csrf
        <div class="mb-4">
            <label for="key" class="block font-medium mb-1">Clave</label>
            <input type="text" name="key" id="key" value="{{ old('key') }}" class="border rounded px-2 py-1 w-full @error('key') border-red-500 @enderror" placeholder="E.g., horarios_default" required>
            @error('key') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label for="value" class="block font-medium mb-1">Valor (JSON)</label>
            <textarea name="value" id="value" rows="5" class="border rounded px-2 py-1 w-full @error('value') border-red-500 @enderror" placeholder='{"horarios": "Lun-Vie 8-18", "max_asignaciones": 10}' required>{{ old('value') }}</textarea>
            @error('value') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label for="activo" class="block font-medium mb-1">Activo</label>
            <input type="checkbox" name="activo" id="activo" value="1" {{ old('activo', 1) == 1 ? 'checked' : '' }} class="rounded @error('activo') border-red-500 @enderror">
            @error('activo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Crear Configuración</button>
        <a href="{{ route('admin.configuraciones.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 ml-2">Cancelar</a>
    </form>
</div>
@endsection
