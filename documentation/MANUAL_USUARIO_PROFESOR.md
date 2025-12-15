# 📖 Manual de Usuario - Profesor

**Sistema de Asignación de Salones**  
**Versión 2.1** | Diciembre 2025

---

## 📋 Índice

1. [Introducción](#introducción)
2. [Acceso al Sistema](#acceso-al-sistema)
3. [Panel Principal (Dashboard)](#panel-principal-dashboard)
4. [Gestión de Actividades](#gestión-de-actividades)
5. [Calificación de Estudiantes](#calificación-de-estudiantes)
6. [Reportes](#reportes)
7. [Perfil y Configuración](#perfil-y-configuración)
8. [Preguntas Frecuentes](#preguntas-frecuentes)

---

## Introducción

### ¿Qué es el Sistema de Asignación de Salones?

El Sistema de Asignación de Salones es una plataforma integral diseñada para facilitar la gestión académica. Como **Profesor**, podrás:

- ✅ Gestionar actividades y tareas de tus cursos
- ✅ Calificar estudiantes de manera eficiente
- ✅ Generar reportes de asistencias y calificaciones
- ✅ Consultar información de tus asignaciones

### Requisitos Previos

- **Navegador**: Chrome, Firefox, Safari o Edge (versión actualizada)
- **Credenciales**: Usuario y contraseña proporcionados por el administrador
- **Rol**: Profesor

---

## Acceso al Sistema

### Inicio de Sesión

1. Accede a la URL del sistema proporcionada por tu institución
2. Ingresa tu **correo electrónico** (ej: `profesor@universidad.edu`)
3. Ingresa tu **contraseña**
4. Haz clic en **"Iniciar Sesión"**

```
📧 Email: profesor@universidad.edu
🔑 Contraseña: [tu contraseña]
```

### Primera Vez

Si es tu primera vez:
- Usa las credenciales temporales proporcionadas
- Se te solicitará cambiar tu contraseña
- Completa tu información de perfil

### ¿Olvidaste tu Contraseña?

1. Haz clic en **"¿Olvidaste tu contraseña?"**
2. Ingresa tu correo electrónico
3. Revisa tu email para el enlace de recuperación
4. Sigue las instrucciones para crear una nueva contraseña

---

## Panel Principal (Dashboard)

### Vista General

Al iniciar sesión, verás tu **Dashboard Profesor** con:

#### Estadísticas Principales

| Tarjeta | Descripción |
|---------|-------------|
| **Cursos Asignados** | Número total de cursos que impartes este semestre |
| **Total Estudiantes** | Cantidad de estudiantes en todos tus cursos |
| **Actividades Pendientes** | Tareas sin calificar o próximas a vencer |
| **Asistencias Hoy** | Resumen de asistencias del día actual |

#### Secciones Rápidas

- **Mis Cursos**: Lista de asignaturas que impartes
- **Próximas Clases**: Calendario con tus próximas sesiones
- **Actividades Recientes**: Últimas tareas creadas o calificadas
- **Accesos Rápidos**: Botones para crear actividades, tomar asistencia, etc.

### Navegación

La barra lateral izquierda contiene:

```
📊 Dashboard          → Panel principal
📚 Mis Cursos         → Lista de asignaturas
✍️ Actividades       → Gestión de tareas
📝 Calificaciones     → Registro de notas
📊 Reportes           → Informes y estadísticas
👤 Mi Perfil          → Configuración personal
```

---

## Gestión de Actividades

### Crear Nueva Actividad

1. **Accede al módulo**:
   - Clic en **"Actividades"** en el menú lateral
   - O clic en **"+ Nueva Actividad"** desde el Dashboard

2. **Completa el formulario**:

   | Campo | Descripción | Ejemplo |
   |-------|-------------|---------|
   | **Título** | Nombre descriptivo de la actividad | "Tarea 1: Variables en Python" |
   | **Descripción** | Instrucciones detalladas | "Resolver ejercicios del capítulo 3..." |
   | **Tipo** | Categoría de actividad | Tarea, Examen, Proyecto, Laboratorio |
   | **Asignación** | Curso donde se aplicará | Programación I - Grupo A |
   | **Fecha Límite** | Fecha y hora de entrega | 2025-12-20 23:59 |
   | **Puntuación Máxima** | Calificación máxima | 100 puntos |
   | **Archivo Adjunto** | Opcional: PDF, Word, etc. | `ejercicios.pdf` |

3. **Opciones Avanzadas** (Opcional):
   - ☑️ **Permitir entregas tardías** (con penalización)
   - ☑️ **Notificar estudiantes por correo**
   - ☑️ **Visible desde ahora** o programar fecha de publicación

4. Haz clic en **"Crear Actividad"**

### Listado de Actividades

**Filtros disponibles**:
- 🔍 **Búsqueda**: Por título o descripción
- 📚 **Curso**: Filtrar por asignatura específica
- 📅 **Estado**: Pendientes, En Progreso, Completadas
- 📆 **Fecha**: Por rango de fechas

**Columnas de información**:
- Título y descripción
- Curso/Grupo asignado
- Fecha límite
- Estudiantes (Calificados / Total)
- Promedio actual
- Acciones (Ver, Editar, Calificar, Eliminar)

### Editar Actividad

1. En el listado, haz clic en ✏️ **"Editar"**
2. Modifica los campos necesarios
3. Haz clic en **"Guardar Cambios"**

⚠️ **Nota**: Si ya hay entregas calificadas, algunos campos no podrán modificarse

### Eliminar Actividad

1. Haz clic en 🗑️ **"Eliminar"**
2. Confirma la acción
3. ⚠️ **Advertencia**: Esta acción eliminará también todas las calificaciones asociadas

---

## Calificación de Estudiantes

### Acceder a Calificaciones

**Opción 1 - Desde Actividades**:
1. Ve a **"Actividades"**
2. Haz clic en 👁️ **"Calificar"** en la actividad deseada

**Opción 2 - Desde Calificaciones**:
1. Ve a **"Calificaciones"** en el menú
2. Selecciona el curso y actividad

### Vista de Calificación

#### Información de Contexto

En la parte superior verás:

```
📚 Materia: Programación I
🎓 Carrera: Ingeniería en Sistemas
📅 Semestre: 2025-1
🏢 Salón: BOL-3010 (Edificio Bolívar)
⏰ Horario: Lunes 08:00-10:00
```

#### Estadísticas de la Actividad

| Estadística | Descripción |
|-------------|-------------|
| **Calificados** | Número de estudiantes ya calificados |
| **Pendientes** | Estudiantes sin calificar |
| **Promedio** | Nota promedio del grupo |
| **Puntuación Máxima** | Calificación máxima posible |

#### Detalles de la Actividad

- **Título**: Nombre de la tarea
- **Descripción**: Instrucciones completas
- **Fecha Límite**: Cuándo vence
- **Puntuación Máxima**: Nota máxima

### Calificar Estudiantes

#### Tabla de Calificación

Para cada estudiante verás:

| Columna | Contenido |
|---------|-----------|
| **#** | Número de lista |
| **Código** | Código único del estudiante |
| **Nombre** | Nombre completo |
| **Calificación** | Campo editable para ingresar nota |
| **Retroalimentación** | Comentarios opcionales |
| **Estado** | Calificado/Pendiente |

#### Ingresar Calificaciones

1. **Calificación**:
   - Ingresa un número entre **0** y la **puntuación máxima**
   - Presiona **Tab** para pasar al siguiente estudiante
   - El sistema valida automáticamente que no excedas el máximo

2. **Retroalimentación** (Opcional):
   - Escribe comentarios constructivos
   - Ejemplo: *"Excelente trabajo, solo revisar el punto 3"*

3. **Guardar**:
   - Haz clic en **"Guardar Calificaciones"**
   - O presiona **Ctrl + S** (atajo de teclado)

#### Calificación Rápida

Para calificar múltiples estudiantes con la misma nota:

1. Marca las casillas de los estudiantes deseados
2. Usa el campo **"Calificación Masiva"** en la parte superior
3. Ingresa la nota y haz clic en **"Aplicar"**

### Exportar Calificaciones

1. Haz clic en **"Exportar"** en la esquina superior derecha
2. Elige el formato:
   - 📄 **PDF**: Para imprimir o archivar
   - 📊 **Excel**: Para análisis adicional
3. El archivo se descargará automáticamente

---

## Reportes

### Acceder a Reportes

1. Haz clic en **"Reportes"** en el menú lateral
2. Verás un panel con todos tus cursos

### Vista de Reportes

#### Información de Contexto

Para cada curso se muestra:

```
👤 Profesor: [Tu nombre]
📧 Correo: [Tu email]
📚 Total Cursos: [Número]
👥 Total Estudiantes: [Número]
```

#### Tarjetas de Curso

Cada tarjeta contiene:

| Información | Descripción |
|-------------|-------------|
| **Código** | Código de la materia (ej: PROG-101) |
| **Nombre** | Nombre completo del curso |
| **Carrera** | Ingeniería, Licenciatura, etc. |
| **Semestre** | Semestre académico |
| **Turno** | Matutino/Vespertino/Nocturno |
| **Créditos** | Créditos académicos |
| **Horario** | Día y hora de clase |
| **Salón** | Aula y edificio asignado |
| **Grupo** | Identificador del grupo |
| **Estudiantes** | Cantidad matriculada |

### Generar Reportes

#### Reporte de Asistencias

1. Haz clic en **"📋 Reporte de Asistencias"** en la tarjeta del curso
2. El sistema genera un PDF con:
   - Lista completa de estudiantes
   - Fechas de asistencia
   - Porcentaje de asistencia por estudiante
   - Estadísticas generales

**Nombre del archivo**: `asistencias_[código]_[fecha].pdf`

#### Reporte de Actividades

1. Haz clic en **"📊 Reporte de Actividades"** en la tarjeta del curso
2. El sistema genera un PDF con:
   - Todas las actividades del curso
   - Calificaciones por estudiante
   - Promedios generales
   - Actividades pendientes

**Nombre del archivo**: `actividades_[código]_[fecha].pdf`

### Contenido de Reportes en PDF

#### Encabezado
- Logo de la institución
- Nombre del sistema
- Fecha de generación
- Información del profesor

#### Datos del Curso
- Código y nombre
- Carrera y semestre
- Horario y salón

#### Tabla de Datos
- Código del estudiante
- Nombre completo
- Calificaciones/Asistencias
- Estadísticas individuales

#### Pie de Página
- Firma del profesor (espacio reservado)
- Fecha y hora de generación
- Número de página

### Uso de Reportes

**Recomendaciones**:
- 📅 Genera reportes semanalmente para seguimiento
- 💾 Guarda copias para respaldo
- 📧 Comparte con coordinación académica cuando sea necesario
- 📊 Usa para análisis de desempeño estudiantil

---

## Perfil y Configuración

### Acceder a Tu Perfil

1. Haz clic en tu **nombre** en la esquina superior derecha
2. Selecciona **"Mi Perfil"**

### Información Personal

Puedes ver y editar:

| Campo | Descripción | Editable |
|-------|-------------|----------|
| **Nombre Completo** | Nombres y apellidos | ✅ |
| **Correo Electrónico** | Email institucional | ❌ (Contacta al admin) |
| **Teléfono** | Número de contacto | ✅ |
| **Especialidad** | Área de expertise | ✅ |
| **Departamento** | Facultad o departamento | ❌ (Asignado) |

### Cambiar Contraseña

1. En tu perfil, haz clic en **"Cambiar Contraseña"**
2. Ingresa tu **contraseña actual**
3. Ingresa tu **nueva contraseña** (mínimo 8 caracteres)
4. Confirma la nueva contraseña
5. Haz clic en **"Actualizar Contraseña"**

**Requisitos de contraseña**:
- ✅ Mínimo 8 caracteres
- ✅ Al menos una mayúscula
- ✅ Al menos un número
- ✅ Al menos un carácter especial (!@#$%^&*)

### Preferencias

- **Notificaciones**: Activar/desactivar emails
- **Idioma**: Español (predeterminado)
- **Zona Horaria**: Configuración regional

---

## Preguntas Frecuentes

### ¿Cómo puedo recuperar mi contraseña?

En la página de inicio de sesión, haz clic en "¿Olvidaste tu contraseña?" e ingresa tu correo. Recibirás un enlace para restablecerla.

### ¿Puedo editar una actividad después de que los estudiantes hayan entregado?

Sí, pero algunos campos estarán bloqueados si ya hay calificaciones registradas (como la puntuación máxima).

### ¿Cómo elimino una calificación incorrecta?

Accede a la actividad, busca al estudiante, borra el valor de la calificación y guarda. Esto la marcará como "Pendiente" nuevamente.

### ¿Los estudiantes pueden ver sus calificaciones inmediatamente?

Sí, una vez que guardas las calificaciones, los estudiantes pueden verlas en su portal.

### ¿Puedo calificar desde mi teléfono móvil?

Sí, el sistema es responsive. Sin embargo, recomendamos usar una computadora para mayor comodidad al calificar múltiples estudiantes.

### ¿Cómo sé si un estudiante ha entregado una tarea?

En el listado de actividades, verás el contador "Calificados / Total". Los estudiantes sin calificación están pendientes.

### ¿Puedo descargar todas las calificaciones de un curso?

Sí, usa el "Reporte de Actividades" que incluye todas las calificaciones en formato PDF.

### ¿Qué hago si no veo mis cursos en el Dashboard?

Contacta al coordinador académico. Es posible que tus asignaciones no estén configuradas correctamente.

### ¿Puedo agregar estudiantes a un curso?

No directamente. Los estudiantes son asignados por el coordinador académico. Contacta al departamento correspondiente.

### ¿Los reportes se pueden editar después de generarlos?

No, los reportes PDF son documentos de solo lectura. Si necesitas cambios, ajusta las calificaciones y genera un nuevo reporte.

---

## 📞 Soporte

### Contacto

- **Email**: soporte@universidad.edu
- **Teléfono**: +XX XXX XXX XXXX
- **Horario**: Lunes a Viernes, 8:00 AM - 5:00 PM

### Antes de Contactar Soporte

✅ **Verifica**:
1. Que estás usando las credenciales correctas
2. Tu conexión a internet
3. Que tu navegador está actualizado
4. Las preguntas frecuentes de este manual

---

**© 2025 Sistema de Asignación de Salones | Universidad**  
*Versión 2.1 - Diciembre 2025*
