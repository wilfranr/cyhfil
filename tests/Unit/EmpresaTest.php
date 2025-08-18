<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Empresa;
use App\Models\Country;
use App\Models\State;
use App\Models\City;

class EmpresaTest extends TestCase
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
    public function puede_crear_empresa_con_datos_completos()
    {
        // Arrange
        $empresaData = [
            'nombre' => 'Empresa Test CYH',
            'direccion' => 'Calle Test #123',
            'telefono' => '1234567890',
            'celular' => '3001234567',
            'email' => 'test@cyh.com',
            'logo_light' => 'logo-light.png',
            'logo_dark' => 'logo-dark.png',
            'nit' => '900123456-7',
            'representante' => 'Representante Test',
            'country_id' => $this->country->id,
            'state_id' => $this->state->id,
            'city_id' => $this->city->id,
            'estado' => 1,
            'siglas' => 'CYH-TEST',
            'flete' => 5000,
            'trm' => 4000,
        ];

        // Act
        $empresa = Empresa::create($empresaData);

        // Assert
        $this->assertInstanceOf(Empresa::class, $empresa);
        $this->assertEquals('Empresa Test CYH', $empresa->nombre);
        $this->assertEquals('900123456-7', $empresa->nit);
        $this->assertEquals(1, $empresa->estado);
        $this->assertEquals(5000, $empresa->flete);
        $this->assertEquals(4000, $empresa->trm);
    }

    /** @test */
    public function empresa_tiene_relaciones_correctas()
    {
        // Arrange
        $empresa = Empresa::create([
            'nombre' => 'Empresa Test',
            'country_id' => $this->country->id,
            'state_id' => $this->state->id,
            'city_id' => $this->city->id,
            'estado' => 1,
        ]);

        // Act & Assert
        $this->assertInstanceOf(Country::class, $empresa->country);
        $this->assertInstanceOf(State::class, $empresa->state);
        $this->assertInstanceOf(City::class, $empresa->city);
        
        $this->assertEquals('Colombia', $empresa->country->name);
        $this->assertEquals('Antioquia', $empresa->state->name);
        $this->assertEquals('Medellín', $empresa->city->name);
    }

    /** @test */
    public function solo_puede_haber_una_empresa_activa()
    {
        // Arrange - Crear primera empresa activa
        $empresa1 = Empresa::create([
            'nombre' => 'Empresa 1',
            'estado' => 1,
        ]);

        // Act - Crear segunda empresa activa
        $empresa2 = Empresa::create([
            'nombre' => 'Empresa 2',
            'estado' => 1,
        ]);

        // Assert - Solo la última debe estar activa
        $this->assertEquals(0, $empresa1->fresh()->estado);
        $this->assertEquals(1, $empresa2->fresh()->estado);
        
        // Verificar que solo hay una empresa activa en total
        $empresasActivas = Empresa::where('estado', 1)->count();
        $this->assertEquals(1, $empresasActivas);
    }

    /** @test */
    public function empresa_puede_cambiar_estado()
    {
        // Arrange
        $empresa = Empresa::create([
            'nombre' => 'Empresa Test',
            'estado' => 0,
        ]);

        // Act
        $empresa->update(['estado' => 1]);

        // Assert
        $this->assertEquals(1, $empresa->fresh()->estado);
    }

    /** @test */
    public function empresa_puede_actualizar_configuracion_global()
    {
        // Arrange
        $empresa = Empresa::create([
            'nombre' => 'Empresa Test',
            'estado' => 1,
            'trm' => 4000,
            'flete' => 5000,
        ]);

        // Act
        $empresa->update([
            'trm' => 4200,
            'flete' => 5500,
        ]);

        // Assert
        $this->assertEquals(4200, $empresa->fresh()->trm);
        $this->assertEquals(5500, $empresa->fresh()->flete);
    }

    /** @test */
    public function empresa_puede_ser_eliminada()
    {
        // Arrange
        $empresa = Empresa::create([
            'nombre' => 'Empresa Test',
            'estado' => 1,
        ]);

        $empresaId = $empresa->id;

        // Act
        $empresa->delete();

        // Assert
        $this->assertDatabaseMissing('empresas', ['id' => $empresaId]);
        $this->assertNull(Empresa::find($empresaId));
    }

    /** @test */
    public function empresa_mantiene_timestamps_correctos()
    {
        // Arrange
        $empresa = Empresa::create([
            'nombre' => 'Empresa Test',
            'estado' => 1,
        ]);

        // Assert
        $this->assertNotNull($empresa->created_at);
        $this->assertNotNull($empresa->updated_at);
        $this->assertInstanceOf(\Carbon\Carbon::class, $empresa->created_at);
        $this->assertInstanceOf(\Carbon\Carbon::class, $empresa->updated_at);
    }

    /** @test */
    public function empresa_puede_tener_campos_opcionales_vacios()
    {
        // Arrange
        $empresa = Empresa::create([
            'nombre' => 'Empresa Test',
            'estado' => 1,
            // Sin logo, representante, etc.
        ]);

        // Assert
        $this->assertInstanceOf(Empresa::class, $empresa);
        $this->assertNull($empresa->logo_light);
        $this->assertNull($empresa->logo_dark);
        $this->assertNull($empresa->representante);
        $this->assertNull($empresa->nit);
    }

    /** @test */
    public function empresa_puede_ser_filtrada_por_estado()
    {
        // Arrange
        Empresa::create(['nombre' => 'Empresa 1', 'estado' => 1]);
        Empresa::create(['nombre' => 'Empresa 2', 'estado' => 0]);
        Empresa::create(['nombre' => 'Empresa 3', 'estado' => 1]);

        // Act
        $empresasActivas = Empresa::where('estado', 1)->get();
        $empresasInactivas = Empresa::where('estado', 0)->get();

        // Assert
        $this->assertCount(2, $empresasActivas);
        $this->assertCount(1, $empresasInactivas);
    }

    /** @test */
    public function empresa_puede_ser_buscada_por_nombre()
    {
        // Arrange
        Empresa::create(['nombre' => 'Empresa ABC', 'estado' => 1]);
        Empresa::create(['nombre' => 'Empresa XYZ', 'estado' => 1]);

        // Act
        $empresaEncontrada = Empresa::where('nombre', 'like', '%ABC%')->first();

        // Assert
        $this->assertNotNull($empresaEncontrada);
        $this->assertEquals('Empresa ABC', $empresaEncontrada->nombre);
    }

    /** @test */
    public function empresa_puede_ser_ordenada_por_nombre()
    {
        // Arrange
        Empresa::create(['nombre' => 'Empresa Z', 'estado' => 1]);
        Empresa::create(['nombre' => 'Empresa A', 'estado' => 1]);
        Empresa::create(['nombre' => 'Empresa M', 'estado' => 1]);

        // Act
        $empresasOrdenadas = Empresa::orderBy('nombre', 'asc')->get();

        // Assert
        $this->assertEquals('Empresa A', $empresasOrdenadas[0]->nombre);
        $this->assertEquals('Empresa M', $empresasOrdenadas[1]->nombre);
        $this->assertEquals('Empresa Z', $empresasOrdenadas[2]->nombre);
    }

    /** @test */
    public function empresa_puede_tener_diferentes_tipos_de_documento()
    {
        // Arrange
        $empresa1 = Empresa::create([
            'nombre' => 'Empresa 1',
            'nit' => '900123456-7',
            'estado' => 1,
        ]);

        $empresa2 = Empresa::create([
            'nombre' => 'Empresa 2',
            'nit' => '800987654-3',
            'estado' => 1,
        ]);

        // Assert
        $this->assertEquals('900123456-7', $empresa1->nit);
        $this->assertEquals('800987654-3', $empresa2->nit);
    }

    /** @test */
    public function empresa_puede_tener_diferentes_formas_de_contacto()
    {
        // Arrange
        $empresa = Empresa::create([
            'nombre' => 'Empresa Test',
            'telefono' => '1234567890',
            'celular' => '3001234567',
            'email' => 'test@cyh.com',
            'estado' => 1,
        ]);

        // Assert
        $this->assertEquals('1234567890', $empresa->telefono);
        $this->assertEquals('3001234567', $empresa->celular);
        $this->assertEquals('test@cyh.com', $empresa->email);
    }

    /** @test */
    public function empresa_puede_tener_siglas_especificas()
    {
        // Arrange
        $empresa = Empresa::create([
            'nombre' => 'Empresa Test',
            'siglas' => 'CYH-TEST',
            'estado' => 1,
        ]);

        // Assert
        $this->assertEquals('CYH-TEST', $empresa->siglas);
    }

    /** @test */
    public function empresa_puede_ser_identificada_por_id()
    {
        // Arrange
        $empresa = Empresa::create([
            'nombre' => 'Empresa Test',
            'estado' => 1,
        ]);

        // Act
        $empresaEncontrada = Empresa::find($empresa->id);

        // Assert
        $this->assertNotNull($empresaEncontrada);
        $this->assertEquals($empresa->id, $empresaEncontrada->id);
        $this->assertEquals('Empresa Test', $empresaEncontrada->nombre);
    }
}
