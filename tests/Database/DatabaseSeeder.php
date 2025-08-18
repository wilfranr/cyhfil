<?php

namespace Tests\Database;

use Illuminate\Database\Seeder;
use App\Models\Empresa;
use App\Models\User;
use App\Models\Tercero;
use App\Models\Referencia;
use App\Models\Pedido;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database for testing.
     */
    public function run(): void
    {
        // Crear empresa de testing
        $empresa = Empresa::create([
            'nombre' => 'Empresa de Testing CYH',
            'direccion' => 'Calle de Testing #123',
            'telefono' => '1234567890',
            'celular' => '3001234567',
            'email' => 'testing@cyh.com',
            'nit' => '900123456-7',
            'representante' => 'Representante de Testing',
            'estado' => 1,
            'siglas' => 'CYH-TEST',
            'trm' => 4000, // 1 USD = 4000 COP
            'flete' => 5000, // 5000 COP por kg
        ]);

        // Crear usuario administrador de testing
        $admin = User::create([
            'name' => 'Admin Testing',
            'email' => 'admin@testing.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Crear usuario vendedor de testing
        $vendedor = User::create([
            'name' => 'Vendedor Testing',
            'email' => 'vendedor@testing.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Crear proveedores de testing
        $proveedorNacional = Tercero::create([
            'nombre' => 'Proveedor Nacional Testing',
            'tipo_documento' => 'NIT',
            'numero_documento' => '800123456-7',
            'direccion' => 'Calle Proveedor #456',
            'telefono' => '9876543210',
            'email' => 'nacional@testing.com',
            'estado' => 1,
            'tipo' => 'Proveedor',
            'forma_pago' => '30 días',
        ]);

        $proveedorInternacional = Tercero::create([
            'nombre' => 'Proveedor Internacional Testing',
            'tipo_documento' => 'NIT',
            'numero_documento' => '700123456-7',
            'direccion' => 'Calle Internacional #789',
            'telefono' => '8765432109',
            'email' => 'internacional@testing.com',
            'estado' => 1,
            'tipo' => 'Proveedor',
            'forma_pago' => '60 días',
        ]);

        // Crear clientes de testing
        $cliente1 = Tercero::create([
            'nombre' => 'Cliente Empresarial Testing',
            'tipo_documento' => 'NIT',
            'numero_documento' => '900123456-7',
            'direccion' => 'Calle Cliente #101',
            'telefono' => '7654321098',
            'email' => 'cliente1@testing.com',
            'estado' => 1,
            'tipo' => 'Cliente',
            'forma_pago' => '15 días',
        ]);

        $cliente2 = Tercero::create([
            'nombre' => 'Cliente Individual Testing',
            'tipo_documento' => 'CC',
            'numero_documento' => '12345678',
            'direccion' => 'Calle Individual #202',
            'telefono' => '6543210987',
            'email' => 'cliente2@testing.com',
            'estado' => 1,
            'tipo' => 'Cliente',
            'forma_pago' => 'Contado',
        ]);

        // Crear referencias de testing
        $referencia1 = Referencia::create([
            'referencia' => 'REF-TEST-001',
            'marca_id' => 1,
            'comentario' => 'Referencia de testing para repuestos',
        ]);

        $referencia2 = Referencia::create([
            'referencia' => 'REF-TEST-002',
            'marca_id' => 1,
            'comentario' => 'Segunda referencia de testing',
        ]);

        $referencia3 = Referencia::create([
            'referencia' => 'REF-TEST-003',
            'marca_id' => 1,
            'comentario' => 'Tercera referencia de testing',
        ]);

        // Crear pedidos de testing
        $pedido1 = Pedido::create([
            'user_id' => $vendedor->id,
            'tercero_id' => $cliente1->id,
            'direccion' => 'Calle Entrega #303',
            'comentario' => 'Pedido de testing para cliente empresarial',
            'estado' => 'Borrador',
        ]);

        $pedido2 = Pedido::create([
            'user_id' => $vendedor->id,
            'tercero_id' => $cliente2->id,
            'direccion' => 'Calle Entrega #404',
            'comentario' => 'Pedido de testing para cliente individual',
            'estado' => 'En Proceso',
        ]);

        $pedido3 = Pedido::create([
            'user_id' => $admin->id,
            'tercero_id' => $cliente1->id,
            'direccion' => 'Calle Entrega #505',
            'comentario' => 'Pedido de testing completado',
            'estado' => 'Completado',
        ]);

        $pedido4 = Pedido::create([
            'user_id' => $vendedor->id,
            'tercero_id' => $cliente2->id,
            'direccion' => 'Calle Entrega #606',
            'comentario' => 'Pedido de testing rechazado',
            'estado' => 'Rechazado',
            'motivo_rechazo' => 'Precio fuera del presupuesto del cliente',
        ]);

        $this->command->info('✅ Base de datos de testing poblada exitosamente');
        $this->command->info('📊 Datos creados:');
        $this->command->info('   - 1 Empresa');
        $this->command->info('   - 2 Usuarios');
        $this->command->info('   - 4 Terceros (2 proveedores, 2 clientes)');
        $this->command->info('   - 3 Referencias');
        $this->command->info('   - 4 Pedidos en diferentes estados');
    }
}
