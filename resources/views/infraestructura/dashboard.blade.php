@extends('layouts.app')

@section('content')
<style>
    .dashboard-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem;
        border-radius: 10px;
        margin-bottom: 2rem;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    
    .dashboard-header h1 {
        margin: 0;
        font-size: 2rem;
        font-weight: 600;
    }
    
    .dashboard-header .subtitle {
        margin: 0.5rem 0 0 0;
        opacity: 0.95;
        font-size: 0.95rem;
    }
    
    .stats-row {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        overflow: hidden;
        margin-bottom: 2rem;
    }
    
    .stats-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .stats-table thead th {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1rem;
        text-align: center;
        font-weight: 600;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-right: 1px solid rgba(255,255,255,0.2);
    }
    
    .stats-table thead th:last-child {
        border-right: none;
    }
    
    .stats-table tbody td {
        padding: 1.5rem 1rem;
        text-align: center;
        border-right: 1px solid #e2e8f0;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .stats-table tbody td:last-child {
        border-right: none;
    }
    
    .stats-table tbody tr:last-child td {
        border-bottom: none;
    }
    
    .stats-table .stat-icon {
        font-size: 2rem;
        display: block;
        margin-bottom: 0.5rem;
    }
    
    .stats-table .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: #2d3748;
        display: block;
    }
    
    .stats-table .stat-label {
        font-size: 0.75rem;
        color: #718096;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-top: 0.25rem;
        display: block;
    }
    
    .quick-actions {
        background: white;
        border-radius: 10px;
        padding: 1.5rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
    }
    
    .quick-actions h3 {
        margin: 0 0 1rem 0;
        font-size: 1.25rem;
        color: #2d3748;
        font-weight: 600;
    }
    
    .action-buttons {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1rem;
    }
    
    .action-btn {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem 1.25rem;
        background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        text-decoration: none;
        color: #2d3748;
        transition: all 0.3s;
        font-weight: 500;
    }
    
    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
        border-color: #667eea;
        background: white;
        color: #667eea;
    }
    
    .action-btn .icon {
        font-size: 2rem;
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .action-btn .text {
        flex: 1;
    }
    
    .action-btn .text .title {
        display: block;
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }
    
    .action-btn .text .desc {
        display: block;
        font-size: 0.8rem;
        opacity: 0.7;
    }
    
    .modules-section {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
    }
    
    .module-card {
        background: white;
        border-radius: 10px;
        padding: 1.5rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: all 0.3s;
        border-top: 4px solid;
    }
    
    .module-card.classrooms { border-top-color: #48bb78; }
    .module-card.maintenance { border-top-color: #ed8936; }
    .module-card.reservations { border-top-color: #4299e1; }
    .module-card.reports { border-top-color: #9f7aea; }
    
    .module-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.15);
    }
    
    .module-card .module-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
    }
    
    .module-card h3 {
        margin: 0 0 0.75rem 0;
        font-size: 1.25rem;
        color: #2d3748;
        font-weight: 600;
    }
    
    .module-card p {
        color: #718096;
        font-size: 0.9rem;
        line-height: 1.5;
        margin-bottom: 1.25rem;
    }
    
    .module-card .btn-module {
        display: inline-block;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 0.625rem 1.25rem;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s;
    }
    
    .module-card .btn-module:hover {
        transform: translateX(4px);
        box-shadow: 0 4px 8px rgba(102, 126, 234, 0.3);
        color: white;
    }
    
    .section-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #2d3748;
        margin: 2rem 0 1rem 0;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #e2e8f0;
    }
</style>

<div class="container mt-4">
    <!-- Header -->
    <div class="dashboard-header">
        <h1>📊 Dashboard de Infraestructura</h1>
        <p class="subtitle">Panel de control centralizado para gestión de espacios y recursos</p>
    </div>

    <!-- Estadísticas en Tabla -->
    <div class="stats-row">
        <table class="stats-table">
            <thead>
                <tr>
                    <th>Salones Activos</th>
                    <th>En Mantenimiento</th>
                    <th>Mtto Pendientes</th>
                    <th>Reservas Pendientes</th>
                    <th>Próximas Reservas</th>
                    <th>Capacidad Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <span class="stat-icon">🏢</span>
                        <span class="stat-value">{{ $activeClassroomsCount ?? 0 }}</span>
                    </td>
                    <td>
                        <span class="stat-icon">🔄</span>
                        <span class="stat-value">{{ $maintenanceInProgressCount ?? 0 }}</span>
                    </td>
                    <td>
                        <span class="stat-icon">⏳</span>
                        <span class="stat-value">{{ $maintenancePendingCount ?? 0 }}</span>
                    </td>
                    <td>
                        <span class="stat-icon">📅</span>
                        <span class="stat-value">{{ $reservationsPending ?? 0 }}</span>
                    </td>
                    <td>
                        <span class="stat-icon">🔜</span>
                        <span class="stat-value">{{ $reservationsUpcoming ?? 0 }}</span>
                    </td>
                    <td>
                        <span class="stat-icon">👥</span>
                        <span class="stat-value">{{ $totalCapacity ?? 0 }}</span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Acciones Rápidas -->
    <div class="quick-actions">
        <h3>⚡ Acciones Rápidas</h3>
        <div class="action-buttons">
            <a href="{{ route('infraestructura.classrooms.create') }}" class="action-btn">
                <div class="icon">➕</div>
                <div class="text">
                    <span class="title">Crear Salón</span>
                    <span class="desc">Agregar nuevo espacio</span>
                </div>
            </a>
            
            <a href="{{ route('infraestructura.maintenance.create') }}" class="action-btn">
                <div class="icon">🔧</div>
                <div class="text">
                    <span class="title">Programar Mantenimiento</span>
                    <span class="desc">Nueva tarea de mantenimiento</span>
                </div>
            </a>
            
            <a href="{{ route('infraestructura.reservations.create') }}" class="action-btn">
                <div class="icon">📝</div>
                <div class="text">
                    <span class="title">Nueva Reserva</span>
                    <span class="desc">Reservar espacio</span>
                </div>
            </a>
            
            <a href="{{ route('infraestructura.reports.index') }}" class="action-btn">
                <div class="icon">📊</div>
                <div class="text">
                    <span class="title">Ver Reportes</span>
                    <span class="desc">Análisis y estadísticas</span>
                </div>
            </a>
        </div>
    </div>

    <!-- Módulos -->
    <h2 class="section-title">📦 Módulos del Sistema</h2>
    <div class="modules-section">
        <div class="module-card classrooms">
            <div class="module-icon">🏢</div>
            <h3>Gestión de Salones</h3>
            <p>Administra la información de salones, capacidad, equipamiento y disponibilidad de todos los espacios del campus.</p>
            <a href="{{ route('infraestructura.classrooms.index') }}" class="btn-module">Gestionar Salones →</a>
        </div>
        
        <div class="module-card maintenance">
            <div class="module-icon">🔧</div>
            <h3>Mantenimiento</h3>
            <p>Programa y gestiona mantenimiento preventivo y correctivo para garantizar el óptimo estado de las instalaciones.</p>
            <a href="{{ route('infraestructura.maintenance.index') }}" class="btn-module">Ver Mantenimiento →</a>
        </div>
        
        <div class="module-card reservations">
            <div class="module-icon">📅</div>
            <h3>Reservas</h3>
            <p>Gestiona reservas de salones y recursos de infraestructura, aprueba solicitudes y controla la ocupación.</p>
            <a href="{{ route('infraestructura.reservations.index') }}" class="btn-module">Ver Reservas →</a>
        </div>
        
        <div class="module-card reports">
            <div class="module-icon">📈</div>
            <h3>Reportes y Análisis</h3>
            <p>Genera reportes detallados de uso, estado de la infraestructura y métricas de ocupación para toma de decisiones.</p>
            <a href="{{ route('infraestructura.reports.index') }}" class="btn-module">Ver Reportes →</a>
        </div>
    </div>
</div>
@endsection
