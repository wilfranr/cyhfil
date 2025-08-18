# Modelo Referencia

## Descripción General

El modelo `Referencia` es el identificador principal para la gestión de artículos en el sistema CYH. Representa un código o número de referencia que puede estar asociado a múltiples artículos, categorías y pedidos. Es fundamental para el sistema de inventario, cotizaciones y gestión de productos.

## Propósito del Modelo

- **Identificación de Artículos**: Proporciona códigos únicos para referenciar productos
- **Gestión de Inventario**: Centraliza la información de artículos por referencia
- **Cotizaciones**: Permite cotizar referencias específicas con múltiples proveedores
- **Categorización**: Organiza artículos por marcas y categorías
- **Trazabilidad**: Rastrea el uso de referencias en pedidos y cotizaciones

## Estructura de la Tabla

### Campos Principales

| Campo | Tipo | Descripción | Requerido | Ejemplo |
|-------|------|-------------|-----------|---------|
| `id` | int | Identificador único de la referencia | Sí | 1 |
| `referencia` | string | Código o número de referencia del artículo | Sí | "REF-001" |
| `marca_id` | int|null | ID de la marca asociada a la referencia | No | 5 |
| `comentario` | string|null | Comentarios adicionales sobre la referencia | No | "Repuesto original" |

### Campos del Sistema

| Campo | Tipo | Descripción | Requerido | Ejemplo |
|-------|------|-------------|-----------|---------|
| `created_at` | timestamp | Fecha de creación de la referencia | Sí | "2025-08-18 13:00:00" |
| `updated_at` | timestamp | Fecha de última actualización | Sí | "2025-08-18 13:00:00" |

## Relaciones

### Relaciones Principales

```php
// Una referencia pertenece a una marca
public function marca()
{
    return $this->belongsTo(Lista::class, 'marca_id');
}

// Una referencia pertenece a una categoría
public function categoria()
{
    return $this->belongsTo(Categoria::class);
}
```

### Relaciones de Contenido

```php
// Una referencia puede estar asociada a múltiples artículos
public function articulos()
{
    return $this->belongsToMany(
        Articulo::class,
        'articulos_referencias',
        'referencia_id',
        'articulo_id'
    );
}

// Una referencia puede estar en múltiples pedidos con cantidades
public function pedidos()
{
    return $this->belongsToMany(Pedido::class, 'pedido_referencia', 'referencia_id', 'pedido_id')
        ->withPivot('cantidad');
}
```

### Relaciones de Detalle

```php
// Una referencia tiene múltiples entradas en la tabla pivot
public function articuloReferencia()
{
    return $this->hasMany(ArticuloReferencia::class, 'referencia_id');
}

// Una referencia puede tener múltiples juegos de artículos
public function articuloJuegos()
{
    return $this->hasMany(ArticuloJuego::class);
}
```

### Relaciones Inversas (Pendientes de Implementar)

```php
// Una referencia puede tener múltiples cotizaciones
public function cotizaciones()
{
    return $this->hasMany(CotizacionReferenciaProveedor::class);
}

// Una referencia puede tener múltiples precios históricos
public function preciosHistoricos()
{
    return $this->hasMany(PrecioHistorico::class);
}
```

## Casos de Uso

### 1. Creación de una Nueva Referencia

```php
// Crear referencia básica
$referencia = Referencia::create([
    'referencia' => 'REF-001',
    'comentario' => 'Repuesto para máquina industrial'
]);

// Crear referencia con marca
$referencia = Referencia::create([
    'referencia' => 'REF-002',
    'marca_id' => $marca->id,
    'comentario' => 'Filtro de aceite original'
]);
```

### 2. Gestión de Artículos por Referencia

```php
// Asociar artículos a una referencia
$referencia->articulos()->attach($articuloId, [
    'compatibilidad' => '100%',
    'notas' => 'Artículo compatible'
]);

// Obtener todos los artículos de una referencia
$articulos = $referencia->articulos;

// Verificar si un artículo es compatible
$esCompatible = $referencia->articulos()->where('id', $articuloId)->exists();
```

### 3. Gestión de Pedidos por Referencia

