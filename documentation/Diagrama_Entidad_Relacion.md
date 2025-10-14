\# Diagrama Entidad Relación

---

El código fuente en Mermaid para el diagrama de entidad-relación (ERD) correspondiente al esquema de la base de datos actualizada, que cumple al 100% con los requerimientos del documento "Proyectos Desarrollo de Software 2.docx". El diagrama incluye todas las tablas, sus atributos, claves primarias, claves foráneas, y relaciones, siguiendo la estructura proporcionada en el esquema SQL. He organizado el diagrama para que sea claro, visualmente comprensible, y refleje las entidades, sus relaciones, y las cardinalidades adecuadas.



\## Explicación del Enfoque



\- Tablas y Atributos: Cada tabla del esquema SQL se representa como una entidad en Mermaid, con sus atributos listados. Las claves primarias están marcadas con (PK) y las claves foráneas con (FK).

\- Relaciones: Las relaciones se derivan de las claves foráneas (FOREIGN KEY) y las tablas de unión (e.g., salon\_recurso, disp\_profesor). Las cardinalidades reflejan las restricciones de integridad (e.g., uno a muchos, muchos a muchos).

\- Optimización Visual: He agrupado las entidades lógicamente y usado nombres claros para facilitar la lectura. Las relaciones están definidas con cardinalidades explícitas (e.g., 1..1, 0..\*) basadas en los requerimientos.

\- Mermaid: El código se genera en la sintaxis de Mermaid para diagramas ER, que es compatible con herramientas como Mermaid Live Editor o integraciones en markdown.



&nbsp;	- Código Fuente Mermaiddiagrama\_entidad\_relacion.mmdmermaid•Explicación del Diagrama



```mermaid

erDiagram

&nbsp;   periodo\_academico {

&nbsp;       CHAR(36) id PK

&nbsp;       VARCHAR(120) nombre

&nbsp;       DATE fecha\_inicio

&nbsp;       DATE fecha\_fin

&nbsp;       TINYINT(1) activo

&nbsp;   }



&nbsp;   bloque\_horario {

&nbsp;       CHAR(36) id PK

&nbsp;       ENUM dia\_semana

&nbsp;       TIME hora\_inicio

&nbsp;       TIME hora\_fin

&nbsp;   }



&nbsp;   usuario {

&nbsp;       CHAR(36) id PK

&nbsp;       VARCHAR(120) nombre

&nbsp;       VARCHAR(160) email UK

&nbsp;       VARCHAR(255) password\_hash

&nbsp;       ENUM rol

&nbsp;       TINYINT(1) activo

&nbsp;       DATETIME created\_at

&nbsp;       DATETIME updated\_at

&nbsp;   }



&nbsp;   profesor {

&nbsp;       CHAR(36) id PK

&nbsp;       CHAR(36) usuario\_id FK,UK

&nbsp;       TEXT especialidades

&nbsp;       VARCHAR(255) hoja\_vida\_url

&nbsp;   }



&nbsp;   grupo {

&nbsp;       CHAR(36) id PK

&nbsp;       VARCHAR(120) nombre

&nbsp;       VARCHAR(60) nivel

&nbsp;       INT num\_estudiantes

&nbsp;       TEXT caracteristicas

&nbsp;       TINYINT(1) activo

&nbsp;   }



&nbsp;   salon {

&nbsp;       CHAR(36) id PK

&nbsp;       VARCHAR(60) codigo UK

&nbsp;       INT capacidad

&nbsp;       VARCHAR(160) ubicacion

&nbsp;       TINYINT(1) activo

&nbsp;   }



&nbsp;   recurso {

&nbsp;       CHAR(36) id PK

&nbsp;       VARCHAR(100) nombre

&nbsp;       VARCHAR(255) descripcion

&nbsp;   }



&nbsp;   salon\_recurso {

&nbsp;       CHAR(36) salon\_id PK,FK

&nbsp;       CHAR(36) recurso\_id PK,FK

&nbsp;       INT cantidad

&nbsp;   }



&nbsp;   recurso\_disponibilidad {

&nbsp;       CHAR(36) recurso\_id PK,FK

&nbsp;       CHAR(36) bloque\_id PK,FK

&nbsp;       ENUM estado

&nbsp;   }



&nbsp;   disp\_profesor {

&nbsp;       CHAR(36) profesor\_id PK,FK

&nbsp;       CHAR(36) bloque\_id PK,FK

&nbsp;       ENUM estado

&nbsp;   }



&nbsp;   disp\_salon {

&nbsp;       CHAR(36) salon\_id PK,FK

&nbsp;       CHAR(36) bloque\_id PK,FK

&nbsp;       ENUM estado

&nbsp;   }



&nbsp;   asignacion {

&nbsp;       CHAR(36) id PK

&nbsp;       CHAR(36) grupo\_id FK

&nbsp;       CHAR(36) salon\_id FK

&nbsp;       CHAR(36) profesor\_id FK

&nbsp;       CHAR(36) bloque\_id FK

&nbsp;       CHAR(36) periodo\_id FK

&nbsp;       ENUM estado

&nbsp;       ENUM origen

&nbsp;       FLOAT score

&nbsp;       CHAR(36) created\_by FK

&nbsp;       DATETIME created\_at

&nbsp;   }



&nbsp;   tipo\_restriccion {

&nbsp;       CHAR(36) id PK

&nbsp;       VARCHAR(80) nombre UK

&nbsp;       TEXT descripcion

&nbsp;       JSON regla\_default\_json

&nbsp;   }



&nbsp;   restriccion {

&nbsp;       CHAR(36) id PK

&nbsp;       VARCHAR(80) tipo

&nbsp;       VARCHAR(80) objetivo\_type

&nbsp;       CHAR(36) objetivo\_id

&nbsp;       JSON regla\_json

&nbsp;       ENUM dureza

&nbsp;   }



&nbsp;   auditoria {

&nbsp;       CHAR(36) id PK

&nbsp;       CHAR(36) usuario\_id FK

&nbsp;       VARCHAR(80) entidad

&nbsp;       CHAR(36) entidad\_id

&nbsp;       VARCHAR(40) accion

&nbsp;       JSON cambios\_json

&nbsp;       VARCHAR(255) motivo

&nbsp;       DATETIME created\_at

&nbsp;   }



&nbsp;   reporte\_ocupacion {

&nbsp;       CHAR(36) id PK

&nbsp;       CHAR(36) periodo\_id FK

&nbsp;       ENUM tipo

&nbsp;       CHAR(36) objetivo\_id

&nbsp;       FLOAT ocupacion\_porcentaje

&nbsp;       INT num\_bloques\_ocupados

&nbsp;       DATETIME created\_at

&nbsp;   }



&nbsp;   parametro\_sistema {

&nbsp;       CHAR(36) id PK

&nbsp;       VARCHAR(120) clave UK

&nbsp;       JSON valor

&nbsp;       VARCHAR(60) scope

&nbsp;   }



&nbsp;   %% Relaciones

&nbsp;   usuario ||--o{ profesor : "es"

&nbsp;   usuario ||--o{ asignacion : "crea"

&nbsp;   usuario ||--o{ auditoria : "realiza"



&nbsp;   profesor ||--o{ disp\_profesor : "tiene"

&nbsp;   profesor ||--o{ asignacion : "asignado"



&nbsp;   grupo ||--o{ asignacion : "asignado"



&nbsp;   salon ||--o{ salon\_recurso : "tiene"

&nbsp;   salon ||--o{ disp\_salon : "tiene"

&nbsp;   salon ||--o{ asignacion : "asignado"



&nbsp;   recurso ||--o{ salon\_recurso : "asignado"

&nbsp;   recurso ||--o{ recurso\_disponibilidad : "tiene"



&nbsp;   bloque\_horario ||--o{ disp\_profesor : "define"

&nbsp;   bloque\_horario ||--o{ disp\_salon : "define"

&nbsp;   bloque\_horario ||--o{ recurso\_disponibilidad : "define"

&nbsp;   bloque\_horario ||--o{ asignacion : "define"



&nbsp;   periodo\_academico ||--o{ asignacion : "pertenece"

&nbsp;   periodo\_academico ||--o{ reporte\_ocupacion : "pertenece"



&nbsp;   salon\_recurso }o--o{ recurso : "relaciona"

&nbsp;   disp\_profesor }o--o{ bloque\_horario : "relaciona"

&nbsp;   disp\_salon }o--o{ bloque\_horario : "relaciona"

&nbsp;   recurso\_disponibilidad }o--o{ bloque\_horario : "relaciona"

```



