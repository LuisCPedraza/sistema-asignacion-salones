@php
    use App\Modules\Asignacion\Models\Assignment;
    use App\Modules\GestionAcademica\Models\StudentGroup;
    $assignments = Assignment::whereHas('teacher', fn($q) => $q->where('user_id', auth()->id()))->with(['group', 'classroom', 'teacher', 'subject'])->get();
@endphp

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Profesor - Sistema de Asignación</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            color: #1e293b;
            min-height: 100vh;
        }
        
        /* Header */
        .header {
            background: linear-gradient(135deg, #f59e0b 0%, #ea580c 100%);
            color: white;
            padding: 1.25rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 20px rgba(245, 158, 11, 0.3);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.5rem;
            font-weight: 700;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }
        
        .user-badge {
            background: rgba(255, 255, 255, 0.2);
            padding: 0.5rem 1rem;
            border-radius: 20px;
            backdrop-filter: blur(10px);
            font-size: 0.9rem;
        }
        
        .btn-logout {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 0.625rem 1.25rem;
            text-decoration: none;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 500;
        }
        
        .btn-logout:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }
        
        /* Container */
        .container {
            display: grid;
            grid-template-columns: 280px 1fr;
            min-height: calc(100vh - 80px);
        }
        
        /* Sidebar */
        .sidebar {
            background: white;
            padding: 2rem 0;
            box-shadow: 4px 0 15px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 80px;
            height: calc(100vh - 80px);
            overflow-y: auto;
        }
        
        .sidebar-nav {
            list-style: none;
        }
        
        .sidebar-nav li {
            margin-bottom: 0.5rem;
        }
        
        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem 2rem;
            color: #64748b;
            text-decoration: none;
            transition: all 0.3s;
            border-left: 4px solid transparent;
            font-weight: 500;
        }
        
        .sidebar-nav a:hover {
            background: #fef3c7;
            color: #f59e0b;
            border-left-color: #f59e0b;
        }
        
        .sidebar-nav a.active {
            background: linear-gradient(90deg, #fef3c7 0%, #fef3c7 100%);
            color: #f59e0b;
            border-left-color: #f59e0b;
        }
        
        .sidebar-nav a.coming-soon {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        /* Main Content */
        .main-content {
            padding: 2.5rem;
            max-width: 1400px;
        }
        
        /* Welcome Section */
        .welcome-section {
            background: white;
            padding: 2.5rem;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 2.5rem;
            border-left: 5px solid #f59e0b;
            position: relative;
            overflow: hidden;
        }
        
        .welcome-section::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(245, 158, 11, 0.1) 0%, transparent 70%);
        }
        
        .welcome-section h1 {
            color: #1e293b;
            margin-bottom: 0.75rem;
            font-size: 2rem;
            font-weight: 700;
        }
        
        .welcome-section p {
            color: #64748b;
            font-size: 1.1rem;
            line-height: 1.6;
        }
        
        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2.5rem;
        }
        
        .stat-card {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            text-align: center;
            transition: all 0.3s;
            border-top: 4px solid transparent;
            position: relative;
            overflow: hidden;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #f59e0b, #ea580c);
            transform: scaleX(0);
            transition: transform 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(245, 158, 11, 0.2);
        }
        
        .stat-card:hover::before {
            transform: scaleX(1);
        }
        
        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 0.75rem;
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #f59e0b, #ea580c);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            color: #64748b;
            font-size: 0.95rem;
            font-weight: 500;
        }
        
        /* Modules Grid */
        .modules-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 2rem;
        }
        
        .module-card {
            background: white;
            padding: 2.5rem;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s;
            border: 2px solid transparent;
            position: relative;
            overflow: hidden;
        }
        
        .module-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #f59e0b, #ea580c);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.3s;
        }
        
        .module-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 30px rgba(245, 158, 11, 0.2);
            border-color: #fef3c7;
        }
        
        .module-card:hover::before {
            transform: scaleX(1);
        }
        
        .module-icon {
            font-size: 3rem;
            margin-bottom: 1.25rem;
            display: block;
        }
        
        .module-card h3 {
            color: #1e293b;
            margin-bottom: 1rem;
            font-size: 1.5rem;
            font-weight: 700;
        }
        
        .module-card p {
            color: #64748b;
            margin-bottom: 1.75rem;
            line-height: 1.6;
            font-size: 1rem;
        }
        
        .btn-module {
            background: linear-gradient(135deg, #f59e0b 0%, #ea580c 100%);
            color: white;
            padding: 0.875rem 1.75rem;
            text-decoration: none;
            border-radius: 10px;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 600;
            display: inline-block;
            box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
        }
        
        .btn-module:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(245, 158, 11, 0.4);
        }
        
        .coming-soon {
            opacity: 0.6;
        }
        
        .coming-soon .btn-module {
            background: linear-gradient(135deg, #94a3b8, #64748b);
            cursor: not-allowed;
            box-shadow: none;
        }
        
        .coming-soon .btn-module:hover {
            transform: none;
            box-shadow: none;
        }

        /* Tipografía accesible */
        h1, h2 { font-size: 32px; }
        h3 { font-size: 24px; }
        h4, h5, h6 { font-size: 20px; }
        p, span, a, button, .btn, .btn-logout, .btn-module, .sidebar-nav a, .user-info, .stat-label, .welcome-section p,
        .module-card p, .stat-number, .stat-icon, .module-icon, .user-badge { font-size: 20px; }
        small { font-size: 18px; }
        
        /* Responsive */
        @media (max-width: 1024px) {
            .container {
                grid-template-columns: 1fr;
            }
            
            .sidebar {
                position: static;
                height: auto;
                padding: 1.5rem;
            }
            
            .sidebar-nav {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 0.5rem;
            }
            
            .main-content {
                padding: 1.5rem;
            }
        }
        
        @media (max-width: 768px) {
            .header {
                padding: 1rem;
            }
            
            .logo {
                font-size: 1.25rem;
            }
            
            .user-badge {
                display: none;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .modules-grid {
                grid-template-columns: 1fr;
            }
            
            .welcome-section h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">
            <span>👨‍🏫</span>
            <span>Panel de Profesor</span>
        </div>
        <div class="user-info">
            <div class="user-badge">
                {{ auth()->user()->name ?? auth()->user()->email }}
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">🚪 Salir</button>
            </form>
        </div>
    </div>

    <div class="container">
        <nav class="sidebar">
            <ul class="sidebar-nav">
                <li><a href="{{ route('profesor.dashboard') }}" class="active">📊 Dashboard</a></li>
                <li><a href="{{ route('profesor.horario') }}">🕐 Mi Horario</a></li>

                <li><a href="{{ route('gestion-academica.teachers.availabilities.my') }}">⏰ Disponibilidad</a></li>
                <li><a href="{{ route('profesor.asistencias.index') }}">✅ Asistencias</a></li>
                <li><a href="{{ route('profesor.actividades.index') }}">📝 Actividades</a></li>
                <li><a href="{{ route('profesor.estudiantes.index') }}">🎓 Mis Estudiantes</a></li>
                <li><a href="{{ route('profesor.reportes.index') }}">📈 Reportes</a></li>
            </ul>
        </nav>

        <main class="main-content">
            <div class="welcome-section">
                <h1>¡Bienvenido, {{ auth()->user()->name }}!</h1>
                <p>Gestiona tus horarios, cursos y asistencias desde este panel centralizado. Aquí encontrarás toda la información relevante sobre tus actividades académicas.</p>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">📖</div>
                    <div class="stat-number">{{ $totalSubjects ?? 0 }}</div>
                    <div class="stat-label">Cursos Asignados</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">⏱️</div>
                    <div class="stat-number">{{ $totalHours ?? 0 }}</div>
                    <div class="stat-label">Horas Semanales</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">🎓</div>
                    <div class="stat-number">{{ $totalStudents ?? 0 }}</div>
                    <div class="stat-label">Total Estudiantes</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">✅</div>
                    <div class="stat-number">{{ $totalGroups ?? 0 }}</div>
                    <div class="stat-label">Grupos Activos</div>
                </div>
            </div>

            <div class="modules-grid">
                <div class="module-card">
                    <span class="module-icon">🕐</span>
                    <h3>Mi Horario Personal</h3>
                    <p>Consulta y gestiona tu calendario de clases, horarios de disponibilidad y carga académica semanal.</p>
                    <a href="{{ route('profesor.horario') }}" class="btn-module">Ver Mi Horario</a>
                </div>
                

                <div class="module-card">
                    <span class="module-icon">⏰</span>
                    <h3>Mis Disponibilidades</h3>
                    <p>Gestiona tus horarios de disponibilidad para la asignación de clases y actividades académicas.</p>
                    <a href="{{ route('gestion-academica.teachers.availabilities.my') }}" class="btn-module">Gestionar Horarios</a>
                </div>

                <div class="module-card">
                    <span class="module-icon">🎓</span>
                    <h3>Mis Estudiantes</h3>
                    <p>Administra el registro de estudiantes de tus grupos. Agrega, edita y consulta información.</p>
                    <a href="{{ route('profesor.estudiantes.index') }}" class="btn-module">Gestionar Estudiantes</a>
                </div>

                <div class="module-card">
                    <span class="module-icon">✅</span>
                    <h3>Control de Asistencias</h3>
                    <p>Registra y gestiona la asistencia de tus estudiantes de forma rápida y eficiente.</p>
                    <a href="{{ route('profesor.asistencias.index') }}" class="btn-module">Gestionar Asistencias</a>
                </div>

                <div class="module-card">
                    <span class="module-icon">📝</span>
                    <h3>Actividades y Notas</h3>
                    <p>Crea actividades, registra calificaciones y brinda retroalimentacion a tus estudiantes.</p>
                    <a href="{{ route('profesor.actividades.index') }}" class="btn-module">Gestionar Actividades</a>
                </div>

                <div class="module-card">
                    <span class="module-icon">📊</span>
                    <h3>Reportes Académicos</h3>
                    <p>Genera reportes detallados sobre tus cursos, asistencias y desempeño académico.</p>
                    <a href="{{ route('profesor.reportes.index') }}" class="btn-module">Ver Reportes</a>
                </div>

            </div>
        </main>
    </div>
</body>
</html>