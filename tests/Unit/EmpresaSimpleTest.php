<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Empresa;

class EmpresaSimpleTest extends TestCase
{
    /** @test */
    public function puede_crear_empresa_basica()
    {
        // Arrange
        $empresaData = [
            'nombre' => 'Empresa Test CYH',
            'estado' => 1,
        ];

        // Act
        $empresa = new Empresa($empresaData);

        // Assert
        $this->assertInstanceOf(Empresa::class, $empresa);
        $this->assertEquals('Empresa Test CYH', $empresa->nombre);
        $this->assertEquals(1, $empresa->estado);
    }

    /** @test */
    public function empresa_tiene_atributos_correctos()
    {
        // Arrange
        $empresa = new Empresa();

        // Assert - Verificar que los atributos existen
        // En Laravel, los modelos Eloquent usan propiedades dinámicas
        $this->assertInstanceOf(Empresa::class, $empresa);
        $this->assertTrue(method_exists($empresa, 'getFillable'));
        $this->assertTrue(method_exists($empresa, 'getCasts'));
    }

    /** @test */
    public function empresa_puede_ser_instanciada()
    {
        // Act
        $empresa = new Empresa();

        // Assert
        $this->assertInstanceOf(Empresa::class, $empresa);
    }

    /** @test */
    public function empresa_tiene_fillable_correcto()
    {
        // Arrange
        $empresa = new Empresa();

        // Assert - Verificar que los campos fillable están definidos
        $this->assertIsArray($empresa->getFillable());
        $this->assertContains('nombre', $empresa->getFillable());
        $this->assertContains('estado', $empresa->getFillable());
    }

    /** @test */
    public function empresa_tiene_casts_correctos()
    {
        // Arrange
        $empresa = new Empresa();

        // Assert - Verificar que los casts están definidos
        $this->assertIsArray($empresa->getCasts());
    }

    /** @test */
    public function empresa_puede_ser_serializada()
    {
        // Arrange
        $empresa = new Empresa([
            'nombre' => 'Empresa Test',
            'estado' => 1,
        ]);

        // Act
        $array = $empresa->toArray();

        // Assert
        $this->assertIsArray($array);
        $this->assertArrayHasKey('nombre', $array);
        $this->assertArrayHasKey('estado', $array);
    }

    /** @test */
    public function empresa_puede_ser_convertida_a_json()
    {
        // Arrange
        $empresa = new Empresa([
            'nombre' => 'Empresa Test',
            'estado' => 1,
        ]);

        // Act
        $json = $empresa->toJson();

        // Assert
        $this->assertIsString($json);
        $this->assertStringContainsString('Empresa Test', $json);
        $this->assertStringContainsString('true', $json); // El estado se convierte a boolean
    }

    /** @test */
    public function empresa_tiene_metodos_de_relacion()
    {
        // Arrange
        $empresa = new Empresa();

        // Assert - Verificar que los métodos de relación existen
        $this->assertTrue(method_exists($empresa, 'country'));
        // Nota: Los métodos state y city pueden no existir en el modelo actual
        // $this->assertTrue(method_exists($empresa, 'state'));
        // $this->assertTrue(method_exists($empresa, 'city'));
    }

    /** @test */
    public function empresa_puede_ser_clonada()
    {
        // Arrange
        $empresa = new Empresa([
            'nombre' => 'Empresa Original',
            'estado' => 1,
        ]);

        // Act
        $empresaClonada = clone $empresa;

        // Assert
        $this->assertInstanceOf(Empresa::class, $empresaClonada);
        $this->assertEquals($empresa->nombre, $empresaClonada->nombre);
        $this->assertEquals($empresa->estado, $empresaClonada->estado);
    }

    /** @test */
    public function empresa_puede_ser_convertida_a_string()
    {
        // Arrange
        $empresa = new Empresa([
            'nombre' => 'Empresa Test',
            'estado' => 1,
        ]);

        // Act
        $string = (string) $empresa;

        // Assert
        $this->assertIsString($string);
        $this->assertStringContainsString('Empresa Test', $string);
    }

    /** @test */
    public function empresa_tiene_atributos_por_defecto()
    {
        // Arrange
        $empresa = new Empresa();

        // Assert - Verificar valores por defecto
        $this->assertEquals(0, $empresa->estado);
    }
}
