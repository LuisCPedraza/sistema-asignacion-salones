@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Editar Restricción de Asignación: {{ $restriccionAsignacion->id }}</h1>
    <form method="POST" action="{{ route('admin.restricciones_asignacion.update', $restriccionAsignacion) }}">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label for="recurso_type" class="block font-medium mb-1">Tipo de Recurso</label>
            <select name="recurso_type" id="recurso_type" class="border rounded px-2 py-1 w-full @error('recurso_type') border-red-500 @enderror" required onchange="toggleRecurso()">
                <option value="" disabled {{ old('recurso_type', $restriccionAsignacion->recurso_type) ? '' : 'selected' }}>Selecciona tipo</option>
                <option value="salon" {{ old('recurso_type', $restriccionAsignacion->recurso_type) == 'salon' ? 'selected' : '' }}>Salón</option>
                <option value="profesor" {{ old('recurso_type', $restriccionAsignacion->recurso_type) == 'profesor' ? 'selected' : '' }}>Profesor</option>
            </select>
            @error('recurso_type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4" id="recurso_id_div">
            <label for="recurso_id" class="block font-medium mb-1">Recurso</label>
            <select name="recurso_id" id="recurso_id" class="border rounded px-2 py-1 w-full @error('recurso_id') border-red-500 @enderror" required>
                <option value="" disabled {{ old('recurso_id', $restriccionAsignacion->recurso_id) ? '' : 'selected' }}>Selecciona recurso</option>
                @foreach ($salones as $salon)
                    <option value="{{ $salon->id }}" {{ old('recurso_id', $restriccionAsignacion->recurso_id) == $salon->id ? 'selected' : '' }}>{{ $salon->codigo }}</option>
                @endforeach
                @foreach ($profesores as $profesor)
                    <option value="{{ $profesor->id }}" {{ old('recurso_id', $restriccionAsignacion->recurso_id) == $profesor->id ? 'selected' : '' }}>{{ $profesor->user->name }}</option>
                @endforeach
            </select>
            @error('recurso_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label for="tipo_restriccion" class="block font-medium mb-1">Tipo de Restricción</label>
            <select name="tipo_restriccion" id="tipo_restriccion" class="border rounded px-2 py-1 w-full @error('tipo_restriccion') border-red-500 @enderror" required>
                <option value="" disabled {{ old('tipo_restriccion', $restriccionAsignacion->tipo_restriccion) ? '' : 'selected' }}>Selecciona tipo</option>
                <option value="horario" {{ old('tipo_restriccion', $restriccionAsignacion->tipo_restriccion) == 'horario' ? 'selected' : '' }}>Horario</option>
                <option value="capacidad" {{ old('tipo_restriccion', $restriccionAsignacion->tipo_restriccion) == 'capacidad' ? 'selected' : '' }}>Capacidad</option>
                <option value="especial" {{ old('tipo_restriccion', $restriccionAsignacion->tipo_restriccion) == 'especial' ? 'selected' : '' }}>Especial</option>
            </select>
            @error('tipo_restriccion') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label for="valor" class="block font-medium mb-1">Valor (JSON)</label>
            <textarea name="valor" id="valor" rows="3" class="border rounded px-2 py-1 w-full @error('valor') border-red-500 @enderror" placeholder='{"dias": ["lun", "mie"], "max_cap": 30}'>{{ old('valor', json_encode($restriccionAsignacion->valor ?? [], JSON_PRETTY_PRINT)) }}</textarea>
            @error('valor') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label for="activo" class="block font-medium mb-1">Activo</label>
            <input type="checkbox" name="activo" id="activo" value="1" {{ old('activo', $restriccionAsignacion->activo) ? 'checked' : '' }} class="rounded @error('activo') border-red-500 @enderror">
            @error('activo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Actualizar Restricción</button>
        <a href="{{ route('admin.restricciones_asignacion.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 ml-2">Cancelar</a>
    </form>
</div>
@endsection

<script>
function toggleRecurso() {
    const type = document.getElementById('recurso_type').value;
    const select = document.getElementById('recurso_id');
    select.innerHTML = '<option value="" disabled selected>Selecciona recurso</option>';
    if (type === 'salon') {
        @foreach ($salones as $salon)
            select.innerHTML += '<option value="{{ $salon->id }}" {{ old('recurso_id', $restriccionAsignacion->recurso_id) == $salon->id ? 'selected' : '' }}>{{ $salon->codigo }}</option>';
        @endforeach
    } else if (type === 'profesor') {
        @foreach ($profesores as $profesor)
            select.innerHTML += '<option value="{{ $profesor->id }}" {{ old('recurso_id', $restriccionAsignacion->recurso_id) == $profesor->id ? 'selected' : '' }}>{{ $profesor->user->name }}</option>';
        @endforeach
    }
}
</script>
