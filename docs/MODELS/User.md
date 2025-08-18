# Modelo User

## Descripción General

El modelo `User` es el componente central de autenticación y autorización del sistema CYH. Extiende el modelo de autenticación estándar de Laravel e implementa funcionalidades específicas de Filament para el control de acceso al panel administrativo.

## Propósito del Modelo

- **Autenticación**: Gestiona el login y sesiones de usuarios
- **Autorización**: Controla el acceso basado en roles y permisos
- **Control de Panel**: Determina quién puede acceder al panel de Filament
- **Gestión de Sesiones**: Maneja tokens de API y sesiones persistentes

## Estructura de la Tabla

### Campos Principales

| Campo | Tipo | Descripción | Requerido | Ejemplo |
|-------|------|-------------|-----------|---------|
| `id` | int | Identificador único del usuario | Sí | 1 |
| `name` | string | Nombre completo del usuario | Sí | "Juan Pérez" |
| `email` | string | Dirección de correo electrónico | Sí | "juan@cyh.com" |
| `password` | string | Contraseña hasheada | Sí | "$2y$10$..." |
| `email_verified_at` | timestamp|null | Fecha de verificación del email | No | "2025-08-18 13:00:00" |
| `remember_token` | string|null | Token para "recordar sesión" | No | "abc123..." |
| `created_at` | timestamp | Fecha de creación del usuario | Sí | "2025-08-18 13:00:00" |
| `updated_at` | timestamp | Fecha de última actualización | Sí | "2025-08-18 13:00:00" |

## Traits y Funcionalidades

### Traits Implementados

```php
use HasApiTokens, HasFactory, Notifiable, HasRoles, HasPanelShield;
```

#### **HasApiTokens**
- **Propósito**: Permite autenticación API con tokens Sanctum
- **Funcionalidad**: Genera y valida tokens de acceso para APIs
- **Uso**: Para integraciones externas y aplicaciones móviles

#### **HasFactory**
- **Propósito**: Permite crear factories para testing
- **Funcionalidad**: Genera datos de prueba para el modelo
- **Uso**: Crear usuarios de prueba en tests

#### **Notifiable**
- **Propósito**: Habilita sistema de notificaciones
- **Funcionalidad**: Envía emails, SMS, notificaciones push
- **Uso**: Notificar cambios de estado, recordatorios, etc.

#### **HasRoles**
- **Propósito**: Gestiona roles y permisos del usuario
- **Funcionalidad**: Asigna y verifica roles del sistema
- **Uso**: Control de acceso basado en roles

#### **HasPanelShield**
- **Propósito**: Control de acceso al panel de Filament
- **Funcionalidad**: Restringe acceso basado en permisos
- **Uso**: Seguridad del panel administrativo

## Relaciones

### Relaciones de Roles y Permisos

```php
// Un usuario puede tener múltiples roles
public function roles()
{
    return $this->belongsToMany(Role::class);
}

// Un usuario puede tener múltiples permisos directos
public function permissions()
{
    return $this->belongsToMany(Permission::class);
}
```

### Relaciones de Negocio (Pendientes de Implementar)

```php
// Un usuario puede crear múltiples pedidos
public function pedidos()
{
    return $this->hasMany(Pedido::class, 'created_by');
}

// Un usuario puede tener múltiples cotizaciones
public function cotizaciones()
{
    return $this->hasMany(Cotizacion::class, 'vendedor_id');
}

// Un usuario puede tener múltiples órdenes de trabajo
public function ordenesTrabajo()
{
    return $this->hasMany(OrdenTrabajo::class, 'asignado_a');
}
```

## Comportamiento del Modelo

### Control de Acceso al Panel

El modelo implementa la interfaz `FilamentUser` con el método `canAccessPanel()`:

```php
public function canAccessPanel(Panel $panel): bool
{
    return $this->hasAnyRole([
        'super_admin',    // Administrador del sistema
        'panel_user',     // Usuario del panel
        'Vendedor',       // Vendedor
        'Analista',       // Analista
        'Administrador',  // Administrador
        'Logistica'       // Logística
    ]);
}
```

**Propósito**: Controlar quién puede acceder al panel administrativo de Filament.

**Comportamiento**: 
- Solo usuarios con roles autorizados pueden acceder
- Los roles están predefinidos en el sistema
- Se puede modificar fácilmente agregando/quitando roles

### Seguridad de Contraseñas

```php
protected $casts = [
    'password' => 'hashed',
];
```

**Propósito**: Asegurar que las contraseñas se hashean automáticamente.

**Comportamiento**: 
- Laravel aplica hash automáticamente al guardar
- Nunca se almacena la contraseña en texto plano
- Compatible con `Hash::make()` y `Hash::check()`

## Casos de Uso

### 1. Autenticación de Usuario

```php
// Login tradicional
if (Auth::attempt(['email' => $email, 'password' => $password])) {
    $user = Auth::user();
    // Usuario autenticado
}

// Verificar acceso al panel
if ($user->canAccessPanel($panel)) {
    // Permitir acceso al panel
} else {
    // Denegar acceso
}
```

### 2. Gestión de Roles

```php
// Asignar rol a usuario
$user->assignRole('Vendedor');

// Verificar si tiene rol
if ($user->hasRole('Vendedor')) {
    // Usuario es vendedor
}

// Verificar si tiene cualquier rol de los especificados
if ($user->hasAnyRole(['Vendedor', 'Analista'])) {
    // Usuario es vendedor o analista
}

// Obtener todos los roles del usuario
$roles = $user->roles;
```

