# Modelo PedidoReferenciaProveedor

## Descripción General

El modelo `PedidoReferenciaProveedor` es un modelo pivote que gestiona la relación entre las referencias de un pedido y sus respectivos proveedores. Este modelo es fundamental para el manejo de cotizaciones y compras, ya que almacena información detallada sobre los precios, cantidades y condiciones de cada proveedor para una referencia específica en un pedido.

## Estructura de la Tabla

### Campos Principales

| Campo | Tipo | Descripción | Requerido | Ejemplo |
|-------|------|-------------|-----------|---------|
| `id` | int | Identificador único | Sí | 1 |
| `pedido_referencia_id` | int | ID de la referencia en el pedido | Sí | 15 |
| `referencia_id` | int | ID de la referencia | Sí | 42 |
| `tercero_id` | int | ID del proveedor | Sí | 8 |
| `marca_id` | int | ID de la marca del producto | No | 12 |
| `cantidad` | int | Cantidad solicitada | Sí | 5 |
| `ubicacion` | string | Ubicación del producto | No | "Bodega 1" |
| `dias_entrega` | int | Días estimados para entrega | No | 3 |
| `costo_unidad` | decimal | Costo unitario del producto | No | 15000.00 |
| `utilidad` | decimal | Porcentaje de utilidad | No | 20.50 |
| `valor_unidad` | decimal | Valor unitario con utilidad | No | 18075.00 |
| `valor_total` | decimal | Valor total (cantidad * valor_unidad) | No | 90375.00 |
| `estado` | int | Estado de la referencia (1: Activo, 0: Inactivo) | Sí | 1 |
| `created_at` | timestamp | Fecha de creación | Sí | "2025-08-18 15:30:00" |
| `updated_at` | timestamp | Fecha de actualización | Sí | "2025-08-18 15:30:00" |

## Relaciones

- **pedidoReferencia**: Pertenece a `PedidoReferencia`
  - Relación: `belongsTo`
  - Clave foránea: `pedido_referencia_id`

- **referencia**: Pertenece a `Referencia`
  - Relación: `belongsTo`
  - Clave foránea: `referencia_id`

- **tercero**: Pertenece a `Tercero` (Proveedor)
  - Relación: `belongsTo`
  - Clave foránea: `tercero_id`

## Comportamiento del Modelo

### Eventos

#### Al Crear (creating)

1. **Obtención de IDs**:
   - Intenta obtener `referencia_id` y `pedido_referencia_id` de la relación `pedidoReferencia` si está disponible.
   - Si no están disponibles, los busca en la solicitud HTTP actual.
   - Como último recurso, verifica en la sesión del usuario.

2. **Validación**:
   - Verifica que tanto `referencia_id` como `pedido_referencia_id` estén presentes.
   - Si falta alguno, lanza una excepción con un mensaje descriptivo.

### Cálculos Automáticos

- **valor_unidad**: Se calcula automáticamente sumando el `costo_unidad` más el porcentaje de `utilidad`.
- **valor_total**: Se calcula multiplicando `cantidad` por `valor_unidad`.

## Uso Típico

### Creación de una Nueva Referencia de Proveedor

```php
$referenciaProveedor = PedidoReferenciaProveedor::create([
    'pedido_referencia_id' => $pedidoReferencia->id,
    'referencia_id' => $referencia->id,
    'tercero_id' => $proveedor->id,
    'marca_id' => $marca->id,
    'cantidad' => 5,
    'costo_unidad' => 15000,
    'utilidad' => 20.5,
    'dias_entrega' => 3,
    'ubicacion' => 'Bodega Principal',
    'estado' => 1
]);
```

### Consultas Comunes

```php
// Obtener todas las referencias de proveedores para un pedido específico
$referencias = PedidoReferenciaProveedor::whereHas('pedidoReferencia', function($query) use ($pedidoId) {
    $query->where('pedido_id', $pedidoId);
})->get();

// Obtener el costo total de un pedido
$costoTotal = PedidoReferenciaProveedor::whereHas('pedidoReferencia', function($query) use ($pedidoId) {
    $query->where('pedido_id', $pedidoId);
})->sum('valor_total');
```

## Validaciones

El modelo incluye las siguientes validaciones:

1. **Presencia de IDs**:
   - `referencia_id` debe existir en la tabla `referencias`.
   - `pedido_referencia_id` debe existir en la tabla `pedido_referencia`.
   - `tercero_id` debe existir en la tabla `terceros`.
   - `marca_id` debe existir en la tabla `listas` con tipo 'Marca'.

2. **Valores Numéricos**:
   - `cantidad` debe ser un entero positivo.
   - `costo_unidad`, `utilidad`, `valor_unidad` y `valor_total` deben ser valores numéricos válidos.

## Consideraciones de Rendimiento

- Se recomienda utilizar eager loading al consultar relaciones para evitar el problema N+1.
- Los cálculos de totales se pueden optimizar utilizando métodos de agregación de la base de datos cuando sea posible.

## Mantenimiento

- **Auditoría**: Se recomienda implementar un sistema de auditoría para rastrear cambios en los precios y condiciones.
- **Historial**: Mantener un historial de cambios en los precios de los proveedores para análisis futuros.
- **Rendimiento**: Para pedidos con muchas referencias, considerar la implementación de paginación o carga diferida.
