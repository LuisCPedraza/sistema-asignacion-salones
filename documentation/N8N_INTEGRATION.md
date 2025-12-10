# Integración con n8n para Automatización de Correos

## 📧 Correos Automatizados Implementados

### 1. **Recordatorio Diario a Profesores**
Envía un correo 1 día antes con las clases del día siguiente.

**Endpoint:** `GET /api/n8n/tomorrow-classes`

**Workflow n8n:**
1. **Schedule Trigger** - Ejecutar diariamente a las 6:00 PM
2. **HTTP Request** - Llamar al endpoint
3. **Split Out** - Dividir por profesor
4. **Email Send** - Enviar correo personalizado a cada profesor

**Ejemplo de Response:**
```json
{
  "success": true,
  "date": "2025-12-11",
  "day_name": "miércoles",
  "total_teachers": 5,
  "total_classes": 12,
  "teachers": [
    {
      "teacher_id": 1,
      "teacher_name": "Juan Pérez",
      "email": "juan.perez@universidad.edu",
      "date": "2025-12-11",
      "date_formatted": "miércoles, 11 de diciembre de 2025",
      "classes": [
        {
          "subject": "Programación I",
          "group": "IS-301",
          "classroom": "Lab 203",
          "classroom_location": "Edificio B, Piso 2",
          "classroom_building": "Edificio B",
          "start_time": "08:00",
          "end_time": "10:00",
          "duration_hours": 2
        }
      ]
    }
  ]
}
```

**Template de Correo (n8n):**
```html
<h2>🗓️ Recordatorio de Clases - {{ $json.date_formatted }}</h2>

<p>Hola {{ $json.teacher_name }},</p>

<p>Estas son tus clases programadas para mañana:</p>

{{ #each classes }}
<div style="border: 1px solid #ddd; padding: 15px; margin: 10px 0;">
  <h3>⏰ {{ start_time }} - {{ end_time }} ({{ duration_hours }}h)</h3>
  <p><strong>Asignatura:</strong> {{ subject }}</p>
  <p><strong>Grupo:</strong> {{ group }}</p>
  <p><strong>Salón:</strong> {{ classroom }}</p>
  <p><strong>Ubicación:</strong> {{ classroom_location }}</p>
</div>
{{ /each }}

<p>¡Que tengas un excelente día!</p>
```

---

### 2. **Informe Diario al Administrador**
Envía estadísticas del sistema al administrador.

**Endpoint:** `GET /api/n8n/daily-stats`

**Workflow n8n:**
1. **Schedule Trigger** - Ejecutar diariamente a las 7:00 AM
2. **HTTP Request** - Llamar al endpoint
3. **Email Send** - Enviar informe al administrador

**Ejemplo de Response:**
```json
{
  "success": true,
  "stats": {
    "date": "2025-12-10",
    "date_formatted": "martes, 10 de diciembre de 2025",
    "tomorrow_classes": 25,
    "active_teachers": 50,
    "guest_teachers_expiring_soon": 3,
    "guest_teachers_expired": 1,
    "total_classrooms": 45,
    "assignments_with_conflicts": 0,
    "pending_reservations": 5
  }
}
```

---

### 3. **Alertas de Conflictos**
Notifica sobre conflictos detectados en asignaciones.

**Endpoint:** `GET /api/n8n/conflicts`

**Workflow n8n:**
1. **Schedule Trigger** - Ejecutar cada hora
2. **HTTP Request** - Llamar al endpoint
3. **IF Node** - Si hay conflictos
4. **Email Send** - Enviar alerta urgente

---

## 🔐 Configuración de Seguridad

### 1. Generar Token de API

En tu archivo `.env`:
```bash
N8N_API_TOKEN=tu_token_secreto_aqui_generar_con_openssl
```

Para generar un token seguro:
```bash
openssl rand -hex 32
```

### 2. Headers Requeridos

Todas las peticiones a `/api/n8n/*` requieren:
```
X-API-Token: tu_token_secreto_aqui
```

---

## 🛠️ Configuración en n8n

### 1. **Instalación de n8n**

```bash
# Opción 1: Docker
docker run -it --rm \
  --name n8n \
  -p 5678:5678 \
  -v ~/.n8n:/home/node/.n8n \
  n8nio/n8n

# Opción 2: npm
npm install n8n -g
n8n start
```

### 2. **Crear Credenciales**

1. Ve a `Settings > Credentials`
2. Crea credencial tipo "HTTP Header Auth"
3. Nombre: `Sistema-Asignacion-API`
4. Header Name: `X-API-Token`
5. Header Value: `tu_token_secreto_aqui`

### 3. **Importar Workflows**

Los workflows JSON están en: `documentation/n8n-workflows/`

**Workflows disponibles:**
- `workflow-recordatorio-profesores.json` - Correos diarios a profesores
- `workflow-informe-admin.json` - Informe diario al administrador
- `workflow-alertas-conflictos.json` - Alertas de conflictos

Para importar:
1. En n8n, click en "Workflows" > "Import from File"
2. Selecciona el archivo JSON
3. Configura las credenciales
4. Activa el workflow

---

## 📋 Checklist de Implementación

### Backend (Laravel)
- [x] Endpoint `/api/n8n/tomorrow-classes`
- [x] Endpoint `/api/n8n/daily-stats`
- [x] Endpoint `/api/n8n/conflicts`
- [x] Validación de token de API
- [x] Configuración en `config/app.php`

### n8n
- [ ] Instalar n8n (Docker/npm)
- [ ] Crear credenciales de API
- [ ] Importar workflows
- [ ] Configurar horarios de ejecución
- [ ] Probar envío de correos
- [ ] Configurar servidor SMTP

### Producción
- [ ] Configurar `N8N_API_TOKEN` en Render
- [ ] Configurar SMTP (Gmail/SendGrid/etc)
- [ ] Probar workflows en producción
- [ ] Monitorear logs de n8n

---

## 🧪 Testing

### Probar Endpoints Manualmente

```bash
# Clases de mañana
curl -X GET http://localhost:8000/api/n8n/tomorrow-classes \
  -H "X-API-Token: tu_token_aqui"

# Estadísticas diarias
curl -X GET http://localhost:8000/api/n8n/daily-stats \
  -H "X-API-Token: tu_token_aqui"

# Conflictos
curl -X GET http://localhost:8000/api/n8n/conflicts \
  -H "X-API-Token: tu_token_aqui"
```

---

## 📚 Recursos Adicionales

- [Documentación oficial de n8n](https://docs.n8n.io/)
- [n8n HTTP Request Node](https://docs.n8n.io/integrations/builtin/core-nodes/n8n-nodes-base.httprequest/)
- [n8n Email Node](https://docs.n8n.io/integrations/builtin/core-nodes/n8n-nodes-base.sendemail/)

---

## 🔧 Troubleshooting

### Error: "Unauthorized"
- Verifica que el header `X-API-Token` esté presente
- Confirma que el token en `.env` coincida con el de n8n

### No se reciben correos
- Verifica configuración SMTP en n8n
- Revisa logs de n8n: `docker logs n8n`
- Prueba envío manual desde n8n

### Endpoint retorna datos vacíos
- Verifica que haya asignaciones en la base de datos
- Confirma que las asignaciones tengan `schedule` relacionado
- Revisa logs de Laravel: `storage/logs/laravel.log`
