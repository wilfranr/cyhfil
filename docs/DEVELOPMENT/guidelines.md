# Guía de Desarrollo - Sistema CYH

## Descripción General

Esta guía establece los estándares, patrones y mejores prácticas para el desarrollo del sistema CYH. Está diseñada para asegurar consistencia, calidad y mantenibilidad del código a través de todo el proyecto.

## Estándares de Código

### Convenciones de Nomenclatura

#### Clases y Archivos
```php
// ✅ Correcto
class PedidoReferencia extends Model
class CalculadoraPrecios
class GestorUsuarios

// ❌ Incorrecto
class pedido_referencia extends Model
class calculadoraPrecios
class gestor_usuarios
```

#### Métodos y Propiedades
```php
// ✅ Correcto
public function calcularValorTotal(): float
public function obtenerProveedores(): Collection
protected $fillable = ['nombre', 'email'];

// ❌ Incorrecto
public function CalcularValorTotal(): float
public function obtener_proveedores(): Collection
protected $fillable = ['Nombre', 'Email'];
```

#### Variables y Parámetros
```php
// ✅ Correcto
$costoUnidad = 1000;
$utilidadPorcentaje = 15;
$proveedorNacional = true;

// ❌ Incorrecto
$costo_unidad = 1000;
$utilidadPorcentaje = 15;
$proveedor_nacional = true;
```

### Estructura de Archivos

```
app/
├── Models/           # Modelos Eloquent
├── Filament/         # Recursos y páginas de Filament
│   ├── Resources/    # Recursos principales
│   ├── Pages/        # Páginas personalizadas
│   └── Forms/        # Formularios reutilizables
├── Http/             # Controladores y middleware
├── Services/         # Lógica de negocio
├── Repositories/     # Acceso a datos
└── Helpers/          # Funciones auxiliares
```

### Comentarios y Documentación

#### PHPDoc para Clases
```php
/**
 * Modelo Pedido - Gestiona los pedidos principales del sistema CYH
 * 
 * Este modelo representa un pedido completo en el sistema, incluyendo
 * información del cliente, referencias solicitadas, artículos y
 * proveedores asociados.
 * 
 * @property int $id Identificador único del pedido
 * @property string $estado Estado actual del pedido
 * @property \Carbon\Carbon $created_at Fecha de creación
 * 
 * @since 1.0.0
 * @author Sistema CYH
 */
class Pedido extends Model
```

#### PHPDoc para Métodos
```php
/**
 * Calcula el valor total de una referencia basado en el tipo de proveedor.
 * 
 * @param float $costoUnidad Costo por unidad en la moneda original
 * @param float $utilidad Porcentaje de utilidad a aplicar
 * @param int $cantidad Cantidad de unidades
 * @param string $ubicacion Ubicación del proveedor (Nacional/Internacional)
 * @return array Array con valor_unidad y valor_total
 * 
 * @throws \InvalidArgumentException Si los parámetros son inválidos
 * @example
 * $valores = $this->calcularValorTotal(1000, 15, 5, 'Nacional');
 */
public function calcularValorTotal(float $costoUnidad, float $utilidad, int $cantidad, string $ubicacion): array
```

#### Comentarios Inline
```php
// ✅ Comentarios útiles
$valorBase = $costoUnidad * $trm; // Convertir USD a COP

// ❌ Comentarios obvios
$valorBase = $costoUnidad * $trm; // Multiplicar costo por TRM
```

## Patrones de Diseño

### Repository Pattern

```php
// Interface del repositorio
interface PedidoRepositoryInterface
{
    public function findById(int $id): ?Pedido;
    public function findByEstado(string $estado): Collection;
    public function create(array $data): Pedido;
    public function update(Pedido $pedido, array $data): bool;
    public function delete(Pedido $pedido): bool;
}

// Implementación del repositorio
class PedidoRepository implements PedidoRepositoryInterface
{
    public function __construct(private Pedido $model) {}
    
    public function findById(int $id): ?Pedido
    {
        return $this->model->find($id);
    }
    
    public function findByEstado(string $estado): Collection
    {
        return $this->model->where('estado', $estado)->get();
    }
    
    // ... otros métodos
}
```

### Service Pattern

