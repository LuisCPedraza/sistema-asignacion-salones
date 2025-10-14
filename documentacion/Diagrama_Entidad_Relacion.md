# Diagrama Entidad Relación
---
El código fuente en Mermaid para el diagrama de entidad-relación (ERD) correspondiente al esquema de la base de datos actualizada, que cumple al 100% con los requerimientos del documento "Proyectos Desarrollo de Software 2.docx". El diagrama incluye todas las tablas, sus atributos, claves primarias, claves foráneas, y relaciones, siguiendo la estructura proporcionada en el esquema SQL. He organizado el diagrama para que sea claro, visualmente comprensible, y refleje las entidades, sus relaciones, y las cardinalidades adecuadas.

## Explicación del Enfoque

- Tablas y Atributos: Cada tabla del esquema SQL se representa como una entidad en Mermaid, con sus atributos listados. Las claves primarias están marcadas con (PK) y las claves foráneas con (FK).
- Relaciones: Las relaciones se derivan de las claves foráneas (FOREIGN KEY) y las tablas de unión (e.g., salon_recurso, disp_profesor). Las cardinalidades reflejan las restricciones de integridad (e.g., uno a muchos, muchos a muchos).
- Optimización Visual: He agrupado las entidades lógicamente y usado nombres claros para facilitar la lectura. Las relaciones están definidas con cardinalidades explícitas (e.g., 1..1, 0..*) basadas en los requerimientos.
- Mermaid: El código se genera en la sintaxis de Mermaid para diagramas ER, que es compatible con herramientas como Mermaid Live Editor o integraciones en markdown.

	- Código Fuente Mermaiddiagrama_entidad_relacion.mmdmermaid•Explicación del Diagrama

```mermaid
erDiagram
    periodo_academico {
        CHAR(36) id PK
        VARCHAR(120) nombre
        DATE fecha_inicio
        DATE fecha_fin
        TINYINT(1) activo
    }

    bloque_horario {
        CHAR(36) id PK
        ENUM dia_semana
        TIME hora_inicio
        TIME hora_fin
    }

    usuario {
        CHAR(36) id PK
        VARCHAR(120) nombre
        VARCHAR(160) email UK
        VARCHAR(255) password_hash
        ENUM rol
        TINYINT(1) activo
        DATETIME created_at
        DATETIME updated_at
    }

    profesor {
        CHAR(36) id PK
        CHAR(36) usuario_id FK,UK
        TEXT especialidades
        VARCHAR(255) hoja_vida_url
    }

    grupo {
        CHAR(36) id PK
        VARCHAR(120) nombre
        VARCHAR(60) nivel
        INT num_estudiantes
        TEXT caracteristicas
        TINYINT(1) activo
    }

    salon {
        CHAR(36) id PK
        VARCHAR(60) codigo UK
        INT capacidad
        VARCHAR(160) ubicacion
        TINYINT(1) activo
    }

    recurso {
        CHAR(36) id PK
        VARCHAR(100) nombre
        VARCHAR(255) descripcion
    }

    salon_recurso {
        CHAR(36) salon_id PK,FK
        CHAR(36) recurso_id PK,FK
        INT cantidad
    }

    recurso_disponibilidad {
        CHAR(36) recurso_id PK,FK
        CHAR(36) bloque_id PK,FK
        ENUM estado
    }

    disp_profesor {
        CHAR(36) profesor_id PK,FK
        CHAR(36) bloque_id PK,FK
        ENUM estado
    }

    disp_salon {
        CHAR(36) salon_id PK,FK
        CHAR(36) bloque_id PK,FK
        ENUM estado
    }

    asignacion {
        CHAR(36) id PK
        CHAR(36) grupo_id FK
        CHAR(36) salon_id FK
        CHAR(36) profesor_id FK
        CHAR(36) bloque_id FK
        CHAR(36) periodo_id FK
        ENUM estado
        ENUM origen
        FLOAT score
        CHAR(36) created_by FK
        DATETIME created_at
    }

    tipo_restriccion {
        CHAR(36) id PK
        VARCHAR(80) nombre UK
        TEXT descripcion
        JSON regla_default_json
    }

    restriccion {
        CHAR(36) id PK
        VARCHAR(80) tipo
        VARCHAR(80) objetivo_type
        CHAR(36) objetivo_id
        JSON regla_json
        ENUM dureza
    }

    auditoria {
        CHAR(36) id PK
        CHAR(36) usuario_id FK
        VARCHAR(80) entidad
        CHAR(36) entidad_id
        VARCHAR(40) accion
        JSON cambios_json
        VARCHAR(255) motivo
        DATETIME created_at
    }

    reporte_ocupacion {
        CHAR(36) id PK
        CHAR(36) periodo_id FK
        ENUM tipo
        CHAR(36) objetivo_id
        FLOAT ocupacion_porcentaje
        INT num_bloques_ocupados
        DATETIME created_at
    }

    parametro_sistema {
        CHAR(36) id PK
        VARCHAR(120) clave UK
        JSON valor
        VARCHAR(60) scope
    }

    %% Relaciones
    usuario ||--o{ profesor : "es"
    usuario ||--o{ asignacion : "crea"
    usuario ||--o{ auditoria : "realiza"

    profesor ||--o{ disp_profesor : "tiene"
    profesor ||--o{ asignacion : "asignado"

    grupo ||--o{ asignacion : "asignado"

    salon ||--o{ salon_recurso : "tiene"
    salon ||--o{ disp_salon : "tiene"
    salon ||--o{ asignacion : "asignado"

    recurso ||--o{ salon_recurso : "asignado"
    recurso ||--o{ recurso_disponibilidad : "tiene"

    bloque_horario ||--o{ disp_profesor : "define"
    bloque_horario ||--o{ disp_salon : "define"
    bloque_horario ||--o{ recurso_disponibilidad : "define"
    bloque_horario ||--o{ asignacion : "define"

    periodo_academico ||--o{ asignacion : "pertenece"
    periodo_academico ||--o{ reporte_ocupacion : "pertenece"

    salon_recurso }o--o{ recurso : "relaciona"
    disp_profesor }o--o{ bloque_horario : "relaciona"
    disp_salon }o--o{ bloque_horario : "relaciona"
    recurso_disponibilidad }o--o{ bloque_horario : "relaciona"
```

