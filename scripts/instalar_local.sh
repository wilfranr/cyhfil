#!/bin/bash

# Script de instalación para entorno local CYHFIL
# Este script automatiza la instalación del proyecto en local

set -e  # Salir si hay algún error

echo "=========================================="
echo "  Instalación Local - CYHFIL"
echo "=========================================="
echo ""

# Colores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Función para imprimir mensajes
print_success() {
    echo -e "${GREEN}✓${NC} $1"
}

print_error() {
    echo -e "${RED}✗${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}⚠${NC} $1"
}

print_info() {
    echo -e "ℹ $1"
}

# Verificar que estamos en el directorio correcto
if [ ! -f "artisan" ]; then
    print_error "Debes ejecutar este script desde el directorio raíz del proyecto Laravel"
    exit 1
fi

# Paso 1: Verificar requisitos
echo "1. Verificando requisitos del sistema..."
echo ""

# Verificar PHP
if ! command -v php &> /dev/null; then
    print_error "PHP no está instalado"
    exit 1
fi
PHP_VERSION=$(php -r "echo PHP_VERSION;" | cut -d. -f1,2)
print_success "PHP $PHP_VERSION encontrado"

# Verificar Composer
if ! command -v composer &> /dev/null; then
    print_error "Composer no está instalado"
    exit 1
fi
print_success "Composer encontrado"

# Verificar Node.js
if ! command -v node &> /dev/null; then
    print_error "Node.js no está instalado"
    exit 1
fi
NODE_VERSION=$(node --version)
print_success "Node.js $NODE_VERSION encontrado"

# Verificar npm
if ! command -v npm &> /dev/null; then
    print_error "npm no está instalado"
    exit 1
fi
print_success "npm encontrado"

# Verificar extensiones PHP requeridas
echo ""
echo "2. Verificando extensiones PHP..."
echo ""

REQUIRED_EXTENSIONS=("bcmath" "ctype" "fileinfo" "json" "mbstring" "openssl" "pdo" "tokenizer" "xml")
MISSING_EXTENSIONS=()

for ext in "${REQUIRED_EXTENSIONS[@]}"; do
    if php -m | grep -qi "^$ext$"; then
        print_success "Extensión $ext instalada"
    else
        print_error "Extensión $ext NO encontrada"
        MISSING_EXTENSIONS+=("$ext")
    fi
done

