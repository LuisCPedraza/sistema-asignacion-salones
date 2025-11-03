@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Dashboard Admin</h1>
    <p>Bienvenido, {{ Auth::user()->name }}. Rol: {{ ucfirst(Auth::user()->rol) }}.</p>
    <ul class="mt-4 space-y-2">
        <li><a href="{{ route('admin.users.index') }}" class="text-blue-500 hover:underline">Gestión de Usuarios</a></li>
        <li><a href="{{ route('admin.grupos.index') }}" class="text-blue-500 hover:underline">Gestión de Grupos</a></li>
        <li><a href="{{ route('admin.salones.index') }}" class="text-blue-500 hover:underline">Gestión de Salones</a></li>
        <li><a href="{{ route('admin.profesores.index') }}" class="text-blue-500 hover:underline">Gestión de Profesores</a></li>
        <li><a href="{{ route('admin.configuraciones.index') }}" class="text-blue-500 hover:underline">Gestión de Configuraciones</a></li>
        <li><a href="{{ route('admin.asignaciones.index') }}" class="text-blue-500 hover:underline">Gestión de Asignaciones</a></li>
        <li><a href="{{ route('admin.propuestas_asignacion.index') }}" class="text-blue-500 hover:underline">Gestión de Propuestas de Asignación</a></li>
    </ul>
</div>
@endsection
