@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>⚙️ Configuración de Reglas</h1>
        <a href="{{ route('academic.dashboard') }}" class="btn btn-secondary">← Volver</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Peso</th>
                        <th>Activa</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rules as $rule)
                    <tr>
                        <td>{{ $rule->name }}</td>
                        <td>{{ number_format($rule->weight * 100, 0) }}%</td>
                        <td><span class="badge {{ $rule->is_active ? 'bg-success' : 'bg-secondary' }}">{{ $rule->is_active ? 'Sí' : 'No' }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <form method="POST" action="{{ route('asignacion.actualizar-reglas') }}" class="mt-3">
                @csrf
                <button type="submit" class="btn btn-primary">Actualizar</button>
            </form>
        </div>
    </div>
</div>
@endsection