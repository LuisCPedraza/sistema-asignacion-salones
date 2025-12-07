@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <h1>🏢 Gestión de Salones (HU5)</h1>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <a href="{{ route('infraestructura.classrooms.create') }}" class="btn btn-primary mb-3">Crear Nuevo Salón</a>
    
    <div class="card">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Edificio</th>
                        <th>Capacidad</th>
                        <th>Tipo</th>
                        <th>Piso</th>
                        <th>Activo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($classrooms as $classroom)
                        <tr>
                            <td>{{ $classroom->code }}</td>
                            <td>{{ $classroom->name }}</td>
                            <td>{{ $classroom->building->name ?? 'N/A' }}</td>
                            <td>{{ $classroom->capacity }}</td>
                            <td>
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
                            </td>
                            <td>{{ $classroom->floor }}</td>
                            <td>
                                <span class="badge {{ $classroom->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $classroom->is_active ? 'Sí' : 'No' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('infraestructura.classrooms.show', $classroom) }}" class="btn btn-sm btn-info">Ver</a>
                                <a href="{{ route('infraestructura.classrooms.edit', $classroom) }}" class="btn btn-sm btn-warning">Editar</a>
                                <a href="{{ route('infraestructura.classrooms.availabilities.index', $classroom) }}" class="btn btn-sm btn-success">📅</a>
                                <form method="POST" action="{{ route('infraestructura.classrooms.destroy', $classroom) }}" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Desactivar salón?')">Desactivar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No hay salones registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $classrooms->links() }}
        </div>
    </div>
</div>
@endsection