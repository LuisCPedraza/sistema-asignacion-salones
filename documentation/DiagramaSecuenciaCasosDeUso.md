# Diagramas de Secuencia para Casos de Uso por Rol

## Introducción
Estos diagramas de secuencia ilustran las interacciones clave de cada **rol real del sistema** (8 roles del `RoleSeeder.php`) con el backend Laravel. Cada diagrama usa sintaxis Mermaid y refleja las restricciones de permisos implementadas vía `RoleMiddleware`.

**Actualización**: Se eliminaron diagramas para roles ficticios (Superadministrador, CoordinadorAcademico, SecretariaAcademica, SecretariaInfraestructura) que no existen en el proyecto. Los 8 roles reales son:

1. **Administrador** (`administrador`)
2. **Secretaria Administrativa** (`secretaria_administrativa`)
3. **Coordinador** (`coordinador`)
4. **Secretaria de Coordinación** (`secretaria_coordinacion`)
5. **Coordinador de Infraestructura** (`coordinador_infraestructura`)
6. **Secretaria de Infraestructura** (`secretaria_infraestructura`)
7. **Profesor** (`profesor`)
8. **Profesor Invitado** (`profesor_invitado`)

## 1. Administrador

**Actividades Principales**: 
- Crea/gestiona cuentas de usuarios (UC1)
- Genera reportes estadísticos (UC15)
- Configura parámetros generales del sistema (UC19)
- Visualiza historial de auditoría (UC18)

**Restricciones Específicas**: 
- Acceso total pero registrado (todas las acciones se auditan vía middleware)
- No ejecuta asignaciones directamente (eso es del Coordinador)
- Puede ver todos los datos pero no modificar asignaciones confirmadas

```mermaid
sequenceDiagram
    actor Admin as Administrador
    participant Sys as Sistema Laravel
    participant DB as Base de Datos

    Admin->>Sys: POST /login (email, password)
    Sys->>DB: SELECT * FROM users WHERE email=? AND role_id=(SELECT id FROM roles WHERE slug='administrador')
    DB->>Sys: User data + role
    Sys->>Admin: JWT token + permissions
    Note over Admin,Sys: Restricción: Solo role_id con slug='administrador'

    Admin->>Sys: POST /users (crear cuenta, UC1)
    Sys->>DB: INSERT INTO users (name, email, role_id)
    DB->>Sys: User created
    Sys->>Admin: Confirmación
    Note over Admin,DB: Middleware: RoleMiddleware verifica role=administrador

    Admin->>Sys: GET /reports/statistics (UC15)
    Sys->>DB: SELECT COUNT(*), AVG(score) FROM assignments GROUP BY...
    DB->>Sys: Estadísticas
    Sys->>Admin: JSON con reportes

    Admin->>Sys: PUT /config/academic-period (UC19)
    Sys->>DB: UPDATE academic_periods SET start_date=?, end_date=?
    DB->>Sys: Config updated
    Sys->>Admin: Confirmación
    Note over Admin,Sys: Restricción: Sin modificar asignaciones
```

## 2. Secretaria Administrativa

**Actividades Principales**:
- Apoya en creación de cuentas básicas (UC1, con aprobación)
- Genera reportes simples (UC15, solo lectura)
- Distribuye horarios a estudiantes/familias (UC13, exportación)

**Restricciones Específicas**:
- Acceso limitado a lectura/edición básica
- No gestiona asignaciones ni configuración global
- Solo visualiza datos no sensibles (sin salarios, evaluaciones personales)

```mermaid
sequenceDiagram
    actor SecAdmin as Secretaria Administrativa
    participant Sys as Sistema Laravel
    participant Admin as Administrador (Aprobador)
    participant DB as Base de Datos

    SecAdmin->>Sys: POST /login (UC2)
    Sys->>DB: SELECT * FROM users WHERE role_id=(SELECT id FROM roles WHERE slug='secretaria_administrativa')
    DB->>Sys: User + role
    Sys->>SecAdmin: Acceso limitado
    Note over SecAdmin,Sys: Restricción: Solo lectura/edición básica

    SecAdmin->>Sys: POST /users (UC1, soporte)
    Sys->>Admin: Notificación de solicitud
    Admin->>Sys: POST /users/approve
    Sys->>DB: INSERT INTO users
    DB->>Sys: Created
    Sys->>SecAdmin: Confirmación diferida
    Note over SecAdmin,Admin: Restricción: Requiere aprobación superior

    SecAdmin->>Sys: GET /reports/basic (UC15)
    Sys->>DB: SELECT * FROM assignments WHERE is_confirmed=true (solo públicos)
    DB->>Sys: Datos no sensibles
    Sys->>SecAdmin: Reporte básico
    Note over SecAdmin,DB: Restricción: Sin datos sensibles

    SecAdmin->>Sys: GET /schedules/export (UC13)
    Sys->>DB: SELECT * FROM assignments JOIN student_groups...
    DB->>Sys: Horarios
    Sys->>SecAdmin: CSV/PDF export
```

