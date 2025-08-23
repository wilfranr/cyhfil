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
        Schema::create('maquina_marca', function (Blueprint $table) {
            $table->id();
            // $table->unsignedBigInteger('maquina_id');
            // $table->unsignedBigInteger('marca_id');
            $table->timestamps();

            $table->foreignId('maquina_id')->references('id')->on('maquinas')->onDelete('cascade');
            $table->foreignId('marca_id')->references('id')->on('marcas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maquina_marca');
    }
};
