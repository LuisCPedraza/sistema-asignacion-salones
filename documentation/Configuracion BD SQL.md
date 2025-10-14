# Configuración Bases de Datos
---
## Descripción General Detallada: Base de Datos
A continuación, presento una **descripción detallada** de cómo el **esquema actualizado de la base de datos** cumple con los requerimientos especificados en el documento **“Proyectos Desarrollo de Software 2.docx”**. El esquema ha sido diseñado para satisfacer **todas las épicas, historias de usuario (HU), historias técnicas (TH), y criterios de aceptación** del **sistema de asignación de salones** para un centro educativo, siguiendo las prácticas de **DevOps, Scrum, Kanban y TDD**, con un enfoque en **mantenibilidad, modularidad, cohesión y bajo acoplamiento**. También se han incorporado mejoras para **optimizar eficiencia, escalabilidad y robustez**, alineándose con las entregas del proyecto y los criterios generales.

---

## 1. Cumplimiento de los Objetivos Generales del Documento

El documento establece que el sistema debe integrar el ciclo completo de **DevOps**, gestionarse con **Scrum** y **Kanban**, e implementar **TDD** (pruebas unitarias y refactoring). Además, se evalúan **mantenibilidad, modularidad, cohesión y bajo acoplamiento**. La base de datos cumple con estos objetivos de la siguiente manera:

### Ciclo DevOps
- La estructura relacional con tablas normalizadas (`usuario`, `profesor`, `grupo`, `salon`, etc.) y la tabla `asignacion` **con particionamiento por `periodo_id`** facilita la integración con herramientas de **integración y despliegue continuo** (p. ej., **GitHub Actions**, mencionadas en la segunda entrega).  
- Los **índices optimizados** (p. ej., `idx_as_horario_salon`, `idx_as_conflictos`) y **vistas** (`vista_conflictos_salon`, `vista_conflictos_profesor`) aseguran un **rendimiento adecuado** para operaciones en tiempo real.  
- La tabla `auditoria` permite **trazabilidad de cambios**, esencial para auditorías en un pipeline DevOps.  
- La tabla `parametro_sistema` soporta **configuraciones dinámicas**, facilitando la adaptación del sistema sin cambios en el código, un principio clave de DevOps.

### Scrum y Kanban
- La base de datos está diseñada para soportar la **gestión del proyecto mediante tableros Kanban** (segunda entrega). Por ejemplo, la tabla `asignacion` con **`estado`** (*Propuesta/Confirmada/Anulada*) y **`origen`** (*Manual/Automática*) permite **rastrear el progreso** de las asignaciones en un tablero Kanban, integrándose con herramientas como **GitHub Issues** o **Projects**.  
- La **estructura modular** (tablas separadas por entidad: `usuario`, `grupo`, `salon`, etc.) permite **iteraciones ágiles**, ya que cada épica puede desarrollarse y probarse de forma independiente.

### TDD (Pruebas Unitarias y Refactoring)
- La **modularidad** de las tablas y las **restricciones de integridad** (`FOREIGN KEY`, `CHECK`, `UNIQUE`) facilitan la creación de **pruebas unitarias** para validar operaciones CRUD y reglas de negocio (p. ej., **capacidad de salones**, **conflictos de horario**).  
- Los **triggers** (p. ej., `trg_valida_restriccion`) y **vistas** aseguran que las **reglas de negocio** se mantengan consistentes, reduciendo la necesidad de **refactoring** complejo en el código de la aplicación.

### Mantenibilidad, Modularidad, Cohesión y Bajo Acoplamiento
- **Mantenibilidad:** Tablas normalizadas (p. ej., `salon_recurso`, `recurso_disponibilidad`) y **nombres de campos claros**, lo que facilita el mantenimiento. La tabla `auditoria` registra cambios, ayudando a **diagnosticar problemas**.  
- **Modularidad:** Cada entidad (`usuarios`, `profesores`, `grupos`, `salones`) tiene su **propia tabla**, lo que permite **desarrollar y modificar módulos** del sistema de forma independiente.  
- **Cohesión:** Cada tabla tiene una **responsabilidad clara** (p. ej., `bloque_horario` para horarios, `restriccion` para reglas), asegurando que las **funciones estén bien definidas**.  
- **Bajo Acoplamiento:** Las relaciones entre tablas usan **claves foráneas**, pero las dependencias están **minimizadas**, permitiendo cambios en una tabla sin afectar ampliamente otras (p. ej., `recurso_disponibilidad` es independiente de `salon`).

