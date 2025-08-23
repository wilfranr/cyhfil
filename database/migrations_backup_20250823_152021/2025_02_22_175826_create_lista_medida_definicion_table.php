<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('lista_medida_definicion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('definicion_id')->constrained('listas')->onDelete('cascade'); // Definición de artículo
            $table->foreignId('medida_id')->constrained('listas')->onDelete('cascade');   // Nombre de medida
            $table->timestamps();

            // Restricción única para evitar relaciones duplicadas
            $table->unique(['definicion_id', 'medida_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lista_medida_definicion');
    }
};