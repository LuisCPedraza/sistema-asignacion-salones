<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tomar Asistencia - Sistema de Asignación</title>
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
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 1.25rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 20px rgba(16, 185, 129, 0.3);
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
            max-width: 1000px;
            margin: 0 auto;
            padding: 2rem;
        }
        .breadcrumb {
            margin-bottom: 2rem;
            font-size: 0.9rem;
        }
        .breadcrumb a {
            color: #10b981;
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
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            margin-bottom: 2rem;
            border-left: 5px solid #10b981;
        }
        .course-info h1 {
            color: #1e293b;
            margin-bottom: 1rem;
            font-size: 1.8rem;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }
        .info-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #64748b;
        }
        .info-icon {
            color: #10b981;
        }
        .date-selector {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            margin-bottom: 2rem;
        }
        .date-selector label {
            display: block;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }
        .date-selector input {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        .date-selector input:focus {
            outline: none;
            border-color: #10b981;
        }
        .attendance-form {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }
        .form-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #e2e8f0;
        }
        .form-header h2 {
            color: #1e293b;
        }
        .bulk-actions {
            display: flex;
            gap: 0.5rem;
        }
        .btn-bulk {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.85rem;
            transition: all 0.3s;
        }
        .btn-bulk-all-present {
            background: #d1fae5;
            color: #065f46;
        }
        .btn-bulk-all-present:hover {
            background: #a7f3d0;
        }
        .students-list {
            display: grid;
            gap: 1rem;
        }
        .student-row {
            background: #f8fafc;
            padding: 1.25rem;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s;
        }
        .student-row:hover {
            background: #f1f5f9;
        }
        .student-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .student-number {
            background: #10b981;
            color: white;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
        .student-details h3 {
            color: #1e293b;
            font-size: 1rem;
            margin-bottom: 0.25rem;
        }
        .student-code {
            color: #64748b;
            font-size: 0.85rem;
        }
        .attendance-options {
            display: flex;
            gap: 0.5rem;
        }
        .attendance-btn {
            padding: 0.625rem 1rem;
            border: 2px solid transparent;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.3s;
            background: white;
        }
        .attendance-btn input[type="radio"] {
            display: none;
        }
        .attendance-btn.presente {
            border-color: #d1fae5;
            color: #065f46;
        }
        .attendance-btn.presente:hover,
        .attendance-btn.presente.active {
            background: #d1fae5;
            border-color: #10b981;
        }
        .attendance-btn.ausente {
            border-color: #fecaca;
            color: #991b1b;
        }
        .attendance-btn.ausente:hover,
        .attendance-btn.ausente.active {
            background: #fecaca;
            border-color: #ef4444;
        }
        .attendance-btn.tardanza {
            border-color: #fed7aa;
            color: #92400e;
        }
        .attendance-btn.tardanza:hover,
        .attendance-btn.tardanza.active {
            background: #fed7aa;
            border-color: #f59e0b;
        }
        .attendance-btn.justificado {
            border-color: #dbeafe;
            color: #1e3a8a;
        }
        .attendance-btn.justificado:hover,
        .attendance-btn.justificado.active {
            background: #dbeafe;
            border-color: #3b82f6;
        }
        .form-actions {
            margin-top: 2rem;
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
        }
        .btn-submit {
            background: linear-gradient(135deg, #10b981, #059669);
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
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
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
        .error-message {
            background: #fee2e2;
            border: 1px solid #ef4444;
            color: #991b1b;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 2rem;
        }
        @media (max-width: 768px) {
            .student-row {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }
            .attendance-options {
                width: 100%;
                display: grid;
                grid-template-columns: repeat(2, 1fr);
            }
            .attendance-btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">✅ Tomar Asistencia</div>
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
            <a href="{{ route('profesor.asistencias.index') }}">Asistencias</a> / 
            <span>Tomar Asistencia</span>
        </div>

        <a href="{{ route('profesor.asistencias.index') }}" class="btn-back">← Volver a Asistencias</a>

        <div class="course-info">
            <h1>{{ $assignment->subject->name ?? 'Materia sin nombre' }}</h1>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-icon">🏷️</span>
                    <span>{{ $assignment->group->name ?? 'Sin grupo' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-icon">🏢</span>
                    <span>{{ $assignment->classroom->code ?? 'Sin aula' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-icon">🎓</span>
                    <span>{{ count($estudiantes) }} estudiantes</span>
                </div>
                @if($assignment->day && $assignment->start_time)
                    <div class="info-item">
                        <span class="info-icon">🕐</span>
                        <span>{{ ucfirst($assignment->day) }}: {{ substr($assignment->start_time, 0, 5) }} - {{ substr($assignment->end_time, 0, 5) }}</span>
                    </div>
                @endif
            </div>
        </div>

        <div class="date-selector">
            <label for="fecha">📅 Fecha de Asistencia</label>
            <input type="date" id="fecha" name="fecha" value="{{ $fecha }}" max="{{ date('Y-m-d') }}">
        </div>

        @if($errors->any())
            <div class="error-message">
                <strong>⚠️ Error al guardar la asistencia:</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('profesor.asistencias.guardar', $assignment->id) }}" class="attendance-form">
            @csrf
            <input type="hidden" name="fecha" id="fecha-hidden" value="{{ $fecha }}">
            
            <div class="form-header">
                <h2>📋 Lista de Estudiantes</h2>
                <div class="bulk-actions">
                    <button type="button" class="btn-bulk btn-bulk-all-present" onclick="marcarTodos('presente')">
                        ✅ Marcar Todos Presentes
                    </button>
                </div>
            </div>

            <div class="students-list">
                @foreach($estudiantes as $index => $estudiante)
                    @php
                        $estadoPrevio = $asistenciasPrevias[$estudiante['id']] ?? null;
                    @endphp
                    <div class="student-row">
                        <div class="student-info">
                            <div class="student-number">{{ $index + 1 }}</div>
                            <div class="student-details">
                                <h3>{{ $estudiante['nombre'] }}</h3>
                                <div class="student-code">Código: {{ $estudiante['codigo'] }}</div>
                            </div>
                        </div>
                        <div class="attendance-options">
                            <label class="attendance-btn presente {{ $estadoPrevio === 'presente' ? 'active' : '' }}" data-student="{{ $estudiante['id'] }}" data-status="presente">
                                <input type="radio" name="asistencias[{{ $estudiante['id'] }}]" value="presente" required @checked($estadoPrevio === 'presente')>
                                ✅ Presente
                            </label>
                            <label class="attendance-btn ausente {{ $estadoPrevio === 'ausente' ? 'active' : '' }}" data-student="{{ $estudiante['id'] }}" data-status="ausente">
                                <input type="radio" name="asistencias[{{ $estudiante['id'] }}]" value="ausente" @checked($estadoPrevio === 'ausente')>
                                ❌ Ausente
                            </label>
                            <label class="attendance-btn tardanza {{ $estadoPrevio === 'tardanza' ? 'active' : '' }}" data-student="{{ $estudiante['id'] }}" data-status="tardanza">
                                <input type="radio" name="asistencias[{{ $estudiante['id'] }}]" value="tardanza" @checked($estadoPrevio === 'tardanza')>
                                ⏰ Tardanza
                            </label>
                            <label class="attendance-btn justificado {{ $estadoPrevio === 'justificado' ? 'active' : '' }}" data-student="{{ $estudiante['id'] }}" data-status="justificado">
                                <input type="radio" name="asistencias[{{ $estudiante['id'] }}]" value="justificado" @checked($estadoPrevio === 'justificado')>
                                📝 Justificado
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="form-actions">
                <a href="{{ route('profesor.asistencias.index') }}" class="btn-cancel">Cancelar</a>
                <button type="submit" class="btn-submit">💾 Guardar Asistencia</button>
            </div>
        </form>
    </div>

    <script>
        // Actualizar fecha oculta cuando cambia el selector
        document.getElementById('fecha').addEventListener('change', function() {
            document.getElementById('fecha-hidden').value = this.value;
        });

        // Manejar clics en botones de asistencia
        document.querySelectorAll('.attendance-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const studentId = this.dataset.student;
                const status = this.dataset.status;
                
                // Remover active de todos los botones de este estudiante
                document.querySelectorAll(`[data-student="${studentId}"]`).forEach(b => {
                    b.classList.remove('active');
                });
                
                // Agregar active al botón clickeado
                this.classList.add('active');
                
                // Marcar el radio button
                this.querySelector('input[type="radio"]').checked = true;
            });
        });

        // Función para marcar todos con un estado
        function marcarTodos(estado) {
            document.querySelectorAll(`[data-status="${estado}"]`).forEach(btn => {
                btn.click();
            });
        }
    </script>
</body>
</html>
