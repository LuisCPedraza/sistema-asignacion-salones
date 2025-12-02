@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>👆 Asignación Manual</h1>
        <a href="{{ route('academic.dashboard') }}" class="btn btn-secondary">← Volver</a>
    </div>

    <div class="card">
        <div class="card-body">
            <p>Asignaciones actuales: {{ $assignments->count() }}</p>
            <div id="calendar" style="height: 400px; background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 0.375rem; margin: 1rem 0;">
                <p class="p-4 text-center text-muted">Calendario interactivo (Drag & Drop) en desarrollo</p>
            </div>
            <a href="{{ route('asignacion.assignments.create') }}" class="btn btn-success">➕ Nueva Asignación</a>
        </div>
    </div>
</div>
@endsection