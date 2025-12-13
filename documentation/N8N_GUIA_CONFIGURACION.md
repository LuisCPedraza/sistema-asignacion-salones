# 🚀 Guía de Configuración: Integración n8n - Sistema de Asignación de Salones

## 📋 Índice
1. [Prerrequisitos](#prerrequisitos)
2. [Instalación de n8n](#instalación-de-n8n)
3. [Configuración de Base de Datos](#configuración-de-base-de-datos)
4. [Workflow 1: Notificación de Asignación Creada](#workflow-1-notificación-de-asignación-creada)
5. [Workflow 2: Detección de Conflictos Horarios](#workflow-2-detección-de-conflictos-horarios)
6. [Workflow 3: Recordatorios de Disponibilidades](#workflow-3-recordatorios-de-disponibilidades)
7. [Configuración Final en Laravel](#configuración-final-en-laravel)
8. [Testing y Verificación](#testing-y-verificación)
9. [Troubleshooting](#troubleshooting)

---

## 1. Prerrequisitos

### ✅ Verificar que tienes:
- [ ] Docker instalado (recomendado) o Node.js 16+
- [ ] Acceso a la base de datos PostgreSQL o SQLite del proyecto
- [ ] Laravel corriendo en `http://localhost:8000`
- [ ] Cuenta de Gmail para enviar emails (o servidor SMTP)
- [ ] Editor de código (VS Code recomendado)

### 📦 Verificar Migraciones

Ejecuta las migraciones para crear las tablas necesarias:

\`\`\`bash
# En Windows (cmd)
cd \\wsl.localhost\\Ubuntu\\home\\suario\\proyectos\\sistema-asignacion-salones
php artisan migrate

# Verifica que se crearon las tablas:
# - audit_logs
# - conflict_alerts
\`\`\`

---

## 2. Instalación de n8n

### Opción A: Docker (Recomendado)

\`\`\`bash
# Crear carpeta para n8n
mkdir C:\\n8n-data

# Ejecutar n8n en Docker
docker run -d ^
  --name n8n ^
  -p 5678:5678 ^
  -v C:\\n8n-data:/home/node/.n8n ^
  n8nio/n8n

# Verificar que está corriendo
docker ps | findstr n8n
\`\`\`

### Opción B: npm (Alternativa)

\`\`\`bash
# Instalar n8n globalmente
npm install n8n -g

# Iniciar n8n
n8n start
\`\`\`

### ✅ Verificación

1. Abre tu navegador en: **http://localhost:5678**
2. Crea una cuenta (primera vez)
3. Deberías ver el dashboard de n8n

---

## 3. Configuración de Base de Datos

### Paso 1: Crear Credenciales PostgreSQL/SQLite en n8n

1. En n8n, ve a: **Settings** → **Credentials** → **Add Credential**
2. Busca: **Postgres** (si usas SQLite, busca **SQLite**)
3. Configura:

#### Para PostgreSQL (Producción):
\`\`\`
Host: localhost (o la IP de tu servidor)
Database: asignacion_salones
User: tu_usuario
Password: tu_password
Port: 5432
SSL Mode: disable (para desarrollo local)
\`\`\`

#### Para SQLite (Local):
\`\`\`
Database Path: C:\\ruta\\a\\tu\\proyecto\\database\\database.sqlite
\`\`\`

4. Haz clic en **Save** y dale un nombre: `SAS_Database`

### Paso 2: Configurar Gmail (o SMTP)

1. En n8n: **Settings** → **Credentials** → **Add Credential**
2. Busca: **Gmail OAuth2** (o **SMTP** si prefieres)
3. Para Gmail OAuth2:
   - Sigue las instrucciones en pantalla
   - Autoriza el acceso a tu cuenta
4. Guarda con el nombre: `Gmail_SAS`

---

## 4. Workflow 1: Notificación de Asignación Creada

### 📌 Objetivo
Enviar email al profesor cuando se le asigna un grupo

### 🛠️ Pasos

#### 1. Crear Nuevo Workflow

1. En n8n, haz clic en **New Workflow**
2. Nombre: `[Workflow 1] Notificación Asignación Creada`

#### 2. Agregar Nodo Webhook (Trigger)

1. Agrega nodo: **Webhook**
2. Configuración:
   - **HTTP Method**: POST
   - **Path**: `assignment-created`
   - **Authentication**: None (por ahora)
3. **Guarda** el workflow para obtener la URL

La URL será algo como: `http://localhost:5678/webhook/assignment-created`

#### 3. Agregar Nodo PostgreSQL - Obtener Profesor

1. Agrega nodo: **Postgres** (o **SQLite**)
2. Conecta desde el webhook
3. Configuración:
   - **Credential**: `SAS_Database`
   - **Operation**: Execute Query
   - **Query**:
\`\`\`sql
SELECT 
  id, 
  first_name, 
  last_name, 
  email, 
  phone 
FROM teachers 
WHERE id = {{ $json.body.teacher_id }}
LIMIT 1;
\`\`\`

#### 4. Agregar Nodo PostgreSQL - Obtener Grupo

1. Agrega otro nodo: **Postgres**
2. Configuración:
   - **Query**:
\`\`\`sql
SELECT 
  id, 
  name, 
  academic_year 
FROM student_groups 
WHERE id = {{ $json.body.group_id }}
LIMIT 1;
\`\`\`

#### 5. Agregar Nodo PostgreSQL - Obtener Salón

1. Agrega otro nodo: **Postgres**
2. Configuración:
   - **Query**:
\`\`\`sql
SELECT 
  id, 
  name, 
  capacity, 
  floor,
  building
FROM classrooms 
WHERE id = {{ $json.body.classroom_id }}
LIMIT 1;
\`\`\`

#### 6. Agregar Nodo Code - Construir Email

1. Agrega nodo: **Code**
2. Configuración:
   - **Mode**: Run Once for All Items
   - **Language**: JavaScript
   - **Code**:

\`\`\`javascript
// Obtener datos del webhook
const webhook = $('Webhook').first().json.body;

// Obtener datos de los queries
const teacher = $('Postgres').first().json;
const group = $('Postgres1').first().json;
const classroom = $('Postgres2').first().json;

// Construir email
return {
  json: {
    to: teacher.email,
    subject: \`✅ Asignación Confirmada - Grupo \${group.name}\`,
    html: \`
      <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
        <h2 style="color: #2c3e50;">¡Asignación Exitosa!</h2>
        
        <p>Estimado/a <strong>\${teacher.first_name} \${teacher.last_name}</strong>,</p>
        
        <p>Se le ha asignado el siguiente grupo:</p>
        
        <div style="background-color: #f8f9fa; padding: 20px; border-left: 4px solid #28a745; margin: 20px 0;">
          <h3 style="margin-top: 0;">📚 Detalles de la Asignación</h3>
          <ul style="list-style: none; padding: 0;">
            <li><strong>📝 Grupo:</strong> \${group.name}</li>
            <li><strong>🏫 Salón:</strong> \${classroom.name} - \${classroom.building}</li>
            <li><strong>📍 Piso:</strong> \${classroom.floor}</li>
            <li><strong>👥 Capacidad:</strong> \${classroom.capacity} estudiantes</li>
            <li><strong>📅 Día:</strong> \${webhook.day}</li>
            <li><strong>⏰ Horario:</strong> \${webhook.start_time} - \${webhook.end_time}</li>
          </ul>
        </div>
        
        <p style="color: #6c757d; font-size: 14px;">
          Este es un mensaje automático del Sistema de Asignación de Salones.
        </p>
        
        <p>Saludos cordiales,<br><strong>Sistema de Asignación de Salones</strong></p>
      </div>
    \`
  }
};
\`\`\`

#### 7. Agregar Nodo Gmail - Enviar Email

1. Agrega nodo: **Gmail** (o **Send Email** si usas SMTP)
2. Configuración:
   - **Credential**: `Gmail_SAS`
   - **Resource**: Message
   - **Operation**: Send
   - **To**: `{{ $json.to }}`
   - **Subject**: `{{ $json.subject }}`
   - **Email Type**: HTML
   - **Message**: `{{ $json.html }}`

#### 8. Agregar Nodo PostgreSQL - Registrar en Auditoría

1. Agrega nodo: **Postgres**
2. Configuración:
   - **Operation**: Insert
   - **Schema**: public (si aplica)
   - **Table**: audit_logs
   - **Columns**:
\`\`\`json
{
  "user_id": null,
  "event": "assignment.created",
  "entity_id": "{{ $('Webhook').first().json.body.assignment_id }}",
  "entity_type": "Assignment",
  "description": "Email enviado a profesor por nueva asignación",
  "source": "n8n",
  "created_at": "{{ $now }}",
  "updated_at": "{{ $now }}"
}
\`\`\`

#### 9. Guardar y Activar

1. **Guarda** el workflow
2. **Activa** el workflow (toggle en la esquina superior derecha)
3. Copia la URL del webhook

### ✅ Verificación

Desde Postman o curl, envía una petición de prueba:

\`\`\`bash
curl -X POST http://localhost:5678/webhook/assignment-created ^
  -H "Content-Type: application/json" ^
  -d "{\"teacher_id\": 1, \"group_id\": 1, \"classroom_id\": 1, \"assignment_id\": 1, \"day\": \"monday\", \"start_time\": \"08:00\", \"end_time\": \"10:00\"}"
\`\`\`

---

## 5. Workflow 2: Detección de Conflictos Horarios

### 📌 Objetivo
Ejecutar validaciones cada 6 horas y enviar alertas si hay conflictos

### 🛠️ Pasos

#### 1. Crear Nuevo Workflow

1. Nombre: `[Workflow 2] Detección Conflictos Horarios`

#### 2. Agregar Nodo Schedule Trigger

1. Agrega nodo: **Schedule Trigger**
2. Configuración:
   - **Trigger Times**: Cron
   - **Cron Expression**: `0 */6 * * *` (cada 6 horas)
   - **Trigger Interval**: Every 6 hours

#### 3. Agregar Nodo PostgreSQL - Buscar Conflictos de Salón

1. Agrega nodo: **Postgres**
2. Configuración:
   - **Query**:

\`\`\`sql
-- Detectar salones con múltiples asignaciones al mismo tiempo
SELECT 
  a1.classroom_id,
  c.name as classroom_name,
  a1.day,
  a1.start_time,
  a1.end_time,
  COUNT(*) as total_conflictos,
  STRING_AGG(CAST(a1.id AS TEXT), ', ') as assignment_ids
FROM assignments a1
INNER JOIN assignments a2 ON 
  a1.classroom_id = a2.classroom_id
  AND a1.day = a2.day
  AND a1.id != a2.id
  AND (
    (a1.start_time < a2.end_time AND a1.end_time > a2.start_time)
  )
INNER JOIN classrooms c ON a1.classroom_id = c.id
GROUP BY a1.classroom_id, c.name, a1.day, a1.start_time, a1.end_time
HAVING COUNT(*) > 1;
\`\`\`

#### 4. Agregar Nodo IF - ¿Hay conflictos?

1. Agrega nodo: **IF**
2. Configuración:
   - **Condition**: `{{ $json.length > 0 }}`

#### 5. Agregar Nodo Code - Generar Reporte

1. En el branch **true** del IF
2. Agrega nodo: **Code**
3. Código:

\`\`\`javascript
const conflicts = $input.all();
const totalConflicts = conflicts.length;

let reportHtml = \`
<div style="font-family: Arial, sans-serif;">
  <h2 style="color: #dc3545;">🚨 Alerta: Conflictos Horarios Detectados</h2>
  <p>Se detectaron <strong>\${totalConflicts}</strong> conflictos en las asignaciones:</p>
  <table style="border-collapse: collapse; width: 100%; margin-top: 20px;">
    <thead>
      <tr style="background-color: #f8f9fa;">
        <th style="border: 1px solid #dee2e6; padding: 10px;">Salón</th>
        <th style="border: 1px solid #dee2e6; padding: 10px;">Día</th>
        <th style="border: 1px solid #dee2e6; padding: 10px;">Horario</th>
        <th style="border: 1px solid #dee2e6; padding: 10px;">Total</th>
      </tr>
    </thead>
    <tbody>
\`;

conflicts.forEach(conflict => {
  const json = conflict.json;
  reportHtml += \`
    <tr>
      <td style="border: 1px solid #dee2e6; padding: 10px;">\${json.classroom_name}</td>
      <td style="border: 1px solid #dee2e6; padding: 10px;">\${json.day}</td>
      <td style="border: 1px solid #dee2e6; padding: 10px;">\${json.start_time} - \${json.end_time}</td>
      <td style="border: 1px solid #dee2e6; padding: 10px; text-align: center;">\${json.total_conflictos}</td>
    </tr>
  \`;
});

reportHtml += \`
    </tbody>
  </table>
  <p style="margin-top: 20px; color: #6c757d; font-size: 14px;">
    <strong>Acción requerida:</strong> Por favor, revise y corrija estas asignaciones.
  </p>
</div>
\`;

return {
  json: {
    totalConflicts,
    reportHtml,
    conflicts: conflicts.map(c => c.json)
  }
};
\`\`\`

#### 6. Agregar Nodo Gmail - Enviar Alerta

1. Agrega nodo: **Gmail**
2. Configuración:
   - **To**: `coordinador@ejemplo.com` (cambiar por email real)
   - **Subject**: `🚨 Conflictos Horarios Detectados - {{ $now.format('DD/MM/YYYY HH:mm') }}`
   - **Email Type**: HTML
   - **Message**: `{{ $json.reportHtml }}`

#### 7. Agregar Nodo PostgreSQL - Registrar Conflictos

1. Agrega nodo: **Postgres**
2. Configuración:
   - **Operation**: Insert
   - **Table**: conflict_alerts
   - **Columns** (usar loop para cada conflicto):

\`\`\`json
{
  "conflict_type": "room_double_booking",
  "severity": "high",
  "classroom_id": "{{ $json.classroom_id }}",
  "description": "Salón {{ $json.classroom_name }} con múltiples asignaciones",
  "day": "{{ $json.day }}",
  "start_time": "{{ $json.start_time }}",
  "end_time": "{{ $json.end_time }}",
  "status": "notified",
  "notified_at": "{{ $now }}",
  "created_at": "{{ $now }}",
  "updated_at": "{{ $now }}"
}
\`\`\`

#### 8. Guardar y Activar

1. Guarda el workflow
2. Activa el workflow

---

## 6. Workflow 3: Recordatorios de Disponibilidades

### 📌 Objetivo
Enviar recordatorios cada lunes a las 8 AM a profesores sin disponibilidad

### 🛠️ Pasos

#### 1. Crear Nuevo Workflow

1. Nombre: `[Workflow 3] Recordatorios Disponibilidades`

#### 2. Agregar Nodo Schedule Trigger

1. Agrega nodo: **Schedule Trigger**
2. Configuración:
   - **Cron Expression**: `0 8 * * 1` (lunes 8:00 AM)

#### 3. Agregar Nodo PostgreSQL - Profesores Sin Disponibilidad

1. Agrega nodo: **Postgres**
2. Query:

\`\`\`sql
SELECT 
  t.id,
  t.first_name,
  t.last_name,
  t.email,
  COALESCE(COUNT(ta.id), 0) as total_disponibilidades
FROM teachers t
LEFT JOIN teacher_availabilities ta ON t.id = ta.teacher_id
WHERE t.is_active = true
GROUP BY t.id, t.first_name, t.last_name, t.email
HAVING COALESCE(COUNT(ta.id), 0) = 0;
\`\`\`

#### 4. Agregar Nodo IF - ¿Hay profesores sin disponibilidad?

1. Configuración:
   - **Condition**: `{{ $json.length > 0 }}`

#### 5. Agregar Nodo Gmail - Enviar Recordatorio Individual

1. En el branch **true**
2. Configuración:
   - **To**: `{{ $json.email }}`
   - **Subject**: `📢 Recordatorio: Complete su disponibilidad`
   - **Email Type**: HTML
   - **Message**:

\`\`\`html
<div style="font-family: Arial, sans-serif; max-width: 600px;">
  <h2 style="color: #fd7e14;">📢 Recordatorio Importante</h2>
  
  <p>Estimado/a <strong>{{ $json.first_name }} {{ $json.last_name }}</strong>,</p>
  
  <p>Detectamos que <strong>aún no has completado tu disponibilidad horaria</strong> en el sistema.</p>
  
  <div style="background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0;">
    <strong>⚠️ Acción requerida:</strong> Por favor, ingresa al sistema y completa tu disponibilidad lo antes posible.
  </div>
  
  <p>Esto es importante para poder asignarte grupos de manera correcta.</p>
  
  <a href="http://localhost:8000/disponibilidades" style="display: inline-block; background-color: #28a745; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; margin-top: 15px;">
    Completar Disponibilidad
  </a>
  
  <p style="margin-top: 20px; color: #6c757d; font-size: 14px;">
    Saludos,<br>Sistema de Asignación de Salones
  </p>
</div>
\`\`\`

#### 6. Agregar Nodo Gmail - Notificar Coordinador

1. Agrega nodo paralelo: **Gmail**
2. Configuración:
   - **To**: `coordinador@ejemplo.com`
   - **Subject**: `📋 Resumen: Profesores sin disponibilidad`
   - **Message**: Lista de profesores

#### 7. Guardar y Activar

---

## 7. Configuración Final en Laravel

### Paso 1: Actualizar .env

Edita tu archivo `.env` y agrega las URLs de los webhooks que copiaste:

\`\`\`env
# N8N WEBHOOKS
N8N_WEBHOOK_ASSIGNMENT_CREATED=http://localhost:5678/webhook/assignment-created
N8N_WEBHOOK_ASSIGNMENT_UPDATED=http://localhost:5678/webhook/assignment-updated
N8N_WEBHOOK_CONFLICTS_DETECTED=http://localhost:5678/webhook/conflicts-detected
N8N_WEBHOOK_INCOMPLETE_AVAILABILITIES=http://localhost:5678/webhook/incomplete-availabilities

N8N_WEBHOOKS_ENABLED=true
\`\`\`

### Paso 2: Limpiar Cache de Configuración

\`\`\`bash
php artisan config:clear
php artisan cache:clear
\`\`\`

---

## 8. Testing y Verificación

### Test 1: Crear Asignación Manual

1. Ve a tu sistema Laravel: `http://localhost:8000/asignacion/manual`
2. Crea una nueva asignación
3. Verifica:
   - [ ] Email recibido en bandeja del profesor
   - [ ] Registro creado en `audit_logs`
   - [ ] Ejecución exitosa en n8n (revisar en el workflow)

### Test 2: Actualizar Asignación

1. Edita una asignación existente
2. Verifica:
   - [ ] Email de cambio recibido
   - [ ] Registro en `audit_logs`

### Test 3: Conflictos

1. Ejecuta manualmente el Workflow 2 en n8n (botón "Execute Workflow")
2. Verifica:
   - [ ] Email recibido si hay conflictos
   - [ ] Registros en `conflict_alerts`

---

## 9. Troubleshooting

### ❌ No se envían emails

**Posible causa**: Credenciales de Gmail incorrectas

**Solución**:
1. Regenera las credenciales en n8n
2. Verifica que autorizaste correctamente
3. Si usas Gmail, habilita "Aplicaciones menos seguras"

### ❌ Webhook no se dispara

**Posible causa**: URL incorrecta en .env

**Solución**:
1. Verifica que la URL del webhook coincide
2. Ejecuta `php artisan config:clear`
3. Revisa los logs: `storage/logs/laravel.log`

### ❌ Error al consultar base de datos

**Posible causa**: Credenciales incorrectas en n8n

**Solución**:
1. Verifica las credenciales de PostgreSQL/SQLite
2. Prueba la conexión desde n8n
3. Revisa que el host sea correcto (localhost o 127.0.0.1)

---

## 🎉 ¡Felicitaciones!

Has configurado exitosamente la integración con n8n. Ahora tu sistema:

✅ Envía notificaciones automáticas cuando se crean/actualizan asignaciones
✅ Detecta y alerta sobre conflictos horarios cada 6 horas
✅ Envía recordatorios semanales sobre disponibilidades incompletas

### 📚 Próximos Pasos

1. Configura los workflows 4-8 del documento original
2. Implementa autenticación en los webhooks
3. Agrega más validaciones personalizadas
4. Crea dashboards en n8n para monitoreo

---

**Documentación creada el:** 11 de diciembre de 2025  
**Versión:** 1.0
