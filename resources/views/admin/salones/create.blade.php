@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-8">Crear Salón</h1>
    <form method="POST" action="{{ route('admin.salones.store') }}">
        @csrf
        <div class="mb-4">
            <label for="codigo" class="block font-medium mb-1">Código</label>
            <input type="text" name="codigo" id="codigo" value="{{ old('codigo') }}" class="border rounded px-2 py-1 w-full @error('codigo') border-red-500 @enderror" placeholder="E.g., S101-A1" required>
            @error('codigo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label for="capacidad" class="block font-medium mb-1">Capacidad</label>
            <input type="number" name="capacidad" id="capacidad" min="1" value="{{ old('capacidad') }}" class="border rounded px-2 py-1 w-full @error('capacidad') border-red-500 @enderror" required>
            @error('capacidad') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label for="ubicacion" class="block font-medium mb-1">Ubicación</label>
            <input type="text" name="ubicacion" id="ubicacion" value="{{ old('ubicacion') }}" class="border rounded px-2 py-1 w-full @error('ubicacion') border-red-500 @enderror" placeholder="E.g., Edificio A, Piso 1" required>
            @error('ubicacion') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label for="activo" class="block font-medium mb-1">Activo</label>
            <input type="checkbox" name="activo" id="activo" value="1" {{ old('activo', 1) == 1 ? 'checked' : '' }} class="rounded @error('activo') border-red-500 @enderror">
            @error('activo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Crear Salón</button>
        <a href="{{ route('admin.salones.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 ml-2">Cancelar</a>
    </form>
</div>
@endsection
