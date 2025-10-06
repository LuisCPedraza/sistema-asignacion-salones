@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Dashboard Admin</h1>
    <p>Bienvenido, {{ Auth::user()->name }}. Rol: {{ ucfirst(Auth::user()->role) }}.</p>
    <ul class="mt-4">
        <li><a href="{{ route('admin.users.index') }}" class="text-blue-500">Gestión de Usuarios</a></li>
        <li><a href="/salones" class="text-blue-500">Gestión de Salones</a></li>  {{-- Futuro --}}
    </ul>
</div>
@endsection