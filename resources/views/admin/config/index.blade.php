@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1>⚙️ Configuración del Sistema (HU19)</h1>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Configuración General</h5>
            <p>Esta funcionalidad está en desarrollo. Aquí se configurarán los parámetros generales del sistema.</p>
            <a href="{{ route('admin.config.edit') }}" class="btn btn-primary">Editar Configuración</a>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Volver al Dashboard</a>
        </div>
    </div>
</div>
@endsection