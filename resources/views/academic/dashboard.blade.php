<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Coordinación - Sistema de Asignación</title>
    <style>
        /* Reutilizar estilos del dashboard de admin con ajustes de colores */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Arial', sans-serif;
            background: #f8fafc;
            color: #334155;
        }
        .header {
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
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
            display: flex;
            min-height: calc(100vh - 80px);
        }
        .sidebar {
            width: 280px;
            background: white;
            padding: 2rem 0;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            border-right: 1px solid #e2e8f0;
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
            padding: 1rem 2rem;
            color: #475569;
            text-decoration: none;
            transition: all 0.3s;
            border-left: 4px solid transparent;
        }
        .sidebar-nav a:hover {
            background: #f1f5f9;
            color: #48bb78;
            border-left-color: #48bb78;
        }
        .sidebar-nav a.active {
            background: #48bb78;
            color: white;
            border-left-color: #38a169;
        }
        .main-content {
            flex: 1;
            padding: 2rem;
            background: #f8fafc;
        }
        .welcome-section {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
            border-left: 4px solid #48bb78;
        }
        .welcome-section h1 {
            color: #1e293b;
            margin-bottom: 0.5rem;
        }
        .welcome-section p {
            color: #64748b;
            font-size: 1.1rem;
        }
        .modules-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }
        .module-card {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
            transition: all 0.3s;
            border: 1px solid #e2e8f0;
        }
        .module-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        .module-card h3 {
            color: #1e293b;
            margin-bottom: 1rem;
            font-size: 1.25rem;
        }
        .module-card p {
            color: #64748b;
            margin-bottom: 1.5rem;
            line-height: 1.5;
        }
        .btn-module {
            background: #48bb78;
            color: white;
            padding: 0.75rem 1.5rem;
            text-decoration: none;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            transition: background 0.3s;
            font-weight: 500;
            display: inline-block;
        }
        .btn-module:hover {
            background: #38a169;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            text-align: center;
        }
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: #48bb78;
            margin-bottom: 0.5rem;
        }
        .stat-label {
            color: #64748b;
            font-size: 0.9rem;
        }
        
        /* Nuevos estilos para el Módulo 6 */
        .visualization-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }
        .visualization-btn {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem;
            border-radius: 8px;
            color: white;
            text-decoration: none;
            transition: all 0.3s;
            font-weight: 500;
        }
        .visualization-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .visualization-btn-blue {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        }
        .visualization-btn-green {
            background: linear-gradient(135deg, #10b981 0%, #047857 100%);
        }
        .btn-icon {
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            padding: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .coming-soon {
            opacity: 0.7;
        }
        .coming-soon .btn-module {
            background: #9ca3af;
            cursor: not-allowed;
        }
        .coming-soon .btn-module:hover {
            background: #9ca3af;
        }

        /* Estilos para botones de asignación */
        .assignment-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }
        .assignment-btn {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem;
            border-radius: 8px;
            color: white;
            text-decoration: none;
            transition: all 0.3s;
            font-weight: 500;
            border: none;
            cursor: pointer;
        }
        .assignment-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            text-decoration: none;
            color: white;
        }
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
                <p>Gestiona grupos de estudiantes, profesores y disponibilidades desde este panel centralizado.</p>
            </div>

            <!-- ✔️ Estadísticas actuales -->
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

            <div class="modules-grid">

                <div class="module-card">
                    <h3>🎓 Gestión de Grupos</h3>
                    <p>Administra grupos de estudiantes, niveles, características especiales y periodos académicos.</p>
                    <a href="{{ route('gestion-academica.student-groups.index') }}" class="btn-module">Gestionar Grupos</a>
                </div>

                <div class="module-card">
                    <h3>👨‍🏫 Gestión de Profesores</h3>
                    <p>Gestiona información de profesores, especialidades, hojas de vida y disponibilidades.</p>
                    <a href="{{ route('gestion-academica.teachers.index') }}" class="btn-module">Gestionar Profesores</a>
                </div>

                <!-- Módulo 5: Sistema de Asignación Inteligente -->
                <div class="module-card">
                    <h3>🤖 Sistema de Asignación Inteligente</h3>
                    <p>Asignaciones automáticas y manuales de salones, configuración de reglas y detección de conflictos.</p>
                    
                    <div class="assignment-grid">
                        <a href="{{ route('asignacion.automatica') }}" class="assignment-btn assignment-btn-blue">
                            <span>
                                <strong>🔄 Automática</strong><br>
                                <small>Algoritmo inteligente</small>
                            </span>
                        </a>

                        <a href="{{ route('asignacion.manual') }}" class="assignment-btn assignment-btn-green">
                            <span>
                                <strong>👆 Manual</strong><br>
                                <small>Arrastrar y soltar</small>
                            </span>
                        </a>

                        <a href="{{ route('asignacion.reglas') }}" class="assignment-btn assignment-btn-purple">
                            <span>
                                <strong>⚙️ Reglas</strong><br>
                                <small>Configurar prioridades</small>
                            </span>
                        </a>

                        <a href="{{ route('asignacion.conflictos') }}" class="assignment-btn assignment-btn-red">
                            <span>
                                <strong>⚠️ Conflictos</strong><br>
                                <small>Detección en tiempo real</small>
                            </span>
                        </a>
                    </div>
                </div>

                <!-- Módulo 6: Visualización de Horarios -->
                <div class="module-card">
                    <h3>📊 Visualización de Horarios</h3>
                    <p>Vista consolidada para coordinación y horarios personalizados por profesor.</p>
                    
                    <div class="visualization-grid">
                        <a href="{{ route('visualizacion.horario.semestral') }}" class="visualization-btn visualization-btn-blue">
                            <div>
                                <div class="font-semibold">📊 Semestral Completo</div>
                                <div class="text-blue-100 text-xs mt-1">Para coordinadores</div>
                            </div>
                            <div class="btn-icon">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                        </a>

                        <a href="{{ route('visualizacion.horario.personal') }}" class="visualization-btn visualization-btn-green">
                            <div>
                                <div class="font-semibold">📅 Personalizado</div>
                                <div class="text-green-100 text-xs mt-1">Para profesores</div>
                            </div>
                            <div class="btn-icon">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="module-card">
                    <h3>📊 Reportes Académicos</h3>
                    <p>Genera reportes de grupos, profesores y estadísticas del departamento.</p>
                    <a href="{{ route('gestion-academica.reports.index') }}" class="btn-module">Ver Reportes</a>
                </div>

            </div>

        </main>
    </div>

</body>
</html>