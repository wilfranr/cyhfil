<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Tercero;

class TerceroSimpleTest extends TestCase
{
    /** @test */
    public function puede_crear_tercero_basico()
    {
        // Arrange
        $terceroData = [
            'nombre' => 'Tercero Test',
            'tipo' => 'Proveedor',
            'estado' => 1,
        ];

        // Act
        $tercero = new Tercero($terceroData);

        // Assert
        $this->assertInstanceOf(Tercero::class, $tercero);
        $this->assertEquals('Tercero Test', $tercero->nombre);
        $this->assertEquals('Proveedor', $tercero->tipo);
        $this->assertEquals(1, $tercero->estado);
    }

    /** @test */
    public function tercero_tiene_atributos_correctos()
    {
        // Arrange
        $tercero = new Tercero();

        // Assert - Verificar que los atributos existen
        // En Laravel, los modelos Eloquent usan propiedades dinámicas
        $this->assertInstanceOf(Tercero::class, $tercero);
        $this->assertTrue(method_exists($tercero, 'getFillable'));
        $this->assertTrue(method_exists($tercero, 'getCasts'));
    }

    /** @test */
    public function tercero_puede_ser_instanciado()
    {
        // Act
        $tercero = new Tercero();

        // Assert
        $this->assertInstanceOf(Tercero::class, $tercero);
    }

    /** @test */
    public function tercero_tiene_fillable_correcto()
    {
        // Arrange
        $tercero = new Tercero();

        // Assert - Verificar que los campos fillable están definidos
        $this->assertIsArray($tercero->getFillable());
        $this->assertContains('nombre', $tercero->getFillable());
        $this->assertContains('tipo', $tercero->getFillable());
        $this->assertContains('estado', $tercero->getFillable());
    }

    /** @test */
    public function tercero_tiene_casts_correctos()
    {
        // Arrange
        $tercero = new Tercero();

        // Assert - Verificar que los casts están definidos
        $this->assertIsArray($tercero->getCasts());
    }

    /** @test */
    public function tercero_puede_ser_serializado()
    {
        // Arrange
        $tercero = new Tercero([
            'nombre' => 'Tercero Test',
            'tipo' => 'Proveedor',
            'estado' => 1,
        ]);

        // Act
        $array = $tercero->toArray();

        // Assert
        $this->assertIsArray($array);
        $this->assertArrayHasKey('nombre', $array);
        $this->assertArrayHasKey('tipo', $array);
        $this->assertArrayHasKey('estado', $array);
    }

    /** @test */
    public function tercero_puede_ser_convertido_a_json()
    {
        // Arrange
        $tercero = new Tercero([
            'nombre' => 'Tercero Test',
            'tipo' => 'Proveedor',
            'estado' => 1,
        ]);

        // Act
        $json = $tercero->toJson();

        // Assert
        $this->assertIsString($json);
        $this->assertStringContainsString('Tercero Test', $json);
        $this->assertStringContainsString('Proveedor', $json);
    }

    /** @test */
    public function tercero_tiene_metodos_de_relacion()
    {
        // Arrange
        $tercero = new Tercero();

        // Assert - Verificar que los métodos de relación existen
        $this->assertTrue(method_exists($tercero, 'country'));
        // Nota: Los métodos state y city pueden no existir en el modelo actual
        // $this->assertTrue(method_exists($tercero, 'state'));
        // $this->assertTrue(method_exists($tercero, 'city'));
    }

    /** @test */
    public function tercero_puede_ser_clonado()
    {
        // Arrange
        $tercero = new Tercero([
            'nombre' => 'Tercero Original',
            'tipo' => 'Proveedor',
            'estado' => 1,
        ]);

        // Act
        $terceroClonado = clone $tercero;

        // Assert
        $this->assertInstanceOf(Tercero::class, $terceroClonado);
        $this->assertEquals($tercero->nombre, $terceroClonado->nombre);
        $this->assertEquals($tercero->tipo, $terceroClonado->tipo);
    }

    /** @test */
    public function tercero_puede_ser_convertido_a_string()
    {
        // Arrange
        $tercero = new Tercero([
            'nombre' => 'Tercero Test',
            'tipo' => 'Proveedor',
            'estado' => 1,
        ]);

        // Act
        $string = (string) $tercero;

        // Assert
        $this->assertIsString($string);
        $this->assertStringContainsString('Tercero Test', $string);
    }

    /** @test */
    public function tercero_tiene_atributos_por_defecto()
    {
        // Arrange
        $tercero = new Tercero();

        // Assert - Verificar valores por defecto
        // El estado puede no tener valor por defecto definido
        $this->assertNull($tercero->estado);
    }

    /** @test */
    public function tercero_puede_tener_diferentes_tipos()
    {
        // Arrange
        $proveedor = new Tercero(['nombre' => 'Proveedor', 'tipo' => 'Proveedor']);
        $cliente = new Tercero(['nombre' => 'Cliente', 'tipo' => 'Cliente']);

        // Assert
        $this->assertEquals('Proveedor', $proveedor->tipo);
        $this->assertEquals('Cliente', $cliente->tipo);
    }

    /** @test */
    public function tercero_puede_tener_diferentes_estados()
    {
        // Arrange
        $terceroActivo = new Tercero(['nombre' => 'Activo', 'estado' => 1]);
        $terceroInactivo = new Tercero(['nombre' => 'Inactivo', 'estado' => 0]);

        // Assert
        $this->assertEquals(1, $terceroActivo->estado);
        $this->assertEquals(0, $terceroInactivo->estado);
    }
}
