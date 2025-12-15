{{-- resources/views/visualization/horario-semestral.blade.php --}}
@extends('layouts.app')

@section('title', 'Horario Semestral Completo')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-11">

            <!-- ENCABEZADO -->
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="display-5 fw-bold text-primary mb-2">
                            <i class="fas fa-calendar-alt"></i> Horario Semestral Completo
                        </h1>
                        <p class="text-muted">Vista consolidada de todas las asignaciones del semestre</p>
                    </div>
                    <div>
                        <a href="{{ route('visualizacion.horario.malla-semestral') }}" class="btn btn-outline-primary">
                            <i class="fas fa-table"></i> Vista Malla Horaria
                        </a>
                    </div>
                </div>
            </div>

            <!-- TARJETA DE FILTROS -->
            <div class="card shadow-sm mb-4 border-0">
                <div class="card-header bg-light border-bottom">
                    <h5 class="mb-0 text-dark">
                        <i class="fas fa-filter"></i> Filtros Avanzados
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form method="GET" action="{{ route('visualizacion.horario.semestral') }}" class="row g-3">
                        <!-- Filtro: Carrera -->
                        <div class="col-md-6 col-lg-2">
                            <label for="career_id" class="form-label fw-semibold">Carrera</label>
                            <select name="career_id" id="career_id" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">-- Todas --</option>
                                @foreach($careers as $id => $name)
                                    @if(!empty($id))
                                        <option value="{{ $id }}" {{ request('career_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <!-- Filtro: Día -->
                        <div class="col-md-6 col-lg-2">
                            <label for="day" class="form-label fw-semibold">Día de la Semana</label>
                            <select name="day" id="day" class="form-select form-select-sm">
                                <option value="">-- Ninguno --</option>
                                <option value="monday" {{ request('day') == 'monday' ? 'selected' : '' }}>Lunes</option>
                                <option value="tuesday" {{ request('day') == 'tuesday' ? 'selected' : '' }}>Martes</option>
                                <option value="wednesday" {{ request('day') == 'wednesday' ? 'selected' : '' }}>Miércoles</option>
                                <option value="thursday" {{ request('day') == 'thursday' ? 'selected' : '' }}>Jueves</option>
                                <option value="friday" {{ request('day') == 'friday' ? 'selected' : '' }}>Viernes</option>
                                <option value="saturday" {{ request('day') == 'saturday' ? 'selected' : '' }}>Sábado</option>
                            </select>
                        </div>

                        <!-- Filtro: Grupo -->
                        <div class="col-md-6 col-lg-2">
                            <label for="group_id" class="form-label fw-semibold">Grupo de Estudiantes</label>
                            <select name="group_id" id="group_id" class="form-select form-select-sm">
                                <option value="">-- Ninguno --</option>
                                @foreach($groups as $id => $name)
                                    @if(!empty($id))
                                        <option value="{{ $id }}" {{ request('group_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <!-- Filtro: Profesor -->
                        <div class="col-md-6 col-lg-2">
                            <label for="teacher_id" class="form-label fw-semibold">Profesor</label>
                            <select name="teacher_id" id="teacher_id" class="form-select form-select-sm">
                                <option value="">-- Ninguno --</option>
                                @foreach($teachers as $id => $name)
                                    @if(!empty($id))
                                        <option value="{{ $id }}" {{ request('teacher_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <!-- Filtro: Salón -->
                        <div class="col-md-6 col-lg-2">
                            <label for="classroom_id" class="form-label fw-semibold">Salón</label>
                            <select name="classroom_id" id="classroom_id" class="form-select form-select-sm">
                                <option value="">-- Ninguno --</option>
                                @foreach($classrooms as $id => $name)
                                    @if(!empty($id))
                                        <option value="{{ $id }}" {{ request('classroom_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <!-- Filtro: Ubicación -->
                        <div class="col-md-6 col-lg-2">
                            <label for="location" class="form-label fw-semibold">Ubicación</label>
                            <select name="location" id="location" class="form-select form-select-sm">
                                <option value="">-- Ninguno --</option>
                                @foreach($locations as $loc)
                                    @if(!empty($loc))
                                        <option value="{{ $loc }}" {{ request('location') == $loc ? 'selected' : '' }}>{{ $loc }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <!-- Botones de acción -->
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="fas fa-search"></i> Aplicar Filtros
                            </button>
                            <a href="{{ route('visualizacion.horario.semestral') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-times"></i> Limpiar
                            </a>
                            <a href="{{ route('visualizacion.horario.semestral.export', request()->query()) }}" class="btn btn-danger btn-sm float-end">
                                <i class="fas fa-file-pdf"></i> Exportar PDF
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- ESTADÍSTICAS -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-body text-center">
                            <div class="h3 fw-bold text-primary mb-2">{{ $assignments->count() }}</div>
                            <small class="text-muted d-block">Asignaciones</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-body text-center">
                            <div class="h3 fw-bold text-success mb-2">{{ round($assignments->avg('score') * 100) }}%</div>
                            <small class="text-muted d-block">Calidad Promedio</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-body text-center">
                            <div class="h3 fw-bold text-warning mb-2">{{ $assignments->where('score', '>=', 0.8)->count() }}</div>
                            <small class="text-muted d-block">Excelentes (80%+)</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-body text-center">
                            <div class="h3 fw-bold text-danger mb-2">{{ $assignments->where('score', '<', 0.7)->count() }}</div>
                            <small class="text-muted d-block">Por Revisar (&lt;70%)</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- VISTA DE SEMANA ESTRUCTURADA -->
            <div class="card shadow-lg mb-4 border-0">
                <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);" class="card-header text-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-week"></i> Horarios por Día de la Semana
                    </h5>
                </div>
                <div class="card-body p-4">
                    @php
                        $diasEspanol = [
                            'monday'    => 'Lunes',
                            'tuesday'   => 'Martes',
                            'wednesday' => 'Miércoles',
                            'thursday'  => 'Jueves',
                            'friday'    => 'Viernes',
                            'saturday'  => 'Sábado'
                        ];
                        $colores = [
                            'monday'    => 'primary',
                            'tuesday'   => 'info',
                            'wednesday' => 'success',
                            'thursday'  => 'warning',
                            'friday'    => 'danger',
                            'saturday'  => 'secondary'
                        ];
                    @endphp
                    <div class="row">
                        @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'] as $day)
                            @php
                                $dayAssignments = $assignments->filter(function($a) use ($day) {
                                    return $a->day === $day;
                                });
                            @endphp
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="card border-{{ $colores[$day] }}">
                                    <div class="card-header bg-{{ $colores[$day] }} text-white py-2">
                                        <h6 class="mb-0">
                                            <i class="fas fa-clock"></i> {{ $diasEspanol[$day] }}
                                            <span class="badge bg-white text-{{ $colores[$day] }} float-end">{{ $dayAssignments->count() }}</span>
                                        </h6>
                                    </div>
                                    <div class="card-body p-3">
                                        @if($dayAssignments->isNotEmpty())
                                            <div style="max-height: 300px; overflow-y: auto;">
                                                @foreach($dayAssignments->sortBy('start_time') as $assign)
                                                    @php
                                                        $inicio = \Carbon\Carbon::parse($assign->start_time);
                                                        $fin = \Carbon\Carbon::parse($assign->end_time);
                                                        $horaInicio = str_replace(['AM','PM'], ['a.m.','p.m.'], $inicio->format('g:i A'));
                                                        $horaFin = str_replace(['AM','PM'], ['a.m.','p.m.'], $fin->format('g:i A'));
                                                    @endphp
                                                    <div class="mb-2 p-2 bg-light rounded border-left border-{{ $colores[$day] }} border-4">
                                                        <small class="d-block fw-bold text-dark">{{ $assign->group->name }}</small>
                                                        <small class="d-block text-muted">{{ $horaInicio }} - {{ $horaFin }}</small>
                                                        <small class="d-block text-muted">Prof: {{ $assign->teacher->first_name ?? 'N/A' }}</small>
                                                        <small class="d-block text-muted">Aula: {{ $assign->classroom->name }}</small>
                                                        @if($assign->score >= 0.8)
                                                            <span class="badge bg-success mt-1">{{ round($assign->score * 100) }}%</span>
                                                        @elseif($assign->score >= 0.7)
                                                            <span class="badge bg-warning mt-1">{{ round($assign->score * 100) }}%</span>
                                                        @else
                                                            <span class="badge bg-danger mt-1">{{ round($assign->score * 100) }}%</span>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="text-muted text-center mb-0">
                                                <em>Sin asignaciones</em>
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- TABLA DETALLADA -->
            <div class="card shadow-lg border-0">
                <div class="card-header bg-dark text-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-table"></i> Detalle Completo de Asignaciones
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th><strong>Grupo</strong></th>
                                    <th><strong>Profesor</strong></th>
                                    <th><strong>Salón</strong></th>
                                    <th><strong>Día</strong></th>
                                    <th><strong>Horario</strong></th>
                                    <th class="text-center"><strong>Calidad</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $diasEspanol = [
                                        'monday'    => 'Lunes',
                                        'tuesday'   => 'Martes',
                                        'wednesday' => 'Miércoles',
                                        'thursday'  => 'Jueves',
                                        'friday'    => 'Viernes',
                                        'saturday'  => 'Sábado'
                                    ];
                                @endphp

                                @forelse($assignments as $assignment)
                                    @php
                                        $inicio = \Carbon\Carbon::parse($assignment->start_time);
                                        $fin    = \Carbon\Carbon::parse($assignment->end_time);
                                        $diaEsp = $diasEspanol[$assignment->day] ?? 'Desconocido';
                                        $horaInicioDet = str_replace(['AM','PM'], ['a.m.','p.m.'], $inicio->format('g:i A'));
                                        $horaFinDet = str_replace(['AM','PM'], ['a.m.','p.m.'], $fin->format('g:i A'));
                                    @endphp
                                    <tr>
                                        <td>
                                            <strong class="text-primary">{{ $assignment->group->name }}</strong>
                                            @if($assignment->group->level ?? false)
                                                <br><small class="text-muted fst-italic">{{ $assignment->group->level }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($assignment->teacher)
                                                <strong>{{ $assignment->teacher->first_name }} {{ $assignment->teacher->last_name }}</strong>
                                            @else
                                                <em class="text-danger">Sin profesor asignado</em>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-info text-dark px-3 py-2 fs-6">
                                                {{ $assignment->classroom->name }}
                                            </span>
                                        </td>
                                        <td>
                                            <strong class="text-success">{{ $diaEsp }}</strong>
                                        </td>
                                        <td class="text-center font-monospace fw-bold text-dark">
                                            {{ $horaInicioDet }} → {{ $horaFinDet }}
                                        </td>
                                        <td class="text-center">
                                            @if($assignment->score >= 0.9)
                                                <span class="badge bg-success fs-6">EXCELENTE {{ round($assignment->score * 100) }}%</span>
                                            @elseif($assignment->score >= 0.8)
                                                <span class="badge bg-primary fs-6">MUY BUENO {{ round($assignment->score * 100) }}%</span>
                                            @elseif($assignment->score >= 0.7)
                                                <span class="badge bg-warning text-dark fs-6">Bueno {{ round($assignment->score * 100) }}%</span>
                                            @else
                                                <span class="badge bg-danger fs-6">Revisar {{ round($assignment->score * 100) }}%</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <h4 class="text-muted">No hay asignaciones con los filtros seleccionados</h4>
                                            <p>Intenta cambiar los filtros o <a href="{{ route('visualizacion.horario.semestral') }}">limpiar todos</a></p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
