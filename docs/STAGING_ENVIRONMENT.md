# Entorno de Staging - CYH Filament

## 📋 Resumen

Este documento describe la configuración y gestión del entorno de staging para la aplicación CYH Filament, desplegado en AWS EC2.

## 🌐 Información de Acceso

### **Aplicación Web**
- **URL**: `http://18.191.122.71:8001`
- **Usuario**: `staging@cyh.local`
- **Contraseña**: `password`

### **Base de Datos**
- **Host**: `database-2.cz2ae00eoyw1.us-east-2.rds.amazonaws.com`
- **Puerto**: `3306`
- **Base de datos**: `cyhfilament_staging`
- **Usuario**: `admin`
- **Contraseña**: `Th3L4stofUs`

## 🖥️ Servidor

### **Información del Servidor**
- **IP**: `18.191.122.71`
- **Sistema**: Ubuntu (AWS EC2)
- **Directorio**: `/home/ubuntu/cyhfil_staging`
- **Servicio**: Supervisor (cyh-staging)

### **Configuración de Red**
- **Puerto 8001**: Abierto para acceso web
- **Puerto 3306**: Abierto para conexión a RDS
- **Security Groups**: Configurados correctamente

## 🔧 Gestión del Servicio

### **Comandos de Supervisor**

```bash
# Ver estado del servicio
sudo supervisorctl status cyh-staging

# Iniciar servicio
sudo supervisorctl start cyh-staging

# Detener servicio
sudo supervisorctl stop cyh-staging

# Reiniciar servicio
sudo supervisorctl restart cyh-staging

# Ver logs en tiempo real
sudo supervisorctl tail -f cyh-staging

# Ver estado de todos los servicios
sudo supervisorctl status
```

### **Configuración del Servicio**

El servicio está configurado en `/etc/supervisor/conf.d/cyh-staging.conf`:

```ini
[program:cyh-staging]
command=php /home/ubuntu/cyhfil_staging/artisan serve --host=0.0.0.0 --port=8001
directory=/home/ubuntu/cyhfil_staging
user=ubuntu
autostart=true
autorestart=true
redirect_stderr=true
stdout_logfile=/home/ubuntu/cyhfil_staging/storage/logs/supervisor.log
stdout_logfile_maxbytes=10MB
stdout_logfile_backups=5
```

## 📊 Estado Actual

### **Migraciones Aplicadas**
- ✅ `2025_08_23_000001_consolidate_core_system_tables.php`
- ✅ `2025_08_23_000002_consolidate_business_module_tables.php`
- ✅ `2025_08_23_000003_consolidate_operations_module_tables.php`

### **Datos Importados**
- ✅ **Piezas Estándar**: Importadas desde `piezas_estandar.xlsx`
- ✅ **Usuarios**: Usuario de prueba configurado
- ✅ **Listas**: Sistema de listas funcionando

### **Funcionalidades Verificadas**
- ✅ **Login y autenticación**
- ✅ **Base de datos conectada**
- ✅ **Migraciones consolidadas**
- ✅ **Importación de archivos Excel**
- ✅ **Servicio funcionando 24/7**

## 🚀 Comandos Útiles

### **Verificar Estado de la Aplicación**

```bash
# Verificar conexión a la base de datos
php artisan tinker --execute="echo 'Conexión DB: ' . (DB::connection()->getPdo() ? 'OK' : 'ERROR');"

# Verificar migraciones
php artisan migrate:status

# Verificar total de listas
php artisan tinker --execute="echo 'Total de listas: ' . App\Models\Lista::count();"

# Verificar piezas estándar
php artisan tinker --execute="echo 'Piezas estándar: ' . App\Models\Lista::where('tipo', 'PIEZA ESTANDAR')->count();"
```

### **Gestión de Archivos**

```bash
# Verificar archivos de importación
ls -la storage/app/imports/

# Ver logs de la aplicación
tail -f storage/logs/laravel.log

# Ver logs de Supervisor
tail -f storage/logs/supervisor.log
```

### **Sincronización de Código**

```bash
# Hacer pull de cambios desde el repositorio
git pull origin feature/migration-consolidation

# Limpiar cachés después de actualizaciones
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Regenerar cachés
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 📋 Checklist de Verificación

### **Pre-Pruebas**
- [ ] Servicio corriendo (`sudo supervisorctl status cyh-staging`)
- [ ] Aplicación accesible (`curl -I http://localhost:8001`)
- [ ] Base de datos conectada
- [ ] Usuario de prueba disponible

### **Post-Pruebas**
- [ ] Funcionalidades críticas verificadas
- [ ] Sin errores en logs
- [ ] Rendimiento aceptable
- [ ] Datos de prueba disponibles

## 🔄 Proceso de Actualización

### **Actualizar Código**
1. Hacer pull desde el repositorio
2. Limpiar cachés
3. Regenerar cachés
4. Reiniciar servicio si es necesario

### **Actualizar Base de Datos**
1. Hacer backup de la base de datos
2. Aplicar migraciones
3. Verificar integridad
4. Probar funcionalidades

## 🛡️ Seguridad

### **Credenciales**
- **Cambiar contraseñas** antes de usar en producción
- **Anonymizar datos** sensibles en staging
- **Limitar acceso** a la base de datos

### **Monitoreo**
- **Verificar logs** regularmente
- **Monitorear recursos** del servidor
- **Revisar accesos** no autorizados

## 📞 Contacto y Soporte

### **En caso de problemas**
1. Verificar estado del servicio
2. Revisar logs de aplicación
3. Verificar conectividad de red
4. Contactar al equipo de desarrollo

### **Comandos de emergencia**
```bash
# Reiniciar todo el servicio
sudo supervisorctl restart cyh-staging

# Ver logs de errores
sudo supervisorctl tail -f cyh-staging

# Verificar recursos del sistema
htop
df -h
```

---

**Fecha de creación**: 1 de Septiembre, 2025  
**Responsable**: Equipo de Desarrollo CYH  
**Estado**: ✅ Activo y funcionando  
**Última actualización**: 1 de Septiembre, 2025
