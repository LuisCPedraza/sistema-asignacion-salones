@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Detalle de Usuario: {{ $user->name }}</h1>
    <div class="bg-white p-6 rounded shadow">
        <p><strong>ID:</strong> {{ $user->id }}</p>
        <p><strong>Nombre:</strong> {{ $user->name }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>Rol:</strong> 
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
        </p>
        <p><strong>Creado:</strong> {{ $user->created_at->format('d/m/Y H:i') }}</p>
        <p><strong>Actualizado:</strong> {{ $user->updated_at->format('d/m/Y H:i') }}</p>
    </div>
    <div class="mt-4">
        <a href="{{ route('admin.users.edit', $user) }}" class="bg-blue-500 text-white px-4 py-2 rounded mr-2">Editar</a>
        <a href="{{ route('admin.users.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Volver a Lista</a>
    </div>
</div>
@endsection