if [ ${#MISSING_EXTENSIONS[@]} -gt 0 ]; then
    echo ""
    print_warning "Faltan las siguientes extensiones PHP:"
    for ext in "${MISSING_EXTENSIONS[@]}"; do
        echo "  - php-$ext"
    done
    echo ""
    print_info "En Ubuntu/Debian, puedes instalarlas con:"
    echo "  sudo apt-get install php${PHP_VERSION}-bcmath php${PHP_VERSION}-mbstring php${PHP_VERSION}-xml php${PHP_VERSION}-pdo php${PHP_VERSION}-pdo-mysql"
    echo ""
    read -p "¿Deseas continuar de todas formas? (s/n): " -n 1 -r
    echo ""
    if [[ ! $REPLY =~ ^[Ss]$ ]]; then
        exit 1
    fi
fi

# Paso 3: Instalar dependencias de Composer
echo ""
echo "3. Instalando dependencias de PHP (Composer)..."
echo ""

if [ -d "vendor" ]; then
    print_warning "El directorio vendor ya existe. ¿Actualizar dependencias? (s/n)"
    read -p "" -n 1 -r
    echo ""
    if [[ $REPLY =~ ^[Ss]$ ]]; then
        composer update
    else
        print_info "Omitiendo instalación de dependencias PHP"
    fi
else
    composer install
    print_success "Dependencias de PHP instaladas"
fi

# Paso 4: Instalar dependencias de Node.js
echo ""
echo "4. Instalando dependencias de Node.js..."
echo ""

if [ -d "node_modules" ]; then
    print_warning "El directorio node_modules ya existe. ¿Actualizar dependencias? (s/n)"
    read -p "" -n 1 -r
    echo ""
    if [[ $REPLY =~ ^[Ss]$ ]]; then
        npm install
    else
        print_info "Omitiendo instalación de dependencias Node.js"
    fi
else
    npm install
    print_success "Dependencias de Node.js instaladas"
fi

# Paso 5: Verificar archivo .env
echo ""
echo "5. Verificando configuración de entorno..."
echo ""

if [ ! -f ".env" ]; then
    if [ -f ".env.example" ]; then
        print_info "Copiando .env.example a .env"
        cp .env.example .env
        print_success "Archivo .env creado"
    else
        print_error "No se encontró .env ni .env.example"
        exit 1
    fi
else
    print_success "Archivo .env existe"
fi

# Paso 6: Generar clave de aplicación
echo ""
echo "6. Generando clave de aplicación..."
echo ""

APP_KEY=$(grep "^APP_KEY=" .env | cut -d '=' -f2)
if [ -z "$APP_KEY" ] || [ "$APP_KEY" == "" ]; then
    php artisan key:generate
    print_success "Clave de aplicación generada"
else
    print_info "La clave de aplicación ya existe"
fi

# Paso 7: Verificar base de datos
echo ""
echo "7. Verificando configuración de base de datos..."
echo ""

DB_HOST=$(grep "^DB_HOST=" .env | cut -d '=' -f2)
DB_PORT=$(grep "^DB_PORT=" .env | cut -d '=' -f2)
DB_DATABASE=$(grep "^DB_DATABASE=" .env | cut -d '=' -f2)
DB_USERNAME=$(grep "^DB_USERNAME=" .env | cut -d '=' -f2)
DB_PASSWORD=$(grep "^DB_PASSWORD=" .env | cut -d '=' -f2)

print_info "Configuración de BD encontrada:"
echo "  Host: $DB_HOST"
echo "  Puerto: $DB_PORT"
echo "  Base de datos: $DB_DATABASE"
echo "  Usuario: $DB_USERNAME"
echo ""

# Verificar conexión a MySQL
if command -v mysql &> /dev/null; then
    print_info "Verificando conexión a MySQL..."
    if mysql -h"$DB_HOST" -P"$DB_PORT" -u"$DB_USERNAME" -p"$DB_PASSWORD" -e "SELECT 1" &> /dev/null; then
        print_success "Conexión a MySQL exitosa"
        
        # Verificar si la base de datos existe
        if mysql -h"$DB_HOST" -P"$DB_PORT" -u"$DB_USERNAME" -p"$DB_PASSWORD" -e "USE $DB_DATABASE" &> /dev/null; then
            print_success "Base de datos '$DB_DATABASE' existe"
        else
            print_warning "La base de datos '$DB_DATABASE' no existe"
            read -p "¿Deseas crearla ahora? (s/n): " -n 1 -r
            echo ""
            if [[ $REPLY =~ ^[Ss]$ ]]; then
                mysql -h"$DB_HOST" -P"$DB_PORT" -u"$DB_USERNAME" -p"$DB_PASSWORD" -e "CREATE DATABASE IF NOT EXISTS $DB_DATABASE CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
                print_success "Base de datos creada"
            fi
        fi
    else
        print_warning "No se pudo conectar a MySQL. Asegúrate de que MySQL esté corriendo y las credenciales sean correctas"
    fi
else
    print_warning "Cliente MySQL no encontrado. No se puede verificar la conexión"
fi

# Paso 8: Ejecutar migraciones
echo ""
echo "8. Ejecutando migraciones..."
echo ""

read -p "¿Deseas ejecutar las migraciones ahora? (s/n): " -n 1 -r
echo ""
if [[ $REPLY =~ ^[Ss]$ ]]; then
    php artisan migrate
    print_success "Migraciones ejecutadas"
    
    read -p "¿Deseas ejecutar los seeders (datos de prueba)? (s/n): " -n 1 -r
    echo ""
    if [[ $REPLY =~ ^[Ss]$ ]]; then
        php artisan db:seed
        print_success "Seeders ejecutados"
    fi
else
    print_info "Omitiendo migraciones. Puedes ejecutarlas después con: php artisan migrate"
fi

# Paso 9: Crear enlace simbólico de storage
echo ""
echo "9. Creando enlace simbólico de storage..."
echo ""

if [ -L "public/storage" ]; then
    print_info "El enlace simbólico ya existe"
else
    php artisan storage:link
    print_success "Enlace simbólico creado"
fi

# Paso 10: Optimizar aplicación
echo ""
echo "10. Optimizando aplicación..."
echo ""

php artisan config:cache
php artisan route:cache
print_success "Aplicación optimizada"

# Resumen final
echo ""
echo "=========================================="
echo "  Instalación Completada"
echo "=========================================="
echo ""
print_success "El proyecto está listo para ejecutarse"
echo ""
echo "Próximos pasos:"
echo ""
echo "1. Inicia el servidor de desarrollo de assets (en una terminal):"
echo "   ${GREEN}npm run dev${NC}"
echo ""
echo "2. Inicia el servidor Laravel (en otra terminal):"
echo "   ${GREEN}php artisan serve${NC}"
echo ""
echo "3. Accede a la aplicación en:"
echo "   ${GREEN}http://localhost:8000${NC}"
echo ""
echo "4. Si usas Filament, el panel administrativo estará en:"
echo "   ${GREEN}http://localhost:8000/admin${NC}"
echo ""
print_info "Para más información, consulta GUIA_INSTALACION.md"
echo ""

