# Guía para Crear los Pull Requests en GitHub

Este documento proporciona instrucciones paso a paso para crear los Pull Requests necesarios para integrar los cambios de `release/2.0.0` → `develop` → `main`.

## 📋 Prerrequisitos

Antes de crear los Pull Requests, asegúrate de:

- [x] Tener acceso de escritura al repositorio
- [x] Estar familiarizado con la estrategia de branching del proyecto
- [x] Haber revisado los documentos de descripción de PRs:
  - `documentation/PR_Release_2.0.0_to_Develop.md`
  - `documentation/PR_Develop_to_Main.md`

## 🔄 Orden de Creación de PRs

**IMPORTANTE**: Los PRs deben crearse y mergearse en este orden específico:

1. **PRIMERO**: `release/2.0.0` → `develop`
2. **SEGUNDO** (después de mergear el primero): `develop` → `main`

## 📝 PR #1: release/2.0.0 → develop

### Paso 1: Acceder a GitHub

1. Abre tu navegador y ve a: `https://github.com/LuisCPedraza/sistema-asignacion-salones`
2. Inicia sesión si no lo has hecho

### Paso 2: Crear el Pull Request

1. Haz clic en la pestaña **"Pull requests"**
2. Haz clic en el botón verde **"New pull request"**
3. Configura las ramas:
   - **base**: `develop` (rama destino)
   - **compare**: `release/2.0.0` (rama origen)
4. GitHub mostrará los cambios que se incluirán
5. Haz clic en **"Create pull request"**

### Paso 3: Completar el Formulario del PR

1. **Título del PR**:
   ```
   Release 2.0.0: Integración de infraestructura y CI/CD en develop
   ```

2. **Descripción del PR**:
   - Abre el archivo `documentation/PR_Release_2.0.0_to_Develop.md`
   - Copia todo el contenido
   - Pégalo en el campo de descripción del PR

3. **Opciones adicionales**:
   - Asigna reviewers (miembros del equipo)
   - Añade labels apropiados: `release`, `infrastructure`, `ci-cd`
   - Asocia con el Project apropiado si existe
   - Vincula con Milestone `Release 2.0.0` si existe

### Paso 4: Finalizar la Creación

1. Revisa que toda la información esté correcta
2. Haz clic en **"Create pull request"**

### Paso 5: Proceso de Review

1. Espera la revisión del equipo
2. Atiende comentarios si los hay
3. Una vez aprobado, haz **merge** del PR

### Paso 6: Verificar el Merge

1. Confirma que el merge se completó exitosamente
2. Verifica que `develop` ahora contiene los commits de `release/2.0.0`
3. Ejecuta los tests en `develop` para confirmar que todo funciona

## 🚀 PR #2: develop → main

### ⚠️ IMPORTANTE

**NO CREAR ESTE PR HASTA QUE EL PR #1 ESTÉ MERGEADO Y VERIFICADO**

### Paso 1: Verificar que PR #1 está Mergeado

1. Confirma que el PR `release/2.0.0` → `develop` fue mergeado
2. Verifica que los tests en `develop` pasan correctamente
3. Confirma que no hay conflictos pendientes

### Paso 2: Crear el Pull Request

1. Ve a: `https://github.com/LuisCPedraza/sistema-asignacion-salones`
2. Haz clic en **"Pull requests"**
3. Haz clic en **"New pull request"**
4. Configura las ramas:
   - **base**: `main` (rama destino - PRODUCCIÓN)
   - **compare**: `develop` (rama origen)
5. GitHub mostrará todos los cambios acumulados
6. Haz clic en **"Create pull request"**

### Paso 3: Completar el Formulario del PR

1. **Título del PR**:
   ```
   Release 2.0.0: Publicación completa del Sistema de Asignación de Salones (Épicas 1-10)
   ```

2. **Descripción del PR**:
   - Abre el archivo `documentation/PR_Develop_to_Main.md`
   - Copia todo el contenido
   - Pégalo en el campo de descripción del PR

3. **Opciones adicionales**:
   - Asigna reviewers (TODO EL EQUIPO debe revisar este PR)
   - Añade labels: `release`, `production`, `v2.0.0`
   - Asocia con el Project principal
   - Vincula con Milestone `Release 2.0.0`
   - Marca como **"Ready for review"**

### Paso 4: Review Exhaustivo

**ESTE PR VA A PRODUCCIÓN - REQUIERE REVIEW CUIDADOSO**

1. Todos los miembros del equipo deben revisar
2. Verificar:
   - ✅ Todos los tests pasan
   - ✅ No hay conflictos
   - ✅ La documentación está actualizada
   - ✅ Los criterios de aceptación se cumplen
   - ✅ No hay credenciales o datos sensibles
   - ✅ El código sigue las convenciones del proyecto

### Paso 5: Aprobación del Product Owner

