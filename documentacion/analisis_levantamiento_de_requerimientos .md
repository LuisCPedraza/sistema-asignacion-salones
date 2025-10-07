# Análisis, Levantamiento de Requerimientos y Diseño del Sistema de Asignación de Salones

## 1. Introducción

El **Sistema de Asignación de Salones** es una aplicación web diseñada para gestionar recursos educativos (grupos, salones, profesores) y programar asignaciones semestrales de manera automática o manual en un centro educativo. Este documento presenta el análisis, levantamiento de requerimientos y diseño del sistema, alineado con el documento *"Proyectos Desarrollo de Software 2.docx"*, que establece un enfoque basado en **DevOps**, **Scrum con Kanban**, **TDD (Desarrollo Dirigido por Pruebas)**, y prioriza la **mantenibilidad**, **modularidad**, **cohesión** y **bajo acoplamiento**.

El sistema se basa en:
- Una **base de datos relacional** (MySQL, motor InnoDB, codificación utf8mb4) optimizada con índices, particiones, triggers y vistas, confirmada al 100%.
- **Diagramas** generados: Entidad-Relación, Modelo Relacional, Modelo Físico, Clases, Flujo de Datos, Casos de Uso y Secuencias.
- **Backlog de producto** con épicas (HU1-HU19), historias técnicas (TH1-TH4) y criterios de aceptación.

El sistema soporta roles diferenciados (**Administrador**, **Coordinador**, **Profesor**, **Coordinador de Infraestructura**) y garantiza **rendimiento** (< 2 segundos por acción), **seguridad** (autenticación, auditoría), **compatibilidad** (navegadores modernos) y **escalabilidad**.

## 2. Levantamiento de Requerimientos

El levantamiento de requerimientos se deriva del backlog de producto del documento, organizado en **requerimientos funcionales** (basados en historias de usuario - HU) y **no funcionales** (criterios de aceptación y historias técnicas - TH).

### 2.1 Requerimientos Funcionales

Los requerimientos funcionales se agrupan en **épicas** según el documento:

#### Épica 1: Gestión de Usuarios y Autenticación
- **HU1**: Crear, editar, desactivar y visualizar cuentas de usuarios con roles (Administrador, Coordinador, Profesor, Coordinador de Infraestructura).
- **HU2**: Iniciar sesión con credenciales (email, contraseña) para acceder a funcionalidades según el rol.

#### Épica 2: Gestión de Grupos de Estudiantes
- **HU3**: Registrar grupos con nombre, nivel, número de estudiantes y características específicas.
- **HU4**: Editar, desactivar o visualizar grupos existentes.

#### Épica 3: Gestión de Salones
- **HU5**: Registrar salones con código, capacidad, ubicación y recursos asociados.
- **HU6**: Gestionar disponibilidad horaria y restricciones específicas de salones.

#### Épica 4: Gestión de Profesores
- **HU7**: Registrar profesores con información personal, especialidades y enlace a hoja de vida.
- **HU8**: Gestionar disponibilidad horaria y asignaciones especiales de profesores.

#### Épica 5: Sistema de Asignación Automática
- **HU9**: Ejecutar un algoritmo de asignación automática considerando disponibilidades, capacidades y preferencias.
- **HU10**: Configurar parámetros y prioridades para optimizar la asignación automática.

#### Épica 6: Sistema de Asignación Manual
- **HU11**: Realizar asignaciones manuales mediante una interfaz visual (arrastrar y soltar).
- **HU12**: Visualizar conflictos (e.g., sobrecupos, superposiciones) en tiempo real durante la asignación manual.

#### Épica 7: Visualización y Reportes
- **HU13**: Visualizar el horario semestral completo.
- **HU14**: Visualizar horarios personales (para profesores).
- **HU15**: Generar reportes de utilización de recursos y estadísticas (e.g., ocupación de salones).

#### Épica 8: Gestión de Conflictos y Restricciones
- **HU16**: Notificar conflictos en asignaciones y sugerir alternativas.
- **HU17**: Establecer restricciones específicas para grupos, salones o profesores.

#### Épica 9: Historial y Auditoría
- **HU18**: Visualizar el historial de cambios y los usuarios responsables.

#### Épica 10: Configuración del Sistema
- **HU19**: Configurar parámetros generales del sistema (e.g., períodos académicos, horarios laborables).

### 2.2 Requerimientos No Funcionales

