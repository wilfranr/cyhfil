#!/bin/bash

# Script para configurar entorno de staging local para CYH (Versión 2)
# Crea una base de datos de prueba y configura el entorno

echo "🛠️  Configurando Entorno de Staging Local para CYH (v2)"
echo "========================================================="
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

# 1. Crear base de datos de prueba limpia
echo "🗄️  1. Configurando base de datos de prueba..."
DB_TEST="cyhfilament_test"

# Eliminar base de datos si existe y crear nueva
mysql -h127.0.0.1 -P3307 -uroot -p -e "DROP DATABASE IF EXISTS $DB_TEST; CREATE DATABASE $DB_TEST;" 2>/dev/null

if [ $? -eq 0 ]; then
    echo "   ✅ Base de datos '$DB_TEST' creada limpia"
else
    echo "   ❌ Error creando base de datos de prueba"
    exit 1
fi

# 2. Crear archivo de configuración temporal
echo ""
echo "⚙️  2. Creando configuración de staging..."

# Crear archivo .env.staging temporal
cat > .env.staging << EOF
APP_NAME="CYH - Staging Local"
APP_ENV=staging
APP_KEY=base64:staging-key-temp
APP_DEBUG=true
APP_URL=http://localhost:8001

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3307
DB_DATABASE=$DB_TEST
DB_USERNAME=root
DB_PASSWORD=

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="staging@cyh.local"
MAIL_FROM_NAME="CYH Staging"

VITE_APP_NAME="CYH Staging"
EOF

echo "   ✅ Archivo .env.staging creado"

# 3. Generar clave de aplicación para staging
echo ""
echo "🔑 3. Generando clave de aplicación para staging..."
php artisan key:generate --env=staging

if [ $? -eq 0 ]; then
    echo "   ✅ Clave de aplicación generada"
else
    echo "   ⚠️  No se pudo generar clave (puede que ya exista)"
fi

# 4. Ejecutar migraciones consolidadas manualmente
echo ""
echo "🔄 4. Ejecutando migraciones consolidadas..."

# Cambiar temporalmente a la base de datos de prueba
export DB_DATABASE=$DB_TEST

# Ejecutar solo las migraciones consolidadas
php artisan migrate --env=staging --path=database/migrations/2025_08_23_000001_consolidate_core_system_tables.php --force
php artisan migrate --env=staging --path=database/migrations/2025_08_23_000002_consolidate_business_module_tables.php --force
php artisan migrate --env=staging --path=database/migrations/2025_08_23_000003_consolidate_operations_module_tables.php --force

if [ $? -eq 0 ]; then
    echo "   ✅ Migraciones consolidadas ejecutadas exitosamente"
else
    echo "   ❌ Error ejecutando migraciones consolidadas"
    exit 1
fi

# 5. Verificar estado de migraciones
echo ""
echo "📊 5. Verificando estado de migraciones..."
php artisan migrate:status --env=staging

# 6. Crear datos de prueba básicos
echo ""
echo "🌱 6. Creando datos de prueba básicos..."

# Crear usuario de prueba
php artisan tinker --env=staging --execute="
if (!DB::table('users')->where('email', 'staging@cyh.local')->exists()) {
    DB::table('users')->insert([
        'name' => 'Usuario Staging',
        'email' => 'staging@cyh.local',
        'password' => bcrypt('password'),
        'created_at' => now(),
        'updated_at' => now()
    ]);
    echo 'Usuario de prueba creado: staging@cyh.local / password';
} else {
    echo 'Usuario de prueba ya existe';
}
"

# 7. Verificar integridad
echo ""
echo "🔍 7. Verificando integridad del entorno de staging..."

# Verificar tablas críticas
TABLES_TO_CHECK=("users" "permissions" "roles" "empresas" "fabricantes" "sistemas")

for table in "${TABLES_TO_CHECK[@]}"; do
    TABLE_EXISTS=$(php artisan tinker --env=staging --execute="echo Schema::hasTable('$table') ? 'true' : 'false';" 2>/dev/null)
    
    if [ "$TABLE_EXISTS" = "true" ]; then
        echo "   ✅ Tabla '$table' existe en staging"
    else
        echo "   ❌ Tabla '$table' NO existe en staging"
    fi
done

# 8. Crear script de inicio para staging
echo ""
echo "📝 8. Creando script de inicio para staging..."

cat > scripts/iniciar_staging.sh << 'EOF'
#!/bin/bash

# Script para iniciar el entorno de staging local

echo "🚀 Iniciando entorno de staging local..."
echo ""

# Configurar variables de entorno para staging
export DB_DATABASE=cyhfilament_test

# Iniciar servidor en puerto 8001
echo "🌐 Iniciando servidor en puerto 8001..."
php artisan serve --host=127.0.0.1 --port=8001 --env=staging

echo ""
echo "✅ Entorno de staging iniciado en: http://127.0.0.1:8001"
echo "👤 Usuario de prueba: staging@cyh.local"
echo "🔑 Contraseña: password"
echo ""
echo "Para detener: Ctrl+C"
EOF

chmod +x scripts/iniciar_staging.sh

# 9. Crear script de limpieza para staging
echo ""
echo "🧹 9. Creando script de limpieza para staging..."

cat > scripts/limpiar_staging.sh << 'EOF'
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
EOF

chmod +x scripts/limpiar_staging.sh

# Resumen final
echo ""
echo "🎉 ENTORNO DE STAGING LOCAL CONFIGURADO EXITOSAMENTE"
echo "====================================================="
echo ""
echo "📋 Resumen de la configuración:"
echo "   - Base de datos: $DB_TEST"
echo "   - Configuración: .env.staging"
echo "   - Migraciones: Ejecutadas correctamente"
echo "   - Usuario de prueba: staging@cyh.local / password"
echo ""
echo "🚀 Para usar el entorno de staging:"
echo "   1. Iniciar: ./scripts/iniciar_staging.sh"
echo "   2. Acceder: http://127.0.0.1:8001"
echo "   3. Limpiar: ./scripts/limpiar_staging.sh"
echo ""
echo "⚠️  IMPORTANTE:"
echo "   - Este es un entorno de PRUEBAS local"
echo "   - No afecta tu base de datos principal"
echo "   - Usa para validar la consolidación"
echo "   - Los datos son de ejemplo, no reales"
echo ""
echo "🔍 Próximo paso: Ejecutar ./scripts/iniciar_staging.sh"
