# CYHFIL

Aplicación de gestión de inventario, pedidos y cotizaciones desarrollada con Laravel 10 y Filament 3.

## Características principales

- Panel administrativo basado en Filament para gestionar Artículos, Referencias, Fabricantes, Pedidos y Órdenes de Trabajo.
- Chat interno en tiempo real mediante eventos y broadcasting.
- Generación de documentos PDF para cotizaciones y órdenes de compra.
- Importación de registros desde archivos Excel.
- Política de acceso y roles administrados con `spatie/laravel-permission`.

## Requisitos

- PHP \>= 8.1
- Composer
- Node.js y npm
- Base de datos MySQL/MariaDB u otra compatible

## Instalación

1. Clonar el repositorio.
2. Copiar `.env.example` a `.env` y ajustar las variables de entorno.
3. Ejecutar `composer install` para instalar las dependencias de PHP.
4. Ejecutar `npm install` para las dependencias de JavaScript.
5. Generar la clave de la aplicación con `php artisan key:generate`.
6. Ejecutar las migraciones con `php artisan migrate`.
7. Levantar el entorno de desarrollo con `npm run dev`.

## Documentación del código

El script `scripts/generate_docs.py` recorre el directorio `app` y crea `docs/CodeBase.md` con un resumen de las clases y métodos.

```bash
python3 scripts/generate_docs.py
```

## Pruebas

Este proyecto utiliza PHPUnit. Para ejecutar las pruebas:

```bash
./vendor/bin/phpunit
```

## Licencia

El código está disponible bajo la licencia MIT según se indica en `composer.json`.
