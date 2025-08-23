<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('tercero_id');
            $table->string('comentario', 255)->nullable();
            $table->unsignedBigInteger('contacto_id')->nullable();
            $table->string('estado', 11)->default('Nuevo');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('tercero_id')->references('id')->on('terceros');
            // Agrega aquí tus otras claves foráneas si es necesario

            // Índices adicionales si los necesitas
            // $table->index('user_id');
            // $table->index('tercero_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pedidos');
    }
}
