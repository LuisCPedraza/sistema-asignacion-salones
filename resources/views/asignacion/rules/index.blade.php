<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración de Reglas - Sistema de Asignación</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f8fafc; color: #334155; }
        .container { max-width: 800px; margin: 0 auto; padding: 2rem; }
        .header { background: white; padding: 1rem 2rem; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 2rem; }
        .rules-grid { display: grid; gap: 1rem; }
        .rule-card { background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .rule-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; }
        .rule-details { display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 1rem; align-items: center; }
        .weight-input { width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 4px; }
        .btn { padding: 0.5rem 1rem; border: none; border-radius: 6px; cursor: pointer; text-decoration: none; display: inline-block; font-weight: 500; }
        .btn-primary { background: #3b82f6; color: white; }
        .btn-success { background: #10b981; color: white; }
        .btn-danger { background: #ef4444; color: white; }
        .inactive { opacity: 0.6; background: #f3f4f6; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>⚙️ Configuración de Reglas de Asignación</h1>
            <p>Configura los pesos y prioridades del algoritmo de asignación automática</p>
        </div>

        @if(session('success'))
            <div style="background: #d1fae5; color: #065f46; padding: 1rem; border-radius: 6px; margin-bottom: 1rem;">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('asignacion.rules.update-weights') }}" method="POST">
            @csrf
            <div class="rules-grid">
                @foreach($rules as $rule)
                    <div class="rule-card {{ $rule->is_active ? '' : 'inactive' }}">
                        <div class="rule-header">
                            <h3>{{ $rule->name }}</h3>
                            <form action="{{ route('asignacion.rules.toggle', $rule) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn {{ $rule->is_active ? 'btn-danger' : 'btn-success' }}">
                                    {{ $rule->is_active ? 'Desactivar' : 'Activar' }}
                                </button>
                            </form>
                        </div>
                        <div class="rule-details">
                            <div>
                                <strong>Parámetro:</strong> {{ $rule->parameter }}<br>
                                <small>Controla: 
                                    @if($rule->parameter == 'capacity') Capacidad del salón
                                    @elseif($rule->parameter == 'teacher_availability') Disponibilidad del profesor
                                    @elseif($rule->parameter == 'classroom_availability') Disponibilidad del salón
                                    @elseif($rule->parameter == 'proximity') Proximidad entre clases
                                    @elseif($rule->parameter == 'resources') Recursos requeridos
                                    @endif
                                </small>
                            </div>
                            <div>
                                <label for="weight_{{ $rule->id }}"><strong>Peso:</strong></label>
                                <input type="number" 
                                       id="weight_{{ $rule->id }}" 
                                       name="rules[{{ $loop->index }}][weight]" 
                                       value="{{ $rule->weight }}" 
                                       step="0.01" 
                                       min="0" 
                                       max="1" 
                                       class="weight-input"
                                       {{ $rule->is_active ? '' : 'disabled' }}>
                                <input type="hidden" name="rules[{{ $loop->index }}][id]" value="{{ $rule->id }}">
                            </div>
                            <div style="text-align: center;">
                                <strong>Impacto:</strong><br>
                                <span style="font-size: 1.5rem; color: #3b82f6;">
                                    {{ number_format($rule->weight * 100, 0) }}%
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div style="margin-top: 2rem; text-align: center;">
                <button type="submit" class="btn btn-primary" style="padding: 1rem 2rem;">
                    💾 Guardar Configuración
                </button>
            </div>
        </form>
    </div>
</body>
</html>