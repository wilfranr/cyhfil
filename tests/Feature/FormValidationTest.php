<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Empresa;
use App\Models\User;
use App\Models\Tercero;
use App\Models\Referencia;
use App\Models\Fabricante;
use App\Models\Categoria;
use Illuminate\Foundation\Testing\WithFaker;

class FormValidationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private Empresa $empresa;
    private User $user;
    private Tercero $proveedor;
    private Tercero $cliente;
    private Referencia $referencia;
    private Fabricante $fabricante;
    private Categoria $categoria;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Crear empresa de testing
        $this->empresa = Empresa::create([
            'nombre' => 'Empresa Test CYH',
            'estado' => 1,
            'trm' => 4000,
            'flete' => 5000,
        ]);

        // Crear usuario de testing
        $this->user = User::create([
            'name' => 'Usuario Test',
            'email' => 'usuario@test.com',
            'password' => bcrypt('password'),
        ]);

        // Crear proveedor de testing
        $this->proveedor = Tercero::create([
            'nombre' => 'Proveedor Test',
            'tipo_documento' => 'NIT',
            'numero_documento' => '800123456-7',
            'tipo' => 'Proveedor',
            'estado' => 1,
        ]);

        // Crear cliente de testing
        $this->cliente = Tercero::create([
            'nombre' => 'Cliente Test',
            'tipo_documento' => 'CC',
            'numero_documento' => '12345678',
            'tipo' => 'Cliente',
            'estado' => 1,
        ]);

        // Crear fabricante de testing
        $this->fabricante = Fabricante::create([
            'nombre' => 'Fabricante Test',
            'descripcion' => 'Descripción del fabricante test',
        ]);

        // Crear categoría de testing
        $this->categoria = Categoria::create([
            'nombre' => 'Categoría Test',
            'descripcion' => 'Descripción de la categoría test',
        ]);

        // Crear referencia de testing
        $this->referencia = Referencia::create([
            'referencia' => 'REF-TEST-001',
            'marca_id' => $this->fabricante->id,
            'categoria_id' => $this->categoria->id,
        ]);
    }

    /** @test */
    public function empresa_requiere_campos_obligatorios()
    {
        // Arrange
        $empresaData = [
            // Sin nombre (campo obligatorio)
            'direccion' => $this->faker->address,
            'estado' => 1,
        ];

        // Act & Assert
        $this->expectException(\Illuminate\Database\QueryException::class);
        Empresa::create($empresaData);
    }

    /** @test */
    public function empresa_puede_tener_campos_opcionales_vacios()
    {
        // Arrange
        $empresaData = [
            'nombre' => 'Empresa Test',
            'estado' => 1,
            // Sin direccion, telefono, email, etc.
        ];

        // Act
        $empresa = Empresa::create($empresaData);

        // Assert
        $this->assertInstanceOf(Empresa::class, $empresa);
        $this->assertEquals('Empresa Test', $empresa->nombre);
        $this->assertNull($empresa->direccion);
        $this->assertNull($empresa->telefono);
        $this->assertNull($empresa->email);
    }

    /** @test */
    public function tercero_requiere_campos_obligatorios()
    {
        // Arrange
        $terceroData = [
            // Sin nombre (campo obligatorio)
            'tipo' => 'Proveedor',
            'estado' => 1,
        ];

        // Act & Assert
        $this->expectException(\Illuminate\Database\QueryException::class);
        Tercero::create($terceroData);
    }

    /** @test */
    public function tercero_puede_tener_campos_opcionales_vacios()
    {
        // Arrange
        $terceroData = [
            'nombre' => 'Tercero Test',
            'tipo' => 'Proveedor',
            'estado' => 1,
            // Sin tipo_documento, numero_documento, etc.
        ];

        // Act
        $tercero = Tercero::create($terceroData);

        // Assert
        $this->assertInstanceOf(Tercero::class, $tercero);
        $this->assertEquals('Tercero Test', $tercero->nombre);
        $this->assertNull($tercero->tipo_documento);
        $this->assertNull($tercero->numero_documento);
        $this->assertNull($tercero->direccion);
    }

    /** @test */
    public function referencia_requiere_campos_obligatorios()
    {
        // Arrange
        $referenciaData = [
            // Sin referencia (campo obligatorio)
            'marca_id' => $this->fabricante->id,
        ];

        // Act & Assert
        $this->expectException(\Illuminate\Database\QueryException::class);
        Referencia::create($referenciaData);
    }

    /** @test */
    public function referencia_requiere_fabricante()
    {
        // Arrange
        $referenciaData = [
            'referencia' => 'REF-TEST-002',
            // Sin marca_id (campo obligatorio)
        ];

        // Act & Assert
        $this->expectException(\Illuminate\Database\QueryException::class);
        Referencia::create($referenciaData);
    }

    /** @test */
    public function referencia_puede_tener_categoria_opcional()
    {
        // Arrange
        $referenciaData = [
            'referencia' => 'REF-TEST-003',
            'marca_id' => $this->fabricante->id,
            // Sin categoria_id (campo opcional)
        ];

        // Act
        $referencia = Referencia::create($referenciaData);

        // Assert
        $this->assertInstanceOf(Referencia::class, $referencia);
        $this->assertEquals('REF-TEST-003', $referencia->referencia);
        $this->assertNull($referencia->categoria_id);
    }

    /** @test */
    public function usuario_requiere_campos_obligatorios()
    {
        // Arrange
        $userData = [
            // Sin name (campo obligatorio)
            'email' => 'test@example.com',
            'password' => 'password',
        ];

        // Act & Assert
        $this->expectException(\Illuminate\Database\QueryException::class);
        User::create($userData);
    }

    /** @test */
    public function usuario_puede_tener_campos_opcionales_vacios()
    {
        // Arrange
        $userData = [
            'name' => 'Usuario Test',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            // Sin email_verified_at, remember_token, etc.
        ];

        // Act
        $user = User::create($userData);

        // Assert
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('Usuario Test', $user->name);
        $this->assertNull($user->email_verified_at);
        $this->assertNull($user->remember_token);
    }

    /** @test */
    public function fabricante_requiere_campos_obligatorios()
    {
        // Arrange
        $fabricanteData = [
            // Sin nombre (campo obligatorio)
            'descripcion' => 'Descripción del fabricante',
        ];

        // Act & Assert
        $this->expectException(\Illuminate\Database\QueryException::class);
        Fabricante::create($fabricanteData);
    }

    /** @test */
    public function fabricante_puede_tener_descripcion_opcional()
    {
        // Arrange
        $fabricanteData = [
            'nombre' => 'Fabricante Test',
            // Sin descripcion (campo opcional)
        ];

        // Act
        $fabricante = Fabricante::create($fabricanteData);

        // Assert
        $this->assertInstanceOf(Fabricante::class, $fabricante);
        $this->assertEquals('Fabricante Test', $fabricante->nombre);
        $this->assertNull($fabricante->descripcion);
    }

    /** @test */
    public function categoria_requiere_campos_obligatorios()
    {
        // Arrange
        $categoriaData = [
            // Sin nombre (campo obligatorio)
            'descripcion' => 'Descripción de la categoría',
        ];

        // Act & Assert
        $this->expectException(\Illuminate\Database\QueryException::class);
        Categoria::create($categoriaData);
    }

    /** @test */
    public function categoria_puede_tener_descripcion_opcional()
    {
        // Arrange
        $categoriaData = [
            'nombre' => 'Categoría Test',
            // Sin descripcion (campo opcional)
        ];

        // Act
        $categoria = Categoria::create($categoriaData);

        // Assert
        $this->assertInstanceOf(Categoria::class, $categoria);
        $this->assertEquals('Categoría Test', $categoria->nombre);
        $this->assertNull($categoria->descripcion);
    }

    /** @test */
    public function validacion_de_longitud_de_campos()
    {
        // Arrange - Nombre muy largo
        $nombreMuyLargo = str_repeat('A', 256); // Más de 255 caracteres
        
        $empresaData = [
            'nombre' => $nombreMuyLargo,
            'estado' => 1,
        ];

        // Act & Assert
        $this->expectException(\Illuminate\Database\QueryException::class);
        Empresa::create($empresaData);
    }

    /** @test */
    public function validacion_de_tipos_de_datos()
    {
        // Arrange - Estado como string en lugar de integer
        $empresaData = [
            'nombre' => 'Empresa Test',
            'estado' => 'activo', // Debería ser 1 o 0
        ];

        // Act & Assert
        $this->expectException(\Illuminate\Database\QueryException::class);
        Empresa::create($empresaData);
    }

    /** @test */
    public function validacion_de_foreign_keys()
    {
        // Arrange - Referencia con fabricante inexistente
        $referenciaData = [
            'referencia' => 'REF-TEST-004',
            'marca_id' => 99999, // ID que no existe
        ];

        // Act & Assert
        $this->expectException(\Illuminate\Database\QueryException::class);
        Referencia::create($referenciaData);
    }

    /** @test */
    public function validacion_de_campos_unicos()
    {
        // Arrange - Crear primera empresa
        Empresa::create([
            'nombre' => 'Empresa Test',
            'estado' => 1,
        ]);

        // Act & Assert - Intentar crear segunda empresa con mismo nombre
        $this->expectException(\Illuminate\Database\QueryException::class);
        Empresa::create([
            'nombre' => 'Empresa Test', // Mismo nombre
            'estado' => 1,
        ]);
    }

    /** @test */
    public function validacion_de_campos_email()
    {
        // Arrange - Email inválido
        $terceroData = [
            'nombre' => 'Tercero Test',
            'tipo' => 'Proveedor',
            'estado' => 1,
            'email' => 'email-invalido', // Email sin formato válido
        ];

        // Act
        $tercero = Tercero::create($terceroData);

        // Assert - Laravel no valida formato de email por defecto
        $this->assertInstanceOf(Tercero::class, $tercero);
        $this->assertEquals('email-invalido', $tercero->email);
    }

    /** @test */
    public function validacion_de_campos_numericos()
    {
        // Arrange - TRM como string
        $empresaData = [
            'nombre' => 'Empresa Test',
            'estado' => 1,
            'trm' => '4000', // String en lugar de número
        ];

        // Act
        $empresa = Empresa::create($empresaData);

        // Assert - Laravel convierte automáticamente
        $this->assertInstanceOf(Empresa::class, $empresa);
        $this->assertEquals(4000, $empresa->trm);
    }

    /** @test */
    public function validacion_de_campos_decimales()
    {
        // Arrange - Flete con decimales
        $empresaData = [
            'nombre' => 'Empresa Test',
            'estado' => 1,
            'flete' => 5000.50, // Decimal válido
        ];

        // Act
        $empresa = Empresa::create($empresaData);

        // Assert
        $this->assertInstanceOf(Empresa::class, $empresa);
        $this->assertEquals(5000.50, $empresa->flete);
    }

    /** @test */
    public function validacion_de_campos_booleanos()
    {
        // Arrange - Estado como boolean
        $empresaData = [
            'nombre' => 'Empresa Test',
            'estado' => true, // Boolean en lugar de integer
        ];

        // Act
        $empresa = Empresa::create($empresaData);

        // Assert - Laravel convierte automáticamente
        $this->assertInstanceOf(Empresa::class, $empresa);
        $this->assertEquals(1, $empresa->estado);
    }

    /** @test */
    public function validacion_de_campos_nullables()
    {
        // Arrange - Campos nullables
        $terceroData = [
            'nombre' => 'Tercero Test',
            'tipo' => 'Proveedor',
            'estado' => 1,
            'telefono' => null,
            'email' => null,
            'direccion' => null,
        ];

        // Act
        $tercero = Tercero::create($terceroData);

        // Assert
        $this->assertInstanceOf(Tercero::class, $tercero);
        $this->assertNull($tercero->telefono);
        $this->assertNull($tercero->email);
        $this->assertNull($tercero->direccion);
    }

    /** @test */
    public function validacion_de_campos_con_valores_por_defecto()
    {
        // Arrange - Sin especificar estado
        $empresaData = [
            'nombre' => 'Empresa Test',
            // Sin estado (debería usar valor por defecto)
        ];

        // Act
        $empresa = Empresa::create($empresaData);

        // Assert
        $this->assertInstanceOf(Empresa::class, $empresa);
        $this->assertEquals(0, $empresa->estado); // Valor por defecto
    }
}
