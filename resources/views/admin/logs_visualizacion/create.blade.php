@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Crear Log de Visualización</h1>
    <form method="POST" action="{{ route('admin.logs_visualizacion.store') }}">
        @csrf
        <div class="mb-4">
            <label for="user_id" class="block font-medium mb-1">Usuario</label>
            <select name="user_id" id="user_id" class="border rounded px-2 py-1 w-full @error('user_id') border-red-500 @enderror" required>
                <option value="" disabled {{ old('user_id') ? '' : 'selected' }}>Selecciona un usuario</option>
                @foreach ($users as $user)
                    @if (in_array($user->rol, ['admin', 'coordinador']))
                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                    @endif
                @endforeach
            </select>
            @error('user_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label for="filtro" class="block font-medium mb-1">Filtros (JSON)</label>
            <textarea name="filtro" id="filtro" rows="3" class="border rounded px-2 py-1 w-full @error('filtro') border-red-500 @enderror" placeholder='{"grupo": "G101", "fecha": "2025-11-01"}'>{{ old('filtro') }}</textarea>
            @error('filtro') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label for="fecha" class="block font-medium mb-1">Fecha</label>
            <input type="date" name="fecha" id="fecha" value="{{ old('fecha') }}" class="border rounded px-2 py-1 w-full @error('fecha') border-red-500 @enderror" required>
            @error('fecha') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label for="activo" class="block font-medium mb-1">Activo</label>
            <input type="checkbox" name="activo" id="activo" value="1" {{ old('activo', 1) == 1 ? 'checked' : '' }} class="rounded @error('activo') border-red-500 @enderror">
            @error('activo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Crear Log</button>
        <a href="{{ route('admin.logs_visualizacion.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 ml-2">Cancelar</a>
    </form>
</div>
@endsection
