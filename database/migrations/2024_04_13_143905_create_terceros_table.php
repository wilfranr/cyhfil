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
        Schema::create('terceros', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('tipo_documento');
            $table->string('numero_documento');
            $table->string('direccion');
            $table->string('telefono');
            $table->string('email');
            $table->string('dv')->nullable();
            $table->string('estado')->default('activo');
            $table->string('forma_pago')->nullable();
            $table->string('email_factura_electronica')->nullable();
            $table->string('rut')->nullable();
            $table->string('certificacion_bancaria')->nullable();
            $table->string('camara_comercio')->nullable();
            $table->string('cedula_representante_legal')->nullable();
            $table->string('sitio_web')->nullable();
            $table->integer('puntos')->nullable();
            $table->enum('tipo', ['cliente', 'proveedor'])->default('cliente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('terceros');
    }
};
