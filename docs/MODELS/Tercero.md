# Modelo Tercero

## Descripción General

El modelo `Tercero` es el componente central para la gestión de relaciones comerciales en el sistema CYH. Representa a todos los entes externos que interactúan con el sistema, incluyendo proveedores, clientes, fabricantes y otros socios comerciales. Es fundamental para la gestión de transacciones, cotizaciones y relaciones de negocio.

## Propósito del Modelo

- **Gestión de Relaciones**: Centraliza información de proveedores, clientes y socios
- **Información Comercial**: Almacena datos fiscales, bancarios y de contacto
- **Clasificación**: Categoriza terceros por tipo y especialización
- **Trazabilidad**: Rastrea todas las interacciones comerciales
- **Compliance**: Gestiona documentación legal y certificaciones

## Estructura de la Tabla

### Campos de Identificación

| Campo | Tipo | Descripción | Requerido | Ejemplo |
|-------|------|-------------|-----------|---------|
| `id` | int | Identificador único del tercero | Sí | 1 |
| `nombre` | string | Nombre o razón social del tercero | Sí | "CYH Importaciones" |
| `tipo_documento` | string | Tipo de documento de identificación | Sí | "NIT" |
| `numero_documento` | string | Número del documento de identificación | Sí | "900123456-7" |
| `dv` | string|null | Dígito de verificación (Colombia) | No | "7" |
| `rut` | string|null | Registro Único Tributario (Chile) | No | "12345678-9" |

### Campos de Contacto

| Campo | Tipo | Descripción | Requerido | Ejemplo |
|-------|------|-------------|-----------|---------|
| `direccion` | string | Dirección física del tercero | Sí | "Calle 123 #45-67" |
| `telefono` | string | Número de teléfono de contacto | Sí | "6011234567" |
| `email` | string | Dirección de correo electrónico | Sí | "contacto@cyh.com" |
| `sitio_web` | string|null | Sitio web del tercero | No | "https://cyh.com" |

### Campos Comerciales

| Campo | Tipo | Descripción | Requerido | Ejemplo |
|-------|------|-------------|-----------|---------|
| `estado` | string | Estado activo/inactivo del tercero | Sí | "Activo" |
| `forma_pago` | string | Forma de pago preferida | Sí | "Transferencia" |
| `puntos` | int | Puntos o crédito del tercero | Sí | 1000 |
| `tipo` | string | Tipo de tercero (proveedor, cliente, ambos) | Sí | "Proveedor" |

### Campos Legales y Fiscales

| Campo | Tipo | Descripción | Requerido | Ejemplo |
|-------|------|-------------|-----------|---------|
| `email_factura_electronica` | string|null | Email para facturación electrónica | No | "facturacion@cyh.com" |
| `certificacion_bancaria` | string|null | Certificación bancaria del tercero | No | "CERT-001" |
| `camara_comercio` | string|null | Registro de cámara de comercio | No | "CC-123456" |
| `cedula_representante_legal` | string|null | Cédula del representante legal | No | "12345678" |

### Campos de Localización

| Campo | Tipo | Descripción | Requerido | Ejemplo |
|-------|------|-------------|-----------|---------|
| `country_id` | int | ID del país del tercero | Sí | 48 (Colombia) |
| `state_id` | int | ID del estado/provincia del tercero | Sí | 1 (Bogotá) |
| `city_id` | int | ID de la ciudad del tercero | Sí | 1 (Bogotá) |

### Campos del Sistema

| Campo | Tipo | Descripción | Requerido | Ejemplo |
|-------|------|-------------|-----------|---------|
| `created_at` | timestamp | Fecha de creación del tercero | Sí | "2025-08-18 13:00:00" |
| `updated_at` | timestamp | Fecha de última actualización | Sí | "2025-08-18 13:00:00" |

## Tipos de Terceros

### Clasificación por Función

1. **Proveedor**: Suministra productos o servicios
2. **Cliente**: Compra productos o servicios
3. **Ambos**: Actúa como proveedor y cliente
4. **Fabricante**: Produce productos específicos
5. **Distribuidor**: Distribuye productos de terceros

