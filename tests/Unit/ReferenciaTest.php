<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Referencia;
use App\Models\Fabricante;
use App\Models\Categoria;

class ReferenciaTest extends TestCase
{
    use RefreshDatabase;

    private Fabricante $fabricante;
    private Categoria $categoria;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Crear datos de fabricante y categoría para testing
        $this->fabricante = Fabricante::create([
            'nombre' => 'Fabricante Test',
            'descripcion' => 'Descripción del fabricante test',
        ]);
        
        $this->categoria = Categoria::create([
            'nombre' => 'Categoría Test',
            'descripcion' => 'Descripción de la categoría test',
        ]);
    }

    /** @test */
    public function puede_crear_referencia_con_datos_completos()
    {
        // Arrange
        $referenciaData = [
            'referencia' => 'REF-TEST-001',
            'marca_id' => $this->fabricante->id,
            'categoria_id' => $this->categoria->id,
            'comentario' => 'Referencia de testing para repuestos',
        ];

        // Act
        $referencia = Referencia::create($referenciaData);

        // Assert
        $this->assertInstanceOf(Referencia::class, $referencia);
        $this->assertEquals('REF-TEST-001', $referencia->referencia);
        $this->assertEquals($this->fabricante->id, $referencia->marca_id);
        $this->assertEquals($this->categoria->id, $referencia->categoria_id);
        $this->assertEquals('Referencia de testing para repuestos', $referencia->comentario);
    }

    /** @test */
    public function puede_crear_referencia_sin_categoria()
    {
        // Arrange
        $referenciaData = [
            'referencia' => 'REF-TEST-002',
            'marca_id' => $this->fabricante->id,
            'comentario' => 'Referencia sin categoría',
        ];

        // Act
        $referencia = Referencia::create($referenciaData);

        // Assert
        $this->assertInstanceOf(Referencia::class, $referencia);
        $this->assertEquals('REF-TEST-002', $referencia->referencia);
        $this->assertNull($referencia->categoria_id);
    }

    /** @test */
    public function referencia_tiene_relaciones_correctas()
    {
        // Arrange
        $referencia = Referencia::create([
            'referencia' => 'REF-TEST-003',
            'marca_id' => $this->fabricante->id,
            'categoria_id' => $this->categoria->id,
        ]);

        // Act & Assert
        $this->assertInstanceOf(Fabricante::class, $referencia->marca);
        $this->assertInstanceOf(Categoria::class, $referencia->categoria);
        
        $this->assertEquals('Fabricante Test', $referencia->marca->nombre);
        $this->assertEquals('Categoría Test', $referencia->categoria->nombre);
    }

    /** @test */
    public function referencia_puede_ser_actualizada()
    {
        // Arrange
        $referencia = Referencia::create([
            'referencia' => 'REF-TEST-004',
            'marca_id' => $this->fabricante->id,
            'comentario' => 'Comentario original',
        ]);

        // Act
        $referencia->update([
            'referencia' => 'REF-TEST-004-UPDATED',
            'comentario' => 'Comentario actualizado',
        ]);

        // Assert
        $this->assertEquals('REF-TEST-004-UPDATED', $referencia->fresh()->referencia);
        $this->assertEquals('Comentario actualizado', $referencia->fresh()->comentario);
    }

    /** @test */
    public function referencia_puede_ser_eliminada()
    {
        // Arrange
        $referencia = Referencia::create([
            'referencia' => 'REF-TEST-005',
            'marca_id' => $this->fabricante->id,
        ]);

        $referenciaId = $referencia->id;

        // Act
        $referencia->delete();

        // Assert
        $this->assertDatabaseMissing('referencias', ['id' => $referenciaId]);
        $this->assertNull(Referencia::find($referenciaId));
    }

    /** @test */
    public function referencia_mantiene_timestamps_correctos()
    {
        // Arrange
        $referencia = Referencia::create([
            'referencia' => 'REF-TEST-006',
            'marca_id' => $this->fabricante->id,
        ]);

        // Assert
        $this->assertNotNull($referencia->created_at);
        $this->assertNotNull($referencia->updated_at);
        $this->assertInstanceOf(\Carbon\Carbon::class, $referencia->created_at);
        $this->assertInstanceOf(\Carbon\Carbon::class, $referencia->updated_at);
    }

    /** @test */
    public function referencia_puede_ser_filtrada_por_fabricante()
    {
        // Arrange
        $fabricante2 = Fabricante::create(['nombre' => 'Fabricante 2']);
        
        Referencia::create(['referencia' => 'REF-1', 'marca_id' => $this->fabricante->id]);
        Referencia::create(['referencia' => 'REF-2', 'marca_id' => $this->fabricante->id]);
        Referencia::create(['referencia' => 'REF-3', 'marca_id' => $fabricante2->id]);

        // Act
        $referenciasFabricante1 = Referencia::where('marca_id', $this->fabricante->id)->get();
        $referenciasFabricante2 = Referencia::where('marca_id', $fabricante2->id)->get();

        // Assert
        $this->assertCount(2, $referenciasFabricante1);
        $this->assertCount(1, $referenciasFabricante2);
    }

    /** @test */
    public function referencia_puede_ser_filtrada_por_categoria()
    {
        // Arrange
        $categoria2 = Categoria::create(['nombre' => 'Categoría 2']);
        
        Referencia::create([
            'referencia' => 'REF-1',
            'marca_id' => $this->fabricante->id,
            'categoria_id' => $this->categoria->id,
        ]);
        Referencia::create([
            'referencia' => 'REF-2',
            'marca_id' => $this->fabricante->id,
            'categoria_id' => $this->categoria->id,
        ]);
        Referencia::create([
            'referencia' => 'REF-3',
            'marca_id' => $this->fabricante->id,
            'categoria_id' => $categoria2->id,
        ]);

        // Act
        $referenciasCategoria1 = Referencia::where('categoria_id', $this->categoria->id)->get();
        $referenciasCategoria2 = Referencia::where('categoria_id', $categoria2->id)->get();

        // Assert
        $this->assertCount(2, $referenciasCategoria1);
        $this->assertCount(1, $referenciasCategoria2);
    }

    /** @test */
    public function referencia_puede_ser_buscada_por_codigo()
    {
        // Arrange
        Referencia::create(['referencia' => 'REF-ABC-001', 'marca_id' => $this->fabricante->id]);
        Referencia::create(['referencia' => 'REF-XYZ-001', 'marca_id' => $this->fabricante->id]);

        // Act
        $referenciaEncontrada = Referencia::where('referencia', 'like', '%ABC%')->first();

        // Assert
        $this->assertNotNull($referenciaEncontrada);
        $this->assertEquals('REF-ABC-001', $referenciaEncontrada->referencia);
    }

    /** @test */
    public function referencia_puede_ser_ordenada_por_codigo()
    {
        // Arrange
        Referencia::create(['referencia' => 'REF-Z-001', 'marca_id' => $this->fabricante->id]);
        Referencia::create(['referencia' => 'REF-A-001', 'marca_id' => $this->fabricante->id]);
        Referencia::create(['referencia' => 'REF-M-001', 'marca_id' => $this->fabricante->id]);

        // Act
        $referenciasOrdenadas = Referencia::orderBy('referencia', 'asc')->get();

        // Assert
        $this->assertEquals('REF-A-001', $referenciasOrdenadas[0]->referencia);
        $this->assertEquals('REF-M-001', $referenciasOrdenadas[1]->referencia);
        $this->assertEquals('REF-Z-001', $referenciasOrdenadas[2]->referencia);
    }

    /** @test */
    public function referencia_puede_ser_ordenada_por_fecha_creacion()
    {
        // Arrange
        $referencia1 = Referencia::create([
            'referencia' => 'REF-1',
            'marca_id' => $this->fabricante->id,
        ]);
        
        $referencia2 = Referencia::create([
            'referencia' => 'REF-2',
            'marca_id' => $this->fabricante->id,
        ]);
        
        $referencia3 = Referencia::create([
            'referencia' => 'REF-3',
            'marca_id' => $this->fabricante->id,
        ]);

        // Act
        $referenciasOrdenadas = Referencia::orderBy('created_at', 'asc')->get();

        // Assert
        $this->assertEquals($referencia1->id, $referenciasOrdenadas[0]->id);
        $this->assertEquals($referencia2->id, $referenciasOrdenadas[1]->id);
        $this->assertEquals($referencia3->id, $referenciasOrdenadas[2]->id);
    }

    /** @test */
    public function referencia_puede_tener_comentarios_largos()
    {
        // Arrange
        $comentarioLargo = 'Este es un comentario muy largo que describe en detalle las características de la referencia, incluyendo información técnica, especificaciones, y cualquier detalle relevante para el equipo de trabajo.';
        
        $referencia = Referencia::create([
            'referencia' => 'REF-TEST-LARGO',
            'marca_id' => $this->fabricante->id,
            'comentario' => $comentarioLargo,
        ]);

        // Assert
        $this->assertEquals($comentarioLargo, $referencia->comentario);
        $this->assertGreaterThan(100, strlen($referencia->comentario));
    }

    /** @test */
    public function referencia_puede_tener_comentario_vacio()
    {
        // Arrange
        $referencia = Referencia::create([
            'referencia' => 'REF-TEST-SIN-COMMENT',
            'marca_id' => $this->fabricante->id,
            // Sin comentario
        ]);

        // Assert
        $this->assertInstanceOf(Referencia::class, $referencia);
        $this->assertNull($referencia->comentario);
    }

    /** @test */
    public function referencia_puede_ser_identificada_por_id()
    {
        // Arrange
        $referencia = Referencia::create([
            'referencia' => 'REF-TEST-ID',
            'marca_id' => $this->fabricante->id,
        ]);

        // Act
        $referenciaEncontrada = Referencia::find($referencia->id);

        // Assert
        $this->assertNotNull($referenciaEncontrada);
        $this->assertEquals($referencia->id, $referenciaEncontrada->id);
        $this->assertEquals('REF-TEST-ID', $referenciaEncontrada->referencia);
    }

    /** @test */
    public function referencia_puede_tener_codigo_con_numeros()
    {
        // Arrange
        $referencia = Referencia::create([
            'referencia' => 'REF-2025-001',
            'marca_id' => $this->fabricante->id,
        ]);

        // Assert
        $this->assertEquals('REF-2025-001', $referencia->referencia);
        $this->assertStringContainsString('2025', $referencia->referencia);
        $this->assertStringContainsString('001', $referencia->referencia);
    }

    /** @test */
    public function referencia_puede_tener_codigo_con_caracteres_especiales()
    {
        // Arrange
        $referencia = Referencia::create([
            'referencia' => 'REF-TEST-@#$%',
            'marca_id' => $this->fabricante->id,
        ]);

        // Assert
        $this->assertEquals('REF-TEST-@#$%', $referencia->referencia);
        $this->assertStringContainsString('@', $referencia->referencia);
        $this->assertStringContainsString('#', $referencia->referencia);
    }

    /** @test */
    public function referencia_puede_ser_buscada_por_parte_del_codigo()
    {
        // Arrange
        Referencia::create(['referencia' => 'REF-MOTOR-001', 'marca_id' => $this->fabricante->id]);
        Referencia::create(['referencia' => 'REF-FRENO-001', 'marca_id' => $this->fabricante->id]);
        Referencia::create(['referencia' => 'REF-MOTOR-002', 'marca_id' => $this->fabricante->id]);

        // Act
        $referenciasMotor = Referencia::where('referencia', 'like', '%MOTOR%')->get();

        // Assert
        $this->assertCount(2, $referenciasMotor);
        $this->assertTrue($referenciasMotor->contains('referencia', 'REF-MOTOR-001'));
        $this->assertTrue($referenciasMotor->contains('referencia', 'REF-MOTOR-002'));
    }

    /** @test */
    public function referencia_puede_ser_contada_por_fabricante()
    {
        // Arrange
        $fabricante2 = Fabricante::create(['nombre' => 'Fabricante 2']);
        
        Referencia::create(['referencia' => 'REF-1', 'marca_id' => $this->fabricante->id]);
        Referencia::create(['referencia' => 'REF-2', 'marca_id' => $this->fabricante->id]);
        Referencia::create(['referencia' => 'REF-3', 'marca_id' => $this->fabricante->id]);
        Referencia::create(['referencia' => 'REF-4', 'marca_id' => $fabricante2->id]);

        // Act
        $totalReferencias = Referencia::count();
        $referenciasFabricante1 = Referencia::where('marca_id', $this->fabricante->id)->count();
        $referenciasFabricante2 = Referencia::where('marca_id', $fabricante2->id)->count();

        // Assert
        $this->assertEquals(4, $totalReferencias);
        $this->assertEquals(3, $referenciasFabricante1);
        $this->assertEquals(1, $referenciasFabricante2);
    }
}
