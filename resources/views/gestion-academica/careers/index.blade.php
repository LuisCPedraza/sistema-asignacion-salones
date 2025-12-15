@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>📚 Gestión de Carreras</h1>
        <a href="{{ route('careers.create') }}" class="btn btn-primary">
            ➕ Nueva Carrera
        </a>
    </div>

    @if($message = session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            ✓ {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($message = session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            ✗ {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Duración (Semestres)</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($careers as $career)
                        <tr>
                            <td><strong>{{ $career->code }}</strong></td>
                            <td>{{ $career->name }}</td>
                            <td>{{ Str::limit($career->description, 50) }}</td>
                            <td>{{ $career->duration_semesters }}</td>
                            <td>
                                @if($career->is_active)
                                    <span class="badge bg-success">Activa</span>
                                @else
                                    <span class="badge bg-secondary">Inactiva</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('careers.edit', $career) }}" class="btn btn-sm btn-warning">
                                    ✎ Editar
                                </a>
                                <form method="POST" action="{{ route('careers.destroy', $career) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Confirmar eliminación?')">
                                        🗑️ Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                No hay carreras registradas
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Paginación -->
    @if($careers->hasPages())
        <nav aria-label="Paginación" class="mt-4">
            <ul class="pagination justify-content-center">
                @if ($careers->onFirstPage())
                    <li class="page-item disabled"><span class="page-link">← Anterior</span></li>
                @else
                    <li class="page-item"><a class="page-link" href="{{ $careers->previousPageUrl() }}">← Anterior</a></li>
                @endif
                @foreach ($careers->getUrlRange(1, $careers->lastPage()) as $page => $url)
                    @if ($page == $careers->currentPage())
                        <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                    @else
                        <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
                @if ($careers->hasMorePages())
                    <li class="page-item"><a class="page-link" href="{{ $careers->nextPageUrl() }}">Siguiente →</a></li>
                @else
                    <li class="page-item disabled"><span class="page-link">Siguiente →</span></li>
                @endif
            </ul>
        </nav>
    @endif
</div>
@endsection
