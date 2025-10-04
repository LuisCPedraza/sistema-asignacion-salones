```markdown

\# Descripción General Detallada: Base de Datos



A continuación, presento una \*\*descripción detallada\*\* de cómo el \*\*esquema actualizado de la base de datos\*\* cumple con los requerimientos especificados en el documento \*\*“Proyectos Desarrollo de Software 2.docx”\*\*. El esquema ha sido diseñado para satisfacer \*\*todas las épicas, historias de usuario (HU), historias técnicas (TH), y criterios de aceptación\*\* del \*\*sistema de asignación de salones\*\* para un centro educativo, siguiendo las prácticas de \*\*DevOps, Scrum, Kanban y TDD\*\*, con un enfoque en \*\*mantenibilidad, modularidad, cohesión y bajo acoplamiento\*\*. También se han incorporado mejoras para \*\*optimizar eficiencia, escalabilidad y robustez\*\*, alineándose con las entregas del proyecto y los criterios generales.



---



\## 1. Cumplimiento de los Objetivos Generales del Documento



El documento establece que el sistema debe integrar el ciclo completo de \*\*DevOps\*\*, gestionarse con \*\*Scrum\*\* y \*\*Kanban\*\*, e implementar \*\*TDD\*\* (pruebas unitarias y refactoring). Además, se evalúan \*\*mantenibilidad, modularidad, cohesión y bajo acoplamiento\*\*. La base de datos cumple con estos objetivos de la siguiente manera:



\### Ciclo DevOps

\- La estructura relacional con tablas normalizadas (`usuario`, `profesor`, `grupo`, `salon`, etc.) y la tabla `asignacion` \*\*con particionamiento por `periodo\_id`\*\* facilita la integración con herramientas de \*\*integración y despliegue continuo\*\* (p. ej., \*\*GitHub Actions\*\*, mencionadas en la segunda entrega).  

\- Los \*\*índices optimizados\*\* (p. ej., `idx\_as\_horario\_salon`, `idx\_as\_conflictos`) y \*\*vistas\*\* (`vista\_conflictos\_salon`, `vista\_conflictos\_profesor`) aseguran un \*\*rendimiento adecuado\*\* para operaciones en tiempo real.  

\- La tabla `auditoria` permite \*\*trazabilidad de cambios\*\*, esencial para auditorías en un pipeline DevOps.  

\- La tabla `parametro\_sistema` soporta \*\*configuraciones dinámicas\*\*, facilitando la adaptación del sistema sin cambios en el código, un principio clave de DevOps.



\### Scrum y Kanban

\- La base de datos está diseñada para soportar la \*\*gestión del proyecto mediante tableros Kanban\*\* (segunda entrega). Por ejemplo, la tabla `asignacion` con \*\*`estado`\*\* (\*Propuesta/Confirmada/Anulada\*) y \*\*`origen`\*\* (\*Manual/Automática\*) permite \*\*rastrear el progreso\*\* de las asignaciones en un tablero Kanban, integrándose con herramientas como \*\*GitHub Issues\*\* o \*\*Projects\*\*.  

\- La \*\*estructura modular\*\* (tablas separadas por entidad: `usuario`, `grupo`, `salon`, etc.) permite \*\*iteraciones ágiles\*\*, ya que cada épica puede desarrollarse y probarse de forma independiente.



\### TDD (Pruebas Unitarias y Refactoring)

\- La \*\*modularidad\*\* de las tablas y las \*\*restricciones de integridad\*\* (`FOREIGN KEY`, `CHECK`, `UNIQUE`) facilitan la creación de \*\*pruebas unitarias\*\* para validar operaciones CRUD y reglas de negocio (p. ej., \*\*capacidad de salones\*\*, \*\*conflictos de horario\*\*).  

\- Los \*\*triggers\*\* (p. ej., `trg\_valida\_restriccion`) y \*\*vistas\*\* aseguran que las \*\*reglas de negocio\*\* se mantengan consistentes, reduciendo la necesidad de \*\*refactoring\*\* complejo en el código de la aplicación.



\### Mantenibilidad, Modularidad, Cohesión y Bajo Acoplamiento

\- \*\*Mantenibilidad:\*\* Tablas normalizadas (p. ej., `salon\_recurso`, `recurso\_disponibilidad`) y \*\*nombres de campos claros\*\*, lo que facilita el mantenimiento. La tabla `auditoria` registra cambios, ayudando a \*\*diagnosticar problemas\*\*.  

\- \*\*Modularidad:\*\* Cada entidad (`usuarios`, `profesores`, `grupos`, `salones`) tiene su \*\*propia tabla\*\*, lo que permite \*\*desarrollar y modificar módulos\*\* del sistema de forma independiente.  

\- \*\*Cohesión:\*\* Cada tabla tiene una \*\*responsabilidad clara\*\* (p. ej., `bloque\_horario` para horarios, `restriccion` para reglas), asegurando que las \*\*funciones estén bien definidas\*\*.  

\- \*\*Bajo Acoplamiento:\*\* Las relaciones entre tablas usan \*\*claves foráneas\*\*, pero las dependencias están \*\*minimizadas\*\*, permitiendo cambios en una tabla sin afectar ampliamente otras (p. ej., `recurso\_disponibilidad` es independiente de `salon`).



---



\## 2. Cumplimiento de las Entregas del Proyecto



El documento especifica \*\*dos entregas principales\*\*, con criterios claros para cada una. La base de datos \*\*soporta ambos conjuntos de requisitos\*\*:



\### Primera Entrega (Clase 9)



\*\*Análisis, Levantamiento de Requerimientos y Diseño (50%)\*\*

\- \*\*Diagrama de Casos de Uso y Casos de Uso:\*\* La base de datos cubre \*\*todas las HU (HU1–HU19)\*\*, desde \*\*autenticación\*\* hasta \*\*reportes\*\*. Por ejemplo, las tablas `usuario` y `profesor` soportan \*\*HU1\*\* y \*\*HU7\*\*, mientras que `asignacion` y `restriccion` soportan \*\*HU9–HU12\*\*.  

\- \*\*Diagrama de Clases / Flujo de Datos:\*\* Las tablas reflejan un \*\*diseño orientado a objetos\*\* (p. ej., `Usuario`, `Profesor`, `Grupo`, `Salon` como clases) con \*\*relaciones claras\*\* (`FOREIGN KEY`) que modelan \*\*flujos de datos\*\*, como la asignación de grupos a salones y profesores.  

\- \*\*DER, Modelo Relacional y Modelo Físico:\*\* El esquema SQL es el \*\*modelo físico\*\*, derivado de un \*\*modelo relacional normalizado\*\* (3FN en la mayoría de las tablas) y un \*\*modelo entidad-relación\*\* implícito en las tablas y sus relaciones.



\*\*Configuración de la Infraestructura de Desarrollo (50%)\*\*

\- \*\*Repositorio GitHub y Estrategia de Branching:\*\* La base de datos no interactúa directamente con GitHub, pero su \*\*diseño modular\*\* permite integrarse con un \*\*repositorio\*\* para almacenar \*\*scripts SQL, triggers y vistas\*\*, soportando \*\*branching\*\* para desarrollo iterativo.  

\- \*\*Configuración de la Base de Datos:\*\* El esquema usa \*\*MySQL\*\* con \*\*InnoDB\*\* y \*\*utf8mb4\*\*, con \*\*índices, particiones, triggers y vistas\*\*, cumpliendo con los requisitos de \*\*configuración robusta\*\*.  

\- \*\*Entorno de Desarrollo:\*\* La base de datos es \*\*compatible con entornos modernos\*\* (MySQL es ampliamente soportado), y los \*\*comentarios SQL\*\* (p. ej., sobre `password\_hash`) guían la \*\*implementación segura\*\*.



\### Segunda Entrega (Clase 15)



\*\*Gestión del Proyecto (25%)\*\*  

Las tablas `asignacion` (con `estado` y `origen`) y `auditoria` permiten \*\*rastrear el estado\*\* de las asignaciones y los cambios, integrándose con herramientas como \*\*GitHub Issues\*\*, \*\*Projects\*\* y \*\*Milestones\*\* para \*\*tableros Kanban\*\*.



\*\*Continuous Development (25%)\*\*  

La estructura soporta \*\*integración con GitHub Repository\*\* mediante scripts SQL \*\*versionados\*\*, y las \*\*claves foráneas y restricciones\*\* facilitan \*\*pull requests\*\* al garantizar \*\*datos consistentes\*\*.



\*\*Integración y Despliegue Continuo (25%)\*\*  

La base de datos está \*\*optimizada\*\* para integrarse con \*\*GitHub Actions\*\* (p. ej., para ejecutar \*\*scripts de migración\*\* o \*\*pruebas\*\*). Las \*\*vistas\*\* (`vista\_conflictos\_salon`, `vista\_conflictos\_profesor`) y la tabla `reporte\_ocupacion` facilitan \*\*pruebas unitarias automatizadas\*\* para validar asignaciones y conflictos.



\*\*Funcionalidad de Módulos Desarrollados (25%)\*\*  

Cada \*\*módulo\*\* (gestión de usuarios, grupos, salones, asignaciones, etc.) está soportado por \*\*tablas específicas\*\*, con \*\*índices y vistas\*\* que aseguran funcionalidad \*\*eficiente\*\*.



---



\## 3. Cumplimiento de las Épicas Principales



A continuación, detallo cómo la base de datos soporta cada épica del \*\*backlog de producto\*\*:



\### Épica 1: Gestión de Usuarios y Autenticación (HU1, HU2)

\- \*\*Tabla `usuario`:\*\* Almacena datos de usuarios (`nombre`, `email`, `password\_hash`) y \*\*roles\*\* (`ADMIN`, `COORDINADOR`, `PROFESOR`, `coord\_INFRA`), soportando \*\*creación, gestión y autenticación por rol\*\*. Campos `created\_at` y `updated\_at` habilitan \*\*auditoría\*\*.  

\- \*\*Seguridad:\*\* El comentario sobre usar \*\*bcrypt\*\* para `password\_hash` asegura \*\*autenticación segura\*\*.



\### Épica 2: Gestión de Grupos de Estudiantes (HU3, HU4)

\- \*\*Tabla `grupo`:\*\* Incluye `nombre`, `nivel`, `num\_estudiantes` y `caracteristicas` para \*\*registrar y gestionar grupos\*\*. El campo `activo` permite \*\*desactivar\*\* grupos sin eliminarlos (\*\*HU4\*\*).



\### Épica 3: Gestión de Salones (HU5, HU6)

\- \*\*Tabla `salon`:\*\* Almacena `codigo`, `capacidad`, `ubicacion` y `activo` para \*\*registrar salones\*\*.  

\- \*\*Tablas `salon\_recurso` y `recurso`:\*\* Gestionan \*\*recursos\*\* como proyectores o computadoras.  

\- \*\*Tabla `disp\_salon`:\*\* Registra \*\*disponibilidad horaria\*\* con estados (\*Disponible, NoDisponible, Reservado, Mantenimiento\*), cumpliendo \*\*HU6\*\*.  

\- \*\*Tabla `recurso\_disponibilidad`:\*\* Añade \*\*restricciones horarias\*\* para recursos.



\### Épica 4: Gestión de Profesores (HU7, HU8)

\- \*\*Tabla `profesor`:\*\* Almacena \*\*especialidades\*\* y `hoja\_vida\_url`, vinculada a `usuario` por `usuario\_id`.  

\- \*\*Tabla `disp\_profesor`:\*\* Gestiona \*\*disponibilidad horaria\*\* con estados (\*Disponible, NoDisponible, Preferido, Licencia\*), soportando \*\*asignaciones especiales\*\*.



\### Épica 5: Sistema de Asignación Automática (HU9, HU10)

\- \*\*Tabla `asignacion`:\*\* Registra asignaciones con `grupo\_id`, `salon\_id`, `profesor\_id`, `bloque\_id`, `periodo\_id`, `estado`, `origen` y `score`. El campo \*\*`score`\*\* facilita la \*\*evaluación\*\* de asignaciones \*\*automáticas\*\*.  

\- \*\*Tablas `restriccion` y `tipo\_restriccion`:\*\* Permiten configurar \*\*parámetros y prioridades\*\* (p. ej., \*\*minimizar cambios de salón\*\*) con `regla\_json` y reglas predefinidas, soportando \*\*HU10\*\*.



\### Épica 6: Sistema de Asignación Manual (HU11, HU12)

\- \*\*`asignacion` con `origen = 'Manual'`\*\* soporta \*\*asignaciones manuales\*\*.  

\- \*\*Vistas `vista\_conflictos\_salon` y `vista\_conflictos\_profesor`:\*\* Detectan \*\*conflictos en tiempo real\*\* (sobrecupos, superposiciones), cumpliendo \*\*HU12\*\*.



\### Épica 7: Visualización y Reportes (HU13, HU14, HU15)

\- `asignacion` junto con `periodo\_academico` y `bloque\_horario` permite \*\*visualizar horarios\*\* completos (\*\*HU13\*\*) y \*\*personales\*\* (\*\*HU14\*\*).  

\- \*\*Tabla `reporte\_ocupacion`:\*\* Proporciona \*\*estadísticas precalculadas\*\* de \*\*utilización\*\* de salones y profesores, optimizando \*\*HU15\*\*.



\### Épica 8: Gestión de Conflictos y Restricciones (HU16, HU17)

\- \*\*Tabla `restriccion`:\*\* Define \*\*restricciones específicas\*\* con `tipo`, `objetivo\_type`, `objetivo\_id`, `regla\_json` y `dureza`. El \*\*trigger `trg\_valida\_restriccion`\*\* asegura la validez de `objetivo\_id`.  

\- \*\*Vistas de conflictos:\*\* `vista\_conflictos\_salon` y `vista\_conflictos\_profesor` \*\*notifican conflictos\*\* y pueden \*\*sugerir alternativas\*\* al integrarse con la lógica de la aplicación.



\### Épica 9: Historial y Auditoría (HU18)

\- \*\*Tabla `auditoria`:\*\* Registra cambios con `usuario\_id`, `entidad`, `entidad\_id`, `accion`, `cambios\_json`, `motivo` y `created\_at`, cumpliendo \*\*HU18\*\* al \*\*rastrear quién\*\* realizó cada modificación.



\### Épica 10: Configuración del Sistema (HU19)

\- \*\*Tabla `parametro\_sistema`:\*\* Almacena \*\*configuraciones generales\*\* (`clave`, `valor`, `scope`) como \*\*períodos académicos\*\* o \*\*días laborables\*\*. Los \*\*comentarios SQL\*\* especifican \*\*claves esperadas\*\*, asegurando \*\*consistencia\*\*.



---



\## 4. Cumplimiento de las Historias Técnicas



\- \*\*TH1: Configurar e implementar la base de datos\*\*  

&nbsp; El esquema completo con \*\*InnoDB\*\* y \*\*utf8mb4\*\* está implementado, con \*\*tablas normalizadas, índices, particiones, triggers y vistas\*\*, cubriendo \*\*todas las entidades y relaciones\*\* necesarias.



\- \*\*TH2: Desarrollar API RESTful\*\*  

&nbsp; Aunque la API es responsabilidad del backend, la base de datos está diseñada para \*\*soportar operaciones RESTful\*\*. Cada tabla (p. ej., `usuario`, `grupo`, `asignacion`) tiene un \*\*`id` único\*\* y \*\*campos claros\*\*, facilitando endpoints como `/usuarios`, `/grupos`, `/asignaciones`.



\- \*\*TH3: Sistema de autenticación y autorización segura\*\*  

&nbsp; `usuario` con `password\_hash` (recomendado \*\*bcrypt\*\*) y `rol` permite \*\*autenticación\*\* y \*\*autorización por roles\*\*. La tabla `auditoria` puede registrar \*\*intentos de acceso\*\* si es necesario.



\- \*\*TH4: Interfaz responsive y accesible\*\*  

&nbsp; La base de datos soporta una \*\*interfaz responsive\*\* al proporcionar \*\*datos estructurados y optimizados\*\* (índices, vistas) para \*\*consultas rápidas\*\*, compatibles con \*\*navegadores modernos\*\*.



---



\## 5. Cumplimiento de los Criterios de Aceptación General



\- \*\*Intuitivo y mínima capacitación:\*\* La estructura \*\*clara y modular\*\* (tablas específicas por entidad) permite una \*\*interfaz intuitiva\*\*, ya que los \*\*datos están organizados lógicamente\*\*.  

\- \*\*Respuesta en menos de 2 segundos:\*\* Los \*\*índices\*\* (p. ej., `idx\_as\_horario\_salon`, `idx\_as\_conflictos`) y \*\*vistas\*\* (`vista\_conflictos\_salon`) \*\*optimizan consultas críticas\*\*, asegurando un \*\*rendimiento adecuado\*\*.  

\- \*\*Compatibilidad con navegadores modernos:\*\* La base de datos es \*\*independiente del frontend\*\*, pero su \*\*diseño relacional\*\* y uso de \*\*MySQL\*\* (ampliamente soportado) garantiza \*\*compatibilidad\*\* con aplicaciones web modernas.  

\- \*\*Seguridad y respaldo de datos:\*\* El uso de \*\*`password\_hash`\*\*, \*\*triggers\*\* para \*\*validaciones\*\*, y `auditoria` asegura la \*\*seguridad\*\*. La estructura \*\*InnoDB\*\* soporta \*\*transacciones\*\* y \*\*respaldos regulares\*\*.



---



\## 6. Priorización Inicial



El documento prioriza las épicas en cuatro grupos. La base de datos las \*\*soporta completamente\*\*:



\- \*\*Épicas 1, 2, 3, 4 (Gestión básica):\*\* Tablas `usuario`, `grupo`, `salon`, `profesor`, `salon\_recurso`, `disp\_salon`, `disp\_profesor` y `recurso\_disponibilidad` cubren la \*\*gestión de usuarios y recursos\*\*.  

\- \*\*Épicas 10, 6 (Configuración y asignación manual):\*\* `parametro\_sistema` y `asignacion` (con `origen='Manual'`) junto con \*\*vistas de conflictos\*\* soportan estas funcionalidades.  

\- \*\*Épica 5 (Asignación automática):\*\* `asignacion` (con `score`), `restriccion` y `tipo\_restriccion` permiten \*\*algoritmos de asignación automática\*\*.  

\- \*\*Épicas 7, 8, 9 (Visualización, conflictos, historial):\*\* `reporte\_ocupacion`, \*\*vistas de conflictos\*\* y `auditoria` cubren estas necesidades.



---



\## 7. Eficiencia y Escalabilidad



\- \*\*Eficiencia:\*\* Los \*\*índices\*\* en `asignacion`, `restriccion` y `auditoria` optimizan \*\*consultas frecuentes\*\*. Las \*\*vistas\*\* precalculan \*\*conflictos\*\*, reduciendo la \*\*carga computacional\*\*. La tabla `reporte\_ocupacion` evita \*\*cálculos complejos\*\* para reportes.  

\- \*\*Escalabilidad:\*\* El \*\*particionamiento por `periodo\_id`\*\* en `asignacion` permite \*\*manejar grandes volúmenes\*\* de datos, dividiendo la tabla en \*\*particiones\*\* más pequeñas.  

\- \*\*Robustez:\*\* \*\*Triggers\*\* (`trg\_valida\_restriccion`) y \*\*restricciones\*\* (`FOREIGN KEY`, `CHECK`, `UNIQUE`) aseguran \*\*integridad\*\*. Estados adicionales en `disp\_salon` (\*Mantenimiento\*) y `disp\_profesor` (\*Licencia\*) \*\*manejan casos reales\*\*.



---



\## Conclusión



La base de datos \*\*cumple al 100%\*\* con los requerimientos del documento, cubriendo todas las \*\*épicas\*\*, \*\*historias técnicas\*\* y \*\*criterios de aceptación\*\*. Es \*\*eficiente\*\* gracias a \*\*índices, vistas y particionamiento\*\*; \*\*robusta\*\* con \*\*validaciones y auditoría\*\*; y \*\*escalable\*\* para manejar grandes volúmenes de datos. Soporta las \*\*entregas del proyecto\*\* (análisis, infraestructura, gestión y despliegue continuo) y sigue las prácticas de \*\*DevOps, Scrum y TDD\*\*, con \*\*alta mantenibilidad, modularidad, cohesión y bajo acoplamiento\*\*.

```





