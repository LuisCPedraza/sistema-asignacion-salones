# ⚡ INICIO RÁPIDO: Gestión Académica

## 🎯 Objetivo
Acceder rápidamente a los módulos de Carreras, Semestres y Materias para crear y gestionar la estructura académica.

---

## 🚀 En 3 Pasos

### **Paso 1: Acceder al Dashboard**
```
1. Inicia sesión como:
   - Usuario con rol "coordinador" O
   - Usuario con rol "secretaria_coordinacion"

2. Se te redirige automáticamente al Dashboard Académico
   (o navega a: /academic/dashboard)
```

### **Paso 2: Seleccionar Módulo**
```
En el sidebar izquierdo, verás:

📊 Dashboard
▼ GESTIÓN ACADÉMICA (nueva sección)
  📚 Carreras
  📋 Semestres
  📖 Materias

Haz click en el módulo deseado
```

### **Paso 3: Usar el Módulo**
```
Se abrirá una tabla con:
- Lista de registros existentes
- Botón "➕ Nueva [Carrera/Semestre/Materia]"
- Botones de "✏️ Editar" y "🗑️ Eliminar" en cada fila

Elige tu acción y completa el formulario
```

---

## 📚 Gestionar Carreras

### Ver Carreras
```
Ruta: /careers
```
✅ Verás tabla con:
- Código | Nombre | Descripción | Duración | Estado | Acciones

### Crear Carrera
```
1. Click en "➕ Nueva Carrera"
2. Completa:
   - Código*: "CST" (ej: único)
   - Nombre*: "Tecnología..."
   - Descripción: (opcional)
   - Duración*: 6-8 semestres
   - Activa: ✓ (checkbox)
3. Click "✓ Crear Carrera"
```

### Editar Carrera
```
1. En la tabla, click "✏️ Editar"
2. Modifica los datos
3. Click "✓ Guardar Cambios"
```

### Eliminar Carrera
```
⚠️ SOLO si NO tiene semestres
1. En la tabla, click "🗑️ Eliminar"
2. Confirma cuando se pida
3. Se elimina y muestra confirmación
```

---

## 📋 Gestionar Semestres

### Ver Semestres
```
Ruta: /semesters
```
✅ Verás tabla agrupada por carrera:
- Carrera | Número | Descripción | Estado | Acciones

### Crear Semestre
```
1. Click en "➕ Nuevo Semestre"
2. Completa:
   - Carrera*: (select desplegable)
   - Número*: 1-12
   - Descripción: (opcional)
   - Activo: ✓
3. Click "✓ Crear Semestre"
```

⚠️ **Validación automática:**
- No puedes crear dos semestres con el mismo número para la misma carrera

### Editar Semestre
```
1. Click "✏️ Editar"
2. Modifica
3. Click "✓ Guardar Cambios"
```

### Eliminar Semestre
```
⚠️ SOLO si NO tiene grupos de estudiantes
1. Click "🗑️ Eliminar"
2. Confirma
3. Se elimina
```

---

## 📖 Gestionar Materias

### Ver Materias
```
Ruta: /subjects
```
✅ Verás tabla con:
- Código | Nombre | Carrera | Semestre | Créditos | Estado | Acciones

### Crear Materia
```
1. Click en "➕ Nueva Materia"
2. Completa DATOS BÁSICOS:
   - Código*: "PRG101" (único)
   - Nombre*: "Programación I"
   - Carrera*: (select)
   - Descripción: (opcional)
   - Especialidad: (opcional, ej: "Web")

3. Completa DATOS ACADÉMICOS:
   - Semestre*: 1-12
   - Créditos*: 1-20
   - Horas Teóricas*: 0-40
   - Horas Laboratorio*: 0-40
   
4. Activa: ✓

5. Click "✓ Crear Materia"
```

### Editar Materia
```
1. Click "✏️ Editar"
2. Modifica cualquier campo
3. Click "✓ Guardar Cambios"
```

### Eliminar Materia
```
⚠️ SOLO si NO tiene asignaciones
1. Click "🗑️ Eliminar"
2. Confirma
3. Se elimina
```

---

## ✅ Flujo Recomendado: Crear Estructura Académica

### Ejemplo: Nueva Carrera en 5 minutos

```
PASO 1: CREAR CARRERA (2 min)
  └─ Accede a /careers
  └─ Click "Nueva Carrera"
  └─ Ingresa: Código="CST", Nombre="Desarrollo Software", Duración=8
  └─ Click "Crear"

PASO 2: CREAR SEMESTRES (2 min)
  └─ Accede a /semesters
  └─ Repite 8 veces:
     └─ Click "Nuevo Semestre"
     └─ Selecciona tu carrera
     └─ Ingresa número (1-8)
     └─ Click "Crear"

PASO 3: CREAR MATERIAS (5-10 min)
  └─ Accede a /subjects
  └─ Para cada materia:
     └─ Click "Nueva Materia"
     └─ Ingresa: Código, Nombre, Carrera, Créditos, Horas
     └─ Click "Crear"

RESULTADO: ✅ Estructura académica lista para asignaciones
```

