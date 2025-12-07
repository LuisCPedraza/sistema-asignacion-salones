@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1>📈 Reportes del Sistema (HU15)</h1>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Reporte de Utilización</h5>
                    <p class="card-text">Genera reportes de utilización de salones y recursos.</p>
                    <a href="{{ route('admin.reports.utilization') }}" class="btn btn-primary">Ver Reporte</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Estadísticas Generales</h5>
                    <p class="card-text">Estadísticas de uso del sistema y métricas.</p>
                    <a href="{{ route('admin.reports.statistics') }}" class="btn btn-primary">Ver Estadísticas</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection