<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdenTrabajosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orden_trabajos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tercero_id')->constrained()->onDelete('cascade');
            $table->foreignId('pedido_id')->constrained()->onDelete('cascade');
            $table->foreignId('cotizacion_id')->nullable()->constrained('cotizaciones')->onDelete('set null');
            $table->string('estado');
            $table->date('fecha_ingreso');
            $table->date('fecha_entrega')->nullable(); // fecha_despacho
            $table->text('observaciones')->nullable();
            $table->string('direccion');
            $table->string('telefono');
            $table->string('guia')->nullable();
            $table->foreignId('transportadora_id')->nullable()->constrained()->onDelete('set null');
            $table->string('archivo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orden_trabajos');
    }
}
