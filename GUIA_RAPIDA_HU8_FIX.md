# 🔧 GUÍA RÁPIDA - HOTFIX HU8 PROFESOR DISPONIBILIDADES

## ⚡ Solución Rápida (3 minutos)

### Paso 1: Verificar cambios realizados

```bash
# Ir al proyecto
cd ~/proyectos/sistema-asignacion-salones

# Ver estado
git status
```

**Cambios esperados:**
- ✅ `app/Modules/GestionAcademica/Models/TeacherAvailability.php` (modificado)
- ✅ `resources/views/gestion-academica/availability/my-availabilities.blade.php` (modificado)
- ✅ `HOTFIX_HU8_PROFESOR_DISPONIBILIDADES.md` (nuevo)

---

### Paso 2: Hacer el commit

**Opción A - Automático (Script):**
```bash
# Ejecutar script
bash COMMIT_HU8_FIX.sh
```

**Opción B - Manual (Paso a paso):**
```bash
# Agregar archivos
git add app/Modules/GestionAcademica/Models/TeacherAvailability.php
git add resources/views/gestion-academica/availability/my-availabilities.blade.php
git add HOTFIX_HU8_PROFESOR_DISPONIBILIDADES.md

# Commit
git commit -m "fix: corregir error 'Call to a member function format()' en HU8 Profesor

PROBLEMA:
- Ruta /gestion-academica/my-availabilities retornaba error
- TeacherAvailability tenía casts y accessors conflictivos

SOLUCIÓN:
- Remover casts problemáticos (start_time/end_time como datetime)
- Remover accessors que retornaban strings
- Agregar métodos formateadores seguros
- Actualizar vista para usar nuevos métodos

ARCHIVOS:
- app/Modules/GestionAcademica/Models/TeacherAvailability.php
- resources/views/gestion-academica/availability/my-availabilities.blade.php
- HOTFIX_HU8_PROFESOR_DISPONIBILIDADES.md

RESULTADO: ✅ Ruta accesible sin errores"
```

---

### Paso 3: Push a develop

```bash
git push origin develop
```

---

## 🧪 Validación Local

### Paso 1: Limpiar cache

```bash
php artisan view:clear
php artisan config:clear
```

### Paso 2: Iniciar servidor

```bash
composer dev
# O manualmente:
php artisan serve --host=127.0.0.1 --port=8000
```

### Paso 3: Probar en navegador

**URL:** `http://127.0.0.1:8000/gestion-academica/my-availabilities`

**Verificar:**
- ✅ Se carga sin error "Call to a member function format()"
- ✅ Se muestra tabla de disponibilidades
- ✅ Se muestra resumen semanal con horarios (ej: "08:00-12:00")
- ✅ Botones de edición/eliminación están presentes
- ✅ No hay avisos en la consola del navegador

---

## 📊 ¿Qué se cambió?

### `TeacherAvailability.php`

**Eliminado:**
```php
// ❌ Casts problemáticos
protected $casts = [
    'start_time' => 'datetime:H:i:s',
    'end_time' => 'datetime:H:i:s',
];

// ❌ Accessors que retornaban strings
public function getStartTimeAttribute($value) { ... }
public function getEndTimeAttribute($value) { ... }
```

**Agregado:**
```php
// ✅ Solo el boolean
protected $casts = [
    'is_available' => 'boolean'
];

// ✅ Métodos que retornan strings formateados
public function getFormattedStartTimeAttribute() { ... }
public function getFormattedEndTimeAttribute() { ... }
```

### `my-availabilities.blade.php`

**Antes (línea 93):**
```blade
{{ $avail->start_time->format('H:i') }}-{{ $avail->end_time->format('H:i') }}
```

**Después:**
```blade
{{ $avail->formatted_start_time }}-{{ $avail->formatted_end_time }}
```

---

## 📝 Alternativas si algo falla

### Si ves el mismo error después del fix:

```bash
# 1. Limpiar todo cache agresivamente
php artisan cache:clear --force
php artisan view:clear --force
php artisan config:clear

# 2. Regenerar autoload
composer dump-autoload

# 3. Reiniciar servidor
# (Ctrl+C en terminal y ejecutar nuevamente:)
php artisan serve --host=127.0.0.1 --port=8000
```

### Si Git no reconoce los cambios:

```bash
# Forzar refresco de Git
git reset --hard HEAD

# Luego hacer pull
git pull origin develop

# Y volver a verificar estado
git status
```

---

## ✅ Resumen Visual

```
ANTES (❌ ERROR):
┌─────────────────────────────────────────┐
│ Error: Call to a member function format()│
│ en: my-availabilities.blade.php:93       │
│ Ruta: /gestion-academica/my-availabilities
│ Roles afectados: profesor, profesor_invitado
└─────────────────────────────────────────┘

DESPUÉS (✅ FUNCIONAL):
┌─────────────────────────────────────────┐
│ ✅ Sin errores                            │
│ ✅ Tabla carga correctamente              │
│ ✅ Horarios se muestran: "08:00-12:00"   │
│ ✅ Botones de edición funcionan           │
│ ✅ HU8 lista para completar funcionalidad │
└─────────────────────────────────────────┘
```

---

## 🚀 Próximas Tareas (FASE 1 - Semana)

Después de este fix, continuar con:

1. **HU9:** Revisar algoritmo asignación automática (90% → 100%)
2. **HU12:** Mejorar mensajes de conflicto (90% → 100%)
3. **Dashboard UI:** Organizar botones y estilos (Tailwind)

---

## 📞 Ayuda

**Si tienes dudas:**
1. Revisar `HOTFIX_HU8_PROFESOR_DISPONIBILIDADES.md` (documentación completa)
2. Revisar logs: `tail -f storage/logs/laravel.log`
3. Revisar console del navegador (F12)
4. Revisar database queries: `php artisan tinker`

---

**Estado:** ✅ COMPLETADO Y LISTO PARA PROBAR  
**Archivo:** GUIA_RAPIDA_HU8_FIX.md  
**Última actualización:** Diciembre 2024
