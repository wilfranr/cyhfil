<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateForeignKeyOnPedidoReferenciaProveedorTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('pedido_referencia_proveedor', function (Blueprint $table) {
            // Eliminar la clave foránea actual
            $table->dropForeign(['marca_id']);

            // Agregar la nueva clave foránea (ajustar la tabla y columna de referencia según sea necesario)
            $table->foreign('marca_id')
                ->references('id')
                ->on('listas') // Cambiar a la tabla correcta (por ejemplo, 'marcas' en lugar de 'fabricantes')
                ->onDelete('cascade')
                ->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('pedido_referencia_proveedor', function (Blueprint $table) {
            // Eliminar la clave foránea agregada en `up`
            $table->dropForeign(['marca_id']);

            // Restaurar la clave foránea original
            $table->foreign('marca_id')
                ->references('id')
                ->on('fabricantes') // Restaurar a la tabla original
                ->onDelete('cascade')
                ->onUpdate('restrict');
        });
    }
}