### 3. Gestión de Permisos

```php
// Asignar permiso directo
$user->givePermissionTo('crear_pedidos');

// Verificar permiso
if ($user->can('crear_pedidos')) {
    // Usuario puede crear pedidos
}

// Verificar múltiples permisos
if ($user->hasAllPermissions(['crear_pedidos', 'editar_pedidos'])) {
    // Usuario tiene todos los permisos
}
```

### 4. Creación de Usuarios

```php
// Crear usuario básico
$user = User::create([
    'name' => 'Nuevo Usuario',
    'email' => 'nuevo@cyh.com',
    'password' => 'contraseña123'
]);

// Crear usuario con rol
$user = User::create([
    'name' => 'Vendedor Nuevo',
    'email' => 'vendedor@cyh.com',
    'password' => 'contraseña123'
]);
$user->assignRole('Vendedor');
```

## Validaciones y Reglas de Negocio

### Reglas de Validación

1. **Campos Requeridos**: name, email, password
2. **Email Único**: Cada email debe ser único en el sistema
3. **Contraseña Segura**: Mínimo 8 caracteres, complejidad recomendada
4. **Verificación de Email**: Opcional pero recomendado

### Restricciones de Negocio

- **Acceso al Panel**: Solo usuarios con roles autorizados
- **Roles Múltiples**: Un usuario puede tener varios roles
- **Permisos Granulares**: Control fino de acciones específicas
- **Seguridad de Sesión**: Tokens de API y sesiones persistentes

## Migraciones y Base de Datos

### Estructura de la Tabla

```sql
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

### Tablas de Relación

```sql
-- Tabla de roles
CREATE TABLE roles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE,
    guard_name VARCHAR(255) NOT NULL DEFAULT 'web'
);

-- Tabla pivot usuarios-roles
CREATE TABLE model_has_roles (
    role_id BIGINT UNSIGNED NOT NULL,
    model_type VARCHAR(255) NOT NULL,
    model_id BIGINT UNSIGNED NOT NULL,
    
    PRIMARY KEY (role_id, model_id, model_type),
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE
);

-- Tabla de permisos
CREATE TABLE permissions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE,
    guard_name VARCHAR(255) NOT NULL DEFAULT 'web'
);

-- Tabla pivot usuarios-permisos
CREATE TABLE model_has_permissions (
    permission_id BIGINT UNSIGNED NOT NULL,
    model_type VARCHAR(255) NOT NULL,
    model_id BIGINT UNSIGNED NOT NULL,
    
    PRIMARY KEY (permission_id, model_id, model_type),
    FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE
);
```

### Índices Recomendados

```sql
-- Índice para búsquedas por email
CREATE INDEX idx_users_email ON users(email);

-- Índice para búsquedas por nombre
CREATE INDEX idx_users_name ON users(name);

-- Índice para verificaciones de email
CREATE INDEX idx_users_email_verified ON users(email_verified_at);
```

## Testing

### Casos de Prueba Recomendados

1. **Autenticación**
   - Login exitoso con credenciales válidas
   - Login fallido con credenciales inválidas
   - Verificación de contraseña hasheada

2. **Control de Acceso**
   - Usuario con rol puede acceder al panel
   - Usuario sin rol no puede acceder al panel
   - Verificación de roles múltiples

3. **Gestión de Roles**
   - Asignar rol a usuario
   - Remover rol de usuario
   - Verificar permisos del rol

4. **Seguridad**
   - Contraseña se hashea automáticamente
   - Campos sensibles están ocultos
   - Tokens de API funcionan correctamente

## Mantenimiento y Evolución

### Consideraciones Futuras

1. **Multi-tenancy**: Soporte para múltiples empresas
2. **Auditoría**: Log de acciones del usuario
3. **Autenticación 2FA**: Doble factor de autenticación
4. **Sesiones Concurrentes**: Control de sesiones múltiples
5. **Políticas de Contraseña**: Reglas de complejidad configurables

### Métricas de Uso

- Número de usuarios activos
- Frecuencia de login
- Roles más utilizados
- Intentos de acceso fallidos
- Uso de tokens de API

## Integración con Filament

### Configuración del Panel

```php
// En config/filament.php
'panels' => [
    'admin' => [
        'path' => 'admin',
        'login' => \App\Filament\Pages\Auth\Login::class,
        'registration' => \App\Filament\Pages\Auth\Register::class,
        'passwordReset' => \App\Filament\Pages\Auth\RequestPasswordReset::class,
        'emailVerification' => \App\Filament\Pages\Auth\EmailVerification::class,
    ],
],
```

### Personalización de Login

```php
// En app/Filament/Pages/Auth/Login.php
class Login extends \Filament\Pages\Auth\Login
{
    public function authenticate(): void
    {
        // Lógica personalizada de autenticación
    }
}
```

## Referencias

- **Laravel Authentication**: [Documentación oficial](https://laravel.com/docs/authentication)
- **Filament User Interface**: [Documentación oficial](https://filamentphp.com/docs/panels)
- **Spatie Permission**: [Gestión de roles y permisos](https://spatie.be/docs/laravel-permission)
- **Laravel Sanctum**: [Autenticación API](https://laravel.com/docs/sanctum)
- **Filament Shield**: [Control de acceso](https://github.com/bezhanSalleh/filament-shield)
