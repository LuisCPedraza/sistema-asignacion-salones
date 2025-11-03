@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Gestión de Usuarios</h1>
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
    @endif
    <div class="mb-4">
        <a href="{{ route('admin.users.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Crear Usuario</a>
        <a href="{{ route('admin.dashboard') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 ml-2">Regresar al Dashboard</a>
    </div>
    <table class="min-w-full bg-white border border-gray-300">
        <thead class="bg-gray-50">
            <tr>
                <th class="py-2 px-4 border-b">ID</th>
                <th class="py-2 px-4 border-b">Nombre</th>
                <th class="py-2 px-4 border-b">Email</th>
                <th class="py-2 px-4 border-b">Rol</th>
                <th class="py-2 px-4 border-b">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
            <tr>
                <td class="py-2 px-4 border-b">{{ $user->id }}</td>
                <td class="py-2 px-4 border-b">{{ $user->name }}</td>
                <td class="py-2 px-4 border-b">{{ $user->email }}</td>
                <td class="py-2 px-4 border-b">
                    <span class="px-2 py-1 rounded text-xs font-bold">
                        @switch($user->rol)
                            @case('admin')
                                <span class="bg-blue-100 text-blue-800">👑 Admin</span>
                                @break
                            @case('superadmin')
                                <span class="bg-purple-100 text-purple-800">🔥 Superadmin</span>
                                @break
                            @case('coordinador')
                                <span class="bg-green-100 text-green-800">📋 Coordinador</span>
                                @break
                            @case('coordinador_infra')
                                <span class="bg-yellow-100 text-yellow-800">🏗️ Coord. Infra</span>
                                @break
                            @case('profesor')
                                <span class="bg-indigo-100 text-indigo-800">👨‍🏫 Profesor</span>
                                @break
                            @case('secretaria')
                                <span class="bg-pink-100 text-pink-800">💼 Secretaria</span>
                                @break
                            @default
                                <span class="bg-gray-100 text-gray-800">👤 {{ ucfirst($user->rol) }}</span>
                        @endswitch
                    </span>
                </td>
                <td class="py-2 px-4 border-b">
                    <a href="{{ route('admin.users.show', $user) }}" class="text-blue-500 mr-2">Ver</a>
                    <a href="{{ route('admin.users.edit', $user) }}" class="text-green-500 mr-2">Editar</a>
                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline" onsubmit="return confirm('¿Eliminar este usuario?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500">Eliminar</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="py-2 px-4 border-b text-center">No hay usuarios.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>
@endsection
