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
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #333;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .header {
            background: rgba(255, 255, 255, 0.95);
            padding: 1.5rem 2rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: bold;
            color: #4a5568;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .auth-buttons {
            display: flex;
            gap: 1rem;
        }

        .btn-logout {
            background: #e53e3e;
            color: white;
            padding: 0.5rem 1.5rem;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn-logout:hover {
            background: #c53030;
        }

        .main-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .welcome-card {
            background: rgba(255, 255, 255, 0.95);
            padding: 3rem;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 600px;
            width: 100%;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .university-icon {
            font-size: 4rem;
            margin-bottom: 1.5rem;
            color: #4a5568;
        }

        .welcome-title {
            font-size: 2.5rem;
            color: #2d3748;
            margin-bottom: 1rem;
            font-weight: 700;
        }

        .welcome-subtitle {
            font-size: 1.2rem;
            color: #718096;
            margin-bottom: 2.5rem;
            line-height: 1.6;
        }

        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2.5rem;
        }

        .feature {
            background: #f7fafc;
            padding: 1.5rem 1rem;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .feature:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            border-color: #48bb78;
        }

        .feature-icon {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            color: #48bb78;
        }

        .feature-text {
            font-size: 0.9rem;
            color: #4a5568;
            font-weight: 500;
        }

        .auth-section {
            margin-top: 2rem;
        }

        .auth-buttons-container {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-login {
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
            color: white;
            padding: 1rem 2.5rem;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(72, 187, 120, 0.3);
            border: none;
            cursor: pointer;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(72, 187, 120, 0.4);
            background: linear-gradient(135deg, #38a169 0%, #2f855a 100%);
        }

        .btn-register {
            background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%);
            color: white;
            padding: 1rem 2.5rem;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(237, 137, 54, 0.3);
            border: none;
            cursor: pointer;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(237, 137, 54, 0.4);
            background: linear-gradient(135deg, #dd6b20 0%, #c05621 100%);
        }

        .btn-dashboard {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem 2.5rem;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }

        .btn-dashboard:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(102, 126, 234, 0.4);
        }

        .footer {
            background: rgba(255, 255, 255, 0.9);
            padding: 1.5rem 2rem;
            text-align: center;
            color: #718096;
            border-top: 1px solid #e2e8f0;
        }

        .user-greeting {
            background: #edf2f7;
            padding: 1rem 2rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            border-left: 4px solid #48bb78;
        }

        .user-greeting p {
            margin: 0;
            color: #4a5568;
            font-weight: 500;
        }

        .register-note {
            margin-top: 1.5rem;
            padding: 1rem;
            background: #fffaf0;
            border-radius: 8px;
            border-left: 4px solid #ed8936;
            font-size: 0.9rem;
            color: #744210;
        }

        @media (max-width: 768px) {
            .welcome-card {
                padding: 2rem;
                margin: 1rem;
            }
            
            .welcome-title {
                font-size: 2rem;
            }
            
            .features {
                grid-template-columns: 1fr;
            }
            
            .header {
                flex-direction: column;
                gap: 1rem;
            }
            
            .auth-buttons-container {
                flex-direction: column;
                align-items: center;
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
        <div class="welcome-card">
            <div class="university-icon">🎓</div>
            
            <h1 class="welcome-title">Bienvenido al Sistema</h1>
            <p class="welcome-subtitle">
                Plataforma integral de gestión académica para la asignación eficiente 
                de salones, horarios y recursos universitarios.
            </p>

            @auth
                <div class="user-greeting">
                    <p>👋 ¡Hola de nuevo, {{ auth()->user()->name ?? auth()->user()->email }}! Ya estás autenticado en el sistema.</p>
                </div>
                <div class="auth-section">
                    <a href="{{ route('dashboard') }}" class="btn-dashboard">
                        🚀 Ir al Dashboard
                    </a>
                </div>
            @else
                <div class="auth-section">
                    <div class="auth-buttons-container">
                        <a href="{{ route('login') }}" class="btn-login">
                            🔑 Iniciar Sesión
                        </a>
                        <a href="{{ route('register') }}" class="btn-register">
                            📝 Solicitar Acceso
                        </a>
                    </div>
                    <div class="register-note">
                        <strong>¿Primera vez en el sistema?</strong> Solicita acceso y un administrador te asignará el rol apropiado.
                    </div>
                </div>
            @endauth
        </div>
    </div>

    <footer class="footer">
        <p>&copy; 2024 Sistema de Asignación de Salones - Universidad. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
<h1 style='color: red; text-align: center; margin-top: 50px;'>¡HOLA MUNDO DESDE GITHUB ACTIONS! 🚀</h1>