### Clasificación por Documento

- **NIT**: Empresas colombianas
- **CC**: Personas naturales colombianas
- **CE**: Cédula de extranjería
- **RUT**: Empresas chilenas
- **DNI**: Personas naturales extranjeras

## Relaciones

### Relaciones de Localización

```php
// Un tercero pertenece a un país
public function country()
{
    return $this->belongsTo(Country::class);
}

// Un tercero pertenece a un estado/provincia
public function states()
{
    return $this->belongsTo(State::class);
}

// Un tercero pertenece a una ciudad
public function city()
{
    return $this->belongsTo(City::class);
}
```

### Relaciones de Contenido

```php
// Un tercero puede tener múltiples contactos
public function contactos()
{
    return $this->hasMany(Contacto::class, 'tercero_id');
}

// Un tercero puede tener múltiples direcciones
public function direcciones()
{
    return $this->hasMany(Direccion::class, 'tercero_id');
}

// Un tercero puede tener múltiples pedidos
public function pedidos()
{
    return $this->hasMany(Pedido::class);
}
```

### Relaciones de Asociación

```php
// Un tercero puede estar asociado a múltiples máquinas
public function maquinas()
{
    return $this->belongsToMany(Maquina::class, 'tercero_maquina', 'tercero_id', 'maquina_id');
}

// Un tercero puede estar asociado a múltiples sistemas
public function sistemas()
{
    return $this->belongsToMany(Sistema::class, 'tercero_sistemas', 'tercero_id', 'sistema_id');
}

// Un tercero puede estar asociado a múltiples fabricantes
public function fabricantes()
{
    return $this->belongsToMany(Fabricante::class, 'tercero_fabricantes', 'tercero_id', 'fabricante_id');
}

// Un tercero puede estar asociado a múltiples categorías
public function categorias()
{
    return $this->belongsToMany(Categoria::class);
}
```

### Relaciones Inversas (Pendientes de Implementar)

```php
// Un tercero puede tener múltiples cotizaciones
public function cotizaciones()
{
    return $this->hasMany(Cotizacion::class);
}

// Un tercero puede tener múltiples órdenes de compra
public function ordenesCompra()
{
    return $this->hasMany(OrdenCompra::class);
}

// Un tercero puede tener múltiples facturas
public function facturas()
{
    return $this->hasMany(Factura::class);
}
```

## Casos de Uso

### 1. Creación de un Nuevo Tercero

```php
// Crear proveedor básico
$proveedor = Tercero::create([
    'nombre' => 'Nuevo Proveedor',
    'tipo_documento' => 'NIT',
    'numero_documento' => '900123456-7',
    'direccion' => 'Calle 123 #45-67, Bogotá',
    'telefono' => '6011234567',
    'email' => 'contacto@proveedor.com',
    'estado' => 'Activo',
    'forma_pago' => 'Transferencia',
    'tipo' => 'Proveedor',
    'country_id' => 48, // Colombia
    'state_id' => 1,    // Bogotá
    'city_id' => 1      // Bogotá
]);

// Crear cliente con información adicional
$cliente = Tercero::create([
    'nombre' => 'Cliente Industrial',
    'tipo_documento' => 'NIT',
    'numero_documento' => '800987654-3',
    'direccion' => 'Avenida 456 #78-90, Medellín',
    'telefono' => '6049876543',
    'email' => 'compras@cliente.com',
    'estado' => 'Activo',
    'forma_pago' => 'Crédito 30 días',
    'tipo' => 'Cliente',
    'puntos' => 500,
    'country_id' => 48, // Colombia
    'state_id' => 2,    // Antioquia
    'city_id' => 2      // Medellín
]);
```

### 2. Gestión de Relaciones

```php
// Asociar máquinas a un tercero
$tercero->maquinas()->attach($maquinaId);

// Asociar sistemas a un tercero
$tercero->sistemas()->attach($sistemaId);

// Asociar fabricantes a un tercero
$tercero->fabricantes()->attach($fabricanteId);

// Asociar categorías a un tercero
$tercero->categorias()->attach($categoriaId);
```

### 3. Consultas y Reportes