```php
// Agregar referencia a un pedido con cantidad
$pedido->referencias()->attach($referenciaId, [
    'cantidad' => 5,
    'comentario' => 'Cantidad solicitada por el cliente'
]);

// Obtener pedidos que incluyen una referencia
$pedidos = $referencia->pedidos;

// Obtener cantidad de una referencia en un pedido específico
$cantidad = $referencia->pedidos()
    ->where('pedido_id', $pedidoId)
    ->first()
    ->pivot
    ->cantidad;
```

### 4. Consultas y Reportes

```php
// Referencias por marca
$referenciasMarca = Referencia::where('marca_id', $marcaId)->get();

// Referencias por categoría
$referenciasCategoria = Referencia::whereHas('categoria', function($query) use ($categoriaId) {
    $query->where('id', $categoriaId);
})->get();

// Referencias más utilizadas en pedidos
$referenciasPopulares = Referencia::withCount('pedidos')
    ->orderBy('pedidos_count', 'desc')
    ->take(10)
    ->get();

// Referencias sin marca asignada
$referenciasSinMarca = Referencia::whereNull('marca_id')->get();

// Búsqueda por código de referencia
$referencia = Referencia::where('referencia', 'LIKE', '%REF%')->get();
```

### 5. Trabajo con Relaciones Complejas

```php
// Obtener referencia con todas sus relaciones
$referencia = Referencia::with([
    'marca',
    'categoria',
    'articulos',
    'pedidos',
    'articuloJuegos'
])->find($referenciaId);

// Obtener artículos compatibles con información de proveedores
$articulosConProveedores = $referencia->articulos()
    ->with(['proveedores', 'fabricantes'])
    ->get();

// Obtener pedidos con información del cliente
$pedidosConCliente = $referencia->pedidos()
    ->with(['tercero', 'user'])
    ->get();
```

## Validaciones y Reglas de Negocio

### Reglas de Validación

1. **Campos Requeridos**: referencia
2. **Campos Opcionales**: marca_id, comentario
3. **Unicidad**: El código de referencia debe ser único en el sistema
4. **Formato**: El código de referencia debe seguir un formato estándar

### Restricciones de Negocio

- **Referencia Única**: No puede haber dos referencias con el mismo código
- **Marca Válida**: Si se asigna marca_id, debe existir en la tabla Lista
- **Categoría Válida**: Si se asigna categoria_id, debe existir en la tabla Categoria
- **Integridad Referencial**: No se puede eliminar una referencia que esté en uso

## Migraciones y Base de Datos

### Estructura de la Tabla

```sql
CREATE TABLE referencias (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    referencia VARCHAR(255) NOT NULL UNIQUE,
    marca_id BIGINT UNSIGNED NULL,
    comentario TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (marca_id) REFERENCES listas(id) ON DELETE SET NULL
);
```

### Tablas de Relación

```sql
-- Tabla pivot referencias-artículos
CREATE TABLE articulos_referencias (
    referencia_id BIGINT UNSIGNED NOT NULL,
    articulo_id BIGINT UNSIGNED NOT NULL,
    compatibilidad VARCHAR(50) DEFAULT '100%',
    notas TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    PRIMARY KEY (referencia_id, articulo_id),
    FOREIGN KEY (referencia_id) REFERENCES referencias(id) ON DELETE CASCADE,
    FOREIGN KEY (articulo_id) REFERENCES articulos(id) ON DELETE CASCADE
);

-- Tabla pivot pedidos-referencias
CREATE TABLE pedido_referencia (
    pedido_id BIGINT UNSIGNED NOT NULL,
    referencia_id BIGINT UNSIGNED NOT NULL,
    cantidad INT NOT NULL DEFAULT 1,
    comentario TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    PRIMARY KEY (pedido_id, referencia_id),
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE,
    FOREIGN KEY (referencia_id) REFERENCES referencias(id) ON DELETE CASCADE
);
```

### Índices Recomendados

