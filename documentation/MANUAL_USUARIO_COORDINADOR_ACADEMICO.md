# 👨‍🎓 Manual de Usuario - Coordinador Académico

**Sistema de Asignación de Salones**  
**Versión 2.1** | Diciembre 2025

---

## 📋 Índice

1. [Introducción](#introducción)
2. [Acceso al Sistema](#acceso-al-sistema)
3. [Panel Principal (Dashboard)](#panel-principal-dashboard)
4. [Gestión de Estructura Académica](#gestión-de-estructura-académica)
   - 4.1 [Gestión de Carreras](#gestión-de-carreras)
   - 4.2 [Gestión de Semestres](#gestión-de-semestres)
   - 4.3 [Gestión de Materias](#gestión-de-materias)
5. [Gestión de Grupos de Estudiantes](#gestión-de-grupos-de-estudiantes)
6. [Gestión de Profesores](#gestión-de-profesores)
7. [Sistema de Asignación Inteligente](#sistema-de-asignación-inteligente)
8. [Visualización de Horarios](#visualización-de-horarios)
9. [Reportes Académicos](#reportes-académicos)

---

## Introducción

### ¿Qué es el Módulo de Coordinación Académica?

El módulo de coordinación académica te permite gestionar la estructura y operación de tus programas académicos. Como **Coordinador Académico**, podrás:

- ✅ Administrar grupos de estudiantes
- ✅ Gestionar profesores y asignaciones
- ✅ Usar asignación automática e inteligente
- ✅ Gestionar conflictos horarios
- ✅ Visualizar horarios consolidados
- ✅ Generar reportes académicos
- ✅ Configurar reglas de asignación

### Requisitos Previos

- **Navegador**: Chrome, Firefox, Safari o Edge (actualizado)
- **Credenciales**: Usuario y contraseña con rol de Coordinador Académico
- **Permisos**: Acceso completo al módulo académico

---

## Acceso al Sistema

### Inicio de Sesión

1. Accede a la URL del sistema
2. Ingresa tus credenciales:
   ```
   📧 Email: coordinador@universidad.edu
   🔑 Contraseña: [tu contraseña]
   ```
3. Haz clic en **"Iniciar Sesión"**
4. Serás redirigido al **Dashboard Académico**

---

## Panel Principal (Dashboard)

### Vista General

El dashboard muestra un resumen rápido de tu institución y acceso a todas las herramientas de coordinación:

#### 📊 Estadísticas Principales

Cuatro métricas clave en tarjetas:

| Tarjeta | Descripción |
|---------|-------------|
| **Grupos de Estudiantes** | Total de grupos registrados |
| **Profesores Registrados** | Total de docentes en el sistema |
| **Grupos Activos** | Grupos actualmente en operación |
| **Profesores Activos** | Profesores con asignaciones activas |

#### 🎯 Módulos Principales

El dashboard ofrece acceso directo a 5 módulos:

**1. 🎓 Gestión de Grupos**
- Administra grupos de estudiantes
- Gestiona niveles académicos
- Configura características especiales
- Define períodos académicos
- **Botón**: *Gestionar Grupos*

**2. 👨‍🏫 Gestión de Profesores**
- Información completa de docentes
- Especialidades académicas
- Hojas de vida
- Disponibilidades horarias
- **Botón**: *Gestionar Profesores*

**3. 🤖 Sistema de Asignación Inteligente**
- Asignaciones automáticas optimizadas
- Gestión manual con arrastrar y soltar
- Configuración de reglas
- Detección de conflictos en tiempo real

   **4 opciones rápidas**:
   - **🔄 Asignación Automática**: Algoritmo inteligente para asignaciones
   - **👆 Asignación Manual**: Interfaz de arrastrar y soltar
   - **⚙️ Configurar Reglas**: Establecer prioridades y restricciones
   - **⚠️ Detectar Conflictos**: Identificar solapamientos horarios

**4. 📊 Visualización de Horarios**
- Vistas consolidadas para coordinadores
- Horarios personalizados para profesores

   **2 opciones**:
   - **📊 Semestral Completo**: Vista consolidada para coordinadores (todos los horarios)
   - **📅 Personalizado**: Horario individual para cada profesor

**5. 📈 Reportes Académicos**
- Reportes detallados de grupos
- Análisis de profesores asignados
- Estadísticas completas del departamento
- **Botón**: *Ver Reportes*

### Navegación Lateral

La barra lateral izquierda ofrece acceso rápido a:
- **📊 Dashboard**: Panel principal (actual)
- **🎓 Grupos de Estudiantes**: Gestión de grupos
- **👨‍🏫 Gestión de Profesores**: Administración de docentes
- **🤖 Asignación Inteligente**: Sistema de asignaciones
- **📊 Visualización Horarios**: Vistas de horarios
- **📈 Reportes Académicos**: Análisis y reportes

---

## Gestión de Estructura Académica

### Introducción a la Estructura Académica

Antes de crear grupos de estudiantes y asignar profesores, es fundamental configurar la **estructura académica** de tu institución. Esta estructura define la jerarquía:

```
Carrera (Ej: Ingeniería en Sistemas)
  └── Semestre (Ej: Primer Semestre)
      └── Materias (Ej: Programación I, Matemáticas I)
          └── Grupos de Estudiantes (Ej: Grupo A, Grupo B)
```

**⚠️ IMPORTANTE**: Actualmente, la gestión de Carreras, Semestres y Materias **no cuenta con interfaz web completa**. Estas entidades se gestionan mediante:
- Seeders (datos iniciales en el sistema)
- APIs disponibles
- **Funcionalidad pendiente de implementación en interfaz web**

Esta sección documenta cómo **debería funcionar** esta gestión cuando la interfaz esté completa.

---

### Gestión de Carreras

#### ¿Qué es una Carrera?

Una **Carrera** representa un programa académico completo (Ej: Ingeniería en Sistemas, Medicina, Arquitectura). Cada carrera contiene múltiples semestres y define la estructura curricular.

#### Vista de Listado (Funcionalidad Planificada)

**📊 Estadísticas de Carreras**

| Métrica | Descripción |
|---------|-------------|
| **Total Carreras** | Cantidad de programas académicos |
| **Carreras Activas** | Programas con estudiantes inscritos |
| **Total Semestres** | Suma de semestres en todas las carreras |
| **Total Grupos** | Grupos asociados a las carreras |

**📋 Tabla de Carreras**

| Columna | Contenido |
|---------|-----------|
| **Código** | ID único (Ej: ING-SIS) |
| **Nombre** | Nombre completo de la carrera |
| **Semestres** | Cantidad de semestres académicos |
| **Grupos** | Total de grupos activos |
| **Estado** | Activo/Inactivo |
| **Acciones** | Ver, Editar, Eliminar |

#### Crear Nueva Carrera (Funcionalidad Planificada)

1. **Haz clic** en **"➕ Nueva Carrera"**

2. **Información Requerida**:

   | Campo | Descripción | Ejemplo |
   |-------|-------------|---------|
   | **Código*** | Identificador único | ING-SIS |
   | **Nombre*** | Nombre completo | Ingeniería en Sistemas |
   | **Descripción** | Descripción breve | Programa enfocado en desarrollo de software... |
   | **Duración (semestres)*** | Cantidad de semestres | 10 |
   | **Estado** | Activo/Inactivo | Activo |

3. **Haz clic** en **"Crear Carrera"**

#### Editar Carrera (Funcionalidad Planificada)

1. En la tabla de carreras, haz clic en **✏️ "Editar"**
2. Modifica los campos necesarios
3. **Haz clic** en **"Guardar Cambios"**

**⚠️ Nota**: Cambiar el código puede afectar relaciones con semestres y grupos existentes.

---

### Gestión de Semestres

#### ¿Qué es un Semestre?

Un **Semestre** representa un período académico dentro de una carrera (Ej: Primer Semestre, Segundo Semestre). Cada semestre agrupa las materias correspondientes a ese nivel.

#### Vista de Listado (Funcionalidad Planificada)

**📊 Estadísticas de Semestres**

| Métrica | Descripción |
|---------|-------------|
| **Total Semestres** | Cantidad total en el sistema |
| **Semestres Activos** | Con grupos activos |
| **Promedio Grupos** | Promedio de grupos por semestre |

**📋 Tabla de Semestres**

| Columna | Contenido |
|---------|-----------|
| **Carrera** | Programa académico al que pertenece |
| **Número** | Nivel del semestre (1, 2, 3...) |
| **Nombre** | Nombre descriptivo |
| **Grupos** | Cantidad de grupos |
| **Estado** | Activo/Inactivo |
| **Acciones** | Ver, Editar, Eliminar |

#### Crear Nuevo Semestre (Funcionalidad Planificada)

1. **Haz clic** en **"➕ Nuevo Semestre"**

2. **Información Requerida**:

   | Campo | Descripción | Ejemplo |
   |-------|-------------|---------|
   | **Carrera*** | Programa académico | Ingeniería en Sistemas |
   | **Número*** | Nivel del semestre | 1 |
   | **Nombre*** | Nombre descriptivo | Primer Semestre |
   | **Descripción** | Información adicional | Semestre introductorio |
   | **Estado** | Activo/Inactivo | Activo |

3. **Haz clic** en **"Crear Semestre"**

---

### Gestión de Materias

#### ¿Qué es una Materia?

Una **Materia** o **Asignatura** es un curso específico que se imparte en uno o más semestres (Ej: Programación I, Matemáticas I, Bases de Datos).

#### Vista de Listado (Funcionalidad Planificada)

**📊 Estadísticas de Materias**

| Métrica | Descripción |
|---------|-------------|
| **Total Materias** | Cantidad de asignaturas |
| **Materias Activas** | Asignaturas ofertadas |
| **Asignaciones** | Total de asignaciones profesor-materia |

**📋 Tabla de Materias**

| Columna | Contenido |
|---------|-----------|
| **Código** | ID único (Ej: PROG-101) |
| **Nombre** | Nombre de la materia |
| **Carrera** | Programa académico (si aplica) |
| **Créditos** | Unidades académicas |
| **Grupos** | Cantidad de secciones |
| **Estado** | Activo/Inactivo |
| **Acciones** | Ver, Editar, Eliminar |

#### Crear Nueva Materia (Funcionalidad Planificada)

1. **Haz clic** en **"➕ Nueva Materia"**

2. **Información Requerida**:

   | Campo | Descripción | Ejemplo |
   |-------|-------------|---------|
   | **Código*** | Identificador único | PROG-101 |
   | **Nombre*** | Nombre completo | Programación I |
   | **Descripción** | Descripción del curso | Introducción a la programación... |
   | **Carrera** | Programa (opcional) | Ingeniería en Sistemas |
   | **Créditos** | Unidades académicas | 4 |
   | **Horas Semanales** | Horas de clase | 6 |
   | **Estado** | Activo/Inactivo | Activo |

3. **Haz clic** en **"Crear Materia"**

---

### 🚧 Estado de Implementación

**Funcionalidad Actual**:
- ✅ Modelos de base de datos creados
- ✅ APIs disponibles para consultar datos
- ✅ Relaciones entre entidades funcionando
- ✅ Datos iniciales mediante seeders

**Funcionalidad Pendiente**:
- ⏳ Interfaz web para CRUD de Carreras
- ⏳ Interfaz web para CRUD de Semestres
- ⏳ Interfaz web para CRUD de Materias
- ⏳ Validaciones de integridad referencial
- ⏳ Reportes por carrera/semestre

**Alternativa Actual**:
Para agregar/modificar Carreras, Semestres o Materias, contacta al **Administrador del Sistema** quien puede:
- Ejecutar seeders personalizados
- Usar las APIs directamente
- Modificar datos via base de datos

---

## Gestión de Grupos de Estudiantes

### Acceder al Módulo

**Opción 1**: Dashboard → **"Gestión de Grupos"**  
**Opción 2**: Menú lateral → **"Gestion de Grupos"**

### Vista de Listado

#### 📊 Estadísticas

| Métrica | Descripción |
|---------|-------------|
| **Total Grupos** | Cantidad total de grupos |
| **Activos** | Grupos activos en el sistema |
| **Total Estudiantes** | Suma de estudiantes en todos los grupos |
| **Promedio Estudiantes** | Promedio de inscritos por grupo |

#### 🔍 Filtros

| Filtro | Opciones |
|--------|----------|
| **Carrera** | Programa académico |
| **Tipo de Grupo** | A, B, C, etc. |
| **Estado** | Activo/Inactivo |
| **Jornada** | Matutino/Vespertino/Nocturno |

#### 📋 Tabla de Grupos

| Columna | Contenido |
|---------|-----------|
| **Nombre** | Nombre del grupo (ej: PROG-101-A) |
| **Nivel** | Nivel curricular |
| **Carrera** | Programa ofertado |
| **Estudiantes** | Cantidad de inscritos |
| **Características** | Datos especiales |
| **Estado** | Activo/Inactivo |
| **Acciones** | Ver, Editar, Eliminar |

### Crear Nuevo Grupo

1. **Haz clic** en **"➕ Crear Grupo"** o **"+ Nuevo Grupo"**

2. **Información Requerida**:

   | Campo | Descripción | Ejemplo |
   |-------|-------------|---------|
   | **Nombre*** | ID único del grupo | PROG-101-A |
   | **Nivel*** | Nivel curricular | 1, 2, 3, etc. |
   | **# Estudiantes*** | Cantidad de inscritos | 35 |
   | **Características** | Información especial | Grupo con laboratorio |
   | **Período Académico** | Período (opcional) | 2025-1 |

3. **Haz clic** en **"Crear"**

### Editar Grupo

1. En la tabla de grupos, haz clic en **✏️ "Editar"**
2. **Puedes modificar**:
   - Nombre del grupo
   - Nivel
   - Número de estudiantes
   - Características especiales
   - Estado (Activo/Inactivo)
   - Período académico
3. **Haz clic** en **"Guardar"** para confirmar cambios

**⚠️ Nota**: La edición puede afectar las asignaciones existentes. Sistema verifica conflictos.

### Ver Detalles del Grupo

1. En la tabla de grupos, haz clic en **👁️ "Ver"** o en el nombre del grupo
2. **Se muestran**:
   - Información completa del grupo
   - Estudiantes asignados (si aplica)
   - Historial de cambios
3. Puedes regresar a la lista con el botón **"Volver"**

### Eliminar Grupo

1. En la tabla de grupos, haz clic en **🗑️ "Eliminar"**
2. Sistema solicita **confirmación**:
   ```
   ⚠️ ¿Está seguro de eliminar este grupo?
   Esto no puede deshacerse.
   ```
3. Confirma la acción
4. El grupo se elimina del sistema

**⚠️ Importante**: 
- Solo se puede eliminar si no tiene asignaciones activas
- Si tiene estudiantes o asignaciones, primero desactiva el grupo (Editar → Estado: Inactivo)

---

## Gestión de Profesores

### Acceder al Módulo

**Opción 1**: Dashboard → **"Gestionar Profesores"**  
**Opción 2**: Menú lateral → **"Gestión de Profesores"**

### Vista de Listado

#### 📊 Estadísticas de Profesores

| Métrica | Descripción |
|---------|-------------|
| **Total Profesores** | Cantidad total en el sistema |
| **Activos** | Profesores activos |
| **Con Asignaciones** | Docentes con grupos asignados |
| **Sobrecargados** | Con 5 o más cursos |
| **Promedio Materias** | Promedio de cursos por profesor |
| **Con Disponibilidades Configuradas** | Docentes que han definido sus disponibilidades |

#### 🔍 Filtros

| Filtro | Opciones |
|--------|----------|
| **Búsqueda** | Por nombre, email o especialidad |
| **Estado** | Activo/Inactivo |
| **Grado Académico** | Especialista, Magíster, Doctor |
| **Carga Horaria** | Sobrecargado, Normal, Disponible |
| **Disponibilidad** | Configurada, Pendiente |

#### 📋 Tabla de Profesores

| Columna | Contenido |
|---------|-----------|
| **Nombre** | Nombre completo |
| **Email** | Correo institucional |
| **Especialidad** | Área de conocimiento |
| **Experiencia** | Años de experiencia |
| **Cursos** | # de asignaciones |
| **Grado** | Nivel académico |
| **Disponibilidad** | Configurada o Pendiente |
| **Estado** | Activo/Inactivo |
| **Acciones** | Ver, Editar, Eliminar |

### Crear Nuevo Profesor

1. **Haz clic** en **"➕ Crear Profesor"** o **"+ Nuevo Profesor"**

2. **Información Personal**:

   | Campo | Descripción | Ejemplo |
   |-------|-------------|---------|
   | **Nombre*** | Primer nombre | Juan |
   | **Apellido*** | Apellido(s) | Rodríguez Pérez |
   | **Email*** | Correo institucional | juan.rodriguez@universidad.edu |
   | **Teléfono** | Contacto personal | +XX XXX XXX XXXX |

3. **Información Académica**:

   | Campo | Descripción | Ejemplo |
   |-------|-------------|---------|
   | **Especialidad Principal*** | Área de experticia | Programación |
   | **Años de Experiencia*** | Años laborales | 10 |
   | **Otras Especialidades** | Separadas por coma | Bases de Datos, Algoritmos |
   | **Grado Académico** | Especialista/Magíster/Doctor | Magíster |

4. **Información Complementaria**:
   - **Hoja de Vida (Resumen)**: Perfil profesional breve
   - **Notas de Disponibilidad**: Restricciones horarias
   - **Asignaciones Especiales**: Roles especiales o restricciones

5. **Estado del Profesor**:
   - ☑️ **Activo** para que pueda recibir asignaciones
   - ☐ **Inactivo** si no está disponible

6. **Haz clic** en **"Crear"**

**El profesor puede configurar después** sus disponibilidades horarias.

### Editar Profesor

1. En la tabla de profesores, haz clic en **✏️ "Editar"**
2. **Puedes modificar**:
   - Información personal (nombre, email, teléfono)
   - Información académica (especialidades, experiencia)
   - Grado académico
   - Notas y asignaciones especiales
   - Estado (Activo/Inactivo)
3. **No se puede modificar** por este módulo:
   - Asignaciones de cursos (usa Asignación Inteligente)
   - Disponibilidades horarias (profesor lo configura)
4. **Haz clic** en **"Guardar"**

### Ver Detalles del Profesor

1. En la tabla, haz clic en el **nombre del profesor** o en **👁️ "Ver"**
2. **Se muestran**:
   - Información completa del profesor
   - Cursos/asignaciones actuales
   - Disponibilidades configuradas
   - Historial de cambios
3. **Acciones disponibles**:
   - Editar profesor
   - Ver disponibilidades
   - Ver asignaciones
   - Descargar CV (si está disponible)

### Eliminar Profesor

1. En la tabla, haz clic en **🗑️ "Eliminar"**
2. Sistema solicita **confirmación**:
   ```
   ⚠️ ¿Está seguro de eliminar este profesor?
   Esto no puede deshacerse.
   ```
3. Confirma la acción

**⚠️ Importante**:
- Solo se puede eliminar si no tiene asignaciones activas
- Si tiene cursos asignados, primero desactiva al profesor (Editar → Estado: Inactivo)
- Los datos históricos se preservan

### Gestionar Disponibilidades del Profesor

**¿Por qué son importantes las disponibilidades?**

Las disponibilidades son los horarios en que cada profesor puede impartir clases. El sistema las usa para:
- Validar asignaciones
- Detectar conflictos de horario
- Asignar automáticamente respetando disponibilidades

**Cómo ver/editar disponibilidades**:

1. En tabla de profesores, selecciona un profesor
2. Haz clic en **"Ver Disponibilidades"**
3. El profesor puede:
   - Ver sus disponibilidades configuradas
   - **⚠️ Nota**: El profesor actualiza sus propias disponibilidades desde su panel
4. Como coordinador, puedes **ver** pero el profesor configura

---

## Sistema de Asignación Inteligente

### Acceder al Módulo

**Opción 1**: Dashboard → **"Asignación Inteligente"** (4 opciones)  
**Opción 2**: Menú lateral → **"Asignación"**

### Las 4 Opciones de Asignación

#### 1. 🔄 Asignación Automática

El sistema automáticamente asigna profesores a grupos respetando:
- Disponibilidades de profesores
- Especialidades requeridas
- Carga horaria máxima
- Conflictos de horario

**Cómo usar**:
1. **Haz clic** en **"🔄 Asignación Automática"**
2. Sistema muestra grupos pendientes de asignación
3. Configura los parámetros (si aplica):
   - Prioridad: Especialidad, Experiencia, Disponibilidad
   - Máximo de grupos por profesor
4. **Haz clic** en **"Ejecutar Asignación"**
5. Sistema procesa y muestra resultados
6. Revisa asignaciones sugeridas
7. **Confirma o rechaza** cambios

#### 2. ✋ Asignación Manual

Para casos especiales o ajustes finos, asigna profesores manualmente usando el **calendario semanal interactivo**:

**¿Cómo funciona?**

1. **Haz clic** en **"✋ Asignación Manual"**
2. **Calendario semanal**: El sistema muestra una vista **semanal recurrente** (Lunes a Sábado)
   - Las asignaciones se repiten cada semana durante todo el semestre
   - Vista optimizada para planificación semanal (no mensual)
3. **Crear asignación**:
   - Haz clic en **"➕ Nueva Asignación"**
   - Selecciona: Grupo, Materia, Profesor, Salón
   - Define: Día de la semana, Hora inicio, Hora fin
4. **Validación en tiempo real**:
   - Sistema verifica disponibilidad del profesor
   - Valida que el salón esté disponible
   - Detecta conflictos de horario
   - Muestra ⚠️ advertencia si hay problema
5. **Drag & Drop** (Arrastrar y Soltar):
   - Arrastra una asignación existente para cambiar día/hora
   - Redimensiona para ajustar duración
   - Cambios se validan automáticamente
6. **Guardar**: Confirma cambios

**Características del calendario**:
- 📅 **Vista semanal por defecto** (recomendado)
- 🔄 **Horarios recurrentes**: La asignación se repite cada semana
- 🎨 **Código de colores**: Identifica profesores, materias o grupos
- 🔍 **Filtros**: Por día, profesor, salón o grupo
- 📊 **Panel de estadísticas**: Resumen de asignaciones actuales

**⚠️ Nota sobre el calendario**: 
La interfaz actual permite cambiar entre vistas (Día/Semana/Mes), pero **se recomienda usar vista semanal** ya que las asignaciones se repiten semanalmente durante el semestre, no varían día a día.

#### 3. ⚙️ Configurar Reglas

Personaliza cómo el sistema asigna automáticamente:

1. **Haz clic** en **"⚙️ Configurar Reglas"**
2. **Tipos de reglas**:
   - Especialidad requerida (sí/no)
   - Experiencia mínima
   - Máximo de grupos por profesor
   - Prioridad de asignación (especialidad > experiencia > disponibilidad)
   - Permitir sobrecarga (sí/no)

3. **Guarda las reglas**
4. Estas se usarán en asignaciones automáticas futuras

#### 4. 🚨 Detectar Conflictos

El sistema identifica problemas potenciales:

1. **Haz clic** en **"🚨 Detectar Conflictos"**
2. Sistema escanea y reporta:
   - Grupos sin profesor asignado
   - Conflictos de horario (profesor en 2 lugares simultáneamente)
   - Profesores sobrecargados
   - Especialidades no coinciden
   - Disponibilidades no configuradas

3. Para cada conflicto:
   - Se sugiere una solución
   - Puedes hacer clic para auto-corregir
   - O editar manualmente

---

## Visualización de Horarios

### Acceder al Módulo

**Opción 1**: Dashboard → **"Visualización de Horarios"** (2 opciones)  
**Opción 2**: Menú lateral → **"Visualización"**

### Las 2 Opciones de Visualización

#### 1. 📅 Horario Semestral

Vista completa de todos los grupos en el semestre:

1. **Haz clic** en **"📅 Visualización Horario Semestral"**
2. Se abre calendario con:
   - Todos los grupos del semestre
   - Código de color por carrera
   - Información del profesor
   - Salón asignado
3. **Interactividad**:
   - Haz clic en un grupo para ver detalles
   - Filtra por carrera, profesor o día
   - Exporta a PDF para impresión
4. **Verifica**:
   - No hay conflictos de horario
   - Salones están disponibles
   - Profesores no están en 2 grupos simultáneamente

#### 2. 👤 Horario Personal

Horario específico para un profesor:

1. **Haz clic** en **"👤 Visualización Horario Personal"**
2. Selecciona el profesor
3. Se muestra su horario semanal con:
   - Grupos asignados
   - Horarios específicos
   - Salones
   - Número de estudiantes por grupo
4. **Verificación**:
   - Confirmar que los horarios son viables
   - Validar disponibilidades del profesor
   - Revisar carga horaria total

---

## Reportes Académicos

### Acceder a Reportes

Dashboard → **"📊 Ver Reportes"** o Menú → **"Reportes"**

### Tipos de Reportes y Estadísticas

El módulo de reportes ofrece una **vista consolidada de estadísticas académicas** con la opción de filtrar por fechas y exportar a PDF.

#### 📊 Estadísticas Principales Mostradas

| Métrica | Descripción |
|---------|-------------|
| **Grupos Totales** | Cantidad total de grupos en el sistema |
| **Grupos Activos** | Grupos actualmente en operación |
| **Profesores Totales** | Total de docentes registrados |
| **Profesores Activos** | Docentes con asignaciones activas |
| **Total Estudiantes** | Suma de estudiantes en todos los grupos |
| **Asignaciones** | Total de asignaciones profesor-grupo |
| **Horas de Clase** | Estimado de horas de enseñanza |
| **Calidad Promedio** | Promedio de calidad de las asignaciones |

#### 🔍 Filtros Disponibles

| Filtro | Descripción |
|--------|-------------|
| **Desde** | Fecha inicial para filtrado |
| **Hasta** | Fecha final para filtrado |

**Ejemplos de uso**:
- Ver estadísticas del semestre actual
- Comparar datos entre períodos
- Analizar tendencias históricas

#### 📄 Generar y Exportar Reportes

**Pasos para generar reportes**:

1. **Ve a Reportes Académicos** desde el Dashboard o menú
2. **(Opcional) Aplica filtros de fecha**:
   - Ingresa fecha inicial (Desde)
   - Ingresa fecha final (Hasta)
   - Haz clic en **"Filtrar"**
3. **Revisa las estadísticas** mostradas en tarjetas
4. **Haz clic en "📄 Exportar PDF"** para descargar reporte
5. El archivo PDF se descarga a tu dispositivo

**Contenido del PDF exportado**:
- Fecha de generación
- Período de datos (si fue filtrado)
- Todas las 8 métricas principales
- Listado de grupos recientes
- Listado de profesores recientes
- Resumen ejecutivo

#### 💡 Usos Comunes de los Reportes

**Para planificación académica**:
- Analizar carga de profesores
- Verificar capacidad de grupos
- Planificar semestres futuros

**Para monitoreo**:
- Verificar número de grupos activos
- Revisar distribución de estudiantes
- Controlar asignaciones de profesores

**Para auditoría**:
- Reportes históricos por período
- Trazabilidad de cambios
- Cumplimiento de capacidades

---

## Preguntas Frecuentes y Soporte

### ¿Qué hago si el sistema asigna un profesor sin especialidad?

Revisa la configuración de reglas:
1. Ve a **Asignación Inteligente** → **Configurar Reglas**
2. Verifica que **"Especialidad Requerida"** esté habilitada
3. Ejecuta nuevamente la asignación automática

### ¿Puedo deshacer una asignación de profesor?

Sí, de dos formas:

1. **Desde la tabla de profesores**: Edita el profesor y modifica su asignación
2. **Desde Asignación Manual**: Arrastra el profesor desde el grupo (suéltalo fuera)

Luego guarda los cambios.

### ¿Cómo sabré si un profesor no tiene disponibilidades configuradas?

En la tabla de Profesores:
- Busca la columna **"Disponibilidad"**
- Si muestra **"Pendiente"** = el profesor aún no configura su disponibilidad
- Si muestra **"Configurada"** = el profesor ya definió sus horarios

Puedes contactar al profesor para que complete su disponibilidad.

### ¿Qué debo hacer si hay conflictos de asignación?

1. Ve a **Asignación Inteligente** → **🚨 Detectar Conflictos**
2. Sistema lista todos los problemas encontrados
3. Para cada conflicto:
   - Lee la descripción
   - Haz clic en **"Resolver"** para sugerencia automática
   - O edita manualmente haciendo clic en **"Editar"**
4. Guarda los cambios

### ¿Los profesores pueden actualizar sus disponibilidades?

Sí. Los profesores pueden:
1. Acceder a su panel personal
2. Ir a **"Disponibilidades"**
3. Agregar, editar o eliminar sus disponibilidades horarias
4. Sistema automáticamente valida futuras asignaciones contra esto

**Puedes ver** pero no editar directamente. El profesor es responsable.

---

## 📞 Soporte y Contacto

### Para Problemas Técnicos

- **Email**: soporte-tecnico@universidad.edu
- **Teléfono**: +XX XXX XXX XXXX Ext. 100
- **Horario**: Lunes a Viernes, 8:00 AM - 6:00 PM

### Para Consultas Académicas

- **Email**: coordinacion-academica@universidad.edu
- **Teléfono**: +XX XXX XXX XXXX Ext. 200
- **Horario**: Lunes a Viernes, 8:00 AM - 5:00 PM

### Reportar Bugs o Sugerencias

1. **Accede** al formulario de reporte en el sistema
2. **Describe** el problema o sugerencia
3. **Adjunta** capturas de pantalla si es necesario
4. Sistema enviará a equipo de desarrollo

---

## 📚 Recursos Adicionales

- **Manual del Sistema**: Disponible en [documentación](/)
- **Video Tutorial**: [Enlace a videotutorial](#)
- **Base de Conocimiento**: [Preguntas frecuentes avanzadas](#)

---

**© 2025 Sistema de Asignación de Salones | Universidad**  
**Versión 2.1 - Diciembre 2025**

**Última actualización**: Diciembre 2025
**Próxima revisión**: Marzo 2026
