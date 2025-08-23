#!/bin/bash

# Script de respaldo rápido para la base de datos CYH

echo "💾 Creando respaldo de la base de datos..."
echo ""

# Verificar si estamos en el directorio correcto
if [ ! -f "artisan" ]; then
    echo "❌ Error: Debes ejecutar este script desde el directorio raíz de Laravel"
    exit 1
fi

# Crear directorio de respaldos si no existe
BACKUP_DIR="database/backups"
mkdir -p "$BACKUP_DIR"

# Generar nombre del archivo de respaldo con timestamp
TIMESTAMP=$(date +%Y%m%d_%H%M%S)
BACKUP_FILE="$BACKUP_DIR/backup_$TIMESTAMP.sql"

echo "📊 Obteniendo configuración de la base de datos..."

# Obtener configuración de la base de datos
DB_HOST=$(php artisan tinker --execute="echo config('database.connections.mysql.host');" 2>/dev/null)
DB_PORT=$(php artisan tinker --execute="echo config('database.connections.mysql.port');" 2>/dev/null)
DB_DATABASE=$(php artisan tinker --execute="echo config('database.connections.mysql.database');" 2>/dev/null)
DB_USERNAME=$(php artisan tinker --execute="echo config('database.connections.mysql.username');" 2>/dev/null)
DB_PASSWORD=$(php artisan tinker --execute="echo config('database.connections.mysql.password');" 2>/dev/null)

if [ -z "$DB_DATABASE" ]; then
    echo "❌ Error: No se pudo obtener la configuración de la base de datos"
    echo "Verifica tu archivo .env"
    exit 1
fi

echo "📊 Base de datos: $DB_DATABASE"
echo "🌐 Servidor: $DB_HOST:$DB_PORT"
echo "👤 Usuario: $DB_USERNAME"
echo "💾 Archivo de respaldo: $BACKUP_FILE"
echo ""

# Crear el respaldo
echo "🔄 Creando respaldo..."
mysqldump -h"$DB_HOST" -P"$DB_PORT" -u"$DB_USERNAME" -p"$DB_PASSWORD" \
    --single-transaction \
    --routines \
    --triggers \
    --skip-events \
    "$DB_DATABASE" > "$BACKUP_FILE"

if [ $? -eq 0 ]; then
    # Obtener el tamaño del archivo
    FILE_SIZE=$(du -h "$BACKUP_FILE" | cut -f1)
    
    echo "✅ Respaldo creado exitosamente!"
    echo "📁 Ubicación: $BACKUP_FILE"
    echo "📏 Tamaño: $FILE_SIZE"
    echo ""
    
    # Mostrar información del respaldo
    echo "📋 Información del respaldo:"
    echo "  • Fecha: $(date)"
    echo "  • Archivo: $(basename "$BACKUP_FILE")"
    echo "  • Tamaño: $FILE_SIZE"
    echo "  • Base de datos: $DB_DATABASE"
    echo ""
    
    # Opción para comprimir
    read -p "¿Deseas comprimir el respaldo? (y/N): " comprimir
    
    if [[ $comprimir =~ ^[Yy]$ ]]; then
        echo "🗜️  Comprimiendo respaldo..."
        gzip "$BACKUP_FILE"
        COMPRESSED_FILE="$BACKUP_FILE.gz"
        COMPRESSED_SIZE=$(du -h "$COMPRESSED_FILE" | cut -f1)
        
        echo "✅ Respaldo comprimido exitosamente!"
        echo "📁 Archivo comprimido: $COMPRESSED_FILE"
        echo "📏 Tamaño comprimido: $COMPRESSED_SIZE"
        echo "💾 Espacio ahorrado: $(echo "scale=1; $(du -b "$COMPRESSED_FILE" | cut -f1) * 100 / $(du -b "$BACKUP_FILE" | cut -f1)" | bc)%"
        
        # Eliminar archivo sin comprimir
        rm "$BACKUP_FILE"
        echo "🗑️  Archivo sin comprimir eliminado"
    fi
    
    echo ""
    echo "💡 Para restaurar este respaldo:"
    echo "   mysql -u[usuario] -p[password] [base_datos] < $BACKUP_FILE"
    
    if [[ $comprimir =~ ^[Yy]$ ]]; then
        echo "   # O si está comprimido:"
        echo "   gunzip < $COMPRESSED_FILE | mysql -u[usuario] -p[password] [base_datos]"
    fi
    
else
    echo "❌ Error creando el respaldo"
    echo "Verifica la conectividad y permisos de la base de datos"
    exit 1
fi

echo ""
echo "🎉 Proceso de respaldo completado!"