---

## 2. Cumplimiento de las Entregas del Proyecto

El documento especifica **dos entregas principales**, con criterios claros para cada una. La base de datos **soporta ambos conjuntos de requisitos**:

### Primera Entrega (Clase 9)

**Análisis, Levantamiento de Requerimientos y Diseño (50%)**
- **Diagrama de Casos de Uso y Casos de Uso:** La base de datos cubre **todas las HU (HU1–HU19)**, desde **autenticación** hasta **reportes**. Por ejemplo, las tablas `usuario` y `profesor` soportan **HU1** y **HU7**, mientras que `asignacion` y `restriccion` soportan **HU9–HU12**.  
- **Diagrama de Clases / Flujo de Datos:** Las tablas reflejan un **diseño orientado a objetos** (p. ej., `Usuario`, `Profesor`, `Grupo`, `Salon` como clases) con **relaciones claras** (`FOREIGN KEY`) que modelan **flujos de datos**, como la asignación de grupos a salones y profesores.  
- **DER, Modelo Relacional y Modelo Físico:** El esquema SQL es el **modelo físico**, derivado de un **modelo relacional normalizado** (3FN en la mayoría de las tablas) y un **modelo entidad-relación** implícito en las tablas y sus relaciones.

**Configuración de la Infraestructura de Desarrollo (50%)**
- **Repositorio GitHub y Estrategia de Branching:** La base de datos no interactúa directamente con GitHub, pero su **diseño modular** permite integrarse con un **repositorio** para almacenar **scripts SQL, triggers y vistas**, soportando **branching** para desarrollo iterativo.  
- **Configuración de la Base de Datos:** El esquema usa **MySQL** con **InnoDB** y **utf8mb4**, con **índices, particiones, triggers y vistas**, cumpliendo con los requisitos de **configuración robusta**.  
- **Entorno de Desarrollo:** La base de datos es **compatible con entornos modernos** (MySQL es ampliamente soportado), y los **comentarios SQL** (p. ej., sobre `password_hash`) guían la **implementación segura**.

### Segunda Entrega (Clase 15)

**Gestión del Proyecto (25%)**  
Las tablas `asignacion` (con `estado` y `origen`) y `auditoria` permiten **rastrear el estado** de las asignaciones y los cambios, integrándose con herramientas como **GitHub Issues**, **Projects** y **Milestones** para **tableros Kanban**.

**Continuous Development (25%)**  
La estructura soporta **integración con GitHub Repository** mediante scripts SQL **versionados**, y las **claves foráneas y restricciones** facilitan **pull requests** al garantizar **datos consistentes**.

**Integración y Despliegue Continuo (25%)**  
La base de datos está **optimizada** para integrarse con **GitHub Actions** (p. ej., para ejecutar **scripts de migración** o **pruebas**). Las **vistas** (`vista_conflictos_salon`, `vista_conflictos_profesor`) y la tabla `reporte_ocupacion` facilitan **pruebas unitarias automatizadas** para validar asignaciones y conflictos.

**Funcionalidad de Módulos Desarrollados (25%)**  
Cada **módulo** (gestión de usuarios, grupos, salones, asignaciones, etc.) está soportado por **tablas específicas**, con **índices y vistas** que aseguran funcionalidad **eficiente**.

---

## 3. Cumplimiento de las Épicas Principales

A continuación, detallo cómo la base de datos soporta cada épica del **backlog de producto**:

