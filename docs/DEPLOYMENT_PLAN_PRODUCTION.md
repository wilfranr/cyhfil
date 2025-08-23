# Plan de Despliegue para Producción - Post-Consolidación

## 📋 Resumen Ejecutivo

Este documento describe el plan de despliegue para producción después de la consolidación exitosa de migraciones en el proyecto CYH.

## 🎯 Objetivos del Despliegue

- ✅ **Desplegar sistema consolidado** en producción
- ✅ **Mantener integridad** de la base de datos
- ✅ **Minimizar tiempo de inactividad** del sistema
- ✅ **Asegurar rollback** en caso de problemas
- ✅ **Documentar proceso** para futuros despliegues

## 🚀 Fases del Despliegue

### **Fase 1: Preparación (1-2 días antes)**

#### **1.1 Verificación de Staging**
```bash
# Ejecutar en entorno de staging
./scripts/verificar_integridad_post_consolidacion.sh

# Verificar funcionalidades críticas
- Login y autenticación
- Gestión de usuarios y permisos
- Operaciones de negocio (pedidos, cotizaciones)
- Reportes y consultas
- Integraciones externas
```

#### **1.2 Preparación de Base de Datos de Producción**
```bash
# Crear backup completo de producción
mysqldump -h[PROD_HOST] -P[PROD_PORT] -u[PROD_USER] -p[PROD_DB] > backup_production_pre_consolidation.sql

# Verificar espacio disponible
df -h /path/to/database

# Verificar permisos de usuario de aplicación
mysql -h[PROD_HOST] -u[PROD_USER] -p -e "SHOW GRANTS;"
```

#### **1.3 Preparación de Código**
```bash
# Crear tag de release
git tag -a v1.0.0-consolidated -m "Release consolidado para producción"
git push origin v1.0.0-consolidated

# Verificar que no hay cambios pendientes
git status
git log --oneline -5
```

### **Fase 2: Despliegue (Día del despliegue)**

#### **2.1 Ventana de Mantenimiento**
- **Duración estimada:** 2-4 horas
- **Horario recomendado:** Fines de semana o madrugada
- **Notificación:** 48 horas antes a usuarios

#### **2.2 Secuencia de Despliegue**
```bash
# 1. Detener aplicación
sudo systemctl stop cyh-app
# o
sudo supervisorctl stop cyh-app

# 2. Crear backup final
mysqldump -h[PROD_HOST] -u[PROD_USER] -p[PROD_DB] > backup_production_final.sql

# 3. Desplegar código
git checkout v1.0.0-consolidated
composer install --no-dev --optimize-autoloader
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 4. Ejecutar migraciones consolidadas
php artisan migrate --force

# 5. Verificar estado
php artisan migrate:status
php artisan tinker --execute="echo 'Verificando tablas críticas...'; Schema::hasTable('users'); Schema::hasTable('permissions');"

# 6. Reiniciar aplicación
sudo systemctl start cyh-app
# o
sudo supervisorctl start cyh-app
```

#### **2.3 Verificación Post-Despliegue**
```bash
# Ejecutar script de verificación
./scripts/verificar_integridad_post_consolidacion.sh

# Verificar logs de aplicación
tail -f storage/logs/laravel.log

# Verificar métricas del sistema
- CPU y memoria
- Conexiones a base de datos
- Tiempo de respuesta de la aplicación
```

### **Fase 3: Monitoreo (Primeras 24-48 horas)**

#### **3.1 Métricas a Monitorear**
- **Rendimiento:** Tiempo de respuesta, throughput
- **Errores:** Logs de errores, excepciones
- **Base de datos:** Conexiones activas, consultas lentas
- **Sistema:** CPU, memoria, disco, red

#### **3.2 Alertas Configurar**
- Errores 5xx en logs
- Tiempo de respuesta > 2 segundos
- Uso de CPU > 80%
- Uso de memoria > 85%
- Errores de base de datos

## 🔄 Plan de Rollback

