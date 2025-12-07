@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <h1>👨‍🏫 Gestión de Profesores (HU7)</h1>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <a href="{{ route('gestion-academica.teachers.create') }}" class="btn btn-primary mb-3">Crear Nuevo Profesor</a>
    
    <div class="card">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Especialidad</th>
                        <th>Email</th>
                        <th>Años Exp.</th>
                        <th>Grado Académico</th>
                        <th>Activo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($teachers as $teacher)
                        <tr>
                            <td>{{ $teacher->first_name }} {{ $teacher->last_name }}</td>
                            <td>{{ $teacher->specialty }}</td>
                            <td>{{ $teacher->email }}</td>
                            <td>{{ $teacher->years_experience }}</td>
                            <td>{{ $teacher->academic_degree ?? 'N/A' }}</td>
                            <td>
                                <span class="badge {{ $teacher->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $teacher->is_active ? 'Sí' : 'No' }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('gestion-academica.teachers.show', $teacher) }}" 
                                    class="btn btn-sm btn-info" title="Ver detalles">👁️</a>
                                    <a href="{{ route('gestion-academica.teachers.edit', $teacher) }}" 
                                    class="btn btn-sm btn-warning" title="Editar">✏️</a>
                                    {{-- ✅ NUEVO BOTÓN PARA DISPONIBILIDADES --}}
                                    <a href="{{ route('gestion-academica.teachers.availabilities.index', $teacher) }}" 
                                    class="btn btn-sm btn-success" title="Gestionar disponibilidades">📅</a>
                                    <form method="POST" action="{{ route('gestion-academica.teachers.destroy', $teacher) }}" 
                                        class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" 
                                                onclick="return confirm('¿Desactivar profesor?')" title="Desactivar">❌</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No hay profesores registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $teachers->links() }}
        </div>
    </div>
</div>
@endsection