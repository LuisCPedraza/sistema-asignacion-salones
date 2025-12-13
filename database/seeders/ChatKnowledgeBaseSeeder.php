<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChatKnowledgeBaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $knowledge = [
            [
                'category' => 'general',
                'question' => '¿Qué es este sistema?',
                'answer' => 'Este es el Sistema de Asignación de Salones de la Universidad del Valle sede Zarzal. Permite gestionar horarios, profesores, grupos académicos y salones de manera eficiente.',
                'usage_count' => 0,
            ],
            [
                'category' => 'general',
                'question' => '¿Cómo creo una asignación?',
                'answer' => 'Para crear una asignación, ve a la sección de "Asignaciones" en el menú principal y haz clic en el botón "Nueva Asignación". Completa el formulario con el profesor, grupo, salón y horario.',
                'usage_count' => 0,
            ],
            [
                'category' => 'asignaciones',
                'question' => '¿Cómo modifico una asignación?',
                'answer' => 'Ve a la lista de asignaciones, busca la que deseas modificar y haz clic en el botón "Editar". Actualiza los datos necesarios y guarda los cambios.',
                'usage_count' => 0,
            ],
            [
                'category' => 'profesores',
                'question' => '¿Cómo registro un profesor?',
                'answer' => 'Accede al módulo de "Profesores", haz clic en "Agregar Profesor" y completa los datos: nombre, correo, disponibilidad horaria y tipo de contrato.',
                'usage_count' => 0,
            ],
            [
                'category' => 'profesores',
                'question' => '¿Qué es un profesor invitado?',
                'answer' => 'Un profesor invitado es un docente temporal que tiene un período de vigencia específico. El sistema te notificará cuando su contrato esté próximo a vencer.',
                'usage_count' => 0,
            ],
            [
                'category' => 'conflictos',
                'question' => '¿Qué es un conflicto horario?',
                'answer' => 'Un conflicto horario ocurre cuando un mismo profesor o salón está asignado a dos grupos diferentes en el mismo horario. El sistema detecta estos conflictos automáticamente.',
                'usage_count' => 0,
            ],
            [
                'category' => 'salones',
                'question' => '¿Cómo registro un salón?',
                'answer' => 'Ve al módulo de "Salones", haz clic en "Agregar Salón" y proporciona el nombre, edificio, capacidad y tipo de salón (aula, laboratorio, auditorio, etc.).',
                'usage_count' => 0,
            ],
            [
                'category' => 'disponibilidades',
                'question' => '¿Qué son las disponibilidades de profesores?',
                'answer' => 'Las disponibilidades son los horarios en los que un profesor está disponible para dictar clases. Se configuran por día de la semana y franja horaria.',
                'usage_count' => 0,
            ],
            [
                'category' => 'notificaciones',
                'question' => '¿Cómo funcionan las notificaciones?',
                'answer' => 'El sistema envía notificaciones automáticas por email cuando se detectan conflictos, cuando un profesor invitado está por vencer su contrato, o cuando se crean/modifican asignaciones.',
                'usage_count' => 0,
            ],
            [
                'category' => 'ayuda',
                'question' => '¿Cómo puedo reportar un problema?',
                'answer' => 'Si encuentras un problema, contacta al administrador del sistema o envía un correo a soporte técnico describiendo el inconveniente.',
                'usage_count' => 0,
            ],
        ];

        foreach ($knowledge as $item) {
            DB::table('chat_knowledge_base')->insert([
                'category' => $item['category'],
                'question' => $item['question'],
                'answer' => $item['answer'],
                'usage_count' => $item['usage_count'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('✅ Base de conocimiento del chatbot creada con ' . count($knowledge) . ' entradas.');
    }
}
