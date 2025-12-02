<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Asignación Manual - Sistema de Asignación</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f8fafc; color: #334155; }
        .container { max-width: 800px; margin: 0 auto; padding: 2rem; }
        .header { background: white; padding: 1rem 2rem; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 2rem; }
        .form-card { background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 1.5rem; }
        label { display: block; margin-bottom: 0.5rem; font-weight: 500; color: #374151; }
        select, input { width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 1rem; }
        .btn { padding: 0.75rem 1.5rem; border: none; border-radius: 6px; cursor: pointer; text-decoration: none; display: inline-block; font-weight: 500; }
        .btn-primary { background: #3b82f6; color: white; }
        .btn-secondary { background: #6b7280; color: white; }
        .error-message { color: #ef4444; font-size: 0.875rem; margin-top: 0.25rem; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>➕ Crear Asignación Manual</h1>
            <p>Asignar grupo a salón y profesor manualmente</p>
        </div>

        @if(session('error'))
            <div style="background: #fee2e2; color: #991b1b; padding: 1rem; border-radius: 6px; margin-bottom: 1rem;">
                {{ session('error') }}
            </div>
        @endif

        <div class="form-card">
            <form action="{{ route('asignacion.assignments.store') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label for="student_group_id">Grupo de Estudiantes *</label>
                    <select name="student_group_id" id="student_group_id" required>
                        <option value="">Seleccionar grupo</option>
                        @foreach($groups as $group)
                            <option value="{{ $group->id }}" {{ old('student_group_id') == $group->id ? 'selected' : '' }}>
                                {{ $group->name }} ({{ $group->number_of_students }} estudiantes)
                            </option>
                        @endforeach
                    </select>
                    @error('student_group_id')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="teacher_id">Profesor *</label>
                    <select name="teacher_id" id="teacher_id" required>
                        <option value="">Seleccionar profesor</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->name }} - {{ $teacher->specialty }}
                            </option>
                        @endforeach
                    </select>
                    @error('teacher_id')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="classroom_id">Salón *</label>
                    <select name="classroom_id" id="classroom_id" required>
                        <option value="">Seleccionar salón</option>
                        @foreach($classrooms as $classroom)
                            <option value="{{ $classroom->id }}" {{ old('classroom_id') == $classroom->id ? 'selected' : '' }}>
                                {{ $classroom->name }} (Capacidad: {{ $classroom->capacity }})
                            </option>
                        @endforeach
                    </select>
                    @error('classroom_id')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="day">Día de la Semana *</label>
                    <select name="day" id="day" required>
                        <option value="">Seleccionar día</option>
                        <option value="monday" {{ old('day') == 'monday' ? 'selected' : '' }}>Lunes</option>
                        <option value="tuesday" {{ old('day') == 'tuesday' ? 'selected' : '' }}>Martes</option>
                        <option value="wednesday" {{ old('day') == 'wednesday' ? 'selected' : '' }}>Miércoles</option>
                        <option value="thursday" {{ old('day') == 'thursday' ? 'selected' : '' }}>Jueves</option>
                        <option value="friday" {{ old('day') == 'friday' ? 'selected' : '' }}>Viernes</option>
                        <option value="saturday" {{ old('day') == 'saturday' ? 'selected' : '' }}>Sábado</option>
                    </select>
                    @error('day')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="start_time">Hora de Inicio *</label>
                    <input type="time" name="start_time" id="start_time" value="{{ old('start_time') }}" required>
                    @error('start_time')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="end_time">Hora de Fin *</label>
                    <input type="time" name="end_time" id="end_time" value="{{ old('end_time') }}" required>
                    @error('end_time')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div style="display: flex; gap: 1rem;">
                    <button type="submit" class="btn btn-primary">Crear Asignación</button>
                    <a href="{{ route('asignacion.assignments.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>