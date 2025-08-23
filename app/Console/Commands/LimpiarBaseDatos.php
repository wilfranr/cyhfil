<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class LimpiarBaseDatos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:limpiar {--confirm : Confirmar la operación sin preguntar}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Limpia la base de datos eliminando todos los datos excepto las tablas esenciales';

    /**
     * Lista de tablas que se mantendrán (solo estructura, sin datos)
     */
    protected $tablasAMantener = [
        'listas',
        'users', 
        'permissions',
        'roles',
        'role_has_permissions',
        'trms',
        'empresas',
        'fabricantes',
        'sistemas',
        'model_has_permissions',
        'model_has_roles',
        'states',
        'countries',
        'cities'
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!$this->option('confirm')) {
            if (!$this->confirm('¿Estás seguro de que quieres limpiar TODA la base de datos? Esta acción NO se puede deshacer.')) {
                $this->info('Operación cancelada.');
                return 0;
            }

            if (!$this->confirm('¿Confirmas que quieres proceder? Escribe "SI" para confirmar.')) {
                $this->info('Operación cancelada.');
                return 0;
            }
        }

        $this->info('Iniciando limpieza de la base de datos...');
        $this->newLine();

        try {
            // Desactivar verificación de claves foráneas
            DB::statement('SET FOREIGN_KEY_CHECKS = 0');

            // Obtener todas las tablas de la base de datos
            $tablas = DB::select('SHOW TABLES');
            $tablasLimpieza = [];

            foreach ($tablas as $tabla) {
                $nombreTabla = array_values((array) $tabla)[0];
                
                // Saltar las tablas que queremos mantener
                if (in_array($nombreTabla, $this->tablasAMantener)) {
                    $this->line("⏭️  Manteniendo tabla: <info>{$nombreTabla}</info>");
                    continue;
                }

                // Saltar tablas del sistema de Laravel
                if (in_array($nombreTabla, ['migrations', 'password_reset_tokens', 'failed_jobs', 'personal_access_tokens'])) {
                    $this->line("⏭️  Manteniendo tabla del sistema: <info>{$nombreTabla}</info>");
                    continue;
                }

                $tablasLimpieza[] = $nombreTabla;
            }

            if (empty($tablasLimpieza)) {
                $this->info('No hay tablas para limpiar.');
                return 0;
            }

            $this->warn('Las siguientes tablas serán limpiadas:');
            foreach ($tablasLimpieza as $tabla) {
                $this->line("  • {$tabla}");
            }
            $this->newLine();

            if (!$this->option('confirm')) {
                if (!$this->confirm('¿Proceder con la limpieza?')) {
                    $this->info('Operación cancelada.');
                    return 0;
                }
            }

            $bar = $this->output->createProgressBar(count($tablasLimpieza));
            $bar->start();

            foreach ($tablasLimpieza as $tabla) {
                try {
                    // Verificar si la tabla existe y tiene datos
                    if (Schema::hasTable($tabla)) {
                        $count = DB::table($tabla)->count();
                        if ($count > 0) {
                            DB::table($tabla)->truncate();
                            $this->line("\n🗑️  Limpiada tabla <info>{$tabla}</info> ({$count} registros eliminados)");
                        } else {
                            $this->line("\n✅ Tabla <info>{$tabla}</info> ya estaba vacía");
                        }
                    }
                } catch (\Exception $e) {
                    $this->error("\n❌ Error limpiando tabla {$tabla}: " . $e->getMessage());
                }
                
                $bar->advance();
            }

            $bar->finish();
            $this->newLine(2);

            // Reactivar verificación de claves foráneas
            DB::statement('SET FOREIGN_KEY_CHECKS = 1');

            $this->info('✅ Base de datos limpiada exitosamente!');
            $this->info('Se mantuvieron las siguientes tablas con su estructura:');
            
            foreach ($this->tablasAMantener as $tabla) {
                $this->line("  • {$tabla}");
            }

            $this->newLine();
            $this->warn('Recuerda que todos los datos han sido eliminados permanentemente.');
            $this->warn('Si necesitas datos de prueba, ejecuta: php artisan db:seed');

        } catch (\Exception $e) {
            $this->error('❌ Error durante la limpieza: ' . $e->getMessage());
            
            // Reactivar verificación de claves foráneas en caso de error
            DB::statement('SET FOREIGN_KEY_CHECKS = 1');
            
            return 1;
        }

        return 0;
    }
}
