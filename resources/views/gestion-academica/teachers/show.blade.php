@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <h1>Detalle: {{ $teacher->first_name }} {{ $teacher->last_name }}</h1>
    
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Información Personal</h5>
                    <p><strong>Nombre:</strong> {{ $teacher->first_name }} {{ $teacher->last_name }}</p>
                    <p><strong>Email:</strong> {{ $teacher->email }}</p>
                    <p><strong>Teléfono:</strong> {{ $teacher->phone ?? 'N/A' }}</p>
                    <p><strong>Especialidad Principal:</strong> {{ $teacher->specialty }}</p>
                    <p><strong>Otras Especialidades:</strong> 
                        {{ $teacher->specialties ? implode(', ', json_decode($teacher->specialties)) : 'Ninguna' }}
                    </p>
                    <p><strong>Años de Experiencia:</strong> {{ $teacher->years_experience }}</p>
                    <p><strong>Grado Académico:</strong> {{ $teacher->academic_degree ?? 'N/A' }}</p>
                    <p><strong>Activo:</strong> 
                        <span class="badge {{ $teacher->is_active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $teacher->is_active ? 'Sí' : 'No' }}
                        </span>
                    </p>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Información Profesional</h5>
                    <p><strong>Notas de Disponibilidad:</strong> {{ $teacher->availability_notes ?? 'Ninguna' }}</p>
                    <p><strong>Asignaciones Especiales:</strong> {{ $teacher->special_assignments ?? 'Ninguna' }}</p>
                    
                    <h6 class="mt-3">Hoja de Vida:</h6>
                    <div class="border p-3 bg-light">
                        {{ $teacher->curriculum ?? 'No registrada.' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('gestion-academica.teachers.edit', $teacher) }}" class="btn btn-warning">Editar</a>
        <a href="{{ route('gestion-academica.teachers.index') }}" class="btn btn-secondary">Volver</a>
    </div>
</div>
@endsection