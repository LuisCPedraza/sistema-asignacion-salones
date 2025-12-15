# 📚 Documentación del Sistema de Asignación de Salones

Bienvenido a la documentación completa del Sistema de Asignación de Salones.

## 🚀 Inicio Rápido

### Para Usuarios del Sistema

Si eres usuario del sistema y necesitas aprender a usarlo:

1. **Profesores**: [MANUAL_USUARIO_PROFESOR.md](./MANUAL_USUARIO_PROFESOR.md) - Actividades, calificaciones, reportes
2. **Coordinadores Académicos**: [MANUAL_USUARIO_COORDINADOR_ACADEMICO.md](./MANUAL_USUARIO_COORDINADOR_ACADEMICO.md) - Carreras, semestres, materias, grupos, estudiantes
3. **Coordinadores Infraestructura**: [MANUAL_USUARIO_INFRAESTRUCTURA.md](./MANUAL_USUARIO_INFRAESTRUCTURA.md) - Salones, mantenimiento, reservas
4. **Administradores**: [MANUAL_USUARIO_ADMINISTRADOR.md](./MANUAL_USUARIO_ADMINISTRADOR.md) - Usuarios, auditoría, configuración

### Para Ver las Últimas Actualizaciones

[RESUMEN_ACTUALIZACIONES.md](./RESUMEN_ACTUALIZACIONES.md) - Todas las mejoras implementadas en Nov-Dic 2025

### Para Crear los Pull Requests de Release 2.0.0

Si tu objetivo es crear los Pull Requests para integrar la versión 2.0.0:

1. **Lee primero**: [RESUMEN_EJECUTIVO_PRS.md](./RESUMEN_EJECUTIVO_PRS.md) - Panorama completo
2. **Sigue la guía**: [GUIA_CREACION_PRS.md](./GUIA_CREACION_PRS.md) - Instrucciones paso a paso
3. **Usa las plantillas**:
   - [PR_Release_2.0.0_to_Develop.md](./PR_Release_2.0.0_to_Develop.md) - Para PR #1
   - [PR_Develop_to_Main.md](./PR_Develop_to_Main.md) - Para PR #2

### Para Navegar la Documentación Completa

Consulta el [INDICE_DOCUMENTACION.md](./INDICE_DOCUMENTACION.md) para acceder a toda la documentación organizada por tema.

## 📋 Estructura de Documentación

### 📖 Manuales de Usuario

| Manual | Audiencia | Contenido |
|--------|-----------|-----------|
| **MANUAL_USUARIO_PROFESOR.md** | 🎓 Profesores | Actividades, calificaciones, reportes, FAQ |
| **MANUAL_USUARIO_COORDINADOR_ACADEMICO.md** | 👨‍🎓 Coordinadores Académicos | Carreras, semestres, materias, grupos, estudiantes, reportes |
| **MANUAL_USUARIO_INFRAESTRUCTURA.md** | 🏢 Coordinadores Infraestructura | Salones, mantenimiento, reservas, reportes |
| **MANUAL_USUARIO_ADMINISTRADOR.md** | 👨‍💼 Administradores | Usuarios, auditoría, reportes, configuración, backups |
| **RESUMEN_ACTUALIZACIONES.md** | 👥 Todos | Resumen de mejoras Nov-Dic 2025 |

### 🎯 Documentación de Release 2.0.0

| Documento | Propósito | Audiencia |
|-----------|-----------|-----------|
| **RESUMEN_EJECUTIVO_PRS.md** | Visión general del proceso de integración | Product Owner, Scrum Master, Equipo |
| **GUIA_CREACION_PRS.md** | Instrucciones detalladas para crear PRs | Desarrollador que creará los PRs |
| **PR_Release_2.0.0_to_Develop.md** | Plantilla completa para PR #1 | Copy-paste al crear PR #1 |
| **PR_Develop_to_Main.md** | Plantilla completa para PR #2 | Copy-paste al crear PR #2 |
| **INDICE_DOCUMENTACION.md** | Índice maestro de toda la documentación | Todos |

### 📐 Documentación de Análisis y Diseño

