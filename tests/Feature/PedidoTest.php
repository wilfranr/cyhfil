<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Empresa;
use App\Models\Pedido;
use App\Models\Referencia;
use App\Models\Tercero;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;

class PedidoTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private Empresa $empresa;
    private User $user;
    private Tercero $cliente;
    private Referencia $referencia;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Crear empresa de testing
        $this->empresa = Empresa::factory()->create([
            'estado' => 1,
            'trm' => 4000,
            'flete' => 5000,
        ]);

        // Crear usuario de testing
        $this->user = User::factory()->create();

        // Crear cliente de testing
        $this->cliente = Tercero::factory()->create([
            'tipo' => 'Cliente',
            'estado' => 1,
        ]);

        // Crear referencia de testing
        $this->referencia = Referencia::factory()->create();
    }

    /** @test */
    public function usuario_puede_ver_lista_de_pedidos()
    {
        // Arrange
        $pedidos = Pedido::factory()->count(3)->create([
            'user_id' => $this->user->id,
            'tercero_id' => $this->cliente->id,
        ]);

        // Act
        $response = $this->actingAs($this->user)
            ->get('/admin/pedidos');

        // Assert
        $response->assertStatus(200);
        $this->assertCount(3, Pedido::all());
    }

    /** @test */
    public function usuario_puede_crear_nuevo_pedido()
    {
        // Arrange
        $pedidoData = [
            'tercero_id' => $this->cliente->id,
            'direccion' => $this->faker->address,
            'comentario' => $this->faker->sentence,
            'estado' => 'Borrador',
        ];

        // Act
        $pedido = Pedido::create($pedidoData);

        // Assert
        $this->assertInstanceOf(Pedido::class, $pedido);
        $this->assertEquals($this->cliente->id, $pedido->tercero_id);
        $this->assertEquals('Borrador', $pedido->estado);
        $this->assertDatabaseHas('pedidos', $pedidoData);
    }

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

    /** @test */
    public function pedido_puede_cambiar_estado()
    {
        // Arrange
        $pedido = Pedido::factory()->create([
            'estado' => 'Borrador',
        ]);

        // Act
        $pedido->update(['estado' => 'En Proceso']);

        // Assert
        $this->assertEquals('En Proceso', $pedido->fresh()->estado);
        $this->assertDatabaseHas('pedidos', [
            'id' => $pedido->id,
            'estado' => 'En Proceso',
        ]);
    }

    /** @test */
    public function pedido_requiere_campos_obligatorios()
    {
        // Arrange
        $pedidoData = [
            // Sin tercero_id (campo obligatorio)
            'direccion' => $this->faker->address,
        ];

        // Act & Assert
        $this->expectException(\Illuminate\Database\QueryException::class);
        Pedido::create($pedidoData);
    }

    /** @test */
    public function pedido_puede_tener_motivo_rechazo()
    {
        // Arrange
        $pedido = Pedido::factory()->create([
            'estado' => 'Rechazado',
            'motivo_rechazo' => 'Precio muy alto',
        ]);

        // Assert
        $this->assertEquals('Rechazado', $pedido->estado);
        $this->assertEquals('Precio muy alto', $pedido->motivo_rechazo);
    }

    /** @test */
    public function pedido_puede_tener_contacto_asociado()
    {
        // Arrange
        $pedido = Pedido::factory()->create([
            'contacto_id' => null, // Sin contacto inicialmente
        ]);

        // Act
        $pedido->update(['contacto_id' => 1]);

        // Assert
        $this->assertEquals(1, $pedido->fresh()->contacto_id);
    }

    /** @test */
    public function pedido_puede_tener_maquina_asociada()
    {
        // Arrange
        $pedido = Pedido::factory()->create([
            'maquina_id' => null, // Sin máquina inicialmente
        ]);

        // Act
        $pedido->update(['maquina_id' => 1]);

        // Assert
        $this->assertEquals(1, $pedido->fresh()->maquina_id);
    }

    /** @test */
    public function pedido_puede_tener_fabricante_asociado()
    {
        // Arrange
        $pedido = Pedido::factory()->create([
            'fabricante_id' => null, // Sin fabricante inicialmente
        ]);

        // Act
        $pedido->update(['fabricante_id' => 1]);

        // Assert
        $this->assertEquals(1, $pedido->fresh()->fabricante_id);
    }

    /** @test */
    public function pedido_mantiene_timestamps_correctos()
    {
        // Arrange
        $pedido = Pedido::factory()->create();

        // Assert
        $this->assertNotNull($pedido->created_at);
        $this->assertNotNull($pedido->updated_at);
        $this->assertInstanceOf(\Carbon\Carbon::class, $pedido->created_at);
        $this->assertInstanceOf(\Carbon\Carbon::class, $pedido->updated_at);
    }

    /** @test */
    public function pedido_puede_ser_eliminado()
    {
        // Arrange
        $pedido = Pedido::factory()->create();

        // Act
        $pedidoId = $pedido->id;
        $pedido->delete();

        // Assert
        $this->assertDatabaseMissing('pedidos', ['id' => $pedidoId]);
        $this->assertNull(Pedido::find($pedidoId));
    }

    /** @test */
    public function pedido_puede_ser_filtrado_por_estado()
    {
        // Arrange
        Pedido::factory()->create(['estado' => 'Borrador']);
        Pedido::factory()->create(['estado' => 'En Proceso']);
        Pedido::factory()->create(['estado' => 'Completado']);

        // Act
        $pedidosBorrador = Pedido::where('estado', 'Borrador')->get();
        $pedidosEnProceso = Pedido::where('estado', 'En Proceso')->get();
        $pedidosCompletados = Pedido::where('estado', 'Completado')->get();

        // Assert
        $this->assertCount(1, $pedidosBorrador);
        $this->assertCount(1, $pedidosEnProceso);
        $this->assertCount(1, $pedidosCompletados);
    }

    /** @test */
    public function pedido_puede_ser_ordenado_por_fecha_creacion()
    {
        // Arrange
        $pedido1 = Pedido::factory()->create(['created_at' => now()->subDays(2)]);
        $pedido2 = Pedido::factory()->create(['created_at' => now()->subDays(1)]);
        $pedido3 = Pedido::factory()->create(['created_at' => now()]);

        // Act
        $pedidosOrdenados = Pedido::orderBy('created_at', 'asc')->get();

        // Assert
        $this->assertEquals($pedido1->id, $pedidosOrdenados[0]->id);
        $this->assertEquals($pedido2->id, $pedidosOrdenados[1]->id);
        $this->assertEquals($pedido3->id, $pedidosOrdenados[2]->id);
    }
}
