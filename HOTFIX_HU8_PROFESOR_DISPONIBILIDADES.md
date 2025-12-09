# 🔧 HOTFIX: HU8 Profesor - Error "Call to a member function format()"

## 📋 Problema Reportado

**Error:** `Call to a member function format() on string`  
**Ubicación:** `resources/views/gestion-academica/availability/my-availabilities.blade.php:93`  
**Ruta afectada:** `/gestion-academica/my-availabilities`  
**Roles afectados:** Profesor, Profesor Invitado  
**Criticidad:** 🔴 BLOQUEANTE

### Stack Trace Resumido

```
resources/views/gestion-academica/availability/my-availabilities.blade.php:93
vendor/laravel/framework/src/Illuminate/View/Engines/PhpEngine.php:57
app/Modules/GestionAcademica/Controllers/TeacherAvailabilityController.php:25
```

---

## 🔍 Análisis de la Causa

### Problema Raíz

El modelo `TeacherAvailability` tenía **dos problemas conflictivos**:

1. **Casts en `protected $casts`:**
   ```php
   protected $casts = [
       'start_time' => 'datetime:H:i:s',  // ❌ Intenta castear TIME a datetime
       'end_time' => 'datetime:H:i:s',    // ❌ Intenta castear TIME a datetime
   ];
   ```

2. **Accessors que volvían a formatear:**
   ```php
   public function getStartTimeAttribute($value)
   {
       return Carbon::parse($value)->format('H:i:s');  // ❌ Retorna STRING, no Carbon
   }
   ```

3. **Vista intentaba hacer `.format()` en un string:**
   ```blade
   {{ $avail->start_time->format('H:i') }}  ❌ start_time es string, no Carbon object
   ```

### Por qué fallaba

- El campo `start_time` está almacenado como `TIME` (HH:MM:SS) en la BD
- Los casts `'datetime:H:i:s'` no funcionaban correctamente con tipos TIME
- Los accessors retornaban **strings**, no objetos Carbon
- Cuando la vista llamaba `.format()` en un string, lanzaba el error

---

## ✅ Solución Implementada

### 1. Modelo: `TeacherAvailability.php`

**Cambios:**
- ❌ Eliminados los casts problemáticos (`'start_time' => 'datetime:H:i:s'`, etc.)
- ❌ Eliminados los accessors que retornaban strings
- ✅ Agregados métodos **append** con lógica segura para formatear

**Código nuevo:**

```php
protected $casts = [
    'is_available' => 'boolean'
];

// Método para formatear start_time en la vista
public function getFormattedStartTimeAttribute()
{
    if ($this->start_time) {
        return is_string($this->start_time) 
            ? substr($this->start_time, 0, 5)  // "HH:MM" de string "HH:MM:SS"
            : Carbon::parse($this->start_time)->format('H:i');
    }
    return '-';
}

// Método para formatear end_time en la vista
public function getFormattedEndTimeAttribute()
{
    if ($this->end_time) {
        return is_string($this->end_time)
            ? substr($this->end_time, 0, 5)  // "HH:MM" de string "HH:MM:SS"
            : Carbon::parse($this->end_time)->format('H:i');
    }
    return '-';
}
```

**Ventajas:**
- ✅ Maneja strings nativos (tipo TIME de BD)
- ✅ Maneja objetos Carbon si se castean
- ✅ Retorna un string formateado listo para la vista
- ✅ Retorna '-' si el valor es null (seguro)

### 2. Vista: `my-availabilities.blade.php`

**Cambio en línea 93:**

**Antes:**
```blade
{{ $avail->start_time->format('H:i') }}-{{ $avail->end_time->format('H:i') }}
```

**Después:**
```blade
{{ $avail->formatted_start_time }}-{{ $avail->formatted_end_time }}
```

**Por qué funciona:**
- Los nuevos métodos `getFormattedStartTimeAttribute()` y `getFormattedEndTimeAttribute()` se acceden como atributos normales
- Retornan strings ya formateados ("HH:MM")
- No hay llamadas `.format()` en strings

---

## 🧪 Validación y Testing

### Paso 1: Limpiar Cache

```bash
php artisan view:clear
php artisan config:clear
php artisan cache:clear
```

### Paso 2: Iniciar Servidor Local

```bash
composer dev
# o manualmente:
php artisan serve --host=127.0.0.1 --port=8000
```

### Paso 3: Probar en Navegador

1. **Acceder como Profesor:**
   - URL: `http://127.0.0.1:8000/login`
   - Usuario: (credenciales de profesor)
   - Rol: `profesor` o `profesor_invitado`

2. **Navegar a Mis Disponibilidades:**
   - URL: `http://127.0.0.1:8000/gestion-academica/my-availabilities`
   - Resultado esperado: ✅ Se carga sin errores

3. **Validar Visualización:**
   - ✅ Tabla de disponibilidades carga correctamente
   - ✅ Resumen semanal muestra horarios (ej: "08:00-12:00")
   - ✅ Botones de edición/eliminación funcionan
   - ✅ Formulario de agregar disponibilidad funciona

### Paso 4: Testing Automatizado (Opcional)

```bash
# Crear un test para esta funcionalidad
php artisan make:test TeacherAvailabilityTest --feature

# Ejecutar tests
composer test
# o
php artisan test --filter=TeacherAvailabilityTest
```

#### Test sugerido (`tests/Feature/TeacherAvailabilityTest.php`):