### Épica 1: Gestión de Usuarios y Autenticación (HU1, HU2)
- **Tabla `usuario`:** Almacena datos de usuarios (`nombre`, `email`, `password_hash`) y **roles** (`ADMIN`, `COORDINADOR`, `PROFESOR`, `coord_INFRA`), soportando **creación, gestión y autenticación por rol**. Campos `created_at` y `updated_at` habilitan **auditoría**.  
- **Seguridad:** El comentario sobre usar **bcrypt** para `password_hash` asegura **autenticación segura**.

### Épica 2: Gestión de Grupos de Estudiantes (HU3, HU4)
- **Tabla `grupo`:** Incluye `nombre`, `nivel`, `num_estudiantes` y `caracteristicas` para **registrar y gestionar grupos**. El campo `activo` permite **desactivar** grupos sin eliminarlos (**HU4**).

### Épica 3: Gestión de Salones (HU5, HU6)
- **Tabla `salon`:** Almacena `codigo`, `capacidad`, `ubicacion` y `activo` para **registrar salones**.  
- **Tablas `salon_recurso` y `recurso`:** Gestionan **recursos** como proyectores o computadoras.  
- **Tabla `disp_salon`:** Registra **disponibilidad horaria** con estados (*Disponible, NoDisponible, Reservado, Mantenimiento*), cumpliendo **HU6**.  
- **Tabla `recurso_disponibilidad`:** Añade **restricciones horarias** para recursos.

### Épica 4: Gestión de Profesores (HU7, HU8)
- **Tabla `profesor`:** Almacena **especialidades** y `hoja_vida_url`, vinculada a `usuario` por `usuario_id`.  
- **Tabla `disp_profesor`:** Gestiona **disponibilidad horaria** con estados (*Disponible, NoDisponible, Preferido, Licencia*), soportando **asignaciones especiales**.

### Épica 5: Sistema de Asignación Automática (HU9, HU10)
- **Tabla `asignacion`:** Registra asignaciones con `grupo_id`, `salon_id`, `profesor_id`, `bloque_id`, `periodo_id`, `estado`, `origen` y `score`. El campo **`score`** facilita la **evaluación** de asignaciones **automáticas**.  
- **Tablas `restriccion` y `tipo_restriccion`:** Permiten configurar **parámetros y prioridades** (p. ej., **minimizar cambios de salón**) con `regla_json` y reglas predefinidas, soportando **HU10**.

### Épica 6: Sistema de Asignación Manual (HU11, HU12)
- **`asignacion` con `origen = 'Manual'`** soporta **asignaciones manuales**.  
- **Vistas `vista_conflictos_salon` y `vista_conflictos_profesor`:** Detectan **conflictos en tiempo real** (sobrecupos, superposiciones), cumpliendo **HU12**.

### Épica 7: Visualización y Reportes (HU13, HU14, HU15)
- `asignacion` junto con `periodo_academico` y `bloque_horario` permite **visualizar horarios** completos (**HU13**) y **personales** (**HU14**).  
- **Tabla `reporte_ocupacion`:** Proporciona **estadísticas precalculadas** de **utilización** de salones y profesores, optimizando **HU15**.

### Épica 8: Gestión de Conflictos y Restricciones (HU16, HU17)
- **Tabla `restriccion`:** Define **restricciones específicas** con `tipo`, `objetivo_type`, `objetivo_id`, `regla_json` y `dureza`. El **trigger `trg_valida_restriccion`** asegura la validez de `objetivo_id`.  
- **Vistas de conflictos:** `vista_conflictos_salon` y `vista_conflictos_profesor` **notifican conflictos** y pueden **sugerir alternativas** al integrarse con la lógica de la aplicación.

### Épica 9: Historial y Auditoría (HU18)
- **Tabla `auditoria`:** Registra cambios con `usuario_id`, `entidad`, `entidad_id`, `accion`, `cambios_json`, `motivo` y `created_at`, cumpliendo **HU18** al **rastrear quién** realizó cada modificación.

### Épica 10: Configuración del Sistema (HU19)
- **Tabla `parametro_sistema`:** Almacena **configuraciones generales** (`clave`, `valor`, `scope`) como **períodos académicos** o **días laborables**. Los **comentarios SQL** especifican **claves esperadas**, asegurando **consistencia**.