```php
class PedidoService
{
    public function __construct(
        private PedidoRepositoryInterface $pedidoRepository,
        private CalculadoraPrecios $calculadora
    ) {}
    
    public function crearPedido(array $data): Pedido
    {
        // Validar datos
        $this->validarDatosPedido($data);
        
        // Crear pedido
        $pedido = $this->pedidoRepository->create($data);
        
        // Calcular precios
        $this->calcularPreciosPedido($pedido);
        
        return $pedido;
    }
    
    private function validarDatosPedido(array $data): void
    {
        // Lógica de validación
    }
    
    private function calcularPreciosPedido(Pedido $pedido): void
    {
        // Lógica de cálculo
    }
}
```

### Factory Pattern

```php
class PedidoFactory
{
    public static function crearPedidoBasico(string $nombre, string $email): Pedido
    {
        return new Pedido([
            'nombre' => $nombre,
            'email' => $email,
            'estado' => 'Borrador',
            'fecha_creacion' => now(),
        ]);
    }
    
    public static function crearPedidoUrgente(string $nombre, string $email): Pedido
    {
        $pedido = self::crearPedidoBasico($nombre, $email);
        $pedido->prioridad = 'Alta';
        $pedido->fecha_limite = now()->addDays(3);
        
        return $pedido;
    }
}
```

## Manejo de Errores

### Excepciones Personalizadas

```php
class PedidoException extends Exception
{
    public static function pedidoNoEncontrado(int $id): self
    {
        return new self("Pedido con ID {$id} no encontrado");
    }
    
    public static function estadoInvalido(string $estado): self
    {
        return new self("Estado '{$estado}' no es válido para el pedido");
    }
    
    public static function camposRequeridos(array $campos): self
    {
        $camposStr = implode(', ', $campos);
        return new self("Los siguientes campos son requeridos: {$camposStr}");
    }
}
```

### Manejo de Errores en Controladores

```php
public function store(Request $request)
{
    try {
        $pedido = $this->pedidoService->crearPedido($request->validated());
        
        return response()->json([
            'success' => true,
            'data' => $pedido,
            'message' => 'Pedido creado exitosamente'
        ], 201);
        
    } catch (PedidoException $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 400);
        
    } catch (Exception $e) {
        Log::error('Error al crear pedido', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Error interno del servidor'
        ], 500);
    }
}
```

### Validaciones

```php
// Request de validación
class CrearPedidoRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telefono' => 'required|string|max:20',
            'direccion' => 'required|string|max:500',
            'referencias' => 'required|array|min:1',
            'referencias.*.referencia_id' => 'required|exists:referencias,id',
            'referencias.*.cantidad' => 'required|integer|min:1',
        ];
    }
    
    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del cliente es obligatorio',
            'email.required' => 'El email del cliente es obligatorio',
            'email.email' => 'El email debe tener un formato válido',
            'referencias.required' => 'Debe incluir al menos una referencia',
            'referencias.*.cantidad.min' => 'La cantidad debe ser mayor a 0',
        ];
    }
}
```

## Testing

### Estructura de Tests

```
tests/
├── Feature/           # Tests de integración
│   ├── PedidoTest.php
│   └── ReferenciaTest.php
├── Unit/              # Tests unitarios
│   ├── PedidoServiceTest.php
│   └── CalculadoraPreciosTest.php
└── Database/          # Factories y seeders
    ├── PedidoFactory.php
    └── DatabaseSeeder.php
```

### Tests Unitarios

```php
class PedidoServiceTest extends TestCase
{
    use RefreshDatabase;
    
    private PedidoService $pedidoService;
    private MockObject $pedidoRepository;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->pedidoRepository = $this->createMock(PedidoRepositoryInterface::class);
        $this->pedidoService = new PedidoService($this->pedidoRepository);
    }
    
    public function test_crear_pedido_exitoso(): void
    {
        // Arrange
        $data = [
            'nombre' => 'Cliente Test',
            'email' => 'test@example.com',
            'estado' => 'Borrador'
        ];
        
        $pedido = new Pedido($data);
        
        $this->pedidoRepository
            ->expects($this->once())
            ->method('create')
            ->with($data)
            ->willReturn($pedido);
        
        // Act
        $resultado = $this->pedidoService->crearPedido($data);
        
        // Assert
        $this->assertInstanceOf(Pedido::class, $resultado);
        $this->assertEquals('Cliente Test', $resultado->nombre);
        $this->assertEquals('Borrador', $resultado->estado);
    }
    
    public function test_crear_pedido_sin_nombre_lanza_excepcion(): void
    {
        // Arrange
        $data = ['email' => 'test@example.com'];
        
        // Act & Assert
        $this->expectException(PedidoException::class);
        $this->expectExceptionMessage('El nombre del cliente es obligatorio');
        
        $this->pedidoService->crearPedido($data);
    }
}
```

