# Diagrama de Modelo Relacional: Sistema de Asignación de Salones

## Descripción General
El **Modelo Relacional** representa la implementación física del ERD en tablas de base de datos relacionales (ej: SQL), con énfasis en claves primarias (PK), claves foráneas (FK), índices y restricciones de integridad. Basado en el ERD moderno previo, este diagrama usa notación Mermaid (`erDiagram`) para mostrar tablas como entidades, atributos con tipos/detalles (PK/FK destacados) y relaciones con cardinalidades (Crow's Foot). 

Para robustez, se aplica normalización 3NF (evitando redundancias, ej: roles como tablas separadas con FK a USUARIO), triggers implícitos para auditoría y vistas sugeridas para reportes. Visualmente, tablas agrupadas (roles arriba, recursos medio, gestión abajo) con relaciones etiquetadas para flujo claro (L→R). Esto facilita la generación de scripts SQL (ej: CREATE TABLE) y asegura escalabilidad (ej: índices en FK para joins rápidos).

**Corrección aplicada**: Se simplificaron definiciones de atributos para compatibilidad con Mermaid (eliminando constraints inline como "NOT NULL" o enums detallados, que causaban errores de parse). Constraints se mueven a notas por tabla. Esto mantiene la robustez sin romper el renderizado.

Diferencia con ERD: Más orientado a BD (tipos de datos explícitos, constraints como NOT NULL), menos abstracto.

## Descripciones por Roles (en Tablas Relacionales)
Cada rol se modela como tabla hija de `USUARIO` (FK para herencia), con atributos/relaciones específicas. Restricciones se implementan vía CHECK constraints o triggers.

- **Administrador**: Tabla para acceso global; FK a USUARIO, relaciona con REPORTE/PARAMETRO (1:N). Constraints: nivel_acceso = 'alto' (CHECK).
- **Superadministrador**: Tabla exclusiva; FK a USUARIO; acceso a backups.

### Diagrama Modelo Relacional (Actualizado)
```mermaid
erDiagram
    USUARIO {
        int id PK
        string nombre
        string email
        string password
        string rol
    }
    note for USUARIO "rol ENUM: 'admin', 'superadmin', 'coordinador', 'profesor', 'secretaria', 'coordinador_infra' (DEFAULT 'profesor')"

    ADMINISTRADOR {
        int id PK
        int usuario_id FK
        string nivel_acceso
    }

    SUPERADMINISTRADOR {
        int id PK
        int usuario_id FK
        string api_keys
    }

    COORDINADOR {
        int id PK
        int usuario_id FK
        string especialidad
    }

    COORDINADOR_ACADEMICO {
        int id PK
        int coordinador_id FK
        string foco_academico
    }

    COORDINADOR_INFRAESTRUCTURA {
        int id PK
        int coordinador_id FK
        string area_mantenimiento
    }

    SECRETARIA {
        int id PK
        int usuario_id FK
        string departamento
    }

    SECRETARIA_ACADEMICA {
        int id PK
        int secretaria_id FK
        string contacto_familias
    }

    SECRETARIA_INFRAESTRUCTURA {
        int id PK
        int secretaria_id FK
        boolean alertas_mantenimiento
    }

    PROFESOR {
        int id PK
        int usuario_id FK
        string especialidad
        text hoja_vida
    }

    PROFESOR_INVITADO {
        int id PK
        int profesor_id FK
        date fecha_expiracion
    }

    GRUPO {
        int id PK
        string nombre
        int nivel
        int numEstudiantes
        string caracteristicas
        int coordinador_id FK
    }

    SALON {
        int id PK
        int capacidad
        string recursos
        string ubicacion
        int coordinador_infra_id FK
    }

    ASIGNACION {
        int id PK
        date fecha
        int grupo_id FK
        int salon_id FK
        int profesor_id FK
        int horario_id FK
    }

    HORARIO {
        int id PK
        date periodo
        int coordinador_id FK
    }

    REPORTE {
        int id PK
        string tipo
        datetime fechaGeneracion
        int admin_id FK
    }

    RESTRICCION {
        int id PK
        string tipo
        string descripcion
        int asignacion_id FK
        int coordinador_id FK
    }

    AUDITORIA {
        int id PK
        datetime timestamp
        string accion
        int usuario_id FK
    }

    PARAMETRO {
        string clave PK
        string valor
        int admin_id FK
    }

    %% Relaciones con cardinalidades
    USUARIO ||--o{ ADMINISTRADOR : "es (1:N)"
    USUARIO ||--|| SUPERADMINISTRADOR : "es (1:1)"
    USUARIO ||--o{ COORDINADOR : "es (1:N)"
    COORDINADOR ||--o{ COORDINADOR_ACADEMICO : "especializa (1:N)"
    COORDINADOR ||--o{ COORDINADOR_INFRAESTRUCTURA : "especializa (1:N)"
    USUARIO ||--o{ SECRETARIA : "es (1:N)"
    SECRETARIA ||--o{ SECRETARIA_ACADEMICA : "especializa (1:N)"
    SECRETARIA ||--o{ SECRETARIA_INFRAESTRUCTURA : "especializa (1:N)"
    USUARIO ||--o{ PROFESOR : "es (1:N)"
    PROFESOR ||--o{ PROFESOR_INVITADO : "especializa (1:N)"

    COORDINADOR ||--o{ GRUPO : "gestiona (1:N)"
    COORDINADOR_INFRAESTRUCTURA ||--o{ SALON : "gestiona (1:N)"
    COORDINADOR ||--o{ HORARIO : "aprueba (1:N)"
    ADMINISTRADOR ||--o{ REPORTE : "genera (1:N)"
    COORDINADOR ||--o{ RESTRICCION : "establece (1:N)"
    USUARIO ||--o{ AUDITORIA : "registra (1:N)"
    ADMINISTRADOR ||--o{ PARAMETRO : "configura (1:N)"

    GRUPO ||--o{ ASIGNACION : "participa (1:N)"
    SALON ||--o{ ASIGNACION : "asignado (1:N)"
    PROFESOR ||--o{ ASIGNACION : "imparte (1:N)"
    HORARIO ||--o{ ASIGNACION : "contiene (1:N)"
    ASIGNACION ||--o{ RESTRICCION : "sujeta (1:N)"

    %% Notas para implementación
    %% Ej: VIEW v_horarios AS SELECT * FROM HORARIO JOIN ASIGNACION ON ...
    %% Constraints: FOREIGN KEY... ON DELETE CASCADE/RESTRICT para integridad.
    %% Índices: Para queries frecuentes (ej: por fecha en ASIGNACION).