```sql
-- Índice para búsquedas por código de referencia
CREATE INDEX idx_referencias_codigo ON referencias(referencia);

-- Índice para búsquedas por marca
CREATE INDEX idx_referencias_marca ON referencias(marca_id);

-- Índice para búsquedas por fecha de creación
CREATE INDEX idx_referencias_created ON referencias(created_at);

-- Índice compuesto para búsquedas frecuentes
CREATE INDEX idx_referencias_marca_codigo ON referencias(marca_id, referencia);
```

## Testing

### Casos de Prueba Recomendados

1. **Creación de Referencias**
   - Crear referencia con código único
   - Crear referencia con marca y comentario
   - Verificar que se generen timestamps automáticamente

2. **Validaciones de Unicidad**
   - Intentar crear referencia con código duplicado
   - Verificar que no se permitan códigos duplicados

3. **Relaciones**
   - Verificar que las relaciones con Marca y Categoria funcionen
   - Verificar que las relaciones con Artículos funcionen
   - Verificar que las relaciones con Pedidos funcionen

4. **Reglas de Negocio**
   - Verificar integridad referencial al eliminar
   - Verificar que marca_id sea válido cuando se asigne
   - Verificar que categoria_id sea válido cuando se asigne

## Mantenimiento y Evolución

### Consideraciones Futuras

1. **Auditoría**: Agregar campos de auditoría (created_by, updated_by)
2. **Soft Deletes**: Implementar eliminación suave para mantener historial
3. **Versiones**: Sistema de versionado de referencias
4. **Metadatos**: Campos adicionales para clasificación avanzada
5. **Búsqueda**: Implementar búsqueda full-text en comentarios

### Métricas de Uso

- Número de referencias por marca
- Referencias más utilizadas en pedidos
- Referencias sin marca asignada
- Referencias por categoría
- Tiempo promedio de vida de una referencia

## Integración con Filament

### Configuración del Resource

```php
// En app/Filament/Resources/ReferenciaResource.php
class ReferenciaResource extends Resource
{
    protected static ?string $model = Referencia::class;
    
    protected static ?string $navigationIcon = 'heroicon-o-tag';
    
    protected static ?string $navigationGroup = 'Inventario';
    
    protected static ?int $navigationSort = 2;
}
```

### Formularios y Validaciones

```php
// En app/Filament/Resources/ReferenciaResource/Forms/ReferenciaForm.php
public static function form(Form $form): Form
{
    return $form
        ->schema([
            TextInput::make('referencia')
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(255)
                ->label('Código de Referencia'),
            
            Select::make('marca_id')
                ->relationship('marca', 'nombre')
                ->searchable()
                ->nullable()
                ->label('Marca'),
            
            Textarea::make('comentario')
                ->rows(3)
                ->maxLength(1000)
                ->label('Comentarios'),
        ]);
}
```

### Tablas y Listados

```php
// En app/Filament/Resources/ReferenciaResource/Pages/ListReferencias.php
public function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('referencia')
                ->searchable()
                ->sortable()
                ->label('Código'),
            
            Tables\Columns\TextColumn::make('marca.nombre')
                ->searchable()
                ->sortable()
                ->label('Marca'),
            
            Tables\Columns\TextColumn::make('categoria.nombre')
                ->searchable()
                ->sortable()
                ->label('Categoría'),
            
            Tables\Columns\TextColumn::make('articulos_count')
                ->counts('articulos')
                ->label('Artículos'),
            
            Tables\Columns\TextColumn::make('pedidos_count')
                ->counts('pedidos')
                ->label('Pedidos'),
        ])
        ->filters([
            Tables\Filters\SelectFilter::make('marca')
                ->relationship('marca', 'nombre'),
            
            Tables\Filters\SelectFilter::make('categoria')
                ->relationship('categoria', 'nombre'),
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ]),
        ]);
}
```

## Referencias

- **Laravel Eloquent**: [Documentación oficial](https://laravel.com/docs/eloquent)
- **Filament Resources**: [Documentación oficial](https://filamentphp.com/docs/resources)
- **Relaciones Eloquent**: [Guía de relaciones](https://laravel.com/docs/eloquent-relationships)
- **Inventory Management**: [Sistemas de gestión de inventario](https://en.wikipedia.org/wiki/Inventory_management_system)
