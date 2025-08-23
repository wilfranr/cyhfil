<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticulosReferenciasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articulos_referencias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('articulo_id')->nullable();
            $table->unsignedBigInteger('referencia_id')->nullable();
            $table->timestamps();

            // Llaves foráneas
            $table->foreign('articulo_id')
                  ->references('id')
                  ->on('articulos')
                  ->onDelete('cascade');

            $table->foreign('referencia_id')
                  ->references('id')
                  ->on('referencias')
                  ->onDelete('cascade');

            // Índice único para evitar duplicados en la relación
            $table->unique(['articulo_id', 'referencia_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articulos_referencias');
    }
}
