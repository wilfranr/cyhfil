# Modelo Empresa

## Descripción General

El modelo `Empresa` es el núcleo del sistema CYH, representando la entidad empresarial que opera la plataforma. Este modelo contiene toda la información necesaria para la operación del sistema, incluyendo configuración de moneda, costos de flete y datos de contacto.

## Propósito del Modelo

- **Gestión de Configuración**: Almacena parámetros globales del sistema como TRM y flete
- **Identidad Corporativa**: Mantiene logos, información de contacto y datos legales
- **Control de Actividad**: Asegura que solo una empresa esté activa en el sistema
- **Localización**: Gestiona la ubicación geográfica de la empresa

## Estructura de la Tabla

### Campos Principales

| Campo | Tipo | Descripción | Requerido | Ejemplo |
|-------|------|-------------|-----------|---------|
| `id` | int | Identificador único | Sí | 1 |
| `nombre` | string | Nombre completo de la empresa | Sí | "CYH Importaciones e Inversiones" |
| `direccion` | string | Dirección física completa | Sí | "Calle 123 #45-67" |
| `telefono` | string | Número de teléfono fijo | Sí | "6011234567" |
| `celular` | string | Número de teléfono móvil | Sí | "3001234567" |
| `email` | string | Dirección de correo electrónico | Sí | "contacto@cyh.com" |

### Campos de Identidad Visual

| Campo | Tipo | Descripción | Requerido | Ejemplo |
|-------|------|-------------|-----------|---------|
| `logo_light` | string|null | Logo versión clara (PNG/JPG) | No | "logos/cyh-light.png" |
| `logo_dark` | string|null | Logo versión oscura (PNG/JPG) | No | "logos/cyh-dark.png" |
| `siglas` | string | Abreviatura de la empresa | Sí | "CYH" |

### Campos Legales y Fiscales

| Campo | Tipo | Descripción | Requerido | Ejemplo |
|-------|------|-------------|-----------|---------|
| `nit` | string | Número de identificación tributaria | Sí | "900123456-7" |
| `representante` | string | Nombre del representante legal | Sí | "Juan Pérez" |

### Campos de Localización

| Campo | Tipo | Descripción | Requerido | Ejemplo |
|-------|------|-------------|-----------|---------|
| `country_id` | int | ID del país donde opera | Sí | 48 (Colombia) |
| `state_id` | int | ID del estado/provincia | Sí | 1 (Bogotá) |
| `city_id` | int | ID de la ciudad | Sí | 1 (Bogotá) |

### Campos de Configuración del Sistema

| Campo | Tipo | Descripción | Requerido | Ejemplo |
|-------|------|-------------|-----------|---------|
| `estado` | boolean | Estado activo/inactivo | Sí | true |
| `flete` | float|null | Costo de flete por kg (COP) | No | 5000.00 |
| `trm` | float|null | Tasa de cambio USD a COP | No | 4000.00 |

## Relaciones

### Relaciones Existentes

```php
// Una empresa pertenece a un país
public function country()
{
    return $this->belongsTo(Country::class);
}

// Una empresa pertenece a un estado/provincia
public function states()
{
    return $this->belongsTo(State::class);
}

// Una empresa pertenece a una ciudad
public function city()
{
    return $this->belongsTo(City::class);
}
```

### Relaciones Inversas (Pendientes de Implementar)

```php
// Una empresa puede tener muchos usuarios
public function users()
{
    return $this->hasMany(User::class);
}

// Una empresa puede tener muchos pedidos
public function pedidos()
{
    return $this->hasMany(Pedido::class);
}
```

## Comportamiento del Modelo

### Evento Saving

El modelo implementa un evento `saving` que asegura que **solo una empresa esté activa** en el sistema:

```php
static::saving(function ($model) {
    if ($model->estado) {
        // Desactiva todas las demás empresas
        static::where('id', '!=', $model->id)
            ->update(['estado' => false]);
    }
});
```

**Propósito**: Evitar conflictos de configuración cuando múltiples empresas están activas.

**Comportamiento**: 
- Al activar una empresa, automáticamente se desactivan todas las demás
- Solo se ejecuta cuando `estado = true`
- No afecta el registro actual que se está guardando

## Casos de Uso

### 1. Configuración del Sistema

```php
// Obtener empresa activa para cálculos
$empresa = Empresa::where('estado', 1)->first();

// Usar TRM para conversiones de moneda
$costo_usd = 100;
$costo_cop = $costo_usd * $empresa->trm;

// Usar flete para cálculos de envío
$peso_kg = 2.5;
$costo_flete = $peso_kg * $empresa->flete;
```

