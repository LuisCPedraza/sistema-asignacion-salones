# Índice de Documentación - Release 2.0.0

Este documento sirve como índice central para toda la documentación relacionada con el Release 2.0.0 del Sistema de Asignación de Salones.

## 🚀 Documentos de Pull Requests

### Documentos Principales

| Documento | Descripción | Uso |
|-----------|-------------|-----|
| [RESUMEN_EJECUTIVO_PRS.md](./RESUMEN_EJECUTIVO_PRS.md) | Resumen ejecutivo del proceso completo de integración | **Leer primero** para entender el panorama completo |
| [GUIA_CREACION_PRS.md](./GUIA_CREACION_PRS.md) | Guía paso a paso para crear los PRs en GitHub | **Consultar** al momento de crear los PRs |
| [PR_Release_2.0.0_to_Develop.md](./PR_Release_2.0.0_to_Develop.md) | Descripción completa para PR #1 (release/2.0.0 → develop) | **Copiar** al crear PR #1 |
| [PR_Develop_to_Main.md](./PR_Develop_to_Main.md) | Descripción completa para PR #2 (develop → main) | **Copiar** al crear PR #2 |

### Orden de Lectura Recomendado

1. 📖 **RESUMEN_EJECUTIVO_PRS.md** - Para entender el contexto completo
2. 📝 **GUIA_CREACION_PRS.md** - Para conocer el proceso
3. 📋 **PR_Release_2.0.0_to_Develop.md** - Para crear el primer PR
4. 📋 **PR_Develop_to_Main.md** - Para crear el segundo PR

## 📚 Documentación de Análisis y Diseño

### Visión del Producto

| Documento | Descripción |
|-----------|-------------|
| [Analisis Levantamiento de Requerimientos.md](./Analisis%20Levantamiento%20de%20Requerimientos.md) | Análisis completo, requerimientos funcionales y no funcionales, backlog |
| [EstrategiaDeBranching.md](./EstrategiaDeBranching.md) | Estrategia de Git Flow, estructura de ramas, fases de desarrollo |

### Diagramas de Casos de Uso y Secuencia

| Documento | Descripción |
|-----------|-------------|
| [DiagramaCasosDeUsoGeneral.md](./DiagramaCasosDeUsoGeneral.md) | Casos de uso por rol con épicas |
| [DiagramaSecuenciaCasosDeUso.md](./DiagramaSecuenciaCasosDeUso.md) | Diagramas de secuencia por rol con guards |

### Diagramas de Arquitectura

| Documento | Descripción |
|-----------|-------------|
| [DiagramaDeClases.md](./DiagramaDeClases.md) | Diagrama de clases con herencia y métodos |
| [DiagramaFlujoDatos.md](./DiagramaFlujoDatos.md) | DFD niveles 0 y 1 con subgraphs por épica |

### Diagramas de Base de Datos

| Documento | Descripción |
|-----------|-------------|
| [DiagramaEntidadRelacion.md](./DiagramaEntidadRelacion.md) | ERD conceptual con Crow's Foot notation |
| [DiagramaModeloRelacional.md](./DiagramaModeloRelacional.md) | Modelo relacional con tablas, FK, guards y vistas |
| [DiagramaModeloFisico.md](./DiagramaModeloFisico.md) | Modelo físico con ENGINE, particiones, índices |
| [BasesDeDatos.md](./BasesDeDatos.md) | Documentación completa de base de datos |

### Documentación de Épicas

| Documento | Descripción |
|-----------|-------------|
| [HU3.md](./HU3.md) | Historia de Usuario 3: Registro de Grupos |
| [HU4.md](./HU4.md) | Historia de Usuario 4: Edición de Grupos |
| [epica2/](./epica2/) | Directorio de documentación de Épica 2 |

### Documentación Técnica

| Documento | Descripción |
|-----------|-------------|
| [github-actions-laravel.md](./github-actions-laravel.md) | Guía de GitHub Actions para Laravel |
| [ProgramasHerraminetas.md](./ProgramasHerraminetas.md) | Programas y herramientas del proyecto |

## 🔍 Navegación Rápida por Tema

### Para Crear los Pull Requests
1. [GUIA_CREACION_PRS.md](./GUIA_CREACION_PRS.md) - Instrucciones paso a paso
2. [PR_Release_2.0.0_to_Develop.md](./PR_Release_2.0.0_to_Develop.md) - Descripción PR #1
3. [PR_Develop_to_Main.md](./PR_Develop_to_Main.md) - Descripción PR #2

