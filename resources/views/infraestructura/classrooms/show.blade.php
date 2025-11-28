@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <h1>Detalle: {{ $classroom->name }} ({{ $classroom->code }})</h1>
    
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Información General</h5>
                    <p><strong>Código:</strong> {{ $classroom->code }}</p>
                    <p><strong>Nombre:</strong> {{ $classroom->name }}</p>
                    <p><strong>Edificio:</strong> {{ $classroom->building->name ?? 'N/A' }}</p>
                    <p><strong>Ubicación:</strong> {{ $classroom->location ?? 'N/A' }}</p>
                    <p><strong>Piso:</strong> {{ $classroom->floor }}</p>
                    <p><strong>Ala/Sector:</strong> {{ $classroom->wing ?? 'N/A' }}</p>
                    <p><strong>Capacidad:</strong> {{ $classroom->capacity }} personas</p>
                    <p><strong>Tipo:</strong> 
                        @php
                            $types = [
                                'aula' => 'Aula',
                                'laboratorio' => 'Laboratorio', 
                                'auditorio' => 'Auditorio',
                                'sala_reuniones' => 'Sala de Reuniones',
                                'taller' => 'Taller'
                            ];
                        @endphp
                        {{ $types[$classroom->type] ?? $classroom->type }}
                    </p>
                    <p><strong>Activo:</strong> 
                        <span class="badge {{ $classroom->is_active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $classroom->is_active ? 'Sí' : 'No' }}
                        </span>
                    </p>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Recursos y Características</h5>
                    <p><strong>Recursos:</strong> 
                        {{ $classroom->resources_list }}
                    </p>
                    <p><strong>Características Especiales:</strong> {{ $classroom->special_features ?? 'Ninguna' }}</p>
                    <p><strong>Restricciones:</strong> {{ $classroom->restrictions ?? 'Ninguna' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('infraestructura.classrooms.edit', $classroom) }}" class="btn btn-warning">Editar</a>
        <a href="{{ route('infraestructura.classrooms.availabilities.index', $classroom) }}" class="btn btn-success">📅 Disponibilidades</a>
        <a href="{{ route('infraestructura.classrooms.index') }}" class="btn btn-secondary">Volver</a>
    </div>
</div>
@endsection