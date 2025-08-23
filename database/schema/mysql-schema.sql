/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `articulo_juegos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `articulo_juegos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `articulo_id` bigint(20) unsigned DEFAULT NULL,
  `referencia_id` bigint(20) unsigned DEFAULT NULL,
  `cantidad` bigint(20) unsigned DEFAULT NULL,
  `comentario` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `articulo_juegos_articulo_id_foreign` (`articulo_id`),
  KEY `articulo_juegos_referencia_id_foreign` (`referencia_id`),
  CONSTRAINT `articulo_juegos_articulo_id_foreign` FOREIGN KEY (`articulo_id`) REFERENCES `articulos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `articulo_juegos_referencia_id_foreign` FOREIGN KEY (`referencia_id`) REFERENCES `referencias` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `articulos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `articulos` (
  `id` bigint(20) unsigned NOT NULL,
  `definicion` text DEFAULT NULL,
  `foto_medida` varchar(255) DEFAULT NULL,
  `comentarios` text DEFAULT NULL,
  `descripcionEspecifica` text DEFAULT NULL,
  `peso` double DEFAULT NULL,
  `fotoDescriptiva` varchar(200) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `articulos_referencias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `articulos_referencias` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `articulo_id` bigint(20) unsigned DEFAULT NULL,
  `referencia_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `articulos_referencias_articulo_id_referencia_id_unique` (`articulo_id`,`referencia_id`),
  KEY `articulos_referencias_referencia_id_foreign` (`referencia_id`),
  CONSTRAINT `articulos_referencias_articulo_id_foreign` FOREIGN KEY (`articulo_id`) REFERENCES `articulos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `articulos_referencias_referencia_id_foreign` FOREIGN KEY (`referencia_id`) REFERENCES `referencias` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `categoria_tercero`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categoria_tercero` (
  `categoria_id` bigint(20) unsigned NOT NULL,
  `tercero_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `categoria_tercero_categoria_id_foreign` (`categoria_id`),
  KEY `categoria_tercero_tercero_id_foreign` (`tercero_id`),
  CONSTRAINT `categoria_tercero_categoria_id_foreign` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON DELETE CASCADE,
  CONSTRAINT `categoria_tercero_tercero_id_foreign` FOREIGN KEY (`tercero_id`) REFERENCES `terceros` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `categorias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categorias` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `chat_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `chat_messages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `sender` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `type` enum('event','message') NOT NULL DEFAULT 'message',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `chat_messages_user_id_foreign` (`user_id`),
  CONSTRAINT `chat_messages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cities` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `country_id` bigint(20) unsigned NOT NULL,
  `state_id` bigint(20) unsigned NOT NULL,
  `name` varchar(100) NOT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cities_country_id_index` (`country_id`),
  KEY `cities_state_id_index` (`state_id`),
  KEY `cities_is_active_index` (`is_active`),
  CONSTRAINT `cities_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `cities_state_id_foreign` FOREIGN KEY (`state_id`) REFERENCES `states` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=149237 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `contactos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contactos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `telefono` varchar(255) NOT NULL,
  `indicativo` varchar(10) DEFAULT NULL,
  `country_id` bigint(20) unsigned DEFAULT NULL,
  `cargo` varchar(255) DEFAULT NULL,
  `principal` tinyint(1) NOT NULL DEFAULT 0,
  `tercero_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `contactos_tercero_id_foreign` (`tercero_id`),
  KEY `contactos_country_id_foreign` (`country_id`),
  CONSTRAINT `contactos_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE SET NULL,
  CONSTRAINT `contactos_tercero_id_foreign` FOREIGN KEY (`tercero_id`) REFERENCES `terceros` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cotizacion_referencia_proveedores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cotizacion_referencia_proveedores` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `cotizacion_id` bigint(20) unsigned NOT NULL,
  `pedido_referencia_proveedor_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cotizacion_referencia_proveedores_cotizacion_id_foreign` (`cotizacion_id`),
  KEY `pedido_referencia_proveedor_id` (`pedido_referencia_proveedor_id`),
  CONSTRAINT `cotizacion_referencia_proveedores_cotizacion_id_foreign` FOREIGN KEY (`cotizacion_id`) REFERENCES `cotizaciones` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `cotizacion_referencia_proveedores_ibfk_1` FOREIGN KEY (`pedido_referencia_proveedor_id`) REFERENCES `pedido_referencia_proveedor` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cotizaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cotizaciones` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tercero_id` bigint(20) unsigned NOT NULL,
  `pedido_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `referencia_proveedor` bigint(20) unsigned DEFAULT NULL,
  `estado` enum('Enviada','Aprobada','Rechazada','') NOT NULL DEFAULT 'Enviada',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `countries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `countries` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `iso2` varchar(2) NOT NULL,
  `iso3` varchar(3) NOT NULL,
  `numeric_code` varchar(3) DEFAULT NULL,
  `phonecode` varchar(255) DEFAULT NULL,
  `capital` varchar(255) DEFAULT NULL,
  `currency` varchar(255) DEFAULT NULL,
  `currency_name` varchar(255) DEFAULT NULL,
  `currency_symbol` varchar(255) DEFAULT NULL,
  `tld` varchar(255) DEFAULT NULL,
  `native` varchar(255) DEFAULT NULL,
  `region` varchar(255) DEFAULT NULL,
  `subregion` varchar(255) DEFAULT NULL,
  `timezones` text DEFAULT NULL,
  `translations` text DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `emoji` varchar(191) DEFAULT NULL,
  `emojiU` varchar(191) DEFAULT NULL,
  `flag` tinyint(1) NOT NULL DEFAULT 1,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `countries_iso2_unique` (`iso2`),
  UNIQUE KEY `countries_iso3_unique` (`iso3`),
  KEY `countries_is_active_index` (`is_active`)
) ENGINE=InnoDB AUTO_INCREMENT=251 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `direcciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `direcciones` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `direccion` varchar(255) NOT NULL,
  `city_id` varchar(255) NOT NULL,
  `ciudad_texto` varchar(255) DEFAULT NULL,
  `state_id` varchar(255) NOT NULL,
  `country_id` varchar(255) NOT NULL,
  `principal` tinyint(1) NOT NULL DEFAULT 0,
  `tercero_id` bigint(20) unsigned NOT NULL,
  `destinatario` varchar(255) NOT NULL,
  `nit_cc` varchar(255) NOT NULL,
  `transportadora_id` bigint(20) unsigned DEFAULT NULL,
  `forma_pago` varchar(255) NOT NULL,
  `telefono` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `direccions_tercero_id_foreign` (`tercero_id`),
  KEY `direcciones_transportadora_id_foreign` (`transportadora_id`),
  CONSTRAINT `direcciones_transportadora_id_foreign` FOREIGN KEY (`transportadora_id`) REFERENCES `transportadoras` (`id`) ON DELETE SET NULL,
  CONSTRAINT `direccions_tercero_id_foreign` FOREIGN KEY (`tercero_id`) REFERENCES `terceros` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `empresas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `empresas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(300) NOT NULL,
  `flete` decimal(10,1) DEFAULT NULL,
  `siglas` varchar(10) DEFAULT NULL,
  `direccion` varchar(255) NOT NULL,
  `telefono` varchar(255) DEFAULT NULL,
  `celular` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `logo_light` varchar(255) DEFAULT NULL,
  `logo_dark` varchar(255) DEFAULT NULL,
  `nit` varchar(255) NOT NULL,
  `representante` varchar(255) NOT NULL,
  `country_id` bigint(20) unsigned NOT NULL,
  `city_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `state_id` bigint(20) unsigned DEFAULT NULL,
  `estado` tinyint(1) NOT NULL,
  `trm` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `empresas_city_id_foreign` (`city_id`),
  KEY `empresas_country_id_foreign` (`country_id`),
  KEY `empresas_state_id_foreign` (`state_id`),
  CONSTRAINT `empresas_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`),
  CONSTRAINT `empresas_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`),
  CONSTRAINT `empresas_state_id_foreign` FOREIGN KEY (`state_id`) REFERENCES `states` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `exports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `exports` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `completed_at` timestamp NULL DEFAULT NULL,
  `file_disk` varchar(255) NOT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `exporter` varchar(255) NOT NULL,
  `processed_rows` int(10) unsigned NOT NULL DEFAULT 0,
  `total_rows` int(10) unsigned NOT NULL,
  `successful_rows` int(10) unsigned NOT NULL DEFAULT 0,
  `user_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `exports_user_id_foreign` (`user_id`),
  CONSTRAINT `exports_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `fabricantes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fabricantes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `descripcion` varchar(500) DEFAULT NULL,
  `logo` varchar(300) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `failed_import_rows`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_import_rows` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`data`)),
  `import_id` bigint(20) unsigned NOT NULL,
  `validation_error` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `failed_import_rows_import_id_foreign` (`import_id`),
  CONSTRAINT `failed_import_rows_import_id_foreign` FOREIGN KEY (`import_id`) REFERENCES `imports` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `imports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `imports` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `completed_at` timestamp NULL DEFAULT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `importer` varchar(255) NOT NULL,
  `processed_rows` int(10) unsigned NOT NULL DEFAULT 0,
  `total_rows` int(10) unsigned NOT NULL,
  `successful_rows` int(10) unsigned NOT NULL DEFAULT 0,
  `user_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `imports_user_id_foreign` (`user_id`),
  CONSTRAINT `imports_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `juegos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `juegos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `lista_medida_definicion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lista_medida_definicion` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `definicion_id` bigint(20) unsigned NOT NULL,
  `medida_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `lista_medida_definicion_definicion_id_medida_id_unique` (`definicion_id`,`medida_id`),
  KEY `lista_medida_definicion_medida_id_foreign` (`medida_id`),
  CONSTRAINT `lista_medida_definicion_definicion_id_foreign` FOREIGN KEY (`definicion_id`) REFERENCES `listas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `lista_medida_definicion_medida_id_foreign` FOREIGN KEY (`medida_id`) REFERENCES `listas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `listas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `listas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `sistema_id` bigint(20) unsigned DEFAULT NULL,
  `tipo` varchar(255) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `definicion` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `fotoMedida` varchar(300) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `listas_sistema_id_foreign` (`sistema_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2043 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `maquinas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `maquinas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tipo` varchar(255) DEFAULT NULL,
  `modelo` varchar(255) NOT NULL,
  `serie` varchar(255) DEFAULT NULL,
  `arreglo` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `fotoId` varchar(300) DEFAULT NULL,
  `fabricante_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `maquinas_serie_unique` (`serie`),
  KEY `maquina_marca_fk` (`fabricante_id`),
  CONSTRAINT `maquina_marca_fk` FOREIGN KEY (`fabricante_id`) REFERENCES `fabricantes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `medidas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `medidas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) DEFAULT NULL,
  `unidad` varchar(255) NOT NULL,
  `valor` varchar(255) NOT NULL,
  `tipo` varchar(255) NOT NULL,
  `identificador` varchar(255) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `articulo_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `medidas_articulo_id_foreign` (`articulo_id`),
  CONSTRAINT `medidas_articulo_id_foreign` FOREIGN KEY (`articulo_id`) REFERENCES `articulos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=93 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) unsigned NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `orden_compra_referencia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orden_compra_referencia` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `orden_compra_id` bigint(20) unsigned NOT NULL,
  `referencia_id` bigint(20) unsigned NOT NULL,
  `cantidad` int(11) NOT NULL,
  `valor_unitario` decimal(10,2) NOT NULL,
  `valor_total` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `orden_compra_referencia_referencia_id_foreign` (`referencia_id`),
  KEY `orden_compra_referencia_orden_compra_id_referencia_id_index` (`orden_compra_id`,`referencia_id`),
  CONSTRAINT `orden_compra_referencia_orden_compra_id_foreign` FOREIGN KEY (`orden_compra_id`) REFERENCES `orden_compras` (`id`) ON DELETE CASCADE,
  CONSTRAINT `orden_compra_referencia_referencia_id_foreign` FOREIGN KEY (`referencia_id`) REFERENCES `referencias` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `orden_compras`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orden_compras` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tercero_id` bigint(20) unsigned NOT NULL,
  `pedido_id` bigint(20) unsigned NOT NULL,
  `cotizaciones_id` bigint(20) unsigned DEFAULT NULL,
  `proveedor_id` bigint(20) unsigned DEFAULT NULL,
  `estado` varchar(255) DEFAULT NULL,
  `fecha_expedicion` date NOT NULL,
  `fecha_entrega` date NOT NULL,
  `observaciones` text DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `direccion` varchar(255) NOT NULL,
  `telefono` varchar(255) NOT NULL,
  `valor_unitario` decimal(10,2) DEFAULT NULL,
  `valor_total` decimal(10,2) DEFAULT NULL,
  `valor_iva` decimal(10,2) DEFAULT NULL,
  `valor_descuento` decimal(10,2) DEFAULT NULL,
  `guia` varchar(255) DEFAULT NULL,
  `color` varchar(255) NOT NULL DEFAULT '#FFFF00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `orden_compras_tercero_id_foreign` (`tercero_id`),
  KEY `orden_compras_pedido_id_foreign` (`pedido_id`),
  KEY `orden_compras_cotizaciones_id_foreign` (`cotizaciones_id`),
  CONSTRAINT `orden_compras_cotizaciones_id_foreign` FOREIGN KEY (`cotizaciones_id`) REFERENCES `cotizaciones` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `orden_compras_pedido_id_foreign` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `orden_compras_tercero_id_foreign` FOREIGN KEY (`tercero_id`) REFERENCES `terceros` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `orden_trabajo_referencias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orden_trabajo_referencias` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `orden_trabajo_id` bigint(20) unsigned NOT NULL,
  `pedido_referencia_id` bigint(20) unsigned NOT NULL,
  `cantidad` int(10) unsigned NOT NULL DEFAULT 1,
  `cantidad_recibida` int(10) unsigned DEFAULT NULL,
  `estado` varchar(255) NOT NULL DEFAULT '#FF0000',
  `recibido` tinyint(1) NOT NULL DEFAULT 0,
  `fecha_recepcion` date DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `orden_trabajo_referencias_orden_trabajo_id_foreign` (`orden_trabajo_id`),
  KEY `orden_trabajo_referencias_pedido_referencia_id_foreign` (`pedido_referencia_id`),
  CONSTRAINT `orden_trabajo_referencias_orden_trabajo_id_foreign` FOREIGN KEY (`orden_trabajo_id`) REFERENCES `orden_trabajos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `orden_trabajo_referencias_pedido_referencia_id_foreign` FOREIGN KEY (`pedido_referencia_id`) REFERENCES `pedido_referencia` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `orden_trabajos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orden_trabajos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tercero_id` bigint(20) unsigned NOT NULL,
  `pedido_id` bigint(20) unsigned NOT NULL,
  `cotizacion_id` bigint(20) unsigned DEFAULT NULL,
  `estado` enum('Pendiente','En Proceso','Completado','Cancelado') DEFAULT 'Pendiente',
  `motivo_cancelacion` text DEFAULT NULL,
  `fecha_ingreso` date NOT NULL,
  `fecha_entrega` date DEFAULT NULL,
  `direccion_id` bigint(20) unsigned DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `telefono` varchar(255) NOT NULL,
  `guia` varchar(255) DEFAULT NULL,
  `transportadora_id` bigint(20) unsigned DEFAULT NULL,
  `archivo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `orden_trabajos_tercero_id_foreign` (`tercero_id`),
  KEY `orden_trabajos_pedido_id_foreign` (`pedido_id`),
  KEY `orden_trabajos_cotizacion_id_foreign` (`cotizacion_id`),
  KEY `orden_trabajos_transportadora_id_foreign` (`transportadora_id`),
  KEY `orden_trabajos_direccion_id_foreign` (`direccion_id`),
  CONSTRAINT `orden_trabajos_cotizacion_id_foreign` FOREIGN KEY (`cotizacion_id`) REFERENCES `cotizaciones` (`id`) ON DELETE SET NULL,
  CONSTRAINT `orden_trabajos_direccion_id_foreign` FOREIGN KEY (`direccion_id`) REFERENCES `direcciones` (`id`),
  CONSTRAINT `orden_trabajos_pedido_id_foreign` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `orden_trabajos_tercero_id_foreign` FOREIGN KEY (`tercero_id`) REFERENCES `terceros` (`id`) ON DELETE CASCADE,
  CONSTRAINT `orden_trabajos_transportadora_id_foreign` FOREIGN KEY (`transportadora_id`) REFERENCES `transportadoras` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `pedido_articulos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pedido_articulos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pedido_id` bigint(20) unsigned NOT NULL,
  `articulo_id` bigint(20) unsigned NOT NULL,
  `cantidad` bigint(20) unsigned NOT NULL,
  `comentario` varchar(255) DEFAULT NULL,
  `sistema_id` bigint(20) unsigned DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pedido_articulos_pedido_id_foreign` (`pedido_id`),
  KEY `pedido_articulos_articulo_id_foreign` (`articulo_id`),
  KEY `pedido_sistemas_sistema_id_foreign` (`sistema_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `pedido_referencia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pedido_referencia` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `cantidad` int(11) NOT NULL,
  `pedido_id` bigint(20) unsigned NOT NULL,
  `referencia_id` bigint(20) unsigned DEFAULT NULL,
  `sistema_id` bigint(20) DEFAULT NULL,
  `marca_id` bigint(20) DEFAULT NULL,
  `definicion` varchar(255) DEFAULT NULL,
  `comentario` varchar(255) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `mostrar_referencia` tinyint(1) DEFAULT 1,
  `estado` int(11) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pedido_referencia_referencia_id_foreign` (`referencia_id`),
  KEY `pedido_referencia_pedido_id_foreign` (`pedido_id`),
  CONSTRAINT `pedido_referencia_pedido_id_foreign` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `pedido_referencia_referencia_id_foreign` FOREIGN KEY (`referencia_id`) REFERENCES `referencias` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `pedido_referencia_proveedor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pedido_referencia_proveedor` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pedido_referencia_id` bigint(20) unsigned NOT NULL,
  `referencia_id` bigint(20) unsigned NOT NULL,
  `tercero_id` bigint(20) unsigned NOT NULL,
  `marca_id` bigint(20) unsigned NOT NULL,
  `dias_entrega` int(11) NOT NULL,
  `costo_unidad` decimal(10,2) NOT NULL,
  `utilidad` decimal(10,2) NOT NULL,
  `valor_unidad` decimal(10,2) NOT NULL,
  `valor_total` decimal(10,2) NOT NULL,
  `ubicacion` varchar(255) DEFAULT NULL,
  `estado` varchar(255) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pedido_referencia_proveedor_pedido_referencia_id_foreign` (`pedido_referencia_id`),
  KEY `pedido_referencia_proveedor_referencia_id_foreign` (`referencia_id`),
  KEY `pedido_referencia_proveedor_tercero_id_foreign` (`tercero_id`),
  KEY `pedido_referencia_proveedor_marca_id_foreign` (`marca_id`),
  CONSTRAINT `pedido_referencia_proveedor_marca_id_foreign` FOREIGN KEY (`marca_id`) REFERENCES `listas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pedido_referencia_proveedor_pedido_referencia_id_foreign` FOREIGN KEY (`pedido_referencia_id`) REFERENCES `pedido_referencia` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pedido_referencia_proveedor_referencia_id_foreign` FOREIGN KEY (`referencia_id`) REFERENCES `referencias` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pedido_referencia_proveedor_tercero_id_foreign` FOREIGN KEY (`tercero_id`) REFERENCES `terceros` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `pedidos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pedidos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `tercero_id` bigint(20) unsigned NOT NULL,
  `direccion` varchar(200) DEFAULT NULL,
  `comentario` varchar(255) DEFAULT NULL,
  `contacto_id` bigint(20) unsigned DEFAULT NULL,
  `estado` enum('Nuevo','Enviado','Entregado','Cancelado','Rechazado','Cotizado','En_Costeo','Aprobado') NOT NULL DEFAULT 'Nuevo',
  `maquina_id` bigint(20) DEFAULT NULL,
  `fabricante_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `motivo_rechazo` varchar(255) DEFAULT NULL,
  `comentarios_rechazo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pedidos_tercero_id_foreign` (`tercero_id`),
  KEY `pedidos_user_id_foreign` (`user_id`),
  CONSTRAINT `pedidos_tercero_id_foreign` FOREIGN KEY (`tercero_id`) REFERENCES `terceros` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `pedidos_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=190 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `referencias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `referencias` (
  `id` bigint(20) unsigned NOT NULL,
  `referencia` varchar(255) DEFAULT NULL,
  `articulo_id` bigint(20) unsigned DEFAULT NULL,
  `marca_id` bigint(20) unsigned DEFAULT NULL,
  `comentario` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `categoria_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `referencias_categoria_id_foreign` (`categoria_id`),
  KEY `referencias_marca_id_foreign` (`marca_id`),
  KEY `referencias_articulo_id_foreign` (`articulo_id`),
  CONSTRAINT `referencias_articulo_id_foreign` FOREIGN KEY (`articulo_id`) REFERENCES `articulos` (`id`) ON DELETE SET NULL,
  CONSTRAINT `referencias_categoria_id_foreign` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON DELETE SET NULL,
  CONSTRAINT `referencias_marca_id_foreign` FOREIGN KEY (`marca_id`) REFERENCES `listas` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sistema_lista`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sistema_lista` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `sistema_id` bigint(20) unsigned NOT NULL,
  `lista_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sistema_lista_sistema_id_foreign` (`sistema_id`),
  KEY `sistema_lista_lista_id_foreign` (`lista_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1715 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sistemas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sistemas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `imagen` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `states`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `states` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `country_id` bigint(20) unsigned NOT NULL,
  `name` varchar(100) NOT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `states_country_id_index` (`country_id`),
  KEY `states_is_active_index` (`is_active`),
  CONSTRAINT `states_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5066 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `t_r_m_s`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `t_r_m_s` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `trm` int(11) NOT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `tercero_contacto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tercero_contacto` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tercero_id` bigint(20) unsigned NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `cargo` varchar(255) NOT NULL,
  `telefono` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `principal` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tercero_contacto_tercero_id_foreign` (`tercero_id`),
  CONSTRAINT `tercero_contacto_tercero_id_foreign` FOREIGN KEY (`tercero_id`) REFERENCES `terceros` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `tercero_fabricantes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tercero_fabricantes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tercero_id` bigint(20) unsigned NOT NULL,
  `fabricante_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `terecero_marcas_tercero_id_foreign` (`tercero_id`),
  KEY `terecero_marcas_marca_id_foreign` (`fabricante_id`),
  CONSTRAINT `terecero_marcas_marca_id_foreign` FOREIGN KEY (`fabricante_id`) REFERENCES `fabricantes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `terecero_marcas_tercero_id_foreign` FOREIGN KEY (`tercero_id`) REFERENCES `terceros` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `tercero_maquina`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tercero_maquina` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tercero_id` bigint(20) unsigned NOT NULL,
  `maquina_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tercero_maquina_tercero_id_foreign` (`tercero_id`),
  KEY `tercero_maquina_maquina_id_foreign` (`maquina_id`),
  CONSTRAINT `tercero_maquina_maquina_id_foreign` FOREIGN KEY (`maquina_id`) REFERENCES `maquinas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tercero_maquina_tercero_id_foreign` FOREIGN KEY (`tercero_id`) REFERENCES `terceros` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `tercero_marcas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tercero_marcas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tercero_id` bigint(20) unsigned NOT NULL,
  `marca_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tercero_marcas_tercero_id_foreign` (`tercero_id`),
  CONSTRAINT `tercero_marcas_tercero_id_foreign` FOREIGN KEY (`tercero_id`) REFERENCES `terceros` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `tercero_sistemas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tercero_sistemas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tercero_id` bigint(20) unsigned NOT NULL,
  `sistema_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `terecero_sistemas_tercero_id_foreign` (`tercero_id`),
  KEY `terecero_sistemas_sistema_id_foreign` (`sistema_id`),
  CONSTRAINT `terecero_sistemas_sistema_id_foreign` FOREIGN KEY (`sistema_id`) REFERENCES `sistemas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `terecero_sistemas_tercero_id_foreign` FOREIGN KEY (`tercero_id`) REFERENCES `terceros` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `terceros`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `terceros` (
  `id` bigint(20) unsigned NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `tipo_documento` varchar(255) NOT NULL,
  `numero_documento` varchar(255) NOT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `telefono` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `dv` varchar(255) DEFAULT NULL,
  `estado` varchar(255) NOT NULL DEFAULT 'activo',
  `forma_pago` varchar(255) DEFAULT NULL,
  `email_factura_electronica` varchar(255) DEFAULT NULL,
  `rut` varchar(255) DEFAULT NULL,
  `certificacion_bancaria` varchar(255) DEFAULT NULL,
  `camara_comercio` varchar(255) DEFAULT NULL,
  `cedula_representante_legal` varchar(255) DEFAULT NULL,
  `sitio_web` varchar(255) DEFAULT NULL,
  `puntos` int(11) DEFAULT NULL,
  `tipo` enum('Cliente','Proveedor','Ambos') NOT NULL DEFAULT 'Cliente',
  `country_id` bigint(20) unsigned DEFAULT NULL,
  `state_id` bigint(20) unsigned DEFAULT NULL,
  `city_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `terceros_city_id_foreign` (`city_id`),
  KEY `terceros_country_id_foreign` (`country_id`),
  KEY `terceros_state_id_foreign` (`state_id`),
  CONSTRAINT `terceros_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `terceros_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `terceros_state_id_foreign` FOREIGN KEY (`state_id`) REFERENCES `states` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `transportadoras`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transportadoras` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `nit` varchar(255) NOT NULL,
  `telefono` varchar(255) NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `city_id` bigint(20) unsigned NOT NULL,
  `state_id` bigint(20) unsigned NOT NULL,
  `country_id` bigint(20) unsigned NOT NULL,
  `email` varchar(255) NOT NULL,
  `contacto` varchar(255) NOT NULL,
  `celular` varchar(255) NOT NULL,
  `observaciones` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transportadoras_city_id_foreign` (`city_id`),
  KEY `transportadoras_state_id_foreign` (`state_id`),
  CONSTRAINT `transportadoras_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`),
  CONSTRAINT `transportadoras_state_id_foreign` FOREIGN KEY (`state_id`) REFERENCES `states` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `country_id` bigint(20) unsigned DEFAULT NULL,
  `state_id` bigint(20) unsigned DEFAULT NULL,
  `city_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_country_id_foreign` (`country_id`),
  KEY `users_state_id_foreign` (`state_id`),
  KEY `users_city_id_foreign` (`city_id`),
  CONSTRAINT `users_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `users_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `users_state_id_foreign` FOREIGN KEY (`state_id`) REFERENCES `states` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