### 2. Gestión de Empresas

```php
// Crear nueva empresa
$empresa = Empresa::create([
    'nombre' => 'Nueva Empresa',
    'estado' => true,  // Automáticamente desactiva las demás
    'trm' => 4000.00,
    'flete' => 5000.00
]);

// Cambiar empresa activa
$empresa_anterior = Empresa::where('estado', 1)->first();
$empresa_anterior->update(['estado' => false]);

$nueva_empresa = Empresa::find(2);
$nueva_empresa->update(['estado' => true]);
```

### 3. Validaciones de Negocio

```php
// Verificar que empresa tenga configuración necesaria
if (!$empresa->trm || !$empresa->flete) {
    throw new Exception('Empresa no tiene TRM o flete configurado');
}

// Verificar que empresa esté activa
if (!$empresa->estado) {
    throw new Exception('Empresa no está activa');
}
```

## Validaciones y Reglas de Negocio

### Reglas de Validación

1. **Unicidad de Estado Activo**: Solo una empresa puede estar activa
2. **Campos Requeridos**: nombre, direccion, telefono, celular, email, nit, representante
3. **Campos Opcionales**: logo_light, logo_dark, flete, trm
4. **Tipos de Datos**: estado como boolean, flete y trm como float

### Restricciones de Negocio

- **TRM**: Debe ser mayor a 0 cuando esté configurado
- **Flete**: Debe ser mayor a 0 cuando esté configurado
- **Email**: Debe ser un email válido
- **NIT**: Debe ser único en el sistema

## Migraciones y Base de Datos

### Estructura de la Tabla

```sql
CREATE TABLE empresas (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    direccion TEXT NOT NULL,
    telefono VARCHAR(20) NOT NULL,
    celular VARCHAR(20) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    logo_light VARCHAR(255) NULL,
    logo_dark VARCHAR(255) NULL,
    nit VARCHAR(20) NOT NULL UNIQUE,
    representante VARCHAR(255) NOT NULL,
    country_id BIGINT UNSIGNED NOT NULL,
    state_id BIGINT UNSIGNED NOT NULL,
    city_id BIGINT UNSIGNED NOT NULL,
    estado BOOLEAN NOT NULL DEFAULT FALSE,
    siglas VARCHAR(10) NOT NULL,
    flete DECIMAL(10,2) NULL,
    trm DECIMAL(10,2) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (country_id) REFERENCES countries(id),
    FOREIGN KEY (state_id) REFERENCES states(id),
    FOREIGN KEY (city_id) REFERENCES cities(id)
);
```

### Índices Recomendados

```sql
-- Índice para búsquedas por estado
CREATE INDEX idx_empresas_estado ON empresas(estado);

-- Índice para búsquedas por país
CREATE INDEX idx_empresas_country ON empresas(country_id);

-- Índice para búsquedas por email
CREATE INDEX idx_empresas_email ON empresas(email);

-- Índice para búsquedas por NIT
CREATE INDEX idx_empresas_nit ON empresas(nit);
```

## Testing

### Casos de Prueba Recomendados

1. **Creación de Empresa**
   - Crear empresa con todos los campos requeridos
   - Verificar que se genere correctamente

2. **Activación de Empresa**
   - Activar una empresa y verificar que se desactiven las demás
   - Verificar que solo una esté activa

3. **Validaciones de Campos**
   - Intentar crear empresa sin campos requeridos
   - Verificar mensajes de error apropiados

4. **Relaciones**
   - Verificar que las relaciones con Country, State y City funcionen
   - Verificar que se puedan acceder a los datos relacionados

## Mantenimiento y Evolución

### Consideraciones Futuras

1. **Auditoría**: Agregar campos de auditoría (created_by, updated_by)
2. **Soft Deletes**: Implementar eliminación suave para mantener historial
3. **Configuración**: Agregar más parámetros de configuración del sistema
4. **Multi-tenancy**: Preparar para soporte de múltiples empresas activas

### Métricas de Uso

- Número de empresas creadas
- Frecuencia de cambios de empresa activa
- Uso de campos TRM y flete en cálculos
- Errores relacionados con empresa no configurada

## Referencias

- **Laravel Eloquent**: [Documentación oficial](https://laravel.com/docs/eloquent)
- **PHPDoc**: [Estándar de documentación](https://docs.phpdoc.org/)
- **Relaciones Eloquent**: [Guía de relaciones](https://laravel.com/docs/eloquent-relationships)
