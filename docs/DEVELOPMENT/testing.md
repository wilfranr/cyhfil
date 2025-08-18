# Guía de Testing - Sistema CYH

## Descripción General

Esta guía establece las mejores prácticas y estándares para implementar testing en el Sistema CYH. El testing es fundamental para asegurar la calidad del código, prevenir regresiones y facilitar el mantenimiento del sistema.

## 🎯 **Objetivos del Testing**

- **Prevenir Regresiones**: Asegurar que nuevas funcionalidades no rompan código existente
- **Validar Lógica de Negocio**: Verificar que los cálculos y reglas funcionen correctamente
- **Documentar Comportamiento**: Los tests sirven como documentación viva del código
- **Facilitar Refactoring**: Permitir cambios seguros en el código
- **Mejorar Calidad**: Identificar bugs temprano en el desarrollo

## 🏗️ **Arquitectura de Testing**

### **Estructura de Directorios**

```
tests/
├── Unit/              # Tests unitarios (lógica aislada)
├── Feature/           # Tests de integración (funcionalidades completas)
├── Database/          # Factories y seeders para testing
└── TestCase.php       # Clase base para todos los tests
```

### **Tipos de Tests**

#### **1. Tests Unitarios (`tests/Unit/`)**
- **Propósito**: Probar lógica aislada (métodos, clases)
- **Alcance**: Una funcionalidad específica
- **Velocidad**: Muy rápidos
- **Ejemplo**: Cálculos matemáticos, validaciones

#### **2. Tests de Integración (`tests/Feature/`)**
- **Propósito**: Probar funcionalidades completas
- **Alcance**: Múltiples componentes trabajando juntos
- **Velocidad**: Rápidos
- **Ejemplo**: Crear pedidos, gestionar usuarios

#### **3. Tests de Base de Datos (`tests/Database/`)**
- **Propósito**: Probar operaciones de base de datos
- **Alcance**: Modelos, relaciones, migraciones
- **Velocidad**: Moderados
- **Ejemplo**: CRUD operations, relaciones Eloquent

## 🚀 **Configuración del Entorno de Testing**

### **Archivo phpunit.xml**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:noNamespaceSchemaLocation="vendor/phpunit/phpunit/phpunit.xsd"
         bootstrap="vendor/autoload.php"
         colors="true"
>
    <testsuites>
        <testsuite name="Unit">
            <directory>tests/Unit</directory>
        </testsuite>
        <testsuite name="Feature">
            <directory>tests/Feature</directory>
        </testsuite>
    </testsuites>
    <source>
        <include>
            <directory>app</directory>
        </include>
    </source>
    <php>
        <env name="APP_ENV" value="testing"/>
        <env name="BCRYPT_ROUNDS" value="4"/>
        <env name="CACHE_DRIVER" value="array"/>
        <env name="DB_DATABASE" value="testing"/>
        <env name="MAIL_MAILER" value="array"/>
        <env name="QUEUE_CONNECTION" value="sync"/>
        <env name="SESSION_DRIVER" value="array"/>
    </php>
</phpunit>
```

### **Configuración de Base de Datos**

```bash
# En .env.testing (opcional)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cyh_testing
DB_USERNAME=root
DB_PASSWORD=
```

**Nota**: Laravel automáticamente usa la base de datos `testing` configurada en `phpunit.xml`.

## 📝 **Escribiendo Tests**

### **Estructura de un Test**

```php
<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Empresa;

class CalculadoraPreciosTest extends TestCase
{
    use RefreshDatabase; // Limpia la BD después de cada test

    private Empresa $empresa;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Preparar datos para todos los tests
        $this->empresa = Empresa::factory()->create([
            'estado' => 1,
            'trm' => 4000,
            'flete' => 5000,
        ]);
    }

    /** @test */
    public function puede_calcular_precio_nacional_correctamente()
    {
        // Arrange - Preparar datos
        $costoUnidad = 10000;
        $utilidad = 15;
        $cantidad = 5;

        // Act - Ejecutar la acción
        $valorUnidad = $costoUnidad + ($costoUnidad * $utilidad / 100);
        $valorTotal = $valorUnidad * $cantidad;

        // Assert - Verificar resultados
        $this->assertEquals(11500, $valorUnidad);
        $this->assertEquals(57500, $valorTotal);
    }
}
```

### **Convenciones de Nomenclatura**

#### **Nombres de Clases**
```php
// ✅ Correcto
class CalculadoraPreciosTest extends TestCase
class PedidoServiceTest extends TestCase
class UserControllerTest extends TestCase

// ❌ Incorrecto
class calculadoraPreciosTest extends TestCase
class pedido_service_test extends TestCase
```

#### **Nombres de Métodos**
```php
// ✅ Correcto
public function puede_calcular_precio_nacional_correctamente()
public function usuario_puede_crear_pedido()
public function pedido_requiere_campos_obligatorios()

