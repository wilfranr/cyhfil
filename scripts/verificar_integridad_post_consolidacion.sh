#!/bin/bash

# Script de Verificación de Integridad Post-Consolidación
# Verifica que el sistema funcione correctamente después de la consolidación

echo "🔍 Verificación de Integridad Post-Consolidación"
echo "================================================"
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

# 1. Verificar estado de migraciones
echo "📊 1. Verificando estado de migraciones..."
MIGRATION_COUNT=$(find database/migrations -name "*.php" -type f | wc -l)
echo "   Migraciones activas: $MIGRATION_COUNT"

if [ "$MIGRATION_COUNT" -eq 3 ]; then
    echo "   ✅ Estado correcto: 3 migraciones consolidadas"
else
    echo "   ❌ Estado incorrecto: Se esperaban 3 migraciones"
    exit 1
fi

# 2. Verificar que las migraciones estén ejecutadas
echo ""
echo "🔄 2. Verificando estado de ejecución de migraciones..."
MIGRATION_STATUS=$(php artisan migrate:status 2>/dev/null | grep "consolidate" | wc -l)

if [ "$MIGRATION_STATUS" -eq 3 ]; then
    echo "   ✅ Todas las migraciones consolidadas están ejecutadas"
else
    echo "   ❌ Algunas migraciones consolidadas no están ejecutadas"
    exit 1
fi

# 3. Verificar que la aplicación funcione
echo ""
echo "🌐 3. Verificando funcionamiento de la aplicación..."
echo "   Iniciando servidor de prueba..."

# Iniciar servidor en background
php artisan serve --host=127.0.0.1 --port=8001 > /dev/null 2>&1 &
SERVER_PID=$!

# Esperar a que el servidor esté listo
sleep 5

# Verificar que el servidor esté funcionando
if ps -p $SERVER_PID > /dev/null; then
    echo "   ✅ Servidor iniciado correctamente"
    
    # Probar conexión
    HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" http://127.0.0.1:8001 2>/dev/null)
    
    if [ "$HTTP_CODE" = "302" ] || [ "$HTTP_CODE" = "200" ]; then
        echo "   ✅ Aplicación responde correctamente (HTTP $HTTP_CODE)"
    else
        echo "   ❌ Aplicación no responde correctamente (HTTP $HTTP_CODE)"
        kill $SERVER_PID 2>/dev/null
        exit 1
    fi
    
    # Detener servidor
    kill $SERVER_PID 2>/dev/null
    echo "   ✅ Servidor detenido correctamente"
else
    echo "   ❌ No se pudo iniciar el servidor"
    exit 1
fi

# 4. Verificar integridad de la base de datos
echo ""
echo "🗄️  4. Verificando integridad de la base de datos..."

# Verificar tablas críticas
TABLES_TO_CHECK=("users" "permissions" "roles" "empresas" "fabricantes" "sistemas" "articulos" "referencias" "terceros" "pedidos")

for table in "${TABLES_TO_CHECK[@]}"; do
    TABLE_EXISTS=$(php artisan tinker --execute="echo Schema::hasTable('$table') ? 'true' : 'false';" 2>/dev/null)
    
    if [ "$TABLE_EXISTS" = "true" ]; then
        echo "   ✅ Tabla '$table' existe"
    else
        echo "   ❌ Tabla '$table' NO existe"
        exit 1
    fi
done

# 5. Verificar backup de migraciones
echo ""
echo "💾 5. Verificando backup de migraciones..."
BACKUP_DIR="database/migrations_backup_20250823_152021"

if [ -d "$BACKUP_DIR" ]; then
    BACKUP_COUNT=$(find "$BACKUP_DIR" -name "*.php" -type f | wc -l)
    echo "   ✅ Backup disponible en: $BACKUP_DIR"
    echo "   📊 Migraciones en backup: $BACKUP_COUNT"
    
    if [ "$BACKUP_COUNT" -ge 70 ]; then
        echo "   ✅ Backup completo: Contiene suficientes migraciones"
    else
        echo "   ⚠️  Backup incompleto: Pocas migraciones encontradas"
    fi
else
    echo "   ❌ Backup no encontrado"
    exit 1
fi

# 6. Verificar backup de base de datos
echo ""
echo "🗄️  6. Verificando backup de base de datos..."
BACKUP_DB="database/backups/backup_migration_consolidation.sql"

if [ -f "$BACKUP_DB" ]; then
    BACKUP_SIZE=$(du -h "$BACKUP_DB" | cut -f1)
    echo "   ✅ Backup de BD disponible: $BACKUP_DB"
    echo "   📊 Tamaño del backup: $BACKUP_SIZE"
else
    echo "   ❌ Backup de BD no encontrado"
    exit 1
fi

# 7. Verificar estado de Git
echo ""
echo "📝 7. Verificando estado de Git..."
GIT_STATUS=$(git status --porcelain | wc -l)

if [ "$GIT_STATUS" -eq 0 ]; then
    echo "   ✅ Repositorio Git limpio"
else
    echo "   ⚠️  Hay cambios sin commitear ($GIT_STATUS archivos)"
fi

# Resumen final
echo ""
echo "🎉 VERIFICACIÓN COMPLETADA EXITOSAMENTE"
echo "========================================"
echo ""
echo "✅ Todas las verificaciones han pasado"
echo "✅ El sistema está funcionando correctamente"
echo "✅ La consolidación fue exitosa"
echo ""
echo "📋 Resumen del estado:"
echo "   - Migraciones: $MIGRATION_COUNT activas (3 consolidadas)"
echo "   - Base de datos: Funcionando correctamente"
echo "   - Aplicación: Respondiendo correctamente"
echo "   - Backup de migraciones: $BACKUP_COUNT archivos"
echo "   - Backup de BD: $BACKUP_SIZE"
echo ""
echo "🚀 El sistema está listo para:"
echo "   - Desarrollo continuo"
echo "   - Despliegue en staging"
echo "   - Preparación para producción"
echo ""
echo "⚠️  Recordatorios importantes:"
echo "   - Las migraciones consolidadas NO se pueden hacer rollback"
echo "   - Mantener backups antes de cambios importantes"
echo "   - Probar en staging antes de producción"
echo "   - Documentar futuras modificaciones"