1. El **Product Owner (Luis Carlos Pedraza)** debe dar aprobación final
2. Verificar que cumple con la visión del producto
3. Confirmar que todas las funcionalidades están completas

### Paso 6: Merge a Producción

1. Una vez todas las aprobaciones estén completas
2. Hacer **merge** del PR (preferiblemente "Merge commit" para mantener historial)
3. **NO ELIMINAR** la rama `develop` después del merge

### Paso 7: Post-Merge (Despliegue)

1. Confirma que el merge a `main` se completó
2. Verifica que el despliegue automático a Render se ejecuta (si está configurado)
3. Monitorea el health check endpoint
4. Verifica que la aplicación funciona en producción
5. Comunica al equipo que el despliegue está completo

## 📊 Checklist General de PRs

### Antes de Crear el PR

- [ ] Las ramas están actualizadas
- [ ] Los tests pasan localmente
- [ ] El código está linted
- [ ] La documentación está actualizada
- [ ] No hay conflictos de merge

### Al Crear el PR

- [ ] Título descriptivo y claro
- [ ] Descripción completa usando las plantillas proporcionadas
- [ ] Reviewers asignados
- [ ] Labels apropiados
- [ ] Vinculado a Project/Milestone

### Durante el Review

- [ ] Atender comentarios de reviewers
- [ ] Resolver conflictos si aparecen
- [ ] Actualizar descripción si hay cambios significativos
- [ ] Mantener comunicación con el equipo

### Antes del Merge

- [ ] Todas las aprobaciones recibidas
- [ ] Tests de CI/CD pasando
- [ ] No hay conflictos pendientes
- [ ] Product Owner ha aprobado (para PR a main)

### Después del Merge

- [ ] Verificar que el merge fue exitoso
- [ ] Confirmar que los cambios están en la rama destino
- [ ] Verificar que el despliegue funciona (si aplica)
- [ ] Actualizar tablero del proyecto

## 🛠️ Comandos Git Útiles (Referencia)

Aunque los PRs se crean en la interfaz web de GitHub, estos comandos pueden ser útiles para verificación:

### Verificar el estado de las ramas

```bash
# Ver todas las ramas
git branch -a

# Ver commits en release/2.0.0 no en develop
git log --oneline origin/develop..origin/release/2.0.0

# Ver commits en develop no en main
git log --oneline origin/main..origin/develop
```

### Actualizar ramas locales

```bash
# Actualizar todas las referencias remotas
git fetch --all

# Cambiar a develop y actualizar
git checkout develop
git pull origin develop

# Cambiar a main y actualizar
git checkout main
git pull origin main
```

### Verificar diferencias

```bash
# Ver archivos cambiados entre release/2.0.0 y develop
git diff --name-only origin/develop...origin/release/2.0.0

# Ver archivos cambiados entre develop y main
git diff --name-only origin/main...origin/develop
```

## ⚠️ Resolución de Problemas

### Conflictos de Merge

Si encuentras conflictos:

1. **Para PR #1 (release/2.0.0 → develop)**:
   - Los conflictos son poco probables ya que `release/2.0.0` contiene principalmente cambios de infraestructura
   - Si hay conflictos, prioriza mantener las funcionalidades de `develop`
   - Consulta con el equipo para resolver conflictos no triviales

2. **Para PR #2 (develop → main)**:
   - Los conflictos aquí son más probables si ha habido cambios directos en `main`
   - Resuelve favoreciendo los cambios de `develop` (es la rama más actualizada)
   - Realiza testing exhaustivo después de resolver conflictos

### Tests Fallando en CI

Si los tests fallan:

1. Revisa los logs de GitHub Actions
2. Reproduce el error localmente
3. Corrige el problema en la rama origen
4. Push los cambios - el PR se actualizará automáticamente

### Problemas de Permisos

Si no puedes crear PRs:

1. Verifica que tienes permisos de escritura en el repositorio
2. Contacta al administrador del repositorio (Luis Carlos Pedraza)
3. Verifica que estás autenticado correctamente en GitHub

## 📞 Contacto y Soporte

Si tienes dudas durante el proceso:

- **Product Owner**: Luis Carlos Pedraza
- **Scrum Master**: Luis Carlos Pedraza
- **Canal de Comunicación**: Daily Scrum (8:00 AM Colombia) o comentarios en GitHub

## 📚 Referencias

- [Estrategia de Branching](./EstrategiaDeBranching.md)
- [Descripción PR Release → Develop](./PR_Release_2.0.0_to_Develop.md)
- [Descripción PR Develop → Main](./PR_Develop_to_Main.md)
- [README Principal](../README.md)

---

**Nota**: Esta guía es parte del proceso DevOps del proyecto y sigue las metodologías ágiles (Scrum con Kanban) establecidas por el equipo.

**Última actualización**: Diciembre 2025
