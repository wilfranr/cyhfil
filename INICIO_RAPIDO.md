# Inicio Rápido - CYHFIL

## Instalación Automatizada (Recomendada)

Ejecuta el script de instalación que automatiza todo el proceso:

```bash
./scripts/instalar_local.sh
```

Este script verificará todos los requisitos, instalará dependencias y configurará el proyecto.

## Instalación Manual (Paso a Paso)

### 1. Instalar dependencias

```bash
# Dependencias PHP
composer install

# Dependencias Node.js
npm install
```

### 2. Configurar entorno

```bash
# Si no existe .env, copiarlo desde .env.example
cp .env.example .env

# Generar clave de aplicación
php artisan key:generate
```

### 3. Configurar base de datos

Edita el archivo `.env` y configura:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cyhfilament
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

Crea la base de datos en MySQL:

```bash
mysql -u root -p
```

```sql
CREATE DATABASE cyhfilament CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 4. Ejecutar migraciones

```bash
php artisan migrate
```

### 5. Crear enlace de storage

```bash
php artisan storage:link
```

### 6. Iniciar servidores

**Terminal 1** - Servidor de assets (Vite):
```bash
npm run dev
```

**Terminal 2** - Servidor Laravel:
```bash
php artisan serve
```

### 7. Acceder a la aplicación

- Aplicación: http://localhost:8000
- Panel Filament: http://localhost:8000/admin

## Comandos Útiles

```bash
# Limpiar cachés
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Ver rutas
php artisan route:list

# Ver estado de migraciones
php artisan migrate:status

# Compilar assets para producción
npm run build
```

## Solución de Problemas

### MySQL no conecta
- Verifica que MySQL esté corriendo: `sudo systemctl status mysql`
- Verifica las credenciales en `.env`

### Error de permisos en storage
```bash
chmod -R 775 storage bootstrap/cache
```

### Extensiones PHP faltantes

Si obtienes errores sobre extensiones PHP faltantes al ejecutar `composer install`:

```bash
# Opción 1: Script automatizado
./scripts/instalar_extensiones_php.sh

# Opción 2: Instalar manualmente todas las extensiones necesarias
sudo apt-get update
sudo apt-get install -y php8.3-xml php8.3-dom php8.3-simplexml php8.3-xmlreader php8.3-xmlwriter php8.3-bcmath php8.3-gd php8.3-mbstring php8.3-pdo php8.3-pdo-mysql php8.3-curl php8.3-zip
```

Después de instalar las extensiones, ejecuta nuevamente:
```bash
composer install
```

## Documentación Completa

Para más detalles, consulta `GUIA_INSTALACION.md`

