<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Coordinador Académico</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8eb 100%);
            min-height: 100vh;
        }
        
        /* Header mejorado */
        .header {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            color: white;
            padding: 1.5rem 2.5rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .logo {
            font-size: 1.6rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.7rem;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            font-size: 0.95rem;
        }
        
        .btn-logout {
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.3);
            color: white;
            padding: 0.65rem 1.3rem;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.3s;
            font-weight: 500;
        }
        
        .btn-logout:hover {
            background: rgba(255,255,255,0.25);
            transform: translateY(-1px);
        }
        
        /* Layout mejorado */
        .container {
            display: grid;
            grid-template-columns: 270px 1fr;
            gap: 0;
            max-width: 1700px;
            margin: 0 auto;
            min-height: calc(100vh - 90px);
        }
        
        /* Sidebar más elegante */
        .sidebar {
            background: white;
            padding: 2rem 0;
            box-shadow: 2px 0 10px rgba(0,0,0,0.06);
            position: sticky;
            top: 90px;
            height: calc(100vh - 90px);
            overflow-y: auto;
        }
        
        .sidebar-nav {
            list-style: none;
        }
        
        .sidebar-nav li {
            margin-bottom: 0.4rem;
        }
        
        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: 0.9rem;
            padding: 1rem 1.8rem;
            color: #475569;
            text-decoration: none;
            transition: all 0.3s;
            border-left: 3px solid transparent;
            font-weight: 500;
            font-size: 0.95rem;
        }
        
        .sidebar-nav a:hover {
            background: #f1f5f9;
            border-left-color: #2563eb;
            color: #2563eb;
        }
        
        .sidebar-nav a.active {
            background: linear-gradient(90deg, #eff6ff 0%, #dbeafe 100%);
            color: #2563eb;
            border-left-color: #2563eb;
            font-weight: 600;
        }
        
        /* Main content mejorado */
        .main-content {
            background: #f8fafc;
            padding: 2.5rem 3rem;
        }
        
        /* Welcome section más atractiva */
        .welcome-section {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            padding: 2.5rem;
            border-radius: 14px;
            margin-bottom: 2.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            border-left: 5px solid #2563eb;
        }
        
        .welcome-section h1 {
            color: #1e293b;
            margin-bottom: 0.6rem;
            font-size: 2rem;
            font-weight: 700;
        }
        
        .welcome-section p {
            color: #64748b;
            font-size: 1.05rem;
            line-height: 1.6;
        }
        
        /* Section titles */
        .section-title {
            color: #1e293b;
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 1.8rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }
        
        /* Stats cards mejoradas */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.8rem;
            margin-bottom: 3.5rem;
        }
        
        .stat-card {
            background: white;
            padding: 2rem;
            border-radius: 14px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            border-left: 5px solid #2563eb;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: rgba(37, 99, 235, 0.05);
            border-radius: 50%;
            transform: translate(30%, -30%);
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.12);
        }
        
        .stat-card:nth-child(1) { border-left-color: #2563eb; }
        .stat-card:nth-child(2) { border-left-color: #10b981; }
        .stat-card:nth-child(3) { border-left-color: #f59e0b; }
        .stat-card:nth-child(4) { border-left-color: #8b5cf6; }
        
        .stat-card:nth-child(1)::before { background: rgba(37, 99, 235, 0.05); }
        .stat-card:nth-child(2)::before { background: rgba(16, 185, 129, 0.05); }
        .stat-card:nth-child(3)::before { background: rgba(245, 158, 11, 0.05); }
        .stat-card:nth-child(4)::before { background: rgba(139, 92, 246, 0.05); }
        
        .stat-number {
            font-size: 2.8rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            position: relative;
            z-index: 1;
        }
        
        .stat-card:nth-child(1) .stat-number { color: #2563eb; }
        .stat-card:nth-child(2) .stat-number { color: #10b981; }
        .stat-card:nth-child(3) .stat-number { color: #f59e0b; }
        .stat-card:nth-child(4) .stat-number { color: #8b5cf6; }
        
        .stat-label {
            color: #64748b;
            font-size: 1rem;
            font-weight: 500;
            position: relative;
            z-index: 1;
        }
        
        /* Module cards mejoradas */
        .modules-section {
            margin-bottom: 2rem;
        }
        
        .modules-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(360px, 1fr));
            gap: 2rem;
        }
        
        .module-card {
            background: white;
            padding: 2.5rem;
            border-radius: 14px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            border-top: 5px solid #2563eb;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }
        
        .module-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, #2563eb 0%, #10b981 100%);
            opacity: 0;
            transition: opacity 0.3s;
        }
        
        .module-card:hover::before {
            opacity: 1;
        }
        
        .module-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 28px rgba(0,0,0,0.15);
        }
        
        .module-card h3 {
            color: #1e293b;
            margin-bottom: 1.2rem;
            font-size: 1.4rem;
            font-weight: 700;
        }
        
        .module-card p {
            color: #64748b;
            margin-bottom: 1.8rem;
            line-height: 1.7;
            font-size: 0.98rem;
        }
        
        .btn-module {
            display: inline-block;
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            color: white;
            padding: 0.9rem 2rem;
            border-radius: 10px;
            text-decoration: none;
            transition: all 0.3s;
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 4px 12px rgba(37,99,235,0.3);
        }
        
        .btn-module:hover {
            background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(37,99,235,0.4);
        }
        
        /* Grids para botones especiales */
        .assignment-grid,
        .visualization-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.2rem;
            margin-top: 1.2rem;
        }
        
        .assignment-btn,
        .visualization-btn {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.3rem 1.5rem;
            border-radius: 12px;
            color: white;
            text-decoration: none;
            transition: all 0.3s;
            font-weight: 600;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(0,0,0,0.15);
        }
        
        .assignment-btn:hover,
        .visualization-btn:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.2);
            text-decoration: none;
            color: white;
        }
        
        /* Colores para botones de asignación */
        .assignment-btn-blue {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        }
        
        .assignment-btn-blue:hover {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
        }
        
        .assignment-btn-green {
            background: linear-gradient(135deg, #10b981 0%, #047857 100%);
        }
        
        .assignment-btn-green:hover {
            background: linear-gradient(135deg, #059669 0%, #065f46 100%);
        }
        
        .assignment-btn-purple {
            background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%);
        }
        
        .assignment-btn-purple:hover {
            background: linear-gradient(135deg, #7c3aed 0%, #5b21b6 100%);
        }
        
        .assignment-btn-red {
            background: linear-gradient(135deg, #ef4444 0%, #991b1b 100%);
        }
        
        .assignment-btn-red:hover {
            background: linear-gradient(135deg, #dc2626 0%, #7f1d1d 100%);
        }
        
        /* Visualización */
        .visualization-btn-blue {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        }
        
        .visualization-btn-green {
            background: linear-gradient(135deg, #10b981 0%, #047857 100%);
        }
        
        .btn-icon {
            background: rgba(255,255,255,0.25);
            border-radius: 50%;
            padding: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="logo">🏫 Sistema de Asignación de Salones</div>
        <div class="user-info">
            <span>👤 {{ auth()->user()->name ?? auth()->user()->email }} ({{ auth()->user()->role->name ?? 'Sin rol' }})</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">🚪 Cerrar Sesión</button>
            </form>
        </div>
    </div>

    <div class="container">
        
        <nav class="sidebar">
            <ul class="sidebar-nav">
                <li><a href="{{ route('academic.dashboard') }}" class="active">📊 Dashboard</a></li>
                <li><a href="{{ route('gestion-academica.student-groups.index') }}">🎓 Grupos de Estudiantes</a></li>
                <li><a href="{{ route('gestion-academica.teachers.index') }}">👨‍🏫 Gestión de Profesores</a></li>
                <li><a href="{{ route('asignacion.automatica') }}">🤖 Asignación Inteligente</a></li>
                <li><a href="{{ route('visualizacion.horario.semestral') }}">📊 Visualización Horarios</a></li>
                <li><a href="{{ route('gestion-academica.reports.index') }}">📈 Reportes Académicos</a></li>
            </ul>
        </nav>

        <main class="main-content">

            <div class="welcome-section">
                <h1>Bienvenido al Panel de Coordinación Académica</h1>
                <p>Gestiona grupos de estudiantes, profesores, asignaciones y horarios desde este panel centralizado. Accede a todas las herramientas necesarias para una coordinación académica eficiente.</p>
            </div>

            <!-- Estadísticas principales -->
            <h2 class="section-title">📊 Resumen General</h2>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number">{{ \App\Modules\GestionAcademica\Models\StudentGroup::count() }}</div>
                    <div class="stat-label">Grupos de Estudiantes</div>
                </div>

                <div class="stat-card">
                    <div class="stat-number">{{ \App\Models\Teacher::count() }}</div>
                    <div class="stat-label">Profesores Registrados</div>
                </div>

                <div class="stat-card">
                    <div class="stat-number">{{ \App\Modules\GestionAcademica\Models\StudentGroup::active()->count() }}</div>
                    <div class="stat-label">Grupos Activos</div>
                </div>

                <div class="stat-card">
                    <div class="stat-number">{{ \App\Models\Teacher::active()->count() }}</div>
                    <div class="stat-label">Profesores Activos</div>
                </div>
            </div>

            <!-- Módulos principales -->
            <h2 class="section-title">🎯 Módulos del Sistema</h2>
            <div class="modules-section">
                <div class="modules-grid">

                    <!-- Gestión de Grupos -->
                    <div class="module-card">
                        <h3>🎓 Gestión de Grupos</h3>
                        <p>Administra grupos de estudiantes, niveles académicos, características especiales y periodos académicos de forma centralizada.</p>
                        <a href="{{ route('gestion-academica.student-groups.index') }}" class="btn-module">Gestionar Grupos</a>
                    </div>

                    <!-- Gestión de Profesores -->
                    <div class="module-card">
                        <h3>👨‍🏫 Gestión de Profesores</h3>
                        <p>Gestiona información completa de profesores, especialidades académicas, hojas de vida y disponibilidades horarias.</p>
                        <a href="{{ route('gestion-academica.teachers.index') }}" class="btn-module">Gestionar Profesores</a>
                    </div>

                    <!-- Sistema de Asignación Inteligente -->
                    <div class="module-card">
                        <h3>🤖 Sistema de Asignación Inteligente</h3>
                        <p>Asignaciones automáticas optimizadas, gestión manual con arrastrar y soltar, configuración de reglas y detección de conflictos en tiempo real.</p>
                        
                        <div class="assignment-grid">
                            <a href="{{ route('asignacion.automatica') }}" class="assignment-btn assignment-btn-blue">
                                <span>
                                    <strong>🔄 Automática</strong><br>
                                    <small style="opacity: 0.9;">Algoritmo inteligente</small>
                                </span>
                            </a>

                            <a href="{{ route('asignacion.manual') }}" class="assignment-btn assignment-btn-green">
                                <span>
                                    <strong>👆 Manual</strong><br>
                                    <small style="opacity: 0.9;">Arrastrar y soltar</small>
                                </span>
                            </a>

                            <a href="{{ route('asignacion.reglas') }}" class="assignment-btn assignment-btn-purple">
                                <span>
                                    <strong>⚙️ Reglas</strong><br>
                                    <small style="opacity: 0.9;">Configurar prioridades</small>
                                </span>
                            </a>

                            <a href="{{ route('asignacion.conflictos') }}" class="assignment-btn assignment-btn-red">
                                <span>
                                    <strong>⚠️ Conflictos</strong><br>
                                    <small style="opacity: 0.9;">Detección en tiempo real</small>
                                </span>
                            </a>
                        </div>
                    </div>

                    <!-- Visualización de Horarios -->
                    <div class="module-card">
                        <h3>📊 Visualización de Horarios</h3>
                        <p>Vista consolidada completa para coordinadores y horarios personalizados individuales para cada profesor del departamento.</p>
                        
                        <div class="visualization-grid">
                            <a href="{{ route('visualizacion.horario.semestral') }}" class="visualization-btn visualization-btn-blue">
                                <div>
                                    <div style="font-weight: 600;">📊 Semestral Completo</div>
                                    <div style="opacity: 0.9; font-size: 0.85rem; margin-top: 0.3rem;">Para coordinadores</div>
                                </div>
                                <div class="btn-icon">
                                    <svg style="width: 1.2rem; height: 1.2rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                            </a>

                            <a href="{{ route('visualizacion.horario.personal') }}" class="visualization-btn visualization-btn-green">
                                <div>
                                    <div style="font-weight: 600;">📅 Personalizado</div>
                                    <div style="opacity: 0.9; font-size: 0.85rem; margin-top: 0.3rem;">Para profesores</div>
                                </div>
                                <div class="btn-icon">
                                    <svg style="width: 1.2rem; height: 1.2rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Reportes Académicos -->
                    <div class="module-card">
                        <h3>📈 Reportes Académicos</h3>
                        <p>Genera reportes detallados de grupos estudiantiles, profesores asignados y estadísticas completas del departamento académico.</p>
                        <a href="{{ route('gestion-academica.reports.index') }}" class="btn-module">Ver Reportes</a>
                    </div>

                </div>
            </div>

        </main>
    </div>

</body>
</html>
