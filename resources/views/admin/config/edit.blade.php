@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1>✏️ Editar Configuración del Sistema (HU19)</h1>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.config.update') }}">
                @csrf @method('PUT')
                
                <div class="mb-3">
                    <label class="form-label">Nombre de la Institución:</label>
                    <input type="text" name="institution_name" class="form-control" value="Universidad Ejemplo">
                </div>

                <div class="mb-3">
                    <label class="form-label">Horario de Trabajo:</label>
                    <div class="row">
                        <div class="col-md-6">
                            <label>Hora de Inicio:</label>
                            <input type="time" name="work_start_time" class="form-control" value="08:00">
                        </div>
                        <div class="col-md-6">
                            <label>Hora de Fin:</label>
                            <input type="time" name="work_end_time" class="form-control" value="21:00">
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Días Laborables:</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="work_days[]" value="monday" checked>
                        <label class="form-check-label">Lunes</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="work_days[]" value="tuesday" checked>
                        <label class="form-check-label">Martes</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="work_days[]" value="wednesday" checked>
                        <label class="form-check-label">Miércoles</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="work_days[]" value="thursday" checked>
                        <label class="form-check-label">Jueves</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="work_days[]" value="friday" checked>
                        <label class="form-check-label">Viernes</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="work_days[]" value="saturday" checked>
                        <label class="form-check-label">Sábado</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-success">Guardar Configuración</button>
                <a href="{{ route('admin.config.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>
@endsection