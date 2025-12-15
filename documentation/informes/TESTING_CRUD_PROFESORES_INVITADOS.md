# 🧪 GUÍA DE TESTING - CRUD PROFESORES INVITADOS

## 📋 Checklist de Pruebas

### ✅ **PASO 1: Acceder al Listado**
**URL:** `http://127.0.0.1:8000/admin/guest-teachers`

**Verificar:**
- [ ] La página carga sin errores
- [ ] Se muestra el listado de profesores invitados (incluido el profesor "Profesor Invitado" existente)
- [ ] Las estadísticas muestran números correctos (Total, Activos, Por Expirar, Expirados)
- [ ] Los filtros funcionan (Todos, Activos, Por Expirar, Expirados)
- [ ] El botón "+ Crear Profesor Invitado" está visible
- [ ] La paginación funciona correctamente (si hay más de 10 registros)

**Captura:** Toma screenshot del listado inicial

---

### ✅ **PASO 2: Crear Nuevo Profesor Invitado**

#### 2.1 Acceder al Formulario de Creación
**Acción:** Click en "+ Crear Profesor Invitado"
**URL:** `http://127.0.0.1:8000/admin/guest-teachers/create`

**Verificar:**
- [ ] El formulario carga correctamente
- [ ] Todos los campos están presentes:
  - Nombre Completo (requerido)
  - Correo Electrónico (requerido)
  - Contraseña (requerido)
  - Confirmar Contraseña (requerido)
  - Fecha y Hora de Expiración (requerido, con datetime-local)
  - Dirección IP Permitida (opcional)
- [ ] El campo de fecha tiene valor por defecto (mañana a la misma hora)
- [ ] Los íconos y ayudas están visibles

#### 2.2 Probar Validaciones (Casos Negativos)
**Acción:** Intentar enviar formulario vacío

**Verificar:**
- [ ] Se muestran errores de validación en campos requeridos
- [ ] Los mensajes de error son claros

**Acción:** Usar email existente (invitado@universidad.edu)

**Verificar:**
- [ ] Se muestra error "El correo ya está registrado"

**Acción:** Passwords que no coinciden

**Verificar:**
- [ ] Se muestra error de confirmación de contraseña

**Acción:** Fecha de expiración en el pasado

**Verificar:**
- [ ] Se muestra error "La fecha debe ser en el futuro"

#### 2.3 Crear Profesor Válido
**Datos de Prueba:**
```
Nombre: María González Pérez
Email: maria.gonzalez@invitado.edu
Password: Test@123456
Confirmar Password: Test@123456
Fecha Expiración: [Mañana a las 18:00]
IP Permitida: [Dejar vacío]
```

**Acción:** Enviar formulario

**Verificar:**
- [ ] Redirección a página de detalle del profesor creado
- [ ] Mensaje de éxito: "✅ Profesor invitado creado exitosamente"
- [ ] Los datos se muestran correctamente en la vista de detalle
- [ ] El badge muestra "🟢 Activo"

**Captura:** Screenshot del profesor recién creado

---

### ✅ **PASO 3: Ver Detalles del Profesor**

**URL:** `http://127.0.0.1:8000/admin/guest-teachers/{ID}`

**Verificar:**
- [ ] Se muestra toda la información del profesor:
  - Nombre completo
  - Correo electrónico
  - Especialidad
  - Estado del acceso (Activo/Expirado)
  - Fecha de expiración
  - Tiempo restante (días, horas, minutos)
  - IP permitida
  - Fecha de creación
  - Última actualización
- [ ] El botón "Editar" está visible
- [ ] El botón "Revocar Acceso" está visible (si está activo)
- [ ] Las disponibilidades se muestran (si existen)
- [ ] Las estadísticas están correctas

**Captura:** Screenshot de la vista de detalles

---

### ✅ **PASO 4: Editar Profesor Invitado**

#### 4.1 Acceder al Formulario de Edición
**Acción:** Click en "Editar" desde la vista de detalle
**URL:** `http://127.0.0.1:8000/admin/guest-teachers/{ID}/edit`

**Verificar:**
- [ ] El formulario carga con los datos actuales pre-llenados
- [ ] Todos los campos están editables
- [ ] El campo de contraseña está vacío (opcional en edición)
- [ ] El panel lateral muestra el estado actual del profesor
- [ ] El botón "Revocar Acceso" está en el panel lateral (si activo)

#### 4.2 Editar Información
**Cambios a Realizar:**
```
Nombre: María González Pérez → María González López
Email: [Mantener igual]
Fecha Expiración: [Extender 7 días más]
IP Permitida: 192.168.1.100
```

**Acción:** Guardar cambios

**Verificar:**
- [ ] Redirección a vista de detalle
- [ ] Mensaje de éxito: "✅ Profesor invitado actualizado correctamente"
- [ ] Los cambios se reflejan correctamente:
  - Nombre actualizado
  - Nueva fecha de expiración
  - IP permitida ahora muestra "192.168.1.100"

**Captura:** Screenshot después de editar

#### 4.3 Cambiar Contraseña
**Acción:** Volver a editar, ahora cambiar solo la contraseña

**Datos:**
```
Nueva Contraseña: NewPass@789
Confirmar: NewPass@789
```

**Verificar:**
- [ ] Se acepta el cambio
- [ ] Mensaje de confirmación
- [ ] No se requiere password si se deja vacío

**Captura:** Screenshot confirmación cambio password