### Tests de Integración

```php
class PedidoTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_usuario_puede_crear_pedido(): void
    {
        // Arrange
        $user = User::factory()->create();
        $referencia = Referencia::factory()->create();
        
        $pedidoData = [
            'nombre' => 'Cliente Test',
            'email' => 'test@example.com',
            'referencias' => [
                [
                    'referencia_id' => $referencia->id,
                    'cantidad' => 5
                ]
            ]
        ];
        
        // Act
        $response = $this->actingAs($user)
            ->postJson('/api/pedidos', $pedidoData);
        
        // Assert
        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Pedido creado exitosamente'
            ]);
        
        $this->assertDatabaseHas('pedidos', [
            'nombre' => 'Cliente Test',
            'email' => 'test@example.com'
        ]);
    }
}
```

## Performance y Optimización

### Consultas de Base de Datos

```php
// ✅ Eager Loading para evitar N+1 queries
$pedidos = Pedido::with(['referencias', 'cliente', 'usuario'])
    ->where('estado', 'Activo')
    ->get();

// ❌ Lazy Loading que causa N+1 queries
$pedidos = Pedido::where('estado', 'Activo')->get();
foreach ($pedidos as $pedido) {
    echo $pedido->referencias->count(); // Query adicional por pedido
}

// ✅ Consultas optimizadas con índices
$pedidos = Pedido::where('estado', 'Activo')
    ->where('fecha_creacion', '>=', now()->subDays(30))
    ->whereHas('referencias', function($query) {
        $query->where('cantidad', '>', 10);
    })
    ->get();

// ✅ Uso de select para campos específicos
$pedidos = Pedido::select('id', 'nombre', 'estado', 'fecha_creacion')
    ->where('estado', 'Activo')
    ->get();
```

### Caching

```php
// Cache de consultas frecuentes
class PedidoRepository
{
    public function getPedidosActivos(): Collection
    {
        return Cache::remember('pedidos_activos', 300, function () {
            return Pedido::where('estado', 'Activo')
                ->with(['referencias', 'cliente'])
                ->get();
        });
    }
    
    public function getEstadisticas(): array
    {
        return Cache::remember('estadisticas_pedidos', 3600, function () {
            return [
                'total' => Pedido::count(),
                'activos' => Pedido::where('estado', 'Activo')->count(),
                'completados' => Pedido::where('estado', 'Completado')->count(),
            ];
        });
    }
}
```

## Seguridad

### Validación de Entrada

```php
// Sanitización de datos
public function sanitizeInput(array $data): array
{
    return [
        'nombre' => strip_tags(trim($data['nombre'] ?? '')),
        'email' => filter_var($data['email'] ?? '', FILTER_SANITIZE_EMAIL),
        'telefono' => preg_replace('/[^0-9+\-\s]/', '', $data['telefono'] ?? ''),
        'direccion' => strip_tags(trim($data['direccion'] ?? '')),
    ];
}
```

### Autorización

```php
// Policies para autorización
class PedidoPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('ver_pedidos');
    }
    
    public function view(User $user, Pedido $pedido): bool
    {
        return $user->hasPermissionTo('ver_pedidos') &&
               ($user->id === $pedido->user_id || $user->hasRole('admin'));
    }
    
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('crear_pedidos');
    }
    
    public function update(User $user, Pedido $pedido): bool
    {
        return $user->hasPermissionTo('editar_pedidos') &&
               ($user->id === $pedido->user_id || $user->hasRole('admin'));
    }
}
```

## Logging y Monitoreo

### Logging Estructurado