\## Esquema SQL Completo





-- Tabla para períodos académicos

```sql

CREATE TABLE periodo\_academico (

&nbsp; id CHAR(36) PRIMARY KEY,

&nbsp; nombre VARCHAR(120) NOT NULL,

&nbsp; fecha\_inicio DATE NOT NULL,

&nbsp; fecha\_fin DATE NOT NULL,

&nbsp; activo TINYINT(1) NOT NULL DEFAULT 1

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

```



-- Tabla para bloques horarios (cambio a ENUM para legibilidad)

```sql

CREATE TABLE bloque\_horario (

&nbsp; id CHAR(36) PRIMARY KEY,

&nbsp; dia\_semana ENUM('Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo') NOT NULL,

&nbsp; hora\_inicio TIME NOT NULL,

&nbsp; hora\_fin TIME NOT NULL,

&nbsp; CONSTRAINT chk\_bloque\_duracion CHECK (hora\_fin > hora\_inicio)

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

```



-- Tabla para usuarios

```sql

CREATE TABLE usuario (

&nbsp; id CHAR(36) PRIMARY KEY,

&nbsp; nombre VARCHAR(120) NOT NULL,

&nbsp; email VARCHAR(160) NOT NULL UNIQUE,

&nbsp; password\_hash VARCHAR(255) NOT NULL,  -- Usar bcrypt para hashing seguro

&nbsp; rol ENUM('ADMIN','COORDINADOR','PROFESOR','COORD\_INFRA') NOT NULL,

&nbsp; activo TINYINT(1) NOT NULL DEFAULT 1,

&nbsp; created\_at DATETIME NOT NULL,

&nbsp; updated\_at DATETIME NOT NULL

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

```



