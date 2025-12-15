<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Activity;
use App\Modules\Asignacion\Models\Assignment;
use Carbon\Carbon;

class ActivitiesSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🎯 Creando actividades para cada assignment...');

        $assignments = Assignment::with(['subject', 'teacher'])->get();
        $created = 0;

        // Plantillas de actividades por tipo de materia
        $activityTemplates = [
            // Contabilidad y finanzas
            'CONTABILIDAD' => [
                ['title' => 'Análisis de Estados Financieros', 'desc' => 'Elaborar un análisis completo de balance general, estado de resultados y flujo de efectivo de una empresa real. Identificar ratios financieros clave.', 'score' => 100],
                ['title' => 'Registro de Asientos Contables', 'desc' => 'Realizar el registro contable de 20 transacciones comerciales aplicando el método de partida doble y elaborar el libro diario correspondiente.', 'score' => 80],
                ['title' => 'Caso Práctico: Cierre Contable', 'desc' => 'Ejecutar el proceso de cierre contable mensual incluyendo ajustes, depreciaciones, provisiones y generación de reportes finales.', 'score' => 120],
            ],
            'AUDITORIA' => [
                ['title' => 'Plan de Auditoría Integral', 'desc' => 'Diseñar un plan de auditoría para una empresa mediana, identificando áreas de riesgo, procedimientos de control y cronograma de ejecución.', 'score' => 100],
                ['title' => 'Evaluación de Controles Internos', 'desc' => 'Evaluar el sistema de control interno de un departamento financiero utilizando el marco COSO y emitir recomendaciones de mejora.', 'score' => 90],
                ['title' => 'Informe de Hallazgos de Auditoría', 'desc' => 'Documentar hallazgos de auditoría según normas internacionales, clasificar por severidad y proponer plan de acción correctivo.', 'score' => 110],
            ],
            'RIESGO' => [
                ['title' => 'Matriz de Riesgos Empresariales', 'desc' => 'Elaborar una matriz de riesgos identificando amenazas operativas, financieras y estratégicas con evaluación de probabilidad e impacto.', 'score' => 100],
                ['title' => 'Plan de Continuidad del Negocio', 'desc' => 'Diseñar un plan de continuidad operacional ante desastres, incluyendo análisis de impacto, estrategias de recuperación y pruebas de viabilidad.', 'score' => 120],
                ['title' => 'Evaluación de Riesgo Crediticio', 'desc' => 'Analizar el perfil de riesgo de tres clientes corporativos utilizando modelos cuantitativos y cualitativos para otorgamiento de crédito.', 'score' => 90],
            ],
            'CONTROL' => [
                ['title' => 'Diseño de Controles Preventivos', 'desc' => 'Diseñar controles preventivos y detectivos para un proceso de compras, documentando políticas, responsables y frecuencia de ejecución.', 'score' => 100],
                ['title' => 'Tablero de Control Gerencial', 'desc' => 'Crear un dashboard con indicadores clave de desempeño (KPI) para monitoreo ejecutivo de áreas financieras y operativas.', 'score' => 110],
                ['title' => 'Pruebas de Efectividad de Controles', 'desc' => 'Ejecutar pruebas de cumplimiento y efectividad sobre controles implementados, documentar resultados y proponer mejoras.', 'score' => 95],
            ],
            'ASEGURAMIENTO' => [
                ['title' => 'Marco de Aseguramiento de Calidad', 'desc' => 'Desarrollar un marco de aseguramiento de calidad para servicios profesionales, incluyendo estándares, métricas y procesos de revisión.', 'score' => 100],
                ['title' => 'Evaluación de Cumplimiento Normativo', 'desc' => 'Evaluar el cumplimiento de una organización con regulaciones aplicables (SOX, GDPR, ISO) y documentar brechas identificadas.', 'score' => 110],
                ['title' => 'Programa de Aseguramiento Independiente', 'desc' => 'Diseñar un programa de aseguramiento independiente que garantice objetividad, competencia y seguimiento de recomendaciones.', 'score' => 95],
            ],
            // Genérico para otras materias
            'DEFAULT' => [
                ['title' => 'Trabajo Práctico Integral', 'desc' => 'Desarrollar un trabajo práctico aplicando los conceptos fundamentales vistos en clase. Incluir análisis teórico y casos de aplicación real.', 'score' => 100],
                ['title' => 'Investigación y Presentación', 'desc' => 'Investigar un tema relevante de la materia, elaborar informe escrito y realizar presentación oral con apoyo visual.', 'score' => 90],
                ['title' => 'Caso de Estudio Empresarial', 'desc' => 'Analizar un caso de estudio real de una empresa, identificar problemáticas, proponer soluciones y justificar decisiones tomadas.', 'score' => 110],
            ],
        ];

        foreach ($assignments as $assignment) {
            // Verificar si ya tiene actividades
            $existingCount = Activity::where('assignment_id', $assignment->id)->count();
            if ($existingCount >= 3) {
                continue;
            }

            $subjectName = strtoupper($assignment->subject->name ?? '');
            
            // Seleccionar template según palabras clave en el nombre de la materia
            $templates = $activityTemplates['DEFAULT'];
            foreach (array_keys($activityTemplates) as $keyword) {
                if ($keyword !== 'DEFAULT' && str_contains($subjectName, $keyword)) {
                    $templates = $activityTemplates[$keyword];
                    break;
                }
            }

            // Crear 3 actividades para este assignment
            $toCreate = min(3, 3 - $existingCount);
            $selectedTemplates = array_slice($templates, 0, $toCreate);

            foreach ($selectedTemplates as $index => $template) {
                $dueDate = Carbon::now()->addWeeks($index + 2); // Fechas escalonadas

                Activity::create([
                    'assignment_id' => $assignment->id,
                    'title' => $template['title'],
                    'description' => $template['desc'],
                    'max_score' => $template['score'],
                    'due_date' => $dueDate,
                    'created_by' => optional($assignment->teacher)->user_id ?? 1,
                ]);
                $created++;
            }
        }

        $this->command->info("✅ Actividades creadas: {$created}");
    }
}