Los requerimientos no funcionales aseguran calidad, rendimiento y mantenibilidad:

- **Rendimiento**: Todas las operaciones (consultas, asignaciones) deben responder en menos de 2 segundos, soportado por índices y particiones en la base de datos.
- **Seguridad**: Autenticación segura con hash de contraseñas (bcrypt), auditoría de cambios, y respaldos regulares.
- **Usabilidad**: Interfaz intuitiva con mínima capacitación requerida; diseño responsive y accesible.
- **Compatibilidad**: Soporte para navegadores modernos (Chrome, Firefox, Edge).
- **Mantenibilidad**: Código modular, cohesivo y de bajo acoplamiento; uso de TDD para pruebas unitarias y refactoring continuo.
- **Escalabilidad**: Base de datos diseñada para manejar grandes volúmenes de datos mediante particiones y optimización de consultas.
- **Tecnologías**:
  - Base de datos: MySQL (InnoDB, utf8mb4).
  - Backend: API RESTful para integración con frontend.
  - DevOps: Repositorio GitHub con CI/CD (GitHub Actions), despliegue en Render.
  - Frontend: Interfaz web con tecnologías modernas (HTML, CSS, JavaScript).

### 2.3 Priorización Inicial

Basado en el documento, las épicas se priorizan en el siguiente orden para el desarrollo iterativo:
1. Épicas 1, 2, 3 y 4: Gestión básica de usuarios, grupos, salones y profesores (base del sistema).
2. Épicas 10 y 6: Configuración del sistema y asignación manual (funcionalidades críticas).
3. Épica 5: Asignación automática (optimización del proceso).
4. Épicas 7, 8 y 9: Visualización, reportes, gestión de conflictos y auditoría (funcionalidades avanzadas).

## 3. Análisis

El análisis evalúa los requerimientos para identificar actores, flujos de trabajo, riesgos, dependencias y restricciones, alineándose con el ciclo de vida de **DevOps** y la metodología **Scrum**.

### 3.1 Actores y Roles

Los actores del sistema y sus responsabilidades son:
- **Administrador**: Acceso total; gestiona usuarios, auditoría y parámetros del sistema (HU1, HU2, HU18, HU19).
- **Coordinador**: Configura recursos (grupos, salones, profesores), realiza asignaciones manuales/automáticas, gestiona restricciones y solicita reportes (HU3-HU12, HU15-HU17).
- **Profesor**: Consulta horarios personales y registra disponibilidad propia (HU7, HU8, HU14).
- **Coordinador de Infraestructura**: Subrol de Coordinador, enfocado en gestión de salones y recursos físicos (HU5, HU6).

### 3.2 Análisis de Requerimientos Funcionales

- **Dependencias**:
  - Las asignaciones (HU9-HU12) requieren que los recursos (grupos, salones, profesores) estén configurados (HU3-HU8).
  - Los reportes (HU15) dependen de datos generados por asignaciones (HU9-HU12) y vistas para conflictos (HU16-HU17).
  - La auditoría (HU18) registra todas las operaciones CRUD.
- **Riesgos**:
  - **Rendimiento**: Consultas en tiempo real para conflictos (HU12, HU16) pueden ser lentas sin índices optimizados.
  - **Complejidad**: El algoritmo de asignación automática (HU9) puede ser computacionalmente costoso; mitigar con cálculo de `score` y restricciones blandas/duras.
  - **Integridad**: Errores en restricciones (HU17) pueden generar conflictos; mitigar con trigger `trg_valida_restriccion`.
- **Flujos de Datos**:
  - CRUD en tablas principales (`usuario`, `asignacion`, `restriccion`, etc.).
  - Validaciones automáticas mediante checks (`num_estudiantes > 0`) y triggers.
  - Consultas optimizadas con vistas (`vista_conflictos_salon`, `vista_conflictos_profesor`).

### 3.3 Análisis de Requerimientos No Funcionales

- **Rendimiento**: Índices en `asignacion` (e.g., `idx_as_conflictos`) y partición por `periodo_id` optimizan consultas. Vistas precalculadas reducen tiempo de respuesta.
- **Seguridad**: Uso de `password_hash` (bcrypt), auditoría automática en `auditoria`, y trigger para validar restricciones (`trg_valida_restriccion`).
- **Usabilidad**: Interfaz drag-and-drop para asignaciones manuales; diseño responsive para múltiples dispositivos.
- **Mantenibilidad**: Base de datos en tercera forma normal (3FN); módulos desacoplados para backend y frontend.
- **Escalabilidad**: Particiones en `asignacion` soportan grandes volúmenes de datos.
- **Compatibilidad**: API RESTful asegura integración con navegadores modernos.

