@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>⚠️ Conflictos Detectados</h1>
        <a href="{{ route('academic.dashboard') }}" class="btn btn-secondary">← Volver</a>
    </div>

    @if(empty($conflicts))
        <div class="alert alert-success text-center">
            <h2>🎉 No hay conflictos</h2>
            <p>El horario está libre de solapamientos.</p>
        </div>
    @else
        <p class="mb-3">Total conflictos: {{ count($conflicts) }}</p>
        @foreach($conflicts as $conflictInfo)
            <div class="card mb-3 border-danger">
                <div class="card-header bg-danger text-white">
                    <h5>{{ $conflictInfo['assignment']->group->name ?? 'Grupo' }} - {{ ucfirst($conflictInfo['assignment']->day) }} {{ $conflictInfo['assignment']->start_time->format('H:i') }}</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @foreach($conflictInfo['conflicts'] as $conflict)
                            <li class="list-group-item">
                                {{ $conflict['group']['name'] ?? 'Grupo' }} en {{ $conflict['classroom']['name'] ?? 'Salón' }} ({{ $conflict['start_time']->format('H:i') }} - {{ $conflict['end_time']->format('H:i') }})
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endforeach
    @endif
</div>
@endsection