#!/bin/bash

# Script para limpiar el entorno de staging local

echo "🧹 Limpiando entorno de staging local..."
echo ""

read -p "¿Estás seguro? Esto eliminará la base de datos de staging (escribe 'SI' para confirmar): " confirmacion

if [ "$confirmacion" != "SI" ]; then
    echo "❌ Operación cancelada"
    exit 0
fi

# Eliminar base de datos de staging
echo "🗄️  Eliminando base de datos de staging..."
mysql -h127.0.0.1 -P3307 -uroot -p -e "DROP DATABASE IF EXISTS cyhfilament_test;"

# Eliminar archivo de configuración
echo "🗑️  Eliminando archivo de configuración..."
rm -f .env.staging

echo "✅ Entorno de staging limpiado exitosamente"
