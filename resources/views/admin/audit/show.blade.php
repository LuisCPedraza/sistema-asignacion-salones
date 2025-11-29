@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1>📋 Detalle de Auditoría (HU18)</h1>

    <div class="card">
        <div class="card-body">
            <p>Detalle del evento de auditoría #{{ $id }}</p>
            <a href="{{ route('admin.audit.index') }}" class="btn btn-secondary">Volver</a>
        </div>
    </div>
</div>
@endsection