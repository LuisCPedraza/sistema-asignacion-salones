# 🏢 Manual de Usuario - Coordinador de Infraestructura

**Sistema de Asignación de Salones**  
**Versión 2.1** | Diciembre 2025

---

## 📋 Índice

1. [Introducción](#introducción)
2. [Acceso al Sistema](#acceso-al-sistema)
3. [Panel Principal (Dashboard)](#panel-principal-dashboard)
4. [Gestión de Salones](#gestión-de-salones)
5. [Mantenimiento](#mantenimiento)
6. [Reservas](#reservas)
7. [Reportes](#reportes)
8. [Preguntas Frecuentes](#preguntas-frecuentes)

---

## Introducción

### ¿Qué es el Módulo de Infraestructura?

El módulo de infraestructura te permite gestionar de manera integral todos los recursos físicos de la institución. Como **Coordinador de Infraestructura**, podrás:

- ✅ Administrar salones y aulas
- ✅ Programar y dar seguimiento a mantenimientos
- ✅ Gestionar reservas de espacios
- ✅ Generar reportes de utilización
- ✅ Monitorear el estado de la infraestructura

### Requisitos Previos

- **Navegador**: Chrome, Firefox, Safari o Edge (actualizado)
- **Credenciales**: Usuario y contraseña con rol de Coordinador Infraestructura
- **Permisos**: Acceso completo al módulo de infraestructura

---

## Acceso al Sistema

### Inicio de Sesión

1. Accede a la URL del sistema
2. Ingresa tus credenciales:
   ```
   📧 Email: infraestructura@universidad.edu
   🔑 Contraseña: [tu contraseña]
   ```
3. Haz clic en **"Iniciar Sesión"**
4. Serás redirigido al **Dashboard de Infraestructura**

---

## Panel Principal (Dashboard)

### Vista General

El dashboard es tu centro de control. Aquí encontrarás:

#### 📊 Tabla de Estadísticas

Una tabla compacta con 6 métricas clave:

| Métrica | Descripción |
|---------|-------------|
| **Salones Activos** | Número de aulas disponibles para uso |
| **En Mantenimiento** | Salones actualmente en mantenimiento |
| **Mtto Pendientes** | Tareas de mantenimiento por iniciar |
| **Reservas Pendientes** | Reservas pendientes de aprobación |
| **Próximas Reservas** | Reservas confirmadas próximas |
| **Capacidad Total** | Suma de capacidad de todos los salones |

**Ejemplo visual**:
```
┌────────────┬────────────┬────────────┬────────────┬────────────┬────────────┐
│ Salones    │ En Mant.   │ Mtto Pend. │ Reservas   │ Próximas   │ Capacidad  │
│ Activos    │            │            │ Pendientes │ Reservas   │ Total      │
├────────────┼────────────┼────────────┼────────────┼────────────┼────────────┤
│    45      │     3      │     8      │     12     │     25     │   1,890    │
└────────────┴────────────┴────────────┴────────────┴────────────┴────────────┘
```

#### ⚡ Acciones Rápidas

Cuatro botones grandes para acciones frecuentes:

- **➕ Crear Salón**: Agregar nuevo salón al inventario
- **🔧 Programar Mantenimiento**: Nueva tarea de mantenimiento
- **📅 Nueva Reserva**: Crear reserva de espacio
- **📊 Ver Reportes**: Acceder a reportes estadísticos

#### 🎯 Módulos

Tarjetas con acceso directo a cada módulo:

**1. 🏫 Gestión de Salones**
- Ver todos los salones
- Buscar y filtrar
- Gestionar disponibilidad
- **Botón**: *Ver Todos los Salones*

**2. 🔧 Mantenimiento**
- Historial completo
- Tareas pendientes
- Seguimiento de estado
- **Botón**: *Ver Mantenimientos*

**3. 📅 Reservas**
- Solicitudes pendientes
- Calendario de reservas
- Gestión de aprobaciones
- **Botón**: *Ver Reservas*

**4. 📊 Reportes**
- Utilización de salones
- Estadísticas de mantenimiento
- Análisis de tendencias
- **Botón**: *Ver Reportes*

---

## Gestión de Salones

### Acceder al Módulo

**Opción 1**: Haz clic en **"🏫 Gestión de Salones"** en el Dashboard  
**Opción 2**: Menú lateral → **"Salones"**

### Vista de Listado

#### 📊 Estadísticas Superiores

Cuatro cajas con métricas:

| Estadística | Descripción | Ejemplo |
|-------------|-------------|---------|
| **Total Salones** | Cantidad total registrada | 48 |
| **Salones Activos** | Actualmente disponibles | 45 |
| **Salones Inactivos** | Fuera de servicio | 3 |
| **Capacidad Total** | Suma de todas las capacidades | 1,890 |

#### 🔍 Filtros de Búsqueda

Panel de filtros expandible con 6 campos:

**1. Edificio** 🏢
- Menú desplegable
- Opciones: Todos, Bolívar, Sucre, Ayacucho, etc.

**2. Tipo de Salón** 📚
- Aula: Clase regular
- Laboratorio: Prácticas
- Auditorio: Eventos grandes
- Sala de Conferencias: Reuniones
- Taller: Trabajo práctico

**3. Estado** 🔘
- Todos
- Activo
- Inactivo

**4. Capacidad Mínima** 👥
- Campo numérico
- Ejemplo: 20 estudiantes

**5. Capacidad Máxima** 👥
- Campo numérico
- Ejemplo: 50 estudiantes

**6. Buscar** 🔍
- Por código o nombre
- Ejemplo: "BOL-" para edificio Bolívar

**Botones**:
- 🔍 **Aplicar Filtros**: Ejecuta la búsqueda
- 🔄 **Limpiar**: Remueve todos los filtros

#### 🏷️ Chips de Filtros Activos

Cuando aplicas filtros, se muestran chips visuales:

```
Filtros aplicados:
[Edificio: Bolívar ✕] [Tipo: Aula ✕] [Capacidad: 20-40 ✕]
```

Haz clic en **✕** para remover filtros individuales.

#### 📋 Tabla de Salones

Columnas de información:

| Columna | Contenido | Ejemplo |
|---------|-----------|---------|
| **Código** | Identificador único | BOL-3010 |
| **Nombre** | Nombre descriptivo | Salón Multimedia |
| **Tipo** | Con icono y badge | 📚 Aula |
| **Edificio** | Nombre del edificio | Bolívar |
| **Capacidad** | Número de estudiantes | 35 |
| **Estado** | Activo/Inactivo con badge | ✅ Activo |
| **Acciones** | Botones de acción | Ver, Editar |

**Badges de tipo**:
- 📚 **Aula**: Badge azul
- 🔬 **Laboratorio**: Badge morado
- 🎭 **Auditorio**: Badge verde
- 🏢 **Sala Conferencias**: Badge naranja
- 🔧 **Taller**: Badge rojo

#### 🔢 Paginación Numérica

Navegación con números de página:

```
‹ 1 2 [3] 4 5 ... 12 ›
```

- **‹ ›**: Página anterior/siguiente
- **[3]**: Página actual (resaltada)
- **...**: Indica páginas omitidas
- Muestra 5-6 números a la vez

### Crear Nuevo Salón

1. **Haz clic** en **"➕ Crear Salón"** (Dashboard o botón superior)

2. **Completa el formulario**:

   | Campo | Descripción | Ejemplo |
   |-------|-------------|---------|
   | **Código*** | Identificador único | BOL-4020 |
   | **Nombre*** | Nombre descriptivo | Salón de Cómputo 2 |
   | **Tipo*** | Categoría del salón | Laboratorio |
   | **Edificio*** | Ubicación | Bolívar |
   | **Piso** | Número de piso | 4 |
   | **Capacidad*** | Número de personas | 30 |
   | **Estado** | Activo/Inactivo | ✅ Activo |
   | **Descripción** | Información adicional | "Equipado con 30 PCs, proyector..." |
   | **Equipamiento** | Lista de recursos | Proyector, Pizarra, Aire acondicionado |

   (*) = Campos obligatorios

3. **Equipamiento**: Selecciona todo lo aplicable
   - ☑️ Proyector
   - ☑️ Pizarra inteligente
   - ☑️ Aire acondicionado
   - ☑️ Computadoras
   - ☑️ Sistema de audio
   - ☑️ Acceso a internet

4. **Haz clic** en **"Crear Salón"**

### Editar Salón

1. En la tabla, localiza el salón
2. Haz clic en **✏️ "Editar"**
3. Modifica los campos necesarios
4. Haz clic en **"Guardar Cambios"**

**Cambios comunes**:
- Actualizar capacidad
- Cambiar estado (Activo/Inactivo)
- Modificar equipamiento
- Actualizar descripción

### Ver Detalles de Salón

1. Haz clic en **👁️ "Ver"**
2. Se muestra información completa:
   - Datos básicos
   - Equipamiento instalado
   - Historial de uso
   - Mantenimientos realizados
   - Próximas reservas

### Cambiar Estado de Salón

**Desactivar un salón**:
1. Edita el salón
2. Cambia estado a **"Inactivo"**
3. Ingresa motivo (ej: "En remodelación")
4. Guarda cambios

⚠️ **Importante**: Los salones inactivos no aparecen en asignaciones automáticas.

---

## Mantenimiento

### Acceder al Módulo

**Opción 1**: Dashboard → **"🔧 Mantenimiento"**  
**Opción 2**: Menú lateral → **"Mantenimiento"**

### Vista de Listado

#### 🔍 Filtros de Mantenimiento

Panel con filtros específicos:

| Filtro | Opciones |
|--------|----------|
| **Estado** | Todos, Pendiente, En Progreso, Completado, Cancelado |
| **Tipo** | Todos, Preventivo, Correctivo, Emergencia |
| **Salón** | Búsqueda por código o nombre |
| **Fecha** | Rango desde/hasta |
| **Responsable** | Técnico o equipo asignado |

#### 📋 Tabla de Mantenimientos

Columnas:

| Columna | Contenido |
|---------|-----------|
| **ID** | Número de ticket |
| **Salón** | Código del salón |
| **Título** | Descripción breve |
| **Tipo** | Preventivo/Correctivo/Emergencia |
| **Estado** | Badge de estado actual |
| **Responsable** | Persona o equipo asignado |
| **Fecha Inicio** | Cuándo inicia |
| **Fecha Fin** | Cuándo finaliza/finalizó |
| **Prioridad** | Baja/Media/Alta/Crítica |
| **Acciones** | Ver, Editar, Cancelar |

**Badges de estado**:
- 🟡 **Pendiente**: Amarillo
- 🔵 **En Progreso**: Azul
- 🟢 **Completado**: Verde
- 🔴 **Cancelado**: Rojo

#### 🔢 Paginación

Similar a salones, con números de página:
```
‹ 1 [2] 3 4 5 ... 15 ›
```

### Crear Nuevo Mantenimiento

1. **Haz clic** en **"🔧 Programar Mantenimiento"**

2. **Completa el formulario**:

   | Campo | Descripción | Ejemplo |
   |-------|-------------|---------|
   | **Salón*** | Selecciona de la lista | BOL-3010 |
   | **Título*** | Descripción corta | "Reparación de proyector" |
   | **Descripción** | Detalles completos | "Proyector presenta imagen borrosa..." |
   | **Tipo*** | Categoría | Correctivo |
   | **Prioridad*** | Urgencia | Alta |
   | **Responsable*** | Quién lo realizará | Juan Pérez - Técnico |
   | **Fecha Inicio*** | Cuándo inicia | 2025-12-15 08:00 |
   | **Fecha Fin Estimada** | Cuándo termina | 2025-12-15 12:00 |
   | **Costo Estimado** | Presupuesto | $150.00 |

3. **Opciones adicionales**:
   - ☑️ **Notificar a coordinación académica**
   - ☑️ **Bloquear salón durante mantenimiento**
   - ☑️ **Requiere materiales externos**

4. **Haz clic** en **"Programar Mantenimiento"**

### Tipos de Mantenimiento

#### 🔧 Preventivo
- Mantenimiento programado regular
- Ejemplo: Limpieza de aires acondicionados
- Prioridad: Baja a Media
- Frecuencia: Mensual/Trimestral

#### 🔨 Correctivo
- Reparación de fallas detectadas
- Ejemplo: Cambio de bombillas quemadas
- Prioridad: Media a Alta
- Frecuencia: Según necesidad

#### 🚨 Emergencia
- Problemas críticos inmediatos
- Ejemplo: Fuga de agua, fallo eléctrico
- Prioridad: Crítica
- Frecuencia: Impredecible

### Gestionar Estado de Mantenimiento

#### Iniciar Mantenimiento

1. Localiza el mantenimiento **Pendiente**
2. Haz clic en **"Iniciar"**
3. Confirma la acción
4. El estado cambia a **En Progreso**

#### Actualizar Progreso

1. Haz clic en **✏️ "Editar"**
2. Actualiza la sección **"Notas de Progreso"**
3. Agrega fotos (opcional)
4. Guarda cambios

#### Completar Mantenimiento

1. Haz clic en **"Completar"**
2. Completa el formulario de cierre:
   - Fecha real de finalización
   - Costo real
   - Trabajo realizado (descripción)
   - Materiales utilizados
   - Observaciones finales
3. Adjunta fotos del trabajo terminado (opcional)
4. Haz clic en **"Marcar Como Completado"**

#### Cancelar Mantenimiento

1. Haz clic en **"Cancelar"**
2. Ingresa motivo de cancelación
3. Confirma la acción

### Historial de Mantenimiento

**Ver historial por salón**:
1. Ve a **"Salones"**
2. Haz clic en **👁️ "Ver"** en un salón
3. Pestaña **"Historial de Mantenimiento"**

**Información mostrada**:
- Todos los mantenimientos realizados
- Fechas y duración
- Costos acumulados
- Problemas recurrentes
- Técnicos responsables

---

## Reservas

### Acceder al Módulo

**Opción 1**: Dashboard → **"📅 Reservas"**  
**Opción 2**: Menú lateral → **"Reservas"**

### Vista de Listado

#### 📊 Estadísticas

| Métrica | Descripción |
|---------|-------------|
| **Pendientes** | Reservas por aprobar |
| **Aprobadas** | Confirmadas y activas |
| **Rechazadas** | No autorizadas |
| **Completadas** | Ya realizadas |

#### 🔍 Filtros

| Filtro | Opciones |
|--------|----------|
| **Estado** | Pendiente, Aprobada, Rechazada, Completada |
| **Salón** | Búsqueda por código |
| **Solicitante** | Por profesor o usuario |
| **Fecha** | Rango de fechas |
| **Tipo de Evento** | Clase, Examen, Conferencia, Evento |

#### 📋 Tabla de Reservas

| Columna | Contenido |
|---------|-----------|
| **ID** | Número de reserva |
| **Salón** | Código y nombre |
| **Solicitante** | Quién solicita |
| **Fecha** | Día de la reserva |
| **Hora Inicio** | Horario inicio |
| **Hora Fin** | Horario fin |
| **Motivo** | Tipo de evento |
| **Estado** | Badge de estado |
| **Acciones** | Aprobar, Rechazar, Ver |

### Crear Nueva Reserva

1. **Haz clic** en **"📅 Nueva Reserva"**

2. **Completa el formulario**:

   | Campo | Descripción | Ejemplo |
   |-------|-------------|---------|
   | **Salón*** | Selecciona de disponibles | AYA-2010 |
   | **Solicitante*** | Profesor o responsable | Dr. Carlos Rodríguez |
   | **Fecha*** | Día de la reserva | 2025-12-20 |
   | **Hora Inicio*** | Hora de inicio | 14:00 |
   | **Hora Fin*** | Hora de fin | 16:00 |
   | **Motivo*** | Razón de la reserva | Conferencia invitado especial |
   | **Descripción** | Detalles adicionales | "Conferencia sobre IA..." |
   | **Participantes Estimados** | Cantidad de asistentes | 45 |
   | **Requiere Equipamiento Especial** | Lista de necesidades | Proyector, micrófono |

3. **Verificar disponibilidad**:
   - El sistema muestra conflictos automáticamente
   - Si hay conflicto, elige otro horario o salón

4. **Haz clic** en **"Crear Reserva"**

### Gestionar Solicitudes

#### Aprobar Reserva

1. Localiza la reserva **Pendiente**
2. Haz clic en **✅ "Aprobar"**
3. Revisa los detalles
4. (Opcional) Agrega nota de aprobación
5. Confirma

**El solicitante recibe notificación por email**

#### Rechazar Reserva

1. Haz clic en **❌ "Rechazar"**
2. **Obligatorio**: Ingresa motivo del rechazo
   - Ejemplo: "Salón ya reservado para examen"
3. Confirma

**El solicitante recibe notificación con el motivo**

#### Modificar Reserva

1. Haz clic en **✏️ "Editar"**
2. Modifica campos necesarios (fecha, hora, salón)
3. Agrega nota explicativa
4. Guarda cambios

**El solicitante recibe notificación de cambios**

### Calendario de Reservas

Vista de calendario mensual:

1. Haz clic en **📅 "Vista Calendario"**
2. Navegación por mes
3. Cada día muestra:
   - Número de reservas
   - Estados (colores)
4. Haz clic en un día para ver detalles

**Códigos de color**:
- 🟡 Amarillo: Pendiente
- 🔵 Azul: Aprobada
- 🔴 Rojo: Rechazada
- 🟢 Verde: Completada

---

## Reportes

### Acceder a Reportes

Dashboard → **"📊 Ver Reportes"** o Menú → **"Reportes"**

### Tipos de Reportes Disponibles

#### 1. 📊 Reporte de Utilización de Salones

**Información mostrada**:
- Porcentaje de uso por salón
- Horas ocupadas vs. disponibles
- Salones más/menos utilizados
- Tendencias por período

**Filtros**:
- Período: Semana, Mes, Semestre, Año
- Edificio: Específico o todos
- Tipo de salón: Aula, Laboratorio, etc.

**Formatos**:
- 📄 **PDF**: Para imprimir/archivar
- 📊 **Excel**: Para análisis adicional
- 📈 **Gráficos**: Visual interactivo

#### 2. 🔧 Reporte de Mantenimiento

**Información mostrada**:
- Mantenimientos realizados
- Costos acumulados
- Tiempo promedio de resolución
- Salones con más mantenimientos

**Filtros**:
- Período de tiempo
- Tipo de mantenimiento
- Estado
- Rango de costo

#### 3. 📅 Reporte de Reservas

**Información mostrada**:
- Total de reservas por período
- Tasa de aprobación/rechazo
- Solicitantes más frecuentes
- Horarios pico

**Filtros**:
- Rango de fechas
- Estado de reservas
- Salón específico

#### 4. 💰 Reporte Financiero

**Información mostrada**:
- Costos de mantenimiento
- Presupuesto utilizado vs. disponible
- Proyección de gastos
- ROI de inversiones en infraestructura

### Generar Reporte

1. **Selecciona tipo de reporte**
2. **Aplica filtros** deseados
3. **Haz clic** en **"Generar Reporte"**
4. **Elige formato** (PDF, Excel, Vista Web)
5. El archivo se descarga o muestra

### Programar Reportes Automáticos

1. Ve a **"Configuración de Reportes"**
2. Haz clic en **"Programar Nuevo Reporte"**
3. Configura:
   - Tipo de reporte
   - Frecuencia (Semanal, Mensual)
   - Día y hora
   - Destinatarios (emails)
4. Haz clic en **"Guardar Programación"**

**Los reportes se enviarán automáticamente** al email especificado.

---

## Preguntas Frecuentes

### ¿Cómo priorizo los mantenimientos urgentes?

Al crear un mantenimiento, selecciona **"Prioridad: Crítica"** o **"Tipo: Emergencia"**. Estos aparecerán destacados en la lista.

### ¿Puedo ver qué salones están disponibles ahora?

Sí, en el listado de salones, usa el filtro **"Disponibilidad: Ahora"** para ver solo los salones libres en este momento.

### ¿Cómo bloqueo un salón temporalmente?

Edita el salón y cambia su estado a **"Inactivo"**. Ingresa el motivo (ej: "Remodelación hasta 31/12"). Recuerda reactivarlo cuando termine.

### ¿Puedo duplicar una reserva recurrente?

Sí, al crear una reserva, marca la opción **"Reserva recurrente"** y selecciona la frecuencia (diaria, semanal, mensual).

### ¿Qué hago si hay conflicto de reservas?

El sistema detecta conflictos automáticamente. Contacta a los solicitantes para negociar o busca un salón alternativo.

### ¿Cómo asigno múltiples responsables a un mantenimiento?

En el campo **"Responsable"**, puedes agregar varios nombres separados por comas o seleccionar un equipo predefinido.

### ¿Los reportes incluyen datos históricos?

Sí, los reportes pueden incluir datos desde el inicio del sistema. Usa los filtros de rango de fechas.

### ¿Puedo exportar la lista de salones?

Sí, en el listado de salones, haz clic en **"Exportar"** (esquina superior) y elige el formato (Excel, PDF, CSV).

### ¿Cómo notific o a profesores sobre mantenimientos?

Al programar un mantenimiento, marca **"Notificar a coordinación académica"**. La coordinación informará a los profesores afectados.

### ¿Puedo ver fotos del estado de los salones?

Sí, en la vista detallada de cada salón, pestaña **"Galería"**, encontrarás fotos del salón y su equipamiento.

---

## 📞 Soporte

### Contacto Técnico

- **Email**: infraestructura@universidad.edu
- **Teléfono**: +XX XXX XXX XXXX Ext. 123
- **Horario**: Lunes a Viernes, 7:00 AM - 6:00 PM

### Emergencias

Para problemas críticos de infraestructura (fugas, fallos eléctricos, etc.):
- **Línea de Emergencia**: +XX XXX XXX XXXX
- **Disponible**: 24/7

---

**© 2025 Sistema de Asignación de Salones | Universidad**  
*Versión 2.1 - Diciembre 2025*
