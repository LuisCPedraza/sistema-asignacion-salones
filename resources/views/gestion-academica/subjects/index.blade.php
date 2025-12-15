@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">📚 Materias</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            ✓ {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($subjects->count())
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Código</th>
                                        <th>Nombre</th>
                                        <th>Carrera</th>
                                        <th>Semestre</th>
                                        <th>Créditos</th>
                                        <th>Estado</th>
                                        <th class="text-end">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($subjects as $subject)
                                        <tr>
                                            <td><code>{{ $subject->code }}</code></td>
                                            <td><strong>{{ $subject->name }}</strong></td>
                                            <td>{{ $subject->career->code ?? '—' }}</td>
                                            <td class="text-center">{{ $subject->semester_level }}</td>
                                            <td class="text-center">{{ $subject->credit_hours }}</td>
                                            <td>
                                                @if($subject->is_active)
                                                    <span class="badge bg-success">Activa</span>
                                                @else
                                                    <span class="badge bg-secondary">Inactiva</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('subjects.edit', $subject) }}" class="btn btn-sm btn-warning">
                                                    ✏️ Editar
                                                </a>
                                                <form action="{{ route('subjects.destroy', $subject) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" 
                                                            onclick="return confirm('¿Está seguro de eliminar esta materia?');">
                                                        🗑️ Eliminar
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($subjects->hasPages())
                            <nav aria-label="Paginación" class="mt-4">
                                <ul class="pagination justify-content-center">
                                    @if ($subjects->onFirstPage())
                                        <li class="page-item disabled"><span class="page-link">← Anterior</span></li>
                                    @else
                                        <li class="page-item"><a class="page-link" href="{{ $subjects->previousPageUrl() }}">← Anterior</a></li>
                                    @endif
                                    @foreach ($subjects->getUrlRange(1, $subjects->lastPage()) as $page => $url)
                                        @if ($page == $subjects->currentPage())
                                            <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                                        @else
                                            <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                                        @endif
                                    @endforeach
                                    @if ($subjects->hasMorePages())
                                        <li class="page-item"><a class="page-link" href="{{ $subjects->nextPageUrl() }}">Siguiente →</a></li>
                                    @else
                                        <li class="page-item disabled"><span class="page-link">Siguiente →</span></li>
                                    @endif
                                </ul>
                            </nav>
                        @endif
                    @else
                        <div class="alert alert-info">
                            No hay materias creadas aún.
                        </div>
                    @endif

                    <div class="d-flex gap-2 justify-content-end mt-4">
                        <a href="{{ route('subjects.create') }}" class="btn btn-success">
                            ➕ Nueva Materia
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
