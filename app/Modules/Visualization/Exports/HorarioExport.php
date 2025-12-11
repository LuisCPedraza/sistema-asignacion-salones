<?php

namespace App\Modules\Visualization\Exports;

use App\Modules\Asignacion\Models\Assignment;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class HorarioExport
{
    protected string $type;
    protected ?int $userId;

    public function __construct(string $type = 'semestral', ?int $userId = null)
    {
        $this->type = $type;
        $this->userId = $userId ?? Auth::id();
    }

    public function toCSV(): string
    {
        $query = Assignment::with(['group', 'teacher', 'classroom', 'subject']);

        if ($this->type === 'personal') {
            $query->whereHas('teacher', function ($q) {
                $q->where('user_id', $this->userId);
            });
        }

        $assignments = $query->orderBy('day')->orderBy('start_time')->get();

        $dayNames = [
            'monday' => 'Lunes',
            'tuesday' => 'Martes',
            'wednesday' => 'Miércoles',
            'thursday' => 'Jueves',
            'friday' => 'Viernes',
            'saturday' => 'Sábado',
            'sunday' => 'Domingo',
        ];

        $csv = "Materia,Grupo,Profesor,Salón,Día,Hora Inicio,Hora Fin,Calidad\n";

        foreach ($assignments as $assignment) {
            $day = $dayNames[strtolower($assignment->day)] ?? $assignment->day;
            $score = round($assignment->score * 100, 1) . '%';
            $teacher = trim(($assignment->teacher->first_name ?? '') . ' ' . ($assignment->teacher->last_name ?? ''));

            $csv .= sprintf(
                "\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\"\n",
                $assignment->subject->name ?? 'Sin materia',
                $assignment->group->name ?? 'Sin grupo',
                $teacher,
                $assignment->classroom->name ?? 'Sin salón',
                $day,
                $assignment->start_time,
                $assignment->end_time,
                $score
            );
        }

        return $csv;
    }

    public function getFileName(): string
    {
        $timestamp = now()->format('Y-m-d_H-i-s');

        if ($this->type === 'personal') {
            $user = Auth::user();
            return "horario_personal_{$user->name}_{$timestamp}.csv";
        }

        return "horario_semestral_{$timestamp}.csv";
    }

    public function toHTML(): string
    {
        $query = Assignment::with(['group', 'teacher', 'classroom', 'subject']);

        if ($this->type === 'personal') {
            $query->whereHas('teacher', function ($q) {
                $q->where('user_id', $this->userId);
            });
        }

        $assignments = $query->orderBy('day')->orderBy('start_time')->get();
        $user = Auth::user();
        $timestamp = now()->format('d/m/Y H:i:s');

        $dayNames = [
            'monday' => 'Lunes',
            'tuesday' => 'Martes',
            'wednesday' => 'Miércoles',
            'thursday' => 'Jueves',
            'friday' => 'Viernes',
            'saturday' => 'Sábado',
            'sunday' => 'Domingo',
        ];

        $title = $this->type === 'personal'
            ? 'Horario Personal - ' . ($user->teacher->first_name ?? '') . ' ' . ($user->teacher->last_name ?? '')
            : 'Horario Semestral';

        $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>' . htmlspecialchars($title) . '</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; color: #333; line-height: 1.6; }
        .container { padding: 20px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #007bff; padding-bottom: 15px; }
        .header h1 { color: #007bff; font-size: 20px; margin-bottom: 5px; }
        .header p { font-size: 12px; color: #666; }
        .metadata { margin-bottom: 15px; font-size: 11px; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 10px; }
        table thead { background-color: #007bff; color: white; }
        table th { padding: 8px; text-align: left; font-weight: bold; border: 1px solid #dee2e6; }
        table td { padding: 6px 8px; border: 1px solid #dee2e6; }
        table tbody tr:nth-child(even) { background-color: #f9f9f9; }
        .quality-high { color: #28a745; font-weight: bold; }
        .quality-medium { color: #ffc107; font-weight: bold; }
        .quality-low { color: #dc3545; font-weight: bold; }
        .footer { margin-top: 20px; padding-top: 10px; border-top: 1px solid #dee2e6; font-size: 9px; color: #999; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>' . htmlspecialchars($title) . '</h1>
            <p>Sistema de Asignación de Salones</p>
        </div>
        <div class="metadata">
            <strong>Usuario:</strong> ' . htmlspecialchars($user->name) . ' | <strong>Generado:</strong> ' . $timestamp . '
        </div>
        <table>
            <thead>
                <tr>
                    <th>Materia</th>
                    <th>Grupo</th>
                    <th>Profesor</th>
                    <th>Salón</th>
                    <th>Día</th>
                    <th>Hora Inicio</th>
                    <th>Hora Fin</th>
                    <th>Calidad</th>
                </tr>
            </thead>
            <tbody>';

        foreach ($assignments as $assignment) {
            $day = $dayNames[strtolower($assignment->day)] ?? $assignment->day;
            $qualityPercent = round($assignment->score * 100, 1);
            $qualityClass = $qualityPercent >= 70 ? 'quality-high' : ($qualityPercent >= 40 ? 'quality-medium' : 'quality-low');
            $teacher = trim(($assignment->teacher->first_name ?? '') . ' ' . ($assignment->teacher->last_name ?? ''));

            $html .= '<tr>
                <td>' . htmlspecialchars($assignment->subject->name ?? 'Sin materia') . '</td>
                <td>' . htmlspecialchars($assignment->group->name ?? '') . '</td>
                <td>' . htmlspecialchars($teacher) . '</td>
                <td>' . htmlspecialchars($assignment->classroom->name ?? '') . '</td>
                <td>' . htmlspecialchars($day) . '</td>
                <td>' . substr($assignment->start_time, 0, 5) . '</td>
                <td>' . substr($assignment->end_time, 0, 5) . '</td>
                <td class="' . $qualityClass . '">' . $qualityPercent . '%</td>
            </tr>';
        }

        $html .= '</tbody>
            </table>
        <div class="footer">
            <p>Generado automáticamente por el Sistema de Asignación de Salones</p>
        </div>
    </div>
</body>
</html>';

        return $html;
    }

    public function getPdfFileName(): string
    {
        $timestamp = now()->format('Y-m-d_H-i-s');

        if ($this->type === 'personal') {
            $user = Auth::user();
            return "horario_personal_{$user->name}_{$timestamp}.pdf";
        }

        return "horario_semestral_{$timestamp}.pdf";
    }

    public function toPdf(): string
    {
        $html = $this->toHTML();
        $pdf = Pdf::loadHTML($html)
            ->setPaper('a4', 'landscape')
            ->setOption('margin-top', 10)
            ->setOption('margin-bottom', 10)
            ->setOption('margin-left', 10)
            ->setOption('margin-right', 10);
        
        return $pdf->output();
    }
}