\- \*\*Entidades:\*\* Cada tabla se representa como una entidad con sus atributos. Por ejemplo, periodo\_academico incluye id (PK), nombre, fecha\_inicio, fecha\_fin, y activo.

\- \*\*Relaciones:\*\*



&nbsp;	- Uno a muchos: Ejemplo, usuario ||--o{ profesor indica que un usuario puede ser un profesor, pero un profesor está ligado a un solo usuario.

&nbsp;	- Muchos a muchos: Ejemplo, salon\_recurso }o--o{ recurso refleja la relación entre salones y recursos, mediada por la tabla de unión salon\_recurso.

&nbsp;	- Relaciones como asignacion vinculan múltiples entidades (grupo, salon, profesor, bloque\_horario, periodo\_academico) con claves foráneas.





\- \*\*Notación:\*\*



||--o{: Relación uno a muchos.

}o--o{: Relación muchos a muchos.

PK, FK, UK: Indican clave primaria, foránea, y única, respectivamente.





\- \*\*Tablas de Unión:\*\* salon\_recurso, disp\_profesor, disp\_salon, y recurso\_disponibilidad modelan relaciones muchos a muchos con atributos adicionales (e.g., cantidad en salon\_recurso, estado en disp\_profesor).

\- \*\*Vistas y Triggers:\*\* No se representan en el diagrama (ya que Mermaid ERD se centra en entidades y relaciones), pero vista\_conflictos\_salon, vista\_conflictos\_profesor, y trg\_valida\_restriccion complementan la funcionalidad en la implementación.



\### Cómo Usar el Código



Copia el código Mermaid dentro del <xaiArtifact> y pégalo en un editor compatible con Mermaid, como Mermaid Live Editor.

Renderiza el diagrama para visualizar las entidades y relaciones.

Si usas markdown en una plataforma compatible (e.g., GitHub), el código puede renderizarse directamente dentro de un bloque ```mermaid:disable-run



\## Cumplimiento con el Documento

El diagrama refleja el modelo entidad-relación requerido en la primera entrega del proyecto (clase 9), cubriendo el Diagrama Entidad-Relación y el Modelo Relacional. Representa todas las entidades necesarias para las épicas (HU1-HU19), incluyendo gestión de usuarios, grupos, salones, profesores, asignaciones, restricciones, auditoría, reportes, y configuraciones. Las relaciones y cardinalidades están alineadas con las necesidades funcionales (e.g., asignaciones automáticas/manuales, conflictos, reportes).

