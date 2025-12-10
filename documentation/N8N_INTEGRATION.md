# 🔄 GUÍA DE INTEGRACIÓN: n8n Workflows

## Overview
n8n se integra con tu app Laravel para automatizar:
1. **Correos diarios a profesores** (día previo a sus clases)
2. **Reportes de conflictos a admin** (conflictos detectados)
3. **Avisos de expiración a invitados** (acceso próximo a vencer)

---

## 📝 Requisitos Previos

- n8n instalado: `n8n --version` → debe mostrar versión (v1.120.4)
- Laravel app corriendo: `http://localhost:8000`
- API disponible en: `http://localhost:8000/api/webhooks/n8n/...`

---

## 🚀 WORKFLOW 1: Correo Diario a Profesores

### Descripción
- **Trigger**: Cada día a las 17:00 (antes de que terminen clases)
- **Acción**: Consulta asignaciones del día siguiente de cada profesor
- **Resultado**: Envía correo con horario, salón, materia, ubicación

### Pasos en n8n

1. **Trigger: Schedule**
   - Type: `Every Day`
   - Time: `17:00`
   - Timezone: `America/Bogota`

2. **HTTP Request: Obtener profesores**
   ```
   GET http://localhost:8000/api/v1/teachers?is_active=true
   Headers: Accept: application/json
   ```

3. **Loop: Para cada profesor**
   - **Node**: Loop Over Items
   - Item: Cada profesor de la respuesta anterior

4. **HTTP Request: Obtener asignaciones mañana**
   ```
   GET http://localhost:8000/api/webhooks/n8n/next-day-assignments?teacher_id={{$node["Loop"].item.id}}
   ```

5. **Condition: ¿Tiene asignaciones?**
   - Si `count > 0` → continuar a paso 6
   - Si `count == 0` → saltar

6. **Send Email (SMTP/Gmail/SendGrid)**
   - To: `{{$node["Loop"].item.email}}`
   - Subject: `Tu horario de mañana - {{$node["Get Assignments"].data.assignments[0].day}}`
   - Body (HTML template):
   ```html
   <h2>Hola {{$node["Loop"].item.name}},</h2>
   <p>Mañana {{date}} tienes las siguientes clases:</p>
   <table border="1">
     <tr>
       <th>Materia</th>
       <th>Grupo</th>
       <th>Salón</th>
       <th>Horario</th>
     </tr>
     {{loop assignments}}
     <tr>
       <td>{{assignment.subject}}</td>
       <td>{{assignment.group}}</td>
       <td>{{assignment.classroom}}</td>
       <td>{{assignment.start_time}} - {{assignment.end_time}}</td>
     </tr>
     {{/loop}}
   </table>
   ```

---

## 🚀 WORKFLOW 2: Reporte de Conflictos a Admin

### Descripción
- **Trigger**: Cada día a las 06:00
- **Acción**: Consulta conflictos detectados
- **Resultado**: Envía resumen a admin si hay conflictos

### Pasos en n8n

1. **Trigger: Schedule**
   - Type: `Every Day`
   - Time: `06:00`
   - Timezone: `America/Bogota`

2. **HTTP Request: Obtener conflictos**
   ```
   GET http://localhost:8000/api/webhooks/n8n/conflicts
   ```

3. **Condition: ¿Hay conflictos?**
   - Si `total_conflicts > 0` → continuar
   - Si `== 0` → terminar

4. **Send Email a Admin**
   - To: `admin@universidad.edu.co`
   - Subject: `⚠️ ALERTA: {{$node["Get Conflicts"].data.total_conflicts}} conflictos detectados`
   - Body (HTML):
   ```html
   <h2>Reporte de Conflictos</h2>
   <p>Se detectaron {{total}} conflictos:</p>
   <ul>
     {{loop conflicts}}
     <li>
       {{type}}: {{description}}<br/>
       Grupos: {{group1}} ↔ {{group2}}<br/>
       Día: {{day}}
     </li>
     {{/loop}}
   </ul>
   <p><a href="http://localhost:8000/asignacion/conflictos">Ver detalles</a></p>
   ```

---

## 🚀 WORKFLOW 3: Aviso de Expiración a Invitados

### Descripción
- **Trigger**: Cada día a las 10:00
- **Acción**: Consulta profesores invitados con acceso próximo a expirar (7 días)
- **Resultado**: Envía aviso individual a cada invitado

### Pasos en n8n

1. **Trigger: Schedule**
   - Type: `Every Day`
   - Time: `10:00`
   - Timezone: `America/Bogota`

2. **HTTP Request: Obtener invitados próximos a expirar**
   ```
   GET http://localhost:8000/api/webhooks/n8n/expiring-guests
   ```

3. **Loop: Para cada invitado**
   - Item: Cada guest de la respuesta

4. **Send Email**
   - To: `{{$node["Loop"].item.email}}`
   - Subject: `⏰ Tu acceso temporal vence en {{days_left}} días`
   - Body (HTML):
   ```html
   <h2>Hola {{name}},</h2>
   <p>Tu acceso temporal al sistema vence en <strong>{{days_left}} días</strong>.</p>
   <p>Fecha de expiración: <strong>{{expires_at}}</strong></p>
   <p>Contacta a la coordinación si necesitas extender tu acceso.</p>
   <p>Saludos,<br/>Sistema de Asignación de Salones</p>
   ```

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