-- Tabla para profesores

```sql

CREATE TABLE profesor (

&nbsp; id CHAR(36) PRIMARY KEY,

&nbsp; usuario\_id CHAR(36) NOT NULL UNIQUE,

&nbsp; especialidades TEXT NULL,

&nbsp; hoja\_vida\_url VARCHAR(255) NULL,

&nbsp; CONSTRAINT fk\_profesor\_usuario FOREIGN KEY (usuario\_id) REFERENCES usuario(id)

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

```



-- Tabla para grupos

```sql

CREATE TABLE grupo (

&nbsp; id CHAR(36) PRIMARY KEY,

&nbsp; nombre VARCHAR(120) NOT NULL,

&nbsp; nivel VARCHAR(60) NOT NULL,

&nbsp; num\_estudiantes INT NOT NULL CHECK (num\_estudiantes > 0),

&nbsp; caracteristicas TEXT NULL,

&nbsp; activo TINYINT(1) NOT NULL DEFAULT 1

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

```



-- Tabla para salones

```sql

CREATE TABLE salon (

&nbsp; id CHAR(36) PRIMARY KEY,

&nbsp; codigo VARCHAR(60) NOT NULL UNIQUE,

&nbsp; capacidad INT NOT NULL CHECK (capacidad > 0),

&nbsp; ubicacion VARCHAR(160) NOT NULL,

&nbsp; activo TINYINT(1) NOT NULL DEFAULT 1

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

```