```php
// Terceros por tipo
$proveedores = Tercero::where('tipo', 'Proveedor')->get();
$clientes = Tercero::where('tipo', 'Cliente')->get();

// Terceros por país
$tercerosColombia = Tercero::where('country_id', 48)->get();

// Terceros activos
$tercerosActivos = Tercero::where('estado', 'Activo')->get();

// Terceros por forma de pago
$tercerosCredito = Tercero::where('forma_pago', 'LIKE', '%Crédito%')->get();

// Terceros con mayor puntaje
$tercerosTop = Tercero::orderBy('puntos', 'desc')->take(10)->get();

// Búsqueda por nombre o documento
$terceros = Tercero::where('nombre', 'LIKE', '%CYH%')
    ->orWhere('numero_documento', 'LIKE', '%900%')
    ->get();
```

### 4. Trabajo con Relaciones Complejas

```php
// Obtener tercero con todas sus relaciones
$tercero = Tercero::with([
    'country',
    'state',
    'city',
    'contactos',
    'direcciones',
    'maquinas',
    'sistemas',
    'fabricantes',
    'categorias',
    'pedidos'
])->find($terceroId);

// Obtener proveedores por categoría
$proveedoresCategoria = Tercero::where('tipo', 'Proveedor')
    ->whereHas('categorias', function($query) use ($categoriaId) {
        $query->where('id', $categoriaId);
    })
    ->get();

// Obtener clientes con pedidos recientes
$clientesConPedidos = Tercero::where('tipo', 'Cliente')
    ->whereHas('pedidos', function($query) {
        $query->where('created_at', '>=', now()->subMonths(3));
    })
    ->withCount('pedidos')
    ->get();
```

## Validaciones y Reglas de Negocio

### Reglas de Validación

1. **Campos Requeridos**: nombre, tipo_documento, numero_documento, direccion, telefono, email, estado, forma_pago, tipo, country_id, state_id, city_id
2. **Campos Opcionales**: dv, rut, certificacion_bancaria, camara_comercio, cedula_representante_legal, sitio_web, puntos, email_factura_electronica
3. **Unicidad**: La combinación tipo_documento + numero_documento debe ser única
4. **Formato**: El email debe ser válido, el teléfono debe seguir formato estándar

### Restricciones de Negocio

- **Documento Único**: No puede haber dos terceros con el mismo tipo y número de documento
- **Estado Válido**: Solo estados predefinidos son permitidos
- **Tipo Válido**: Solo tipos predefinidos son permitidos
- **Localización**: country_id, state_id y city_id deben formar una combinación válida
- **Integridad Referencial**: No se puede eliminar un tercero con transacciones activas

## Migraciones y Base de Datos

### Estructura de la Tabla

```sql
CREATE TABLE terceros (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    tipo_documento VARCHAR(20) NOT NULL,
    numero_documento VARCHAR(50) NOT NULL,
    direccion TEXT NOT NULL,
    telefono VARCHAR(20) NOT NULL,
    email VARCHAR(255) NOT NULL,
    dv VARCHAR(5) NULL,
    estado VARCHAR(20) NOT NULL DEFAULT 'Activo',
    forma_pago VARCHAR(50) NOT NULL,
    email_factura_electronica VARCHAR(255) NULL,
    rut VARCHAR(20) NULL,
    certificacion_bancaria VARCHAR(100) NULL,
    camara_comercio VARCHAR(100) NULL,
    cedula_representante_legal VARCHAR(20) NULL,
    sitio_web VARCHAR(255) NULL,
    puntos INT NOT NULL DEFAULT 0,
    tipo VARCHAR(20) NOT NULL,
    country_id BIGINT UNSIGNED NOT NULL,
    state_id BIGINT UNSIGNED NOT NULL,
    city_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    UNIQUE KEY uk_tercero_documento (tipo_documento, numero_documento),
    FOREIGN KEY (country_id) REFERENCES countries(id) ON DELETE CASCADE,
    FOREIGN KEY (state_id) REFERENCES states(id) ON DELETE CASCADE,
    FOREIGN KEY (city_id) REFERENCES cities(id) ON DELETE CASCADE
);
```

