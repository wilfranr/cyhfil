CREATE TABLE `terceros` (
  `id` bigint(20) UNSIGNED NOT NULL,
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
  `country_id` bigint(20) UNSIGNED DEFAULT NULL,
  `state_id` bigint(20) UNSIGNED DEFAULT NULL,
  `city_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tercero_contacto`
--

CREATE TABLE `tercero_contacto` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tercero_id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `cargo` varchar(255) NOT NULL,
  `telefono` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `principal` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tercero_fabricantes`
--

CREATE TABLE `tercero_fabricantes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tercero_id` bigint(20) UNSIGNED NOT NULL,
  `fabricante_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tercero_maquina`
--

CREATE TABLE `tercero_maquina` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tercero_id` bigint(20) UNSIGNED NOT NULL,
  `maquina_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tercero_sistemas`
--

CREATE TABLE `tercero_sistemas` (
