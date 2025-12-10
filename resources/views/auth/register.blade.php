<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Sistema de Asignación de Salones</title>
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
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .register-wrapper {
            width: 100%;
            max-width: 900px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            align-items: center;
        }

        /* Left side - Info */
        .register-info {
            color: white;
            padding: 2rem;
        }

        .register-info h2 {
            font-size: 2.2rem;
            margin-bottom: 1rem;
            line-height: 1.3;
            font-weight: 700;
        }

        .register-info-gradient {
            background: linear-gradient(135deg, #60a5fa 0%, #34d399 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .register-info p {
            color: #cbd5e1;
            font-size: 1.05rem;
            line-height: 1.7;
            margin-bottom: 2rem;
        }

        .steps-list {
            display: grid;
            gap: 1.5rem;
        }

        .step {
            display: flex;
            gap: 1.2rem;
        }

        .step-number {
            min-width: 40px;
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: white;
            flex-shrink: 0;
        }

        .step-content h4 {
            color: white;
            margin-bottom: 0.25rem;
            font-weight: 600;
        }

        .step-content p {
            color: #cbd5e1;
            font-size: 0.95rem;
            margin: 0;
        }

        /* Right side - Form */
        .register-form-container {
            background: rgba(30, 41, 59, 0.4);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(148, 163, 184, 0.2);
            border-radius: 16px;
            padding: 2.5rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .register-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .register-logo {
            font-size: 1.4rem;
            font-weight: 700;
            color: white;
            margin-bottom: 1rem;
        }

        .register-title {
            font-size: 1.8rem;
            color: white;
            margin-bottom: 0.5rem;
            font-weight: 700;
        }

        .register-subtitle {
            color: #cbd5e1;
            font-size: 0.95rem;
        }

        .form-group {
            margin-bottom: 1.3rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.6rem;
            color: #e2e8f0;
            font-weight: 600;
            font-size: 0.95rem;
        }

        .form-input {
            width: 100%;
            padding: 0.9rem 1.1rem;
            border: 1px solid rgba(148, 163, 184, 0.3);
            border-radius: 10px;
            font-size: 1rem;
            background: rgba(15, 23, 42, 0.4);
            color: white;
            transition: all 0.3s ease;
        }

        .form-input::placeholder {
            color: #64748b;
        }

        .form-input:focus {
            outline: none;
            border-color: #2563eb;
            background: rgba(15, 23, 42, 0.6);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.2);
        }

        .form-input:-webkit-autofill,
        .form-input:-webkit-autofill:hover,
        .form-input:-webkit-autofill:focus {
            -webkit-box-shadow: 0 0 0 1000px rgba(15, 23, 42, 0.6) inset;
            -webkit-text-fill-color: white;
        }

        .btn-register {
            width: 100%;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 1rem 1.5rem;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 1rem;
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.3);
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(16, 185, 129, 0.4);
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
        }

        .btn-login {
            width: 100%;
            background: transparent;
            color: #60a5fa;
            padding: 1rem 1.5rem;
            border: 2px solid rgba(96, 165, 250, 0.3);
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: block;
            text-align: center;
        }

        .btn-login:hover {
            border-color: #60a5fa;
            background: rgba(96, 165, 250, 0.1);
        }

        .alert {
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
            line-height: 1.5;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.2);
            border: 1px solid rgba(16, 185, 129, 0.4);
            color: #86efac;
        }

        .errors {
            background: rgba(239, 68, 68, 0.2);
            border: 1px solid rgba(239, 68, 68, 0.4);
            color: #fca5a5;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
        }

        .errors ul {
            margin: 0;
            padding-left: 1.5rem;
        }

        .errors li {
            margin-bottom: 0.3rem;
        }

        .approval-notice {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(59, 130, 246, 0.05) 100%);
            border: 1px solid rgba(59, 130, 246, 0.3);
            border-radius: 10px;
            padding: 1.3rem;
            margin-bottom: 1.5rem;
        }

        .approval-notice h3 {
            color: #60a5fa;
            margin-bottom: 0.8rem;
            font-size: 1rem;
            font-weight: 700;
        }

        .approval-notice ul {
            margin: 0;
            padding-left: 1.5rem;
        }

        .approval-notice li {
            color: #cbd5e1;
            margin-bottom: 0.4rem;
            font-size: 0.9rem;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .register-wrapper {
                grid-template-columns: 1fr;
            }

            .register-info {
                padding: 0;
            }

            .register-info h2 {
                font-size: 1.8rem;
            }

            .register-form-container {
                max-width: 500px;
                margin: 0 auto;
                width: 100%;
            }
        }

        @media (max-width: 768px) {
            .register-wrapper {
                gap: 1rem;
                padding: 1rem;
            }

            .register-form-container {
                padding: 2rem;
            }

            .register-info {
                display: none;
            }

            .register-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="register-wrapper">
        
        <!-- Left side - Information -->
        <div class="register-info">
            <h2>Únete a nuestra <span class="register-info-gradient">comunidad académica</span></h2>
            <p>Solicita acceso a la plataforma de gestión académica más avanzada. Tu cuenta será revisada y activada rápidamente.</p>
            
            <div class="steps-list">
                <div class="step">
                    <div class="step-number">1</div>
                    <div class="step-content">
                        <h4>Completa el formulario</h4>
                        <p>Ingresa tu información personal y datos de contacto</p>
                    </div>
                </div>

                <div class="step">
                    <div class="step-number">2</div>
                    <div class="step-content">
                        <h4>Envía tu solicitud</h4>
                        <p>Un administrador revisará tu solicitud de acceso</p>
                    </div>
                </div>

                <div class="step">
                    <div class="step-number">3</div>
                    <div class="step-content">
                        <h4>Obtén aprobación</h4>
                        <p>Se te asignará un rol y recibirás confirmación por correo</p>
                    </div>
                </div>

                <div class="step">
                    <div class="step-number">4</div>
                    <div class="step-content">
                        <h4>¡Accede al sistema!</h4>
                        <p>Inicia sesión y comienza a usar todas las funcionalidades</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right side - Form -->
        <div class="register-form-container">
            <div class="register-header">
                <div class="register-logo">🏫 Sistema de Asignación</div>
                <h1 class="register-title">Solicitar Acceso</h1>
                <p class="register-subtitle">Crea tu cuenta en el sistema</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success">
                    ✅ {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="errors">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="approval-notice">
                <h3>📋 Proceso de Aprobación</h3>
                <ul>
                    <li>Tu solicitud será revisada por un administrador</li>
                    <li>Se asignará un rol según tu perfil académico</li>
                    <li>Recibirás confirmación por correo electrónico</li>
                </ul>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="form-group">
                    <label for="name" class="form-label">Nombre Completo</label>
                    <input type="text" id="name" name="name" class="form-input" placeholder="Juan Pérez García" value="{{ old('name') }}" required autofocus>
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <input type="email" id="email" name="email" class="form-input" placeholder="tu.email@universidad.edu" value="{{ old('email') }}" required>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" id="password" name="password" class="form-input" placeholder="Mínimo 8 caracteres" required>
                </div>

                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" placeholder="Repite tu contraseña" required>
                </div>

                <button type="submit" class="btn-register">
                    📝 Solicitar Acceso
                </button>

                <a href="{{ route('login') }}" class="btn-login">
                    🔑 ¿Ya tienes cuenta? Inicia Sesión
                </a>
            </form>
        </div>
    </div>
        @if(session('success'))
        <script>
            setTimeout(function() {
                const alertSuccess = document.querySelector('.alert-success');
                if (alertSuccess) {
                    alertSuccess.style.display = 'none';
                }
            }, 6000);
        </script>
        @endif
    </div>
</body>
</html>