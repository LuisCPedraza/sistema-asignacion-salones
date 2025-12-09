# Programas y Herramientas Utilizados en el Proyecto "Sistema de Asignación de Salones"

## Índice
1. [Resumen Ejecutivo](#resumen-ejecutivo)
2. [Tecnologías Core del Proyecto](#tecnologías-core-del-proyecto)
3. [Herramientas de Desarrollo](#herramientas-de-desarrollo)
4. [Infraestructura y Despliegue](#infraestructura-y-despliegue)
5. [Testing y Calidad de Código](#testing-y-calidad-de-código)
6. [DevOps y CI/CD](#devops-y-cicd)
7. [Gestión de Proyecto](#gestión-de-proyecto)
8. [Resumen de Versiones](#resumen-de-versiones)

---

## Resumen Ejecutivo

Este documento consolida todas las herramientas, tecnologías y programas que componen el ecosistema técnico del **Sistema de Asignación de Salones**. La selección de cada componente responde a necesidades específicas de escalabilidad, mantenibilidad, seguridad y eficiencia operacional, alineándose con las mejores prácticas de la industria y con un enfoque DevOps integral.

**Stack Tecnológico Principal:**
- **Backend:** Laravel ^12.0 con PHP ^8.2
- **Base de Datos:** PostgreSQL (Supabase en producción), SQLite (desarrollo local)
- **Frontend:** Vite 7.0.7, Tailwind CSS 4.0.0
- **Testing:** Pest ^3.8 + PHPUnit ^11.5.3 (83 tests pasando)
- **Despliegue:** Render (web service) + Supabase (database)
- **Control de Versiones:** Git + GitHub con GitHub Actions

---

## Tecnologías Core del Proyecto

### 1. Backend

#### Laravel Framework
- **Versión:** ^12.0 (Laravel 12.x)
- **Tipo:** Framework PHP basado en MVC (Modelo-Vista-Controlador)
- **Propósito:** Construcción del API, lógica de negocio, autenticación, validaciones, modelos y controladores modulares.
- **Características destacadas:**
  - **Artisan CLI:** Comandos personalizados para migraciones, seeders, generación de código
  - **Eloquent ORM:** Interacción fluida con PostgreSQL/SQLite mediante modelos
  - **Blade Templating:** Motor de plantillas para vistas dinámicas
  - **Middleware:** Sistema de roles implementado con 8 roles diferentes (administrador, secretaria_administrativa, coordinador, secretaria_coordinacion, coordinador_infraestructura, secretaria_infraestructura, profesor, profesor_invitado)
  - **Queue System:** Procesamiento asíncrono de tareas
  - **Cache:** Optimización de consultas frecuentes
- **Arquitectura:** Modular (`app/Modules`) para separación de responsabilidades

#### PHP
- **Versión:** ^8.2 (PHP 8.2+)
- **Propósito:** Lenguaje interpretado del servidor para la lógica del backend
- **Características utilizadas:**
  - Tipado estricto en clases y métodos
  - Enumeraciones para estados (`AssignmentStatus`, `RoleType`)
  - Atributos para metadatos
  - Named arguments
  - Manejo de errores con try-catch y custom exceptions
  - Arrays asociativos nativos

#### PostgreSQL
- **Versión:** 15.x (gestionado por Supabase en producción)
- **Motor:** Sistema de gestión de bases de datos relacional de código abierto
- **Propósito:** Almacenamiento persistente de datos: usuarios, salones, grupos, horarios, asignaciones
- **Características utilizadas:**
  - **Tipos avanzados:** JSONB para metadatos flexibles, ENUM para estados
  - **Índices B-tree:** En columnas de búsqueda frecuente (`codigo`, `email`, `nombre`)
  - **Índices GIN:** Para búsquedas en campos JSONB
  - **Foreign keys:** En cascada para integridad referencial
  - **Timestamps:** `created_at`, `updated_at` para auditoría automática
  - **Soft deletes:** `deleted_at` para eliminación lógica
- **Optimizaciones aplicadas:**
  - Índices compuestos en tablas de relaciones (N:M)
  - Índices parciales para registros activos (`WHERE deleted_at IS NULL`)
  - Constraints CHECK para validación de datos
  - Sequences para IDs autoincrementales

#### SQLite
- **Versión:** 3.x (incluido en PHP)
- **Propósito:** Base de datos local para desarrollo y testing
- **Ventajas:** Sin configuración adicional, archivo único (`database/database.sqlite`), ideal para CI/CD

---

### 2. Paquetes Backend Esenciales

#### Doctrine DBAL
- **Versión:** ^4.3
- **Propósito:** Capa de abstracción de base de datos para operaciones avanzadas
- **Uso:** Migraciones complejas, operaciones SQL directas, introspección de esquema

#### Maatwebsite/Laravel Excel
- **Versión:** ^1.1
- **Propósito:** Importación/exportación de datos en formato Excel (XLSX, CSV)
- **Uso:** Exportación de reportes de asignaciones, importación masiva de grupos/profesores

#### Laravel Tinker
- **Versión:** ^2.10.1
- **Propósito:** REPL (Read-Eval-Print Loop) para interactuar con la aplicación desde consola
- **Uso:** Debugging, testing rápido de modelos, ejecución de comandos Eloquent

---

### 3. Frontend

#### Vite
- **Versión:** 7.0.7
- **Propósito:** Bundling ultrarrápido de assets (CSS, JS), hot module replacement (HMR)
- **Ventajas:** Build nativo con ESM, tiempos de recarga instantáneos, optimización automática para producción
- **Integración:** Laravel Vite Plugin ^2.0.0
- **Scripts:**
  - `npm run dev` → Servidor de desarrollo con HMR
  - `npm run build` → Compilación optimizada para producción

#### Tailwind CSS
- **Versión:** 4.0.0
- **Propósito:** Framework CSS utility-first para diseño responsivo y consistente
- **Características:**
  - Compilación JIT (Just-In-Time)
  - Eliminación automática de CSS no utilizado
  - Personalización mediante `@tailwindcss/vite`
  - Sistema de diseño escalable con clases utilitarias

#### Axios
- **Versión:** 1.11.0
- **Propósito:** Cliente HTTP basado en promesas para requests AJAX
- **Uso:** Comunicación con API Laravel, interceptores para autenticación, manejo de errores

#### Concurrently
- **Versión:** 9.0.1
- **Propósito:** Ejecución simultánea de múltiples comandos npm/composer
- **Uso:** `composer dev` ejecuta simultáneamente `artisan serve`, `queue:work`, `pail`, `vite`

---

## Herramientas de Desarrollo

### 1. Control de Versiones

#### Git
- **Versión:** 2.x (recomendado >= 2.30)
- **Propósito:** Sistema de control de versiones distribuido
- **Estrategia de branching:** GitFlow con ramas `main`, `develop`, `feature/`, `release/`, `hotfix/`
- **Configuración:** Hooks pre-commit para Laravel Pint (code style)

#### GitHub
- **Tipo:** Plataforma de hosting de repositorios Git con CI/CD integrado
- **Características utilizadas:**
  - **GitHub Actions:** Pipelines de CI/CD para testing y despliegue automático
  - **GitHub Projects:** Gestión Scrum/Kanban con sprints
  - **Pull Requests:** Revisiones de código obligatorias
  - **Issues:** Tracking de bugs y features
  - **Releases:** Versionado semántico (SemVer)
  - **Dependabot:** Actualizaciones automáticas de dependencias

---

### 2. Editores y IDEs

#### Visual Studio Code
- **Versión:** Última estable (1.x)
- **Propósito:** Editor de código principal del equipo
- **Extensiones clave:**
  - PHP Intelephense (IntelliSense para PHP)
  - Laravel Extension Pack (snippets, blade syntax)
  - GitLens (visualización avanzada de Git)
  - Tailwind CSS IntelliSense (autocompletado de clases)
  - ESLint / Prettier (linting y formateo)

---

### 3. Gestores de Dependencias

#### Composer
- **Versión:** 2.x
- **Propósito:** Gestor de dependencias para PHP
- **Uso:** Instalación de Laravel, paquetes, autoload PSR-4
- **Scripts personalizados:**
  - `composer setup` → Instalación completa (install, .env, key:generate, migrate, npm install/build)
  - `composer dev` → Entorno de desarrollo concurrente (serve, queue, pail, vite)
  - `composer test` → Ejecución de tests (config:clear + test)

#### npm (Node Package Manager)
- **Versión:** 22.x (Node.js 22.x)
- **Propósito:** Gestor de dependencias para JavaScript/Frontend
- **Uso:** Instalación de Vite, Tailwind, Axios, compilación de assets
- **Scripts:**
  - `npm run dev` → Vite development server
  - `npm run build` → Compilación optimizada para producción

---

## Infraestructura y Despliegue

### 1. Servicios Cloud

#### Render
- **Tipo:** Plataforma de despliegue PaaS (Platform as a Service)
- **Propósito:** Hosting del backend Laravel (web service)
- **Características:**
  - Despliegue automático desde GitHub (push to deploy)
  - Buildpacks para PHP/Composer/npm
  - Variables de entorno seguras
  - Logs en tiempo real
  - Escalado automático
- **URL de producción:** https://sistema-asignacion-salones.onrender.com

#### Supabase
- **Tipo:** Backend as a Service (BaaS) con PostgreSQL gestionado
- **Propósito:** Base de datos PostgreSQL en producción
- **Características:**
  - PostgreSQL 15.x con extensiones (pgvector, pgjwt)
  - Backups automáticos
  - Connection pooling
  - Dashboard de administración
  - RESTful API automática (no utilizada, usamos Laravel Eloquent)

---

### 2. Entorno Local (Desarrollo)

#### Ubuntu / WSL (Windows Subsystem for Linux)
- **Versión:** Ubuntu 24.04 (Noble Numbat)
- **Propósito:** Sistema operativo para desarrollo en Windows
- **Ventajas:** Acceso a herramientas Unix, compatibilidad con scripts bash, integración con VS Code

#### Laravel Artisan Server
- **Comando:** `php artisan serve`
- **Propósito:** Servidor de desarrollo local (puerto 8000 por defecto)
- **Uso:** Testing rápido sin necesidad de nginx/Apache

---

## Testing y Calidad de Código

### 1. Frameworks de Testing

#### Pest PHP
- **Versión:** ^3.8
- **Propósito:** Framework de testing moderno para PHP (wrapper de PHPUnit)
- **Características:**
  - Sintaxis expresiva y legible (`test()->expect()->toBe()`)
  - Arquitectura por defecto
  - Plugins para Laravel (`pest-plugin-laravel ^3.2`)
  - Snapshots, datasets, coverage
- **Uso principal:** Tests de funcionalidad, feature tests

#### PHPUnit
- **Versión:** ^11.5.3
- **Propósito:** Framework de testing unitario para PHP (base de Pest)
- **Características:**
  - Assertions tradicionales
  - Mocking con Mockery
  - Test suites configurables
- **Resultados actuales:** 83 tests pasando (mix de Pest + PHPUnit)

#### Faker PHP
- **Versión:** ^1.23
- **Propósito:** Generación de datos falsos para testing y seeders
- **Uso:** Factories de modelos, datos de prueba realistas

---

### 2. Herramientas de Calidad

#### Laravel Pint
- **Versión:** ^1.24
- **Propósito:** Code style fixer para PHP (basado en PHP CS Fixer)
- **Características:**
  - Configuración PSR-12 por defecto
  - Corrección automática de estilo
  - Integración con pre-commit hooks
- **Comando:** `./vendor/bin/pint`

#### Laravel Pail
- **Versión:** ^1.2.2
- **Propósito:** Visualizador de logs en tiempo real para Laravel
- **Características:**
  - Coloreado de logs
  - Filtrado por nivel (info, error, debug)
  - Integración con `composer dev`
- **Comando:** `php artisan pail`

#### Mockery
- **Versión:** ^1.6
- **Propósito:** Framework de mocking para tests
- **Uso:** Simulación de dependencias en unit tests

#### Collision
- **Versión:** ^8.6
- **Propósito:** Error handler hermoso para CLI
- **Características:** Stack traces coloreados, debugging mejorado

---

## DevOps y CI/CD

### GitHub Actions
- **Propósito:** Automatización de CI/CD (Continuous Integration/Continuous Deployment)
- **Pipelines configurados:**
  - **Tests automáticos:** Ejecución de Pest/PHPUnit en cada push/PR
  - **Code style:** Verificación con Laravel Pint
  - **Deploy automático:** Push a `main` despliega a Render
- **Workflows:** `.github/workflows/laravel.yml`

### Scripts Composer Personalizados
```bash
composer setup   # Instalación completa del proyecto
composer dev     # Entorno de desarrollo concurrente
composer test    # Ejecución de tests
```

---

## Gestión de Proyecto

### 1. Metodología Ágil

#### Scrum/Kanban
- **Herramienta:** GitHub Projects
- **Artefactos:**
  - Sprint Planning (2 semanas)
  - Daily Standups (asíncronos vía GitHub)
  - Sprint Review/Retrospective
- **Board:** Columnas To Do, In Progress, In Review, Done

---

### 2. Documentación

#### Markdown
- **Archivos clave:**
  - `README.md` → Descripción general del proyecto
  - `GUIA_CONFIGURACION.md` → Instrucciones de instalación
  - `GUIA_SINCRONIZACION.md` → Sincronización con Supabase
  - `documentation/` → Diagramas técnicos (ERD, Modelo Relacional, Secuencias, etc.)

#### Draw.io
- **Propósito:** Creación de diagramas UML, ERD, flujos de datos
- **Integración:** Exportación a Mermaid para Markdown

---

## Resumen de Versiones

| Categoría | Herramienta | Versión | Propósito |
|-----------|-------------|---------|-----------|
| **Backend** | PHP | ^8.2 | Lenguaje del servidor |
| | Laravel Framework | ^12.0 | Framework MVC |
| | Doctrine DBAL | ^4.3 | Abstracción de BD |
| | Maatwebsite/Excel | ^1.1 | Importación/Exportación Excel |
| | Laravel Tinker | ^2.10.1 | REPL |
| **Frontend** | Vite | 7.0.7 | Bundling de assets |
| | Tailwind CSS | 4.0.0 | Framework CSS |
| | Axios | 1.11.0 | Cliente HTTP |
| | Concurrently | 9.0.1 | Ejecución concurrente |
| | Laravel Vite Plugin | ^2.0.0 | Integración Laravel-Vite |
| **Base de Datos** | PostgreSQL | 15.x | BD Producción (Supabase) |
| | SQLite | 3.x | BD Desarrollo/Testing |
| **Testing** | Pest PHP | ^3.8 | Framework de testing |
| | PHPUnit | ^11.5.3 | Testing unitario |
| | Faker PHP | ^1.23 | Generación de datos falsos |
| | Mockery | ^1.6 | Mocking |
| **Calidad** | Laravel Pint | ^1.24 | Code style fixer |
| | Laravel Pail | ^1.2.2 | Visualizador de logs |
| | Collision | ^8.6 | Error handler |
| **DevOps** | GitHub Actions | - | CI/CD |
| | Git | 2.x | Control de versiones |
| **Despliegue** | Render | - | Hosting web service |
| | Supabase | - | PostgreSQL gestionado |
| **Desarrollo** | Node.js | 22.x | Runtime JavaScript |
| | Composer | 2.x | Gestor dependencias PHP |
| | npm | 22.x | Gestor dependencias JS |
| | VS Code | 1.x | Editor de código |
| **Opcional** | Laravel Sail | ^1.41 | Wrapper Docker (no en uso activo) |

---

## Notas Adicionales

### Sistema Operativo
- **Recomendado:** Ubuntu 24.04 (Noble Numbat) o WSL en Windows
- **Alternativa:** macOS con Homebrew

### Arquitectura del Proyecto
- **Patrón:** MVC (Modelo-Vista-Controlador) con arquitectura modular
- **Módulos:** `app/Modules` para separación de responsabilidades
- **Roles implementados:** 8 roles distintos (desde `RoleSeeder.php`)
  - administrador
  - secretaria_administrativa
  - coordinador
  - secretaria_coordinacion
  - coordinador_infraestructura
  - secretaria_infraestructura
  - profesor
  - profesor_invitado

### Testing
- **Total de tests:** 83 tests pasando
- **Cobertura:** Feature tests + Unit tests
- **Frameworks:** Pest (sintaxis moderna) + PHPUnit (base)

### Despliegue
- **Estrategia:** Push to Deploy (GitHub → Render automático)
- **Entornos:**
  - **Desarrollo:** Local (SQLite, artisan serve)
  - **Producción:** Render (web) + Supabase (PostgreSQL)
- **Variables de entorno:** `.env` para configuración sensible (credenciales, API keys)

---

**Última actualización:** Diciembre 2024  
**Mantenedores:** Luis Carlos Pedraza, Johan Alejandro Rodríguez, Kevin Andrés Galeano, Katherin Acevedo
