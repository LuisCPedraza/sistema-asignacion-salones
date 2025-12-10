@extends('layouts.app')

@section('content')
<style>
    .reportes-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
    }

    .reportes-header {
        margin-bottom: 2.5rem;
    }

    .reportes-header h1 {
        font-size: 2.25rem;
        font-weight: bold;
        color: #111827;
        margin-bottom: 0.5rem;
    }

    .reportes-header p {
        color: #4b5563;
        font-size: 1.125rem;
    }

    .course-card {
        background: white;
        border-radius: 0.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        margin-bottom: 1.5rem;
        overflow: hidden;
        transition: box-shadow 0.3s ease;
    }

    .course-card:hover {
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .course-header {
        background: linear-gradient(to right, #2563eb, #1d4ed8);
        color: white;
        padding: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .course-info h3 {
        font-size: 1.5rem;
        font-weight: bold;
        margin-bottom: 0.5rem;
    }

    .course-info p {
        color: #eff6ff;
        font-size: 0.875rem;
        margin-top: 0.5rem;
    }

    .student-count {
        background: rgba(59, 130, 246, 0.5);
        color: #dbeafe;
        font-size: 0.875rem;
        font-weight: 600;
        padding: 0.5rem 1rem;
        border-radius: 9999px;
    }

    .course-actions {
        padding: 1.5rem;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
    }

    .report-link {
        display: block;
        padding: 1.5rem;
        border-radius: 0.5rem;
        border: 1px solid;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .report-link:hover {
        transform: translateY(-2px);
    }

    .report-link.asistencias {
        background: #eff6ff;
        border-color: #bfdbfe;
        color: #1e40af;
    }

    .report-link.asistencias:hover {
        background: #dbeafe;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.15);
    }

    .report-link.calificaciones {
        background: #f0fdf4;
        border-color: #bbf7d0;
        color: #15803d;
    }

    .report-link.calificaciones:hover {
        background: #dcfce7;
        box-shadow: 0 4px 12px rgba(34, 197, 94, 0.15);
    }

    .report-icon {
        width: 2rem;
        height: 2rem;
        margin-bottom: 0.75rem;
    }

    .report-title {
        font-size: 1.125rem;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .report-desc {
        font-size: 0.875rem;
        opacity: 0.75;
    }

    .empty-state {
        background: #fef3c7;
        border: 1px solid #fcd34d;
        border-radius: 0.5rem;
        padding: 2rem;
        text-align: center;
    }

    .empty-state-icon {
        width: 4rem;
        height: 4rem;
        margin: 0 auto 1rem;
        color: #f59e0b;
    }

    .empty-state p {
        color: #4b5563;
        font-size: 1.125rem;
    }
</style>

<div class="reportes-container">
    <!-- Header -->
    <div class="reportes-header">
        <h1>Reportes Académicos</h1>
        <p>Consulta datos de asistencias y calificaciones de tus cursos</p>
    </div>

    @if($assignments->isEmpty())
        <div class="empty-state">
            <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <p>No tienes cursos asignados aún.</p>
        </div>
    @else
        @foreach($assignments as $assignment)
            <div class="course-card">
                <!-- Card Header -->
                <div class="course-header">
                    <div class="course-info">
                        <h3>{{ $assignment->subject->nombre ?? 'Materia sin nombre' }}</h3>
                        <p>
                            <strong>{{ $assignment->group->nombre ?? 'Grupo sin nombre' }}</strong>
                            <span style="margin: 0 0.5rem;">•</span>
                            <span>Semestre {{ $assignment->group->semester->numero ?? '?' }}</span>
                        </p>
                        <p style="font-size: 0.8rem; color: #dbeafe; margin-top: 0.5rem;">
                            📅 Día: <strong>{{ ucfirst($assignment->day) }}</strong> 
                            <span style="margin: 0 0.5rem;">•</span>
                            🕐 {{ substr($assignment->start_time, 0, 5) }} - {{ substr($assignment->end_time, 0, 5) }}
                        </p>
                    </div>
                    <div class="student-count">
                        {{ $assignment->group->students()->count() }} estudiantes
                    </div>
                </div>

                <!-- Card Actions -->
                <div class="course-actions">
                    <!-- Asistencias Report -->
                    <a href="{{ route('profesor.reportes.asistencias', $assignment->id) }}" 
                       class="report-link asistencias">
                        <svg class="report-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                        <div class="report-title">Asistencias</div>
                        <div class="report-desc">Control y estadísticas de presencia</div>
                    </a>

                    <!-- Calificaciones Report -->
                    <a href="{{ route('profesor.reportes.actividades', $assignment->id) }}" 
                       class="report-link calificaciones">
                        <svg class="report-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <div class="report-title">Calificaciones</div>
                        <div class="report-desc">Actividades y desempeño académico</div>
                    </a>
                </div>
            </div>
        @endforeach
    @endif
</div>
@endsection
