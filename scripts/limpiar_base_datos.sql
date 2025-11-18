-- Script para limpiar la base de datos CYH
-- Mantiene solo las tablas esenciales: Listas, users, permissions, roles, role_has_permissions, trms, empresa, fabricantes, sistemas, model_has_permissions, model_has_roles, states, countries, cities

-- Desactivar verificación de claves foráneas temporalmente
SET FOREIGN_KEY_CHECKS = 0;

-- Limpiar tablas de pedidos y referencias
TRUNCATE TABLE pedido_referencia_proveedor;
TRUNCATE TABLE pedido_referencia;
TRUNCATE TABLE pedido_articulos;
TRUNCATE TABLE pedidos;

-- Limpiar tablas de artículos
TRUNCATE TABLE articulos_referencias;
TRUNCATE TABLE articulos_juegos;
TRUNCATE TABLE articulos;

-- Limpiar tablas de terceros
TRUNCATE TABLE tercero_contacto;
TRUNCATE TABLE tercero_fabricantes;
TRUNCATE TABLE tercero_sistemas;
TRUNCATE TABLE tercero_maquina;
TRUNCATE TABLE terceros;

-- Limpiar tablas de fabricantes y marcas
TRUNCATE TABLE fabricantes;

-- Limpiar tablas de máquinas
TRUNCATE TABLE maquina_marca;
TRUNCATE TABLE maquinas;

-- Limpiar tablas de sistemas
TRUNCATE TABLE sistema_lista;
TRUNCATE TABLE sistemas;

-- Limpiar tablas de categorías
TRUNCATE TABLE categoria_tercero;
TRUNCATE TABLE categorias;

-- Limpiar tablas de medidas
TRUNCATE TABLE lista_medida_definicion;
TRUNCATE TABLE medidas;

-- Limpiar tablas de contactos
TRUNCATE TABLE contactos;

-- Limpiar tablas de direcciones
TRUNCATE TABLE direccions;

-- Limpiar tablas de transportadoras
TRUNCATE TABLE transportadoras;

-- Limpiar tablas de ordenes de trabajo
TRUNCATE TABLE orden_trabajo_referencias;
TRUNCATE TABLE orden_trabajos;

-- Limpiar tablas de ordenes de compra
TRUNCATE TABLE orden_compra_referencia;
TRUNCATE TABLE orden_compras;

-- Limpiar tablas de cotizaciones
TRUNCATE TABLE cotizaciones;

-- Limpiar tablas de juegos
TRUNCATE TABLE juegos;

-- Limpiar tablas de referencias
TRUNCATE TABLE referencias;

-- Limpiar tablas de importación/exportación
TRUNCATE TABLE failed_import_rows;
TRUNCATE TABLE exports;
TRUNCATE TABLE imports;

-- Limpiar tablas de chat
TRUNCATE TABLE chat_messages;

-- Limpiar tablas de notificaciones
TRUNCATE TABLE notifications;

-- Limpiar tablas de jobs fallidos
TRUNCATE TABLE failed_jobs;

-- Limpiar tablas de tokens
TRUNCATE TABLE password_reset_tokens;
TRUNCATE TABLE personal_access_tokens;

-- Reactivar verificación de claves foráneas
SET FOREIGN_KEY_CHECKS = 1;

-- Mostrar mensaje de confirmación
SELECT 'Base de datos limpiada exitosamente. Se mantuvieron las tablas esenciales.' AS mensaje;
