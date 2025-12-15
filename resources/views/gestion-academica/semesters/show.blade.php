@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">📅 Semestres</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            ✓ {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($semesters->count())
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Carrera</th>
                                        <th>Número</th>
                                        <th>Descripción</th>
                                        <th>Estado</th>
                                        <th class="text-end">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($semesters as $semester)
                                        <tr>
                                            <td><strong>{{ $semester->career->code ?? '—' }} - {{ $semester->career->name ?? '—' }}</strong></td>
                                            <td class="text-center"><span class="badge bg-info">Semestre {{ $semester->number }}</span></td>
                                            <td>{{ $semester->description ?? '—' }}</td>
                                            <td>
                                                @if($semester->is_active)
                                                    <span class="badge bg-success">Activo</span>
                                                @else
                                                    <span class="badge bg-secondary">Inactivo</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('semesters.edit', $semester) }}" class="btn btn-sm btn-warning">
                                                    ✏️ Editar
                                                </a>
                                                <form action="{{ route('semesters.destroy', $semester) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" 
                                                            onclick="return confirm('¿Está seguro de eliminar este semestre?');">
                                                        🗑️ Eliminar
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $semesters->links() }}
                    @else
                        <div class="alert alert-info">
                            No hay semestres creados aún.
                        </div>
                    @endif

                    <div class="d-flex gap-2 justify-content-end mt-4">
                        <a href="{{ route('semesters.create') }}" class="btn btn-success">
                            ➕ Nuevo Semestre
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
