# 🧪 Guía de Testing: CRUD Gestión Académica

## Descripción
Esta guía proporciona instrucciones paso a paso para testear el módulo completo de Gestión Académica, incluyendo:
- ✅ Carreras (Careers)
- ✅ Semestres (Semesters)
- ✅ Materias (Subjects)

---

## 📋 Requisitos Previos

1. **Usuario con rol**: Asegúrate de tener un usuario con rol **"coordinador"** o **"secretaria_coordinacion"**
2. **Acceso al sistema**: Inicia sesión en el sistema
3. **Navegación**: Puedes acceder a través de:
   - Dashboard Académico → Gestión Académica → [Carreras/Semestres/Materias]
   - O directamente a las URLs:
     - `/careers` - Lista de Carreras
     - `/semesters` - Lista de Semestres
     - `/subjects` - Lista de Materias

---

## 🧪 Plan de Testing

### **MÓDULO 1: CARRERAS (Careers)**

#### 1.1 Test de Lectura (READ)
```
Paso 1: Navega a "/careers"
Paso 2: Verifica que se muestre una tabla con:
  - Columnas: Código, Nombre, Descripción, Duración, Estado, Acciones
  - Paginación funcional (si hay más de 15 registros)
  - Badges de estado: "Activa" (verde) o "Inactiva" (gris)
  
Resultado esperado: ✅ Lista de carreras visible y paginada
```

#### 1.2 Test de Creación (CREATE)
```
Paso 1: Click en botón "➕ Nueva Carrera"
Paso 2: Completa el formulario:
  - Código: "CST" (debe ser único)
  - Nombre: "Tecnología en Desarrollo de Software"
  - Descripción: "Carrera enfocada en desarrollo de aplicaciones"
  - Duración: 8 semestres
  - Activa: ✓ (checkbox marcado)

Paso 3: Click en "✓ Crear Carrera"

Resultado esperado: ✅ Redirige a lista y muestra mensaje "Carrera creada exitosamente"
```

#### 1.3 Test de Validación de Creación
```
Paso 1: Intenta crear una carrera sin llenar campos requeridos
Paso 2: Click en "✓ Crear Carrera"

Resultado esperado: ✅ Muestra errores en rojo debajo de cada campo requerido
Errores esperados:
  - Código: "The code field is required"
  - Nombre: "The name field is required"
  - Duración: "The duration_semesters field is required"
```

#### 1.4 Test de Validación de Código Único
```
Paso 1: Intenta crear una carrera con código "CST" (ya existe)
Paso 2: Click en "✓ Crear Carrera"

Resultado esperado: ✅ Muestra error "The code has already been taken"
```

#### 1.5 Test de Edición (UPDATE)
```
Paso 1: En la lista, click en "✏️ Editar" de una carrera
Paso 2: Modifica el nombre: "Tecnología en Desarrollo de Software (Edición 2025)"
Paso 3: Modifica la descripción
Paso 4: Click en "✓ Guardar Cambios"

Resultado esperado: ✅ Redirige a lista y muestra "Carrera actualizada exitosamente"
```

#### 1.6 Test de Intento de Eliminación con Semestres
```
Paso 1: Intenta eliminar una carrera que tenga semestres asociados
Paso 2: Click en "🗑️ Eliminar"
Paso 3: Confirma la eliminación

Resultado esperado: ✅ Muestra error "No se puede eliminar una carrera que tiene semestres asociados"
```

#### 1.7 Test de Eliminación (DELETE)
```
Paso 1: Crea una carrera sin semestres
Paso 2: En la lista, click en "🗑️ Eliminar"
Paso 3: Confirma con "OK" en el modal de confirmación

Resultado esperado: ✅ Carrera eliminada y muestra "Carrera eliminada exitosamente"
```

---

### **MÓDULO 2: SEMESTRES (Semesters)**

#### 2.1 Test de Lectura (READ)
```
Paso 1: Navega a "/semesters"
Paso 2: Verifica que se muestre una tabla con:
  - Columnas: Carrera, Número, Descripción, Estado, Acciones
  - Semestres agrupados por carrera
  - Badges: "Semestre 1", "Semestre 2", etc.
  
Resultado esperado: ✅ Lista de semestres visible, filtrada por carrera
```

#### 2.2 Test de Creación (CREATE)
```
Paso 1: Click en "➕ Nuevo Semestre"
Paso 2: Completa el formulario:
  - Carrera: "Tecnología en Desarrollo de Software" (select)
  - Número: 1
  - Descripción: "Primer semestre - Introducción a programación"
  - Activo: ✓ (checkbox marcado)

Paso 3: Click en "✓ Crear Semestre"

Resultado esperado: ✅ Redirige a lista y muestra "Semestre creado exitosamente"
```

#### 2.3 Test de Validación de Combinación Única
```
Paso 1: Intenta crear otro Semestre 1 para la misma carrera
Paso 2: Click en "✓ Crear Semestre"

Resultado esperado: ✅ Muestra error "Ya existe un semestre con este número para la carrera seleccionada"
```

#### 2.4 Test de Edición (UPDATE)
```
Paso 1: Click en "✏️ Editar" de un semestre
Paso 2: Cambia el número a 2 (si es válido)
Paso 3: Modifica la descripción
Paso 4: Click en "✓ Guardar Cambios"

Resultado esperado: ✅ Semestre actualizado correctamente
```

#### 2.5 Test de Intento de Eliminación con Grupos
```
Paso 1: Intenta eliminar un semestre que tenga grupos de estudiantes
Paso 2: Click en "🗑️ Eliminar"

Resultado esperado: ✅ Muestra error "No se puede eliminar un semestre que tiene grupos de estudiantes asociados"
```

