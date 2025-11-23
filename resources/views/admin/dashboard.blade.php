<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Administrador</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f5f5f5;
        }
        .dashboard {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #667eea;
            padding-bottom: 15px;
        }
        .welcome {
            color: #333;
        }
        .btn-logout {
            background: #e53e3e;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            border: none;
            cursor: pointer;
        }
        .btn-logout:hover {
            background: #c53030;
        }
        .modules {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        .module-card {
            background: #667eea;
            color: white;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            text-decoration: none;
            transition: transform 0.3s;
        }
        .module-card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <div class="header">
            <h1 class="welcome">Bienvenido, Administrador</h1>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">Cerrar Sesión</button>
            </form>
        </div>

        <h2>Módulos Disponibles</h2>
        <div class="modules">
            <div class="module-card">
                <h3>Gestión de Usuarios</h3>
                <p>Administrar cuentas y roles</p>
            </div>
            <div class="module-card">
                <h3>Reportes</h3>
                <p>Generar reportes del sistema</p>
            </div>
            <div class="module-card">
                <h3>Configuración</h3>
                <p>Configurar parámetros del sistema</p>
            </div>
        </div>
    </div>
</body>
</html>