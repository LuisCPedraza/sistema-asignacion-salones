# Análisis, Levantamiento de Requerimientos y Diseño

## 1. Introducción
El **Sistema de Asignación de Salones** es una aplicación web en Laravel que gestiona usuarios, grupos, salones, disponibilidades y genera asignaciones horarias semestrales. El proyecto usa Laravel con Blade, pruebas automatizadas (Pest/PHPUnit), despliegue en Render y base de datos Supabase (PostgreSQL) en producción; para desarrollo local usa SQLite por simplicidad.

Este documento resume el análisis y requerimientos alineados con el código y la documentación existente en `documentation/` (diagramas de casos de uso, ERD, clases y modelos relacionales incluidos en esa carpeta).

## 2. Alcance y stack actual
- **Backend**: Laravel (PHP 8.x), Blade, middleware de roles.
- **Base de datos**: PostgreSQL (Supabase) en producción; SQLite en local. No se usan particiones ni triggers avanzados; se emplean migraciones y seeders estándar.
- **Autenticación y roles**: Roles definidos en seeders y middleware (ej. admin, coordinador, profesor, perfiles relacionados a infraestructura/académico según módulos). No hay superadministrador ni particiones por rol en BD.
- **Despliegue**: Render Web Service con variables de entorno para Postgres (Supabase) y `APP_KEY` configurada.
- **Frontend**: Blade + Vite (assets con npm). No es una API REST desacoplada; las vistas se sirven desde Laravel.

## 3. Requerimientos funcionales (alineados con el código)
- Gestión de usuarios y autenticación: login/logout, roles, protección de rutas por middleware.
- Gestión de docentes y su disponibilidad: creación/edición, horarios disponibles.
- Gestión de aulas/salones y disponibilidades: registro de salones, atributos básicos, disponibilidad.
- Gestión de grupos y carreras/semestres/mallas: creación/edición y filtrado para construir horarios.
- Algoritmo de asignación: genera asignaciones respetando disponibilidad y tipo de horario (tests de unidad cubren reorganización, respeto de tipo de horario, marcas de asignado, notas con timestamp, manejo de vacíos).
- Asignación manual y visualización: vistas para horarios semestrales, filtros por carrera/semestre/grupo y bloques día/noche (cubierto por tests de feature de malla horaria).
- Configuración del sistema: parámetros básicos (config admin), sesiones, caché y colas (DB/local según entorno).
- Reportes/visualización: vistas de malla y dashboards según rol (tests de feature de redirección y accesos).

## 4. Requerimientos no funcionales
- **Seguridad**: hash de contraseñas (bcrypt), CSRF, validaciones de formularios, control de acceso por roles. Auditoría por triggers no está implementada.
- **Rendimiento**: sin optimizaciones de particionado; se espera operación fluida para volúmenes moderados típicos de un centro educativo. Índices y consultas pueden ajustarse en futuras iteraciones.
- **Usabilidad**: vistas Blade responsivas básicas; accesibilidad y UX pueden mejorarse iterativamente.
- **Compatibilidad**: navegadores modernos (Chrome/Firefox/Edge). Render usa HTTPS; en local se usa HTTP.
- **Mantenibilidad**: pruebas automatizadas (83 tests) cubren autenticación, roles, malla, asignación y entidades base. Estructura Laravel estándar con módulos y seeders.
- **Escalabilidad**: se puede migrar de SQLite local a PostgreSQL sin cambios de código; usar Supabase/Render para ambientes superiores.

## 5. Datos y entorno
- **Local (dev)**: `DB_CONNECTION=sqlite`, archivo `database/database.sqlite`; `SESSION_DRIVER=file`, `CACHE_DRIVER=file`; `APP_DEBUG=true`.
- **Producción (Render/Supabase)**: `DB_CONNECTION=pgsql`, host/puerto/usuario/clave de Supabase; `SESSION_DRIVER=database`, `CACHE_STORE=database`; `APP_DEBUG=false`; `APP_KEY` fija; `APP_URL` con dominio Render.

## 6. Priorización sugerida (iterativa)
1) Autenticación y roles (login, dashboards y redirecciones por rol).
2) Catálogos base: carreras, semestres, grupos, salones, docentes y disponibilidades.
3) Malla horaria (visualización y filtros) y asignación manual.
4) Algoritmo de asignación automática y ajustes de parámetros.
5) Reportes y mejoras de UX/validaciones.
6) Endurecer no funcionales: monitoreo, índices específicos, métricas de rendimiento, colas/cron para procesos pesados.

## 7. Diferencias contra el plan inicial
- Se elimina la referencia a MySQL con particiones/triggers; el stack real es PostgreSQL (Supabase) y SQLite en dev.
- No hay superadministrador ni todas las secretarías/académicos listados; los roles vigentes son los definidos en seeders y middleware (admin/coordinador/profesor y variantes que existan en el código).
- No hay vistas/triggers de auditoría ni particiones; las migraciones son estándar.
- El frontend no es una API REST desacoplada: se usa Blade con Vite para assets.