## 3. Coordinador

**Actividades Principales**:
- Registra/edita grupos de estudiantes (UC3)
- Gestiona profesores (UC7) - incluye funciones académicas
- Ejecuta asignación automática (UC9)
- Realiza asignación manual (UC10)
- Visualiza horarios y conflictos (UC12, UC13)
- Establece restricciones (UC17)

**Restricciones Específicas**:
- Dependiente de disponibilidades reales (no puede asignar si hay conflictos)
- No accede a configuración global (eso es del Administrador)
- Acceso amplio a gestión académica pero con validaciones

```mermaid
sequenceDiagram
    actor Coord as Coordinador
    participant Sys as Sistema Laravel
    participant Algo as Algoritmo Asignación
    participant DB as Base de Datos

    Coord->>Sys: POST /login (UC2)
    Sys->>DB: SELECT * FROM users WHERE role_id=(SELECT id FROM roles WHERE slug='coordinador')
    DB->>Sys: User + role
    Sys->>Coord: Acceso general
    Note over Coord,Sys: Restricción: Dependiente de disponibilidades

    Coord->>Sys: POST /student-groups (UC3)
    Sys->>DB: INSERT INTO student_groups (name, level, semester_id, group_type, schedule_type)
    DB->>Sys: Created
    Sys->>Coord: Confirmación

    Coord->>Sys: POST /teachers (UC7)
    Sys->>DB: INSERT INTO teachers (first_name, last_name, specialties JSONB)
    DB->>Sys: Created
    Sys->>Coord: Confirmación

    Coord->>Sys: POST /assignments/auto (UC9)
    Sys->>Algo: Ejecutar algoritmo
    Algo->>DB: SELECT * FROM assignment_rules, teacher_availabilities, classroom_availabilities
    DB->>Algo: Reglas + disponibilidades
    Algo->>DB: INSERT INTO assignments (assigned_by_algorithm=true, score)
    DB->>Algo: Assignments created
    Algo->>Sys: Resultados + conflictos
    Sys->>Coord: Lista de asignaciones + conflictos detectados (UC12)
    Note over Coord,Algo: Restricción: Validación automática de conflictos

    Coord->>Sys: POST /assignments/manual (UC10)
    Sys->>DB: CHECK si hay overlap en (student_group_id, day, start_time)
    DB->>Sys: Conflicto detectado
    Sys->>Coord: Error 409 (conflicto)
    Coord->>Sys: PUT /assignments/{id} (ajustar horario)
    Sys->>DB: INSERT (sin conflictos)
    DB->>Sys: Created
    Sys->>Coord: Confirmación

    Coord->>Sys: GET /schedules/semester (UC13)
    Sys->>DB: SELECT * FROM assignments JOIN teachers, classrooms, student_groups
    DB->>Sys: Horarios completos
    Sys->>Coord: Vista semestral
```

## 4. Secretaria de Coordinación

**Actividades Principales**:
- Maneja registros administrativos de grupos/profesores (UC3/UC7, soporte)
- Distribuye horarios a estudiantes/familias (UC13)
- Exporta a calendarios externos (UC13)

**Restricciones Específicas**:
- No asigna salones ni edita disponibilidades físicas
- Solo datos académicos no sensibles
- Acceso temporal a info de estudiantes (privacidad)