### **Escenario 1: Problemas con Migraciones**
```bash
# 1. Detener aplicación
sudo systemctl stop cyh-app

# 2. Restaurar base de datos
mysql -h[PROD_HOST] -u[PROD_USER] -p[PROD_DB] < backup_production_pre_consolidation.sql

# 3. Revertir código
git checkout main
composer install --no-dev --optimize-autoloader

# 4. Reiniciar aplicación
sudo systemctl start cyh-app
```

### **Escenario 2: Problemas de Rendimiento**
```bash
# 1. Revertir a versión anterior
git checkout v1.0.0-pre-consolidation

# 2. Limpiar cachés
php artisan cache:clear
php artisan config:clear

# 3. Reiniciar aplicación
sudo systemctl restart cyh-app
```

### **Escenario 3: Problemas de Base de Datos**
```bash
# 1. Verificar logs de MySQL
sudo tail -f /var/log/mysql/error.log

# 2. Verificar estado de servicios
sudo systemctl status mysql
sudo systemctl status redis

# 3. Restaurar desde backup si es necesario
mysql -h[PROD_HOST] -u[PROD_USER] -p[PROD_DB] < backup_production_final.sql
```

## 📊 Checklist de Despliegue

### **Pre-Despliegue**
- [ ] Verificación en staging completada
- [ ] Backup de producción creado
- [ ] Código etiquetado y verificado
- [ ] Equipo notificado
- [ ] Ventana de mantenimiento confirmada

### **Durante el Despliegue**
- [ ] Aplicación detenida
- [ ] Backup final creado
- [ ] Código desplegado
- [ ] Migraciones ejecutadas
- [ ] Aplicación reiniciada
- [ ] Verificaciones básicas completadas

### **Post-Despliegue**
- [ ] Script de verificación ejecutado
- [ ] Funcionalidades críticas probadas
- [ ] Monitoreo activado
- [ ] Equipo notificado del éxito
- [ ] Documentación actualizada

## 🛡️ Consideraciones de Seguridad

### **Base de Datos**
- Usar conexiones SSL/TLS
- Verificar permisos mínimos necesarios
- Monitorear intentos de acceso no autorizados

### **Aplicación**
- Verificar variables de entorno
- Validar configuración de caché
- Revisar logs de seguridad

### **Infraestructura**
- Verificar firewalls y reglas de red
- Monitorear acceso SSH
- Validar backups automáticos

## 📈 Métricas de Éxito

### **Técnicas**
- ✅ Migraciones ejecutadas sin errores
- ✅ Aplicación responde correctamente
- ✅ Base de datos funcionando
- ✅ Logs sin errores críticos

### **Funcionales**
- ✅ Usuarios pueden autenticarse
- ✅ Funcionalidades críticas operativas
- ✅ Reportes generándose correctamente
- ✅ Integraciones funcionando

### **Operacionales**
- ✅ Tiempo de inactividad < 4 horas
- ✅ Sin pérdida de datos
- ✅ Rollback disponible si es necesario
- ✅ Documentación actualizada

## 🚨 Contactos de Emergencia

### **Equipo de Desarrollo**
- **Líder técnico:** [Nombre] - [Teléfono]
- **DBA:** [Nombre] - [Teléfono]
- **DevOps:** [Nombre] - [Teléfono]

### **Equipo de Operaciones**
- **On-call:** [Nombre] - [Teléfono]
- **Manager:** [Nombre] - [Teléfono]

## 📝 Notas Adicionales

### **Dependencias**
- Servicios de base de datos estables
- Espacio suficiente en disco
- Permisos de usuario correctos
- Configuración de red adecuada

### **Riesgos Identificados**
- **Bajo:** Problemas de migración (backup disponible)
- **Medio:** Problemas de rendimiento (rollback disponible)
- **Alto:** Problemas de infraestructura (plan de contingencia)

### **Lecciones Aprendidas**
- Documentar cualquier problema encontrado
- Actualizar procedimientos según sea necesario
- Compartir conocimiento con el equipo

---

**Fecha de creación:** 23 de Agosto, 2025  
**Responsable:** Equipo de Desarrollo CYH  
**Estado:** 📋 Planificado  
**Próxima revisión:** Antes del despliegue en producción