#### 2.6 Test de Eliminación (DELETE)
```
Paso 1: Elimina un semestre sin grupos asociados
Paso 2: Confirma la eliminación

Resultado esperado: ✅ "Semestre eliminado exitosamente"
```

---

### **MÓDULO 3: MATERIAS (Subjects)**

#### 3.1 Test de Lectura (READ)
```
Paso 1: Navega a "/subjects"
Paso 2: Verifica que se muestre una tabla con:
  - Columnas: Código, Nombre, Carrera, Semestre, Créditos, Estado, Acciones
  - Paginación
  - Materias con diferentes semestres
  
Resultado esperado: ✅ Lista de materias visible
```

#### 3.2 Test de Creación (CREATE)
```
Paso 1: Click en "➕ Nueva Materia"
Paso 2: Completa el formulario:
  - Código: "PRG101" (único)
  - Nombre: "Programación I"
  - Carrera: "Tecnología en Desarrollo de Software"
  - Descripción: "Introducción a la programación"
  - Especialidad: "Web" (opcional)
  - Semestre: 1
  - Créditos: 4
  - Horas Teóricas: 3
  - Horas Laboratorio: 2
  - Activa: ✓

Paso 3: Click en "✓ Crear Materia"

Resultado esperado: ✅ "Materia creada exitosamente"
```

#### 3.3 Test de Validación de Rango de Valores
```
Paso 1: Intenta crear una materia con:
  - Créditos: 25 (fuera de rango 1-20)
  - Horas Teóricas: 50 (fuera de rango 0-40)

Resultado esperado: ✅ Muestra errores de validación
```

#### 3.4 Test de Edición (UPDATE)
```
Paso 1: Click en "✏️ Editar" de una materia
Paso 2: Modifica:
  - Nombre: "Programación I - Avanzado"
  - Créditos: 5
  - Horas Teóricas: 4

Paso 3: Click en "✓ Guardar Cambios"

Resultado esperado: ✅ "Materia actualizada exitosamente"
```

#### 3.5 Test de Intento de Eliminación con Asignaciones
```
Paso 1: Intenta eliminar una materia que tenga asignaciones
Paso 2: Click en "🗑️ Eliminar"

Resultado esperado: ✅ Muestra error "No se puede eliminar una materia que tiene asignaciones asociadas"
```

#### 3.6 Test de Eliminación (DELETE)
```
Paso 1: Elimina una materia sin asignaciones
Paso 2: Confirma la eliminación

Resultado esperado: ✅ "Materia eliminada exitosamente"
```

---

## 🔒 Tests de Seguridad

#### 4.1 Test de Control de Acceso
```
Paso 1: Inicia sesión con un usuario que NO sea coordinador
Paso 2: Intenta acceder a "/careers"

Resultado esperado: ✅ Muestra error 403 "Acceso denegado. Se requiere rol de coordinador académico."
```

#### 4.2 Test de Middleware de Rol
```
Paso 1: Intenta acceder a las rutas sin autenticación
  - /careers
  - /semesters
  - /subjects

Resultado esperado: ✅ Redirige a login
```

---

## 📊 Casos de Uso Reales

### Caso 1: Crear una Carrera Completa
```
1. Crear carrera "Ingeniería en Sistemas"
2. Crear 8 semestres (1-8)
3. Para cada semestre, crear 5-6 materias
   - Ejemplo Semestre 1:
     - Programación I (4 créditos, 3 teóricas, 2 laboratorio)
     - Matemáticas I (5 créditos, 4 teóricas, 0 laboratorio)
     - Lógica Matemática (3 créditos, 2 teóricas, 2 laboratorio)
     - etc.
```

### Caso 2: Editar y Reorganizar Semestre
```
1. Abrir semestre 3 de una carrera
2. Editar descripción con nuevo plan de estudios
3. Modificar materias (cambiar semestres si es necesario)
```

### Caso 3: Gestión de Materias Electivas
```
1. Crear materias electivas con código "ELE-XXX"
2. Asociarlas a múltiples semestres
3. Testear que se visualizan correctamente en el sistema
```

---

## ✅ Checklist Final

- [ ] Todos los CRUD funcionan sin errores
- [ ] Las validaciones muestran mensajes correctos
- [ ] Los elementos protegidos no pueden eliminarse
- [ ] La paginación funciona (si aplica)
- [ ] Los badges de estado muestran colores correctos
- [ ] Los selectores de relaciones (career_id) funcionan
- [ ] Los mensajes de éxito/error se muestran correctamente
- [ ] El control de acceso funciona por rol
- [ ] Las modificaciones se guardan en la base de datos
- [ ] No hay errores en la consola del navegador

---

## 📝 Reporte de Resultados

Después de completar los tests, completa este reporte:

```
Fecha: [Tu fecha]
Usuario: [Tu usuario]
Navegador: [Chrome/Firefox/Safari/Edge]

MÓDULO CARRERAS:     ✅ APROBADO / ❌ FALLIDO
MÓDULO SEMESTRES:    ✅ APROBADO / ❌ FALLIDO
MÓDULO MATERIAS:     ✅ APROBADO / ❌ FALLIDO
SEGURIDAD:           ✅ APROBADO / ❌ FALLIDO

Observaciones:
[Describe cualquier problema encontrado]
```

---

**Última actualización**: 14 de diciembre de 2025
**Estado**: ✅ Sistema Listo para Testing
