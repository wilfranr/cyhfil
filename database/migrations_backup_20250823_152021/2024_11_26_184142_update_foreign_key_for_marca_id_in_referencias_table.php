<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateForeignKeyForMarcaIdInReferenciasTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('referencias', function (Blueprint $table) {
            // Eliminar la clave foránea existente
            $table->dropForeign(['marca_id']);

            $table->unsignedBigInteger('marca_id')->nullable()->change();

            // Agregar la nueva clave foránea que apunta a la tabla `listas`
            $table->foreign('marca_id')
                ->references('id')
                ->on('listas')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('referencias', function (Blueprint $table) {
            // Eliminar la nueva clave foránea
            $table->dropForeign(['marca_id']);

            // Restaurar la clave foránea original que apunta a `fabricantes`
            $table->foreign('marca_id')
                ->references('id')
                ->on('fabricantes')
                ->onDelete('cascade');
        });
    }
}
