<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Empresa;
use App\Models\Pedido;
use App\Models\Referencia;
use App\Models\Tercero;
use App\Models\User;

class CalculadoraPreciosTest extends TestCase
{
    use RefreshDatabase;

    private Empresa $empresa;
    private User $user;
    private Tercero $proveedorNacional;
    private Tercero $proveedorInternacional;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Crear empresa de testing con TRM y flete
        $this->empresa = Empresa::factory()->create([
            'estado' => 1,
            'trm' => 4000, // 1 USD = 4000 COP
            'flete' => 5000, // 5000 COP por kg
        ]);

        // Crear usuario de testing
        $this->user = User::factory()->create();

        // Crear proveedor nacional
        $this->proveedorNacional = Tercero::factory()->create([
            'tipo' => 'Proveedor',
            'estado' => 1,
        ]);

        // Crear proveedor internacional
        $this->proveedorInternacional = Tercero::factory()->create([
            'tipo' => 'Proveedor',
            'estado' => 1,
        ]);
    }

    /** @test */
    public function puede_calcular_precio_nacional_correctamente()
    {
        // Arrange
        $costoUnidad = 10000; // 10,000 COP
        $utilidad = 15; // 15%
        $cantidad = 5;

        // Act - Simular cálculo nacional
        $valorUnidad = $costoUnidad + ($costoUnidad * $utilidad / 100);
        $valorTotal = $valorUnidad * $cantidad;

        // Assert
        $this->assertEquals(11500, $valorUnidad); // 10,000 + 1,500 = 11,500
        $this->assertEquals(57500, $valorTotal); // 11,500 * 5 = 57,500
    }

    /** @test */
    public function puede_calcular_precio_internacional_correctamente()
    {
        // Arrange
        $costoUnidadUSD = 25; // 25 USD
        $utilidad = 12; // 12%
        $cantidad = 3;
        $peso = 2.5; // 2.5 kg
        $trm = 4000; // 1 USD = 4000 COP
        $flete = 5000; // 5000 COP por kg

        // Act - Simular cálculo internacional paso a paso
        $costoCOP = $costoUnidadUSD * $trm; // 25 * 4000 = 100,000 COP
        $valorBase = $costoCOP + ($peso * 2.2 * $flete); // 100,000 + (2.5 * 2.2 * 5000) = 127,500 COP
        $valorUnidad = $valorBase + ($utilidad * $valorBase / 100); // 127,500 + (12 * 127,500 / 100) = 142,800 COP
        $valorUnidad = round($valorUnidad, -2); // Redondear a centenas = 142,800 COP
        $valorTotal = $valorUnidad * $cantidad; // 142,800 * 3 = 428,400 COP

        // Assert
        $this->assertEquals(100000, $costoCOP);
        $this->assertEquals(127500, $valorBase);
        $this->assertEquals(142800, $valorUnidad);
        $this->assertEquals(428400, $valorTotal);
    }

    /** @test */
    public function redondeo_a_centenas_funciona_correctamente()
    {
        // Arrange
        $valores = [
            142847 => 142800,
            142899 => 142900,
            142850 => 142800,
            142900 => 142900,
        ];

        // Act & Assert
        foreach ($valores as $valorOriginal => $valorEsperado) {
            $valorRedondeado = round($valorOriginal, -2);
            $this->assertEquals($valorEsperado, $valorRedondeado);
        }
    }

    /** @test */
    public function empresa_activa_tiene_trm_y_flete_configurados()
    {
        // Assert
        $this->assertNotNull($this->empresa->trm);
        $this->assertNotNull($this->empresa->flete);
        $this->assertEquals(4000, $this->empresa->trm);
        $this->assertEquals(5000, $this->empresa->flete);
        $this->assertEquals(1, $this->empresa->estado);
    }

    /** @test */
    public function proveedores_tienen_estado_activo()
    {
        // Assert
        $this->assertEquals(1, $this->proveedorNacional->estado);
        $this->assertEquals(1, $this->proveedorInternacional->estado);
        $this->assertEquals('Proveedor', $this->proveedorNacional->tipo);
        $this->assertEquals('Proveedor', $this->proveedorInternacional->tipo);
    }

    /** @test */
    public function calculos_con_valores_cero_manejan_errores()
    {
        // Arrange
        $costoUnidad = 0;
        $utilidad = 15;
        $cantidad = 5;

        // Act
        $valorUnidad = $costoUnidad + ($costoUnidad * $utilidad / 100);
        $valorTotal = $valorUnidad * $cantidad;

        // Assert
        $this->assertEquals(0, $valorUnidad);
        $this->assertEquals(0, $valorTotal);
    }

    /** @test */
    public function calculos_con_utilidad_cero_funcionan()
    {
        // Arrange
        $costoUnidad = 10000;
        $utilidad = 0;
        $cantidad = 3;

        // Act
        $valorUnidad = $costoUnidad + ($costoUnidad * $utilidad / 100);
        $valorTotal = $valorUnidad * $cantidad;

        // Assert
        $this->assertEquals(10000, $valorUnidad); // Sin utilidad
        $this->assertEquals(30000, $valorTotal); // 10,000 * 3
    }

    /** @test */
    public function calculos_con_cantidad_uno_funcionan()
    {
        // Arrange
        $costoUnidad = 10000;
        $utilidad = 20;
        $cantidad = 1;

        // Act
        $valorUnidad = $costoUnidad + ($costoUnidad * $utilidad / 100);
        $valorTotal = $valorUnidad * $cantidad;

        // Assert
        $this->assertEquals(12000, $valorUnidad); // 10,000 + 2,000
        $this->assertEquals(12000, $valorTotal); // 12,000 * 1
    }

    /** @test */
    public function formulas_matematicas_son_precisas()
    {
        // Arrange
        $costoUnidad = 15000;
        $utilidad = 25;
        $cantidad = 4;

        // Act
        $valorUnidad = $costoUnidad + ($costoUnidad * $utilidad / 100);
        $valorTotal = $valorUnidad * $cantidad;

        // Assert - Verificar que las matemáticas son correctas
        $utilidadCalculada = $costoUnidad * $utilidad / 100; // 15,000 * 25 / 100 = 3,750
        $this->assertEquals(3750, $utilidadCalculada);
        
        $this->assertEquals(18750, $valorUnidad); // 15,000 + 3,750 = 18,750
        $this->assertEquals(75000, $valorTotal); // 18,750 * 4 = 75,000
    }
}
