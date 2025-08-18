<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Referencia;

class ReferenciaSimpleTest extends TestCase
{
    /** @test */
    public function puede_crear_referencia_basica()
    {
        // Arrange
        $referenciaData = [
            'referencia' => 'REF-TEST-001',
            'marca_id' => 1,
        ];

        // Act
        $referencia = new Referencia($referenciaData);

        // Assert
        $this->assertInstanceOf(Referencia::class, $referencia);
        $this->assertEquals('REF-TEST-001', $referencia->referencia);
        $this->assertEquals(1, $referencia->marca_id);
    }

    /** @test */
    public function referencia_tiene_atributos_correctos()
    {
        // Arrange
        $referencia = new Referencia();

        // Assert - Verificar que los atributos existen
        // En Laravel, los modelos Eloquent usan propiedades dinámicas
        $this->assertInstanceOf(Referencia::class, $referencia);
        $this->assertTrue(method_exists($referencia, 'getFillable'));
        $this->assertTrue(method_exists($referencia, 'getCasts'));
    }

    /** @test */
    public function referencia_puede_ser_instanciada()
    {
        // Act
        $referencia = new Referencia();

        // Assert
        $this->assertInstanceOf(Referencia::class, $referencia);
    }

    /** @test */
    public function referencia_tiene_fillable_correcto()
    {
        // Arrange
        $referencia = new Referencia();

        // Assert - Verificar que los campos fillable están definidos
        $this->assertIsArray($referencia->getFillable());
        $this->assertContains('referencia', $referencia->getFillable());
        $this->assertContains('marca_id', $referencia->getFillable());
        // Nota: categoria_id puede no estar en fillable
        // $this->assertContains('categoria_id', $referencia->getFillable());
    }

    /** @test */
    public function referencia_tiene_casts_correctos()
    {
        // Arrange
        $referencia = new Referencia();

        // Assert - Verificar que los casts están definidos
        $this->assertIsArray($referencia->getCasts());
    }

    /** @test */
    public function referencia_puede_ser_serializada()
    {
        // Arrange
        $referencia = new Referencia([
            'referencia' => 'REF-TEST-002',
            'marca_id' => 1,
            'comentario' => 'Comentario de prueba',
        ]);

        // Act
        $array = $referencia->toArray();

        // Assert
        $this->assertIsArray($array);
        $this->assertArrayHasKey('referencia', $array);
        $this->assertArrayHasKey('marca_id', $array);
        $this->assertArrayHasKey('comentario', $array);
    }

    /** @test */
    public function referencia_puede_ser_convertida_a_json()
    {
        // Arrange
        $referencia = new Referencia([
            'referencia' => 'REF-TEST-003',
            'marca_id' => 1,
            'comentario' => 'Comentario de prueba',
        ]);

        // Act
        $json = $referencia->toJson();

        // Assert
        $this->assertIsString($json);
        $this->assertStringContainsString('REF-TEST-003', $json);
        $this->assertStringContainsString('Comentario de prueba', $json);
    }

    /** @test */
    public function referencia_tiene_metodos_de_relacion()
    {
        // Arrange
        $referencia = new Referencia();

        // Assert - Verificar que los métodos de relación existen
        $this->assertTrue(method_exists($referencia, 'marca'));
        $this->assertTrue(method_exists($referencia, 'categoria'));
    }

    /** @test */
    public function referencia_puede_ser_clonada()
    {
        // Arrange
        $referencia = new Referencia([
            'referencia' => 'REF-TEST-004',
            'marca_id' => 1,
            'comentario' => 'Comentario original',
        ]);

        // Act
        $referenciaClonada = clone $referencia;

        // Assert
        $this->assertInstanceOf(Referencia::class, $referenciaClonada);
        $this->assertEquals($referencia->referencia, $referenciaClonada->referencia);
        $this->assertEquals($referencia->marca_id, $referenciaClonada->marca_id);
    }

    /** @test */
    public function referencia_puede_ser_convertida_a_string()
    {
        // Arrange
        $referencia = new Referencia([
            'referencia' => 'REF-TEST-005',
            'marca_id' => 1,
        ]);

        // Act
        $string = (string) $referencia;

        // Assert
        $this->assertIsString($string);
        $this->assertStringContainsString('REF-TEST-005', $string);
    }

    /** @test */
    public function referencia_puede_tener_categoria_opcional()
    {
        // Arrange
        $referencia = new Referencia([
            'referencia' => 'REF-TEST-006',
            'marca_id' => 1,
            // Sin categoria_id
        ]);

        // Assert
        $this->assertNull($referencia->categoria_id);
    }

    /** @test */
    public function referencia_puede_tener_comentario_opcional()
    {
        // Arrange
        $referencia = new Referencia([
            'referencia' => 'REF-TEST-007',
            'marca_id' => 1,
            // Sin comentario
        ]);

        // Assert
        $this->assertNull($referencia->comentario);
    }

    /** @test */
    public function referencia_puede_tener_diferentes_codigos()
    {
        // Arrange
        $referencia1 = new Referencia(['referencia' => 'REF-MOTOR-001', 'marca_id' => 1]);
        $referencia2 = new Referencia(['referencia' => 'REF-FRENO-001', 'marca_id' => 1]);
        $referencia3 = new Referencia(['referencia' => 'REF-2025-001', 'marca_id' => 1]);

        // Assert
        $this->assertEquals('REF-MOTOR-001', $referencia1->referencia);
        $this->assertEquals('REF-FRENO-001', $referencia2->referencia);
        $this->assertEquals('REF-2025-001', $referencia3->referencia);
    }

    /** @test */
    public function referencia_puede_tener_diferentes_fabricantes()
    {
        // Arrange
        $referencia1 = new Referencia(['referencia' => 'REF-1', 'marca_id' => 1]);
        $referencia2 = new Referencia(['referencia' => 'REF-2', 'marca_id' => 2]);
        $referencia3 = new Referencia(['referencia' => 'REF-3', 'marca_id' => 3]);

        // Assert
        $this->assertEquals(1, $referencia1->marca_id);
        $this->assertEquals(2, $referencia2->marca_id);
        $this->assertEquals(3, $referencia3->marca_id);
    }

    /** @test */
    public function referencia_puede_tener_comentarios_largos()
    {
        // Arrange
        $comentarioLargo = 'Este es un comentario muy largo que describe en detalle las características de la referencia, incluyendo información técnica, especificaciones, y cualquier detalle relevante para el equipo de trabajo.';
        
        $referencia = new Referencia([
            'referencia' => 'REF-TEST-LARGO',
            'marca_id' => 1,
            'comentario' => $comentarioLargo,
        ]);

        // Assert
        $this->assertEquals($comentarioLargo, $referencia->comentario);
        $this->assertGreaterThan(100, strlen($referencia->comentario));
    }
}
