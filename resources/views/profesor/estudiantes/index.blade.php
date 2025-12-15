<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Estudiantes - Sistema de Asignación</title>
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
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
            color: white;
            padding: 1.25rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 20px rgba(139, 92, 246, 0.3);
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
            color: #8b5cf6;
            text-decoration: none;
        }
        .breadcrumb a:hover {
            text-decoration: underline;
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
        .page-header {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            margin-bottom: 2rem;
            border-left: 5px solid #8b5cf6;
        }
        .page-header h1 {
            color: #1e293b;
            margin-bottom: 0.5rem;
            font-size: 2rem;
        }
        .page-header p {
            color: #64748b;
        }
        .stats-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .stat-box {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            text-align: center;
        }
        .stat-box .icon {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }
        .stat-box .number {
            font-size: 2rem;
            font-weight: bold;
            color: #8b5cf6;
            margin-bottom: 0.25rem;
        }
        .stat-box .label {
            color: #64748b;
            font-size: 0.9rem;
        }
        .success-message {
            background: #d1fae5;
            border: 1px solid #10b981;
            color: #065f46;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 2rem;
        }
        .error-message {
            background: #fee2e2;
            border: 1px solid #ef4444;
            color: #991b1b;
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
        }
        .course-header {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
            color: white;
            padding: 1.5rem;
        }
        .course-header h2 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }
        .course-stats {
            display: flex;
            gap: 2rem;
            font-size: 0.9rem;
            opacity: 0.9;
        }
        .course-body {
            padding: 1.5rem;
        }
        .groups-section {
            display: grid;
            gap: 1.5rem;
        }
        .group-card {
            background: #f8fafc;
            border-radius: 8px;
            overflow: hidden;
            border: 2px solid #e2e8f0;
        }
        .group-header {
            background: #8b5cf6;
            color: white;
            padding: 1rem 1.25rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .group-name {
            font-weight: 600;
            font-size: 1.1rem;
        }
        .btn-add-student {
            background: rgba(255,255,255,0.2);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.9rem;
            border: 1px solid rgba(255,255,255,0.3);
            transition: all 0.3s;
        }
        .btn-add-student:hover {
            background: rgba(255,255,255,0.3);
        }
        .students-table {
            width: 100%;
            border-collapse: collapse;
        }
        .students-table thead {
            background: #f1f5f9;
        }
        .students-table th {
            padding: 0.875rem;
            text-align: left;
            font-weight: 600;
            color: #475569;
            font-size: 0.9rem;
        }
        .students-table td {
            padding: 0.875rem;
            border-bottom: 1px solid #e2e8f0;
            color: #334155;
        }
        .students-table tbody tr:hover {
            background: #faf5ff;
        }
        .student-codigo {
            font-family: 'Courier New', monospace;
            background: #f1f5f9;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.85rem;
        }
        .student-estado {
            padding: 0.375rem 0.75rem;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .estado-activo {
            background: #d1fae5;
            color: #065f46;
        }
        .estado-inactivo {
            background: #fee2e2;
            color: #991b1b;
        }
        .estado-retirado {
            background: #e2e8f0;
            color: #475569;
        }
        .actions {
            display: flex;
            gap: 0.5rem;
        }
        .btn-edit {
            background: #3b82f6;
            color: white;
            padding: 0.5rem 0.875rem;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.85rem;
            transition: all 0.3s;
        }
        .btn-edit:hover {
            background: #2563eb;
        }
        .btn-delete {
            background: #ef4444;
            color: white;
            padding: 0.5rem 0.875rem;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-size: 0.85rem;
            transition: all 0.3s;
        }
        .btn-delete:hover {
            background: #dc2626;
        }
        .empty-group {
            padding: 2rem;
            text-align: center;
            color: #64748b;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">🎓 Mis Estudiantes</div>
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
            <a href="{{ route('profesor.dashboard') }}">Dashboard</a> / <span>Mis Estudiantes</span>
        </div>

        <a href="{{ route('profesor.dashboard') }}" class="btn-back">← Volver al Dashboard</a>

        <div class="page-header">
            <h1>🎓 Gestión de Estudiantes</h1>
            <p>Administra los estudiantes de todos tus grupos. Puedes agregar, editar y consultar la información de cada estudiante.</p>
        </div>

        @if(session('success'))
            <div class="success-message">
                ✅ {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="error-message">
                ⚠️ {{ session('error') }}
            </div>
        @endif

        <div class="stats-summary">
            <div class="stat-box">
                <div class="icon">👥</div>
                <div class="number">{{ $totalEstudiantes }}</div>
                <div class="label">Total Estudiantes</div>
            </div>
            <div class="stat-box">
                <div class="icon">📚</div>
                <div class="number">{{ count($cursos) }}</div>
                <div class="label">Materias</div>
            </div>
            <div class="stat-box">
                <div class="icon">🏷️</div>
                <div class="number">{{ collect($cursos)->sum(fn($c) => count($c['groups'])) }}</div>
                <div class="label">Grupos</div>
            </div>
        </div>

        @if(empty($cursos))
            <div class="empty-group">
                <h3>No tienes cursos asignados</h3>
                <p>Actualmente no hay cursos disponibles para gestionar estudiantes.</p>
            </div>
        @else
            <div class="courses-grid">
                @foreach($cursos as $curso)
                    <div class="course-card">
                        <div class="course-header">
                            <h2 style="display:flex; align-items:center; gap:0.5rem; flex-wrap:wrap;">
                                {{ $curso['subject']->name ?? 'Materia sin nombre' }}
                                @if(!empty($curso['subject']->code))
                                    <span style="background: rgba(255,255,255,0.15); padding:0.15rem 0.5rem; border-radius:6px; font-size:0.85rem;">Código: {{ $curso['subject']->code }}</span>
                                @endif
                            </h2>
                            <div class="course-stats" style="flex-wrap:wrap;">
                                <span>📚 {{ count($curso['groups']) }} grupo(s)</span>
                                <span>👥 {{ $curso['total_students'] }} estudiante(s)</span>
                                @if(!empty($curso['career']))
                                    <span>🎯 {{ $curso['career']->name ?? 'Carrera no definida' }}</span>
                                @endif
                                @if(!empty($curso['subject']->semester_level))
                                    <span>📅 Nivel/Semestre: {{ $curso['subject']->semester_level }}</span>
                                @endif
                                @if(!empty($curso['subject']->credit_hours))
                                    <span>⏱️ Créditos: {{ $curso['subject']->credit_hours }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="course-body">
                            <div class="groups-section">
                                @foreach($curso['groups'] as $groupData)
                                    <div class="group-card">
                                        <div class="group-header">
                                            <div class="group-name" style="display:flex; flex-direction:column; gap:0.15rem;">
                                                <div>
                                                    {{ $groupData['group']->name ?? 'Grupo sin nombre' }}
                                                    <span style="font-weight: normal; font-size: 0.9rem;">
                                                        ({{ $groupData['student_count'] }} estudiantes)
                                                    </span>
                                                </div>
                                                <div style="font-weight:500; font-size:0.9rem; opacity:0.95;">
                                                    @php
                                                        $semesterName = $groupData['semester']->name ?? null;
                                                        $careerName = $groupData['semester']->career->name ?? $curso['career']->name ?? null;
                                                        $turno = $groupData['group']->group_type === 'B' ? 'Nocturno' : 'Diurno';
                                                    @endphp
                                                    {{ $semesterName ? "Semestre: $semesterName" : 'Semestre no definido' }}
                                                    @if($careerName)
                                                        · {{ $careerName }}
                                                    @endif
                                                    · {{ $turno }}
                                                </div>
                                            </div>
                                            <a href="{{ route('profesor.estudiantes.create', ['assignment_id' => $groupData['assignment_id']]) }}" 
                                               class="btn-add-student">
                                                ➕ Agregar Estudiante
                                            </a>
                                        </div>
                                        @if($groupData['students']->isEmpty())
                                            <div class="empty-group">
                                                <p>📭 No hay estudiantes registrados en este grupo.</p>
                                            </div>
                                        @else
                                            <table class="students-table">
                                                <thead>
                                                    <tr>
                                                        <th>Código</th>
                                                        <th>Nombre Completo</th>
                                                        <th>Email</th>
                                                        <th>Teléfono</th>
                                                        <th>Estado</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($groupData['students'] as $student)
                                                        <tr>
                                                            <td>
                                                                <span class="student-codigo">{{ $student->codigo }}</span>
                                                            </td>
                                                            <td>{{ $student->nombre_completo }}</td>
                                                            <td>{{ $student->email }}</td>
                                                            <td>{{ $student->telefono ?? '-' }}</td>
                                                            <td>
                                                                <span class="student-estado estado-{{ $student->estado }}">
                                                                    {{ ucfirst($student->estado) }}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <div class="actions">
                                                                    <a href="{{ route('profesor.estudiantes.edit', $student->id) }}" 
                                                                       class="btn-edit">
                                                                        ✏️ Editar
                                                                    </a>
                                                                    <form method="POST" 
                                                                          action="{{ route('profesor.estudiantes.destroy', $student->id) }}" 
                                                                          style="display: inline;"
                                                                          onsubmit="return confirm('¿Está seguro de eliminar a {{ $student->nombre_completo }}?');">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="btn-delete">🗑️ Eliminar</button>
                                                                    </form>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
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