---

## 🆘 Errores Comunes y Soluciones

### ❌ "Acceso denegado. Se requiere rol de coordinador"
```
✅ Solución:
  1. Verifica que tu usuario sea "coordinador" o "secretaria_coordinacion"
  2. Solicita al admin cambiar tu rol si es necesario
```

### ❌ "The code has already been taken"
```
✅ Solución:
  1. El código debe ser único
  2. Usa un código diferente (ej: CST2, CST-2025)
```

### ❌ "Ya existe un semestre con este número para la carrera"
```
✅ Solución:
  1. No puedes crear dos semestres 1, 2, 3... para la misma carrera
  2. Verifica que uses números diferentes (1-12)
```

### ❌ "No se puede eliminar una carrera que tiene semestres"
```
✅ Solución:
  1. Primero elimina todos los semestres de esa carrera
  2. Luego intenta eliminar la carrera nuevamente
  3. O desactiva en lugar de eliminar (is_active = false)
```

### ❌ Campos mostrando error en rojo
```
✅ Solución:
  1. Revisa el mensaje debajo del campo
  2. Campos con * son obligatorios
  3. Respeta rangos (créditos 1-20, horas 0-40, semestre 1-12)
```

---

## 💡 Tips y Trucos

### Activar/Desactivar sin Eliminar
```
Si necesitas que una carrera no aparezca, pero no quieres eliminarla:
1. Click "Editar"
2. Desactiva el checkbox "Activa"
3. Click "Guardar"

⚠️ Esto es mejor que eliminar si tiene dependencias
```

### Búsqueda Rápida
```
Para encontrar un registro:
1. En la tabla, usa el buscador del navegador (Ctrl+F)
2. Escribe parte del código o nombre
3. El navegador te lo resaltará
```

### Paginación
```
Si hay muchos registros:
1. La tabla muestra 15 por página
2. Al final verás números de página
3. Click en página para navegar
```

### Confirmación de Eliminación
```
Cuando intentes eliminar:
1. Se mostrará un cuadro de confirmación
2. Click OK para confirmar eliminación
3. Click Cancel para cancelar
```

---

## 🔗 Enlaces Útiles

| Recurso | URL |
|---------|-----|
| Carreras | `/careers` |
| Semestres | `/semesters` |
| Materias | `/subjects` |
| Dashboard Académico | `/academic/dashboard` |
| Documentación Testing | `GUIA_TESTING_CRUD_GESTION_ACADEMICA.md` |
| Resumen Ejecutivo | `RESUMEN_GESTION_ACADEMICA_CRUD.md` |
| Arquitectura | `ARQUITECTURA_GESTION_ACADEMICA.md` |

---

## 📱 Responsivo

✅ Las interfaces funcionan perfectamente en:
- 🖥️ Computadora de escritorio
- 📱 Tablet
- 📱 Teléfono móvil

No necesitas hacer nada especial, la interfaz se adapta automáticamente.

---

## 🎓 Video Tutorial (Paso a Paso)

### Si prefieres ver en video:
1. Graba tu pantalla mientras completas el flujo
2. Comparte con tu equipo
3. Así todos aprenden juntos

---

## 📞 Soporte

### Si algo no funciona:
1. **Lee este documento** - La solución probablemente está aquí
2. **Consulta GUIA_TESTING** - Para casos más complejos
3. **Revisa ARQUITECTURA** - Para entender cómo funciona
4. **Abre la consola** (F12) - Busca mensajes de error

---

## ⏱️ Tiempos Estimados

| Tarea | Tiempo |
|-------|--------|
| Aprender esta guía | 5 min |
| Crear 1 carrera | 1 min |
| Crear 8 semestres | 8 min |
| Crear 40 materias (5 por semestre) | 20 min |
| **Total estructura académica completa** | **~35 min** |

---

## 🎉 ¡Listo!

Ya puedes empezar a usar el módulo de Gestión Académica.

**Próximos pasos:**
1. ✅ Crear tu estructura académica
2. ✅ Crear grupos de estudiantes
3. ✅ Asignar estudiantes a grupos
4. ✅ Usar asignación automática
5. ✅ Visualizar horarios

---

**Última actualización**: 14 de diciembre de 2025  
**Versión**: 1.0  
**Status**: ✅ Operativo