---

### ✅ **PASO 5: Revocar Acceso**

#### 5.1 Acceder a Modal de Revocación
**Acción:** Desde vista de detalle o edición, click en "Revocar Acceso"

**Verificar:**
- [ ] Se abre modal de confirmación
- [ ] El modal muestra advertencia clara
- [ ] Se menciona que es una acción inmediata
- [ ] Botón "Cancelar" está presente
- [ ] Botón "Revocar" está en rojo/danger

**Captura:** Screenshot del modal

#### 5.2 Confirmar Revocación
**Acción:** Click en "Revocar"

**Verificar:**
- [ ] Redirección al listado de profesores
- [ ] Mensaje de éxito: "✅ Acceso revocado correctamente"
- [ ] El profesor ahora aparece en filtro "Expirados"
- [ ] El badge muestra "🔴 Expirado"
- [ ] Al ver detalles:
  - Estado: "🔴 Expirado"
  - Tiempo restante: "⏱️ Acceso expirado"
  - Botón "Revocar" ya no está disponible o está deshabilitado

**Captura:** Screenshot del profesor revocado

---

### ✅ **PASO 6: Verificar Eventos de Auditoría**

**Acción:** Ir a vista de auditoría (si existe)
**URL:** `http://127.0.0.1:8000/admin/audit` o revisar en base de datos

#### 6.1 Revisar en Base de Datos
**Comando:**
```bash
docker exec sas-app php artisan tinker --execute="
\App\Models\AuditLog::where('auditable_type', 'App\Modules\GestionAcademica\Models\Teacher')
    ->where('auditable_id', [ID_DEL_PROFESOR_CREADO])
    ->orderBy('created_at', 'desc')
    ->get(['event', 'old_data', 'new_data', 'created_at'])
    ->each(function(\$log) {
        echo "\n=== Evento: {\$log->event} ===\n";
        echo "Fecha: {\$log->created_at}\n";
        if (\$log->old_data) echo "Old Data: " . json_encode(\$log->old_data) . "\n";
        if (\$log->new_data) echo "New Data: " . json_encode(\$log->new_data) . "\n";
    });
"
```

**Verificar:**
- [ ] Existe evento `CREATED` con los datos iniciales
- [ ] Existe evento `UPDATED` con old_data y new_data mostrando cambios
- [ ] Existe evento `REVOKED` con fecha de expiración antigua vs nueva

**Captura:** Screenshot de los logs de auditoría

---

### ✅ **PASO 7: Filtros y Búsqueda**

**Acciones y Verificaciones:**

#### 7.1 Filtro "Todos"
- [ ] Muestra todos los profesores invitados (activos y expirados)

#### 7.2 Filtro "Activos"
- [ ] Solo muestra profesores con `access_expires_at` > ahora

#### 7.3 Filtro "Por Expirar"
- [ ] Muestra solo profesores que expiran en menos de 7 días

#### 7.4 Filtro "Expirados"
- [ ] Solo muestra profesores con `access_expires_at` <= ahora

#### 7.5 Búsqueda por Nombre
**Acción:** Buscar "María"

**Verificar:**
- [ ] Solo aparece el profesor "María González López"

#### 7.6 Búsqueda por Email
**Acción:** Buscar "invitado"

**Verificar:**
- [ ] Aparecen todos los profesores con "invitado" en el email

**Captura:** Screenshot de búsqueda funcionando

---

### ✅ **PASO 8: Casos Edge y Validaciones Adicionales**

#### 8.1 Intentar crear profesor con email duplicado
**Verificar:**
- [ ] Error de validación antes de guardar

#### 8.2 Intentar editar con email de otro usuario
**Verificar:**
- [ ] Error de validación

#### 8.3 Password débil
**Acción:** Intentar password "123"

**Verificar:**
- [ ] Error: "La contraseña debe tener al menos 8 caracteres"

#### 8.4 IP Inválida
**Acción:** Intentar IP "999.999.999.999"

**Verificar:**
- [ ] (Opcional) Validación de formato IP

#### 8.5 Revocar Profesor Ya Expirado
**Verificar:**
- [ ] Botón deshabilitado o mensaje indicando que ya está expirado

---

## 📊 **RESUMEN DE RESULTADOS**

### Funcionalidades Probadas
- [ ] **CREATE** - Crear profesor invitado
- [ ] **READ** - Ver listado y detalles
- [ ] **UPDATE** - Editar información y contraseña
- [ ] **DELETE/REVOKE** - Revocar acceso
- [ ] **Validaciones** - Todos los campos
- [ ] **Filtros** - Estados y búsqueda
- [ ] **Auditoría** - Eventos registrados

### Bugs Encontrados
```
[Listar aquí cualquier bug o comportamiento inesperado]

1. 
2. 
3. 
```

### Mejoras Sugeridas
```
[Listar aquí mejoras que se podrían hacer]

1. 
2. 
3. 
```

---

## 🚀 **Próximos Pasos**

Si todos los tests pasan:
1. ✅ Marcar HU10 y CRUD Profesores Invitados como **100% completo**
2. Documentar cualquier bug encontrado
3. Crear issues en GitHub para bugs (si los hay)
4. Continuar con Dashboard UI o HU9

---

**Fecha de Testing:** 10 de diciembre de 2025
**Tester:** [Tu nombre]
**Ambiente:** Local (Docker)
**Navegador:** [Chrome/Firefox/Edge]