### 3.4 Análisis de Riesgos y Asunciones

- **Riesgos**:
  - Alto volumen de asignaciones puede ralentizar consultas; mitigar con índices (`idx_as_horario_salon`) y particiones.
  - Conflictos no detectados en tiempo real; mitigar con vistas optimizadas.
  - Errores en la configuración de parámetros; validar con `UNIQUE` en `parametro_sistema.clave`.
- **Asunciones**:
  - Usuarios son adultos con conocimientos básicos de tecnología.
  - Operaciones realizadas en buena fe (sin intentos maliciosos).
  - Infraestructura de red estable para despliegue en Render.

## 4. Diseño

El diseño detalla la arquitectura del sistema, la estructura de la base de datos, los diagramas generados y la integración con prácticas DevOps y Scrum.

### 4.1 Diseño de la Base de Datos

La base de datos relacional (MySQL, InnoDB, utf8mb4) consta de 17 tablas, optimizada con índices, particiones, triggers y vistas:

- **Tablas Principales**:
  - `usuario`: Almacena cuentas con `email` (UNIQUE), `password_hash` (bcrypt), `rol` (ENUM).
  - `profesor`: Vinculada a `usuario` (1:1), con especialidades y hoja de vida.
  - `grupo`: Grupos con `num_estudiantes` (CHECK > 0).
  - `salon`: Salones con `capacidad` (CHECK > 0) y `codigo` (UNIQUE).
  - `asignacion`: Asignaciones con `estado` (ENUM), `origen` (ENUM), `score` y partición por `periodo_id`.
  - `restriccion`: Restricciones con `regla_json`, `dureza` (ENUM) y trigger `trg_valida_restriccion`.
  - `auditoria`: Registro de cambios con `cambios_json`.
  - `reporte_ocupacion`: Estadísticas de ocupación por salón/profesor.
  - `parametro_sistema`: Configuraciones con `clave` (UNIQUE) y `valor` (JSON).
- **Tablas de Unión**:
  - `salon_recurso`, `disp_profesor`, `disp_salon`, `recurso_disponibilidad` para relaciones muchos a muchos.
- **Optimizaciones**:
  - **Índices**: `idx_as_conflictos`, `idx_restriccion_objetivo`, etc., para consultas rápidas.
  - **Particiones**: `asignacion` particionada por `periodo_id` (HASH, 4 particiones).
  - **Trigger**: `trg_valida_restriccion` valida `objetivo_id` contra `objetivo_type`.
  - **Vistas**: `vista_conflictos_salon`, `vista_conflictos_profesor` para detectar conflictos.

### 4.2 Diagramas

Los diagramas generados reflejan el análisis y diseño del sistema:

- **Diagrama Entidad-Relación (ERD)**: Representa entidades y relaciones (ver código Mermaid `modelo_fisico.mmd`).
- **Modelo Relacional**: Detalla relaciones con claves foráneas (ver ERD).
- **Modelo Físico**: Especifica tipos de datos MySQL, restricciones, índices, particiones (ver `modelo_fisico.mmd`).
- **Diagrama de Clases**: Clases con atributos y métodos (ver `diagrama_clases.mmd`).
  - Ejemplo: Clase `Asignacion` con métodos `asignarProfesor()`, `calcularScore()`.
- **Diagrama de Flujo de Datos (DFD)**: Procesos, almacenes y flujos (ver `diagrama_flujo_datos.mmd`).
  - Procesos: Gestionar Usuarios, Asignaciones, Reportes, etc.
- **Diagrama de Casos de Uso**: Actores y casos de uso (ver `diagrama_casos_de_uso.mmd`).
- **Secuencias de Casos de Uso**: Diagramas de secuencia para cada HU (ver `secuencia_*.mmd`).

### 4.3 Diseño Arquitectural

El sistema sigue una arquitectura en capas para garantizar modularidad:

- **Capa de Presentación**:
  - Interfaz web responsive (HTML, CSS, JavaScript).
  - Drag-and-drop para asignaciones manuales (HU11).
  - Visualización de horarios y reportes (HU13-HU15).