```mermaid
sequenceDiagram
    actor SecCoord as Secretaria de Coordinación
    participant Sys as Sistema Laravel
    participant DB as Base de Datos

    SecCoord->>Sys: POST /login (UC2)
    Sys->>DB: SELECT * FROM users WHERE role_id=(SELECT id FROM roles WHERE slug='secretaria_coordinacion')
    DB->>Sys: User + role
    Sys->>SecCoord: Acceso admin académico
    Note over SecCoord,Sys: Restricción: Datos no sensibles

    SecCoord->>Sys: PUT /student-groups/{id} (UC3, soporte)
    Sys->>DB: UPDATE student_groups SET number_of_students=?
    DB->>Sys: Updated
    Sys->>SecCoord: Confirmación

    SecCoord->>Sys: GET /schedules/export (UC13)
    Sys->>DB: SELECT * FROM assignments WHERE student_group_id IN (...)
    DB->>Sys: Horarios
    Sys->>SecCoord: iCal/CSV export
    Note over SecCoord,DB: Restricción: Acceso temporal estudiantes

    SecCoord->>Sys: POST /teachers (UC7, registro básico)
    Sys->>DB: INSERT INTO teachers (first_name, last_name, email)
    DB->>Sys: Created
    Sys->>SecCoord: Confirmación
```

## 5. Coordinador de Infraestructura

**Actividades Principales**:
- Registra/gestiona salones (UC5) - capacidad, recursos, ubicación
- Configura disponibilidad horaria de salones (UC6)
- Establece restricciones de uso (UC17, físicas)

**Restricciones Específicas**:
- Enfocado solo en recursos físicos (classrooms, buildings)
- No ve/edita datos académicos (grupos, profesores, asignaciones)
- Cambios requieren validación para evitar conflictos con asignaciones existentes

```mermaid
sequenceDiagram
    actor CoordInfra as Coordinador Infraestructura
    participant Sys as Sistema Laravel
    participant Valid as Validador
    participant DB as Base de Datos

    CoordInfra->>Sys: POST /login (UC2)
    Sys->>DB: SELECT * FROM users WHERE role_id=(SELECT id FROM roles WHERE slug='coordinador_infraestructura')
    DB->>Sys: User + role
    Sys->>CoordInfra: Acceso infraestructura
    Note over CoordInfra,Sys: Restricción: Solo recursos físicos

    CoordInfra->>Sys: POST /classrooms (UC5)
    Sys->>DB: INSERT INTO classrooms (name, code, capacity, resources JSONB, type)
    DB->>Sys: Created
    Sys->>CoordInfra: Confirmación

    CoordInfra->>Sys: POST /classroom-availabilities (UC6)
    Sys->>Valid: Verificar si hay assignments en ese horario
    Valid->>DB: SELECT * FROM assignments WHERE classroom_id=? AND day=? AND start_time<?
    DB->>Valid: Asignaciones existentes
    Valid->>Sys: Conflicto detectado
    Sys->>CoordInfra: Warning: 5 asignaciones afectadas, confirmar cambio
    CoordInfra->>Sys: PUT /classroom-availabilities (confirmado)
    Sys->>DB: INSERT INTO classroom_availabilities (classroom_id, day_of_week, is_available=false, availability_type='maintenance')
    DB->>Sys: Updated
    Sys->>CoordInfra: Confirmación
    Note over CoordInfra,Valid: Restricción: Validación para evitar conflictos

    CoordInfra->>Sys: PUT /classrooms/{id} (UC5, editar recursos)
    Sys->>DB: UPDATE classrooms SET resources=?::jsonb
    DB->>Sys: Updated
    Sys->>CoordInfra: Confirmación
    Note over CoordInfra,DB: Restricción: Sin académicos
```

## 6. Secretaria de Infraestructura

**Actividades Principales**:
- Actualiza disponibilidades de salones (UC6, por mantenimiento/eventos)
- Genera reportes de uso de recursos físicos (UC15, infraestructura)
- Notifica restricciones a coordinadores

**Restricciones Específicas**:
- Enfocado solo en datos de salones/infraestructura
- No accede a horarios académicos o grupos
- Requiere aprobación para cambios que afecten asignaciones activas