| Documento | Contenido |
|-----------|-----------|
| **Analisis Levantamiento de Requerimientos.md** | Requerimientos funcionales, no funcionales, backlog completo |
| **EstrategiaDeBranching.md** | Git Flow, estructura de ramas, fases de desarrollo |
| **DiagramaCasosDeUsoGeneral.md** | Casos de uso por rol y épica |
| **DiagramaSecuenciaCasosDeUso.md** | Secuencias de interacción por rol |
| **DiagramaDeClases.md** | Estructura de clases y relaciones |
| **DiagramaFlujoDatos.md** | Flujo de datos del sistema (DFD) |

### 💾 Documentación de Base de Datos

| Documento | Contenido |
|-----------|-----------|
| **DiagramaEntidadRelacion.md** | Modelo conceptual (ERD) |
| **DiagramaModeloRelacional.md** | Modelo lógico con tablas y relaciones |
| **DiagramaModeloFisico.md** | Modelo físico con optimizaciones |
| **BasesDeDatos.md** | Documentación completa de BD |

### 📖 Documentación de Épicas

| Documento | Contenido |
|-----------|-----------|
| **HU3.md** | Historia de Usuario 3: Registro de Grupos |
| **HU4.md** | Historia de Usuario 4: Edición de Grupos |
| **epica2/** | Carpeta con documentación de Épica 2 |

### 🔧 Documentación Técnica

| Documento | Contenido |
|-----------|-----------|
| **github-actions-laravel.md** | Guía de CI/CD con GitHub Actions |
| **ProgramasHerraminetas.md** | Herramientas y programas del proyecto |

## 🎯 Por Caso de Uso

### Quiero crear los Pull Requests para Release 2.0.0

**Ruta sugerida**:
1. [RESUMEN_EJECUTIVO_PRS.md](./RESUMEN_EJECUTIVO_PRS.md)
2. [GUIA_CREACION_PRS.md](./GUIA_CREACION_PRS.md)
3. [PR_Release_2.0.0_to_Develop.md](./PR_Release_2.0.0_to_Develop.md)
4. [PR_Develop_to_Main.md](./PR_Develop_to_Main.md)

### Quiero entender la arquitectura del sistema

**Ruta sugerida**:
1. [Analisis Levantamiento de Requerimientos.md](./Analisis%20Levantamiento%20de%20Requerimientos.md)
2. [DiagramaDeClases.md](./DiagramaDeClases.md)
3. [DiagramaFlujoDatos.md](./DiagramaFlujoDatos.md)
4. [EstrategiaDeBranching.md](./EstrategiaDeBranching.md)

### Quiero entender la base de datos

**Ruta sugerida**:
1. [DiagramaEntidadRelacion.md](./DiagramaEntidadRelacion.md)
2. [DiagramaModeloRelacional.md](./DiagramaModeloRelacional.md)
3. [DiagramaModeloFisico.md](./DiagramaModeloFisico.md)
4. [BasesDeDatos.md](./BasesDeDatos.md)

### Quiero entender las funcionalidades por rol

**Ruta sugerida**:
1. [RESUMEN_EJECUTIVO_PRS.md](./RESUMEN_EJECUTIVO_PRS.md) - Sección "Progresión por Roles"
2. [PR_Develop_to_Main.md](./PR_Develop_to_Main.md) - Sección "Progresión de Funcionalidades por Rol"
3. [DiagramaCasosDeUsoGeneral.md](./DiagramaCasosDeUsoGeneral.md)

### Quiero entender el proceso de desarrollo

**Ruta sugerida**:
1. [EstrategiaDeBranching.md](./EstrategiaDeBranching.md)
2. [github-actions-laravel.md](./github-actions-laravel.md)
3. [Analisis Levantamiento de Requerimientos.md](./Analisis%20Levantamiento%20de%20Requerimientos.md)

## 📊 Estado del Proyecto

### Release 2.0.0

| Aspecto | Estado | Documento |
|---------|--------|-----------|
| **Épicas 1-10** | ✅ Completas | [PR_Develop_to_Main.md](./PR_Develop_to_Main.md) |
| **Infraestructura DevOps** | ✅ Implementada | [PR_Release_2.0.0_to_Develop.md](./PR_Release_2.0.0_to_Develop.md) |
| **Documentación** | ✅ Completa | Este directorio |
| **Tests** | ✅ Pasando | [github-actions-laravel.md](./github-actions-laravel.md) |
| **CI/CD** | ✅ Funcional | [github-actions-laravel.md](./github-actions-laravel.md) |
| **PR #1 (release → develop)** | ⏳ Por crear | [GUIA_CREACION_PRS.md](./GUIA_CREACION_PRS.md) |
| **PR #2 (develop → main)** | ⏳ Por crear | [GUIA_CREACION_PRS.md](./GUIA_CREACION_PRS.md) |
| **Despliegue a Producción** | ⏳ Pendiente | [PR_Develop_to_Main.md](./PR_Develop_to_Main.md) |

## 🏗️ Arquitectura Modular

El sistema implementa una arquitectura modular completa:

- ✅ **Autenticación y Usuarios** (Épica 1)
- ✅ **Gestión de Recursos** (Épicas 2, 3, 4)
- ✅ **Asignaciones** (Épicas 5, 6)
- ✅ **Visualización y Reportes** (Épica 7)
- ✅ **Restricciones y Conflictos** (Épica 8)
- ✅ **Auditoría e Historial** (Épica 9)
- ✅ **Configuración** (Épica 10)

Detalles completos en: [PR_Develop_to_Main.md](./PR_Develop_to_Main.md)

## 👥 Progresión por Roles

El desarrollo siguió un enfoque progresivo:

1. **Fase 1: Rol Profesor** ✅
   - Funcionalidades base de consulta
   - Visualización de horarios personales

2. **Fase 2: Rol Coordinador** ✅
   - Gestión completa de recursos
   - Asignaciones automáticas y manuales
   - Reportes y configuración

3. **Fase 3: Roles Especializados** ✅
   - Coordinador Académico
   - Coordinador de Infraestructura
   - Secretarias especializadas
   - Administradores

Detalles completos en: [RESUMEN_EJECUTIVO_PRS.md](./RESUMEN_EJECUTIVO_PRS.md)

## 📖 Metodologías Aplicadas

### Scrum con Kanban
- Sprints de 2 semanas
- Daily Scrum (8:00 AM Colombia)
- Definition of Ready (DoR)
- Definition of Done (DoD)

### DevOps
- Integración Continua (CI) con GitHub Actions
- Despliegue Continuo (CD) en Render
- Infraestructura como Código (Docker)

### TDD (Test-Driven Development)
- Tests automatizados con Pest
- Cobertura de código
- Pre-commit hooks

Detalles en: [Analisis Levantamiento de Requerimientos.md](./Analisis%20Levantamiento%20de%20Requerimientos.md)

## 🔗 Enlaces Importantes

### Repositorio
- **GitHub**: [LuisCPedraza/sistema-asignacion-salones](https://github.com/LuisCPedraza/sistema-asignacion-salones)

### Diagramas Completos
- **Draw.io**: [Enlace a Diagramas](https://drive.google.com/file/d/15zuAVwyVuvfje4TfutLYILP8fPk8Fikk/view?usp=sharing)

### Documento Principal
- **README**: [../README.md](../README.md)

## 👨‍💻 Equipo

- **Product Owner**: Luis Carlos Pedraza
- **Scrum Master**: Luis Carlos Pedraza
- **Development Team**:
  - Luis Carlos Pedraza
  - Johan Alejandro Rodríguez
  - Kevin Andrés Galeano
  - Katherin Acevedo

## 🎯 Próximos Pasos

1. ✅ Crear PR #1: release/2.0.0 → develop
2. ⏳ Review y merge de PR #1
3. ⏳ Crear PR #2: develop → main
4. ⏳ Review exhaustivo de PR #2
5. ⏳ Despliegue a producción

Sigue la guía: [GUIA_CREACION_PRS.md](./GUIA_CREACION_PRS.md)

## 📞 Soporte

Para dudas o asistencia:
- Daily Scrum (8:00 AM Colombia)
- Comentarios en GitHub Issues/PRs
- Contacto directo con el Product Owner

---

**Versión del Sistema**: 2.0.0  
**Última actualización de la documentación**: Diciembre 2025  
**Mantenido por**: Equipo de Desarrollo del Sistema de Asignación de Salones
