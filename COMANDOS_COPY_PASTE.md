# 🔧 COMANDOS COPY-PASTE PARA UBUNTU

## ⏱️ RÁPIDO (5 minutos)

### Opción A: Script Automático

```bash
cd ~/proyectos/sistema-asignacion-salones && bash COMMIT_HU8_FIX.sh
```

Luego:

```bash
git push origin develop
```

---

### Opción B: Comandos Manuales

```bash
# 1. Ir a proyecto
cd ~/proyectos/sistema-asignacion-salones

# 2. Agregar archivos
git add app/Modules/GestionAcademica/Models/TeacherAvailability.php
git add resources/views/gestion-academica/availability/my-availabilities.blade.php
git add HOTFIX_HU8_PROFESOR_DISPONIBILIDADES.md

# 3. Verificar que se agregaron
git status

# 4. Commit
git commit -m "fix: corregir error 'Call to a member function format()' en HU8 Profesor

PROBLEMA:
- Ruta /gestion-academica/my-availabilities retornaba error 500
- Vista intentaba hacer .format() en string, no en Carbon object
- TeacherAvailability tenía casts y accessors conflictivos

SOLUCIÓN:
- Remover casts problemáticos (start_time/end_time como datetime)
- Remover accessors que retornaban strings
- Agregar métodos formateadores seguros (getFormattedStartTimeAttribute)
- Actualizar vista para usar métodos en lugar de .format()

IMPACTO:
- ✅ Ruta accesible sin errores
- ✅ HU8 disponible para desarrollo
- ✅ 2 roles desbloqueados (profesor, profesor_invitado)"

# 5. Push a develop
git push origin develop
```

---

## 🧪 VALIDACIÓN EN NAVEGADOR (2 minutos)

### Paso 1: Limpiar Cache

```bash
cd ~/proyectos/sistema-asignacion-salones
php artisan view:clear
php artisan config:clear
```

### Paso 2: Iniciar Servidor

```bash
composer dev
```

O si prefieres manualmente:

```bash
php artisan serve --host=127.0.0.1 --port=8000
```

### Paso 3: Abrir Navegador

**URL:** `http://127.0.0.1:8000/gestion-academica/my-availabilities`

**Verificar:**
- ✅ Se carga sin error "Call to a member function"
- ✅ Se muestra tabla con disponibilidades
- ✅ Se muestra resumen semanal
- ✅ Horarios aparecen con formato "HH:MM-HH:MM"

---

## 📊 VER CAMBIOS REALIZADOS (1 minuto)

```bash
cd ~/proyectos/sistema-asignacion-salones

# Ver cambios en archivos
git diff app/Modules/GestionAcademica/Models/TeacherAvailability.php
git diff resources/views/gestion-academica/availability/my-availabilities.blade.php

# Ver último commit
git log -1 --stat

# Ver todos los cambios sin stagear
git status
```

---

## 🔄 SI NECESITAS DESHACER

```bash
# Si no hiciste commit aún
git restore app/Modules/GestionAcademica/Models/TeacherAvailability.php
git restore resources/views/gestion-academica/availability/my-availabilities.blade.php
git clean -fd  # Borrar archivos nuevos

# Si ya hiciste commit (último commit)
git reset --soft HEAD~1  # Deshacer commit pero mantener cambios
git reset --hard HEAD~1  # Deshacer commit y cambios (⚠️ IRREVERSIBLE)
```

---

## 📝 CREAR PR EN GITHUB

Después de hacer push:

1. **Ir a GitHub:**
   ```
   https://github.com/LuisCPedraza/sistema-asignacion-salones
   ```

2. **Click en "Compare & pull request"** (aparece después del push)

3. **Asegúrate de que:**
   - `base: main`
   - `compare: develop`

4. **Titulo del PR:**
   ```
   Release v2.0.0 - Hotfix: Corregir error format() en HU8 Profesor
   ```

