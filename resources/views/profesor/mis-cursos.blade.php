<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Cursos - Sistema de Asignación</title>
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
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
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
            padding: 0.5rem 1rem;
            text-decoration: none;
            border-radius: 5px;
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
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
            border-left: 4px solid #f59e0b;
        }
        .page-header h1 {
            color: #1e293b;
            margin-bottom: 0.5rem;
        }
        .page-header p {
            color: #64748b;
        }
        .stats-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .stat-box {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            text-align: center;
        }
        .stat-box .number {
            font-size: 2rem;
            font-weight: bold;
            color: #f59e0b;
            margin-bottom: 0.25rem;
        }
        .stat-box .label {
            color: #64748b;
            font-size: 0.9rem;
        }
        .courses-grid {
            display: grid;
            gap: 2rem;
        }
        .course-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: all 0.3s;
        }
        .course-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        .course-header {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
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
        .course-stats {
            display: flex;
            gap: 2rem;
            margin-bottom: 1.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid #e2e8f0;
        }
        .course-stat {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .course-stat-icon {
            font-size: 1.5rem;
        }
        .course-stat-value {
            font-weight: bold;
            color: #1e293b;
        }
        .course-stat-label {
            color: #64748b;
            font-size: 0.85rem;
        }
        .groups-list {
            display: grid;
            gap: 1rem;
        }
        .group-item {
            background: #f8fafc;
            padding: 1rem;
            border-radius: 8px;
            border-left: 3px solid #f59e0b;
        }
        .group-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.75rem;
        }
        .group-name {
            font-weight: 600;
            color: #1e293b;
        }
        .group-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            font-size: 0.9rem;
        }
        .info-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #475569;
        }
        .info-icon {
            color: #f59e0b;
        }
        .schedules-list {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #e2e8f0;
        }
        .schedules-title {
            font-weight: 600;
            color: #475569;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }
        .schedule-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #64748b;
            font-size: 0.85rem;
            margin-bottom: 0.25rem;
        }
        .btn-details {
            background: #f59e0b;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.3s;
            display: inline-block;
        }
        .btn-details:hover {
            background: #d97706;
        }
        .btn-back {
            background: #64748b;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 2rem;
            transition: all 0.3s;
        }
        .btn-back:hover {
            background: #475569;
        }
        .empty-state {
            background: white;
            padding: 3rem;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
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
        @media (max-width: 768px) {
            .course-stats {
                flex-direction: column;
                gap: 1rem;
            }
            .group-info {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">📚 Sistema de Asignación de Salones</div>
        <div class="user-info">
            <span>{{ auth()->user()->name }} ({{ auth()->user()->role->name ?? 'Profesor' }})</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">Cerrar Sesión</button>
            </form>
        </div>
    </div>

    <div class="container">
        <div class="breadcrumb">
            <a href="{{ route('profesor.dashboard') }}">Dashboard</a> / <span>Mis Cursos</span>
        </div>

        <a href="{{ route('profesor.dashboard') }}" class="btn-back">← Volver al Dashboard</a>

        <div class="page-header">
            <h1>📖 Mis Cursos Asignados</h1>
            <p>Revisa todos los cursos y grupos que tienes asignados en este período académico.</p>
        </div>

        @if(session('error'))
            <div style="background: #fee2e2; color: #991b1b; padding: 1rem; border-radius: 8px; margin-bottom: 2rem;">
                {{ session('error') }}
            </div>
        @endif

        <div class="stats-summary">
            <div class="stat-box">
                <div class="number">{{ $cursos->count() }}</div>
                <div class="label">Materias Asignadas</div>
            </div>
            <div class="stat-box">
                <div class="number">{{ $totalAssignments }}</div>
                <div class="label">Total de Grupos</div>
            </div>
            <div class="stat-box">
                <div class="number">{{ $cursos->sum('total_students') }}</div>
                <div class="label">Total de Estudiantes</div>
            </div>
        </div>

        @if($cursos->isEmpty())
            <div class="empty-state">
                <div class="empty-state-icon">📭</div>
                <h3>No tienes cursos asignados</h3>
                <p>Actualmente no hay materias asignadas a tu perfil. Contacta al coordinador académico para más información.</p>
            </div>
        @else
            <div class="courses-grid">
                @foreach($cursos as $curso)
                    <div class="course-card">
                        <div class="course-header">
                            <h2>{{ $curso['subject']->name ?? 'Materia sin nombre' }}</h2>
                            <div class="subject-code">Código: {{ $curso['subject']->code ?? 'N/A' }}</div>
                        </div>
                        <div class="course-body">
                            <div class="course-stats">
                                <div class="course-stat">
                                    <div class="course-stat-icon">👥</div>
                                    <div>
                                        <div class="course-stat-value">{{ $curso['total_groups'] }}</div>
                                        <div class="course-stat-label">Grupos</div>
                                    </div>
                                </div>
                                <div class="course-stat">
                                    <div class="course-stat-icon">🎓</div>
                                    <div>
                                        <div class="course-stat-value">{{ $curso['total_students'] }}</div>
                                        <div class="course-stat-label">Estudiantes</div>
                                    </div>
                                </div>
                            </div>

                            <div class="groups-list">
                                @foreach($curso['groups'] as $groupData)
                                    <div class="group-item">
                                        <div class="group-header">
                                            <div class="group-name">
                                                {{ $groupData['group']->name ?? 'Grupo sin nombre' }}
                                            </div>
                                        </div>
                                        <div class="group-info">
                                            <div class="info-item">
                                                <span class="info-icon">🏢</span>
                                                <span>{{ $groupData['classroom']->building->name ?? 'Sin edificio' }} - {{ $groupData['classroom']->code ?? 'Sin aula' }}</span>
                                            </div>
                                            <div class="info-item">
                                                <span class="info-icon">📚</span>
                                                <span>{{ $groupData['group']->semester->name ?? 'Sin semestre' }}</span>
                                            </div>
                                            <div class="info-item">
                                                <span class="info-icon">🎓</span>
                                                <span>{{ $groupData['student_count'] }} estudiantes</span>
                                            </div>
                                            @if($groupData['group']->career)
                                            <div class="info-item">
                                                <span class="info-icon">🎯</span>
                                                <span>{{ $groupData['group']->career->name }}</span>
                                            </div>
                                            @endif
                                        </div>

                                        @if($groupData['day'] && $groupData['start_time'] && $groupData['end_time'])
                                            <div class="schedules-list">
                                                <div class="schedules-title">📅 Horario:</div>
                                                <div class="schedule-item">
                                                    🕐 {{ ucfirst($groupData['day']) }}: {{ substr($groupData['start_time'], 0, 5) }} - {{ substr($groupData['end_time'], 0, 5) }}
                                                </div>
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
