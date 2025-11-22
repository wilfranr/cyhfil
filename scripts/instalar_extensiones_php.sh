#!/bin/bash

# Script para instalar todas las extensiones PHP necesarias para CYHFIL

echo "Instalando extensiones PHP necesarias para CYHFIL..."
echo ""

# Extensiones requeridas
EXTENSIONS=(
    "php8.3-xml"
    "php8.3-dom"
    "php8.3-simplexml"
    "php8.3-xmlreader"
    "php8.3-xmlwriter"
    "php8.3-bcmath"
    "php8.3-gd"
    "php8.3-mbstring"
    "php8.3-pdo"
    "php8.3-pdo-mysql"
    "php8.3-curl"
    "php8.3-zip"
)

echo "Las siguientes extensiones serán instaladas:"
for ext in "${EXTENSIONS[@]}"; do
    echo "  - $ext"
done
echo ""

# Verificar si estamos en un sistema basado en apt (Debian/Ubuntu)
if command -v apt-get &> /dev/null; then
    echo "Instalando extensiones..."
    sudo apt-get update
    sudo apt-get install -y "${EXTENSIONS[@]}"
    
    echo ""
    echo "Verificando instalación..."
    php -m | grep -E "xml|dom|simplexml|xmlreader|xmlwriter|bcmath|gd|mbstring|pdo|curl|zip"
    
    echo ""
    echo "Extensiones instaladas correctamente!"
else
    echo "Error: Este script está diseñado para sistemas basados en apt (Debian/Ubuntu)"
    echo "Por favor, instala manualmente las siguientes extensiones:"
    for ext in "${EXTENSIONS[@]}"; do
        echo "  - $ext"
    done
fi