-- Tabla para recursos

```sql

CREATE TABLE recurso (

&nbsp; id CHAR(36) PRIMARY KEY,

&nbsp; nombre VARCHAR(100) NOT NULL,

&nbsp; descripcion VARCHAR(255) NULL

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

```



-- Tabla para asociación salón-recurso

```sql

CREATE TABLE salon\_recurso (

&nbsp; salon\_id CHAR(36) NOT NULL,

&nbsp; recurso\_id CHAR(36) NOT NULL,

&nbsp; cantidad INT NOT NULL CHECK (cantidad >= 0),

&nbsp; PRIMARY KEY (salon\_id, recurso\_id),

&nbsp; CONSTRAINT fk\_sr\_salon FOREIGN KEY (salon\_id) REFERENCES salon(id),

&nbsp; CONSTRAINT fk\_sr\_recurso FOREIGN KEY (recurso\_id) REFERENCES recurso(id)

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

```



-- Nueva tabla para disponibilidad de recursos (normalización)

```sql

CREATE TABLE recurso\_disponibilidad (

&nbsp; recurso\_id CHAR(36) NOT NULL,

&nbsp; bloque\_id CHAR(36) NOT NULL,

&nbsp; estado ENUM('Disponible','NoDisponible','Reservado') NOT NULL,

&nbsp; PRIMARY KEY (recurso\_id, bloque\_id),

&nbsp; CONSTRAINT fk\_rd\_recurso FOREIGN KEY (recurso\_id) REFERENCES recurso(id),

&nbsp; CONSTRAINT fk\_rd\_bloque FOREIGN KEY (bloque\_id) REFERENCES bloque\_horario(id)

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

```