- **Capa de Lógica**:
  - API RESTful (TH2) para operaciones CRUD (`/usuarios`, `/asignaciones`).
  - Algoritmo de asignación automática basado en `score` y restricciones (HU9).
  - Validaciones en tiempo real usando vistas para conflictos (HU12, HU16).
- **Capa de Datos**:
  - MySQL con estructura normalizada (3FN).
  - Vistas y triggers para integridad y optimización.
  - Índices y particiones para rendimiento.
- **Patrones de Diseño**:
  - **MVC**: Separación de presentación, lógica y datos.
  - **Observer**: Notificaciones de conflictos en tiempo real.
  - **Repository**: Acceso a datos encapsulado.
- **Integración DevOps**:
  - Repositorio GitHub con branching (feature, develop, main).
  - CI/CD con GitHub Actions: pruebas unitarias (TDD), linting, despliegue en Render.
  - Monitoreo de rendimiento y auditoría automática.

### 4.4 Diseño de Casos de Uso

Los casos de uso detallados (ver secciones previas) incluyen:
- **Iniciar Sesión (HU2)**: Autenticación con `usuario` y registro en `auditoria`.
- **Gestionar Usuarios (HU1)**: CRUD en `usuario` con auditoría.
- **Gestionar Grupos (HU3, HU4)**: CRUD en `grupo` con validaciones.
- **Gestionar Salones (HU5, HU6)**: CRUD en `salon`, `salon_recurso`, `disp_salon`.
- **Gestionar Profesores (HU7, HU8)**: CRUD en `profesor`, `disp_profesor`.
- **Ejecutar Asignación Automática (HU9, HU10)**: Algoritmo con `asignacion`, validado por vistas.
- **Realizar Asignación Manual (HU11, HU12)**: Interfaz drag-and-drop con validación en tiempo real.
- **Visualizar Horarios (HU13, HU14)**: Consultas a `asignacion`, `bloque_horario`.
- **Generar Reportes (HU15)**: Consultas a `reporte_ocupacion`, vistas de conflictos.
- **Gestionar Conflictos (HU16, HU17)**: Notificaciones y ajustes con `restriccion`.
- **Visualizar Historial (HU18)**: Consulta a `auditoria`.
- **Configurar Sistema (HU19)**: CRUD en `parametro_sistema`.

### 4.5 Integración con DevOps y Scrum

- **Scrum**:
  - **Backlog Refinado**: Priorización de épicas en sprints de 2 semanas.
  - **Sprints**: Iteraciones para implementar HU1-HU4 primero, luego HU10, HU6, HU5, HU7-HU9, HU13-HU19.
  - **Kanban**: Tablero para seguimiento de tareas (To-Do, In Progress, Done).
- **DevOps**:
  - **CI/CD**: GitHub Actions para pruebas automatizadas, linting y despliegue.
  - **TDD**: Pruebas unitarias para cada endpoint RESTful y lógica de negocio.
  - **Despliegue**: Render para hosting del backend y frontend.
  - **Monitoreo**: Logs en `auditoria` para seguimiento de errores y cambios.
- **Métricas de Calidad**:
  - **Mantenibilidad**: Índice de mantenibilidad > 80% (medido con herramientas como SonarQube).
  - **Cobertura de Pruebas**: > 90% con TDD.
  - **Tiempo de Respuesta**: < 2 segundos, verificado con pruebas de carga.

## 5. Conclusión

El análisis, levantamiento de requerimientos y diseño presentados cumplen al 100% con el documento *"Proyectos Desarrollo de Software 2.docx"* y la base de datos proporcionada. El sistema está diseñado para ser:
- **Funcional**: Soporta todas las épicas (HU1-HU19) y casos de uso.
- **Eficiente**: Optimizado con índices, particiones y vistas.
- **Seguro**: Autenticación robusta, auditoría y validaciones.
- **Mantenible**: Modular, normalizado y probado con TDD.
- **Escalable**: Preparado para grandes volúmenes de datos.

Los diagramas generados (ERD, Clases, DFD, Casos de Uso, Secuencias) y el esquema de la base de datos proporcionan una base sólida para la implementación. El siguiente paso es la codificación, comenzando con la capa de datos (scripts SQL) y la API RESTful, siguiendo el ciclo DevOps.

Para cualquier ajuste, implementación específica (e.g., código en Python, JavaScript) o documentación adicional, por favor indique los requerimientos específicos.
