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
        Schema::create('referencias', function (Blueprint $table) {
            $table->id();
            $table->string('referencia');

            $table->foreignId('articulo_id')->constrained('articulos')->onDelete('cascade');
            //relacion con marcas
            $table->foreignId('marca_id')->constrained('marcas')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referencias');
    }
};
