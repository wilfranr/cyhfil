-- Script completo para corregir AUTO_INCREMENT en campos ID
-- Este script resuelve el error: "SQLSTATE[HY000]: General error: 1364 Field 'id' doesn't have a default value"
-- Ejecutar en la base de datos MySQL/MariaDB

-- =====================================================
-- CORRECCIÓN DE TABLA TERCEROS (con manejo de restricciones)
-- =====================================================

-- 1. Eliminar temporalmente todas las restricciones de clave foránea
ALTER TABLE `categoria_tercero` DROP FOREIGN KEY `categoria_tercero_tercero_id_foreign`;
ALTER TABLE `contactos` DROP FOREIGN KEY `contactos_tercero_id_foreign`;
ALTER TABLE `direcciones` DROP FOREIGN KEY `direccions_tercero_id_foreign`;
ALTER TABLE `orden_compras` DROP FOREIGN KEY `orden_compras_tercero_id_foreign`;
ALTER TABLE `orden_trabajos` DROP FOREIGN KEY `orden_trabajos_tercero_id_foreign`;
ALTER TABLE `pedido_referencia_proveedor` DROP FOREIGN KEY `pedido_referencia_proveedor_tercero_id_foreign`;
ALTER TABLE `pedidos` DROP FOREIGN KEY `pedidos_tercero_id_foreign`;
ALTER TABLE `tercero_contacto` DROP FOREIGN KEY `tercero_contacto_tercero_id_foreign`;
ALTER TABLE `tercero_fabricantes` DROP FOREIGN KEY `terecero_marcas_tercero_id_foreign`;
ALTER TABLE `tercero_maquina` DROP FOREIGN KEY `tercero_maquina_tercero_id_foreign`;
ALTER TABLE `tercero_marcas` DROP FOREIGN KEY `tercero_marcas_tercero_id_foreign`;
ALTER TABLE `tercero_sistemas` DROP FOREIGN KEY `terecero_sistemas_tercero_id_foreign`;

-- 2. Corregir el campo id de terceros
ALTER TABLE `terceros` MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `terceros` ADD PRIMARY KEY (`id`);

-- 3. Restaurar todas las restricciones de clave foránea
ALTER TABLE `categoria_tercero` ADD CONSTRAINT `categoria_tercero_tercero_id_foreign` FOREIGN KEY (`tercero_id`) REFERENCES `terceros` (`id`) ON DELETE CASCADE;
ALTER TABLE `contactos` ADD CONSTRAINT `contactos_tercero_id_foreign` FOREIGN KEY (`tercero_id`) REFERENCES `terceros` (`id`) ON DELETE CASCADE;
ALTER TABLE `direcciones` ADD CONSTRAINT `direccions_tercero_id_foreign` FOREIGN KEY (`tercero_id`) REFERENCES `terceros` (`id`) ON DELETE CASCADE;
ALTER TABLE `orden_compras` ADD CONSTRAINT `orden_compras_tercero_id_foreign` FOREIGN KEY (`tercero_id`) REFERENCES `terceros` (`id`) ON DELETE CASCADE;
ALTER TABLE `orden_trabajos` ADD CONSTRAINT `orden_trabajos_tercero_id_foreign` FOREIGN KEY (`tercero_id`) REFERENCES `terceros` (`id`) ON DELETE CASCADE;
ALTER TABLE `pedido_referencia_proveedor` ADD CONSTRAINT `pedido_referencia_proveedor_tercero_id_foreign` FOREIGN KEY (`tercero_id`) REFERENCES `terceros` (`id`) ON DELETE CASCADE;
ALTER TABLE `pedidos` ADD CONSTRAINT `pedidos_tercero_id_foreign` FOREIGN KEY (`tercero_id`) REFERENCES `terceros` (`id`) ON DELETE CASCADE;
ALTER TABLE `tercero_contacto` ADD CONSTRAINT `tercero_contacto_tercero_id_foreign` FOREIGN KEY (`tercero_id`) REFERENCES `terceros` (`id`) ON DELETE CASCADE;
ALTER TABLE `tercero_fabricantes` ADD CONSTRAINT `terecero_marcas_tercero_id_foreign` FOREIGN KEY (`tercero_id`) REFERENCES `terceros` (`id`) ON DELETE CASCADE;
ALTER TABLE `tercero_maquina` ADD CONSTRAINT `tercero_maquina_tercero_id_foreign` FOREIGN KEY (`tercero_id`) REFERENCES `terceros` (`id`) ON DELETE CASCADE;
ALTER TABLE `tercero_marcas` ADD CONSTRAINT `tercero_marcas_tercero_id_foreign` FOREIGN KEY (`tercero_id`) REFERENCES `terceros` (`id`) ON DELETE CASCADE;
ALTER TABLE `tercero_sistemas` ADD CONSTRAINT `terecero_sistemas_tercero_id_foreign` FOREIGN KEY (`tercero_id`) REFERENCES `terceros` (`id`) ON DELETE CASCADE;

-- =====================================================
-- CORRECCIÓN DE OTRAS TABLAS RELACIONADAS
-- =====================================================

-- Corregir tabla tercero_contacto
ALTER TABLE `tercero_contacto` MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `tercero_contacto` ADD PRIMARY KEY (`id`);

-- Corregir tabla tercero_fabricantes
ALTER TABLE `tercero_fabricantes` MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `tercero_fabricantes` ADD PRIMARY KEY (`id`);

-- Corregir tabla tercero_maquina
ALTER TABLE `tercero_maquina` MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `tercero_maquina` ADD PRIMARY KEY (`id`);

-- Corregir tabla tercero_sistemas
ALTER TABLE `tercero_sistemas` MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `tercero_sistemas` ADD PRIMARY KEY (`id`);

-- Corregir tabla tercero_marcas
ALTER TABLE `tercero_marcas` MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `tercero_marcas` ADD PRIMARY KEY (`id`);

-- =====================================================
-- VERIFICACIÓN FINAL
-- =====================================================

-- Verificar que todas las correcciones se aplicaron correctamente
SELECT 
    TABLE_NAME,
    COLUMN_NAME,
    COLUMN_DEFAULT,
    IS_NULLABLE,
    EXTRA,
    CASE 
        WHEN EXTRA LIKE '%auto_increment%' THEN '✅ AUTO_INCREMENT'
        ELSE '❌ Sin AUTO_INCREMENT'
    END as Status
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = DATABASE() 
AND TABLE_NAME IN (
    'terceros', 
    'tercero_contacto', 
    'tercero_fabricantes', 
    'tercero_maquina', 
    'tercero_sistemas',
    'tercero_marcas'
)
AND COLUMN_NAME = 'id'
ORDER BY TABLE_NAME;

-- Verificar que las restricciones de clave foránea estén activas
SELECT 
    TABLE_NAME,
    CONSTRAINT_NAME,
    COLUMN_NAME,
    REFERENCED_TABLE_NAME,
    REFERENCED_COLUMN_NAME,
    '✅ Activa' as Status
FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
WHERE REFERENCED_TABLE_SCHEMA = DATABASE() 
AND REFERENCED_TABLE_NAME = 'terceros' 
AND REFERENCED_COLUMN_NAME = 'id'
ORDER BY TABLE_NAME;
