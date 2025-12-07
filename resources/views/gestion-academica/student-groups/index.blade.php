@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <h1>🎓 Grupos de Estudiantes (HU4)</h1>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <a href="{{ route('gestion-academica.student-groups.create') }}" class="btn btn-primary mb-3">Crear Nuevo</a>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Nivel</th>
                <th># Estudiantes</th>
                <th>Características</th>
                <th>Activo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($groups as $group)
                <tr>
                    <td>{{ $group->name }}</td>
                    <td>{{ $group->level }}</td>
                    <td>{{ $group->student_count }}</td>
                    <td>{{ Str::limit($group->special_features ?? '', 50) }}</td>
                    <td><span class="badge {{ $group->is_active ? 'bg-success' : 'bg-secondary' }}">{{ $group->is_active ? 'Sí' : 'No' }}</span></td>
                    <td>
                        <a href="{{ route('gestion-academica.student-groups.show', $group) }}" class="btn btn-sm btn-info">Ver</a>
                        <a href="{{ route('gestion-academica.student-groups.edit', $group) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form method="POST" action="{{ route('gestion-academica.student-groups.destroy', $group) }}" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Desactivar?')">Desactivar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center">No hay grupos.</td></tr>
            @endforelse
        </tbody>
    </table>
    {{ $groups->links() }}
</div>
@endsection