# 📋 Sistema de Auditoría (HU18) - Documentación Técnica

## 📌 Visión General

Sistema completo de auditoría que registra todos los cambios en el sistema, permitiendo rastrear quién hizo qué, cuándo y desde dónde.

## 🏗️ Arquitectura

### 1. **Modelo de Datos**

#### Tabla: `audit_logs`
```sql
- id (PK)
- user_id (FK -> users)
- model: VARCHAR - Nombre del modelo afectado (User, StudentGroup, etc.)
- model_id: BIGINT - ID del registro
- action: ENUM(create, update, delete, restore, export)
- old_values: LONGTEXT JSON - Valores anteriores
- new_values: LONGTEXT JSON - Valores nuevos
- description: VARCHAR - Descripción amigable
- ip_address: VARCHAR - IP del usuario
- user_agent: VARCHAR - Navegador/Cliente
- created_at, updated_at: TIMESTAMP
```

**Índices:**
- `user_id`
- `(model, model_id)`
- `action`
- `created_at`

### 2. **Componentes**

#### **Modelo: `AuditLog`** (`app/Models/AuditLog.php`)

```php
// Registrar un cambio manualmente
AuditLog::log(
    User::class,
    $user->id,
    'update',
    ['email' => 'old@example.com'],
    ['email' => 'new@example.com'],
    'Email actualizado'
);
```

**Métodos Principales:**
- `log()`: Registra cambios
- `getActionLabel()`: Etiqueta amigable de la acción
- `getFormattedChanges()`: Cambios en formato legible
- `getReadableChanges()`: String de cambios
- `getAvailableFilters()`: Filtros disponibles

#### **Trait: `AuditableModel`** (`app/Traits/AuditableModel.php`)

Automáticamente registra cambios en modelos que lo usen:

```php
class User extends Authenticatable {
    use AuditableModel;
    
    public function getAuditableDescription(): string {
        return "Usuario: {$this->name}";
    }
}
```

**Eventos Automáticos:**
- `created`: Se registra al crear
- `updated`: Se registra al actualizar (solo cambios)
- `deleted`: Se registra al eliminar
- `restored`: Se puede registrar al restaurar (soft deletes)

#### **Controlador: `AuditController`** (`app/Modules/Admin/Controllers/AuditController.php`)

**Acciones:**
- `index`: Lista de logs con filtros y paginación
  - Filtrar por modelo
  - Filtrar por acción
  - Filtrar por usuario
  - Filtrar por rango de fechas
  - Búsqueda por descripción
  
- `show`: Detalle de un log específico
  - Información general
  - Detalles del usuario responsable
  - Contexto técnico (IP, User Agent)
  - Cambios formateados (antes/después)
  - Resumen en tabla

### 3. **Rutas**

```php
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/audit', [AuditController::class, 'index'])->name('admin.audit.index');
    Route::get('/audit/{auditLog}', [AuditController::class, 'show'])->name('admin.audit.show');
});
```

**URLs:**
- `GET /admin/audit` - Listado de auditoría
- `GET /admin/audit/{id}` - Detalle de un log

### 4. **Vistas**

#### **`resources/views/admin/audit/index.blade.php`**

Características:
- Tabla responsiva con logs paginados (25 por página)
- Panel de filtros colapsable:
  - Modelo (select)
  - Acción (select)
  - Rango de fechas (from/to)
  - Búsqueda libre
- Colores por acción:
  - 🟢 Verde: Crear
  - 🔵 Azul: Actualizar
  - 🔴 Rojo: Eliminar
  - 🟡 Amarillo: Restaurar
  - ⚪ Gris: Exportar
- Información del usuario con email clickeable

#### **`resources/views/admin/audit/show.blade.php`**

Características:
- Panel izquierdo:
  - Información general (ID, fecha, acción, modelo)
  - Información del usuario responsable
  - Contexto técnico (IP, User Agent)
  
- Panel derecho:
  - Descripción del cambio
  - Valores anteriores (rojo)
  - Valores nuevos (verde)
  - Tabla de resumen de cambios

## 🔌 Integración

### Modelos Auditables

