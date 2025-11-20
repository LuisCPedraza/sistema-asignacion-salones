@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Editar Profesor: {{ $profesor->user->name ?? 'No asociado' }}</h1>  # Fix: ?? for null user
    <form method="POST" action="{{ route('admin.profesores.update', $profesor) }}">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label for="usuario_id" class="block font-medium mb-1">Usuario (Profesor)</label>
            <select name="usuario_id" id="usuario_id" class="border rounded px-2 py-1 w-full @error('usuario_id') border-red-500 @enderror" required>
                <option value="" disabled>Selecciona un usuario</option>
                @foreach ($users as $user)
                    @if ($user->rol == 'profesor' || $user->rol == 'superadmin')
                        <option value="{{ $user->id }}" {{ old('usuario_id', $profesor->usuario_id) == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                    @endif
                @endforeach
            </select>
            @error('usuario_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label for="especialidades" class="block font-medium mb-1">Especialidades</label>
            <input type="text" name="especialidades" id="especialidades" value="{{ old('especialidades', $profesor->especialidades) }}" class="border rounded px-2 py-1 w-full @error('especialidades') border-red-500 @enderror" required>
            @error('especialidades') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label for="activo" class="block font-medium mb-1">Activo</label>
            <input type="checkbox" name="activo" id="activo" value="1" {{ old('activo', $profesor->activo) ? 'checked' : '' }} class="rounded @error('activo') border-red-500 @enderror">
            @error('activo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Nueva sección: Grid for Horarios -->
        <div class="mb-6">
            <label class="block font-medium mb-4">Disponibilidad Horaria (horas disponibles por día)</label>
            <table class="min-w-full bg-gray-50 border">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b">Día</th>
                        <th class="py-2 px-4 border-b text-center">8-9</th>
                        <th class="py-2 px-4 border-b text-center">9-10</th>
                        <th class="py-2 px-4 border-b text-center">10-11</th>
                        <th class="py-2 px-4 border-b text-center">11-12</th>
                        <th class="py-2 px-4 border-b text-center">14-15</th>
                        <th class="py-2 px-4 border-b text-center">15-16</th>
                        <th class="py-2 px-4 border-b text-center">16-17</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $horarios = $profesor->horarios;
                    @endphp
                    @foreach (['lun' => 'Lunes', 'mar' => 'Martes', 'mie' => 'Miércoles', 'jue' => 'Jueves', 'vie' => 'Viernes'] as $key => $dia)
                        <tr>
                            <td class="py-2 px-4 border-b">{{ $dia }}</td>
                            @foreach (['8', '9', '10', '11', '14', '15', '16'] as $hora)
                                <td class="py-2 px-4 border-b text-center">
                                    <input type="checkbox" name="horarios[{{ $key }}][{{ $hora }}]" value="{{ $hora }}" {{ isset($horarios[$key]) && in_array($hora, $horarios[$key]) ? 'checked' : '' }} class="rounded">
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Actualizar Profesor</button>
        <a href="{{ route('admin.profesores.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 ml-2">Cancelar</a>
    </form>
</div>
@endsection
