# Scripts de Limpieza de Base de Datos

Este directorio contiene scripts para limpiar la base de datos CYH, manteniendo solo las tablas esenciales con su estructura.

## 🚨 ADVERTENCIA IMPORTANTE

**ESTA OPERACIÓN ES IRREVERSIBLE**. Todos los datos serán eliminados permanentemente. Solo se mantendrá la estructura de las tablas.

## 📋 Tablas que se Mantienen

Las siguientes tablas se mantendrán con su estructura (pero sin datos):

- **Sistema de Usuarios y Permisos:**
  - `users` - Usuarios del sistema
  - `permissions` - Permisos disponibles
  - `roles` - Roles del sistema
  - `role_has_permissions` - Relación roles-permisos
  - `model_has_permissions` - Permisos asignados a modelos
  - `model_has_roles` - Roles asignados a modelos

- **Configuración del Sistema:**
  - `listas` - Listas del sistema
  - `trms` - Tasas de cambio
  - `empresas` - Empresas configuradas
  - `fabricantes` - Fabricantes del sistema
  - `sistemas` - Sistemas disponibles

- **Ubicaciones Geográficas:**
  - `countries` - Países
  - `states` - Estados/Provincias
  - `cities` - Ciudades

## 🛠️ Opciones de Limpieza

### 1. Comando Artisan (Recomendado)

```bash
# Ejecutar con confirmaciones interactivas
php artisan db:limpiar

# Ejecutar sin confirmaciones (útil para scripts automatizados)
php artisan db:limpiar --confirm
```

### 2. Script de Shell

```bash
# Ejecutar el script interactivo
./scripts/limpiar_db.sh
```

### 3. Script SQL Directo

```bash
# Ejecutar directamente en MySQL
mysql -u[usuario] -p[password] [base_datos] < scripts/limpiar_base_datos.sql
```

## 📊 Qué se Elimina

El script eliminará TODOS los datos de las siguientes categorías:

- **Pedidos y Referencias:** pedidos, referencias, pedido_referencia, etc.
- **Artículos:** articulos, articulos_referencias, articulos_juegos
- **Terceros:** terceros, contactos, direcciones
- **Fabricantes y Marcas:** fabricantes, marcas
- **Máquinas:** maquinas, maquina_marca
- **Sistemas:** sistemas, sistema_lista
- **Categorías:** categorias, categoria_tercero
- **Medidas:** medidas, lista_medida_definicion
- **Órdenes:** orden_trabajos, orden_compras, cotizaciones
- **Chat y Notificaciones:** chat_messages, notifications
- **Importación/Exportación:** imports, exports, failed_import_rows

## 🔒 Seguridad

- El script desactiva temporalmente la verificación de claves foráneas
- Se reactiva la verificación al finalizar
- Incluye múltiples confirmaciones para evitar ejecuciones accidentales
- Manejo de errores robusto

## 📝 Después de la Limpieza

1. **Verificar la estructura:** Las tablas deben existir pero vacías
2. **Datos de prueba:** Si necesitas datos de ejemplo, ejecuta:
   ```bash
   php artisan db:seed
   ```
3. **Verificar permisos:** Asegúrate de que los usuarios tengan los permisos correctos

## 🚀 Desarrollo

### Agregar Nuevas Tablas a Mantener

Edita el archivo `app/Console/Commands/LimpiarBaseDatos.php` y agrega la tabla al array `$tablasAMantener`.

### Modificar el Script SQL

Edita `scripts/limpiar_base_datos.sql` para agregar o remover tablas según sea necesario.

## ⚠️ Respaldo

**SIEMPRE** haz un respaldo antes de ejecutar la limpieza:

```bash
# Respaldo completo de la base de datos
mysqldump -u[usuario] -p[password] [base_datos] > backup_$(date +%Y%m%d_%H%M%S).sql

# O usar el comando de Laravel
php artisan db:backup
```

## 🆘 Solución de Problemas

### Error de Permisos
```bash
chmod +x scripts/limpiar_db.sh
```

### Error de Conexión a BD
Verifica tu archivo `.env` y la configuración de la base de datos.

### Error de Claves Foráneas
El script maneja esto automáticamente, pero si hay problemas, ejecuta:
```sql
SET FOREIGN_KEY_CHECKS = 1;
```

## 📞 Soporte

Si encuentras problemas:
1. Revisa los logs de Laravel en `storage/logs/`
2. Verifica la conectividad a la base de datos
3. Asegúrate de tener permisos suficientes en la base de datos
