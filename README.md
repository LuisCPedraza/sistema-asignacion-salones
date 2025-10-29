# Sistema de Asignación de Salones para Centro Educativo 🏫

¡Hola! Bienvenidos a nuestro sistema web para simplificar la vida en las escuelas. Imagina coordinar grupos, salones y profesores sin el caos de las agendas manuales: eso es lo que hemos construido aquí, una herramienta que automatiza la programación semestral, ya sea de forma automática o manual, para que los equipos educativos se enfoquen en lo que realmente importa.

Este proyecto surgió de la necesidad real de hacer más eficiente la gestión académica, y lo hemos desarrollado con un enfoque natural: usando metodologías ágiles como Scrum con toques de Kanban, DevOps para un flujo continuo y TDD para que todo funcione sin sorpresas. El resultado es un sistema modular, fácil de mantener y escalable, listo para crecer con el centro educativo.

## Descripción del Proyecto
Sistema web para la gestión integral de recursos educativos (grupos, salones, profesores) y la programación semestral de asignaciones, tanto automática como manualmente.

## 🎯 Objetivo

Integrar todas las tecnologías y conceptos necesarios para la implementación de un ciclo DevOps completo utilizando metodologías ágiles (Scrum/Kanban) y prácticas como TDD.

## 👥 Integrantes del Equipo

- Luis Carlos Pedraza
- Johan Alejandro Rodríguez 
- Kevin Andrés Galeano
- Katherin Acevedo

## 🚀 Tecnologías y Lenguajes

Para llevar a cabo este ambicioso proyecto, hemos seleccionado un conjunto de tecnologías de vanguardia que nos permitirán construir un sistema robusto, modular y eficiente.

- **Sistema Operativo:** Ubuntu (recomendado para desarrollo)
- **Contenedores:** Docker Desktop
- **Framework:** Laravel, patrón arquitectónico Modelo-Vista-Controlador (MVC) 
- **Gestor de Dependencias:** Composer
- **Lenguaje Backend:** PHP
- **Base de Datos:** MySQL
- **Editor de Código:** Visual Studio Code
- **Control de Versiones:** Git y GitHub
- **Servicio de Despliegue:** Render

## 📋 Entregas del Proyecto

Nuestro trabajo se divide en dos entregas principales, enfocadas en diferentes etapas del ciclo de vida del proyecto.

### Primera Entrega (Aproximadamente, Clase 9)

La primera entrega se centra en la fase de análisis, diseño e infraestructura.
#### Enlace Diagramas en Draw.io
    
https://drive.google.com/file/d/15zuAVwyVuvfje4TfutLYILP8fPk8Fikk/view?usp=sharing
    
- **Análisis, levantamiento de requerimientos y diseño (50%):** Se entregará la documentación completa de la fase inicial.
  - **Diagramas:** Se incluirán el diagrama de casos de uso y casos de uso, diagrama de clases / diagrama de flujo de datos, y el diagrama Entidad Relación, Modelo Relacional y Modelo Físico.
- **Configuración de la Infraestructura de Desarrollo (50%):** Se revisará la configuración del repositorio de GitHub y la estrategia de branching, la configuración de la base de datos y la configuración del entorno de desarrollo.

### Segunda Entrega (Aproximadamente, Clase 15)

La segunda entrega se enfoca en el desarrollo, la integración y el despliegue continuo.

- **Gestión del Proyecto (25%):** Se revisará la correcta gestión del proyecto utilizando tableros Kanban, GitHub Issues, GitHub Projects y Milestones.
- **Desarrollo Continuo (25%):** Se evaluará el uso del repositorio de GitHub, la estrategia de branching y los Pull Requests.
- **Integración y Despliegue Continuo (25%):** Se verificará la implementación de GitHub Actions para las pruebas unitarias y el despliegue continuo en Render.
- **Funcionalidad (25%):** Se evaluará la funcionalidad de los módulos desarrollados.

## Roles del Equipo

### 🧭 Product Owner (PO): **Luis Carlos Pedraza**
- Responsable de **maximizar el valor del producto** y mantener el **Product Backlog** priorizado.
- Define las **épicas e historias de usuario** junto con el equipo y valida su cumplimiento.
- Se comunica con el profesor (cliente) para **aclarar requerimientos y priorizar entregas**.
- Aprueba los incrementos al final de cada sprint (aceptación de HU).
- Supervisa la alineación entre los objetivos del curso y el progreso del proyecto.

### ⚙️ Scrum Master (SM): **Luis Carlos Pedraza**
- Facilita las **ceremonias Scrum** (planning, daily, review, retrospective).
- Asegura que el equipo entienda y aplique correctamente el **marco Scrum**.
- Elimina **bloqueos o impedimentos** que afecten el avance del equipo.
- Asegura el cumplimiento del **Definition of Ready (DoR)** y **Definition of Done (DoD)**.

### 💻 Development Team (Dev Team)
**Integrantes:**  
- Johan Alejandro Rodríguez  
- Kevin Andrés Galeano  
- Katherin Acevedo  
- Luis Carlos Pedraza 

**Responsabilidades:**
- Desarrollar las historias de usuario acordadas en cada sprint.  
- Implementar tanto el **backend (API REST Laravel)** como el **frontend (React + Vite)**.  
- Diseñar y mantener la base de datos, integrando el ciclo **TDD (pruebas unitarias, integración y refactorización)**.  
- Participar en las revisiones, retrospectivas y decisiones técnicas.  
- Asegurar la calidad, el versionamiento y los **commits siguiendo las convenciones** del equipo.

