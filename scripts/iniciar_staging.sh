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