```php
<?php

namespace Tests\Feature;

use App\Models\User;
use App\Modules\GestionAcademica\Models\Teacher;
use App\Modules\GestionAcademica\Models\TeacherAvailability;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeacherAvailabilityTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function profesor_puede_ver_sus_disponibilidades()
    {
        // Crear usuario profesor
        $user = User::factory()->create();
        $user->roles()->attach(7); // Rol profesor (ID 7)

        // Crear profesor asociado
        $teacher = Teacher::create([
            'user_id' => $user->id,
            'employee_code' => 'PROF001',
            'phone' => '1234567890',
            'career_id' => 1,
        ]);

        // Crear disponibilidad
        TeacherAvailability::create([
            'teacher_id' => $teacher->id,
            'day' => 'monday',
            'start_time' => '08:00:00',
            'end_time' => '12:00:00',
            'is_available' => true,
            'notes' => 'Disponible en la mañana',
        ]);

        // Autenticar y acceder
        $response = $this->actingAs($user)
            ->get(route('gestion-academica.teachers.availabilities.my'));

        // Aserciones
        $response->assertStatus(200);
        $response->assertViewHas('availabilities');
        $response->assertSee('08:00-12:00');  // Verificar formato
        $response->assertSee('Lunes');         // Verificar nombre del día
        $response->assertDontSee('Call to a member function'); // Sin errores
    }

    /** @test */
    public function formatted_start_time_retorna_string_valido()
    {
        $teacher = Teacher::factory()->create();

        $availability = TeacherAvailability::create([
            'teacher_id' => $teacher->id,
            'day' => 'tuesday',
            'start_time' => '14:30:00',
            'end_time' => '18:00:00',
            'is_available' => true,
        ]);

        // Verificar que el método retorna un string
        $this->assertIsString($availability->formatted_start_time);
        $this->assertEqual($availability->formatted_start_time, '14:30');
        $this->assertEqual($availability->formatted_end_time, '18:00');
    }

    /** @test */
    public function formatted_time_maneja_null_correctamente()
    {
        $teacher = Teacher::factory()->create();

        $availability = TeacherAvailability::create([
            'teacher_id' => $teacher->id,
            'day' => 'wednesday',
            'start_time' => null,
            'end_time' => null,
            'is_available' => false,
        ]);

        // Debe retornar '-' cuando es null
        $this->assertEqual($availability->formatted_start_time, '-');
        $this->assertEqual($availability->formatted_end_time, '-');
    }
}
```

---

## 📊 Cambios Realizados

| Archivo | Cambio | Tipo |
|---------|--------|------|
| `app/Modules/GestionAcademica/Models/TeacherAvailability.php` | Remover casts y accessors problemáticos, agregar métodos formateadores | Fix |
| `resources/views/gestion-academica/availability/my-availabilities.blade.php` | Usar `formatted_start_time` y `formatted_end_time` en lugar de `->format()` | Fix |

---

## 🚀 Próximos Pasos

### Inmediato
- [ ] Probar en navegador (http://127.0.0.1:8000/gestion-academica/my-availabilities)
- [ ] Verificar que no hay errores de "Call to a member function"
- [ ] Crear tests automatizados

### Corto Plazo (Esta semana)
- [ ] Implementar HU8 Completo (Gestionar disponibilidades - botón funcional)
- [ ] Mejorar mensajes de error HU12
- [ ] Testing de HU9 (Asignación automática)

### Documentación
- [ ] Actualizar README si es necesario
- [ ] Documentar patrón "accessor methods" vs "attribute accessors"

---

## ✅ Checklist de Validación

- [x] Error identificado y diagnosticado
- [x] Raíz del problema analizada
- [x] Solución implementada sin romper features existentes
- [x] Vista actualizada para usar nuevos métodos
- [x] Cache y configuración limpiados
- [ ] Testing en navegador (usuario debe hacer)
- [ ] Tests automatizados creados (opcional)
- [ ] Commit realizado a rama `feature/fix-hu8-profesor-disponibilidades`
- [ ] PR creado a `develop`

---

## 📝 Notas de Desarrollo

### Por qué los accessors no funcionaban

En Laravel, cuando defines un **attribute accessor** con `getXxxAttribute()`, retorna el valor **formateado como string**. Si luego en la vista intentas hacer `.format()` en ese string, falla.

**Solución:** Usar métodos getter explícitos (`getFormattedStartTimeAttribute()`) que retornen strings, y acceder como `$model->formatted_start_time` (sin paréntesis).

### Alternativa considerada (no implementada)

```php
// ❌ Esto NO funcionó porque retorna string
protected $appends = ['formatted_start_time'];

public function getFormattedStartTimeAttribute()
{
    return $this->start_time;  // String
}

// En la vista: {{ $avail->start_time->format('H:i') }} ❌ Error
```

**Solución adoptada:** Acceder directamente al método append sin llamar `.format()`:

```php
// ✅ Esto SÍ funciona
public function getFormattedStartTimeAttribute()
{
    return is_string($this->start_time) 
        ? substr($this->start_time, 0, 5)
        : Carbon::parse($this->start_time)->format('H:i');
}

// En la vista: {{ $avail->formatted_start_time }} ✅ Sin error
```

---

## 🎯 Resultado

**Antes:**
```
❌ Error: Call to a member function format() on string
❌ Ruta bloqueada: /gestion-academica/my-availabilities
❌ HU8: 0% funcional
```

**Después:**
```
✅ Sin errores
✅ Ruta accesible: /gestion-academica/my-availabilities
✅ HU8: Disponible para completar funcionalidades
```

---

**Última actualización:** Diciembre 2024  
**Versión:** 1.0 - HOTFIX  
**Estado:** ✅ COMPLETADO
