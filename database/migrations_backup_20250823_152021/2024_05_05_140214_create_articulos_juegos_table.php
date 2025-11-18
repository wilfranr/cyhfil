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
        Schema::create('articulos_juegos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('articulo_id')->constrained()->onDelete('cascade');
            $table->foreignId('juego_id')->constrained()->onDelete('cascade');
            $table->integer('cantidad');
            $table->text('comentario')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articulos_juegos');
    }
};
