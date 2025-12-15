# 👨‍💼 Manual de Usuario - Administrador

**Sistema de Asignación de Salones**  
**Versión 2.1** | Diciembre 2025

---

## 📋 Índice

1. [Introducción](#introducción)
2. [Acceso al Sistema](#acceso-al-sistema)
3. [Panel Principal (Dashboard)](#panel-principal-dashboard)
4. [Gestión de Usuarios](#gestión-de-usuarios)
5. [Sistema de Auditoría](#sistema-de-auditoría)
6. [Reportes Administrativos](#reportes-administrativos)
7. [Configuración del Sistema](#configuración-del-sistema)
8. [Copias de Seguridad](#copias-de-seguridad)
9. [Preguntas Frecuentes](#preguntas-frecuentes)

---

## Introducción

### ¿Qué es el Panel de Administración?

El panel de administración te otorga control total sobre el Sistema de Asignación de Salones. Como **Administrador**, tienes acceso a:

- ✅ Gestión completa de usuarios y roles
- ✅ Sistema de auditoría y trazabilidad
- ✅ Reportes estadísticos avanzados
- ✅ Configuración global del sistema
- ✅ Monitoreo de actividad y seguridad
- ✅ Copias de seguridad y recuperación

### Responsabilidades del Administrador

- 🔐 **Seguridad**: Gestionar accesos y permisos
- 👥 **Usuarios**: Crear, modificar y desactivar cuentas
- 📊 **Monitoreo**: Supervisar uso del sistema
- 🔍 **Auditoría**: Revisar logs de actividad
- ⚙️ **Configuración**: Mantener parámetros del sistema
- 💾 **Respaldos**: Garantizar integridad de datos

---

## Acceso al Sistema

### Inicio de Sesión

1. Accede a la URL administrativa del sistema
2. Ingresa tus credenciales de administrador:
   ```
   📧 Email: admin@universidad.edu
   🔑 Contraseña: [tu contraseña segura]
   ```
3. **Autenticación de dos factores** (si está habilitada):
   - Ingresa el código de 6 dígitos desde tu app autenticadora
4. Haz clic en **"Iniciar Sesión"**

### Seguridad de la Cuenta

**Recomendaciones**:
- ✅ Usa contraseñas de al menos 12 caracteres
- ✅ Activa autenticación de dos factores (2FA)
- ✅ Cambia tu contraseña cada 3 meses
- ✅ No compartas credenciales
- ✅ Cierra sesión al terminar

---

## Panel Principal (Dashboard)

### Vista General

El dashboard administrativo muestra:

#### 📊 Métricas Globales

| Métrica | Descripción | Ejemplo |
|---------|-------------|---------|
| **Usuarios Activos** | Total de cuentas activas | 350 |
| **Sesiones Activas** | Usuarios conectados ahora | 45 |
| **Salones Totales** | Inventario completo | 48 |
| **Asignaciones Activas** | Cursos en semestre actual | 120 |
| **Mantenimientos Pendientes** | Tareas sin completar | 8 |
| **Acciones de Auditoría (24h)** | Eventos registrados hoy | 1,247 |

#### 📈 Gráficos de Actividad

- **Uso del Sistema**: Gráfico de línea mostrando accesos por hora/día
- **Distribución de Roles**: Gráfico circular (Profesores, Coordinadores, Admin)
- **Salones por Estado**: Barras (Activos, En Mantenimiento, Inactivos)
- **Tendencias de Reservas**: Línea temporal de reservas por semana

#### ⚠️ Alertas y Notificaciones

Panel de alertas críticas:

| Tipo | Ejemplo | Acción |
|------|---------|--------|
| 🔴 **Crítico** | Fallo en backup automático | Revisar logs |
| 🟠 **Advertencia** | Salón sin mantenimiento >6 meses | Programar |
| 🟡 **Info** | Nueva actualización disponible | Actualizar |
| 🔵 **Éxito** | Backup completado correctamente | Verificar |

---

## Gestión de Usuarios

### Acceder al Módulo

Menú lateral → **"👥 Usuarios"** o Dashboard → **"Gestión de Usuarios"**

### Vista de Listado

#### 📊 Estadísticas de Usuarios

| Métrica | Valor |
|---------|-------|
| **Total Usuarios** | 350 |
| **Profesores** | 85 |
| **Coordinadores** | 12 |
| **Administradores** | 3 |
| **Estudiantes** | 250 |

#### 🔍 Filtros

| Filtro | Opciones |
|--------|----------|
| **Rol** | Todos, Profesor, Coordinador, Administrador, Estudiante |
| **Estado** | Activo, Inactivo, Suspendido |
| **Búsqueda** | Por nombre, email o código |
| **Fecha de Registro** | Rango desde/hasta |
| **Último Acceso** | Activos hoy, última semana, último mes |

#### 📋 Tabla de Usuarios

| Columna | Contenido |
|---------|-----------|
| **ID** | Identificador único |
| **Código** | Código institucional (ej: T-0001, E-1234) |
| **Nombre Completo** | Nombre y apellidos |
| **Email** | Correo electrónico |
| **Rol** | Badge con color de rol |
| **Estado** | Activo/Inactivo/Suspendido |
| **Último Acceso** | Fecha y hora |
| **Acciones** | Ver, Editar, Desactivar |

**Badges de rol**:
- 🟣 **Administrador**: Morado
- 🔵 **Coordinador**: Azul
- 🟢 **Profesor**: Verde
- 🟡 **Estudiante**: Amarillo

### Crear Nuevo Usuario

1. **Haz clic** en **"➕ Crear Usuario"**

2. **Información Básica**:

   | Campo | Descripción | Ejemplo |
   |-------|-------------|---------|
   | **Código*** | ID institucional | T-0089 |
   | **Nombre*** | Nombres completos | Carlos Alberto |
   | **Apellidos*** | Apellidos completos | Rodríguez Pérez |
   | **Email*** | Correo institucional | carlos.rodriguez@universidad.edu |
   | **Teléfono** | Número de contacto | +XX XXX XXX XXXX |
   | **Fecha de Nacimiento** | DD/MM/AAAA | 15/05/1985 |

3. **Información de Cuenta**:

   | Campo | Descripción | Ejemplo |
   |-------|-------------|---------|
   | **Rol*** | Tipo de usuario | Profesor |
   | **Estado*** | Estado inicial | Activo |
   | **Contraseña Temporal*** | Contraseña inicial | Temp2025! |
   | **Forzar Cambio de Contraseña** | ☑️ Sí | ✅ |

4. **Información Adicional** (según rol):

   **Si es Profesor**:
   - Especialidad
   - Departamento
   - Tipo de contrato
   - Fecha de inicio

   **Si es Coordinador**:
   - Área de responsabilidad
   - Departamento

5. **Permisos Especiales** (opcional):
   - ☐ Acceso a reportes avanzados
   - ☐ Gestión de salones
   - ☐ Aprobación de reservas

6. **Haz clic** en **"Crear Usuario"**

**El usuario recibirá un email** con sus credenciales temporales.

### Editar Usuario

1. Localiza al usuario en la tabla
2. Haz clic en **✏️ "Editar"**
3. Modifica los campos necesarios
4. **Guarda cambios**

**Cambios comunes**:
- Actualizar datos de contacto
- Cambiar rol o permisos
- Modificar estado (Activo/Inactivo)
- Resetear contraseña

### Cambiar Rol de Usuario

1. Edita el usuario
2. En **"Rol"**, selecciona el nuevo rol
3. ⚠️ **Advertencia**: Esto cambiará los permisos del usuario
4. Confirma el cambio
5. Guarda

**Impacto por rol**:
- **Profesor → Coordinador**: Obtiene acceso a gestión de infraestructura
- **Coordinador → Administrador**: Obtiene acceso total al sistema
- **Profesor → Estudiante**: Pierde acceso a gestión académica

### Desactivar/Suspender Usuario

#### Desactivar (Temporal)

1. Haz clic en **"Desactivar"**
2. Ingresa motivo:
   - "Periodo sabático"
   - "Licencia médica"
   - "Fin de contrato temporal"
3. (Opcional) Fecha de reactivación automática
4. Confirma

**Efecto**: El usuario no puede acceder pero sus datos permanecen.

#### Suspender (Sanción)

1. Haz clic en **"Suspender"**
2. **Obligatorio**: Ingresa motivo disciplinario
3. Duración de suspensión (días)
4. Notificar al usuario (☑️)
5. Confirma

**Efecto**: Similar a desactivar, pero queda registrado en auditoría.

### Eliminar Usuario

⚠️ **CUIDADO**: Esta acción es irreversible.

1. Haz clic en **"Eliminar"**
2. **Doble confirmación requerida**
3. Ingresa tu contraseña de administrador
4. Ingresa motivo de eliminación
5. Confirma

**Recomendación**: En lugar de eliminar, desactiva usuarios para mantener historial.

### Resetear Contraseña

1. Localiza al usuario
2. Haz clic en **"Resetear Contraseña"**
3. Opciones:
   - **Generar automática**: Sistema crea contraseña segura
   - **Ingresar manual**: Tú defines la contraseña
4. ☑️ **Forzar cambio en primer inicio**
5. ☑️ **Enviar por correo al usuario**
6. Confirma

---

## Sistema de Auditoría

### ¿Qué es la Auditoría?

El sistema de auditoría registra **todas las acciones** realizadas en el sistema:

- ✅ Creación, modificación y eliminación de registros
- ✅ Inicios de sesión y cierres de sesión
- ✅ Cambios de configuración
- ✅ Exportación de reportes
- ✅ Accesos a información sensible

### Acceder a Auditoría

Menú lateral → **"🔍 Auditoría"** o Dashboard → **"Logs de Auditoría"**

### Vista de Auditoría

#### 📊 Estadísticas de Auditoría

| Métrica | Descripción |
|---------|-------------|
| **Acciones Hoy** | Eventos registrados en las últimas 24h |
| **Acciones Esta Semana** | Eventos de los últimos 7 días |
| **Usuarios Activos** | Usuarios con actividad reciente |
| **Accesos Fallidos** | Intentos de inicio de sesión fallidos |

#### 🔍 Filtros de Auditoría

| Filtro | Opciones/Descripción |
|--------|---------------------|
| **Acción** | created, updated, deleted, login, logout, exported |
| **Modelo** | User, Assignment, Student, Teacher, Classroom, etc. |
| **Usuario** | Búsqueda por nombre o email |
| **Rango de Fechas** | Desde/Hasta (con selector de calendario) |
| **Dirección IP** | Filtrar por IP específica |
| **Búsqueda** | Texto libre en descripción |

#### 📋 Tabla de Auditoría

| Columna | Contenido |
|---------|-----------|
| **ID** | Número de registro |
| **Fecha/Hora** | Timestamp exacto |
| **Usuario** | Quién realizó la acción |
| **Acción** | Tipo de evento (badge con color) |
| **Modelo** | Tipo de registro afectado |
| **Descripción** | Resumen del evento |
| **IP** | Dirección IP del usuario |
| **Detalles** | Botón para ver información completa |

**Badges de acción**:
- 🟢 **created**: Verde - Creación
- 🔵 **updated**: Azul - Actualización
- 🔴 **deleted**: Rojo - Eliminación
- 🟣 **login**: Morado - Inicio de sesión
- 🟡 **exported**: Amarillo - Exportación

### Ver Detalles de Auditoría

1. En la tabla, haz clic en **"Ver Detalles"**
2. Se muestra modal con información completa:

   **Información General**:
   - Fecha y hora exacta
   - Usuario responsable (nombre, email, rol)
   - Dirección IP y navegador (User Agent)
   - Tipo de acción

   **Datos Afectados**:
   - Modelo: Tipo de registro
   - ID del registro: Identificador único
   - Descripción: Resumen legible

   **Valores Anteriores** (si aplica):
   ```json
   {
     "first_name": "Carlos",
     "last_name": "Rodríguez",
     "email": "carlos@universidad.edu"
   }
   ```

   **Valores Nuevos** (si aplica):
   ```json
   {
     "first_name": "Carlos Alberto",
     "last_name": "Rodríguez Pérez",
     "email": "carlos.rodriguez@universidad.edu"
   }
   ```

### Ejemplos de Eventos de Auditoría

#### Ejemplo 1: Creación de Usuario

```
👤 Admin Usuario (admin@universidad.edu)
📅 2025-12-15 10:30:25
🟢 created
📝 Usuario creado: Profesor Carlos Alberto (T-0089)
🌐 IP: 192.168.1.100
```

#### Ejemplo 2: Actualización de Asignación

```
👤 Coord. Académico (coord@universidad.edu)
📅 2025-12-15 11:45:12
🔵 updated
📝 Asignación actualizada: Programación I - Grupo A (Prof. Carlos)
🌐 IP: 192.168.1.105

Cambios:
- classroom_id: 15 → 18 (Cambio de salón)
- schedule: "Lun 08:00" → "Lun 10:00" (Cambio de horario)
```

#### Ejemplo 3: Eliminación de Estudiante

```
👤 Admin Usuario (admin@universidad.edu)
📅 2025-12-15 14:20:30
🔴 deleted
📝 Estudiante eliminado: María García (E-1234)
🌐 IP: 192.168.1.100
⚠️ Motivo: Retiro voluntario del estudiante
```

#### Ejemplo 4: Exportación de Reporte

```
👤 Profesor López (lopez@universidad.edu)
📅 2025-12-15 16:00:45
🟡 exported
📝 Reporte exportado: Actividades de Programación I
🌐 IP: 192.168.1.120
📄 Archivo: actividades_PROG-101_20251215.pdf
```

### Exportar Logs de Auditoría

1. Aplica los filtros deseados
2. Haz clic en **"Exportar Logs"**
3. Selecciona formato:
   - 📄 **PDF**: Reporte formateado
   - 📊 **Excel**: Para análisis
   - 📝 **CSV**: Para procesamiento
   - 🔧 **JSON**: Para integración con otros sistemas
4. El archivo se descarga automáticamente

### Alertas de Auditoría

#### Configurar Alertas

1. Ve a **"Configuración de Auditoría"**
2. Haz clic en **"+ Nueva Alerta"**
3. Configura:
   - **Tipo de evento**: ej. "Eliminación de usuarios"
   - **Condición**: ej. "Más de 5 intentos de login fallidos"
   - **Acción**: Enviar email a admin
   - **Destinatarios**: admin@universidad.edu
4. Guarda

**Alertas predefinidas**:
- 🔴 Múltiples intentos de inicio de sesión fallidos
- 🟠 Eliminación masiva de registros
- 🟡 Cambios en configuración crítica del sistema
- 🔵 Acceso desde IP desconocida

---

## Reportes Administrativos

### Acceder a Reportes

Menú → **"📊 Reportes"** → **"Reportes Administrativos"**

### Tipos de Reportes

#### 1. 📊 Reporte de Utilización de Recursos

**URL**: `/admin/reports/utilization`

**Información mostrada**:

**A. Utilización de Profesores**

Estadísticas superiores:

| Métrica | Descripción |
|---------|-------------|
| **Total Profesores** | Cantidad de profesores activos |
| **Horas Asignadas** | Total de horas lectivas |
| **Promedio Horas/Profesor** | Media de carga docente |
| **Profesores Disponibles** | Con disponibilidad para más clases |

Tabla de profesores:

| Columna | Contenido |
|---------|-------------|
| **Código** | Código del profesor (ej: T-0001) |
| **Nombre** | Nombre completo |
| **Email** | Correo electrónico |
| **Cursos** | Número de asignaciones |
| **Horas Semanales** | Total de horas lectivas |
| **Estudiantes** | Total de estudiantes |
| **Carga** | Barra de progreso visual (0-100%) |

**B. Utilización de Salones**

Estadísticas superiores:

| Métrica | Descripción |
|---------|-------------|
| **Total Salones** | Cantidad de salones disponibles |
| **Horas Ocupadas** | Total de horas de uso |
| **Promedio Ocupación** | Porcentaje promedio de uso |
| **Salones Subutilizados** | Con menos de 60% de ocupación |

Tabla de salones:

| Columna | Contenido |
|---------|-------------|
| **Código** | Código del salón |
| **Nombre** | Nombre descriptivo |
| **Edificio** | Ubicación |
| **Capacidad** | Número de estudiantes |
| **Horas Ocupadas** | Horas semanales usadas |
| **Horas Disponibles** | Horas semanales disponibles |
| **Ocupación** | Barra de progreso (0-100%) |

**Filtros**:
- Período: Semana actual, Mes, Semestre, Año
- Edificio: Todos o específico
- Departamento: Todos o específico

**Exportar**:
- 📄 PDF: Reporte completo formateado
- 📊 Excel: Datos tabulares para análisis

#### 2. 👥 Reporte de Actividad de Usuarios

**Información mostrada**:
- Usuarios más activos
- Accesos por hora del día
- Accesos por día de la semana
- Tiempo promedio de sesión
- Funcionalidades más usadas

**Filtros**:
- Rango de fechas
- Rol de usuario
- Acción específica

#### 3. 💰 Reporte Financiero (Mantenimiento)

**Información mostrada**:
- Costos totales de mantenimiento
- Desglose por tipo (preventivo, correctivo, emergencia)
- Costos por edificio
- Costos por mes
- Proyección de gastos

**Gráficos**:
- Línea temporal de gastos
- Torta por tipo de mantenimiento
- Barras por edificio

#### 4. 📈 Reporte de Tendencias

**Información mostrada**:
- Crecimiento de usuarios
- Aumento de asignaciones
- Incremento de reservas
- Evolución de mantenimientos

**Útil para**:
- Planificación de recursos
- Presupuesto futuro
- Identificación de tendencias

### Dashboards Personalizados

#### Crear Dashboard Personalizado

1. Ve a **"Reportes"** → **"Dashboards Personalizados"**
2. Haz clic en **"+ Nuevo Dashboard"**
3. **Nombre**: ej. "Dashboard Ejecutivo"
4. **Agregar widgets**:
   - Gráfico de barras: Salones por edificio
   - Número: Total de usuarios activos
   - Gráfico de línea: Reservas por mes
   - Tabla: Top 10 salones más usados
5. **Organiza widgets** (arrastrar y soltar)
6. **Guarda** el dashboard

#### Compartir Dashboard

1. Abre el dashboard personalizado
2. Haz clic en **"Compartir"**
3. Opciones:
   - Generar link público (solo lectura)
   - Compartir con usuarios específicos
   - Exportar como PDF
   - Programar envío por email

---

## Configuración del Sistema

### Acceder a Configuración

Menú → **"⚙️ Configuración"** → **"Configuración del Sistema"**

### Secciones de Configuración

#### 1. ⚙️ General

| Configuración | Descripción | Ejemplo |
|---------------|-------------|---------|
| **Nombre del Sistema** | Título mostrado | "Sistema de Asignación de Salones" |
| **Nombre de la Institución** | Universidad/Colegio | "Universidad Nacional" |
| **Logo** | Imagen del encabezado | Subir archivo PNG/JPG |
| **Zona Horaria** | Configuración regional | America/La_Paz |
| **Idioma Predeterminado** | Idioma del sistema | Español |
| **Formato de Fecha** | DD/MM/AAAA o MM/DD/AAAA | DD/MM/AAAA |

#### 2. 🔐 Seguridad

| Configuración | Descripción | Valor Recomendado |
|---------------|-------------|-------------------|
| **Longitud Mínima Contraseña** | Caracteres mínimos | 8 |
| **Requiere Mayúsculas** | ☑️ Sí/☐ No | ✅ Sí |
| **Requiere Números** | ☑️ Sí/☐ No | ✅ Sí |
| **Requiere Caracteres Especiales** | ☑️ Sí/☐ No | ✅ Sí |
| **Expiración de Contraseña** | Días antes de cambio forzado | 90 días |
| **Intentos de Login** | Máximo antes de bloqueo | 5 intentos |
| **Tiempo de Bloqueo** | Minutos de bloqueo tras exceder intentos | 30 minutos |
| **Autenticación de Dos Factores** | ☑️ Obligatoria/☐ Opcional | ☑️ Obligatoria (para admins) |
| **Sesión Inactiva** | Minutos antes de cierre automático | 30 minutos |

#### 3. 📧 Email

| Configuración | Descripción | Ejemplo |
|---------------|-------------|---------|
| **Servidor SMTP** | Host del servidor | smtp.gmail.com |
| **Puerto** | Puerto SMTP | 587 |
| **Usuario** | Email de envío | sistema@universidad.edu |
| **Contraseña** | Contraseña del email | ********** |
| **Encriptación** | TLS/SSL | TLS |
| **Email Remitente** | "De:" en emails | Sistema de Asignación |
| **Email de Prueba** | Botón para enviar email de prueba | Enviar Prueba |

#### 4. 📅 Académico

| Configuración | Descripción | Ejemplo |
|---------------|-------------|---------|
| **Semestre Actual** | Período académico | 2025-1 |
| **Fecha Inicio Semestre** | Inicio de clases | 2025-03-01 |
| **Fecha Fin Semestre** | Fin de clases | 2025-07-31 |
| **Duración Período Clase** | Minutos por período | 50 minutos |
| **Horarios** | Bloques horarios | 08:00-09:50, 10:00-11:50, etc. |

#### 5. 🏢 Infraestructura

| Configuración | Descripción |
|---------------|-------------|
| **Mantenimiento Preventivo** | Frecuencia (meses) |
| **Anticipación Reservas** | Días mínimos de anticipación |
| **Duración Mínima Reserva** | Minutos mínimos |
| **Duración Máxima Reserva** | Horas máximas |

### Guardar Configuración

1. Modifica los valores deseados
2. Haz clic en **"Guardar Configuración"**
3. ⚠️ **Advertencia**: Algunos cambios requieren reinicio del sistema
4. Confirma la acción

---

## Copias de Seguridad

### ¿Por Qué Son Importantes?

Las copias de seguridad (backups) protegen contra:
- 💾 Pérdida de datos por fallo técnico
- 🔥 Desastres naturales
- 🔒 Ataques cibernéticos
- ❌ Errores humanos

### Acceder a Backups

Menú → **"💾 Copias de Seguridad"**

### Crear Backup Manual

1. Haz clic en **"+ Crear Backup Ahora"**
2. Selecciona qué incluir:
   - ☑️ **Base de datos**: Todos los registros
   - ☑️ **Archivos subidos**: PDFs, imágenes, etc.
   - ☑️ **Configuración**: Ajustes del sistema
   - ☑️ **Logs de auditoría**: Histórico completo
3. Ingresa **descripción** (opcional):
   - ej. "Backup antes de actualización a v2.2"
4. Haz clic en **"Crear Backup"**
5. **Tiempo estimado**: 5-15 minutos dependiendo del tamaño

### Programar Backups Automáticos

1. Haz clic en **"Configurar Backups Automáticos"**
2. **Frecuencia**:
   - Diaria (recomendado)
   - Semanal
   - Mensual
3. **Hora de ejecución**: ej. 02:00 AM (baja actividad)
4. **Retención**: Cuántas copias mantener
   - Diarias: últimas 7
   - Semanales: últimas 4
   - Mensuales: últimas 6
5. **Destino**:
   - ☑️ Servidor local
   - ☑️ Almacenamiento en la nube
   - ☑️ Servidor remoto (FTP/SFTP)
6. **Notificaciones**:
   - ☑️ Enviar email al completar
   - ☑️ Alertar si falla
7. Guarda la configuración

### Lista de Backups

Tabla con todos los backups:

| Columna | Contenido |
|---------|-----------|
| **Fecha** | Cuándo se creó |
| **Tipo** | Manual/Automático |
| **Tamaño** | MB/GB del archivo |
| **Descripción** | Nota opcional |
| **Estado** | Completo/En Progreso/Fallido |
| **Acciones** | Descargar, Restaurar, Eliminar |

### Restaurar Backup

⚠️ **ADVERTENCIA**: Esto **sobrescribirá** todos los datos actuales.

1. Localiza el backup a restaurar
2. Haz clic en **"Restaurar"**
3. **Triple confirmación**:
   - Confirmación 1: Entiende que datos actuales se perderán
   - Confirmación 2: Ingresa tu contraseña de administrador
   - Confirmación 3: Escribe "RESTAURAR" en mayúsculas
4. Haz clic en **"Confirmar Restauración"**
5. **El sistema se pondrá en modo mantenimiento**
6. Proceso de restauración (puede tomar varios minutos)
7. El sistema reiniciará automáticamente
8. Verifica que todo funciona correctamente

### Descargar Backup

1. Haz clic en **"Descargar"** en el backup deseado
2. El archivo `.zip` se descargará
3. **Guárdalo en lugar seguro** (disco externo, nube)

**Contenido del ZIP**:
- `database.sql`: Dump de la base de datos
- `uploads/`: Carpeta con archivos subidos
- `config/`: Archivos de configuración
- `audit-logs/`: Registros de auditoría
- `README.txt`: Información del backup

---

## Preguntas Frecuentes

### ¿Cómo restablezco la contraseña de un usuario que la olvidó?

Ve a **"Usuarios"**, localiza al usuario, haz clic en **"Resetear Contraseña"**, y elige si generar automática o manual. Marca **"Enviar por correo"** para que el usuario la reciba.

### ¿Puedo ver qué hizo un usuario específico en el sistema?

Sí, ve a **"Auditoría"**, filtra por el nombre o email del usuario, y verás todas sus acciones registradas.

### ¿Qué hago si hay múltiples intentos de login fallidos?

Esto se registra en auditoría. Si es un ataque, puedes:
1. Bloquear la IP en configuración de seguridad
2. Forzar cambio de contraseña al usuario afectado
3. Activar 2FA obligatorio

### ¿Con qué frecuencia debo hacer backups?

**Recomendación**:
- **Backups automáticos diarios** (02:00 AM)
- **Backups manuales** antes de actualizar el sistema
- **Retención**: 7 diarios, 4 semanales, 6 mensuales

### ¿Puedo eliminar logs de auditoría antiguos?

Técnicamente sí, pero **NO recomendado**. Los logs son críticos para:
- Cumplimiento normativo
- Investigación de incidentes
- Análisis de uso

Si es necesario por espacio, archiva logs de más de 1 año en almacenamiento externo antes de eliminar.

### ¿Cómo agrego un nuevo rol personalizado?

Actualmente el sistema tiene roles predefinidos (Admin, Coordinador, Profesor, Estudiante). Para roles personalizados, contacta al equipo de desarrollo para evaluar la implementación.

### ¿Puedo programar reportes automáticos?

Sí, en cada tipo de reporte hay opción **"Programar Envío Automático"**. Configura frecuencia y destinatarios.

### ¿Qué hago si un reporte muestra datos incorrectos?

1. Verifica los filtros aplicados
2. Revisa auditoría por cambios recientes en esos datos
3. Contacta soporte con captura de pantalla del reporte

### ¿Puedo revocar el acceso de un administrador?

Sí, pero **requiere al menos 2 administradores activos**. No puedes dejar el sistema sin ningún administrador.

### ¿Los cambios en configuración se auditan?

Sí, **todos** los cambios de configuración quedan registrados en auditoría con los valores anteriores y nuevos.

---

## 🚨 Problemas Comunes y Soluciones

### Sistema Lento

**Causas posibles**:
- Base de datos muy grande
- Muchos usuarios conectados simultáneamente
- Logs de auditoría excesivos

**Soluciones**:
1. Ejecutar **"Optimizar Base de Datos"** (Configuración → Mantenimiento)
2. Archivar logs de auditoría antiguos
3. Revisar configuración de servidor

### No Llegan Emails

**Verificar**:
1. Configuración SMTP correcta
2. Usuario y contraseña válidos
3. Puerto y encriptación correctos
4. Enviar **"Email de Prueba"** desde configuración
5. Revisar carpeta de spam del destinatario

### Backup Falla

**Verificar**:
1. Espacio suficiente en disco
2. Permisos de escritura en carpeta de backups
3. Revisar logs de error (último error mostrado)
4. Verificar que servicios necesarios están corriendo

### Usuario No Puede Iniciar Sesión

**Verificar**:
1. Estado de la cuenta (¿Activa?)
2. Contraseña no expirada
3. No excedió intentos de login fallidos
4. Email correcto (sin espacios extra)

---

## 📞 Soporte y Contacto

### Soporte Técnico

- **Email**: soporte.tecnico@universidad.edu
- **Teléfono**: +XX XXX XXX XXXX Ext. 100
- **Horario**: Lunes a Viernes, 7:00 AM - 7:00 PM

### Soporte de Emergencia

Para problemas críticos (sistema caído, violación de seguridad):
- **Línea Directa**: +XX XXX XXX XXXX
- **Email**: emergencia@universidad.edu
- **Disponible**: 24/7

### Documentación Técnica

- **Wiki del Proyecto**: https://wiki.universidad.edu/sistema-asignacion
- **Documentación API**: https://api.universidad.edu/docs
- **Repositorio GitHub**: (si aplica)

---

## 📚 Recursos Adicionales

### Guías Relacionadas

- [Manual de Usuario - Profesor](MANUAL_USUARIO_PROFESOR.md)
- [Manual de Usuario - Coordinador Infraestructura](MANUAL_USUARIO_INFRAESTRUCTURA.md)
- [Guía de Configuración](GUIA_CONFIGURACION.md)
- [Guía de Despliegue Local](GUIA_DESPLIEGUE_LOCAL.md)

### Videos Tutoriales

- Tutorial: Gestión de Usuarios
- Tutorial: Sistema de Auditoría
- Tutorial: Configuración de Backups
- Tutorial: Reportes Administrativos

---

**© 2025 Sistema de Asignación de Salones | Universidad**  
*Versión 2.1 - Diciembre 2025*

**Clasificación**: Uso Interno | Confidencial  
**Última Actualización**: Diciembre 2025