```mermaid
sequenceDiagram
    actor SecInfra as Secretaria Infraestructura
    participant Sys as Sistema Laravel
    participant Aprobador as Coordinador Infraestructura
    participant DB as Base de Datos

    SecInfra->>Sys: POST /login (UC2)
    Sys->>DB: SELECT * FROM users WHERE role_id=(SELECT id FROM roles WHERE slug='secretaria_infraestructura')
    DB->>Sys: User + role
    Sys->>SecInfra: Acceso infraestructura limitado
    Note over SecInfra,Sys: Restricción: Solo salones

    SecInfra->>Sys: PUT /classroom-availabilities/{id} (UC6)
    Sys->>Aprobador: Solicitar aprobación (afecta 3 asignaciones)
    Aprobador->>Sys: POST /classroom-availabilities/approve
    Sys->>DB: UPDATE classroom_availabilities SET is_available=false, notes='Mantenimiento'
    DB->>Sys: Updated
    Sys->>SecInfra: Actualizado
    Note over SecInfra,Aprobador: Restricción: Requiere aprobación

    SecInfra->>Sys: GET /reports/classrooms (UC15)
    Sys->>DB: SELECT classroom_id, COUNT(*) FROM assignments GROUP BY classroom_id
    DB->>Sys: Estadísticas de uso
    Sys->>SecInfra: Reporte físico
    Note over SecInfra,DB: Restricción: Sin datos académicos
```

## 7. Profesor

**Actividades Principales**:
- Inicia sesión (UC2)
- Visualiza horario personal y salones asignados (UC14)
- Actualiza disponibilidad horaria (UC8)
- Reporta preferencias de horarios

**Restricciones Específicas**:
- Acceso solo a datos personales (filtro por `teacher.user_id` o `teacher_id`)
- No edición global de recursos
- Dependiente de asignaciones de coordinadores
- No ve horarios de otros profesores

```mermaid
sequenceDiagram
    actor Prof as Profesor
    participant Sys as Sistema Laravel
    participant DB as Base de Datos

    Prof->>Sys: POST /login (UC2)
    Sys->>DB: SELECT u.*, t.id AS teacher_id FROM users u JOIN teachers t ON t.user_id=u.id WHERE u.role_id=(SELECT id FROM roles WHERE slug='profesor')
    DB->>Sys: User + teacher_id
    Sys->>Prof: Acceso personal
    Note over Prof,Sys: Restricción: Solo datos propios

    Prof->>Sys: GET /schedules/my (UC14)
    Sys->>DB: SELECT * FROM assignments WHERE teacher_id=(SELECT id FROM teachers WHERE user_id=?)
    DB->>Sys: Horarios personales
    Sys->>Prof: Vista de horario y salones asignados
    Note over Prof,DB: Restricción: Filtro por teacher_id

    Prof->>Sys: PUT /teacher-availabilities (UC8)
    Sys->>DB: INSERT INTO teacher_availabilities (teacher_id, day_of_week, start_time, end_time, is_available)
    DB->>Sys: Created
    Sys->>Prof: Disponibilidad actualizada
    Note over Prof,Sys: Validación: Coordinador debe aprobar si afecta asignaciones

    Prof->>Sys: GET /assignments/{id}/details (UC14)
    Sys->>DB: SELECT * FROM assignments WHERE id=? AND teacher_id=(SELECT id FROM teachers WHERE user_id=?)
    DB->>Sys: Assignment details
    Sys->>Prof: Detalles de asignación
    Note over Prof,DB: Restricción: No edición global
```

## 8. Profesor Invitado

**Actividades Principales**:
- Visualiza horarios temporales (UC14, acceso limitado por fecha)
- Reporta disponibilidades limitadas (UC8, temporal)
- Recibe notificaciones por email/SMS

**Restricciones Específicas**:
- Acceso caduco (expira automáticamente vía `temporary_access_expires_at`)
- Sin edición profunda ni gestión de recursos
- Solo para sesiones puntuales (1-2 semestres máximo)
- No puede crear/modificar grupos o salones

