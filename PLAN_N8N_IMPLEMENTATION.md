# 📋 PLAN IMPLEMENTACIÓN n8n - RESUMEN EJECUTIVO

## ✅ Ya Hecho

1. **Servicio N8nNotificationService** (`app/Services/N8nNotificationService.php`)
   - Métodos para obtener asignaciones del día siguiente
   - Consultar profesores invitados próximos a expirar
   - Obtener resumen de conflictos

2. **Controlador N8nWebhookController** (`app/Http/Controllers/Api/N8nWebhookController.php`)
   - Endpoints para recibir eventos de n8n
   - Endpoints para consultar datos (GET)
   - Manejo de webhooks webhook notifications

3. **Rutas API** (`routes/api.php`)
   - POST `/api/webhooks/n8n/notify` - Webhook principal
   - GET `/api/webhooks/n8n/next-day-assignments` - Asignaciones próximas
   - GET `/api/webhooks/n8n/conflicts` - Conflictos detectados
   - GET `/api/webhooks/n8n/expiring-guests` - Invitados próximos a expirar

4. **Documentación Actualizada** (`documentation/N8N_INTEGRATION.md`)
   - Guía completa de workflows
   - URLs de API
   - Pasos paso a paso para crear workflows en n8n

---

## 🎯 PRÓXIMOS PASOS (En Orden)

### PASO 1: Iniciar n8n Localmente
```bash
# Terminal (desde cualquier ubicación)
n8n

# Se abrirá en: http://localhost:5678
# Crear cuenta admin (primera vez)
```

**Tiempo estimado: 5 min**

---

### PASO 2: Probar Endpoints en Postman/cURL
Valida que las rutas API funcionan antes de conectar n8n.

```bash
# Test endpoint 1: Obtener asignaciones (reemplaza teacher_id con un ID válido)
curl "http://localhost:8000/api/webhooks/n8n/next-day-assignments?teacher_id=1"

# Test endpoint 2: Obtener conflictos
curl "http://localhost:8000/api/webhooks/n8n/conflicts"

# Test endpoint 3: Obtener invitados próximos a expirar
curl "http://localhost:8000/api/webhooks/n8n/expiring-guests"
```

**Tiempo estimado: 5 min**

---

### PASO 3: Crear Workflow 1 - Correos Diarios a Profesores

**En n8n:**

1. Click en **"New Workflow"**
2. Nombre: `Correos Diarios a Profesores`
3. Agregar nodos:

   **Nodo 1: Schedule Trigger**
   - Tipo: `Cron`
   - Expresión: `0 17 * * *` (17:00 todos los días)
   - Timezone: `America/Bogota`

   **Nodo 2: HTTP Request (Obtener profesores activos)**
   - URL: `http://localhost:8000/api/v1/teachers?is_active=true`
   - Method: GET
   - Headers: `Accept: application/json`

   **Nodo 3: Loop Over Items**
   - Expression: `{{$node["HTTP Request"].json.data}}`

   **Nodo 4: HTTP Request (Obtener asignaciones del día siguiente)**
   - URL: `http://localhost:8000/api/webhooks/n8n/next-day-assignments?teacher_id={{$node["Loop"].item.id}}`
   - Method: GET

   **Nodo 5: IF (Condition)**
   - `$node["HTTP Request1"].json.assignments.length > 0`
   - Verdadero: continúa
   - Falso: detén

   **Nodo 6: Send Email**
   - Para: `{{$node["Loop"].item.email}}`
   - Asunto: `Tu horario de mañana`
   - Cuerpo (HTML): Ver template en documentación

4. Click **Save**
5. Click **Execute Workflow** para probar

**Tiempo estimado: 20 min**

---

### PASO 4: Crear Workflow 2 - Reporte de Conflictos a Admin

**En n8n:**