---

## Acuerdos del Equipo

### ⏳ Duración de los Sprints
- Cada **sprint dura 2 semanas** (10 a 14 días hábiles).  
- El último día del sprint se realiza la **Sprint Review** (demostración) y la **Retrospective** (análisis de mejora).

### 🕐 Daily Scrum
- Se realiza de lunes a viernes a las **8:00 a.m. (hora Colombia)** vía reunión corta (5-10 min) o comentarios en GitHub Project.
- Cada integrante responde tres preguntas:
  - ¿Qué hice ayer?
  - ¿Qué haré hoy?
  - ¿Qué impedimentos tengo?

### ✅ Definition of Ready (DoR)
Una historia se considera **lista para ser desarrollada** cuando cumple:
- Tiene descripción clara en formato: *Como [rol] quiero [necesidad] para [beneficio]*.  
- Posee **criterios de aceptación** definidos.  
- Está estimada en **Story Points**.  
- Está priorizada por el **PO** y visible en el **Project (Backlog)**.  
- No depende de otra historia sin completada.

### 🧩 Definition of Done (DoD)
Una historia se considera **terminada** cuando:
- El código está **implementado, probado y revisado** (pruebas y lint pasan en CI).  
- Los cambios fueron **mergeados a `develop` mediante Pull Request aprobado**.  
- La documentación (Swagger/README) está actualizada.  
- El incremento fue **desplegado y verificado en Render (entorno funcional)**.  
- El PO validó que cumple los criterios de aceptación.

---

## 📝 Tareas del Proyecto (GitHub Issues)

Aquí se detalla la estructura de las tareas y subtareas que gestionaremos en GitHub para un seguimiento claro del progreso.

### Fase 1: Configuración del Proyecto y Documentación

- `PROJECT-SETUP-01`: Configuración Inicial del Proyecto y Documentación
- `TASK-DOC-01`: Documentación de Análisis y Requerimientos
- `TASK-DIAGRAMS-01`: Diseño de Diagramas (Casos de Uso, Clases, ER)
- `TASK-GIT-01`: Configuración de GitHub y Estrategia de Branching
- `TASK-DB-01`: Configuración de la Base de Datos
- `TASK-ENV-01`: Configuración del Entorno de Desarrollo

### Fase 2: Desarrollo de las Épicas

- **`EPIC-USERS-01`**: Gestión de Usuarios y Autenticación
  - `TASK-HU-01`: HU1: Crear y gestionar cuentas de usuario
  - `TASK-HU-02`: HU2: Iniciar sesión y acceder según el rol
  - `TASK-TH-03`: TH3: Implementar sistema de autenticación seguro
- **`EPIC-GROUPS-02`**: Gestión de Grupos de Estudiantes
  - `TASK-HU-03`: HU3: Registrar nuevos grupos de estudiantes
  - `TASK-HU-04`: HU4: Editar, desactivar y visualizar grupos existentes
- **`EPIC-ROOMS-03`**: Gestión de Salones
  - `TASK-HU-05`: HU5: Registrar salones
  - `TASK-HU-06`: HU6: Gestionar la disponibilidad horaria de cada salón
- **`EPIC-PROFS-04`**: Gestión de Profesores
  - `TASK-HU-07`: HU7: Registrar profesores
  - `TASK-HU-08`: HU8: Gestionar la disponibilidad horaria de cada profesor
- **`EPIC-AUTO-ASSIGN-05`**: Sistema de Asignación Automática
  - `TASK-HU-09`: HU9: Ejecutar algoritmo de asignación automática
  - `TASK-HU-10`: HU10: Configurar parámetros y prioridades de la asignación automática
- **`EPIC-MANUAL-ASSIGN-06`**: Sistema de Asignación Manual
  - `TASK-HU-11`: HU11: Realizar asignaciones manuales con arrastrar y soltar
  - `TASK-HU-12`: HU12: Visualizar conflictos en tiempo real
- **`EPIC-REPORTS-07`**: Visualización y Reportes
  - `TASK-HU-13`: HU13: Visualizar el horario semestral completo
  - `TASK-HU-14`: HU14: Visualizar el horario personal del profesor
  - `TASK-HU-15`: HU15: Generar reportes de utilización
- **`EPIC-CONFLICTS-08`**: Gestión de Conflictos y Restricciones
  - `TASK-HU-16`: HU16: Notificar conflictos y sugerir alternativas
  - `TASK-HU-17`: HU17: Establecer restricciones para recursos específicos
- **`EPIC-AUDIT-09`**: Historial y Auditoría
  - `TASK-HU-18`: HU18: Visualizar el historial de cambios en las asignaciones
- **`EPIC-CONFIG-10`**: Configuración del Sistema
  - `TASK-HU-19`: HU19: Configurar parámetros generales del sistema

### Fase 3: Tareas Técnicas y de DevOps

- `TECH-TASKS-00`: Tareas Técnicas del Backlog
- `TASK-TH-01`: TH1: Configurar e implementar la base de datos
- `TASK-TH-02`: TH2: Desarrollar API RESTful para las operaciones
- `TASK-TH-03`: TH3: Implementar sistema de autenticación seguro
- `TASK-TH-04`: TH4: Crear la interfaz responsive y accesible
- `TASK-DEVOPS-01`: Integración Continua (CI) con GitHub Actions
- `TASK-DEVOPS-02`: Despliegue Continuo (CD) con Render
- `TASK-DEVOPS-03`: Implementar Pruebas Unitarias
- `TASK-DEVOPS-04`: Integrar el Tablero Kanban
