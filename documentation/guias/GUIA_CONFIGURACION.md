# 📋 Guía de Configuración: Carreras, Semestres y Materias

## 1️⃣ **DÓNDE SE CONFIGURAN LOS DATOS**

### Opción A: Editar el Seeder (RECOMENDADO)
**Archivo:** `database/seeders/CareerSpecificMallaHorariaSeeder.php`

**Cambios que puedes hacer:**

#### A.1 - Cambiar nombres de Carreras
```php
// Línea ~66-67
$career1 = Career::create([
    'name' => 'Tecnología en Desarrollo de Software',  // ← CAMBIAR AQUÍ
    'description' => 'Especialización en desarrollo de aplicaciones y software',
    'duration_semesters' => 6,
]);

$career2 = Career::create([
    'name' => 'Administración de Empresas',  // ← CAMBIAR AQUÍ
    'description' => 'Especialización en gestión y administración empresarial',
    'duration_semesters' => 6,
]);
```

#### A.2 - Cambiar cantidad de Semestres por Carrera
```php
// Línea ~72-76 (CARRERA 1) y ~95-99 (CARRERA 2)
'duration_semesters' => 6,  // ← CAMBIAR ESTE NÚMERO (ej: 8, 10, etc)

// También cambiar el loop:
for ($i = 1; $i <= 6; $i++) {  // ← CAMBIAR 6 POR EL NUEVO NÚMERO
```

#### A.3 - Cambiar Materias por Carrera
```php
// Línea ~78-85 (CARRERA 1 - TDS)
$subjectsTDS = [
    ['name' => 'Introducción a la Programación', 'code' => 'PROG101', 'specialty' => 'Programación'],
    ['name' => 'Programación Orientada a Objetos', 'code' => 'PROG201', 'specialty' => 'Programación'],
    // ← AGREGAR O MODIFICAR AQUÍ
];

// Línea ~103-110 (CARRERA 2 - ADMIN)
$subjectsADMIN = [
    ['name' => 'Contabilidad I', 'code' => 'CONT101', 'specialty' => 'Contabilidad'],
    ['name' => 'Contabilidad II', 'code' => 'CONT201', 'specialty' => 'Contabilidad'],
    // ← AGREGAR O MODIFICAR AQUÍ
];
```

#### A.4 - Cambiar cuántas materias por Semestre
```php
// Línea ~127 (CARRERA 1)
$subjectsToAssign = $subjectsCollection1->shuffle()->take(4);  // ← CAMBIAR 4

// Línea ~152 (CARRERA 2)
$subjectsToAssign = $subjectsCollection2->shuffle()->take(4);  // ← CAMBIAR 4
```

#### A.5 - Cambiar clases por semana
```php
// Línea ~181
$classesPerWeek = rand(12, 16);  // ← CAMBIAR ESTOS NÚMEROS
                                  // ej: rand(8, 10) para menos clases
```

---

## 2️⃣ **CÓMO APLICAR LOS CAMBIOS**

### Paso 1: Editar el Seeder
1. Abre `database/seeders/CareerSpecificMallaHorariaSeeder.php`
2. Realiza los cambios que necesites
3. Guarda el archivo

### Paso 2: Ejecutar el Seeder
```bash
php artisan db:seed --class=CareerSpecificMallaHorariaSeeder --force
```

### Paso 3: Ver los cambios en el navegador
- Abre: `http://localhost:8000/visualizacion/horario/malla-semestral`
- Los datos se reflejan automáticamente

---

## 3️⃣ **ESTRUCTURA DE DATOS**

```
CARRERA (Career)
├── Semestre 1 (Semester)
│   ├── Grupo A - Diurno (08:00-18:00)
│   │   ├── Materia 1 → Profesor 1 → Salón A → Franjas Horarias Diurnas
│   │   ├── Materia 2 → Profesor 2 → Salón B → Franjas Horarias Diurnas
│   │   └── Materia 3 → ...
│   └── Grupo B - Nocturno (18:00-22:00)
│       ├── Materia 1 → Profesor 1 → Salón A → Franjas Horarias Nocturnas
│       ├── Materia 2 → Profesor 2 → Salón B → Franjas Horarias Nocturnas
│       └── Materia 3 → ...
├── Semestre 2
│   ├── Grupo A - Diurno
│   └── Grupo B - Nocturno
└── ... (más semestres)
```

