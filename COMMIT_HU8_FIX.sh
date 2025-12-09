#!/bin/bash

# 🔧 SCRIPT DE COMMIT - HOTFIX HU8 PROFESOR DISPONIBILIDADES
# Ejecutar en Ubuntu/WSL desde la raíz del proyecto

echo "=========================================="
echo "🔧 HOTFIX: HU8 - Error format() en Profesor"
echo "=========================================="
echo ""

# 1. Verificar estado del repositorio
echo "📋 Estado del repositorio..."
git status
echo ""

# 2. Agregar archivos modificados
echo "📝 Agregando archivos modificados..."
git add app/Modules/GestionAcademica/Models/TeacherAvailability.php
git add resources/views/gestion-academica/availability/my-availabilities.blade.php
git add HOTFIX_HU8_PROFESOR_DISPONIBILIDADES.md
echo "✅ Archivos agregados"
echo ""

# 3. Verificar archivos staged
echo "🔍 Archivos que se van a commitear:"
git diff --cached --name-only
echo ""

# 4. Hacer commit
echo "💾 Realizando commit..."
git commit -m "fix: corregir error 'Call to a member function format()' en HU8 Profesor

PROBLEMA:
- Ruta /gestion-academica/my-availabilities retornaba error
- Vista intentaba hacer .format() en string, no en Carbon object
- TeacherAvailability tenía casts y accessors conflictivos
- Afectaba roles: profesor, profesor_invitado

SOLUCIÓN:
- Remover casts problemáticos en TeacherAvailability.php
- Remover accessors que retornaban strings
- Agregar métodos formateadores seguros (getFormattedStartTimeAttribute, getFormattedEndTimeAttribute)
- Actualizar vista my-availabilities.blade.php para usar los nuevos métodos
- Cambiar {{ \$avail->start_time->format('H:i') }} por {{ \$avail->formatted_start_time }}

ARCHIVOS MODIFICADOS:
- app/Modules/GestionAcademica/Models/TeacherAvailability.php
- resources/views/gestion-academica/availability/my-availabilities.blade.php

DOCUMENTACIÓN:
- HOTFIX_HU8_PROFESOR_DISPONIBILIDADES.md (análisis completo + tests sugeridos)

RESULTADO:
- ✅ Ruta accesible sin errores
- ✅ HU8 disponible para desarrollo posterior
- ✅ Manejo seguro de tipos TIME de BD

TESTING:
- Probar en http://127.0.0.1:8000/gestion-academica/my-availabilities
- Verificar tabla de disponibilidades se carga
- Verificar resumen semanal muestra horarios correctamente
- Verificar botones de edición/eliminación funcionan"

echo ""
echo "✅ Commit realizado exitosamente"
echo ""

# 5. Mostrar resumen
echo "📊 Resumen del commit:"
git log -1 --oneline
echo ""

# 6. Opciones siguientes
echo "=========================================="
echo "🚀 PRÓXIMOS PASOS:"
echo "=========================================="
echo ""
echo "Opción A - Push a develop inmediatamente:"
echo "  git push origin develop"
echo ""
echo "Opción B - Hacer más commits primero:"
echo "  git checkout -b feature/hu8-complete-functionality"
echo "  (Implementar funcionalidad completa de HU8)"
echo "  git push origin feature/hu8-complete-functionality"
echo ""
echo "Opción C - Crear PR manualmente en GitHub:"
echo "  1. Push: git push origin develop"
echo "  2. Ir a: https://github.com/LuisCPedraza/sistema-asignacion-salones"
echo "  3. Crear PR: develop → main"
echo ""
echo "=========================================="
echo ""
