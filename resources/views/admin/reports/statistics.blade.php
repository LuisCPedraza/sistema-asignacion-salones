@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1>📊 Estadísticas del Sistema (HU15)</h1>

    <div class="card">
        <div class="card-body">
            <p>Esta funcionalidad está en desarrollo. Aquí se mostrarán las estadísticas generales del sistema.</p>
            <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary">Volver</a>
        </div>
    </div>
</div>
@endsection