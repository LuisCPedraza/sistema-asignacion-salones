# 🔗 Integración n8n - Resumen Ejecutivo

## ✅ Archivos Creados/Modificados

### 📝 Migraciones
- `database/migrations/2025_12_09_create_audit_logs_table.php` - **ACTUALIZADA** ✏️
- `database/migrations/2025_12_11_120000_create_conflict_alerts_table.php` - **NUEVA** ✨

### 🎯 Controladores
- `app/Http/Controllers/Api/WebhookController.php` - **NUEVO** ✨
- `app/Modules/Asignacion/Controllers/AssignmentController.php` - **ACTUALIZADO** ✏️

### 🗄️ Modelos
- `app/Models/AuditLog.php` - **ACTUALIZADO** ✏️
- `app/Models/ConflictAlert.php` - **NUEVO** ✨

### ⚙️ Configuración
- `config/webhooks.php` - **NUEVO** ✨
- `.env` - **ACTUALIZADO** ✏️

### 📚 Documentación
- `documentation/N8N_GUIA_CONFIGURACION.md` - **NUEVA** ✨
- `documentation/N8N_IMPLEMENTATION_SUMMARY.md` - **ESTE ARCHIVO** ✨

---

## 🚀 Pasos para Activar la Integración

### 1️⃣ Ejecutar Migraciones

\`\`\`bash
php artisan migrate
\`\`\`

Esto creará:
- Tabla `audit_logs` (actualizada con campos para n8n)
- Tabla `conflict_alerts` (nueva)

### 2️⃣ Instalar y Configurar n8n

\`\`\`bash
# Con Docker (recomendado)
docker run -d --name n8n -p 5678:5678 n8nio/n8n

# O con npm
npm install n8n -g
n8n start
\`\`\`

Accede a: **http://localhost:5678**

### 3️⃣ Crear los 3 Workflows Prioritarios

En n8n, crea estos workflows siguiendo la guía `N8N_GUIA_CONFIGURACION.md`:

1. **Workflow 1**: Notificación de Asignación Creada
   - Trigger: Webhook POST
   - Envía email al profesor
   - Registra en auditoría

2. **Workflow 2**: Detección de Conflictos Horarios
   - Trigger: Cron (cada 6 horas)
   - Consulta conflictos en BD
   - Envía alertas al coordinador

3. **Workflow 3**: Recordatorios de Disponibilidades
   - Trigger: Cron (lunes 8 AM)
   - Detecta profesores sin disponibilidad
   - Envía recordatorios

### 4️⃣ Configurar URLs en Laravel

Edita `.env` y agrega las URLs de los webhooks que n8n generó:

\`\`\`env
N8N_WEBHOOK_ASSIGNMENT_CREATED=http://localhost:5678/webhook/assignment-created
N8N_WEBHOOK_ASSIGNMENT_UPDATED=http://localhost:5678/webhook/assignment-updated
N8N_WEBHOOK_CONFLICTS_DETECTED=http://localhost:5678/webhook/conflicts-detected
N8N_WEBHOOK_INCOMPLETE_AVAILABILITIES=http://localhost:5678/webhook/incomplete-availabilities
N8N_WEBHOOKS_ENABLED=true
\`\`\`

### 5️⃣ Limpiar Cache

\`\`\`bash
php artisan config:clear
php artisan cache:clear
\`\`\`

---

## 🧪 Testing

### Test Manual: Crear Asignación

1. Ve a: `http://localhost:8000/asignacion/manual`
2. Crea una nueva asignación
3. Verifica:
   - ✉️ Email recibido en bandeja del profesor
   - 📋 Registro en tabla `audit_logs`
   - ✅ Ejecución exitosa en n8n

### Test Manual: Actualizar Asignación

1. Edita una asignación existente
2. Verifica que se envía email de notificación de cambio

### Test Programado: Conflictos

1. En n8n, ejecuta manualmente el Workflow 2
2. Si hay conflictos, recibirás email

### Logs para Debugging

