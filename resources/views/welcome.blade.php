<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Asignación de Salones - Universidad</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Header */
        .header {
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(10px);
            padding: 1.2rem 2.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            border-bottom: 1px solid rgba(148, 163, 184, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .logo {
            font-size: 24px;
            font-weight: 700;
            color: white;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            letter-spacing: -0.5px;
        }

        .logo-icon {
            font-size: 2rem;
        }

        .auth-buttons {
            display: flex;
            gap: 1rem;
        }

        .btn-logout {
            background: rgba(239, 68, 68, 0.9);
            color: white;
            padding: 0.7rem 1.5rem;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 20px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        .btn-logout:hover {
            background: rgba(220, 38, 38, 0.9);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(239, 68, 68, 0.4);
        }

        /* Main Container */
        .main-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem 2rem;
        }

        .content-wrapper {
            max-width: 1200px;
            width: 100%;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
        }

        /* Left side - Hero text */
        .hero-content {
            color: white;
        }

        .hero-pretitle {
            font-size: 20px;
            font-weight: 600;
            color: #60a5fa;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 1rem;
        }

        .hero-title {
            font-size: 36px;
            font-weight: 800;
            margin-bottom: 1.5rem;
            line-height: 1.3;
            letter-spacing: -1px;
        }

        .hero-title-gradient {
            background: linear-gradient(135deg, #60a5fa 0%, #34d399 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-description {
            font-size: 20px;
            line-height: 1.6;
            color: #cbd5e1;
            margin-bottom: 2.5rem;
        }

        .features-list {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.2rem;
            margin-bottom: 3rem;
        }

        .feature-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }

        .feature-icon {
            font-size: 1.8rem;
            flex-shrink: 0;
        }

        .feature-text {
            display: flex;
            flex-direction: column;
        }

        .feature-title {
            font-weight: 600;
            color: white;
            margin-bottom: 0.25rem;
            font-size: 24px;
        }

        .feature-desc {
            color: #cbd5e1;
            font-size: 20px;
        }

        .auth-section {
            margin-top: 2.5rem;
        }

        .auth-buttons-container {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .btn {
            padding: 1.1rem 2.2rem;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 20px;
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
        }

        .btn-login {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            color: white;
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(37, 99, 235, 0.4);
            background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%);
        }

        .btn-register {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .btn-register:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(16, 185, 129, 0.4);
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
        }

        .btn-dashboard {
            background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%);
            color: white;
        }

        .btn-dashboard:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(139, 92, 246, 0.4);
            background: linear-gradient(135deg, #6d28d9 0%, #5b21b6 100%);
        }

        .register-note {
            margin-top: 1.5rem;
            padding: 1.2rem 1.5rem;
            background: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.3);
            border-radius: 10px;
            font-size: 20px;
            color: #cbd5e1;
            line-height: 1.6;
        }

        /* Right side - Cards showcase */
        .card-showcase {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        .card {
            background: rgba(30, 41, 59, 0.4);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(148, 163, 184, 0.2);
            border-radius: 16px;
            padding: 2rem;
            transition: all 0.3s ease;
        }

        .card:hover {
            background: rgba(30, 41, 59, 0.6);
            border-color: rgba(148, 163, 184, 0.4);
            transform: translateY(-8px);
        }

        .card-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .card-title {
            font-size: 24px;
            font-weight: 700;
            color: white;
            margin-bottom: 0.5rem;
        }

        .card-desc {
            color: #cbd5e1;
            font-size: 20px;
            line-height: 1.6;
        }

        .user-greeting {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(34, 197, 94, 0.1) 100%);
            padding: 1.5rem 1.8rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #cbd5e1;
        }

        .user-greeting p {
            margin: 0;
            color: #e2e8f0;
            font-weight: 500;
        }

        .footer {
            background: rgba(15, 23, 42, 0.8);
            padding: 1.5rem 2rem;
            text-align: center;
            color: #94a3b8;
            border-top: 1px solid rgba(148, 163, 184, 0.1);
            font-size: 20px;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .content-wrapper {
                grid-template-columns: 1fr;
                gap: 2.5rem;
            }

            .card-showcase {
                grid-template-columns: 1fr;
            }

            .hero-title {
                font-size: 2.8rem;
            }
        }

        @media (max-width: 768px) {
            .main-container {
                padding: 2rem 1.5rem;
            }

            .hero-title {
                font-size: 2rem;
            }

            .hero-description {
                font-size: 1.05rem;
            }

            .header {
                padding: 1rem 1.5rem;
            }

            .logo {
                font-size: 1.3rem;
            }

            .auth-buttons-container {
                flex-direction: column;
                width: 100%;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }

            .card-showcase {
                margin-top: 2rem;
            }
        }
    </style>
