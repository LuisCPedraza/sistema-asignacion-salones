<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Asignaciones Manuales</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; }
        h1 { font-size: 18px; margin-bottom: 4px; }
        h3 { margin: 6px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 6px; }
        th { background: #f0f0f0; }
    </style>
</head>
<body>
    <h1>Asignaciones Manuales</h1>
    <p>Generado: {{ $generated_at }}</p>
    @if($period)
        <p>Período: {{ $period->name }} ({{ $period->start_date->format('d/m/Y') }} - {{ $period->end_date->format('d/m/Y') }})</p>
    @endif
    @if($selectedCareer)
        <p>Carrera ID: {{ $selectedCareer }}</p>
    @endif
    @if($selectedSemester)
        <p>Semestre ID: {{ $selectedSemester }}</p>
    @endif

    <table>
        <thead>
            <tr>
                <th>Materia</th>
                <th>Grupo</th>
                <th>Profesor</th>
                <th>Salón</th>
                <th>Día</th>
                <th>Inicio</th>
                <th>Fin</th>
            </tr>
        </thead>
        <tbody>
            @forelse($assignments as $a)
                <tr>
                    <td>{{ $a->subject->name ?? 'Sin materia' }}</td>
                    <td>{{ $a->group->name ?? 'Sin grupo' }}</td>
                    <td>{{ ($a->teacher->first_name ?? '') . ' ' . ($a->teacher->last_name ?? '') }}</td>
                    <td>{{ $a->classroom->name ?? 'Sin salón' }}</td>
                    <td>{{ ucfirst($a->day) }}</td>
                    <td>{{ \Carbon\Carbon::parse($a->start_time)->format('H:i') }}</td>
                    <td>{{ \Carbon\Carbon::parse($a->end_time)->format('H:i') }}</td>
                </tr>
            @empty
                <tr><td colspan="7">Sin asignaciones</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
