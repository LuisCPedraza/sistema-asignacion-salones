<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Career;
use App\Models\Subject;
use App\Models\CourseSchedule;

class ReportCourseSchedules extends Command
{
    protected $signature = 'report:course-schedules {careerCode?}';
    protected $description = 'Reporte de materias por carrera/semestre con docente asignado y validaciones básicas';

    public function handle(): int
    {
        $codes = $this->argument('careerCode') ? [$this->argument('careerCode')] : ['3845','3841','3857','2724'];
        foreach ($codes as $code) {
            $career = Career::where('code', $code)->first();
            if (!$career) { $this->warn("Carrera {$code} no encontrada"); continue; }
            $this->info("Carrera {$code} - {$career->name}");
            $problems = [];

            foreach ($career->semesters()->orderBy('number')->get() as $sem) {
                $this->line("  Semestre {$sem->number}");
                $subjects = Subject::where('career_id', $career->id)->where('semester_level', $sem->number)->orderBy('code')->get();
                foreach ($subjects as $s) {
                    $cs = CourseSchedule::where('subject_id', $s->id)->where('semester_id', $sem->id)->first();
                    $teacherName = $cs && $cs->teacher ? strtoupper($cs->teacher->full_name) : 'SIN DOCENTE';
                    $this->line(sprintf("    [%s] %s => %s", $s->code, $s->name, $teacherName));
                    // Validaciones básicas: que no mezcle carreras/semestres y que tenga un único schedule por materia+semestre
                    $countPair = CourseSchedule::where('subject_id', $s->id)->where('semester_id', $sem->id)->count();
                    if ($countPair > 1) {
                        $problems[] = "Duplicado de horario para subject {$s->id} en semestre {$sem->id}";
                    }
                }
            }

            if ($problems) {
                $this->error('Problemas detectados:');
                foreach ($problems as $p) $this->line("  - ".$p);
            } else {
                $this->info('Sin problemas de duplicados en subject+semestre.');
            }
            $this->newLine();
        }
        return Command::SUCCESS;
    }
}