</head>
<body>
    
    <header class="header">
        <div class="logo">
            <span class="logo-icon">🏫</span>
            Sistema de Asignación de Salones
        </div>
        @auth
        <div class="auth-buttons">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">🚪 Cerrar Sesión</button>
            </form>
        </div>
        @endauth
    </header>

    <div class="main-container">
        <div class="content-wrapper">
            
            <!-- Left Side -->
            <div class="hero-content">
                <div class="hero-pretitle">Bienvenido a</div>
                <h1 class="hero-title">
                    Sistema de 
                    <span class="hero-title-gradient">Asignación de Salones para Institutos Educativos</span>
                </h1>
                
                <p class="hero-description">
                    Gestiona grupos de estudiantes, asignaciones de salones y horarios académicos de forma eficiente y automatizada.
                </p>

                <div class="features-list">
                    <div class="feature-item">
                        <div class="feature-icon">🎯</div>
                        <div class="feature-text">
                            <div class="feature-title">Asignaciones Inteligentes</div>
                            <div class="feature-desc">Algoritmos avanzados que optimizan la distribución de recursos</div>
                        </div>
                    </div>

                    <div class="feature-item">
                        <div class="feature-icon">📊</div>
                        <div class="feature-text">
                            <div class="feature-title">Reportes Detallados</div>
                            <div class="feature-desc">Analiza métricas y genera informes completos en PDF</div>
                        </div>
                    </div>

                    <div class="feature-item">
                        <div class="feature-icon">⚡</div>
                        <div class="feature-text">
                            <div class="feature-title">Rápido y Eficiente</div>
                            <div class="feature-desc">Procesa miles de asignaciones en segundos</div>
                        </div>
                    </div>

                    <div class="feature-item">
                        <div class="feature-icon">🔒</div>
                        <div class="feature-text">
                            <div class="feature-title">Seguridad Garantizada</div>
                            <div class="feature-desc">Protección de datos con encriptación de nivel empresarial</div>
                        </div>
                    </div>
                </div>

                @auth
                    <div class="user-greeting">
                        <p>👋 ¡Hola de nuevo, {{ auth()->user()->name ?? auth()->user()->email }}! Accede al panel de control.</p>
                    </div>
                    <div class="auth-section">
                        <a href="{{ route('dashboard') }}" class="btn btn-dashboard">
                            🚀 Ir al Dashboard
                        </a>
                        <a href="{{ route('chatbot') }}" class="btn btn-register" style="margin-left:.75rem;background:linear-gradient(135deg,#10b981 0%,#22c55e 100%);box-shadow:0 12px 30px rgba(34,197,94,.35)">
                            ✨ Pregunta al Asistente
                        </a>
                    </div>
                @else
                    <div class="auth-section">
                        <div class="auth-buttons-container">
                            <a href="{{ route('login') }}" class="btn btn-login">
                                🔑 Iniciar Sesión
                            </a>
                            <a href="{{ route('register') }}" class="btn btn-register">
                                📝 Solicitar Acceso
                            </a>
                            <a href="{{ route('chatbot') }}" class="btn btn-register" style="background:linear-gradient(135deg,#10b981 0%,#22c55e 100%);box-shadow:0 12px 30px rgba(34,197,94,.35)">
                                ✨ Pregunta al Asistente
                            </a>
                        </div>
                        <div class="register-note">
                            <strong>¿Primera vez aquí?</strong> Solicita acceso y un administrador te asignará el rol apropiado para acceder a todas las funcionalidades del sistema.
                        </div>
                    </div>
                @endauth
            </div>

            <!-- Right Side -->
            <div class="card-showcase">
                <div class="card">
                    <div class="card-icon">🎓</div>
                    <div class="card-title">Gestión de Grupos</div>
                    <div class="card-desc">Administra grupos de estudiantes, niveles y periodos académicos de forma centralizada.</div>
                </div>

                <div class="card">
                    <div class="card-icon">👨‍🏫</div>
                    <div class="card-title">Gestión de Profesores</div>
                    <div class="card-desc">Registra profesores, especialidades y disponibilidades horarias.</div>
                </div>

                <div class="card">
                    <div class="card-icon">🤖</div>
                    <div class="card-title">Asignaciones Automáticas</div>
                    <div class="card-desc">Optimiza asignaciones con algoritmos inteligentes y detección de conflictos.</div>
                </div>

                <div class="card">
                    <div class="card-icon">📅</div>
                    <div class="card-title">Visualización de Horarios</div>
                    <div class="card-desc">Consulta horarios completos y personalizados en tiempo real.</div>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer">
        <p>&copy; 2024 Sistema de Asignación de Salones - Universidad. Todos los derechos reservados.</p>
    </footer>

</body>
</html>
