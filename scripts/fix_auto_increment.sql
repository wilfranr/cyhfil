-- Script para corregir AUTO_INCREMENT en campos ID
-- Ejecutar este script en la base de datos para resolver el error:
-- "SQLSTATE[HY000]: General error: 1364 Field 'id' doesn't have a default value"

-- Corregir tabla terceros
ALTER TABLE `terceros` MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `terceros` ADD PRIMARY KEY (`id`);

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

-- Corregir tabla tercero_marcas (si existe)
-- ALTER TABLE `tercero_marcas` MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT;
-- ALTER TABLE `tercero_marcas` ADD PRIMARY KEY (`id`);

-- Verificar que las correcciones se aplicaron correctamente
SELECT 
    TABLE_NAME,
    COLUMN_NAME,
    COLUMN_DEFAULT,
    IS_NULLABLE,
    EXTRA
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = DATABASE() 
AND TABLE_NAME IN ('terceros', 'tercero_contacto', 'tercero_fabricantes', 'tercero_maquina', 'tercero_sistemas')
AND COLUMN_NAME = 'id'
ORDER BY TABLE_NAME;