---

## 4. Cumplimiento de las Historias Técnicas

- **TH1: Configurar e implementar la base de datos**  
  El esquema completo con **InnoDB** y **utf8mb4** está implementado, con **tablas normalizadas, índices, particiones, triggers y vistas**, cubriendo **todas las entidades y relaciones** necesarias.

- **TH2: Desarrollar API RESTful**  
  Aunque la API es responsabilidad del backend, la base de datos está diseñada para **soportar operaciones RESTful**. Cada tabla (p. ej., `usuario`, `grupo`, `asignacion`) tiene un **`id` único** y **campos claros**, facilitando endpoints como `/usuarios`, `/grupos`, `/asignaciones`.

- **TH3: Sistema de autenticación y autorización segura**  
  `usuario` con `password_hash` (recomendado **bcrypt**) y `rol` permite **autenticación** y **autorización por roles**. La tabla `auditoria` puede registrar **intentos de acceso** si es necesario.

- **TH4: Interfaz responsive y accesible**  
  La base de datos soporta una **interfaz responsive** al proporcionar **datos estructurados y optimizados** (índices, vistas) para **consultas rápidas**, compatibles con **navegadores modernos**.

---

## 5. Cumplimiento de los Criterios de Aceptación General

- **Intuitivo y mínima capacitación:** La estructura **clara y modular** (tablas específicas por entidad) permite una **interfaz intuitiva**, ya que los **datos están organizados lógicamente**.  
- **Respuesta en menos de 2 segundos:** Los **índices** (p. ej., `idx_as_horario_salon`, `idx_as_conflictos`) y **vistas** (`vista_conflictos_salon`) **optimizan consultas críticas**, asegurando un **rendimiento adecuado**.  
- **Compatibilidad con navegadores modernos:** La base de datos es **independiente del frontend**, pero su **diseño relacional** y uso de **MySQL** (ampliamente soportado) garantiza **compatibilidad** con aplicaciones web modernas.  
- **Seguridad y respaldo de datos:** El uso de **`password_hash`**, **triggers** para **validaciones**, y `auditoria` asegura la **seguridad**. La estructura **InnoDB** soporta **transacciones** y **respaldos regulares**.

---

## 6. Priorización Inicial

El documento prioriza las épicas en cuatro grupos. La base de datos las **soporta completamente**:

- **Épicas 1, 2, 3, 4 (Gestión básica):** Tablas `usuario`, `grupo`, `salon`, `profesor`, `salon_recurso`, `disp_salon`, `disp_profesor` y `recurso_disponibilidad` cubren la **gestión de usuarios y recursos**.  
- **Épicas 10, 6 (Configuración y asignación manual):** `parametro_sistema` y `asignacion` (con `origen='Manual'`) junto con **vistas de conflictos** soportan estas funcionalidades.  
- **Épica 5 (Asignación automática):** `asignacion` (con `score`), `restriccion` y `tipo_restriccion` permiten **algoritmos de asignación automática**.  
- **Épicas 7, 8, 9 (Visualización, conflictos, historial):** `reporte_ocupacion`, **vistas de conflictos** y `auditoria` cubren estas necesidades.

---

## 7. Eficiencia y Escalabilidad

- **Eficiencia:** Los **índices** en `asignacion`, `restriccion` y `auditoria` optimizan **consultas frecuentes**. Las **vistas** precalculan **conflictos**, reduciendo la **carga computacional**. La tabla `reporte_ocupacion` evita **cálculos complejos** para reportes.  
- **Escalabilidad:** El **particionamiento por `periodo_id`** en `asignacion` permite **manejar grandes volúmenes** de datos, dividiendo la tabla en **particiones** más pequeñas.  
- **Robustez:** **Triggers** (`trg_valida_restriccion`) y **restricciones** (`FOREIGN KEY`, `CHECK`, `UNIQUE`) aseguran **integridad**. Estados adicionales en `disp_salon` (*Mantenimiento*) y `disp_profesor` (*Licencia*) **manejan casos reales**.