### Para Entender la Arquitectura
1. [DiagramaDeClases.md](./DiagramaDeClases.md) - Estructura de clases
2. [DiagramaFlujoDatos.md](./DiagramaFlujoDatos.md) - Flujo de datos
3. [Analisis Levantamiento de Requerimientos.md](./Analisis%20Levantamiento%20de%20Requerimientos.md) - Sección 2.1 (Módulos)

### Para Entender la Progresión por Rol
1. [RESUMEN_EJECUTIVO_PRS.md](./RESUMEN_EJECUTIVO_PRS.md) - Sección "Progresión por Roles"
2. [PR_Develop_to_Main.md](./PR_Develop_to_Main.md) - Sección "Progresión de Funcionalidades por Rol"
3. [DiagramaCasosDeUsoGeneral.md](./DiagramaCasosDeUsoGeneral.md) - Casos de uso por rol

### Para Entender la Base de Datos
1. [DiagramaEntidadRelacion.md](./DiagramaEntidadRelacion.md) - Modelo conceptual
2. [DiagramaModeloRelacional.md](./DiagramaModeloRelacional.md) - Modelo lógico
3. [DiagramaModeloFisico.md](./DiagramaModeloFisico.md) - Modelo físico con optimizaciones
4. [BasesDeDatos.md](./BasesDeDatos.md) - Documentación completa

### Para Entender el Desarrollo
1. [EstrategiaDeBranching.md](./EstrategiaDeBranching.md) - Estrategia de ramas
2. [github-actions-laravel.md](./github-actions-laravel.md) - CI/CD con GitHub Actions
3. [Analisis Levantamiento de Requerimientos.md](./Analisis%20Levantamiento%20de%20Requerimientos.md) - Backlog y épicas

## 📋 Checklist de Documentación

### Antes de Crear PRs
- [ ] Leer [RESUMEN_EJECUTIVO_PRS.md](./RESUMEN_EJECUTIVO_PRS.md)
- [ ] Leer [GUIA_CREACION_PRS.md](./GUIA_CREACION_PRS.md)
- [ ] Revisar [EstrategiaDeBranching.md](./EstrategiaDeBranching.md)
- [ ] Confirmar entendimiento de arquitectura modular

### Al Crear PR #1 (release/2.0.0 → develop)
- [ ] Copiar contenido de [PR_Release_2.0.0_to_Develop.md](./PR_Release_2.0.0_to_Develop.md)
- [ ] Seguir pasos en [GUIA_CREACION_PRS.md](./GUIA_CREACION_PRS.md) - Sección PR #1
- [ ] Asignar reviewers
- [ ] Añadir labels apropiados

### Al Crear PR #2 (develop → main)
- [ ] Confirmar que PR #1 está mergeado
- [ ] Copiar contenido de [PR_Develop_to_Main.md](./PR_Develop_to_Main.md)
- [ ] Seguir pasos en [GUIA_CREACION_PRS.md](./GUIA_CREACION_PRS.md) - Sección PR #2
- [ ] Asegurar review de TODO el equipo
- [ ] Obtener aprobación del Product Owner

## 🔗 Enlaces Externos

### Repositorio
- [GitHub: LuisCPedraza/sistema-asignacion-salones](https://github.com/LuisCPedraza/sistema-asignacion-salones)

### Diagramas (Draw.io)
- [Enlace a Diagramas Completos](https://drive.google.com/file/d/15zuAVwyVuvfje4TfutLYILP8fPk8Fikk/view?usp=sharing)

## 📞 Contacto

- **Product Owner**: Luis Carlos Pedraza
- **Scrum Master**: Luis Carlos Pedraza
- **Development Team**: Luis, Johan, Kevin, Katherin

## 🎯 Próximos Pasos

1. **Inmediato**: Crear PR #1 (release/2.0.0 → develop)
2. **Corto plazo**: Review y merge de PR #1
3. **Corto plazo**: Crear PR #2 (develop → main)
4. **Mediano plazo**: Review exhaustivo de PR #2
5. **Mediano plazo**: Despliegue a producción

## 📊 Estado del Release

| Aspecto | Estado |
|---------|--------|
| **Desarrollo** | ✅ Completo (10/10 épicas) |
| **Documentación** | ✅ Completa |
| **Tests** | ✅ Pasando |
| **CI/CD** | ✅ Funcional |
| **PR #1** | ⏳ Por crear |
| **PR #2** | ⏳ Por crear |
| **Despliegue** | ⏳ Pendiente |

---

**Última actualización**: Diciembre 2025  
**Versión**: 2.0.0  
**Mantenido por**: Equipo de Desarrollo
