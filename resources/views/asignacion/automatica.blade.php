@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>🔄 Asignación Automática</h1>
        <a href="{{ route('academic.dashboard') }}" class="btn btn-secondary">← Volver</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <p>Grupos disponibles: {{ $groups->count() }} | Salones: {{ $classrooms->count() }}</p>
            <form method="POST" action="{{ route('asignacion.ejecutar-automatica') }}">
                @csrf
                <button type="submit" class="btn btn-primary">Ejecutar Algoritmo</button>
            </form>
        </div>
    </div>
</div>
@endsection