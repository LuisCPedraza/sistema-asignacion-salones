<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Estudiante - Sistema de Asignación</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: #f8fafc;
            color: #334155;
        }
        .header {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
            color: white;
            padding: 1.25rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 20px rgba(139, 92, 246, 0.3);
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
            padding: 0.625rem 1.25rem;
            text-decoration: none;
            border-radius: 8px;
            border: 1px solid rgba(255,255,255,0.3);
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-logout:hover {
            background: rgba(255,255,255,0.3);
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
        }
        .breadcrumb {
            margin-bottom: 2rem;
            font-size: 0.9rem;
        }
        .breadcrumb a {
            color: #8b5cf6;
            text-decoration: none;
        }
        .breadcrumb a:hover {
            text-decoration: underline;
        }
        .btn-back {
            background: #64748b;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 2rem;
            transition: all 0.3s;
        }
        .btn-back:hover {
            background: #475569;
        }
        .course-info {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            margin-bottom: 2rem;
            border-left: 5px solid #8b5cf6;
        }
        .course-info h2 {
            color: #1e293b;
            margin-bottom: 0.75rem;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            color: #64748b;
            font-size: 0.9rem;
        }
        .form-container {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }
        .form-header {
            margin-bottom: 2rem;
        }
        .form-header h1 {
            color: #1e293b;
            margin-bottom: 0.5rem;
        }
        .form-header p {
            color: #64748b;
        }
        .error-message {
            background: #fee2e2;
            border: 1px solid #ef4444;
            color: #991b1b;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }
        .error-message ul {
            margin-top: 0.5rem;
            margin-left: 1.5rem;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-group label {
            display: block;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }
        .form-group label .required {
            color: #ef4444;
        }
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #8b5cf6;
        }
        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }
        .form-group small {
            display: block;
            color: #64748b;
            font-size: 0.85rem;
            margin-top: 0.25rem;
        }
        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 2px solid #e2e8f0;
        }
        .btn-submit {
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
            color: white;
            padding: 0.875rem 2rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 600;
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(139, 92, 246, 0.3);
        }
        .btn-cancel {
            background: #e2e8f0;
            color: #475569;
            padding: 0.875rem 2rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
        }
        .btn-cancel:hover {
            background: #cbd5e1;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">➕ Registrar Estudiante</div>
        <div class="user-info">
            <span>{{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">Cerrar Sesión</button>
            </form>
        </div>
    </div>

    <div class="container">
        <div class="breadcrumb">
            <a href="{{ route('profesor.dashboard') }}">Dashboard</a> / 
            <a href="{{ route('profesor.estudiantes.index') }}">Estudiantes</a> / 
            <span>Registrar Nuevo</span>
        </div>

        <a href="{{ route('profesor.estudiantes.index') }}" class="btn-back">← Volver a Estudiantes</a>

        <div class="course-info">
            <h2>📚 {{ $assignment->subject->name ?? 'Materia' }}</h2>
            <div class="info-grid">
                <div>🏷️ Grupo: {{ $assignment->group->name ?? 'Sin grupo' }}</div>
                <div>🏢 Aula: {{ $assignment->classroom->code ?? 'Sin aula' }}</div>
                <div>📚 Semestre: {{ $assignment->group->semester->name ?? 'Sin semestre' }}</div>
            </div>
        </div>

        <div class="form-container">
            <div class="form-header">
                <h1>🎓 Nuevo Estudiante</h1>
                <p>Completa el formulario para registrar un nuevo estudiante en este grupo.</p>
            </div>

            @if($errors->any())
                <div class="error-message">
                    <strong>⚠️ Por favor corrija los siguientes errores:</strong>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('profesor.estudiantes.store') }}">
                @csrf
                <input type="hidden" name="assignment_id" value="{{ $assignment->id }}">

                <div class="form-group">
                    <label for="codigo">
                        Código del Estudiante <span class="required">*</span>
                    </label>
                    <input type="text" 
                           id="codigo" 
                           name="codigo" 
                           value="{{ old('codigo') }}" 
                           required
                           placeholder="Ej: 2020-001">
                    <small>Código único que identifica al estudiante en la institución.</small>
                </div>

                <div class="form-group">
                    <label for="nombre">
                        Nombre(s) <span class="required">*</span>
                    </label>
                    <input type="text" 
                           id="nombre" 
                           name="nombre" 
                           value="{{ old('nombre') }}" 
                           required
                           placeholder="Ej: Juan Carlos">
                </div>

                <div class="form-group">
                    <label for="apellido">
                        Apellido(s) <span class="required">*</span>
                    </label>
                    <input type="text" 
                           id="apellido" 
                           name="apellido" 
                           value="{{ old('apellido') }}" 
                           required
                           placeholder="Ej: García López">
                </div>

                <div class="form-group">
                    <label for="email">
                        Email <span class="required">*</span>
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           required
                           placeholder="Ej: juan.garcia@universidad.edu">
                    <small>Email institucional del estudiante.</small>
                </div>

                <div class="form-group">
                    <label for="telefono">
                        Teléfono
                    </label>
                    <input type="tel" 
                           id="telefono" 
                           name="telefono" 
                           value="{{ old('telefono') }}"
                           placeholder="Ej: +1234567890">
                    <small>Número de contacto (opcional).</small>
                </div>

                <div class="form-group">
                    <label for="observaciones">
                        Observaciones
                    </label>
                    <textarea id="observaciones" 
                              name="observaciones" 
                              placeholder="Notas adicionales sobre el estudiante (opcional)">{{ old('observaciones') }}</textarea>
                </div>

                <div class="form-actions">
                    <a href="{{ route('profesor.estudiantes.index') }}" class="btn-cancel">Cancelar</a>
                    <button type="submit" class="btn-submit">💾 Registrar Estudiante</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