// ❌ Incorrecto
public function testPuedeCalcularPrecioNacional()
public function usuarioPuedeCrearPedido()
public function test_pedido_requiere_campos_obligatorios()
```

#### **Anotaciones**
```php
/** @test */
public function nombre_del_test()
{
    // Test code
}

// O usar el prefijo 'test'
public function testNombreDelTest()
{
    // Test code
}
```

## 🧪 **Traits de Testing**

### **RefreshDatabase**
```php
use Illuminate\Foundation\Testing\RefreshDatabase;

class MiTest extends TestCase
{
    use RefreshDatabase;
    
    // Limpia la base de datos después de cada test
    // Útil para tests que modifican datos
}
```

### **WithFaker**
```php
use Illuminate\Foundation\Testing\WithFaker;

class MiTest extends TestCase
{
    use WithFaker;
    
    public function test_con_datos_fake()
    {
        $nombre = $this->faker->name();
        $email = $this->faker->email();
        $direccion = $this->faker->address();
    }
}
```

### **WithoutMiddleware**
```php
use Illuminate\Foundation\Testing\WithoutMiddleware;

class MiTest extends TestCase
{
    use WithoutMiddleware;
    
    // Deshabilita middleware para tests específicos
    // Útil para tests de controladores
}
```

## 🔧 **Factories y Seeders**

### **Crear Factory**

```php
<?php

namespace Tests\Database;

use App\Models\Pedido;
use Illuminate\Database\Eloquent\Factories\Factory;

class PedidoFactory extends Factory
{
    protected $model = Pedido::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'tercero_id' => Tercero::factory(),
            'direccion' => $this->faker->address(),
            'comentario' => $this->faker->optional()->sentence(),
            'estado' => $this->faker->randomElement(['Borrador', 'En Proceso', 'Completado']),
        ];
    }

    // Estados personalizados
    public function borrador(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => 'Borrador',
        ]);
    }

    public function enProceso(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => 'En Proceso',
        ]);
    }
}
```

### **Usar Factory en Tests**

```php
// Crear un pedido con datos por defecto
$pedido = Pedido::factory()->create();

// Crear múltiples pedidos
$pedidos = Pedido::factory()->count(5)->create();

// Crear pedido con estado específico
$pedido = Pedido::factory()->borrador()->create();

// Crear pedido con datos personalizados
$pedido = Pedido::factory()->create([
    'direccion' => 'Dirección específica',
    'estado' => 'Urgente',
]);

// Crear pedido sin persistir en BD
$pedido = Pedido::factory()->make();
```

### **Crear Seeder**

```php
<?php

namespace Tests\Database;

use Illuminate\Database\Seeder;
use App\Models\Empresa;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Crear empresa de testing
        Empresa::create([
            'nombre' => 'Empresa de Testing CYH',
            'estado' => 1,
            'trm' => 4000,
            'flete' => 5000,
        ]);

        // Crear usuarios de testing
        User::create([
            'name' => 'Admin Testing',
            'email' => 'admin@testing.com',
            'password' => Hash::make('password'),
        ]);
    }
}
```

## ✅ **Assertions Comunes**

### **Assertions de Igualdad**
```php
// Verificar valores exactos
$this->assertEquals(11500, $valorUnidad);
$this->assertSame(11500, $valorUnidad); // Estricto (tipo y valor)

// Verificar que no sean iguales
$this->assertNotEquals(10000, $valorUnidad);
$this->assertNotSame(10000, $valorUnidad);
```

### **Assertions de Booleanos**
```php
// Verificar valores booleanos
$this->assertTrue($esValido);
$this->assertFalse($esInvalido);
$this->assertNull($valorNulo);
$this->assertNotNull($valorNoNulo);
```

### **Assertions de Arrays y Colecciones**
```php
// Verificar conteo
$this->assertCount(3, $pedidos);
$this->assertEmpty($arrayVacio);
$this->assertNotEmpty($arrayConDatos);

// Verificar que contenga elementos
$this->assertContains('Borrador', $estados);
$this->assertNotContains('Eliminado', $estados);
```

### **Assertions de Base de Datos**
```php
// Verificar que exista en BD
$this->assertDatabaseHas('pedidos', [
    'estado' => 'Borrador',
    'user_id' => $user->id,
]);

// Verificar que no exista en BD
$this->assertDatabaseMissing('pedidos', [
    'estado' => 'Eliminado',
]);

// Verificar conteo en tabla
$this->assertDatabaseCount('pedidos', 5);
```

### **Assertions de Modelos**
```php
// Verificar instancia
$this->assertInstanceOf(Pedido::class, $pedido);
$this->assertInstanceOf(User::class, $pedido->user);