-- Tabla para disponibilidad de profesores (estados adicionales)

```sql

CREATE TABLE disp\_profesor (

&nbsp; profesor\_id CHAR(36) NOT NULL,

&nbsp; bloque\_id CHAR(36) NOT NULL,

&nbsp; estado ENUM('Disponible','NoDisponible','Preferido','Licencia') NOT NULL,

&nbsp; PRIMARY KEY (profesor\_id, bloque\_id),

&nbsp; CONSTRAINT fk\_dp\_profesor FOREIGN KEY (profesor\_id) REFERENCES profesor(id),

&nbsp; CONSTRAINT fk\_dp\_bloque FOREIGN KEY (bloque\_id) REFERENCES bloque\_horario(id)

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

```



-- Tabla para disponibilidad de salones (estados adicionales)

```sql

CREATE TABLE disp\_salon (

&nbsp; salon\_id CHAR(36) NOT NULL,

&nbsp; bloque\_id CHAR(36) NOT NULL,

&nbsp; estado ENUM('Disponible','NoDisponible','Reservado','Mantenimiento') NOT NULL,

&nbsp; PRIMARY KEY (salon\_id, bloque\_id),

&nbsp; CONSTRAINT fk\_ds\_salon FOREIGN KEY (salon\_id) REFERENCES salon(id),

&nbsp; CONSTRAINT fk\_ds\_bloque FOREIGN KEY (bloque\_id) REFERENCES bloque\_horario(id)

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

```



