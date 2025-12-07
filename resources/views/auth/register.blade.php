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
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #333;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .register-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 3rem;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .logo {
            text-align: center;
            margin-bottom: 2rem;
            font-size: 1.5rem;
            font-weight: bold;
            color: #4a5568;
        }

        .register-title {
            text-align: center;
            font-size: 2rem;
            color: #2d3748;
            margin-bottom: 0.5rem;
            font-weight: 700;
        }

        .register-subtitle {
            text-align: center;
            color: #718096;
            margin-bottom: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            color: #4a5568;
            font-weight: 500;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .btn-register {
            width: 100%;
            background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%);
            color: white;
            padding: 1rem;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 1rem;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(237, 137, 54, 0.4);
        }

        .btn-login {
            width: 100%;
            background: transparent;
            color: #4a5568;
            padding: 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: block;
            text-align: center;
        }

        .btn-login:hover {
            border-color: #667eea;
            color: #667eea;
        }

        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }

        .alert-success {
            background: #f0fff4;
            border: 1px solid #9ae6b4;
            color: #2f855a;
        }

        .alert-error {
            background: #fed7d7;
            border: 1px solid #feb2b2;
            color: #c53030;
        }

        .errors {
            background: #fed7d7;
            border: 1px solid #feb2b2;
            color: #c53030;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }

        .errors ul {
            margin: 0;
            padding-left: 1.5rem;
        }

        .approval-notice {
            background: #fffaf0;
            border: 1px solid #fed7aa;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border-left: 4px solid #ed8936;
        }

        .approval-notice h3 {
            color: #744210;
            margin-bottom: 0.5rem;
            font-size: 1.1rem;
        }

        .approval-notice p {
            color: #744210;
            margin: 0.25rem 0;
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .register-container {
                padding: 2rem;
                margin: 1rem;
            }
            
            .register-title {
                font-size: 1.75rem;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="logo">
            🏫 Sistema de Asignación de Salones
        </div>
        <h1 class="register-title">Solicitar Acceso</h1>
        <p class="register-subtitle">Regístrate para solicitar acceso al sistema</p>
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
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
            <p>• Tu cuenta requerirá aprobación de un administrador</p>
            <p>• Se te asignará un rol apropiado según tu perfil</p>
            <p>• Recibirás una notificación cuando tu cuenta esté activa</p>
        </div>
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="form-group">
                <label for="name" class="form-label">Nombre Completo</label>
                <input type="text" id="name" name="name" class="form-input" value="{{ old('name') }}" required autofocus>
            </div>
            <div class="form-group">
                <label for="email" class="form-label">Correo Electrónico</label>
                <input type="email" id="email" name="email" class="form-input" value="{{ old('email') }}" required>
            </div>
            <div class="form-group">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" id="password" name="password" class="form-input" required>
            </div>
            <div class="form-group">
                <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" required>
            </div>
            <button type="submit" class="btn-register">
                📝 Solicitar Acceso
            </button>
        </form>
        <a href="{{ route('login') }}" class="btn-login">
            🔑 ¿Ya tienes cuenta? Inicia Sesión
        </a>
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
</body>
</html>