@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <h1>Detalle: {{ $studentGroup->name }}</h1>
    <div class="row">
        <div class="col-md-6">
            <p><strong>Nivel:</strong> {{ $studentGroup->level }}</p>
            <p><strong># Estudiantes:</strong> {{ $studentGroup->student_count }}</p>
            <p><strong>Características:</strong> {{ $studentGroup->special_features ?? 'Ninguna' }}</p>
            <p><strong>Período:</strong> {{ $studentGroup->academicPeriod?->name ?? 'N/A' }}</p>
            <p><strong>Activo:</strong> <span class="badge {{ $studentGroup->is_active ? 'bg-success' : 'bg-secondary' }}">{{ $studentGroup->is_active ? 'Sí' : 'No' }}</span></p>
            <p><strong>Creado:</strong> {{ $studentGroup->created_at->format('d/m/Y H:i') }}</p>
        </div>
    </div>
    <a href="{{ route('gestion-academica.student-groups.edit', $studentGroup) }}" class="btn btn-warning">Editar</a>
    <a href="{{ route('gestion-academica.student-groups.index') }}" class="btn btn-secondary">Volver</a>
</div>
@endsection