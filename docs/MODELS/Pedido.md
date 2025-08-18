# Modelo Pedido

## Descripción General

El modelo `Pedido` es el núcleo del flujo de trabajo de cotización y compra en el sistema CYH. Representa un pedido completo que incluye información del cliente, referencias solicitadas, artículos específicos y proveedores asociados. Es la entidad principal que conecta todos los aspectos del proceso de venta.

## Propósito del Modelo

- **Gestión de Pedidos**: Centraliza toda la información relacionada con un pedido
- **Flujo de Trabajo**: Coordina el proceso desde cotización hasta entrega
- **Relaciones Clave**: Conecta usuarios, clientes, referencias y proveedores
- **Seguimiento**: Permite rastrear el estado y progreso del pedido

## Estructura de la Tabla

### Campos Principales

| Campo | Tipo | Descripción | Requerido | Ejemplo |
|-------|------|-------------|-----------|---------|
| `id` | int | Identificador único del pedido | Sí | 1 |
| `user_id` | int | ID del usuario que creó el pedido | Sí | 5 |
| `tercero_id` | int | ID del tercero (cliente/proveedor) | Sí | 12 |
| `direccion` | string | Dirección de entrega del pedido | Sí | "Calle 123 #45-67" |
| `comentario` | string|null | Comentarios adicionales del pedido | No | "Entrega urgente" |
| `contacto_id` | int|null | ID del contacto asociado | No | 8 |
| `maquina_id` | int|null | ID de la máquina asociada | No | 3 |
| `fabricante_id` | int|null | ID del fabricante | No | 7 |
| `estado` | string | Estado actual del pedido | Sí | "En_Costeo" |
| `motivo_rechazo` | string|null | Motivo si el pedido fue rechazado | No | "Precio muy alto" |

### Campos del Sistema

| Campo | Tipo | Descripción | Requerido | Ejemplo |
|-------|------|-------------|-----------|---------|
| `created_at` | timestamp | Fecha de creación del pedido | Sí | "2025-08-18 13:00:00" |
| `updated_at` | timestamp | Fecha de última actualización | Sí | "2025-08-18 13:00:00" |

## Estados del Pedido

### Flujo de Estados Típico

1. **Borrador** - Pedido en creación
2. **En_Costeo** - En proceso de cotización
3. **Cotizado** - Cotización completada
4. **Aprobado** - Pedido aprobado por el cliente
5. **En_Proceso** - En proceso de compra/entrega
6. **Entregado** - Pedido entregado al cliente
7. **Rechazado** - Pedido rechazado por el cliente
8. **Cancelado** - Pedido cancelado

### Estados Especiales

- **Pendiente_Revision** - Requiere revisión técnica
- **En_Espera** - Esperando aprobación o información
- **Urgente** - Prioridad alta para procesamiento

## Relaciones

### Relaciones Principales

```php
// Un pedido pertenece a un usuario (creador)
public function user()
{
    return $this->belongsTo(User::class);
}

// Un pedido pertenece a un tercero (cliente)
public function tercero()
{
    return $this->belongsTo(Tercero::class);
}

// Un pedido puede estar asociado a una máquina
public function maquina()
{
    return $this->belongsTo(Maquina::class);
}

// Un pedido puede estar asociado a un fabricante
public function fabricante()
{
    return $this->belongsTo(Fabricante::class);
}
```

### Relaciones de Contenido

```php
// Un pedido tiene múltiples referencias
public function referencias()
{
    return $this->hasMany(PedidoReferencia::class);
}

// Un pedido tiene múltiples artículos
public function articulos()
{
    return $this->hasMany(PedidoArticulo::class);
}

// Un pedido tiene múltiples referencias con proveedores
public function referenciasProveedor()
{
    return $this->hasMany(PedidoReferenciaProveedor::class);
}
```

### Relaciones Inversas (Pendientes de Implementar)

```php
// Un pedido puede tener múltiples cotizaciones
public function cotizaciones()
{
    return $this->hasMany(Cotizacion::class);
}

// Un pedido puede tener múltiples órdenes de trabajo
public function ordenesTrabajo()
{
    return $this->hasMany(OrdenTrabajo::class);
}

// Un pedido puede tener múltiples órdenes de compra
public function ordenesCompra()
{
    return $this->hasMany(OrdenCompra::class);
}
```