\`\`\`bash
# Ver logs de Laravel
tail -f storage/logs/laravel.log

# Ver logs de n8n
docker logs -f n8n
\`\`\`

---

## 📊 Cómo Funciona la Integración

### Flujo: Crear Asignación

\`\`\`
Usuario crea asignación en Laravel
         ↓
AssignmentController::storeManual()
         ↓
WebhookController::notifyAssignmentCreated()
         ↓
HTTP POST → n8n Webhook
         ↓
n8n consulta PostgreSQL (profesor, grupo, salón)
         ↓
n8n envía email con detalles
         ↓
n8n registra en audit_logs
\`\`\`

### Flujo: Actualizar Asignación

\`\`\`
Usuario actualiza asignación
         ↓
AssignmentController::updateManual()
         ↓
WebhookController::notifyAssignmentUpdated()
         ↓
HTTP POST → n8n Webhook (con cambios)
         ↓
n8n envía email al profesor afectado
         ↓
n8n registra en audit_logs
\`\`\`

### Flujo: Detección de Conflictos (Automático)

\`\`\`
Cron de n8n (cada 6 horas)
         ↓
n8n consulta PostgreSQL (busca conflictos)
         ↓
¿Hay conflictos? → Sí
         ↓
n8n genera reporte HTML
         ↓
n8n envía email al coordinador
         ↓
n8n inserta registros en conflict_alerts
\`\`\`

---

## 🔍 Verificación de Componentes

### Tabla audit_logs

\`\`\`sql
SELECT * FROM audit_logs 
WHERE event IN ('assignment.created', 'assignment.updated')
ORDER BY created_at DESC 
LIMIT 10;
\`\`\`

### Tabla conflict_alerts

\`\`\`sql
SELECT * FROM conflict_alerts 
WHERE status = 'pending'
ORDER BY created_at DESC;
\`\`\`

### Webhooks en Laravel

\`\`\`php
// Verificar configuración
php artisan tinker
>>> config('webhooks.n8n_assignment_created')
// Debería mostrar la URL del webhook
\`\`\`

---

## 🎯 Endpoints de Laravel que Disparan Webhooks

| Método | Ruta | Webhook | Descripción |
|--------|------|---------|-------------|
| POST | `/asignacion/manual/store` | `assignment.created` | Crear asignación manual |
| PUT | `/asignacion/manual/{id}` | `assignment.updated` | Actualizar asignación |

---

## 🔐 Seguridad (Para Producción)

### Recomendaciones

1. **Agregar autenticación a webhooks:**
   - En n8n, usa "Header Auth" en nodo Webhook
   - En Laravel, agrega token en el header

2. **Validar origen de requests:**
   - Verifica IP de n8n
   - Usa tokens firmados

3. **HTTPS en producción:**
   - Configura SSL en n8n
   - Usa URLs https:// en .env

4. **Encriptar credenciales:**
   - No guardar passwords en .env sin encriptar
   - Usar Laravel Secrets para producción

---

## 📈 Métricas y Monitoreo

### En n8n
- Dashboard muestra ejecuciones exitosas/fallidas
- Historial de ejecuciones por workflow
- Logs detallados de cada nodo

### En Laravel
- Tabla `audit_logs` registra todos los eventos
- Logs en `storage/logs/laravel.log`

---

## 🆘 Troubleshooting Común

### ❌ "Webhook URL not configured"
**Solución:** Verifica que .env tenga las URLs configuradas y ejecuta `php artisan config:clear`

### ❌ "Connection refused to localhost:5678"
**Solución:** Verifica que n8n esté corriendo con `docker ps` o revisando el puerto 5678

### ❌ "Query failed in PostgreSQL node"
**Solución:** Verifica credenciales de base de datos en n8n y que las tablas existan

### ❌ "Email not sent"
**Solución:** Revisa credenciales de Gmail en n8n y que el email del profesor sea válido

---

## 📞 Soporte

Para más detalles, consulta:
- 📄 `N8N_GUIA_CONFIGURACION.md` - Guía paso a paso completa
- 📄 `n8n_workflows_plan.md` - Plan original con todos los workflows
- 🌐 [Documentación oficial de n8n](https://docs.n8n.io)

---

**Fecha de implementación:** 11 de diciembre de 2025  
**Versión:** 1.0  
**Estado:** ✅ Listo para usar
