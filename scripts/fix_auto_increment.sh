#!/bin/bash

# Script para corregir el problema de AUTO_INCREMENT en campos ID
# Este script resuelve el error: "SQLSTATE[HY000]: General error: 1364 Field 'id' doesn't have a default value"

echo "🔧 Corrigiendo problema de AUTO_INCREMENT en campos ID..."
echo ""

# Verificar si estamos en el directorio correcto
if [ ! -f "artisan" ]; then
    echo "❌ Error: No se encontró el archivo artisan. Ejecuta este script desde la raíz del proyecto Laravel."
    exit 1
fi

# Opción 1: Ejecutar la migración (recomendado)
echo "📋 Opción 1: Ejecutar migración Laravel"
echo "Ejecutando migración para corregir AUTO_INCREMENT..."
php artisan migrate --path=database/migrations/2025_08_23_000004_fix_auto_increment_ids.php

echo ""
echo "📋 Opción 2: Ejecutar script SQL directamente"
echo "Si la migración no funciona, puedes ejecutar el script SQL directamente:"
echo "mysql -u [usuario] -p [base_datos] < scripts/fix_auto_increment.sql"
echo ""

# Opción 3: Verificar el estado de las migraciones
echo "📋 Opción 3: Verificar estado de migraciones"
php artisan migrate:status

echo ""
echo "✅ Proceso completado. Verifica que el error se haya resuelto."
echo ""
echo "💡 Si sigues teniendo problemas, revisa:"
echo "   1. La conexión a la base de datos"
echo "   2. Los permisos del usuario de la base de datos"
echo "   3. Que las tablas existan en la base de datos"
echo ""
echo "🔍 Para verificar manualmente, ejecuta:"
echo "   mysql -u [usuario] -p [base_datos] -e \"DESCRIBE terceros;\""
