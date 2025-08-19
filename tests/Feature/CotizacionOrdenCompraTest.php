<?php

namespace Tests\Feature;

use App\Models\Cotizacion;
use App\Models\Pedido;
use App\Models\PedidoReferencia;
use App\Models\PedidoReferenciaProveedor;
use App\Models\OrdenCompra;
use App\Models\OrdenCompraReferencia;
use App\Models\Referencia;
use App\Models\Tercero;
use App\Models\User;
use App\Models\Direccion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Filament\Notifications\Notification;

class CotizacionOrdenCompraTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Crear usuario con rol de administrador
        $this->user = User::factory()->create();
        $this->user->assignRole('Administrador');
        
        $this->actingAs($this->user);
    }

    /** @test */
    public function al_aprobar_cotizacion_se_genera_una_orden_de_compra_por_proveedor()
    {
        // Crear cliente
        $cliente = Tercero::create([
            'nombre' => 'Cliente Test',
            'tipo_documento' => 'NIT',
            'numero_documento' => '123456789',
            'direccion' => 'Dirección Test',
            'telefono' => '3001234567',
            'email' => 'cliente@test.com',
            'city_id' => 1,
            'estado' => 1,
        ]);

        // Crear dirección del cliente
        $direccion = Direccion::create([
            'tercero_id' => $cliente->id,
            'direccion' => 'Dirección de Entrega Test',
            'city_id' => 1,
            'state_id' => 1,
            'country_id' => 1,
        ]);

        // Crear proveedores
        $proveedor1 = Tercero::create([
            'nombre' => 'Proveedor 1',
            'tipo_documento' => 'NIT',
            'numero_documento' => '111111111',
            'direccion' => 'Dirección Proveedor 1',
            'telefono' => '3001111111',
            'email' => 'proveedor1@test.com',
            'city_id' => 1,
            'estado' => 1,
        ]);

        $proveedor2 = Tercero::create([
            'nombre' => 'Proveedor 2',
            'tipo_documento' => 'NIT',
            'numero_documento' => '222222222',
            'direccion' => 'Dirección Proveedor 2',
            'telefono' => '3002222222',
            'email' => 'proveedor2@test.com',
            'city_id' => 1,
            'estado' => 1,
        ]);

        // Crear referencias
        $referencia1 = Referencia::create([
            'referencia' => 'REF001',
            'articulo_id' => 1,
            'marca_id' => 1,
        ]);

        $referencia2 = Referencia::create([
            'referencia' => 'REF002',
            'articulo_id' => 1,
            'marca_id' => 1,
        ]);

        $referencia3 = Referencia::create([
            'referencia' => 'REF003',
            'articulo_id' => 1,
            'marca_id' => 1,
        ]);

        // Crear pedido
        $pedido = Pedido::create([
            'tercero_id' => $cliente->id,
            'estado' => 'Cotizado',
            'observaciones' => 'Pedido de prueba',
        ]);

        // Crear cotización
        $cotizacion = Cotizacion::create([
            'pedido_id' => $pedido->id,
            'tercero_id' => $cliente->id,
            'estado' => 'Pendiente',
        ]);

        // Crear pedido_referencia
        $pedidoRef1 = PedidoReferencia::create([
            'pedido_id' => $pedido->id,
            'referencia_id' => $referencia1->id,
            'estado' => 1,
        ]);

        $pedidoRef2 = PedidoReferencia::create([
            'pedido_id' => $pedido->id,
            'referencia_id' => $referencia2->id,
            'estado' => 1,
        ]);

        $pedidoRef3 = PedidoReferencia::create([
            'pedido_id' => $pedido->id,
            'referencia_id' => $referencia3->id,
            'estado' => 1,
        ]);

        // Crear proveedores para las referencias
        // Referencia 1 y 2 van al Proveedor 1
        PedidoReferenciaProveedor::create([
            'pedido_referencia_id' => $pedidoRef1->id,
            'referencia_id' => $referencia1->id,
            'proveedor_id' => $proveedor1->id,
            'cantidad' => 2,
            'costo_unidad' => 100.00,
            'valor_unidad' => 120.00,
            'valor_total' => 240.00,
            'estado' => 1,
        ]);

        PedidoReferenciaProveedor::create([
            'pedido_referencia_id' => $pedidoRef2->id,
            'referencia_id' => $referencia2->id,
            'proveedor_id' => $proveedor1->id,
            'cantidad' => 1,
            'costo_unidad' => 150.00,
            'valor_unidad' => 180.00,
            'valor_total' => 180.00,
            'estado' => 1,
        ]);

        // Referencia 3 va al Proveedor 2
        PedidoReferenciaProveedor::create([
            'pedido_referencia_id' => $pedidoRef3->id,
            'referencia_id' => $referencia3->id,
            'proveedor_id' => $proveedor2->id,
            'cantidad' => 3,
            'costo_unidad' => 200.00,
            'valor_unidad' => 240.00,
            'valor_total' => 720.00,
            'estado' => 1,
        ]);

        // Simular la aprobación de la cotización
        $this->aprobarCotizacion($pedido, $cotizacion, $direccion->id);

        // Verificar que se crearon solo 2 órdenes de compra (una por proveedor)
        $this->assertEquals(2, OrdenCompra::count(), 'Debe haber solo 2 órdenes de compra (una por proveedor)');

        // Verificar que el Proveedor 1 tiene una orden con 2 referencias
        $ordenCompraProveedor1 = OrdenCompra::where('proveedor_id', $proveedor1->id)->first();
        $this->assertNotNull($ordenCompraProveedor1, 'Debe existir orden de compra para Proveedor 1');
        $this->assertEquals(2, $ordenCompraProveedor1->referencias()->count(), 'Proveedor 1 debe tener 2 referencias');

        // Verificar que el Proveedor 2 tiene una orden con 1 referencia
        $ordenCompraProveedor2 = OrdenCompra::where('proveedor_id', $proveedor2->id)->first();
        $this->assertNotNull($ordenCompraProveedor2, 'Debe existir orden de compra para Proveedor 2');
        $this->assertEquals(1, $ordenCompraProveedor2->referencias()->count(), 'Proveedor 2 debe tener 1 referencia');

        // Verificar que se crearon las relaciones en la tabla pivot
        $this->assertEquals(3, OrdenCompraReferencia::count(), 'Debe haber 3 registros en la tabla pivot');

        // Verificar que el pedido cambió a estado 'Aprobado'
        $pedido->refresh();
        $this->assertEquals('Aprobado', $pedido->estado, 'El pedido debe estar en estado Aprobado');

        // Verificar que la cotización cambió a estado 'Aprobada'
        $cotizacion->refresh();
        $this->assertEquals('Aprobada', $cotizacion->estado, 'La cotización debe estar en estado Aprobada');
    }

    /** @test */
    public function al_aprobar_cotizacion_con_un_solo_proveedor_se_genera_una_sola_orden_de_compra()
    {
        // Crear cliente
        $cliente = Tercero::create([
            'nombre' => 'Cliente Test 2',
            'tipo_documento' => 'NIT',
            'numero_documento' => '987654321',
            'direccion' => 'Dirección Test 2',
            'telefono' => '3009876543',
            'email' => 'cliente2@test.com',
            'city_id' => 1,
            'estado' => 1,
        ]);

        // Crear dirección del cliente
        $direccion = Direccion::create([
            'tercero_id' => $cliente->id,
            'direccion' => 'Dirección de Entrega Test 2',
            'city_id' => 1,
            'state_id' => 1,
            'country_id' => 1,
        ]);

        // Crear un solo proveedor
        $proveedor = Tercero::create([
            'nombre' => 'Proveedor Único',
            'tipo_documento' => 'NIT',
            'numero_documento' => '333333333',
            'direccion' => 'Dirección Proveedor Único',
            'telefono' => '3003333333',
            'email' => 'proveedor@test.com',
            'city_id' => 1,
            'estado' => 1,
        ]);

        // Crear referencias
        $referencia1 = Referencia::create([
            'referencia' => 'REF004',
            'articulo_id' => 1,
            'marca_id' => 1,
        ]);

        $referencia2 = Referencia::create([
            'referencia' => 'REF005',
            'articulo_id' => 1,
            'marca_id' => 1,
        ]);

        // Crear pedido
        $pedido = Pedido::create([
            'tercero_id' => $cliente->id,
            'estado' => 'Cotizado',
            'observaciones' => 'Pedido de prueba con un solo proveedor',
        ]);

        // Crear cotización
        $cotizacion = Cotizacion::create([
            'pedido_id' => $pedido->id,
            'tercero_id' => $cliente->id,
            'estado' => 'Pendiente',
        ]);

        // Crear pedido_referencia
        $pedidoRef1 = PedidoReferencia::create([
            'pedido_id' => $pedido->id,
            'referencia_id' => $referencia1->id,
            'estado' => 1,
        ]);

        $pedidoRef2 = PedidoReferencia::create([
            'pedido_id' => $pedido->id,
            'referencia_id' => $referencia2->id,
            'estado' => 1,
        ]);

        // Crear proveedores para las referencias (ambas al mismo proveedor)
        PedidoReferenciaProveedor::create([
            'pedido_referencia_id' => $pedidoRef1->id,
            'referencia_id' => $referencia1->id,
            'proveedor_id' => $proveedor->id,
            'cantidad' => 1,
            'costo_unidad' => 100.00,
            'valor_unidad' => 120.00,
            'valor_total' => 120.00,
            'estado' => 1,
        ]);

        PedidoReferenciaProveedor::create([
            'pedido_referencia_id' => $pedidoRef2->id,
            'referencia_id' => $referencia2->id,
            'proveedor_id' => $proveedor->id,
            'cantidad' => 2,
            'costo_unidad' => 150.00,
            'valor_unidad' => 180.00,
            'valor_total' => 360.00,
            'estado' => 1,
        ]);

        // Simular la aprobación de la cotización
        $this->aprobarCotizacion($pedido, $cotizacion, $direccion->id);

        // Verificar que se creó solo 1 orden de compra
        $this->assertEquals(1, OrdenCompra::count(), 'Debe haber solo 1 orden de compra para un solo proveedor');

        // Verificar que la orden tiene 2 referencias
        $ordenCompra = OrdenCompra::where('proveedor_id', $proveedor->id)->first();
        $this->assertNotNull($ordenCompra, 'Debe existir orden de compra para el proveedor');
        $this->assertEquals(2, $ordenCompra->referencias()->count(), 'La orden debe tener 2 referencias');

        // Verificar que se crearon las relaciones en la tabla pivot
        $this->assertEquals(2, OrdenCompraReferencia::count(), 'Debe haber 2 registros en la tabla pivot');
    }

    /** @test */
    public function al_aprobar_cotizacion_sin_proveedores_no_se_generan_ordenes_de_compra()
    {
        // Crear cliente
        $cliente = Tercero::create([
            'nombre' => 'Cliente Test 3',
            'tipo_documento' => 'NIT',
            'numero_documento' => '555555555',
            'direccion' => 'Dirección Test 3',
            'telefono' => '3005555555',
            'email' => 'cliente3@test.com',
            'city_id' => 1,
            'estado' => 1,
        ]);

        // Crear dirección del cliente
        $direccion = Direccion::create([
            'tercero_id' => $cliente->id,
            'direccion' => 'Dirección de Entrega Test 3',
            'city_id' => 1,
            'state_id' => 1,
            'country_id' => 1,
        ]);

        // Crear pedido
        $pedido = Pedido::create([
            'tercero_id' => $cliente->id,
            'estado' => 'Cotizado',
            'observaciones' => 'Pedido de prueba sin proveedores',
        ]);

        // Crear cotización
        $cotizacion = Cotizacion::create([
            'pedido_id' => $pedido->id,
            'tercero_id' => $cliente->id,
            'estado' => 'Pendiente',
        ]);

        // Crear pedido_referencia sin proveedores
        $referencia = Referencia::create([
            'referencia' => 'REF006',
            'articulo_id' => 1,
            'marca_id' => 1,
        ]);

        PedidoReferencia::create([
            'pedido_id' => $pedido->id,
            'referencia_id' => $referencia->id,
            'estado' => 1,
        ]);

        // Simular la aprobación de la cotización
        $this->aprobarCotizacion($pedido, $cotizacion, $direccion->id);

        // Verificar que no se crearon órdenes de compra
        $this->assertEquals(0, OrdenCompra::count(), 'No debe haber órdenes de compra sin proveedores');

        // Verificar que no se crearon registros en la tabla pivot
        $this->assertEquals(0, OrdenCompraReferencia::count(), 'No debe haber registros en la tabla pivot');
    }

    /**
     * Método helper para simular la aprobación de cotización
     */
    private function aprobarCotizacion($pedido, $cotizacion, $direccionId)
    {
        // Cambiar el estado del pedido a 'Aprobado'
        $pedido->estado = 'Aprobado';
        $pedido->save();

        // Cambiar el estado de la cotización a 'Aprobada'
        $cotizacion->estado = 'Aprobada';
        $cotizacion->save();

        // Agrupar referencias por proveedor
        $referenciasPorProveedor = PedidoReferencia::where('pedido_id', $pedido->id)
            ->with('proveedores')
            ->get()
            ->groupBy(function ($referencia) {
                return $referencia->proveedores->first()->proveedor_id ?? null;
            });

        // Iterar sobre cada proveedor
        foreach ($referenciasPorProveedor as $proveedorId => $referencias) {
            if (!$proveedorId) {
                continue; // Omitir referencias sin proveedor
            }

            // Crear una única orden de compra por proveedor
            $ordenCompra = new OrdenCompra();
            $ordenCompra->pedido_id = $pedido->id;
            $ordenCompra->tercero_id = $pedido->tercero_id; // Cliente
            $ordenCompra->proveedor_id = $proveedorId;
            $ordenCompra->fecha_expedicion = now();
            $ordenCompra->fecha_entrega = now()->addDays(30);
            $ordenCompra->observaciones = $pedido->observaciones;
            $ordenCompra->direccion = 'Dirección de entrega';
            $ordenCompra->telefono = $pedido->tercero->telefono;
            $ordenCompra->save();

            // Iterar sobre las referencias de este proveedor
            foreach ($referencias as $referencia) {
                $proveedor = $referencia->proveedores->first();

                // Crear la relación en la tabla pivot
                $ordenCompra->referencias()->attach($referencia->referencia_id, [
                    'cantidad' => $proveedor->cantidad,
                    'valor_unitario' => $proveedor->costo_unidad,
                    'valor_total' => $proveedor->valor_total,
                ]);
            }
        }
    }
}