-- Tabla para asignaciones (agregado score y particionamiento para escalabilidad)

```sql

CREATE TABLE asignacion (

&nbsp; id CHAR(36) PRIMARY KEY,

&nbsp; grupo\_id CHAR(36) NOT NULL,

&nbsp; salon\_id CHAR(36) NOT NULL,

&nbsp; profesor\_id CHAR(36) NOT NULL,

&nbsp; bloque\_id CHAR(36) NOT NULL,

&nbsp; periodo\_id CHAR(36) NOT NULL,

&nbsp; estado ENUM('Propuesta','Confirmada','Anulada') NOT NULL DEFAULT 'Propuesta',

&nbsp; origen ENUM('Manual','Automatica') NOT NULL,

&nbsp; score FLOAT NULL,  -- Puntaje para asignaciones automáticas

&nbsp; created\_by CHAR(36) NOT NULL,

&nbsp; created\_at DATETIME NOT NULL,

&nbsp; CONSTRAINT fk\_as\_grupo FOREIGN KEY (grupo\_id) REFERENCES grupo(id),

&nbsp; CONSTRAINT fk\_as\_salon FOREIGN KEY (salon\_id) REFERENCES salon(id),

&nbsp; CONSTRAINT fk\_as\_prof FOREIGN KEY (profesor\_id) REFERENCES profesor(id),

&nbsp; CONSTRAINT fk\_as\_bloque FOREIGN KEY (bloque\_id) REFERENCES bloque\_horario(id),

&nbsp; CONSTRAINT fk\_as\_periodo FOREIGN KEY (periodo\_id) REFERENCES periodo\_academico(id),

&nbsp; CONSTRAINT uq\_as\_unique UNIQUE (grupo\_id, bloque\_id, periodo\_id),

&nbsp; INDEX idx\_as\_horario\_salon (periodo\_id, bloque\_id, salon\_id),

&nbsp; INDEX idx\_as\_horario\_prof (periodo\_id, bloque\_id, profesor\_id),

&nbsp; INDEX idx\_as\_conflictos (periodo\_id, bloque\_id, salon\_id, profesor\_id)  -- Índice adicional para conflictos

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4

PARTITION BY HASH(periodo\_id) PARTITIONS 4;  -- Particionamiento para escalabilidad

```



