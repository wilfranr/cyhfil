#!/bin/bash

# Script para limpiar la base de datos CYH
# Mantiene solo las tablas esenciales: Listas, users, permissions, role_has_permissions, trms, empresa, model_has_permissions, model_has_roles, states, countries, cities

echo "🚨 ADVERTENCIA: Este script limpiará TODA la base de datos"
echo "Solo se mantendrán las tablas esenciales con su estructura"
echo ""

# Verificar si estamos en el directorio correcto
if [ ! -f "artisan" ]; then
    echo "❌ Error: Debes ejecutar este script desde el directorio raíz de Laravel"
    exit 1
fi

# Verificar si existe el comando artisan
if ! command -v php &> /dev/null; then
    echo "❌ Error: PHP no está instalado o no está en el PATH"
    exit 1
fi

echo "📋 Opciones disponibles:"
echo "1. Usar comando Artisan (recomendado)"
echo "2. Ejecutar script SQL directamente"
echo "3. Solo mostrar qué se limpiaría (dry run)"
echo ""

read -p "Selecciona una opción (1-3): " opcion

case $opcion in
    1)
        echo ""
        echo "🔄 Ejecutando limpieza con Artisan..."
        php artisan db:limpiar
        ;;
    2)
        echo ""
        echo "⚠️  ADVERTENCIA: Ejecutarás SQL directamente en la base de datos"
        echo "Asegúrate de tener un respaldo antes de continuar"
        echo ""
        read -p "¿Continuar? (escribe 'SI' para confirmar): " confirmacion
        
        if [ "$confirmacion" = "SI" ]; then
            echo "🔄 Ejecutando script SQL..."
            
            # Obtener configuración de la base de datos
            DB_HOST=$(php artisan tinker --execute="echo config('database.connections.mysql.host');" 2>/dev/null)
            DB_PORT=$(php artisan tinker --execute="echo config('database.connections.mysql.port');" 2>/dev/null)
            DB_DATABASE=$(php artisan tinker --execute="echo config('database.connections.mysql.database');" 2>/dev/null)
            DB_USERNAME=$(php artisan tinker --execute="echo config('database.connections.mysql.username');" 2>/dev/null)
            DB_PASSWORD=$(php artisan tinker --execute="echo config('database.connections.mysql.password');" 2>/dev/null)
            
            if [ -z "$DB_DATABASE" ]; then
                echo "❌ Error: No se pudo obtener la configuración de la base de datos"
                exit 1
            fi
            
            echo "📊 Conectando a: $DB_DATABASE en $DB_HOST:$DB_PORT"
            
            # Ejecutar el script SQL
            mysql -h"$DB_HOST" -P"$DB_PORT" -u"$DB_USERNAME" -p"$DB_PASSWORD" "$DB_DATABASE" < scripts/limpiar_base_datos.sql
            
            if [ $? -eq 0 ]; then
                echo "✅ Base de datos limpiada exitosamente"
            else
                echo "❌ Error ejecutando el script SQL"
                exit 1
            fi
        else
            echo "❌ Operación cancelada"
            exit 0
        fi
        ;;
    3)
        echo ""
        echo "🔍 Modo dry run - mostrando qué se limpiaría:"
        php artisan db:limpiar --help
        echo ""
        echo "Para ver exactamente qué se limpiaría, ejecuta:"
        echo "php artisan db:limpiar"
        ;;
    *)
        echo "❌ Opción inválida"
        exit 1
        ;;
esac

echo ""
echo "🎉 Proceso completado!"
echo ""
echo "📝 Recordatorio:"
echo "• Se mantuvieron las tablas esenciales con su estructura"
echo "• Todos los datos han sido eliminados permanentemente"
echo "• Si necesitas datos de prueba, ejecuta: php artisan db:seed"
echo ""
