# Guía de Instalación y Ejecución Local - CYHFIL

Esta guía te ayudará a configurar y ejecutar el proyecto CYHFIL en tu entorno local.

## Requisitos Previos

- PHP >= 8.1 (tienes PHP 8.3.6 ✓)
- Composer (tienes Composer 2.7.1 ✓)
- Node.js y npm (tienes Node v25.2.1 y npm 11.6.2 ✓)
- MySQL/MariaDB (necesario instalar y configurar)
- Extensiones PHP requeridas: BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML

## Opción 1: Instalación sin Docker (Recomendada para desarrollo rápido)

### Paso 1: Verificar extensiones PHP

```bash
php -m | grep -E "bcmath|ctype|fileinfo|json|mbstring|openssl|pdo|tokenizer|xml"
```

Si falta alguna extensión, instálala según tu distribución Linux.

### Paso 2: Instalar dependencias de PHP

```bash
composer install
```

### Paso 3: Instalar dependencias de Node.js

```bash
npm install
```

### Paso 4: Configurar variables de entorno

El archivo `.env` ya existe. Verifica que tenga las siguientes configuraciones importantes:

- `APP_KEY`: Debe estar generada
- `DB_CONNECTION`: mysql
- `DB_HOST`: 127.0.0.1 (o la IP de tu MySQL)
- `DB_PORT`: 3306 (puerto por defecto de MySQL)
- `DB_DATABASE`: nombre de tu base de datos
- `DB_USERNAME`: usuario de MySQL
- `DB_PASSWORD`: contraseña de MySQL

Si necesitas regenerar la clave de aplicación:

```bash
php artisan key:generate
```

### Paso 5: Configurar base de datos MySQL

Asegúrate de tener MySQL instalado y ejecutándose:

```bash
# Verificar si MySQL está corriendo
sudo systemctl status mysql
# O iniciarlo si no está corriendo
sudo systemctl start mysql
```

Crea la base de datos:

```bash
mysql -u root -p
```

Dentro de MySQL:

```sql
CREATE DATABASE cyhfilament CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'cyhfilament_user'@'localhost' IDENTIFIED BY 'tu_contraseña_segura';
GRANT ALL PRIVILEGES ON cyhfilament.* TO 'cyhfilament_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

Actualiza el archivo `.env` con estos datos:

```
DB_DATABASE=cyhfilament
DB_USERNAME=cyhfilament_user
DB_PASSWORD=tu_contraseña_segura
```

### Paso 6: Ejecutar migraciones

```bash
php artisan migrate
```

Si necesitas datos de prueba, puedes ejecutar seeders:

```bash
php artisan db:seed
```

### Paso 7: Crear enlace simbólico para almacenamiento

```bash
php artisan storage:link
```

### Paso 8: Compilar assets de desarrollo

En una terminal, ejecuta:

```bash
npm run dev
```

### Paso 9: Iniciar servidor de desarrollo

En otra terminal, ejecuta:

```bash
php artisan serve
```

El proyecto estará disponible en: `http://localhost:8000`

## Opción 2: Instalación con Docker (Laravel Sail)

Si prefieres usar Docker, primero necesitas configurar Docker Desktop con integración WSL2.

### Configurar Docker en WSL2

1. Instala Docker Desktop en Windows
2. Abre Docker Desktop
3. Ve a Settings > Resources > WSL Integration
4. Habilita la integración para tu distribución WSL2
5. Reinicia WSL2 o Docker Desktop

### Usar Laravel Sail

Una vez Docker esté configurado:

```bash
# Dar permisos de ejecución al script sail
chmod +x sail

# Iniciar los contenedores
./sail up -d

# Instalar dependencias de PHP (dentro del contenedor)
./sail composer install

# Instalar dependencias de Node.js
./sail npm install

# Generar clave de aplicación
./sail artisan key:generate

# Ejecutar migraciones
./sail artisan migrate

# Compilar assets
./sail npm run dev
```

El proyecto estará disponible en: `http://localhost` (puerto configurado en APP_PORT del .env)

## Comandos Útiles

### Artisan (Laravel)

```bash
# Ver todas las rutas
php artisan route:list

# Limpiar caché
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Optimizar aplicación
php artisan optimize

# Ver estado de migraciones
php artisan migrate:status
```

### Composer

```bash
# Actualizar dependencias
composer update

# Actualizar solo dependencias de desarrollo
composer update --dev
```

### NPM

```bash
# Compilar para producción
npm run build

# Ejecutar en modo desarrollo (watch)
npm run dev
```

## Solución de Problemas Comunes

### Error: "SQLSTATE[HY000] [2002] No such file or directory"

- Verifica que MySQL esté corriendo: `sudo systemctl status mysql`
- Verifica la configuración de `DB_HOST` en `.env` (debe ser `127.0.0.1` o `localhost`)

### Error: Extensiones PHP faltantes

Si obtienes errores sobre extensiones PHP faltantes (ext-dom, ext-xml, ext-simplexml, etc.), ejecuta:

```bash
# Opción 1: Usar el script automatizado
./scripts/instalar_extensiones_php.sh

# Opción 2: Instalar manualmente todas las extensiones necesarias
sudo apt-get update
sudo apt-get install -y php8.3-xml php8.3-dom php8.3-simplexml php8.3-xmlreader php8.3-xmlwriter php8.3-bcmath php8.3-gd php8.3-mbstring php8.3-pdo php8.3-pdo-mysql php8.3-curl php8.3-zip
```

### Error: "Class 'PDO' not found"

- Instala la extensión PDO de PHP: `sudo apt-get install php8.3-pdo php8.3-mysql`

### Error: "Permission denied" en storage o bootstrap/cache

```bash
chmod -R 775 storage bootstrap/cache
chown -R $USER:www-data storage bootstrap/cache
```

### Error al compilar assets con Vite

- Asegúrate de que el puerto 5173 esté disponible
- Verifica que `npm run dev` esté corriendo antes de acceder a la aplicación

## Estructura del Proyecto

- `app/`: Código fuente de la aplicación Laravel
- `config/`: Archivos de configuración
- `database/`: Migraciones, seeders y factories
- `public/`: Punto de entrada público
- `resources/`: Vistas, CSS, JavaScript
- `routes/`: Definición de rutas
- `storage/`: Archivos subidos y logs
- `tests/`: Pruebas automatizadas

## Próximos Pasos

1. Accede al panel administrativo de Filament (generalmente en `/admin`)
2. Crea un usuario administrador si no existe
3. Configura los permisos y roles según necesites
4. Revisa la documentación en `docs/` para más información

## Notas Adicionales

- El proyecto usa Filament 3 para el panel administrativo
- Incluye broadcasting en tiempo real (requiere configuración de Pusher o similar)
- Genera PDFs con dompdf
- Permite importar datos desde Excel