### Tablas de Relación

```sql
-- Tabla pivot terceros-máquinas
CREATE TABLE tercero_maquina (
    tercero_id BIGINT UNSIGNED NOT NULL,
    maquina_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    PRIMARY KEY (tercero_id, maquina_id),
    FOREIGN KEY (tercero_id) REFERENCES terceros(id) ON DELETE CASCADE,
    FOREIGN KEY (maquina_id) REFERENCES maquinas(id) ON DELETE CASCADE
);

-- Tabla pivot terceros-sistemas
CREATE TABLE tercero_sistemas (
    tercero_id BIGINT UNSIGNED NOT NULL,
    sistema_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    PRIMARY KEY (tercero_id, sistema_id),
    FOREIGN KEY (tercero_id) REFERENCES terceros(id) ON DELETE CASCADE,
    FOREIGN KEY (sistema_id) REFERENCES sistemas(id) ON DELETE CASCADE
);

-- Tabla pivot terceros-fabricantes
CREATE TABLE tercero_fabricantes (
    tercero_id BIGINT UNSIGNED NOT NULL,
    fabricante_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    PRIMARY KEY (tercero_id, fabricante_id),
    FOREIGN KEY (tercero_id) REFERENCES terceros(id) ON DELETE CASCADE,
    FOREIGN KEY (fabricante_id) REFERENCES fabricantes(id) ON DELETE CASCADE
);

-- Tabla pivot terceros-categorías
CREATE TABLE categoria_tercero (
    tercero_id BIGINT UNSIGNED NOT NULL,
    categoria_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    PRIMARY KEY (tercero_id, categoria_id),
    FOREIGN KEY (tercero_id) REFERENCES terceros(id) ON DELETE CASCADE,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE CASCADE
);
```

### Índices Recomendados

```sql
-- Índice para búsquedas por tipo de tercero
CREATE INDEX idx_terceros_tipo ON terceros(tipo);

-- Índice para búsquedas por estado
CREATE INDEX idx_terceros_estado ON terceros(estado);

-- Índice para búsquedas por país
CREATE INDEX idx_terceros_country ON terceros(country_id);

-- Índice para búsquedas por nombre
CREATE INDEX idx_terceros_nombre ON terceros(nombre);

-- Índice para búsquedas por email
CREATE INDEX idx_terceros_email ON terceros(email);

-- Índice compuesto para búsquedas frecuentes
CREATE INDEX idx_terceros_tipo_estado ON terceros(tipo, estado);
```

## Testing

### Casos de Prueba Recomendados

1. **Creación de Terceros**
   - Crear tercero con campos mínimos requeridos
   - Crear tercero con todos los campos opcionales
   - Verificar que se generen timestamps automáticamente

2. **Validaciones de Unicidad**
   - Intentar crear tercero con documento duplicado
   - Verificar que no se permitan documentos duplicados

3. **Relaciones**
   - Verificar que las relaciones con Country, State y City funcionen
   - Verificar que las relaciones con Máquinas, Sistemas y Fabricantes funcionen
   - Verificar que las relaciones con Contactos y Direcciones funcionen

4. **Reglas de Negocio**
   - Verificar integridad referencial al eliminar
   - Verificar que country_id, state_id y city_id sean válidos
   - Verificar que tipo y estado sean valores permitidos

## Mantenimiento y Evolución

### Consideraciones Futuras

1. **Auditoría**: Agregar campos de auditoría (created_by, updated_by)
2. **Soft Deletes**: Implementar eliminación suave para mantener historial
3. **Validación de Documentos**: Implementar validación automática de documentos
4. **Scoring**: Sistema de puntuación automática basado en comportamiento
5. **Blacklist**: Sistema de lista negra para terceros problemáticos

### Métricas de Uso

- Número de terceros por tipo
- Terceros por país/región
- Terceros más activos (por pedidos)
- Terceros con mayor puntuación
- Terceros por categoría de producto

## Integración con Filament

### Configuración del Resource

