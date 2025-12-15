
# Sistema de Asignación de Salones para Centro Educativo 🏫

[![Laravel](https://img.shields.io/badge/Laravel-^12.0-FF2D20?logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-^8.2-777BB4?logo=php&logoColor=white)](https://php.net)
[![PostgreSQL](https://img.shields.io/badge/PostgreSQL-15.x-4169E1?logo=postgresql&logoColor=white)](https://postgresql.org)
[![Vite](https://img.shields.io/badge/Vite-7.0.7-646CFF?logo=vite&logoColor=white)](https://vitejs.dev)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind-4.0.0-06B6D4?logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![Tests](https://img.shields.io/badge/Tests-245_passing-success?logo=github-actions&logoColor=white)]()
[![Render](https://img.shields.io/badge/Deploy-Render-46E3B7?logo=render&logoColor=white)](https://sistema-asignacion-salones.onrender.com)

¡Hola! Bienvenidos a nuestro sistema web para simplificar la vida en las escuelas. Imagina coordinar grupos, salones y profesores sin el caos de las agendas manuales: eso es lo que hemos construido aquí, una herramienta que automatiza la programación semestral, ya sea de forma automática o manual, para que los equipos educativos se enfoquen en lo que realmente importa.

> Estado actual del proyecto: Tests pasando (245) y documentación consolidada.

Este proyecto surgió de la necesidad real de hacer más eficiente la gestión académica, y lo hemos desarrollado con un enfoque natural: usando metodologías ágiles como Scrum con toques de Kanban, DevOps para un flujo continuo y TDD para que todo funcione sin sorpresas. El resultado es un sistema modular, fácil de mantener y escalable, listo para crecer con el centro educativo.

## 📋 Tabla de Contenidos

- [Descripción del Proyecto](#-descripción-del-proyecto)
- [Características Destacadas](#-características-destacadas)
- [Tecnologías y Lenguajes](#-tecnologías-y-lenguajes)
- [Roles del Sistema](#-roles-del-sistema)
- [Equipo de Desarrollo](#-integrantes-del-equipo)
- [Entregas del Proyecto](#-entregas-del-proyecto)
- [Estructura del Proyecto](#-estructura-del-proyecto)
- [Instalación y Configuración](#-instalación-y-configuración)
- [Testing](#-testing)
- [Documentación](#-documentación)
- [Despliegue](#-despliegue)

## 📝 Descripción del Proyecto

Sistema web para la gestión integral de recursos educativos (grupos, salones, profesores) y la programación semestral de asignaciones, tanto automática como manualmente.

### ✨ Características Destacadas

- **🔐 Sistema de Roles Robusto:** 8 roles especializados con permisos granulares
- **🤖 Asignación Automática Inteligente:** Algoritmo de optimización para asignar salones y profesores
- **✏️ Asignación Manual Flexible:** Interfaz drag-and-drop para ajustes personalizados
- **⚠️ Detección de Conflictos:** Validación en tiempo real de solapamientos y restricciones
- **📊 Reportes y Visualización:** Horarios por profesor, grupo y salón
- **📱 Diseño Responsivo:** Interfaz adaptable a dispositivos móviles y escritorio
- **🧪 Alta Cobertura de Tests:** 245 tests automatizados (Pest + PHPUnit)
- **🚀 CI/CD Completo:** Despliegue automático con GitHub Actions + Render
- **📦 Arquitectura Modular:** Separación por módulos funcionales (`app/Modules`)

## 🎯 Objetivo

Integrar todas las tecnologías y conceptos necesarios para la implementación de un ciclo DevOps completo utilizando metodologías ágiles (Scrum/Kanban) y prácticas como TDD.

## 👥 Integrantes del Equipo

- Luis Carlos Pedraza
- Johan Alejandro Rodríguez 
- Kevin Andrés Galeano
- Katherin Acevedo

## 🚀 Tecnologías y Lenguajes

Para llevar a cabo este ambicioso proyecto, hemos seleccionado un conjunto de tecnologías de vanguardia que nos permitirán construir un sistema robusto, modular y eficiente.

### Backend
- **Framework:** Laravel ^12.0 (patrón MVC con arquitectura modular)
- **Lenguaje:** PHP ^8.2
- **Base de Datos:** PostgreSQL 15.x (Supabase - Producción) / SQLite 3.x (Desarrollo)
- **ORM:** Eloquent (Laravel)
- **Testing:** Pest ^3.8 + PHPUnit ^11.5.3 (245 tests pasando)
- **Code Quality:** Laravel Pint ^1.24, Laravel Pail ^1.2.2

### Frontend
- **Build Tool:** Vite 7.x (hot module replacement)
- **UI:** Bootstrap 5 + FontAwesome 6 (diseño responsive y accesible)
- **Calendario:** FullCalendar 6.x (semanal con enriquecimiento de eventos)
- **Template Engine:** Blade (Laravel)

### DevOps & Infraestructura
- **Control de Versiones:** Git + GitHub
- **CI/CD:** GitHub Actions (tests automáticos + despliegue)
- **Hosting:** Render (web service)
- **Database Cloud:** Supabase (PostgreSQL gestionado)
- **Sistema Operativo:** Ubuntu 24.04 / WSL2
- **Editor de Código:** Visual Studio Code
- **Gestores de Dependencias:** Composer 2.x (PHP) + npm (Node.js 22.x)
 - **Automatizaciones:** n8n (workflows, notificaciones por correo, chatbot)

#### 🔗 Enlace despliegue con Render
https://sistema-asignacion-salones.onrender.com

## 👤 Roles del Sistema

El sistema implementa **8 roles especializados** con permisos diferenciados para garantizar seguridad y separación de responsabilidades:

| Rol | Slug | Permisos Principales |
|-----|------|---------------------|
| **Administrador** | `administrador` | Acceso completo al sistema, gestión de usuarios y configuración global |
| **Secretaria Administrativa** | `secretaria_administrativa` | Gestión administrativa, generación de reportes y exportación de datos |
| **Coordinador** | `coordinador` | Gestión académica completa, asignaciones automáticas/manuales, aprobaciones |
| **Secretaria de Coordinación** | `secretaria_coordinacion` | Apoyo en gestión académica, consulta de asignaciones y reportes |
| **Coordinador de Infraestructura** | `coordinador_infraestructura` | Gestión de salones, disponibilidad horaria y recursos físicos |
| **Secretaria de Infraestructura** | `secretaria_infraestructura` | Apoyo en gestión de infraestructura, consulta de salones |
| **Profesor** | `profesor` | Consulta de horarios personales, disponibilidad horaria |
| **Profesor Invitado** | `profesor_invitado` | Acceso temporal limitado a horarios personales |

> **Nota:** Los roles están implementados en `database/seeders/RoleSeeder.php` y se controlan mediante middleware (`RoleMiddleware`).

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

---

## 📦 Release 2.0.0 - Pull Requests

### Integración de Cambios a Producción

Para la publicación de la versión 2.0.0, hemos preparado documentación completa para los Pull Requests necesarios:

1. **PR: release/2.0.0 → develop** - [Ver Descripción Completa](./documentation/PR_Release_2.0.0_to_Develop.md)
   - Integración de mejoras de infraestructura y CI/CD
   - Configuración de Dockerfile multi-servicio
   - GitHub Actions optimizado
   - Configuración para Render y Supabase

2. **PR: develop → main** - [Ver Descripción Completa](./documentation/PR_Develop_to_Main.md)
   - Publicación completa del sistema (Épicas 1-10)
   - Todas las funcionalidades implementadas
   - Sistema listo para producción

### Guías de Integración

- 📖 [Resumen Ejecutivo de PRs](./documentation/RESUMEN_EJECUTIVO_PRS.md)
- 📝 [Guía para Crear los PRs](./documentation/GUIA_CREACION_PRS.md)

### Arquitectura Modular y Progresión por Rol

La versión 2.0.0 implementa una arquitectura modular completa con progresión de funcionalidades por rol:

- ✅ **Rol Profesor**: Funcionalidades base de consulta (HU14)
- ✅ **Rol Coordinador**: Gestión completa de recursos y asignaciones (HU3-HU19)
- ✅ **Roles Especializados**: Coordinador Académico, de Infraestructura, Secretarias
- ✅ **Administrador**: Control total del sistema

Para más detalles, consulta la [Estrategia de Branching](./documentation/EstrategiaDeBranching.md) y el [Análisis de Requerimientos](./documentation/Analisis%20Levantamiento%20de%20Requerimientos.md).

---

## 📁 Estructura del Proyecto

```
sistema-asignacion-salones/
├── app/
│   ├── Console/Commands/         # Comandos Artisan personalizados
│   ├── Http/
│   │   ├── Controllers/          # Controladores base
│   │   ├── Middleware/           # RoleMiddleware, autenticación
│   │   └── Kernel.php
│   ├── Models/                   # Modelos Eloquent (User, Assignment, etc.)
│   └── Modules/                  # Arquitectura modular
│       ├── Auth/                 # Autenticación y roles
│       ├── Assignments/          # Asignaciones automáticas/manuales
│       ├── Groups/               # Gestión de grupos
│       ├── Rooms/                # Gestión de salones
│       └── Teachers/             # Gestión de profesores
├── database/
│   ├── migrations/               # Migraciones de PostgreSQL/SQLite
│   ├── seeders/                  # RoleSeeder, CareerSeeder, etc.
│   └── factories/                # Factories para testing
├── resources/
│   ├── views/                    # Vistas Blade
│   ├── css/                      # Tailwind CSS
│   └── js/                       # JavaScript + Axios
├── routes/
│   ├── web.php                   # Rutas web principales
│   └── console.php               # Rutas de consola
├── tests/
│   ├── Feature/                  # Feature tests (Pest/PHPUnit)
│   └── Unit/                     # Unit tests
├── documentation/                # Documentación técnica
│   ├── DiagramaEntidadRelacion.md
│   ├── DiagramaModeloRelacional.md
│   ├── DiagramaSecuenciaCasosDeUso.md
│   ├── ProgramasHerraminetas.md
│   └── ...
├── .github/workflows/            # GitHub Actions (CI/CD)
├── composer.json                 # Dependencias PHP
├── package.json                  # Dependencias JavaScript
├── vite.config.js                # Configuración Vite
└── README.md                     # Este archivo
```

## 🤖 Chatbot y n8n AI Agent

El sistema incluye un chatbot integrado con n8n que permite responder preguntas y automatizar notificaciones.

- **Arquitectura:** Frontend (Blade) → API Laravel → n8n Chat Trigger → AI Agent → Tools (HTTP Request) → API Laravel.
- **Tecnologías:** n8n (Chat Trigger + AI Agent), modelo LLM (p.ej. qwen3-next u OpenAI), HTTP Request tools a endpoints Laravel.
- **Endpoints:** `routes/api.php` expone rutas tipo `/api/webhooks/n8n/...` para datos (asignaciones, conflictos, invitados por expirar).
- **Seguridad:** Header `X-API-Token` validado en middleware para llamadas desde n8n.
- **Configuración:** `.env` con `N8N_WEBHOOK_CHATBOT` apuntando al webhook del Chat Trigger.

Documentación:
- Esquema y conexiones: [documentation/informes/ESQUEMA_CHAT_N8N.md](documentation/informes/ESQUEMA_CHAT_N8N.md)
- Plan de implementación: [documentation/informes/PLAN_N8N_IMPLEMENTATION.md](documentation/informes/PLAN_N8N_IMPLEMENTATION.md)

### Prueba rápida del chatbot

```bash
# Iniciar n8n localmente

# Abrir UI de n8n
# http://localhost:5678

# Probar endpoints Laravel desde WSL
## 🛠️ Instalación y Configuración

### Requisitos Previos

En n8n, conecta el nodo "When chat message received" al "AI Agent" y declara las herramientas HTTP con el header `X-API-Token`.

## 🎬 Guía de Demo Rápida

Para presentar el proyecto en vivo:

```bash

- **PHP:** >= 8.2
- **Composer:** >= 2.0
- **Node.js:** >= 22.x
- **PostgreSQL:** >= 15.x (o SQLite para desarrollo local)

- Módulos a mostrar:
  - Gestión Académica: Carreras, Semestres, Materias (CRUD con validaciones y paginación)
  - Calendario semanal (FullCalendar) con eventos enriquecidos
  - Asignación Manual con filtros por Carrera → Semestre
  - Exportar PDF de asignaciones (respetando filtros)
  - Chatbot (si n8n está activo) consultando datos vía tools

## 📚 Documentación consolidada

Accede al índice central: [documentation/INDICE_DOCUMENTACION.md](documentation/INDICE_DOCUMENTACION.md)

- Guías: [documentation/guias](documentation/guias)
- Resúmenes: [documentation/resumenes](documentation/resumenes)
- Informes: [documentation/informes](documentation/informes)

Enlaces útiles:
- Arquitectura académica: [documentation/informes/ARQUITECTURA_GESTION_ACADEMICA.md](documentation/informes/ARQUITECTURA_GESTION_ACADEMICA.md)
- Reporte de redistribución final: [documentation/informes/REPORTE_REDISTRIBUCION_FINAL.md](documentation/informes/REPORTE_REDISTRIBUCION_FINAL.md)
- Finalización módulo Gestión Académica: [documentation/resumenes/FINALIZACION_GESTION_ACADEMICA.md](documentation/resumenes/FINALIZACION_GESTION_ACADEMICA.md)
- **Git:** >= 2.30

### Instalación Rápida

```bash
# 1. Clonar el repositorio
git clone <repository-url>
cd sistema-asignacion-salones

# 2. Ejecutar script de configuración automática
composer setup
# Este comando ejecuta:
# - composer install (dependencias PHP)
# - cp .env.example .env (archivo de configuración)
# - php artisan key:generate (clave de aplicación)
# - php artisan migrate (migraciones de BD)
# - npm install && npm run build (assets frontend)

# 3. Poblar base de datos con datos de prueba
php artisan db:seed --class=RoleSeeder
php artisan db:seed --class=CareerSpecificMallaHorariaSeeder

# 4. Iniciar servidor de desarrollo
composer dev
# Este comando ejecuta concurrentemente:
# - php artisan serve (servidor Laravel)
# - php artisan queue:work (procesamiento de colas)
# - php artisan pail (visualizador de logs)
# - npm run dev (Vite HMR)
```

### Configuración Manual

1. **Configurar `.env`:**
   ```env
   APP_NAME="Sistema de Asignación de Salones"
   APP_ENV=local
   APP_KEY=base64:...
   APP_DEBUG=true
   APP_URL=http://localhost:8000

   # Base de datos (PostgreSQL en producción, SQLite en local)
   DB_CONNECTION=sqlite  # o pgsql para PostgreSQL
   DB_DATABASE=/absolute/path/to/database/database.sqlite
   
   # Para PostgreSQL:
   # DB_CONNECTION=pgsql
   # DB_HOST=127.0.0.1
   # DB_PORT=5432
   # DB_DATABASE=asignacion_salones
   # DB_USERNAME=tu_usuario
   # DB_PASSWORD=tu_password
   ```

2. **Crear base de datos SQLite (si usas SQLite):**
   ```bash
   touch database/database.sqlite
   ```

3. **Ejecutar migraciones:**
   ```bash
   php artisan migrate
   ```

4. **Poblar con datos de prueba:**
   ```bash
   php artisan db:seed
   ```

### Guías Adicionales

- 📖 [Guía de Configuración Detallada](./GUIA_CONFIGURACION.md)
- 🔄 [Guía de Sincronización con Supabase](./GUIA_SINCRONIZACION.md)
- 🔄 [Sincronización Supabase (SYNC_SUPABASE.md)](./SYNC_SUPABASE.md)

## 🧪 Testing

### Ejecutar Tests

```bash
# Ejecutar todos los tests (Pest + PHPUnit)
composer test
# o directamente:
php artisan test

# Ejecutar solo tests de una suite específica
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit

# Ejecutar con cobertura de código
php artisan test --coverage

# Ejecutar con Pest directamente (sintaxis moderna)
./vendor/bin/pest

# Ejecutar con filtro
php artisan test --filter=AuthenticationTest
```

### Estadísticas de Testing

- **Total de Tests:** 83 tests
- **Estado:** ✅ Todos pasando
- **Frameworks:** Pest ^3.8 + PHPUnit ^11.5.3
- **Cobertura:** Feature tests (flujos completos) + Unit tests (lógica aislada)
- **CI/CD:** Ejecución automática en cada push/PR vía GitHub Actions

### Estructura de Tests

```
tests/
├── Feature/
│   ├── Auth/                    # Tests de autenticación y roles
│   ├── Assignments/             # Tests de asignaciones
│   ├── Groups/                  # Tests de grupos
│   └── ...
├── Unit/
│   ├── Models/                  # Tests de modelos Eloquent
│   ├── Services/                # Tests de servicios
│   └── ...
└── TestCase.php                 # Clase base para tests
```

## 📚 Documentación

### Documentación Técnica

Toda la documentación técnica está disponible en la carpeta [`documentation/`](./documentation/):

#### Diagramas de Arquitectura
- [📊 Índice de Documentación](./documentation/INDICE_DOCUMENTACION.md)
- [🗂️ Diagrama Entidad Relación (ERD)](./documentation/DiagramaEntidadRelacion.md)
- [🔗 Diagrama Modelo Relacional](./documentation/DiagramaModeloRelacional.md)
- [💾 Diagrama Modelo Físico (PostgreSQL)](./documentation/DiagramaModeloFisico.md)
- [📐 Diagrama de Clases](./documentation/DiagramaDeClases.md)
- [🔄 Diagrama de Flujo de Datos](./documentation/DiagramaFlujoDatos.md)
- [📋 Diagrama de Casos de Uso](./documentation/DiagramaCasosDeUsoGeneral.md)
- [⏱️ Diagrama de Secuencia](./documentation/DiagramaSecuenciaCasosDeUso.md)

#### Análisis y Requerimientos
- [📝 Análisis y Levantamiento de Requerimientos](./documentation/Analisis%20Levantamiento%20de%20Requerimientos.md)
- [🎯 Resumen Ejecutivo](./documentation/RESUMEN_EJECUTIVO_PRS.md)

#### Guías de Desarrollo
- [🔧 Programas y Herramientas](./documentation/ProgramasHerraminetas.md)
- [🌿 Estrategia de Branching (GitFlow)](./documentation/EstrategiaDeBranching.md)
- [🔀 Guía para Crear Pull Requests](./documentation/GUIA_CREACION_PRS.md)
- [⚙️ GitHub Actions para Laravel](./documentation/github-actions-laravel.md)

#### Base de Datos
- [🗄️ Documentación de Bases de Datos](./documentation/BasesDeDatos.md)

### Ejemplos de Uso

#### Crear un nuevo usuario con rol

```php
use App\Models\User;
use App\Modules\Auth\Models\Role;

$user = User::create([
    'name' => 'Juan Pérez',
    'email' => 'juan.perez@ejemplo.com',
    'password' => bcrypt('password123'),
]);

$coordinadorRole = Role::where('slug', Role::COORDINADOR)->first();
$user->roles()->attach($coordinadorRole->id);
```

#### Ejecutar asignación automática

```bash
php artisan assignments:auto-assign --period=1 --career=1
```

## 🚀 Despliegue

### Producción (Render + Supabase)

El proyecto está configurado para despliegue automático en **Render** con base de datos **PostgreSQL** en **Supabase**.

#### Configuración en Render

1. **Crear Web Service en Render:**
   - Build Command: `composer install && npm install && npm run build`
   - Start Command: `php artisan serve --host=0.0.0.0 --port=$PORT`

2. **Variables de Entorno en Render:**
   ```env
   APP_ENV=production
   APP_DEBUG=false
   APP_KEY=<generar-con-artisan>
   DB_CONNECTION=pgsql
   DB_HOST=<supabase-host>
   DB_PORT=5432
   DB_DATABASE=<supabase-database>
   DB_USERNAME=<supabase-user>
   DB_PASSWORD=<supabase-password>
   ```

3. **Configurar GitHub Actions:**
   - El archivo `.github/workflows/laravel.yml` ejecuta tests automáticamente
   - Push a `main` despliega automáticamente a Render

#### URL de Producción

🔗 **https://sistema-asignacion-salones.onrender.com**

### CI/CD Pipeline

```yaml
# .github/workflows/laravel.yml
name: Laravel CI/CD

on:
  push:
    branches: [ main, develop ]
  pull_request:
    branches: [ main, develop ]

jobs:
  test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.2'
      - name: Install Dependencies
        run: composer install
      - name: Run Tests
        run: php artisan test
      - name: Code Style Check
        run: ./vendor/bin/pint --test
```

---

## 🤝 Contribuciones

### GitFlow Workflow

Utilizamos **GitFlow** para gestionar el desarrollo:

- `main` → Rama de producción (solo código estable)
- `develop` → Rama de desarrollo (integración continua)
- `feature/*` → Nuevas funcionalidades
- `release/*` → Preparación de releases
- `hotfix/*` → Correcciones urgentes en producción

### Proceso de Contribución

1. **Crear rama desde `develop`:**
   ```bash
   git checkout develop
   git pull origin develop
   git checkout -b feature/nombre-funcionalidad
   ```

2. **Desarrollar y commitear:**
   ```bash
   git add .
   git commit -m "feat: descripción del cambio"
   ```

3. **Ejecutar tests localmente:**
   ```bash
   composer test
   ./vendor/bin/pint  # Code style
   ```

4. **Crear Pull Request a `develop`:**
   - Completar template de PR
   - Esperar revisión de código
   - Aprobar CI/CD (tests automáticos)

### Convención de Commits

Usamos **Conventional Commits**:

- `feat:` → Nueva funcionalidad
- `fix:` → Corrección de bug
- `docs:` → Cambios en documentación
- `test:` → Añadir/modificar tests
- `refactor:` → Refactorización sin cambio funcional
- `style:` → Cambios de formato (Pint)
- `chore:` → Tareas de mantenimiento

---

## 📞 Contacto y Soporte

- **Repositorio:** GitHub (privado)
- **Despliegue:** https://sistema-asignacion-salones.onrender.com
- **Equipo:** Luis Carlos Pedraza, Johan Alejandro Rodríguez, Kevin Andrés Galeano, Katherin Acevedo

---

## 📄 Licencia

Este proyecto es de uso académico para el curso de DevOps.

---

**Última actualización:** Diciembre 2024  
**Versión:** 2.0.0