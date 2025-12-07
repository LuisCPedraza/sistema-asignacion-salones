<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Modules\GestionAcademica\Models\StudentGroup;

class StudentGroupSeeder extends Seeder
{
    public function run()
    {
        $groups = [
            [
                'name' => 'Ingeniería de Software - Grupo A',
                'level' => 'Universitario',
                'student_count' => 35,
                'special_features' => 'Necesita laboratorio de computación con IDE Java y Python',
                'is_active' => true,
            ],
            [
                'name' => 'Matemáticas Avanzadas - Grupo Nocturno',
                'level' => 'Universitario',
                'student_count' => 28,
                'special_features' => 'Requiere pizarra inteligente y software de cálculo simbólico',
                'is_active' => true,
            ],
            [
                'name' => 'Bases de Datos II - Laboratorio',
                'level' => 'Universitario',
                'student_count' => 25,
                'special_features' => 'Laboratorio con SQL Server, MySQL y MongoDB instalados',
                'is_active' => true,
            ],
            [
                'name' => 'Redes y Comunicaciones - Práctica',
                'level' => 'Universitario',
                'student_count' => 20,
                'special_features' => 'Laboratorio de redes con equipos Cisco y herramientas de simulación',
                'is_active' => true,
            ],
            [
                'name' => 'Inteligencia Artificial - Grupo Investigación',
                'level' => 'Posgrado',
                'student_count' => 15,
                'special_features' => 'Laboratorio con GPUs para entrenamiento de modelos, acceso a datasets',
                'is_active' => true,
            ],
            [
                'name' => 'Desarrollo Web Full Stack',
                'level' => 'Diplomado',
                'student_count' => 30,
                'special_features' => 'Laboratorio con VS Code, Node.js, React y bases de datos',
                'is_active' => true,
            ],
            [
                'name' => 'Ciberseguridad - Grupo Avanzado',
                'level' => 'Posgrado',
                'student_count' => 18,
                'special_features' => 'Laboratorio aislado para prácticas de ethical hacking',
                'is_active' => true,
            ],
            [
                'name' => 'Arquitectura de Software - Teoría',
                'level' => 'Universitario',
                'student_count' => 40,
                'special_features' => 'Aula con proyector y sistema de audio para presentaciones',
                'is_active' => true,
            ],
            [
                'name' => 'UX/UI Design - Taller',
                'level' => 'Diplomado',
                'student_count' => 22,
                'special_features' => 'Aula con tablets gráficas y software de diseño (Figma, Adobe)',
                'is_active' => true,
            ],
            [
                'name' => 'Estadística Aplicada - Grupo B',
                'level' => 'Universitario',
                'student_count' => 32,
                'special_features' => 'Aula con computadoras con R y Python para análisis estadístico',
                'is_active' => true,
            ],
            [
                'name' => 'Física Computacional',
                'level' => 'Universitario',
                'student_count' => 26,
                'special_features' => 'Laboratorio con software de simulación física (MATLAB, Comsol)',
                'is_active' => true,
            ],
            [
                'name' => 'Química Orgánica - Laboratorio',
                'level' => 'Universitario',
                'student_count' => 24,
                'special_features' => 'Laboratorio de química con equipos de seguridad y reactivos',
                'is_active' => true,
            ],
            [
                'name' => 'Programación para Niños',
                'level' => 'Curso Corto',
                'student_count' => 20,
                'special_features' => 'Aula con computadoras coloridas y software Scratch',
                'is_active' => true,
            ],
            [
                'name' => 'Machine Learning - Práctica',
                'level' => 'Posgrado',
                'student_count' => 16,
                'special_features' => 'Laboratorio con Jupyter Notebooks y acceso a Google Colab Pro',
                'is_active' => true,
            ],
            [
                'name' => 'Sistemas Operativos - Kernel',
                'level' => 'Universitario',
                'student_count' => 28,
                'special_features' => 'Laboratorio con máquinas virtuales para prácticas de kernel',
                'is_active' => true,
            ]
        ];

        foreach ($groups as $group) {
            StudentGroup::create($group);
        }
    }
}