5. **Descripción (copiar):**
   ```markdown
   ## 🔧 HOTFIX: HU8 - Error "Call to a member function format()"

   ### Problema
   - Ruta `/gestion-academica/my-availabilities` retornaba error 500
   - Vista intentaba hacer `.format()` en string, no en objeto Carbon
   - Afectaba roles: Profesor, Profesor Invitado

   ### Solución
   - Remover casts conflictivos en TeacherAvailability.php
   - Remover accessors que retornaban strings
   - Agregar métodos formateadores seguros (getFormattedStartTimeAttribute)
   - Actualizar vista para usar nuevos métodos

   ### Archivos Modificados
   - `app/Modules/GestionAcademica/Models/TeacherAvailability.php`
   - `resources/views/gestion-academica/availability/my-availabilities.blade.php`

   ### Impacto
   - ✅ HU8 desbloqueada (de error 500 a funcional)
   - ✅ 2 roles pueden acceder a funcionalidad crítica
   - ✅ Sin breaking changes en otras features

   ### Validación
   - [x] Testeado en navegador localmente
   - [x] Sin errores "Call to a member function"
   - [x] Tabla de disponibilidades carga correctamente
   - [x] Resumen semanal se muestra correctamente

   Tipo: Hotfix  
   Criticidad: 🔴 Alta  
   ```

6. **Click "Create pull request"**

---

## 🚀 DESPUÉS DEL COMMIT (PRÓXIMOS PASOS)

```bash
# 1. Cambiar a rama de siguiente feature
git checkout -b feature/hu9-auto-assignment-testing

# 2. Trabajar en HU9 (próxima tarea crítica)

# 3. Cuando termines
git add .
git commit -m "feat: completar HU9 algoritmo asignación automática"
git push origin feature/hu9-auto-assignment-testing

# 4. Crear PR en GitHub (feature → develop)
```

---

## 📋 RESUMEN TOTAL

```bash
# COMANDO ÚNICO (TODO EN UNO):
cd ~/proyectos/sistema-asignacion-salones && \
git add app/Modules/GestionAcademica/Models/TeacherAvailability.php resources/views/gestion-academica/availability/my-availabilities.blade.php HOTFIX_HU8_PROFESOR_DISPONIBILIDADES.md && \
git commit -m "fix: corregir error format() en HU8 Profesor disponibilidades" && \
git push origin develop && \
echo "✅ Commit realizado y pusheado a develop"
```

---

## ✅ CHECKLIST DE EJECUCIÓN

- [ ] Ejecuté script o comandos manuales
- [ ] Git status muestra commits pusheados
- [ ] Navegador carga http://127.0.0.1:8000/gestion-academica/my-availabilities sin error
- [ ] Tabla de disponibilidades se muestra
- [ ] Resumen semanal se muestra con horarios
- [ ] Creé PR en GitHub (develop → main)
- [ ] PR tiene título y descripción completos
- [ ] PR está listo para revisar

---

## 🆘 SI ALGO FALLA

### Error: "fatal: detected dubious ownership"

```bash
git config --global --add safe.directory '//wsl.localhost/Ubuntu/home/suario/proyectos/sistema-asignacion-salones'
```

### Error: "could not find driver"

```bash
# Normal si el servidor no está corriendo. Ignora y continúa
# Solo necesario si quieres hacer migrations
php artisan migrate
```

### Error: "Permission denied" en COMMIT_HU8_FIX.sh

```bash
chmod +x COMMIT_HU8_FIX.sh
bash COMMIT_HU8_FIX.sh
```

### Error en la vista: Sigue viendo error 500

```bash
# Limpiar agresivamente
php artisan cache:clear --force
php artisan view:clear --force
php artisan config:clear
composer dump-autoload

# Reiniciar servidor
# (Ctrl+C en terminal)
php artisan serve --host=127.0.0.1 --port=8000
```

---

**Última actualización:** Diciembre 2024  
**Para:** Ejecución desde Ubuntu/WSL  
**Estado:** ✅ LISTO PARA USAR
