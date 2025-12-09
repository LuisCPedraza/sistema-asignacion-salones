# Diagrama de Clases: Sistema de Asignación de Salones

## Introducción
Este diagrama de clases refleja la estructura real del proyecto Laravel. Basado en migraciones y modelos existentes, incluye:

- **Autenticación**: Role, User (con role_id nullable).
- **Académico**: Teacher, StudentGroup, Subject, Semester, Career, CourseSchedule, AcademicPeriod.
- **Infraestructura**: Classroom, Building, ClassroomAvailability.
- **Horarios**: TimeSlot, Assignment, TeacherAvailability.
- **Sistema**: AssignmentRule, Auditoria.

Los 8 roles del sistema son: `Administrador`, `Secretaria Administrativa`, `Coordinador`, `Secretaria de Coordinación`, `Coordinador de Infraestructura`, `Secretaria de Infraestructura`, `Profesor`, `Profesor Invitado`.

## Diagrama Mermaid
```mermaid
classDiagram
    %% ===== AUTENTICACIÓN Y USUARIOS =====
    class Role {
        -int id
        -String name
        -String slug (único)
        -String description
        -bool is_active
        +hasUsers() List~User~
    }

    class User {
        -int id
        -String name
        -String email (único)
        -String password
        -Role role_id (nullable FK)
        -bool is_active
        -bool temporary_access
        -DateTime access_expires_at
        -DateTime temporary_access_expires_at
        +hasRole(String slug) bool
        +canAccessSystem() bool
        +getPermissions() List~String~
    }

    %% ===== RECURSOS ACADÉMICOS =====
    class Career {
        -int id
        -String name
        -String description
        -int duration_semesters
        -bool is_active
        +hasSemesters() List~Semester~
    }

    class Semester {
        -int id
        -Career career_id (FK)
        -int number (1-7)
        -String description
        -bool is_active
        +hasCourseSchedules() List~CourseSchedule~
    }

    class Subject {
        -int id
        -String name
        -String code
        -String description
        +hasCourseSchedules() List~CourseSchedule~
        +hasAssignments() List~Assignment~
    }

    class CourseSchedule {
        -int id
        -Subject subject_id (FK)
        -Semester semester_id (FK)
        -int position_in_semester
        -int required_teachers
        +getSubject() Subject
        +getSemester() Semester
    }

    class AcademicPeriod {
        -int id
        -String name
        -Date start_date
        -Date end_date
        -bool is_active
        +hasStudentGroups() List~StudentGroup~
    }

    %% ===== DOCENTES =====
    class Teacher {
        -int id
        -String first_name
        -String last_name
        -String email (único)
        -String phone
        -String specialty
        -JSON specialties[]
        -String curriculum
        -int years_experience
        -String academic_degree
        -bool is_active
        -String availability_notes
        -JSON weekly_availability
        -String special_assignments
        -User user_id (FK nullable)
        +hasAvailabilities() List~TeacherAvailability~
        +hasAssignments() List~Assignment~
        +isAvailableAt(Day, Time) bool
    }

    class TeacherAvailability {
        -int id
        -Teacher teacher_id (FK cascade)
        -Enum day_of_week (mon-sat)
        -Time start_time
        -Time end_time
        -bool is_available
        -String notes
        +getTeacher() Teacher
    }

    %% ===== ESTUDIANTES Y GRUPOS =====
    class StudentGroup {
        -int id
        -String name
        -String level
        -int number_of_students
        -String special_features
        -bool is_active
        -AcademicPeriod academic_period_id (FK nullable)
        -Semester semester_id (FK nullable)
        -Enum group_type (A|B)
        -Enum schedule_type (day|night)
        +hasAssignments() List~Assignment~
    }

    %% ===== INFRAESTRUCTURA =====
    class Building {
        -int id
        +hasClassrooms() List~Classroom~
    }

    class Classroom {
        -int id
        -String name
        -String code (único)
        -int capacity (default 30)
        -JSON resources
        -String location
        -String special_features
        -bool is_active
        -String restrictions
        -Enum type (aula|laboratorio|auditorio|sala_reuniones|taller)
        -int floor
        -String wing
        -Building building_id (FK nullable)
        +hasAvailabilities() List~ClassroomAvailability~
        +hasAssignments() List~Assignment~
        +isAvailableAt(Date, Time) bool
    }

    class ClassroomAvailability {
        -int id
        -Classroom classroom_id (FK cascade)
        -Enum day_of_week (lunes-sábado)
        -Time start_time
        -Time end_time
        -bool is_available
        -String notes
        -Enum availability_type (regular|maintenance|reserved|special_event)
        +getClassroom() Classroom
    }

    %% ===== HORARIOS Y TIME SLOTS =====
    class TimeSlot {
        -int id
        -String name (ej: "Bloque 1")
        -Time start_time
        -Time end_time
        -Enum schedule_type (day|night)
        -int duration_minutes (default 120)
        +hasAssignments() List~Assignment~
    }

    %% ===== ASIGNACIONES =====
    class Assignment {
        -int id
        -StudentGroup student_group_id (FK cascade)
        -Teacher teacher_id (FK cascade)
        -Classroom classroom_id (FK cascade)
        -Enum day (monday|tuesday|...|saturday)
        -Time start_time
        -Time end_time
        -bool is_confirmed (default false)
        -String notes
        -decimal score (8,2, default 0)
        -TimeSlot time_slot_id (FK nullable)
        -Subject subject_id (FK nullable)
        -bool assigned_by_algorithm (default false)
        +validateConflicts() List~String~
        +suggestAlternatives() List~Classroom~
        +confirmAssignment() bool
    }

    %% ===== CONFIGURACIÓN Y REGLAS =====
    class AssignmentRule {
        -int id
        -String parameter (capacity|teacher_availability|classroom_availability|resources|proximity)
        -int weight
        -bool is_active
        -String description
        +calculateScore(Assignment a) int
    }

    %% ===== AUDITORÍA =====
    class Auditoria {
        -int id
        -DateTime timestamp
        -User usuario_id (FK)
        -Enum accion (create|update|delete)
        -String tabla_afectada
        -String descripcion
        +logChange(String action, String table, String desc) void
    }

    %% ===== RELACIONES =====
    Role "1" -- "*" User : contiene
    User "1" -- "*" Auditoria : genera

    Career "1" -- "*" Semester : tiene
    Semester "1" -- "*" CourseSchedule : asigna
    Subject "1" -- "*" CourseSchedule : parte de
    Subject "1" -- "*" Assignment : enseña en

    AcademicPeriod "1" -- "*" StudentGroup : agrupa
    Semester "1" -- "*" StudentGroup : asigna a

    Teacher "0..1" -- "1" User : vinculado a
    Teacher "1" -- "*" TeacherAvailability : tiene
    Teacher "1" -- "*" Assignment : realiza
    
    Building "1" -- "*" Classroom : contiene
    Classroom "1" -- "*" ClassroomAvailability : tiene horario
    Classroom "1" -- "*" Assignment : asigna a

    TimeSlot "1" -- "*" Assignment : bloque de

    StudentGroup "1" -- "*" Assignment : asigna
    Assignment "*" -- "1" Teacher : para
    Assignment "*" -- "1" Classroom : en
    Assignment "*" -- "0..1" Subject : enseña

    AssignmentRule "1" -- "*" Assignment : valida
    Auditoria "*" -- "1" User : registra
```