## Casos de Uso

### 1. Creación de un Nuevo Pedido

```php
// Crear pedido básico
$pedido = Pedido::create([
    'user_id' => Auth::id(),
    'tercero_id' => $cliente->id,
    'direccion' => 'Calle 123 #45-67, Bogotá',
    'estado' => 'Borrador',
    'comentario' => 'Pedido urgente para mantenimiento'
]);

// Crear pedido con máquina y fabricante
$pedido = Pedido::create([
    'user_id' => Auth::id(),
    'tercero_id' => $cliente->id,
    'direccion' => $cliente->direccion,
    'maquina_id' => $maquina->id,
    'fabricante_id' => $fabricante->id,
    'estado' => 'En_Costeo'
]);
```

### 2. Gestión del Flujo de Trabajo

```php
// Cambiar estado del pedido
$pedido->update(['estado' => 'En_Costeo']);

// Agregar motivo de rechazo
$pedido->update([
    'estado' => 'Rechazado',
    'motivo_rechazo' => 'Precio fuera del presupuesto'
]);

// Marcar como urgente
$pedido->update(['estado' => 'Urgente']);
```

### 3. Consultas y Reportes

```php
// Pedidos por usuario
$pedidosUsuario = Pedido::where('user_id', $userId)->get();

// Pedidos por estado
$pedidosEnCosteo = Pedido::where('estado', 'En_Costeo')->get();

// Pedidos por tercero
$pedidosCliente = Pedido::where('tercero_id', $terceroId)->get();

// Pedidos urgentes
$pedidosUrgentes = Pedido::where('estado', 'Urgente')->get();

// Pedidos del mes actual
$pedidosMes = Pedido::whereMonth('created_at', now()->month)->get();
```

### 4. Trabajo con Relaciones

```php
// Obtener pedido con todas sus relaciones
$pedido = Pedido::with([
    'user',
    'tercero',
    'maquina',
    'fabricante',
    'referencias',
    'articulos',
    'referenciasProveedor'
])->find($pedidoId);

// Agregar referencias al pedido
$pedido->referencias()->create([
    'referencia_id' => $referenciaId,
    'cantidad' => 5,
    'comentario' => 'Referencia específica'
]);

// Obtener proveedores del pedido
$proveedores = $pedido->referenciasProveedor()
    ->with('proveedor')
    ->get()
    ->pluck('proveedor')
    ->unique();
```

## Validaciones y Reglas de Negocio

### Reglas de Validación

1. **Campos Requeridos**: user_id, tercero_id, direccion, estado
2. **Campos Opcionales**: comentario, contacto_id, maquina_id, fabricante_id, motivo_rechazo
3. **Estados Válidos**: Lista predefinida de estados del pedido
4. **Relaciones**: user_id y tercero_id deben existir en sus respectivas tablas

### Restricciones de Negocio

- **Usuario Creador**: Solo el usuario que creó el pedido puede modificarlo (excepto roles especiales)
- **Cambios de Estado**: Solo se permiten transiciones válidas entre estados
- **Motivo de Rechazo**: Obligatorio cuando el estado es "Rechazado"
- **Pedidos Urgentes**: Máximo 3 pedidos urgentes por usuario simultáneamente

## Migraciones y Base de Datos

### Estructura de la Tabla

```sql
CREATE TABLE pedidos (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    tercero_id BIGINT UNSIGNED NOT NULL,
    direccion TEXT NOT NULL,
    comentario TEXT NULL,
    contacto_id BIGINT UNSIGNED NULL,
    maquina_id BIGINT UNSIGNED NULL,
    fabricante_id BIGINT UNSIGNED NULL,
    estado VARCHAR(50) NOT NULL DEFAULT 'Borrador',
    motivo_rechazo TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (tercero_id) REFERENCES terceros(id) ON DELETE CASCADE,
    FOREIGN KEY (contacto_id) REFERENCES contactos(id) ON DELETE SET NULL,
    FOREIGN KEY (maquina_id) REFERENCES maquinas(id) ON DELETE SET NULL,
    FOREIGN KEY (fabricante_id) REFERENCES fabricantes(id) ON DELETE SET NULL
);
```