// Verificar relaciones
$this->assertCount(3, $pedido->referencias);
$this->assertTrue($pedido->user->exists);
```

## 🚨 **Manejo de Excepciones**

### **Verificar que se Lance Excepción**
```php
/** @test */
public function lanza_excepcion_con_datos_invalidos()
{
    // Arrange
    $datosInvalidos = ['nombre' => ''];

    // Act & Assert
    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessage('El nombre es obligatorio');
    
    $this->pedidoService->crearPedido($datosInvalidos);
}
```

### **Verificar Excepciones de Base de Datos**
```php
/** @test */
public function requiere_campos_obligatorios()
{
    // Arrange
    $pedidoData = [
        // Sin tercero_id (campo obligatorio)
        'direccion' => 'Dirección de prueba',
    ];

    // Act & Assert
    $this->expectException(\Illuminate\Database\QueryException::class);
    Pedido::create($pedidoData);
}
```

## 🔄 **Testing de Relaciones**

### **Verificar Relaciones Eloquent**
```php
/** @test */
public function pedido_tiene_relaciones_correctas()
{
    // Arrange
    $pedido = Pedido::factory()->create([
        'user_id' => $this->user->id,
        'tercero_id' => $this->cliente->id,
    ]);

    // Act & Assert
    $this->assertInstanceOf(User::class, $pedido->user);
    $this->assertInstanceOf(Tercero::class, $pedido->tercero);
    $this->assertEquals($this->user->id, $pedido->user->id);
    $this->assertEquals($this->cliente->id, $pedido->tercero->id);
}
```

### **Verificar Relaciones Múltiples**
```php
/** @test */
public function pedido_puede_tener_multiples_referencias()
{
    // Arrange
    $pedido = Pedido::factory()->create();
    $referencias = Referencia::factory()->count(3)->create();

    // Act
    $pedido->referencias()->attach($referencias->pluck('id'));

    // Assert
    $this->assertCount(3, $pedido->referencias);
    $this->assertTrue($pedido->referencias->contains($referencias->first()));
}
```

## 🧹 **Limpieza y Organización**

### **Método tearDown**
```php
protected function tearDown(): void
{
    // Limpiar recursos después de cada test
    // Útil para cerrar conexiones, limpiar archivos, etc.
    
    parent::tearDown();
}
```

### **Tests Aislados**
```php
/** @test */
public function test_aislado()
{
    // Este test no depende de otros
    // Cada test debe ser independiente
}
```

## 🚀 **Ejecutando Tests**

### **Comandos Básicos**
```bash
# Ejecutar todos los tests
php artisan test

# Ejecutar tests específicos
php artisan test tests/Unit/CalculadoraPreciosTest.php
php artisan test tests/Feature/PedidoTest.php

# Ejecutar tests con filtro
php artisan test --filter="puede_calcular_precio_nacional"

# Ejecutar tests con coverage (requiere Xdebug)
php artisan test --coverage

# Ejecutar tests en paralelo
php artisan test --parallel
```

### **Tests por Suite**
```bash
# Solo tests unitarios
php artisan test --testsuite=Unit

# Solo tests de integración
php artisan test --testsuite=Feature
```

### **Tests con Verbosidad**
```bash
# Mostrar más detalles
php artisan test -v

# Mostrar máximo detalle
php artisan test -vvv
```

## 📊 **Métricas de Testing**

### **Cobertura de Código**
```bash
# Generar reporte de cobertura
php artisan test --coverage --min=80

# Ver cobertura en navegador
php artisan test --coverage-html=coverage
```

### **Objetivos de Cobertura**
- **Tests Unitarios**: 90%+
- **Tests de Integración**: 80%+
- **Cobertura Total**: 85%+

## 🔍 **Debugging de Tests**

### **Dump y Debug**
```php
/** @test */
public function debug_test()
{
    // Dump variables para debugging
    dump($variable);
    
    // Pausar test para inspección
    dd($variable);
    
    // Log para debugging
    \Log::info('Debug info', ['variable' => $variable]);
}
```

### **Tests Interactivos**
```bash
# Ejecutar test específico con más detalle
php artisan test tests/Unit/CalculadoraPreciosTest.php --verbose
```

## 📚 **Ejemplos de Tests**

### **Test de Cálculo de Precios**
```php
/** @test */
public function calcula_precio_internacional_con_redondeo()
{
    // Arrange
    $costoUSD = 25;
    $utilidad = 12;
    $cantidad = 3;
    $peso = 2.5;
    $trm = 4000;
    $flete = 5000;

    // Act
    $costoCOP = $costoUSD * $trm;
    $valorBase = $costoCOP + ($peso * 2.2 * $flete);
    $valorUnidad = $valorBase + ($utilidad * $valorBase / 100);
    $valorUnidad = round($valorUnidad, -2);
    $valorTotal = $valorUnidad * $cantidad;

    // Assert
    $this->assertEquals(100000, $costoCOP);
    $this->assertEquals(127500, $valorBase);
    $this->assertEquals(142800, $valorUnidad);
    $this->assertEquals(428400, $valorTotal);
}
```

### **Test de Validación de Modelo**
```php
/** @test */
public function valida_campos_requeridos()
{
    // Arrange
    $pedido = new Pedido();

    // Act
    $esValido = $pedido->validate([
        'tercero_id' => 'required',
        'direccion' => 'required|string|max:500',
    ]);

    // Assert
    $this->assertFalse($esValido);
    $this->assertArrayHasKey('tercero_id', $pedido->errors());
    $this->assertArrayHasKey('direccion', $pedido->errors());
}
```

## 🎯 **Mejores Prácticas**

### **1. Nombres Descriptivos**
```php
// ✅ Correcto
public function usuario_puede_crear_pedido_con_datos_validos()
public function sistema_calcula_precio_correctamente_con_utilidad_cero()