```mermaid
sequenceDiagram
    actor ProfInv as Profesor Invitado
    participant Sys as Sistema Laravel
    participant DB as Base de Datos

    ProfInv->>Sys: POST /login (UC2, temporal)
    Sys->>DB: SELECT * FROM users WHERE role_id=(SELECT id FROM roles WHERE slug='profesor_invitado') AND temporary_access=true AND temporary_access_expires_at > NOW()
    DB->>Sys: User + expiration
    Sys->>ProfInv: Acceso caduco (válido hasta YYYY-MM-DD)
    Note over ProfInv,Sys: Restricción: Expira automáticamente

    ProfInv->>Sys: GET /schedules/my (UC14)
    Sys->>DB: SELECT * FROM assignments WHERE teacher_id=(SELECT id FROM teachers WHERE user_id=?) AND created_at >= (NOW() - INTERVAL '3 months')
    DB->>Sys: Horarios temporales (últimos 3 meses)
    Sys->>ProfInv: Vista temporal
    Note over ProfInv,DB: Restricción: Solo asignaciones recientes

    ProfInv->>Sys: POST /teacher-availabilities (UC8, limitado)
    Sys->>DB: INSERT INTO teacher_availabilities (teacher_id, day_of_week, start_time, end_time, notes='Disponibilidad temporal')
    DB->>Sys: Created
    Sys->>ProfInv: Notificación enviada (email/SMS)
    Note over ProfInv,Sys: Restricción: Sin edición profunda

    %% Acceso expirado
    Note over ProfInv,Sys: DESPUÉS de temporary_access_expires_at
    ProfInv->>Sys: GET /schedules/my (intento post-expiración)
    Sys->>DB: SELECT * FROM users WHERE id=? AND temporary_access_expires_at > NOW()
    DB->>Sys: No results (expirado)
    Sys->>ProfInv: HTTP 401 Unauthorized - Acceso expirado
```

## Notas de Implementación

### Middleware de Autorización (RoleMiddleware)
Todas las rutas protegidas usan `RoleMiddleware` que verifica:
```php
// app/Http/Middleware/RoleMiddleware.php
public function handle($request, Closure $next, ...$roles)
{
    if (!auth()->check()) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    
    $userRole = auth()->user()->role->slug;
    
    if (!in_array($userRole, $roles)) {
        return response()->json(['error' => 'Forbidden'], 403);
    }
    
    return $next($request);
}
```

### Ejemplo de Rutas con Roles
```php
// routes/web.php
Route::middleware(['auth', 'role:administrador'])->group(function () {
    Route::post('/users', [UserController::class, 'store']); // UC1
    Route::get('/reports/statistics', [ReportController::class, 'statistics']); // UC15
});

Route::middleware(['auth', 'role:coordinador'])->group(function () {
    Route::post('/student-groups', [StudentGroupController::class, 'store']); // UC3
    Route::post('/assignments/auto', [AssignmentController::class, 'auto']); // UC9
    Route::post('/assignments/manual', [AssignmentController::class, 'manual']); // UC10
});

Route::middleware(['auth', 'role:coordinador_infraestructura'])->group(function () {
    Route::post('/classrooms', [ClassroomController::class, 'store']); // UC5
    Route::post('/classroom-availabilities', [ClassroomAvailabilityController::class, 'store']); // UC6
});

Route::middleware(['auth', 'role:profesor,profesor_invitado'])->group(function () {
    Route::get('/schedules/my', [ScheduleController::class, 'personal']); // UC14
});
```

### Validación de Acceso Temporal (Profesor Invitado)
```php
// app/Http/Middleware/CheckTemporaryAccess.php
public function handle($request, Closure $next)
{
    $user = auth()->user();
    
    if ($user->temporary_access && 
        $user->temporary_access_expires_at < now()) {
        auth()->logout();
        return response()->json(['error' => 'Temporary access expired'], 401);
    }
    
    return $next($request);
}
```

## Diferencias con Documentación Anterior

**❌ Eliminado**:
- Diagrama para **Superadministrador** (rol inexistente)
- Diagrama para **Coordinador Académico** (consolidado en Coordinador)
- Diagrama para **Secretaria Académica** (ahora es Secretaria de Coordinación)
- Diagramas separados para subtipos de secretarias (unificados)

**✅ Actualizado**:
- 8 roles reales del `RoleSeeder.php`
- Interacciones con tablas reales (users, roles, teachers, assignments, etc.)
- Restricciones implementadas vía `RoleMiddleware` y policies Laravel
- Validaciones de conflictos vía queries SQL (no triggers)
- Acceso temporal para Profesor Invitado con expiración automática

**✅ Agregado**:
- Flujos para Secretaria de Coordinación (apoyo académico)
- Flujos para Secretaria de Infraestructura (apoyo en salones)
- Validación de disponibilidades antes de asignaciones
- Notificaciones de conflictos al Coordinador
- Aprobaciones para cambios críticos (Secretarias → Administrador/Coordinadores)
