<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ChangeEstadoToEnumInOrdenTrabajosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Usar DB::statement() para modificar el tipo de la columna a ENUM
        DB::statement("ALTER TABLE `orden_trabajos` MODIFY `estado` ENUM('Pendiente', 'En Proceso', 'Completado', 'Cancelado') DEFAULT 'Pendiente'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Revertir la columna a VARCHAR en caso de rollback
        DB::statement("ALTER TABLE `orden_trabajos` MODIFY `estado` VARCHAR(255) DEFAULT 'Pendiente'");
    }
}
