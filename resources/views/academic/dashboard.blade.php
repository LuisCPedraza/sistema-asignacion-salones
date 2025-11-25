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
            color: white;
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
        .coming-soon {
            opacity: 0.7;
            pointer-events: none;
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
                <li><a href="#" class="coming-soon">🎓 Grupos de Estudiantes (Próximamente)</a></li>
                <li><a href="#" class="coming-soon">👨‍🏫 Gestión de Profesores (Próximamente)</a></li>
                <li><a href="#" class="coming-soon">📅 Asignación de Salones (Próximamente)</a></li>
                <li><a href="#" class="coming-soon">📈 Reportes (Próximamente)</a></li>
            </ul>
        </nav>

        <main class="main-content">
            <div class="welcome-section">
                <h1>Bienvenido al Panel de Coordinación Académica</h1>
                <p>Gestiona grupos de estudiantes, profesores y disponibilidades desde este panel centralizado.</p>
            </div>

            <!-- Estadísticas simplificadas -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number">0</div>
                    <div class="stat-label">Grupos de Estudiantes</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">0</div>
                    <div class="stat-label">Profesores Registrados</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">0</div>
                    <div class="stat-label">Grupos Activos</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">0</div>
                    <div class="stat-label">Profesores Activos</div>
                </div>
            </div>

            <div class="modules-grid">
                <div class="module-card coming-soon">
                    <h3>🎓 Gestión de Grupos</h3>
                    <p>Administra grupos de estudiantes, niveles, características especiales y periodos académicos.</p>
                    <a href="#" class="btn-module">Gestionar Grupos (Próximamente)</a>
                </div>
                
                <div class="module-card coming-soon">
                    <h3>👨‍🏫 Gestión de Profesores</h3>
                    <p>Gestiona información de profesores, especialidades, hojas de vida y disponibilidades.</p>
                    <a href="#" class="btn-module">Gestionar Profesores (Próximamente)</a>
                </div>
                
                <div class="module-card coming-soon">
                    <h3>📅 Disponibilidades</h3>
                    <p>Configura y gestiona las disponibilidades horarias de los profesores para asignaciones.</p>
                    <a href="#" class="btn-module">Ver Disponibilidades (Próximamente)</a>
                </div>
                
                <div class="module-card coming-soon">
                    <h3>📊 Reportes Académicos</h3>
                    <p>Genera reportes de grupos, profesores y estadísticas del departamento académico.</p>
                    <a href="#" class="btn-module">Ver Reportes (Próximamente)</a>
                </div>
            </div>
        </main>
    </div>
</body>
</html>