```php
// En app/Filament/Resources/TerceroResource.php
class TerceroResource extends Resource
{
    protected static ?string $model = Tercero::class;
    
    protected static ?string $navigationIcon = 'heroicon-o-users';
    
    protected static ?string $navigationGroup = 'Comercial';
    
    protected static ?int $navigationSort = 1;
}
```

### Formularios y Validaciones

```php
// En app/Filament/Resources/TerceroResource/Forms/TerceroForm.php
public static function form(Form $form): Form
{
    return $form
        ->schema([
            Section::make('Información Básica')
                ->schema([
                    TextInput::make('nombre')
                        ->required()
                        ->maxLength(255)
                        ->label('Nombre/Razón Social'),
                    
                    Select::make('tipo_documento')
                        ->options([
                            'NIT' => 'NIT',
                            'CC' => 'Cédula de Ciudadanía',
                            'CE' => 'Cédula de Extranjería',
                            'RUT' => 'RUT (Chile)',
                            'DNI' => 'DNI (Extranjero)'
                        ])
                        ->required()
                        ->label('Tipo de Documento'),
                    
                    TextInput::make('numero_documento')
                        ->required()
                        ->maxLength(50)
                        ->label('Número de Documento'),
                    
                    TextInput::make('dv')
                        ->maxLength(5)
                        ->label('Dígito de Verificación'),
                ])->columns(2),
            
            Section::make('Información de Contacto')
                ->schema([
                    Textarea::make('direccion')
                        ->required()
                        ->rows(3)
                        ->label('Dirección'),
                    
                    TextInput::make('telefono')
                        ->required()
                        ->tel()
                        ->maxLength(20)
                        ->label('Teléfono'),
                    
                    TextInput::make('email')
                        ->required()
                        ->email()
                        ->maxLength(255)
                        ->label('Email'),
                    
                    TextInput::make('sitio_web')
                        ->url()
                        ->maxLength(255)
                        ->label('Sitio Web'),
                ])->columns(2),
            
            Section::make('Información Comercial')
                ->schema([
                    Select::make('tipo')
                        ->options([
                            'Proveedor' => 'Proveedor',
                            'Cliente' => 'Cliente',
                            'Ambos' => 'Proveedor y Cliente',
                            'Fabricante' => 'Fabricante',
                            'Distribuidor' => 'Distribuidor'
                        ])
                        ->required()
                        ->label('Tipo de Tercero'),
                    
                    Select::make('estado')
                        ->options([
                            'Activo' => 'Activo',
                            'Inactivo' => 'Inactivo',
                            'Suspendido' => 'Suspendido',
                            'Bloqueado' => 'Bloqueado'
                        ])
                        ->required()
                        ->default('Activo')
                        ->label('Estado'),
                    
                    Select::make('forma_pago')
                        ->options([
                            'Efectivo' => 'Efectivo',
                            'Transferencia' => 'Transferencia',
                            'Cheque' => 'Cheque',
                            'Crédito 30 días' => 'Crédito 30 días',
                            'Crédito 60 días' => 'Crédito 60 días'
                        ])
                        ->required()
                        ->label('Forma de Pago'),
                    
                    TextInput::make('puntos')
                        ->numeric()
                        ->default(0)
                        ->label('Puntos/Crédito'),
                ])->columns(2),
            
            Section::make('Localización')
                ->schema([
                    Select::make('country_id')
                        ->relationship('country', 'name')
                        ->required()
                        ->searchable()
                        ->label('País'),
                    
                    Select::make('state_id')
                        ->relationship('state', 'name')
                        ->required()
                        ->searchable()
                        ->label('Estado/Provincia'),
                    
                    Select::make('city_id')
                        ->relationship('city', 'name')
                        ->required()
                        ->searchable()
                        ->label('Ciudad'),
                ])->columns(3),
        ]);
}
```

## Referencias

- **Laravel Eloquent**: [Documentación oficial](https://laravel.com/docs/eloquent)
- **Filament Resources**: [Documentación oficial](https://filamentphp.com/docs/resources)
- **Relaciones Eloquent**: [Guía de relaciones](https://laravel.com/docs/eloquent-relationships)
- **Customer Relationship Management**: [Sistemas CRM](https://en.wikipedia.org/wiki/Customer_relationship_management)
