@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <!-- Encabezado -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h1 class="h3 mb-1">
                        <i class="fas fa-table"></i> Malla Horaria Semestral
                    </h1>
                    <p class="text-muted mb-0">Vista tipo cuadrícula - Bloques horarios por día</p>
                </div>
                <div>
                    <a href="{{ route('visualizacion.horario.semestral') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-list"></i> Vista con Filtros
                    </a>
                </div>
            </div>

            <!-- Selectores de Carrera, Semestre y Grupo -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('visualizacion.horario.malla-semestral') }}" class="row g-3">
                        <!-- Selector de Carrera -->
                        <div class="col-md-4">
                            <label for="career_id" class="form-label fw-semibold">
                                <i class="fas fa-graduation-cap"></i> Carrera
                            </label>
                            <select name="career_id" id="career_id" class="form-select" onchange="this.form.submit()">
                                <option value="">-- Seleccionar Carrera --</option>
                                @foreach($careers as $career)
                                    <option value="{{ $career->id }}" {{ $selectedCareeId == $career->id ? 'selected' : '' }}>
                                        {{ $career->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Selector de Semestre -->
                        <div class="col-md-4">
                            <label for="semester_id" class="form-label fw-semibold">
                                <i class="fas fa-hourglass"></i> Semestre
                            </label>
                            <select name="semester_id" id="semester_id" class="form-select" onchange="this.form.submit()" {{ empty($semesters) ? 'disabled' : '' }}>
                                <option value="">-- Seleccionar Semestre --</option>
                                @foreach($semesters as $semester)
                                    <option value="{{ $semester->id }}" {{ $selectedSemesterId == $semester->id ? 'selected' : '' }}>
                                        Semestre {{ $semester->number }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Selector de Grupo -->
                        <div class="col-md-4">
                            <label for="group_id" class="form-label fw-semibold">
                                <i class="fas fa-users"></i> Grupo
                            </label>
                            <select name="group_id" id="group_id" class="form-select" onchange="this.form.submit()" {{ empty($groups) ? 'disabled' : '' }}>
                                <option value="">-- Seleccionar Grupo --</option>
                                @foreach($groups as $group)
                                    <option value="{{ $group->id }}" {{ $selectedGroupId == $group->id ? 'selected' : '' }}>
                                        {{ $group->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        @if($selectedGroupId)
                            <div class="col-12">
                                <a href="{{ route('visualizacion.horario.malla-semestral') }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-times"></i> Limpiar Filtros
                                </a>
                            </div>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Malla Horaria -->
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-primary text-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-alt"></i> 
                        @if($selectedGroupId)
                            Horario - {{ optional(\App\Modules\GestionAcademica\Models\StudentGroup::find($selectedGroupId))->name }}
                        @elseif($selectedSemesterId)
                            Horarios - Semestre {{ optional(\App\Models\Semester::find($selectedSemesterId))->number }}
                        @elseif($selectedCareeId)
                            Horarios - {{ optional(\App\Models\Career::find($selectedCareeId))->name }}
                        @else
                            Malla Horaria Semestral
                        @endif
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm mb-0" style="table-layout: fixed;">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th class="text-center align-middle" style="width: 120px; background-color: #f8f9fa;">
                                        <strong>BLOQUE</strong>
                                    </th>
                                    @php
                                        $diasEspanol = [
                                            'monday' => 'LUNES',
                                            'tuesday' => 'MARTES',
                                            'wednesday' => 'MIÉRCOLES',
                                            'thursday' => 'JUEVES',
                                            'friday' => 'VIERNES',
                                            'saturday' => 'SÁBADO'
                                        ];
                                    @endphp
                                    @foreach($days as $day)
                                        <th class="text-center align-middle" style="background-color: #e9ecef;">
                                            <strong>{{ $diasEspanol[$day] }}</strong>
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalAssignments = 0;
                                    foreach($schedule as $blockSchedules) {
                                        foreach($blockSchedules as $assignmentsInDay) {
                                            if(is_array($assignmentsInDay)) {
                                                $totalAssignments += count($assignmentsInDay);
                                            } elseif($assignmentsInDay) {
                                                $totalAssignments++;
                                            }
                                        }
                                    }
                                @endphp
                                
                                @if($totalAssignments === 0 && $selectedGroupId)
                                    <tr>
                                        <td colspan="7" class="text-center p-4 text-muted">
                                            <p><i class="fas fa-info-circle"></i> No hay asignaciones para este grupo</p>
                                            <small>Debug: Group ID = {{ $selectedGroupId }}, Schedule items = {{ count($schedule) }}</small>
                                        </td>
                                    </tr>
                                @else
                                @foreach($timeBlocks as $block)
                                    <tr>
                                        <!-- Columna del Bloque Horario -->
                                        <td class="text-center align-middle bg-light" style="vertical-align: middle;">
                                            <div class="fw-bold">{{ $block['name'] }}</div>
                                            <small class="text-muted">{{ $block['start'] }} - {{ $block['end'] }}</small>
                                        </td>

                                        <!-- Columnas de los días -->
                                        @foreach($days as $day)
                                            <td class="p-2" style="min-height: 140px; vertical-align: top; overflow-y: auto;">
                                                @php
                                                    $assignmentsInCell = $schedule[$block['id']][$day] ?? [];
                                                @endphp

                                                @forelse($assignmentsInCell as $assignment)
                                                    <div class="border rounded p-2 mb-2 position-relative" 
                                                         style="background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%); border-left: 4px solid #667eea !important; font-size: 0.8rem;">
                                                        
                                                        <!-- Materia -->
                                                        @if($assignment->subject)
                                                            <div class="badge bg-info mb-1" style="font-size: 0.65rem;">
                                                                {{ $assignment->subject->code }}
                                                            </div>
                                                            <div class="fw-bold text-primary mb-1" style="font-size: 0.8rem; line-height: 1.2;">
                                                                {{ Str::limit($assignment->subject->name, 20) }}
                                                            </div>
                                                        @else
                                                            <div class="fw-bold text-primary mb-1" style="font-size: 0.8rem; line-height: 1.2;">
                                                                {{ Str::limit($assignment->notes ?? $assignment->group->name ?? 'N/A', 20) }}
                                                            </div>
                                                        @endif

                                                        <!-- Horario -->
                                                        <small class="d-block text-dark mb-1" style="font-size: 0.7rem;">
                                                            <i class="fas fa-clock"></i> 
                                                            {{ \Carbon\Carbon::parse($assignment->start_time)->format('H:i') }}-{{ \Carbon\Carbon::parse($assignment->end_time)->format('H:i') }}
                                                        </small>

                                                        <!-- Profesor -->
                                                        <small class="d-block text-secondary mb-1" style="font-size: 0.7rem;">
                                                            <i class="fas fa-user"></i> 
                                                            {{ Str::limit($assignment->teacher->first_name ?? 'N/A', 12) }}
                                                        </small>

                                                        <!-- Salón -->
                                                        <small class="d-block text-info" style="font-size: 0.7rem;">
                                                            <i class="fas fa-door-open"></i> 
                                                            {{ $assignment->classroom->name ?? 'N/A' }}
                                                        </small>

                                                        <!-- Badge de Calidad -->
                                                        <div class="position-absolute" style="top: 2px; right: 2px;">
                                                            @if($assignment->score >= 0.8)
                                                                <span class="badge bg-success" style="font-size: 0.65rem;">{{ round($assignment->score * 100) }}%</span>
                                                            @elseif($assignment->score >= 0.7)
                                                                <span class="badge bg-warning" style="font-size: 0.65rem;">{{ round($assignment->score * 100) }}%</span>
                                                            @else
                                                                <span class="badge bg-danger" style="font-size: 0.65rem;">{{ round($assignment->score * 100) }}%</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @empty
                                                    <div class="text-center text-muted" style="min-height: 60px; display: flex; align-items: center; justify-content: center;">
                                                        <small><em>---</em></small>
                                                    </div>
                                                @endforelse
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Leyenda -->
            <div class="card mt-4 shadow-sm">
                <div class="card-body">
                    <h6 class="card-title mb-3"><i class="fas fa-info-circle"></i> Leyenda de Calidad</h6>
                    <div class="row">
                        <div class="col-md-4">
                            <span class="badge bg-success">80% - 100%</span> Excelente calidad
                        </div>
                        <div class="col-md-4">
                            <span class="badge bg-warning">70% - 79%</span> Buena calidad
                        </div>
                        <div class="col-md-4">
                            <span class="badge bg-danger">0% - 69%</span> Por revisar
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    }
    
    .table-bordered td, .table-bordered th {
        border: 2px solid #dee2e6 !important;
    }
    
    .sticky-top {
        position: sticky;
        top: 0;
        z-index: 10;
    }
    
    /* Hacer que las celdas con asignaciones resalten al pasar el mouse */
    tbody td:hover {
        background-color: #f8f9fa;
    }
    
    /* Responsive: reducir tamaño de fuente en pantallas pequeñas */
    @media (max-width: 768px) {
        .table {
            font-size: 0.75rem;
        }
        
        tbody td {
            height: 120px !important;
        }
    }
</style>
@endsection
