<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pedido_referencia', function (Blueprint $table) {
            // Elimina la restricción de clave foránea
            $table->dropForeign('pedido_referencia_referencia_id_foreign');

            // Modifica la columna para que permita valores NULL
            $table->unsignedBigInteger('referencia_id')->nullable()->change();

            // Vuelve a agregar la clave foránea
            $table->foreign('referencia_id')
                ->references('id')
                ->on('referencias')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pedido_referencia', function (Blueprint $table) {
            // Elimina la restricción de clave foránea
            $table->dropForeign(['referencia_id']);

            // Revertir la columna a NOT NULL
            $table->unsignedBigInteger('referencia_id')->nullable(false)->change();

            // Vuelve a agregar la clave foránea
            $table->foreign('referencia_id')
                ->references('id')
                ->on('referencias')
                ->onDelete('cascade');
        });
    }
};
