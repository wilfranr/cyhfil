# Changelog

Todas las notables modificaciones a este proyecto serán documentadas en este archivo.

El formato está basado en [Keep a Changelog](https://keepachangelog.com/es-ES/1.0.0/),
y este proyecto adhiere a [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- **Filtro por Proveedor en Pedidos**: Nueva funcionalidad que permite filtrar referencias por proveedor en la vista de edición de pedidos
  - Selector intuitivo de proveedores con búsqueda
  - Filtrado en tiempo real de referencias usando JavaScript del lado del cliente
  - Indicadores visuales del estado del filtro
  - Botón para limpiar el filtro fácilmente
  - Compatibilidad con todos los tipos de pedidos existentes
  - API REST para obtener proveedores de pedidos específicos

### Changed
- Mejorada la interfaz de edición de pedidos para mayor usabilidad
- Implementado filtrado por proveedor usando JavaScript para mejor rendimiento

### Technical
- Agregada relación `pedidosReferenciaProveedor` al modelo `Tercero`
- Implementado filtrado inteligente que solo muestra proveedores relevantes
- Creado controlador API `PedidoController` para obtener proveedores de pedidos
- Agregada ruta API `/api/pedidos/{pedido}/proveedores`
- Implementado componente Blade personalizado con Alpine.js para el filtro
- Uso de atributos de datos en repeaters para filtrado por JavaScript

## [2024.08.18] - 2024-08-18

### Fixed
- **MaquinasResource**: Corregido error crítico en formularios de edición
  - **Problema**: Error "Return value must be of type array, null returned" en `getEditOptionActionFormData()`
  - **Causa**: Los métodos `editOptionForm` estaban definidos como funciones anónimas que podían devolver `null`
  - **Solución**: Convertidos a arrays directos para garantizar que siempre devuelvan arrays válidos
  - **Archivos afectados**: 
    - `app/Filament/Resources/MaquinasResource.php` - Campos `tipo` y `fabricante_id`
  - **Impacto**: Los usuarios ahora pueden editar máquinas sin errores

### Changed
- **MaquinasResource**: Mejorada la configuración de formularios de edición
  - Campos `tipo` y `fabricante_id` ahora tienen formularios de edición estables
  - Eliminada la posibilidad de errores por valores nulos en formularios

### Technical Details
- **Archivo**: `app/Filament/Resources/MaquinasResource.php`
- **Líneas modificadas**: 108-119 y 180-191
- **Método**: `editOptionForm()` para campos Select
- **Tipo de cambio**: Bug fix crítico

## [2024.08.17] - 2024-08-17

### Added
- Documentación inicial del proyecto
- Estructura base de Filament Resources
- Sistema de autenticación y autorización

### Changed
- Configuración inicial de Laravel con Filament
- Estructura de base de datos implementada

---

## Notas de Versión

### Versionado
- **Formato**: YYYY.MM.DD
- **Ejemplo**: 2024.08.18

### Tipos de Cambios
- **Added**: Nueva funcionalidad
- **Changed**: Cambios en funcionalidad existente
- **Deprecated**: Funcionalidad que será removida
- **Removed**: Funcionalidad removida
- **Fixed**: Corrección de bugs
- **Security**: Correcciones de seguridad

### Mantenimiento
- Este archivo se actualiza con cada release
- Los cambios se documentan en orden cronológico inverso
- Se incluyen detalles técnicos relevantes para desarrolladores