### Índices Recomendados

```sql
-- Índice para búsquedas por usuario
CREATE INDEX idx_pedidos_user ON pedidos(user_id);

-- Índice para búsquedas por tercero
CREATE INDEX idx_pedidos_tercero ON pedidos(tercero_id);

-- Índice para búsquedas por estado
CREATE INDEX idx_pedidos_estado ON pedidos(estado);

-- Índice para búsquedas por fecha de creación
CREATE INDEX idx_pedidos_created ON pedidos(created_at);

-- Índice compuesto para búsquedas frecuentes
CREATE INDEX idx_pedidos_user_estado ON pedidos(user_id, estado);
```

## Testing

### Casos de Prueba Recomendados

1. **Creación de Pedidos**
   - Crear pedido con campos mínimos requeridos
   - Crear pedido con todos los campos opcionales
   - Verificar que se generen timestamps automáticamente

2. **Validaciones de Estado**
   - Cambiar estado a valores válidos
   - Intentar cambiar a estados inválidos
   - Verificar transiciones de estado permitidas

3. **Relaciones**
   - Verificar que las relaciones con User y Tercero funcionen
   - Verificar que las relaciones con Referencias funcionen
   - Verificar que las relaciones con Artículos funcionen

4. **Reglas de Negocio**
   - Verificar límite de pedidos urgentes por usuario
   - Verificar que solo el creador pueda modificar (excepto roles especiales)
   - Verificar que motivo_rechazo sea obligatorio en estado "Rechazado"

## Mantenimiento y Evolución

### Consideraciones Futuras

1. **Auditoría**: Agregar campos de auditoría (created_by, updated_by)
2. **Soft Deletes**: Implementar eliminación suave para mantener historial
3. **Workflow Engine**: Implementar motor de flujo de trabajo para estados
4. **Notificaciones**: Sistema de notificaciones automáticas por cambios de estado
5. **Aprobaciones**: Sistema de aprobaciones en cadena para pedidos grandes

### Métricas de Uso

- Número de pedidos por estado
- Tiempo promedio en cada estado
- Pedidos por usuario
- Pedidos por tercero
- Pedidos urgentes vs normales
- Tasa de conversión (borrador → entregado)

## Integración con Filament

### Configuración del Resource

```php
// En app/Filament/Resources/PedidoResource.php
class PedidoResource extends Resource
{
    protected static ?string $model = Pedido::class;
    
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    
    protected static ?string $navigationGroup = 'Ventas';
    
    protected static ?int $navigationSort = 1;
}
```

### Formularios y Validaciones

```php
// En app/Filament/Resources/PedidoResource/Forms/PedidoForm.php
public static function form(Form $form): Form
{
    return $form
        ->schema([
            Select::make('user_id')
                ->relationship('user', 'name')
                ->required()
                ->default(Auth::id()),
            
            Select::make('tercero_id')
                ->relationship('tercero', 'nombre')
                ->required()
                ->searchable(),
            
            Textarea::make('direccion')
                ->required()
                ->rows(3),
            
            Select::make('estado')
                ->options([
                    'Borrador' => 'Borrador',
                    'En_Costeo' => 'En Costeo',
                    'Cotizado' => 'Cotizado',
                    'Aprobado' => 'Aprobado',
                    'En_Proceso' => 'En Proceso',
                    'Entregado' => 'Entregado',
                    'Rechazado' => 'Rechazado',
                    'Cancelado' => 'Cancelado',
                ])
                ->required()
                ->default('Borrador'),
        ]);
}
```

## Referencias

- **Laravel Eloquent**: [Documentación oficial](https://laravel.com/docs/eloquent)
- **Filament Resources**: [Documentación oficial](https://filamentphp.com/docs/resources)
- **Relaciones Eloquent**: [Guía de relaciones](https://laravel.com/docs/eloquent-relationships)
- **Workflow Management**: [Patrones de flujo de trabajo](https://en.wikipedia.org/wiki/Workflow_management_system)