## Notas de Implementación

### Correspondencia con Laravel
- **Role** → `app/Modules/Auth/Models/Role.php`
- **User** → `app/Models/User.php`
- **Teacher** → `app/Modules/GestionAcademica/Models/Teacher.php`
- **StudentGroup** → `app/Modules/GestionAcademica/Models/StudentGroup.php`
- **Subject** → `app/Models/Subject.php`
- **Classroom** → `app/Modules/Infraestructura/Models/Classroom.php`
- **Assignment** → `app/Modules/Asignacion/Models/Assignment.php`
- **TimeSlot** → `app/Models/TimeSlot.php`

### Migraciones Base
Todas las tablas se crean con `php artisan migrate --seed`. Las migraciones incluyen índices, constraints (CASCADE/RESTRICT), y valores por defecto según se muestra en el diagrama.

### Roles y Permisos (8 roles reales)
Los roles se crean en `database/seeders/RoleSeeder.php`:
1. `administrador` - Acceso completo al sistema
2. `secretaria_administrativa` - Gestión administrativa y reportes
3. `coordinador` - Gestión académica y asignaciones
4. `secretaria_coordinacion` - Apoyo académico y distribución de horarios
5. `coordinador_infraestructura` - Gestión de salones y recursos
6. `secretaria_infraestructura` - Apoyo en gestión de infraestructura
7. `profesor` - Acceso a horarios personales y disponibilidades
8. `profesor_invitado` - Acceso temporal al sistema

Los permisos se validan en `app/Http/Middleware/RoleMiddleware.php` mediante `role_id` en la tabla `users`.

### Validaciones en Assignment
- Conflictos de horario (profesor, salón, estudiantes en la misma fecha/hora).
- Capacidad: `StudentGroup.number_of_students <= Classroom.capacity`.
- Disponibilidades: Consultadas desde `TeacherAvailability` y `ClassroomAvailability`.
- Reglas de asignación: Definidas en `AssignmentRule` (weights para algoritmo automático).
- Transiciones: `assigned_by_algorithm` indica si fue generada por el algoritmo o manual.
