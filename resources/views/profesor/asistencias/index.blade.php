<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Control de Asistencias - Sistema de Asignación</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: #f8fafc;
            color: #334155;
        }
        .header {
            background: linear-gradient(135deg, #f59e0b 0%, #ea580c 100%);
            color: white;
            padding: 1.25rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 20px rgba(245, 158, 11, 0.3);
        }
        .logo {
            font-size: 1.5rem;
            font-weight: bold;
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .btn-logout {
            background: rgba(255,255,255,0.2);
            color: white;
            padding: 0.625rem 1.25rem;
            text-decoration: none;
            border-radius: 8px;
            border: 1px solid rgba(255,255,255,0.3);
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-logout:hover {
            background: rgba(255,255,255,0.3);
        }
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }
        .breadcrumb {
            margin-bottom: 2rem;
            font-size: 0.9rem;
        }
        .breadcrumb a {
            color: #f59e0b;
            text-decoration: none;
        }
        .breadcrumb a:hover {
            text-decoration: underline;
        }
        .page-header {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            margin-bottom: 2rem;
            border-left: 5px solid #10b981;
        }
        .page-header h1 {
            color: #1e293b;
            margin-bottom: 0.5rem;
            font-size: 2rem;
        }
        .page-header p {
            color: #64748b;
        }
        .btn-back {
            background: #64748b;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 2rem;
            transition: all 0.3s;
        }
        .btn-back:hover {
            background: #475569;
        }
        .success-message {
            background: #d1fae5;
            border: 1px solid #10b981;
            color: #065f46;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 2rem;
        }
        .courses-grid {
            display: grid;
            gap: 2rem;
        }
        .course-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            overflow: hidden;
            transition: all 0.3s;
        }
        .course-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        .course-header {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 1.5rem;
        }
        .course-header h2 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }
        .course-header .subject-code {
            opacity: 0.9;
            font-size: 0.9rem;
        }
        .course-body {
            padding: 1.5rem;
        }
        .groups-list {
            display: grid;
            gap: 1rem;
        }
        .group-item {
            background: #f8fafc;
            padding: 1.25rem;
            border-radius: 8px;
            border-left: 3px solid #10b981;
        }
        .group-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        .group-name {
            font-weight: 600;
            color: #1e293b;
            font-size: 1.1rem;
        }
        .group-actions {
            display: flex;
            gap: 0.75rem;
        }
        .btn-attendance {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            padding: 0.625rem 1.25rem;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .btn-attendance:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }
        .btn-history {
            background: #3b82f6;
            color: white;
            padding: 0.625rem 1.25rem;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .btn-history:hover {
            background: #2563eb;
            transform: translateY(-2px);
        }
        .group-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            font-size: 0.9rem;
            color: #475569;
        }
        .info-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .info-icon {
            color: #10b981;
        }
        .schedule-info {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #e2e8f0;
            color: #64748b;
            font-size: 0.9rem;
        }
        .empty-state {
            background: white;
            padding: 3rem;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }
        .empty-state-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }
        .empty-state h3 {
            color: #1e293b;
            margin-bottom: 0.5rem;
        }
        .empty-state p {
            color: #64748b;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">✅ Control de Asistencias</div>
        <div class="user-info">
            <span>{{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">Cerrar Sesión</button>
            </form>
        </div>
    </div>

    <div class="container">
        <div class="breadcrumb">
            <a href="{{ route('profesor.dashboard') }}">Dashboard</a> / <span>Control de Asistencias</span>
        </div>

        <a href="{{ route('profesor.dashboard') }}" class="btn-back">← Volver al Dashboard</a>

        <div class="page-header">
            <h1>📋 Control de Asistencias</h1>
            <p>Selecciona un grupo para registrar la asistencia de hoy o consultar el historial.</p>
        </div>

        @if(session('success'))
            <div class="success-message">
                ✅ {{ session('success') }}
            </div>
        @endif

        @if($cursos->isEmpty())
            <div class="empty-state">
                <div class="empty-state-icon">📭</div>
                <h3>No tienes cursos asignados</h3>
                <p>Actualmente no hay cursos disponibles para tomar asistencia.</p>
            </div>
        @else
            <div class="courses-grid">
                @foreach($cursos as $curso)
                    <div class="course-card">
                        <div class="course-header">
                            <h2 style="display:flex; gap:0.5rem; align-items:center; flex-wrap:wrap;">
                                {{ $curso['subject']->name ?? 'Materia sin nombre' }}
                                @if(!empty($curso['subject']->code))
                                    <span style="background: rgba(255,255,255,0.15); padding:0.15rem 0.5rem; border-radius:6px; font-size:0.85rem;">Código: {{ $curso['subject']->code }}</span>
                                @endif
                            </h2>
                            <div class="subject-code" style="display:flex; gap:1rem; flex-wrap:wrap;">
                                @if(!empty($curso['career']))
                                    <span>🎯 {{ $curso['career']->name ?? 'Carrera no definida' }}</span>
                                @endif
                                @if(!empty($curso['subject']->semester_level))
                                    <span>📅 Nivel/Semestre: {{ $curso['subject']->semester_level }}</span>
                                @endif
                                @if(!empty($curso['subject']->credit_hours))
                                    <span>⏱️ Créditos: {{ $curso['subject']->credit_hours }}</span>
                                @endif
                                <span>👥 {{ $curso['total_students'] }} estudiante(s)</span>
                                <span>📚 {{ count($curso['groups']) }} grupo(s)</span>
                            </div>
                        </div>
                        <div class="course-body">
                            <div class="groups-list">
                                @foreach($curso['groups'] as $groupData)
                                    <div class="group-item">
                                        <div class="group-header">
                                            <div class="group-name" style="display:flex; flex-direction:column; gap:0.15rem;">
                                                <div>{{ $groupData['group']->name ?? 'Grupo sin nombre' }}</div>
                                                <div style="font-weight:500; font-size:0.9rem; opacity:0.95;">
                                                    @php
                                                        $semesterName = $groupData['semester']->name ?? 'Semestre no definido';
                                                        $careerName = $groupData['semester']->career->name ?? $curso['career']->name ?? null;
                                                        $turno = ($groupData['group']->group_type ?? 'A') === 'B' ? 'Nocturno' : 'Diurno';
                                                    @endphp
                                                    {{ $semesterName }}
                                                    @if($careerName)
                                                        · {{ $careerName }}
                                                    @endif
                                                    · {{ $turno }}
                                                </div>
                                            </div>
                                            <div class="group-actions">
                                                <a href="{{ route('profesor.asistencias.tomar', $groupData['assignment_id']) }}" class="btn-attendance">
                                                    ✅ Tomar Asistencia
                                                </a>
                                                <a href="{{ route('profesor.asistencias.historial', $groupData['assignment_id']) }}" class="btn-history">
                                                    📊 Historial
                                                </a>
                                            </div>
                                        </div>
                                        <div class="group-info">
                                            <div class="info-item">
                                                <span class="info-icon">🏢</span>
                                                <span>
                                                    {{ $groupData['classroom']->code ?? 'Sin aula' }}
                                                    @if(!empty($groupData['building']))
                                                        · {{ $groupData['building']->name ?? '' }}
                                                    @endif
                                                </span>
                                            </div>
                                            <div class="info-item">
                                                <span class="info-icon">🎓</span>
                                                <span>{{ $groupData['student_count'] }} estudiantes</span>
                                            </div>
                                            <div class="info-item">
                                                <span class="info-icon">📚</span>
                                                <span>{{ $groupData['semester']->name ?? 'Sin semestre' }}</span>
                                            </div>
                                        </div>
                                        @if($groupData['day'] && $groupData['start_time'])
                                            <div class="schedule-info">
                                                🕐 {{ ucfirst($groupData['day']) }}: {{ substr($groupData['start_time'], 0, 5) }} - {{ substr($groupData['end_time'], 0, 5) }}
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</body>
</html>
