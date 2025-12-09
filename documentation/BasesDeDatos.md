# Modelo de Datos (Laravel + PostgreSQL/Supabase en prod, SQLite en local)

Este documento describe el esquema actual según las migraciones de Laravel del proyecto.

## Motores y entornos
- **Producción**: PostgreSQL (Supabase). Sin particiones ni triggers personalizados.
- **Desarrollo local**: SQLite (`database/database.sqlite`).
- Las migraciones y seeders de Laravel crean/ajustan el esquema; no se usa un script SQL monolítico.

## Tablas principales (según migraciones vigentes)

### Autenticación y Roles
- **roles**
  - Campos: `id`, `name`, `slug` (único), `description`, `is_active`, `created_at`, `updated_at`.
  - Seed inicial: `administrador`, `secretaria_administrativa`, `coordinador`, `secretaria_coordinacion`, `coordinador_infraestructura`, `secretaria_infraestructura`, `profesor`, `profesor_invitado`.

- **users**
  - Campos: `id`, `name`, `email` (único), `email_verified_at`, `password`, `role_id` (FK nullable → roles), `is_active` (bool), `temporary_access` (bool), `access_expires_at`, `temporary_access_expires_at`, `remember_token`, `created_at`, `updated_at`.
  - Relación con roles vía `role_id` (nullable).

### Recursos académicos y docentes
- **teachers**
  - Campos: `id`, `first_name`, `last_name`, `email` (único), `phone`, `specialty`, `specialties` (JSON), `curriculum`, `years_experience`, `academic_degree`, `is_active`, `availability_notes`, `weekly_availability` (JSON), `special_assignments`, `user_id` (FK nullable → users), `created_at`, `updated_at`.

- **teacher_availabilities**
  - Campos: `id`, `teacher_id` (FK cascade), `day_of_week` (enum: lunes-sábado), `start_time`, `end_time`, `is_available` (bool), `notes`, `created_at`, `updated_at`.
  - Unique: (`teacher_id`, `day_of_week`, `start_time`).

- **careers**
  - Campos: `id`, `name`, `description`, `duration_semesters` (int), `is_active`, `created_at`, `updated_at`.

- **semesters**
  - Campos: `id`, `career_id` (FK cascade), `number` (1-7), `description`, `is_active`, `created_at`, `updated_at`.
  - Unique: (`career_id`, `number`).

- **subjects**
  - Campos: Ver en `app/Models/Subject.php` o en migración dedicada.

- **course_schedules**
  - Campos: `id`, `subject_id` (FK cascade), `semester_id` (FK cascade), `position_in_semester`, `required_teachers`, `created_at`, `updated_at`.
  - Unique: (`subject_id`, `semester_id`).

### Académico y Estudiantes
- **academic_periods**
  - Campos: `id`, `name`, `start_date`, `end_date`, `is_active`, `created_at`, `updated_at`.
  - Seed: Período 1 y 2 (2024), Período 1 (2025).

- **student_groups**
  - Campos: `id`, `name`, `level`, `number_of_students` (int), `special_features`, `is_active`, `academic_period_id` (FK nullable), `semester_id` (FK nullable), `group_type` (enum: 'A'|'B'), `schedule_type` (enum: 'day'|'night'), `created_at`, `updated_at`.

### Infraestructura
- **buildings**
  - Campos base: `id`, `created_at`, `updated_at` (con ampliaciones en migraciones posteriores).

- **classrooms**
  - Campos: `id`, `name`, `code` (único), `capacity` (int, default 30), `resources` (JSON), `location`, `special_features`, `is_active`, `restrictions`, `type` (enum: `aula|laboratorio|auditorio|sala_reuniones|taller`), `floor`, `wing`, `building_id` (FK nullable), `created_at`, `updated_at`.

- **classroom_availabilities**
  - Campos: `id`, `classroom_id` (FK cascade), `day_of_week` (enum: lunes-sábado), `start_time`, `end_time`, `is_available` (bool), `notes`, `availability_type` (enum: `regular|maintenance|reserved|special_event`), `created_at`, `updated_at`.
  - Unique: (`classroom_id`, `day_of_week`, `start_time`).

### Horarios y Asignaciones
- **time_slots**
  - Campos: `id`, `name` (ej. "Bloque 1"), `start_time`, `end_time`, `schedule_type` (enum: `day|night`), `duration_minutes` (int, default 120), `created_at`, `updated_at`.

- **assignments**
  - Campos: `id`, `student_group_id` (FK cascade), `teacher_id` (FK cascade), `classroom_id` (FK cascade), `day` (enum: `monday|tuesday|...|saturday`), `start_time`, `end_time`, `is_confirmed` (bool, default false), `notes`, `score` (decimal 8,2, default 0), `time_slot_id` (FK nullable), `subject_id` (FK nullable), `assigned_by_algorithm` (bool, default false), `created_at`, `updated_at`.
  - Índices: (`day`, `start_time`, `end_time`), `is_confirmed`.

- **schedules**
  - Campos base: `id`, `created_at`, `updated_at` (tabla esqueleto, puede ampliarse).

### Infraestructura del Sistema
- **sessions**
  - Tabla estándar de Laravel para `SESSION_DRIVER=database`.

- **cache**
  - Tabla estándar de Laravel para almacenamiento de caché en BD.

- **jobs**
  - Tabla estándar de Laravel para colas de trabajos en BD.

## Relaciones destacadas
- **Roles ↔ Users**: `users.role_id` → `roles.id` (nullable).
- **Teachers ↔ Users**: `teachers.user_id` → `users.id` (nullable).
- **Availabilities**: `teacher_availabilities.teacher_id` y `classroom_availabilities.classroom_id` con cascada.
- **Académico**: 
  - `careers` ← `semesters` ← `course_schedules` → `subjects`.
  - `student_groups.academic_period_id` → `academic_periods`.
  - `student_groups.semester_id` → `semesters`.
- **Asignaciones**: `assignments` vincula `student_group_id`, `teacher_id`, `classroom_id`, `subject_id`, `time_slot_id`.

## Notas importantes
- No hay triggers, particiones ni vistas materializadas definidos en las migraciones.
- Tabla `buildings` y `schedules` son esqueletos que pueden expandirse en migraciones futuras.
- El proyecto depende de las migraciones y seeders; cambios estructurales deben hacerse mediante nuevas migraciones.
- En desarrollo: `php artisan migrate --seed` con SQLite.
- En producción (Render + Supabase): mismas migraciones sobre PostgreSQL; variables env en Render para DB_HOST, DB_PORT, DB_PASSWORD_PROD, etc.
