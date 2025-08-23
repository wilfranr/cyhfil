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
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 300);
            $table->string('siglas', 10)->nullable();            
            $table->string('direccion');
            $table->string('telefono')->nullable();
            $table->string('celular');
            $table->string('email');
            $table->string('logo')->nullable();
            $table->string('nit');
            $table->string('representante');
            $table->unsignedBigInteger('country_id');
            $table->unsignedBigInteger('city_id');

            $table->foreign('country_id')->references('id')->on('countries');
            $table->foreign('city_id')->references('id')->on('cities');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};
