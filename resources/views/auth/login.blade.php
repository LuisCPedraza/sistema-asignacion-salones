<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Sistema de Asignación</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .login-wrapper {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            width: 100%;
            max-width: 1000px;
            align-items: center;
        }

        /* Left side - Information */
        .login-info {
            color: #e2e8f0;
        }

        .login-info h2 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 1.5rem;
            line-height: 1.3;
        }

        .login-info-gradient {
            background: linear-gradient(135deg, #2563eb, #34d399);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .login-info p {
            font-size: 20px;
            color: #cbd5e1;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .info-features {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .feature-item {
            display: flex;
            gap: 1rem;
            align-items: flex-start;
        }

        .feature-icon {
            font-size: 1.5rem;
            min-width: 2rem;
        }

        .feature-content h4 {
            color: #e2e8f0;
            margin-bottom: 0.25rem;
            font-size: 24px;
        }

        .feature-content p {
            color: #cbd5e1;
            font-size: 20px;
            margin: 0;
        }

        /* Right side - Form */
        .login-form-container {
            background: rgba(30, 41, 59, 0.5);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(148, 163, 184, 0.2);
            border-radius: 16px;
            padding: 2.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .login-header {
            margin-bottom: 2rem;
            text-align: center;
        }

        .login-logo {
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .login-title {
            font-size: 24px;
            font-weight: 700;
            color: #e2e8f0;
            margin-bottom: 0.5rem;
        }

        .login-subtitle {
            color: #cbd5e1;
            font-size: 20px;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            color: #e2e8f0;
            font-weight: 600;
            margin-bottom: 0.75rem;
            font-size: 20px;
        }

        .form-input {
            width: 100%;
            padding: 0.875rem 1rem;
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(148, 163, 184, 0.3);
            border-radius: 8px;
            color: #e2e8f0;
            font-size: 20px;
            transition: all 0.3s ease;
        }

        .form-input::placeholder {
            color: rgba(148, 163, 184, 0.6);
        }

        .form-input:focus {
            outline: none;
            border-color: #2563eb;
            background: rgba(15, 23, 42, 0.8);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .remember-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            font-size: 20px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .remember-me input[type="checkbox"] {
            cursor: pointer;
            accent-color: #2563eb;
        }

        .remember-me label {
            color: #cbd5e1;
            cursor: pointer;
        }

        .forgot-password {
            color: #2563eb;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .forgot-password:hover {
            color: #1d4ed8;
            text-decoration: underline;
        }

        .btn-login {
            width: 100%;
            padding: 0.875rem;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 700;
            font-size: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
            margin-bottom: 1rem;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .register-link-container {
            text-align: center;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(148, 163, 184, 0.2);
        }

        .register-link-container p {
            color: #cbd5e1;
            margin-bottom: 1rem;
            font-size: 20px;
        }

        .btn-register {
            width: 100%;
            padding: 0.875rem;
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 20px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.3);
        }

        .errors {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.5);
            color: #fca5a5;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-size: 20px;
        }

        .errors p {
            margin: 0.5rem 0;
        }

        .errors p:first-child {
            margin-top: 0;
        }

        .errors p:last-child {
            margin-bottom: 0;
        }

        .success-message {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.5);
            color: #86efac;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-size: 20px;
        }

        @media (max-width: 768px) {
            .login-wrapper {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .login-info {
                text-align: center;
                display: none;
            }

            .login-form-container {
                padding: 2rem;
            }

            .login-info h2 {
                font-size: 2rem;
            }

            .login-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        
        <!-- Left side - Information -->
        <div class="login-info">
            <h2>Bienvenido a <span class="login-info-gradient">nuestra plataforma</span></h2>
            <p>Accede al sistema de gestión académica más avanzado. Gestiona grupos, profesores y asignaciones de forma eficiente.</p>
            
            <div class="info-features">
                <div class="feature-item">
                    <div class="feature-icon">📊</div>
                    <div class="feature-content">
                        <h4>Gestión Avanzada</h4>
                        <p>Control completo de grupos y horarios</p>
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">⚡</div>
                    <div class="feature-content">
                        <h4>Asignación Inteligente</h4>
                        <p>Algoritmos automáticos de optimización</p>
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">📈</div>
                    <div class="feature-content">
                        <h4>Reportes Detallados</h4>
                        <p>Análisis y estadísticas en tiempo real</p>
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">🔐</div>
                    <div class="feature-content">
                        <h4>Seguridad Garantizada</h4>
                        <p>Acceso basado en roles y permisos</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right side - Form -->
        <div class="login-form-container">
            <div class="login-header">
                <div class="login-logo">🔑 Acceso</div>
                <h1 class="login-title">Inicia Sesión</h1>
                <p class="login-subtitle">Ingresa tus credenciales para continuar</p>
            </div>

            @if(session('success'))
                <div class="success-message">
                    ✅ {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="errors">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <input type="email" id="email" name="email" class="form-input" placeholder="tu.email@universidad.edu" value="{{ old('email') }}" required autofocus>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" id="password" name="password" class="form-input" placeholder="Tu contraseña segura" required>
                </div>

                <div class="remember-section">
                    <div class="remember-me">
                        <input type="checkbox" id="remember" name="remember" value="on">
                        <label for="remember">Recuérdame</label>
                    </div>
                    <a href="#" class="forgot-password" title="Contacta al administrador para recuperar tu contraseña">¿Olvidaste tu contraseña?</a>
                </div>

                <button type="submit" class="btn-login">
                    🔓 Iniciar Sesión
                </button>
            </form>

            <div class="register-link-container">
                <p>¿No tienes cuenta aún?</p>
                <a href="{{ route('register') }}" class="btn-register">
                    📝 Solicitar Acceso
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
    <script>
        setTimeout(function() {
            const successMessage = document.querySelector('.success-message');
            if (successMessage) {
                successMessage.style.display = 'none';
            }
        }, 5000);
    </script>
    @endif
</body>
</html>