```php
// Log de operaciones importantes
Log::info('Pedido creado', [
    'pedido_id' => $pedido->id,
    'cliente' => $pedido->cliente->nombre,
    'usuario' => auth()->user()->name,
    'referencias_count' => $pedido->referencias->count(),
    'valor_total' => $pedido->valor_total,
    'timestamp' => now()->toISOString()
]);

// Log de errores con contexto
Log::error('Error al procesar pedido', [
    'pedido_id' => $pedido->id,
    'error' => $e->getMessage(),
    'trace' => $e->getTraceAsString(),
    'user_id' => auth()->id(),
    'request_data' => $request->all()
]);
```

### Métricas de Performance

```php
// Medición de tiempo de ejecución
$startTime = microtime(true);

// ... código a medir ...

$executionTime = microtime(true) - $startTime;
Log::info('Tiempo de ejecución', [
    'operation' => 'calculo_precios',
    'execution_time_ms' => round($executionTime * 1000, 2),
    'pedido_id' => $pedido->id
]);
```

## Deployment y CI/CD

### Variables de Entorno

```bash
# .env.example
APP_NAME="Sistema CYH"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://cyh.example.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cyh_production
DB_USERNAME=cyh_user
DB_PASSWORD=secure_password

CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis
```

### Comandos de Deployment

```bash
# Script de deployment
#!/bin/bash
set -e

echo "🚀 Iniciando deployment..."

# Pull del código más reciente
git pull origin main

# Instalar dependencias
composer install --no-dev --optimize-autoloader

# Limpiar caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Ejecutar migraciones
php artisan migrate --force

# Optimizar para producción
php artisan optimize

# Reiniciar servicios
sudo systemctl restart php8.1-fpm
sudo systemctl restart nginx

echo "✅ Deployment completado exitosamente!"
```

### Pipeline de CI/CD

```yaml
# .github/workflows/deploy.yml
name: Deploy to Production

on:
  push:
    branches: [main]

jobs:
  deploy:
    runs-on: ubuntu-latest
    
    steps:
    - uses: actions/checkout@v2
    
    - name: Setup PHP
      uses: shivammathur/setup-php@v2
      with:
        php-version: '8.1'
        
    - name: Install dependencies
      run: composer install --no-dev --optimize-autoloader
      
    - name: Run tests
      run: php artisan test
      
    - name: Deploy to server
      run: |
        ssh user@server.com "cd /var/www/cyh && ./deploy.sh"
```

## Mantenimiento

### Tareas Programadas

```php
// Console Commands para mantenimiento
class LimpiarPedidosAntiguos extends Command
{
    protected $signature = 'pedidos:limpiar {--days=90}';
    protected $description = 'Limpiar pedidos antiguos del sistema';
    
    public function handle(): int
    {
        $days = $this->option('days');
        $fechaLimite = now()->subDays($days);
        
        $pedidosEliminados = Pedido::where('created_at', '<', $fechaLimite)
            ->where('estado', 'Completado')
            ->delete();
            
        $this->info("Se eliminaron {$pedidosEliminados} pedidos antiguos");
        
        return 0;
    }
}

// Programar en Kernel
protected function schedule(Schedule $schedule): void
{
    $schedule->command('pedidos:limpiar --days=90')->daily();
    $schedule->command('backup:database')->daily();
    $schedule->command('cache:clear')->weekly();
}
```

### Monitoreo de Salud

```php
// Health Check del sistema
class HealthCheckController extends Controller
{
    public function check(): JsonResponse
    {
        $checks = [
            'database' => $this->checkDatabase(),
            'cache' => $this->checkCache(),
            'storage' => $this->checkStorage(),
            'queue' => $this->checkQueue(),
        ];
        
        $isHealthy = !in_array(false, $checks);
        $statusCode = $isHealthy ? 200 : 503;
        
        return response()->json([
            'status' => $isHealthy ? 'healthy' : 'unhealthy',
            'timestamp' => now()->toISOString(),
            'checks' => $checks
        ], $statusCode);
    }
    
    private function checkDatabase(): bool
    {
        try {
            DB::connection()->getPdo();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
```

## Referencias

- **PSR Standards**: [PHP-FIG Standards](https://www.php-fig.org/psr/)
- **Laravel Best Practices**: [Laravel Documentation](https://laravel.com/docs)
- **PHPUnit Testing**: [PHPUnit Documentation](https://phpunit.de/)
- **Clean Code**: [Robert C. Martin](https://blog.cleancoder.com/)
- **SOLID Principles**: [Wikipedia](https://en.wikipedia.org/wiki/SOLID)
