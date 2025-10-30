@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Editar Usuario: {{ $user->name }}</h1>
    <form method="POST" action="{{ route('admin.users.update', $user) }}">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label for="name" class="block font-medium">Nombre</label>
            <input type="text" name="name" id="name" value="{{ $user->name }}" class="border rounded px-2 py-1 w-full @error('name') border-red-500 @enderror" required>
            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label for="email" class="block font-medium">Email</label>
            <input type="email" name="email" id="email" value="{{ $user->email }}" class="border rounded px-2 py-1 w-full @error('email') border-red-500 @enderror" required>
            @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label for="password" class="block font-medium">Nuevo Password (opcional)</label>
            <input type="password" name="password" id="password" class="border rounded px-2 py-1 w-full @error('password') border-red-500 @enderror">
            @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label for="password_confirmation" class="block font-medium">Confirmar Nuevo Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="border rounded px-2 py-1 w-full">
        </div>
        <div class="mb-4">
            <label for="rol" class="block font-medium">Rol</label>
            <select name="rol" id="rol" class="border rounded px-2 py-1 w-full @error('rol') border-red-500 @enderror" required>
                <option value="" disabled {{ old('rol') ? '' : 'selected' }}>Selecciona un rol</option>
                <option value="admin" {{ old('rol', $user->rol) == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="superadmin" {{ old('rol', $user->rol) == 'superadmin' ? 'selected' : '' }}>Superadmin</option>
                <option value="coordinador" {{ old('rol', $user->rol) == 'coordinador' ? 'selected' : '' }}>Coordinador</option>
                <option value="profesor" {{ old('rol', $user->rol) == 'profesor' ? 'selected' : '' }}>Profesor</option>
                <option value="secretaria" {{ old('rol', $user->rol) == 'secretaria' ? 'selected' : '' }}>Secretaria</option>
                <option value="coordinador_infra" {{ old('rol', $user->rol) == 'coordinador_infra' ? 'selected' : '' }}>Coord. Infra</option>
            </select>
            @error('rol') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Actualizar Usuario</button>
        <a href="{{ route('admin.users.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 ml-2">Cancelar</a>
    </form>
</div>
@endsection