- **Entidades:** Cada tabla se representa como una entidad con sus atributos. Por ejemplo, periodo_academico incluye id (PK), nombre, fecha_inicio, fecha_fin, y activo.
- **Relaciones:**

	- Uno a muchos: Ejemplo, usuario ||--o{ profesor indica que un usuario puede ser un profesor, pero un profesor está ligado a un solo usuario.
	- Muchos a muchos: Ejemplo, salon_recurso }o--o{ recurso refleja la relación entre salones y recursos, mediada por la tabla de unión salon_recurso.
	- Relaciones como asignacion vinculan múltiples entidades (grupo, salon, profesor, bloque_horario, periodo_academico) con claves foráneas.


- **Notación:**

||--o{: Relación uno a muchos.
}o--o{: Relación muchos a muchos.
PK, FK, UK: Indican clave primaria, foránea, y única, respectivamente.


- **Tablas de Unión:** salon_recurso, disp_profesor, disp_salon, y recurso_disponibilidad modelan relaciones muchos a muchos con atributos adicionales (e.g., cantidad en salon_recurso, estado en disp_profesor).
- **Vistas y Triggers:** No se representan en el diagrama (ya que Mermaid ERD se centra en entidades y relaciones), pero vista_conflictos_salon, vista_conflictos_profesor, y trg_valida_restriccion complementan la funcionalidad en la implementación.

### Cómo Usar el Código

Copia el código Mermaid dentro del <xaiArtifact> y pégalo en un editor compatible con Mermaid, como Mermaid Live Editor.
Renderiza el diagrama para visualizar las entidades y relaciones.
Si usas markdown en una plataforma compatible (e.g., GitHub), el código puede renderizarse directamente dentro de un bloque ```mermaid:disable-run

## Cumplimiento con el Documento
El diagrama refleja el modelo entidad-relación requerido en la primera entrega del proyecto (clase 9), cubriendo el Diagrama Entidad-Relación y el Modelo Relacional. Representa todas las entidades necesarias para las épicas (HU1-HU19), incluyendo gestión de usuarios, grupos, salones, profesores, asignaciones, restricciones, auditoría, reportes, y configuraciones. Las relaciones y cardinalidades están alineadas con las necesidades funcionales (e.g., asignaciones automáticas/manuales, conflictos, reportes).
