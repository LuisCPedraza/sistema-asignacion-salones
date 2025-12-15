<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horario Semestral</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 10px;
        }
        
        .header h1 {
            font-size: 18px;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        
        .header p {
            color: #666;
            font-size: 9px;
        }
        
        .generated-date {
            text-align: right;
            font-size: 8px;
            color: #999;
            margin-bottom: 15px;
        }
        
        .table-container {
            margin: 20px 0;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        thead {
            background-color: #2c3e50;
            color: white;
        }
        
        th {
            padding: 8px 6px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #2c3e50;
            font-size: 9px;
        }
        
        td {
            padding: 6px;
            border: 1px solid #ddd;
            font-size: 9px;
        }
        
        tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
        }
        
        tbody tr:hover {
            background-color: #f0f0f0;
        }
        
        .day {
            font-weight: bold;
            background-color: #ecf0f1;
        }
        
        .time {
            font-weight: bold;
            color: #2c3e50;
        }
        
        .group {
            background-color: #e8f4f8;
            font-weight: 500;
        }
        
        .teacher {
            color: #2c3e50;
        }
        
        .classroom {
            background-color: #fef5e7;
        }
        
        .stats {
            margin-top: 20px;
            padding: 10px;
            background-color: #ecf0f1;
            border-radius: 4px;
            font-size: 9px;
        }
        
        .stats-row {
            display: inline-block;
            margin-right: 20px;
            margin-bottom: 5px;
        }
        
        .stats-label {
            font-weight: bold;
            color: #2c3e50;
        }
        
        .no-data {
            text-align: center;
            padding: 20px;
            color: #999;
            font-style: italic;
        }
        
        .day-label {
            text-transform: capitalize;
            color: #2c3e50;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1><i class="fas fa-calendar-alt"></i> Horario Semestral Completo</h1>
        <p>Visualización consolidada de todas las asignaciones del semestre</p>
    </div>
    
    <div class="generated-date">
        Generado: {{ now()->format('d/m/Y H:i:s') }}
    </div>
    
    @if($assignments->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Día</th>
                    <th>Hora</th>
                    <th>Grupo</th>
                    <th>Carrera</th>
                    <th>Materia</th>
                    <th>Profesor</th>
                    <th>Salón</th>
                    <th>Ubicación</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $currentDay = null;
                    $dayNames = [
                        'monday' => 'Lunes',
                        'tuesday' => 'Martes',
                        'wednesday' => 'Miércoles',
                        'thursday' => 'Jueves',
                        'friday' => 'Viernes',
                        'saturday' => 'Sábado'
                    ];
                @endphp
                
                @foreach($assignments as $assignment)
                    <tr>
                        <td class="day-label">
                            {{ $dayNames[$assignment->day] ?? $assignment->day }}
                        </td>
                        <td class="time">
                            @php
                                $startRaw = optional($assignment->timeSlot)->start_time ?? $assignment->start_time ?? null;
                                $endRaw = optional($assignment->timeSlot)->end_time ?? $assignment->end_time ?? null;
                                try {
                                    $startFmt = $startRaw ? \Carbon\Carbon::parse($startRaw)->format('H:i') : null;
                                    $endFmt = $endRaw ? \Carbon\Carbon::parse($endRaw)->format('H:i') : null;
                                } catch (\Exception $e) {
                                    $startFmt = $startRaw;
                                    $endFmt = $endRaw;
                                }
                            @endphp
                            {{ $startFmt ?? 'N/A' }} - {{ $endFmt ?? 'N/A' }}
                        </td>
                        @php
                            $groupName = $assignment->group->name ?? 'N/A';
                            $scheduleType = $assignment->group->schedule_type ?? null; // day/night
                            $scheduleLabel = $scheduleType === 'night' ? 'Nocturno' : ($scheduleType === 'day' ? 'Diurno' : $scheduleType);
                            $semesterNum = optional(optional($assignment->group)->semester)->number;
                            $careerFromGroup = optional(optional($assignment->group)->career)->name;
                            $careerFromSubject = optional(optional($assignment->subject)->career)->name;
                            $careerName = $careerFromGroup ?? $careerFromSubject ?? 'N/A';
                            $subjectCode = optional($assignment->subject)->code;
                            $subjectName = optional($assignment->subject)->name ?? 'N/A';
                        @endphp
                        <td class="group">
                            {{ $groupName }}
                            @if($semesterNum)
                                <div style="font-size:10px; color:#2c3e50;">Semestre {{ $semesterNum }}</div>
                            @endif
                            @if($scheduleLabel)
                                <div style="font-size:10px; color:#2c3e50;">Jornada {{ $scheduleLabel }}</div>
                            @endif
                        </td>
                        <td>
                            {{ $careerName }}
                        </td>
                        <td>
                            @if($subjectCode)
                                <div style="font-size:10px; color:#2c3e50;">{{ $subjectCode }}</div>
                            @endif
                            <div>{{ $subjectName }}</div>
                        </td>
                        <td class="teacher">
                            {{ $assignment->teacher->first_name ?? '' }} {{ $assignment->teacher->last_name ?? '' }}
                        </td>
                        <td class="classroom">
                            {{ $assignment->classroom->name ?? 'N/A' }}
                        </td>
                        <td>
                            @php
                                $locRaw = optional(optional($assignment->classroom)->building)->location ?? null;
                                $loc = is_string($locRaw) ? trim($locRaw) : $locRaw;
                            @endphp
                            @if(!empty($loc))
                                <span style="padding:3px 8px; background:#eef1f6; color:#2c3e50; border:1px solid #2c3e50; border-radius:3px; font-size:8px; font-weight:bold;">{{ strtoupper($loc) }}</span>
                            @else
                                N/A
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="stats">
            <div class="stats-row">
                <span class="stats-label">Total de Asignaciones:</span>
                {{ $assignments->count() }}
            </div>
            <div class="stats-row">
                <span class="stats-label">Grupos Únicos:</span>
                {{ $assignments->pluck('student_group_id')->unique()->count() }}
            </div>
            <div class="stats-row">
                <span class="stats-label">Profesores Únicos:</span>
                {{ $assignments->pluck('teacher_id')->unique()->count() }}
            </div>
            <div class="stats-row">
                <span class="stats-label">Salones Únicos:</span>
                {{ $assignments->pluck('classroom_id')->unique()->count() }}
            </div>
        </div>
    @else
        <div class="no-data">
            No hay asignaciones que coincidan con los filtros seleccionados.
        </div>
    @endif
</body>
</html>