1. **New Workflow** → `Reporte de Conflictos`
2. Nodos:

   **Nodo 1: Schedule Trigger**
   - Cron: `0 6 * * *` (06:00 todos los días)
   - Timezone: `America/Bogota`

   **Nodo 2: HTTP Request**
   - URL: `http://localhost:8000/api/webhooks/n8n/conflicts`
   - Method: GET

   **Nodo 3: IF (¿Hay conflictos?)**
   - `$node["HTTP Request"].json.data.total_conflicts > 0`

   **Nodo 4: Send Email**
   - Para: `admin@universidad.edu.co` (reemplaza con tu email)
   - Asunto: `⚠️ {{$node["HTTP Request"].json.data.total_conflicts}} conflictos detectados`
   - Cuerpo: HTML con lista de conflictos

3. Save y Execute para probar

**Tiempo estimado: 15 min**

---

### PASO 5: Crear Workflow 3 - Aviso Expiración Invitados

**En n8n:**

1. **New Workflow** → `Avisos Expiración Invitados`
2. Nodos:

   **Nodo 1: Schedule Trigger**
   - Cron: `0 10 * * *` (10:00 todos los días)
   - Timezone: `America/Bogota`

   **Nodo 2: HTTP Request**
   - URL: `http://localhost:8000/api/webhooks/n8n/expiring-guests`
   - Method: GET

   **Nodo 3: Loop Over Items**
   - Expression: `{{$node["HTTP Request"].json.guests}}`

   **Nodo 4: Send Email**
   - Para: `{{$node["Loop"].item.email}}`
   - Asunto: `⏰ Tu acceso temporal vence en {{$node["Loop"].item.days_left}} días`
   - Cuerpo: Aviso personalizado

3. Save y Execute

**Tiempo estimado: 15 min**

---

### PASO 6: Habilitar Workflows Activos

Una vez probados, en cada workflow:
- Click en el botón **"Activate"** (arriba)
- El workflow correrá automáticamente según el Schedule

**Tiempo estimado: 5 min**

---

### PASO 7: Validar Logs

```bash
# Revisar logs de Laravel para ver si n8n está consultando
docker exec sas-app tail -f storage/logs/laravel.log

# Buscar en logs de n8n (si está en Docker)
# O revisar en UI de n8n: Execution History
```

**Tiempo estimado: 5 min**

---

## 📊 Tiempo Total Estimado: **90 minutos**

| Paso | Tarea | Tiempo |
|------|-------|--------|
| 1 | Iniciar n8n | 5 min |
| 2 | Probar endpoints | 5 min |
| 3 | Crear Workflow 1 | 20 min |
| 4 | Crear Workflow 2 | 15 min |
| 5 | Crear Workflow 3 | 15 min |
| 6 | Activar workflows | 5 min |
| 7 | Validar logs | 5 min |
| **Total** | | **90 min** |

---

## 🔧 Configuración SMTP (Obligatorio para Enviar Correos)

Actualiza `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu-email@gmail.com
MAIL_PASSWORD=tu-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=tu-email@gmail.com
MAIL_FROM_NAME="Sistema Asignación"
```

O usa **Mailtrap** para testing (no envía correos reales):
- Registra en: https://mailtrap.io
- Copia las credenciales a `.env`

---

## ⚡ Quick Test sin Esperar Schedules

Para probar workflows sin esperar al horario:

**En n8n:**
1. Abre el workflow
2. Click **"Execute Workflow"** (botón azul superior)
3. Revisa output en la derecha
4. Si hay error, revisa URL y parámetros

---

## ❌ Troubleshooting Común

| Problema | Solución |
|----------|----------|
| "Connection refused" | Verifica que Laravel corre en puerto 8000: `docker ps` |
| Error 404 en API | Revisa rutas en `routes/api.php` y reinicia Laravel: `docker-compose restart app` |
| Correos no llegan | Verifica SMTP en `.env` o usa Mailtrap para testing |
| n8n no encuentra datos | Verifica que hay profesores/asignaciones activas en BD |

---

## 📚 Documentación Completa

Ver `documentation/N8N_INTEGRATION.md` para detalles técnicos y templates HTML.

---

¿Quieres que comencemos? Primero inicia n8n:

```bash
n8n
```

Luego accede a http://localhost:5678 y avísame cuando tengas la cuenta creada.
