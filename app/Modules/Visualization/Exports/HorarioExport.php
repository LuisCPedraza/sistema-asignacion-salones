<?php

namespace App\Modules\Visualization\Exports;

use App\Models\Assignment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Support\Facades\Auth;

class HorarioExport implements FromCollection, WithHeadings, WithTitle
{
    protected $type;
    protected $userId;

    public function __construct($type = 'semestral', $userId = null)
    {
        $this->type = $type;
        $this->userId = $userId;
    }

    public function collection()
    {
        $query = Assignment::with(['group', 'teacher', 'classroom']);
        if ($this->type === 'personal') {
            $query->where('teacher_id', $this->userId);
        }

        return $query->get()->map(function ($assignment) {
            return [
                'Grupo' => $assignment->group->name ?? 'N/A',
                'Profesor' => $assignment->teacher->name ?? 'N/A',
                'Salón' => $assignment->classroom->name ?? 'N/A',
                'Día' => ucfirst($assignment->day),
                'Inicio' => $assignment->start_time->format('H:i'),
                'Fin' => $assignment->end_time->format('H:i'),
                'Score' => round($assignment->score * 100, 0) . '%',
            ];
        });
    }

    public function headings(): array
    {
        return ['Grupo', 'Profesor', 'Salón', 'Día', 'Inicio', 'Fin', 'Score'];
    }

    public function title(): string
    {
        return $this->type === 'personal' ? 'Mi Horario Personal' : 'Horario Semestral';
    }
}