@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Crear Usuario</h1>
    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf
        <div class="mb-4">
            <label for="name" class="block font-medium">Nombre</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" class="border rounded px-2 py-1 w-full @error('name') border-red-500 @enderror" required>
            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label for="email" class="block font-medium">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" class="border rounded px-2 py-1 w-full @error('email') border-red-500 @enderror" required>
            @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label for="password" class="block font-medium">Password</label>
            <input type="password" name="password" id="password" class="border rounded px-2 py-1 w-full @error('password') border-red-500 @enderror" required>
            @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label for="password_confirmation" class="block font-medium">Confirmar Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="border rounded px-2 py-1 w-full" required>
        </div>
        <div class="mb-4">
            <label for="rol" class="block font-medium">Rol</label>
            <select name="rol" id="rol" class="border rounded px-2 py-1 w-full @error('rol') border-red-500 @enderror" required>
                <option value="" disabled {{ old('rol') ? '' : 'selected' }}>Selecciona un rol</option>
                <option value="admin" {{ old('rol') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="superadmin" {{ old('rol') == 'superadmin' ? 'selected' : '' }}>Superadmin</option>
                <option value="coordinador" {{ old('rol') == 'coordinador' ? 'selected' : '' }}>Coordinador</option>
                <option value="profesor" {{ old('rol') == 'profesor' ? 'selected' : '' }}>Profesor</option>
                <option value="secretaria" {{ old('rol') == 'secretaria' ? 'selected' : '' }}>Secretaria</option>
                <option value="coordinador_infra" {{ old('rol') == 'coordinador_infra' ? 'selected' : '' }}>Coord. Infra</option>
            </select>
            @error('rol') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Crear Usuario</button>
        <a href="{{ route('admin.users.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 ml-2">Cancelar</a>
    </form>
</div>
@endsection