// ❌ Incorrecto
public function test1()
public function testCrearPedido()
```

### **2. Tests Independientes**
```php
// ✅ Correcto - Cada test es independiente
public function test_a() { /* ... */ }
public function test_b() { /* ... */ }

// ❌ Incorrecto - Tests dependen entre sí
public function test_a() { $this->variable = 'valor'; }
public function test_b() { $this->assertEquals('valor', $this->variable); }
```

### **3. Arrange-Act-Assert**
```php
/** @test */
public function ejemplo_aaa()
{
    // Arrange - Preparar datos
    $costoUnidad = 10000;
    $utilidad = 15;
    
    // Act - Ejecutar acción
    $resultado = $this->calculadora->calcularPrecio($costoUnidad, $utilidad);
    
    // Assert - Verificar resultado
    $this->assertEquals(11500, $resultado);
}
```

### **4. Tests Pequeños y Enfocados**
```php
// ✅ Correcto - Un test, una funcionalidad
public function calcula_precio_nacional()
public function calcula_precio_internacional()
public function redondea_a_centenas()

// ❌ Incorrecto - Muchas funcionalidades en un test
public function calcula_todos_los_precios()
```

## 🚨 **Anti-patrones Comunes**

### **1. Tests Frágiles**
```php
// ❌ Incorrecto - Depende de datos externos
public function test_con_fecha_actual()
{
    $fecha = now();
    $this->assertEquals($fecha, $pedido->fecha_creacion);
}

// ✅ Correcto - Usar mocks o datos fijos
public function test_con_fecha_especifica()
{
    $fecha = '2025-01-15';
    $pedido = Pedido::factory()->create(['fecha_creacion' => $fecha]);
    $this->assertEquals($fecha, $pedido->fecha_creacion);
}
```

### **2. Tests Lentos**
```php
// ❌ Incorrecto - Hace llamadas HTTP reales
public function test_api_externa()
{
    $response = Http::get('https://api.externa.com/datos');
    $this->assertEquals(200, $response->status());
}

// ✅ Correcto - Usar mocks
public function test_api_externa()
{
    Http::fake([
        'https://api.externa.com/datos' => Http::response(['data' => 'test'], 200)
    ]);
    
    $response = Http::get('https://api.externa.com/datos');
    $this->assertEquals(200, $response->status());
}
```

## 📈 **Integración Continua**

### **GitHub Actions**
```yaml
# .github/workflows/test.yml
name: Tests

on: [push, pull_request]

jobs:
  test:
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
```

### **Pre-commit Hooks**
```bash
#!/bin/bash
# .git/hooks/pre-commit

echo "🧪 Ejecutando tests antes del commit..."

if ! php artisan test; then
    echo "❌ Tests fallaron. Commit cancelado."
    exit 1
fi

echo "✅ Tests pasaron. Commit permitido."
exit 0
```

## 🎉 **Conclusión**

Implementar testing en el Sistema CYH es fundamental para:

1. **Asegurar Calidad**: Código probado y validado
2. **Prevenir Regresiones**: Cambios seguros sin romper funcionalidades
3. **Facilitar Mantenimiento**: Refactoring seguro
4. **Documentar Comportamiento**: Tests como documentación viva
5. **Mejorar Confianza**: Equipo seguro al hacer cambios

### **Próximos Pasos**
1. **Ejecutar Tests Existentes**: Verificar que funcionen correctamente
2. **Aumentar Cobertura**: Agregar tests para funcionalidades no cubiertas
3. **Automatizar**: Configurar CI/CD con GitHub Actions
4. **Capacitar Equipo**: Entrenar en mejores prácticas de testing

---

**Recuerda**: Los tests son una inversión que se paga con el tiempo. Un sistema bien testeado es más mantenible, confiable y escalable. 🚀