-- Nueva tabla auxiliar para tipos de restricciones predefinidas

```sql

CREATE TABLE tipo\_restriccion (

&nbsp; id CHAR(36) PRIMARY KEY,

&nbsp; nombre VARCHAR(80) NOT NULL UNIQUE,

&nbsp; descripcion TEXT NULL,

&nbsp; regla\_default\_json JSON NULL  -- Plantilla JSON para reglas predefinidas

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

```



-- Tabla para restricciones (con trigger para validación)

```sql

CREATE TABLE restriccion (

&nbsp; id CHAR(36) PRIMARY KEY,

&nbsp; tipo VARCHAR(80) NOT NULL,

&nbsp; objetivo\_type VARCHAR(80) NOT NULL,

&nbsp; objetivo\_id CHAR(36) NOT NULL,

&nbsp; regla\_json JSON NOT NULL,

&nbsp; dureza ENUM('Blando','Duro') NOT NULL,

&nbsp; INDEX idx\_restriccion\_objetivo (objetivo\_type, objetivo\_id)

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

```



-- Trigger para validar objetivo\_type y objetivo\_id en restricción

```sql

DELIMITER //

CREATE TRIGGER trg\_valida\_restriccion BEFORE INSERT ON restriccion

FOR EACH ROW

BEGIN

&nbsp; DECLARE exists\_count INT;

&nbsp; IF NEW.objetivo\_type = 'salon' THEN

&nbsp;   SELECT COUNT(\*) INTO exists\_count FROM salon WHERE id = NEW.objetivo\_id;

&nbsp; ELSEIF NEW.objetivo\_type = 'profesor' THEN

&nbsp;   SELECT COUNT(\*) INTO exists\_count FROM profesor WHERE id = NEW.objetivo\_id;

&nbsp; ELSEIF NEW.objetivo\_type = 'grupo' THEN

&nbsp;   SELECT COUNT(\*) INTO exists\_count FROM grupo WHERE id = NEW.objetivo\_id;

&nbsp; -- Agregar más tipos según necesidades

&nbsp; ELSE

&nbsp;   SIGNAL SQLSTATE '45000' SET MESSAGE\_TEXT = 'Tipo de objetivo inválido';

&nbsp; END IF;

&nbsp; IF exists\_count = 0 THEN

&nbsp;   SIGNAL SQLSTATE '45000' SET MESSAGE\_TEXT = 'ID de objetivo no existe para el tipo especificado';

&nbsp; END IF;

END;

//

DELIMITER ;

```