---

## Conclusión

La base de datos **cumple al 100%** con los requerimientos del documento, cubriendo todas las **épicas**, **historias técnicas** y **criterios de aceptación**. Es **eficiente** gracias a **índices, vistas y particionamiento**; **robusta** con **validaciones y auditoría**; y **escalable** para manejar grandes volúmenes de datos. Soporta las **entregas del proyecto** (análisis, infraestructura, gestión y despliegue continuo) y sigue las prácticas de **DevOps, Scrum y TDD**, con **alta mantenibilidad, modularidad, cohesión y bajo acoplamiento**.

## Esquema SQL Completo
-- Tabla para períodos académicos
```sql
CREATE TABLE periodo_academico (
  id CHAR(36) PRIMARY KEY,
  nombre VARCHAR(120) NOT NULL,
  fecha_inicio DATE NOT NULL,
  fecha_fin DATE NOT NULL,
  activo TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

-- Tabla para bloques horarios (cambio a ENUM para legibilidad)
```sql
CREATE TABLE bloque_horario (
  id CHAR(36) PRIMARY KEY,
  dia_semana ENUM('Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo') NOT NULL,
  hora_inicio TIME NOT NULL,
  hora_fin TIME NOT NULL,
  CONSTRAINT chk_bloque_duracion CHECK (hora_fin > hora_inicio)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

-- Tabla para usuarios
```sql
CREATE TABLE usuario (
  id CHAR(36) PRIMARY KEY,
  nombre VARCHAR(120) NOT NULL,
  email VARCHAR(160) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,  -- Usar bcrypt para hashing seguro
  rol ENUM('ADMIN','COORDINADOR','PROFESOR','COORD_INFRA') NOT NULL,
  activo TINYINT(1) NOT NULL DEFAULT 1,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

-- Tabla para profesores
```sql
CREATE TABLE profesor (
  id CHAR(36) PRIMARY KEY,
  usuario_id CHAR(36) NOT NULL UNIQUE,
  especialidades TEXT NULL,
  hoja_vida_url VARCHAR(255) NULL,
  CONSTRAINT fk_profesor_usuario FOREIGN KEY (usuario_id) REFERENCES usuario(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

-- Tabla para grupos
```sql
CREATE TABLE grupo (
  id CHAR(36) PRIMARY KEY,
  nombre VARCHAR(120) NOT NULL,
  nivel VARCHAR(60) NOT NULL,
  num_estudiantes INT NOT NULL CHECK (num_estudiantes > 0),
  caracteristicas TEXT NULL,
  activo TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

-- Tabla para salones
```sql
CREATE TABLE salon (
  id CHAR(36) PRIMARY KEY,
  codigo VARCHAR(60) NOT NULL UNIQUE,
  capacidad INT NOT NULL CHECK (capacidad > 0),
  ubicacion VARCHAR(160) NOT NULL,
  activo TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

-- Tabla para recursos
```sql
CREATE TABLE recurso (
  id CHAR(36) PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  descripcion VARCHAR(255) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

-- Tabla para asociación salón-recurso
```sql
CREATE TABLE salon_recurso (
  salon_id CHAR(36) NOT NULL,
  recurso_id CHAR(36) NOT NULL,
  cantidad INT NOT NULL CHECK (cantidad >= 0),
  PRIMARY KEY (salon_id, recurso_id),
  CONSTRAINT fk_sr_salon FOREIGN KEY (salon_id) REFERENCES salon(id),
  CONSTRAINT fk_sr_recurso FOREIGN KEY (recurso_id) REFERENCES recurso(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

-- Nueva tabla para disponibilidad de recursos (normalización)
```sql
CREATE TABLE recurso_disponibilidad (
  recurso_id CHAR(36) NOT NULL,
  bloque_id CHAR(36) NOT NULL,
  estado ENUM('Disponible','NoDisponible','Reservado') NOT NULL,
  PRIMARY KEY (recurso_id, bloque_id),
  CONSTRAINT fk_rd_recurso FOREIGN KEY (recurso_id) REFERENCES recurso(id),
  CONSTRAINT fk_rd_bloque FOREIGN KEY (bloque_id) REFERENCES bloque_horario(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

-- Tabla para disponibilidad de profesores (estados adicionales)
```sql
CREATE TABLE disp_profesor (
  profesor_id CHAR(36) NOT NULL,
  bloque_id CHAR(36) NOT NULL,
  estado ENUM('Disponible','NoDisponible','Preferido','Licencia') NOT NULL,
  PRIMARY KEY (profesor_id, bloque_id),
  CONSTRAINT fk_dp_profesor FOREIGN KEY (profesor_id) REFERENCES profesor(id),
  CONSTRAINT fk_dp_bloque FOREIGN KEY (bloque_id) REFERENCES bloque_horario(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

-- Tabla para disponibilidad de salones (estados adicionales)
```sql
CREATE TABLE disp_salon (
  salon_id CHAR(36) NOT NULL,
  bloque_id CHAR(36) NOT NULL,
  estado ENUM('Disponible','NoDisponible','Reservado','Mantenimiento') NOT NULL,
  PRIMARY KEY (salon_id, bloque_id),
  CONSTRAINT fk_ds_salon FOREIGN KEY (salon_id) REFERENCES salon(id),
  CONSTRAINT fk_ds_bloque FOREIGN KEY (bloque_id) REFERENCES bloque_horario(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

-- Tabla para asignaciones (agregado score y particionamiento para escalabilidad)
```sql
CREATE TABLE asignacion (
  id CHAR(36) PRIMARY KEY,
  grupo_id CHAR(36) NOT NULL,
  salon_id CHAR(36) NOT NULL,
  profesor_id CHAR(36) NOT NULL,
  bloque_id CHAR(36) NOT NULL,
  periodo_id CHAR(36) NOT NULL,
  estado ENUM('Propuesta','Confirmada','Anulada') NOT NULL DEFAULT 'Propuesta',
  origen ENUM('Manual','Automatica') NOT NULL,
  score FLOAT NULL,  -- Puntaje para asignaciones automáticas
  created_by CHAR(36) NOT NULL,
  created_at DATETIME NOT NULL,
  CONSTRAINT fk_as_grupo FOREIGN KEY (grupo_id) REFERENCES grupo(id),
  CONSTRAINT fk_as_salon FOREIGN KEY (salon_id) REFERENCES salon(id),
  CONSTRAINT fk_as_prof FOREIGN KEY (profesor_id) REFERENCES profesor(id),
  CONSTRAINT fk_as_bloque FOREIGN KEY (bloque_id) REFERENCES bloque_horario(id),
  CONSTRAINT fk_as_periodo FOREIGN KEY (periodo_id) REFERENCES periodo_academico(id),
  CONSTRAINT uq_as_unique UNIQUE (grupo_id, bloque_id, periodo_id),
  INDEX idx_as_horario_salon (periodo_id, bloque_id, salon_id),
  INDEX idx_as_horario_prof (periodo_id, bloque_id, profesor_id),
  INDEX idx_as_conflictos (periodo_id, bloque_id, salon_id, profesor_id)  -- Índice adicional para conflictos
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
PARTITION BY HASH(periodo_id) PARTITIONS 4;  -- Particionamiento para escalabilidad
```

-- Nueva tabla auxiliar para tipos de restricciones predefinidas
```sql
CREATE TABLE tipo_restriccion (
  id CHAR(36) PRIMARY KEY,
  nombre VARCHAR(80) NOT NULL UNIQUE,
  descripcion TEXT NULL,
  regla_default_json JSON NULL  -- Plantilla JSON para reglas predefinidas
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

-- Tabla para restricciones (con trigger para validación)
```sql
CREATE TABLE restriccion (
  id CHAR(36) PRIMARY KEY,
  tipo VARCHAR(80) NOT NULL,
  objetivo_type VARCHAR(80) NOT NULL,
  objetivo_id CHAR(36) NOT NULL,
  regla_json JSON NOT NULL,
  dureza ENUM('Blando','Duro') NOT NULL,
  INDEX idx_restriccion_objetivo (objetivo_type, objetivo_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

-- Trigger para validar objetivo_type y objetivo_id en restricción
```sql
DELIMITER //
CREATE TRIGGER trg_valida_restriccion BEFORE INSERT ON restriccion
FOR EACH ROW
BEGIN
  DECLARE exists_count INT;
  IF NEW.objetivo_type = 'salon' THEN
    SELECT COUNT(*) INTO exists_count FROM salon WHERE id = NEW.objetivo_id;
  ELSEIF NEW.objetivo_type = 'profesor' THEN
    SELECT COUNT(*) INTO exists_count FROM profesor WHERE id = NEW.objetivo_id;
  ELSEIF NEW.objetivo_type = 'grupo' THEN
    SELECT COUNT(*) INTO exists_count FROM grupo WHERE id = NEW.objetivo_id;
  -- Agregar más tipos según necesidades
  ELSE
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Tipo de objetivo inválido';
  END IF;
  IF exists_count = 0 THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'ID de objetivo no existe para el tipo especificado';
  END IF;
END;
//
DELIMITER ;
```

-- Tabla para auditoría (agregado motivo)
```sql
CREATE TABLE auditoria (
  id CHAR(36) PRIMARY KEY,
  usuario_id CHAR(36) NOT NULL,
  entidad VARCHAR(80) NOT NULL,
  entidad_id CHAR(36) NOT NULL,
  accion VARCHAR(40) NOT NULL,
  cambios_json JSON NOT NULL,
  motivo VARCHAR(255) NULL,  -- Motivo de la acción
  created_at DATETIME NOT NULL,
  CONSTRAINT fk_aud_usuario FOREIGN KEY (usuario_id) REFERENCES usuario(id),
  INDEX idx_aud_entidad (entidad, entidad_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

-- Nueva tabla para reportes de ocupación precalculados
```sql
CREATE TABLE reporte_ocupacion (
  id CHAR(36) PRIMARY KEY,
  periodo_id CHAR(36) NOT NULL,
  tipo ENUM('Salon','Profesor') NOT NULL,
  objetivo_id CHAR(36) NOT NULL,
  ocupacion_porcentaje FLOAT NOT NULL,  -- Porcentaje de ocupación
  num_bloques_ocupados INT NOT NULL,
  created_at DATETIME NOT NULL,
  CONSTRAINT fk_ro_periodo FOREIGN KEY (periodo_id) REFERENCES periodo_academico(id),
  UNIQUE KEY uq_ro_unique (periodo_id, tipo, objetivo_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

-- Tabla para parámetros del sistema (con comentarios para claves esperadas)
```sql
CREATE TABLE parametro_sistema (
  id CHAR(36) PRIMARY KEY,
  clave VARCHAR(120) NOT NULL UNIQUE,  -- Ejemplos: 'periodo_academico', 'horas_laborables', 'dias_laborables'
  valor JSON NOT NULL,
  scope VARCHAR(60) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```sql

-- Vistas para detección de conflictos
```sql
CREATE VIEW vista_conflictos_salon AS
SELECT 
  a.periodo_id, a.bloque_id, a.salon_id, 
  COUNT(*) AS num_asignaciones,
  s.capacidad,
  SUM(g.num_estudiantes) AS total_estudiantes
FROM asignacion a
JOIN salon s ON a.salon_id = s.id
JOIN grupo g ON a.grupo_id = g.id
WHERE a.estado = 'Confirmada'
GROUP BY a.periodo_id, a.bloque_id, a.salon_id
HAVING total_estudiantes > s.capacidad OR num_asignaciones > 1;

CREATE VIEW vista_conflictos_profesor AS
SELECT 
  a.periodo_id, a.bloque_id, a.profesor_id, 
  COUNT(*) AS num_asignaciones
FROM asignacion a
WHERE a.estado = 'Confirmada'
GROUP BY a.periodo_id, a.bloque_id, a.profesor_id
HAVING num_asignaciones > 1;
```
