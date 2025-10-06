@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Perfil Profesor</h1>
    <p>Bienvenido, {{ Auth::user()->name }}. Rol: {{ ucfirst(Auth::user()->role) }}.</p>
    <p>Aquí verás tus asignaciones futuras.</p>
</div>
@endsection