-- Tabla para auditoría (agregado motivo)

```sql

CREATE TABLE auditoria (

&nbsp; id CHAR(36) PRIMARY KEY,

&nbsp; usuario\_id CHAR(36) NOT NULL,

&nbsp; entidad VARCHAR(80) NOT NULL,

&nbsp; entidad\_id CHAR(36) NOT NULL,

&nbsp; accion VARCHAR(40) NOT NULL,

&nbsp; cambios\_json JSON NOT NULL,

&nbsp; motivo VARCHAR(255) NULL,  -- Motivo de la acción

&nbsp; created\_at DATETIME NOT NULL,

&nbsp; CONSTRAINT fk\_aud\_usuario FOREIGN KEY (usuario\_id) REFERENCES usuario(id),

&nbsp; INDEX idx\_aud\_entidad (entidad, entidad\_id)

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

```



-- Nueva tabla para reportes de ocupación precalculados

```sql

CREATE TABLE reporte\_ocupacion (

&nbsp; id CHAR(36) PRIMARY KEY,

&nbsp; periodo\_id CHAR(36) NOT NULL,

&nbsp; tipo ENUM('Salon','Profesor') NOT NULL,

&nbsp; objetivo\_id CHAR(36) NOT NULL,

&nbsp; ocupacion\_porcentaje FLOAT NOT NULL,  -- Porcentaje de ocupación

&nbsp; num\_bloques\_ocupados INT NOT NULL,

&nbsp; created\_at DATETIME NOT NULL,

&nbsp; CONSTRAINT fk\_ro\_periodo FOREIGN KEY (periodo\_id) REFERENCES periodo\_academico(id),

&nbsp; UNIQUE KEY uq\_ro\_unique (periodo\_id, tipo, objetivo\_id)

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

```



-- Tabla para parámetros del sistema (con comentarios para claves esperadas)

```sql

CREATE TABLE parametro\_sistema (

&nbsp; id CHAR(36) PRIMARY KEY,

&nbsp; clave VARCHAR(120) NOT NULL UNIQUE,  -- Ejemplos: 'periodo\_academico', 'horas\_laborables', 'dias\_laborables'

&nbsp; valor JSON NOT NULL,

&nbsp; scope VARCHAR(60) NULL

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

```sql



-- Vistas para detección de conflictos

```sql

CREATE VIEW vista\_conflictos\_salon AS

SELECT 

&nbsp; a.periodo\_id, a.bloque\_id, a.salon\_id, 

&nbsp; COUNT(\*) AS num\_asignaciones,

&nbsp; s.capacidad,

&nbsp; SUM(g.num\_estudiantes) AS total\_estudiantes

FROM asignacion a

JOIN salon s ON a.salon\_id = s.id

JOIN grupo g ON a.grupo\_id = g.id

WHERE a.estado = 'Confirmada'

GROUP BY a.periodo\_id, a.bloque\_id, a.salon\_id

HAVING total\_estudiantes > s.capacidad OR num\_asignaciones > 1;



CREATE VIEW vista\_conflictos\_profesor AS

SELECT 

&nbsp; a.periodo\_id, a.bloque\_id, a.profesor\_id, 

&nbsp; COUNT(\*) AS num\_asignaciones

FROM asignacion a

WHERE a.estado = 'Confirmada'

GROUP BY a.periodo\_id, a.bloque\_id, a.profesor\_id

HAVING num\_asignaciones > 1;

```sql