Actualmente auditables:
- ✅ `User` - Cambios en usuarios
- ✅ `StudentGroup` - Cambios en grupos de estudiantes

Para hacer auditable un modelo:

```php
use App\Traits\AuditableModel;

class MyModel extends Model {
    use AuditableModel;
    
    // Opcional: personalizar descripción
    public function getAuditableDescription(): string {
        return "Mi Modelo: {$this->name}";
    }
}
```

### Logging Manual

Para acciones complejas:

```php
// En controlador
public function customAction(Request $request, User $user) {
    $oldEmail = $user->email;
    
    // Hacer cambios...
    $user->email = 'newemail@example.com';
    $user->save();
    
    // Registrar en auditoría
    AuditLog::log(
        User::class,
        $user->id,
        'update',
        ['email' => $oldEmail],
        ['email' => $user->email],
        'Email cambiado desde acción personalizada'
    );
}
```

## 🔐 Seguridad

### Middleware
Solo administradores pueden acceder a auditoría:
```php
if (!Auth::user()->hasRole('administrador')) {
    abort(403, 'Acceso denegado');
}
```

### Privacidad
- Contraseñas NO se registran en old/new values
- Solo se auditan cambios reales (no duplicados)
- IP y User Agent se registran para trazabilidad

## 📊 Querying

```php
// Obtener todos los cambios de un usuario
AuditLog::where('user_id', $user->id)->get();

// Obtener cambios en un modelo específico
AuditLog::where('model', 'User')
    ->where('model_id', $user->id)
    ->orderBy('created_at', 'desc')
    ->get();

// Cambios en rango de fechas
AuditLog::whereBetween('created_at', [$from, $to])->get();

// Cambios por acción
AuditLog::where('action', 'delete')->get();
```

## 🧪 Tests

Se incluyen 6 tests en `tests/Feature/AuditTest.php`:

```bash
php artisan test tests/Feature/AuditTest.php
```

**Tests:**
- ✅ `test_audit_index_page_loads` - Vista de listado
- ✅ `test_audit_show_page` - Vista de detalle
- ✅ `test_audit_logs_user_creation` - Auto-logging
- ✅ `test_audit_filters_work` - Filtros funcionan
- ✅ `test_audit_formatted_changes` - Cambios formateados
- ✅ `test_unauthorized_cannot_access_audit` - Control de acceso

## 📈 Casos de Uso

### 1. Rastrear cambios de usuario
```
Admin → Ver Auditoría → Filtrar por Usuario "Juan" → Ver todas sus acciones
```

### 2. Investigar cambio sospechoso
```
Admin → Ver Auditoría → Ver detalle → Comparar antes/después
```

### 3. Análisis temporal
```
Admin → Ver Auditoría → Filtrar por fecha → Entender qué pasó en X fecha
```

### 4. Debugging
```
Dev → Ver Auditoría → Entender qué campos cambiaron → Identificar bug
```

## 🚀 Mejoras Futuras

1. **Exportación**: Descargar logs en CSV/Excel
2. **Diferencias Visuales**: Comparador visual de cambios
3. **Webhooks**: Notificar cambios a sistemas externos
4. **Retención**: Limpiar logs antiguos automáticamente
5. **Alertas**: Notificar cambios críticos en tiempo real
6. **Comparación**: Comparar estado de entidad entre fechas
7. **Reporte**: Reportes de auditoría por período

## 📝 Notas

- Auditoría solo registra cambios cuando hay usuario autenticado
- Los logs son inmutables (no se pueden editar/eliminar)
- JSON se usa para permitir flexibilidad en estructuras
- Paginación: 25 registros por página
- Búsqueda es case-insensitive

## 🔗 Referencias

- **URL**: `http://127.0.0.1:8000/admin/audit`
- **Modelo**: `app/Models/AuditLog.php`
- **Controlador**: `app/Modules/Admin/Controllers/AuditController.php`
- **Trait**: `app/Traits/AuditableModel.php`
- **Vistas**: `resources/views/admin/audit/`
- **Tests**: `tests/Feature/AuditTest.php`
- **Migración**: `database/migrations/2025_12_09_create_audit_logs_table.php`
