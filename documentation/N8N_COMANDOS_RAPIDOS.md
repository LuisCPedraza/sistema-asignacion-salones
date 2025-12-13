# 📋 Comandos Rápidos - Integración n8n

## 🚀 Setup Inicial

### 1. Ejecutar Migraciones
\`\`\`bash
php artisan migrate
\`\`\`

### 2. Limpiar Cache
\`\`\`bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
\`\`\`

---

## 🐳 Docker - n8n

### Instalar y Ejecutar n8n
\`\`\`bash
# Crear directorio para datos
mkdir C:\\n8n-data

# Ejecutar n8n
docker run -d --name n8n -p 5678:5678 -v C:\\n8n-data:/home/node/.n8n n8nio/n8n
\`\`\`

### Comandos Útiles de Docker
\`\`\`bash
# Ver estado de n8n
docker ps | findstr n8n

# Ver logs de n8n
docker logs -f n8n

# Detener n8n
docker stop n8n

# Iniciar n8n
docker start n8n

# Reiniciar n8n
docker restart n8n

# Eliminar contenedor
docker rm -f n8n
\`\`\`

---

## 🧪 Testing

### Test Manual con curl (Workflow 1)
\`\`\`bash
curl -X POST http://localhost:5678/webhook/assignment-created ^
  -H "Content-Type: application/json" ^
  -d "{\"teacher_id\": 1, \"group_id\": 1, \"classroom_id\": 1, \"assignment_id\": 1, \"day\": \"monday\", \"start_time\": \"08:00\", \"end_time\": \"10:00\", \"teacher_email\": \"profesor@ejemplo.com\", \"teacher_name\": \"Juan Perez\", \"group_name\": \"Grupo A\", \"classroom_name\": \"Salon 101\"}"
\`\`\`

### Test Manual con PHP
\`\`\`bash
php test-n8n-webhooks.php
\`\`\`

---

## 📊 Consultas SQL Útiles

### Ver últimas asignaciones
\`\`\`sql
SELECT 
  a.id,
  t.first_name || ' ' || t.last_name as profesor,
  sg.name as grupo,
  c.name as salon,
  a.day,
  a.start_time,
  a.end_time,
  a.created_at
FROM assignments a
LEFT JOIN teachers t ON a.teacher_id = t.id
LEFT JOIN student_groups sg ON a.student_group_id = sg.id
LEFT JOIN classrooms c ON a.classroom_id = c.id
ORDER BY a.created_at DESC
LIMIT 10;
\`\`\`

### Ver registros de auditoría de webhooks
\`\`\`sql
SELECT 
  id,
  event,
  entity_id,
  entity_type,
  description,
  created_at
FROM audit_logs
WHERE source = 'webhook'
ORDER BY created_at DESC
LIMIT 20;
\`\`\`

### Ver conflictos detectados
\`\`\`sql
SELECT 
  ca.id,
  ca.conflict_type,
  ca.severity,
  ca.description,
  ca.day,
  ca.start_time,
  ca.end_time,
  ca.status,
  c.name as salon
FROM conflict_alerts ca
LEFT JOIN classrooms c ON ca.classroom_id = c.id
WHERE ca.status IN ('pending', 'notified')
ORDER BY ca.severity DESC, ca.created_at DESC;
\`\`\`

### Buscar salones con múltiples asignaciones (conflictos)
\`\`\`sql
SELECT 
  a1.classroom_id,
  c.name as salon,
  a1.day,
  a1.start_time,
  a1.end_time,
  COUNT(*) as total_conflictos,
  GROUP_CONCAT(a1.id) as assignment_ids
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

### Buscar profesores sin disponibilidad
\`\`\`sql
SELECT 
  t.id,
  t.first_name,
  t.last_name,
  t.email,
  COUNT(ta.id) as total_disponibilidades
FROM teachers t
LEFT JOIN teacher_availabilities ta ON t.id = ta.teacher_id
WHERE t.is_active = 1
GROUP BY t.id, t.first_name, t.last_name, t.email
HAVING COUNT(ta.id) = 0;
\`\`\`

---

## 🔍 Debugging

### Ver logs de Laravel
\`\`\`bash
# Ver en tiempo real
tail -f storage/logs/laravel.log

# Ver últimas 50 líneas
tail -n 50 storage/logs/laravel.log

# Buscar errores
findstr /I "error" storage/logs/laravel.log

# Buscar webhooks
findstr /I "webhook" storage/logs/laravel.log
\`\`\`

### Verificar configuración de webhooks
\`\`\`bash
php artisan tinker
>>> config('webhooks.n8n_assignment_created')
>>> config('webhooks.n8n_assignment_updated')
>>> config('webhooks.enabled')
>>> exit
\`\`\`

### Verificar que existen las tablas
\`\`\`bash
php artisan tinker
>>> Schema::hasTable('audit_logs')
>>> Schema::hasTable('conflict_alerts')
>>> exit
\`\`\`

---

## 🔧 Configuración .env

### Agregar estas líneas a tu .env
\`\`\`env
# N8N WEBHOOKS
N8N_WEBHOOK_ASSIGNMENT_CREATED=http://localhost:5678/webhook/assignment-created
N8N_WEBHOOK_ASSIGNMENT_UPDATED=http://localhost:5678/webhook/assignment-updated
N8N_WEBHOOK_CONFLICTS_DETECTED=http://localhost:5678/webhook/conflicts-detected
N8N_WEBHOOK_INCOMPLETE_AVAILABILITIES=http://localhost:5678/webhook/incomplete-availabilities

N8N_WEBHOOKS_ENABLED=true
N8N_WEBHOOK_TIMEOUT=10
N8N_WEBHOOK_RETRY_ATTEMPTS=3
N8N_WEBHOOK_RETRY_DELAY=100
N8N_WEBHOOKS_LOG_AUDIT=true

N8N_API_TOKEN=tu_token_aqui_si_necesitas
\`\`\`

---

## 📨 URLs de Webhooks (Copiar después de crear workflows)

### Workflow 1 - Asignación Creada
\`\`\`
http://localhost:5678/webhook/assignment-created
\`\`\`

### Workflow 2 - Conflictos Detectados
\`\`\`
http://localhost:5678/webhook/conflicts-detected
\`\`\`

### Workflow 3 - Disponibilidades Incompletas
\`\`\`
http://localhost:5678/webhook/incomplete-availabilities
\`\`\`

### Workflow 6 - Asignación Actualizada
\`\`\`
http://localhost:5678/webhook/assignment-updated
\`\`\`

---

## 🔄 Reiniciar Todo

### Cuando hagas cambios
\`\`\`bash
# 1. Reiniciar Laravel (si usas php artisan serve)
# Ctrl+C y luego:
php artisan serve

# 2. Reiniciar n8n
docker restart n8n

# 3. Limpiar cache de Laravel
php artisan config:clear
php artisan cache:clear
\`\`\`

---

## 📧 Test de Email con Gmail

### Configurar Gmail en n8n
1. Ve a: **Settings → Credentials → Add Credential**
2. Busca: **Gmail OAuth2**
3. Sigue las instrucciones para autorizar
4. Guarda con nombre: `Gmail_SAS`

### Verificar que funciona
Crea un workflow simple:
- Nodo Manual Trigger
- Nodo Gmail (Send)
- To: tu_email@gmail.com
- Subject: Test
- Message: Hola mundo

---

## 🔐 Seguridad (Producción)

### Agregar autenticación a webhooks
\`\`\`env
# En .env
N8N_WEBHOOK_SECRET=un_token_secreto_super_seguro_12345
\`\`\`

En n8n:
1. En nodo Webhook, activa **Authentication**
2. Selecciona **Header Auth**
3. Name: `Authorization`
4. Value: `Bearer un_token_secreto_super_seguro_12345`

---

## 📚 Recursos Útiles

- **n8n Docs:** https://docs.n8n.io
- **PostgreSQL Node:** https://docs.n8n.io/integrations/databases/postgres/
- **Webhook Node:** https://docs.n8n.io/nodes/core-nodes/webhook/
- **Gmail Node:** https://docs.n8n.io/integrations/builtin/credentials/gmail/
- **Cron Generator:** https://crontab.guru/

---

**Última actualización:** 11 de diciembre de 2025