---

## 4️⃣ **CAMPOS QUE PUEDES EDITAR**

### Carrera
- `name`: Nombre de la carrera (ej: "Ingeniería de Sistemas")
- `description`: Descripción (ej: "Formación en TI")
- `duration_semesters`: Cantidad de semestres (ej: 8)

### Materia/Asignatura
- `name`: Nombre (ej: "Algoritmos Avanzados")
- `code`: Código único (ej: "ALG301")
- `specialty`: Especialidad (ej: "Programación")
- `credit_hours`: Créditos (ej: 3)
- `lecture_hours`: Horas teoría (ej: 2)
- `lab_hours`: Horas práctica (ej: 1)

### Grupo (StudentGroup)
- Automáticamente **Grupo A (Diurno)** y **Grupo B (Nocturno)**
- `student_count`: Estudiantes por grupo (aleatorio 25-35)

---

## 5️⃣ **VISTAS QUE MUESTRAN LOS DATOS**

| Vista | Ubicación | Muestra |
|-------|-----------|---------|
| **Malla Horaria** | `/visualizacion/horario/malla-semestral` | Horario semanal (Grupo A y B) |
| **Asignación Auto.** | `/asignacion/automatica` | Reorganiza asignaciones |
| **Resultados** | `/asignacion/resultados` | Estadísticas de asignaciones |

---

## 6️⃣ **EJEMPLO: CREAR UNA NUEVA CARRERA**

### Paso 1: Editar el Seeder
```php
// Agregar después de CARRERA 2:

$career3 = Career::create([
    'name' => 'Ingeniería Civil',  // ← NUEVA CARRERA
    'description' => 'Especialización en infraestructura',
    'duration_semesters' => 8,
    'is_active' => true,
]);

// Crear 8 semestres
for ($i = 1; $i <= 8; $i++) {
    Semester::create([
        'career_id' => $career3->id,
        'number' => $i,
        'description' => "Semestre {$i}",
        'is_active' => true,
    ]);
}

// Crear materias
$subjectsINGENIERIA = [
    ['name' => 'Cálculo I', 'code' => 'MAT101', 'specialty' => 'Matemáticas'],
    ['name' => 'Física I', 'code' => 'FIS101', 'specialty' => 'Física'],
    // ... más materias
];

// Vincular materias y generar asignaciones...
```

### Paso 2: Ejecutar
```bash
php artisan db:seed --class=CareerSpecificMallaHorariaSeeder --force
```

---

## 7️⃣ **ALGORITMO DE ASIGNACIÓN AUTOMÁTICA**

**Archivo:** `app/Modules/Asignacion/Services/AssignmentAlgorithm.php`

**Qué hace:**
- Toma las asignaciones existentes
- **Reorganiza** (shuffles):
  - Profesores (asigna nuevos al azar)
  - Aulas (asigna nuevas al azar)
  - Franjas horarias (asigna nuevas al azar)
  - Días (lunes a sábado al azar)
- **Mantiene** la materia y grupo de estudiantes igual

**No crea nuevas asignaciones, solo reacomoda las existentes.**

---

## 🎯 **RESUMEN**

1. ✅ **Editar Seeder** → Cambiar carreras, semestres, materias
2. ✅ **Ejecutar Seeder** → `php artisan db:seed --class=CareerSpecificMallaHorariaSeeder --force`
3. ✅ **Ver en navegador** → `/visualizacion/horario/malla-semestral`
4. ✅ **Algoritmo organiza** → Solo reacomoda posiciones (profesor, aula, franja)

¿Necesitas hacer cambios en algo específico?
