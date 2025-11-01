@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Crear Profesor</h1>
    <form method="POST" action="{{ route('admin.profesores.store') }}">
        @csrf
        <div class="mb-4">
            <label for="usuario_id" class="block font-medium mb-1">Usuario (Profesor)</label>
            <select name="usuario_id" id="usuario_id" class="border rounded px-2 py-1 w-full @error('usuario_id') border-red-500 @enderror" required>
                <option value="" disabled {{ old('usuario_id') ? '' : 'selected' }}>Selecciona un usuario</option>
                @foreach ($users as $user)
                    @if ($user->rol == 'profesor' || $user->rol == 'superadmin')
                        <option value="{{ $user->id }}" {{ old('usuario_id') == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                    @endif
                @endforeach
            </select>
            @error('usuario_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label for="especialidades" class="block font-medium mb-1">Especialidades</label>
            <input type="text" name="especialidades" id="especialidades" value="{{ old('especialidades') }}" class="border rounded px-2 py-1 w-full @error('especialidades') border-red-500 @enderror" placeholder="E.g., Matemáticas, Física" required>
            @error('especialidades') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label for="activo" class="block font-medium mb-1">Activo</label>
            <input type="checkbox" name="activo" id="activo" value="1" {{ old('activo', 1) == 1 ? 'checked' : '' }} class="rounded @error('activo') border-red-500 @enderror">
            @error('activo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Crear Profesor</button>
        <a href="{{ route('admin.profesores.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 ml-2">Cancelar</a>
    </form>
</div>
@endsection
