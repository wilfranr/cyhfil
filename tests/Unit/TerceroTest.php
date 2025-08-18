<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Tercero;
use App\Models\Country;
use App\Models\State;
use App\Models\City;

class TerceroTest extends TestCase
{
    use RefreshDatabase;

    private Country $country;
    private State $state;
    private City $city;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Crear datos de ubicación para testing
        $this->country = Country::create(['name' => 'Colombia']);
        $this->state = State::create([
            'name' => 'Antioquia',
            'country_id' => $this->country->id
        ]);
        $this->city = City::create([
            'name' => 'Medellín',
            'state_id' => $this->state->id
        ]);
    }

    /** @test */
    public function puede_crear_tercero_proveedor()
    {
        // Arrange
        $terceroData = [
            'nombre' => 'Proveedor Test',
            'tipo_documento' => 'NIT',
            'numero_documento' => '800123456-7',
            'direccion' => 'Calle Proveedor #123',
            'telefono' => '1234567890',
            'email' => 'proveedor@test.com',
            'dv' => '7',
            'estado' => 1,
            'forma_pago' => '30 días',
            'tipo' => 'Proveedor',
            'country_id' => $this->country->id,
            'state_id' => $this->state->id,
            'city_id' => $this->city->id,
        ];

        // Act
        $tercero = Tercero::create($terceroData);

        // Assert
        $this->assertInstanceOf(Tercero::class, $tercero);
        $this->assertEquals('Proveedor Test', $tercero->nombre);
        $this->assertEquals('NIT', $tercero->tipo_documento);
        $this->assertEquals('800123456-7', $tercero->numero_documento);
        $this->assertEquals('Proveedor', $tercero->tipo);
        $this->assertEquals(1, $tercero->estado);
    }

    /** @test */
    public function puede_crear_tercero_cliente()
    {
        // Arrange
        $terceroData = [
            'nombre' => 'Cliente Test',
            'tipo_documento' => 'CC',
            'numero_documento' => '12345678',
            'direccion' => 'Calle Cliente #456',
            'telefono' => '9876543210',
            'email' => 'cliente@test.com',
            'estado' => 1,
            'forma_pago' => 'Contado',
            'tipo' => 'Cliente',
        ];

        // Act
        $tercero = Tercero::create($terceroData);

        // Assert
        $this->assertInstanceOf(Tercero::class, $tercero);
        $this->assertEquals('Cliente Test', $tercero->nombre);
        $this->assertEquals('Cliente', $tercero->tipo);
        $this->assertEquals('Contado', $tercero->forma_pago);
    }

    /** @test */
    public function tercero_tiene_relaciones_correctas()
    {
        // Arrange
        $tercero = Tercero::create([
            'nombre' => 'Tercero Test',
            'tipo' => 'Proveedor',
            'estado' => 1,
            'country_id' => $this->country->id,
            'state_id' => $this->state->id,
            'city_id' => $this->city->id,
        ]);

        // Act & Assert
        $this->assertInstanceOf(Country::class, $tercero->country);
        $this->assertInstanceOf(State::class, $tercero->state);
        $this->assertInstanceOf(City::class, $tercero->city);
        
        $this->assertEquals('Colombia', $tercero->country->name);
        $this->assertEquals('Antioquia', $tercero->state->name);
        $this->assertEquals('Medellín', $tercero->city->name);
    }

    /** @test */
    public function tercero_puede_cambiar_estado()
    {
        // Arrange
        $tercero = Tercero::create([
            'nombre' => 'Tercero Test',
            'tipo' => 'Proveedor',
            'estado' => 1,
        ]);

        // Act
        $tercero->update(['estado' => 0]);

        // Assert
        $this->assertEquals(0, $tercero->fresh()->estado);
    }

    /** @test */
    public function tercero_puede_cambiar_tipo()
    {
        // Arrange
        $tercero = Tercero::create([
            'nombre' => 'Tercero Test',
            'tipo' => 'Proveedor',
            'estado' => 1,
        ]);

        // Act
        $tercero->update(['tipo' => 'Cliente']);

        // Assert
        $this->assertEquals('Cliente', $tercero->fresh()->tipo);
    }

    /** @test */
    public function tercero_puede_ser_eliminado()
    {
        // Arrange
        $tercero = Tercero::create([
            'nombre' => 'Tercero Test',
            'tipo' => 'Proveedor',
            'estado' => 1,
        ]);

        $terceroId = $tercero->id;

        // Act
        $tercero->delete();

        // Assert
        $this->assertDatabaseMissing('terceros', ['id' => $terceroId]);
        $this->assertNull(Tercero::find($terceroId));
    }

    /** @test */
    public function tercero_mantiene_timestamps_correctos()
    {
        // Arrange
        $tercero = Tercero::create([
            'nombre' => 'Tercero Test',
            'tipo' => 'Proveedor',
            'estado' => 1,
        ]);

        // Assert
        $this->assertNotNull($tercero->created_at);
        $this->assertNotNull($tercero->updated_at);
        $this->assertInstanceOf(\Carbon\Carbon::class, $tercero->created_at);
        $this->assertInstanceOf(\Carbon\Carbon::class, $tercero->updated_at);
    }

    /** @test */
    public function tercero_puede_tener_campos_opcionales_vacios()
    {
        // Arrange
        $tercero = Tercero::create([
            'nombre' => 'Tercero Test',
            'tipo' => 'Proveedor',
            'estado' => 1,
            // Sin email, telefono, etc.
        ]);

        // Assert
        $this->assertInstanceOf(Tercero::class, $tercero);
        $this->assertNull($tercero->email);
        $this->assertNull($tercero->telefono);
        $this->assertNull($tercero->direccion);
    }

    /** @test */
    public function tercero_puede_ser_filtrado_por_tipo()
    {
        // Arrange
        Tercero::create(['nombre' => 'Proveedor 1', 'tipo' => 'Proveedor', 'estado' => 1]);
        Tercero::create(['nombre' => 'Cliente 1', 'tipo' => 'Cliente', 'estado' => 1]);
        Tercero::create(['nombre' => 'Proveedor 2', 'tipo' => 'Proveedor', 'estado' => 1]);

        // Act
        $proveedores = Tercero::where('tipo', 'Proveedor')->get();
        $clientes = Tercero::where('tipo', 'Cliente')->get();

        // Assert
        $this->assertCount(2, $proveedores);
        $this->assertCount(1, $clientes);
    }

    /** @test */
    public function tercero_puede_ser_filtrado_por_estado()
    {
        // Arrange
        Tercero::create(['nombre' => 'Tercero 1', 'tipo' => 'Proveedor', 'estado' => 1]);
        Tercero::create(['nombre' => 'Tercero 2', 'tipo' => 'Proveedor', 'estado' => 0]);
        Tercero::create(['nombre' => 'Tercero 3', 'tipo' => 'Proveedor', 'estado' => 1]);

        // Act
        $tercerosActivos = Tercero::where('estado', 1)->get();
        $tercerosInactivos = Tercero::where('estado', 0)->get();

        // Assert
        $this->assertCount(2, $tercerosActivos);
        $this->assertCount(1, $tercerosInactivos);
    }

    /** @test */
    public function tercero_puede_ser_buscado_por_nombre()
    {
        // Arrange
        Tercero::create(['nombre' => 'Proveedor ABC', 'tipo' => 'Proveedor', 'estado' => 1]);
        Tercero::create(['nombre' => 'Proveedor XYZ', 'tipo' => 'Proveedor', 'estado' => 1]);

        // Act
        $terceroEncontrado = Tercero::where('nombre', 'like', '%ABC%')->first();

        // Assert
        $this->assertNotNull($terceroEncontrado);
        $this->assertEquals('Proveedor ABC', $terceroEncontrado->nombre);
    }

    /** @test */
    public function tercero_puede_ser_buscado_por_documento()
    {
        // Arrange
        Tercero::create([
            'nombre' => 'Tercero Test',
            'tipo_documento' => 'NIT',
            'numero_documento' => '800123456-7',
            'tipo' => 'Proveedor',
            'estado' => 1,
        ]);

        // Act
        $terceroEncontrado = Tercero::where('numero_documento', '800123456-7')->first();

        // Assert
        $this->assertNotNull($terceroEncontrado);
        $this->assertEquals('800123456-7', $terceroEncontrado->numero_documento);
    }

    /** @test */
    public function tercero_puede_ser_ordenado_por_nombre()
    {
        // Arrange
        Tercero::create(['nombre' => 'Tercero Z', 'tipo' => 'Proveedor', 'estado' => 1]);
        Tercero::create(['nombre' => 'Tercero A', 'tipo' => 'Proveedor', 'estado' => 1]);
        Tercero::create(['nombre' => 'Tercero M', 'tipo' => 'Proveedor', 'estado' => 1]);

        // Act
        $tercerosOrdenados = Tercero::orderBy('nombre', 'asc')->get();

        // Assert
        $this->assertEquals('Tercero A', $tercerosOrdenados[0]->nombre);
        $this->assertEquals('Tercero M', $tercerosOrdenados[1]->nombre);
        $this->assertEquals('Tercero Z', $tercerosOrdenados[2]->nombre);
    }

    /** @test */
    public function tercero_puede_tener_diferentes_formas_de_pago()
    {
        // Arrange
        $tercero1 = Tercero::create([
            'nombre' => 'Tercero 1',
            'tipo' => 'Proveedor',
            'forma_pago' => '30 días',
            'estado' => 1,
        ]);

        $tercero2 = Tercero::create([
            'nombre' => 'Tercero 2',
            'tipo' => 'Proveedor',
            'forma_pago' => '60 días',
            'estado' => 1,
        ]);

        // Assert
        $this->assertEquals('30 días', $tercero1->forma_pago);
        $this->assertEquals('60 días', $tercero2->forma_pago);
    }

    /** @test */
    public function tercero_puede_tener_diferentes_tipos_de_documento()
    {
        // Arrange
        $tercero1 = Tercero::create([
            'nombre' => 'Tercero 1',
            'tipo_documento' => 'NIT',
            'numero_documento' => '800123456-7',
            'tipo' => 'Proveedor',
            'estado' => 1,
        ]);

        $tercero2 = Tercero::create([
            'nombre' => 'Tercero 2',
            'tipo_documento' => 'CC',
            'numero_documento' => '12345678',
            'tipo' => 'Cliente',
            'estado' => 1,
        ]);

        // Assert
        $this->assertEquals('NIT', $tercero1->tipo_documento);
        $this->assertEquals('CC', $tercero2->tipo_documento);
    }

    /** @test */
    public function tercero_puede_tener_digito_verificacion()
    {
        // Arrange
        $tercero = Tercero::create([
            'nombre' => 'Tercero Test',
            'tipo_documento' => 'NIT',
            'numero_documento' => '800123456',
            'dv' => '7',
            'tipo' => 'Proveedor',
            'estado' => 1,
        ]);

        // Assert
        $this->assertEquals('7', $tercero->dv);
    }

    /** @test */
    public function tercero_puede_tener_certificacion_bancaria()
    {
        // Arrange
        $tercero = Tercero::create([
            'nombre' => 'Tercero Test',
            'tipo' => 'Proveedor',
            'estado' => 1,
            'certificacion_bancaria' => 'Certificado válido hasta 2025',
        ]);

        // Assert
        $this->assertEquals('Certificado válido hasta 2025', $tercero->certificacion_bancaria);
    }

    /** @test */
    public function tercero_puede_tener_camara_comercio()
    {
        // Arrange
        $tercero = Tercero::create([
            'nombre' => 'Tercero Test',
            'tipo' => 'Proveedor',
            'estado' => 1,
            'camara_comercio' => 'Registro válido',
        ]);

        // Assert
        $this->assertEquals('Registro válido', $tercero->camara_comercio);
    }

    /** @test */
    public function tercero_puede_tener_cedula_representante()
    {
        // Arrange
        $tercero = Tercero::create([
            'nombre' => 'Tercero Test',
            'tipo' => 'Proveedor',
            'estado' => 1,
            'cedula_representante_legal' => '12345678',
        ]);

        // Assert
        $this->assertEquals('12345678', $tercero->cedula_representante_legal);
    }

    /** @test */
    public function tercero_puede_tener_sitio_web()
    {
        // Arrange
        $tercero = Tercero::create([
            'nombre' => 'Tercero Test',
            'tipo' => 'Proveedor',
            'estado' => 1,
            'sitio_web' => 'https://www.tercero.com',
        ]);

        // Assert
        $this->assertEquals('https://www.tercero.com', $tercero->sitio_web);
    }

    /** @test */
    public function tercero_puede_tener_puntos()
    {
        // Arrange
        $tercero = Tercero::create([
            'nombre' => 'Tercero Test',
            'tipo' => 'Cliente',
            'estado' => 1,
            'puntos' => 150,
        ]);

        // Assert
        $this->assertEquals(150, $tercero->puntos);
    }

    /** @test */
    public function tercero_puede_ser_identificado_por_id()
    {
        // Arrange
        $tercero = Tercero::create([
            'nombre' => 'Tercero Test',
            'tipo' => 'Proveedor',
            'estado' => 1,
        ]);

        // Act
        $terceroEncontrado = Tercero::find($tercero->id);

        // Assert
        $this->assertNotNull($terceroEncontrado);
        $this->assertEquals($tercero->id, $terceroEncontrado->id);
        $this->assertEquals('Tercero Test', $terceroEncontrado->nombre);
    }
}
