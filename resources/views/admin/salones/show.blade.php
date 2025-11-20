@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Detalle de Salón: {{ $salon->id }}</h1>
    <div class="bg-white p-6 rounded shadow">
        <p><strong>ID:</strong> {{ $salon->id }}</p>
        <p><strong>Código:</strong> {{ $salon->codigo }}</p>
        <p><strong>Capacidad:</strong> {{ $salon->capacidad }}</p>
        <p><strong>Ubicación:</strong> {{ $salon->ubicacion }}</p>
        <p><strong>Activo:</strong> <span class="px-2 py-1 bg-{{ $salon->activo ? 'green' : 'red' }}-200 rounded">{{ $salon->activo ? 'Sí' : 'No' }}</span></p>
        <p><strong>Creado:</strong> {{ $salon->created_at->format('d/m/Y H:i') }}</p>
        <p><strong>Actualizado:</strong> {{ $salon->updated_at->format('d/m/Y H:i') }}</p>
        <!-- Nueva sección for horarios -->
        <div class="mt-4">
            <h3 class="text-lg font-semibold mb-2">Disponibilidad Horaria</h3>
            <table class="min-w-full bg-gray-50 border">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b">Día</th>
                        <th class="py-2 px-4 border-b">8-9</th>
                        <th class="py-2 px-4 border-b">9-10</th>
                        <th class="py-2 px-4 border-b">10-11</th>
                        <th class="py-2 px-4 border-b">11-12</th>
                        <th class="py-2 px-4 border-b">14-15</th>
                        <th class="py-2 px-4 border-b">15-16</th>
                        <th class="py-2 px-4 border-b">16-17</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach (['lun' => 'Lunes', 'mar' => 'Martes', 'mie' => 'Miércoles', 'jue' => 'Jueves', 'vie' => 'Viernes'] as $key => $dia)
                        <tr>
                            <td class="py-2 px-4 border-b">{{ $dia }}</td>
                            @foreach (['8', '9', '10', '11', '14', '15', '16'] as $hora)
                                <td class="py-2 px-4 border-b text-center">
                                    <span class="px-2 py-1 bg-green-200 rounded text-xs">Sí</span>
                                    @if (isset($salon->horarios[$key]) && in_array($hora, $salon->horarios[$key]))
                                        <span class="px-2 py-1 bg-green-200 rounded text-xs">Sí</span>
                                    @else
                                        <span class="px-2 py-1 bg-red-200 rounded text-xs">No</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-4">
        <a href="{{ route('admin.salones.edit', $salon) }}" class="bg-blue-500 text-white px-4 py-2 rounded mr-2">Editar</a>
        <a href="{{ route('admin.salones.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Volver a Lista</a>
    </div>
</div>
@endsection
