#!/bin/bash

# Script de limpieza rápida con respaldo automático

echo "🚀 Script de Limpieza Rápida de Base de Datos CYH"
echo "=================================================="
echo ""

# Verificar si estamos en el directorio correcto
if [ ! -f "artisan" ]; then
    echo "❌ Error: Debes ejecutar este script desde el directorio raíz de Laravel"
    exit 1
fi

echo "📋 Este script realizará:"
echo "  1. ✅ Respaldo automático de la base de datos"
echo "  2. 🗑️  Limpieza completa (manteniendo estructura)"
echo "  3. 🔍 Verificación del resultado"
echo ""

read -p "¿Continuar? (escribe 'SI' para confirmar): " confirmacion

if [ "$confirmacion" != "SI" ]; then
    echo "❌ Operación cancelada"
    exit 0
fi

echo ""
echo "🔄 Paso 1: Creando respaldo automático..."
echo ""

# Ejecutar script de respaldo
./scripts/backup_db.sh

if [ $? -ne 0 ]; then
    echo "❌ Error en el respaldo. Abortando limpieza."
    exit 1
fi

echo ""
echo "🔄 Paso 2: Ejecutando limpieza de la base de datos..."
echo ""

# Ejecutar limpieza con confirmación automática
php artisan db:limpiar --confirm

if [ $? -ne 0 ]; then
    echo "❌ Error en la limpieza."
    echo "💡 Puedes restaurar desde el respaldo creado anteriormente."
    exit 1
fi

echo ""
echo "🔄 Paso 3: Verificando resultado..."
echo ""

# Verificar que las tablas esenciales existan
echo "📊 Verificando tablas esenciales..."

TABLAS_ESENCIALES=(
    "listas"
    "users"
    "permissions"
    "roles"
    "role_has_permissions"
    "trms"
    "empresas"
    "fabricantes"
    "sistemas"
    "model_has_permissions"
    "model_has_roles"
    "states"
    "countries"
    "cities"
)

TABLAS_OK=0
TABLAS_TOTAL=${#TABLAS_ESENCIALES[@]}

for tabla in "${TABLAS_ESENCIALES[@]}"; do
    if php artisan tinker --execute="echo Schema::hasTable('$tabla') ? '✅' : '❌';" 2>/dev/null | grep -q "✅"; then
        echo "  ✅ $tabla"
        ((TABLAS_OK++))
    else
        echo "  ❌ $tabla"
    fi
done

echo ""
echo "📊 Resumen de verificación:"
echo "  • Tablas verificadas: $TABLAS_TOTAL"
echo "  • Tablas existentes: $TABLAS_OK"
echo "  • Tablas faltantes: $((TABLAS_TOTAL - TABLAS_OK))"

if [ $TABLAS_OK -eq $TABLAS_TOTAL ]; then
    echo ""
    echo "🎉 ¡Limpieza completada exitosamente!"
    echo ""
    echo "📝 Resumen de la operación:"
    echo "  ✅ Respaldo creado en database/backups/"
    echo "  ✅ Base de datos limpiada"
    echo "  ✅ Estructura de tablas esenciales mantenida"
    echo ""
    echo "💡 Próximos pasos recomendados:"
    echo "  • Verificar que la aplicación funcione correctamente"
    echo "  • Si necesitas datos de prueba: php artisan db:seed"
    echo "  • Revisar permisos de usuarios si es necesario"
else
    echo ""
    echo "⚠️  Advertencia: Algunas tablas esenciales no se encontraron"
    echo "💡 Verifica que las migraciones estén ejecutadas: php artisan migrate"
fi

echo ""
echo "🔒 El respaldo está disponible en database/backups/"
echo "📚 Revisa scripts/README.md para más información"
