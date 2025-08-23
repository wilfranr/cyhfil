#!/bin/bash

# Script para limpiar migraciones antiguas después de la consolidación
# ⚠️  SOLO EJECUTAR DESPUÉS DE VERIFICAR QUE LA CONSOLIDACIÓN FUNCIONA

echo "🧹 Script de Limpieza de Migraciones Antiguas"
echo "=============================================="
echo ""

# Verificar si estamos en el directorio correcto
if [ ! -f "artisan" ]; then
    echo "❌ Error: Debes ejecutar este script desde el directorio raíz de Laravel"
    exit 1
fi

# Verificar que estemos en la rama de consolidación
CURRENT_BRANCH=$(git branch --show-current)
if [ "$CURRENT_BRANCH" != "feature/migration-consolidation" ]; then
    echo "❌ Error: Debes estar en la rama 'feature/migration-consolidation'"
    echo "Rama actual: $CURRENT_BRANCH"
    exit 1
fi

echo "✅ Rama correcta: $CURRENT_BRANCH"
echo ""

# Crear backup de las migraciones actuales
echo "💾 Creando backup de las migraciones actuales..."
BACKUP_DIR="database/migrations_backup_$(date +%Y%m%d_%H%M%S)"
mkdir -p "$BACKUP_DIR"
cp -r database/migrations/* "$BACKUP_DIR/"
echo "✅ Backup creado en: $BACKUP_DIR"
echo ""

# Mostrar migraciones que se van a eliminar
echo "📋 Migraciones que se van a eliminar (excepto las consolidadas):"
echo ""

# Listar migraciones que NO son de consolidación
find database/migrations -name "*.php" -type f | grep -v "consolidate" | sort

echo ""
echo "📊 Total de migraciones a eliminar: $(find database/migrations -name "*.php" -type f | grep -v "consolidate" | wc -l)"
echo "📊 Migraciones de consolidación a mantener: $(find database/migrations -name "*consolidate*.php" -type f | wc -l)"
echo ""

read -p "¿Continuar con la eliminación? (escribe 'ELIMINAR' para confirmar): " confirmacion

if [ "$confirmacion" != "ELIMINAR" ]; then
    echo "❌ Operación cancelada"
    echo "Puedes eliminar manualmente el directorio de backup: $BACKUP_DIR"
    exit 0
fi

echo ""
echo "🗑️  Eliminando migraciones antiguas..."

# Eliminar migraciones que NO son de consolidación
find database/migrations -name "*.php" -type f | grep -v "consolidate" | xargs rm -f

echo "✅ Migraciones antiguas eliminadas"
echo ""

# Verificar el resultado
echo "📊 Estado final:"
echo "Migraciones restantes: $(find database/migrations -name "*.php" -type f | wc -l)"
echo ""

ls -la database/migrations/*.php

echo ""
echo "🎉 Limpieza completada exitosamente!"
echo "📁 Backup disponible en: $BACKUP_DIR"
echo ""
echo "⚠️  IMPORTANTE:"
echo "1. Verifica que la aplicación funcione correctamente"
echo "2. Ejecuta 'php artisan migrate:status' para verificar el estado"
echo "3. Si todo está bien, haz commit de los cambios"
echo "4. Si hay problemas, restaura desde el backup"
echo ""
echo "🔄 Para restaurar desde el backup:"
echo "cp -r $BACKUP_DIR/* database/migrations/"
